<?php
// register custom taxonomies
function bm_custom_taxonomies()
{
    // industry tax
    $industry_labels = array(
        'name'                       => _x('Industries', 'Taxonomy General Name', 'text_domain'),
        'singular_name'              => _x('Industry', 'Taxonomy Singular Name', 'text_domain'),
        'menu_name'                  => __('Industries', 'text_domain'),
        'all_items'                  => __('All Industries', 'text_domain'),
        'parent_item'                => __('Parent Industry', 'text_domain'),
        'parent_item_colon'          => __('Parent Industry:', 'text_domain'),
        'new_item_name'              => __('New Industry Name', 'text_domain'),
        'add_new_item'               => __('Add New Industry', 'text_domain'),
        'edit_item'                  => __('Edit Industry', 'text_domain'),
        'update_item'                => __('Update Industry', 'text_domain'),
        'view_item'                  => __('View Industry', 'text_domain'),
        'separate_items_with_commas' => __('Separate items with commas', 'text_domain'),
        'add_or_remove_items'        => __('Add or remove items', 'text_domain'),
        'choose_from_most_used'      => __('Choose from the most used', 'text_domain'),
        'popular_items'              => __('Popular Items', 'text_domain'),
        'search_items'               => __('Search Items', 'text_domain'),
        'not_found'                  => __('Not Found', 'text_domain'),
        'no_terms'                   => __('No items', 'text_domain'),
        'items_list'                 => __('industries list', 'text_domain'),
        'items_list_navigation'      => __('industries list navigation', 'text_domain'),
    );
    $industry_args = array(
        'labels'                     => $industry_labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_in_rest'               => true,
        'show_tagcloud'              => true,
    );
    register_taxonomy('industry', array('case_studies', ' page'), $industry_args);
}
add_action('init', 'bm_custom_taxonomies', 0);

// // add categories to pages
// function bm_add_categories_to_pages()
// {
//     register_taxonomy_for_object_type('category', 'page');
//     register_taxonomy_for_object_type('industry', 'page');
// }
// add_action('init', 'bm_add_categories_to_pages');
