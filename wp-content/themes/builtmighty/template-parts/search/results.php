<?php
include(trailingslashit(get_template_directory()) . 'template-parts/search/result.php');

if (!function_exists('search_results')) {
    function search_results()
    {
?>
        <section class="featured-articles flex col container container-main">
            <div id="article-container" class = "container container-main flex row afs jfs">
                <?php
                if (have_posts()) : while (have_posts()) :
                        the_post();
                        search_result();
                    endwhile;
                ?>
                    <section class="pagination flex row afc jfc">
                        <?php if (get_previous_posts_link()) {
                        ?>
                            <div class="paginate-button" id="page-prev"><i class="fas fa-arrow-left"></i> <?php previous_posts_link('PREV'); ?></div>
                        <?php
                        } ?>
                        <div class="paginate-button" id="page-next"><?php next_posts_link('NEXT'); ?> <i class="fas fa-arrow-right"></i></div>
                    </section>
                <?php
                endif;
                ?>
            </div>
        </section>
<?php
    }
}
