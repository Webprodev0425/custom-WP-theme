<?php
/**
* Template Name: All Blogs
*
* @package WordPress
*/
get_header();
global $wp_query;
?>
<header class="container container-main flex col afc jfc">
    <p>LATEST NEWS AND UPDATES</p>
    <h1>Lorem ipsum dolor sit amet consectetur</h1>
    <h4>
    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo.
    </h4>
</header>

<div class="tabs flex row afc jfs">
    <div class="case-studies-archive__filter-bar--dropdown tab">
        <input type="checkbox" id="chck1">
        <label class="tab-label" for="chck1">Filter by Author</label>
        <div class="tab-content">
            <ul>
            <?php
            $users = get_users( array( 'role__in' => array( 'author', 'administrator' ) ) );
            foreach($users as $user):
                echo '<li>';
                echo '<a href="' . '?author='. $user->ID. '">' . get_user_by('id', $user->ID)->user_login . '</a>';
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
            <div class="case-studies-archive__filter-bar--dropdown tab">
                <input type="checkbox" id="chck2">
                <label class="tab-label" for="chck2">Filter by Category</label>
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
    <?php endif; 
?>
<?php
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
    $author = ( isset( $_GET['author'] )) ? $_GET['author'] : '';
    // $args = 'author_name='. $author;
    $args = array(
        'posts_per_page' => 9,
        'paged'=>$paged,
        'author' => $author,
        'year' => 2018
    );
    $author_query = new WP_Query($args);
    $total = $author_query->max_num_pages;

    if ( $author_query->have_posts() ) {
        echo '<ul>';
        while ( $author_query->have_posts() ) {
            $author_query->the_post();
            echo '<li>' . get_the_title() . '</li>';
        }
        echo '</ul>';
    } else {
        echo "No founds";
    }
    ?>

    <section>
        <?php
        echo '<div class="blog__pagination flex">';
        echo paginate_links( array(
            'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
            'current'      => max( 1, get_query_var( 'paged' ) ),
            'format'       => '?paged=%#%',
            'end_size'     => 2,
            'mid_size'     => 1,
            'total'         => $total,
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