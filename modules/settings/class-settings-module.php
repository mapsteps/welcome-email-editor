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

		add_action( 'admin_menu', array( self::get_instance(), 'submenu_page' ), 20 );
		// add_action( 'admin_init', array( self::get_instance(), 'add_settings' ) );

		add_action( 'admin_enqueue_scripts', array( self::get_instance(), 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( self::get_instance(), 'admin_scripts' ) );

		// The module output.
		require_once __DIR__ . '/class-settings-output.php';
		Settings_Output::init();

	}

	/**
	 * Add submenu page.
	 */
	public function submenu_page() {

		add_submenu_page(
			'options-general.php',
			__( 'Welome Email Editor', 'welcome-email-editor' ),
			__( 'Welome Email Editor', 'welcome-email-editor' ),
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
		add_settings_section( 'weed-general-section', __( 'General Settings', 'ultimate-quick-view-woocommerce' ), '', 'weed-general-settings' );
		add_settings_section( 'weed-button-section', __( 'Quick View Button Settings', 'ultimate-quick-view-woocommerce' ), '', 'weed-button-settings' );
		add_settings_section( 'weed-popup-section', __( 'Popup Settings', 'ultimate-quick-view-woocommerce' ), '', 'weed-popup-settings' );
		add_settings_section( 'weed-custom-section', __( 'Custom Settings', 'ultimate-quick-view-woocommerce' ), '', 'weed-custom-settings' );

		// General fields.
		add_settings_field( 'disable', __( 'Disable Quick View', 'ultimate-quick-view-woocommerce' ), array( $this, 'disable_field' ), 'weed-general-settings', 'weed-general-section' );
		add_settings_field( 'disable-on-mobile', __( 'Disable Only on Mobile', 'ultimate-quick-view-woocommerce' ), array( $this, 'disable_on_mobile_field' ), 'weed-general-settings', 'weed-general-section' );
		add_settings_field( 'remove-all-settings', __( 'Remove Data on Uninstall', 'ultimate-quick-view-woocommerce' ), array( $this, 'remove_on_uninstall_field' ), 'weed-general-settings', 'weed-general-section' );

		// Button fields.
		add_settings_field( 'button-position', __( 'Button Position', 'ultimate-quick-view-woocommerce' ), array( $this, 'button_position_field' ), 'weed-button-settings', 'weed-button-section' );
		add_settings_field( 'button-text', __( 'Button Text', 'ultimate-quick-view-woocommerce' ), array( $this, 'button_text_field' ), 'weed-button-settings', 'weed-button-section' );
		add_settings_field( 'button-colors', __( 'Button Colors', 'ultimate-quick-view-woocommerce' ), array( $this, 'button_colors_field' ), 'weed-button-settings', 'weed-button-section' );
	}

	/**
	 * Some setting field.
	 */
	public function disable_field() {

		$field = require __DIR__ . '/templates/fields/disable.php';
		$field( $this );

	}

	/**
	 * Some setting field.
	 */
	public function disable_on_mobile_field() {

		$field = require __DIR__ . '/templates/fields/disable-on-mobile.php';
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

	/**
	 * Button color field.
	 */
	public function button_colors_field() {

		$field = require __DIR__ . '/templates/fields/button-colors.php';
		$field( $this );

	}

}
