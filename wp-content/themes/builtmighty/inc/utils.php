<?php
// var dump with pre
if (!function_exists('debug')) {
    function debug($var)
    { ?>
        <pre>
            <?php echo var_dump($var); ?>
        </pre>
        <?php
    }
}

// loop and apply a callback, like JavaScript .map()
if (!function_exists('loop')) {
    function loop($iterations, $callback)
    {
        if (is_callable($callback)) {
            for ($i = 0; $i < $iterations; $i++) {
                $callback($i);
            }
        }
    }
}

if (!function_exists('get_html_attr')) {
    function get_html_attr($html, $attr)
    {
        preg_match('@' . $attr . '="([^"]+)"@', $html, $match);
        $src = array_pop($match);
        return $src;
    }
}

// checks for local env (on MAMP)
if (!function_exists('is_local_dev')) {
    function is_local_dev()
    {
        if (get_site_url() == 'http://buildtmighty.local') {
            return true;
        } else {
            return false;
        }
    }
}

// field validator
if (!function_exists('echo_validated')) {
    function echo_validated($fields)
    {
        foreach ($fields as $field => $value) {
            if (!empty($field)) {
                echo $value;
            }
        }
    }
}

if (!function_exists('if_is_single_page')) {
    function if_is_single_page($output)
    {
        if (is_single()) {
            return $output;
        }
    }
}

// check if category has children, the return children
if (!function_exists('category_has_children')) {
    function category_has_children($term_id = 0, $taxonomy = 'category')
    {
        $children = get_categories(array(
            'child_of'      => $term_id,
            'taxonomy'      => $taxonomy,
            'hide_empty'    => false,
            'fields'        => 'ids',
        ));
        return ($children);
    }
}

// maybe link a related feature
if (!function_exists('maybe_link_feature')) {
    function maybe_link_feature($link_field, $content, $classes)
    {
        if ($link_field) {
        ?>
            <a href="<?php echo $link_field; ?>" class="<?php echo $classes; ?>">
                <?php echo $content; ?>
            </a>
        <?php
        } else {
            echo $content;
        }
    }
}

// -- renderers

// breadcrumbs
if (!function_exists('render_breadcrumbs')) {
    function render_breadcrumbs()
    {
        // function to generate links from a list of categories
        function generate_link($cats, $is_cat)
        {
            $output = array();
            if (is_array($cats)) {
                foreach ($cats as $cat) {
                    if ($is_cat) {
                        $catID = $cat->term_id;
                        $what_is = get_term_meta($catID, 'corresponding_what_is_page', true);
                        if (!empty($what_is)) {
                            $link = get_permalink($what_is);
                        } else {
                            $link = get_category_link($cat->term_id);
                        }
                    } else {
                        $link = get_term_link($cat->term_id);
                    }
                    $new_crumb = "<a itemprop='item' href='" . $link . "'><span itemprop='name'>" . $cat->name . "</span></a>";
                    if (!in_array($new_crumb, $output)) {
                        $output[] = $new_crumb;
                    }
                }
                return $output;
            }
        }
        $breadcrumbs = array();

        // for articles and pages
        if (is_page() || is_single()) {
            $postID = get_the_ID();
            $title = get_the_title($postID);
            $industry = get_the_terms($postID, 'industry') ? get_the_terms($postID, 'industry') : array();
            $categories = get_the_category($postID);
            $child_cats = array();
            $parent_cats = array();
            foreach ($categories as $cat) {
                if ($cat->category_parent !== 0) {
                    $child_cats[] = get_term($cat->term_id);
                    if (get_category($cat->term_id)->parent) {
                        $category_parent = get_category($cat->term_id)->parent;
                        $parent = get_term($category_parent);
                        $parent_cats[] = $parent;
                    }
                } else {
                    $parent_cats[] = get_term($cat->term_id);
                }
            }
            $crumb_groups = array(
                'parents' => $parent_cats,
                'children' => $child_cats,
                'industry' => $industry
            );
            foreach ($crumb_groups as $key => $group) {
                if ($key !== 'industry') {
                    $group = generate_link($group, true);
                } else {
                    $group = generate_link($group, false);
                }
                $breadcrumbs = array_merge($breadcrumbs, $group);
            }
        }

        // for categories/taxonomies
        if (is_category() || is_tax()) {
            $parent = null;
            if (is_category()) {
                $catID = get_queried_object()->term_id;
                $category_parent = get_category($catID)->parent;
                if ($category_parent) {
                    $parent = get_category($category_parent);
                    $parent = "<a itemprop='item' href='" . get_category_link($parent->term_id) . "'><span itemprop='name'>" . $parent->name . "</span></a>";
                    $breadcrumbs[] = $parent;
                }
            }
            $title = get_queried_object()->name;
            $breadcrumbs[] = $title;
        }

        // put it all together now
        // open breadcrumbs
        $output = "<nav id='breadcrumbs'><ul class='flex row afc jfs' itemscope itemtype='https://schema.org/BreadcrumbList'><li itemprop='itemListElement' itemscope
        itemtype='https://schema.org/ListItem'><a class='home-link' itemprop='item' href='" . get_site_url() . "'><span itemprop='name'>Blog</span></a><meta itemprop='position' content='1' /></li>";
        $i = 2;
        $last_crumb = array_key_last($breadcrumbs);
        foreach ($breadcrumbs as $key => $crumb) {
            if($key !== $last_crumb) {
                $output .= "<i class='fas fa-chevron-right'></i><li itemprop='itemListElement' itemscope
                itemtype='https://schema.org/ListItem'>" . $crumb . "<meta itemprop='position' content='".$i++."' /></li>";
            } else {
                $output .= "<i class='fas fa-chevron-right'></i><li itemprop='itemListElement' itemscope
            itemtype='https://schema.org/ListItem'><span itemprop='name'>" . $crumb . "</span><meta itemprop='position' content='".$i++."' /></li>";
            }
            
        }
        // close breadcrumbs
        $output .= "</ul></nav>";

        echo $output;
    }
}

