<?php
/**
 * Settings module setup.
 *
 * @package Welcome_Email_Editor
 */

namespace Weed\Settings;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Weed\Base\Base_Module;

/**
 * Class to set up settings module.
 */
class Settings_Module extends Base_Module {

	/**
	 * The class instance.
	 *
	 * @var self
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
	 * Set up the module.
	 */
	public function setup() {

		add_action( 'init', array( $this, 'set_plugin_priority' ) );

		add_action( 'admin_menu', array( $this, 'menu_page' ), 20 );
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
	 * Get instance of the class.
	 *
	 * @return self
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;

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
	 * Add menu page.
	 */
	public function menu_page() {
		add_menu_page(
			__( 'Swift SMTP', 'welcome-email-editor' ), // Page title
			__( 'Swift SMTP', 'welcome-email-editor' ), // Menu title
			apply_filters( 'weed_settings_capability', 'manage_options' ), // Capability
			'weed_settings', // Menu slug
			array( $this, 'submenu_page_content' ), // Function to display the page content
			'dashicons-email', // Icon (you can change this to any Dashicon)
			60 // Position in the menu
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
		wp_enqueue_style( 'weed-admin', WEED_PLUGIN_URL . '/assets/css/settings.css', array(), WEED_PLUGIN_VERSION );

	}

	/**
	 * Enqueue admin scripts.
	 */
	public function admin_scripts() {

		if ( ! $this->screen()->is_settings() ) {
			return;
		}

		wp_enqueue_script(
			'weed-settings',
			WEED_PLUGIN_URL . '/assets/js/settings.js',
			array(
				'jquery',
				'wp-polyfill',
			),
			WEED_PLUGIN_VERSION,
			true
		);

		wp_localize_script(
			'weed-settings',
			'weedSettings',
			array(
				'nonces'          => array(
					'adminWelcomeEmail'  => wp_create_nonce( WEED_PLUGIN_DIR . '_Admin_Welcome_Email' ),
					'userWelcomeEmail'   => wp_create_nonce( WEED_PLUGIN_DIR . '_User_Welcome_Email' ),
					'resetPasswordEmail' => wp_create_nonce( WEED_PLUGIN_DIR . '_Reset_Password_Email' ),
					'testSmtpEmail'      => wp_create_nonce( WEED_PLUGIN_DIR . '_Test_SMTP_Email' ),
				),
				'warningMessages' => array(
					'resetSettings' => __( 'Caution! Are you sure you want to reset all settings?', 'welcome-email-editor' ),
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
		add_settings_section( 'weed-smtp-section', __( 'SMTP Settings', 'welcome-email-editor' ), '', 'weed-smtp-settings' );
		add_settings_section( 'weed-test-smtp-section', __( 'Send Test Email', 'welcome-email-editor' ), '', 'weed-test-smtp-settings' );
		add_settings_section( 'weed-user-welcome-email-section', __( 'Welcome Email (for Users)', 'welcome-email-editor' ), '', 'weed-user-welcome-email-settings' );
		add_settings_section( 'weed-admin-new-user-notif-email-section', __( 'New User Notification Email (for Admins)', 'welcome-email-editor' ), '', 'weed-admin-new-user-notif-email-settings' );
		add_settings_section( 'weed-reset-password-email-section', __( 'Reset Password Email', 'welcome-email-editor' ), '', 'weed-reset-password-email-settings' );
		add_settings_section( 'weed-enable-logging-section', __( 'Email Logging', 'welcome-email-editor' ), '', 'weed-enable-logging-settings' );
		add_settings_section( 'weed-misc-section', __( 'Other', 'welcome-email-editor' ), '', 'weed-misc-settings' );

		// General fields.
		add_settings_field(
			'from-email',
			__( 'From Email Address', 'welcome-email-editor' ),
			array(
				$this,
				'from_email_field',
			),
			'weed-general-settings',
			'weed-general-section'
		);

		add_settings_field(
			'force-from-email',
			__( 'Force From Email', 'welcome-email-editor' ),
			array(
				$this,
				'force_from_email_field',
			),
			'weed-general-settings',
			'weed-general-section'
		);

		add_settings_field(
			'from-name',
			__( 'From Name', 'welcome-email-editor' ),
			array(
				$this,
				'from_name_field',
			),
			'weed-general-settings',
			'weed-general-section'
		);

		add_settings_field(
			'force-from-name',
			__( 'Force From Name', 'welcome-email-editor' ),
			array(
				$this,
				'force_from_name_field',
			),
			'weed-general-settings',
			'weed-general-section'
		);

		add_settings_field(
			'content-type',
			__( 'Mail Content Type', 'welcome-email-editor' ),
			array(
				$this,
				'content_type_field',
			),
			'weed-general-settings',
			'weed-general-section'
		);

		// SMTP fields.
		add_settings_field(
			'smtp-host',
			__( 'SMTP Host', 'welcome-email-editor' ),
			array(
				$this,
				'smtp_host_field',
			),
			'weed-smtp-settings',
			'weed-smtp-section'
		);

		add_settings_field(
			'smtp-encryption',
			__( 'SMTP Encryption', 'welcome-email-editor' ),
			array(
				$this,
				'smtp_encryption_field',
			),
			'weed-smtp-settings',
			'weed-smtp-section'
		);

		add_settings_field(
			'smtp-port',
			__( 'SMTP Port', 'welcome-email-editor' ),
			array(
				$this,
				'smtp_port_field',
			),
			'weed-smtp-settings',
			'weed-smtp-section'
		);

		add_settings_field(
			'smtp-username',
			__( 'SMTP Username', 'welcome-email-editor' ),
			array(
				$this,
				'smtp_username_field',
			),
			'weed-smtp-settings',
			'weed-smtp-section'
		);

		add_settings_field(
			'smtp-password',
			__( 'SMTP Password', 'welcome-email-editor' ),
			array(
				$this,
				'smtp_password_field',
			),
			'weed-smtp-settings',
			'weed-smtp-section'
		);

		add_settings_field(
			'test-smtp',
			'',
			array(
				$this,
				'test_smtp_field',
			),
			'weed-test-smtp-settings',
			'weed-test-smtp-section'
		);

		// User welcome email fields.
		add_settings_field(
			'user-welcome-email-subject',
			__( 'Email Subject', 'welcome-email-editor' ),
			array(
				$this,
				'user_welcome_email_subject_field',
			),
			'weed-user-welcome-email-settings',
			'weed-user-welcome-email-section'
		);

		add_settings_field(
			'user-welcome-email-body',
			__( 'Email Body', 'welcome-email-editor' ),
			array(
				$this,
				'user_welcome_email_body_field',
			),
			'weed-user-welcome-email-settings',
			'weed-user-welcome-email-section'
		);

		add_settings_field(
			'user-welcome-email-attachment',
			__( 'Email Attachment URL', 'welcome-email-editor' ),
			array(
				$this,
				'user_welcome_email_attachment_field',
			),
			'weed-user-welcome-email-settings',
			'weed-user-welcome-email-section'
		);

		add_settings_field(
			'user-welcome-email-reply-to-email',
			__( '"Reply-To" Email', 'welcome-email-editor' ),
			array(
				$this,
				'user_welcome_email_reply_to_email_field',
			),
			'weed-user-welcome-email-settings',
			'weed-user-welcome-email-section'
		);

		add_settings_field(
			'user-welcome-email-reply-to-name',
			__( '"Reply-To" Name', 'welcome-email-editor' ),
			array(
				$this,
				'user_welcome_email_reply_to_name_field',
			),
			'weed-user-welcome-email-settings',
			'weed-user-welcome-email-section'
		);

		add_settings_field(
			'user-welcome-email-additional-headers',
			__( 'Additional Email Headers', 'welcome-email-editor' ),
			array(
				$this,
				'user_welcome_email_additional_headers_field',
			),
			'weed-user-welcome-email-settings',
			'weed-user-welcome-email-section'
		);

		add_settings_field(
			'user-welcome-email-test',
			'',
			array(
				$this,
				'user_welcome_email_test_field',
			),
			'weed-user-welcome-email-settings',
			'weed-user-welcome-email-section'
		);

		// Admin new user notification email fields.
		add_settings_field(
			'admin-new-user-notif-email-subject',
			__( 'Email Subject', 'welcome-email-editor' ),
			array(
				$this,
				'admin_new_user_notif_email_subject_field',
			),
			'weed-admin-new-user-notif-email-settings',
			'weed-admin-new-user-notif-email-section'
		);

		add_settings_field(
			'admin-new-user-notif-email-body',
			__( 'Email Body', 'welcome-email-editor' ),
			array(
				$this,
				'admin_new_user_notif_email_body_field',
			),
			'weed-admin-new-user-notif-email-settings',
			'weed-admin-new-user-notif-email-section'
		);

		add_settings_field(
			'admin-new-user-notif-email-recipients',
			__( 'Additional Recipients', 'welcome-email-editor' ),
			array(
				$this,
				'admin_new_user_notif_email_custom_recipients_field',
			),
			'weed-admin-new-user-notif-email-settings',
			'weed-admin-new-user-notif-email-section'
		);

		add_settings_field(
			'admin-new-user-notif-email-test',
			'',
			array(
				$this,
				'admin_new_user_notif_email_test_field',
			),
			'weed-admin-new-user-notif-email-settings',
			'weed-admin-new-user-notif-email-section'
		);

		// Reset password email fields.
		add_settings_field(
			'reset-password-email-subject',
			__( 'Email Subject', 'welcome-email-editor' ),
			array(
				$this,
				'reset_password_email_subject_field',
			),
			'weed-reset-password-email-settings',
			'weed-reset-password-email-section'
		);

		add_settings_field(
			'reset-password-email-body',
			__( 'Email Body', 'welcome-email-editor' ),
			array(
				$this,
				'reset_password_email_body_field',
			),
			'weed-reset-password-email-settings',
			'weed-reset-password-email-section'
		);

		add_settings_field(
			'reset-password-email-test',
			'',
			array(
				$this,
				'reset_password_email_test_field',
			),
			'weed-reset-password-email-settings',
			'weed-reset-password-email-section'
		);

		// Email Logging settings.
		add_settings_field(
			'enable-email-logging',
			'Enable Email Logging',
			array(
				$this,
				'enable_email_logging_field',
			),
			'weed-enable-logging-settings',
			'weed-enable-logging-section'
		);

		// Misc settings.
		add_settings_field(
			'remove-on-uninstall',
			__( 'Remove on Uninstall <p class="description">This setting will remove all related database entries when deleting the plugin.</p>', 'welcome-email-editor' ),
			array(
				$this,
				'remove_on_uninstall_field',
			),
			'weed-misc-settings',
			'weed-misc-section'
		);

	}

	/**
	 * From email field.
	 */
	public function from_email_field() {

		$field = require __DIR__ . '/templates/fields/general/from-email.php';
		$field( $this );

	}

	/**
	 * Force from email field.
	 */
	public function force_from_email_field() {

		$field = require __DIR__ . '/templates/fields/general/force-from-email.php';
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
	 * Force from name field.
	 */
	public function force_from_name_field() {

		$field = require __DIR__ . '/templates/fields/general/force-from-name.php';
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
	 * SMTP host field.
	 */
	public function smtp_host_field() {

		$field = require __DIR__ . '/templates/fields/smtp/host.php';
		$field( $this );

	}

	/**
	 * SMTP encryption field.
	 */
	public function smtp_encryption_field() {

		$field = require __DIR__ . '/templates/fields/smtp/encryption.php';
		$field( $this );

	}

	/**
	 * SMTP port field.
	 */
	public function smtp_port_field() {

		$field = require __DIR__ . '/templates/fields/smtp/port.php';
		$field( $this );

	}

	/**
	 * SMTP username field.
	 */
	public function smtp_username_field() {

		$field = require __DIR__ . '/templates/fields/smtp/username.php';
		$field( $this );

	}

	/**
	 * SMTP password field.
	 */
	public function smtp_password_field() {

		$field = require __DIR__ . '/templates/fields/smtp/password.php';
		$field( $this );

	}

	/**
	 * Test SMTP field.
	 */
	public function test_smtp_field() {

		$field = require __DIR__ . '/templates/fields/test-smtp/test-smtp.php';
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
	 * Admin new user notification email's subject field.
	 */
	public function admin_new_user_notif_email_subject_field() {

		$field = require __DIR__ . '/templates/fields/admin-new-user-notif-email/subject.php';
		$field( $this );

	}

	/**
	 * Admin new user notification email's body field.
	 */
	public function admin_new_user_notif_email_body_field() {

		$field = require __DIR__ . '/templates/fields/admin-new-user-notif-email/body.php';
		$field( $this );

	}

	/**
	 * Admin new user notification email's body field.
	 */
	public function admin_new_user_notif_email_custom_recipients_field() {

		$field = require __DIR__ . '/templates/fields/admin-new-user-notif-email/custom-recipients.php';
		$field( $this );

	}

	/**
	 * Admin new user notification email's test field.
	 */
	public function admin_new_user_notif_email_test_field() {

		$field = require __DIR__ . '/templates/fields/admin-new-user-notif-email/test-button.php';
		$field( $this );

	}

	/**
	 * Reset password email's subject field.
	 */
	public function reset_password_email_subject_field() {

		$field = require __DIR__ . '/templates/fields/reset-password-email/subject.php';
		$field( $this );

	}

	/**
	 * Reset password email's body field.
	 */
	public function reset_password_email_body_field() {

		$field = require __DIR__ . '/templates/fields/reset-password-email/body.php';
		$field( $this );

	}

	/**
	 * Reset password email's test field.
	 */
	public function reset_password_email_test_field() {

		$field = require __DIR__ . '/templates/fields/reset-password-email/test-button.php';
		$field( $this );

	}

	/**
	 * Content type field.
	 */
	public function enable_email_logging_field() {

		$field = require __DIR__ . '/templates/fields/logging/enable-email-logging.php';
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
