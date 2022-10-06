<?php
if ( ! class_exists( 'PPW_Pro_Asset_Services' ) ) {

	/**
	 * Class to manage asserts in front end.
	 *
	 * Class PPW_Pro_Asset_Services
	 */
	class PPW_Pro_Asset_Services {

		/**
		 * Current screen
		 *
		 * @var The screen needs to load asserts
		 */
		private $screen;

		/**
		 * Page name of current screen
		 *
		 * @var string The current page needs to load asserts
		 */
		private $page;

		/**
		 * Tab name of current screen
		 *
		 * @var The current tab in setting page.
		 */
		private $tab;

		/**
		 * PPW_Pro_Asset_Services constructor.
		 *
		 * @param string $screen     The current screen.
		 * @param array  $get_params The params in GET request.
		 */
		public function __construct( $screen, $get_params ) {
			$this->screen = $screen;
			if ( isset( $get_params['page'] ) ) {
				$this->page = $get_params['page'];
			}
			if ( isset( $get_params['tab'] ) ) {
				$this->tab = $get_params['tab'];
			}
		}

		/**
		 * Loading assert (js and css) for the license tab.
		 */
		public function load_asset_for_license_tab() {
			$module = PPW_Pro_Constants::LICENSE_MODULE;
			$this->load_shared_css( PPW_PRO_VERSION );
			$this->load_js( $module, PPW_PRO_VERSION );
			wp_localize_script(
				"ppw-pro-$module-js",
				'ppw_license_data',
				array(
					'ajax_url' => admin_url( 'admin-ajax.php' ),
				)
			);

			$asset_services = new PPW_Asset_Services( null, null );
			$asset_services->load_toastr_lib();
		}

		/**
		 * Render css and js for entire site tab
		 */
		public function load_assets_for_entire_site_tab() {
			$module = PPW_Constants::ENTIRE_SITE_MODULE;
			if ( PPW_Constants::MENU_NAME === $this->page && 'entire_site' === $this->tab ) {
				$this->load_jquery_ui();
				$this->load_shared_css( PPW_PRO_VERSION );
				$this->load_js( $module, PPW_PRO_VERSION );
				wp_localize_script(
					"ppw-pro-$module-js",
					'ppw_entire_site_data',
					array(
						'ajax_url'          => admin_url( 'admin-ajax.php' ),
						'duplicate_message' => PPW_Pro_Constants::MESSAGE_DUPLICATE_PASSWORD_ENTIRE_SITE,
						'space_password'    => PPW_Pro_Constants::MESSAGE_EMPTY_PASSWORD,
					)
				);

				$asset_services = new PPW_Asset_Services( null, null );
				$asset_services->load_select2_lib();
				$asset_services->load_toastr_lib();
			}
		}

		/**
		 * Render css and js for shortcode tab
		 */
		public function load_assets_for_shortcode_tab() {
			$module = PPW_Pro_Constants::SHORTCODE_MODULE;
			if ( PPW_Constants::MENU_NAME === $this->page && $module === $this->tab ) {
				$this->load_shared_css( PPW_PRO_VERSION );
			}
		}

		/**
		 * Render css and js for general tab
		 */
		public function load_assets_for_general_tab() {
			$module = PPW_Constants::GENERAL_SETTINGS_MODULE;
			if ( PPW_Constants::MENU_NAME === $this->page && ( $module === $this->tab || null === $this->tab ) ) {
				$this->load_shared_css( PPW_PRO_VERSION );
				$this->load_js( $module, PPW_PRO_VERSION );
				wp_localize_script(
					"ppw-pro-$module-js",
					'ppw_pro_setting_data',
					array(
						'ajax_url'  => admin_url( 'admin-ajax.php' ),
						'post_type' => ppw_pro_get_post_type_protection(),
					)
				);

				$asset_services = new PPW_Asset_Services( null, null );
				$asset_services->load_select2_lib();
				$asset_services->load_toastr_lib();
			}
		}

		/**
		 * Render css and js for meta box
		 */
		public function load_assets_for_meta_box() {
			$module = PPW_Constants::META_BOX_MODULE;
			$this->load_css( $module, PPW_PRO_VERSION );
			$this->load_js( $module, PPW_PRO_VERSION );
			wp_localize_script( "ppw-pro-$module-js", 'ppwp_data', array(
				'home_url'   => is_ssl() ? home_url( '/', 'https' ) : home_url( '/' ),
				'roles'      => array_keys( get_editable_roles() ),
				'nonce'      => wp_create_nonce( 'wp_rest' ),
				'plugin_url' => PPW_PRO_DIR_URL,
				'api_url'    => get_rest_url(),
			) );
		}

		/**
		 * Load css for child page in meta box
		 */
		public function load_css_for_child_page_meta_box() {
			if ( ! ppw_core_get_setting_type_bool( PPW_Pro_Constants::WPP_AUTO_PROTECT_ALL_CHILD_PAGES ) ) {
				return;
			}

			global $post;
			if ( ! is_null( $post ) && $post->post_parent ) {
				wp_enqueue_style( 'ppw-pro-child-page-meta-box-css', PPW_PRO_VIEW_URL . 'meta-box/ppw-pro-child-page-meta-box.css', array(), PPW_PRO_VERSION, 'all' );
			}
		}

		/**
		 * Render css and js for meta box
		 */
		public function load_assets_for_column() {
			if ( ! ppw_pro_check_permission_for_post_type( $this->screen, true ) ) {
				return;
			}

			$module = PPW_Pro_Constants::COLUMN_MODULE;
			$this->load_css( $module, PPW_PRO_VERSION );
			$this->load_js( $module, PPW_PRO_VERSION );
			wp_localize_script( "ppw-pro-$module-js", 'wp_protect_password_data', array(
				'home_url'                    => is_ssl() ? home_url( '/', 'https' ) : home_url( '/' ),
				'nonce'                       => wp_create_nonce( 'wp_rest' ),
				'auto_protect_all_child_page' => ppw_core_get_setting_type_bool( PPW_Pro_Constants::WPP_AUTO_PROTECT_ALL_CHILD_PAGES ),
				'roles'                       => array_keys( get_editable_roles() ),
				'api_url'                     => get_rest_url(),
				'bypass_param'                => PPW_Pro_Constants::BYPASS_PARAM,
			) );
		}

		/**
		 * Render css and js for entire site tab
		 */
		public function load_assets_for_category_page() {
			$module = PPW_Pro_Constants::EDIT_CATEGORY_MODULE;
			if ( PPW_Pro_Constants::EDIT_CATEGORY_PAGE === $this->screen ) {
				$this->load_css( $module, PPW_PRO_VERSION );
				$this->load_js( $module, PPW_PRO_VERSION );
				wp_localize_script( "ppw-pro-$module-js", 'ppw_entire_site_data', array(
					'ajax_url' => admin_url( 'admin-ajax.php' ),
				) );

				$asset_services = new PPW_Asset_Services( null, null );
				$asset_services->load_select2_lib();
				$asset_services->load_toastr_lib();
			}
		}

		public function load_js_show_notice_deactivate_plugin() {
			if ( 'plugins' === $this->screen ) {
				wp_enqueue_script( 'ppw-pro-notice-deactivate-js', PPW_PRO_DIR_URL . 'admin/js/class-ppw-pro-notice-deactivate.js', array( 'jquery' ), PPW_PRO_VERSION, true );
			}
		}

		public function load_asserts_for_pcp_passwords() {
			$module = PPW_Pro_Constants::PCP_MODULE;
			if ( PPW_Constants::MENU_NAME === $this->page && $module === $this->tab ) {
				wp_enqueue_script( "ppw-{$module}-js", PPW_PRO_DIR_URL . 'includes/views/pcp-passwords/assets/ppw-pcp-passwords.js', array( 'jquery' ), PPW_VERSION, true );
				wp_enqueue_style( "ppw-{$module}-css", PPW_PRO_DIR_URL . 'includes/views/pcp-passwords/assets/ppw-pcp-passwords.css', array(), PPW_VERSION, 'all' );
				wp_localize_script(
					"ppw-{$module}-js",
					'ppwPCPPasswords',
					array(
						'restUrl'   => get_rest_url(),
						'nonce'     => wp_create_nonce( 'wp_rest' ),
						'roles'     => array_keys( get_editable_roles() ),
					)
				);
			}
		}

		/**
		 * Helper function to load css of module
		 *
		 * @param       $module
		 * @param       $version
		 * @param array $dependencies
		 */
		public function load_css( $module, $version, $dependencies = array() ) {
			wp_enqueue_style( "ppw-pro-$module-css", PPW_PRO_VIEW_URL . "$module/assets/ppw-pro-$module.css", $dependencies, $version, 'all' );
		}

		/**
		 * Helper function to load js of module
		 *
		 * @param       $module
		 * @param       $version
		 * @param array $dependencies
		 */
		public function load_js( $module, $version, $dependencies = array() ) {
			wp_enqueue_script( "ppw-pro-$module-js", PPW_PRO_VIEW_URL . "$module/assets/ppw-pro-$module.js", $dependencies, $version, 'all' );
		}

		/**
		 * Loading shared css
		 *
		 * @param string $version      The version of css file.
		 * @param array  $dependencies The css framework's dependencies.
		 */
		public function load_shared_css( $version, $dependencies = array() ) {
			wp_enqueue_style( 'ppw-pro-shared-css', PPW_PRO_VIEW_URL . 'shared/dist/ppw-pro-setting.css', $dependencies, $version, 'all' );
			wp_enqueue_script( 'ppw-pro-shared-js', PPW_PRO_VIEW_URL . 'shared/dist/ppw-pro-setting.js', array( 'jquery' ), $version, true );
			if ( ppw_pro_is_wp_version_compatible( '5.3' ) ) {
				wp_enqueue_style( 'ppw-pro-css-wp-5-3', PPW_PRO_VIEW_URL . 'shared/css-for-5-3/general.css', $dependencies, $version, 'all' );
			}

			wp_enqueue_script( 'ppw-pro-sidebar-js', PPW_PRO_VIEW_URL . 'sidebar/assets/ppw-pro-sidebar.js', array( 'jquery' ), $version, true );
			wp_localize_script( 'ppw-pro-sidebar-js', 'ppw_pro_sidebar_data', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
		}

		/**
		 * Load jQuery UI
		 */
		public function load_jquery_ui() {
			wp_enqueue_script( 'ppw-pro-jquery-ui-min-js', PPW_PRO_DIR_URL . 'admin/js/lib/jquery-ui.min.js', array( 'jquery' ), PPW_PRO_VERSION, true );
			wp_enqueue_style( 'ppw-pro-jquery-ui-min-css', PPW_PRO_DIR_URL . 'admin/css/lib/jquery-ui.min.css', array(), PPW_PRO_VERSION, 'all' );
		}

	}
}
