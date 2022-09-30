<?php
global $flex_content;
$author = $flex_content['author'];
$background_color = $flex_content['background_color'];
$company = $flex_content['company'];
$long_quote = $flex_content['long_quote'];
$quote_highlight = $flex_content['quote_highlight'];
$image = $flex_content['column_image'];
$background_image = $flex_content['column_background_image'];
$accent_image_positions = $flex_content['accent_image_position'];
$text_column_position = $flex_content['text_column_position'];
?>

<section class="testimonial <?php echo $background_color; ?>">
    <div class="container container-lg grid">          
        <?php foreach($accent_image_positions as $accent_image_position): ?>
            <?php if($accent_image_position != 'None'): ?>
            <img class="<?php if($accent_image_position == 'Right') echo 'dots-right'; else if($accent_image_position == 'Top Right') echo 'dots-right__top'; else if($accent_image_position == 'Left') echo 'dots-left'; else if($accent_image_position == 'Top Left') echo 'dots-left__top'; else if($accent_image_position == 'Bottom Left') echo 'dots-left__bottom'; ?>" src="<?php if($accent_image_position == 'Bottom Left' || $accent_image_position == 'Top Left') echo get_stylesheet_directory_uri() . '/assets/img/accent_left.png'; else if($accent_image_position == 'Top Right') echo get_stylesheet_directory_uri() . '/assets/img/accent_right.png'; else echo get_stylesheet_directory_uri() . '/assets/img/plus-grid-bg.png'; ?>">
            <?php endif; ?>
        <?php endforeach; ?>
        
        <?php
            switch($text_column_position) {
                case 'Right':
                    $image_col_class = 'gr_3_5-1';
                    $text_col_class = 'gr_2_5-2';
                    include('image_column.php');
                    include('text_column.php');
                    break;
                case 'Bottom':
                    $image_col_class = 'flex row jfc top_img';
                    include('image_column.php');
                    include('text_column.php');
                    break;
                default:
                    $image_col_class = 'gr_3_5-2';
                    $text_col_class = 'gr_2_5-1';
                    include('text_column.php');
                    include('image_column.php');
            }
        ?>
        
    </div>
</section>