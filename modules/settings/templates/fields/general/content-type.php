<?php
/**
 * Content type field.
 *
 * @package Welcome_Email_Editor
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Outputting content type field.
 *
 * @param Settings_Module $module The Settings_Module instance.
 */
return function ( $module ) {

	$values = $module->values;
	?>

	<select name="weed_settings[content_type]" id="weed_settings--content_type" class="regular-text">
		<option value="html" <?php selected( $values['content_type'], 'html' ); ?>><?php _e( 'HTML', 'welcome-email-editor' ); ?></option>
		<option value="text" <?php selected( $values['content_type'], 'text' ); ?>><?php _e( 'Plain Text', 'welcome-email-editor' ); ?></option>
	</select>

	<?php

};
