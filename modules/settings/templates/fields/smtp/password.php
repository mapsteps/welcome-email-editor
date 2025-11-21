<?php
/**
 * SMTP password field.
 *
 * @package Welcome_Email_Editor
 */

use Weed\Settings\Settings_Module;
use Weed\Vars;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Outputting SMTP usernama field.
 *
 * @param Settings_Module $module The Settings_Module instance.
 */
return function ( $module ) {

	$values = Vars::get( 'values' );
	$value  = ! empty( $values['smtp_password'] ) ? $values['smtp_password'] : '';
	?>

	<div data-show-when-mailer-type="smtp">
		<input type="password" name="weed_settings[smtp_password]" id="weed_settings--smtp_password" class="regular-text"
				value="<?php echo esc_attr( $value ); ?>" placeholder="&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;"/>
	</div>

	<?php

};
