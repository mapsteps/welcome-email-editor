<?php
/**
 * SMTP encryption field.
 *
 * @package Welcome_Email_Editor
 */

use Weed\Settings\Settings_Module;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Outputting SMTP encryption field.
 *
 * @param Settings_Module $module The Settings_Module instance.
 */
return function ( $module ) {

	$values = $module->values;
	$value  = ! empty( $values['smtp_encryption'] ) ? $values['smtp_encryption'] : '';
	?>

	<div class="weed-fields weed-radio-fields">
		<label for="weed_settings--smtp_encryption-none" class="label radio-label">
			<?php esc_html_e( 'None', 'welcome-email-editor' ); ?>
			<input type="radio" name="weed_settings[smtp_encryption]" id="weed_settings--smtp_encryption-none"
					value="" <?php checked( $value, '' ); ?>/>
			<div class="indicator"></div>
		</label>

		<label for="weed_settings--smtp_encryption-ssl" class="label radio-label">
			<?php esc_html_e( 'SSL', 'welcome-email-editor' ); ?>
			<input type="radio" name="weed_settings[smtp_encryption]" id="weed_settings--smtp_encryption-ssl"
					value="ssl" <?php checked( $value, 'ssl' ); ?>/>
			<div class="indicator"></div>
		</label>

		<label for="weed_settings--smtp_encryption-tls" class="label radio-label">
			<?php esc_html_e( 'TLS', 'welcome-email-editor' ); ?>
			<input type="radio" name="weed_settings[smtp_encryption]" id="weed_settings--smtp_encryption-tls"
					value="tls" <?php checked( $value, 'tls' ); ?>/>
			<div class="indicator"></div>
		</label>
	</div>

	<?php

};
