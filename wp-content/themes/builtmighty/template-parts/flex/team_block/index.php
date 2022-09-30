<?php
global $flex_content;
$heading = $flex_content['heading'];
$subheading = $flex_content['subheading'];
$members = $flex_content['members'];
?>
<section class="team-block">
    <div class="container container-main flex col">
        <?php
        if (!empty($heading)) {
            echo '<h2 class="team-block__heading">' . $heading . '</h2>';
        }
        if (!empty($subheading)) {
            echo '<h3 class="team-block__subheading">' . $subheading . '</h3>';
        }
        ?>
    </div>
</section>
<?php if(!empty($members)) :?>
    <section class = "team-details">
        <div class = "items">
            <?php foreach($members as $member) :
            $member_img = $member['member_image'];
            $member_txt = $member['member_quote'];
            ?>
            <?php if(!empty($member_img)) : ?>
            <div class = "item member_img">
                <img src = "<?php print_r($member_img['url']); ?>">
            </div>
            <?php endif; ?>
            <?php if(!empty($member_txt)) : ?>
            <div class = "item member_txt" style = "background-image: url('<?php echo get_stylesheet_directory_uri() . '/assets/img/member_bg.png'; ?>')">
                <h3><?php echo $member_txt; ?></h3>
            </div>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>
</section>
<?php endif; ?>