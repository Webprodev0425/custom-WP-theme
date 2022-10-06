<?php
$all_page_post     = ppw_pro_get_all_page_post();
$is_apply_password = ppw_core_get_setting_type_bool( PPW_Pro_Constants::WPP_APPLY_PASSWORD_FOR_PAGES_POSTS );
$is_display        = $is_apply_password ? '' : 'ppwp-hidden-password';
$checked           = $is_apply_password ? 'checked' : '';

$repository        = new PPW_Pro_Repository();
$all_post_selected = $repository->get_all_post_id_by_type( PPW_Pro_Constants::CAMPAIGN_TYPE['COMMON'] );

$selected_posts = array_map( function ( $post ) {
	return $post->post_id;
}, $all_post_selected );

?>
	<tr>
		<td>
			<label class="pda_switch" for="ppwp_apply_password_for_pages_posts">
				<input type="checkbox" id="ppwp_apply_password_for_pages_posts" <?php echo esc_attr( $checked ); ?>/>
				<span class="pda-slider round"></span>
			</label>
		</td>
		<td>
			<p>
				<label><?php echo esc_html__( 'Password Protect Private Pages', PPW_Constants::DOMAIN ); ?></label>
				<?php echo esc_html__( 'Set the same password to protect the following pages and posts', PPW_Constants::DOMAIN ); ?>
			</p>
		</td>
	</tr>
	<tr class="ppwp-pages-posts-set-password <?php echo $is_display; ?>">
		<td></td>
		<td><p><?php echo esc_html__( 'Select your private pages or posts', PPW_Constants::DOMAIN ); ?></p>
			<select multiple="multiple" id="ppwp-pages-posts-select" class="ppwp_select2">
				<?php foreach ( $all_page_post as $page ): ?>
					<?php $is_selected = ! empty( $selected_posts ) && array_search( $page->ID, $selected_posts ) !== false ? "selected" : ""; ?>
					<option <?php echo esc_attr( $is_selected ); ?>
							value="<?php echo esc_html( $page->ID ); ?>"><?php echo esc_html( $page->post_title ); ?></option>
				<?php endforeach; ?>
			</select>
		</td>
	</tr>
<?php if ( empty( $all_post_selected ) ) { ?>
	<tr class="ppwp-pages-posts-set-password <?php echo $is_display; ?> ppwp_hide_form_enter_password">
		<td></td>
		<td class="ppwp_wrap_set_new_password_for_pages_posts">
			<p><?php echo esc_html__( 'Set a password', PPW_Constants::DOMAIN ); ?></p>
			<input required type="text"
			       placeholder="<?php echo esc_html__( 'Enter a password', PPW_Constants::DOMAIN ); ?>"
			       id="ppwp-password-for-pages-posts"
			       maxlength="<?php echo esc_attr( PPW_Pro_Constants::MAX_LENGTH_FOR_PASSWORD ); ?>"/>
		</td>
	</tr>
<?php } else { ?>
	<tr class="ppwp-pages-posts-set-password <?php echo esc_attr( $is_display ); ?> ppwp_show_form_enter_password">
		<td></td>
		<td class="ppwp-set-height-for-new-password">
			<p class="ppw_wrap_password_private_page"><?php _e( 'You’ve set this password to protect the above pages: ' . '<b class="ppwp_text_after_enter_password">' . esc_html( $all_post_selected[0]->password ) . '</b>', PPW_Constants::DOMAIN ) ?></p>
			<div class="ppwp-wrap-new-password">
				<label class="pda_switch" for="ppwp_set_new_password_for_pages_posts">
					<input type="checkbox" id="ppwp_set_new_password_for_pages_posts"
					       name="ppwp_set_new_password_for_pages_posts"/>
					<span class="pda-slider round"></span>
				</label>
				<span class="ppwp-set-new-password-text">Set a new password</span>
			</div>
			<div class="ppwp-hidden-new-password" id="ppwp-new-password">
				<div class="ppwp-wrap-new-password">
					<label class="pda_switch"></label>
					<span class="ppwp-set-new-password-input">
                    <input type="text"
                           placeholder="<?php echo esc_html__( 'Enter new password', PPW_Constants::DOMAIN ); ?>"
                           id="ppwp-password-for-pages-posts" maxlength="<?php echo esc_attr( PPW_Pro_Constants::MAX_LENGTH_FOR_PASSWORD ); ?>"/>
                    <input type="hidden" value="<?php echo esc_attr( $all_post_selected[0]->password ); ?>"
                           id="ppwp-password-hidden">
                </span>
				</div>
			</div>
			<div class="ppwp-message">
				<span>Want to unlock all protected content at once with one password? Check out <a target="_blank" rel="noopener noreferrer" href="https://passwordprotectwp.com/extensions/group-protection">Group Password Protection</a> extension.</span>
			</div>
		</td>
	</tr>
<?php }
