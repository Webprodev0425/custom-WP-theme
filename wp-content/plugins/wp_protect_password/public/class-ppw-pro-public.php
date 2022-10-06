<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://passwordprotectwp.com/
 * @since      1.0.0
 *
 * @package    Password_Protect_Page_Pro
 * @subpackage Password_Protect_Page_Pro/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Password_Protect_Page_Pro
 * @subpackage Password_Protect_Page_Pro/public
 * @author     BWPS <hello@bwps.com>
 */
class PPW_Pro_Public {

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
	 * Class PPW_Pro_Password_Services
	 *
	 * @var PPW_Pro_Password_Services Class PPW_Pro_Password_Services.
	 */
	private $password_services;

	/**
	 * Class PPW_Pro_Settings_Services
	 *
	 * @var PPW_Pro_Settings_Services Class PPW_Pro_Settings_Services.
	 */
	private $setting_services;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version     The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name       = $plugin_name;
		$this->version           = $version;
		$this->password_services = new PPW_Pro_Password_Services();
		$this->setting_services  = new PPW_Pro_Settings_Services();
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

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
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

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
	}

	/**
	 * Check bypass URL
	 */
	public function ppwp_redirect() {
		$this->password_services->handle_bypass_url();
	}

	/**
	 * Handle before render woo product
	 *
	 * @param bool   $is_valid  Is post type is product.
	 * @param string $post_type Post type.
	 *
	 * @return bool
	 */
	public function ppw_handle_before_render_woo_product( $is_valid, $post_type ) {
		return false;
	}

	/**
	 * Custom post type for feature Hide protected post.
	 *
	 * @param array $post_type List default post type from Free version.
	 *
	 * @return array
	 */
	public function custom_post_type_hide_protected_post( $post_type ) {
		return ppw_pro_get_post_type_protection();
	}

	/**
	 * Custom post type for feature Hide protected post in recent post and "Next + Previous".
	 *
	 * @param array $post_type List default post type from Free version.
	 *
	 * @return mixed
	 */
	public function custom_post_type_recent_post_and_next_previous( $post_type ) {
		$post_types = ppw_core_get_setting_type_array( PPW_Pro_Constants::WPP_WHITELIST_COLUMN_PROTECTIONS );
		array_push( $post_types, 'post' );

		return $post_types;
	}

	/**
	 * Custom list protected id for feature Hide protected post
	 *
	 * @param array $list_post_id List protected ID.
	 *
	 * @return array
	 */
	public function custom_post_id_hide_protected_post( $list_post_id ) {
		return $this->setting_services->get_protected_id_by_whitelist_roles();
	}

	/**
	 * This callback that helps to add the descendants of protected pages.
	 *
	 * @param array $list_post_id List protected ID.
	 *
	 * @return array
	 */
	public function custom_post_id_hide_protected_post_and_child( $list_post_id ) {
		if ( empty( $list_post_id ) ) {
			return $list_post_id;
		}

		$is_auto_protect_all_child_pages = ppw_core_get_setting_type_bool( PPW_Pro_Constants::WPP_AUTO_PROTECT_ALL_CHILD_PAGES );
		if ( false === $is_auto_protect_all_child_pages ) {
			return $list_post_id;
		}

		$ids = array();
		foreach ( $list_post_id as $id ) {
			$childs = $this->password_services->get_all_id_child_page( $id );
			$ids    = array_merge( $ids, $childs );
		}

		return array_merge( $list_post_id, $ids );
	}

	/**
	 * Change status for option Hide
	 *
	 * @param string $status    Status of options: disable or ''.
	 * @param string $post_type The post type name.
	 *
	 * @return string
	 */
	public function custom_option_hide_protect_content( $status, $post_type ) {
		return $this->setting_services->handle_status_option_hide_protect_content( $status, $post_type );
	}

	/**
	 * Custom position for feature Hide protected post
	 *
	 * @param array  $positions List default position.
	 * @param string $post_type The post type name.
	 *
	 * @return array
	 */
	public function custom_positions_hide_protected_post( $positions, $post_type ) {
		return $this->setting_services->handle_positions_hide_protected_post( $positions, $post_type );
	}

	/**
	 * Custom default option for feature Hide protected post follow post type.
	 *
	 * @param array  $positions List default position.
	 * @param string $post_type The post type name.
	 *
	 * @return array
	 */
	public function custom_default_options_hide_protected_post( $positions, $post_type ) {
		return $this->setting_services->handle_default_options_hide_protected_post( $positions, $post_type );
	}

	/**
	 * Custom query product WooCommerce
	 *
	 * @param WP_Query $query Query instance.
	 */
	public function handle_woocommerce_product_query( $query ) {
		$this->setting_services->handle_hide_protected_product( $query );
	}

	/**
	 * Register the plugin shortcodes.
	 */
	public function register_shortcodes() {
		PPWP_Pro_SideWide::get_instance();
	}
}
