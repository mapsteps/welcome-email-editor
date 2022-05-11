<?php
/**
 * Setup Welcome Email Editor plugin.
 *
 * @package Welcome_Email_Editor
 */

namespace Weed;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Weed\Vars;

/**
 * Class to setup Ultimate Quick View plugin.
 */
class Setup {
	/**
	 * The class instance.
	 *
	 * @var object
	 */
	public static $instance;

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
	 * Init the class setup.
	 */
	public static function init() {

		$class = self::get_instance();

		add_action( 'plugins_loaded', array( $class, 'setup' ) );

	}

	/**
	 * Setup the class.
	 */
	public function setup() {

		$this->set_data();

		add_filter( 'plugin_action_links_' . WEED_PLUGIN_BASENAME, array( $this, 'plugin_action_links' ) );
		add_filter( 'admin_body_class', array( $this, 'admin_body_class' ) );

		$this->load_modules();

		register_deactivation_hook( WEED_PLUGIN_BASENAME, array( $this, 'deactivation' ), 20 );

	}

	/**
	 * Provide data for the plugin.
	 * This is optimal strategy to only get_option once across modules.
	 */
	public function set_data() {

		/* translators: %s: User login. */
		$user_welcome_email_body  = __( 'Username:', 'welcome-email-editor' ) . ' [user_login]' . "<br><br>\r\n\r\n";
		$user_welcome_email_body .= __( 'To set your password, visit the following address:' ) . "<br><br>\r\n\r\n";
		$user_welcome_email_body .= '<a href="[reset_pass_url]">[reset_pass_url]</a><br><br>' . "\r\n\r\n";
		$user_welcome_email_body .= '<a href="[login_url]">[login_url]</a>' . "\r\n";

		/* translators: %s: Site title. */
		$admin_welcome_email_body = __( 'New user registration on your site', 'welcome-email-editor' ) . " [blog_name]<br><br>\r\n\r\n";
		/* translators: %s: User login. */
		$admin_welcome_email_body .= __( 'Username:', 'welcome-email-editor' ) . " [user_login]<br><br>\r\n\r\n";
		/* translators: %s: User email address. */
		$admin_welcome_email_body .= __( 'Email:', 'welcome-email-editor' ) . " [user_email]<br><br>\r\n";

		$reset_password_message = __( 'Someone has requested a password reset for the following account:', 'welcome-email-editor' ) . "<br><br>\r\n\r\n";
		/* translators: %s: Site name. */
		$reset_password_message .= __( 'Site Name:', 'welcome-email-editor' ) . " [blog_name]<br><br>\r\n\r\n";
		/* translators: %s: User login. */
		$reset_password_message .= __( 'Username:', 'welcome-email-editor' ) . " [user_login]<br><br>\r\n\r\n";
		$reset_password_message .= __( 'If this was a mistake, ignore this email and nothing will happen.', 'welcome-email-editor' ) . "<br><br>\r\n\r\n";
		$reset_password_message .= __( 'To reset your password, visit the following address:', 'welcome-email-editor' ) . "<br><br>\r\n\r\n";
		$reset_password_message .= '<a href="[reset_pass_url]">[reset_pass_url]</a>' . "\r\n";

		$defaults = array(
			// General settings.
			'from_email'                                   => '',
			'from_name'                                    => '',
			'content_type'                                 => 'html',

			// Welcome email settings - for user.
			'user_welcome_email_subject'                   => '[[blog_name]] ' . __( 'Login Details', 'welcome-email-editor' ),
			'user_welcome_email_body'                      => $user_welcome_email_body,
			'user_welcome_email_attachment_url'            => '',
			'user_welcome_email_reply_to_email'            => '',
			'user_welcome_email_reply_to_name'             => '',
			'user_welcome_email_additional_headers'        => '',

			// Welcome email settings - for admin.
			'admin_new_user_notif_email_subject'           => '[[blog_name]] ' . __( 'New User Registration', 'welcome-email-editor' ),
			'admin_new_user_notif_email_body'              => $admin_welcome_email_body,
			'admin_new_user_notif_email_custom_recipients' => '',

			// Reset password email settings.
			'reset_password_email_subject'                 => '[[blog_name]] ' . __( 'Password Reset', 'welcome-email-editor' ),
			'reset_password_email_body'                    => $reset_password_message,
		);

		$settings = get_option( 'weed_settings', array() );

		$values = wp_parse_args( $settings, $defaults );

		Vars::set( 'default_settings', $defaults );
		Vars::set( 'settings', $settings );
		Vars::set( 'values', $values );

	}

	/**
	 * Add action links displayed in plugins page.
	 *
	 * @param array $links The action links array.
	 * @return array The modified action links array.
	 */
	public function plugin_action_links( $links ) {

		$settings = array( '<a href="' . admin_url( 'admin.php?page=weed_settings' ) . '">' . __( 'Settings', 'ultimate-quick-view-woocommerce' ) . '</a>' );

		return array_merge( $settings, $links );

	}

	/**
	 * Admin body class.
	 *
	 * @param string $classes The existing body classes.
	 * @return string The body classes.
	 */
	public function admin_body_class( $classes ) {

		$screens = array(
			'settings_page_weed_settings',
		);

		$screen = get_current_screen();

		if ( ! in_array( $screen->id, $screens, true ) ) {
			return $classes;
		}

		$classes .= ' heatbox-admin has-header';

		return $classes;

	}

	/**
	 * Load modules.
	 */
	public function load_modules() {

		$modules = array();

		$modules['Weed\\Settings\\Settings_Module'] = __DIR__ . '/modules/settings/class-settings-module.php';

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
	 * Plugin deactivation.
	 */
	public function deactivation() {

		$settings = get_option( 'weed_settings' );

		$remove_on_uninstall = isset( $settings['remove_on_uninstall'] ) ? true : false;

		if ( $remove_on_uninstall ) {

			delete_option( 'weed_settings' );
			delete_option( 'weed_v5_compatibility' );

		}

	}
}
