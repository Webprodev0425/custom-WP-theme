<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 3/17/20
 * Time: 16:20
 */

if ( class_exists( 'PPWP_Pro_SideWide' ) ) {
	return;
}

/**
 * Pro SideWide form shortcode.
 *
 * Class PPWP_Pro_SideWide
 */
class PPWP_Pro_SideWide extends PPWP_Pro_Abstract_Shortcode {

	/**
	 * Password Services.
	 *
	 * @var PPW_Pro_Password_Services
	 */
	private $pwd_service;

	/**
	 * Class instance
	 *
	 * @var PPWP_Pro_Abstract_Shortcode
	 */
	private static $instance;

	/**
	 * Constructor
	 *
	 * @param PPW_Pro_Password_Services $pwd_service Password Services.
	 */
	public function __construct( $pwd_service = null ) {
		parent::__construct();
		// @deprecated
		add_shortcode( PPW_Pro_Constants::SITEWIDE_SC, array( $this, 'render_shortcode' ) );
		add_shortcode( PPW_Pro_Constants::SITE_WIDE_SC, array( $this, 'render_shortcode' ) );
		if ( is_null( $pwd_service ) ) {
			$pwd_service = new PPW_Pro_Password_Services();
		}
		$this->pwd_service = $pwd_service;
	}

	/**
	 * Get service instance.
	 *
	 * @return PPWP_PS_General_SC|static
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
	 * Render shortcode main function
	 *
	 * @param array  $attrs   list of attributes including password.
	 * @param string $content the content inside short code.
	 *
	 * @return string
	 */
	public function render_shortcode( $attrs, $content = null ) {

		if ( $this->should_render_sc_content() ) {
			return $content;
		}

		require_once PPW_PRO_DIR_PATH . 'includes/views/entire-site/view-ppw-pro-form-password.php';
		$form = apply_filters( PPW_Pro_Constants::HOOK_CUSTOM_ENTIRE_SITE_LOGIN_FORM, entire_site_render_login_form() );

		$this->add_asserts();

		$main_style = $this->get_form_style();
		// https://www.w3.org/TR/html52/document-metadata.html#the-style-element ref.
		$style = $this->add_customized_form_style();

		return $main_style . $style . $form;
	}


	/**
	 * Check whether the shortcode content should render.
	 * There are two conditions
	 *  1. Entire site password is disabled
	 *  2. The entire site cookie existed and valid.
	 *
	 * @return bool
	 */
	public function should_render_sc_content() {

		$post_id = ppw_pro_get_post_id();

		if ( $this->pwd_service->is_whitelist_roles( $post_id ) ) {
			return true;
		}

		$data = $this->pwd_service->get_entire_site_passwords();
		if ( ! isset( $data['passwords'] ) ) {
			return true;
		}
		$passwords = $data['passwords'];

		if ( ! $this->pwd_service->is_entire_site_protection_enabled( $passwords ) || $this->pwd_service->is_valid_entire_site_cookie( $passwords ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Add styles for shortcode
	 */
	public function add_asserts() {
		if ( is_admin() ) {
			return;
		}



		wp_enqueue_script(
			'ppwp-sidewide-sc-bundle',
			PPW_PRO_VIEW_URL . 'entire-site/assets/dist/ppw-entire-site-sc-form.bundle.js',
			array( 'jquery' ),
			PPW_PRO_VERSION,
			true
		);

		wp_localize_script(
			'ppwp-sidewide-sc-bundle',
			'sideWideGlobal',
			array(
				'restUrl' => get_rest_url(),
				'nonce'   => wp_create_nonce( 'wp_rest' ),
				'label'   => array(
					'LOADING' => _x( 'Loading...', 'password-protect-page', 'password-protect-page' ),
				),
			)
		);
	}

	/**
	 * Add inline customized style. Tested that WordPress won't add duplicate styles in case one post having multiple shortcodes.
	 */
	private function add_customized_form_style() {
		ob_start();
		do_action( PPW_Pro_Constants::HOOK_CUSTOM_STYLE_FORM_ENTIRE_SITE );
		$style = ob_get_contents();
		ob_end_clean();

		return "<style type='text/css'>$style</style>";
	}

	/**
	 * Add main form style tab. It help to not loading css front-end everywhere.
	 *
	 * @return string <style type='text/css'>...<style>
	 */
	private function get_form_style() {
		ob_start();
		include PPW_PRO_VIEW_PATH . 'entire-site/assets/ppw-form-entire-site.css';
		$styles = ob_get_contents();
		ob_end_clean();
		return "<style type='text/css'>$styles</style>";
	}

}
