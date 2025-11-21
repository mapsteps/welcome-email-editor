<?php
/**
 * SMTP username field.
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
	$value  = ! empty( $values['smtp_username'] ) ? $values['smtp_username'] : '';
	?>

	<div data-show-when-mailer-type="smtp">
		<input type="text" name="weed_settings[smtp_username]" id="weed_settings--smtp_username" class="regular-text"
				value="<?php echo esc_attr( $value ); ?>" placeholder="yourname@yourwebsite.com"/>
	</div>

	<?php

};
