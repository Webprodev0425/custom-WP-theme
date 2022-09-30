<?php
get_header();
$postID = get_the_ID();

// featured articles
$catID = get_queried_object()->term_id;
?>
<section class="error container container-main">
    <h1 class="404 flex col afs jfs"><span id="404">404</span>A MIGHTY FAIL.</h1>
    <p>You don't have to put up with this. Get back on track on our <a href="<?php echo site_url(); ?>">home page</a>.</p>
</section>
<?php

get_footer();
