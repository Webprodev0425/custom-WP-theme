<?php
global $flex_content;
$heading = $flex_content['heading'];
$category = $flex_content['category'];
$args = array(
    'post_type' => 'case_studies',
    'posts_per_page' => -1,
    'orderby' => 'menu_order',
    'order' => 'ASC',
);
$case_studies = new WP_Query($args);
?>
<section class="case_studies_with_category">
    <div class="container container-lg flex col afc jfc">
        <?php
        if (!empty($heading)) {
            echo '<h2 class="case_studies_with_category__heading">' . $heading . '</h2>';
        }

        if ($case_studies->found_posts) : ?>
            <div class="case_studies_with_category_items flex full jfsb afsb">
                <?php
                foreach ($case_studies->posts as $case_study) :
                    $case_study_name = $case_study->post_title;
                    $ID = $case_study->ID;
                    $roles = get_the_category($ID);
                    foreach($roles as $role){
                        if($role->cat_name == $category) { ?>
                            <div class='case_studies_with_category_item flex col'>
                                <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $ID ), 'single-post-thumbnail' ); ?>
                                <a href="<?php echo get_the_permalink($ID); ?>"><img src = "<?php echo $image[0]; ?>" ></a>
                                <h3 class="case_studies_with_category__name"><?php echo $case_study_name; ?></h3>
                            </div>
                        <?php
                        }
                    }
                endforeach;
                ?>
            </div>
        <?php
        endif;
        ?>
        <a class="button" href="<?php echo trailingslashit(get_site_url()) . 'case-studies'; ?>">See all case studies <span>&rarr;</span></a>
    </div>    
</section>