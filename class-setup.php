<?php
/**
 * Setup Welcome Email Editor plugin.
 *
 * @package Welcome_Email_Editor
 */

namespace Weed;

use Weed\Helpers\Content_Helper;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Class to set up Welcome Email Editor plugin.
 */
class Setup {
	/**
	 * The class instance.
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Init the class setup.
	 */
	public static function init() {

		$class = self::get_instance();

		add_action( 'plugins_loaded', array( $class, 'setup' ) );

	}

	/**
	 * Get instance of the class.
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Set up the class.
	 */
	public function setup() {

		add_action( 'updated_option_weed_settings', [ $this, 'handle_updated_option' ], 10, 3 );

		add_action( 'init', array( $this, 'register_action_links' ) );
		add_filter( 'admin_body_class', array( $this, 'admin_body_class' ) );

		$this->load_modules();

		register_deactivation_hook( WEED_PLUGIN_BASENAME, array( $this, 'deactivation' ), 20 );

	}

	/**
	 * Provide data for the plugin.
	 * This is optimal strategy to only get_option once across modules.
	 */
	public function set_data() {
		$settings = get_option( 'weed_settings', array() );
		$defaults = array(
			// General settings.
			'from_email'                                   => '',
			'force_from_email'                             => false,
			'from_name'                                    => '',
			'force_from_name'                              => false,
			'content_type'                                 => 'text',
			// Test SMTP settings.
			'test_smtp_recipient_email'                    => '',
			// SMTP settings.
			'smtp_host'                                    => '',
			'smtp_port'                                    => 25,
			'smtp_encryption'                              => '',
			'smtp_username'                                => '',
			'smtp_password'                                => '',
			// Welcome email settings - for user.
			'user_welcome_email_subject'                   => '',
			'user_welcome_email_body'                      => '',
			'user_welcome_email_attachment_url'            => '',
			'user_welcome_email_reply_to_email'            => '',
			'user_welcome_email_reply_to_name'             => '',
			'user_welcome_email_additional_headers'        => '',
			// Welcome email settings - for admin.
			'admin_new_user_notif_email_subject'           => '',
			'admin_new_user_notif_email_body'              => '',
			'admin_new_user_notif_email_custom_recipients' => '',
			// Reset password email settings.
			'reset_password_email_subject'                 => '',
			'reset_password_email_body'                    => '',
		);

		$values = wp_parse_args( $settings, $defaults );

		Vars::set( 'values', $values );
	}

	/**
	 * Update settings cache after 'weed_settings' option is updated.
	 *
	 * Fires after the value of a 'weed_settings' option has been changed
	 * via 'updated_option_weed_settings' hook.
	 *
	 * @see https://developer.wordpress.org/reference/hooks/update_option_option/
	 *
	 * @param mixed  $old_value The old option value.
	 * @param mixed  $value     The new option value.
	 * @param string $option    Option name.
	 */
	public function handle_updated_option( $old_value, $value, $option ) {

		$values = ( new Content_Helper() )->parse_settings( is_array( $value ) ? $value : null );

		Vars::set( 'values', $values );

	}

	/**
	 * Load modules.
	 */
	public function load_modules() {

		$modules = array();

		$modules['Weed\\Settings\\Settings_Module'] = __DIR__ . '/modules/settings/class-settings-module.php';
		$modules['Weed\\Smtp\\Smtp_Module']         = __DIR__ . '/modules/smtp/class-smtp-module.php';
		$modules['Weed\\Logs\\Logs_Module']         = __DIR__ . '/modules/logs/class-logs-module.php';

		$modules = apply_filters( 'weed_modules', $modules );

		foreach ( $modules as $class => $file ) {
			$splits      = explode( '/', $file );
			$module_name = $splits[ count( $splits ) - 2 ];
			$filter_name = str_ireplace( '-', '_', $module_name );
			$filter_name = 'weed_' . $filter_name;

			// We have a filter here weed_$module_name to allow us to prevent loading modules under certain circumstances.
			if ( apply_filters( $filter_name, true ) ) {

				require_once $file;
				$module = new $class();
				$module->setup();

			}
		}

	}

	/**
	 * Register plugin action links filter after init hook.
	 * This ensures translations are loaded before the callback is executed.
	 */
	public function register_action_links() {

		add_filter( 'plugin_action_links_' . WEED_PLUGIN_BASENAME, array( $this, 'plugin_action_links' ) );

	}

	/**
	 * Add action links displayed in plugins page.
	 *
	 * @param array $links The action links array.
	 *
	 * @return array The modified action links array.
	 */
	public function plugin_action_links( $links ) {

		$settings = array( '<a href="' . admin_url( 'admin.php?page=weed_settings' ) . '">' . __( 'Settings', 'welcome-email-editor' ) . '</a>' );

		return array_merge( $settings, $links );

	}

	/**
	 * Admin body class.
	 *
	 * @param string $classes The existing body classes.
	 *
	 * @return string The body classes.
	 */
	public function admin_body_class( $classes ) {

		$screens = array(
			'toplevel_page_weed_settings',
		);

		$screen = get_current_screen();

		if ( ! in_array( $screen->id, $screens, true ) ) {
			return $classes;
		}

		$classes .= ' heatbox-admin has-header';

		return $classes;

	}

	/**
	 * Plugin deactivation.
	 */
	public function deactivation() {

		$settings = get_option( 'weed_settings' );

		$remove_on_uninstall = isset( $settings['remove_on_uninstall'] ) ? true : false;

		if ( $remove_on_uninstall ) {

			// Delete all settings.
			delete_option( 'weed_settings' );
			delete_option( 'weed_v5_compatibility' );

			// Remove all email logs.
			$email_logs = get_posts( array(
				'post_type'   => 'weed_email_logs',
				'numberposts' => -1, // Retrieve all email logs.
				'post_status' => 'any', // Includes all statuses.
			));

			// Loop through each email log and delete it.
			foreach ( $email_logs as $email_log ) {
				wp_delete_post( $email_log->ID, true ); // 'true' forces deletion without sending to trash.
			}

		}

	}
}
