<?php
global $flex_content;
$number_of_columns = $flex_content['number_of_columns'];
$background_color = $flex_content['background_color'];
$column_heading = $flex_content['column_heading'];
$subheading = $flex_content['subheading'];
$checkbox_list = $flex_content['checkbox_list'];
$image = $flex_content['image'];
$eyebrow_heading = $flex_content['eyebrow_heading'];
$big_heading = $flex_content['big_heading'];
$content = $flex_content['content'];
$clipping_svg = $flex_content['clipping_svg'];
$image_background_svg = $flex_content['image_background_svg'];
$desktop_background_image = $flex_content['desktop_background_image'];
$get_started = $flex_content['get_started'];
?>

<section class="hero hero__<?php echo $number_of_columns; ?>-column <?php echo $background_color; ?>" style="background-image: url('<?php echo $desktop_background_image['url']; ?>');">
    <div class="container container-lg flex row afc">
        <?php if ($number_of_columns > 1) : ?>
            <div class="item_1_2">
                <?php if (!empty($column_heading)) : ?><h1 class="hero__heading"><?php echo $column_heading; ?></h1> <?php endif; ?>
                <?php if (!empty($subheading)) : ?><h2 class="hero__subheading"><?php echo $subheading; ?></h2> <?php endif; ?>
                <?php if (!empty($checkbox_list)) : ?>
                    <ul class="hero__checkbox-list">
                        <?php foreach ($checkbox_list as $checkbox) : 
                            $icon_type = $checkbox['icon_type'] ?? 'check';
                            // $icon_type = $checkbox['icon_type'] !== 'x' ? 'check' : 'x';
                            ?>
                            <li class="hero__checkbox-list-item flex row afs nowrap">
                                <?php
                                render_svg(sprintf('circle-%s.svg'
                                , $icon_type), 'circle-check');
                                echo '<span>' . $checkbox['checkbox_content'] . '</span>';;
                                ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <?php if(!empty($get_started)) : ?>
                    <div class = "hero-btn">
                        <a class = "hero__get_started_btn" href = "<?php echo $get_started['url'];?>"><?php echo $get_started['title']; ?><span>&rarr;</span></a>
                    </div>
                <?php endif; ?>
            </div>
            <div class="item_1_2 flex row jfe">
                <?php if (!empty($image)) : ?>
                    <?php echo $clipping_svg; ?>
                    <!-- <img class="plus-bg" src="<?php echo get_stylesheet_directory_uri() . '/assets/img/plus-grid-bg.png'; ?>" /> -->
                    <?php if(!empty($image_background_svg)) : ?>
                        <?php echo $image_background_svg; ?>
                    <?php endif; ?>
                    <picture class="hero_image">
                        <source srcset="<?php echo wp_get_attachment_image_src($image['id'], 'large')[0]; ?>" media="(min-width: 991px)">
                        <source srcset="<?php echo wp_get_attachment_image_src($image['id'], 'medium')[0]; ?>" media="(min-width: 768px)">
                        <?php echo wp_get_attachment_image($image['id'], 'full'); ?>
                    </picture>
                    <div style="clear:both;"></div>                    
                <?php endif; ?>
            </div>  
    </div>
<?php else : ?>
    <div class="flex col afc jfc full">
        <?php if (!empty($eyebrow_heading)) : ?>
            <div class="hero__eyebrow-heading">
                <?php echo $eyebrow_heading; ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($big_heading)) : ?>
            <h1 class="hero__heading">
                <?php echo $big_heading; ?>
            </h1>
        <?php endif; ?>
        <?php if (!empty($content)) : ?>
            <div class="hero__content-text">
                <?php echo apply_filters('the_content', $content); ?>
            </div>
            <?php if(!empty($get_started)) : ?>                
                <a class = "hero__get_started_1_col_btn" href = "<?php echo $get_started['url'];?>"><?php echo $get_started['title']; ?><span>&rarr;</span></a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
<?php endif; ?>
</div>
</section>