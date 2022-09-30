<?php
global $flex_content;
$layout = $flex_content['layout'];
$heading = $flex_content['heading'];
$clients = $flex_content['clients'];
?>

<section class="client-results">
    <div class="container container-main flex col afc jfc">
        <?php if (!empty($heading)) : ?>
            <h2 class="client-results__heading"><?php echo $heading; ?></h2>
        <?php endif; ?>
        <div class="client-results__clients flex <?php echo $layout; ?> full jfsb afsb">
            <?php foreach ($clients as $client) : ?>
                <?php
                $client_object = $client['client'];
                $client_result = $client['result'];
                $client_bg_image = $client['background_image'];

                if (!empty($client_bg_image)) {
                    $client_bg_image = $client_bg_image['url'];
                }

                if ($layout === 'row') {
                    $class_list = 'afs jfsb item_1_3_gut';
                } else {
                    $class_list = 'afc jfc';
                }
                ?>
                <div class="client-results__client flex col <?php echo $class_list; ?>" <?php
                                                                                        if (!empty($client_bg_image)) :
                                                                                        ?> style="background-image: url(<?php echo $client_bg_image; ?>);" <?php
                                                                                        endif;
                                                                                        ?>>
                    <?php if ($layout === 'row') :
                        $year = get_post_meta($client_object->ID, 'study_year', true);
                    ?>
                        <header class="client-results__client--header flex row afc jfsb">
                            <span><?php echo $client_object->post_title; ?></span>
                            <span><?php echo $year; ?></span>
                        </header>
                    <?php endif; ?>
                    <h4 class="client-results__client--result">How <?php echo $client_object->post_title . ' ' . $client_result; ?></h4>
                    <a class="client-results__client--link" href="<?php echo get_permalink($client_object->ID); ?>">
                        Read case study <span>&rarr;</span>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        <a class="button" href="<?php echo trailingslashit(get_site_url()) . 'case-studies'; ?>">See all case studies <span>&rarr;</span></a>
    </div>
</section>