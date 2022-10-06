<?php
$protect_child_page_checked = ppw_core_get_setting_type_bool( PPW_Pro_Constants::WPP_AUTO_PROTECT_ALL_CHILD_PAGES ) ? 'checked' : '';
?>
<tr>
	<td>
		<label class="pda_switch" for="ppwp_auto_protect_all_child_pages">
			<input type="checkbox"
			       id="ppwp_auto_protect_all_child_pages" <?php echo esc_attr( $protect_child_page_checked ); ?>/>
			<span class="pda-slider round"></span>
		</label>
	</td>
	<td>
		<p>
			<label><?php echo esc_html__( 'Password Protect Child Pages', PPW_Constants::DOMAIN ) ?></label>
			<?php echo esc_html__( 'Automatically protect all child pages once their parent is protected', PPW_Constants::DOMAIN ) ?>
		</p>
	</td>
</tr>
