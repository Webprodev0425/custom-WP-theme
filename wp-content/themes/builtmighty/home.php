<?php
get_header();
$form = get_field('form_block', 'option');
$form_heading = $form['heading'];
$form_content = $form['content'];
$count = 0;
$author = ( isset( $_GET['author'] )) ? $_GET['author'] : '';
$year = ( isset( $_GET['year'] )) ? $_GET['year'] : '';
$cat = ( isset( $_GET['category_name'] )) ? $_GET['category_name'] : '';
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$count_args = array(
    'posts_per_page' => -1,
    'paged'=>$paged,
    'author' => $author,
    'year' => $year,
    'category_name' => $cat,
);
$count_query = new WP_Query($count_args);
if($count_query->have_posts()) {
    while ($count_query->have_posts()): 
    $count_query->the_post();
    $count = $count + 1;
    endwhile;
}
$args = array(
    'posts_per_page' => 12,
    'paged'=>$paged,
    'author' => $author,
    'year' => $year,
    'category_name' => $cat,
);
$author_query = new WP_Query($args);
$total = $author_query->max_num_pages;

if ($author_query->have_posts()): ?>
    <header class="container container-main flex col afc jfc blog_header">
        <p>LATEST NEWS AND UPDATES</p>
        <h1>Lorem ipsum dolor sit amet consectetur</h1>
        <h4>
        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo.
        </h4>
    </header>
    
    <div class = "blog_filter_options container container-main">
         <div class="tabs flex row afc jfs">
            <div class="all_blogs tab">
                <input type="checkbox" id="chck1">
                <label class="tab-label" for="chck1">Filter by Year</label>
                <div class="tab-content">
                    <ul>
                    <?php
                    $years = array(2012,2013,2014,2015,2016,2017,2018,2019,2020,2021,2022);
                    foreach($years as $year):
                        echo '<li>';
                        echo '<a href="' . '/blog/?year='. $year . '">' . $year . '</a>';
                        echo '</li>';
                    endforeach;
                    ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="tabs flex row afc jfs">
            <div class="all_blogs tab">
                <input type="checkbox" id="chck2">
                <label class="tab-label" for="chck2">Filter by Author</label>
                <div class="tab-content">
                    <ul>
                    <?php
                    $users = get_users( array( 'role__in' => array( 'author', 'administrator' ) ) );
                    foreach($users as $user):
                        echo '<li>';
                        echo '<a href="' . '/blog/?author='. $user->ID . '">' . get_user_by('id', $user->ID)->user_login . '</a>';
                        echo '</li>';
                    endforeach;
                    ?>
                    </ul>
                </div>
            </div>
        </div>
        <?php
        $terms = get_terms(array(
            'taxonomy' => 'category',
        ));
        if(!empty($terms)): ?>
            <div class="tabs flex row afc jfs">
                <div class="all_blogs tab">
                    <input type="checkbox" id="chck3">
                    <label class="tab-label" for="chck3">Filter by Category</label>
                    <div class="tab-content">
                        <ul>
                        <?php
                        foreach($terms as $term):
                            echo '<li>';
                            echo '<a href="/blog/?category_name=' . $term->slug .'">' . $term->name . '</a>';
                            echo '</li>';
                        endforeach;
                        ?>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div class="search_form">
            <?php echo do_shortcode('[ivory-search id="8493"]'); ?>
        </div>
        <div class="search_count">
            <?php echo "Showing " .$count." results"; ?>
        </div>
    </div>
    <section class="blog__listings container container-main flex row afs jfs">
    <?php
    while (have_posts()): the_post();
        $postID = get_the_ID();
        $post_title = get_the_title();
        $excerpt = get_excerpt($postID, '160', false);
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
                <h2 class="blog__listing__title"><?php echo $post_title; ?></h2>
                <div class="flex blog__listing__meta">
                    <div class="post_date"><img src = "<?php echo get_stylesheet_directory_uri() . '/assets/img/calendar.png'; ?>"><?php echo get_the_date(); ?></div>
                    <div class="reading_time"><img src = "<?php echo get_stylesheet_directory_uri() . '/assets/img/view.png'; ?>"><?php echo do_shortcode('[rt_reading_time]') . 'mins'; ?></div>
                    <div class="post_author"><img src = "<?php echo get_stylesheet_directory_uri() . '/assets/img/author.png'; ?>"><?php echo get_the_author_meta('user_login') ?></div>
                </div>
            </div>
        </a>
    </article>
    <?php
    endwhile; ?>
    </section>
    <section>
        <?php
        echo '<div class="blog__pagination flex">';
        echo paginate_links( array(
            'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
            'current'      => max( 1, get_query_var( 'paged' ) ),
            'format'       => '?paged=%#%',
            'end_size'     => 1,
            'mid_size'     => 4,
            'total'         => $total,
            'prev_text'    => sprintf( '	
            &#10094; %1$s', __( 'PREVIOUS', 'text-domain' ) ),
            'next_text'    => sprintf( '%1$s &#x276F;', __( 'NEXT', 'text-domain' ) ),
        ) );
        echo '</div>';
        ?>
    </section>
<?php endif; ?>
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