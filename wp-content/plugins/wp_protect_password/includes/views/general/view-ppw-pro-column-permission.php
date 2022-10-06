<?php
$post_types = ppw_pro_get_all_post_types();
unset( $post_types['post'] );
unset( $post_types['page'] );
$selected_column = ppw_core_get_setting_type_array( PPW_Pro_Constants::WPP_WHITELIST_COLUMN_PROTECTIONS );
?>
<tr>
	<td class="feature-input"><span class="feature-input"></span></td>
	<td>
		<p>
			<label><?php echo esc_html__( 'Post Type Protection', PPW_Constants::DOMAIN ); ?></label>
			<?php echo _e( '<a target="_blank" rel="noopener noreferrer" href="https://passwordprotectwp.com/docs/settings/#cpt">Select which custom post types</a> you want to password protect. Default: Pages & Posts.', PPW_Constants::DOMAIN ); ?>
		</p>
		<div class="ppw_pro_wrap_select_protection_selected">
			<div class="ppw_pro_wrap_protection_selected">
				<span class="ppw_pro_protection_selected">Pages</span>
				<span class="ppw_pro_protection_selected">Posts</span>
			</div>
			<select multiple="multiple" id="ppwp_whitelist_column_protections_select2" class="ppwp_select2">
				<?php foreach ( $post_types as $post_type ): ?>
					<option <?php echo in_array( $post_type->name, $selected_column ) ? 'selected="selected"' : '' ?>
							value="<?php echo esc_attr( $post_type->name ) ?>"><?php echo esc_html__( $post_type->label ); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</td>
</tr>
