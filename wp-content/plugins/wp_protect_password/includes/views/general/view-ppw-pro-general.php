<form class="ppw_main_container" id="wp_protect_password_general_form">
	<input type="hidden" id="ppw_general_form_nonce"
	       value="<?php echo wp_create_nonce( PPW_Constants::GENERAL_FORM_NONCE ); ?>"/>
	<table class="ppwp_settings_table" cellpadding="4">
		<tr id="pda-password-protection">
			<td colspan="2">
				<h3><?php echo esc_html__( 'PASSWORD PROTECTION', 'password-protect-page' ); ?></h3>
			</td>
		</tr>
		<?php
		include PPW_PRO_VIEW_PATH . 'general/view-ppw-pro-column-permission.php';
		// Extend Free version.
		if ( defined( 'PPW_DIR_PATH' ) && is_file( PPW_DIR_PATH . 'includes/views/general/view-ppw-expired-cookie.php' ) ) {
			include PPW_DIR_PATH . 'includes/views/general/view-ppw-expired-cookie.php';
		}
		include PPW_PRO_VIEW_PATH . 'general/view-ppw-pro-whitelist-roles.php';
		include PPW_PRO_VIEW_PATH . 'general/view-ppw-pro-auto-protect-child-page.php';
		include PPW_PRO_VIEW_PATH . 'general/view-ppw-pro-protect-private-pages.php';
		// Extend Free version.
		if ( defined( 'PPW_DIR_PATH' ) && is_file( PPW_DIR_PATH . 'includes/views/general/view-ppw-hide-protected-post.php' ) ) {
			include PPW_DIR_PATH . 'includes/views/general/view-ppw-hide-protected-post.php';
		}
		?>
		<tr>
			<td colspan="2">
				<hr>
			</td>
		</tr>
		<tr id="wpp-password-form">
			<td colspan="2">
				<h3 style="margin-bottom: 0.5em"><?php echo esc_html__( 'PASSWORD FORM CUSTOMIZATION', 'password-protect-page' ); ?></h3>
				<?php
				$link_error_message = sprintf(
					'<a target="_blank" rel="noopener" href="%s">error message and password form</a>',
					'https://passwordprotectwp.com/customize-password-form-wordpress-customizer/'
				);
				$link_customizer    = sprintf(
					'<a target="_blank" rel="noopener" href="%s">WordPress Customizer</a>',
					'customize.php'
				);
				$form_message       = sprintf(
					// translators: %s: Link to documentation.
					esc_html__( 'Customize the default %1$s including its headline, description and button under %2$s. The following messages will display by default if you haven\'t customized them via WordPress Customizer.', 'password-protect-page' ),
					$link_error_message,
					$link_customizer
				);
				?>
				<p class="ppw-description-password-form"><?php echo wp_kses_post( $form_message ); ?></p>
			</td>
		</tr>
		<?php
		include PPW_PRO_VIEW_PATH . 'general/view-ppw-pro-form-message.php';
		include PPW_PRO_VIEW_PATH . 'general/view-ppw-pro-error-message.php';
		?>
		<tr>
			<td colspan="2">
				<hr>
			</td>
		</tr>
		<tr id="wpp-password-form">
			<td colspan="2">
				<h3><?php echo esc_html__( 'ADVANCED OPTIONS', 'password-protect-page' ); ?></h3>
			</td>
		</tr>
		<?php
		include PPW_PRO_VIEW_PATH . 'general/view-ppw-pro-remove-search-engine.php';
		// Extend Free version.
		include PPW_DIR_PATH . 'includes/views/general/view-ppw-remove-data.php';
		?>
	</table>
	<?php
	submit_button();
	?>
	<table class="ppwp_settings_table" cellpadding="4">
		<?php
		// Extend Free version.
		include PPW_DIR_PATH . 'includes/views/general/view-ppw-notices-cache.php';
		?>
	</table>
</form>
