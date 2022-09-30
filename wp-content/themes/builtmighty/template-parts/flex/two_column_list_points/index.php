<?php
global $flex_content;

$style = $flex_content['style'];
$heading = $flex_content['heading'];
$content = $flex_content['content'];
$rc_heading = $flex_content['rc_heading'];
$list_points = $flex_content['list_points'];
$accent_image_position = $flex_content['accent_image_position'];
?>

<section class="two-column-list-points two-column-list-points__<?php echo $style; ?>">
    <div class="container container-lg flex row afs jfs">
    <?php if($accent_image_position != 'None'): ?>
                <img class="<?php if($accent_image_position == 'Right') echo 'dots-right'; else if($accent_image_position == 'Top Right') echo 'dots-right__top'; else if($accent_image_position == 'Left') echo 'dots-left'; else if($accent_image_position == 'Bottom Left') echo 'dots-left__bottom'; ?>" src="<?php if($accent_image_position == 'Bottom Left') echo get_stylesheet_directory_uri() . '/assets/img/accent_left.png'; else if($accent_image_position == 'Top Right') echo get_stylesheet_directory_uri() . '/assets/img/accent_right.png'; else echo get_stylesheet_directory_uri() . '/assets/img/plus-grid-bg.png'; ?>">
            <?php endif; ?>
        <div class="two-column-list-points__content item_1_2 flex col">
            <?php if(!empty($heading)): ?>
                <h2 class="two-column-list-points__heading"><?php echo $heading; ?></h2>
            <?php endif; ?>
            <?php if(!empty($content)): ?>
                <div class="two-column-list-points__content__text">
                    <?php echo $content; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="two-column-list-points__list item_1_2 flex col">
            <?php if(!empty($rc_heading)): ?>
                <h3 class="two-column-list-points__list__heading"><?php echo $rc_heading; ?></h3>
            <?php endif; ?>
            <?php if(!empty($list_points)): ?>
                <?php foreach($list_points as $list_point): ?>
                    <div class="two-column-list-points__list__item flex row afs jfs nowrap">
                        <div>
                            <?php if(!empty($list_point['icon'])): ?>
                                <?php render_svg($list_point['icon'] . '.svg', 'two-column-list-points__list__item__icon'); ?>
                            <?php endif; ?>
                        </div>
                        <div>
                            <?php if(!empty($list_point['point_heading'])): ?>
                                <h3 class="two-column-list-points__list__item__heading"><?php echo $list_point['point_heading']; ?></h3>
                            <?php endif; ?>
                            <?php if(!empty($list_point['point_content'])): ?>
                                <div class="two-column-list-points__list__item__content">
                                    <?php echo $list_point['point_content']; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>