<?php
/**
 * Mailjet template selector field.
 *
 * @package Welcome_Email_Editor
 */

use Weed\Settings\Settings_Module;
use Weed\Helpers\Mailjet_Helper;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Outputting Mailjet template selector field.
 *
 * @param Settings_Module $module The Settings_Module instance.
 */
return function ( $module ) {

	$values = $module->values;
	$value  = ! empty( $values['mailjet_template_id'] ) ? $values['mailjet_template_id'] : '';

	$mailjet_helper = new Mailjet_Helper();
	$templates = $mailjet_helper->get_mailjet_templates();
	?>

	<select name="weed_settings[mailjet_template_id]" id="weed_settings--mailjet_template_id" class="regular-text">
		<option value=""><?php esc_html_e( 'No Template (Plain Email)', 'welcome-email-editor' ); ?></option>
		<?php if ( ! empty( $templates ) ) : ?>
			<?php foreach ( $templates as $template ) : ?>
				<option value="<?php echo esc_attr( $template['id'] ); ?>" <?php selected( $value, $template['id'] ); ?>>
					<?php echo esc_html( $template['name'] ); ?>
					<?php if ( ! empty( $template['description'] ) ) : ?>
						&mdash; <?php echo esc_html( $template['description'] ); ?>
					<?php endif; ?>
				</option>
			<?php endforeach; ?>
		<?php else : ?>
			<option value="" disabled><?php esc_html_e( 'No templates available (configure API keys first)', 'welcome-email-editor' ); ?></option>
		<?php endif; ?>
	</select>
	<p class="description">
		<?php esc_html_e( 'Select a Mailjet transactional template to use for emails. Leave blank to send plain emails.', 'welcome-email-editor' ); ?>
	</p>

	<?php

};
