<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 3/17/20
 * Time: 16:20
 */

if ( class_exists( 'PPWP_Pro_Abstract_Shortcode' ) ) {
	return;
}

/**
 * Shortcode abstract class
 *
 * Class IPPWP_Pro_Shortcode
 */
abstract class PPWP_Pro_Abstract_Shortcode {

	/**
	 * Constructor
	 *
	 * PPWP_Pro_Abstract_Shortcode constructor.
	 */
	public function __construct() {
		// Do something here.
	}

	/**
	 * Short code attributes.
	 *
	 * @var array
	 */
	protected $attributes;


	/**
	 * Render shortcode main function
	 *
	 * @param array  $attrs   list of attributes including password.
	 * @param string $content the content inside short code.
	 *
	 * @return string
	 */
	abstract public function render_shortcode( $attrs, $content = null );

	/**
	 * Check whether the content should render.
	 *
	 * @return bool
	 */
	abstract protected function should_render_sc_content();
}
