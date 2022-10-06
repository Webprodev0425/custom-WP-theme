<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 7/23/19
 * Time: 16:33
 */

$is_show = apply_filters('ppwp_sitewide_should_show_form', true);
?>
<div class="ppw_main_container">
	<?php
	do_action('ppwp_view_before_sitewide_form');
	if ( $is_show ) { ?>
		<form id="ppw_entire_site_form">
		<input type="hidden" id="ppw-entire-site-nonce"
		       value="<?php echo wp_create_nonce( PPW_Constants::ENTIRE_SITE_FORM_NONCE ); ?>"/>
		<table class="ppwp_settings_table" cellpadding="4">
			<?php
			$is_protected = ppw_pro_check_is_protect_entire_site();
			include PPW_PRO_VIEW_PATH . 'entire-site/view-ppw-pro-set-password.php';
			include PPW_PRO_VIEW_PATH . 'entire-site/view-ppw-pro-exclude-page.php';
			include PPW_PRO_VIEW_PATH . 'entire-site/view-ppw-pro-redirection.php';
			?>
		</table>
		<?php
		submit_button();
		?>
	</form>
	<?php
		if ( ! defined( 'PPWP_PS_VERSION' ) ) { ?>
			<span class="ppwp-plugin-advise">
				<?php
					echo sprintf( '<a target="_blank" rel="noopener noreferrer" href="https://passwordprotectwp.com/docs/extend-sitewide-protection-features/#migrate">%1$s</a>, %2$s <a target="_blank" rel="noopener noreferrer" href="https://passwordprotectwp.com/extensions/password-suite/">%3$s</a> addon.', __( 'Restrict password usage', 'password-protect-page' ), __('create access links to bypass sitewide protection and much more with', 'password-protect-page'), __( 'Password Suite', 'password-protect-page' )   );
				?>
			</span>
		<?php	}
	}
	do_action('ppwp_view_after_sitewide_form');
	?>
</div>
<?php
//$asset_services = new PPW_Asset_Services( null, null );
//$asset_services->load_css( 'entire-site', PPW_PRO_VERSION );
//wp_enqueue_script( 'ppw-entire-site-js', plugin_dir_url( __FILE__ ) . 'entire-site.js', array( 'jquery' ), PPW_PRO_VERSION, false );
//wp_localize_script( 'ppw-entire-site-js', 'ppw_entire_site_data', array(
//	'ajax_url' => admin_url( 'admin-ajax.php' ),
//) );

?>
