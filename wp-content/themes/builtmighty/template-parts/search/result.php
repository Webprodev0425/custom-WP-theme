<?php
if (!function_exists(('search_result'))) {
    function search_result()
    {
        $postID = get_the_ID();
        setup_postdata($postID);
        $title = get_the_title();
        $cats = get_the_category();
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
        <article class="blog__listing item_1_3_gut-blog flex col afs jfs">
        <a href="<?php echo get_the_permalink(); ?>">
            <div class="post_img">
                <?php if(!empty(get_the_post_thumbnail(get_the_ID(), 'large'))) {
                    echo get_the_post_thumbnail(get_the_ID(), 'large');
                }
                else{ ?>
                <img src = "<?php echo get_stylesheet_directory_uri() . '/assets/img/post_placeholder.png'; ?>">
                <?php
                }
                ?>
            </div>
            <div class="post_meta">
                <div class="blog_cat">
                    <?php
                        $category_detail=get_the_category(get_the_ID());
                        echo $category_detail[0]->cat_name;
                    ?>
                </div>
                <h2 class="blog__listing__title"><?php echo $title; ?></h2>
                <div class="flex blog__listing__meta">
                    <div class="post_date"><img src = "<?php echo get_stylesheet_directory_uri() . '/assets/img/calendar.png'; ?>"><?php echo get_the_date(); ?></div>
                    <div class="reading_time"><img src = "<?php echo get_stylesheet_directory_uri() . '/assets/img/view.png'; ?>"><?php echo do_shortcode('[rt_reading_time]') . 'mins'; ?></div>
                    <div class="post_author"><img src = "<?php echo get_stylesheet_directory_uri() . '/assets/img/author.png'; ?>"><?php echo get_the_author_meta('user_login') ?></div>
                </div>
            </div>
        </a>
    </article>
<?php
    }
}
