<?php
/**
 * Metabox template for displaying email test.
 *
 * @package Welcome_Email_Editor
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

$admin_email = get_bloginfo( 'admin_email' );
?>

	<div class="heatbox weed-test-smtp-metabox">
		<?php do_settings_sections( 'weed-test-smtp-settings' ); ?>
	</div>
<?php
