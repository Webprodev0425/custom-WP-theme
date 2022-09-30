<?php
global $flex_content;
$heading = $flex_content['heading'];
$subheading = $flex_content['subheading'];
$points = $flex_content['points'];
$image = $flex_content['image'];
?>
<section class="grid-points">
    <div class="container container-main flex col afc jfc">
        <?php if (!empty($heading)) : ?>
            <h2 class="grid-points__heading"><?php echo $heading; ?></h2>
        <?php endif; ?>
        <?php if (!empty($subheading)) : ?>
            <h3 class="grid-points__subheading"><?php echo $subheading; ?></h3>
        <?php endif; ?>
        <div class="flex row afs">
            <div class="grid-points__column-left item_4_7">
                <?php if (!empty($points)) :
                    $point_count = 1;
                ?>
                    <div class="grid-points__points flex row jfsb">
                        <?php foreach ($points as $point) :
                            $point_heading = $point['point']['point_heading'];
                            $point_content = $point['point']['point_content'];
                            $count_num = str_pad($point_count, 2, '0', STR_PAD_LEFT);
                        ?>
                            <div class="grid-points__point item_1_2">
                                <?php echo '<span class="count">' . $count_num . '.</span>'; ?>
                                <?php if (!empty($point_heading)) : ?>
                                    <h4 class="grid-points__point__heading"><?php echo $point_heading; ?></h4>
                                <?php endif; ?>
                                <?php if (!empty($point_content)) : ?>
                                    <div class="grid-points__point__content">
                                        <?php echo $point_content; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php
                            $point_count++;
                        endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="grid-points__column-right item_3_7">
                <?php if (!empty($image)) : echo wp_get_attachment_image($image['id'], 'full');
                endif; ?>
            </div>
        </div>
    </div>
</section>