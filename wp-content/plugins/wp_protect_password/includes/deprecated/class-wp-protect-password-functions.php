<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 7/13/18
 * Time: 16:45
 * @deprecated
 */


if ( ! function_exists( 'wpp_is_gold_function' ) ) {
	/**
	 * @return bool
	 * @deprecated
	 */
	function wpp_is_gold_function() {
		$license_service = new PPW_Pro_License_Services();

		return $license_service->is_valid_license();
	}
}

if ( ! function_exists( 'ppwp_get_user_name' ) ) {

	/**
	 * @return string
	 * @deprecated
	 */
	function ppwp_get_user_name() {
		$current_user = wp_get_current_user();

		return $current_user->ID === 0 ? 'N/A' : $current_user->user_login;
	}
}

if ( ! function_exists( 'ppwp_get_post_page_parent_and_status' ) ) {
	/**
	 * TODO: Refactor & Integration-test
	 * Helper function to get the page or post is protected.
	 *
	 * @param int $post_id Post ID
	 *
	 * @used-by PDA_Stats_Helpers
	 *
	 * @return array
	 * @deprecated
	 */
	function ppwp_get_post_page_parent_and_status( $post_id ) {
		$ppwp_auto_protect_all_child_pages = ppw_core_get_setting_type_bool( PPW_Pro_Constants::WPP_AUTO_PROTECT_ALL_CHILD_PAGES );
		if ( ! $ppwp_auto_protect_all_child_pages ) {
			return array();
		}
		$parents    = get_post_ancestors( $post_id );
		$post_id    = $parents ? $parents[ count( $parents ) - 1 ] : $post_id;
		$repository = new PPW_Pro_Repository();

		return array(
			'status'         => $repository->is_protected_item( $post_id ),
			'parent_post_id' => strval( $post_id )
		);
	}
}

if ( ! function_exists( 'get_table_name' ) ) {

	/**
	 * @return string
	 */
	function get_table_name() {
		global $wpdb;

		return $wpdb->prefix . PPW_Pro_Constants::TBL_NAME;
	}
}

