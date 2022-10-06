<?php
if ( ! class_exists( 'PPW_Pro_Password_Services' ) ) {
	class PPW_Pro_Password_Services extends PPW_Password_Services {

		/**
		 * Class PPW_Pro_Repository
		 *
		 * @var PPW_Pro_Repository
		 */
		private $repository;

		/**
		 * Class PPW_Pro_Token_Services
		 *
		 * @var PPW_Pro_Token_Services
		 */
		private $token_service;

		/**
		 * Class PPW_Password_Services
		 *
		 * @var PPW_Password_Services|null
		 */
		private $free_password_service;

		/**
		 * PPW_Pro_Password_Services constructor.
		 *
		 * @param PPW_Pro_Repository     $repository            The repository.
		 * @param PPW_Pro_Token_Services $token_service         The token service.
		 * @param PPW_Password_Services  $free_password_service The free password service.
		 */
		public function __construct( $repository = null, $token_service = null, $free_password_service = null ) {
			if ( is_null( $repository ) ) {
				$repository = new PPW_Pro_Repository();
			}
			$this->repository = $repository;

			if ( is_null( $token_service ) ) {
				$token_service = new PPW_Pro_Token_Services();
			}
			$this->token_service = $token_service;

			if ( is_null( $free_password_service ) ) {
				$free_password_service = new PPW_Password_Services();
			}
			$this->free_password_service = $free_password_service;
		}

		/**
		 * Check the page or post is protected.
		 *
		 * @param string|int $post_id   the attachment id.
		 * @param string|int $parent_id the parent id.
		 *
		 * @return bool
		 */
		public function is_protected_content( $post_id, $parent_id = null ) {
			if ( ! $this->check_protection( $post_id ) ) {
				return false;
			}
			$post_id = is_null( $parent_id ) ? ppw_pro_get_post_id_follow_protect_child_page( $post_id ) : $parent_id;

			return $this->repository->is_protected_item( $post_id );
		}

		/**
		 * Check protection in settings
		 *
		 * @param int $post_id The post ID.
		 *
		 * @return bool
		 */
		public function check_protection( $post_id ) {
			$post_type = get_post_type( $post_id );
			if ( 'post' === $post_type || 'page' === $post_type ) {
				return true;
			}

			$post_type_selected = ppw_core_get_setting_type_array( PPW_Pro_Constants::WPP_WHITELIST_COLUMN_PROTECTIONS );

			return in_array( $post_type, $post_type_selected );
		}

		/**
		 * Protect page/post
		 *
		 * @param int $post_id The post ID.
		 */
		public function protect_page_post( $post_id ) {
			$this->repository->update_page_post_status( $post_id, 'true' );
		}

		/**
		 * Unprotect page/post
		 *
		 * @param int $post_id The post ID.
		 */
		public function un_protect_page_post( $post_id ) {
			$this->repository->update_page_post_status( $post_id, 'false' );
		}

		/**
		 * Get status post, post title and edit url
		 *
		 * @param int $post_id The post ID.
		 *
		 * @return array
		 */
		public function get_status_post_and_data_related( $post_id ) {
			$file_is_protect = get_post_meta( $post_id, PPW_Pro_Constants::AUTO_GENERATE_PWD_META_DATA, true );
			$title           = get_the_title( $post_id );

			return array(
				'file_is_protect' => $file_is_protect,
				'post'            => array(
					'title'    => $title ? $title : '(no title)',
					'edit_url' => get_permalink( $post_id ),
				),
			);
		}

		/**
		 * Check condition before create new password
		 *
		 * @param array $data The request data.
		 *
		 * @throws Exception
		 */
		public function check_condition_before_create_new_password( $data ) {
			$post_id   = $data['id'];
			$passwords = $this->repository->get_all_password_by_post_id( $post_id );
			if ( empty( $passwords ) ) {
				$this->auto_generate_pwd( $post_id );
			}
		}

		/**
		 * Generate password
		 *
		 * @param int        $post_id    The post ID.
		 * @param bool       $data       The additional data.
		 * @param string|int $contact_id The contact ID.
		 * @param string     $app_type   The password app type.
		 *
		 * @return mixed|WP_Error
		 * @throws Exception
		 */
		public function auto_generate_pwd( $post_id, $data = false, $contact_id = '', $app_type = '' ) {
			$result = $this->check_password_before_save_to_db( $data, $post_id );
			if ( $result[ PPW_Pro_Constants::IS_ERROR ] ) {
				return $result;
			}

			$pwd = $result[ PPW_Pro_Constants::MESSAGE ];
			if ( empty( $contact_id ) ) {
				return $this->handle_case_contact_id_empty( $data, $post_id, $pwd );
			}

			return $this->handle_case_contact_id_not_empty( $contact_id, $post_id, $app_type, $pwd, $data );
		}

		/**
		 * @param $data
		 * @param $post_id
		 *
		 * @return array
		 */
		public function check_password_before_save_to_db( $data, $post_id ) {
			$isset_pwd = isset( $data['ppwp_password'] );
			$pwd       = $isset_pwd ? $data['ppwp_password'] : generate_pwd();
			if ( $isset_pwd && strpos( $pwd, ' ' ) !== false ) {
				return array(
					PPW_Pro_Constants::IS_ERROR => true,
					PPW_Pro_Constants::MESSAGE  => __( PPW_Pro_Constants::MESSAGE_EMPTY_PASSWORD, 'protect-password-page' ),
				);
			}

			if ( ! is_null( $this->repository->get_advance_password_by_password_and_post_id( $pwd, $post_id ) ) ) {
				return array(
					PPW_Pro_Constants::IS_ERROR => true,
					PPW_Pro_Constants::MESSAGE  => __( PPW_Pro_Constants::MESSAGE_DUPLICATE_PASSWORD, 'password-protect-wordpress' ),
				);
			}

			return array(
				PPW_Pro_Constants::IS_ERROR => false,
				PPW_Pro_Constants::MESSAGE  => $pwd,
			);
		}

		/**
		 * Create new password and send json for API with case contact id empty
		 *
		 * @param $data
		 * @param $post_id
		 * @param $pwd
		 *
		 * @return mixed
		 * @throws Exception
		 */
		public function handle_case_contact_id_empty( $data, $post_id, $pwd ) {
			$inserted_password = $this->create_new_password_case_contact_id_empty( $data, $post_id, $pwd );
			if ( false === $inserted_password ) {
				return array(
					PPW_Pro_Constants::IS_ERROR => true,
					PPW_Pro_Constants::MESSAGE  => __( PPW_Pro_Constants::MESSAGE_CREATE_PASSWORD_ERROR, 'protect-password-page' ),
				);
			}

			return array(
				PPW_Pro_Constants::IS_ERROR => false,
				PPW_Pro_Constants::MESSAGE  => __( PPW_Pro_Constants::MESSAGE_CREATE_PASSWORD_SUCCESS, 'protect-password-page' ),
				PPW_Pro_Constants::VALUE    => $inserted_password,
			);
		}

		/**
		 * Create new password and send json for API with case contact id not empty
		 *
		 * @param $contact_id
		 * @param $post_id
		 * @param $app_type
		 * @param $pwd
		 * @param $data
		 *
		 * @return mixed
		 * @throws Exception
		 */
		public function handle_case_contact_id_not_empty( $contact_id, $post_id, $app_type, $pwd, $data ) {
			$is_error = true;
			$message  = PPW_Pro_Constants::MESSAGE_CREATE_PASSWORD_ERROR;
			if ( $this->create_new_password_case_contact_id_not_empty( $contact_id, $post_id, $app_type, $pwd, $data ) ) {
				$is_error = false;
				$message  = PPW_Pro_Constants::MESSAGE_CREATE_PASSWORD_SUCCESS;
			}

			return array(
				PPW_Pro_Constants::IS_ERROR => $is_error,
				PPW_Pro_Constants::MESSAGE  => __( $message, 'protect-password-page' ),
				PPW_Pro_Constants::PW       => $pwd,
			);
		}

		/**
		 * Handle create new password with case contact id not empty
		 *
		 * @param $contact_id
		 * @param $post_id
		 * @param $app_type
		 * @param $pwd
		 * @param $data
		 *
		 * @return mixed
		 * @throws Exception
		 */
		public function create_new_password_case_contact_id_not_empty( $contact_id, $post_id, $app_type, $pwd, $data ) {
			$existed = $this->repository->get_password_by_contact_id( $contact_id );
			if ( is_null( $existed ) ) {
				return $this->repository->insert(
					array(
						'post_id'           => $post_id,
						'contact_id'        => $contact_id,
						'campaign_app_type' => $app_type,
						'password'          => $pwd,
						'usage_limit'       => isset( $data['ppwp_usage_limit'] ) ? $data['ppwp_usage_limit'] : null,
						'expired_date'      => isset( $data['ppwp_expired_days'] ) ? $this->get_expired_time_stamp( $data['ppwp_expired_days'] ) : null,
					)
				);
			}

			return $this->repository->update_password_by_contact_id(
				$contact_id,
				array(
					'password' => $pwd,
					'post_id'  => $post_id,
				)
			);
		}

		/**
		 * Create new password with case contact id empty
		 *
		 * @param $data
		 * @param $post_id
		 * @param $pwd
		 *
		 * @return mixed
		 * @throws Exception
		 */
		public function create_new_password_case_contact_id_empty( $data, $post_id, $pwd ) {
			$password_type = PPW_Pro_Constants::CAMPAIGN_TYPE['AUTO'];
			if ( isset( $data['typeSelected'] ) && $data['typeSelected'] !== 'global' ) {
				$password_type = $this->handle_roles_selected( $data['roleSelected'] );
			}

			return $this->protect_and_insert_password_by_type( $post_id, $password_type, $data, $pwd );
		}

		/**
		 * Handle roles selected
		 *
		 * @param $roles_selected
		 *
		 * @return string
		 */
		public function handle_roles_selected( $roles_selected ) {
			$results = array_map( function ( $role ) {
				return PPW_Pro_Constants::CAMPAIGN_TYPE['ROLE'] . $role;
			}, $roles_selected );

			return implode( ";", $results );
		}

		/**
		 * Protect and insert new password by type
		 *
		 * @param $post_id
		 * @param $password_type
		 * @param $data
		 * @param $pwd
		 *
		 * @return mixed
		 * @throws Exception
		 */
		public function protect_and_insert_password_by_type( $post_id, $password_type, $data, $pwd ) {
			if ( ! $this->is_protected_content( $post_id ) ) {
				$this->protect_page_post( $post_id );
			}
			$data['ppwp_campaign_app_type'] = $password_type;

			return $this->insert_password( $post_id, $pwd, $data );
		}

		/**
		 * Insert new password into database
		 *
		 * @param int    $post_id Post ID.
		 * @param string $pwd     Password Input.
		 * @param array  $data    Information for password.
		 *
		 * @return array|False
		 * @throws Exception
		 * @since 1.1 Return false|Information password after insert.
		 *
		 * @since 1.0 Init function
		 */
		public function insert_password( $post_id, $pwd, $data = array() ) {
			$insert_data = array(
				'post_id'           => $post_id,
				'password'          => $pwd,
				'campaign_app_type' => isset( $data['ppwp_campaign_app_type'] ) ? $data['ppwp_campaign_app_type'] : null,
				'usage_limit'       => isset( $data['ppwp_usage_limit'] ) ? $data['ppwp_usage_limit'] : null,
				'expired_date'      => isset( $data['ppwp_expired_days'] ) ? $this->get_expired_time_stamp( $data['ppwp_expired_days'] ) : null,
				'label'             => isset( $data['ppwp_label'] ) ? $data['ppwp_label'] : '',
				'created_time'      => time(),
			);
			if ( ! $this->repository->insert( $insert_data ) ) {
				return false;
			}
			global $wpdb;
			// It will handle only a request.
			$insert_data['id'] = $wpdb->insert_id;

			return $insert_data;
		}

		/**
		 * Get expired time stamp
		 *
		 * @param $days_to_expired
		 *
		 * @return int
		 * @throws Exception
		 */
		function get_expired_time_stamp( $days_to_expired ) {
			$curr_date    = new DateTime();
			$expired_date = $curr_date->modify( intval( $days_to_expired ) . ' day' );

			return $expired_date->getTimestamp();
		}

		/**
		 * Get password data
		 *
		 * @param $post_id
		 *
		 * @return mixed
		 */
		public function get_password_data( $post_id ) {
			$advance_password = $this->repository->get_type_and_password_by_post_id_and_campaign_type( $post_id );

			return ppw_pro_massage_data_password_for_api_in_meta_box( $advance_password );
		}

		/**
		 * Check password is valid
		 *
		 * @param string     $password      the password.
		 * @param string|int $post_id       the post id.
		 * @param array      $current_roles List current roles.
		 *
		 * @return array
		 */
		public function is_valid_password( $password, $post_id, $current_roles ) {
			$password_info = $this->get_password_info( $password, $post_id, $current_roles );

			$result        = array(
				'is_valid' => false,
			);
			if ( is_null( $password_info ) ) {
				return $result;
			}
			$password_type = $password_info->campaign_app_type;
			if ( ! $this->is_password_for_role( $password_type ) ) {
				$result['is_valid']      = true;
				$result['cookie_name']   = $password;
				$result['password_info'] = $password_info;
			} else {
				$role = $this->is_valid_password_type_is_roles( $current_roles, $password_type );
				if ( $role ) {
					$result['is_valid']      = true;
					$result['cookie_name']   = $password . $role;
					$result['password_info'] = $password_info;
				}
			}

			return $result;
		}

		/**
		 * Update hits count for password
		 *
		 * @param object $advance_password password info in DB.
		 *
		 * @return bool
		 */
		public function update_hits_count_for_password( $advance_password ) {
			$data = array(
				'hits_count' => (int) $advance_password->hits_count + 1,
			);

			return $this->repository->update_data_password_by_id( $advance_password->id, $data );
		}

		/**
		 * Check is password for role
		 *
		 * @param string $password_type password type in DB.
		 *
		 * @return bool
		 */
		public function is_password_for_role( $password_type ) {
			$password_types = array(
				PPW_Pro_Constants::CAMPAIGN_TYPE['DEFAULT'],
				PPW_Pro_Constants::CAMPAIGN_TYPE['AUTO'],
				PPW_Pro_Constants::CAMPAIGN_TYPE['COMMON'],
				PPW_Pro_Constants::CAMPAIGN_TYPE['ACTIVE_CAMPAIGN'],
			);

			// Fire the hook that can extend the password types.
			$password_types = apply_filters( PPW_Pro_Constants::HOOK_PPWP_PWD_TYPES, $password_types );

			return ! in_array( $password_type, $password_types, true );
		}

		/**
		 * Check password is valid.
		 *
		 * @param string $password      Password.
		 * @param int    $post_id       Post ID.
		 * @param array  $current_roles List current roles.
		 * @param string $type          Type of password.
		 *
		 * @return TRUE|FALSE
		 */
		public function check_password_is_valid( $password, $post_id, $current_roles, $type = '' ) {
			$parent_id = ppw_pro_get_post_id_follow_protect_child_page( $post_id );
			$result = apply_filters(
				'ppwp_pro_check_valid_password',
				$this->is_valid_password( $password, $parent_id, $current_roles ),
				array(
					'password'      => $password,
					'parent_id'     => $parent_id,
					'current_roles' => $current_roles
				)
			);
			$is_valid  = $result['is_valid'];
			if ( $is_valid ) {
				do_action( 'ppwp_pro_before_set_post_cookie', $post_id, $password, $type );
				$this->set_cookie( $result['cookie_name'], $parent_id );
				do_action( 'ppwp_pro_after_set_post_cookie', $post_id, $password, $type );
				$this->update_hits_count_for_password( $result['password_info'] );
			}
			$this->handle_password_before_response( $is_valid, $password, $post_id, $type );

			return $is_valid;
		}

		/**
		 * Handle password before response data
		 *
		 * @param bool   $is_valid Is valid.
		 * @param int    $password Password.
		 * @param int    $post_id  Post ID.
		 * @param string $type     Type.
		 *
		 * @return boolean
		 */
		public function handle_password_before_response( $is_valid, $password, $post_id, $type = '' ) {
			$stats_data = array(
				'server_env' => $_SERVER,
				'is_valid'   => $is_valid,
				'post_id'    => $post_id,
				'password'   => $password,
				'username'   => ppwp_get_user_name(),
				'type'       => $type,
			);

			apply_filters( 'ppwp_after_check_valid_password', $stats_data );

			return $is_valid;
		}

		/**
		 * Check password for type is roles.
		 *
		 * @param array  $current_roles List current roles.
		 * @param string $password_type password type in DB.
		 *
		 * @return bool|mixed
		 */
		public function is_valid_password_type_is_roles( $current_roles, $password_type ) {
			$type_array = explode( ';', $password_type );
			foreach ( $type_array as $password_type ) {
				$role = str_replace( PPW_Pro_Constants::CAMPAIGN_TYPE['ROLE'], '', $password_type );
				if ( in_array( $role, $current_roles, true ) ) {
					return $role;
				}
			}

			return false;
		}

		/**
		 * Check password before render content
		 *
		 * @param string  $content post content.
		 * @param integer $post_id post id.
		 *
		 * @return mixed
		 * @deprecated Because we only use post_password_required to show login form and from 1.2.2 PPW version.
		 *
		 */
		public function check_password_before_render_content( $content, $post_id ) {
			$post_id = ppw_pro_get_post_id_follow_protect_child_page( $post_id );
			if ( ! $this->is_protected_content( $post_id ) || $this->is_whitelist_roles( $post_id ) ) {
				return $content;
			}
			$passwords = $this->repository->get_password_by_post_id( $post_id );
			if ( $this->is_valid_cookie( $post_id, $passwords, PPW_Pro_Constants::GOLD_PASS_COOKIE ) ) {
				return $content;
			}

			return ppw_core_render_login_form();
		}

		/**
		 * Check condition to replace url
		 *
		 * @param string|int $post_id   the post id.
		 * @param string|int $parent_id the parent id.
		 *
		 * @return bool
		 */
		public function should_replace_url( $post_id, $parent_id ) {
			if ( ! $this->is_protected_content( $post_id, $parent_id ) ) {
				return false;
			}
			if ( $this->is_whitelist_roles( $parent_id ) ) {
				return true;
			}
			$passwords = $this->repository->get_password_by_post_id( $parent_id );

			return $this->is_valid_cookie( $parent_id, $passwords, PPW_Pro_Constants::GOLD_PASS_COOKIE );
		}

		/**
		 * Replace pda link or private link for PDA Gold following the media file’s permission.
		 * Only handle for file is protected.
		 * Replace for case: Whitelist role and enter password successfully.
		 *
		 * @param array $data       All urls file in the content.
		 * @param array $conditions Check condition to replace.
		 *
		 * @return array Data after handled.
		 */
		public function handle_replace_urls( $data, $conditions ) {
			// Check data before handle.
			if ( empty( $data['urls'] ) || empty( $data['post_id'] ) ) {
				return $data;
			}

			// Check PPWP is handle S&R.
			if ( isset( $conditions['ppwp_is_using_search_and_replace'] ) && false === $conditions['ppwp_is_using_search_and_replace'] ) {
				return $data;
			}

			// Convert post id to parent id follow feature protect child page.
			$parent_id = ppw_pro_get_post_id_follow_protect_child_page( $data['post_id'] );

			// Check condition to replace.
			if ( version_compare( PDA_GOLD_V3_VERSION, '3.1.2.4', '<' ) && ! $this->should_replace_url( $data['post_id'], $parent_id ) ) {
				return $data;
			}

			$pda_services  = new PDA_Services();
			$pda_repo      = new PDA_v3_Gold_Repository();
			$pda_functions = new Pda_Gold_Functions();

			$base_url = wp_upload_dir()['baseurl'];

			// Replace all protected links with _pda links
			$pda_urls = array_map(
				function ( $url ) use (
					$pda_repo,
					$pda_services,
					$base_url,
					$parent_id,
					$pda_functions
				) {
					return $this->check_permission_media_file_and_replace_url( $base_url, $url, $pda_repo, $pda_services, $pda_functions, $parent_id, false );
				},
				$data['urls']
			);

			// Append token to protected links.
			// Exclude the file having valid permission.
			$token_urls = array_filter(
				$pda_urls,
				function ( $obj ) {
					return ! isset( $obj['valid_permission'] );
				}
			);

			$token_urls = array_map(
				function ( $url ) use (
					$pda_repo,
					$pda_services,
					$base_url,
					$parent_id,
					$pda_functions
				) {
					$url['is_replaced'] = false;

					return $this->check_permission_media_file_and_replace_url( $base_url, $url, $pda_repo, $pda_services, $pda_functions, $parent_id, true );
				},
				$token_urls
			);

			$data['urls'] = array_merge( $pda_urls, $token_urls );

			return $data;
		}

		/**
		 * Check permission and replace url.
		 * Have permission => pda link
		 * Don't have permission => private link
		 *
		 * @param string                 $base_url      File name and date folder.
		 * @param array                  $url           Url in content.
		 * @param PDA_v3_Gold_Repository $pda_repo      Class Repositories in PDA Gold plugin.
		 * @param PDA_Services           $pda_services  Class Services in PDA Gold plugin.
		 * @param Pda_Gold_Functions     $pda_functions Helper PDA Gold functions.
		 * @param int                    $post_id       Current post ID or its parent.
		 * @param bool                   $append_token  Whether to append token
		 *
		 * @return mixed
		 */
		private function check_permission_media_file_and_replace_url( $base_url, $url, $pda_repo, $pda_services, $pda_functions, $post_id, $append_token ) {
			// If url replaced by other plugins such as PDA S3 then return url.
			if ( $url['is_replaced'] ) {
				return $url;
			}

			// Get attachment id and file size.
			list( $attachment_id, $size ) = $this->get_size_and_attachment_id_by_attachment_url( $url['url'] );

			if ( 0 === $attachment_id || ! $pda_repo->is_protected_file( $attachment_id ) ) {
				return $url;
			}

			// Add the _pda to protected link if the permission is valid.
			if ( $pda_functions->check_file_access_permission_for_post( $attachment_id ) ) {
				$url['new_url']          = $pda_services->get_new_url( $url['url'], $base_url );
				$url['is_replaced']      = true;
				$url['valid_permission'] = true;

				return $url;
			}


			if ( defined( 'PDA_GOLD_V3_VERSION' ) && version_compare( PDA_GOLD_V3_VERSION, '3.1.3', '>=' ) ) {
				// Covert to _pda link.
				$url['new_url'] = $pda_services->get_new_url( $url['url'], $base_url );
				if ( $append_token ) {
					// Here all links replace to _pda, need to change url to replace.
					$url['url_to_replace'] = $url['new_url'];
					$url['new_url']        = $this->token_service->append_token_to_protected_link( $url['new_url'], $attachment_id, $post_id );
				}
			} else {
				$url = $this->handle_pda_link_before_v313( $attachment_id, $url, $size );
			}
			$url['is_replaced'] = true;

			return $url;
		}

		/**
		 * Need to keep the old logic to generate the expired private links.
		 *
		 * @param int    $attachment_id The attachment ID.
		 * @param array  $url           URL information include url, new_url and is_replaced.
		 * @param string $size          The image file size (optional)
		 *
		 * @return mixed return the URL information including new_url.
		 */
		private function handle_pda_link_before_v313( $attachment_id, $url, $size ) {
			$private_link_info = array(
				'type'            => PDA_v3_Constants::PDA_PRIVATE_LINK_EXPIRED,
				'limit_downloads' => 1,
			);

			$private_url    = PDA_Private_Link_Services::create_private_link( $attachment_id, $private_link_info );
			$url['new_url'] = $this->append_file_size_to_url( $private_url, $size );

			return $url;
		}

		/**
		 * Handle protected file by
		 *  1. Check the token exists in GET params
		 *  2. Pre-process the token and re-check with id from PDA
		 *  3. Return the file if it is valid cookie
		 *
		 * @param bool $valid         Valid to return the file.
		 * @param int  $attachment_id The attachment ID.
		 *
		 * @return bool
		 */
		public function handle_protected_file( $valid, $attachment_id ) {
			if ( ! isset( $_GET[ PPW_Pro_Constants::PDA_ORIGIN_LINK_TOKEN ] ) ) {
				return false;
			}

			$post_id = $this->token_service->process_protected_file_token( $_GET[ PPW_Pro_Constants::PDA_ORIGIN_LINK_TOKEN ], $attachment_id ); //phpcs:ignore

			if ( false === $post_id || ! $this->is_protected_content( $post_id ) ) {
				return false;
			}

			if ( $this->is_whitelist_roles( $post_id ) ) {
				return true;
			}

			$passwords = $this->repository->get_password_by_post_id( $post_id );
			$result    = $this->is_valid_cookie( $post_id, $passwords, PPW_Pro_Constants::GOLD_PASS_COOKIE );

			return apply_filters( PPW_Pro_Constants::HOOK_UNLOCK_PDA_FILE, $result, $post_id, $attachment_id );
		}


		/**
		 * Append the file size to existing URL. If the URL do not have the query parameter then connect them by ?. Otherwise using & (Follow the HTTP URL format).
		 *
		 * @param string $url  URL without size.
		 * @param string $size The image size.
		 *
		 * @return string
		 */
		public function append_file_size_to_url( $url, $size ) {
			$get_param_prefix = wp_parse_url( $url, PHP_URL_QUERY ) ? '&' : '?';
			$param            = empty( $size ) ? '' : "{$get_param_prefix}size=" . str_replace( '-', '', $size );

			return $url . $param;
		}

		/**
		 * Get file size and attachment id by URL. We need to remove file size
		 *
		 * @param string $attachment_url the attachment URL.
		 *
		 * @return array
		 */
		public function get_size_and_attachment_id_by_attachment_url( $attachment_url ) {
			// Remove parameter from URL.
			list ( $url ) = explode( '?', $attachment_url );

			// Get attachment_id with gold function.
			$results = $this->pda_gold_get_attachment_id_by_url( $url );
			if ( is_array( $results ) ) {
				return $results;
			}

			$attachment_id = $this->get_attachment_id_by_attachment_url( $url );
			if ( 0 !== $attachment_id ) {
				return array( $attachment_id, '' );
			}

			// Handle for image has file size.
			return $this->remove_file_size_and_get_attachment_id( $url );
		}

		/**
		 * Get attachment ID by File URL.
		 *
		 * @param string $url File URL.
		 *
		 * @return bool|array
		 */
		public function pda_gold_get_attachment_id_by_url( $url ) {
			$results = apply_filters( 'ppwp_pro_return_before_get_attachment_id_by_url', false, $url );
			if ( is_array( $results ) ) {
				return $results;
			}

			if ( ! method_exists( 'Pda_v3_Gold_Helper', 'attachment_image_url_to_post' ) || ! method_exists( 'Pda_v3_Gold_Helper', 'get_image_size_of_link' ) ) {
				return false;
			}

			$wp_upload_dir  = wp_upload_dir();
			$baseurl        = rtrim( $wp_upload_dir['baseurl'], '/' );
			$default_values = array( 0, '' );
			$pda_baseurl    = $baseurl . '/_pda';
			// Check pda_folder have in url.
			if ( false !== strpos( $url, $pda_baseurl ) ) {
				$current_file_path = str_replace( $pda_baseurl . '/', '', $url );
			} else {
				$current_file_path = str_replace( $baseurl . '/', '', $url );
			}

			$extension = wp_check_filetype( $url );
			$is_image  = false !== $extension['type'] && false !== strpos( $extension['type'], 'image' );

			$gold_helper = new Pda_v3_Gold_Helper();

			// Get attachment object by original url.
			if ( $is_image ) {
				// Handle only image types.
				$attachment    = $gold_helper->attachment_image_url_to_post( $pda_baseurl . '/', $current_file_path );
				$attachment_id = empty( $attachment ) ? 0 : $attachment->post_id;
			} else {
				// Handle all file types.
				$attachment_id = attachment_url_to_postid( $pda_baseurl . '/' . $current_file_path );
			}

			if ( empty( $attachment_id ) ) {
				return $default_values;
			}

			list( $size, $url_file ) = $gold_helper->get_image_size_of_link( $url );

			return array(
				$attachment_id,
				$size,
			);
		}

		/**
		 * Remove file size and get attachment id
		 *
		 * @param string $url The attachment URL.
		 *
		 * @return array
		 */
		public function remove_file_size_and_get_attachment_id( $url ) {
			$default_value = array( 0, '' );
			if ( ! method_exists( 'Pda_v3_Gold_Helper', 'get_image_size_of_link' ) ) {
				// Still maintain the logic due to the PDA Gold is not updated.
				list ( $size, $new_url ) = $this->get_image_size_of_link( $url );
			} else {
				list( $size, $new_url ) = Pda_v3_Gold_Helper::get_instance()->get_image_size_of_link( $url );
			}

			if ( '' === $size ) {
				return $default_value;
			}

			$attachment_id = $this->get_attachment_id_by_attachment_url( $new_url );
			if ( 0 !== $attachment_id ) {
				return array(
					$attachment_id,
					$size,
				);
			}

			return $default_value;
		}

		/**
		 * Duplicate code from function get_image_size_of_link of Pda_v3_Gold_Helper
		 *
		 * @param string $file URL file.
		 *
		 * @return array
		 * @deprecated
		 */
		private function get_image_size_of_link( $file ) {
			$default_results = array( '', $file );
			preg_match( '/\.(gif|jpg|jpe?g|tiff|png|bmp|webp)$/i', $file, $matches );
			$is_image_type = ! empty( $matches );

			if ( ! $is_image_type ) {
				return $default_results;
			}
			preg_match_all( '(-\d+x\d+\.\w+$)', $file, $matches, PREG_PATTERN_ORDER );

			$found = end( $matches[0] );

			if ( empty( $found ) ) {
				return $default_results;
			}

			$arr      = explode( '.', $found );
			$size     = $arr[0];
			$ext      = $arr[1];
			$url_file = str_replace( $found, ".$ext", $file );

			return array( $size, $url_file );
		}

		/**
		 * Get attachment id by attachment URL
		 *
		 * @param string $url attachment URL.
		 *
		 * @return mixed
		 */
		public function get_attachment_id_by_attachment_url( $url ) {
			// Get and return attachment id of the URL if exist.
			$attachment_id = attachment_url_to_postid( $url );
			if ( 0 !== $attachment_id ) {
				return $attachment_id;
			}

			$base_url  = wp_upload_dir()['baseurl'];
			$url_path  = $this->remove_base_upload_url( $base_url, $url );
			$url_parts = $this->insert_or_remove_pda_from_url_paths( explode( '/', $url_path ) );

			// Merge full url paths and try to get the attachment ID.
			$new_file = $base_url . '/' . implode( '/', $url_parts );

			return attachment_url_to_postid( $new_file );
		}

		/**
		 * Remove base upload URL
		 *
		 * @param string $base_url The base WordPress upload URL.
		 * @param string $url      The URL.
		 *
		 * @return mixed
		 */
		private function remove_base_upload_url( $base_url, $url ) {
			return str_replace( $base_url . '/', '', $url );
		}

		/**
		 * Check if _pda exists in URL parts then remove. Else add _pda into the parts.
		 *
		 * @param $paths
		 *
		 * @return mixed
		 */
		private function insert_or_remove_pda_from_url_paths( $paths ) {
			if ( ! in_array( '_pda', $paths, true ) ) {
				array_unshift( $paths, '_pda' );
			} else {
				array_shift( $paths );
			}

			return $paths;
		}

		/**
		 * Is whitelist roles
		 *
		 * @param string|bool $post_id post id.
		 *
		 * @return bool
		 */
		public function is_whitelist_roles( $post_id = false ) {
			$is_whitelist = ppw_core_get_setting_type_string( PPW_Pro_Constants::WPP_WHITELIST_ROLES );
			if ( empty( $is_whitelist ) || PPW_Pro_Constants::PERMISSION_NO_ONE === $is_whitelist ) {
				return false;
			}

			switch ( $is_whitelist ) {
				case PPW_Pro_Constants::PERMISSION_ADMIN_USER:
					return current_user_can( 'administrator' );
				case PPW_Pro_Constants::PERMISSION_AUTHOR:
					if ( ! $post_id ) {
						$current_user_id = get_current_user_id();

						return $current_user_id > 0 && is_author( $current_user_id );
					}

					return get_current_user_id() == get_post_field( 'post_author', $post_id, 'raw' );
				case PPW_Pro_Constants::PERMISSION_LOGGED_USER:
					return is_user_logged_in();
				case PPW_Pro_Constants::PERMISSION_CUSTOM_ROLES:
					return $this->check_whitelist_case_custom_roles();
				default:
					return false;
			}
		}

		/**
		 * Check whitelist role case custom roles
		 *
		 * @return bool
		 */
		public function check_whitelist_case_custom_roles() {
			$user_login      = wp_get_current_user()->roles;
			$user_roles      = ppw_core_get_setting_type_array( PPW_Pro_Constants::WPP_ROLE_SELECT );
			$whitelist_roles = array_intersect( $user_login, $user_roles );

			return ! empty( $whitelist_roles );
		}

		/**
		 * Check whether password existed
		 *
		 * @param $password
		 * @param $post_id
		 *
		 * @return bool
		 */
		public function is_password_existed( $password, $post_id ) {
			return ! is_null( $this->repository->get_advance_password_by_password( $password, $post_id ) );
		}

		/**
		 * @param $post_id
		 *
		 * @return array
		 */
		public function get_all_id_child_page( $post_id ) {
			return $this->repository->get_all_id_child_page( $post_id );
		}

		/**
		 * @param $password
		 * @param $post_id
		 */
		public function set_cookie( $password, $post_id ) {
			if ( ppw_pro_should_enable_feature() ) {
				$this->set_password_to_cookie( $password . $post_id, PPW_Pro_Constants::WP_POST_PASS );
			}
			$this->set_password_to_cookie( $password . $post_id, PPW_Pro_Constants::GOLD_PASS_COOKIE . $post_id );
		}

		/**
		 * @param $post_id
		 *
		 * @return array
		 */
		public function list_all_password_by_post_id( $post_id ) {
			$list_password = $this->repository->get_password_info_by_post_id( $post_id );

			return $this->revamp_data_for_all_passwords( $list_password );
		}

		/**
		 *
		 * @return array
		 */
		public function get_pcp_passwords() {
			return $this->repository->fetch_passwords_by_type( PPW_Pro_Constants::CAMPAIGN_TYPE['SHORTCODE'] );
		}

		/**
		 * Add new PCP password
		 *
		 * @param object $request Request data.
		 *
		 * @return WP_REST_Response
		 */
		public function add_new_pcp_password( $request ) {
			$password       = $request->get_param( 'password' );
			$usage_limit    = $request->get_param( 'usage_limit' );
			$expired_dates  = $request->get_param( 'expired_dates' );
			$role_type      = $request->get_param( 'role_type' );
			$roles_selected = $request->get_param( 'roles_selected' );
			$label          = $request->get_param( 'label' );
			$post_types     = $request->get_param( 'post_types' );

			$is_exist = $this->repository->get_pcp_password( $password );

			if ( $is_exist || '' === $password ) {
				return wp_send_json(
					array(
						'result'  => array(),
						'success' => false,
					),
					400
				);
			}
			$roles = PPW_Pro_Constants::CAMPAIGN_TYPE['SHORTCODE'];
			if ( 'roles' === $role_type ) {
				$roles = $roles_selected;
			}

			try {
				$is_added = $this->repository->add_new_password(
					array(
						'password'          => $password,
						'created_time'      => time(),
						'campaign_app_type' => $roles,
						'usage_limit'       => $usage_limit ? $usage_limit : null,
						'expired_date'      => $expired_dates ? $this->get_expired_time_stamp( $expired_dates ) : null,
						'label'             => $label,
						'post_types'        => $post_types,
					)
				);

				if ( $is_added ) {
					return wp_send_json(
						array(
							'result'  => $is_added,
							'success' => true,
						),
						200
					);
				}
			} catch ( Exception $exception ) {
				return wp_send_json(
					array(
						'result'  => array(),
						'success' => false,
						'message' => $exception->getMessage(),
					),
					400
				);
			}

			return wp_send_json(
				array(
					'result'  => array(),
					'success' => false,
				),
				400
			);
		}

		/**
		 * Revamp data
		 *
		 * @param $list_password
		 *
		 * @return array
		 */
		public function revamp_data_for_all_passwords( $list_password ) {
			return array_map( function ( $data ) {
				$type  = $this->massage_role_of_password( $data->campaign_app_type );
				$label = apply_filters( 'ppwp_pro_password_label', $data->label, $data );

				return array(
					'id'           => $data->id,
					'label'        => $label,
					'password'     => $data->password,
					'is_activated' => $data->is_activated,
					'hits_count'   => $data->hits_count,
					'type'         => $type,
					'created_time' => $data->created_time,
					'expired_date' => $data->expired_date,
					'usage_limit'  => $data->usage_limit,
				);
			}, $list_password );
		}

		/**
		 * Massage data
		 *
		 * @param $roles
		 *
		 * @return string
		 */
		public function massage_role_of_password( $roles ) {
			if ( empty( $roles ) ) {
				return $roles;
			}
			if ( false === strpos( $roles, 'Role_' ) ) {
				$types = ppw_pro_get_map_types();
				return array_key_exists( $roles, $types ) ? $types[ $roles ] : '';
			}
			$roles_array = explode( ";", $roles );
			$roles_array = array_unique( $roles_array );
			if ( count( $roles_array ) === 1 ) {
				return "Role " . "(" . explode( '_', $roles )[1] . ")";
			}
			$results = array_map( function ( $role ) {
				return str_replace( PPW_Pro_Constants::CAMPAIGN_TYPE['ROLE'], "", $role );
			}, $roles_array );

			return "Role (" . implode( ", ", $results ) . ")";
		}

		/**
		 * Delete password
		 *
		 * @param $id
		 *
		 * @return array
		 */
		public function delete_password( $id ) {
			if ( $this->repository->delete_password_by_id( $id ) ) {
				return array(
					PPW_Pro_Constants::IS_ERROR => false,
					PPW_Pro_Constants::MESSAGE  => __( PPW_Pro_Constants::MESSAGE_DELETE_PASSWORD_SUCCESS, 'protect-password-page' ),
				);
			}

			return array(
				PPW_Pro_Constants::IS_ERROR => true,
				PPW_Pro_Constants::MESSAGE  => __( PPW_Pro_Constants::MESSAGE_DELETE_PASSWORD_ERROR, 'protect-password-page' ),
			);
		}

		/**
		 * Delete passwords selected
		 *
		 * @param array $selected_ids ID Password selected.
		 *
		 * @return array
		 */
		public function delete_selected_passwords( $selected_ids ) {
			if ( $this->repository->delete_selected_passwords( $selected_ids ) ) {
				return array(
					PPW_Pro_Constants::IS_ERROR => false,
					PPW_Pro_Constants::MESSAGE  => __( PPW_Pro_Constants::MESSAGE_DELETE_PASSWORDS_SUCCESS, 'protect-password-page' ),
				);
			}

			return array(
				PPW_Pro_Constants::IS_ERROR => true,
				PPW_Pro_Constants::MESSAGE  => __( PPW_Pro_Constants::MESSAGE_DELETE_PASSWORDS_ERROR, 'protect-password-page' ),
			);
		}

		/**
		 * Update password
		 *
		 * @param $id
		 * @param $data
		 *
		 * @return array
		 */
		public function update_data_password( $id, $data ) {
			if ( $this->repository->update_data_password_by_id( $id, $data ) ) {
				return array(
					PPW_Pro_Constants::IS_ERROR => false,
					PPW_Pro_Constants::MESSAGE  => __( PPW_Pro_Constants::MESSAGE_UPDATE_PASSWORD_SUCCESS, 'protect-password-page' ),
				);
			}

			return array(
				PPW_Pro_Constants::IS_ERROR => true,
				PPW_Pro_Constants::MESSAGE  => __( PPW_Pro_Constants::MESSAGE_UPDATE_PASSWORD_ERROR, 'protect-password-page' ),
			);
		}

		/**
		 * Check all posts is protected
		 *
		 * @param array $post_ids
		 *
		 * @return bool
		 */
		public function is_protected_all_posts( $post_ids ) {
			if ( empty( $post_ids ) ) {
				return false;
			}
			foreach ( $post_ids as $post_id ) {
				if ( ! $this->is_protected_content( $post_id ) ) {
					return false;
				}
			}

			return true;
		}

		/**
		 * @param $post_id
		 *
		 * @return mixed
		 * @throws Exception
		 */
		public function protect_post_by_password( $post_id ) {
			$data                  = $this->repository->get_password_info_by_post_id( $post_id );
			$password_is_activated = false;
			foreach ( (array) $data as $value ) {
				if ( $value->is_activated === '1' ) {
					$password_is_activated = true;
					break;
				}
			}

			if ( ! $password_is_activated ) {
				$this->auto_generate_pwd( $post_id );
			}

			return update_post_meta( $post_id, PPW_Pro_Constants::AUTO_GENERATE_PWD_META_DATA, 'true' );
		}

		/**
		 * Handle protect product
		 */
		public function handle_before_render_product() {
			$post    = get_post();
			$post_id = isset( $post->ID ) ? $post->ID : null;
			if ( is_null( $post_id ) || ! $this->is_protected_content( $post_id ) || $this->is_whitelist_roles( $post_id ) ) {
				return;
			}
			$passwords = $this->repository->get_password_by_post_id( $post_id );
			if ( $this->is_valid_cookie( $post_id, $passwords, PPW_Pro_Constants::GOLD_PASS_COOKIE ) ) {
				return;
			}

			echo ppw_core_render_login_form();

			add_filter( 'the_password_form', array( $this, 'not_render_wp_login_form' ) );

			$this->remove_action_woo();
		}

		public function remove_action_woo() {
			//Title and button add to cart
			$list_tag        = [
				'woocommerce_single_product_summary'        => 'woocommerce_single_product_summary',
				'woocommerce_before_single_product_summary' => 'woocommerce_before_single_product_summary',
				'woocommerce_after_single_product_summary'  => 'woocommerce_after_single_product_summary',
				'woocommerce_before_single_product'         => 'woocommerce_before_single_product',
				'woocommerce_single_variation'              => 'woocommerce_single_variation',
			];
			$list_action_woo = [
				[
					'tag'                => $list_tag['woocommerce_single_product_summary'],
					'function_to_remove' => 'woocommerce_template_single_title',
					'priority'           => 5,
				],
				[
					'tag'                => $list_tag['woocommerce_single_product_summary'],
					'function_to_remove' => 'woocommerce_template_single_rating',
					'priority'           => 10,
				],
				[
					'tag'                => $list_tag['woocommerce_single_product_summary'],
					'function_to_remove' => 'woocommerce_template_single_price',
					'priority'           => 10,
				],
				[
					'tag'                => $list_tag['woocommerce_single_product_summary'],
					'function_to_remove' => 'woocommerce_template_single_excerpt',
					'priority'           => 20,
				],
				[
					'tag'                => $list_tag['woocommerce_single_product_summary'],
					'function_to_remove' => 'woocommerce_template_single_add_to_cart',
					'priority'           => 30,
				],
				[
					'tag'                => $list_tag['woocommerce_single_product_summary'],
					'function_to_remove' => 'woocommerce_template_single_meta',
					'priority'           => 40,
				],
				[
					'tag'                => $list_tag['woocommerce_single_product_summary'],
					'function_to_remove' => 'woocommerce_template_single_sharing',
					'priority'           => 50,
				],
				[
					'tag'                => $list_tag['woocommerce_before_single_product_summary'],
					'function_to_remove' => 'woocommerce_show_product_images',
					'priority'           => 20,
				],
				[
					'tag'                => $list_tag['woocommerce_after_single_product_summary'],
					'function_to_remove' => 'woocommerce_output_product_data_tabs',
					'priority'           => 10,
				],
				[
					'tag'                => $list_tag['woocommerce_after_single_product_summary'],
					'function_to_remove' => 'woocommerce_upsell_display',
					'priority'           => 15,
				],
				[
					'tag'                => $list_tag['woocommerce_after_single_product_summary'],
					'function_to_remove' => 'woocommerce_output_related_products',
					'priority'           => 20,
				],
				[
					'tag'                => $list_tag['woocommerce_before_single_product'],
					'function_to_remove' => 'wc_print_notices',
					'priority'           => 10,
				],
				[
					'tag'                => $list_tag['woocommerce_before_single_product_summary'],
					'function_to_remove' => 'woocommerce_show_product_sale_flash',
					'priority'           => 10,
				],
				[
					'tag'                => $list_tag['woocommerce_before_single_product_summary'],
					'function_to_remove' => 'woocommerce_show_product_images',
					'priority'           => 20,
				],
				[
					'tag'                => $list_tag['woocommerce_single_variation'],
					'function_to_remove' => 'woocommerce_single_variation',
					'priority'           => 10,
				],
				[
					'tag'                => $list_tag['woocommerce_single_variation'],
					'function_to_remove' => 'woocommerce_single_variation_add_to_cart_button',
					'priority'           => 20,
				],
			];

			foreach ( $list_action_woo as $action ) {
				remove_action( $action['tag'], $action['function_to_remove'], $action['priority'] );
			}

			return $list_action_woo;
		}

		/**
		 * Not render password form
		 *
		 * @return string
		 */
		public function not_render_wp_login_form() {
			return '';
		}

		/**
		 * Add tag meta not index for post protected
		 */
		public function custom_header_for_file_protected() {
			$is_protect_entire_site = ppw_pro_check_is_protect_entire_site();
			if ( $is_protect_entire_site && ! ppw_pro_exclude_page() ) {
				?>
				<meta name="robots" content="noindex,follow"/>
				<?php
				return true;
			}
			ppw_pro_add_tag_meta_no_index_to_head();

			return false;
		}

		/**
		 * Handle post password required
		 *
		 * @param int  $post_id  The post ID.
		 * @param bool $required Whether the user needs to supply a password. True if password has not been.
		 *
		 * @return bool
		 */
		public function handle_post_password_required( $post_id, $required ) {
			/**
			 * Check type protection.
			 */
			if ( ! $this->check_protection( $post_id ) ) {
				return $required;
			}

			/**
			 * Check whitelist-roles in settings
			 */
			if ( $this->is_whitelist_roles( $post_id ) ) {
				return $this->unlock_content();
			}

			/**
			 * Get parent post id if post have parent-child page.
			 */
			$new_post_id = ppw_pro_get_post_id_follow_protect_child_page( $post_id );
			/**
			 * Check post or page is protected.
			 */
			if ( ! $this->is_protected_content( $new_post_id ) ) {
				return false === $this->is_content_unlocked(
						array(
							'is_post_protected'   => false,
							'is_content_unlocked' => true,
						),
						$post_id
					);
			}

			$passwords = $this->repository->get_password_by_post_id( $new_post_id );
			/**
			 * Whether it's valid cookie when user access a post/page.
			 */
			if ( $this->is_valid_cookie( $new_post_id, $passwords, PPW_Pro_Constants::GOLD_PASS_COOKIE ) ) {
				return $this->unlock_content();
			}

			/**
			 * User has never entered correct password or invalid cookie.
			 */
			return false === $this->is_content_unlocked(
					array(
						'is_post_protected'   => true,
						'is_content_unlocked' => false,
					),
					$post_id
				);
		}

		/**
		 * Declare the ppwp_post_password_required hook for another plugins or add-ons to consume.
		 *
		 * @param array $data    including
		 *                       required (bool) Whether the post is protected by current plugin.
		 *                       is_valid_password (bool) Whether user entered the corrected password. False if user has never entered password or password is incorrect.
		 *
		 * @param int   $post_id The current post's ID.
		 *
		 * @return bool
		 *      True if the filter's result having is valid password or the password is not required in any add-ons or plugins.
		 *      False if the password is required for the post or user has never entered the correct password.
		 */
		private function is_content_unlocked( $data, $post_id ) {
			$result = apply_filters( PPW_Pro_Constants::HOOK_PPWP_POST_PASSWORD_REQUIRED, $data, $post_id );

			// Support for old version that return boolean $required.
			if ( is_bool( $result ) ) {
				return false === $result;
			}

			if ( isset( $result['is_content_unlocked'] ) ) {
				return $result['is_content_unlocked'];
			}

			// If the filter result does not follow our rule then return the Pro required result.
			return $data['is_content_unlocked'];
		}

		/**
		 * Unlock the protected post content
		 *
		 * @return false
		 */
		private function unlock_content() {
			return false;
		}

		/**
		 * This function decode base64 to json object which have password
		 * Check password exist & user have white list role
		 *
		 * @param integer $post_id Post ID.
		 * @param string  $token   Token string.
		 *
		 * @return bool
		 */
		public function check_valid_bypass_url( $post_id, $token ) {
			$data_decrypted = ppw_encrypt_decrypt( 'decrypt', $token );
			if ( is_null( $data_decrypted ) || ! isset( $data_decrypted->password ) ) {
				return false;
			}
			$current_roles = ppw_core_get_current_role();

			return $this->check_password_is_valid( $data_decrypted->password, $post_id, $current_roles, PPW_Pro_Constants::BYPASS_TYPE );
		}

		/**
		 * Handle Bypass URL for page, post, custom post types
		 *
		 * Check existing single post of any post type
		 * Get permalink of current post
		 * If permalink is false then post is not exist
		 * If have permalink then get post id to check current post is protected
		 * Valid bypass url if post is protected
		 * Redirect with current permalink
		 *
		 * @since 1.1.2 Init function.
		 */
		public function handle_bypass_url() {
			if ( ! isset( $_GET[ PPW_Pro_Constants::BYPASS_PARAM ] ) || ! is_singular() ) {
				return;
			}
			global $post;
			$current_url = get_permalink( $post );
			if ( false === $current_url ) {
				return;
			}
			if ( $this->is_protected_content( $post->ID ) ) {
				// Decode bypass url to get password. If it is valid then save cookie.
				$this->check_valid_bypass_url( $post->ID, $_GET[ PPW_Pro_Constants::BYPASS_PARAM ] );
			}

			wp_safe_redirect( $current_url );
			exit();
		}

		/**
		 * Handle create multiple passwords
		 *
		 * @param $data
		 *
		 * @throws Exception
		 */
		public function handle_create_multiple_passwords( $data ) {
			// Validate before insert passwords to db
			$this->ppw_pro_validate_passwords( $data );

			$post_id       = $data['id'];
			$passwords     = $data[ PPW_Pro_Constants::MULTIPLE_PASSWORDS_KEY ];
			$password_type = PPW_Pro_Constants::CAMPAIGN_TYPE['AUTO'];
			if ( isset( $data['typeSelected'] ) && $data['typeSelected'] !== 'global' ) {
				$password_type = $this->handle_roles_selected( $data['roleSelected'] );
			}

			$data['ppwp_campaign_app_type'] = $password_type;
			foreach ( $passwords as $password ) {
				$this->insert_password( $post_id, $password, $data );
			}

			wp_send_json(
				array(
					'isError' => false,
					'message' => __( PPW_Pro_Constants::MESSAGE_CREATE_MULTIPLE_PASSWORDS_SUCCESS, 'protect-password-page' ),
				)
			);
		}

		/**
		 * Validate multiple passwords
		 *
		 * @param $data
		 */
		private function ppw_pro_validate_passwords( $data ) {
			if ( $this->is_bad_request( $data ) ) {
				wp_send_json(
					array(
						'isError' => true,
						'message' => __( PPW_Constants::BAD_REQUEST_MESSAGE, 'protect-password-page' ),
					),
					400
				);
			}

			if ( $this->is_password_duplicated( $data ) ) {
				wp_send_json(
					array(
						'isError' => true,
						'message' => __( PPW_Pro_Constants::MESSAGE_DUPLICATE_MULTIPLE_PASSWORD, 'protect-password-page' ),
					),
					400
				);
			}
		}

		/**
		 * Check data is valid
		 *
		 * @param $data
		 *
		 * @return bool
		 */
		private function is_bad_request( $data ) {
			if ( ! isset( $data['id'] ) || ! is_string( $data['id'] ) || ! isset( $data[ PPW_Pro_Constants::MULTIPLE_PASSWORDS_KEY ] ) || ! is_array( $data[ PPW_Pro_Constants::MULTIPLE_PASSWORDS_KEY ] ) ) {
				return true;
			}

			$passwords = $data[ PPW_Pro_Constants::MULTIPLE_PASSWORDS_KEY ];
			if ( ppw_array_is_empty( ppw_array_filter( $passwords ) ) ) {
				return true;
			}

			foreach ( $passwords as $password ) {
				if ( strpos( $password, ' ' ) !== false ) {
					return true;
				}
			}

			return false;
		}

		/**
		 * Check duplicate passwords
		 *
		 * @param $data
		 *
		 * @return bool
		 */
		private function is_password_duplicated( $data ) {
			$passwords     = $data[ PPW_Pro_Constants::MULTIPLE_PASSWORDS_KEY ];
			$post_id       = $data['id'];
			$repository    = new PPW_Pro_Repository();
			$old_passwords = $repository->get_passwords_by_post_id( $post_id );
			foreach ( $passwords as $password ) {
				if ( in_array( $password, $old_passwords, true ) ) {
					return true;
				}
			}

			return false;
		}

		/**
		 *  Is handle search and replace of PPWP.
		 *
		 * @param array $conditions Conditions to check plugin.
		 * @param array $data       Data from PDA Gold.
		 *
		 * @return array Conditions handle search and replace for password.
		 */
		public function is_handle_search_and_replace( $conditions, $data ) {
			$post_id = $data['post_id'];

			$parent_id = ppw_pro_get_post_id_follow_protect_child_page( $post_id );

			$result                                         = $this->should_replace_url( $post_id, $parent_id );
			$conditions['ppwp_is_using_search_and_replace'] = apply_filters( PPW_Pro_Constants::HOOK_BEFORE_HANDLE_SEARCH_REPLACE, $result, $parent_id );

			return $conditions;
		}

		/**
		 * Add action protect/unprotect
		 *
		 * @param array    $actions An array of row action links.
		 * @param stdClass $post    The post object.
		 *
		 * @return array
		 */
		public function add_row_action( $actions, $post ) {
			$protect_child_pages = ppw_core_get_setting_type_bool( PPW_Pro_Constants::WPP_AUTO_PROTECT_ALL_CHILD_PAGES );
			if ( $protect_child_pages ) {
				if ( 0 === $post->post_parent ) {
					$actions['ppwp_protect'] = $this->generate_button( $post );
				}
			} else {
				$actions['ppwp_protect'] = $this->generate_button( $post );
			}

			return $actions;
		}

		/**
		 * Generate button for row action
		 *
		 * @param stdClass $post The post object.
		 *
		 * @return string
		 */
		public function generate_button( $post ) {
			$post_id           = $post->ID;
			$post_type         = 'page'; // TODO: improvement by getting the post type. Need to improve in popup also.
			$is_protected      = $this->is_protected_content( $post_id );
			$btn_label         = $is_protected ? PPW_Pro_Constants::UNPROTECT_LABEL : PPW_Pro_Constants::PROTECT_LABEL;
			$title             = $is_protected ? 'Unprotect this ' . $post_type : 'Password protect this ' . $post_type;
			$protection_status = $is_protected ? PPW_Pro_Constants::PROTECTION_STATUS['unprotect'] : PPW_Pro_Constants::PROTECTION_STATUS['protect'];

			return '<a style="cursor: pointer" data-ppw-status="' . $protection_status . '" onclick="ppwpRowAction.handleOnClickRowAction(' . $post_id . ')" id="ppw-protect-post_' . $post_id . '" class="ppwp-protect-action" title="' . $title . '">' . $btn_label . '</a>';
		}

		/**
		 * Update post status request from row action
		 *
		 * @param array $request Request from row action.
		 *
		 * @throws Exception
		 */
		public function update_post_status( $request ) {
			if ( ! isset( $request['postId'] ) || ! isset( $request['status'] ) ) {
				send_json_data_error( __( 'Our server cannot understand the data request!', 'password-protect-page' ) );
			}
			$post_id       = $request['postId'];
			$client_status = (int) $request['status'];
			if ( ! in_array( $client_status, array_values( PPW_Pro_Constants::PROTECTION_STATUS ), true ) ) {
				send_json_data_error( __( 'Our server cannot understand the data request!', 'password-protect-page' ) );
			}

			$protect_child_pages = ppw_core_get_setting_type_bool( PPW_Pro_Constants::WPP_AUTO_PROTECT_ALL_CHILD_PAGES );
			// List all id child page follow feature "Password Protect Child Pages".
			$list_child_page = array();
			if ( $protect_child_pages ) {
				$list_child_page = $this->get_all_id_child_page( $post_id );
			}

			$server_status  = $client_status;
			$message        = __( 'Oops! Something went wrong. Please reload the page and try again.', 'password-protect-page' );
			$status_request = 400;
			if ( PPW_Pro_Constants::PROTECTION_STATUS['protect'] === $client_status ) {
				if ( ! $this->repository->is_protected_item( $post_id ) ) {
					$this->protect_page_post( $post_id );
					$this->check_condition_before_create_new_password( array( 'id' => $post_id ) );
					$server_status  = PPW_Pro_Constants::PROTECTION_STATUS['unprotect'];
					$message        = __( 'Great! You\'ve successfully protected this page.', 'password-protect-page' );
					$status_request = 200;
				}
			} else {
				if ( $this->repository->is_protected_item( $post_id ) ) {
					$this->un_protect_page_post( $post_id );
					$server_status  = PPW_Pro_Constants::PROTECTION_STATUS['protect'];
					$message        = __( 'Great! You\'ve successfully unprotected this page.', 'password-protect-page' );
					$status_request = 200;
				}
			}

			wp_send_json(
				array(
					'is_error'         => 200 === $status_request ? false : true,
					'list_child_pages' => $list_child_page,
					'server_status'    => $server_status,
					'message'          => $message,
				),
				$status_request
			);
			wp_die();
		}

		/**
		 * Handle unlock PDA file for master password.
		 *
		 * @param bool $valid   Is valid to return the protected file.
		 * @param int  $post_id The post ID.
		 *
		 * @return bool
		 */
		public function handle_unlock_pda_file( $valid, $post_id ) {
			if ( true === $valid ) {
				return $valid;
			}

			return $this->is_valid_cookie_master_password( $post_id );
		}

		/**
		 * Check valid cookie of master password to handle search and replace
		 *
		 * @param bool $result  Result to know has handle search and replace.
		 * @param int  $post_id The post ID.
		 *
		 * @return bool
		 */
		public function is_search_replace_master_password( $result, $post_id ) {
			if ( true === $result ) {
				return $result;
			}

			return $this->is_valid_cookie_master_password( $post_id );
		}

		/**
		 * Check cookie master password.
		 *
		 * @param int $post_id The post ID.
		 *
		 * @return bool
		 */
		public function is_valid_cookie_master_password( $post_id ) {
			if ( method_exists( 'PPW_Password_Services', 'check_master_password_is_valid' ) ) {

				return $this->free_password_service->check_master_password_is_valid( $post_id );
			}

			return false;
		}

		/**
		 * Check whether the entire side password is valid.
		 *
		 * @param string $pwd The password.
		 *
		 * @return bool|string
		 */
		public function is_valid_entire_site_password( $pwd ) {
			$entire_site_conf = apply_filters( 'ppwp_sitewide_passwords', false );
			if ( ! $entire_site_conf ) {
				$entire_site_conf = ppw_pro_get_setting_entire_site_type_array( PPW_Pro_Constants::PPW_PASSWORD_FOR_ENTIRE_SITE );
			}
			$passwords = ppw_pro_get_string_key_in_array( $entire_site_conf );

			$setting_service = new PPW_Pro_Settings_Services();
			if ( $setting_service->entire_site_is_valid_password( $pwd, $passwords, $entire_site_conf ) ) {
				do_action( 'ppwp_sitewide_handle_after_valid_password', $pwd, $passwords );

				return $setting_service->get_entire_site_redirect_url( $pwd, $entire_site_conf );
			}

			return false;
		}

		/**
		 * Check whether the entire site protection enabled.
		 *
		 * @param $passwords The entire site passwords.
		 *
		 * @return bool
		 */
		public function is_entire_site_protection_enabled( $passwords ) {
			$setting_service = new PPW_Pro_Settings_Services();

			return $setting_service->is_enabled_entire_site_protection( $passwords );
		}

		/**
		 * Check whether the entire site cookie is valid.
		 *
		 * @param $passwords The entire site passwords.
		 *
		 * @return bool
		 */
		public function is_valid_entire_site_cookie( $passwords ) {
			$setting_service = new PPW_Pro_Settings_Services();

			return $setting_service->validate_auth_cookie_entire_site( $passwords );
		}

		/**
		 * Get entire site passwords.
		 *
		 * @return array
		 */
		public function get_entire_site_passwords() {
			$config = apply_filters( 'ppwp_sitewide_passwords', false );
			if ( ! $config ) {
				$config = ppw_pro_get_setting_entire_site_type_array( PPW_Pro_Constants::PPW_PASSWORD_FOR_ENTIRE_SITE );
			}
			$passwords = ppw_pro_get_string_key_in_array( $config );

			return array(
				'config'    => $config,
				'passwords' => $passwords,
			);
		}

		/**
		 * Get information of a password to check valid.
		 *
		 * @param string $password Password.
		 * @param int    $post_id Post ID
		 * @param array  $current_roles Current user roles.
		 *
		 * @return null|object
		 */
		private function get_password_info( $password, $post_id, array $current_roles ) {
			$password_info = apply_filters(
				'ppwp_pro_get_valid_password',
				false,
				array(
					'password'      => $password,
					'post_id'       => $post_id,
					'current_roles' => $current_roles
				)
			);
			if ( false === $password_info ) {
				$password_info = $this->repository->get_password_info_by_password_and_post_id( $password, $post_id );
			}

			return $password_info;
		}
	}
}
