<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 7/23/19
 * Time: 16:41
 */

$entire_site_passwords      = ppw_pro_get_setting_entire_site_type_array( PPW_Pro_Constants::PPW_PASSWORD_FOR_ENTIRE_SITE );
$is_redirect                = ppw_pro_get_setting_entire_site_type_bool( PPW_Pro_Constants::ENTIRE_SITE_REDIRECTION );
$ppw_hide                   = $is_redirect ? '' : 'ppwp-hidden-password';
$is_display                 = $is_protected ? '' : 'ppwp-hidden-password';
$should_show_redirect_url_input = apply_filters( 'ppwp_sitewide_should_show_redirect_url_input', true );

if ( ! $should_show_redirect_url_input ) {
	return;
}

?>
<tr class="ppw-redirect-url-component <?php echo esc_attr( $is_display ); ?>">
	<td></td>
	<td class="ppwp_set_height_for_password_entire_site">
		<div class="ppwp_wrap_new_password">
			<label class="pda_switch">
				<input type="checkbox" id="<?php echo esc_attr( PPW_Pro_Constants::ENTIRE_SITE_REDIRECTION ); ?>" <?php echo $is_redirect ? 'checked' : ''; ?>/>
				<span class="pda-slider round"></span>
			</label>

			<span class="ppwp-set-new-password-text">
				Redirect after entering correct passwords
				<span title="<?php echo esc_html( 'Save your passwords to use this feature', 'password-protect-page' ); ?>" class="dashicons dashicons-warning ppw-tooltip"></span>
			</span>
		</div>
		<div id="ppwp_wrap_redirection" class="<?php echo esc_attr( $ppw_hide ); ?>">
			<table class="ppw_redirection">
				<?php foreach ( $entire_site_passwords as $pass => $url ) { ?>
					<?php $e_pass = base64_encode( $pass ); //phpcs:ignore ?>
					<tr>
						<td class="ppw-td-redirection">
							<div title="<?php echo esc_attr( $pass ); ?>"><?php echo esc_html( $pass ); ?></div>
						</td>
						<td class="ppw-password-and-redirection">
							<input placeholder="<?php echo esc_html( 'Enter a valid URL, e.g. http://example.com', 'password-protect-page' ); ?>" type="text" id="ppw-url-pwd_<?php echo esc_attr( $e_pass ); ?>" value="<?php echo esc_attr( $url['redirect_url'] ); ?>"/>
						</td>
					</tr>
				<?php } ?>
			</table>
		</div>
		<p class="ppw_error ppw_error_redirection">Please enter a valid link.</p>
	</td>
</tr>

