<?php
get_header();
$short_description = get_post_meta(get_the_ID(), 'short_description', true);
$industries = get_the_terms(get_the_ID(), 'industry');
$role = get_post_meta(get_the_ID(), 'role', true);
$technology = get_post_meta(get_the_ID(), 'technology', true);
$single_case_study_background_image = get_field('single_case_study_background_image');
$form = get_field('form_block', 'option');
$form_heading = $form['heading'];
$form_content = $form['content'];
$bg = '';
?>
<?php if(!empty($single_case_study_background_image['url'])){
    $bg = $single_case_study_background_image['url'];
}
else {
    $bg = '';
}
?>
<section class="cs-hero single_case_study col container container-lg" style="background-image: url('<?php echo $bg; ?>');">
    <div class="cs-hero-left">
        <a href="/case-studies" class="cs-hero__back-to-cs"><?php render_svg('arrow-left.svg', 'left-arrow') ?>Back to all case studies</a>
        <h1 class="cs-hero__title">
            <?php echo $short_description; ?>
        </h1>
        <div class="cs-hero__meta flex row">
            <?php if(!empty($role)) {
                echo '<div class="cs-hero__meta__group flex col">';
                echo '<span class="cs-hero__meta__label">Role</span>';
                echo '<span class="cs-hero__meta__value">' . $role . '</span>';
                echo '</div>';
            } ?>
            <?php if(!empty($technology)) {
                echo '<div class="cs-hero__meta__group flex col">';
                echo '<span class="cs-hero__meta__label">Technology</span>';
                echo '<span class="cs-hero__meta__value">' . $technology . '</span>';
                echo '</div>';
            } ?>
            <?php if(!empty($industries)) {
                echo '<div class="cs-hero__meta__group flex col">';
                foreach($industries as $industry) {
                    echo '<span class="cs-hero__meta__label">Industry</span>';
                    echo '<span class="cs-hero__meta__value"><a href="'. get_term_link($industry->term_id) .'">' . $industry->name . '</a></span>';
                }
                echo '</div>';
            } ?>
        </div>
    </div>
    <div class="cs-hero__meta__image cs-hero-right">
        <a href="<?php echo get_the_permalink(get_the_ID()); ?>"><?php echo get_the_post_thumbnail(get_the_ID(), 'large'); ?></a>
    </div>
</section>
<?php
get_template_part('template-parts/flex/flexible_content');
?>
<section class="form-block" id="form-block">
    <div class="container container-main flex row afs">
        <div class="form-block__content item_1_2">
            <?php if(!empty($form_heading)): ?>
                <h2 class="form-block__heading"><?php echo $form_heading; ?></h2>
            <?php endif; ?>
            <?php if(!empty($form_content)): ?>
                <div class="form-block__content__content">
                    <?php echo $form_content; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="form-block__form item_1_2">
            <?php echo do_shortcode('[gravityform id="1" title="false"]'); ?>
        </div>
    </div>
    <img class = "dots-left__bottom" src = "<?php echo get_stylesheet_directory_uri() . '/assets/img/accent_left.png'; ?>">
    <img class = "dots-right__bottom" src = "<?php echo get_stylesheet_directory_uri() . '/assets/img/accent_right.png'; ?>">
</section>
<?php
get_footer();