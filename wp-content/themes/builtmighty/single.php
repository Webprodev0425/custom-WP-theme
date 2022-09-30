<?php
get_header();
$postID = get_the_ID();
$form = get_field('form_block', 'option');
$form_heading = $form['heading'];
$form_content = $form['content'];

?>
<div id="_progress"></div>
<?php
if (have_posts()) : while (have_posts()) : the_post();
?>
<img id="author_open" src = "<?php echo get_stylesheet_directory_uri() . '/assets/img/author_open.png'; ?>">
<section class="single-post__hero" style = "background-image: url('<?php echo the_post_thumbnail_url(); ?>')">
    <div class="container">
        <div class="single-post-meta">
            <a href="/blog" class="cs-hero__back-to-cs">Back to Blogs</a>
            <h1><?php the_title(); ?></h1>
            <div class="flex blog_meta">
                <div class="post_date"><img src = "<?php echo get_stylesheet_directory_uri() . '/assets/img/calendar_white.png'; ?>"><?php echo get_the_date(); ?></div>
                <div class="reading_time"><img src = "<?php echo get_stylesheet_directory_uri() . '/assets/img/view_white.png'; ?>"><?php echo do_shortcode('[rt_reading_time]') . 'mins'; ?></div>
                <div class="post_author"><img src = "<?php echo get_stylesheet_directory_uri() . '/assets/img/author_white.png'; ?>"><?php echo get_the_author_meta('user_login') ?></div>
            </div>
        </div>
    </div>
</section>
<main>
    <section class="single-post__container container container-main clearfix">
        <div class="left-content" id="current_author">
            <?php echo do_shortcode('[ez-toc]'); ?>
            <div class = "author_detail">
                <p class="written_by">Written By</p>
                <div class="author_photo">
                    <?php echo get_avatar(get_the_author_meta('user_email'), '100'); ?>
                </div>
                <p class="author_name"><?php echo get_the_author_meta('user_login'); ?></p>
                <p class="author_role"><?php echo get_the_author_meta('roles')[0]; ?></p>
                <p class="author_desc"><?php echo get_the_author_meta('description'); ?></p>                
            </div>
        </div>
        <img id="author_close" src = "<?php echo get_stylesheet_directory_uri() . '/assets/img/author_close.png'; ?>">
        <div class='right-content'>
            <?php the_content(); ?>
            <div class = "divider_line"></div>
            <?php
                $next_post = get_next_post();
                $next_post_id = $next_post->ID;
                $next_author_id=$next_post->post_author;
                $prev_post = get_previous_post();
                $prev_post_id = $prev_post->ID;
                $prev_author_id=$prev_post->post_author;
            ?>
            <div class="next_prev_posts">
                <div class = "prev_post">
                    <div class = "prev_post_thumb">
                        <?php
                        if(!empty(get_the_post_thumbnail($prev_post_id, 'large'))) {
                            echo get_the_post_thumbnail($prev_post_id, 'large');
                        }
                        else{ ?>
                        <img src = "<?php echo get_stylesheet_directory_uri() . '/assets/img/post_placeholder.png'; ?>">
                        <?php
                        }
                        ?>
                    </div>
                    <div class = "prev_post_meta">
                        <div class = "prev_post_cat">
                            <?php
                                $category_detail=get_the_category($prev_post_id);
                                echo $category_detail[0]->cat_name;
                            ?>
                        </div>
                        <div class = "prev_post_title">
                            <?php echo get_the_title($prev_post_id); ?>
                        </div>
                        <div class = "prev_post_date">
                            <img src = "<?php echo get_stylesheet_directory_uri() . '/assets/img/calendar.png'; ?>">
                            <?php echo get_the_date('Y-m-d',$prev_post_id); ?>
                        </div>
                        <div class = "prev_post_read_time">
                            <img src = "<?php echo get_stylesheet_directory_uri() . '/assets/img/view.png'; ?>">
                            <?php echo do_shortcode('[rt_reading_time post_id="'.$prev_post_id.'"]') .'mins'; ?>
                        </div>
                        <div class = "prev_post_author">
                            <img src = "<?php echo get_stylesheet_directory_uri() . '/assets/img/author.png'; ?>">
                            <?php echo get_the_author_meta('user_login',$prev_author_id); ?>
                        </div>
                    </div>
                </div>
                <div class = "next_post">
                    <div class = "next_post_thumb">
                        <?php
                        if(!empty(get_the_post_thumbnail($next_post_id, 'large'))) {
                            echo get_the_post_thumbnail($next_post_id, 'large');
                        }
                        else{ ?>
                        <img src = "<?php echo get_stylesheet_directory_uri() . '/assets/img/post_placeholder.png'; ?>">
                        <?php
                        }
                        ?>
                    </div>
                    <div class = "next_post_meta">
                        <div class = "next_post_cat">
                            <?php
                                $category_detail=get_the_category($next_post_id);
                                echo $category_detail[0]->cat_name;
                            ?>
                        </div>
                        <div class = "next_post_title">
                            <?php echo get_the_title($next_post_id); ?>
                        </div>
                        <div class = "next_post_date">
                            <img src = "<?php echo get_stylesheet_directory_uri() . '/assets/img/calendar.png'; ?>">
                            <?php echo get_the_date('Y-m-d',$next_post_id); ?>
                        </div>
                        <div class = "next_post_read_time">
                            <img src = "<?php echo get_stylesheet_directory_uri() . '/assets/img/view.png'; ?>">
                            <?php echo do_shortcode('[rt_reading_time post_id="'.$next_post_id.'"]') .'mins'; ?>
                        </div>
                        <div class = "next_post_author">
                            <img src = "<?php echo get_stylesheet_directory_uri() . '/assets/img/author.png'; ?>">
                            <?php echo get_the_author_meta('user_login',$next_author_id); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="next_prev_links">
                <div class="prev_link">
                    <?php previous_post_link( '%link', '<img src = "/wordpress/wp-content/themes/builtmighty/assets/img/prev_post.png"> Previous' ); ?>
                </div>
                <div class="next_link">
                    <?php next_post_link( '%link', 'Next <img src = "/wordpress/wp-content/themes/builtmighty/assets/img/next_post.png">' ); ?>
                </div>
            </div>
        </div>
    </section>    
</main>

<?php
    endwhile;
endif;
?>
<section class="form-block" id="form-block">
    <div class="container container-main flex row afs">
        <div class="form-block__content item_1_2">
            <?php if(!empty($form_heading)): ?>
                <h2 class="form-block__heading"><?php echo $form_heading; ?></h2>
            <?php endif; ?>
            <?php if(!empty($form_content)): ?>
                <div class="form-block__content__content">
                    <?php echo $form_content; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="form-block__form item_1_2">
            <?php echo do_shortcode('[gravityform id="1" title="false"]'); ?>
        </div>
    </div>
    <img class = "dots-left__bottom" src = "<?php echo get_stylesheet_directory_uri() . '/assets/img/accent_left.png'; ?>">
    <img class = "dots-right__bottom" src = "<?php echo get_stylesheet_directory_uri() . '/assets/img/accent_right.png'; ?>">
</section>
<?php

get_footer();
