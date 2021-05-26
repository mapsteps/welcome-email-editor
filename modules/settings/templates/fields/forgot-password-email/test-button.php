<?php
/**
 * User welcome email's test button field.
 *
 * @package Welcome_Email_Editor
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Outputting user welcome email's test button field.
 *
 * @param Settings_Module $module The Settings_Module instance.
 */
return function ( $module ) {

	$defaults = $module->defaults;
	$values   = $module->values;
	?>

	<button type="button" class="button button-primary">
		<?php _e( 'Send a test email for current user', 'welcome-email-editor' ); ?>
	</button>

	<?php

};
