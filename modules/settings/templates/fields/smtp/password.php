<?php
/**
 * Mailjet sender email field.
 *
 * @package Welcome_Email_Editor
 */

use Weed\Settings\Settings_Module;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Outputting Mailjet sender email field.
 *
 * @param Settings_Module $module The Settings_Module instance.
 */
return function ( $module ) {

	$values = $module->values;
	$value  = ! empty( $values['mailjet_sender_email'] ) ? $values['mailjet_sender_email'] : get_option( 'admin_email' );
	?>

	<input type="email" name="weed_settings[mailjet_sender_email]" id="weed_settings--mailjet_sender_email" class="regular-text"
		   value="<?php echo esc_attr( $value ); ?>" placeholder="sender@yourwebsite.com"/>
	<p class="description">
		<?php esc_html_e( 'The email address that will appear as the sender. This must be a verified email in your Mailjet account.', 'welcome-email-editor' ); ?>
	</p>

	<?php

};
