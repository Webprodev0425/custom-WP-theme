<?php
define('GOOGLE_ANALYTICS_ID', '');

// load theme assets and inherited dependencies
if (!function_exists('bm_enqueue_assets')) :
    add_action('wp_enqueue_scripts', 'bm_enqueue_assets');
    function bm_enqueue_assets()
    {
        wp_register_style('site_main_css', get_template_directory_uri() . '/assets/css/build/theme.min.css', array(), date("YmdHis"), 'all');
        wp_enqueue_style('site_main_css', get_template_directory_uri() . '/assets/css/build/theme.min.css');
        wp_enqueue_script('site_main_js', get_template_directory_uri() . '/assets/js/build/theme.min.js', null, null, true);
    }
endif;

// load admin only scripts
if (!function_exists('load_custom_wp_admin_scripts')) {
    function load_custom_wp_admin_scripts()
    {
        wp_register_style('site_admin_css', get_template_directory_uri() . '/assets/css/build/admin.min.css');
        wp_enqueue_style('site_admin_css', get_template_directory_uri() . '/assets/css/build/admin.min.css');
        if (is_local_dev()) {
            wp_enqueue_script('admin_scripts', get_template_directory_uri() . '/assets/js/build/admin.min.js');
        } else {
            // compressed version
            wp_enqueue_script('admin_scripts', get_template_directory_uri() . '/assets/js/build/admin.min.js.br');
        }
    }
    add_action('admin_enqueue_scripts', 'load_custom_wp_admin_scripts');
}

// let's async and defer some scripts
function add_defer_attribute($tag, $handle)
{
    // add script handles to the array below
    $scripts_to_defer = array('font-awesome');

    foreach ($scripts_to_defer as $defer_script) {
        if ($defer_script === $handle) {
            return str_replace(' src', ' defer src', $tag);
        }
    }
    return $tag;
}
// add_filter('script_loader_tag', 'add_defer_attribute', 10, 2);

function add_async_attribute($tag, $handle)
{
    $scripts_to_async = array('site_main_js');

    foreach ($scripts_to_async as $async_script) {
        if ($async_script === $handle) {
            return str_replace(' src', ' async src', $tag);
        }
    }
    return $tag;
}
add_filter('script_loader_tag', 'add_async_attribute', 10, 2);

