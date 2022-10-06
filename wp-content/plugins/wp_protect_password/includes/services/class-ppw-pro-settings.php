<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 7/23/19
 * Time: 11:23
 */

if ( ! class_exists( 'PPW_Pro_Settings_Services' ) ) {

	class PPW_Pro_Settings_Services {

		/**
		 * @var PPW_Pro_Repository
		 */
		private $repository;

		/**
		 * Class PPW_Pro_Password_Services
		 *
		 * @var PPW_Pro_Password_Services
		 */
		private $password_services;

		/**
		 * Class PPW_Pro_License_Services
		 *
		 * @var PPW_Pro_License_Services
		 */
		private $license_service;

		/**
		 * PPW_Pro_Password_Services constructor.
		 */
		public function __construct() {
			$this->repository        = new PPW_Pro_Repository();
			$this->password_services = new PPW_Pro_Password_Services();
			$this->license_service   = new PPW_Pro_License_Services();
		}

		/**
		 * Set default tab
		 *
		 * @return string
		 */
		public function set_default_tab() {
			return 'license';
		}

		/**
		 * Add license tab
		 *
		 * @param $tabs
		 *
		 * @return array
		 */
		public function add_license_tab( $tabs ) {
			$license_tab = [
				'tab'      => 'license',
				'tab_name' => 'License',
			];
			$tabs[]      = $license_tab;

			return $tabs;
		}

		/**
		 * Add shortcode passwords tab
		 *
		 * @param $tabs
		 *
		 * @return array
		 */
		public function add_pcp_passwords_tab( $tabs ) {
			$pcp_passwords_tab = [
				'tab'      => PPW_Pro_Constants::PCP_MODULE,
				'tab_name' => 'PCP Passwords',
			];

			// Only show PCP Passwords when user use activate license.
			if ( ! $this->license_service->is_valid_license() ) {
				return $tabs;
			}

			if ( count( $tabs ) > 3 ) {
				array_splice( $tabs, 3, 0, array( $pcp_passwords_tab ) );
			} else {
				$tabs[] = $pcp_passwords_tab;
			}

			return $tabs;
		}

		/**
		 * Declare custom tab hooks including license tab
		 *
		 * @param $tabs
		 *
		 * @return array
		 */
		public function declare_custom_tab_hooks( $tabs ) {
			$license = 'license';
			$tabs[]  = $license;
			$tabs[]  = PPW_Pro_Constants::PCP_MODULE;

			return $tabs;
		}


		/**
		 * Render license content
		 */
		public function render_license_content() {
			?>
			<div class="ppwp_setting_page">
				<?php
				require_once PPW_PRO_VIEW_PATH . 'license/view-ppw-pro-license.php';
				if ( $this->license_service->is_valid_license() ) {
					require_once PPW_PRO_VIEW_PATH . 'sidebar/view-ppw-pro-sidebar.php';
				}
				?>
			</div>
			<?php
		}

		/**
		 * Render license content
		 */
		public function render_pcp_passwords_content() {
			?>
			<div id="ppwp_pcp_passwords" class="ppw_main_container"></div>
			<?php
		}

		/**
		 * Render entire site content
		 */
		public function render_entire_site_content() {
			?>
			<div class="ppwp_setting_page">
				<?php
				require_once PPW_PRO_VIEW_PATH . 'entire-site/view-ppw-pro-entire-site.php';
				require_once PPW_PRO_VIEW_PATH . 'sidebar/view-ppw-pro-sidebar.php';
				?>
			</div>
			<?php
		}

		/**
		 * Render sidebar for shortcode tab
		 */
		public function render_sidebar_shortcode() {
			require_once PPW_PRO_VIEW_PATH . 'sidebar/view-ppw-pro-sidebar.php';
		}

		/**
		 * Render entire site content
		 */
		public function render_general_content() {
			?>
			<div class="ppwp_setting_page">
				<?php
				require_once PPW_PRO_VIEW_PATH . 'general/view-ppw-pro-general.php';
				require_once PPW_PRO_VIEW_PATH . 'sidebar/view-ppw-pro-sidebar.php';
				?>
			</div>
			<?php
		}

		/**
		 * Update entire site settings
		 *
		 * @param array $request The get request.
		 */
		public function update_entire_site_settings( $request ) {
			$data_settings = wp_unslash( $_REQUEST['settings'] );

			$setting_keys                              = array( PPW_Constants::IS_PROTECT_ENTIRE_SITE );
			$password_entire_site                      = $this->massage_pwd_entire_site( $data_settings );
			$data_settings['ppw_password_entire_site'] = $password_entire_site;

			if (
				ppw_pro_is_data_invalid( $request, PPW_Constants::ENTIRE_SITE_FORM_NONCE, PPW_Pro_Constants::DATA_SETTINGS, $setting_keys ) ||
				ppw_pro_data_entire_site_settings_invalid( $data_settings ) ) {
				send_json_data_error( PPW_Constants::BAD_REQUEST_MESSAGE );
			}

			if ( 'true' !== $data_settings[ PPW_Constants::IS_PROTECT_ENTIRE_SITE ] ) {
				$data_settings[ PPW_Pro_Constants::PPW_PASSWORD_FOR_ENTIRE_SITE ] = array();
				$data_settings[ PPW_Pro_Constants::IS_EXCLUDE_PAGE ]              = 'false';
				$data_settings[ PPW_Pro_Constants::PAGE_EXCLUDED ]                = array();
				$data_settings[ PPW_Pro_Constants::ENTIRE_SITE_REDIRECTION ]      = 'false';
			} elseif ( 'true' !== $data_settings[ PPW_Pro_Constants::IS_EXCLUDE_PAGE ] ) {
				$data_settings[ PPW_Pro_Constants::PAGE_EXCLUDED ] = array();
			}

			update_option( PPW_Pro_Constants::PPW_ENTIRE_SITE_OPTIONS, $data_settings, 'no' );
			wp_die( true );
		}

		/**
		 * Exclude page for feature protect entire site
		 *
		 * @return bool
		 */
		public function exclude_page_in_entire_site_feature() {
			$is_exclude = ppw_core_get_setting_entire_site_type_bool( PPW_Pro_Constants::IS_EXCLUDE_PAGE );
			if ( ! $is_exclude ) {
				return true;
			}

			$pages_selected = ppw_core_get_setting_entire_site_type_array( PPW_Pro_Constants::PAGE_EXCLUDED );
			if ( ! is_home() ) {
				global $post;

				return ! in_array( $post->ID, $pages_selected );
			}

			return ! in_array( PPW_Pro_Constants::EXCLUDE_HOME_PAGE, $pages_selected );
		}

		/**
		 * Update general settings
		 *
		 * @param $request
		 *
		 * @throws Exception
		 */
		public function update_general_settings( $request ) {
			$setting_keys  = array(
				PPW_Pro_Constants::WPP_WHITELIST_COLUMN_PROTECTIONS,
				PPW_Constants::COOKIE_EXPIRED,
				PPW_Pro_Constants::WPP_WHITELIST_ROLES,
				PPW_Pro_Constants::WPP_AUTO_PROTECT_ALL_CHILD_PAGES,
				PPW_Pro_Constants::WPP_APPLY_PASSWORD_FOR_PAGES_POSTS,
				PPW_Pro_Constants::WPP_FORM_MESSAGE,
				PPW_Pro_Constants::WPP_ERROR_MESSAGE,
				PPW_Pro_Constants::WPP_REMOVE_SEARCH_ENGINE,
				PPW_Constants::REMOVE_DATA,
			);
			$data_settings = wp_unslash( $_REQUEST['settings'] );
			$this->validate_before_update_setting( $request, $data_settings, $setting_keys );

			$old = ppw_core_get_setting_type_array( PPW_Pro_Constants::WPP_WHITELIST_COLUMN_PROTECTIONS );
			$this->handle_update_general_settings( $data_settings );
			$this->run_job_when_post_type_protection_changed( $data_settings, $old );
			wp_die( true );
		}

		/**
		 * Validate before update settings
		 *
		 * @param $request
		 * @param $data_settings
		 * @param $setting_keys
		 */
		public function validate_before_update_setting( $request, $data_settings, $setting_keys ) {
			if ( ppw_pro_is_data_invalid( $request, PPW_Constants::GENERAL_FORM_NONCE, PPW_Pro_Constants::DATA_SETTINGS, $setting_keys )
			     || ppw_pro_data_settings_invalid( $data_settings ) ) {
				send_json_data_error( __( PPW_Constants::BAD_REQUEST_MESSAGE, 'password-protect-page' ) );
			}

			$password       = $data_settings[ PPW_Pro_Constants::WPP_PASSWORD_FOR_PAGES_POSTS ];
			$cookie_expired = $data_settings[ PPW_Constants::COOKIE_EXPIRED ];
			if ( ppw_core_validate_cookie_expiry( $cookie_expired ) || strpos( $password, ' ' ) !== false ) {
				send_json_data_error( __( PPW_Constants::BAD_REQUEST_MESSAGE, 'password-protect-page' ) );
			}
		}

		public function run_job_when_post_type_protection_changed( $data, $old ) {
			if ( ! isset( $data[ PPW_Pro_Constants::WPP_WHITELIST_COLUMN_PROTECTIONS ] ) ) {
				return;
			}

			$new = $data[ PPW_Pro_Constants::WPP_WHITELIST_COLUMN_PROTECTIONS ];
			if ( empty( $new ) ) {
				return;
			}

			if ( empty( array_diff( $new, $old ) ) ) {
				return;
			}

			global $cpt_migration_service;
			$cpt_migration_service->start_run();
		}

		/**
		 * Handle data before update general settings
		 *
		 * @param $data_settings
		 *
		 * @throws Exception
		 */
		public function handle_update_general_settings( $data_settings ) {
			$this->handle_feature_password_protect_private_page( $data_settings );
			update_option( PPW_Pro_Constants::GENERAL_SETTING_OPTIONS, wp_json_encode( $data_settings ), 'no' );
		}

		/**
		 * Update password for feature password protect private page
		 *
		 * @param $data_settings
		 *
		 * @throws Exception
		 */
		public function handle_feature_password_protect_private_page( $data_settings ) {
			$is_protect = $data_settings[ PPW_Pro_Constants::WPP_APPLY_PASSWORD_FOR_PAGES_POSTS ];
			if ( 'true' === $is_protect ) {
				$post_selected = $data_settings[ PPW_Pro_Constants::WPP_PAGES_POST_SELECTED ];
				$password      = $data_settings[ PPW_Pro_Constants::WPP_PASSWORD_FOR_PAGES_POSTS ];
				foreach ( $post_selected as $post_id ) {
					$password_info = $this->repository->get_password_info( $password, $post_id, PPW_Pro_Constants::CAMPAIGN_TYPE['COMMON'] );
					if ( ! is_null( $password_info ) ) {
						send_json_data_error( __( PPW_Pro_Constants::MESSAGE_DUPLICATE_PASSWORD, 'password-protect-page' ) );
					}
				}
				$this->repository->update_password_for_feature_protect_private_pages( $post_selected, $password );
			} else {
				$this->repository->delete_all_password_type_is_common();
			}
		}

		/**
		 * Check is exclude page in feature protect entire site
		 *
		 * @return bool
		 */
		public function is_exclude_page() {
			$is_exclude = ppw_pro_get_setting_entire_site_type_bool( PPW_Pro_Constants::IS_EXCLUDE_PAGE );
			if ( ! $is_exclude ) {
				return false;
			}

			$pages_selected = ppw_pro_get_setting_entire_site_type_array( PPW_Pro_Constants::PAGE_EXCLUDED );
			if ( ! is_home() ) {
				global $post;
				if ( is_null( $post ) || ! is_object( $post ) ) {
					return false;
				}

				return in_array( $post->ID, $pages_selected );
			}

			return in_array( PPW_Pro_Constants::EXCLUDE_HOME_PAGE, $pages_selected );
		}


		/**
		 * Check is exclude page in feature protect entire site
		 *
		 * @return bool
		 */
		public function is_excluded_post_type() {
			$post_types = defined( 'PPWP_PRO_SITEWIDE_POST_TYPE_EXCLUDES' ) ? PPWP_PRO_SITEWIDE_POST_TYPE_EXCLUDES : false;
			if ( ! $post_types || ! is_array( $post_types ) || count( $post_types ) === 0 ) {
				return false;
			}

			if ( is_singular() ) {
				$post_type = get_post_type();
				if ( false === $post_type ) {
					return false;
				}

				return in_array( $post_type, $post_types, true );
			}

			return false;
		}

		/**
		 * Validate auth cookie for entire site
		 *
		 * @param $passwords
		 *
		 * @return bool
		 */
		public function validate_auth_cookie_entire_site( $passwords ) {
			$cookie_elements = $this->parse_cookie_entire_site();
			if ( false === $cookie_elements ) {
				return false;
			}

			if ( (int) $cookie_elements[1] < time() ) {
				return false;
			}

			foreach ( $passwords as $password ) {
				$hash = hash_hmac( 'md5', PPW_Constants::ENTIRE_SITE_COOKIE_NAME, $password );
				if ( $cookie_elements[0] === $hash ) {
					return true;
				}

				$hash_pass = hash_hmac( 'md5', PPW_Constants::ENTIRE_SITE_COOKIE_NAME, md5( wp_slash( $password ) ) );
				if ( $cookie_elements[0] === $hash_pass ) {
					return true;
				}
			}

			return false;
		}

		/**
		 * Parse cookie entire site
		 *
		 * @return array|bool
		 */
		function parse_cookie_entire_site() {
			$cookie_name = apply_filters( 'ppwp_sitewide_cookie_name', PPW_Constants::ENTIRE_SITE_COOKIE_NAME );
			if ( empty( $_COOKIE[ $cookie_name ] ) ) {
				return false;
			}

			$cookie          = $_COOKIE[ $cookie_name ];
			$cookie_elements = explode( '|', $cookie );
			if ( count( $cookie_elements ) != 2 ) {
				return false;
			}

			return $cookie_elements;
		}

		/**
		 * Check is valid password for entire site
		 *
		 * @param string      $input_password        The password from request.
		 * @param array       $passwords             The collection of passwords.
		 * @param array|false $entire_site_passwords Entire site passwords.
		 *
		 * @return bool|string False means that the password is not valid.
		 */
		public function entire_site_is_valid_password( $input_password, $passwords, $entire_site_passwords = false ) {
			$is_valid_password = apply_filters( 'ppwp_sitewide_is_valid_password', in_array( $input_password, $passwords, true ), $input_password, $passwords );
			if ( $is_valid_password ) {
				$this->entire_site_set_password_to_cookie( $input_password );
				$this->entire_site_handle_password_before_response( true, $input_password, $entire_site_passwords );

				return $input_password;
			}
			$this->entire_site_handle_password_before_response( false, $input_password, $entire_site_passwords );

			return false;
		}

		/**
		 * Handle register hook to pass data for Stats after enter password.
		 *
		 * @param bool        $is_valid              Password status.
		 * @param string      $password              The password.
		 * @param array|false $entire_site_passwords Entire site passwords.
		 *
		 * @return mixed
		 */
		public function entire_site_handle_password_before_response( $is_valid, $password, $entire_site_passwords = false ) {
			if ( ! $entire_site_passwords ) {
				$entire_site_passwords = ppw_pro_get_setting_entire_site_type_array( PPW_Pro_Constants::PPW_PASSWORD_FOR_ENTIRE_SITE );
			}
			if ( $is_valid ) {
				$redirect_url = isset( $entire_site_passwords[ $password ]['redirect_url'] ) ? $entire_site_passwords[ $password ]['redirect_url'] : 'N/A';
			} else {
				$redirect_url = 'N/A';
			}

			$user_name = ppw_pro_get_current_user_name();
			$data      = array(
				'server_env'   => $_SERVER,
				'is_valid'     => $is_valid,
				'password'     => $password,
				'redirect_url' => $redirect_url,
				'username'     => $user_name,
				'post_type'    => PPW_Pro_Constants::ENTIRE_SITE_TYPE,
			);
			apply_filters( PPW_Pro_Constants::HOOK_ENTIRE_SITE_AFTER_CHECK_VALID_PASSWORD, $data );

			return $is_valid;
		}

		/**
		 * Set password to cookie for entire site
		 *
		 * @param string $password The password.
		 */
		public function entire_site_set_password_to_cookie( $password ) {
			$expire                  = apply_filters( PPW_Constants::HOOK_COOKIE_EXPIRED, time() + 7 * DAY_IN_SECONDS );
			$password_cookie_expired = ppw_core_get_setting_type_string( PPW_Constants::COOKIE_EXPIRED );
			if ( ! empty( $password_cookie_expired ) ) {
				$time = explode( " ", $password_cookie_expired )[0];
				$unit = ppw_core_get_unit_time( $password_cookie_expired );
				if ( $unit !== 0 ) {
					$expire = apply_filters( PPW_Constants::HOOK_COOKIE_EXPIRED, time() + (int) $time * $unit );
				}
			}

			$hash   = hash_hmac( 'md5', PPW_Constants::ENTIRE_SITE_COOKIE_NAME, $password );
			$cookie = $hash . "|" . $expire;

			if ( ppw_pro_should_enable_feature() ) {
				ppw_free_bypass_cache_with_cookie_for_pro_version( $cookie, $expire );
			}

			$cookie_name = apply_filters( 'ppwp_sitewide_cookie_name', PPW_Constants::ENTIRE_SITE_COOKIE_NAME );
			setcookie( $cookie_name, $cookie, $expire, COOKIEPATH, COOKIE_DOMAIN );
		}

		/**
		 * Get entire site's redirect URL
		 *
		 * @param string $password              the password is valid.
		 * @param array  $entire_site_passwords All password and redirect url.
		 *
		 * @return string
		 */
		public function get_entire_site_redirect_url( $password, $entire_site_passwords ) {
			$is_redirect = ppw_pro_get_setting_entire_site_type_bool( PPW_Pro_Constants::ENTIRE_SITE_REDIRECTION );
			$is_redirect = apply_filters( 'ppwp_sitewide_is_redirect', $is_redirect, $password, $entire_site_passwords );
			if ( $is_redirect && isset( $entire_site_passwords[ $password ]['redirect_url'] ) && ! empty( $entire_site_passwords[ $password ]['redirect_url'] ) ) {
				$redirect_url = $entire_site_passwords[ $password ]['redirect_url'];
			} else {
				if ( ! empty( $_SERVER['HTTP_REFERER'] ) ) {
					$redirect_url = $_SERVER['HTTP_REFERER']; //phpcs:ignore
				} else {
					global $wp;
					$redirect_url = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );
				}
			}

			return $redirect_url;
		}

		/**
		 * Redirect after user enter password success
		 *
		 * @param string $password              the password is valid.
		 * @param array  $entire_site_passwords All password and redirect url.
		 */
		public function entire_site_redirect_after_enter_password( $password, $entire_site_passwords ) {
			$is_redirect = ppw_pro_get_setting_entire_site_type_bool( PPW_Pro_Constants::ENTIRE_SITE_REDIRECTION );
			$is_redirect = apply_filters('ppwp_sitewide_is_redirect', $is_redirect, $password, $entire_site_passwords );
			if ( $is_redirect && isset( $entire_site_passwords[ $password ]['redirect_url'] ) && ! empty( $entire_site_passwords[ $password ]['redirect_url'] ) ) {
				$redirect_url = $entire_site_passwords[ $password ]['redirect_url'];
			} else {
				if ( ! empty( $_SERVER['HTTP_REFERER'] ) ) {
					$redirect_url = $_SERVER['HTTP_REFERER']; //phpcs:ignore
				} else {
					global $wp;
					$redirect_url = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );
				}
			}
			do_action( PPW_Pro_Constants::HOOK_ENTIRE_SITE_HANDLE_BEFORE_REDIRECT, $password, $redirect_url );
			wp_redirect( $redirect_url );
		}

		/**
		 * Check condition to render content for entire site
		 *
		 * @param array $passwords all passwords.
		 *
		 * @return bool
		 */
		public function entire_site_is_render_content( $passwords ) {
			if ( ! $this->is_enabled_entire_site_protection( $passwords ) ) {
				return true;
			}

			$post_id = ppw_pro_get_post_id();

			return $this->password_services->is_whitelist_roles( $post_id )
			       || $this->is_excluded_post_type()
			       || $this->is_exclude_page()
			       || $this->validate_auth_cookie_entire_site( $passwords );
		}

		/**
		 * Check whether having the entire site protection or cookie validation.
		 *
		 * @param array $passwords Entire site passwords.
		 *
		 * @return bool
		 */
		public function is_enabled_entire_site_protection( $passwords ) {
			$is_protect = ppw_pro_get_setting_entire_site_type_bool( PPW_Constants::IS_PROTECT_ENTIRE_SITE );
			return $is_protect && count( $passwords ) > 0;
		}

		/**
		 * Massage entire site password from the request.
		 *
		 * @param $data_settings
		 *
		 * @return array including pass and redirect_url key
		 */
		private function massage_pwd_entire_site( $data_settings ) {
			$password_entire_site = array();
			if ( ! isset( $data_settings['ppw_password_entire_site'] ) ) {
				return $password_entire_site;
			}

			foreach ( $data_settings['ppw_password_entire_site'] as $pass ) {
				$password_entire_site[ $pass['pass'] ] = array(
					'redirect_url' => $pass['redirect_url'],
				);
			}

			return $password_entire_site;
		}

		/**
		 * Get id pages/posts protected
		 *
		 * @return array
		 */
		public function get_protected_ids() {
			$protected_posts = $this->repository->get_all_protected_posts();
			$protected_ids   = array_filter(
				array_map(
					function ( $post ) {
						return (int) $post->post_id;
					},
					$protected_posts
				),
				function ( $post_id ) {
					return $this->password_services->is_protected_content( $post_id );
				}
			);

			return apply_filters( PPW_Pro_Constants::HOOK_CUSTOM_PROTECTED_ID, $protected_ids );
		}

		/**
		 * Get all ID of protected post but ignore post in whitelist roles.
		 *
		 * @return array list protected ID.
		 */
		public function get_protected_id_by_whitelist_roles() {
			return array_filter(
				$this->get_protected_ids(),
				function ( $id ) {
					return ! $this->password_services->is_whitelist_roles( $id );
				}
			);
		}

		/**
		 * Change status for option Hide
		 *
		 * @param string $status    Status of options: disable or ''.
		 * @param string $post_type The post type name.
		 *
		 * @return string
		 */
		public function handle_status_option_hide_protect_content( $status, $post_type ) {
			if ( 'page_post' === $post_type ) {
				return '';
			}

			if ( in_array( $post_type, ppw_core_get_setting_type_array( PPW_Pro_Constants::WPP_WHITELIST_COLUMN_PROTECTIONS ), true ) ) {
				return '';
			}

			return $status;
		}

		/**
		 * Custom position for feature Hide protected post
		 *
		 * @param array  $positions List default position.
		 * @param string $post_type The post type name.
		 *
		 * @return array
		 */
		public function handle_positions_hide_protected_post( $positions, $post_type ) {
			if ( 'page' === $post_type || 'post' === $post_type ) {
				return $positions;
			}

			$options = array();
			if ( function_exists( 'ppw_core_check_yoast_seo_turn_on_site_maps' ) && ppw_core_check_yoast_seo_turn_on_site_maps() ) {
				$options = array(
					array(
						'value' => PPW_Constants::XML_YOAST_SEO_SITEMAPS,
						'label' => esc_html__( 'XML sitemaps', 'password-protect-page' ),
					),
				);
			}

			$post_type_options = array(
				array(
					'value' => PPW_Constants::SEARCH_RESULTS,
					'label' => esc_html__( 'Search results', 'password-protect-page' ),
				),
				array(
					'value' => PPW_Constants::CATEGORY_PAGE,
					'label' => esc_html__( 'Category pages', 'password-protect-page' ),
				),
				array(
					'value' => PPW_Constants::TAG_PAGE,
					'label' => esc_html__( 'Tag pages', 'password-protect-page' ),
				),
			);

			switch ( $post_type ) {
				case PPW_Pro_Constants::WOO_PRODUCT:
					array_push(
						$post_type_options,
						array(
							'value' => PPW_Pro_Constants::WOO_STORE_PAGE,
							'label' => esc_html__( 'Store', 'password-protect-page' ),
						)
					);
					break;
			}

			return array_merge( $options, $post_type_options );
		}

		/**
		 * Custom default option for feature Hide protected post follow post type.
		 *
		 * @param array  $positions List default position.
		 * @param string $post_type The post type name.
		 *
		 * @return array
		 */
		public function handle_default_options_hide_protected_post( $positions, $post_type ) {
			if ( 'page' === $post_type || 'post' === $post_type ) {
				return $positions;
			}

			$options = array();
			if ( function_exists( 'ppw_core_check_yoast_seo_turn_on_site_maps' ) && ppw_core_check_yoast_seo_turn_on_site_maps() ) {
				$options = array( PPW_Constants::XML_YOAST_SEO_SITEMAPS );
			}

			$post_type_options = array(
				PPW_Constants::SEARCH_RESULTS,
				PPW_Constants::CATEGORY_PAGE,
				PPW_Constants::TAG_PAGE,
			);

			switch ( $post_type ) {
				case 'product':
					array_push( $post_type_options, PPW_Pro_Constants::WOO_STORE_PAGE );
					break;
			}

			return array_merge( $options, $post_type_options );
		}

		/**
		 * Ignore protected product for category page, tag page, store page
		 *
		 * @param WP_Query $query Query instance.
		 */
		public function handle_hide_protected_product( $query ) {
			if ( ! defined( 'PPW_Constants::HIDE_SELECTED' ) ) {
				return;
			}

			$is_hide = ppw_core_get_setting_type_bool( PPW_Constants::HIDE_PROTECTED . PPW_Pro_Constants::WOO_PRODUCT );
			if ( ! $is_hide ) {
				return;
			}

			$old_post_not_in   = $query->get( 'post__not_in' );
			$list_protected_id = $this->get_protected_id_by_whitelist_roles();
			foreach ( $list_protected_id as $id ) {
				if ( PPW_Pro_Constants::WOO_PRODUCT === get_post_type( $id ) ) {
					array_push( $old_post_not_in, $id );
				}
			}
			$position_selected = ppw_core_get_setting_type_array( PPW_Constants::HIDE_SELECTED . PPW_Pro_Constants::WOO_PRODUCT );

			// Hide protected product on shop page.
			if ( is_shop() ) {
				if ( in_array( PPW_Pro_Constants::WOO_STORE_PAGE, $position_selected, true ) ) {
					$query->set( 'post__not_in', $old_post_not_in );
				}
			}

			// Hide protected product on category page.
			if ( is_product_category() ) {
				if ( in_array( PPW_Constants::CATEGORY_PAGE, $position_selected, true ) ) {
					$query->set( 'post__not_in', $old_post_not_in );
				}
			}

			// Hide protected product on tag page.
			if ( is_product_tag() ) {
				if ( in_array( PPW_Constants::TAG_PAGE, $position_selected, true ) ) {
					$query->set( 'post__not_in', $old_post_not_in );
				}
			}
		}

	}
}
