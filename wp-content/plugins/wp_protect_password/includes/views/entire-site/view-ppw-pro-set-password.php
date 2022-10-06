<?php
$entire_site_passwords      = ppw_pro_get_setting_entire_site_type_array( PPW_Pro_Constants::PPW_PASSWORD_FOR_ENTIRE_SITE );
$passwords                  = ppw_pro_get_string_key_in_array( $entire_site_passwords );
$is_display                 = $is_protected ? '' : 'ppwp-hidden-password';
$checked                    = $is_protected ? 'checked' : '';
$old_protected              = ppw_core_get_setting_entire_site_type_bool( PPW_Constants::IS_PROTECT_ENTIRE_SITE );
$hide_notice                = get_option( PPW_Pro_Constants::PPW_ENTIRE_SITE_OPTIONS ) || ! $old_protected ? 'ppwp-hidden-password' : '';
$should_show_password_input = apply_filters( 'ppwp_sitewide_should_show_password_input', true );
?>
<tr>
	<td>
		<label class="pda_switch" for="ppwp_apply_password_for_entire_site">
			<input type="checkbox" id="ppwp_apply_password_for_entire_site" <?php echo esc_attr( $checked ); ?> />
			<span class="pda-slider round"></span>
		</label>
	</td>
	<td>
		<p>
			<label><?php echo esc_html__( 'Password Protect Entire Site', PPW_Constants::DOMAIN ); ?></label>
			<?php echo sprintf( '%1$s <a href="%2$s" rel="noreferrer noopener" target="_blank">%3$s</a>.', __( 'Set passwords to protect your entire WordPress site. Customize password login form using', 'password-protect-page' ), admin_url( 'customize.php?autofocus[panel]=ppwp_sitewide' ), __( 'WordPress Customizer', 'password-protect-page' ) ) ?>
		</p>
		<p id="ppw_notice_entire_site" class="<?php echo esc_attr( $hide_notice ); ?>"><?php _e( 'You’ve set a hashed password to protect your entire site. Once you add new passwords to the text field below,
			<a href="https://passwordprotectwp.com/docs/password-protect-entire-wordpress-site/#hashed-password" rel="noreferrer noopener" target="_blank">the hashed one will be removed</a>.', PPW_Constants::DOMAIN ); ?></p>
	</td>
</tr>
<?php
if ( $should_show_password_input ) {
?>
<tr class="ppwp_logic_show_input_password <?php echo esc_attr( $is_display ) ?>">
	<td></td>
	<td>
		<div class="ppwp_wrap_new_password">
			<span class="ppw_set_new_password"></span>
			<p><?php echo esc_html__( 'Set new passwords', PPW_Constants::DOMAIN ); ?></p>
		</div>
		<div class="ppw_wrap_textarea_entire_site">
			<textarea required id="<?php echo esc_attr( PPW_Pro_Constants::PPW_PASSWORD_FOR_ENTIRE_SITE ); ?>"
			          class="textarea_password_entire_site"
			          placeholder="<?php echo esc_html__( 'One password per line', PPW_Constants::DOMAIN ); ?>"
			          rows="5"><?php echo esc_textarea( implode( "\n", $passwords ) ); ?></textarea>
			<!--For wrong message -->
			<p id="ppw_wrong_password"></p>
		</div>
	</td>
</tr>
<?php
}
?>
