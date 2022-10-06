<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.buildwps.com/
 * @since      1.0.0
 *
 * @package    Prevent_ur_pages
 * @subpackage Prevent_ur_pages/include
 */

/**
 *
 * Defines the Constants
 *
 * @package    Prevent_ur_pages
 * @subpackage Prevent_ur_pages/include
 * @author     Bwps <support@bwps.us>
 */

if ( ! class_exists( 'WP_Protect_Password_Constant' ) ) {
	/**
	 * Constants helper class
	 *
	 * Class WP_Protect_Password_Constant
	 */
	class WP_Protect_Password_Constant {
		const CAMPAIGN_APP = array(
			'AC' => 'ActiveCampaign'
		);
	}
}
?>
