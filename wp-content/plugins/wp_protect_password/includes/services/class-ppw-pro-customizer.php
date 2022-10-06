<?php

if ( ! class_exists( 'PPW_Pro_Customizer_Service' ) ) {
	class PPW_Pro_Customizer_Service {

		/**
		 * Instance of PPW_Pro_Shortcode class.
		 *
		 * @var PPW_Pro_Customizer_Service
		 */
		protected static $instance = null;

		/**
		 * Constructor for PPW_Customizer
		 */
		public function __construct() {
            add_action( 'customize_register', array( $this, 'customize_register' ), 15 );
			add_action( PPW_Pro_Constants::HOOK_CUSTOM_STYLE_FORM_ENTIRE_SITE, array( $this, 'dynamic_styles' ) );
			add_action( PPW_Pro_Constants::HOOK_CUSTOM_SCRIPT_FORM_ENTIRE_SITE, array( $this, 'dynamic_scripts' ) );

		}

		/**
		 * Get instance of PPW_Customizer
		 *
		 * @return PPW_Pro_Customizer_Service
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

		function login_designer_sanitize_checkbox( $checked ) {
			// Boolean check.
			return ( ( isset( $checked ) && true === $checked ) ? true : false );
		}

		/**
		 * Register customizer fields
		 *
		 * @param object $wp_customize customizer object.
		 *
		 * @return void
		 */
		public function customize_register( $wp_customize ) {
			$wp_customize->add_panel( 'ppwp_sitewide',
				array(
					'priority'       => 999,
					'capability'     => 'edit_theme_options',
					'theme_supports' => '',
					'title'          => __( 'PPWP Sitewide Protection Form', 'password-protect-page' ),
				)
			);

			/* form logo section */
			$wp_customize->add_section( 'ppwp_pro_form_logo', array(
				'title'    => __( 'Logo', 'password-protect-page' ),
				'panel'    => 'ppwp_sitewide',
				'priority' => 100,
			) );

			/* register toggle control */
			$wp_customize->register_control_type( 'PPW_Pro_Toggle_Control' );
			$wp_customize->register_control_type( 'PPW_Pro_Control_Title' );

			// Add an option to disable the logo.
			$wp_customize->add_setting( 'ppwp_pro_logo_disable', array(
				'default'           => __( PPW_Pro_Constants::DEFAULT_LOGO_CUSTOMIZE_DISABLE, 'password-protect-page' ),
				// 'type'              => 'option',
				// 'transport'         => 'postMessage',
				// 'sanitize_callback' => 'login_designer_sanitize_checkbox',
			) );

			$wp_customize->add_control(
				new PPW_Pro_Toggle_Control(
					$wp_customize,
					'ppwp_pro_logo_disable_control', array(
					'label'       => __( 'Disable Logo', 'password-protect-page' ),
					'section'     => 'ppwp_pro_form_logo',
					'type'        => 'toggle',
					'settings'    => 'ppwp_pro_logo_disable',
				) )
			);

			/* logo customize */
			$wp_customize->add_setting( 'ppwp_pro_logo_customize', array(
				'default' => __( PPW_Pro_Constants::DEFAULT_LOGO_CUSTOMIZE_IMAGE, 'password-protect-page' ),
			) );

			$wp_customize->add_control(
				new \WP_Customize_Image_Control(
					$wp_customize,
					'ppwp_pro_logo_customize_control', array(
					'label'    => __( 'Logo Image', 'password-protect-page' ),
					'section'  => 'ppwp_pro_form_logo',
					'settings' => 'ppwp_pro_logo_customize',
				) )
			);

			/* logo width */
			$wp_customize->add_setting( 'ppwp_pro_logo_customize_width', array(
				'default' => PPW_Pro_Constants::DEFAULT_LOGO_CUSTOMIZE_WIDTH,
			) );
			$wp_customize->add_control( 'ppwp_pro_logo_customize_width_control', array(
				'label'       => __( 'Logo Width', 'password-protect-page' ),
				'description' => __( 'Width in px', 'password-protect-page' ),
				'section'     => 'ppwp_pro_form_logo',
				'settings'    => 'ppwp_pro_logo_customize_width',
				'type'        => 'number',
			) );

			/* logo height */
			$wp_customize->add_setting( 'ppwp_pro_logo_customize_height', array(
				'default' => PPW_Pro_Constants::DEFAULT_LOGO_CUSTOMIZE_HEIGHT,
			) );
			$wp_customize->add_control( 'ppwp_pro_logo_customize_height_control', array(
				'label'       => __( 'Logo Height', 'password-protect-page' ),
				'description' => __( 'Height in px', 'password-protect-page' ),
				'section'     => 'ppwp_pro_form_logo',
				'settings'    => 'ppwp_pro_logo_customize_height',
				'type'        => 'number',
			) );

			/* logo border-radius */
			$wp_customize->add_setting( 'ppwp_pro_logo_customize_border_radius', array(
				'default' => PPW_Pro_Constants::DEFAULT_LOGO_CUSTOMIZE_BORDER_RADIUS,
			) );
			$wp_customize->add_control( 'ppwp_pro_logo_customize_border_radius_control', array(
				'label'       => __( 'Logo Radius', 'password-protect-page' ),
				'description' => __( 'Border Radius in %', 'password-protect-page' ),
				'section'     => 'ppwp_pro_form_logo',
				'settings'    => 'ppwp_pro_logo_customize_border_radius',
				'type'        => 'number',
			) );

			/* logo content */
			$wp_customize->add_setting( 'ppwp_pro_form_logo_content', array(
				'default' => __( PPW_Pro_Constants::DEFAULT_CONTENT_TEXT, 'password-protect-page' ),
			) );

			$wp_customize->add_control(
				new PPW_Pro_Text_Editor_Custom_Control(
					$wp_customize,
					'ppwp_pro_form_logo_content',
					array(
						'label'    => __( 'Headline', 'password-protect-page' ),
						'section'  => 'ppwp_pro_form_logo',
						'settings' => 'ppwp_pro_form_logo_content',
						'type'     => 'textarea',
					)
				)
			);

			/* logo content font size */
			$wp_customize->add_setting( 'ppwp_pro_form_logo_content_font_size', array(
				'default' => PPW_Pro_Constants::DEFAULT_TEXT_FONT_SIZE,
			) );
			$wp_customize->add_control( 'ppwp_pro_form_logo_content_font_size_control', array(
				'label'       => __( 'Headline Font Size', 'password-protect-page' ),
				'description' => __( 'Font size in px', 'password-protect-page' ),
				'section'     => 'ppwp_pro_form_logo',
				'settings'    => 'ppwp_pro_form_logo_content_font_size',
				'type'        => 'number',
			) );

			/* logo content font weight */
			$wp_customize->add_setting( 'ppwp_pro_form_logo_content_font_weight', array(
				'default' => PPW_Pro_Constants::DEFAULT_TEXT_FONT_WEIGHT,
			) );
			$wp_customize->add_control( 'ppwp_pro_form_logo_content_font_weight_control', array(
				'label'    => __( 'Headline Font Weight', 'password-protect-page' ),
				'section'  => 'ppwp_pro_form_logo',
				'settings' => 'ppwp_pro_form_logo_content_font_weight',
				'type'     => 'number',
			) );

			/* logo content color */
			$wp_customize->add_setting( 'ppwp_pro_form_logo_content_font_color', array(
				'default' => PPW_Pro_Constants::DEFAULT_TEXT_FONT_COLOR,
			) );

			$wp_customize->add_control(
				new \WP_Customize_Color_Control(
					$wp_customize,
					'ppwp_pro_form_logo_content_font_color_control', array(
					'label'    => __( 'Headline Color', 'password-protect-page' ),
					'section'  => 'ppwp_pro_form_logo',
					'settings' => 'ppwp_pro_form_logo_content_font_color',
				) )
			);

			/* background sitewide section */
			$wp_customize->add_section( 'ppwp_pro_form_background', array(
				'title'    => __( 'Background', 'password-protect-page' ),
				'panel'    => 'ppwp_sitewide',
				'priority' => 200,
			) );

			/* background image */
			$wp_customize->add_setting( 'ppwp_pro_form_background_image', array(
				'default' => __( PPW_Pro_Constants::DEFAULT_SITEWIDE_BACKGROUND_IMAGE, 'password-protect-page' ),
			) );

			$wp_customize->add_control(
				new \WP_Customize_Image_Control(
					$wp_customize,
					'ppwp_pro_form_background_image_control', array(
					'label'    => __( 'Background Image', 'password-protect-page' ),
					'section'  => 'ppwp_pro_form_background',
					'settings' => 'ppwp_pro_form_background_image',
				) )
			);

			/* background color */
			$wp_customize->add_setting( 'ppwp_pro_form_background_color', array(
				'default' => PPW_Pro_Constants::DEFAULT_SITEWIDE_BACKGROUND_COLOR,
			) );

			$wp_customize->add_control(
				new \WP_Customize_Color_Control(
					$wp_customize,
					'ppwp_pro_form_background_color_control', array(
					'label'    => __( 'Background Color', 'password-protect-page' ),
					'section'  => 'ppwp_pro_form_background',
					'settings' => 'ppwp_pro_form_background_color',
				) )
			);

            /* password form section */
			$wp_customize->add_section( 'ppwp_pro_form_instructions', array(
				'title'    => __( 'Password Form', 'password-protect-page' ),
				'panel'    => 'ppwp_sitewide',
				'priority' => 300,
			) );

			/* form section group */
			$wp_customize->add_setting( 'ppwp_pro_form_section_group', array(
				'default' => '',
			) );

			$wp_customize->add_control(
				new PPW_Pro_Control_Title(
					$wp_customize,
					'ppwp_pro_form_section_group', array(
					'label'			=> __( 'Password Form', 'password-protect-page' ),
					'section'  		=> 'ppwp_pro_form_instructions',
					'settings' 		=> 'ppwp_pro_form_section_group',
					'type'     		=> 'control_title',
				) )
			);

			/* enable form transparency */
			$wp_customize->add_setting( 'ppwp_pro_form_enable_transparency', array(
				'default'           => __( PPW_Pro_Constants::DEFAULT_FORM_TRANSPARENCY, 'password-protect-page' ),
				// 'type'              => 'option',
				// 'transport'         => 'postMessage',
				// 'sanitize_callback' => 'login_designer_sanitize_checkbox',
			) );

			$wp_customize->add_control(
				new PPW_Pro_Toggle_Control(
					$wp_customize,
					'ppwp_pro_form_enable_transparency_control', array(
					'label'       => __( 'Enable Form Transparency', 'password-protect-page' ),
					'section'     => 'ppwp_pro_form_instructions',
					'type'        => 'toggle',
					'settings'    => 'ppwp_pro_form_enable_transparency',
				) )
			);

			/* password form background color */
			$wp_customize->add_setting( 'ppwp_pro_form_instructions_background_color', array(
				'default' => PPW_Pro_Constants::DEFAULT_FORM_BACKGROUND_COLOR,
			) );

			$wp_customize->add_control(
				new \WP_Customize_Color_Control(
					$wp_customize,
					'ppwp_pro_form_instructions_background_color_control', array(
					'label'    => __( 'Form Background Color', 'password-protect-page' ),
					'section'  => 'ppwp_pro_form_instructions',
					'settings' => 'ppwp_pro_form_instructions_background_color',
				) )
			);

			/* password form width */
			$wp_customize->add_setting( 'ppwp_pro_form_instructions_width', array(
				'default' => PPW_Pro_Constants::DEFAULT_FORM_WIDTH,
			) );

			$wp_customize->add_control( 'ppwp_pro_form_instructions_width_control', array(
				'label'			=> __( 'Form Width', 'password-protect-page' ),
				'section'  		=> 'ppwp_pro_form_instructions',
				'settings' 		=> 'ppwp_pro_form_instructions_width',
				'description'	=> 'Width in px',
				'type'     		=> 'number',
			) );

			/* password form padding-left */
			$wp_customize->add_setting( 'ppwp_pro_form_instructions_padding_left', array(
				'default' => PPW_Pro_Constants::DEFAULT_FORM_PADDING_LEFT,
			) );

			$wp_customize->add_control( 'ppwp_pro_form_instructions_padding_control', array(
				'label'			=> __( 'Form Padding-left', 'password-protect-page' ),
				'section'  		=> 'ppwp_pro_form_instructions',
				'settings' 		=> 'ppwp_pro_form_instructions_padding_left',
				'description'	=> 'padding-left in px',
				'type'     		=> 'number',
			) );

			/* password form padding-top */
			$wp_customize->add_setting( 'ppwp_pro_form_instructions_padding_top', array(
				'default' => PPW_Pro_Constants::DEFAULT_FORM_PADDING_TOP,
			) );

			$wp_customize->add_control( 'ppwp_pro_form_instructions_padding_top_control', array(
				'label'			=> __( 'Form Padding-top', 'password-protect-page' ),
				'section'  		=> 'ppwp_pro_form_instructions',
				'settings' 		=> 'ppwp_pro_form_instructions_padding_top',
				'description'	=> 'padding top in px',
				'type'     		=> 'number',
			) );

			/* password form padding-right */
			$wp_customize->add_setting( 'ppwp_pro_form_instructions_padding_right', array(
				'default' => PPW_Pro_Constants::DEFAULT_FORM_PADDING_RIGHT,
			) );

			$wp_customize->add_control( 'ppwp_pro_form_instructions_padding_right_control', array(
				'label'			=> __( 'Form Padding-right', 'password-protect-page' ),
				'section'  		=> 'ppwp_pro_form_instructions',
				'settings' 		=> 'ppwp_pro_form_instructions_padding_right',
				'description'	=> 'padding-right in px',
				'type'     		=> 'number',
			) );

			/* password form padding-bottom */
			$wp_customize->add_setting( 'ppwp_pro_form_instructions_padding_bottom', array(
				'default' => PPW_Pro_Constants::DEFAULT_FORM_PADDING_BOTTOM,
			) );

			$wp_customize->add_control( 'ppwp_pro_form_instructions_padding_bottom_control', array(
				'label'			=> __( 'Form Padding-bottom', 'password-protect-page' ),
				'section'  		=> 'ppwp_pro_form_instructions',
				'settings' 		=> 'ppwp_pro_form_instructions_padding_bottom',
				'description'	=> 'padding-bottom in px',
				'type'     		=> 'number',
			) );

			/* password form border radius */
			$wp_customize->add_setting( 'ppwp_pro_form_instructions_border_radius', array(
				'default' => PPW_Pro_Constants::DEFAULT_BORDER_RADIUS,
			) );

			$wp_customize->add_control( 'ppwp_pro_form_instructions_border_radius_control', array(
				'label'			=> __( 'Form Border Radius', 'password-protect-page' ),
				'section'  		=> 'ppwp_pro_form_instructions',
				'settings' 		=> 'ppwp_pro_form_instructions_border_radius',
				'description'	=> 'Border Radius in px',
				'type'     		=> 'number',
			) );

			/* password label group */
			$wp_customize->add_setting( 'ppwp_pro_password_label_group', array(
				'default' => '',
			) );

			$wp_customize->add_control(
				new PPW_Pro_Control_Title(
					$wp_customize,
					'ppwp_pro_password_label_group', array(
					'label'			=> __( 'Password Label', 'password-protect-page' ),
					'section'  		=> 'ppwp_pro_form_instructions',
					'settings' 		=> 'ppwp_pro_password_label_group',
					'type'     		=> 'control_title',
				) )
			);

			/* instruction password label */
            $wp_customize->add_setting( 'ppwp_pro_form_instructions_password_label', array(
				'default' => PPW_Pro_Constants::DEFAULT_PASSWORD_LABEL,
            ) );

            $wp_customize->add_control(
				new PPW_Pro_Text_Editor_Custom_Control(
					$wp_customize,
					'ppwp_pro_form_instructions_password_label',
					array(
						'label'    => __( 'Password Label', 'password-protect-page' ),
						'section'  => 'ppwp_pro_form_instructions',
						'settings' => 'ppwp_pro_form_instructions_password_label',
						'type'     => 'textarea',
					)
				)
			);

			/* password label font size */
			$wp_customize->add_setting( 'ppwp_pro_form_instructions_password_label_font_size', array(
				'default' => PPW_Pro_Constants::DEFAULT_PASSWORD_LABEL_FONT_SIZE,
			) );
			$wp_customize->add_control( 'ppwp_pro_form_instructions_password_label_font_size_control', array(
				'label'       => __( 'Font Size', 'password-protect-page' ),
				'section'     => 'ppwp_pro_form_instructions',
				'settings'    => 'ppwp_pro_form_instructions_password_label_font_size',
				'description' => __( 'Font size in px', 'password-protect-page' ),
				'type'        => 'number',
			) );

			/* password label font weight */
			$wp_customize->add_setting( 'ppwp_pro_form_instructions_password_label_font_weight', array(
				'default' => PPW_Pro_Constants::DEFAULT_PASSWORD_LABEL_FONT_WEIGHT,
			) );

			$wp_customize->add_control( 'ppwp_pro_form_instructions_password_label_font_weight_control', array(
				'label'    => __( 'Font Weight', 'password-protect-page' ),
				'section'  => 'ppwp_pro_form_instructions',
				'settings' => 'ppwp_pro_form_instructions_password_label_font_weight',
				'type'     => 'number',
			) );

			/* password label color */
			$wp_customize->add_setting( 'ppwp_pro_form_instructions_password_label_color', array(
				'default' => PPW_Pro_Constants::DEFAULT_PASSWORD_LABEL_FONT_COLOR,
			) );

			$wp_customize->add_control(
				new \WP_Customize_Color_Control(
					$wp_customize,
					'ppwp_pro_form_instructions_password_label_color_control', array(
					'label'    => __( 'Label Color', 'password-protect-page' ),
					'section'  => 'ppwp_pro_form_instructions',
					'settings' => 'ppwp_pro_form_instructions_password_label_color',
				) )
			);

			/* placeholder text */
			$wp_customize->add_setting( 'ppwp_pro_form_instructions_placeholder', array(
				'default' => PPW_Pro_Constants::DEFAULT_PLACEHOLDER,
			) );

			$wp_customize->add_control( 'ppwp_pro_form_instructions_placeholder_control', array(
				'label'    => __( 'Placeholder', 'password-protect-page' ),
				'section'  => 'ppwp_pro_form_instructions',
				'settings' => 'ppwp_pro_form_instructions_placeholder',
				'type'     => 'text',
			) );

			/* password reveal group */
			$wp_customize->add_setting( 'ppwp_pro_password_reveal_group', array(
				'default' => '',
			) );

			$wp_customize->add_control(
				new PPW_Pro_Control_Title(
					$wp_customize,
					'ppwp_pro_password_reveal_group', array(
					'label'			=> __( 'Password Reveal Button', 'password-protect-page' ),
					'section'  		=> 'ppwp_pro_form_instructions',
					'settings' 		=> 'ppwp_pro_password_reveal_group',
					'type'     		=> 'control_title',
				) )
			);

			/* password reveal button */
			$wp_customize->add_setting( 'ppwp_pro_form_instructions_is_show_password', array(
				'default'           => __( PPW_Pro_Constants::DEFAULT_IS_SHOW_PASSWORD, 'password-protect-page' ),
				// 'type'              => 'option',
				// 'transport'         => 'postMessage',
				// 'sanitize_callback' => 'login_designer_sanitize_checkbox',
			) );

			$wp_customize->add_control(
				new PPW_Pro_Toggle_Control(
					$wp_customize,
					'ppwp_pro_form_instructions_is_show_password_control', array(
					'label'       => __( 'Enable Password Reveal Button', 'password-protect-page' ),
					'section'     => 'ppwp_pro_form_instructions',
					'type'        => 'toggle',
					'settings'    => 'ppwp_pro_form_instructions_is_show_password',
				) )
			);

			/* show password text */
			$wp_customize->add_setting( 'ppwp_pro_form_instructions_show_password_text', array(
				'default' => PPW_Pro_Constants::DEFAULT_SHOW_PASSWORD_TEXT,
			) );

			$wp_customize->add_control( 'ppwp_pro_form_instructions_show_password_text_control', array(
				'label'			=> __( 'Button Text', 'password-protect-page' ),
				'section'  		=> 'ppwp_pro_form_instructions',
				'settings' 		=> 'ppwp_pro_form_instructions_show_password_text',
				'type'     		=> 'text',
			) );

			/* show password text size */
			$wp_customize->add_setting( 'ppwp_pro_form_instructions_show_password_text_size', array(
				'default' => PPW_Pro_Constants::DEFAULT_SHOW_PASSWORD_TEXT_SIZE,
			) );
			$wp_customize->add_control( 'ppwp_pro_form_instructions_show_password_text_size_control', array(
				'label'       => __( 'Button Font Size', 'password-protect-page' ),
				'section'     => 'ppwp_pro_form_instructions',
				'settings'    => 'ppwp_pro_form_instructions_show_password_text_size',
				'description' => __( 'Font size in px', 'password-protect-page' ),
				'type'        => 'number',
			) );

			/* show password text color */
			$wp_customize->add_setting( 'ppwp_pro_form_instructions_show_password_text_color', array(
				'default' => PPW_Pro_Constants::DEFAULT_PASSWORD_LABEL_FONT_COLOR,
			) );

			$wp_customize->add_control(
				new \WP_Customize_Color_Control(
					$wp_customize,
					'ppwp_pro_form_instructions_show_password_text_color_control', array(
					'label'    => __( 'Button Color', 'password-protect-page' ),
					'section'  => 'ppwp_pro_form_instructions',
					'settings' => 'ppwp_pro_form_instructions_show_password_text_color',
				) )
			);

			/* form button section */
			$wp_customize->add_section( 'ppwp_pro_form_button', array(
				'title'    => __( 'Button', 'password-protect-page' ),
				'panel'    => 'ppwp_sitewide',
				'priority' => 400,
			) );

			/* button label */
			$wp_customize->add_setting( 'ppwp_pro_form_button_label', array(
				'default' => __( PPW_Pro_Constants::DEFAULT_SUBMIT_LABEL, 'password-protect-page' ),
			) );
			$wp_customize->add_control( 'ppwp_pro_form_button_label_control', array(
				'label'    => __( 'Button Label', 'password-protect-page' ),
				'section'  => 'ppwp_pro_form_button',
				'settings' => 'ppwp_pro_form_button_label',
				'type'     => 'text',
			) );

			/* button height */
			$wp_customize->add_setting( 'ppwp_pro_form_button_height', array(
				'default' => PPW_Pro_Constants::DEFAULT_BUTTON_HEIGHT,
			) );

			$wp_customize->add_control( 'ppwp_pro_form_button_height_control', array(
				'label'			=> __( 'Button Height', 'password-protect-page' ),
				'section'  		=> 'ppwp_pro_form_button',
				'settings' 		=> 'ppwp_pro_form_button_height',
				'description'	=> 'Height in px',
				'type'     		=> 'number',
			) );

			/* button width */
			$wp_customize->add_setting( 'ppwp_pro_form_button_width', array(
				'default' => PPW_Pro_Constants::DEFAULT_BUTTON_WIDTH,
			) );

			$wp_customize->add_control( 'ppwp_pro_form_button_width_control', array(
				'label'			=> __( 'Button Width', 'password-protect-page' ),
				'section'  		=> 'ppwp_pro_form_button',
				'settings' 		=> 'ppwp_pro_form_button_width',
				'description'	=> 'Width in px',
				'type'     		=> 'number',
			) );


			/* button text font size */
			$wp_customize->add_setting( 'ppwp_pro_form_button_text_font_size', array(
				'default' => __( PPW_Pro_Constants::DEFAULT_BUTTON_TEXT_FONT_SIZE, 'password-protect-page' ),
			) );
			$wp_customize->add_control( 'ppwp_pro_form_button_text_font_size_control', array(
				'label'    => __( 'Font Size', 'password-protect-page' ),
				'description' => __( 'Font size in px', 'password-protect-page' ),
				'section'  => 'ppwp_pro_form_button',
				'settings' => 'ppwp_pro_form_button_text_font_size',
				'type'     => 'number',
			) );

			/* button text color */
			$wp_customize->add_setting( 'ppwp_pro_form_button_text_color', array(
				'default' => PPW_Pro_Constants::DEFAULT_BUTTON_TEXT_FONT_COLOR,
			) );

			$wp_customize->add_control(
				new \WP_Customize_Color_Control(
					$wp_customize,
					'ppwp_pro_form_button_text_color_control', array(
					'label'    => __( 'Text Color', 'password-protect-page' ),
					'section'  => 'ppwp_pro_form_button',
					'settings' => 'ppwp_pro_form_button_text_color',
				) )
			);

			/* button text hover color */
			$wp_customize->add_setting( 'ppwp_pro_form_button_text_hover_color', array(
				'default' => PPW_Pro_Constants::DEFAULT_BUTTON_TEXT_HOVER_COLOR,
			) );

			$wp_customize->add_control(
				new \WP_Customize_Color_Control(
					$wp_customize,
					'ppwp_pro_form_button_text_hover_color_control', array(
					'label'    => __( 'Text Color (Hover)', 'password-protect-page' ),
					'section'  => 'ppwp_pro_form_button',
					'settings' => 'ppwp_pro_form_button_text_hover_color',
				) )
			);

			/* button background color */
			$wp_customize->add_setting( 'ppwp_pro_form_button_background_color', array(
				'default' => PPW_Pro_Constants::DEFAULT_BUTTON_BACKGROUND_COLOR,
			) );

			$wp_customize->add_control(
				new \WP_Customize_Color_Control(
					$wp_customize,
					'ppwp_pro_form_button_background_color_control', array(
					'label'    => __( 'Background Color', 'password-protect-page' ),
					'section'  => 'ppwp_pro_form_button',
					'settings' => 'ppwp_pro_form_button_background_color',
				) )
			);

			/* button background hover color */
			$wp_customize->add_setting( 'ppwp_pro_form_button_background_hover_color', array(
				'default' => PPW_Pro_Constants::DEFAULT_BUTTON_BACKGROUND_HOVER_COLOR,
			) );

			$wp_customize->add_control(
				new \WP_Customize_Color_Control(
					$wp_customize,
					'ppwp_pro_form_button_background_hover_color_control', array(
					'label'    => __( 'Background Color (Hover)', 'password-protect-page' ),
					'section'  => 'ppwp_pro_form_button',
					'settings' => 'ppwp_pro_form_button_background_hover_color',
				) )
			);

			/* form error message section */
			$wp_customize->add_section( 'ppwp_pro_form_error_message', array(
				'title'    => __( 'Error Message', 'password-protect-page' ),
				'panel'    => 'ppwp_sitewide',
				'priority' => 500,
			) );

			/* error message text */
            $wp_customize->add_setting( 'ppwp_pro_form_error_message_text', array(
				'default' => __( apply_filters( PPW_Constants::HOOK_MESSAGE_ENTERING_WRONG_PASSWORD, PPW_Constants::DEFAULT_WRONG_PASSWORD_MESSAGE ), 'password-protect-page' ),
            ) );

            $wp_customize->add_control(
				new PPW_Pro_Text_Editor_Custom_Control(
					$wp_customize,
					'ppwp_pro_form_error_message_text',
					array(
						'label'    => __( 'Error Message', 'password-protect-page' ),
						'section'  => 'ppwp_pro_form_error_message',
						'settings' => 'ppwp_pro_form_error_message_text',
						'type'     => 'textarea',
					)
				)
			);

			/* error message font size */
			$wp_customize->add_setting( 'ppwp_pro_form_error_message_text_font_size', array(
				'default' => PPW_Pro_Constants::DEFAULT_ERROR_TEXT_FONT_SIZE,
			) );

			$wp_customize->add_control( 'ppwp_pro_form_error_message_text_font_size_control', array(
				'label'       => __( 'Font Size', 'password-protect-page' ),
				'description' => __( 'Font size in px', 'password-protect-page' ),
				'section'     => 'ppwp_pro_form_error_message',
				'settings'    => 'ppwp_pro_form_error_message_text_font_size',
				'type'        => 'number',
			) );

			/* error message font weight */
			$wp_customize->add_setting( 'ppwp_pro_form_error_message_text_font_weight', array(
				'default' => PPW_Pro_Constants::DEFAULT_ERROR_TEXT_FONT_WEIGHT,
			) );
			$wp_customize->add_control( 'ppwp_form_error_message_text_font_weight_control', array(
				'label'    => __( 'Font Weight', 'password-protect-page' ),
				'section'  => 'ppwp_pro_form_error_message',
				'settings' => 'ppwp_pro_form_error_message_text_font_weight',
				'type'     => 'number',
			) );

			/* error message text color */
			$wp_customize->add_setting( 'ppwp_pro_form_error_message_text_color', array(
				'default' => PPW_Pro_Constants::DEFAULT_ERROR_TEXT_FONT_COLOR,
			) );

			$wp_customize->add_control(
				new \WP_Customize_Color_Control(
					$wp_customize,
					'ppwp_pro_form_error_message_text_color_control', array(
					'label'    => __( 'Text Color', 'password-protect-page' ),
					'section'  => 'ppwp_pro_form_error_message',
					'settings' => 'ppwp_pro_form_error_message_text_color',
				) )
			);
        }

		/**
		 * Add dynamic styles
		 *
		 * TODO: move this styles into css file.
		 * @return void
		 */

		public function dynamic_styles() {

			$button_color = get_theme_mod( 'ppwp_pro_form_button_background_color', PPW_Pro_Constants::DEFAULT_BUTTON_BACKGROUND_COLOR );

		?>
			.pda-form-login {
				width: <?php echo get_theme_mod( 'ppwp_pro_form_instructions_width', PPW_Pro_Constants::DEFAULT_FORM_WIDTH ); ?>px;
			}

			.pda-form-login label {
				font-size: <?php echo get_theme_mod( 'ppwp_pro_form_instructions_password_label_font_size', PPW_Pro_Constants::DEFAULT_PASSWORD_LABEL_FONT_SIZE ); ?>px;
				font-weight: <?php echo get_theme_mod( 'ppwp_pro_form_instructions_password_label_font_weight', PPW_Pro_Constants::DEFAULT_PASSWORD_LABEL_FONT_WEIGHT ); ?>;
				color: <?php echo get_theme_mod( 'ppwp_pro_form_instructions_password_label_color', PPW_Pro_Constants::DEFAULT_PASSWORD_LABEL_FONT_COLOR ); ?>;
			}

			.pda-form-login form {
				background-color: <?php echo get_theme_mod( 'ppwp_pro_form_instructions_background_color', PPW_Pro_Constants::DEFAULT_FORM_BACKGROUND_COLOR  ) ?>;
				border-radius: <?php echo get_theme_mod( 'ppwp_pro_form_instructions_border_radius', PPW_Pro_Constants::DEFAULT_BORDER_RADIUS ) ?>px;
				padding-left: <?php echo get_theme_mod( 'ppwp_pro_form_instructions_padding_left', PPW_Pro_Constants::DEFAULT_FORM_PADDING_LEFT ) ?>px;
				padding-top: <?php echo get_theme_mod( 'ppwp_pro_form_instructions_padding_top', PPW_Pro_Constants::DEFAULT_FORM_PADDING_TOP ) ?>px;
				padding-right: <?php echo get_theme_mod( 'ppwp_pro_form_instructions_padding_right', PPW_Pro_Constants::DEFAULT_FORM_PADDING_RIGHT ) ?>px;
				padding-bottom: <?php echo get_theme_mod( 'ppwp_pro_form_instructions_padding_bottom', PPW_Pro_Constants::DEFAULT_FORM_PADDING_BOTTOM ) ?>px;

			}

			.ppwp-sitewide-protection {
				width: 100%;
				height: auto;
				margin: 0;
				background-image: url(<?php echo get_theme_mod( 'ppwp_pro_form_background_image', PPW_Pro_Constants::DEFAULT_SITEWIDE_BACKGROUND_IMAGE ) ?>);
				background-position: center;
				background-repeat: no-repeat;
				background-size: cover;
				background-color: <?php echo get_theme_mod( 'ppwp_pro_form_background_color', PPW_Pro_Constants::DEFAULT_SITEWIDE_BACKGROUND_COLOR  ) ?>;
			}

			.pda-form-login a.ppw-swp-logo {
				background-image: none, url(<?php echo get_theme_mod( 'ppwp_pro_logo_customize', PPW_Pro_Constants::DEFAULT_LOGO_CUSTOMIZE_IMAGE ) ?>);
				background-size: cover;
				width: <?php echo get_theme_mod( 'ppwp_pro_logo_customize_width', PPW_Pro_Constants::DEFAULT_LOGO_CUSTOMIZE_WIDTH ) ?>px;
				height: <?php echo get_theme_mod( 'ppwp_pro_logo_customize_height', PPW_Pro_Constants::DEFAULT_LOGO_CUSTOMIZE_HEIGHT ) ?>px;
				border-radius: <?php echo get_theme_mod( 'ppwp_pro_logo_customize_border_radius', PPW_Pro_Constants::DEFAULT_LOGO_CUSTOMIZE_BORDER_RADIUS ) ?>%;
			}

			.pda-form-login .pda-form-headline {
				font-size: <?php echo get_theme_mod( 'ppwp_pro_form_logo_content_font_size', PPW_Pro_Constants::DEFAULT_TEXT_FONT_SIZE ); ?>px;
				font-weight: <?php echo get_theme_mod( 'ppwp_pro_form_logo_content_font_weight', PPW_Pro_Constants::DEFAULT_TEXT_FONT_WEIGHT ); ?>;
				color: <?php echo get_theme_mod( 'ppwp_pro_form_logo_content_font_color', PPW_Pro_Constants::DEFAULT_TEXT_FONT_COLOR ); ?>;
				word-break: break-all;
				text-align: center;
			}

			.pda-form-login .ppw-show-password {
				font-size: <?php echo get_theme_mod( 'ppwp_pro_form_instructions_show_password_text_size', PPW_Pro_Constants::DEFAULT_SHOW_PASSWORD_TEXT_SIZE ); ?>px;
				color: <?php echo get_theme_mod( 'ppwp_pro_form_instructions_show_password_text_color', PPW_Pro_Constants::DEFAULT_SHOW_PASSWORD_TEXT_COLOR ); ?>;
			}

			.ppw-entire-site-password-error {
				font-size: <?php echo get_theme_mod( 'ppwp_pro_form_error_message_text_font_size', PPW_Pro_Constants::DEFAULT_ERROR_TEXT_FONT_SIZE ); ?>px;
				font-weight: <?php echo get_theme_mod( 'ppwp_pro_form_error_message_text_font_weight', PPW_Pro_Constants::DEFAULT_ERROR_TEXT_FONT_WEIGHT ); ?>;
				color: <?php echo get_theme_mod( 'ppwp_pro_form_error_message_text_color', PPW_Pro_Constants::DEFAULT_ERROR_TEXT_FONT_COLOR ); ?>;
			}

			.pda-form-login .button-login {
				font-size: <?php echo get_theme_mod( 'ppwp_pro_form_button_text_font_size', PPW_Pro_Constants::DEFAULT_PASSWORD_LABEL_FONT_SIZE ); ?>px;
				color: <?php echo get_theme_mod( 'ppwp_pro_form_button_text_color', PPW_Pro_Constants::DEFAULT_BUTTON_TEXT_FONT_COLOR ); ?>;
				text-shadow: 0 -1px 1px <?php echo $button_color ?>, 1px 0 1px <?php echo $button_color ?>, 0 1px 1px <?php echo $button_color ?>, -1px 0 1px <?php echo $button_color ?>;
				border-color: <?php echo $button_color ?>;
				box-shadow: 0 1px 0 <?php echo $button_color ?>;
				background: <?php echo $button_color ?>;
				width: <?php echo get_theme_mod( 'ppwp_pro_form_button_width', PPW_Pro_Constants::DEFAULT_BUTTON_WIDTH ) ?>px;
				height: <?php echo get_theme_mod( 'ppwp_pro_form_button_height', PPW_Pro_Constants::DEFAULT_BUTTON_HEIGHT ) ?>px;
			}

			.pda-form-login .button-login:hover {
				color: <?php echo get_theme_mod( 'ppwp_pro_form_button_text_hover_color', PPW_Pro_Constants::DEFAULT_BUTTON_TEXT_HOVER_COLOR ); ?>;
				background: <?php echo get_theme_mod( 'ppwp_pro_form_button_background_hover_color', PPW_Pro_Constants::DEFAULT_BUTTON_BACKGROUND_HOVER_COLOR ); ?>

			}
			<?php
		}

		/**
		 * Add dynamic scripts to show password in sidewide shortcode.
		 *
		 * TODO: add to JS files.
		 */
		public function dynamic_scripts() {
			?>
			function ppwShowSiteWidePassword() {
				var x = document.getElementById("input_wp_protect_password");
				if (x.type === "password") {
					x.type = "text";
				} else {
					x.type = "password";
				}
			}
			<?php
		}

	}
}
