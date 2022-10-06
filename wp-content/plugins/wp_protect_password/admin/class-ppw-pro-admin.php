<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://passwordprotectwp.com/
 * @since      1.0.0
 *
 * @package    Password_Protect_Page_Pro
 * @subpackage Password_Protect_Page_Pro/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Password_Protect_Page_Pro
 * @subpackage Password_Protect_Page_Pro/admin
 * @author     BWPS <hello@bwps.com>
 */
class PPW_Pro_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Settings service
	 * @var PPW_Pro_Settings_Services
	 */
	private $settings_service;

	/**
	 * License service
	 * @var PPW_Pro_License_Services
	 */
	private $license_service;

	/**
	 * Password services
	 *
	 * @var PPW_Pro_Password_Services
	 */
	private $password_services;

	/**
	 * Update services
	 *
	 * @var PPW_Pro_Update_Services
	 */
	private $update_services;

	/**
	 * Column services
	 *
	 * @var PPW_Pro_Column_Services
	 */
	private $column_services;

	/**
	 * Column services
	 *
	 * @var PPW_Pro_Column_Services
	 */
	private $category_services;

	/**
	 * Shortcode services
	 *
	 * @var PPW_Pro_Shortcode
	 */
	private $shortcode_services;

	/**
	 * Add-on services
	 *
	 * @var PPW_Pro_Add_On_Services
	 */
	private $add_on_services;

	/**
	 * Subscribe services
	 *
	 * @var PPW_Pro_Password_Subscribe
	 */
	private $subscribe_services;

	/**
	 * Asset service in Free version
	 *
	 * @var PPW_Asset_Services
	 */
	private $free_asset_services;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		$this->license_service     = new PPW_Pro_License_Services();
		$this->settings_service    = new PPW_Pro_Settings_Services();
		$this->password_services   = new PPW_Pro_Password_Services();
		$this->column_services     = new PPW_Pro_Column_Services();
		$this->category_services   = new PPW_Pro_Category_Services();
		$this->update_services     = new PPW_Pro_Update_Services();
		$this->shortcode_services  = new PPW_Pro_Shortcode();
		$this->add_on_services     = new PPW_Pro_Add_On_Services();
		$this->subscribe_services  = new PPW_Pro_Password_Subscribe();
		$this->free_asset_services = new PPW_Asset_Services( null, null );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_assets() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Password_Protect_Page_Pro_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Password_Protect_Page_Pro_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if ( $this->license_service->is_valid_license() && function_exists( 'get_current_screen' ) ) {
			$assert_services = new PPW_Pro_Asset_Services( get_current_screen()->id, $_GET );
			$assert_services->load_assets_for_general_tab();
			$assert_services->load_assets_for_entire_site_tab();
			$assert_services->load_assets_for_shortcode_tab();
			$assert_services->load_assets_for_category_page();
			$assert_services->load_js_show_notice_deactivate_plugin();
			$assert_services->load_css_for_child_page_meta_box();
			$assert_services->load_assets_for_column();
			$assert_services->load_asserts_for_pcp_passwords();
		}
	}

	/**
	 * Handle admin init
	 */
	public function handle_admin_init() {
		// TODO: Add to service
		// Create database table.
		$db = new PPW_Pro_DB();
		$db->install();

		// Set default setting.
		ppw_pro_set_default_settings();

		// Convert data.
		$this->update_services->convert_data_entire_site();

		// Re-run the data free migration.
		if ( ! $this->license_service->is_valid_license() || PPW_Options_Services::get_instance()->get_flag( PPW_Pro_Constants::MIGRATED_FREE_FLAG ) ) {
			return;
		}

		global $migration_service;
		$migration_service->start_run();
	}

	/**
	 * Set default settings tab
	 *
	 * @param $default_tab
	 *
	 * @return bool
	 */
	public function set_default_settings_tab( $default_tab ) {
		return $this->settings_service->set_default_tab();
	}

	/**
	 * Add new settings tab
	 *
	 * @param $tabs
	 *
	 * @return array
	 */
	public function add_new_settings_tab( $tabs ) {
		$tabs = $this->settings_service->add_pcp_passwords_tab( $tabs );
		$tabs = $this->settings_service->add_license_tab( $tabs );

		return $tabs;
	}

	/**
	 * Handle custom tab content
	 *
	 * @param $tabs
	 *
	 * @return mixed
	 */
	public function handle_custom_tab_content( $tabs ) {
		return $this->settings_service->declare_custom_tab_hooks( $tabs );
	}

	/**
	 * Handle license content
	 */
	public function handle_license_content() {
		$this->settings_service->render_license_content();
	}

	/**
	 * Handle license content
	 */
	public function handle_pcp_content() {
		$this->settings_service->render_pcp_passwords_content();
	}

	/**
	 * Check plugin license
	 */
	public function handle_license() {
		$this->license_service->check_license( $_REQUEST );
	}

	/**
	 * @return bool
	 */
	public function is_activated() {
		return true;
	}

	/**
	 * Render entire site content
	 */
	public function handle_render_entire_site_content() {
		$this->settings_service->render_entire_site_content();
	}

	/**
	 * Render entire site content
	 */
	public function handle_render_general_content() {
		$this->settings_service->render_general_content();
	}

	/**
	 * Change plugin info
	 *
	 * @return array
	 */
	public function change_plugin_info() {
		return array(
			'name'    => PPW_PRO_NAME,
			'version' => PPW_PRO_VERSION,
		);
	}

	/**
	 * Handle update entire settings
	 */
	public function handle_entire_site_settings() {
		$this->settings_service->update_entire_site_settings( $_REQUEST );
	}

	/**
	 * Exclude page for feature protect entire site
	 *
	 * @return bool
	 */
	public function do_not_render_form_free_version() {
		if ( get_option( PPW_Pro_Constants::PPW_ENTIRE_SITE_OPTIONS ) ) {
			return false;
		}

		$post_id = ppw_pro_get_post_id();
		if ( $this->password_services->is_whitelist_roles( $post_id ) ) {
			return false;
		}

		return $this->settings_service->exclude_page_in_entire_site_feature();
	}

	/**
	 * Custom header for form entire site
	 *
	 * Needless fix format for this function, because this format for browser.
	 */
	public function custom_header_form_entire_site() {
		// Need to check whether the entire site free from is loading to prevent duplicated form assets (js, css).
		$is_rendering_free_form = apply_filters( PPW_Constants::HOOK_BEFORE_RENDER_FORM_ENTIRE_SITE, true );
		if ( $is_rendering_free_form ) {
			return;
		}

		$page_title = ppw_pro_get_page_title();
		?>
		<meta name='robots' content='noindex,follow'/>
		<meta charset="utf-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<meta name="description" content=""/>
		<meta name="viewport" content="width=device-width"/>
		<link rel="icon" href="<?php echo esc_attr( get_site_icon_url() ); ?>"/>
		<title><?php echo esc_attr( $page_title ); ?></title>
		<link rel="stylesheet" href="<?php echo PPW_PRO_VIEW_URL; ?>entire-site/assets/ppw-form-entire-site.css"
		      type="text/css"/>
		<script type="text/javascript"
		        src="<?php echo PPW_PRO_VIEW_URL; ?>entire-site/assets/ppw-form-entire-site.js"></script>
		<?php
	}

	/**
	 * Change text for feature Remove data
	 *
	 * @return array
	 */
	public function custom_text_feature_remove_data() {
		return array(
			'label'       => 'Remove Data Upon Uninstall',
			'description' => 'Remove your license and ALL related data upon uninstall. Your license may not be used on this website again or elsewhere anymore.',
		);
	}

	/**
	 * Update settings
	 */
	public function handle_settings() {
		$this->settings_service->update_general_settings( $_REQUEST );
	}

	/**
	 * Customize password form message
	 *
	 * @param $default
	 *
	 * @return string
	 */
	public function customize_password_form_message( $default ) {
		$new_message = ppw_core_get_setting_type_string( PPW_Pro_Constants::WPP_FORM_MESSAGE );

		return ppw_pro_esc_message( $default, $new_message );
	}

	/**
	 * Customize entering wrong password message
	 *
	 * @param $default
	 *
	 * @return string
	 */
	public function customize_entering_wrong_password_message( $default ) {
		$new_message = ppw_core_get_setting_type_string( PPW_Pro_Constants::WPP_ERROR_MESSAGE );

		return ppw_pro_esc_message( $default, $new_message );
	}

	/**
	 * Render UI for set password in metabox
	 * @return string
	 */
	public function set_password_in_meta_box() {
		include_once PPW_PRO_VIEW_PATH . 'meta-box/view-ppw-pro-meta-box.php';

		return PPW_Pro_Constants::FUNCTION_TO_HANDLE_META_BOX;
	}

	/**
	 * Return position render feature protect in meta box
	 *
	 * @param $positions
	 *
	 * @return mixed
	 */
	public function custom_meta_box_position( $positions ) {
		return $this->column_services->handle_meta_box_position( $positions );
	}

	/**
	 * Register API
	 */
	public function rest_api_init() {
		$api = new PPW_Pro_Api;
		$api->register_rest_routes();
	}

	/**
	 * Check password is valid.
	 *
	 * @param boolean $is_valid      Password is valid.
	 * @param string  $password      Password.
	 * @param integer $post_id       Post ID.
	 * @param array   $current_roles List current roles.
	 *
	 * @return bool
	 * @since 1.1 Wrap function to password service class.
	 *
	 * @since 1.0 Init function.
	 */
	public function check_password_is_valid( $is_valid, $password, $post_id, $current_roles ) {
		return $this->password_services->check_password_is_valid( $password, $post_id, $current_roles );
	}

	/**
	 * Check password before render content
	 *
	 * @param string $content post content.
	 * @param int    $post_id post id.
	 *
	 * @return mixed
	 * @deprecated Because we only use post_password_required to show login form and from 1.2.2 PPW version.
	 *
	 */
	public function check_password_before_render_content( $content, $post_id ) {
		return $this->password_services->check_password_before_render_content( $content, $post_id );
	}

	/**
	 * Integrate with PDA Gold.
	 *
	 * @param array $data       All urls file in the content.
	 * @param array $conditions Check condition to replace.
	 *
	 * @return mixed
	 */
	public function integrate_with_pda_gold( $data, $conditions = null ) {
		return $this->password_services->handle_replace_urls( $data, $conditions );
	}

	/**
	 * Add column
	 *
	 * @param $columns
	 *
	 * @return mixed
	 */
	public function post_custom_column( $columns ) {
		return $this->column_services->add_column( $columns );
	}

	/**
	 * Render content for column
	 *
	 * @param $column
	 * @param $post_id
	 *
	 * @return mixed
	 */
	public function post_custom_column_content( $column, $post_id ) {
		return $this->column_services->render_post_column_content( $column, $post_id );
	}

	/**
	 * Custom position hide feature set default password in WordPress
	 *
	 * @param $positions
	 *
	 * @return array
	 */
	public function custom_hide_default_pw_wp_position( $positions ) {
		return $this->column_services->custom_position_hide_default_pw_wp( $positions );
	}

	/**
	 * Custom post type for feature migration
	 *
	 * @param $types
	 *
	 * @return mixed
	 */
	public function add_post_types( $types ) {
		if ( PPW_Options_Services::get_instance()->get_flag( PPW_Pro_Constants::MIGRATED_FREE_FLAG ) ) {
			return $this->column_services->custom_post_type_for_feature_migration( $types );
		}

		return $types;
	}

	/**
	 * @param $deprecated
	 * @param $column_name
	 * @param $term_id
	 *
	 * @return bool
	 */
	public function render_category_custom_column( $deprecated, $column_name, $term_id ) {
		return $this->column_services->custom_category_column( $column_name, $term_id );
	}

	/**
	 * @param $columns
	 *
	 * @return mixed
	 */
	public function add_column_to_category_table( $columns ) {
		return $this->column_services->add_column_to_category_table( $columns );
	}

	/**
	 * Update category protect
	 */
	public function update_category_protect() {
		return $this->category_services->update_category_protect_response();
	}

	/**
	 * Admin notices
	 */
	public function handle_admin_notices_license() {
		PPW_Pro_Notice_Services::get_instance()->show_license_notice();
	}

	/**
	 * Show message by admin_notices
	 */
	public function handle_admin_notices_message() {
		PPW_Pro_Notice_Services::get_instance()->notice_integrate_with_pda_gold();
		PPW_Pro_Notice_Services::get_instance()->notice_integrate_with_multisite();
	}

	/**
	 * Check password for product
	 */
	public function check_password_before_render_product() {
		if ( defined( 'PPW_VERSION' ) && version_compare( PPW_VERSION, '1.2.2' ) >= 0 ) {
			return;
		}

		return $this->password_services->handle_before_render_product();
	}

	/**
	 * Custom header follow feature Block Search Indexing
	 */
	public function custom_header() {
		$this->password_services->custom_header_for_file_protected();
	}

	/**
	 * Whether post requires password and correct password has been provided.
	 *
	 * @param boolean     $required Whether the user needs to supply a password. True if password has not been
	 *                              provided or is incorrect, false if password has been supplied or is not required.
	 * @param object|null $post     Post data.
	 *
	 * @return bool false if a password is not required or the correct password cookie is present, true otherwise.
	 */
	public function handle_post_password_required( $required, $post ) {
		if ( empty( $post->ID ) ) {
			return $required;
		}

		$post_global = PPW_Pro_Constants::GLOBAL_POST_PASSWORD_REQUIRED . '-' . $post->ID;
		if ( ! isset( $GLOBALS[ $post_global ] ) ) {
			$GLOBALS[ $post_global ] = $this->password_services->handle_post_password_required( $post->ID, $required );
		}

		return $GLOBALS[ $post_global ];
	}

	/**
	 * Check condition and block entire site
	 */
	public function handle_protect_entire_site() {
		$has_bypass = apply_filters( 'ppwp_sitewide_has_bypass', false );
		if ( $has_bypass ) {
			return;
		}

		$entire_site_passwords = apply_filters( 'ppwp_sitewide_passwords', false );
		if ( ! $entire_site_passwords ) {
			$entire_site_passwords = ppw_pro_get_setting_entire_site_type_array( PPW_Pro_Constants::PPW_PASSWORD_FOR_ENTIRE_SITE );
		}
		$passwords = ppw_pro_get_string_key_in_array( $entire_site_passwords );
		if ( $this->settings_service->entire_site_is_render_content( $passwords ) ) {
			return;
		}

		do_action( 'ppwp_sitewide_handle_before_valid_password', $passwords, $entire_site_passwords, $this->settings_service );

		// TODO: need to bring $_REQUEST['input_wp_protect_password'] as a parameter.
		if ( isset( $_REQUEST['input_wp_protect_password'] ) ) {
			$input_password = wp_unslash( $_REQUEST['input_wp_protect_password'] );
			$password       = $this->settings_service->entire_site_is_valid_password( $input_password, $passwords, $entire_site_passwords );
			if ( false !== $password ) {
				do_action( 'ppwp_sitewide_handle_after_valid_password', $input_password, $passwords );
				$this->settings_service->entire_site_redirect_after_enter_password( $password, $entire_site_passwords );
				die();
			}
		}

		$body_class = apply_filters( PPW_Pro_Constants::HOOK_CUSTOM_ENTIRE_SITE_BODY_CLASS, 'ppwp-sitewide-protection' );

		echo '<html>
		<head>
';
		do_action( PPW_Pro_Constants::HOOK_CUSTOM_HEADER_FORM_ENTIRE_SITE );
		echo '
		<style>
			body {
				font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
		  	}
';
		do_action( PPW_Pro_Constants::HOOK_CUSTOM_STYLE_FORM_ENTIRE_SITE );
		echo '
		</style>
		</head>
		<body class=' . $body_class . '>';
		include PPW_PRO_DIR_PATH . 'includes/views/entire-site/view-ppw-pro-form-password.php';
		echo apply_filters( PPW_Pro_Constants::HOOK_CUSTOM_ENTIRE_SITE_LOGIN_FORM, entire_site_render_login_form() );
		echo '	</body>
</html>';
		die();
	}

	/**
	 * Exclude meta key for page/post status
	 *
	 * @param $protected
	 * @param $meta_key
	 * @param $meta_type
	 *
	 * @return bool
	 */
	function handle_is_protected_meta( $protected, $meta_key, $meta_type ) {
		if ( $meta_key === PPW_Pro_Constants::AUTO_GENERATE_PWD_META_DATA ) {
			return true;
		}

		return $protected;
	}

	/**
	 * Check condition S&R for password.
	 *
	 * @param array $conditions Conditions to check plugin.
	 * @param array $data       Data from PDA Gold.
	 *
	 * @return array Conditions handle search and replace for password.
	 */
	public function check_condition_for_search_and_replace( $conditions, $data ) {
		return $this->password_services->is_handle_search_and_replace( $conditions, $data );
	}

	/**
	 * Add field copy shortcode for feature Password Protect Files
	 */
	public function add_shortcode_to_settings() {
		include_once PPW_PRO_VIEW_PATH . 'shortcode/view-ppw-pro-shortcode.php';
	}

	/**
	 * Custom post type protection for master password
	 *
	 * @param array $posts_type List post type support in Free version.
	 *
	 * @return array
	 */
	public function custom_post_type_for_master_password( $posts_type ) {
		return ppw_pro_get_post_type_protection();
	}

	/**
	 * Custom field password shortcode for Beaver Builder plugin
	 *
	 * @param array $general_fields List field in Free version.
	 *
	 * @return array Beaver Builder fields.
	 */
	public function custom_field_for_beaver_builder( $general_fields ) {
		return $this->add_on_services->handle_field_for_beaver_builder( $general_fields );
	}

	/**
	 * Callback function of hook ppw_shortcode_beaver_builder_attributes.
	 * It will add template attributes to current shortcode then.
	 *
	 * @param string $shortcode The current shortcode.
	 * @param array  $settings  The Beaver Builder settings.
	 *
	 * @return string
	 */
	public function handle_shortcode_beaver_builder_attributes( $shortcode, $settings ) {
		return $this->add_on_services->add_template_attribute( $shortcode, $settings, 'bb' );
	}

	/**
	 * Custom field password shortcode for Elementor plugin
	 *
	 * @param array $controls Controls of elementor.
	 *
	 * @return array Elementor controls.
	 */
	public function custom_field_for_elementor( $controls ) {
		return $this->add_on_services->handle_field_for_elementor( $controls );
	}

	/**
	 * Add side for shortcode tab
	 */
	public function add_sidebar_shortcode() {
		$this->settings_service->render_sidebar_shortcode();
	}

	/**
	 * Handle subscriber request
	 */
	public function handle_subscribe_request() {
		return $this->subscribe_services->handle_subscribe_request( $_REQUEST );
	}

	/**
	 * Add action protect/unprotect
	 *
	 * @param array    $actions An array of row action links.
	 * @param stdClass $post    The post object.
	 *
	 * @return array
	 */
	public function custom_row_action( $actions, $post ) {
		$post_status = $post->post_status;
		$post_type   = $post->post_type;
		$post_id     = $post->ID;
		if ( ! in_array( $post_type, ppw_pro_get_post_type_protection(), true ) || 'trash' === $post_status || ! ppw_pro_has_permission_edit_post( $post_id ) ) {
			return $actions;
		}

		wp_enqueue_script( 'ppw-pro-row-action-js', PPW_PRO_DIR_URL . 'admin/js/class-ppw-pro-row-action.js', array( 'jquery' ), PPW_PRO_VERSION, true );
		wp_localize_script(
			'ppw-pro-row-action-js',
			'ppw_row_action_data',
			array(
				'ajax_url'    => admin_url( 'admin-ajax.php' ),
				'nonce'       => wp_create_nonce( 'wp_rest' ),
				'plugin_name' => PPW_PRO_NAME,
			)
		);
		$this->free_asset_services->load_toastr_lib();

		return $this->password_services->add_row_action( $actions, $post );
	}

	/**
	 * Handle feature update post status in row action
	 */
	public function handle_update_post_status() {
		return $this->password_services->update_post_status( $_REQUEST );
	}

	/**
	 * Callback function to handle protected file of pda_after_check_file_exist filter.
	 *
	 * @param bool $valid         Is valid to return the protected file.
	 * @param int  $attachment_id The attachment ID.
	 *
	 * @return bool
	 */
	public function handle_protected_file( $valid, $attachment_id ) {
		return $this->password_services->handle_protected_file( $valid, $attachment_id );
	}

	/**
	 * Call back function to handle protected file of ppwp_unlock_pda_file filter.
	 *
	 * @param bool $is_valid      Is valid to return the protected file.
	 * @param int  $post_id       The post ID.
	 * @param int  $attachment_id The attachment ID.
	 *
	 * @return bool
	 */
	public function handle_unlock_pda_file( $is_valid, $post_id, $attachment_id ) {
		return $this->password_services->handle_unlock_pda_file( $is_valid, $post_id );
	}

	/**
	 * Call back function to handle protected file of ppwp_before_handle_search_replace filter.
	 *
	 * @param bool $result  Result to know has handle search and replace.
	 * @param int  $post_id The post ID.
	 *
	 * @return bool
	 */
	public function handle_search_replace_for_master_password( $result, $post_id ) {
		return $this->password_services->is_search_replace_master_password( $result, $post_id );
	}

	/**
	 * Callback function to handle shortcode attributes when elementor render the Widget.
	 *
	 * @param string $shortcode The shortcode template.
	 * @param array  $settings  The Elementor Widget settings.
	 *
	 * @return string
	 */
	public function handle_shortcode_elementor_attributes( $shortcode, $settings ) {
		return $this->add_on_services->add_template_attribute( $shortcode, $settings, 'elementor' );
	}

	/**
	 * Callback function to handle logic to check whether the shortcode content is empty.
	 *
	 * @param bool   $is_empty Is empty content.
	 * @param string $content  The shortcode content.
	 * @param array  $attrs    The shortcode attributes.
	 *
	 * @return bool
	 */
	public function handle_shortcode_empty_content_logic( $is_empty, $content, $attrs ) {
		return $this->shortcode_services->is_empty_shortcode( $is_empty, $content, $attrs );

	}
}
