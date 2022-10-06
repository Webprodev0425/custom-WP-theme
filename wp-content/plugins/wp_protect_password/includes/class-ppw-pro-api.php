<?php
if ( ! class_exists( "PPW_Pro_Api" ) ) {
	/**
	 * Class WP_Protect_Password_Api
	 */
	class PPW_Pro_Api {
		/**
		 * @var PPW_Pro_Password_Services
		 */
		private $service;

		/**
		 * PPW_Pro_Api constructor.
		 */
		public function __construct() {
			$this->service = new PPW_Pro_Password_Services();
		}

		/**
		 * Register rest routes
		 */
		public function register_rest_routes() {
			#region API Debug
			register_rest_route( 'wppp/v1', '/debug', array(
				'methods'  => 'GET',
				'callback' => array( $this, 'debug' ),
			) );
			#endregion

			#region API Meta Box
			register_rest_route( 'wppp/v1', '/get-data-password/(?P<id>\d+)', array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_password' ),
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			) );

			register_rest_route( 'wppp/v1', '/add-new-password/(?P<id>\d+)', array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'add_new_password' ),
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			) );

			register_rest_route( 'wppp/v1', '/create-multiple-passwords/(?P<id>\d+)', array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'create_multiple_passwords' ),
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			) );

			register_rest_route( 'wppp/v1', '/file-is-protect/(?P<id>\d+)', array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'file_is_protect' ),
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			) );

			register_rest_route( 'wppp/v1', '/protect-this-file/(?P<id>\d+)', array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'protect_this_post' ),
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			) );

			register_rest_route( 'wppp/v1', '/un-protect-this-file/(?P<id>\d+)', array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'un_protect_this_post' ),
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			) );
			#endregion

			register_rest_route( 'wppp/v1', '/get-all-password/(?P<id>\d+)', array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_all_password' ),
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			) );

			register_rest_route( 'wppp/v1', '/pcp-passwords', array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_pcp_passwords' ),
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			) );

			register_rest_route( 'wppp/v1', '/pcp-passwords', array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'add_new_pcp_password' ),
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			) );

			register_rest_route( 'wppp/v1', '/delete-password/(?P<id>\d+)', array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'delete_password' ),
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			) );

			register_rest_route( 'wppp/v1', '/delete-selected-passwords', array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'delete_selected_passwords' ),
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			) );

			register_rest_route( 'wppp/v1', '/update-data-password/(?P<id>\d+)', array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'update_data_password' ),
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			) );

			register_rest_route( 'wppp/v1', '/migrate-default-password', array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'ppwp_migrate_default_password' ),
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			) );

			register_rest_route( 'wppp/v1', '/pcp-settings', array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'update_pcp_settings' ),
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			) );

			register_rest_route( 'wppp/v1', '/pcp-settings', array(
				'methods'  => 'GET',
				'callback' => array( $this, 'get_pcp_settings' ),
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			) );

			register_rest_route(
				'wppp/v1',
				'/side-wide',
				array(
					'methods'  => 'POST',
					'callback' => array( $this, 'handle_side_wide_pwd' ),
				)
			);
		}

		/**
		 * API DEBUG
		 *
		 * @return array
		 */
		public function debug() {
			delete_option( PPW_Pro_Constants::LICENSE_OPTIONS );
			delete_option( PPW_Pro_Constants::LICENSE_KEY );

			return array(
				'result' => post_password_required( 2356 ),
			);
		}

		/**
		 * Get password data
		 *
		 * @param $data
		 *
		 * @return mixed
		 */
		public function get_password( $data ) {
			return $this->service->get_password_data( $data['id'] );
		}

		/**
		 * Add new password
		 *
		 * @param array $data
		 *
		 * @return mixed|WP_Error
		 * @throws Exception
		 */
		public function add_new_password( $data ) {
			$result       = $this->service->auto_generate_pwd( $data['id'], $data );
			$new_password = isset( $result[ PPW_Pro_Constants::VALUE ] ) ? $result[ PPW_Pro_Constants::VALUE ] : '';

			return ppw_pro_return_json_api( $result['is_error'], $result['message'], $new_password );
		}

		/**
		 * Create multiple passwords
		 *
		 * @param $data
		 *
		 * @throws Exception
		 */
		public function create_multiple_passwords( $data ) {
			$this->service->handle_create_multiple_passwords( $data );
		}

		/**
		 * Get status file and data related
		 *
		 * @param $data
		 *
		 * @return array
		 */
		public function file_is_protect( $data ) {
			return $this->service->get_status_post_and_data_related( $data['id'] );
		}

		/**
		 * Protect post
		 *
		 * @param $data
		 *
		 * @throws Exception
		 */
		function protect_this_post( $data ) {
			$this->service->protect_page_post( $data['id'] );
			$this->service->check_condition_before_create_new_password( $data );
		}

		/**
		 * Unprotect post
		 *
		 * @param $data
		 */
		function un_protect_this_post( $data ) {
			$this->service->un_protect_page_post( $data['id'] );
		}

		/**
		 * List all password
		 *
		 * @param $data
		 *
		 * @return array
		 */
		public function get_all_password( $data ) {
			return $this->service->list_all_password_by_post_id( $data['id'] );
		}

		/**
		 * Delete password
		 *
		 * @param $data
		 *
		 * @return mixed
		 */
		public function delete_password( $data ) {
			$result = $this->service->delete_password( $data['id'] );

			return ppw_pro_return_json_api( $result['is_error'], $result['message'] );
		}

		/**
		 * Delete passwords
		 *
		 * @param array $data Data from request.
		 *
		 * @return mixed
		 */
		public function delete_selected_passwords( $data ) {
			$result = $this->service->delete_selected_passwords( $data['selected_ids'] );

			return ppw_pro_return_json_api( $result['is_error'], $result['message'] );
		}

		/**
		 * @param $data
		 *
		 * @return mixed
		 */
		public function update_data_password( $data ) {
			$result = $this->service->update_data_password( $data['id'], $data['info'] );

			return ppw_pro_return_json_api( $result['is_error'], $result['message'] );
		}

		public function ppwp_migrate_default_password() {
			$post_type_selected = wpp_get_settings_value( PPW_Pro_Constants::WPP_WHITELIST_COLUMN_PROTECTIONS );
			$posts              = ppwp_get_posts_password_protected_by_wp( $post_type_selected );

			return ppwp_migrate_default_password_to_gold( $posts );
		}

		/**
		 * Get pcp settings
		 *
		 * @param $data
		 *
		 * @return mixed
		 */
		public function get_pcp_settings() {
			return array (
				'unlock_multiple_protected_section' => ppw_pro_get_pcp_settings_boolean( PPW_Pro_Constants::WPP_UNLOCK_ALL_PROTECTED_SECTIONS )
			);
		}

		public function update_pcp_settings( $data ) {
			$unlock_multiple_protected_section = $data['unlock_multiple_protected_section'];
			ppw_pro_save_pcp_settings_value( PPW_Pro_Constants::WPP_UNLOCK_ALL_PROTECTED_SECTIONS, $unlock_multiple_protected_section );
			wp_send_json( array( 'is_success' => true ), 200 );
		}

		/**
		 * List all pcp passwords
		 *
		 * @return array
		 */
		public function get_pcp_passwords() {
			wp_send_json(
				array(
					'result'  => $this->service->get_pcp_passwords(),
					'success' => true,
				),
				200
			);
		}

		/**
		 * Add new variable.
		 *
		 * @param WP_REST_Request $request The REST API request to process.
		 *
		 * @return WP_REST_Response The REST response.
		 * @throws Exception Exception.
		 */
		public function add_new_pcp_password( $request ) {
			return $this->service->add_new_pcp_password( $request );
		}

		/**
		 * Handle side wide pwd from short code.
		 *
		 * @param array $request The post data request including password ($pwd).
		 *
		 * @return mixed
		 */
		public function handle_side_wide_pwd( $request ) {
			$pwd    = $request->get_param( 'pwd' );
			$result = $this->service->is_valid_entire_site_password( $pwd );
			if ( false === $result ) {
				return wp_send_json(
					array(
						'result'  => false,
						'success' => false,
					),
					200
				);
			}

			return wp_send_json(
				array(
					'result'  => array(
						'redirect_url' => $result,
					),
					'success' => true,
				),
				200
			);
		}
	}
}
