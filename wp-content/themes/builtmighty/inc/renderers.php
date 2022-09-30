<?php
/*--------------------------------------------------------------
# structural sections
--------------------------------------------------------------*/

// visible header

if (!function_exists('site_header')) :
    function site_header()
    { ?>
        <div class = "main-header">
            <div id="system-bar">Get started building your WooCommerce store or web application  —  Call us today at <a href="tel:!12068067809">(206) 806.7809</a></div>
            <header id="site-header" class="flex row afc jfsb" role="banner">
                <div class="container container-wide flex full row afc jfsb">
                    <img class = "mobile_nav_icon" src = "<?php echo get_stylesheet_directory_uri() . '/assets/img/mobile_nav.png'; ?>">
                    <?php bm_render_svg_logo(); ?>
                    <nav id="site-navigation" class="main-navigation flex jfe" role="navigation">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'menu-main',
                            'menu_id'        => 'primary-menu',
                            'menu_class'     => 'flex row afc',
                        ));
                        ?>
                    </nav>
                </div>
            </header>
            <div id="mobile-site-navigation" class="main-mobile-navigation flex jfe" role="navigation">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'menu-main',
                    'menu_id'        => 'primary-menu',
                    'menu_class'     => 'flex row afc',
                ));
                ?>
            </div>
        </div>
    <?php
    }
endif;

// visible footer
if (!function_exists('site_footer')) :
    function site_footer()
    { ?>
    <?php      
        $locations = get_nav_menu_locations();
        $menu_id   = $locations[ 'footer-menu' ] ;
        $menu = wp_get_nav_menu_object($menu_id);
        $head = get_field('heading', $menu);
        $description = get_field('description', $menu);
        $contact_number = get_field('contact_number', $menu);
        $contact_email = get_field('contact_email', $menu);
        
    ?>
        <footer id="site-footer" class="site-footer" role="contentinfo">
            <div class="container container-wide flex col">
                <section class="site-footer__top-row flex row afs">
                    <div class="site-footer__logo item_1_4 flex row afs">
                        <?php render_svg("footer-logo.svg", "footer-logo"); ?>
                    </div>
                    <div class="site-footer__menu item_1_4 flex col">
                        <h4>Built Mighty</h4>
                        <?php
                        wp_nav_menu( array(
                            'theme_location' => 'footer-menu',
                            'menu_id'        => 'footer-menu',
                            'container_class'	 => 'site-footer__menu-items flex row',
                            'menu_class'	 => 'flex row',
                        ) );
                        ?>
                    </div>
                    <div class="site-footer__menu item_1_4 flex col">
                        <h4><?php echo $head; ?></h4>
                        <?php echo $description; ?>
                    </div>
                    <div class="site-footer__menu item_1_4 flex col">
                        <h4>Contact</h4>
                        <ul>
                            <li><?php echo $contact_number; ?></li>
                            <li><?php echo $contact_email; ?></li>
                        </ul>
                    </div>
                </section>
                <section class="site-footer__copyright flex row afc jfsb">
                    <span>Proudly made in Seattle, WA.</span>
                    <span>&copy; <?php echo date('Y'); ?> All rights reserved.</span>
                </section>
            </div>
            <script type="text/javascript" src="https://www.bugherd.com/sidebarv2.js?apikey=uuguzijxt0qt9phbilymbq" async="true"></script>
        </footer>
    <?php
    }
endif;