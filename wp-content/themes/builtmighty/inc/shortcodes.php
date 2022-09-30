<?php
// take a list of strings, comma separated, and rotate through them
function rotate_text( $atts = array() ) 
{
    extract(shortcode_atts(array(
        'text_values' => '',
    ), $atts));

    return '<span class="rotate-text" data-rotate="'.$text_values.'"></span>';
}
add_shortcode('bm_rotating_text', 'rotate_text');

// render a checklist with icons
function render_checklist( $atts = array(), $content = null )
{
    extract(shortcode_atts(array(
        'icons' => 'check-line',
        'color' => 'black',
    ), $atts));
    $content = explode(",", $content);
    ob_start();
    ?>
    <div class="checklist flex col afs jfc">
        <?php foreach ($content as $item): ?>
            <span class="checklist__item flex row jfc afs nowrap">
                <?php 
                if ($icons == 'check'):
                    $file_tail = '-line';
                else:
                    $file_tail = '';
                endif;
                render_svg('circle-' . $icons . $file_tail .'.svg', $icons); 
                ?>
                <span class="text text__<?php echo $color; ?>"><?php echo $item; ?></span>
            </span>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('bm_checklist', 'render_checklist');