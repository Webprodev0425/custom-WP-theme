<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 4/15/20
 * Time: 20:29
 */

if ( class_exists( 'PPW_Pro_Beaver_Helper' ) ) {
	return;
}

/**
 * Helper class for ppwp bb module.
 *
 * Class PPW_Pro_Beaver_Helper
 */
class PPW_Pro_Beaver_Helper {

	/**
	 * Helper function to get Beaver Builder post template.
	 *
	 * @param string $type Template type.
	 *
	 * @return array
	 */
	public static function get_bb_post_template( $type = 'layout' ) {
		$posts = get_posts(
			array(
				'post_type'      => 'fl-builder-template',
				'orderby'        => 'title',
				'order'          => 'ASC',
				'posts_per_page' => '-1',
				'tax_query'      => array(
					array(
						'taxonomy' => 'fl-builder-template-type',
						'field'    => 'slug',
						'terms'    => $type,
					),
				),
			)
		);

		$templates = array();

		foreach ( $posts as $post ) {

			$templates[] = array(
				'id'     => $post->ID,
				'name'   => $post->post_title,
				'global' => get_post_meta( $post->ID, '_fl_builder_template_global', true ),
			);
		}

		return $templates;
	}

	/**
	 * Get saved Beaver Builder page template
	 *
	 * @return array
	 */
	public static function get_bb_saved_page_template() {
		$options = array();
		if ( ! self::is_template_enabled() ) {
			return $options;
		}

		return self::get_post_template( 'layout' );
	}

	/**
	 * Get Beaver Builder saved module template.
	 *
	 * @return array
	 */
	public static function get_bb_saved_module_template() {
		$options = array();
		if ( ! self::is_template_enabled() ) {
			return $options;
		}

		return self::get_post_template( 'module' );
	}

	/**
	 * Get row template of BB.
	 *
	 * @return array
	 */
	public static function get_bb_saved_row_template() {
		$options = array();
		if ( ! self::is_template_enabled() ) {
			return $options;
		}

		return self::get_post_template( 'row' );
	}

	/**
	 * Get BB post template.
	 *
	 * @param string $type BB template type.
	 *
	 * @return array
	 */
	private static function get_post_template( $type ) {
		$options    = array();
		$saved_rows = self::get_bb_post_template( $type );
		if ( count( $saved_rows ) ) {
			foreach ( $saved_rows as $saved_row ) {
				$options[ $saved_row['id'] ] = $saved_row['name'];
			}
		} else {
			$options['no_template'] = __( 'It seems that, you have not saved any template yet.', 'password-protect-page' );
		}

		return $options;
	}

	/**
	 * Check whether the FLBuilderModel class existed.
	 *
	 * @return bool
	 */
	private static function is_template_enabled() {
		if ( ! class_exists( 'FLBuilderModel' ) ) {
			return false;
		}

		return FLBuilderModel::node_templates_enabled();
	}
}
