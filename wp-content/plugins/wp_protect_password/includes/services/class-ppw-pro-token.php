<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 2/4/20
 * Time: 15:12
 */
if ( ! class_exists( 'PPW_Pro_Token_Services' ) ) {
	/**
	 * Token Services class.
	 *
	 * Class PPW_Pro_Token_Services
	 */
	class PPW_Pro_Token_Services {
		/**
		 * Append token storing the encrypted post ID to URL's query param.
		 * Token format: {random_string}-{nonce}-{post_id}-{timestamp}.
		 *
		 * @param string $url           The URL to replace.
		 * @param int    $attachment_id The Attachment's ID.
		 * @param int    $post_id       The current post ID.
		 *
		 * @return string
		 */
		public function append_token_to_protected_link( $url, $attachment_id, $post_id ) {
			$now = time();
			$rd  = '';
			try {
				$rd = bin2hex( random_bytes( 3 ) );
			} catch ( Exception $ex ) {
				error_log( 'Exception: ' . print_r( $ex, true ) );
			}

			// Add a security level here that make users harder to change the attachment_id.
			$nonce = wp_create_nonce( PPW_Pro_Constants::PDA_TOKEN_NONCE_ACTION . $attachment_id );
			$token = PPW_Pro_Constants::PDA_ORIGIN_LINK_TOKEN . '=' . ppw_encrypt_decrypt( 'encrypt', "$rd-$nonce-$post_id-$now" ) . PPW_Pro_Constants::PDA_TOKEN_POST_FIX;
			$query = wp_parse_url( $url, PHP_URL_QUERY );

			return $query ? "$url&$token" : "$url?$token";
		}

		/**
		 * Process the raw token from GET params.
		 *
		 * @param string $raw_token     Encrypted token.
		 * @param int    $attachment_id The attachment ID.
		 *
		 * @return bool|int
		 *  bool: False means invalid token
		 *  int: Post ID
		 */
		public function process_protected_file_token( $raw_token, $attachment_id ) {
			// Remove the post fix and everything string appended.
			$beforePostFix = explode( PPW_Pro_Constants::PDA_TOKEN_POST_FIX, $raw_token )[0];
			$token         = ppw_encrypt_decrypt( 'decrypt', $beforePostFix );

			// Token format: {rd}-{nonce}-{post_id}-{timestamp}.
			$parts = explode( '-', $token );
			if ( 4 !== count( $parts ) ) {
				return false;
			}

			$nonce = $parts[1];
			if ( ! wp_verify_nonce( $nonce, PPW_Pro_Constants::PDA_TOKEN_NONCE_ACTION . $attachment_id ) ) {
				return false;
			}

			$post_id = (int) $parts[2];

			return $post_id;
		}

	}
}

