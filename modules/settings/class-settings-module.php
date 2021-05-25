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

		add_action( 'admin_notices', array( $this, 'placeholders_notice' ) );
		add_action( 'admin_menu', array( $this, 'submenu_page' ), 20 );
		add_action( 'admin_init', array( $this, 'add_settings' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

		// The module output.
		require_once __DIR__ . '/class-settings-output.php';
		Settings_Output::init();

	}

	/**
	 * Admin notice to provide list of available template placeholders.
	 */
	public function placeholders_notice() {

		if ( ! $this->screen()->is_settings() ) {
			return;
		}

		$description  = __( 'Available template placeholders:', 'welome-email-editor' ) . '<br>';
		$description .= '<code>[site_url]</code>, <code>[login_url]</code>, <code>[reset_pass_url]</code>, <code>[user_email]</code>, <code>[user_login]</code>, <code>[user_id]</code>, <code>[first_name]</code>, <code>[last_name]</code>, <code>[blog_name]</code>, <code>[admin_email]</code>, <code>[custom_fields]</code>, <code>[date]</code>, <code>[time]</code>, <code>[bp_custom_fields]</code> (buddypress custom fields &mdash; admin only), <code>[post_data]</code> (admin only &mdash; sends $_REQUEST)';
		$description  = '<p>' . $description . '</p>';

		printf( '<div class="notice notice-info">%1s</div>', $description );

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

		wp_enqueue_script( 'weed-settings', $this->url . '/assets/js/settings.js', array( 'jquery', 'wp-polyfill' ), WEED_PLUGIN_VERSION, true );

	}

	/**
	 * Add settings.
	 */
	public function add_settings() {

		// Register settings.
		register_setting( 'weed-settings-group', 'weed_settings' );

		// Register sections.
		add_settings_section( 'weed-general-section', __( 'General Settings', 'welcome-email-editor' ), '', 'weed-general-settings' );
		// add_settings_section( 'weed-button-section', __( 'Quick View Button Settings', 'welcome-email-editor' ), '', 'weed-button-settings' );
		// add_settings_section( 'weed-popup-section', __( 'Popup Settings', 'welcome-email-editor' ), '', 'weed-popup-settings' );
		// add_settings_section( 'weed-custom-section', __( 'Custom Settings', 'welcome-email-editor' ), '', 'weed-custom-settings' );

		// General fields.
		add_settings_field( 'from-email', __( 'From Email Address', 'welcome-email-editor' ), array( $this, 'from_email_field' ), 'weed-general-settings', 'weed-general-section' );
		add_settings_field( 'from-name', __( 'From Name', 'welcome-email-editor' ), array( $this, 'from_name_field' ), 'weed-general-settings', 'weed-general-section' );
		add_settings_field( 'content-type', __( 'Mail Content Type', 'welcome-email-editor' ), array( $this, 'content_type_field' ), 'weed-general-settings', 'weed-general-section' );
		add_settings_field( 'disable-global-headers', __( 'Disable Global Headers', 'welcome-email-editor' ), array( $this, 'disable_global_headers_field' ), 'weed-general-settings', 'weed-general-section' );
		// add_settings_field( 'disable-on-mobile', __( 'Disable Only on Mobile', 'welcome-email-editor' ), array( $this, 'disable_on_mobile_field' ), 'weed-general-settings', 'weed-general-section' );
		// add_settings_field( 'remove-all-settings', __( 'Remove Data on Uninstall', 'welcome-email-editor' ), array( $this, 'remove_on_uninstall_field' ), 'weed-general-settings', 'weed-general-section' );

		// Button fields.
		// add_settings_field( 'button-position', __( 'Button Position', 'welcome-email-editor' ), array( $this, 'button_position_field' ), 'weed-button-settings', 'weed-button-section' );
		// add_settings_field( 'button-text', __( 'Button Text', 'welcome-email-editor' ), array( $this, 'button_text_field' ), 'weed-button-settings', 'weed-button-section' );
		// add_settings_field( 'button-colors', __( 'Button Colors', 'welcome-email-editor' ), array( $this, 'button_colors_field' ), 'weed-button-settings', 'weed-button-section' );
	}

	/**
	 * From email field.
	 */
	public function from_email_field() {

		$field = require __DIR__ . '/templates/fields/from-email.php';
		$field( $this );

	}

	/**
	 * From name field.
	 */
	public function from_name_field() {

		$field = require __DIR__ . '/templates/fields/from-name.php';
		$field( $this );

	}

	/**
	 * Content type field.
	 */
	public function content_type_field() {

		$field = require __DIR__ . '/templates/fields/content-type.php';
		$field( $this );

	}

	/**
	 * Set global headers field.
	 */
	public function disable_global_headers_field() {

		$field = require __DIR__ . '/templates/fields/disable-global-headers.php';
		$field( $this );

	}

	/**
	 * Remove data on uninstall field.
	 */
	public function remove_on_uninstall_field() {

		$field = require __DIR__ . '/templates/fields/remove-on-uninstall.php';
		$field();

	}

	/**
	 * Button position field.
	 */
	public function button_position_field() {

		$field = require __DIR__ . '/templates/fields/button-position.php';
		$field();

	}

	/**
	 * Button text field.
	 */
	public function button_text_field() {

		$field = require __DIR__ . '/templates/fields/button-text.php';
		$field( $this );

	}

}
