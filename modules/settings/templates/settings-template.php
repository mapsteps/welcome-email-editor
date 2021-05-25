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

					<span class="title">
						<?php _e( 'Welcome Email Editor', 'welcome-email-editor' ); ?>
						<span class="version"><?php echo esc_html( WEED_PLUGIN_VERSION ); ?></span>
					</span>

					<p class="subtitle"><?php _e( 'Change the content, layout, for many of the built-in WordPress emails.', 'welcome-email-editor' ); ?></p>

				</div>

			</div>

		</div>

		<div class="heatbox-container heatbox-container-center">

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
					<?php do_settings_sections( 'weed-admin-welcome-email-settings' ); ?>
				</div>

				<div class="heatbox">
					<?php do_settings_sections( 'weed-forgot-password-email-settings' ); ?>
				</div>

				<div class="heatbox">
					<?php do_settings_sections( 'weed-misc-settings' ); ?>
				</div>

				<?php submit_button( '', 'button button-primary button-larger' ); ?>

			</form>

			<div class="heatbox-divider"></div>

		</div>

		<div class="heatbox-container heatbox-container-wide heatbox-container-center weed-featured-products">

			<h2><?php _e( 'Check out our other free WordPress products!', 'welcome-email-editor' ); ?></h2>

			<ul class="products">
				<li class="heatbox">
					<a href="https://wordpress.org/plugins/swift-control/" target="_blank">
						<img src="<?php echo esc_url( WEED_PLUGIN_URL ); ?>/assets/images/swift-control.jpg">
					</a>
					<div class="heatbox-content">
						<h3><?php _e( 'WP Swift Control', 'welcome-email-editor' ); ?></h3>
						<p class="subheadline"><?php _e( 'Replace the boring WordPress Admin Bar with this!', 'welcome-email-editor' ); ?></p>
						<p><?php _e( 'Swift Control is the plugin that make your clients love WordPress. It drastically improves the user experience when working with WordPress and allows you to replace the boring WordPress admin bar with your own navigation panel.', 'welcome-email-editor' ); ?></p>
						<a href="https://wordpress.org/plugins/swift-control/" target="_blank" class="button"><?php _e( 'View Features', 'welcome-email-editor' ); ?></a>
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
					<a href="https://wordpress.org/plugins/responsive-youtube-vimeo-popup/" target="_blank">
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
