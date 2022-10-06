<?php
$configs            = require( PPW_PRO_DIR_PATH . 'includes/class-ppw-pro-configs.php' );
$license_service    = new PPW_Pro_License_Services();
$license_type       = $license_service->get_license_type();
$is_entered_license = $license_service->is_valid_license();
?>
<div class="ppw_main_container">
	<form id="wp_protect_password_license_form" class="wppp-license-container">
		<?php
		ppw_render_license_header( $is_entered_license, $license_type );
		?>
		<?php
		ppw_render_license_form( $is_entered_license, $configs->debug_mode );
		?>
	</form>
</div>

<?php
function ppw_render_license_header( $is_entered_license, $license_type ) {
	if ( ! $is_entered_license ) { ?>
		<h3><?php echo esc_html( PPW_Pro_Constants::LICENSE_NOT_ACTIVATED ); ?></h3>
	<?php } else { ?>
		<div class="wppp-license-info">
			<label>Password Protect WordPress Pro</label>
			<span>
				<i class="ppw-icon-star dashicons dashicons-star-filled" aria-hidden="true"></i>
				<?php echo sprintf( __( 'Pro version %s', 'password-protect-page' ), PPW_PRO_VERSION ); ?>
			</span>
		</div>
		<div class="wppp-license-info">
			<label><?php _e( 'License type', 'password-protect-page' ); ?></label>
			<span><?php echo esc_html( $license_type ); ?></span>
		</div>
	<?php }
}

function ppw_render_license_form( $is_entered_license, $debug_mode ) {
	$license_key = get_option( PPW_Pro_Constants::LICENSE_KEY, '' );
	?>
	<div class="wppp-license-info">
		<input type="hidden" value="<?php echo wp_create_nonce( PPW_Pro_Constants::LICENSE_FORM_NONCE ); ?>"
		       id="ppw_license_nonce"/>
		<label><?php _e( 'License key', 'password-protect-page' ); ?></label>
		<?php if ( $is_entered_license && ! $debug_mode ) { ?>
			<span><?php echo esc_html( $license_key ); ?></span>
		<?php } elseif ( $is_entered_license && $debug_mode ) { ?>
			<td>
				<input required style="width: 330px" type="text" id="wp-protect-password-gold_license_key"
				       name="wp-protect-password-gold_license_key" value="<?php echo esc_attr( $license_key ); ?>"/>
			</td>
		<?php } else { ?>
			<td>
				<input required style="width: 330px" type="text" id="wp-protect-password-gold_license_key"
				       name="wp-protect-password-gold_license_key" value=""/>
			</td>
		<?php } ?>
	</div>
	<?php if ( ! $is_entered_license || $debug_mode ) {
		submit_button();
	} ?>
	<?php
}

$assert_services = new PPW_Pro_Asset_Services( get_current_screen()->id, $_GET );
$assert_services->load_asset_for_license_tab();
?>