// time to read article
if (!function_exists('time_to_read')) {
    function time_to_read()
    {
        global $post;

        // get the content and divide words by the average reading speed in WPM
        // then round up for aproximate read time
        $content = get_the_content($post);
        $count = str_word_count($content);
        $readtime = round($count / 265);
        ?>
        <span class="time-to-read"><?php echo $readtime; ?> min read</span>
        <?php
    }
}

// custom excerpt length
if (!function_exists('get_excerpt')) {
    function get_excerpt($postID = false, $numletters, $readmore = true)
    {
        if ($postID) {
            $content_post = get_post($postID);
            $content = $content_post->post_content;
            $content = apply_filters('the_content', $content);
            $content = str_replace(']]>', ']]&gt;', $content);
            $permalink = get_the_permalink($postID);
            $excerpt = $content;
        } else {
            $excerpt = get_the_content();
        }

        $excerpt = preg_replace(" ([*])", '', $excerpt);
        $excerpt = strip_shortcodes($excerpt);
        $excerpt = strip_tags($excerpt);
        $excerpt = substr($excerpt, 0, $numletters);
        $excerpt = substr($excerpt, 0, strripos($excerpt, " "));
        if($readmore) {
            $excerpt .= ' <a class="readmore" href="' . esc_url($permalink) . '">Read More <span>>></span></a>';
        } else {
            $excerpt .= "...";
        }
        

        return $excerpt;
    }
}

// render an image
if (!function_exists('render_featured_image')) {
    function render_featured_image(
        $thumb_args = array(
            'thumb_id' => 0,
            'size' => 'full'
        )
    ) {
        $thumb = array();
        $args = array(
            'post_type' => 'attachment',
            'include' => $thumb_args['thumb_id']
        );
        $thumbs = get_posts($args);
        if ($thumbs) {
            $thumb['title'] = $thumbs[0]->post_title;
            $thumb['description'] = $thumbs[0]->post_content;
            $thumb['caption'] = $thumbs[0]->post_excerpt;
            $thumb['alt'] = get_post_meta($thumb_args['thumb_id'], '_wp_attachment_image_alt', true);
            $thumb['size'] = wp_get_attachment_image_url($thumb_args['thumb_id'], $thumb_args['size']);
        }
        if (!empty($thumb)) {
        ?>
            <figure class="flex featured-image featured-image-<?php echo $thumb_args['size'] ?>">
                <?php echo '<img class="imgfix" src="' . $thumb['size'] . '" alt="' . $thumb['alt'] . '" title="' . $thumb['title'] . '" itemscope itemprop="image" />'; ?>
            </figure>
        <?php
        }
    }
}

// render an array of menus
if (!function_exists('render_menus')) {
    function render_menus($menus, $show_title = false)
    {
        foreach ($menus as $menu) {
            if ($show_title) {
            }
            echo $menu;
        }
    }
}

// render footer menus with titles
if (!function_exists('render_footer_menus')) {
    function render_footer_menus($menus)
    {
        foreach ($menus as $menu) {
            $menu_obj = wp_get_nav_menu_object($menu);
            $output = wp_nav_menu(array(
                'menu' => $menu_obj->term_id,
                'menu_class'     => 'acq-menu flex col afs jfs',
                'echo' => false,
            ))
        ?>
            <div class="item_1_5 footer-menu">
                <h4 class="footer-menu-title"><?php echo $menu_obj->name; ?><i class="fas fa-chevron-down"></i></h4>
                <?php
                echo $output;
                ?>
            </div>
        <?php
        }
    }
}

// render BM logo
if (!function_exists('bm_render_svg_logo')) {
    function bm_render_svg_logo()
    {
    ?>
        <a href="<?php echo get_site_url(); ?>">
            <img class="logo" src="<?php echo get_stylesheet_directory_uri().'/assets/svg/Logo.svg'; ?>" alt="BuiltMighty Logo">
        </a>
    <?php
    }
}

// render an svg
if (!function_exists('render_svg')) {
    function render_svg($file, $htmlClass)
    {
    ?>
        <img class="<?php echo $htmlClass; ?>" src="<?php echo get_stylesheet_directory_uri().'/assets/svg/'.$file; ?>">
    <?php
    }
}

