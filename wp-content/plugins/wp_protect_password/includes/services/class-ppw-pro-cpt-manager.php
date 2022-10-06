<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 7/25/19
 * Time: 14:20
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'PPW_Migration_Cpt_Services' ) ) {
	class PPW_Migration_Cpt_Services extends PPW_Migration_Manager {

		/**
		 * Get module name.
		 *
		 * Retrieve the module name.
		 *
		 * @since 1.7.0
		 * @access public
		 *
		 * @return string Module name.
		 */
		public function get_name() {
			return 'cpt-migration';
		}

		public function get_action() {
			return 'ppw_cpt_migration';
		}

		public function get_plugin_name() {
			return 'ppw';
		}

		public function get_plugin_label() {
			return __( PPW_PRO_NAME, 'password-protect-page-pro' );
		}

		public function get_updater_label() {
			return sprintf( '<strong>%s </strong> &#8211;', __( PPW_PRO_NAME, 'password-protect-page' ) );
		}

		public function get_query_limit() {
			// TODO: Implement get_query_limit() method.
		}

		public function get_migrations_class() {
			return 'PPW_Cpt_Migrations';
		}

		public function get_migration_label() {
			return sprintf( '<strong>%s </strong> &#8211;', __( 'PPWP Data Migration', 'password-protect-wordpress' ) );
		}

		public function get_success_message() {
			return '<p>' . sprintf( __( '%s The <a href="https://passwordprotectwp.com/password-migration/" target="_blank" rel="noopener noreferrer">password migration process</a> is now complete. Thank you for your patience!', 'password-protect-page' ), $this->get_updater_label() ) . '</p>';
		}

	}
}
