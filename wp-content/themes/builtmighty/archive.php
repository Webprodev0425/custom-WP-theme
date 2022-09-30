<?php
get_header();
$form = get_field('form_block', 'option');
$form_heading = $form['heading'];
$form_content = $form['content'];
if (have_posts()): ?>
    <section class="blog__listings">
    <?php 
    while (have_posts()): the_post();
        $postID = get_the_ID();
        $post_title = get_the_title();
        $excerpt = get_excerpt($postID, '100', false);
    ?>
    <article class="blog__listing">
    <?php echo $post_title; ?>
    </article>
    <?php
    endwhile; ?>
    </section>
<?php endif; ?>
<?php
echo '<div class="blog__pagination flex row afc jfc">';
echo paginate_links( array(
    'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
    'current'      => max( 1, get_query_var( 'paged' ) ),
    'format'       => '?paged=%#%',
    'end_size'     => 2,
    'mid_size'     => 1,
    'prev_text'    => sprintf( '	
    &#10094; %1$s', __( 'PREVIOUS', 'text-domain' ) ),
    'next_text'    => sprintf( '%1$s &#x276F;', __( 'NEXT', 'text-domain' ) ),
) );
echo '</div>';
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
    <img class="dots" src="<?php echo get_stylesheet_directory_uri() . '/assets/img/dots-light.png'; ?>">
</section>
<?php
get_footer();