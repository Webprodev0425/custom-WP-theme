<?php
get_header();
// hero stuff
$hero = get_field('hero', 'option');
$number_of_columns = $hero['number_of_columns'];
$column_heading = $hero['column_heading'];
$column_content = $hero['column_content'];
$subheading = $hero['subheading'];
$checkbox_list = $hero['checkbox_list'];
$image = $hero['image'];
$eyebrow_heading = $hero['eyebrow_heading'];
$big_heading = $hero['big_heading'];
$hero_content = $hero['content'];
$desktop_background_image = $hero['hero_desktop_background_image'];
// form stuff 
$form = get_field('form_block', 'option');
$form_heading = $form['heading'];
$form_content = $form['content'];
?>
<?php
    $data = get_queried_object();
?>
<section class="case_studies_hero hero__<?php echo $number_of_columns; ?>-column" style="background-image: url('<?php echo $desktop_background_image['url']; ?>');">
    <?php if($number_of_columns == 1): ?>
        <img class="dots-top" src="<?php echo get_stylesheet_directory_uri() . '/assets/img/dots-half-vertical.png'; ?>">
        <img class="dots-bottom" src="<?php echo get_stylesheet_directory_uri() . '/assets/img/dots-half-vertical.png'; ?>">
    <?php endif; ?>
    <div class="container container-wide flex row afc">
        <?php if($number_of_columns > 1): ?>
            <div class="item_1_2">
                <?php if(!empty($subheading)): ?><h1 class="hero__heading"><?php echo $data->name; ?></h1> <?php endif; ?>             
                <?php if(!empty($column_content)): ?><h4 class="hero__col_content"><?php echo $data->description; ?></h4> <?php endif; ?>
                <?php if(!empty($checkbox_list)): ?>
                    <ul class="hero__checkbox-list">
                    <?php foreach($checkbox_list as $checkbox): ?>
                        <li class="hero__checkbox-list-item flex row afs nowrap">
                            <?php 
                            render_svg('circle-check.svg', 'circle-check');
                            echo '<span>' . $checkbox['checkbox_content'] . '</span>';; 
                            ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </div>
            <div class="item_1_2 flex row jfe">
                <?php if(!empty($image)): ?>
                    <picture>
                        <source srcset="<?php echo wp_get_attachment_image_src($image['id'], 'large')[0]; ?>" media="(min-width: 991px)">
                        <source srcset="<?php echo wp_get_attachment_image_src($image['id'], 'medium')[0]; ?>" media="(min-width: 768px)">
                        <?php echo wp_get_attachment_image($image['id'], 'full'); ?>
                    </picture>
                <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="flex col afc jfc full">
                <?php if(!empty($eyebrow_heading)): ?>
                    <div class="hero__eyebrow-heading">
                        <?php echo $eyebrow_heading; ?>
                    </div>
                <?php endif; ?>
                <?php if(!empty($big_heading)): ?>
                    <h1 class="hero__heading">
                        <?php echo $big_heading; ?>
                    </h1>
                <?php endif; ?>
                <?php if(!empty($hero_content)): ?>
                    <div class="hero__content-text">
                        <?php echo apply_filters( 'the_content', $hero_content ); ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="case-studies-archive__filter-bar">
    <div class="flex row container container-wide">
    <span>FILTER BY:</span>
    <?php
    $terms = get_terms(array(
        'taxonomy' => 'industry',
    ));
    if(!empty($terms)): ?>
        <div class="tabs flex row afc jfs">
            <div class="case-studies-archive__filter-bar--dropdown tab">
                <input type="checkbox" id="chck1">
                <label class="tab-label" for="chck1">Industries</label>
                <div class="tab-content">
                    <ul>
                    <?php
                    foreach($terms as $term):
                        echo '<li>';
                        echo '<a href="' . get_term_link($term) . '">' . $term->name . '</a>';
                        echo '</li>';
                    endforeach;
                    ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif; ?>
    </div>
</section>
<section class="case-studies-archive">
    <div class="container container-wide flex row afs jfs">
    <?php 
    while ( have_posts() ) : the_post(); 
    $industries = get_the_terms(get_the_ID(), 'industry');
    $short_description = get_post_meta(get_the_ID(), 'short_description', true);
    ?>
    <article class="case-study item_1_3_gut flex col afs jfs">
        <div class="case-study__image">
            <a href="<?php echo get_the_permalink(get_the_ID()); ?>"><?php echo get_the_post_thumbnail(get_the_ID(), 'large'); ?></a>
        </div>
        <?php if(!empty($industries)) {
            echo '<div class="case-study__industries">';
            foreach($industries as $industry) {
                echo '<span class="case-study__industry"><a href="'. get_term_link($industry->term_id) .'">' . $industry->name . '</a></span>';
            }
            echo '</div>';
        } ?>
        <?php if(!empty($short_description)): ?>
            <h4 class="case-study__short-description"><?php echo $short_description; ?></h4>
        <?php endif; ?>
        <div class="case-study__client"><?php the_title(); ?></div>
    </article>
    <?php endwhile; ?>
    </div>
</section>
<section>
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
</section>
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