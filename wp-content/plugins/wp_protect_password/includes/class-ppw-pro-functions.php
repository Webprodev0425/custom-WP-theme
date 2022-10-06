<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 7/23/19
 * Time: 17:23
 */

if ( ! function_exists( 'is_plugin_active' ) ) {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

/**
 * Generate a unique password
 * @return string
 */
function generate_pwd() {
	return uniqid( '', false );
}

/**
 * Check array is empty
 *
 * @param $param
 *
 * @return bool
 */
function ppw_array_is_empty( $param ) {
	return count( $param ) <= 0;
}

/**
 * Filter param "" for array
 *
 * @param $param
 *
 * @return array
 */
function ppw_array_filter( $param ) {
	return array_filter( $param, function ( $i ) {
		return '' !== $i;
	} );
}

/**
 * Get all page and post
 *
 * @return array
 */
function ppw_pro_get_all_page_post() {
	$results    = array();
	$post_types = ppw_core_get_setting_type_array( PPW_Pro_Constants::WPP_WHITELIST_COLUMN_PROTECTIONS );
	array_push( $post_types, 'post', 'page' );
	$post_types = array_unique( $post_types );

	$auto_protect_child_pages = ppw_core_get_setting_type_bool( PPW_Pro_Constants::WPP_AUTO_PROTECT_ALL_CHILD_PAGES );
	foreach ( $post_types as $post_type ) {
		$args = array(
			'post_status' => 'publish',
			'post_type'   => $post_type,
			'numberposts' => - 1,
		);
		foreach ( get_posts( $args ) as $post ) {
			if ( $auto_protect_child_pages ) {
				if ( $post->post_parent === 0 ) {
					array_push( $results, $post );
				}
			} else {
				array_push( $results, $post );
			}
		}
	}

	return $results;
}

/**
 * Update settings
 */
function ppw_pro_save_pcp_settings_value( $name_settings, $value ) {
	$settings = get_option( PPW_PRO_Constants::WPP_PCP_PASSWORD_OPTIONS );
	if ( ! $settings ) {
		$options = new StdClass();
	} else {
		$options = json_decode( $settings );
	}
	$options->$name_settings = $value;
	update_option( PPW_PRO_Constants::WPP_PCP_PASSWORD_OPTIONS, wp_json_encode( $options ) );
}

function ppw_pro_get_pcp_settings_boolean( $name_settings ) {
	$settings = get_option( PPW_PRO_Constants::WPP_PCP_PASSWORD_OPTIONS );
	return ppw_pro_check_pcp_option_settings( $settings, $name_settings );
}

function ppw_pro_check_pcp_option_settings( $settings, $name_settings ) {
	if ( $settings ) {
		$options = json_decode( $settings );
		if ( ! isset( $options->$name_settings ) ) {
			return false;
		}
		if ( $options->$name_settings == "true" || $options->$name_settings == "1" ) {
			return true;
		}
	}

	return false;
}

/**
 * Get all post types
 *
 * @param string $output Value to output
 *
 * @return array Array Post types
 *
 */
function ppw_pro_get_all_post_types( $output = 'objects' ) {
	$args       = array(
		'public' => true,
	);
	$post_types = get_post_types( $args, $output );
	unset( $post_types['attachment'] );

	return $post_types;
}

/**
 * Check data before update setting
 *
 * @param $request
 * @param $setting_keys
 * @param $nonce_key
 * @param $data_key
 *
 * @return bool
 */
function ppw_pro_is_data_invalid( $request, $nonce_key, $data_key, $setting_keys = array() ) {
	if ( ! current_user_can( 'manage_options' ) ||
	     ! array_key_exists( PPW_Pro_Constants::SECURITY_CHECK, $request ) ||
	     ! array_key_exists( $data_key, $request ) ||
	     ! wp_verify_nonce( $request[ PPW_Pro_Constants::SECURITY_CHECK ], $nonce_key ) ) {
		return true;
	}

	$settings = $request[ $data_key ];
	foreach ( $setting_keys as $key ) {
		if ( ! array_key_exists( $key, $settings ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Validate data settings
 *
 * @param $data
 *
 * @return bool
 */
function ppw_pro_data_settings_invalid( $data ) {
	if ( '' !== $data[ PPW_Pro_Constants::WPP_WHITELIST_COLUMN_PROTECTIONS ] && ! is_array( $data[ PPW_Pro_Constants::WPP_WHITELIST_COLUMN_PROTECTIONS ] ) ) {
		return true;
	}

	if ( ! is_string( $data[ PPW_Pro_Constants::WPP_WHITELIST_ROLES ] ) ) {
		return true;
	}

	$white_list_role = array(
		PPW_Pro_Constants::PERMISSION_NO_ONE,
		PPW_Pro_Constants::PERMISSION_ADMIN_USER,
		PPW_Pro_Constants::PERMISSION_AUTHOR,
		PPW_Pro_Constants::PERMISSION_LOGGED_USER,
		PPW_Pro_Constants::PERMISSION_CUSTOM_ROLES,
	);
	if ( ! in_array( $data[ PPW_Pro_Constants::WPP_WHITELIST_ROLES ], $white_list_role, true ) ) {
		return true;
	}

	if ( PPW_Pro_Constants::PERMISSION_CUSTOM_ROLES === $data[ PPW_Pro_Constants::WPP_WHITELIST_ROLES ] && ( ! is_array( $data[ PPW_Pro_Constants::WPP_ROLE_SELECT ] ) || ppw_array_is_empty( ppw_array_filter( $data[ PPW_Pro_Constants::WPP_ROLE_SELECT ] ) ) ) ) {
		return true;
	}

	if ( 'true' === $data[ PPW_Pro_Constants::WPP_APPLY_PASSWORD_FOR_PAGES_POSTS ] && ( ! is_array( $data[ PPW_Pro_Constants::WPP_PAGES_POST_SELECTED ] ) || ppw_array_is_empty( ppw_array_filter( $data[ PPW_Pro_Constants::WPP_PAGES_POST_SELECTED ] ) ) ) ) {
		return true;
	}

	if ( 'true' === $data[ PPW_Pro_Constants::WPP_APPLY_PASSWORD_FOR_PAGES_POSTS ] && ( ! is_string( $data[ PPW_Pro_Constants::WPP_PASSWORD_FOR_PAGES_POSTS ] ) || ! strlen( $data[ PPW_Pro_Constants::WPP_PASSWORD_FOR_PAGES_POSTS ] ) ) ) {
		return true;
	}

	if ( ! is_string( $data[ PPW_Pro_Constants::WPP_FORM_MESSAGE ] ) || ! strlen( $data[ PPW_Pro_Constants::WPP_FORM_MESSAGE ] ) || PPW_Pro_Constants::MAX_LENGTH_FOR_MESSAGE < strlen( $data[ PPW_Pro_Constants::WPP_FORM_MESSAGE ] ) ) {
		return true;
	}

	if ( ! is_string( $data[ PPW_Pro_Constants::WPP_ERROR_MESSAGE ] ) || ! strlen( $data[ PPW_Pro_Constants::WPP_ERROR_MESSAGE ] ) || PPW_Pro_Constants::MAX_LENGTH_FOR_MESSAGE < strlen( $data[ PPW_Pro_Constants::WPP_ERROR_MESSAGE ] ) ) {
		return true;
	}

	return isset( $data[ PPW_Pro_Constants::WPP_PASSWORD_FOR_PAGES_POSTS ] ) && PPW_Pro_Constants::MAX_LENGTH_FOR_PASSWORD < strlen( $data[ PPW_Pro_Constants::WPP_PASSWORD_FOR_PAGES_POSTS ] );
}

/**
 * Validate data entire site settings
 *
 * TODO: bring it into the Validation class.
 *
 * @param array $data data from client.
 *
 * @return bool
 */
function ppw_pro_data_entire_site_settings_invalid( $data ) {
	if ( 'true' !== $data[ PPW_Constants::IS_PROTECT_ENTIRE_SITE ] ) {
		return false;
	}

	if ( ! isset( $data[ PPW_Pro_Constants::PPW_PASSWORD_FOR_ENTIRE_SITE ] ) ) {
		return true;
	}

	$password_entire_site  = $data[ PPW_Pro_Constants::PPW_PASSWORD_FOR_ENTIRE_SITE ];
	$passwords             = ppw_pro_get_string_key_in_array( $password_entire_site );
	$is_passwords_required = apply_filters( 'ppwp_sitewide_is_passwords_required', ! is_array( $passwords ) || ppw_array_is_empty( ppw_array_filter( $passwords ) ), $passwords );
	if ( $is_passwords_required ) {
		return true;
	}

	// Check whether password is valid with conditions is string, not space and its lengh is less than 100 characters.
	foreach ( $passwords as $password ) {
		if ( ! is_string( $password ) || strpos( $password, ' ' ) !== false || strlen( $password ) > 100 ) {
			return true;
		}
	}

	// Check element unique in array.
	if ( count( $passwords ) !== count( array_unique( $passwords ) ) ) {
		return true;
	}

	foreach ( $password_entire_site as $pass ) {
		if ( ! isset( $pass['redirect_url'] ) ) {
			return true;
		}

		if ( '' !== $pass['redirect_url'] && ! ppw_pro_is_valid_url( $pass['redirect_url'] ) ) {
			return true;
		}
	}

	if ( 'true' === $data[ PPW_Pro_Constants::IS_EXCLUDE_PAGE ] && (
			! is_array( $data[ PPW_Pro_Constants::PAGE_EXCLUDED ] ) ||
			ppw_array_is_empty( ppw_array_filter( $data[ PPW_Pro_Constants::PAGE_EXCLUDED ] ) )
		)
	) {
		return true;
	}

	return false;
}

/**
 * Check url is valid
 *
 * @param string $url url.
 *
 * @return false|int
 */
function ppw_pro_is_valid_url( $url ) {
	$pattern = '/^(https?|ftp):\/\/([a-zA-Z0-9.-]+(:[a-zA-Z0-9.&%$-]+)*@)*((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9][0-9]?)(\.(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9]?[0-9])){3}|([a-zA-Z0-9-]+\.)*[a-zA-Z0-9-]+\.([a-zA-Z]{1,63}))(:[0-9]+)*(\/($|[a-zA-Z0-9.,?\'\\+&%$#=~_-]+){1,256})*$/';

	return preg_match( $pattern, $url );
}

/**
 * Check permission for post type
 *
 * @param string $screen_id Current screen.
 * @param bool   $is_popup  Check screen popup.
 *
 * @return bool
 */
function ppw_pro_check_permission_for_post_type( $screen_id, $is_popup = false ) {
	$posts_type_selected = ppw_core_get_setting_type_array( PPW_Pro_Constants::WPP_WHITELIST_COLUMN_PROTECTIONS );
	array_push( $posts_type_selected, 'page', 'post' );
	$posts_type_selected = array_unique( $posts_type_selected );
	if ( $is_popup ) {
		foreach ( $posts_type_selected as $post_type ) {
			if ( 'edit-' . $post_type === $screen_id ) {
				return true;
			}
		}
	} else {
		foreach ( $posts_type_selected as $post_type ) {
			if ( $screen_id === $post_type || 'edit-' . $post_type === $screen_id ) {
				return true;
			}
		}
	}

	return false;
}

/**
 * Massage data password
 *
 * @param $advance_password Data password in database.
 *
 * @return array
 */
function ppw_pro_massage_data_password_for_api_in_meta_box( $advance_password ) {
	$new_data = array();
	foreach ( $advance_password as $data_password ) {
		$campaign_app_type = $data_password->campaign_app_type;
		if ( PPW_Pro_Constants::CAMPAIGN_TYPE['AUTO'] === $campaign_app_type || PPW_Pro_Constants::CAMPAIGN_TYPE['DEFAULT'] === $campaign_app_type || PPW_Pro_Constants::CAMPAIGN_TYPE['COMMON'] === $campaign_app_type ) {
			if ( ! isset( $new_data['global'] ) ) {
				$new_data['global'] = array();
			}
			array_push( $new_data['global'], $data_password->password );
			continue;
		}

		$types = explode( ";", $campaign_app_type );
		sort( $types );
		$campaign_app_type = implode( ";", $types );
		if ( ! isset( $new_data[ $campaign_app_type ] ) ) {
			$new_data[ $campaign_app_type ] = array();
		}
		array_push( $new_data[ $campaign_app_type ], $data_password->password );
	}

	$new_data = array_map( function ( $key, $value ) {
		return array(
			'campaign_app_type' => $key,
			'password'          => implode( ', ', $value )
		);
	}, array_keys( $new_data ), $new_data );

	return $new_data;
}

function ppw_pro_return_json_api( $status, $message, $value = '' ) {
	if ( $status ) {
		return wp_send_json(
			array(
				'isError' => $status,
				'message' => $message,
			),
			400
		);
	}

	return wp_send_json(
		array(
			'isError' => $status,
			'message' => $message,
			'value'   => $value
		)
	);
}

/**
 * Get post id follow feature protect child page
 *
 * @param $post_id
 *
 * @return mixed
 */
function ppw_pro_get_post_id_follow_protect_child_page( $post_id ) {
	$is_auto_protect_child_pages = ppw_core_get_setting_type_bool( PPW_Pro_Constants::WPP_AUTO_PROTECT_ALL_CHILD_PAGES );
	if ( $is_auto_protect_child_pages ) {
		$parents = get_post_ancestors( $post_id );
		$post_id = $parents ? $parents[ count( $parents ) - 1 ] : $post_id;
	}

	return $post_id;
}

/**
 * Set default settings
 *
 * @return bool
 */
function ppw_pro_set_default_settings() {
	$default_value = array(
		PPW_Pro_Constants::WPP_REMOVE_SEARCH_ENGINE => 'true',
		PPW_Pro_Constants::WPP_WHITELIST_ROLES      => PPW_Pro_Constants::PERMISSION_ADMIN_USER,
	);

	$settings = get_option( PPW_Pro_Constants::GENERAL_SETTING_OPTIONS );
	$options  = json_decode( $settings );
	if ( ! $settings || empty( $options ) ) {
		return update_option( PPW_Pro_Constants::GENERAL_SETTING_OPTIONS, wp_json_encode( $default_value ) );
	}

	ppw_pro_check_setting_existed_to_set_default_value( $options, $default_value );
}

/**
 * Check value existed in option
 *
 * @param object $options       Settings option.
 * @param array  $default_value Default value in settings.
 *
 * @return bool
 */
function ppw_pro_check_setting_existed_to_set_default_value( $options, $default_value ) {
	foreach ( $default_value as $key => $value ) {
		if ( isset( $options->$key ) ) {
			continue;
		}

		$options->$key = $value;
	}

	return update_option( PPW_Pro_Constants::GENERAL_SETTING_OPTIONS, wp_json_encode( $options ) );
}

/**
 * Clean data
 */
function ppw_pro_clean_data() {
	$keys = array(
		PPW_Pro_Constants::GENERAL_SET_PASSWORD_OPTIONS,
		PPW_Pro_Constants::GENERAL_SETTING_OPTIONS,
		PPW_Pro_Constants::TBL_VERSION,
		PPW_Pro_Constants::LICENSE_KEY,
		PPW_Pro_Constants::LICENSE_ERROR,
		PPW_Pro_Constants::LICENSE_OPTIONS,
		PPW_Pro_Constants::APP_ID,
		PPW_Pro_Constants::PPW_ENTIRE_SITE_OPTIONS,
	);

	if ( is_multisite() ) {
		global $wpdb;
		foreach ( get_sites() as $site ) {
			$blog_id = $site->blog_id;
			if ( ppw_core_get_setting_type_bool( PPW_Constants::REMOVE_DATA, $blog_id ) ) {
				foreach ( $keys as $key ) {
					delete_blog_option( $blog_id, $key );
				}
				$wp_prefix = $wpdb->get_blog_prefix( $blog_id );
				ppw_core_delete_data_in_post_meta_by_meta_key( PPW_Pro_Constants::POST_PROTECTION_ROLES, $wp_prefix );
				ppw_core_delete_data_in_post_meta_by_meta_key( PPW_Pro_Constants::AUTO_GENERATE_PWD_META_DATA, $wp_prefix );
				$db = new PPW_Pro_DB( $wp_prefix );
				$db->uninstall();
			}
		}
	} else {
		if ( ppw_core_get_setting_type_bool( PPW_Constants::REMOVE_DATA ) ) {
			foreach ( $keys as $key ) {
				delete_option( $key );
			}
			ppw_core_delete_data_in_post_meta_by_meta_key( PPW_Pro_Constants::POST_PROTECTION_ROLES );
			ppw_core_delete_data_in_post_meta_by_meta_key( PPW_Pro_Constants::AUTO_GENERATE_PWD_META_DATA );
			$db = new PPW_Pro_DB();
			$db->uninstall();
		}
	}
}

/**
 * Send json error for client if data error
 *
 * @param $message
 */
function send_json_data_error( $message ) {
	wp_send_json( array(
		'is_error' => true,
		'message'  => $message
	), 400 );
	wp_die();
}

/**
 * Esc message
 *
 * @param $default
 * @param $new_message
 *
 * @return mixed
 */
function ppw_pro_esc_message( $default, $new_message ) {
	$allowed_html = wp_kses_allowed_html();

	return ! is_string( $new_message ) || ! strlen( $new_message ) ? wp_kses( $default, $allowed_html ) : wp_kses( $new_message, $allowed_html );
}

/**
 * Exclude page in feature protect entire site
 *
 * @return bool
 */
function ppw_pro_exclude_page() {
	if ( get_option( PPW_Pro_Constants::PPW_ENTIRE_SITE_OPTIONS ) ) {
		$is_exclude     = ppw_pro_get_setting_entire_site_type_bool( PPW_Pro_Constants::IS_EXCLUDE_PAGE );
		$pages_selected = ppw_pro_get_setting_entire_site_type_array( PPW_Pro_Constants::PAGE_EXCLUDED );
	} else {
		$is_exclude     = ppw_core_get_setting_entire_site_type_bool( PPW_Pro_Constants::IS_EXCLUDE_PAGE );
		$pages_selected = ppw_core_get_setting_entire_site_type_array( PPW_Pro_Constants::PAGE_EXCLUDED );
	}

	if ( ! $is_exclude ) {
		return false;
	}

	if ( is_home() ) {
		return in_array( 'ppwp_home_page', $pages_selected );
	}

	global $post;
	if ( is_null( $post ) || ! is_object( $post ) ) {
		return false;
	}

	return in_array( $post->ID, $pages_selected );
}

/**
 * Add <meta name="robots" content="noindex,follow"/> to header
 */
function ppw_pro_add_tag_meta_no_index_to_head() {
	if ( ! is_singular() || ! ppw_core_get_setting_type_bool( PPW_Pro_Constants::WPP_REMOVE_SEARCH_ENGINE ) ) {
		return;
	}

	global $post;
	$services = new PPW_Pro_Password_Services();
	if ( ! $services->is_protected_content( $post->ID ) ) {
		return;
	} ?>
	<meta name="robots" content="noindex,follow"/>
	<?php
}

/**
 * Get new option entire site settings
 *
 * @param      $name_settings
 * @param bool $blog_id
 *
 * @return string|null|array
 */
function ppw_pro_get_entire_site_settings( $name_settings, $blog_id = false ) {
	$options        = ! $blog_id ? get_option( PPW_Pro_Constants::PPW_ENTIRE_SITE_OPTIONS, false ) : get_blog_option( $blog_id, PPW_Pro_Constants::PPW_ENTIRE_SITE_OPTIONS, false );
	$default_result = null;
	if ( ! $options ) {
		return $default_result;
	}

	if ( ! isset( $options[ $name_settings ] ) ) {
		return $default_result;
	}

	return $options[ $name_settings ];
}

/**
 * Get entire site setting type is bool
 *
 * @param      $name_settings
 * @param bool $blog_id
 *
 * @return bool
 */
function ppw_pro_get_setting_entire_site_type_bool( $name_settings, $blog_id = false ) {
	$setting = ppw_pro_get_entire_site_settings( $name_settings, $blog_id );

	return 'true' === $setting;
}

/**
 * Get entire site setting type is string
 *
 * @param      $name_settings
 * @param bool $blog_id
 *
 * @return string
 */
function ppw_pro_get_setting_entire_site_type_string( $name_settings, $blog_id = false ) {
	$setting = ppw_pro_get_entire_site_settings( $name_settings, $blog_id );

	return is_string( $setting ) ? $setting : '';
}

/**
 * Get entire site setting type is array
 *
 * @param      $name_settings
 * @param bool $blog_id
 *
 * @return array
 */
function ppw_pro_get_setting_entire_site_type_array( $name_settings, $blog_id = false ) {
	$setting = ppw_pro_get_entire_site_settings( $name_settings, $blog_id );

	return is_array( $setting ) ? $setting : array();
}

/**
 * Check is protect entire site
 *
 * @return bool
 */
function ppw_pro_check_is_protect_entire_site() {
	if ( get_option( PPW_Pro_Constants::PPW_ENTIRE_SITE_OPTIONS ) ) {
		return ppw_pro_get_setting_entire_site_type_bool( PPW_Constants::IS_PROTECT_ENTIRE_SITE );
	}

	return ppw_core_get_setting_entire_site_type_bool( PPW_Constants::IS_PROTECT_ENTIRE_SITE );
}

/**
 * Get post id
 *
 * @return bool|string
 */
function ppw_pro_get_post_id() {
	global $post;
	if ( is_null( $post ) || ! is_object( $post ) || ! is_singular() ) {
		return false;
	}

	return $post->ID;
}

/**
 * Encrypt/Decrypt data via base64
 *
 * @param string       $action Crypto action.
 * @param array|string $data   Input data.
 *
 * @return string|object|null
 */
function ppw_encrypt_decrypt( $action, $data ) {
	if ( 'encrypt' === $action ) {
		return base64_encode( json_encode( $data ) );
	} elseif ( 'decrypt' === $action ) {
		return json_decode( base64_decode( $data ) );
	}

	return null;
}

/**
 * Get and convert key to string in array
 *
 * @param array $input input array.
 *
 * @return array
 */
function ppw_pro_get_string_key_in_array( $input ) {
	return array_map(
		function ( $element ) {
			return (string) $element;
		},
		array_keys( $input )
	);
}

/**
 * Convert string to positive int array.
 *
 * @param string $str Input string.
 *
 * @return array Array element abs positive integer.
 */
function ppw_pro_convert_string_to_positive_array( $str ) {
	$arr = explode( ',', $str );
	if ( empty( $arr ) ) {
		return array();
	}


	return array_filter(
		$arr,
		function ( $value ) {
			return intval( $value ) > 0;
		}
	);
}

/**
 * WP introduced is_wp_version_compatible function from version 5.2.0 only.
 * (https://developer.wordpress.org/reference/functions/is_wp_version_compatible/)
 * Need to write the helper by our-self.
 *
 * @param string $required Version to check.
 *
 * @return bool
 */
function ppw_pro_is_wp_version_compatible( $required ) {
	return empty( $required ) || version_compare( get_bloginfo( 'version' ), $required, '>=' );
}

/**
 * List post type protection
 *
 * @return array
 */
function ppw_pro_get_post_type_protection() {
	$post_types = ppw_core_get_setting_type_array( PPW_Pro_Constants::WPP_WHITELIST_COLUMN_PROTECTIONS );
	array_push( $post_types, 'post', 'page' );

	return $post_types;
}

/**
 * Get page title for home, category, tag or post
 * If any logic or bug fix updates in this function, please modify the function ppw_get_page_title in Free version
 *
 * @return string
 */
function ppw_pro_get_page_title() {
	// If Free version created ppw_get_page_title function then use it.
	if ( function_exists( 'ppw_get_page_title' ) ) {
		return ppw_get_page_title();
	}

	$site_title       = get_bloginfo( 'title' );
	$site_description = get_bloginfo( 'description' );
	$post_title       = wp_title( '', false ); // Post title, category tile, tag title.
	$dash_score_site  = '' === $site_title || '' === $site_description ? '' : ' – ';
	$dash_score_post  = '' === $site_title || '' === $post_title ? '' : ' – ';

	return is_home() || is_front_page()
		? sprintf( '%1$s%2$s%3$s', $site_title, $dash_score_site, $site_description )
		: sprintf( '%1$s%2$s%3$s', $post_title, $dash_score_post, $site_title );
}

/**
 * Check status post and user has permission edit post
 *
 * @param string|int $post_id The post ID.
 *
 * @return bool
 */
function ppw_pro_has_permission_edit_post( $post_id ) {
	$allowed = current_user_can( 'edit_post', $post_id ) && false === wp_check_post_lock( $post_id );

	return $allowed && ppw_pro_should_enable_feature();
}

/**
 * Clear cache for Cache plugin, includes: WP Super Cache, WP Fastest Cache and W3 Total Cache
 *
 * @param int|string $post_id The post ID.
 *
 * @return mixed
 */
function ppw_pro_clear_cache_by_id( $post_id ) {
	// Clear cache for WP Super Cache plugin.
	if ( function_exists( 'prune_super_cache' ) && function_exists( 'get_supercache_dir' ) ) {
		global $blog_cache_dir;
		prune_super_cache( $blog_cache_dir, true );
		prune_super_cache( get_supercache_dir(), true );
	}

	// Clear cache for WP Fastest Cache plugin.
	do_action( 'wpfc_clear_post_cache_by_id', false, $post_id );

	// Clear cache for W3 Total Cache plugin.
	if ( function_exists( 'w3tc_pgcache_flush_post' ) ) {
		// Main site.
		w3tc_pgcache_flush_post( $post_id );
	}

	// Sub site.
	do_action( 'w3tc_flush_url', get_permalink( $post_id ) );
}

/**
 * Get current roles if user is login.
 *
 * @return array Current roles.
 */
function ppw_pro_get_current_user_roles() {
	if ( ! is_user_logged_in() ) {
		return array();
	}

	$user  = wp_get_current_user();
	$roles = ( array ) $user->roles;

	if ( is_multisite() && is_super_admin( wp_get_current_user()->ID ) ) {
		$roles[] = 'administrator';
	}

	return $roles;
}

/**
 * Get current user name.
 *
 * @return string
 */
function ppw_pro_get_current_user_name() {
	$current_user = wp_get_current_user();
	return 0 === $current_user->ID ? 'N/A' : $current_user->user_login;
}

/**
 * Should enable feature on main-site and single site.
 *
 * @return bool
 */
function ppw_pro_should_enable_feature() {
	return apply_filters( 'ppwp_should_enable_feature', ! is_multisite() || ( is_multisite() && is_main_site() ) );
}

/**
 * Get campaign app types with the scalability.
 *
 * @since 1.2.2
 *
 * @return mixed
 */
function ppw_pro_get_map_types() {
	return apply_filters( PPW_Pro_Constants::HOOK_PPWP_PWD_TYPE_MAP, PPW_Pro_Constants::MAP_CAMPAIGN_TYPE );
}

/**
 * Generate the a href link for reusing.
 *
 * @param string $message Link text.
 * @param string $url     Link url.
 *
 * @return string
 */
function ppw_pro_generate_link( $message, $url ) {
	return sprintf( '<a target="_blank" rel="noopener" href=%1$s>%2$s</a>', $url, $message );

}
