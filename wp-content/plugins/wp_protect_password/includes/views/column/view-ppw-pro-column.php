<?php
$services     = new PPW_Pro_Password_Services();
$is_protected = $services->is_protected_content( $post_id );
$btn_label    = $is_protected ? PPW_Pro_Constants::MANAGE_PWD_BTN : PPW_Pro_Constants::PROTECT_PWD_BTN;
$lock_class   = $is_protected ? 'dashicons-lock' : 'dashicons-unlock';
$color_class  = $is_protected ? 'ppw_protected_color' : 'ppw_unprotected_color';
$status       = $is_protected ? 'protected' : 'unprotected';
$post         = get_post( $post_id );
if ( 'page' === get_post_type() ) {
	$all_id_child_page = implode( ";", $services->get_all_id_child_page( $post_id ) );
}
$is_auto_protect_child_pages = ppw_core_get_setting_type_bool( PPW_Pro_Constants::WPP_AUTO_PROTECT_ALL_CHILD_PAGES );
?>

<div class="pda-pwd-tools">
	<input type="hidden" id="pda-password-nonce_<?php echo esc_attr( $post_id ) ?>"
	       nonce="<?php echo wp_create_nonce( PPW_Pro_Constants::AUTO_GENERATE_PWD_FORM_NONCE ); ?>"/>
	<input id="all_id_child_page_<?php echo esc_attr( $post_id ) ?>" type="hidden"
	       value="<?php echo ! empty( $all_id_child_page ) ? esc_attr( $all_id_child_page ) : ""; ?>">
	<p>
		<span id="ppw_wrap_icon_protect_<?php echo esc_attr( $post_id ); ?>" class="ppw_icon_protected <?php echo esc_attr( $color_class ); ?>">
			<i class="dashicons <?php echo esc_attr( $lock_class ); ?>"></i> <?php echo esc_html( $status, 'password-protect-page' ); ?>
		</span>
		<?php
			do_action( PPW_Pro_Constants::HOOK_PPWP_BADGE_PROTECTION, $post_id, $is_protected );
		?>
	</p>
	<?php
	if ( ppw_pro_has_permission_edit_post( $post_id ) ) {
		if ( $is_auto_protect_child_pages ) {
			if ( 0 === $post->post_parent ) {
				?>
				<p>
					<a class="pda-pwd-tbl-actions wp-protect-password-show-popup"
					   id="pda-protect-password_<?php echo esc_html( $post_id ) ?>"><?php echo esc_html( $btn_label, 'password-protect-page' ); ?></a>
				</p>
				<?php
			}
		} else {
			?>
			<p>
				<a class="pda-pwd-tbl-actions wp-protect-password-show-popup"
				   id="pda-protect-password_<?php echo esc_html( $post_id ) ?>"><?php echo esc_html( $btn_label, 'password-protect-page' ); ?></a>
			</p>
			<?php
		}
	}
	?>
</div>
