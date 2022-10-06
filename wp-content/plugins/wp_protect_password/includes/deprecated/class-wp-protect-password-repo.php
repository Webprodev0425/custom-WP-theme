<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 7/13/18
 * Time: 15:31
 * @deprecated
 */
if ( ! class_exists( 'WP_Protect_Password_Repo' ) ) {
	/**
	 * Repository
	 * Class WP_Protect_Password_Service
	 */
	class WP_Protect_Password_Repo {
		/**
		 * @var object repository
		 */
		protected $repository;

		/**
		 * @var PPW_Pro_Password_Services
		 */
		protected $password_services;

		/**
		 * @var string
		 */
		protected $table_name;

		/**
		 * WP_Protect_Password_Repo constructor.
		 *
		 */
		public function __construct( $table_name ) {
			$this->repository = new PPW_Pro_Repository();
			$this->password_services = new PPW_Pro_Password_Services();
			$this->table_name = $table_name;
		}

		/**
		 * Check whether the post is protected or not
		 *
		 * @param int $post_id Post ID
		 *
		 * @return bool
		 */
		public function is_protected_item( $post_id ) {
			return $this->password_services->is_protected_content( $post_id );
		}

	}
}
