<?php
global $flex_content;
$eyebrow_heading = $flex_content['eyebrow_heading'];
$heading = $flex_content['heading'];
$subheading = $flex_content['subheading'];
$central_cta = $flex_content['central_cta'];
$cta_accent_image_posiiton = $flex_content['cta_accent_image_position'];
$img_with_txt_column_cta_background_image = $flex_content['img_with_txt_column_cta_background_image'];
$img_with_txt_column_cta_background_overlay_start = $flex_content['img_with_txt_column_cta_background_overlay_start'];
$img_with_txt_column_cta_background_overlay_end = $flex_content['img_with_txt_column_cta_background_overlay_end'];
$gradient_deg = $flex_content['gradient_deg'];
$style = $flex_content['style'];
?>

<section class="image-text-column-cta" style="<?php if(!empty($img_with_txt_column_cta_background_image['url'])) { echo 'background-image: url(' .$img_with_txt_column_cta_background_image['url'] .');';}?>">
    <div class="container container-full flex col afs jfc" style = 'background:<?php echo "linear-gradient(" .$gradient_deg ."deg, " .$img_with_txt_column_cta_background_overlay_start ." 0%, " .$img_with_txt_column_cta_background_overlay_end ." 100%)";?>; <?php  echo $style == 'Light' ? 'color:#fff' : 'color:initial'; ?>'>
        <?php if($cta_accent_image_posiiton != 'None'): ?>
            <img class="<?php if($cta_accent_image_posiiton == 'Right') echo 'dots-right'; else if($cta_accent_image_posiiton == 'Left') echo 'dots-left'; else if($cta_accent_image_posiiton == 'Top Left') echo 'dots-left__top'; ?>" src="<?php if($cta_accent_image_posiiton == 'Top Left') echo get_stylesheet_directory_uri() . '/assets/img/accent_left.png'; else echo get_stylesheet_directory_uri() . '/assets/img/plus-grid-bg.png'; ?>">
        <?php endif; ?>    
        <div class="image-text-column-cta__headings item_full">            
            <?php if(!empty($heading)): ?>
                <h2 class="image-text-column-cta__headings__heading">
                    <img class = "dots-left__bottom" src = "<?php echo get_stylesheet_directory_uri() . '/assets/img/white_quote.png'; ?>">
                    <?php echo $heading; ?>
                </h2>
            <?php endif; ?>    
            <?php if(!empty($eyebrow_heading)): ?>
                <h3 class="image-text-column-cta__headings__eyebrow-heading"><?php echo $eyebrow_heading; ?></h3>
            <?php endif; ?>
            <?php if(!empty($subheading)): ?>
                <p class="image-text-column-cta__headings__subheading"><?php echo $subheading; ?></p>
            <?php endif; ?>
        </div>
        <div class="two-column-cta__ctas flex row afs jfc item_full">
            <?php if (!empty($central_cta['central_cta_label'])): ?>
                <a class="two-column-cta__ctas__center__button" style="<?php  echo $style == 'Light' ? 'color:#fff' : 'color:#d4121f'; ?>" href="<?php echo $central_cta['central_cta_link']; ?>"><?php echo $central_cta['central_cta_label']; ?> 	<span>&rarr;</span></a>
            <?php endif; ?>
        </div>
    </div>
</section>