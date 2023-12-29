<?php
/**
 * SMTP post field.
 *
 * @package Welcome_Email_Editor
 */

use Weed\Settings\Settings_Module;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Outputting SMTP host field.
 *
 * @param Settings_Module $module The Settings_Module instance.
 */
return function ( $module ) {

	$values = $module->values;
	$value  = ! empty( $values['smtp_port'] ) ? $values['smtp_port'] : '';
	?>

	<input type="text" name="weed_settings[smtp_port]" id="weed_settings--smtp_port" class="small"
		   value="<?php echo esc_attr( $value ); ?>" placeholder="587"/>

	<?php

};
