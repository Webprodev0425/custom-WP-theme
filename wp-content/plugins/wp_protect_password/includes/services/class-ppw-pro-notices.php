<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 8/1/19
 * Time: 14:29
 */
if ( ! class_exists( 'PPW_Pro_Notice_Services' ) ) {

	class PPW_Pro_Notice_Services extends PPW_Pro_Base_Services {

		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new PPW_Pro_Notice_Services();
			}

			return self::$instance;
		}

		/**
		 * Show notice when user not enter license
		 * Check current user can activate plugins
		 * Check function get_current_screen exists
		 *
		 * Show on page: Plugins, Pages, Posts, Edit Page, Edit Post, PPWP Settings Page
		 */
		public function show_license_notice() {
			if ( ! current_user_can( 'activate_plugins' ) || ! function_exists( 'get_current_screen' ) ) {
				return;
			}
			$screen_display = array(
				'plugins',
				'page',
				'post',
				'edit-page',
				'edit-post',
				'toplevel_page_wp_protect_password_options',
			);
			if ( ! in_array( get_current_screen()->id, $screen_display, true ) ) {
				return;
			}

			/* translators: %1$s Plugin name */
			$plugin_name = sprintf( __( '%s: ', 'password-protect-page' ), PPW_PRO_NAME );

			/* translators: %1$s The guide link */
			$message = sprintf( __( 'Please <a href="%s">enter your license key</a> that you’ve received after purchasing our Pro version to activate all our premium features.', 'password-protect-page' ), 'admin.php?page=wp_protect_password_options&tab=license' );
			?>
			<div class="error is-dismissible notice">
				<p>
					<b><?php echo esc_html( $plugin_name ); ?></b><?php echo wp_kses_post( $message ); ?>
				</p>
			</div>
			<?php
		}

		/**
		 * Handle notices when integration with PDA Gold
		 * Check PDA is activate
		 * Check version PDA < 3.1.2 => show notice
		 * Check current user can activate plugins
		 * Check function get_current_screen exists
		 *
		 * Show on page: Plugins, Pages, Posts, Edit Page, Edit Post, PPWP Settings Page
		 */
		public function notice_integrate_with_pda_gold() {
			if ( Yme_Plugin_Utils::is_plugin_activated( 'pda_gold' ) !== - 1 && Yme_Plugin_Utils::is_plugin_activated( 'pda_v3' ) !== - 1 ) {
				return;
			}
			if ( version_compare( PDA_GOLD_V3_VERSION, '3.1.2' ) >= 0 || ! current_user_can( 'activate_plugins' ) || ! function_exists( 'get_current_screen' ) ) {
				return;
			}
			$screen_display = array(
				'plugins',
				'page',
				'post',
				'edit-page',
				'edit-post',
				'toplevel_page_wp_protect_password_options',
			);
			if ( ! in_array( get_current_screen()->id, $screen_display, true ) ) {
				return;
			}

			/* translators: %1$s Plugin name */
			$plugin_name = sprintf( __( '%s: ', 'password-protect-page' ), PPW_PRO_NAME );

			/* translators: %1$s The guide link */
			$message = sprintf( __( 'Our plugin <a target="_blank" rel="noopener noreferrer" href="%s">can\'t be integrated with PDA Gold</a> version 3.1.1.2 and lower. Please update PDA Gold to the newest version.', 'password-protect-page' ), 'https://passwordprotectwp.com/extensions/prevent-direct-access-gold-integration/' );
			?>
			<div class="error is-dismissible notice">
				<p>
					<b><?php echo esc_html( $plugin_name ); ?></b><?php echo wp_kses_post( $message ); ?>
				</p>
			</div>
			<?php
		}

		/**
		 * Only show notice on subsite when
		 *
		 */
		public function notice_integrate_with_multisite() {
			if ( defined( 'PDA_Multisite_VERSION' ) ) {
				return;
			}

			/**
			 * Not show notice on main site and single site.
			 */
			if ( ! is_multisite() || is_main_site() ) {
				return;
			}

			$screen_display = array(
				'toplevel_page_wp_protect_password_options',
			);

			if ( ! in_array( get_current_screen()->id, $screen_display, true ) ) {
				return;
			}

			/* translators: %1$s Plugin name */
			$plugin_name = sprintf( __( '%s: ', 'password-protect-page' ), PPW_PRO_NAME );

			/* translators: %1$s The guide link */
			$message = sprintf( __( 'Password Protect WordPress Pro requires <a target="_blank" rel="noopener noreferrer" href="%s">Multisite</a> addon to work properly in sub sites.', 'password-protect-page' ), 'https://passwordprotectwp.com/extensions/wordpress-multisite-integration/' );
			?>
			<div class="notice notice-warning is-dismissible">
				<p>
					<b><?php echo esc_html( $plugin_name ); ?></b><?php echo wp_kses_post( $message ); ?>
				</p>
			</div>
			<?php
		}
	}
}
