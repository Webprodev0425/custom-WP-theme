<?php

if ( ! class_exists( 'PPW_Pro_Add_On_Services' ) ) {

	class PPW_Pro_Add_On_Services {

		/**
		 * Handle field for Beaver Builder plugin
		 *
		 * @param array $general_fields List field in Free version.
		 *
		 * @return array
		 */
		public function handle_field_for_beaver_builder( $general_fields ) {
			if ( ! is_array( $general_fields ) || count( $general_fields ) < 3 || ! defined( 'PDA_GOLD_V3_VERSION' ) ) {
				return $general_fields;
			}

			$new_general_fields = array(
				'ppwp_download_limit' => array(
					'type'  => 'ppwp-number',
					'label' => __( 'Download Limit', 'password-protect-page' ),
					'unit'  => 'Clicks',
				),
				'ppwp_cookie'         => array(
					'type'  => 'ppwp-number',
					'label' => __( 'Cookie Expiration Time', 'password-protect-page' ),
					'unit'  => 'Hours',
				),
			);

			return array_merge( array_slice( $general_fields, 0, 2 ), $new_general_fields, array_slice( $general_fields, 2 ) );
		}

		/**
		 * Handle field for Elementor plugin
		 *
		 * @param array $controls Controls of elementor.
		 *
		 * @return array
		 */
		public function handle_field_for_elementor( $controls ) {
			if ( ! is_array( $controls ) || count( $controls ) <= 3 || ! defined( 'PDA_GOLD_V3_VERSION' ) ) {
				return $controls;
			}

			$inserted = array(
				array(
					'key'   => 'ppwp_cookie',
					'value' => array(
						'label' => __( 'Cookie Expiration Time (hours)', 'password-protect-page' ),
						'type'  => \Elementor\Controls_Manager::NUMBER,
						'min'   => '1',
						'title' => 'The number of hours before the cookie expires',
					),
				),
				array(
					'key'   => 'ppwp_download_limit',
					'value' => array(
						'label' => __( 'Download Limit (clicks)', 'password-protect-page' ),
						'type'  => \Elementor\Controls_Manager::NUMBER,
						'min'   => '1',
						'title' => 'The number of clicks user can access private download links',
					),
				),
			);

			return array_merge( array_slice( $controls, 0, 3 ), $inserted, array_slice( $controls, 3 ) );
		}

		/**
		 * Add template attribute to ppwp shortcode by concat the string format template=${visual-builder-type}-${template_id}.
		 *
		 * @param string $shortcode Current shortcode string.
		 * @param array  $settings  Elementor settings.
		 * @param string $type      The visual builder type.
		 *
		 * @return string
		 */
		public function add_template_attribute( $shortcode, $settings, $type ) {
			if ( PPW_Pro_Constants::ELEMENTOR === $type ) {
				if ( ! isset( $settings['ppwp_protected_content_type'] ) || ! isset( $settings['ppwp_protected_content_template'] ) ) {
					return $shortcode;
				}

				if ( 'template' !== $settings['ppwp_protected_content_type'] ) {
					return $shortcode;
				}

				$attribute = sprintf( PPW_Pro_Constants::SHORTCODE_TEMPLATE_ATTR_FORMAT, $type, $settings['ppwp_protected_content_template'] );
				$shortcode = $shortcode . ' ' . $attribute;
			} elseif ( PPW_Pro_Constants::BEAVER_BUILDER === $type ) {
				if ( ! isset( $settings->ppwp_protected_content_type ) ) {
					return $shortcode;
				}

				if ( 'content' === $settings->ppwp_protected_content_type ) {
					return $shortcode;
				}

				$tpl_key     = $type . '-' . $settings->ppwp_protected_content_type;
				$setting_key = 'ppwp_protected_content_' . $settings->ppwp_protected_content_type;
				if ( ! isset( $settings->$setting_key ) ) {
					return $shortcode;
				}

				$attribute = sprintf( PPW_Pro_Constants::SHORTCODE_TEMPLATE_ATTR_FORMAT, $tpl_key, $settings->$setting_key );
				$shortcode = $shortcode . ' ' . $attribute;
			}

			return $shortcode;
		}

		/**
		 * Add advanced content by adding template type.
		 *
		 * @param array $controls Elementor controls with key is property and value is options.
		 *
		 * @return array
		 */
		public function add_advanced_content_for_elementor( $controls ) {
			$keys                  = array_column( $controls, 'key' );
			$content_control_index = array_search( 'ppwp_protected_content', $keys, true ); // 4
			if ( false === $keys ) {
				return $controls;
			}

			$new_control = array(
				array(
					'key'   => 'ppwp_protected_content_type',
					'value' => [
						'label'   => __( 'Content Type', 'password-protect-page' ),
						'type'    => \Elementor\Controls_Manager::SELECT,
						'options' => [
							'content'  => __( 'Content', 'password-protect-page' ),
							'template' => __( 'Saved Templates', 'password-protect-page' ),
						],
						'default' => 'content',
					],
				),
				array(
					'key'   => 'ppwp_protected_content_template',
					'value' => [
						'label'     => __( 'Choose Template', 'password-protect-page' ),
						'type'      => \Elementor\Controls_Manager::SELECT,
						'options'   => $this->get_page_templates(),
						'condition' => $this->get_control_conditions( 'ppwp_protected_content_template' ),
					],
				),
				array(
					'key'   => 'ppwp_protected_content',
					'value' => [
						'type'        => \Elementor\Controls_Manager::WYSIWYG,
						'default'     => __( 'This content is your protected content', 'password-protect-page' ),
						'label_block' => true,
						'dynamic'     => [
							'active' => true,
						],
						'condition'   => $this->get_control_conditions( 'ppwp_protected_content' ),
					],
				),
			);

			$controls = array_merge(
				array_slice( $controls, 0, $content_control_index ),
				$new_control,
				array_slice( $controls, $content_control_index + 1 )
			);

			return $controls;
		}


		/**
		 * Add advanced content for beaver builder.
		 *
		 * @param array $fields The beaver builder Widget fields.
		 *
		 * @return array
		 */
		public function add_advanced_content_for_beaver_builder( $fields ) {
			$keys                  = array_keys( $fields );
			$content_control_index = array_search( 'ppwp_protected_content', $keys, true );

			if ( false === $content_control_index ) {
				return $fields;
			}

			$new_fields = array(
				'ppwp_protected_content_type'     => array(
					'type'    => 'select',
					'label'   => __( 'Type', 'password-protect-page' ),
					'default' => 'content',
					'options' => array(
						'content'  => __( 'Content', 'password-protect-page' ),
						'row'      => array(
							'label' => __( 'Saved Rows', 'password-protect-page' ),
						),
						'module'   => array(
							'label' => __( 'Saved Modules', 'password-protect-page' ),
						),
						'template' => array(
							'label' => __( 'Saved Page Templates', 'password-protect-page' ),
						),
					),
					'toggle'  => array(
						'content'  => array(
							'fields' => array( 'ppwp_protected_content' ),
						),
						'row'      => array(
							'fields' => array( 'ppwp_protected_content_row' ),
						),
						'module'   => array(
							'fields' => array( 'ppwp_protected_content_module' ),
						),
						'template' => array(
							'fields' => array( 'ppwp_protected_content_template' ),
						),
					),
				),
				'ppwp_protected_content'          => array(
					'type'    => 'editor',
					'label'   => __( 'Protected Content', 'password-protect-page' ),
					'default' => __( 'This is your protected content.', 'password-protect-page' ),
					'rows'    => '6',
				),
				'ppwp_protected_content_row'      => array(
					'type'    => 'select',
					'label'   => __( 'Select Row', 'password-protect-page' ),
					'options' => PPW_Pro_Beaver_Helper::get_bb_saved_row_template(),
				),
				'ppwp_protected_content_module'   => array(
					'type'    => 'select',
					'label'   => __( 'Select Module', 'password-protect-page' ),
					'options' => PPW_Pro_Beaver_Helper::get_bb_saved_module_template(),
				),
				'ppwp_protected_content_template' => array(
					'type'    => 'select',
					'label'   => __( 'Select Page Template', 'password-protect-page' ),
					'options' => PPW_Pro_Beaver_Helper::get_bb_saved_page_template(),
				),
			);

			$fields = array_merge(
				array_slice( $fields, 0, $content_control_index ),
				$new_fields,
				array_slice( $fields, $content_control_index + 1 )
			);

			return $fields;
		}

		/**
		 * Get control conditions only work if Free allowed empty content.
		 * Only work in PPWP Free version since 1.4.4
		 *
		 * @param string $key Control key.
		 *
		 * @since 1.3.0
		 *
		 * @return array|mixed
		 */
		private function get_control_conditions( $key ) {
			$conditions = array();
			if ( version_compare( PPW_VERSION, '1.4.4', '>=' ) ) {
				$conditions = array(
					'ppwp_protected_content_template' => [
						'ppwp_protected_content_type' => 'template',
					],
					'ppwp_protected_content'          => [
						'ppwp_protected_content_type' => 'content',
					],
				);
			}

			return isset( $conditions[ $key ] ) ? $conditions[ $key ] : [];
		}


		/**
		 * Get all Elementor page templates.
		 *
		 * @param null|string $type The template type.
		 *
		 * @return array
		 */
		private function get_page_templates( $type = null ) {
			$args = [
				'post_type'      => 'elementor_library',
				'posts_per_page' => - 1,
			];

			if ( $type ) {
				$args['tax_query'] = [ // phpcs:ignore
					[
						'taxonomy' => 'elementor_library_type',
						'field'    => 'slug',
						'terms'    => $type,
					],
				];
			}

			$page_templates = get_posts( $args );
			$options        = array();

			if ( ! empty( $page_templates ) && ! is_wp_error( $page_templates ) ) {
				foreach ( $page_templates as $post ) {
					$options[ $post->ID ] = $post->post_title;
				}
			}

			return $options;
		}
	}
}
