<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://passwordprotectwp.com/
 * @since      1.0.0
 *
 * @package    Password_Protect_Page_Pro
 * @subpackage Password_Protect_Page_Pro/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Password_Protect_Page_Pro
 * @subpackage Password_Protect_Page_Pro/includes
 * @author     BWPS <hello@bwps.com>
 */
class PPW_Pro_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		$option_name = 'ppw_pro_migrated_free';
		if ( is_multisite() ) {
			foreach ( get_sites() as $site ) {
				delete_blog_option( $site->blog_id, $option_name );
			}
		} else {
			delete_option( $option_name );
		}
	}

}
