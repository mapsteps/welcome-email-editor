<?php
/**
 * Disable global headers field.
 *
 * @package Welcome_Email_Editor
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Outputting disable global headers field.
 *
 * @param Settings_Module $module The Settings_Module instance.
 */
return function ( $module ) {

	$defaults = $module->defaults;
	$values   = $module->values;
	?>

	<label for="weed_settings--disable_global_headers--no" class="label checkbox-label">
		<?php _e( 'Yes', 'welcome-email-editor' ); ?>
		<input type="checkbox" name="weed_settings[disable_global_headers]" id="weed_settings--disable_global_headers--no" value="1">
		<div class="indicator"></div>
	</label>

	<?php

};
