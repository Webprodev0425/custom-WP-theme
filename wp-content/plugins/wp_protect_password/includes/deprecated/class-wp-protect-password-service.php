<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 7/13/18
 * Time: 15:31
 * @deprecated
 */
if ( ! class_exists( 'WP_Protect_Password_Service' ) ) {
	/**
	 * Repository
	 * Class WP_Protect_Password_Service
	 */
	class WP_Protect_Password_Service {
		/**
		 * @var object repository
		 */
		protected $repo;

		/**
		 * @var PPW_Pro_Password_Services
		 */
		protected $password_services;

		/**
		 * WP_Protect_Password_Repo constructor.
		 * @deprecated
		 */
		public function __construct() {
			$this->repo              = new PPW_Pro_Repository();
			$this->password_services = new PPW_Pro_Password_Services();
		}

		/**
		 * Get all password by campaign app type
		 * @used-by PDA_Stats_Helpers
		 * @return mixed
		 * @deprecated
		 */
		public function ppw_get_all_password() {
			$items = $this->repo->get_all_password_by_campaign_app_type();

			$items = apply_filters( 'ppwp_pro_all_passwords', $items );

			return $items;
		}

		/**
		 * Get all protected post
		 * @used-by PDA_Stats_Helpers PDA_Stats_Service
		 * @return mixed
		 * @deprecated
		 */
		public function ppw_get_all_protected_posts() {
			return $this->repo->get_all_protected_posts();
		}

		/**
		 * @return mixed
		 * @deprecated
		 */
		public function get_all_protected_posts() {
			return $this->repo->get_all_protected_posts();
		}

		/**
		 * @param $roles
		 *
		 * @return string
		 */
		public function get_user_first_role( $roles ) {
			if ( ! is_array( $roles ) ) {
				return '';
			}

			$values = array_values( $roles );

			return empty( $values ) ? '' : $values[0];
		}

		/**
		 * @param      $post_id
		 * @param      $contact_id
		 * @param      $app_type
		 * @param bool $data
		 *
		 * @return mixed|WP_Error
		 * @throws Exception
		 * @deprecated
		 */
		public function auto_generate_pwd( $post_id, $contact_id, $app_type, $data = false ) {
			$result = $this->password_services->auto_generate_pwd( $post_id, $data, $contact_id, $app_type );
			if ( $result['is_error'] ) {
				return '';
			}

			return $result[ PPW_Pro_Constants::PW ];
		}

		/**
		 * Massage type of password.
		 *
		 * @param string $type Type
		 *
		 * @return string
		 */
		public function massage_role_of_password( $type ) {
			if ( 'ActiveCampaign' === $type ) {
				return 'Email Marketing';
			}

			return $this->password_services->massage_role_of_password( $type );
		}


	}
}
