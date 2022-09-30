<?php
global $flex_content;
$image = $flex_content['image'];
?>

<section class="full-width-image container container-main flex row afc jfc">
    <?php if(!empty($image)): ?>
        <picture>
            <source srcset="<?php echo wp_get_attachment_image_src($image['id'], 'large')[0]; ?>" media="(min-width: 991px)">
            <source srcset="<?php echo wp_get_attachment_image_src($image['id'], 'medium')[0]; ?>" media="(min-width: 768px)">
            <?php echo wp_get_attachment_image($image['id'], 'full'); ?>
        </picture>
    <?php endif; ?>
</section>