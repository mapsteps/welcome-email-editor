<?php
/**
 * User welcome email's additional headers field.
 *
 * @package Welcome_Email_Editor
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Outputting user welcome email's additional_headers field.
 *
 * @param Settings_Module $module The Settings_Module instance.
 */
return function ( $module ) {

	$defaults = $module->defaults;
	$values   = $module->values;
	?>

	<textarea name="weed_settings[user_welcome_email_additional_headers]" id="weed_settings--user_welcome_email_additional_headers" class="regular-text" rows="5" placeholder="<?php echo esc_attr( $defaults['user_welcome_email_additional_headers'] ); ?>"><?php echo esc_html( $values['user_welcome_email_additional_headers'] ); ?></textarea>

	<p class="description">
		<?php _e( 'Add custom http header string for your email sending. E.g:', 'welcome-email-editor' ); ?>
		<br>
		<code>Content-Type: text/plain</code>
		<br>
		<?php _e( 'If you would like to add multiple headers, then add them in multi lines format.', 'welcome-email-editor' ); ?>
	</p>

	<?php

};
