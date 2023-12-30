<?php
/**
 * SMTP username field.
 *
 * @package Welcome_Email_Editor
 */

use Weed\Settings\Settings_Module;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Outputting SMTP usernama field.
 *
 * @param Settings_Module $module The Settings_Module instance.
 */
return function ( $module ) {

	$values = $module->values;
	$value  = ! empty( $values['smtp_username'] ) ? $values['smtp_username'] : '';
	?>

	<input type="text" name="weed_settings[smtp_username]" id="weed_settings--smtp_username" class="regular-text"
		   value="<?php echo esc_attr( $value ); ?>" placeholder="yourname@yourwebsite.com"/>

	<?php

};