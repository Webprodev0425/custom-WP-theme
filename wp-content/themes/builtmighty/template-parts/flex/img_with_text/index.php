<?php
global $flex_content;
$subtitle = $flex_content['subtitle'];
$title = $flex_content['title'];
$content = $flex_content['content'];
$image = $flex_content['column_image'];
$text_column_position = $flex_content['text_column_position'];
?>

<section class="image_with_text">
    <div class="container container-lg grid">    
        <?php
            switch($text_column_position) {
                case 'Right':?>
                    <div class="first_half image">
                        <?php if(!empty($image)): ?>
                            <img src="<?php echo $image; ?>" />
                        <?php endif; ?>
                    </div>
                    <div class="second_half content">
                        <?php if(!empty($subtitle)): ?>
                            <p class="image_with_text_subtitle"><?php echo $subtitle; ?></p>
                        <?php endif; ?>
                        <?php if(!empty($title)): ?>
                            <h2 class="image_with_text_title"><?php echo $title; ?></h2>
                        <?php endif; ?>
                        <?php if(!empty($content)): ?>
                            <div class="image_with_text_content"><?php echo $content; ?></div>
                        <?php endif; ?>
                    </div>
                    <?php
                    break;
                default:?>
                <div class="first_half content">                    
                    <?php if(!empty($subtitle)): ?>
                        <p class="image_with_text_subtitle"><?php echo $subtitle; ?></p>
                    <?php endif; ?>
                    <?php if(!empty($title)): ?>
                        <h2 class="image_with_text_title"><?php echo $title; ?></h2>
                    <?php endif; ?>
                    <?php if(!empty($content)): ?>
                        <div class="image_with_text_content"><?php echo $content; ?></div>
                    <?php endif; ?>                    
                </div>
                <div class="second_half image">
                    <?php if(!empty($image)): ?>
                        <img src="<?php echo $image; ?>" />
                    <?php endif; ?>
                </div>
                <?php
            }
        ?>        
    </div>
</section>