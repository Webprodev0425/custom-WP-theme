<?php

/**
 * @deprecated
 * Class PPW_Pro_Beaver_Loader
 */
class PPW_Pro_Beaver_Loader {
	/**
	 * Instance of PPW_Pro_Beaver_Loader class.
	 *
	 * @var PPW_Pro_Beaver_Loader
	 */
	protected static $instance = null;

	/**
	 * PPW_Pro_Beaver_Loader constructor.
	 */
	public function __construct() {
		$this->setup_hooks();
	}

	/**
	 * Setup hooks.
	 */
	public function setup_hooks() {
		if ( ! class_exists( 'FLBuilder' ) ) {
			return;
		}

		add_filter( 'fl_builder_custom_fields', array( $this, 'register_fields' ) );
	}

	/**
	 * Get instance
	 *
	 * @return PPW_Pro_Beaver_Loader
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			// Use static instead of self due to the inheritance later.
			// For example: ChildSC extends this class, when we call get_instance
			// it will return the object of child class. On the other hand, self function
			// will return the object of base class.
			self::$instance = new static();
		}

		return self::$instance;
	}

	/**
	 * Register custom fields.
	 *
	 * @param array $fields Fields.
	 *
	 * @return array Fields.
	 */
	public function register_fields( $fields ) {
		$fields['ppwp-number'] = PPW_PRO_DIR_PATH . 'includes/addons/beaver-builder/fields/input-number.php';
		return $fields;
	}
}
