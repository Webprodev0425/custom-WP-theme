<?php
if ( ! class_exists( 'PPW_Pro_Repository' ) ) {
	/**
	 * Connect database
	 *
	 * Class PPW_Pro_Repository
	 */
	class PPW_Pro_Repository {

		/**
		 * Password Protect WordPress Pro Table
		 *
		 * @var string
		 */
		private $table_name;

		/**
		 * @var object
		 */
		private $wpdb;

		public function __construct() {
			global $wpdb;
			$this->wpdb       = $wpdb;
			$this->table_name = $this->wpdb->prefix . PPW_Pro_Constants::TBL_NAME;
		}

		/**
		 * Insert new data
		 *
		 * @param array $data Information password.
		 *
		 * @return int|False
		 * @throws Exception
		 */
		public function insert( $data ) {
			if ( empty( $data['created_time'] ) ) {
				$now                  = new DateTime();
				$data['created_time'] = $now->getTimestamp();
			}

			return $this->wpdb->insert( $this->table_name, $data );
		}

		/**
		 * Update page/post status
		 *
		 * @param string|int $post_id The post ID.
		 * @param string     $status  true|false.
		 */
		public function update_page_post_status( $post_id, $status ) {
			// Clear cache before update status post.
			ppw_pro_clear_cache_by_id( $post_id );

			update_post_meta( $post_id, PPW_Pro_Constants::AUTO_GENERATE_PWD_META_DATA, $status );
		}

		/**
		 * Check page/post is protected
		 *
		 * @param $post_id
		 *
		 * @return bool
		 */
		public function is_protected_item( $post_id ) {
			return get_post_meta( $post_id, PPW_Pro_Constants::AUTO_GENERATE_PWD_META_DATA, true ) === "true";
		}

		/**
		 * Get all post id by password type
		 *
		 * @param $type
		 *
		 * @return mixed
		 */
		public function get_all_post_id_by_type( $type ) {
			return $this->wpdb->get_results( $this->wpdb->prepare( "SELECT * FROM $this->table_name WHERE campaign_app_type = %s", $type ) );
		}

		/**
		 * Get password by post id, password and not type
		 *
		 * @param $password
		 * @param $post_id
		 * @param $type
		 *
		 * @return mixed
		 */
		public function get_password_info( $password, $post_id, $type ) {
			return $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM $this->table_name WHERE BINARY password = %s AND post_id = %s AND campaign_app_type != %s", $password, $post_id, $type ) );
		}

		/**
		 * Update password for feature protect private pages
		 *
		 * @param $all_page_id
		 * @param $password
		 *
		 * @throws Exception
		 */
		function update_password_for_feature_protect_private_pages( $all_page_id, $password ) {
			$this->insert_or_update_password_type_is_common( $all_page_id, $password );
			$this->delete_page_post_un_selected( $all_page_id );
		}

		/**
		 * Delete page or post user un selected in feature protect private pages
		 *
		 * @param $all_page_id
		 */
		function delete_page_post_un_selected( $all_page_id ) {
			$all_post_selected = $this->get_all_post_id_by_type( PPW_Pro_Constants::CAMPAIGN_TYPE['COMMON'] );

			$selected_posts = array_map( function ( $post ) {
				return $post->post_id;
			}, $all_post_selected );

			$post_id_remove = array_diff( $selected_posts, $all_page_id );
			foreach ( $post_id_remove as $post_id ) {
				$this->wpdb->delete( $this->table_name, array(
					'post_id'           => $post_id,
					'campaign_app_type' => 'Common'
				) );
			}
		}

		/**
		 * Check condition before insert or update password type is common
		 *
		 * @param $all_page_id
		 * @param $password
		 *
		 * @throws Exception
		 */
		function insert_or_update_password_type_is_common( $all_page_id, $password ) {
			foreach ( $all_page_id as $page_id ) {
				$advance_password = $this->get_password_by_post_id_and_type( $page_id, PPW_Pro_Constants::CAMPAIGN_TYPE['COMMON'] );
				// Check before insert or update password
				if ( is_null( $advance_password ) ) {
					$result = $this->insert(
						array(
							'post_id'           => $page_id,
							'password'          => $password,
							'campaign_app_type' => PPW_Pro_Constants::CAMPAIGN_TYPE['COMMON'],
						)
					);
				} else {
					$result = $this->wpdb->update(
						$this->table_name,
						array( 'password' => $password ),
						array( 'id' => $advance_password->id )
					);
				}

				if ( false === $result ) {
					send_json_data_error( __( PPW_Constants::BAD_REQUEST_MESSAGE, 'password-protect-page' ) );
				}

				// Check and protect page/post
				$password_services = new PPW_Pro_Password_Services();
				if ( ! $password_services->is_protected_content( $page_id ) ) {
					$this->update_page_post_status( $page_id, 'true' );
				}
			}
		}

		/**
		 * Get password by post id and type
		 *
		 * @param $post_id
		 * @param $type
		 *
		 * @return mixed
		 */
		public function get_password_by_post_id_and_type( $post_id, $type ) {
			return $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM $this->table_name WHERE post_id = %s AND campaign_app_type = %s", $post_id, $type ) );
		}

		/**
		 * Delete all password type is common
		 */
		public function delete_all_password_type_is_common() {
			$all_post_selected = $this->get_all_post_id_by_type( PPW_Pro_Constants::CAMPAIGN_TYPE['COMMON'] );
			foreach ( $all_post_selected as $post ) {
				$this->wpdb->delete(
					$this->table_name,
					array(
						'id' => $post->id
					)
				);
			}
		}

		/**
		 * Get password and password type by post id and campaign type
		 *
		 * @param $post_id
		 *
		 * @return mixed
		 */
		public function get_type_and_password_by_post_id_and_campaign_type( $post_id ) {
			$advance_password = $this->wpdb->get_results( $this->wpdb->prepare( "SELECT campaign_app_type, password FROM $this->table_name WHERE post_id = %s AND is_activated = 1 AND (expired_date IS NULL OR expired_date > UNIX_TIMESTAMP()) AND (usage_limit IS NULL OR hits_count < usage_limit) AND (campaign_app_type LIKE %s OR campaign_app_type = %s OR campaign_app_type = %s OR campaign_app_type = %s) ", $post_id, PPW_Pro_Constants::CAMPAIGN_TYPE['ROLE'] . '%', PPW_Pro_Constants::CAMPAIGN_TYPE['AUTO'], PPW_Pro_Constants::CAMPAIGN_TYPE['DEFAULT'], PPW_Pro_Constants::CAMPAIGN_TYPE['COMMON'] ) );

			return $advance_password;
		}

		/**
		 * Get all passwords by post_id
		 *
		 * @param $post_id
		 *
		 * @return mixed
		 */
		public function get_all_password_by_post_id( $post_id ) {
			$query_string = $this->wpdb->prepare( "SELECT * FROM $this->table_name WHERE post_id = %s AND (expired_date IS NULL OR expired_date > UNIX_TIMESTAMP()) AND (usage_limit IS NULL OR hits_count < usage_limit) AND is_activated = 1", $post_id );

			return $this->wpdb->get_row( $query_string );
		}

		/**
		 * Get advance password by password and post id
		 *
		 * @param $password
		 * @param $post_id
		 *
		 * @return mixed
		 */
		public function get_advance_password_by_password_and_post_id( $password, $post_id ) {
			$advance_password = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM $this->table_name WHERE BINARY password = %s AND post_id = %s", $password, $post_id ) );

			return $advance_password;
		}

		/**
		 * get password by contact id
		 *
		 * @param $contact_id
		 *
		 * @return mixed
		 */
		public function get_password_by_contact_id( $contact_id ) {
			$query_string = $this->wpdb->prepare( "SELECT password FROM $this->table_name WHERE contact_id = %d and is_activated = 1", $contact_id );

			return $this->wpdb->get_row( $query_string );
		}

		/**
		 * update password by contact id
		 *
		 * @param $contact_id
		 * @param $data
		 *
		 * @return mixed
		 */
		public function update_password_by_contact_id( $contact_id, $data ) {
			return $this->wpdb->update( $this->table_name, $data, array(
				'contact_id' => $contact_id
			) );
		}

		/**
		 * Get password by post_id
		 *
		 * @param string $post_id Post ID.
		 *
		 * @return array Array passwords.
		 */
		public function get_password_by_post_id( $post_id ) {
			$query_string = $this->wpdb->prepare( "SELECT password FROM $this->table_name WHERE post_id = %s and is_activated = 1", $post_id );
			$passwords    = $this->wpdb->get_results( $query_string );

			return array_map(
				function ( $pass ) {
					return $pass->password;
				},
				$passwords
			);
		}

		/**
		 * Get password info by password and post id
		 *
		 * @param string     $password the password.
		 * @param string|int $post_id  the post id.
		 * @return mixed
		 */
		public function get_password_info_by_password_and_post_id( $password, $post_id ) {
			$query_string = $this->wpdb->prepare( "SELECT * FROM $this->table_name WHERE BINARY password = %s and is_activated = 1 and (expired_date is NULL OR expired_date > UNIX_TIMESTAMP()) and (usage_limit is NULL OR hits_count < usage_limit) and post_id = %s", $password, $post_id );

			return $this->wpdb->get_row( $query_string );
		}

		/**
		 * get advance password by password
		 *
		 * @param $password
		 * @param $post_id
		 *
		 * @return mixed
		 */
		public function get_advance_password_by_password( $password, $post_id ) {
			$query_string = $this->wpdb->prepare( "SELECT * FROM $this->table_name WHERE BINARY password = %s and post_id = %s and is_activated = 1", $password, $post_id );

			return $this->wpdb->get_row( $query_string );
		}

		public function update_data_password_by_id( $id, $data ) {
			try {
				$this->wpdb->update( $this->table_name, $data, array(
					'ID' => $id
				) );

				return true;
			} catch ( Exception $e ) {

				return false;
			}
		}

		/**
		 * Get all id child page
		 *
		 * @param $page_id
		 *
		 * @return array
		 */
		function get_all_id_child_page( $page_id ) {
			$my_wp_query       = new WP_Query();
			$all_wp_pages      = $my_wp_query->query( array( 'post_type' => 'page', 'posts_per_page' => '-1' ) );
			$all_page_children = get_page_children( $page_id, $all_wp_pages );
			$arr_page_id       = array_map( function ( $page_child ) {
				return $page_child->ID;
			}, $all_page_children );

			return $arr_page_id;
		}

		/**
		 * Get password info by post id
		 *
		 * @param $post_id
		 *
		 * @return mixed
		 */
		public function get_password_info_by_post_id( $post_id ) {
			$query_string = $this->wpdb->prepare( "SELECT * FROM $this->table_name WHERE post_id = %s", $post_id );

			return $this->wpdb->get_results( $query_string );
		}

		/**
		 * Delete password by id
		 *
		 * @param $id
		 *
		 * @return mixed
		 */
		function delete_password_by_id( $id ) {
			return $this->wpdb->delete(
				$this->table_name,
				array(
					'ID' => $id
				)
			);
		}

		/**
		 * Delete selected passwords by id
		 * String will convert to int
		 *
		 * @param array $selected_ids ID Passwords selected.
		 *
		 * @return mixed
		 */
		public function delete_selected_passwords( $selected_ids ) {
			$selected_ids = implode( ',', array_map( 'absint', $selected_ids ) );

			return $this->wpdb->query( "DELETE FROM $this->table_name WHERE ID IN($selected_ids)" );
		}

		/**
		 * Display all allowed password type.
		 */
		private function get_allowed_password_type() {
			$default_type        = PPW_Pro_Constants::CAMPAIGN_TYPE['DEFAULT'];
			$common_type         = PPW_Pro_Constants::CAMPAIGN_TYPE['COMMON'];
			$auto_type           = PPW_Pro_Constants::CAMPAIGN_TYPE['AUTO'];
			$emai_marketing_type = PPW_Pro_Constants::CAMPAIGN_TYPE['ACTIVE_CAMPAIGN'];

			$types = array(
				"'$default_type'",
				"'$common_type'",
				"'$auto_type'",
				"'$emai_marketing_type'"
			);

			$types = apply_filters( 'ppwp_allowed_password_type', $types );

			return implode( ', ', $types );
		}

		/**
		 * Get all password by campaign app type
		 *
		 * @return mixed
		 */
		public function get_all_password_by_campaign_app_type() {
			$allowed_types = $this->get_allowed_password_type();
			$role_type     = PPW_Pro_Constants::CAMPAIGN_TYPE['ROLE'];

			return $this->wpdb->get_results( "SELECT * FROM $this->table_name WHERE campaign_app_type LIKE '$role_type%' OR campaign_app_type IN ($allowed_types)" );
		}

		/**
		 * @return mixed
		 */
		public function get_all_protected_posts() {
			$table_name = $this->wpdb->prefix . 'postmeta';
			// Check Exist
			$result = $this->wpdb->get_results( "SELECT DISTINCT post_id FROM $table_name WHERE meta_key = '" . PPW_Pro_Constants::AUTO_GENERATE_PWD_META_DATA . "' AND meta_value = 'true' " );

			return $result;

		}

		/**
		 * Delete password by post id and is default
		 *
		 * @param $post_id
		 * @param $is_default
		 *
		 * @return mixed
		 */
		public function delete_password_by_post_id_and_is_default( $post_id, $is_default ) {
			return $this->wpdb->delete( $this->table_name,
				array(
					'post_id'    => $post_id,
					'is_default' => $is_default
				)
			);
		}

		/**
		 * Delete password by post id and password
		 *
		 * @param $post_id
		 * @param $password
		 *
		 * @return mixed
		 */
		function delete_password_by_post_id_and_password( $post_id, $password ) {
			return $this->wpdb->delete( $this->table_name,
				array(
					'post_id'  => $post_id,
					'password' => $password
				)
			);
		}

		/**
		 * Get passwords by post_id
		 *
		 * @param $post_id
		 *
		 * @return array
		 */
		public function get_passwords_by_post_id( $post_id ) {
			$query_string = $this->wpdb->prepare( "SELECT password FROM $this->table_name WHERE post_id = %s", $post_id );

			return $this->wpdb->get_col( $query_string );
		}

		/**
		 * Get activate passwords by id and types.
		 * String will convert to int
		 *
		 * @param array  $ids  ID Passwords selected.
		 * @param string $type Type password.
		 *
		 * @return array|object|null Database query results
		 */
		public function fetch_pcp_passwords_by_ids_and_type( $ids, $type ) {
			$ids_str = implode( ',', array_map( 'absint', $ids ) );

			$query = $this->wpdb->prepare( "
				SELECT * FROM $this->table_name 
				WHERE ID IN($ids_str)
				AND campaign_app_type = %s
				AND (expired_date IS NULL OR expired_date > UNIX_TIMESTAMP()) 
				AND (usage_limit IS NULL OR hits_count < usage_limit) 
				AND is_activated = 1",
				$type );

			return $this->wpdb->get_results( $query, ARRAY_A );
		}

		/**
		 * Get activate passwords by id and types.
		 * String will convert to int
		 *
		 * @param string $type Type password.
		 *
		 * @return array|object|null Database query results
		 */
		public function fetch_passwords_by_type( $type ) {
			$query = $this->wpdb->prepare( "SELECT * FROM $this->table_name where campaign_app_type LIKE %s", $type . '%' );

			return $this->wpdb->get_results( $query );
		}

		/**
		 * Fetch all activate PCP passwords by global and roles
		 *
		 * @param array $ids              Array passwords ids.
		 * @param bool  $is_check_expired Is check expired (date, count).
		 *
		 * @return array|object|null Database query results
		 */
		public function fetch_activate_pcp_passwords_by_ids( $ids, $roles, $is_check_expired = false ) {
			$like_where    = $this->generate_where_like_for_roles( $roles );
			$expired_where = '';
			if ( $is_check_expired ) {
				$expired_where = " AND (expired_date IS NULL OR expired_date > UNIX_TIMESTAMP()) AND (usage_limit IS NULL OR hits_count < usage_limit) ";
			}
			$ids_str = implode( ',', array_map( 'absint', $ids ) );
			$query   = $this->wpdb->prepare( "
				SELECT * FROM $this->table_name 
				WHERE ID IN($ids_str)
				{$expired_where}
				AND is_activated = 1
				AND ( campaign_app_type = %s {$like_where})",
				PPW_Pro_Constants::CAMPAIGN_TYPE['SHORTCODE']
			);

			return $this->wpdb->get_results( $query );
		}

		/**
		 * Generate query to get password roles type in DB
		 *
		 * @param array $roles User roles.
		 *
		 * @return string
		 */
		private function generate_where_like_for_roles( $roles ) {
			$where_like_string = '';
			$pcp_role          = PPW_Pro_Constants::CAMPAIGN_TYPE['SHORTCODE_ROLE'];
			if ( is_array( $roles ) && count( $roles ) > 0 ) {
				/**
				 * Generate roles to string with like condition.
				 * Example:
				 *    ['editor,'admin'] to ' OR campaign_app_type LIKE '%editor% OR campaign_app_type LIKE '%admin%'
				 */
				$where_like_string = array_reduce(
					$roles,
					function ( $carry, $role ) use ( $pcp_role ) {
						if ( ! empty( $role ) ) {
							$carry = $carry . "OR campaign_app_type LIKE '%{$pcp_role}{$role};%' OR campaign_app_type LIKE '%{$pcp_role}{$role}' ";
						}

						return $carry;
					}, $where_like_string );
				$where_like_string = ! empty( $where_like_string ) ? $where_like_string : '';
			}

			return $where_like_string;
		}

		/**
		 * Get PCP Passwords data.
		 *
		 * @param string $password Password.
		 *
		 * @return array|object|void|null Database query result in format specified by $output or null on failure
		 */
		public function get_pcp_password( $password ) {
			$sql = $this->wpdb->prepare( "SELECT * FROM {$this->table_name} WHERE BINARY password = %s AND campaign_app_type LIKE %s", $password, PPW_Pro_Constants::CAMPAIGN_TYPE['SHORTCODE'] . '%'  );

			return $this->wpdb->get_row( $sql );
		}

		/**
		 * Add a row in table by id.
		 *
		 * @param array $data Data to add.
		 *
		 * @return int|false The number of rows updated, or false on error.
		 */
		public function add_new_password( $data ) {
			$is_added = $this->wpdb->insert( $this->table_name, $data );
			if ( $is_added ) {
				return $this->wpdb->insert_id;
			}

			return false;
		}

		/**
		 * Update count by password
		 *
		 * @param string $password
		 *
		 * @return bool
		 */
		public function update_count_by_password( $password ) {
			$sql = $this->wpdb->prepare( "UPDATE {$this->table_name} SET hits_count = hits_count + 1 WHERE BINARY password = %s AND campaign_app_type LIKE %s", $password, PPW_Pro_Constants::CAMPAIGN_TYPE['SHORTCODE'] . '%' );

			return $this->wpdb->get_row( $sql );
		}


	}
}
