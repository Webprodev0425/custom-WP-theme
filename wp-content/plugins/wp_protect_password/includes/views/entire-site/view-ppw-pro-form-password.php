<?php
// var_dump($password_label);
function entire_site_render_login_form() {

	$logo_content 			= wp_kses_post ( get_theme_mod( 'ppwp_pro_form_logo_content', PPW_Pro_Constants::DEFAULT_CONTENT_TEXT ) );
	$password_label 		= wp_kses_post ( get_theme_mod( 'ppwp_pro_form_instructions_password_label', PPW_Pro_Constants::DEFAULT_PASSWORD_LABEL ) );
	$error_message 			= wp_kses_post ( get_theme_mod( 'ppwp_pro_form_error_message_text', PPW_Pro_Constants::WPP_DEFAULT_ERROR_MESSAGE ) );
	$password_placehoder 	= wp_kses_post ( get_theme_mod( 'ppwp_pro_form_instructions_placeholder', PPW_Pro_Constants::DEFAULT_PLACEHOLDER ) );
	$button_text 			= wp_kses_post ( get_theme_mod( 'ppwp_pro_form_button_label' , PPW_Pro_Constants::DEFAULT_SUBMIT_LABEL ) );
	$show_password_text 	= wp_kses_post ( get_theme_mod( 'ppwp_pro_form_instructions_show_password_text' , PPW_Pro_Constants::DEFAULT_SHOW_PASSWORD_TEXT ) );
	$show_password 			= get_theme_mod( 'ppwp_pro_form_instructions_is_show_password', PPW_Pro_Constants::DEFAULT_IS_SHOW_PASSWORD ) ? '<div class="ppw-show-password"><label><input type="checkbox" onclick="ppwShowSiteWidePassword()">'. _x( $show_password_text, PPW_Pro_Constants::CONTEXT_PASSWORD_FORM, 'password-protect-page' ) . '</label></div>' : '';
	$disable_logo 			= get_theme_mod( 'ppwp_pro_logo_disable', PPW_Pro_Constants::DEFAULT_LOGO_CUSTOMIZE_DISABLE ) ? 'none' : 'block';
	$form_transparency 		= get_theme_mod( 'ppwp_pro_form_enable_transparency', PPW_Pro_Constants::DEFAULT_FORM_TRANSPARENCY ) ? 'style="background: none; box-shadow: initial;"' : '';

	$script_show_password = '';
	if ( ! empty( $show_password ) ) {
		ob_start();
		do_action( PPW_Pro_Constants::HOOK_CUSTOM_SCRIPT_FORM_ENTIRE_SITE );
		$script = ob_get_contents();
		ob_end_clean();

		$script_show_password = '<script>' . $script . '</script>';
	}


	return '
			<div class="pda-form-login ppw-swp-form-container">
				<a style="display: ' . $disable_logo . ' " title=" ' . esc_attr__( 'This site is password protected by PPWP plugin', 'password-protect-page') . '" class="ppw-swp-logo">' . PPW_PRO_NAME . ' plugin</a>
				<div class="pda-form-headline">' . $logo_content . '</div>
				<form ' . $form_transparency . ' action="?action=ppw_postpass&wrong_password=true" method="post">
					<label for="input_wp_protect_password">' . $password_label . '</label>
					<input class="input_wp_protect_password" type="password" id="input_wp_protect_password"
						name="input_wp_protect_password" placeholder="' . $password_placehoder . '" >
					' . $show_password . ' 
					<div id="ppw_entire_site_wrong_password"
					class="ppw-entire-site-password-error">' . $error_message . '</div>
					<input id="submit" type="submit" class="button button-primary button-login" value="' . $button_text . '">
				</form>
			</div>' . $script_show_password;
}
?>
