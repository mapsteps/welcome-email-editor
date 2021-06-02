<?php
/**
 * Settings module setup.
 *
 * @package Welcome_Email_Editor
 */

namespace Weed\Settings;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Weed\Vars;
use Weed\Base\Base_Module;

/**
 * Class to setup quick view module.
 */
class Settings_Module extends Base_Module {

	/**
	 * The class instance.
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * The current module url.
	 *
	 * @var string
	 */
	public $url;

	/**
	 * Module constructor.
	 */
	public function __construct() {

		parent::__construct();

		$this->url = WEED_PLUGIN_URL . '/modules/settings';

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
	 * Setup tool module.
	 */
	public function setup() {

		add_action( 'init', array( $this, 'set_plugin_priority' ) );

		add_action( 'admin_notices', array( $this, 'placeholders_notice' ) );
		add_action( 'admin_menu', array( $this, 'submenu_page' ), 20 );
		add_action( 'current_screen', array( $this, 'reset_settings' ) );
		add_action( 'admin_init', array( $this, 'add_settings' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

		// The module output.
		require_once __DIR__ . '/class-settings-output.php';
		Settings_Output::init();

		$this->setup_ajax();

	}

	/**
	 * Setup ajax.
	 */
	public function setup_ajax() {

		require_once __DIR__ . '/ajax/class-test-emails.php';
		add_action( 'wp_ajax_weed_test_emails', array( Ajax\Test_Emails::get_instance(), 'ajax_handler' ) );

	}

	/**
	 * Set our plugin as the first plugin priority.
	 */
	public function set_plugin_priority() {

		$active_plugins = get_option( 'active_plugins' );
		$current_index  = array_search( WEED_PLUGIN_BASENAME, $active_plugins, true );

		// Stop if our plugin is already the first priority (index 0).
		if ( ! $current_index ) {
			return;
		}

		array_splice( $active_plugins, $current_index, 1 );
		array_unshift( $active_plugins, WEED_PLUGIN_BASENAME );
		update_option( 'active_plugins', $active_plugins );

	}

	/**
	 * Admin notice to provide list of available template placeholders.
	 */
	public function placeholders_notice() {

		if ( ! $this->screen()->is_settings() ) {
			return;
		}

		$description  = '<h4>' . __( 'Available template placeholders:', 'welome-email-editor' ) . '</h4>';
		$description .= '<p><code>[site_url]</code>, <code>[login_url]</code>, <code>[reset_pass_url]</code>, <code>[user_email]</code>, <code>[user_login]</code>, <code>[user_id]</code>, <code>[first_name]</code>, <code>[last_name]</code>, <code>[blog_name]</code>, <code>[admin_email]</code>, <code>[custom_fields]</code>, <code>[date]</code>, <code>[time]</code>, <code>[bp_custom_fields]</code> (admin only &mdash; will print buddypress custom fields), <code>[post_data]</code> (admin only &mdash; will print $_REQUEST)</p>';

		printf( '<div class="notice notice-info weed-placeholder-notice">%1s</div>', $description );

	}

	/**
	 * Add submenu page.
	 */
	public function submenu_page() {

		add_submenu_page(
			'options-general.php',
			__( 'Welcome Email Editor', 'welcome-email-editor' ),
			__( 'Welcome Email Editor', 'welcome-email-editor' ),
			apply_filters( 'weed_settings_capability', 'manage_options' ),
			'weed_settings',
			array( $this, 'submenu_page_content' )
		);

	}

	/**
	 * Submenu page content.
	 */
	public function submenu_page_content() {

		$template = require __DIR__ . '/templates/settings-template.php';
		$template();

	}

	/**
	 * Enqueue admin styles.
	 */
	public function admin_styles() {

		if ( ! $this->screen()->is_settings() ) {
			return;
		}

		wp_enqueue_style( 'heatbox', WEED_PLUGIN_URL . '/assets/css/heatbox.css', array(), WEED_PLUGIN_VERSION );
		wp_enqueue_style( 'weed-admin', $this->url . '/assets/css/settings.css', array(), WEED_PLUGIN_VERSION );

	}

	/**
	 * Enqueue admin scripts.
	 */
	public function admin_scripts() {

		if ( ! $this->screen()->is_settings() ) {
			return;
		}

		wp_enqueue_script( 'weed-test-emails', $this->url . '/assets/js/test-emails.js', array( 'jquery', 'wp-polyfill' ), WEED_PLUGIN_VERSION, true );

		wp_localize_script(
			'weed-test-emails',
			'weedTestEmails',
			array(
				'nonces' => array(
					'adminWelcomeEmail'   => wp_create_nonce( WEED_PLUGIN_DIR . '_Admin_Welcome_Email' ),
					'userWelcomeEmail'    => wp_create_nonce( WEED_PLUGIN_DIR . '_User_Welcome_Email' ),
					'forgotPasswordEmail' => wp_create_nonce( WEED_PLUGIN_DIR . '_Forgot_Password_Email' ),
				),
			)
		);

	}

	/**
	 * Reset all settings.
	 */
	public function reset_settings() {

		if ( ! $this->screen()->is_settings() || ! isset( $_GET['action'] ) || ! isset( $_GET['nonce'] ) || ! isset( $_GET['http_referer'] ) ) {
			return;
		}

		if ( 'weed_reset_settings' !== $_GET['action'] || ! wp_verify_nonce( $_GET['nonce'], WEED_PLUGIN_DIR ) ) {
			return;
		}

		delete_option( 'weed_settings' );
		wp_safe_redirect( $_GET['http_referer'] );

	}

	/**
	 * Add settings.
	 */
	public function add_settings() {

		// Register settings.
		register_setting( 'weed-settings-group', 'weed_settings' );

		// Register sections.
		add_settings_section( 'weed-general-section', __( 'General Settings', 'welcome-email-editor' ), '', 'weed-general-settings' );
		add_settings_section( 'weed-user-welcome-email-section', __( 'Welcome Email Settings &mdash; For User', 'welcome-email-editor' ), '', 'weed-user-welcome-email-settings' );
		add_settings_section( 'weed-admin-welcome-email-section', __( 'Welcome Email Settings &mdash; For Admin', 'welcome-email-editor' ), '', 'weed-admin-welcome-email-settings' );
		add_settings_section( 'weed-forgot-password-email-section', __( 'Forgot Password Email Settings', 'welcome-email-editor' ), '', 'weed-forgot-password-email-settings' );
		add_settings_section( 'weed-misc-section', __( 'Misc. Settings', 'welcome-email-editor' ), '', 'weed-misc-settings' );

		// General fields.
		add_settings_field( 'enable', __( 'Enable Features', 'welcome-email-editor' ), array( $this, 'enable_field' ), 'weed-general-settings', 'weed-general-section' );
		add_settings_field( 'from-email', __( 'From Email Address', 'welcome-email-editor' ), array( $this, 'from_email_field' ), 'weed-general-settings', 'weed-general-section' );
		add_settings_field( 'from-name', __( 'From Name', 'welcome-email-editor' ), array( $this, 'from_name_field' ), 'weed-general-settings', 'weed-general-section' );
		add_settings_field( 'content-type', __( 'Mail Content Type', 'welcome-email-editor' ), array( $this, 'content_type_field' ), 'weed-general-settings', 'weed-general-section' );

		// User welcome email fields.
		add_settings_field( 'user-welcome-email-subject', __( 'Email Subject', 'welcome-email-editor' ), array( $this, 'user_welcome_email_subject_field' ), 'weed-user-welcome-email-settings', 'weed-user-welcome-email-section' );
		add_settings_field( 'user-welcome-email-body', __( 'Email Body', 'welcome-email-editor' ), array( $this, 'user_welcome_email_body_field' ), 'weed-user-welcome-email-settings', 'weed-user-welcome-email-section' );
		add_settings_field( 'user-welcome-email-attachment', __( 'Email Attachment URL', 'welcome-email-editor' ), array( $this, 'user_welcome_email_attachment_field' ), 'weed-user-welcome-email-settings', 'weed-user-welcome-email-section' );
		add_settings_field( 'user-welcome-email-reply-to-email', __( '"Reply-To" Email Address', 'welcome-email-editor' ), array( $this, 'user_welcome_email_reply_to_email_field' ), 'weed-user-welcome-email-settings', 'weed-user-welcome-email-section' );
		add_settings_field( 'user-welcome-email-reply-to-name', __( '"Reply-To" Name', 'welcome-email-editor' ), array( $this, 'user_welcome_email_reply_to_name_field' ), 'weed-user-welcome-email-settings', 'weed-user-welcome-email-section' );
		add_settings_field( 'user-welcome-email-additional-headers', __( 'Additional Email Headers', 'welcome-email-editor' ), array( $this, 'user_welcome_email_additional_headers_field' ), 'weed-user-welcome-email-settings', 'weed-user-welcome-email-section' );
		add_settings_field( 'user-welcome-email-test', '', array( $this, 'user_welcome_email_test_field' ), 'weed-user-welcome-email-settings', 'weed-user-welcome-email-section' );

		// Admin welcome email fields.
		add_settings_field( 'admin-welcome-email-subject', __( 'Email Subject', 'welcome-email-editor' ), array( $this, 'admin_welcome_email_subject_field' ), 'weed-admin-welcome-email-settings', 'weed-admin-welcome-email-section' );
		add_settings_field( 'admin-welcome-email-body', __( 'Email Body', 'welcome-email-editor' ), array( $this, 'admin_welcome_email_body_field' ), 'weed-admin-welcome-email-settings', 'weed-admin-welcome-email-section' );
		add_settings_field( 'admin-welcome-email-recipients', __( 'Custom Recipients', 'welcome-email-editor' ), array( $this, 'admin_welcome_email_custom_recipients_field' ), 'weed-admin-welcome-email-settings', 'weed-admin-welcome-email-section' );
		add_settings_field( 'admin-welcome-email-test', '', array( $this, 'admin_welcome_email_test_field' ), 'weed-admin-welcome-email-settings', 'weed-admin-welcome-email-section' );

		// Forgot password email fields.
		add_settings_field( 'forgot-password-email-subject', __( 'Email Subject', 'welcome-email-editor' ), array( $this, 'forgot_password_email_subject_field' ), 'weed-forgot-password-email-settings', 'weed-forgot-password-email-section' );
		add_settings_field( 'forgot-password-email-body', __( 'Email Body', 'welcome-email-editor' ), array( $this, 'forgot_password_email_body_field' ), 'weed-forgot-password-email-settings', 'weed-forgot-password-email-section' );
		add_settings_field( 'forgot-password-email-test', '', array( $this, 'forgot_password_email_test_field' ), 'weed-forgot-password-email-settings', 'weed-forgot-password-email-section' );

		// Misc. settings.
		add_settings_field( 'remove-on-uninstall', __( 'Remove on Uninstall', 'welcome-email-editor' ), array( $this, 'remove_on_uninstall_field' ), 'weed-misc-settings', 'weed-misc-section' );

	}

	/**
	 * Enable field.
	 */
	public function enable_field() {

		$field = require __DIR__ . '/templates/fields/general/enable.php';
		$field( $this );

	}

	/**
	 * From email field.
	 */
	public function from_email_field() {

		$field = require __DIR__ . '/templates/fields/general/from-email.php';
		$field( $this );

	}

	/**
	 * From name field.
	 */
	public function from_name_field() {

		$field = require __DIR__ . '/templates/fields/general/from-name.php';
		$field( $this );

	}

	/**
	 * Content type field.
	 */
	public function content_type_field() {

		$field = require __DIR__ . '/templates/fields/general/content-type.php';
		$field( $this );

	}

	/**
	 * User welcome email's subject field.
	 */
	public function user_welcome_email_subject_field() {

		$field = require __DIR__ . '/templates/fields/user-welcome-email/subject.php';
		$field( $this );

	}

	/**
	 * User welcome email's body field.
	 */
	public function user_welcome_email_body_field() {

		$field = require __DIR__ . '/templates/fields/user-welcome-email/body.php';
		$field( $this );

	}

	/**
	 * User welcome email's attachment field.
	 */
	public function user_welcome_email_attachment_field() {

		$field = require __DIR__ . '/templates/fields/user-welcome-email/attachment.php';
		$field( $this );

	}

	/**
	 * User welcome email's reply to email field.
	 */
	public function user_welcome_email_reply_to_email_field() {

		$field = require __DIR__ . '/templates/fields/user-welcome-email/reply-to-email.php';
		$field( $this );

	}

	/**
	 * User welcome email's reply to name field.
	 */
	public function user_welcome_email_reply_to_name_field() {

		$field = require __DIR__ . '/templates/fields/user-welcome-email/reply-to-name.php';
		$field( $this );

	}

	/**
	 * User welcome email's additional headers field.
	 */
	public function user_welcome_email_additional_headers_field() {

		$field = require __DIR__ . '/templates/fields/user-welcome-email/additional-headers.php';
		$field( $this );

	}

	/**
	 * User welcome email's test field.
	 */
	public function user_welcome_email_test_field() {

		$field = require __DIR__ . '/templates/fields/user-welcome-email/test-button.php';
		$field( $this );

	}

	/**
	 * Admin welcome email's subject field.
	 */
	public function admin_welcome_email_subject_field() {

		$field = require __DIR__ . '/templates/fields/admin-welcome-email/subject.php';
		$field( $this );

	}

	/**
	 * Admin welcome email's body field.
	 */
	public function admin_welcome_email_body_field() {

		$field = require __DIR__ . '/templates/fields/admin-welcome-email/body.php';
		$field( $this );

	}

	/**
	 * Admin welcome email's body field.
	 */
	public function admin_welcome_email_custom_recipients_field() {

		$field = require __DIR__ . '/templates/fields/admin-welcome-email/custom-recipients.php';
		$field( $this );

	}

	/**
	 * Admin welcome email's test field.
	 */
	public function admin_welcome_email_test_field() {

		$field = require __DIR__ . '/templates/fields/admin-welcome-email/test-button.php';
		$field( $this );

	}

	/**
	 * Forgot password email's subject field.
	 */
	public function forgot_password_email_subject_field() {

		$field = require __DIR__ . '/templates/fields/forgot-password-email/subject.php';
		$field( $this );

	}

	/**
	 * Forgot password email's body field.
	 */
	public function forgot_password_email_body_field() {

		$field = require __DIR__ . '/templates/fields/forgot-password-email/body.php';
		$field( $this );

	}

	/**
	 * Forgot password email's test field.
	 */
	public function forgot_password_email_test_field() {

		$field = require __DIR__ . '/templates/fields/forgot-password-email/test-button.php';
		$field( $this );

	}

	/**
	 * Remove data on uninstall field.
	 */
	public function remove_on_uninstall_field() {

		$field = require __DIR__ . '/templates/fields/misc/remove-on-uninstall.php';
		$field( $this );

	}

}
