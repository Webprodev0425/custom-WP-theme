<?php
if ( ! class_exists( 'PPW_Pro_Update_Services' ) ) {

	/**
	 * Class PPW_Pro_Update_Services
	 */
	class PPW_Pro_Update_Services {

		/**
		 * Version to handle when update plugin
		 *
		 * @var string Update version.
		 */
		private $update_version;

		/**
		 * PPW_Pro_Update_Services constructor.
		 */
		public function __construct() {
			$this->update_version = get_option( PPW_Pro_Constants::UPDATE_VERSION, false ) === false ? '1.0' : get_option( PPW_Pro_Constants::UPDATE_VERSION );
		}

		/**
		 * Convert data for entire site
		 */
		public function convert_data_entire_site() {
			if ( '1.0' === $this->update_version ) {
				$old_data = get_option( PPW_Pro_Constants::PPW_ENTIRE_SITE_OPTIONS );
				if ( $old_data ) {
					$passwords     = $old_data[ PPW_Pro_Constants::PPW_PASSWORD_FOR_ENTIRE_SITE ];
					$new_passwords = array();
					foreach ( $passwords as $password ) {
						$new_passwords[ $password ] = array(
							'redirect_url' => '',
						);
					}
					$old_data[ PPW_Pro_Constants::PPW_PASSWORD_FOR_ENTIRE_SITE ] = $new_passwords;
					update_option( PPW_Pro_Constants::PPW_ENTIRE_SITE_OPTIONS, $old_data );
				}
				update_option( PPW_Pro_Constants::UPDATE_VERSION, '1.1' );
			}
		}

	}
}


