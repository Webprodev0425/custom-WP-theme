<?php

// render the sidebar sections
if (!function_exists('bm_sidebar_ctas')) {
    function bm_sidebar_ctas($sections)
    {
        foreach ($sections as $section) {
            $button = $section['button'];
            $fill = $section['logo_color'] == 'light';
?>
            <aside id="<?php echo $section['id']; ?>-cta" class="sidebar-cta">
                <?php render_bm_logo_icon_svg($fill); ?>
                <?php if ($section['tagline'] && !empty($section['tagline'])) : ?>
                    <h4><?php echo sanitize_text_field($section['tagline']); ?></h4>
                <?php endif; ?>
                <?php if ($section['action_verbage'] && !empty($section['action_verbage'])) : ?>
                    <p><?php echo sanitize_text_field($section['action_verbage']); ?></p>
                <?php endif; ?>
                <?php
                if (!empty($button['label']) && !empty($button['link'])) :
                    $button_output = '<a href="' . sanitize_text_field($button['link']) . '"';
                    if ($button['external_link']) {
                        $button_output .= 'target="_blank"';
                    }
                    $button_output .= '>';
                    $button_output .= sanitize_text_field($button['label']);
                    if ($button['icon']) {
                        $button_output .= $button['icon'];
                    }
                    $button_output .= '</a>';
                    echo $button_output;
                ?>

                <?php endif; ?>
            </aside>
<?php
        }
    }
}
