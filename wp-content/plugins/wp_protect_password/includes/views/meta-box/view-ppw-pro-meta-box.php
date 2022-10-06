<?php

function ppw_pro_render_form_set_password_meat_box() {
	global $post;
	$is_auto_protect_all_child_pages = ppw_core_get_setting_type_bool( PPW_Pro_Constants::WPP_AUTO_PROTECT_ALL_CHILD_PAGES );
	if ( $is_auto_protect_all_child_pages ) {
		if ( $post->post_parent ) {
			$service = new PPW_Pro_Password_Services();
			$class   = $service->is_protected_content( $post->ID ) ? 'ppw_unprotected_button' : 'ppw_protected_button';
			?>
			<label class="<?php echo esc_attr( $class ); ?>"></label>
			<label class="ppw_protect_label"><?php echo esc_html__( 'Password protected', 'password-protect-page' ); ?></label>
			<p id="ppw_message_for_child_page"><?php echo esc_html__( 'Please manage passwords on parent page.', 'password-protect-page' ); ?></p>
			<?php
			return;
		}
	}
	?>
	<div id="ppwp_password_protect_by_roles" class="ppwp-password-protect-by-roles_<?php echo $post->ID; ?>"></div>
	<?php
	$assert_services = new PPW_Pro_Asset_Services( null, null );
	$assert_services->load_assets_for_meta_box();
}
