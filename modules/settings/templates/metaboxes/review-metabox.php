<?php
/**
 * Metabox template for displaying review link.
 *
 * @package Welcome_Email_Editor
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );
?>

<div class="heatbox weed-review-metabox">
	<h2>
		<?php esc_html_e( 'Leave us a Review', 'welcome-email-editor' ); ?>
	</h2>

	<div class="heatbox-content">
		<p>
			<?php esc_html_e( 'Do you enjoy Swift SMTP? Leave a review in the WordPress directory and spread the word!', 'welcome-email-editor' ); ?>
		</p>
		<a href="https://wordpress.org/support/plugin/welcome-email-editor/reviews/#new-post" target="_blank"
		   class="button">
			<?php esc_html_e( 'Leave a Review', 'welcome-email-editor' ); ?>
		</a>
	</div>
</div>
