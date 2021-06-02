<?php
/**
 * From name field.
 *
 * @package Welcome_Email_Editor
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Outputting from name field.
 *
 * @param Settings_Module $module The Settings_Module instance.
 */
return function ( $module ) {

	$defaults = $module->defaults;
	$values   = $module->values;
	?>

	<input type="text" name="weed_settings[from_name]" id="weed_settings--from_name" class="regular-text" value="<?php echo esc_attr( $values['from_name'] ); ?>" placeholder="WordPress" />

	<?php

};
