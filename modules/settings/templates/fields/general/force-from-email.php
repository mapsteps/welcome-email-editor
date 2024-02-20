<?php
/**
 * "Force from email" field.
 *
 * @package Welcome_Email_Editor
 */

use Weed\Settings\Settings_Module;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Outputting "force from email" field.
 *
 * @param Settings_Module $module The Settings_Module instance.
 */
return function ( $module ) {

	$values     = $module->values;
	$is_checked = $values['force_from_email'];

	$force_from_email_description = __(
		'
			If enabled, the "From Email" setting above will be used for all emails, ignoring values set by other plugins or themes.
			It\'s recommended to enable this if you\'re using SMTP features.
		',
		'welcome-email-editor'
	);
	?>

	<label for="weed_settings--force_from_email" class="toggle-switch">
		<input
			type="checkbox"
			name="weed_settings[force_from_email]"
			id="weed_settings--force_from_email"
			value="1"
			<?php checked( $is_checked, true ); ?>
		/>
		<div class="switch-track">
			<div class="switch-thumb"></div>
		</div>
	</label>

	<p class="description">
		<?php echo esc_html( $force_from_email_description ); ?>
	</p>

	<?php

};
