<?php
global $flex_content;
$background_color = $flex_content['background_color'];
$column_widths = explode('-', $flex_content['column_widths']);
$two_column_info_background_image = $flex_content['two_column_info_background_image'];
$two_column_info_background_overlay_start = $flex_content['two_column_info_background_overlay_start'];
$two_column_info_background_overlay_end = $flex_content['two_column_info_background_overlay_end'];
$gradient_deg = $flex_content['gradient_deg'];
$content = $flex_content['content'];
$container_width = $flex_content['container_width'];
$cta = $flex_content['cta'];
$eyebrow_heading = $flex_content['eyebrow_heading'];
$heading = $flex_content['heading'];
$image = $flex_content['image'];
$show_statistics = $flex_content['show_statistics'];
$top_heading = $flex_content['top_heading'];
?>
<section class="two-column-info <?php if(!empty($two_column_info_background_image)){echo 'white_text'; } else {echo "default_text";} ?>" style='background-image: url("<?php if(!empty($two_column_info_background_image)){ echo $two_column_info_background_image['url']; } ?>"); background:<?php echo "linear-gradient(" .$gradient_deg ."deg, " .$two_column_info_background_overlay_start ." 0%, " .$two_column_info_background_overlay_end ." 100%)";?>;'>
    <div class="container container-<?php echo $container_width; ?> grid <?php if(empty($image)) echo 'pb_enable'; ?>">
        <?php if (!empty($top_heading)) : ?>
            <h2 class="two-column-info__top-heading gr_1_2-all">
                <?php echo $top_heading; ?>
            </h2>
        <?php endif; ?>
        <?php if (!empty($eyebrow_heading)) : ?>
            <div class="two-column-info__eyebrow-heading gr_<?php echo $column_widths[0]; ?>">
                <?php echo $eyebrow_heading; ?>
            </div>
        <?php endif; ?>
        <div class="two-column-info__left-column gr_<?php echo $column_widths[0]; ?>-1">
            <?php if (!empty($heading)) : ?>
                <h3 class="two-column-info__heading">
                    <?php echo $heading; ?>
                </h3>
            <?php endif; ?>
            <?php if (!empty($image)) : ?>
                <h3 class="two-column-info__heading">
                    <?php echo wp_get_attachment_image($image['id'], 'full'); ?>
                </h3>
            <?php endif; ?>
        </div>
        <div class="two-column-info__right-column gr_<?php echo $column_widths[1]; ?>-2">
            <?php if ($show_statistics) : ?>
                <div class="two-column-info__statistics flex row afs jfsb">
                    <?php
                    $case_study = get_the_ID();
                    $cs_stats = get_post_meta($case_study, 'statistics', true);
                    for ($i = 0; $i < $cs_stats; $i++) {
                        $featured = get_post_meta($case_study, 'statistics_' . $i . '_featured', true);
                        $leading_character = get_post_meta($case_study, 'statistics_' . $i . '_leading_character', true);
                        $figure = get_post_meta($case_study, 'statistics_' . $i . '_figure', true);
                        $symbol = get_post_meta($case_study, 'statistics_' . $i . '_symbol', true);
                        $figure_title = get_post_meta($case_study, 'statistics_' . $i . '_figure_title', true);
                    ?>
                        <div class="stat flex col afc jfc">
                            <figure class="stat__figure flex row">
                                <?php if (!empty($leading_character)) : ?>
                                    <span class="figure__leading_character"><?php echo $leading_character; ?></span>
                                <?php endif; ?>
                                <?php if (!empty($figure)) : ?>
                                    <span data-target="<?php echo $figure; ?>" class="figure__figure">0</span>
                                <?php endif; ?>
                                <?php if (!empty($symbol)) : ?>
                                    <span class="figure__symbol"><?php echo $symbol; ?></span>
                                <?php endif; ?>
                            </figure>
                            <?php if (!empty($figure_title)) : ?>
                                <span class="stat__title"><?php echo $figure_title; ?></span>
                            <?php endif; ?>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($content)) : ?>
                <div class="two-column-info__content-text">
                    <?php echo apply_filters('the_content', $content); ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($cta['label'])) : ?>
                <div class="two-column-info__cta">
                    <a class="button" href="<?php echo $cta['link']; ?>"><?php echo $cta['label']; ?> <span>&rarr;</span></a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>