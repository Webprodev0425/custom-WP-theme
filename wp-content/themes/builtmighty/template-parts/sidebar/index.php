<?php
include_once(trailingslashit(get_template_directory()) . 'template-parts/sidebar/cta.php');

if (!function_exists('bm_render_sidebar')) :
    function bm_render_sidebar()
    {
        $postID = get_the_ID();
        $free_trial = array(
            'id' => 'ft',
            'tagline' => get_option('options_ft_tagline'),
            'action_verbage' => get_option('options_ft_action_verbage'),
            'button' => array(
                'label' => get_option('options_ft_button_label'),
                'link' => get_option('options_ft_button_link'),
                'external_link' => get_option('options_ft_external_link'),
            ),
            'logo_color' => get_option('options_ft_logo_color'),
        );
        $free_webinar = array(
            'id' => 'fw',
            'tagline' => get_option('options_fw_tagline'),
            'action_verbage' => get_option('options_fw_action_verbage'),
            'button' => array(
                'label' => get_option('options_fw_button_label'),
                'link' => get_option('options_fw_button_link'),
                'icon' => '<i class="fas fa-chevron-right"></i>',
                'external_link' => get_option('options_fw_external_link'),
            ),
            'logo_color' => get_option('options_fw_logo_color'),
        );
        $free_download = array(
            'id' => 'fd',
            'action_verbage' => get_option('options_fd_action_verbage'),
            'button' => array(
                'label' => get_option('options_fd_button_label'),
                'link' => get_option('options_fd_button_link'),
                'icon' => '<i class="fas fa-chevron-right"></i>',
                'external_link' => get_option('options_fd_external_link'),
            ),
            'logo_color' => get_option('options_fd_logo_color'),
        );
        $sidebar_sections = [$free_trial, $free_webinar, $free_download];
?>
        <section class="bm-sidebar-container">
            <?php bm_sidebar_related_features($postID); ?>
            <?php bm_sidebar_ctas($sidebar_sections); ?>
        </section>
<?php
    }
endif;
