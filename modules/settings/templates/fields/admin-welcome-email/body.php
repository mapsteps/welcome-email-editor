<?php
/**
 * Admin welcome email's body field.
 *
 * @package Welcome_Email_Editor
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Outputting admin welcome email's body field.
 *
 * @param Settings_Module $module The Settings_Module instance.
 */
return function ( $module ) {

	$defaults = $module->defaults;
	$values   = $module->values;
	?>

	<textarea name="weed_settings[admin_welcome_email_body]" id="weed_settings--admin_welcome_email_body" class="large-text" rows="5" value="<?php echo esc_attr( $values['admin_welcome_email_body'] ); ?>" placeholder="<?php echo esc_attr( $defaults['admin_welcome_email_body'] ); ?>"><?php echo esc_html( $defaults['admin_welcome_email_body'] ); ?></textarea>

	<?php

};
