<?php
/**
 * Astra functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Astra
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Define Constants
 */
define( 'ASTRA_THEME_VERSION', '3.9.2' );
define( 'ASTRA_THEME_SETTINGS', 'astra-settings' );
define( 'ASTRA_THEME_DIR', trailingslashit( get_template_directory() ) );
define( 'ASTRA_THEME_URI', trailingslashit( esc_url( get_template_directory_uri() ) ) );

/**
 * Minimum Version requirement of the Astra Pro addon.
 * This constant will be used to display the notice asking user to update the Astra addon to the version defined below.
 */
define( 'ASTRA_EXT_MIN_VER', '3.9.2' );

/**
 * Setup helper functions of Astra.
 */
require_once ASTRA_THEME_DIR . 'inc/core/class-astra-theme-options.php';
require_once ASTRA_THEME_DIR . 'inc/core/class-theme-strings.php';
require_once ASTRA_THEME_DIR . 'inc/core/common-functions.php';
require_once ASTRA_THEME_DIR . 'inc/core/class-astra-icons.php';

/**
 * Update theme
 */
require_once ASTRA_THEME_DIR . 'inc/theme-update/class-astra-theme-update.php';
require_once ASTRA_THEME_DIR . 'inc/theme-update/astra-update-functions.php';
require_once ASTRA_THEME_DIR . 'inc/theme-update/class-astra-theme-background-updater.php';
require_once ASTRA_THEME_DIR . 'inc/theme-update/class-astra-pb-compatibility.php';


/**
 * Fonts Files
 */
require_once ASTRA_THEME_DIR . 'inc/customizer/class-astra-font-families.php';
if ( is_admin() ) {
	require_once ASTRA_THEME_DIR . 'inc/customizer/class-astra-fonts-data.php';
}

require_once ASTRA_THEME_DIR . 'inc/lib/webfont/class-astra-webfont-loader.php';
require_once ASTRA_THEME_DIR . 'inc/customizer/class-astra-fonts.php';

require_once ASTRA_THEME_DIR . 'inc/dynamic-css/custom-menu-old-header.php';
require_once ASTRA_THEME_DIR . 'inc/dynamic-css/container-layouts.php';
require_once ASTRA_THEME_DIR . 'inc/dynamic-css/astra-icons.php';
require_once ASTRA_THEME_DIR . 'inc/core/class-astra-walker-page.php';
require_once ASTRA_THEME_DIR . 'inc/core/class-astra-enqueue-scripts.php';
require_once ASTRA_THEME_DIR . 'inc/core/class-gutenberg-editor-css.php';
require_once ASTRA_THEME_DIR . 'inc/core/class-astra-wp-editor-css.php';
require_once ASTRA_THEME_DIR . 'inc/dynamic-css/block-editor-compatibility.php';
require_once ASTRA_THEME_DIR . 'inc/dynamic-css/inline-on-mobile.php';
require_once ASTRA_THEME_DIR . 'inc/dynamic-css/content-background.php';
require_once ASTRA_THEME_DIR . 'inc/class-astra-dynamic-css.php';
require_once ASTRA_THEME_DIR . 'inc/class-astra-global-palette.php';

/**
 * Custom template tags for this theme.
 */
require_once ASTRA_THEME_DIR . 'inc/core/class-astra-attr.php';
require_once ASTRA_THEME_DIR . 'inc/template-tags.php';

require_once ASTRA_THEME_DIR . 'inc/widgets.php';
require_once ASTRA_THEME_DIR . 'inc/core/theme-hooks.php';
require_once ASTRA_THEME_DIR . 'inc/admin-functions.php';
require_once ASTRA_THEME_DIR . 'inc/core/sidebar-manager.php';

/**
 * Markup Functions
 */
require_once ASTRA_THEME_DIR . 'inc/markup-extras.php';
require_once ASTRA_THEME_DIR . 'inc/extras.php';
require_once ASTRA_THEME_DIR . 'inc/blog/blog-config.php';
require_once ASTRA_THEME_DIR . 'inc/blog/blog.php';
require_once ASTRA_THEME_DIR . 'inc/blog/single-blog.php';

