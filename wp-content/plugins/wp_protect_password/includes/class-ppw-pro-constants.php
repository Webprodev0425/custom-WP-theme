<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.buildwps.com/
 * @since      1.0.0
 *
 * @package    Prevent_ur_pages
 * @subpackage Prevent_ur_pages/include
 */

/**
 *
 * Defines the Constants
 *
 * @package    Prevent_ur_pages
 * @subpackage Prevent_ur_pages/include
 * @author     Bwps <support@bwps.us>
 */

if ( ! class_exists( 'PPW_Pro_Constants' ) ) {
	/**
	 * Constants helper class
	 *
	 * Class PPW_Pro_Constants
	 */
	class PPW_Pro_Constants {
		const ROLE_POST_PASS_COOKIE = 'wp-postpass-role_';

		const WP_POST_PASS = 'wp-postpass_';

		const GOLD_PASS_COOKIE = 'wp-goldpass-pagepost_';

		const PPW_ENTIRE_SITE_OPTIONS = 'ppw_entire_site_options';

		//phpcs:ignore
		const PDA_OPTIONS = array(
			'GENERAL_SET_PASSWORD_OPTIONS' => 'wp_protect_password_set_password_options',
			'GENERAL_SETTING_OPTIONS'      => 'wp_protect_password_setting_options',
		);

		//phpcs:ignore
		const GENERAL_SET_PASSWORD_OPTIONS = self::PDA_OPTIONS['GENERAL_SET_PASSWORD_OPTIONS'];

		//phpcs:ignore
		const GENERAL_SETTING_OPTIONS = self::PDA_OPTIONS['GENERAL_SETTING_OPTIONS'];

		const POST_PROTECTION_ROLES = 'post_protection_roles';

		const CUSTOM_POST_TABLE_COLUMN_NAME = 'pda_password_protection';

		const CUSTOM_POST_TABLE_COLUMN_TITLE = 'Password Protection';

		const AUTO_GENERATE_PWD_META_DATA = 'auto-generate-pwd';

		const AUTO_GENERATE_PWD_FORM_NONCE = 'auto-generate-pwd-nonce';

		const TBL_VERSION = 'pda-pwd-tbl-version';

		const TBL_NAME = 'pda_passwords';

		const PREFIX_PWD = 'PDA_PWD';

		//phpcs:ignore
		const CAMPAIGN_APP = array(
			'AC' => 'ActiveCampaign',
		);

		const COOKIE_NAME_WP_PROTECT_PASSWORD = 'pda_protect_password';

		const MULTIPLE_PASSWORDS_POST_META = 'wp_protect_password_multiple_passwords';

		const LICENSE_OPTIONS = 'wp_protect_password_licensed';

		const LICENSE_NOT_ACTIVATED = 'Please enter your license to activate our powerful Pro features!';

		const LICENSE_FORM_NONCE = 'wp_protect_password_license_form_nonce';

		const LICENSE_KEY = 'wp_protect_password_license_key';

		const APP_ID = 'wp_protect_password_app_id';

		const LICENSE_ERROR = 'wp_protect_password_license_error';

		const CUSTOM_CATEGORY_TABLE_COLUMN_NAME = 'wp_protect_password_protect-category';

		const UPDATE_PROTECT_CATEGORY_FORM_NONCE = 'update-protect-category-nonce';

		const PROTECT_PWD_BTN = 'Password protect';

		const MANAGE_PWD_BTN = 'Manage passwords';

		//phpcs:ignore
		const PROTECTION_STATUS = array(
			'protect'   => 1,
			'unprotect' => 0,
		);

		const PROTECT_LABEL = 'Protect';

		const UNPROTECT_LABEL = 'Unprotect';

		//phpcs:ignore
		const CAMPAIGN_TYPE = array(
			'ROLE'            => 'Role_',
			'DEFAULT'         => 'Default',
			'ACTIVE_CAMPAIGN' => 'ActiveCampaign',
			'AUTO'            => 'Auto',
			'COMMON'          => 'Common',
			'SHORTCODE'       => 'PCPShortcode',
			'SHORTCODE_ROLE'  => 'PCPShortcodeRole_',

		);

		// @deprecated Use ppw_pro_get_map_types instead of.
		//phpcs:ignore
		const MAP_CAMPAIGN_TYPE = array(
			'Default'        => 'Global (default)',
			'Auto'           => 'Global (custom)',
			'ActiveCampaign' => 'ActiveCampaign',
			'Common'         => 'Global (shared)',
		);

		/**
		 * Get pwd map types to show in UI.
		 *
		 * @return mixed
		 */
		public static function get_map_pwd_types() {
			$types = array(
				'Default'        => 'Global (default)',
				'Auto'           => 'Global (custom)',
				'ActiveCampaign' => 'ActiveCampaign',
				'Common'         => 'Global (shared)',
			);

			return apply_filters( self::HOOK_PPWP_PWD_TYPE_MAP, $types );
		}

		const WPP_WHITELIST_COLUMN_PROTECTIONS = 'wpp_whitelist_column_protections';

		const WPP_WHITELIST_ROLES = 'wpp_whitelist_roles';

		//TODO: do not use.
		const WPP_PASSWORD_COOKIE_EXPIRED = 'wpp_password_cookie_expired';

		const WPP_ROLE_ACCESS = 'wpp_roles_access';

		const WPP_ROLE_SELECT = 'wpp_roles_select';

		const WPP_PAGES_POST_SELECTED = 'wpp_pages_posts_select';

		const WPP_APPLY_PASSWORD_FOR_PAGES_POSTS = 'wpp_apply_password_for_pages_posts';

		const WPP_PASSWORD_FOR_PAGES_POSTS = 'wpp_password_for_pages_posts';

		const WPP_AUTO_PROTECT_ALL_CHILD_PAGES = 'wpp_auto_protect_all_child_pages';

		const WPP_REMOVE_DATA = 'wpp_remove_data';

		const WPP_ERROR_MESSAGE = 'wpp_error_message';

		const WPP_FORM_MESSAGE = 'wpp_form_message';

		const WPP_UPDATE_DEFAULT_SETTING = 'wpp_update_default_setting';

		const WPP_MAX_COOKIE_EXPIRED = 365;

		const IS_EXCLUDE_PAGE = 'ppwp_switch_exclude_page';

		const PAGE_EXCLUDED = 'ppwp_page_exclude';

		const EXCLUDE_HOME_PAGE = 'ppwp_home_page';

		const WPP_DEFAULT_FORM_MESSAGE = 'This content is password protected. To view it please enter your password below:';

		const WPP_DEFAULT_ERROR_MESSAGE = 'Please enter the correct password!';

		const WPP_REMOVE_SEARCH_ENGINE = 'ppwp_remove_search_engine';

		const DOMAIN = 'password-protect-page';

		const WPP_UNLOCK_ALL_PROTECTED_SECTIONS = 'unlock_multiple_protected_section';

		//phpcs:ignore
		const DEFAULT_VALUE_SETTINGS = array(
			self::WPP_WHITELIST_COLUMN_PROTECTIONS => array( 'post', 'page' ),
			self::WOOCOMMERCE_PLUGIN_NAME          => array(
				self::WPP_WHITELIST_COLUMN_PROTECTIONS => array( 'post', 'page', 'product' ),
			),
		);

		const WOOCOMMERCE_PLUGIN_NAME = 'woocommerce';

		//phpcs:ignore
		const PLUGINS_PATH = array(
			self::WOOCOMMERCE_PLUGIN_NAME => 'woocommerce/woocommerce.php',
		);

		const WOO_PRODUCT = 'product';

		const WPP_SCREEN_SETTING_PAGE = 'toplevel_page_wp_protect_password_options';

		const WPP_PROTECT_PASSWORD_OPTIONS = 'wp_protect_password_options';

		const WPP_PCP_PASSWORD_OPTIONS = 'wp_protect_password_pcp_options';

		const SECURITY_CHECK = 'security_check';

		const DATA_SETTINGS = 'settings';

		const DATA_LICENSE = 'license';

		const LICENSE_MODULE = 'license';

		const PCP_MODULE = 'pcp_passwords';

		const WPP_DATA_FREE_MIGRATED = 'ppw_free_data_migrated';

		const MIGRATED_DEFAULT_PW = 'migrated_default_pw';

		const MESSAGE_CREATE_PASSWORD_ERROR = 'Opps! Unable to create new password. Please try again or contact plugin owner.';

		const MESSAGE_CREATE_PASSWORD_SUCCESS = 'Great! You’ve successfully created a new secure password.';

		const MESSAGE_EMPTY_PASSWORD = 'Please remove spaces in password!';

		const MESSAGE_DELETE_PASSWORD_ERROR = 'Opps! Unable to delete the password. Please try again or contact plugin owner.';

		const MESSAGE_DELETE_PASSWORDS_ERROR = 'Opps! Unable to delete the passwords. Please try again or contact plugin owner.';

		const MESSAGE_DELETE_PASSWORD_SUCCESS = 'Cool! You’ve successfully deleted the password';

		const MESSAGE_DELETE_PASSWORDS_SUCCESS = 'Cool! You’ve successfully deleted the passwords';

		const MESSAGE_UPDATE_PASSWORD_ERROR = 'Opps! Unable to update the password. Please try again or contact plugin owner.';

		const MESSAGE_UPDATE_PASSWORD_SUCCESS = 'Cool! You’ve successfully updated the password data.';

		const MESSAGE_DUPLICATE_PASSWORD = 'The password is already in use. Please create a new one.';

		const MESSAGE_DUPLICATE_PASSWORD_ENTIRE_SITE = 'You can\'t create duplicate password. Please try again.';

		const MESSAGE_DUPLICATE_MULTIPLE_PASSWORD = 'You can\'t create duplicate passwords. Please try again.';

		const MESSAGE_CREATE_MULTIPLE_PASSWORDS_SUCCESS = 'Great! You’ve successfully created new secure passwords.';

		//phpcs:ignore
		const DB_DATA_COLUMN_TABLE = array(
			array(
				'old_version' => '1.0',
				'new_version' => '1.1',
				'value'       => 'hits_count mediumint(9) NOT NULL',
			),
			array(
				'old_version' => '1.1',
				'new_version' => '1.2',
				'value'       => 'is_default tinyint(1) DEFAULT 0',
			),
			array(
				'old_version' => '1.2',
				'new_version' => '1.3',
				'value'       => 'expired_date BIGINT DEFAULT NULL',
			),
			array(
				'old_version' => '1.3',
				'new_version' => '1.4',
				'value'       => 'usage_limit mediumint(9)',
			),
		);

		//phpcs:ignore
		const DB_UPDATE_COLUMN_TABLE = array(
			array(
				'old_version' => '1.4',
				'new_version' => '1.5',
				'value'       => "campaign_app_type campaign_app_type text DEFAULT '' NULL",
			),
			array(
				'old_version' => '1.5',
				'new_version' => '1.6',
				'value'       => "password password varchar(255) DEFAULT '' NULL",
			),
		);

		const IS_ERROR = 'is_error';

		const MESSAGE = 'message';

		const VALUE = 'value';

		const PW = 'password';

		const MIGRATED_FREE_FLAG = 'migrated_free';

		const FUNCTION_TO_HANDLE_META_BOX = 'ppw_pro_render_form_set_password_meat_box';

		const COLUMN_MODULE = 'column';

		const EDIT_CATEGORY_PAGE = 'edit-category';

		const EDIT_CATEGORY_MODULE = 'category-column';

		const PPW_PASSWORD_FOR_ENTIRE_SITE = 'ppw_password_entire_site';

		//phpcs:ignore
		#region Hook
		const HOOK_CUSTOM_HEADER_FORM_ENTIRE_SITE = 'ppw_custom_header_form_entire_site';

		const HOOK_CUSTOM_STYLE_FORM_ENTIRE_SITE = 'ppw_custom_style_form_entire_site';

		const HOOK_CUSTOM_SCRIPT_FORM_ENTIRE_SITE = 'ppw_custom_script_form_entire_site';

		const HOOK_ENTIRE_SITE_HANDLE_BEFORE_REDIRECT = 'ppwp_entire_site_handle_before_redirect';

		const HOOK_CUSTOM_MESSAGE_WRONG_PASSWORD_ENTIRE_SITE = 'ppw_custom_message_wrong_password_entire_site';

		const HOOK_CUSTOM_ENTIRE_SITE_LOGIN_FORM = 'ppw_custom_entire_site_login_form';

		const HOOK_PPWP_POST_PASSWORD_REQUIRED = 'ppwp_post_password_required';

		const HOOK_PPWP_BADGE_PROTECTION = 'ppwp_badge_protection';

		const HOOK_CUSTOM_ENTIRE_SITE_BODY_CLASS = 'ppwp_custom_body_entire_site';

		const HOOK_ENTIRE_SITE_AFTER_CHECK_VALID_PASSWORD = 'ppwp_entire_site_after_check_valid_password';

		const HOOK_PCP_AFTER_CHECK_VALID_PASSWORD = 'ppwp_pcp_after_check_valid_password';

		const HOOK_CUSTOM_PROTECTED_ID = 'ppwp_custom_protected_id';

		const HOOK_UNLOCK_PDA_FILE = 'ppwp_unlock_pda_file';

		const HOOK_BEFORE_HANDLE_SEARCH_REPLACE = 'ppwp_before_handle_search_replace';

		const HOOK_PPWP_PWD_TYPES = 'ppwp_pro_password_types';

		const HOOK_PPWP_PWD_TYPE_MAP = 'ppwp_pro_password_type_map';
		//phpcs:ignore #endregion


		const PERMISSION_NO_ONE = 'blank';

		const PERMISSION_ADMIN_USER = 'admin_users';

		const PERMISSION_AUTHOR = 'author';

		const PERMISSION_LOGGED_USER = 'logged_users';

		const PERMISSION_CUSTOM_ROLES = 'custom_roles';

		const MAX_LENGTH_FOR_MESSAGE = 200;

		const MAX_LENGTH_FOR_PASSWORD = 100;

		const BYPASS_PARAM = 'ppwp_ac';

		const BYPASS_TYPE = 'bypass';

		const MULTIPLE_PASSWORDS_KEY = 'multiple_passwords';

		const GLOBAL_POST_PASSWORD_REQUIRED = 'ppwp_is_valid_post_password_required';

		const UPDATE_VERSION = 'ppw_pro_version_update_services';

		const ENTIRE_SITE_REDIRECTION = 'ppw_redirection';

		const SHORT_CODE_THE_CONTENT_TYPE = '';

		const SHORTCODE_LINK_EXPIRED = 'ppwp_shortcode';

		const CF_SHORTCODE_CLASS_NAME = 'ppw-restricted-content-cf';

		const CF_SHORTCODE_FORM_TYPE = 'cf';

		const SHORTCODE_MODULE = 'shortcodes';

		const SHORTCODE_TEMPLATE_ATTR_FORMAT = "template='%s_%s'";

		const USER_SUBSCRIBE = 'ppwp_pro_subscribe';

		const SUBSCRIBE_FORM_NONCE = 'ppwp_pro_subscribe_form_nonce';

		const ENTIRE_SITE_TYPE = 'sitewide';

		const PCP_TYPE = 'pcp';

		const WOO_STORE_PAGE = 'woo_store_page';

		const PDA_ORIGIN_LINK_TOKEN = 'ppwp_token';

		const PDA_TOKEN_POST_FIX = '-ppwp';

		const PDA_TOKEN_NONCE_ACTION = 'ppwp-pda-unlock-file-';

		const CONTEXT_SITEWIDE_PASSWORD_FORM = 'SWP Pro';

		//phpcs:ignore
		#region Default
		const DEFAULT_SUBMIT_LABEL = 'Enter';

		const DEFAULT_PASSWORD_LABEL = 'Password:';

		const DEFAULT_PLACEHOLDER = '';

		const DEFAULT_IS_SHOW_PASSWORD = 0;

		const DEFAULT_FORM_TRANSPARENCY = '';

		const DEFAULT_FORM_WIDTH = '';

		const DEFAULT_FORM_BACKGROUND_COLOR = '#ffffff';

		const DEFAULT_FORM_PADDING_LEFT = '';

		const DEFAULT_FORM_PADDING_TOP = '';

		const DEFAULT_FORM_PADDING_RIGHT = '';

		const DEFAULT_FORM_PADDING_BOTTOM = '';

		const DEFAULT_PASSWORD_LABEL_FONT_SIZE = '';

		const DEFAULT_PASSWORD_LABEL_FONT_WEIGHT = '';

		const DEFAULT_PASSWORD_LABEL_FONT_COLOR = '';

		const DEFAULT_BORDER_RADIUS = '';

		const DEFAULT_CONTENT_TEXT = '';

		const DEFAULT_TEXT_FONT_SIZE = '';

		const DEFAULT_TEXT_FONT_WEIGHT = '';

		const DEFAULT_TEXT_FONT_COLOR = '';

		const DEFAULT_ERROR_TEXT_FONT_SIZE = 13;

		const DEFAULT_ERROR_TEXT_FONT_WEIGHT = '';

		const DEFAULT_ERROR_TEXT_FONT_COLOR = '#dc3232';

		const DEFAULT_BUTTON_TEXT_FONT_SIZE = '';

		const DEFAULT_BUTTON_TEXT_FONT_COLOR = '#ffffff';

		const DEFAULT_BUTTON_BACKGROUND_COLOR = '#0085ba';

		const DEFAULT_BUTTON_TEXT_HOVER_COLOR = '';

		const DEFAULT_BUTTON_BACKGROUND_HOVER_COLOR = '';

		const DEFAULT_BUTTON_WIDTH = '';

		const DEFAULT_BUTTON_HEIGHT = '';

		const DEFAULT_SITEWIDE_BACKGROUND_IMAGE = '';

		const DEFAULT_SITEWIDE_BACKGROUND_COLOR = '#ededf0';

		const DEFAULT_LOGO_CUSTOMIZE_IMAGE = PPW_PRO_DIR_URL . 'includes/views/entire-site/assets/ppwp-logo.png';

		const DEFAULT_LOGO_CUSTOMIZE_DISABLE = 0;

		const DEFAULT_LOGO_CUSTOMIZE_WIDTH = '';

		const DEFAULT_LOGO_CUSTOMIZE_HEIGHT = '';

		const DEFAULT_LOGO_CUSTOMIZE_BORDER_RADIUS = '';

		const CONTEXT_PASSWORD_FORM = 'PPF';

		const DEFAULT_SHOW_PASSWORD_TEXT = 'Show password';

		const DEFAULT_SHOW_PASSWORD_TEXT_SIZE = 13;

		const DEFAULT_SHOW_PASSWORD_TEXT_COLOR = '#72777c';

		//phpcs:ignore #endregion

		// @deprecated:
		const SITEWIDE_SC = 'ppwp-swf';

		const SITE_WIDE_SC = 'ppwp_sitewide';

		const ELEMENTOR = 'elementor';

		const BEAVER_BUILDER = 'bb';
	}
}
