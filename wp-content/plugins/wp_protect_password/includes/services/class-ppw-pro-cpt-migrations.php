<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 7/25/19
 * Time: 14:48
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'PPW_Cpt_Migrations' ) ) {

	class PPW_Cpt_Migrations {

		const MIGRATED = 'migrated';

		const FAILED = 'failed';

		public static function migrate_v_2_0_0() {
			self::migrate_default_password_to_pro();
		}

		public static function migrate_default_password_to_pro() {
			$posts      = ppw_core_get_posts_password_protected_by_wp();
			self::migrate_default_password_to_pro_by_posts( $posts );
		}

		private static function migrate_default_password_to_pro_by_posts( $posts ) {
			$repository = new PPW_Pro_Repository();
			$services   = new PPW_Pro_Password_Services();
			$total   = count( $posts );
			error_log( '---[PPW-Cpt-Migration] Total: ' . wp_json_encode( $total ) );
			error_log( '---[PPW-Cpt-Migration] Start--- ' );
			$reports = [];

			foreach ( $posts as $key => $post ) {
				$post_id       = $post->ID;
				$post_password = str_replace( ' ', '', $post->post_password );

				// 1. Delete if exist password is default in db
				$repository->delete_password_by_post_id_and_is_default( $post_id, 1 );

				// 2. Delete if exist password in db
				$repository->delete_password_by_post_id_and_password( $post_id, $post_password );

				// 3. Check condition and protect page or post
				if ( ! $services->is_protected_content( $post_id ) ) {
					$services->protect_page_post( $post_id );
				}

				$tmp      = $key + 1;
				$percent  = ceil( $tmp / ( $total / 100 ) );
				$progress = sprintf( '(%s of %s, %s%%)', $tmp, $total, $percent );
				error_log( sprintf( '|Migrating post ID: %d', $post_id ) );
				error_log( sprintf( '|Progress: %s', $progress ) );

				// 4. Insert new default password
				$data = array(
					'post_id'           => $post_id,
					'password'          => $post_password,
					'campaign_app_type' => PPW_Pro_Constants::CAMPAIGN_TYPE['DEFAULT'],
					'is_default'        => 1,
				);
				$result = $repository->insert( $data );
				if ( FALSE === $result ) {
					$reports[$post_id] = $post_password;
					continue;
				}

				// 5. Remove default password in Wordpress
				wp_update_post( array(
					'ID'            => $post_id,
					'post_password' => '',
				) );

				$success = count( $reports ) === $total;

				if ( $success ) {
					error_log( '|Migrate result: OK' );
				} else {
					error_log( '|Failed records: ' . wp_json_encode( $reports ) );
				}
				error_log( '---[PPW-End-Migration] End --- ' );

			}
		}

	}

}
