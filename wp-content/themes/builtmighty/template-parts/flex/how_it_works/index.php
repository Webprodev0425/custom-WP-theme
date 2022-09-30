<?php
global $flex_content;
$heading = $flex_content['heading'];
$content = $flex_content['content'];
$subheading = $flex_content['subheading'];
$points = $flex_content['points'];
$image = $flex_content['image'];
?>
<section class="how-it-works">
    <div class="container container-lg flex col afc jfc">
        <div class = "how-it-works_content">
            <div class = "how-it-works_content_text">
                <?php if (!empty($heading)) : ?>
                    <h2 class="how-it-works__heading"><?php echo $heading; ?></h2>
                <?php endif; ?>
                <?php if (!empty($content)) : ?>
                    <h3 class="how-it-works__content"><?php echo $content; ?></h3>
                <?php endif; ?>
            </div>
            <div class = "how-it-works_content_image">
                <?php if (!empty($image)) : echo wp_get_attachment_image($image['id'], 'full'); endif; ?>
            </div>
        </div>
        <div class = "how-it-works_blocks">
            <?php if (!empty($subheading)) : ?>
                <h3 class="how-it-works__subheading"><?php echo $subheading; ?></h3>
            <?php endif; ?>
            <?php if (!empty($points)) :?>
                <div class="how-it-works_blocks_items">
                    <?php foreach ($points as $point) :
                        $point_icon = $point['point']['icon'];
                        $point_heading = $point['point']['point_heading'];
                        $point_content = $point['point']['point_content'];
                    ?>
                        <div class="how-it-works_blocks_item">
                            <div class="how-it-works_blocks_item_img">
                                <?php if(!empty($point_icon)): ?>
                                    <?php render_svg($point_icon . '.svg', 'how-it-works_blocks_item__icon'); ?>
                                <?php endif; ?>
                            </div>
                            <div class="how-it-works_blocks_item_txt">
                                <?php if (!empty($point_heading)) : ?>
                                    <h4 class="how-it-works_blocks_item_txt__heading"><?php echo $point_heading; ?></h4>
                                <?php endif; ?>
                                <?php if (!empty($point_content)) : ?>
                                    <div class="how-it-works_blocks_item_txt__content">
                                        <?php echo $point_content; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>