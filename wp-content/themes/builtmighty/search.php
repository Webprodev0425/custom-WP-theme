<?php
get_header();
include(trailingslashit(get_template_directory()) . 'template-parts/search/index.php');
global $wp_query;
?>
<header id="page-header" class="container container-main flex row afc jfsb">
    <h1 class="search-results-heading"><span class="count"><?php echo $wp_query->found_posts; ?></span> search results for <span>"<?php echo get_search_query(); ?>"</span></h1>
    <?php get_search_form(); ?>
</header>
<?php
search_layout();
// bm_prefooter_cta($postID);

get_footer();
