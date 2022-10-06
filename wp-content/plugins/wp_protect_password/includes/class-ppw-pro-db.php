<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 7/13/18
 * Time: 10:53
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'PPW_Pro_DB' ) ) {
	/**
	 * DB class to create table and manage version
	 * Class PPW_Pro_DB
	 */
	class PPW_Pro_DB {
		/**
		 * Table version
		 * @var string
		 */
		private $tbl_version;
		/**
		 * Table name
		 * @var string
		 */
		private $tbl_name;

		/**
		 * @var object
		 */
		private $wpdb;

		/**
		 * PPW_Pro_DB constructor.
		 *
		 * @param $prefix
		 */
		public function __construct( $prefix = false ) {
			global $wpdb;
			$this->wpdb        = $wpdb;
			$this->tbl_version = $this->get_table_version();
			$this->tbl_name    = ! $prefix ? $this->wpdb->prefix . PPW_Pro_Constants::TBL_NAME : $prefix . PPW_Pro_Constants::TBL_NAME;
		}

		/**
		 * Install table
		 */
		public function install() {
			$this->init_tbl();

			// Add new column
			foreach ( PPW_Pro_Constants::DB_DATA_COLUMN_TABLE as $data ) {
				$this->add_new_column( $data['old_version'], $data['new_version'], $data['value'] );
			}

			// Update column
			foreach ( PPW_Pro_Constants::DB_UPDATE_COLUMN_TABLE as $dt ) {
				$this->update_table( $dt['old_version'], $dt['new_version'], $dt['value'] );
			}

			$this->update_label_and_post_types_column();
		}

		/**
		 * Uninstall table
		 */
		public function uninstall() {
			$this->wpdb->query( "DROP TABLE IF EXISTS $this->tbl_name" );
		}

		/**
		 * Init table
		 */
		private function init_tbl() {
			if ( $this->is_table_does_not_exist() ) {
				$charset_collate = $this->wpdb->get_charset_collate();
				$sql             = "CREATE TABLE $this->tbl_name (
						id mediumint(9) NOT NULL AUTO_INCREMENT,
						post_id mediumint(9) NOT NULL,
						contact_id mediumint(9) NULL,
						campaign_app_type varchar(50) DEFAULT '' NULL,
						password varchar(30) NOT NULL,
						is_activated tinyint(1) DEFAULT 1,
						created_time BIGINT DEFAULT NULL,
						expired_time BIGINT DEFAULT NULL,
						UNIQUE KEY id(id)
				) $charset_collate;";
				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				dbDelta( $sql );
				$this->tbl_version = "1.0";
				$this->update_table_version( $this->tbl_version );
			}
		}

		/**
		 * Add new column for table
		 *
		 * @param $old_version
		 * @param $new_version
		 * @param $value
		 */
		private function add_new_column( $old_version, $new_version, $value ) {
			if ( $this->tbl_version === $old_version ) {
				$charset_collate = $this->wpdb->get_charset_collate();
				$sql             = "CREATE TABLE $this->tbl_name ( $value ) $charset_collate;";
				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				dbDelta( $sql );
				$this->tbl_version = $new_version;
				$this->update_table_version( $this->tbl_version );
			}
		}

		/**
		 * Update value for column in table
		 *
		 * @param $old_version
		 * @param $new_version
		 * @param $value
		 */
		private function update_table( $old_version, $new_version, $value ) {
			if ( $this->tbl_version === $old_version ) {
				$sql = "ALTER TABLE $this->tbl_name CHANGE $value";
				require_once ABSPATH . 'wp-admin/includes/upgrade.php';
				$this->wpdb->query( $sql );
				$this->tbl_version = $new_version;
				$this->update_table_version( $this->tbl_version );
			}
		}

		/**
		 * Check table is exist
		 *
		 * @return bool
		 */
		private function is_table_does_not_exist() {
			$query_string = 'SHOW TABLES LIKE %s';
			$preparation  = $this->wpdb->prepare( $query_string, $this->tbl_name );

			return $this->wpdb->get_var( $preparation ) != $this->tbl_name;
		}

		/**
		 * Get the plugin table's version
		 */
		private function get_table_version() {
			$version = get_option( PPW_Pro_Constants::TBL_VERSION, false );

			return ! $version ? '1.0' : $version;
		}

		/**
		 * Update table version
		 *
		 * @param $version
		 */
		private function update_table_version( $version ) {
			update_option( PPW_Pro_Constants::TBL_VERSION, $version );
		}

		/**
		 * Update label and post types column.
		 */
		public function update_label_and_post_types_column() {
			$this->add_new_column( '1.6', '1.7', 'label TINYTEXT' );
			$this->add_new_column( '1.7', '1.8', 'post_types varchar(255)' );
		}

	}
}
