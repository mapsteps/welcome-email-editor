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

		add_action( 'init', array( $this, 'setup_text_domain' ) );
		add_filter( 'plugin_action_links_' . WEED_PLUGIN_BASENAME, array( $this, 'plugin_action_links' ) );
		add_filter( 'admin_body_class', array( $this, 'admin_body_class' ) );

		$this->load_modules();

		register_deactivation_hook( WEED_PLUGIN_BASENAME, array( $this, 'deactivation' ), 20 );

	}

	/**
	 * Setup textdomain.
	 */
	public function setup_text_domain() {
		load_plugin_textdomain( 'welcome-email-editor', false, WEED_PLUGIN_BASENAME . '/languages' );
	}

	/**
	 * Provide data for the plugin.
	 * This is optimal strategy to only get_option once across modules.
	 */
	public function set_data() {

		$defaults = array(
			// General settings.
			'from_email'                            => '[admin_email]',
			'from_name'                             => '',
			'content_type'                          => 'html',

			// Welcome email settings - for user.
			'user_welcome_email_subject'            => '[[blog_name]] Your username and password',
			'user_welcome_email_body'               => 'Username: [user_login]<br>Password: [user_password]<br>[login_url]',
			'user_welcome_email_attachment_url'     => '',
			'user_welcome_email_additional_headers' => '',
			'user_welcome_email_reply_to_email'     => '',
			'user_welcome_email_reply_to_name'      => '',

			// Welcome email settings - for admin.
			'admin_welcome_email_subject'           => '[[blog_name]] New User Registration',
			'admin_welcome_email_body'              => 'New user registration on your blog [blog_name]<br><br>Username: [user_login]<br>Email: [user_email]',
			'admin_welcome_email_custom_recipients' => '',

			// Forgot password email settings.
			'forgot_password_email_subject'         => '[[blog_name]] Forgot Password',
			'forgot_password_email_body'            => 'Someone requested that the password be reset for the following account.<br><br>[site_url]<br><br>Username: [user_login]<br><br>If this was a mistake, just ignore this email and nothing will happen.<br><br>To reset your password, visit the following address: [reset_url]',
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
	 * Load Woocommerce Quick View modules.
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
		$remove_on_uninstall = apply_filters( 'weed_clean_uninstall', $remove_on_uninstall );

		if ( $remove_on_uninstall ) {

			delete_option( 'weed_settings' );

		}

	}
}
