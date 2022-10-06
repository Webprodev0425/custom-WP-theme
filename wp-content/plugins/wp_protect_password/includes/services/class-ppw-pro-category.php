<?php
if ( ! class_exists( 'PPW_Pro_Category_Services' ) ) {
	class PPW_Pro_Category_Services extends PPW_Password_Services {

		/**
		 * @var PPW_Pro_Password_Services
		 */
		private $password_services;

		/**
		 * PPW_Pro_Password_Services constructor.
		 */
		public function __construct() {
			$this->password_services = new PPW_Pro_Password_Services();
		}

		/**
		 * @param $term_id
		 *
		 * @return string
		 */
		public function get_all_parent_category( $term_id ) {
			if ( 0 === get_category( $term_id )->parent ) {
				return "";
			}

			$arr_all_id_parent = [];
			do {
				$term_id = get_category( $term_id )->parent;
				if ( $term_id !== 0 ) {
					array_push( $arr_all_id_parent, $term_id );
				}
			} while ( $term_id !== 0 );

			return implode( ";", $arr_all_id_parent );
		}


		/**
		 * @param $all_post
		 *
		 * @return array
		 * @throws Exception
		 */
		public function update_category_protect( $all_post ) {
			$all_post_id         = explode( ";", $all_post );
			$is_protect_category = $this->password_services->is_protected_all_posts( $all_post_id );

			if ( $is_protect_category ) {
				foreach ( $all_post_id as $post_id ) {
					update_post_meta( $post_id, PPW_Pro_Constants::AUTO_GENERATE_PWD_META_DATA, "false" );
				}

				return $all_post_id;
			}

			foreach ( $all_post_id as $post_id ) {
				$this->password_services->protect_post_by_password( $post_id );
			}

			return $all_post_id;
		}

		/**
		 * @param $post_id
		 *
		 * @return array
		 */
		public function check_category_parent_protect( $post_id ) {
			$category      = get_the_category( $post_id );
			$all_parent_id = $this->get_all_parent_category( $category[0]->term_id );
			if ( ! empty( $all_parent_id ) ) {
				$arr_parent = array_map( function ( $parent_id ) {
					return [
						"id"         => $parent_id,
						"is_protect" => $this->check_is_protect_category( $parent_id ),
					];
				}, explode( ";", $all_parent_id ) );
			}
			return isset( $arr_parent ) ? $arr_parent : [];
		}

		/**
		 * @param $parent_id
		 *
		 * @return bool
		 */
		public function check_is_protect_category( $parent_id ) {
			$post_ids = $this->get_all_category( $parent_id );

			return $this->password_services->is_protected_all_posts( $post_ids );
		}

		/**
		 * @param $term_id
		 *
		 * @return mixed
		 */
		public function get_all_category( $term_id ) {
			return get_posts( array(
				'numberposts' => - 1,
				'tax_query'   => array(
					array(
						'taxonomy' => 'category',
						'field'    => 'id',
						'terms'    => $term_id,
					),
				),
				'fields'      => 'ids',
			) );
		}


		/**
		 * @throws Exception
		 */
		public function update_category_protect_response() {
			$nonce = $_REQUEST['security_check'];
			if ( ! wp_verify_nonce( $nonce, PPW_Pro_Constants::UPDATE_PROTECT_CATEGORY_FORM_NONCE ) ) {
				send_json_data_error( 'Invalid nonce' );
			}
			if ( ! $_REQUEST['all_post_id'] ) {
				send_json_data_error( 'Invalid nonce' );
			}
			$all_post_id = $this->update_category_protect( $_REQUEST['all_post_id'] );
			wp_send_json( $this->check_category_parent_protect( $all_post_id[0])  );
			wp_die();
		}

		/**
		 * @param $post_ids
		 *
		 * @return bool
		 */
		public function is_protected_all_posts( $post_ids ) {
			return $this->password_services->is_protected_all_posts( $post_ids );
		}

	}
}
