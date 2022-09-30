<?php
// add options page for custom fields 
if (function_exists('acf_add_options_page')) {

    acf_add_options_page(array(
        'page_title'     => 'Site Options',
    ));
}

// add theme support
if (function_exists('add_theme_support')) {
    add_theme_support('menus');
    add_theme_support('post-thumbnails');
    add_theme_support('automatic-feed-links');
    add_theme_support('align-wide');
    add_theme_support('align-full');
    add_theme_support('editor-styles');
}

// register menus
add_action('after_setup_theme', 'menu_registration');
function menu_registration()
{
    register_nav_menus(array(
        'menu-main' => esc_html__('Main Menu', 'bm-blogs'),
        'footer-menu' => esc_html__('Footer Menu', 'bm-blogs'),
    ));
}

// custom image sizes
add_image_size('caseStudy', 434, 524, true);

// add human readable image size names
add_filter('image_size_names_choose', 'bm_custom_sizes');
function bm_custom_sizes($sizes)
{
    return array_merge($sizes, array(
        'caseStudy' => __('Case Study'),
    ));
}

// exclude everything except posts from search
function bm_restrict_search($query)
{
    if ($query->is_search) {
        $query->set('post_type', 'post');
    }
    return $query;
}
add_filter('pre_get_posts', 'bm_restrict_search');

// add ACF fields to REST API
function acf_to_rest_api($response, $post, $request)
{
    if (!function_exists('get_fields')) return $response;

    if (isset($post)) {
        $acf = get_fields($post->id);
        $response->data['acf'] = $acf;
    }
    return $response;
}
add_filter('rest_prepare_post', 'acf_to_rest_api', 10, 3);

// add parent_category filter to REST API
function rest_filter_by_parent_category($args, $request) 
{
  if (isset($request['parent_category'])) {
    $parent_category = sanitize_text_field($request['parent_category']);
    $args['tax_query'] = [
      [
        'taxonomy'         => 'category', 
        'field'            => 'term_id', 
        'include_children' => true,
        'operator'         => 'IN',
        'terms'            => $parent_category, 
      ]
    ];
  }
  return $args;
}

add_filter('rest_post_query', 'rest_filter_by_parent_category', 10, 3);

// -- utility functions
include(trailingslashit(get_template_directory()) . 'inc/utils.php');
// -- shortcodes
include(trailingslashit(get_template_directory()) . 'inc/shortcodes.php');
// -- generator functions
include(trailingslashit(get_template_directory()) . 'inc/renderers.php');
// -- custom taxonomies
include(trailingslashit(get_template_directory()) . 'inc/post-meta/taxonomies.php');
// -- ajax handlers
include(trailingslashit(get_template_directory()) . 'inc/ajax-handlers.php');
// -- custom post types
include(trailingslashit(get_template_directory()) . 'inc/custom_post_types.php');
