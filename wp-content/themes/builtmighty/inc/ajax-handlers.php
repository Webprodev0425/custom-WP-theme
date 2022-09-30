<?php

function bm_featured_ajax()
{
    $page = $_POST["page"];
    $ppp = $_POST["ppp"];
    $category = $_POST['category'];
    $post_args = $_POST['postArgs'];
    header("Content-Type: text/html");

    $args = array(
        'paged' => $page,
        'post_status' => 'publish',
        'post_type' => 'post',
        'ignore_sticky_posts' => true,
        'posts_per_page' => $ppp,
    );
    if ($post_args) {
        foreach ($post_args as $key => $arg) {
            $args[$key] = $arg;
        }
    }

    $loop = new WP_Query($args);
    while ($loop->have_posts()) {
        $loop->the_post();

        // post meta
        $postID = get_the_ID();
        setup_postdata($postID);
        $title = get_the_title();
        $cats = get_the_category();
        $link = get_the_permalink();
        $cat_list = $cats;
        $excerpt = get_excerpt($postID, 145);
        $thumb = get_post_meta($postID, 'article_thumbnail', true);
        $thumb_args = array(
            'size' => 'featuredPost'
        );
        if (!empty($thumb)) {
            $thumb_args['thumb_id'] = $thumb;
        } else {
            $thumb_args['thumb_id'] = get_post_thumbnail_id();
        }
?>
        <article class="flex row afc nowrap article">
            <a class="featured-image" href="<?php echo $link; ?>">
                <?php render_featured_image($thumb_args); ?>
            </a>
            <aside class="post-excerpt">
                <?php featured_cats($cat_list); ?>
                <hr />
                <a class="featured-image" href="<?php echo $link; ?>">
                    <h4 class="featured-title"><?php echo $title; ?></h4>
                </a>
                <?php echo $excerpt; ?>
                <div class="flex row afc jfs">
                    <?php display_author(); ?>
                </div>
            </aside>
        </article>
<?php
    }
    exit;
}

add_action('wp_ajax_nopriv_bm_featured_ajax', 'bm_featured_ajax');
add_action('wp_ajax_bm_featured_ajax', 'bm_featured_ajax');
