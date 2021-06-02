<?php
/**
 * From email field.
 *
 * @package Welcome_Email_Editor
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Outputting from email field.
 *
 * @param Settings_Module $module The Settings_Module instance.
 */
return function ( $module ) {

	$defaults = $module->defaults;
	$values   = $module->values;
	?>

	<input type="text" name="weed_settings[from_email]" id="weed_settings--from_email" class="regular-text" value="<?php echo esc_attr( $values['from_email'] ); ?>" placeholder="[admin_email]" />

	<?php

};
