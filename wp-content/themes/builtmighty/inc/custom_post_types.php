<?php
// Case Studies
function case_study_cpt() {

	$labels = array(
		'name'                  => 'Case Studies',
		'singular_name'         => 'Case Study',
		'menu_name'             => 'Case Studies',
		'name_admin_bar'        => 'Case Study',
		'archives'              => 'Case Study Archives',
		'attributes'            => 'Case Study Attributes',
		'parent_item_colon'     => 'Parent Case Study:',
		'all_items'             => 'All Case Studies',
		'add_new_item'          => 'Add New Case Study',
		'add_new'               => 'Add New',
		'new_item'              => 'New Case Study',
		'edit_item'             => 'Edit Case Study',
		'update_item'           => 'Update Case Study',
		'view_item'             => 'View Case Study',
		'view_items'            => 'View Case Study',
		'search_items'          => 'Search Case Study',
		'not_found'             => 'Not found',
		'not_found_in_trash'    => 'Not found in Trash',
		'featured_image'        => 'Featured Image',
		'set_featured_image'    => 'Set featured image',
		'remove_featured_image' => 'Remove featured image',
		'use_featured_image'    => 'Use as featured image',
		'insert_into_item'      => 'Insert into case study',
		'uploaded_to_this_item' => 'Uploaded to this case study',
		'items_list'            => 'Case studies list',
		'items_list_navigation' => 'Case studies list navigation',
		'filter_items_list'     => 'Filter case studies list',
	);
	$rewrite = array(
		'slug'                  => 'case-studies',
		'with_front'            => true,
		'pages'                 => true,
		'feeds'                 => true,
	);
	$args = array(
		'label'                 => 'Case Study',
		'description'           => 'Built Mighty Case Studies',
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail' ),
		'taxonomies'            => array( 'category', 'post_tag' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-businessman',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'rewrite'               => $rewrite,
		'capability_type'       => 'page',
		'show_in_rest'          => true,
	);
	register_post_type( 'case_studies', $args );

}
add_action( 'init', 'case_study_cpt', 0 );

// Team Members
function team_member_cpt() {

	$labels = array(
		'name'                  => 'Team Members',
		'singular_name'         => 'Team Member',
		'menu_name'             => 'Team Members',
		'name_admin_bar'        => 'Team Member',
		'archives'              => 'Team Member Archives',
		'attributes'            => 'Team Member Attributes',
		'parent_item_colon'     => 'Parent Team Member:',
		'all_items'             => 'All Team Members',
		'add_new_item'          => 'Add New Team Member',
		'add_new'               => 'Add New',
		'new_item'              => 'New Team Member',
		'edit_item'             => 'Edit Team Member',
		'update_item'           => 'Update Team Member',
		'view_item'             => 'View Team Member',
		'view_items'            => 'View Team Member',
		'search_items'          => 'Search Team Member',
		'not_found'             => 'Not found',
		'not_found_in_trash'    => 'Not found in Trash',
		'featured_image'        => 'Featured Image',
		'set_featured_image'    => 'Set featured image',
		'remove_featured_image' => 'Remove featured image',
		'use_featured_image'    => 'Use as featured image',
		'insert_into_item'      => 'Insert into team member',
		'uploaded_to_this_item' => 'Uploaded to this team member',
		'items_list'            => 'Team Members list',
		'items_list_navigation' => 'Team Members list navigation',
		'filter_items_list'     => 'Filter case studies list',
	);
	$rewrite = array(
		'slug'                  => 'team-members',
		'with_front'            => true,
		'pages'                 => true,
		'feeds'                 => true,
	);
	$args = array(
		'label'                 => 'Team Member',
		'description'           => 'Built Mighty Team Members',
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail' ),
		'taxonomies'            => array( 'category', 'post_tag' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-businessman',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'rewrite'               => $rewrite,
		'capability_type'       => 'page',
		'show_in_rest'          => true,
	);
	register_post_type( 'team_members', $args );

}
add_action( 'init', 'team_member_cpt', 0 );