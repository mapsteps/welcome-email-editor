<?php
/**
 * Enable field.
 *
 * @package Welcome_Email_Editor
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Outputting enable field.
 *
 * @param Settings_Module $module The Settings_Module instance.
 */
return function ( $module ) {

	$settings   = $module->settings;
	$is_checked = isset( $settings['enable'] ) ? 1 : 0;
	?>

	<label for="weed_settings--enable" class="label checkbox-label">
		<?php _e( 'Yes', 'welcome-email-editor' ); ?>
		<input type="checkbox" name="weed_settings[enable]" id="weed_settings--enable" value="1" <?php checked( $is_checked, 1 ); ?>>
		<div class="indicator"></div>
	</label>

	<p class="description">
		<?php _e( "Your customizations below wouldn't be implemented unless you enable this.", 'welcome-email-editor' ); ?>
	</p>

	<?php

};
