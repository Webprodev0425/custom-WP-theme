<?php

/**
 * Fired during plugin activation
 *
 * @link       https://passwordprotectwp.com/
 * @since      1.0.0
 *
 * @package    Password_Protect_Page_Pro
 * @subpackage Password_Protect_Page_Pro/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Password_Protect_Page_Pro
 * @subpackage Password_Protect_Page_Pro/includes
 * @author     BWPS <hello@bwps.com>
 */
class PPW_Pro_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		self::save_product_id_to_option();
	}

	/**
	 * Save product ID to option
	 */
	private static function save_product_id_to_option() {
		require_once PPW_PRO_DIR_PATH . 'includes/class-ppw-pro-constants.php';
		if ( get_site_option( PPW_Pro_Constants::APP_ID ) === false ) {
			$configs = include_once PPW_PRO_DIR_PATH . 'includes/class-ppw-pro-configs.php';
			update_site_option( PPW_Pro_Constants::APP_ID, $configs->app_id );
		}
	}

}
