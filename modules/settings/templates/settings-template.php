<?php
/**
 * Settings page template.
 *
 * @package Welcome_Email_Editor
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {
	?>

	<div class="wrap heatbox-wrap weed-settings-page">

		<div class="heatbox-header heatbox-margin-bottom">

			<div class="heatbox-container heatbox-container-center">

				<div class="logo-container">

					<div>
						<span class="title">
							<?php _e( 'Welcome Email Editor', 'welcome-email-editor' ); ?>
							<span class="version"><?php echo esc_html( WEED_PLUGIN_VERSION ); ?></span>
						</span>
						<p class="subtitle"><?php _e( 'Change WordPress\' Welcome & Reset Password Emails', 'welcome-email-editor' ); ?></p>
					</div>

					<div>
						<img src="<?php echo esc_url( WEED_PLUGIN_URL ); ?>/assets/images/welcome-email-editor-logo.png">
					</div>

				</div>

			</div>

		</div>

		<div class="heatbox-container heatbox-container-center heatbox-column-container">

			<div class="heatbox-main">
				<!-- Faking H1 tag to place admin notices -->
				<h1 style="display: none;"></h1>

				<form method="post" action="options.php" class="weed-settings-form">

					<?php settings_fields( 'weed-settings-group' ); ?>

					<div class="heatbox">
						<?php do_settings_sections( 'weed-general-settings' ); ?>
					</div>

					<div class="heatbox">
						<?php do_settings_sections( 'weed-user-welcome-email-settings' ); ?>
					</div>

					<div class="heatbox">
						<?php do_settings_sections( 'weed-admin-new-user-notif-email-settings' ); ?>
					</div>

					<div class="heatbox">
						<?php do_settings_sections( 'weed-reset-password-email-settings' ); ?>
					</div>

					<div class="heatbox">
						<?php do_settings_sections( 'weed-misc-settings' ); ?>
					</div>

					<div class="udb-buttons">
						<?php
						submit_button( '', 'button button-primary button-larger', 'submit', false );

						$current_url = site_url() . $_SERVER['REQUEST_URI'];

						$reset_settings_url = add_query_arg(
							array(
								'action'       => 'weed_reset_settings',
								'nonce'        => wp_create_nonce( WEED_PLUGIN_DIR ),
								'http_referer' => $current_url,
							),
							$current_url
						);
						?>

						<a href="<?php echo esc_url( $reset_settings_url ); ?>" class="button button-larger weed-reset-button weed-reset-settings-button">
							<?php _e( 'Reset Settings', 'welcome-email-editor' ); ?>
						</a>
					</div>

				</form>
			</div>

			<div class="heatbox-sidebar">
				<div class="heatbox weed-tags-metabox">
					<h2><?php _e( 'Template Tags', 'welcome-email-editor' ); ?></h2>
					<div class="heatbox-content">
						<p><?php _e( 'Use the template tags below in any fields to output certain information.', 'welcome-email-editor' ); ?></p>
						<p class="tags-wrapper">
							<code>[site_url]</code>, <code>[login_url]</code>, <code>[reset_pass_url]</code>, <code>[user_email]</code>, <code>[user_login]</code>, <code>[user_id]</code>, <code>[first_name]</code>, <code>[last_name]</code>, <code>[blog_name]</code>, <code>[admin_email]</code>, <code>[custom_fields]</code>, <code>[date]</code>, <code>[time]</code>
						</p>
					</div>
				</div>

				<?php if ( defined( 'BP_PLUGIN_URL' ) ) : ?>
				<div class="heatbox weed-tags-metabox">
					<h2><?php _e( 'BuddyPress Template Tags', 'welcome-email-editor' ); ?></h2>
					<div class="heatbox-content">
						<p><?php _e( 'Use the template tag below in your <strong>Admin Email</strong>.', 'welcome-email-editor' ); ?></p>
						<p><?php _e( 'This will print BuddyPress custom fields.', 'welcome-email-editor' ); ?></p>
						<p class="tags-wrapper">
							<code>[bp_custom_fields]</code>
						</p>
					</div>
				</div>
				<?php endif; ?>

				<div class="heatbox weed-tags-metabox">
					<h2><?php _e( 'Debugging', 'welcome-email-editor' ); ?></h2>
					<div class="heatbox-content">
						<p><?php _e( 'Use the template tag below in your <strong>Admin Email</strong> for debugging.', 'welcome-email-editor' ); ?></p>
						<p><?php _e( 'This will print $_REQUEST.', 'welcome-email-editor' ); ?></p>
						<p class="tags-wrapper">
							<code>[post_data]</code>
						</p>
					</div>
				</div>
			</div>

		</div>

		<div class="heatbox-container heatbox-container-wide heatbox-container-center weed-featured-products">
			<div class="heatbox-divider"></div>

			<h2><?php _e( 'Check out our other free WordPress products!', 'welcome-email-editor' ); ?></h2>

			<ul class="products">
				<li class="heatbox">
					<a href="https://wordpress.org/plugins/better-admin-bar/" target="_blank">
						<img src="<?php echo esc_url( WEED_PLUGIN_URL ); ?>/assets/images/swift-control.jpg">
					</a>
					<div class="heatbox-content">
						<h3><?php _e( 'Better Admin Bar', 'welcome-email-editor' ); ?></h3>
						<p class="subheadline"><?php _e( 'Replace the boring WordPress Admin Bar with this!', 'welcome-email-editor' ); ?></p>
						<p><?php _e( 'Better Admin Bar is the plugin that make your clients love WordPress. It drastically improves the user experience when working with WordPress and allows you to replace the boring WordPress admin bar with your own navigation panel.', 'welcome-email-editor' ); ?></p>
						<a href="https://wordpress.org/plugins/better-admin-bar/" target="_blank" class="button"><?php _e( 'View Features', 'welcome-email-editor' ); ?></a>
					</div>
				</li>
				<li class="heatbox">
					<a href="https://wordpress.org/plugins/ultimate-dashboard/" target="_blank">
						<img src="<?php echo esc_url( WEED_PLUGIN_URL ); ?>/assets/images/ultimate-dashboard.jpg">
					</a>
					<div class="heatbox-content">
						<h3><?php _e( 'Ultimate Dashboard', 'welcome-email-editor' ); ?></h3>
						<p class="subheadline"><?php _e( 'The #1 plugin to customize your WordPress dashboard.', 'welcome-email-editor' ); ?></p>
						<p><?php _e( 'Ultimate Dashboard is a clean & lightweight plugin that was made to optimize the user experience for clients inside the WordPress admin area.', 'welcome-email-editor' ); ?></p>
						<a href="https://wordpress.org/plugins/ultimate-dashboard/" target="_blank" class="button"><?php _e( 'View Features', 'welcome-email-editor' ); ?></a>
					</div>
				</li>
				<li class="heatbox">
					<a href="https://wordpress.org/themes/page-builder-framework/" target="_blank">
						<img src="<?php echo esc_url( WEED_PLUGIN_URL ); ?>/assets/images/page-builder-framework.jpg">
					</a>
					<div class="heatbox-content">
						<h3><?php _e( 'Page Builder Framework', 'welcome-email-editor' ); ?></h3>
						<p class="subheadline"><?php _e( 'A modern, fast & minimalistic theme designed for the new WordPress Era.', 'welcome-email-editor' ); ?></p>
						<p><?php _e( 'The theme was designed specifically to work with WordPress page builders, like Elementor, Beaver Builder & Brizy.', 'welcome-email-editor' ); ?></p>
						<a href="https://wordpress.org/themes/page-builder-framework/" target="_blank" class="button"><?php _e( 'View Features', 'welcome-email-editor' ); ?></a>
					</div>
				</li>
			</ul>

			<p class="credit"><?php _e( 'Made with ❤ in Aschaffenburg, Germany', 'welcome-email-editor' ); ?></p>

		</div>

	</div>

	<?php
};
