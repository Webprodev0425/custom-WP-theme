<?php
global $flex_content;
$heading = $flex_content['heading'];
$content = $flex_content['content'];
?>

<section class="form-block" id="form-block">
    <div class="container container-main flex row afs">
        <div class="form-block__content item_1_2">
            <?php if(!empty($heading)): ?>
                <h2 class="form-block__heading"><?php echo $heading; ?></h2>
            <?php endif; ?>
            <?php if(!empty($content)): ?>
                <div class="form-block__content__content">
                    <?php echo $content; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="form-block__form item_1_2">
            <?php echo do_shortcode('[gravityform id="1" title="false"]'); ?>
        </div>
    </div>
    <img class = "dots-left__bottom" src = "<?php echo get_stylesheet_directory_uri() . '/assets/img/accent_left.png'; ?>">
    <img class = "dots-right__bottom" src = "<?php echo get_stylesheet_directory_uri() . '/assets/img/accent_right.png'; ?>">
</section>