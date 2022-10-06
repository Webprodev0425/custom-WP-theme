<?php

if ( class_exists( 'PPWP_Pro_Plugin_Compatibility' ) ) {
	return;
}

/**
 * It's the common plugin compatibility class. Extensions will inherit or override the base class.
 * Will help to reduce the DRY problems.
 */
class PPWP_Pro_Plugin_Compatibility extends PPWP_Pro_Plugin_Compatibility_Base {

	/**
	 * Extension name.
	 *
	 * @var string
	 */
	protected $extension;

	/**
	 * Get message before activating.
	 *
	 * @param int    $addon_id   The addon ID.
	 * @param string $addon_slug The addon slug.
	 *
	 * @return string Empty if all the checking conditions passed.
	 */
	public function get_message_before_activate( $addon_id, $addon_slug ) {
		$message = $this->get_message_for_admin_notices();
		if ( ! empty( $message ) ) {
			return $message;
		}

		if ( ! $this->is_valid_purchased_addon( $addon_id, $addon_slug ) ) {
			return $this->get_warning_messages()['check_license_ppwp'];
		}

		return apply_filters( 'ppw_pro_activation_message', '' );
	}

	/**
	 * Get message in admin notices.
	 *
	 * @return string
	 */
	public function get_message_for_admin_notices() {
		if ( ! $this->is_pro_activated() ) {
			return self::get_plugin_compatibility_msg()['activate_ppwp_pro'];
		}

		if ( ! $this->is_free_activated() ) {
			// No need to show notice because Pro already did it.
			return '';
		}

		if ( ! $this->is_valid_license() ) {
			return self::get_plugin_compatibility_msg()['check_license_ppwp'];
		}

		return apply_filters( 'ppw_pro_admin_notice_message', '' );
	}

	/**
	 * Get warming messages for extensions.
	 *
	 * @return array
	 */
	public function get_warning_messages() {
		$pricing_url = self::generate_link(
			__( 'Password Protect WordPress Pro', 'ppwp-woo' ),
			'https://passwordprotectwp.com/pricing/'
		);

		$pro_do_it_now = self::generate_link(
			__( 'do it now', 'ppwp-woo' ),
			'https://passwordprotectwp.com/extensions/woocommerce-integration/'
		);

		$free_url = self::generate_link(
			__( 'Password Protect WordPress Free', 'ppwp-woo' ),
			'https://wordpress.org/plugins/password-protect-page/'
		);

		$email_url = self::generate_link(
			'hello@PreventDirectAccess.com',
			'mailto:hello@PreventDirectAccess.com'
		);

		$ppwp_free_message = sprintf(
			// translators: %s Plugin link.
			__( 'Please install and activate %s plugin', 'ppwp-woo' ),
			$free_url
		);

		$ppwp_pro_message = sprintf(
			// translators: %s Plugin link.
			__( 'Please install and activate %s plugin', 'ppwp-woo' ),
			$pricing_url
		);

		$invalid_license_messsage = sprintf(
			// translators: %1$s Plugin name, %2$s call to action, %3$s email.
			__( 'You didn\'t purchase this add-on with your %1$s plugin. Please %2$s or drop us an email at %3$s', 'ppwp-woo' ),
			$pricing_url,
			$pro_do_it_now,
			$email_url
		);

		$latest_plugin_message = sprintf(
			// translators: %s Extension name.
			__( 'Please update Password Protect WordPress Lite and Pro to the latest versions for %s extension to work properly.', 'ppwp-woo' ),
			$this->extension
		);

		return apply_filters(
			'ppw_pro_warning_messages',
			array(
				'activate_ppwp_free'    => $ppwp_free_message,
				'activate_ppwp_pro'     => $ppwp_pro_message,
				'check_license_ppwp'    => $invalid_license_messsage,
				'require_latest_plugin' => $latest_plugin_message,
			)
		);
	}

	/**
	 * Show message in admin notices with the same class 'notice notice-error is-dismissible' across the extensions.
	 *
	 * @param string $message     The warning message.
	 * @param string $plugin_name The plugin name.
	 *
	 * @return void
	 */
	public function show_message_in_admin_notices( $message, $plugin_name ) {
		$class = apply_filters( 'ppw_pro_admin_notice_class', 'notice notice-error is-dismissible' );
		printf( '<div class="%1$s"><p><b>%2$s: </b>%3$s</p></div>', esc_attr( $class ), esc_html( $plugin_name ), $message ); // phpcs:ignore
	}

	/**
	 * Check whether the Pro is activated based on the constant PPW_PRO_VERSION
	 *
	 * @return bool
	 */
	public function is_pro_activated() {
		return defined( 'PPW_PRO_VERSION' );
	}

	/**
	 * Check whether the Free is activated based on the constant PPW_VERSION
	 *
	 * @return bool
	 */
	public function is_free_activated() {
		return defined( 'PPW_VERSION' );
	}

	/**
	 * Check whether the current Pro having the valid license.
	 *
	 * @return bool
	 */
	public function is_valid_license() {
		return apply_filters( 'ppw_pro_is_valid_license', function_exists( 'is_pro_active_and_valid_license' ) && is_pro_active_and_valid_license() );
	}

	/**
	 * Check whether the addon is purchased with current PPWP Pro license.
	 *
	 * @param int    $addon_id   The add-on ID.
	 * @param string $addon_slug The add-on slug.
	 *
	 * @return bool
	 */
	public function is_valid_purchased_addon( $addon_id, $addon_slug ) {
		$license = get_option( 'wp_protect_password_license_key', '' );
		if ( empty( $license ) || ! class_exists( 'YME_Addon' ) ) {
			return false;
		}
		$yme_addon = new YME_Addon( $addon_slug );
		$data      = $yme_addon->isValidPurchased( $addon_id, $license );

		return isset( $data['isValid'] ) && $data['isValid'];
	}

	/**
	 * Check whether the current screen is valid to show warning message.
	 *
	 * @param array $whitelisted_screens The array of whitelisted screens.
	 *
	 * @return bool
	 */
	public function is_show_admin_notices( $whitelisted_screens ) {
		$current_screen = get_current_screen();
		if ( null === $current_screen ) {
			return false;
		}

		return apply_filters( 'ppw_pro_is_show_admin_notices', in_array( $current_screen->id, $whitelisted_screens, true ) );
	}


}
