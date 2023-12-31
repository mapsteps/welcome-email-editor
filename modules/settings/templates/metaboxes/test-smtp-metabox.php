<?php
/**
 * Metabox template for displaying email test.
 *
 * @package Welcome_Email_Editor
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

$admin_email = get_bloginfo( 'admin_email' );
?>

	<div class="heatbox weed-test-email-metabox">
		<h2>
			<?php esc_html_e( 'Send Test Email', 'welcome-email-editor' ); ?>
		</h2>

		<div class="heatbox-content weed-fields">
			<div class="weed-field">
				<label class="weed-label" for="weed-test-smtp" style="margin-bottom: 10px;">
					<?php esc_html_e( 'Please enter an email address to which the test email should be sent.', 'welcome-email-editor' ); ?>
				</label>
				<input type="email" name="weed_test_smtp" id="weed-test-smtp"
						value="<?php echo esc_attr( $admin_email ); ?>" class="all-options">
			</div>

			<button type="button" class="button button-larger button-primary weed-test-email-button" data-email-type="test_smtp_email">
				<?php esc_html_e( 'Send Test Email (Save First!)', 'welcome-email-editor' ); ?>
			</button>
		</div>
	</div>
<?php
