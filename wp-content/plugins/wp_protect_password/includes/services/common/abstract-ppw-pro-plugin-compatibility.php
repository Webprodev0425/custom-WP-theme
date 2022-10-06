<?php

if ( class_exists( 'PPWP_Pro_Plugin_Compatibility_Base' ) ) {
	return;
}

/**
 * Class PPWP_Pro_Plugin_Compatibility_Base
 */
abstract class PPWP_Pro_Plugin_Compatibility_Base {
	/**
	 * Get warning message before activating plugins.
	 *
	 * @param int    $addon_id   The addon ID.
	 * @param string $addon_slug The addon slug.
	 *
	 * @return string
	 */
	abstract public function get_message_before_activate( $addon_id, $addon_slug );

	/**
	 * Get warning message in admin dashboard's notices.
	 *
	 * @return string
	 */
	abstract public function get_message_for_admin_notices();

	/**
	 * Show warning message in admin dashboard's notices.
	 *
	 * @param string $message     The error message to be shown.
	 * @param string $plugin_name The plugin name headline.
	 *
	 * @return bool
	 */
	abstract public function show_message_in_admin_notices( $message, $plugin_name );

	/**
	 * Check whether the current screen is valid to show warning message.
	 *
	 * @param array $whitelisted_screens The array of whitelisted screens.
	 *
	 * @return bool
	 */
	abstract public function is_show_admin_notices( $whitelisted_screens );

	/**
	 * Check whether the free version is activated.
	 *
	 * @return bool
	 */
	abstract public function is_free_activated();

	/**
	 * Check whether the pro version is activated.
	 *
	 * @return bool
	 */
	abstract public function is_pro_activated();

	/**
	 * Check whether the pro version is having the valid license.
	 *
	 * @return bool
	 */
	abstract public function is_valid_license();

	/**
	 * Check whether the addon is purchased with current PPWP Pro license.
	 *
	 * @param int    $addon_id   The add-on ID.
	 * @param string $addon_slug The add-on slug.
	 *
	 * @return bool
	 */
	abstract public function is_valid_purchased_addon( $addon_id, $addon_slug );

	/**
	 * Get warning messages.
	 *
	 * @return array
	 */
	abstract public function get_warning_messages();
}
