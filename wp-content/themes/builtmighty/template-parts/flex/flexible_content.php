<?php
$flex_contents = get_field('content_rows');
global $flex_content;
if(!empty($flex_contents)): foreach($flex_contents as $content):
	$flex_content = $content;
	get_template_part('template-parts/flex/'. $flex_content['acf_fc_layout'] .'/index');
endforeach; endif;