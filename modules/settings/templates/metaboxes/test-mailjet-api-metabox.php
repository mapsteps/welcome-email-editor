<?php
/**
 * Metabox template for displaying Mailjet API email test.
 *
 * @package Welcome_Email_Editor
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

$admin_email = get_bloginfo( 'admin_email' );
?>

	<div class="heatbox weed-test-mailjet-api-metabox" data-show-when-mailer-type="mailjet_api">
		<?php do_settings_sections( 'weed-mailjet-api-test-settings' ); ?>
	</div>
<?php
