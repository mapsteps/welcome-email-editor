<?php
/**
 * "Enable email logging" field.
 *
 * @package Welcome_Email_Editor
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $module ) {

	$settings   = $module->settings;
	$is_checked = isset( $settings['enable_email_logging'] ) ? 1 : 0;

	$enable_email_logging_description = __(
		'If enabled, all outgoing emails will be recorded.',
		'welcome-email-editor'
	);
	?>

	<label for="weed_settings--enable_email_logging" class="toggle-switch">
		<input
			type="checkbox"
			name="weed_settings[enable_email_logging]"
			id="weed_settings--enable_email_logging"
			value="1"
			<?php checked( $is_checked, true ); ?>
		/>
		<div class="switch-track">
			<div class="switch-thumb"></div>
		</div>
	</label>

	<p class="description">
		<?php echo esc_html( $enable_email_logging_description ); ?>
	</p>

	<?php

};