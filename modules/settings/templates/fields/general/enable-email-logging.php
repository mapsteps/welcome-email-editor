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
		'If checked, all emails sent from your site will be recorded and shown under the "Email logs" sub menu.',
		'welcome-email-editor'
	);
	?>

	<label for="weed_settings[enable_email_logging]" class="label checkbox-label">
		&nbsp;
		<input type="checkbox" name="weed_settings[enable_email_logging]" id="weed_settings[enable_email_logging]" value="1" <?php checked( $is_checked, 1 ); ?>>
		<div class="indicator"></div>
	</label>

	<p class="description">
		<?php echo esc_html( $enable_email_logging_description ); ?>
	</p>

	<?php

};