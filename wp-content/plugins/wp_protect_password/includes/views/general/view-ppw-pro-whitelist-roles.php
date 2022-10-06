<?php
$roles                 = get_editable_roles();
$roles_access          = ppw_core_get_setting_type_string( PPW_Pro_Constants::WPP_WHITELIST_ROLES );
$custom_roles_selected = ppw_core_get_setting_type_array( PPW_Pro_Constants::WPP_ROLE_SELECT );
$is_display            = $roles_access === PPW_Pro_Constants::PERMISSION_CUSTOM_ROLES ? '' : 'wpp_hide_role_access';
?>
<tr>
	<td class="feature-input"><span class="feature-input"></span></td>
	<td>
		<p>
			<label><?php echo esc_html__( 'Whitelisted Roles', PPW_Constants::DOMAIN ) ?></label>
			<?php echo esc_html__( 'Select user roles who can access all protected content without having to enter passwords', PPW_Constants::DOMAIN ) ?>
		</p>
		<select id="wpp_whitelist_roles">
			<option value="<?php echo esc_attr( PPW_Pro_Constants::PERMISSION_NO_ONE ); ?>" <?php if ( $roles_access === PPW_Pro_Constants::PERMISSION_NO_ONE ) {
				echo "selected";
			} ?>><?php echo esc_html__( 'No one', PPW_Constants::DOMAIN ) ?>
			</option>
			<option value="<?php echo esc_attr( PPW_Pro_Constants::PERMISSION_ADMIN_USER ); ?>" <?php if ( $roles_access === PPW_Pro_Constants::PERMISSION_ADMIN_USER ) {
				echo "selected";
			} ?> ><?php echo esc_html__( 'Admin users', PPW_Constants::DOMAIN ) ?>
			</option>
			<option value="<?php echo esc_attr( PPW_Pro_Constants::PERMISSION_AUTHOR ); ?>" <?php if ( $roles_access === PPW_Pro_Constants::PERMISSION_AUTHOR ) {
				echo "selected";
			} ?> ><?php echo esc_html__( 'The post\'s author', PPW_Constants::DOMAIN ) ?>
			</option>
			<option value="<?php echo esc_attr( PPW_Pro_Constants::PERMISSION_LOGGED_USER ); ?>" <?php if ( $roles_access === PPW_Pro_Constants::PERMISSION_LOGGED_USER ) {
				echo "selected";
			} ?> ><?php echo esc_html__( 'Logged-in users', PPW_Constants::DOMAIN ) ?>
			</option>
			<option value="<?php echo esc_attr( PPW_Pro_Constants::PERMISSION_CUSTOM_ROLES ); ?>" <?php if ( $roles_access === PPW_Pro_Constants::PERMISSION_CUSTOM_ROLES ) {
				echo "selected";
			} ?> ><?php echo esc_html__( 'Choose custom roles', PPW_Constants::DOMAIN ) ?>
			</option>
		</select>
	</td>
</tr>
<tr id="wpp_roles_access" class="<?php echo esc_attr( $is_display ); ?>">
	<td></td>
	<td>
		<p><?php echo esc_html__( 'Grant access to these user roles only', PPW_Constants::DOMAIN ) ?></p>
		<select multiple="multiple" id="wpp_roles_select" class="wpp_roles_select ppwp_select2">
			<?php foreach ( $roles as $role_name => $role_info ):
				$arrRole = array( $role_name ); ?>
				<option <?php echo array_intersect( $arrRole, $custom_roles_selected ) ? 'selected="selected"' : '' ?>
						value="<?php echo $role_name ?>"><?php echo $role_name ?></option>
			<?php endforeach; ?>
		</select>
	</td>
</tr>
