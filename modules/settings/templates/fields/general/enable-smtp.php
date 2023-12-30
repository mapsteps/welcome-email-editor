<?php
/**
 * Content type field.
 *
 * @package Welcome_Email_Editor
 */

use Weed\Settings\Settings_Module;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Outputting "enable SMTP" field.
 *
 * @param Settings_Module $module The Settings_Module instance.
 */
return function ( $module ) {

	$values     = $module->values;
	$is_checked = ! empty( $values['enable_smtp'] );
	?>

	<label for="weed_settings--enable_smtp" class="toggle-switch">
		<input
			type="checkbox"
			name="weed_settings[enable_smtp]"
			id="weed_settings--enable_smtp"
			value="1"
			<?php checked( $is_checked, true ); ?>
		/>
		<span class="switch-track">
			<span class="switch-thumb"></span>
		</span>
	</label>

	<?php

};
