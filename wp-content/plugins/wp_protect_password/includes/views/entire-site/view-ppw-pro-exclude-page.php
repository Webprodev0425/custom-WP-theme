<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 7/23/19
 * Time: 16:41
 */

$all_page_post = ppw_pro_get_all_page_post();
if ( get_option( PPW_Pro_Constants::PPW_ENTIRE_SITE_OPTIONS ) ) {
	$is_exclude     = ppw_pro_get_setting_entire_site_type_bool( PPW_Pro_Constants::IS_EXCLUDE_PAGE );
	$pages_selected = ppw_pro_get_setting_entire_site_type_array( PPW_Pro_Constants::PAGE_EXCLUDED );
} else {
	$is_exclude     = ppw_core_get_setting_entire_site_type_bool( PPW_Pro_Constants::IS_EXCLUDE_PAGE );
	$pages_selected = ppw_core_get_setting_entire_site_type_array( PPW_Pro_Constants::PAGE_EXCLUDED );
}
$home_page  = in_array( PPW_Pro_Constants::EXCLUDE_HOME_PAGE, $pages_selected ) ? 'selected' : '';
$ppw_hide   = $is_exclude ? '' : 'ppwp-hidden-password';
$is_display = $is_protected ? '' : 'ppwp-hidden-password';
?>
<tr class="ppwp_logic_show_input_password ppwp_wrap_exclude_page <?php echo esc_attr( $is_display ); ?>">
	<td></td>
	<td class="ppwp_set_height_for_password_entire_site">
		<div class="ppwp_wrap_new_password">
			<label class="pda_switch" for="<?php echo esc_attr( PPW_Pro_Constants::IS_EXCLUDE_PAGE ); ?>">
				<input type="checkbox"
				       id="<?php echo esc_attr( PPW_Pro_Constants::IS_EXCLUDE_PAGE ); ?>" <?php echo $is_exclude ? 'checked' : ''; ?>/>
				<span class="pda-slider round"></span>
			</label>
			<span class="ppwp-set-new-password-text">Exclude these pages and posts from site-wide protection</span>
		</div>
		<div class="ppwp_wrap_select_exclude_page <?php echo esc_attr( $ppw_hide ); ?>">
			<select required multiple="multiple" id="<?php echo esc_attr( PPW_Pro_Constants::PAGE_EXCLUDED ); ?>"
			        class="ppwp_select2">
				<option <?php echo $home_page; ?>
						value="<?php echo esc_attr( PPW_Pro_Constants::EXCLUDE_HOME_PAGE ); ?>">Home Page
				</option>
				<?php foreach ( $all_page_post as $page ) { ?>
					<?php $is_selected = ! empty( $pages_selected ) && array_search( $page->ID, $pages_selected ) !== false ? 'selected' : ''; ?>
					<option <?php echo esc_attr( $is_selected ); ?>
							value="<?php echo esc_attr( $page->ID ); ?>"><?php echo esc_html( $page->post_title ); ?></option>
				<?php } ?>
			</select>
		</div>
	</td>
</tr>

