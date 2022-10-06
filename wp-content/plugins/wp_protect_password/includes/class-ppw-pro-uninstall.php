<?php
if ( ! class_exists( 'PPW_Pro_Uninstall' ) ) {
	/**
	 * This class defines all code necessary to run during the plugin's Uninstall.
	 *
	 * Class PPW_Pro_Uninstall
	 */
	class PPW_Pro_Uninstall {
		/**
		 * Uninstall plugin
		 */
		public static function uninstall() {
			if ( did_action( 'ppw_free/loaded' ) ) {
				require_once PPW_PRO_DIR_PATH . 'includes/class-ppw-pro-constants.php';
				require_once PPW_PRO_DIR_PATH . 'includes/class-ppw-pro-functions.php';
				require_once PPW_PRO_DIR_PATH . 'includes/class-ppw-pro-db.php';
				ppw_pro_clean_data();
			}
		}
	}
}
