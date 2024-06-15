<?php
/**
 * Metabox template for displaying template tags.
 *
 * @package Welcome_Email_Editor
 */
?>

<div class="heatbox tags-heatbox">
	<h2>
		<?php esc_html_e( 'Template tags', 'welcome-email-editor' ); ?>
		<span class="action-status">📋 Copied</span>
	</h2>

	<div class="heatbox-content">
		<p>
			<?php esc_html_e( 'Use the template tags below in any fields to output certain information.', 'welcome-email-editor' ); ?>
			<br><strong><?php esc_html_e( '(Click to copy)', 'welcome-email-editor' ); ?></strong>
		</p>
		<div class="tags-wrapper">
			<code>[site_url]</code> <code>[login_url]</code> <code>[reset_pass_url]</code> <code>[user_email]</code>
			<code>[user_login]</code> <code>[user_id]</code> <code>[first_name]</code>
			<code>[last_name]</code> <code>[blog_name]</code> <code>[admin_email]</code> <code>[custom_fields]</code>
			<code>[date]</code> <code>[time]</code> <code>[user_ip]</code>
			<code>[not_logged_in][/not_logged_in]</code> <code>[logged_in][/logged_in]</code>
		</div>
	</div>
</div>

<?php if ( defined( 'BP_PLUGIN_URL' ) ) : ?>
	<div class="heatbox tags-heatbox">
		<h2>
			<?php esc_html_e( 'BuddyPress Template Tags', 'welcome-email-editor' ); ?>
		</h2>

		<div class="heatbox-content">
			<p>
				<?php _e( 'Use the template tag below in your <strong>Admin Email</strong>.', 'welcome-email-editor' ); ?>
			</p>
			<p>
				<?php esc_html_e( 'This will print BuddyPress custom fields.', 'welcome-email-editor' ); ?>
			</p>
			<p class="tags-wrapper">
				<code>[bp_custom_fields]</code>
			</p>
		</div>
	</div>
<?php endif; ?>

<div class="heatbox tags-heatbox">
	<h2>
		<?php esc_html_e( 'Debugging', 'welcome-email-editor' ); ?>
	</h2>

	<div class="heatbox-content">
		<p>
			<?php _e( 'Use the template tag below in your <strong>Admin Email</strong> for debugging.', 'welcome-email-editor' ); ?>
		</p>
		<p>
			<?php esc_html_e( 'This will print $_REQUEST.', 'welcome-email-editor' ); ?>
		</p>
		<p class="tags-wrapper">
			<code>[post_data]</code>
		</p>
	</div>
</div>

<div class="heatbox tags-heatbox">
	<h2>
		<?php esc_html_e( 'Test Emails', 'welcome-email-editor' ); ?>
	</h2>

	<div class="heatbox-content">
		<p>
			<?php _e( 'Sending a test email will send the respective welcome email to the current user (you).', 'welcome-email-editor' ); ?>
		</p>
		<p>
			<?php _e( 'Before sending a test email, please save your settings.', 'welcome-email-editor' ); ?>
		</p>
		<p>
			<?php _e( 'If you haven\'t received the test email, please check your spam folder and ensure the SMTP settings are configured correctly.', 'welcome-email-editor' ); ?>
		</p>
	</div>
</div>
