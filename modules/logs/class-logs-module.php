<?php
/**
 * The Logging module class.
 *
 * @package Weed
 */

namespace Weed\Logs;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Weed\Base\Base_Module;
use Weed\Settings\Settings_Module;

/**
 * Class to set up Logs module.
 */
class Logs_Module extends Base_Module {

	/**
	 * The global variable name for storing current email log details.
	 *
	 * @var string
	 */
	public $log_global_var = 'weed_current_email_log';

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
	 * The current module url.
	 *
	 * @var string
	 */
	public $settings;

	/**
	 * Module constructor.
	 */
	public function __construct() {

		parent::__construct();

		$module = new Settings_Module();

		$this->url      = WEED_PLUGIN_URL . '/modules/logs';
		$this->settings = $module->settings;

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
	 * Set up the module.
	 */
	public function setup() {

		$is_checked = isset( $this->settings['enable_email_logging'] ) ? 1 : 0;

		if ( ! $is_checked ) {
			return;
		}

		add_action( 'init', array( $this, 'register_email_logs_cpt' ), 20 );
		add_action( 'admin_menu', array( $this, 'email_logs_submenu' ), 20 );
		add_filter( 'wp_mail', array( $this, 'capture_email_details_for_logging' ), 10, 2 );
		add_action( 'phpmailer_init', array( $this, 'capture_email_sender' ), 10, 1 );
		add_action( 'wp_mail_succeeded', array( $this, 'handle_success_email' ) );
		add_action( 'wp_mail_failed', array( $this, 'handle_failed_email' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'email_logs_detail_styles' ) );
		add_action( 'admin_init', array( $this, 'set_logs_capabilities' ) );

		// The module output.
		require_once __DIR__ . '/class-logs-output.php';
		Logs_Output::init();

	}

	/**
	 * Register a Custom Post Type for Email Logs
	 */
	public function register_email_logs_cpt() {

		$labels = array(
			'name'               => _x( 'Email Logs', 'post type general name', 'welcome-email-editor' ),
			'singular_name'      => _x( 'Email Log', 'post type singular name', 'welcome-email-editor' ),
			'menu_name'          => _x( 'Email Logs', 'admin menu', 'welcome-email-editor' ),
			'name_admin_bar'     => _x( 'Email Log', 'add new on admin bar', 'welcome-email-editor' ),
			'add_new'            => _x( 'Add New', 'email log', 'welcome-email-editor' ),
			'add_new_item'       => __( 'Add New Email Log', 'welcome-email-editor' ),
			'new_item'           => __( 'New Email Log', 'welcome-email-editor' ),
			'edit_item'          => __( 'Edit Email Log', 'welcome-email-editor' ),
			'view_item'          => __( 'View Email Log', 'welcome-email-editor' ),
			'all_items'          => __( 'All Email Logs', 'welcome-email-editor' ),
			'search_items'       => __( 'Search Email Logs', 'welcome-email-editor' ),
			'not_found'          => __( 'No email logs found.', 'welcome-email-editor' ),
			'not_found_in_trash' => __( 'No email logs found in Trash.', 'welcome-email-editor' ),
		);

		$capabilities = array(
			'edit_post'          => 'edit_log',
			'read_post'          => 'read_log',
			'delete_post'        => 'delete_log',
			'edit_posts'         => 'edit_logs',
			'edit_others_posts'  => 'edit_others_logs',
			'delete_posts'       => 'delete_logs',
			'publish_posts'      => 'publish_logs',
			'read_private_posts' => 'read_private_logs',
			'create_posts'       => 'edit_logs',
		);

		$args = array(
			'labels'          => $labels,
			'public'          => false,
			'show_ui'         => true,
			'show_in_menu'    => false,
			'query_var'       => true,
			'rewrite'         => array( 'slug' => 'weed_email_log' ),
			'capability_type' => array( 'log', 'logs' ),
			'capabilities'    => $capabilities,
			'map_meta_cap'    => false,
			'has_archive'     => false,
			'hierarchical'    => false,
			'menu_position'   => null,
			'supports'        => array( 'title', 'editor' ),
		);

		register_post_type( 'weed_email_logs', $args );

	}

	/**
	 * Add email logs as sub menu of the SMTP settings.
	 */
	public function email_logs_submenu() {

		add_submenu_page(
			'weed_settings',
			'Email Logs',
			'Email Logs',
			'manage_options',
			'edit.php?post_type=weed_email_logs'
		);

	}

	/**
	 * Hook into pre_wp_mail to capture email details before sending
	 */
	public function capture_email_details_for_logging( $args ) {

		$GLOBALS[ $this->log_global_var ] = array(
			'subject'     => $args['subject'],
			'email_body'  => $args['message'],
			'recipient'   => is_array( $args['to'] ) ? implode( ', ', $args['to'] ) : $args['to'],
			'headers'     => $args['headers'],
			'attachments' => $args['attachments'],
		);

		return $args;

	}

	/**
	 * Action to handle successful emails.
	 * Type object $phpmailer
	 */
	public function capture_email_sender( $phpmailer ) {

		// Get the 'From' email
		$from_email = $phpmailer->From;

		if ( isset( $GLOBALS[ $this->log_global_var ] ) ) {
			$GLOBALS[ $this->log_global_var ]['sender'] = $from_email;
		}

	}

	/**
	 * Action to handle successful emails.
	 */
	public function handle_success_email( $mail_data ) {

		if ( isset( $GLOBALS[ $this->log_global_var ] ) ) {
			// Assuming $mail_data or another global variable contains the server response
			// Initialize an empty response
			$server_response = 'Email sent successfully.';

			// Log the email with success status and the server response
			$this->log_email_event( 'Success', $server_response );
			unset( $GLOBALS[ $this->log_global_var ] );

		}

	}

	/**
	 * Hook into the wp_mail_failed action to handle email failures
	 *
	 * @param object $wp_error The WP_Error object.
	 */
	public function handle_failed_email( $wp_error ) {

		if ( isset( $GLOBALS[ $this->log_global_var ] ) ) {
			$server_response = $wp_error->get_error_message();

			// Log the email as failed
			$this->log_email_event( 'Failed', $server_response );
			unset( $GLOBALS[ $this->log_global_var ] );

		}
	}

	/**
	 * Helper function to log email events
	 */
	public function log_email_event( $status, $server_response = '' ) {

		$email_log = $this->get_current_email_log();

		// If no email log data exists, do nothing
		if ( ! $email_log ) {
			return;
		}

		$sender = $email_log['sender'];

		// Insert the email log as a custom post type entry
		$this->insert_email_log_post( $email_log, $sender, $status, $server_response );

		// Clear the global variable after logging
		unset( $GLOBALS[ $this->log_global_var ] );

	}

	/**
	 * Retrieve current email log from the global variable
	 */
	protected function get_current_email_log() {

		return isset( $GLOBALS[ $this->log_global_var ] ) ? $GLOBALS[ $this->log_global_var ] : false;

	}

	/**
	 * Insert the email log as a custom post type entry
	 */
	protected function insert_email_log_post( $email_log, $sender, $status, $server_response ) {

		wp_insert_post(array(
			'post_title'   => $email_log['subject'],
			'post_type'    => 'weed_email_logs',
			'post_status'  => 'publish',
			'post_content' => $email_log['email_body'],
			'post_date'    => current_time( 'mysql' ),
			'meta_input'   => array(
				'subject'         => $email_log['subject'],
				'sender'          => $sender,
				'recipient'       => $email_log['recipient'],
				'status'          => $status,
				'server_response' => $server_response,
			),
		));

	}

	/**
	 * Enqueue email logs details styles.
	 */
	public function email_logs_detail_styles() {

		// Get the current screen object
		$screen = get_current_screen();

		// Check if the current screen is related to the 'email_logs' post type
		if ( $screen && $screen->id === 'weed_email_logs' ) {
			wp_enqueue_style( 'email-logs-details', $this->url . '/assets/css/email-logs-detail.css', array(), WEED_PLUGIN_VERSION );
		}

	}

	/**
	 * Set the capabilities for the logs post type
	 */
	public function set_logs_capabilities() {

		$role = get_role( 'administrator' );
		$role->add_cap( 'edit_log' );
		$role->add_cap( 'read_log' );
		$role->add_cap( 'delete_log' );
		$role->add_cap( 'edit_logs' );
		$role->add_cap( 'edit_others_logs' );
		$role->add_cap( 'delete_logs' );
		$role->add_cap( 'publish_logs' );
		$role->add_cap( 'read_private_logs' );

	}

}
