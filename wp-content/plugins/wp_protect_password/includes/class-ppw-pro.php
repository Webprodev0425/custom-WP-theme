<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://passwordprotectwp.com/
 * @since      1.0.0
 *
 * @package    Password_Protect_Page_Pro
 * @subpackage Password_Protect_Page_Pro/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Password_Protect_Page_Pro
 * @subpackage Password_Protect_Page_Pro/includes
 * @author     BWPS <hello@bwps.com>
 */
class Password_Protect_Page_Pro {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      PPW_Pro_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Class PPW_Pro_License_Services
	 *
	 * @var PPW_Pro_License_Services class PPW_Pro_License_Services.
	 */
	protected $license_service;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'PASSWORD_PROTECT_PAGE_PRO_VERSION' ) ) {
			$this->version = PPW_PRO_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'password-protect-page-pro';
		$this->load_dependencies();
		$this->license_service = new PPW_Pro_License_Services();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Password_Protect_Page_Pro_Loader. Orchestrates the hooks of the plugin.
	 * - Password_Protect_Page_Pro_i18n. Defines internationalization functionality.
	 * - Password_Protect_Page_Pro_Admin. Defines all hooks for the admin area.
	 * - Password_Protect_Page_Pro_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ppw-pro-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ppw-pro-i18n.php';

		/**
		 * The class responsible for defining all plugin constants.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ppw-pro-constants.php';

		/**
		 * The class responsible for defining all helper functions.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ppw-pro-functions.php';

		$this->load_migration_services();

		/**
		 * The class create database.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ppw-pro-db.php';

		/**
		 * The class responsible for defining all API.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ppw-pro-api.php';

		/**
		 * The class connect database.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ppw-pro-repository.php';

		/**
		 * The class responsible for base service class
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/services/class-ppw-pro-base.php';

		/**
		 * The class responsible for plugin compatibility checker.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/services/common/abstract-ppw-pro-plugin-compatibility.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/services/common/class-ppw-pro-plugin-compatibility.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/services/class-ppw-pro-assets.php';

		/**
		 * The class responsible for defining all license services.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/services/class-ppw-pro-licenses.php';

		/**
		 * The class responsible for defining all notice services.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/services/class-ppw-pro-notices.php';

		/**
		 * The class responsible for defining all setting services.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/services/class-ppw-pro-settings.php';

		/**
		 * The class responsible for token services.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/services/class-ppw-pro-token.php';

		/**
		 * The class responsible for defining all password services.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/services/class-ppw-pro-passwords.php';

		/**
		 * The class responsible for defining all subscribe services.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/services/class-ppw-pro-subscribe.php';

		/**
		 * The helper class responsible for Beaver Builder template getters.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/addons/beaver-builder/class-ppw-pro-beaver-helper.php';

		/**
		 * The class responsible for defining all add-on services.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/services/class-ppw-pro-add-on.php';

		/**
		 * The class responsible for defining all column services.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/services/class-ppw-pro-column.php';

		/**
		 * The class responsible for defining all update services.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/services/class-ppw-pro-update.php';

		/**
		 * The class responsible for shortcode handler.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/services/class-ppw-pro-shortcode.php';

		/**
		 * The class responsible for defining all plugin update services.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'yme-plugin-update-checker/plugin-update-checker.php';

		/**
		 * Ymese plugin's sdk
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'yme-wp-plugins-sdk/require.php';


		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-ppw-pro-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-ppw-pro-public.php';

		/**
		 * The class responsible for defining all actions that occur in the customizer
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/services/class-ppw-pro-customizer.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/customizers/class-ppw-pro-text-editor-control.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/customizers/class-ppw-pro-toggle-control.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/customizers/class-ppw-pro-control-title-group.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/services/class-ppw-pro-category.php';

		/**
		 * The class to provide services for old pda-stats
		 * side of the site.
		 *
		 * @deprecated
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/deprecated/class-wp-protect-password-service.php';

		/**
		 * The class to provide functions for old pda-stats
		 * side of the site.
		 *
		 * @deprecated
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/deprecated/class-wp-protect-password-functions.php';

		/**
		 * The class to provide repository for old other plugin
		 * side of the site.
		 *
		 * @deprecated
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/deprecated/class-wp-protect-password-repo.php';

		/**
		 * The class to provide CONSTANTS for old other plugin
		 * side of the site.
		 *
		 * @deprecated
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/deprecated/class-wp-protect-password-constants.php';

		/**
		 * The class to provide Elementor Addons to integrate.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/addons/elementor/class-ppw-pro-elementor.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/addons/beaver-builder/class-ppw-pro-beaver-loader.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/services/class-ppw-pro-customizer.php';


		// Shorcode.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/shortcodes/abstract-service-ppwp-shortcode.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/shortcodes/class-service-ppwp-sidewide-shortcode.php';


		$this->loader = new PPW_Pro_Loader();


	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Password_Protect_Page_Pro_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new PPW_Pro_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new PPW_Pro_Admin( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_assets' );

		if ( $this->license_service->is_valid_license() ) {

			$this->loader->add_action( 'admin_init', $plugin_admin, 'handle_admin_init' );

			//phpcs:ignore
			#region Settings
			$this->loader->add_filter( 'ppw_is_pro_activate', $plugin_admin, 'is_activated' );
			$this->loader->add_filter( 'ppw_plugin_info', $plugin_admin, 'change_plugin_info' );
			$this->loader->add_filter( 'wp_ajax_ppw_pro_handle_entire_site_settings', $plugin_admin, 'handle_entire_site_settings' );

			$this->loader->add_filter( 'ppw_render_content_general', $plugin_admin, 'handle_render_general_content' );
			$this->loader->add_filter( 'ppw_render_content_entire_site', $plugin_admin, 'handle_render_entire_site_content' );
			$this->loader->add_filter( 'ppw_render_content_pcp_passwords', $plugin_admin, 'handle_pcp_content' );
			//phpcs:ignore #endregion

			$this->loader->add_filter( 'ppw_before_render_form_entire_site', $plugin_admin, 'do_not_render_form_free_version' );
			$this->loader->add_filter( 'ppw_custom_header_form_entire_site', $plugin_admin, 'custom_header_form_entire_site' );

			$this->loader->add_filter( 'ppw_custom_text_feature_remove_data', $plugin_admin, 'custom_text_feature_remove_data' );

			$this->loader->add_action( 'wp_ajax_ppw_pro_handle_settings', $plugin_admin, 'handle_settings' );
			$this->loader->add_filter( 'ppwp_customize_password_form_message', $plugin_admin, 'customize_password_form_message' );
			$this->loader->add_filter( 'ppwp_text_for_entering_wrong_password', $plugin_admin, 'customize_entering_wrong_password_message' );

			$this->loader->add_filter( 'ppw_function_handle_meta_box', $plugin_admin, 'set_password_in_meta_box' );
			$this->loader->add_filter( 'ppw_meta_box_position', $plugin_admin, 'custom_meta_box_position' );

			$this->loader->add_action( 'rest_api_init', $plugin_admin, 'rest_api_init', 10, 2 );

			$this->loader->add_action( 'ppw_post_types', $plugin_admin, 'add_post_types' );

			$this->loader->add_filter( 'ppw_check_password_is_valid', $plugin_admin, 'check_password_is_valid', 10, 4 );
			$this->loader->add_filter( 'ppw_check_password_before_render_content', $plugin_admin, 'check_password_before_render_content', 10, 2 );
			$this->loader->add_filter( 'pda_the_content', $plugin_admin, 'integrate_with_pda_gold', 150, 10, 1 );
			$this->loader->add_filter( 'pda_before_the_content', $plugin_admin, 'check_condition_for_search_and_replace', 15, 2 );

			$this->loader->add_action( 'pda_after_check_file_exist', $plugin_admin, 'handle_protected_file', 10, 2 );

			$this->loader->add_filter( 'ppw_hide_default_pw_wp_position', $plugin_admin, 'custom_hide_default_pw_wp_position' );

			$this->loader->add_action( 'woocommerce_before_single_product', $plugin_admin, 'check_password_before_render_product' );

			//phpcs:ignore
			#region column popup
			$columns = array(
				'posts',
				'pages',
			);
			foreach ( $columns as $column ) {
				$this->loader->add_filter( 'manage_' . $column . '_columns', $plugin_admin, 'post_custom_column' );
				$this->loader->add_action( 'manage_' . $column . '_custom_column', $plugin_admin, 'post_custom_column_content', 10, 2 );
			}

			//phpcs:ignore
			#region row actions
			$this->loader->add_action( 'post_row_actions', $plugin_admin, 'custom_row_action', 10, 2 );
			$this->loader->add_action( 'page_row_actions', $plugin_admin, 'custom_row_action', 10, 2 );
			$this->loader->add_filter( 'wp_ajax_ppw_pro_update_post_status', $plugin_admin, 'handle_update_post_status' );
			//phpcs:ignore #endregion

			//phpcs:ignore
			#region category column
			$this->loader->add_filter( 'manage_category_custom_column', $plugin_admin, 'render_category_custom_column', 10, 3 );
			$this->loader->add_filter( 'manage_edit-category_columns', $plugin_admin, 'add_column_to_category_table', 10 );
			$this->loader->add_filter( 'wp_ajax_ppw_pro_update_category_protect', $plugin_admin, 'update_category_protect' );
			//phpcs:ignore #endregion

			//phpcs:ignore
			#region Block Search Indexing
			$this->loader->add_action( 'wp_head', $plugin_admin, 'custom_header' );
			//phpcs:ignore #endregion

			//phpcs:ignore
			#region Post Password Required
			$this->loader->add_filter( 'post_password_required', $plugin_admin, 'handle_post_password_required', 10, 2 );
			//phpcs:ignore #endregion

			//phpcs:ignore
			#region Form Entire Site
			$this->loader->add_action( 'template_redirect', $plugin_admin, 'handle_protect_entire_site', 15 );
			//phpcs:ignore #endregion

			//phpcs:ignore
			#region Change status meta
			$this->loader->add_filter( 'is_protected_meta', $plugin_admin, 'handle_is_protected_meta', 10, 3 );
			//phpcs:ignore #endregion

			//phpcs:ignore
			#region Handle admin notices
			$this->loader->add_filter( 'admin_notices', $plugin_admin, 'handle_admin_notices_message' );
			//phpcs:ignore #endregion

			//phpcs:ignore
			#region ppwp shortcode
			// This filter applies in check password of shortcode API.
			$shortcode_service = PPW_Pro_Shortcode::get_instance();
			$shortcode_service->apply_filters( $this->loader );
			//phpcs:ignore #endregion

			//phpcs:ignore
			#region ppwp addons
			PPW_Pro_Elementor::get_instance( $this->loader );

			// TODO: Seem we don't use it anymore. Can remove.
			PPW_Pro_Beaver_Loader::get_instance();
			$this->loader->add_filter( 'ppw_shortcode_elementor_controls', $plugin_admin, 'custom_field_for_elementor', 10, 1 );
			$this->loader->add_filter( 'ppw_shortcode_beaver_builder_general_fields', $plugin_admin, 'custom_field_for_beaver_builder', 10, 1 );
			//phpcs:ignore #endregion

			//phpcs:ignore
			#region add shortcode to shortcode tab in settings page.
			$this->loader->add_action( 'ppw_shortcode_settings_extend', $plugin_admin, 'add_shortcode_to_settings' );
			//phpcs:ignore #endregion

			//phpcs:ignore
			#region custom post type for master password.
			$this->loader->add_filter( 'ppw_master_passwords_valid_post_types', $plugin_admin, 'custom_post_type_for_master_password' );
			//phpcs:ignore #endregion

			//phpcs:ignore
			#region Add sidebar for shortcode tab
			$this->loader->add_action( 'ppw_sidebar_shortcode', $plugin_admin, 'add_sidebar_shortcode' );
			$this->loader->add_action( 'wp_ajax_ppwp_pro_subscribe_request', $plugin_admin, 'handle_subscribe_request' );
			//phpcs:ignore #endregion

			//phpcs:ignore
			#region Handle master password for search and replace
			$this->loader->add_filter( PPW_Pro_Constants::HOOK_UNLOCK_PDA_FILE, $plugin_admin, 'handle_unlock_pda_file', 10, 3 );
			$this->loader->add_filter( PPW_Pro_Constants::HOOK_BEFORE_HANDLE_SEARCH_REPLACE, $plugin_admin, 'handle_search_replace_for_master_password', 10, 2 );
			//phpcs:ignore #endregion

			PPW_Pro_Customizer_Service::get_instance();
		} else {
			//phpcs:ignore
			#region set default tab
			$this->loader->add_filter( 'ppw_default_tab', $plugin_admin, 'set_default_settings_tab' );
			$this->loader->add_filter( 'admin_notices', $plugin_admin, 'handle_admin_notices_license' );
			//phpcs:ignore #endregion set default tab
		}

		//phpcs:ignore
		#region License
		$this->loader->add_filter( 'ppw_add_new_tab', $plugin_admin, 'add_new_settings_tab' );
		$this->loader->add_filter( 'ppw_custom_tab', $plugin_admin, 'handle_custom_tab_content' );
		$this->loader->add_filter( 'ppw_render_content_license', $plugin_admin, 'handle_license_content' );
		$this->loader->add_action( 'wp_ajax_ppw_pro_check_license', $plugin_admin, 'handle_license' );
		//phpcs:ignore #endregion License
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new PPW_Pro_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		if ( $this->license_service->is_valid_license() ) {
			// Hook to handle bypass URL.
			$this->loader->add_action( 'template_redirect', $plugin_public, 'ppwp_redirect', 10 );

			// Hook handle before render password form for woo product.
			$this->loader->add_filter( 'ppw_handle_before_render_woo_product', $plugin_public, 'ppw_handle_before_render_woo_product', 10, 2 );

			//phpcs:ignore
			#region Handle hide protected content.
			$this->loader->add_filter( 'ppw_custom_post_type_for_hide_protected_post', $plugin_public, 'custom_post_type_hide_protected_post' );
			$this->loader->add_filter( 'ppw_custom_post_type_for_recent_post', $plugin_public, 'custom_post_type_recent_post_and_next_previous' );
			$this->loader->add_filter( 'ppw_custom_post_type_for_next_and_previous', $plugin_public, 'custom_post_type_recent_post_and_next_previous' );
			$this->loader->add_filter( 'ppw_custom_post_id_for_hide_protected_post', $plugin_public, 'custom_post_id_hide_protected_post' );
			// Handle the descendants of the protected pages from the above hook.
			$this->loader->add_filter( 'wpseo_exclude_from_sitemap_by_post_ids', $plugin_public, 'custom_post_id_hide_protected_post_and_child', 15 );
			$this->loader->add_filter( 'ppw_custom_option_hide_protect_content', $plugin_public, 'custom_option_hide_protect_content', 10, 2 );
			$this->loader->add_filter( 'ppw_custom_positions_for_hide_protected_post', $plugin_public, 'custom_positions_hide_protected_post', 10, 2 );
			$this->loader->add_filter( 'ppw_custom_default_options_for_hide_protected_post', $plugin_public, 'custom_default_options_hide_protected_post', 10, 2 );
			$this->loader->add_action( 'woocommerce_product_query', $plugin_public, 'handle_woocommerce_product_query' );
			//phpcs:ignore #endregion Handle hide protected content.

			$this->loader->add_action( 'init', $plugin_public, 'register_shortcodes' );

		}
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 * @since     1.0.0
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    PPW_Pro_Loader    Orchestrates the hooks of the plugin.
	 * @since     1.0.0
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Load dependence for migration services
	 */
	private function load_migration_services() {
		$service_dir = PPW_PRO_DIR_PATH . 'includes/services';
		require_once "$service_dir/class-ppw-pro-migrations.php";
		require_once "$service_dir/class-ppw-pro-migration-manager.php";
		require_once "$service_dir/class-ppw-pro-cpt-migrations.php";
		require_once "$service_dir/class-ppw-pro-cpt-manager.php";
	}

}
