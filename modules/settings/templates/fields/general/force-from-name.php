<?php
/**
 * "Force from name" field.
 *
 * @package Welcome_Email_Editor
 */

use Weed\Settings\Settings_Module;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Outputting "force from name" field.
 *
 * @param Settings_Module $module The Settings_Module instance.
 */
return function ( $module ) {

	$values     = $module->values;
	$is_checked = $values['force_from_name'];

	$force_from_name_description = __(
		'If enabled, the "From Name" setting above will be used for all emails, ignoring values set by other plugins or themes.',
		'welcome-email-editor'
	);
	?>

	<label for="weed_settings--force_from_name" class="toggle-switch">
		<input
			type="checkbox"
			name="weed_settings[force_from_name]"
			id="weed_settings--force_from_name"
			value="1"
			<?php checked( $is_checked, true ); ?>
		/>
		<div class="switch-track">
			<div class="switch-thumb"></div>
		</div>
	</label>

	<p class="description">
		<?php echo esc_html( $force_from_name_description ); ?>
	</p>

	<?php

};
