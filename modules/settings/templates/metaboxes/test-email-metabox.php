<?php
/**
 * Metabox template for displaying email test.
 *
 * @package Welcome_Email_Editor
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );
?>

	<div class="heatbox weed-test-email-metabox">
		<h2>
			<?php esc_html_e( 'Send a Test Email', 'page-builder-framework' ); ?>
		</h2>

		<div class="heatbox-content weed-fields">
			<div class="weed-field">
				<label class="weed-label" for="weed-test-email" style="margin-bottom: 10px;">
					<?php esc_html_e( 'Enter email address where test email will be sent.', 'welcome-email-editor' ); ?>
				</label>
				<input type="email" name="weed_test_email" id="weed-test-email" class="all-options">
			</div>

			<button type="button" class="button button-primary">
				<?php esc_html_e( 'Send', 'welcome-email-editor' ); ?>
			</button>
		</div>
	</div>
<?php
