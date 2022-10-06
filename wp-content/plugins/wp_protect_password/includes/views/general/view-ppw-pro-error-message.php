<?php
$error_message = ppw_core_get_setting_type_string( PPW_Pro_Constants::WPP_ERROR_MESSAGE );
if ( ! strlen( $error_message ) ) {
	$error_message = PPW_Pro_Constants::WPP_DEFAULT_ERROR_MESSAGE;
}
?>
<tr>
	<td class="feature-input"><span class="feature-input"></span></td>
	<td>
		<p>
			<label><?php echo esc_html__( 'Error Message', PPW_Constants::DOMAIN ) ?></label>
			<?php echo esc_html__( 'Customize the error message when users enter wrong passwords', PPW_Constants::DOMAIN ) ?>
		</p>
		<span>
            <input required type="text" placeholder="" id="wpp_error_message" maxlength="200"
                   value="<?php echo esc_html( $error_message ); ?>" name="wpp_error_message">
        </span>
	</td>
</tr>
