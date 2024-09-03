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

		<div class="heatbox-header heatbox-has-tab-nav heatbox-margin-bottom">

			<div class="heatbox-container heatbox-container-center">

				<div class="logo-container">

					<div>
						<span class="title">
							<?php esc_html_e( 'Swift SMTP', 'welcome-email-editor' ); ?>
							<span class="version"><?php echo esc_html( WEED_PLUGIN_VERSION ); ?></span>
						</span>
						<p class="subtitle">
							<?php esc_html_e( 'A free & simple SMTP Plugin for WordPress.', 'welcome-email-editor' ); ?>
						</p>
					</div>

					<div>
						<img src="<?php echo esc_url( WEED_PLUGIN_URL ); ?>/assets/images/swift-smtp-logo.png"
							alt="Swift SMTP">
					</div>

				</div>

				<nav>
					<ul class="heatbox-tab-nav">
						<li class="heatbox-tab-nav-item">
							<a href="#smtp">
								<?php esc_html_e( 'SMTP Settings', 'welcome-email-editor' ); ?>
							</a>
						</li>
						<li class="heatbox-tab-nav-item">
							<a href="#email-logging">
								<?php esc_html_e( 'Email Logging', 'welcome-email-editor' ); ?>
							</a>
						</li>
						<li class="heatbox-tab-nav-item">
							<a href="#welcome-email">
								<?php esc_html_e( 'Welcome Email Editor', 'welcome-email-editor' ); ?>
							</a>
						</li>						
						<li class="heatbox-tab-nav-item">
							<a href="#misc">
								<?php esc_html_e( 'Other', 'welcome-email-editor' ); ?>
							</a>
						</li>
					</ul>
				</nav>

			</div>

		</div>

		<?php
		$current_url = site_url() . $_SERVER['REQUEST_URI'];
		?>

		<form method="post" action="options.php" class="weed-settings-form">
			<div class="heatbox-container heatbox-container-center heatbox-column-container">

				<div class="heatbox-main">
					<!-- Faking H1 tag to place admin notices -->
					<h1 style="display: none;"></h1>

					<?php settings_fields( 'weed-settings-group' ); ?>

					<div class="heatbox-admin-panel" data-show-when-tab="smtp">
						<div class="heatbox">
							<?php do_settings_sections( 'weed-general-settings' ); ?>
						</div>

						<div class="heatbox">
							<?php do_settings_sections( 'weed-smtp-settings' ); ?>
						</div>
					</div>

					<div class="heatbox-admin-panel" data-show-when-tab="email-logging">
						<div class="heatbox">
							<?php do_settings_sections( 'weed-enable-logging-settings' ); ?>
						</div>
					</div>
					
					<div class="heatbox-admin-panel" data-show-when-tab="welcome-email">
						<div class="heatbox">
							<?php do_settings_sections( 'weed-user-welcome-email-settings' ); ?>
						</div>

						<div class="heatbox">
							<?php do_settings_sections( 'weed-admin-new-user-notif-email-settings' ); ?>
						</div>

						<div class="heatbox">
							<?php do_settings_sections( 'weed-reset-password-email-settings' ); ?>
						</div>
					</div>

					<div class="heatbox-admin-panel" data-show-when-tab="misc">
						<div class="heatbox">
							<?php do_settings_sections( 'weed-misc-settings' ); ?>
						</div>
					</div>

					<div class="weed-buttons">
						<?php
						submit_button( '', 'button button-primary button-larger', 'submit', false );

						$reset_settings_url = add_query_arg(
							array(
								'action'       => 'weed_reset_settings',
								'nonce'        => wp_create_nonce( WEED_PLUGIN_DIR ),
								'http_referer' => $current_url,
							),
							$current_url
						);
						?>

						<a href="<?php echo esc_url( $reset_settings_url ); ?>"
							class="button button-larger weed-reset-button weed-reset-settings-button">
							<?php esc_html_e( 'Reset Settings', 'welcome-email-editor' ); ?>
						</a>
					</div>

				</div>

				<div class="heatbox-sidebar">
					<div class="heatbox-admin-panel" data-show-when-tab="welcome-email">
						<?php require __DIR__ . '/metaboxes/template-tags-metabox.php'; ?>
					</div>

					<div class="heatbox-admin-panel" data-show-when-tab="smtp">
						<?php
						require __DIR__ . '/metaboxes/test-smtp-metabox.php';
						require __DIR__ . '/metaboxes/review-metabox.php';
						?>
					</div>

					<div class="heatbox-admin-panel" data-show-when-tab="misc">
						<?php require __DIR__ . '/metaboxes/review-metabox.php'; ?>
					</div>
				</div>

			</div>
		</form>

		<div class="heatbox-container heatbox-container-wide heatbox-container-center weed-featured-products">
			<div class="heatbox-divider"></div>

			<h2><?php esc_html_e( 'Check out our other free WordPress products!', 'welcome-email-editor' ); ?></h2>

			<ul class="products">
				<li class="heatbox">
					<a href="https://wordpress.org/plugins/better-admin-bar/" target="_blank">
						<img src="<?php echo esc_url( WEED_PLUGIN_URL ); ?>/assets/images/swift-control.jpg">
					</a>
					<div class="heatbox-content">
						<h3>
							<?php esc_html_e( 'Better Admin Bar', 'welcome-email-editor' ); ?>
						</h3>
						<p class="subheadline">
							<?php esc_html_e( 'Replace the boring WordPress Admin Bar with this!', 'welcome-email-editor' ); ?>
						</p>
						<p>
							<?php esc_html_e( 'Better Admin Bar is the plugin that make your clients love WordPress. It drastically improves the user experience when working with WordPress and allows you to replace the boring WordPress admin bar with your own navigation panel.', 'welcome-email-editor' ); ?>
						</p>
						<a href="https://wordpress.org/plugins/better-admin-bar/" target="_blank" class="button">
							<?php esc_html_e( 'View Features', 'welcome-email-editor' ); ?>
						</a>
					</div>
				</li>
				<li class="heatbox">
					<a href="https://wordpress.org/plugins/ultimate-dashboard/" target="_blank">
						<img src="<?php echo esc_url( WEED_PLUGIN_URL ); ?>/assets/images/ultimate-dashboard.jpg">
					</a>
					<div class="heatbox-content">
						<h3>
							<?php esc_html_e( 'Ultimate Dashboard', 'welcome-email-editor' ); ?>
						</h3>
						<p class="subheadline">
							<?php esc_html_e( 'The #1 plugin to customize your WordPress dashboard.', 'welcome-email-editor' ); ?>
						</p>
						<p>
							<?php esc_html_e( 'Ultimate Dashboard is a clean & lightweight plugin that was made to optimize the user experience for clients inside the WordPress admin area.', 'welcome-email-editor' ); ?>
						</p>
						<a href="https://wordpress.org/plugins/ultimate-dashboard/" target="_blank" class="button">
							<?php esc_html_e( 'View Features', 'welcome-email-editor' ); ?>
						</a>
					</div>
				</li>
				<li class="heatbox">
					<a href="https://wordpress.org/themes/page-builder-framework/" target="_blank">
						<img src="<?php echo esc_url( WEED_PLUGIN_URL ); ?>/assets/images/page-builder-framework.jpg">
					</a>
					<div class="heatbox-content">
						<h3>
							<?php esc_html_e( 'Page Builder Framework', 'welcome-email-editor' ); ?>
						</h3>
						<p class="subheadline">
							<?php esc_html_e( 'A modern, fast & minimalistic theme designed for the new WordPress Era.', 'welcome-email-editor' ); ?>
						</p>
						<p>
							<?php esc_html_e( 'The theme was designed specifically to work with WordPress page builders, like Elementor, Beaver Builder & Brizy.', 'welcome-email-editor' ); ?>
						</p>
						<a href="https://wordpress.org/themes/page-builder-framework/" target="_blank" class="button">
							<?php esc_html_e( 'View Features', 'welcome-email-editor' ); ?>
						</a>
					</div>
				</li>
			</ul>

			<p class="credit">
				<?php esc_html_e( 'Made with ❤ in Torsby, Sweden', 'welcome-email-editor' ); ?>
			</p>

		</div>

	</div>

	<?php
};
