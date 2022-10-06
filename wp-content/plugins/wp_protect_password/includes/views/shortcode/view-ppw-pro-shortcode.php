<?php
$is_show_notices = defined( 'PPW_VERSION' ) && version_compare( PPW_VERSION, '1.3.0', '<' );
if ( $is_show_notices ) {
	?>
	<tr>
		<td></td>
		<td>
			<a target="_blank" rel="noopener"
			   href="https://passwordprotectwp.com/docs/protect-partial-content-page-builders/">Use PPWP block</a> for
			Page Builder plugins.
		</td>
	</tr>
	<?php
}
if ( ! defined( 'PDA_GOLD_V3_VERSION' ) ) {
	$html_link = sprintf(
		'<a target="_blank" rel="noopener" href="%s">protect files embedded in content</a>',
		'https://passwordprotectwp.com/extensions/prevent-direct-access-gold-integration/'
	);

	$gold_link = sprintf(
		'<a target="_blank" rel="noopener" href="%s">Prevent Direct Access Gold</a>',
		'https://preventdirectaccess.com/features/'
	);

	$desc = sprintf(
		// translators: %s: Link to documentation.
		esc_html__( 'Want to %1$1s? Integrate Password Protect WordPress Pro with %2$2s.', 'password-protect-page' ),
		$html_link,
		$gold_link
	);
	?>
	<tr>
		<td class="feature-input"><span class="feature-input"></span></td>
		<td>
			<p>
				<label><?php esc_html_e( 'Password Protect Files', 'password-protect-page' ); ?></label>
				<?php echo wp_kses_post( $desc ); ?>
			</p>
		</td>
	</tr>
	<?php
} else {
	$html_link = sprintf(
		'<a target="_blank" rel="noopener" href="%s">protect files embedded in content</a>',
		'https://passwordprotectwp.com/how-to-password-protect-files-in-content/'
	);
	$desc    = sprintf(
		// translators: %s: Link to documentation.
		esc_html__( 'Use the following shortcode to %s. Restrict access to these files by time or number of clicks.', 'password-protect-page' ),
		$html_link
	);
	$message = esc_html__( 'Great! You’ve successfully copied the shortcode to clipboard.', 'password-protect-page' );
	?>
	<tr>
		<td class="feature-input"><span class="feature-input"></span></td>
		<td>
			<p>
				<label><?php esc_html_e( 'Password Protect Files', 'password-protect-page' ); ?></label>
				<?php echo wp_kses_post( $desc ); ?>
			</p>
			<div class="ppwp-shortcodes-wrap">
				<textarea class="ppw-shortcode-sample" id="ppwp-shortcode-protect-file" style="height: auto" rows="4" readonly>[ppwp id="" class="" passwords="password1 password2" whitelisted_roles="administrator, editor" cookie=1 download_limit=1]&#13;&#10;Your protected files&#13;&#10;[/ppwp]</textarea>
				<span id="pppw-copy-shortcode" class="button" onclick="ppwUtils.copy('ppwp-shortcode-protect-file', '<?php echo esc_attr( $message, 'password-protect-page' ); ?>', '<?php echo esc_attr( PPW_PRO_NAME ); ?>')">Copy</span>
			</div>
		</td>
	</tr>
	<?php
}

$swf_link = sprintf(
	'<a target="_blank" rel="noopener" href="%s">display sitewide protection form</a>',
	'https://passwordprotectwp.com/docs/password-protect-entire-wordpress-site/#shortcode'
);
$swf_desc = sprintf(
// translators: %s: Link to documentation.
	esc_html__( 'Use the following shortcode to %1$1s in your content', 'password-protect-page' ),
	$swf_link
);
$message = esc_html__( 'Great! You’ve successfully copied the shortcode to clipboard.', 'password-protect-page' );
?>
<tr>
	<td class="feature-input"><span class="feature-input"></span></td>
	<td>
		<p>
			<label><?php esc_html_e( 'Sitewide Protection Form', 'password-protect-page' ); ?></label>
			<?php echo wp_kses_post( $swf_desc ); ?>
		</p>
		<div class="ppwp-shortcodes-wrap">
			<textarea class="ppw-shortcode-sample" id="ppwp-shortcode-swf" style="height: auto" rows="1" readonly>[ppwp_sitewide]</textarea>
			<span id="pppw-copy-shortcode" class="button" onclick="ppwUtils.copy('ppwp-shortcode-swf', '<?php echo esc_attr( $message, 'password-protect-page' ); ?>', '<?php echo esc_attr( PPW_PRO_NAME ); ?>')">Copy</span>
		</div>
	</td>
</tr>
