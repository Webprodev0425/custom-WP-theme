<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 11/7/19
 * Time: 11:24
 */

if ( ! class_exists( 'PPW_Pro_Shortcode' ) ) {
	/**
	 * PPWP Shortcode handler in Pro.
	 *
	 * Class PPW_Pro_Shortcode
	 */
	class PPW_Pro_Shortcode {

		/**
		 * Short code attributes.
		 *
		 * @var array
		 */
		private $attributes;

		/**
		 * Maintains and registers all hooks for the plugin.
		 *
		 * @var PPW_Pro_Loader $loader Maintains and registers all hooks for the plugin.
		 */
		private $loader;

		/**
		 * Password repository.
		 *
		 * @var PPW_Pro_Repository
		 */
		private $password_repo;

		/**
		 * Instance of PPW_Pro_Shortcode class.
		 *
		 * @var PPW_Pro_Shortcode
		 */
		protected static $instance = null;

		/**
		 * PPW_Pro_Shortcode constructor.
		 */
		public function __construct() {
			$this->attributes = array(
				'pwd'            => '', // Password Is, separate by comma. Eg: 5,6,7.
				'protected_file' => '', // Attachment Ids, separate by comma. Eg: 1,2,3.
				'cookie'         => '', // Expiry time for cookie.
				'download_limit' => '', // Download limit for expired url.
			);

			$this->password_repo = new PPW_Pro_Repository();
		}

		/**
		 * Apply filter from Free
		 *
		 * @param PPW_Pro_Loader $loader Maintains and registers all hooks for the plugin.
		 */
		public function apply_filters( $loader ) {
			$this->loader = $loader;
			$this->loader->add_filter( 'ppw_short_code_attributes', $this, 'add_more_attributes' );

			if (
				method_exists( 'PDA_Private_Link_Services', 'create_private_link' ) &&
				method_exists( 'Pda_v3_Gold_Helper', 'get_urls_of_a_tag' ) &&
				method_exists( 'Pda_v3_Gold_Helper', 'filter_internal_url' )
			) {
				$this->loader->add_filter( 'ppw_shortcode_render_content', $this, 'handle_the_content_after_render_shortcode', 10, 2 );
			}

			$this->loader->add_filter( 'ppw_shortcode_passwords', $this, 'handle_shortcode_passwords', 10, 2 );
			$this->loader->add_filter( 'ppw_supported_post_types', $this, 'ppwp_supported_post_types' );
			$this->loader->add_filter( 'ppw_content_shortcode_source', $this, 'get_content_shortcode_from_post_meta', 10, 3 );
			$this->loader->add_filter( 'ppw_shortcode_attributes_validation', $this, 'valid_attributes_shortcode', 10, 2 );
			$this->loader->add_filter( 'ppw_restrict_content_after_valid_pw', $this, 'handle_after_valid_password', 10, 2 );

			/**
			 * Handle global scope option hook.
			 *
			 * @since 1.2.2
			 */
			$this->loader->add_filter( 'ppw_shortcode_is_valid_password_with_cookie', $this, 'is_password_valid_with_cookie', 10, 3 );

		}

		/**
		 * Get short code instance
		 *
		 * @return PPW_Pro_Shortcode
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				// Use static instead of self due to the inheritance later.
				// For example: ChildSC extends this class, when we call get_instance
				// it will return the object of child class. On the other hand, self function
				// will return the object of base class.
				self::$instance = new static();
			}

			return self::$instance;
		}

		/**
		 * Not check validation for passwords because of using passwords in settings.
		 *
		 * @param array $rules Rules to check validation.
		 * @param array $attrs Attributes of shortcode.
		 *
		 * @return array
		 */
		public function valid_attributes_shortcode( $rules, $attrs ) {
			if ( isset( $attrs['pwd'] ) && ! empty( $attrs['pwd'] ) ) {
				return array_filter( $rules, function ( $value ) {
					return isset( $value['key'] ) && $value['key'] !== 'passwords';
				} );
			}

			return $rules;
		}

		/**
		 * Handle after enter password.
		 *
		 * @param object $post_obj Post meta include shortcode attributes.
		 * @param string $password Password.
		 */
		public function handle_after_valid_password( $post_obj, $password ) {
			if ( ! isset( $GLOBALS['ppw_pcp_passwords_setting'] ) || ! is_array( $GLOBALS['ppw_pcp_passwords_setting'] ) || count( $GLOBALS['ppw_pcp_passwords_setting'] ) === 0 ) {
				return;
			}

			$is_valid_password = in_array( $password, $GLOBALS['ppw_pcp_passwords_setting'], true );

			if ( ! $is_valid_password ) {
				return;
			}

			$this->password_repo->update_count_by_password( $password );

			$user_name = ppw_pro_get_current_user_name();
			$data      = array(
				'server_env' => $_SERVER,
				'is_valid'   => $is_valid_password,
				'password'   => $password,
				'username'   => $user_name,
				'post_type'  => PPW_Pro_Constants::PCP_TYPE,
				'post_id'    => isset( $post_obj->ID ) ? $post_obj->ID : 0,
			);

			apply_filters( PPW_Pro_Constants::HOOK_PCP_AFTER_CHECK_VALID_PASSWORD, $data );
		}

		/**
		 * Add more attributes
		 *
		 * @param array $attributes The array of attributes which needs to add.
		 *
		 * @return array
		 */
		public function add_more_attributes( $attributes ) {
			$this->attributes += $attributes;

			return $this->get_attributes();
		}

		/**
		 * Get attributes
		 *
		 * @return array
		 */
		public function get_attributes() {
			return $this->attributes;
		}

		/**
		 * Get all passwords by pwd ids from shortcode attributes.
		 *
		 * @param array $passwords_from_shortcode Passwords from shortcode attributes.
		 * @param array $atts                     Attributes from shortcode.
		 *
		 * @return array Passwords.
		 */
		public function handle_shortcode_passwords( $passwords_from_shortcode, $atts ) {
			if ( empty( $atts['pwd'] ) ) {
				return $passwords_from_shortcode;
			}

			$password_ids = ppw_pro_convert_string_to_positive_array( $atts['pwd'] );
			if ( 0 === count( $password_ids ) ) {
				return $passwords_from_shortcode;
			}

			// Detect user enter password form.
			$is_form_request = defined( 'REST_REQUEST' ) && REST_REQUEST;

			/**
			 * If user enter password from form then get passwords expired(day, count, activated).
			 * If user have already entered password (Cookie saved) then only get password activated.
			 */
			$current_user_roles = ppw_pro_get_current_user_roles();
			$passwords_object   = $this->password_repo->fetch_activate_pcp_passwords_by_ids( $password_ids, $current_user_roles, $is_form_request );

			if ( count( $passwords_object ) === 0 ) {
				return $passwords_from_shortcode;
			}

			$passwords = array_map(
				function ( $value ) {
					return $value->password;
				},
				$passwords_object
			);

			// Only allow update count when enter password.
			if ( $is_form_request ) {
				$GLOBALS['ppw_pcp_passwords_setting'] = $passwords;
			}

			return array_merge( $passwords_from_shortcode, $passwords );
		}

		/**
		 * Replace url with expired url if content have valid internal url.
		 *
		 * @param string $content Content from shortcode.
		 * @param array  $atts    Attributes from shortcode.
		 *
		 * @return string Content after replace url with expired url.
		 */
		public function handle_the_content_after_render_shortcode( $content, $atts ) {
			$pda_gold_helper = new Pda_v3_Gold_Helper();

			// Only search for the a tag. We will handle the images later after finding the better solution.
			$urls = $pda_gold_helper->get_urls_of_a_tag( $content );
			list( $urls, $content ) = $pda_gold_helper->filter_internal_url( $urls, $content );
			if ( empty( $urls ) ) {
				return $content;
			}

			$download_limit = 1;
			if ( ! empty( $atts['download_limit'] ) ) {
				$download_limit = intval( $atts['download_limit'] ) > 0 ? absint( $atts['download_limit'] ) : $download_limit;
			}
			$object_urls = $this->massage_urls_in_content( $urls, $download_limit );

			foreach ( $object_urls as $value ) {
				if ( $value['url'] !== $value['new_url'] ) {
					$content = str_replace( $value['url'], $value['new_url'], $content );
				}
			}

			return $content;
		}

		/**
		 * Handle the content for template attribute
		 *
		 * @param string $content The shortcode content.
		 * @param array  $atts    The shortcode attributes.
		 *
		 * @return string The short's code content to render.
		 */
		public function handle_the_content_for_template_attribute( $content, $atts ) {
			if ( ! isset( $atts['template'] ) ) {
				return $content;
			}

			$tmp = $this->is_valid_template_attr( $atts['template'] );
			if ( false === $tmp ) {
				return $content;
			}

			$template_type = $tmp[0];
			if ( PPW_Pro_Constants::ELEMENTOR === $template_type ) {
				$template_id                = $tmp[1];
				$elementor_template_content = $this->get_elementor_content( $template_id );
				return $this->wrap_content( $content, $elementor_template_content );
			} elseif ( false !== strpos( $template_type, PPW_Pro_Constants::BEAVER_BUILDER ) ) {
				$template_id = $tmp[1];
				$bb_content  = $this->get_bb_content( $template_id );
				if ( ! empty( $bb_content ) ) {
					return $this->wrap_content( $content, $bb_content );
				}
			}

			return $content;
		}

		/**
		 * Get Elementor content by template ID.
		 *
		 * @param int $template_id The Elementor template ID.
		 *
		 * @return string
		 */
		public function get_elementor_content( $template_id ) {
			$ele_frontend = new \Elementor\Frontend();

			return $ele_frontend->get_builder_content( $template_id, true );
		}

		/**
		 * Get Beaver Builder content
		 *
		 * @param string $id Template ID.
		 *
		 * @return string
		 */
		public function get_bb_content( $id ) {
			if ( ! is_callable( 'FLBuilderShortcodes::insert_layout' ) ) {
				return '';
			}

			return FLBuilderShortcodes::insert_layout(
				array(
					'id' => $id,
				)
			);
		}

		/**
		 * Add condition to define what's the empty content.
		 *
		 * @param bool   $is_empty Is empty content.
		 * @param string $content  The shortcode content.
		 * @param array  $attrs    The shortcode attributes.
		 *
		 * @return bool
		 */
		public function is_empty_shortcode( $is_empty, $content, $attrs ) {
			if ( ! isset( $attrs['template'] ) ) {
				return $is_empty;
			}
			if ( false === $this->is_valid_template_attr( $attrs['template'] ) ) {
				return $is_empty;
			}

			return false;
		}

		/**
		 * Check whether the template attribute is valid format.
		 *
		 * @param string $value Template value.
		 *
		 * @return bool|array False if the template attr is invalid.
		 */
		private function is_valid_template_attr( $value ) {
			$tmp = explode( '_', $value );

			if ( count( $tmp ) < 2 ) {
				return false;
			}

			$template_type = $tmp[0];
			if ( PPW_Pro_Constants::ELEMENTOR === $template_type ) {
				if ( ! class_exists( '\Elementor\Frontend' ) ) {
					return false;
				}
			} elseif ( false !== strpos( $template_type, PPW_Pro_Constants::BEAVER_BUILDER ) ) {
				if ( ! class_exists( 'FLBuilderShortcodes' ) ) {
					return false;
				}
			}

			return $tmp;
		}

		/**
		 * The content passed from shortcode will wrap by parent <div class="ppw-restricted-content"></div>
		 * Class ppw-restricted-content used to add the index to solve the multiple shortcode in a post.
		 * We need to insert the elementor template between the parent <div>.
		 *
		 * @param string $content       Parent content.
		 * @param string $child_content Child content need to be wrapped.
		 *
		 * @return mixed
		 */
		private function wrap_content( $content, $child_content ) {
			$div_ele = explode( '</div>', $content );
			if ( count( $div_ele ) < 2 ) {
				return $content;
			}

			return $div_ele[0] . $child_content . '</div>';
		}

		/**
		 * Get urls from content.
		 *
		 * @param string $content Content from shortcode.
		 *
		 * @return array
		 */
		private function get_urls_from_content( $content ) {
			preg_match_all( '#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $content, $matches );

			return $matches[0];
		}

		/**
		 * Get array with element url generated by expired url.
		 *
		 * @param array $urls           URLs.
		 * @param int   $download_limit Download limit for expired url.
		 *
		 * @return array
		 */
		private function massage_urls_in_content( $urls, $download_limit ) {
			$password_service = new PPW_Pro_Password_Services();

			$private_link_info = array(
				'type'            => PPW_Pro_Constants::SHORTCODE_LINK_EXPIRED,
				'limit_downloads' => $download_limit,
			);

			return array_map(
				function ( $url ) use ( $password_service, $private_link_info ) {
					list( $attachment_id, $size ) = $password_service->get_size_and_attachment_id_by_attachment_url( esc_url( $url ) );

					if ( ! $attachment_id ) {
						return array(
							'url'     => $url,
							'new_url' => $url,
						);
					}
					$new_url = PDA_Private_Link_Services::create_private_link( $attachment_id, $private_link_info );

					return array(
						'url'     => $url,
						'new_url' => ! empty( $new_url ) ? $password_service->append_file_size_to_url( $new_url, $size ) : $url,
					);
				},
				$urls
			);
		}

		/**
		 * Supported post types.
		 *
		 * @param array $post_types Post types.
		 *
		 * @return array
		 */
		public function ppwp_supported_post_types( $post_types ) {
			$all_post_types = ppw_pro_get_all_post_types( 'names' );
			if ( count( $all_post_types ) > 0 ) {
				return array_keys( $all_post_types );
			}

			return $post_types;
		}

		/**
		 * Get content shortcode in post meta
		 *
		 * @param string $content The post content.
		 * @param object $post    The post object.
		 * @param string $data    Post data include.
		 *                        + Form type: Default form or custom field form.
		 *                        + The meta key.
		 *
		 * @return string|bool
		 */
		public function get_content_shortcode_from_post_meta( $content, $post, $data ) {
			if ( 'cf' !== $data['formType'] ) {
				return $content;
			}

			if ( '' === $data['metaKey'] ) {
				return false;
			}

			if ( ! isset( $post->ID ) ) {
				return false;
			}

			$contents = get_post_meta( $post->ID, $data['metaKey'] );

			return implode( ' ', $contents );
		}

		/**
		 * Massage meta value
		 *
		 * @param string $meta_value The meta value.
		 *
		 * @return mixed
		 */
		public function massage_meta_value( $meta_value ) {
			preg_match_all( '/' . get_shortcode_regex() . '/', $meta_value, $matches, PREG_SET_ORDER );
			$matches = $this->filter_short_code_matches( $matches, PPW_Constants::PPW_HOOK_SHORT_CODE_NAME );
			foreach ( $matches as $match ) {
				$attrs             = shortcode_parse_atts( $match[3] );
				$attrs['type']     = PPW_Pro_Constants::CF_SHORTCODE_FORM_TYPE;
				$attrs['revision'] = time();
				$result            = implode(
					' ',
					array_map(
						function ( $key, $value ) {
							return "$key=\"$value\"";
						},
						array_keys( $attrs ),
						$attrs
					)
				);

				$new_shortcode = str_replace( ltrim( $match[3] ), $result, $match[0] );
				$meta_value    = str_replace( $match[0], $new_shortcode, $meta_value );
			}

			return $meta_value;
		}

		/**
		 * Filter ppwp Shortcode
		 *
		 * @param array  $result         The current result.
		 * @param string $shortcode_name The shortcode name.
		 *
		 * @return array
		 */
		public function filter_short_code_matches( $result, $shortcode_name ) {
			return array_values(
				array_filter(
					$result,
					function ( $match ) use ( $shortcode_name ) {
						return isset( $match[2] ) && $shortcode_name === $match[2];
					}
				)
			);
		}

		/**
		 * Check whether the password is valid with current cookie.
		 * Need this function to check when the global scope option enabled.
		 *
		 * @param bool   $is_valid Is valid cookie.
		 * @param string $password Password user entered.
		 * @param array  $cookies  Cookie from browser.
		 *
		 * @return bool
		 */
		public function is_password_valid_with_cookie( $is_valid, $password, $cookies ) {
			if ( $is_valid || ! $this->is_global_scope_enabled() || ! is_array( $cookies ) ) {
				return $is_valid;
			}

			global $wp_hasher;
			foreach ( $cookies as $key => $value ) {
				if ( preg_match( '/^ppw_rc-\d*/', $key ) ) {
					// Here do not need to sanitize $_COOKIE data, because we use it for comparision.
					$cookie_val = json_decode( wp_unslash( $value ) ); // phpcs:ignore
					if ( ! is_array( $cookie_val ) ) {
						continue;
					}
					$passwords = array_values( array_column( $cookie_val, 'passwords' ) );
					foreach ( $passwords as $cookie_passwords ) {
						foreach ( $cookie_passwords as $cookie_password ) {
							if ( $wp_hasher->CheckPassword( $cookie_password, $password ) ) {
								return true;
							}
						}
					}
				}
			}

			return $is_valid;

		}

		/**
		 * Checking whether the global scope is enabled.
		 *
		 * @return bool
		 */
		private function is_global_scope_enabled() {
			return ppw_pro_get_pcp_settings_boolean( PPW_Pro_Constants::WPP_UNLOCK_ALL_PROTECTED_SECTIONS );
		}

	}
}
