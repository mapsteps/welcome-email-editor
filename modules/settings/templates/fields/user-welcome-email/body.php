<?php
/**
 * User welcome email's body field.
 *
 * @package Welcome_Email_Editor
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Outputting user welcome email's body field.
 *
 * @param Settings_Module $module The Settings_Module instance.
 */
return function ( $module ) {

	$defaults = $module->defaults;
	$values   = $module->values;
	?>

	<textarea name="weed_settings[user_welcome_email_body]" id="weed_settings--user_welcome_email_body" class="large-text" rows="8" placeholder="<?php echo esc_attr( $defaults['user_welcome_email_body'] ); ?>"><?php echo esc_html( $values['user_welcome_email_body'] ); ?></textarea>

	<?php

};
