<?php
global $flex_content;
$heading = $flex_content['heading'];
$subheading = $flex_content['subheading'];
$two_column_cards_background_image = $flex_content['two_column_cards_background_image'];
$two_column_cards_background_overlay_start = $flex_content['two_column_cards_background_overlay_start'];
$two_column_cards_background_overlay_end = $flex_content['two_column_cards_background_overlay_end'];
$gradient_deg = $flex_content['gradient_deg'];
$card_sides = array(
            'left' => $flex_content['left_cards'], 
            'right' => $flex_content['right_cards'],
        );
?>
<section class="two-column-expandable-cards" style='background-image: url("<?php echo $two_column_cards_background_image['url']; ?>"); background:<?php echo "linear-gradient(" .$gradient_deg ."deg, " .$two_column_cards_background_overlay_start ." 0%, " .$two_column_cards_background_overlay_end ." 100%)";?>;'>
    <div class="container container-main flex col">
        <h2 class="two-column-expandable-cards__heading"><?php echo $heading; ?></h2>
        <?php if(!empty($subheading)): ?>
            <div class="two-column-expandable-cards__subheading"><?php echo $subheading; ?></div>
        <?php endif; ?>
        <div class="flex row jfsb">
        <?php 
        foreach ($card_sides as $side => $cards): 
            if(!empty($cards)): ?>
            <div class="two-column-expandable-cards__column-<?php echo $side; ?> item_1_2_gut flex col">
                <?php 
                foreach ($cards as $card): 
                    $icon = $card[$side .'_card_icon'];
                    $heading = $card[$side .'_card_heading'];
                    $content = $card[$side .'_card_content'];
                    $expandable_heading = $card[$side .'_card_expandable_heading'];
                    $expandable_points = $card[$side .'_card_expandable_points'];
                ?>
                    <div class="two-column-expandable-cards__card flex col">
                        <div class="two-column-expandable-cards__card__content">
                            <?php if(!empty($icon)):
                                echo wp_get_attachment_image($icon['id'], 'full');
                            endif; ?>
                            <?php if(!empty($heading)): ?>
                                <h3 class="two-column-expandable-cards__card__heading"><?php echo $heading; ?></h3>
                            <?php endif; ?>
                            <?php if(!empty($content)): ?>
                                <div class="two-column-expandable-cards__card__content__text">
                                    <?php echo $content; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <details class="two-column-expandable-cards__card__points">
                            <?php if (!empty($expandable_heading)): ?>
                                <summary clas="flex row jfsb"><span><?php echo $expandable_heading; ?></span><span class="transforming-x"></span></summary>
                            <?php endif; ?>
                            <?php 
                            if(!empty($expandable_points)): 
                                echo '<div class="points flex row afs jfc">';
                                foreach ($expandable_points as $point): 
                            ?>
                                <span class="point item_1_2 flex row afc">
                                    <?php 
                                    render_svg('circle-check-line.svg', 'circle-check-line'); 
                                    echo $point[$side .'_card_point'];
                                    ?>
                                </span>
                            <?php
                                endforeach;
                                echo '</div>';
                            endif;
                            ?>

                        </details>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php 
            endif;
        endforeach; 
        ?>
        </div>
    </div>
</section>