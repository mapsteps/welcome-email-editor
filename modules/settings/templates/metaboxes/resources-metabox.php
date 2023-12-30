<?php
/**
 * Metabox template for displaying additional resources.
 *
 * @package Welcome_Email_Editor
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );
?>

<div class="heatbox weed-resources-metabox">
	<h2>
		<?php esc_html_e( 'Additional Resources', 'welcome-email-editor' ); ?>
	</h2>

	<div class="heatbox-content">
		<ul>
			<li>
				<a href="https://wordpress.org/plugins/welcome-email-editor/"
				   target="_blank">
					<span class="dashicons dashicons-download"></span>
					<?php esc_html_e( 'Plugin Page', 'welcome-email-editor' ); ?>
				</a>
			</li>

			<li>
				<a href="https://wordpress.org/support/plugin/welcome-email-editor/" target="_blank">
					<span class="dashicons dashicons-sos"></span>
					<?php esc_html_e( 'Support Forum', 'welcome-email-editor' ); ?>
				</a>
			</li>
		</ul>
	</div>
</div>
