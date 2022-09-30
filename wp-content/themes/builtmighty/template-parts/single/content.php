<?php
if (!function_exists('post_content')) :
    function post_content($content)
    {
        $thumb_args = array(
            'thumb_id' => get_post_thumbnail_id(),
            'size' => 'full'
        );
        $postID = get_the_ID();
?>
        <section class="post-content">
            <?php render_featured_image($thumb_args); ?>
            <section class="copy">
                <?php
                foreach ($content as $block) {
                    echo apply_filters('the_content', render_block($block));
                }
                ?>
            </section>
            <?php /** bm Promo Widget **/
            global $bmPostPromotions;
            global $post;
            $bmPostPromotions->display_post_promotion($post);
            /** END bm Promo Widget **/ ?>
        </section>
<?php
    }
endif;
