<?php

class PPW_Pro_Elementor {
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @access   protected
	 * @var      PPW_Pro_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;
	/**
	 * Minimum elementor version.
	 *
	 * @var PPW_Pro_Elementor
	 */
	private static $instance;

	const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

	const MINIMUM_PPW_FREE_VERSION = '1.2.3.3';

	const MAXIMUM_PPW_FREE_VERSION = '1.3.0';

	/**
	 * Get instance.
	 *
	 * @param PPW_Pro_Loader $loader Maintains and registers all hooks for the plugin.
	 *
	 * @return PPW_Pro_Elementor
	 */
	public static function get_instance( $loader ) {
		if ( null === self::$instance ) {
			self::$instance = new self( $loader );
		}

		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 * @param PPW_Pro_Loader $loader Maintains and registers all hooks for the plugin.
	 *
	 * PPW_Pro_Elementor constructor.
	 */
	public function __construct( $loader ) {
		$this->loader = $loader;
		$this->init();
	}

	/**
	 * Register Elementor hooks.
	 */
	public function init() {
		if ( ! did_action( 'elementor/loaded' ) ) {
			return;
		}

		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) || ! version_compare( PPW_VERSION, self::MINIMUM_PPW_FREE_VERSION, '>=' ) || version_compare( PPW_VERSION, self::MAXIMUM_PPW_FREE_VERSION, '>=' ) ) {
			return;
		}

		$this->loader->add_action( 'elementor/widgets/widgets_registered', $this, 'register_widgets' );
	}

	/**
	 * Register widgets.
	 */
	public function register_widgets() {
		// Include widget files.
		require_once( __DIR__ . '/widgets/class-ppw-pro-elementor-widget-shortcode.php' ); //phpcs:ignore

		// Register widget.
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new PPW_Pro_Shortcode_Widget() );
	}


}
