<?php
include(trailingslashit(get_template_directory()) . 'template-parts/search/results.php');

if (!function_exists('search_layout')) {
    function search_layout()
    {
        search_results();
    }
}