/**
 * Markup Files
 */
require_once ASTRA_THEME_DIR . 'inc/template-parts.php';
require_once ASTRA_THEME_DIR . 'inc/class-astra-loop.php';
require_once ASTRA_THEME_DIR . 'inc/class-astra-mobile-header.php';

/**
 * Functions and definitions.
 */
require_once ASTRA_THEME_DIR . 'inc/class-astra-after-setup-theme.php';

// Required files.
require_once ASTRA_THEME_DIR . 'inc/core/class-astra-admin-helper.php';

require_once ASTRA_THEME_DIR . 'inc/schema/class-astra-schema.php';

if ( is_admin() ) {

	/**
	 * Admin Menu Settings
	 */
	require_once ASTRA_THEME_DIR . 'inc/core/class-astra-admin-settings.php';
	require_once ASTRA_THEME_DIR . 'inc/lib/astra-notices/class-astra-notices.php';

}

/**
 * Metabox additions.
 */
require_once ASTRA_THEME_DIR . 'inc/metabox/class-astra-meta-boxes.php';

require_once ASTRA_THEME_DIR . 'inc/metabox/class-astra-meta-box-operations.php';

/**
 * Customizer additions.
 */
require_once ASTRA_THEME_DIR . 'inc/customizer/class-astra-customizer.php';

/**
 * Astra Modules.
 */
require_once ASTRA_THEME_DIR . 'inc/modules/related-posts/class-astra-related-posts.php';

/**
 * Compatibility
 */
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-gutenberg.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-jetpack.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/woocommerce/class-astra-woocommerce.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/edd/class-astra-edd.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/lifterlms/class-astra-lifterlms.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/learndash/class-astra-learndash.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-beaver-builder.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-bb-ultimate-addon.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-contact-form-7.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-visual-composer.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-site-origin.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-gravity-forms.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-bne-flyout.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-ubermeu.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-divi-builder.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-amp.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-yoast-seo.php';
require_once ASTRA_THEME_DIR . 'inc/addons/transparent-header/class-astra-ext-transparent-header.php';
require_once ASTRA_THEME_DIR . 'inc/addons/breadcrumbs/class-astra-breadcrumbs.php';
require_once ASTRA_THEME_DIR . 'inc/addons/heading-colors/class-astra-heading-colors.php';
require_once ASTRA_THEME_DIR . 'inc/builder/class-astra-builder-loader.php';

// Elementor Compatibility requires PHP 5.4 for namespaces.
if ( version_compare( PHP_VERSION, '5.4', '>=' ) ) {
	require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-elementor.php';
	require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-elementor-pro.php';
	require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-web-stories.php';
}

// Beaver Themer compatibility requires PHP 5.3 for anonymus functions.
if ( version_compare( PHP_VERSION, '5.3', '>=' ) ) {
	require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-beaver-themer.php';
}

require_once ASTRA_THEME_DIR . 'inc/core/markup/class-astra-markup.php';

/**
 * Load deprecated functions
 */
require_once ASTRA_THEME_DIR . 'inc/core/deprecated/deprecated-filters.php';
require_once ASTRA_THEME_DIR . 'inc/core/deprecated/deprecated-hooks.php';
require_once ASTRA_THEME_DIR . 'inc/core/deprecated/deprecated-functions.php';
acf_add_options_page(array(
    'page_title'     => 'Site Options',
));

function mytheme_custom_scripts(){
	$scriptSrc = get_template_directory_uri() . '/assets/js/unminified/custom.js';
	wp_enqueue_script( 'custom', $scriptSrc , array(), '1.0',  false );
}
add_action( 'wp_enqueue_scripts', 'mytheme_custom_scripts' );

