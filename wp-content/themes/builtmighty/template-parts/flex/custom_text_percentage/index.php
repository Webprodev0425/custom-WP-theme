<?php
global $flex_content;
$subtitle = $flex_content['subtitle'];
$title = $flex_content['title'];
$content = $flex_content['content'];
$percentages = $flex_content['percentages'];
?>

<section class="content_percentage">
    <?php if(!empty($subtitle)): ?>
        <div class="container container-lg content_percentage_subtitle"><?php echo $subtitle; ?></div>
    <?php endif; ?>
    <div class="container container-lg">
        <div class="content_percentage_text">
            <?php if(!empty($title)): ?>
                <h2 class="content_percentage_title"><?php echo $title; ?></h2>
            <?php endif; ?>
        </div>
        <div class="content_percentage_percentages">
            <div class="content_percentage_percentages_items">
                <?php foreach ($percentages as $percentage) : ?>
                    <?php
                    $percent_value = $percentage['percent_value'];
                    $percent_title = $percentage['percent_title'];
                    ?>
                    <div class="content_percentage_percentages_item">
                        <?php if(!empty($percent_value)): ?>
                            <div class="content_percentage_percentages_item_value"><?php echo $percent_value; ?></div>
                        <?php endif; ?>
                        <?php if(!empty($percent_title)): ?>
                            <div class="content_percentage_percentages_item_title"><?php echo $percent_title; ?></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if(!empty($content)): ?>
                <div class="content_percentage_content"><?php echo $content; ?></div>
            <?php endif; ?>
        </div>
    </div>
</section>