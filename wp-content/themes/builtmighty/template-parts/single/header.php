<?php
if (!function_exists('post_header')) :
    function post_header($header_args)
    {
?>
        <header id="post-title" class="flex col">
            <h1 itemprop="headline name"><?php echo $header_args['title']; ?></h1>
            <div itemprop="datePublished" content="<?php echo get_the_date(); ?>" class="data"></div>
            <aside id="author" class="flex row afc jfsb">
                <?php
                $author_count = count($header_args['authors_list']);
                $show_date = get_post_meta(get_the_ID(), 'show_last_modified_date', true);
                ?>
                <div>
                    <?php
                    foreach ($header_args['authors_list'] as $author) {
                        $author_count--;
                        echo $author['name'];
                        if ($author_count > 0) {
                            echo ', ';
                        }
                    }
                    $last_modified = get_the_modified_date('F j, Y', get_the_ID());
                    if ($show_date !== "0") {
                        echo '<small>Last Modified: ' . $last_modified . '</small>';
                    }
                    ?>
                    <meta itemprop="dateModified" content="<?php echo $last_modified; ?>" />
                </div>
                <?php time_to_read(); ?>
                <?php
                echo do_shortcode('[Sassy_Social_Share]');
                ?>

            </aside>
            <?php

            ?>
        </header>
<?php
    }
endif;