if (!function_exists('render_svg')) {
    function render_svg($type)
    {
        $raf = '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="24" viewBox="0 0 15 24">
        <path fill="#FFF" fillRule="evenodd" d="M1221.08513,516.064538 C1221.31748,515.97995 1221.56257,515.982459 1221.81694,516.04907 L1221.81694,516.04907 L1222.7018,518.363194 L1222.34208,516.162458 C1224.41883,516.736832 1229.50234,524.330593 1228.95972,528.673396 C1228.83548,529.667877 1228.47986,531.186982 1227.68112,531.789691 C1225.60605,533.355527 1217.10377,533.55276 1215.22926,531.482972 C1214.24028,530.391035 1214.0929,529.530752 1214.00711,528.023817 C1213.78952,524.20313 1218.62064,516.598128 1220.70059,516.121209 L1220.70059,516.121209 L1219.75376,518.342569 Z M1222.14349,520.381142 L1221.42406,520.381142 L1221.42406,521.39936 C1220.87171,521.455799 1220.45304,521.677745 1220.16811,522.065244 C1219.83463,522.518843 1219.66704,523.078398 1219.66704,523.746418 C1219.66704,524.483465 1219.85963,525.060254 1220.2464,525.476878 C1220.52507,525.775144 1221.18425,526.105461 1222.22548,526.467829 C1222.4478,526.549166 1222.59183,526.640397 1222.656,526.736505 C1222.71708,526.837584 1222.74842,526.96579 1222.74842,527.118616 C1222.74842,527.345392 1222.69204,527.527761 1222.57931,527.663353 C1222.4102,527.855662 1222.15971,527.951771 1221.82622,527.951771 C1221.524,527.951771 1221.28759,527.850692 1221.12005,527.646119 C1220.95098,527.444009 1220.83981,527.145743 1220.785,526.753785 L1220.785,526.753785 L1219.48858,527.06436 C1219.60757,527.789097 1219.86123,528.361009 1220.24797,528.78251 C1220.55425,529.115011 1220.94632,529.317121 1221.42406,529.387635 L1221.42406,529.387635 L1221.42406,530.405574 L1222.14349,530.405574 L1222.14349,529.398923 C1222.72054,529.334216 1223.1675,529.086396 1223.48429,528.656812 C1223.85851,528.151466 1224.04483,527.545041 1224.04483,526.842508 C1224.04483,526.196692 1223.91019,525.691345 1223.63931,525.331438 C1223.36687,524.973994 1222.88619,524.673266 1222.19727,524.426794 C1221.50839,524.180276 1221.10596,523.990475 1220.98854,523.854883 C1220.90242,523.753804 1220.86016,523.630568 1220.86016,523.485128 C1220.86016,523.317531 1220.90868,523.179477 1221.00732,523.073475 C1221.15608,522.923064 1221.40032,522.84916 1221.74163,522.84916 C1222.01251,522.84916 1222.22078,522.927988 1222.36638,523.088246 C1222.51197,523.248458 1222.61222,523.480204 1222.66383,523.780932 L1222.66383,523.780932 L1223.87572,523.425995 C1223.75361,522.753005 1223.52972,522.245197 1223.20559,521.900062 C1222.95461,521.634777 1222.60029,521.471453 1222.14349,521.411252 L1222.14349,521.411252 L1222.14349,520.381142 Z M1227.20904,509.783695 L1226.11526,512.400616 L1227.28062,510.887741 C1229.72029,512.373345 1226.9876,513.726098 1225.5224,515.988639 C1225.30281,515.816263 1225.01283,515.707825 1224.64325,515.671756 L1225.39973,513.923764 L1224.16283,515.579718 C1223.84936,515.579718 1222.49831,515.378689 1221.78103,515.937507 C1220.91533,514.808171 1219.90313,513.774927 1219.07986,512.642505 C1218.72022,512.147902 1219.6273,511.175464 1220.75884,510.836608 L1221.93438,513.16728 L1220.9326,509.773514 C1222.05364,508.795595 1225.74043,508.686512 1227.20904,509.783695 Z" transform="translate(-1214 -509)"/>
    </svg>';
        $mobile = '<svg
    width="13.6"
    height="25"
    viewBox="0 0 14 25"
>
    <path d="M12.5 19c0 .1-.1.1 0 0l-11.7.1c-.1 0-.1 0-.1-.1V3c0-.1 0-.1.1-.1h11.6c.1 0 .1 0 .1.1v16zM5 21.9c0-.9.7-1.6 1.6-1.6s1.6.7 1.6 1.6c0 .9-.7 1.6-1.6 1.6S5 22.7 5 21.9zM2.2 0C1 0 0 1 0 2.2v20.6C0 24 1 25 2.2 25h8.7c1.2 0 2.2-1 2.2-2.2V2.2c0-1.2-1-2.2-2.2-2.2H2.2z" fillRule="evenodd" clipRule="evenodd" fill="#fff"/>
</svg>';

        switch ($type) {
            case "raf":
                echo $raf;
                break;
            case "mobile":
                echo $mobile;
                break;
        }
    }
}