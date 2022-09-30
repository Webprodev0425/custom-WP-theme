<?php
global $flex_content;
$heading = $flex_content['heading'];
$case_studies = $flex_content['case_studies'];
$cta = $flex_content['cta'];
?>
<section class="case-studies">
    <div class="container container-lg flex col afc jfc">
        <?php if(!empty($heading)): ?>
            <h2 class="case-studies__heading"><?php echo $heading; ?></h2>
        <?php endif; ?>
        <?php 
        if(!empty($case_studies)): 
            $cols = count($case_studies);
        ?>
            <div class="case-studies__items flex row afc jfsb">
                <?php 
                foreach($case_studies as $case_study): 
                    setup_postdata($case_study);
                    $cs_title = get_the_title($case_study);
                    $cs_featured_image = get_the_post_thumbnail_url($case_study, 'caseStudy');
                    $cs_featured_image_id = get_post_thumbnail_id($case_study);
                    $image_alt = get_post_meta($cs_featured_image_id, '_wp_attachment_image_alt', true);
                    $cs_date = get_post_meta($case_study, 'study_year', true);
                ?>
                    <div class="case-studies__item item_1_<?php echo $cols; ?>_gut">
                        <picture>
                            <img src="<?php echo $cs_featured_image; ?>" alt="<?php echo $image_alt; ?>">
                        </picture>
                        <div class="case-studies__item__text flex col afc jfc">
                            <header class="flex row afs jfsb">
                                <h3><?php echo $cs_title; ?></h3>
                                <?php if(!empty($cs_date)): ?>
                                    <span class="case-studies__item__date"><?php echo $cs_date; ?></span>
                                <?php endif; ?>
                            </header>
                            <aside class="case-studies__item__stats flex col afc jfc">
                                <?php 
                                $cs_stats = get_post_meta($case_study, 'statistics', true);
                                for ($i = 0; $i < $cs_stats; $i++) {
                                    $featured = get_post_meta($case_study, 'statistics_' . $i . '_featured', true);
                                    $leading_character = get_post_meta($case_study, 'statistics_' . $i . '_leading_character', true);
                                    $figure = get_post_meta($case_study, 'statistics_' . $i . '_figure', true);
                                    $symbol = get_post_meta($case_study, 'statistics_' . $i . '_symbol', true);
                                    $figure_title = get_post_meta($case_study, 'statistics_' . $i . '_figure_title', true);
                                    if((int)$featured):
                                        ?>
                                        <div class="stat flex col afc jfc">
                                            <figure class="stat__figure flex row">
                                                <?php if(!empty($leading_character)): ?>
                                                    <span class="figure__leading_character"><?php echo $leading_character; ?></span>
                                                <?php endif; ?>
                                                <?php if(!empty($figure)): ?>
                                                    <span data-target="<?php echo $figure; ?>" class="figure__figure">0</span>
                                                <?php endif; ?>
                                                <?php if(!empty($symbol)): ?>
                                                    <span class="figure__symbol"><?php echo $symbol; ?></span>
                                                <?php endif; ?>
                                            </figure>
                                            <?php if(!empty($figure_title)): ?>
                                                <span class="stat__title"><?php echo $figure_title; ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <?php
                                    endif;
                                }
                                ?>
                            </aside>
                        </div>
                    </div>
                <?php 
                wp_reset_postdata();
                endforeach; ?>
            </div>
        <?php endif; ?>
        <a href="#" class="case-studies__button">Check out more results <span>&rarr;</span></a>
    </div>
</section>