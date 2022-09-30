<div class="<?php echo $image_col_class; ?> testimonial__long-side"
    <?php if(!empty($background_image)): ?>
        style="background-image: url('<?php echo $background_image; ?>'); background-repeat:no-repeat; background-position: left center; display: flex; align-items: center ;background-size: 100% 100%;" 755 547
    <?php endif; ?>
>
    <?php if(!empty($image)): ?>
        <img src="<?php echo $image; ?>" />
    <?php endif; ?>
</div>