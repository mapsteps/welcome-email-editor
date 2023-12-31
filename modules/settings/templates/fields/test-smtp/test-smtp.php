<?php
/**
 * Test SMTP field.
 *
 * @package Welcome_Email_Editor
 */

use Weed\Settings\Settings_Module;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Outputting test SMTP field.
 *
 * @param Settings_Module $module The Settings_Module instance.
 */
return function ( $module ) {

	$values      = $module->values;
	$admin_email = get_bloginfo( 'admin_email' );
	$value       = ! empty( $values['test_smtp_recipient_email'] ) ? $values['test_smtp_recipient_email'] : $admin_email;
	?>

	<div class="weed-fields">
		<div class="weed-field">
			<label class="weed-label" for="weed_settings--test_smtp_recipient_email" style="margin-bottom: 10px;">
				<?php esc_html_e( 'Please enter an email address to which the test email should be sent.', 'welcome-email-editor' ); ?>
			</label>

			<input type="text" name="weed_settings[test_smtp_recipient_email]"
					id="weed_settings--test_smtp_recipient_email" class="all-options"
					value="<?php echo esc_attr( $value ); ?>" placeholder="<?php echo esc_attr( $admin_email ); ?>"/>
		</div>

		<button type="button" class="button button-larger button-primary weed-test-email-button"
				data-email-type="test_smtp_email">
			<?php esc_html_e( 'Send Test Email (Save First!)', 'welcome-email-editor' ); ?>
		</button>
	</div>

	<?php

};
