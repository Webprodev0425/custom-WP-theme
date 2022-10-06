<?php
$post_ids     = $this->category_service->get_all_category( $term_id );

$str_post_ids = implode( ";", $post_ids );

$all_category_childs = get_categories( [
	'child_of' => $term_id,
] );
$arr_id_childs       = array_map( function ( $category_child ) {
	return $category_child->term_id;
}, $all_category_childs );

$str_all_id_childs = implode( ";", $arr_id_childs );
$all_parent_category_id = $this->category_service->get_all_parent_category( $term_id );
$is_protected = $this->category_service->is_protected_all_posts( $post_ids );
$btn_label = $is_protected ? "Unprotect category" : "Protect category";
?>
<div class="pda-pwd-tools">
	<input type="hidden" id="pda-password-nonce-category_<?php echo esc_html( $term_id ) ?>"
	       nonce="<?php echo wp_create_nonce( PPW_Pro_Constants::UPDATE_PROTECT_CATEGORY_FORM_NONCE ); ?>"/>
	<input id="all_post_id_in_category_<?php echo esc_html( $term_id ) ?>" type="hidden"
	       value="<?php echo esc_html( $str_post_ids ); ?>">
	<input id="all-child-category-id_<?php echo esc_html( $term_id ) ?>" type="hidden"
	       value="<?php echo esc_html( $str_all_id_childs ); ?>">
	<input id="all-parent-category-id_<?php echo esc_html( $term_id ) ?>" type="hidden"
	       value="<?php echo esc_html( $all_parent_category_id ); ?>">
	<input type="checkbox" disabled class="wppp-check-category-protection"
	       id="pda-password-protection-category_<?php echo esc_html( $term_id ); ?>"
		<?php if ( $is_protected )
			esc_attr_e( 'checked' ) ?>
	> Password protected?</br>
</div>
<?php if ( $post_ids ) { ?>
	<a class="pda-pwd-tbl-category" style="cursor: pointer"
	   id="pda-protect-password-category_<?php echo esc_html( $term_id ) ?>"><?php echo esc_html( $btn_label ) ?></a>
<?php } ?>

