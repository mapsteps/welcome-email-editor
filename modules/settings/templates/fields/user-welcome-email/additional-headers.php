<?php
/**
 * User welcome email's additional headers field.
 *
 * @package Welcome_Email_Editor
 */

use Weed\Settings\Settings_Module;

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

	<textarea name="weed_settings[user_welcome_email_additional_headers]"
			  id="weed_settings--user_welcome_email_additional_headers" class="regular-text" rows="5"
			  placeholder="<?php echo esc_attr( $defaults['user_welcome_email_additional_headers'] ); ?>"><?php echo esc_html( $values['user_welcome_email_additional_headers'] ); ?></textarea>

	<p class="description">
		<?php _e( 'Add custom HTTP headers to your email, such as: <code>Content-Type: text/plain</code>.', 'welcome-email-editor' ); ?><br>
		<?php _e( 'Please use one line per HTTP header.', 'welcome-email-editor' ); ?>
	</p>

	<?php

};
