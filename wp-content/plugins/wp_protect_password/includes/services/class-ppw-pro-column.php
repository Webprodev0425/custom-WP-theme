<?php
if ( ! class_exists( 'PPW_Pro_Column_Services' ) ) {

	class PPW_Pro_Column_Services {

		/**
		 * @var PPW_Pro_Category_Services
		 */
		private $category_service;

		/**
		 * PPW_Pro_Column_Services constructor.
		 */
		public function __construct() {
			$this->category_service = new PPW_Pro_Category_Services();
		}

		/**
		 * Add new column
		 *
		 * @param array $columns An associative array of column headings.
		 *
		 * @return mixed
		 */
		public function add_column( $columns ) {
			global $post_status;
			if ( 'trash' === $post_status ) {
				return $columns;
			}

			// TODO: Need to use global $post_tpye and get_post_type() function in this case.
			$check_current_screen = function_exists( 'get_current_screen' ) && ! is_null( get_current_screen() ) && ppw_pro_check_permission_for_post_type( get_current_screen()->id );
			if ( $check_current_screen ) {
				$columns[ PPW_Pro_Constants::CUSTOM_POST_TABLE_COLUMN_NAME ] = PPW_Pro_Constants::CUSTOM_POST_TABLE_COLUMN_TITLE;

				return $columns;
			}

			$check_post_type = function_exists( 'get_post_type' ) && false !== get_post_type() && ppw_pro_check_permission_for_post_type( get_post_type() );
			if ( $check_post_type ) {
				$columns[ PPW_Pro_Constants::CUSTOM_POST_TABLE_COLUMN_NAME ] = PPW_Pro_Constants::CUSTOM_POST_TABLE_COLUMN_TITLE;

				return $columns;
			}

			return $columns;
		}

		/**
		 * Render content for column
		 *
		 * @param array      $column  An associative array of column headings.
		 * @param int|string $post_id The post ID.
		 */
		public function render_post_column_content( $column, $post_id ) {
			if ( PPW_Pro_Constants::CUSTOM_POST_TABLE_COLUMN_NAME === $column ) {
				include PPW_PRO_VIEW_PATH . 'column/view-ppw-pro-column.php';
			}
		}

		/**
		 * Custom position hide feature set default password in WordPress
		 *
		 * @param $positions
		 *
		 * @return array
		 */
		public function custom_position_hide_default_pw_wp( $positions ) {
			$posts_type_selected = ppw_core_get_setting_type_array( PPW_Pro_Constants::WPP_WHITELIST_COLUMN_PROTECTIONS );
			foreach ( $posts_type_selected as $type ) {
				array_push( $positions, 'edit-' . $type, $type );
			}

			return array_unique( $positions );
		}

		/**
		 * Handle meta box position
		 *
		 * @param $positions
		 *
		 * @return array
		 */
		public function handle_meta_box_position( $positions ) {
			$posts_type_selected = ppw_core_get_setting_type_array( PPW_Pro_Constants::WPP_WHITELIST_COLUMN_PROTECTIONS );
			$new_position        = array_merge( $positions, $posts_type_selected );

			return array_unique( $new_position );
		}

		/**
		 * Custom post type for feature migration
		 *
		 * @param $types
		 *
		 * @return array
		 */
		public function custom_post_type_for_feature_migration( $types ) {
			$gold_types = ppw_core_get_setting_type_array( PPW_Pro_Constants::WPP_WHITELIST_COLUMN_PROTECTIONS );

			return array_diff( $gold_types, $types );
		}

		/**
		 * @param $column_name
		 * @param $term_id
		 *
		 * @return bool
		 */
		public function custom_category_column( $column_name, $term_id ) {
			if ( $column_name !== PPW_Pro_Constants::CUSTOM_CATEGORY_TABLE_COLUMN_NAME ) {
				return false;
			}

			include PPW_PRO_VIEW_PATH . 'category-column/view-ppw-pro-category-column.php';
		}

		public function add_column_to_category_table( $columns ) {
			if ( isset( $_GET['post_type'] ) ) {
				return $columns;
			}
			$columns[ PPW_Pro_Constants::CUSTOM_CATEGORY_TABLE_COLUMN_NAME ] = PPW_Pro_Constants::CUSTOM_POST_TABLE_COLUMN_TITLE;

			return $columns;
		}
	}
}
