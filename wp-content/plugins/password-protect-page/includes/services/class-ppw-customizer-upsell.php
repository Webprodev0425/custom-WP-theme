<?php

if ( ! class_exists( 'PPW_Customizer_Upsell' ) ) {

	/**
	 * Register PPW_Customizer_Upsell Configurations.
	 */
	class PPW_Customizer_Upsell {

		/**
		 * Register upsell section for customize
		 *
		 * @var PPW_Customizer_Upsell
		 */
		protected static $instance = null;


		/**
		 * Constructor for PPW_Customizer_Upsell
		 */
		public function __construct() {
			add_action( 'customize_register', array( $this, 'customize_register' ) );
			add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue' ) );
        }

		/**
		 * Get instance of PPW_Customizer_Upsell
		 *
		 * @return PPW_Customizer_Upsell
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new static();
			}

			return self::$instance;
		}

		public function customize_register( $wp_customize ) {
			include PPW_DIR_PATH . 'includes/customizers/class-ppw-customize-link-section.php';

			$wp_customize->register_section_type( 'PPW_Customize_Link_Section' );

		}

		public function enqueue() {
            wp_enqueue_script( 'ppw-upsell-section-scripts', PPW_DIR_URL . 'includes/customizers/assets/ppw-upsell-section.js', array( 'jquery' ), PPW_VERSION, true );
        }

	}
}
