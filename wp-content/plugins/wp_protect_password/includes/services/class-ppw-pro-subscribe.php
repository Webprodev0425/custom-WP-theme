<?php
if ( ! class_exists( 'PPW_Pro_Password_Subscribe' ) ) {
	class PPW_Pro_Password_Subscribe {

		/**
		 * Handle subscriber request(Call api to save data for subscriber)
		 *
		 * @param array $request data request from client.
		 */
		public function handle_subscribe_request( $request ) {
			if ( ! $this->is_valid_data_subscribe( $request ) ) {
				send_json_data_error( __( 'Our server cannot understand the data request!', 'password-protect-page' ) );
			}

			//phpcs:ignore
			$result = $this->request_api( $request['settings']['ppwp_email'] );
			wp_send_json(
				array(
					'is_error' => isset( $result['error_message'] ) ? true : false,
					'message'  => isset( $result['error_message'] ) ? isset( $result['error_message'] ) : '',
				),
				isset( $result['error_message'] ) ? 400 : 200
			);
			wp_die();
		}

		/**
		 * Check is valid data for subscbire form
		 *
		 * @param array $request data request from client.
		 *
		 * @return bool
		 */
		public function is_valid_data_subscribe( $request ) {
			if ( ! array_key_exists( 'settings', $request ) || ! array_key_exists( 'security_check', $request ) ) {
				return false;
			}

			if ( ! wp_verify_nonce( $request['security_check'], PPW_Pro_Constants::SUBSCRIBE_FORM_NONCE ) ) {
				return false;
			}

			if ( ! isset( $_REQUEST['settings']['ppwp_email'] ) ) {
				return false;
			}

			return true;
		}

		/**
		 * Handle subscriber request(Call api to save data for subscriber)
		 *
		 * @param string $email email user request.
		 *
		 * @return array
		 */
		public function request_api( $email ) {
			$data     = array(
				'email'    => $email,
				'campaign' => array(
					'campaignId' => 'KerTB',
				),
			);
			$args     = array(
				'body'        => json_encode( $data ),
				'timeout'     => '100',
				'redirection' => '5',
				'httpversion' => '1.0',
				'blocking'    => true,
				'headers'     => array(
					'X-Auth-Token' => 'api-key ae824cfc3df1a2aa18e8a5419ec1c38b',
					'Content-Type' => 'application/json',
				),
			);
			$response = wp_remote_post(
				'https://api.getresponse.com/v3/contacts',
				$args
			);
			if ( is_wp_error( $response ) ) {
				return array(
					'error_message' => $response->get_error_message(),
				);
			} else {
				update_user_meta( get_current_user_id(), PPW_Pro_Constants::USER_SUBSCRIBE, true );

				return array(
					'data' => json_decode( wp_remote_retrieve_body( $response ) ),
				);
			}
		}
	}
}
