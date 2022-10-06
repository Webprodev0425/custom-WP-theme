<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://passwordprotectwp.com?utm_source=user-website&utm_medium=pluginsite_link&utm_campaign=ppwp-pro
 * @since             1.0.0
 * @package           Password_Protect_Page_Pro
 *
 * @wordpress-plugin
 * Plugin Name:       Password Protect WordPress Pro
 * Plugin URI:        https://passwordprotectwp.com?utm_source=user-website&utm_medium=pluginsite_link&utm_campaign=ppwp-pro
 * Description:       Taking password protection to another level. Password protect custom post types, sub-pages as well as multiple pages at the same time and much more.
 * Version:           1.2.2
 * Author:            BWPS
 * Author URI:        https://passwordprotectwp.com?utm_source=user-website&utm_medium=pluginsite_link&utm_campaign=ppwp-pro
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       password-protect-page-pro
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PPW_PRO_VERSION', '1.2.2' );

if ( ! defined( 'PPW_PRO_NAME' ) ) {
	define( 'PPW_PRO_NAME', 'Password Protect WordPress Pro' );
}

if ( ! defined( 'PPW_PRO_BASE_FILE' ) ) {
	define( 'PPW_PRO_BASE_FILE', __FILE__ );
}

if ( ! defined( 'PPW_PRO_DIR_PATH' ) ) {
	define( 'PPW_PRO_DIR_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'PPW_PRO_VIEW_URL' ) ) {
	define( 'PPW_PRO_VIEW_URL', plugin_dir_url( __FILE__ ) . 'includes/views/' );
}

if ( ! defined( 'PPW_PRO_VIEW_PATH' ) ) {
	define( 'PPW_PRO_VIEW_PATH', plugin_dir_path( __FILE__ ) . 'includes/views/' );
}

if ( ! defined( 'PPW_PRO_DIR_URL' ) ) {
	define( 'PPW_PRO_DIR_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ppw-pro-activator.php
 */
function activate_password_protect_page_pro() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ppw-pro-activator.php';
	PPW_Pro_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ppw-pro-deactivator.php
 */
function deactivate_password_protect_page_pro() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ppw-pro-deactivator.php';
	PPW_Pro_Deactivator::deactivate();
}

/**
 * Uninstall the password protect page pro
 */
function uninstall_password_protect_page_pro() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ppw-pro-uninstall.php';
	PPW_Pro_Uninstall::uninstall();
}

register_activation_hook( __FILE__, 'activate_password_protect_page_pro' );
register_deactivation_hook( __FILE__, 'deactivate_password_protect_page_pro' );
register_uninstall_hook( __FILE__, 'uninstall_password_protect_page_pro' );

add_action( 'plugins_loaded', 'ppw_pro_load_plugin' );

/**
 * Load plugin
 */
function ppw_pro_load_plugin() {
	if ( ! did_action( 'ppw_free/loaded' ) ) {
		add_action( 'admin_notices', 'ppw_pro_fail_load' );

		return;
	}

	run_password_protect_page_pro();
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ppw-pro.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_password_protect_page_pro() {
	$plugin = new Password_Protect_Page_Pro();
	$plugin->run();

	global $migration_service;
	$migration_service = new PPW_Migration_Manager_Services();

	global $cpt_migration_service;
	$cpt_migration_service = new PPW_Migration_Cpt_Services();

	$configs = require PPW_PRO_DIR_PATH . '/includes/class-ppw-pro-configs.php';
	if ( method_exists( 'Puc_v4p8_Factory', 'buildUpdateChecker' ) ) {
		Puc_v4p8_Factory::buildUpdateChecker(
			$configs->update_url,
			__FILE__,
			'wp_protect_password'
		);
	} elseif ( method_exists( 'Puc_v4_Factory', 'buildUpdateChecker' ) ) {
		Puc_v4_Factory::buildUpdateChecker(
			$configs->update_url,
			__FILE__,
			'wp_protect_password'
		);
	}
}

function ppw_pro_fail_load() {
	$screen = get_current_screen();
	if ( isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id ) {
		return;
	}

	$plugin = 'password-protect-page/wp-protect-password.php';

	if ( _is_ppw_free_installed() ) {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		if ( defined( 'PROTECT_BY_PASSWORD' ) ) {
//			$install_url = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=password-protect-page/wp-protect-password.php' ), 'install-plugin_password-protect-page' );
			$message     = '<p style="margin-bottom: 0">' . __( 'Password Protect WordPress Pro is not working because you need to <a target="_blank" rel="noopener noreferrer" href="https://passwordprotectwp.com/docs/installation/#free-version">update our Free version to latest version</a>.', 'password-protect-page' ) . '</p>';
			$message .= '<p style="margin-top: 0">' . __( 'You <b>must NOT delete</b> the current Free version. Otherwise, you’ll lose all your current settings data.', 'password-protect-page') . '</p>';
//			$message     .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, __( 'Update Free version now', 'password-protect-page' ) ) . '</p>';
		} else {
			$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );
			$message        = '<p>' . __( 'Password Protect WordPress Pro is not working because you need to activate our Free version.', 'password-protect-page' ) . '</p>';
			$message        .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, __( 'Activate Free version now', 'password-protect-page' ) ) . '</p>';
		}

	} else {
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}
		$install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=password-protect-page' ), 'install-plugin_password-protect-page' );
		$message     = '<p>' . __( 'Password Protect WordPress Pro is not working because you need to <a target="_blank" rel="noopener noreferrer" href="https://passwordprotectwp.com/docs/installation/#free-version">install our Free version</a>.', 'password-protect-page' ) . '</p>';
		$message     .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, __( 'Install Free version now', 'password-protect-page' ) ) . '</p>';
	}

	echo '<div class="error">'. $message . '</div>';
}

if ( ! function_exists( '_is_ppw_free_installed' ) ) {
	/**
	 * @return bool
	 */
	function _is_ppw_free_installed() {
		$file_path         = 'password-protect-page/wp-protect-password.php';
		$installed_plugins = get_plugins();

		return isset( $installed_plugins[ $file_path ] );
	}
}


