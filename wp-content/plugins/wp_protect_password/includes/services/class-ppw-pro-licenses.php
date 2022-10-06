<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 7/23/19
 * Time: 11:23
 */

if ( ! class_exists( 'PPW_Pro_License_Services' ) ) {

	class PPW_Pro_License_Services extends PPW_Pro_Base_Services {

		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new PPW_Pro_License_Services();
			}

			return self::$instance;
		}
		/**
		 *
		 * @return bool
		 */
		public function is_valid_license() {
			$license_key      = get_option( PPW_Pro_Constants::LICENSE_KEY, '' );
			$is_valid_license = get_option( PPW_Pro_Constants::LICENSE_OPTIONS );

			return ! empty( $license_key ) && ( '1' === $is_valid_license || true === $is_valid_license );
		}

		/**
		 * @param $request
		 */
		public function check_license( $request ) {
			if ( ppw_pro_is_data_invalid( $request, PPW_Pro_Constants::LICENSE_FORM_NONCE, PPW_Pro_Constants::DATA_LICENSE ) ) {
				send_json_data_error( __( PPW_Constants::BAD_REQUEST_MESSAGE, 'password-protect-page' ) );
			}

			$license = $request['license'];
			$result  = YME_LICENSE::checkLicense( $license, 'wpp', get_site_option( PPW_Pro_Constants::APP_ID ) );
			$data    = $result['data'];
			if ( ! $data ) {
				send_json_data_error( __( "There is something's wrong. Please <a href=\"hello@preventdirectaccess.com\">contact</a> the plugin owner!", 'password-protect-page' ) );
			}

			if ( is_object( $data ) && property_exists( $data, 'errorMessage' ) ) {
				send_json_data_error( __( $data->errorMessage, 'password-protect-page' ) );
			}

			update_option( PPW_Pro_Constants::LICENSE_KEY, $license );
			update_option( PPW_Pro_Constants::LICENSE_OPTIONS, true );
			update_option( PPW_Pro_Constants::LICENSE_ERROR, '' );

			global $migration_service;
			$migration_service->start_run();

			wp_send_json( array(
				'is_error' => false,
				'message'  => __( 'Your settings have been updated successfully!', 'password-protect-page' )
			) );
			wp_die();
		}

		public function remove_license() {
			return delete_option( PPW_Pro_Constants::LICENSE_KEY ) && delete_option( PPW_Pro_Constants::LICENSE_OPTIONS );
		}

		public function get_license_type() {
			$app_id             = get_site_option( PPW_Pro_Constants::APP_ID, null );
			$not_available_type = 'N/A';

			if ( is_null( $app_id ) ) {
				return $not_available_type;
			}

			$license_map = [
				'77808414' => '3-site subscription license',
				'78043506' => '10-site subscription license',
				'78043507' => '15-site subscription license',
				'78022875' => '3-site lifetime license',
				'78043526' => '10-site lifetime license',
				'78043515' => '15-site lifetime license',
			];

			return isset( $license_map[ $app_id ] ) ? $license_map[ $app_id ] : $not_available_type;

		}
	}
}
