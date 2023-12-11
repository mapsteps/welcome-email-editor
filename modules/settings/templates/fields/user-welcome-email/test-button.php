<?php
/**
 * User welcome email's test button field.
 *
 * @package Welcome_Email_Editor
 */

use Weed\Settings\Settings_Module;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Outputting user welcome email's test button field.
 *
 * @param Settings_Module $module The Settings_Module instance.
 */
return function ( $module ) {

	?>

	<button type="button" class="button button-primary weed-test-email-button" data-email-type="user_welcome_email">
		<?php _e( 'Send a test email for current user (save first!)', 'welcome-email-editor' ); ?>
	</button>

	<?php

};
