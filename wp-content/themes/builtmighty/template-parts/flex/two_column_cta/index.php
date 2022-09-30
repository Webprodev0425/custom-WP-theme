<?php
global $flex_content;
$eyebrow_heading = $flex_content['eyebrow_heading'];
$heading = $flex_content['heading'];
$subheading = $flex_content['subheading'];
$cta_columns = $flex_content['cta_columns'];
$central_cta = $flex_content['central_cta'];
$cta_accent_image_posiiton = $flex_content['cta_accent_image_position'];
$two_column_cta_background_image = $flex_content['two_column_cta_background_image'];
$two_column_cta_background_overlay_start = $flex_content['two_column_cta_background_overlay_start'];
$two_column_cta_background_overlay_end = $flex_content['two_column_cta_background_overlay_end'];
$gradient_deg = $flex_content['gradient_deg'];
$style = $flex_content['style'];
?>

<section class="two-column-cta" style="<?php if(!empty($two_column_cta_background_image['url'])) { echo 'background-image: url(' .$two_column_cta_background_image['url'] .');';}?>">
    <div class="container container-full flex col afs jfc" style = 'background:<?php echo "linear-gradient(" .$gradient_deg ."deg, " .$two_column_cta_background_overlay_start ." 0%, " .$two_column_cta_background_overlay_end ." 100%)";?>; <?php  echo $style == 'Light' ? 'color:#fff' : 'color:initial'; ?>'>
        <div class="two-column-cta__headings flex col afc jfc item_full">            
            <?php if(!empty($eyebrow_heading)): ?>
                <h3 class="two-column-cta__headings__eyebrow-heading"><?php echo $eyebrow_heading; ?></h3>
            <?php endif; ?>
            <?php if(!empty($heading)): ?>
                <h2 class="two-column-cta__headings__heading"><?php echo $heading; ?></h2>
            <?php endif; ?>
            <?php if(!empty($subheading)): ?>
                <p class="two-column-cta__headings__subheading"><?php echo $subheading; ?></p>
            <?php endif; ?>
        </div>
        <div class="two-column-cta__ctas flex row afs jfc item_full">
            <?php if(!empty($cta_columns)): ?>
                <?php foreach($cta_columns as $cta_column): ?>
                    <?php
                        $cta_icon = $cta_column['cta_icon'];
                        $cta_heading = $cta_column['cta_heading'];
                        $cta_content = $cta_column['cta_content'];
                        $list_points = $cta_column['list_points'];
                        $button_label = $cta_column['button_label'];
                        $button_url = $cta_column['button_url'];
                    ?>
                    <div class="two-column-cta__ctas__column item_1_2 flex col afc jfs">
                        <div class="flex col afs two-column-cta__ctas__column--wrapper">
                            <?php
                                if(!empty($cta_icon)) {
                                    echo wp_get_attachment_image($cta_icon['id'], 'full');
                                }
                            ?>
                            <?php if(!empty($cta_heading)): ?>
                                <h3 class="two-column-cta__ctas__column__heading"><?php echo $cta_heading; ?></h3>
                            <?php endif; ?>
                            <?php if(!empty($cta_content)): ?>
                                <p class="two-column-cta__ctas__column__content"><?php echo $cta_content; ?></p>
                            <?php endif; ?>
                            <?php if(!empty($list_points)): ?>
                                <ul class="two-column-cta__ctas__column__list">
                                    <?php
                                    foreach($list_points as $list_point): 
                                        $point = $list_point['point'];
                                        $icon = $list_point['icon'];
                                    ?>
                                        <li class="two-column-cta__ctas__column__list__item"><?php render_svg($icon . '.svg', 'icon '. $icon); ?><?php echo $list_point['point']; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                            <?php if(!empty($button_url)): ?>
                                <a class="two-column-cta__ctas__column__button" style="<?php  echo $style == 'Light' ? 'color:#fff' : 'color:d4121f'; ?>" href="<?php echo $button_url; ?>"><?php echo $button_label; ?> 	<span>&rarr;</span></a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if (!empty($central_cta['central_cta_label'])): ?>
                <a class="two-column-cta__ctas__center__button" style="<?php  echo $style == 'Light' ? 'color:#fff' : 'color:#d4121f'; ?>" href="<?php echo $central_cta['central_cta_link']; ?>"><?php echo $central_cta['central_cta_label']; ?> 	<span>&rarr;</span></a>
            <?php endif; ?>
        </div>
    </div>
</section>