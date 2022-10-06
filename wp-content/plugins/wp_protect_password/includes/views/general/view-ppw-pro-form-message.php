<?php
$form_message = ppw_core_get_setting_type_string( PPW_Pro_Constants::WPP_FORM_MESSAGE );
if ( ! strlen( $form_message ) ) {
	$form_message = PPW_Pro_Constants::WPP_DEFAULT_FORM_MESSAGE;
}
?>
<tr>
	<td class="feature-input"><span class="feature-input"></span></td>
	<td>
		<p>
			<label><?php echo esc_html__( 'Form Message', PPW_Constants::DOMAIN ) ?></label>
			<?php echo esc_html__( 'Customize the message which displays above the password field', PPW_Constants::DOMAIN ) ?>
		</p>
		<input required type="text" placeholder="" id="wpp_form_message" class="wpp_form_message" maxlength="200"
		       value="<?php echo esc_html( $form_message ); ?>" name="wpp_form_message"/>
	</td>
</tr>