add_action( 'wp_ajax_my_ajax_request', 'tft_handle_ajax_request' );
  add_action( 'wp_ajax_nopriv_my_ajax_request', 'tft_handle_ajax_request' );
  function tft_handle_ajax_request() {
    $name	= isset($_POST['name'])?trim($_POST['name']):"";
	$email	= isset($_POST['email'])?trim($_POST['email']):"";
	$pass	= isset($_POST['pass'])?trim($_POST['pass']):"";
    $response	= array();
    $response['name']	= $name;
	$response['email']	= $email;
	$response['pass']	= $pass;

      //DB connection
      $servername = "localhost";
      $database = "l33devsite";
      $username = "l33devsite";
      $password = "PMayiUb5POrkTrj";

      $conn = mysqli_connect($servername, $username, $password, $database);

      if (!$conn) {
          die("Connection failed: " . mysqli_connect_error());
      }

	//create a new role for the user
      if(!current_user_can( 'manage_options' ) && !empty($name) && !empty($email)) {
          add_role($name, __($name), array('read' => true, 'edit_posts' => false));

          //create a new user account
          wp_create_user($name, $pass, $email);

          //add a new role to the user
          $user_login = $name;
          $user = get_userdatabylogin($user_login);
          $user_id = $user->ID;
          $user->set_role($name);

          //auto login
          wp_set_current_user($user_id, $user_login);
          wp_set_auth_cookie($user_id);
          do_action('wp_login', $user_login);

          //generate a new password and expiration date
          $table_name = "wp_pda_passwords";
          $created_time = time();
          $expired_time = $created_time + 2592000;
          $role = 'master_role_' . $name;

          $sql = "INSERT INTO $table_name (campaign_app_type, `password`, created_time, expired_date, label, post_types) VALUES ('$role', '$pass', $created_time, $expired_time, '$name', 'post')";
          if (mysqli_query($conn, $sql)) {
              echo "added";
              $sender = "Latitude 33 Aviation <charter@l33jets.com>";
              $subject = 'Your Password';
              $img = "http://l33devsite.kinsta.cloud/wp-content/uploads/2022/10/latitude33.png";
              $footer_img = "http://l33devsite.kinsta.cloud/wp-content/uploads/2022/10/latitude33_sale.png";
              $message = "<div style='width: 50%; margin: auto; text-align: center;'> <img src='$img' width='250'/></div>";
              $message .= "<hr style='width: 300px; margin: 50px auto; background-color: #000;'>";
              $message .= "<div style='width: 50%; margin: 50px auto;'>";
              $message .= "<h3>Hi <span style='font-size: 40px;'><i>" . $name . "," . "</i></span></h3>" . "Your password is generated. Please use this password '" . $pass . "' to see the <a href='/aircraft-calculator/'>calculator</a>";
              $message .= "</div>";
              $message .= "<div style='background-color: #eee; padding: 30px 0;'><div style='width: 50%; margin: 0px auto;'><a href='https://l33jets.com/'><img src='$footer_img' style='width: 100%' /></a>";
              $message .= "<p style='text-align: center;'>*Tax not included.<br>The information above is intended to be as accurate as possible; however, charter flight schedules change regularly and the flight availability on this page may not reflect all recent changes. Subject to <a href='https://l33jets.com/terms'>terms and conditions</a></p>";
              $message .= "<p style='font-weight: bold; text-align: center;'>Latitude 33 Aviation, 2100 Palomar Airport Rd., Suite 211, Carlsbad, CA 92011</p>";
              $message .= "</div></div>";

              $headers = implode("From: " . $sender . " \r\n", [
                  "MIME-Version: 1.0",
                  "Content-type: text/html; charset=utf-8"
              ]);
              $send_email = mail($email, $subject, $message, $headers);
              $response['email_sent'] = ($send_email) ? 'success' : 'error';
          } else {
              echo "not added";
              echo "Error: " . $sql . "<br>" . mysqli_error($conn);
          }

          mysqli_close($conn);
      }
    echo json_encode($response);
    exit;
  }
