<?php

/**
 * @deprecated
 * Class PPW_Pro_Shortcode_Widget
 */
class PPW_Pro_Shortcode_Widget extends \Elementor\Widget_Base {

	/**
	 * Get element name.
	 *
	 * Retrieve the element name.
	 *
	 * @return string The name.
	 * @since  1.4.0
	 * @access public
	 */
	public function get_name() {
		return 'ppwp_pro';
	}

	/**
	 * Get skin title.
	 *
	 * Retrieve the skin title.
	 *
	 * @since  1.0.0
	 * @access public
	 * @abstract
	 */
	public function get_title() {
		return __( 'PPWP', 'password-protect-page' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 * @since  1.0.0
	 * @access public
	 */
	public function get_icon() {
		return 'fa fa-lock';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the widget categories.
	 *
	 * @return array Widget categories.
	 * @since  1.0.10
	 * @access public
	 */
	public function get_categories() {
		return array( 'general' );
	}

	/**
	 * Add Controls to Widgets
	 */
	protected function _register_controls() {    //phpcs:ignore
		$is_gold_activate = defined( 'PDA_GOLD_V3_VERSION' );
		$roles            = [];
		$raw_roles        = apply_filters(
			'ppw_supported_white_list_roles',
			array(
				'administrator',
				'editor',
				'author',
				'contributor',
				'subscriber',
			)
		);
		foreach ( $raw_roles as $value ) {
			$roles[ $value ] = $value;
		}
		$this->start_controls_section(
			'ppwp_section',
			array(
				'label' => __( 'PPWP Shortcode', 'password-protect-page' ),
			)
		);

		$controls = [
			[
				'key'   => 'ppwp_protect_options',
				'value' => [
					'label'     => __( 'Partial Content Protection', 'password-protect-page' ),
					'type'      => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				],
			],
			[
				'key'   => 'ppwp_passwords',
				'value' => [
					'label'       => __( 'Passwords', 'password-protect-page' ),
					'type'        => \Elementor\Controls_Manager::TEXT,
					'placeholder' => __( 'Enter your password, e.g. password1 password2', 'password-protect-page' ),
					'default'     => 'password1 password2',
					'description' => 'Multiple passwords are separated by space, case-sensitivity, no more than 100 characters and don’t contain [, ], “, ‘',
					'label_block' => true,
				],
			],
			[
				'key'   => 'ppwp_whitelisted_roles',
				'value' => [
					'label'       => __( 'Whitelisted Roles', 'password-protect-page' ),
					'type'        => \Elementor\Controls_Manager::SELECT2,
					'placeholder' => __( 'Select whitelisted roles', 'password-protect-page' ),
					'multiple'    => true,
					'options'     => $roles,
					'description' => 'Select user roles who can access protected area without having to enter passwords',
					'label_block' => true,
				],
			],
			[
				'key'     => 'ppwp_cookie',
				'value'   => [
					'label' => __( 'Cookie Expiration Time (hours)', 'password-protect-page' ),
					'type'  => \Elementor\Controls_Manager::NUMBER,
					'min'   => '1',
					'title' => 'The number of hours before the cookie expires',
				],
				'is_hide' => ! $is_gold_activate,
			],
			[
				'key'     => 'ppwp_download_limit',
				'value'   => [
					'label' => __( 'Download Limit (clicks)', 'password-protect-page' ),
					'type'  => \Elementor\Controls_Manager::NUMBER,
					'min'   => '1',
					'title' => 'The number of clicks user can access private download links',
				],
				'is_hide' => ! $is_gold_activate,
			],
			[
				'key'   => 'ppwp_protected_content_headline',
				'value' => [
					'label'     => __( 'Protected Content', 'password-protect-page' ),
					'type'      => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				],
			],
			[
				'key'   => 'ppwp_protected_content',
				'value' => [
					'type'    => \Elementor\Controls_Manager::WYSIWYG,
					'default' => __( 'This content is your protected content', 'password-protect-page' ),
				],
			],
		];

		foreach ( $controls as $control ) {
			if ( ! isset( $control['is_hide'] ) || true !== $control['is_hide'] ) {
				$this->add_control( $control['key'], $control['value'] );
			}
		}

		$this->end_controls_section();
	}

	/**
	 * Render content.
	 */
	protected function render() {
		$shortcode = do_shortcode( ( $this->generate_shortcode() ) );
		?>
		<div class="elementor-shortcode"><?php echo $shortcode; ?></div>
		<?php
	}

	/**
	 * Render shortcode widget as plain content.
	 *
	 * Override the default behavior by printing the shortcode instead of rendering it.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function render_plain_content() {
		// In plain mode, render without shortcode.

		echo $this->generate_shortcode();
	}

	/**
	 * Generate PPWP shortcode.
	 *
	 * @return string PPWP Shortcode
	 */
	public function generate_shortcode() {
		$settings          = $this->get_settings_for_display();
		$passwords         = isset( $settings['ppwp_passwords'] ) ? $settings['ppwp_passwords'] : '';
		$whitelisted_roles = $this->transform_whitelisted_roles_to_string( $settings );
		$download_limit    = isset( $settings['ppwp_download_limit'] ) ? $settings['ppwp_download_limit'] : '';
		$cookie            = isset( $settings['ppwp_cookie'] ) ? $settings['ppwp_cookie'] : '';
		$shortcode         = sprintf(
			'[ppwp id="" class="" passwords="%1$s" cookie="%2$s" download_limit="%3$s" whitelisted_roles="%4$s"]',
			$passwords,
			$cookie,
			$download_limit,
			$whitelisted_roles
		);

		return $shortcode . $settings['ppwp_protected_content'] . '[/ppwp]';
	}

	/**
	 * Get whitelisted roles from Settings.
	 *
	 * @param array $settings The settings.
	 *
	 * @return string
	 */
	private function transform_whitelisted_roles_to_string( $settings ) {
		if (
			! isset( $settings['ppwp_whitelisted_roles'] ) ||
			! is_array( $settings['ppwp_whitelisted_roles'] ) ||
			count( $settings['ppwp_whitelisted_roles'] ) === 0
		) {
			return '';
		}

		return implode( ',', $settings['ppwp_whitelisted_roles'] );
	}

}
