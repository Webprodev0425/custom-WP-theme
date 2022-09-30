<?php
get_header();
$postID = get_the_ID();

if(have_posts()): while(have_posts()): the_post();
	get_template_part('template-parts/flex/flexible_content');
	echo !empty(get_the_content()) ?  '<div class="container container-sm">'.apply_filters( 'the_content', $post->post_content ).'</div>' :  '';
endwhile; endif;

get_footer();
