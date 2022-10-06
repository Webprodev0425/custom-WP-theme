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

if ( ! class_exists( 'PPW_Migrations' ) ) {

	class PPW_Migrations {

		const MIGRATED = 'migrated';

		const FAILED = 'failed';

		public static function migrate_v_2_0_0() {
			$free_service = new PPW_Password_Services();

			self::migrate_default_pwd( $free_service );
//			$column_services = new PPW_Pro_Column_Services();
//			$post_type          = $column_services->custom_post_type_for_feature_migration( array( 'page', 'post' ) );


			$gold_service = new PPW_Pro_Password_Services();
			$repository   = new PPW_Pro_Repository();

			$records = $free_service->get_data_to_migrate();
			$total   = count( $records );
			error_log( '---[PPW-Free-Migration] Total: ' . wp_json_encode( $total ) );
			error_log( '---[PPW-Free-Migration] Start--- ' );
			$reports = [];

			foreach ( $records as $key => $item ) {
				$types   = $item['passwords'];
				$post_id = $item['post_id'];

				if ( ! $repository->is_protected_item( $post_id ) ) {
					$gold_service->protect_page_post( $post_id );
				}

				$global   = $types['global'];
				$role     = $types['role'];
				$tmp      = $key + 1;
				$percent  = ceil( $tmp / ( $total / 100 ) );
				$progress = sprintf( '(%s of %s, %s%%)', $tmp, $total, $percent );
				error_log( sprintf( '|Migrating post ID: %d', $post_id ) );
				error_log( sprintf( '|Progress: %s', $progress ) );

				// Migrate global passwords
				$global_result = self::migrate_global_pw( $global, $gold_service, $post_id );
				$role_result   = self::migrate_role_pw( $role, $gold_service, $post_id );

				$reports[ $post_id ] = [
					'global' => $global_result,
					'role'   => $role_result,
					'status' => count( $global_result[ self::FAILED ] ) > 0 || count( $role_result[ self::FAILED ] ) > 0 ? 0 : 1
				];

			}

			$success = array_search( 0, array_column( $reports, 'status' ) ) === false;
			if ( $success ) {
				error_log( sprintf( '|Migrate result: OK' ) );
				// update_flag : migrated
				PPW_Options_Services::get_instance()->add_flag( PPW_Pro_Constants::MIGRATED_FREE_FLAG );
			} else {
				error_log( sprintf( '|Migrate failed: OK' ) );
			}
			PPW_Cpt_Migrations::migrate_v_2_0_0();

			error_log( '|Reports: ' . wp_json_encode( $reports ) );
			error_log( '---[PPW-End-Migration] End --- ' );
		}

		/**
		 * @param $global
		 * @param $service
		 * @param $post_id
		 *
		 * @return array
		 */
		private static function migrate_global_pw( $global, $service, $post_id ) {
			$result = [
				self::FAILED   => [],
				self::MIGRATED => [],
			];

			foreach ( $global as $val ) {
				if ( $service->is_password_existed( $val, $post_id ) ) {
					continue;
				}

				$status = $service->insert_password( $post_id, $val, array(
					'ppwp_campaign_app_type' => PPW_Pro_Constants::CAMPAIGN_TYPE['AUTO'],
				) );

				$result = self::set_pwd_report( $status, $val, $result );
			}

			return $result;
		}

		private static function migrate_role_pw( $pws, $service, $post_id ) {
			$result = [
				self::FAILED   => [],
				self::MIGRATED => [],
			];

			foreach ( $pws as $pw => $roles ) {
				$type = implode(
					';',
					array_map( function ( $val ) {
						return PPW_Pro_Constants::CAMPAIGN_TYPE['ROLE'] . $val;
					}, $roles )
				);

				if ( $service->is_password_existed( $pw, $post_id ) ) {
					continue;
				}

				$status = $service->insert_password( $post_id, $pw, array(
					'ppwp_campaign_app_type' => $type,
				) );

				$result = self::set_pwd_report( $status, $pw, $result );
			}

			return $result;
		}

		/**
		 * @param $status
		 * @param $val
		 * @param $result
		 *
		 * @return mixed
		 */
		private static function set_pwd_report( $status, $val, $result ) {
			if ( false === $status ) {
				$result[ self::FAILED ][] = $val;
			} else {
				$result[ self::MIGRATED ][] = $val;
			}

			return $result;
		}

		private static function migrate_default_pwd( $free_service ) {
			if ( PPW_Options_Services::get_instance()->get_flag( PPW_Constants::MIGRATED_DEFAULT_PW ) ) {
				return;
			}
			$free_service->migrate_default_password();
			PPW_Options_Services::get_instance()->add_flag( PPW_Constants::MIGRATED_DEFAULT_PW );
		}
	}

}
