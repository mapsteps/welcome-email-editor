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

		$module = new Settings_Module; 

		$this->url = WEED_PLUGIN_URL . '/modules/logs';
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

		if ( !$is_checked ) {
			return;
		}

		add_action( 'init', array( $this, 'register_email_logs_cpt' ), 20 ); 
 		add_action( 'admin_menu', array( $this, 'email_logs_submenu' ), 20 );		  
		add_filter( 'wp_mail', array( $this, 'capture_email_details_for_logging' ), 10, 1 );
		add_action( 'wp_mail_succeeded', array( $this, 'handle_success_email' ) ); 
		add_action( 'wp_mail_failed', array( $this, 'handle_failed_email' ) );

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
			'not_found_in_trash' => __( 'No email logs found in Trash.', 'welcome-email-editor' )
		);

		$args = array(
			'labels'             => $labels,
			'public'             => false,
			'show_ui'            => true,
			'show_in_menu'       => false,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'email_log' ),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor' )
		);

		register_post_type( 'email_logs', $args );
	}

	/**
	 * Add email logs as sub menu of the SMTP settings.
	 */
	public function email_logs_submenu() { 

		add_submenu_page(
			'weed_settings', // parent slug
			'Email Logs', // page title
			'Email Logs', // sub-menu title
			'edit_posts', // capability
			'edit.php?post_type=email_logs' // your menu menu slug
		);
	} 
 
	/**
	 * Hook into wp_mail to capture email details before sending
	 */
	public function capture_email_details_for_logging($args) {
	 
		$GLOBALS['current_email_log'] = array(
			'subject'     => $args['subject'], 
			'email_body'  => $args['message'],
			'recipient'   => is_array( $args['to']) ? implode(', ', $args['to']) : $args['to'],
			'headers'     => $args['headers'],
			'attachments' => $args['attachments'],
		);
		
		return $args;
	}

	/**
	 * Action to handle successful emails.
	 */
	public function handle_success_email($mail_data) { 
		 if (isset($GLOBALS['current_email_log'])) {

			// Defer the logging to after the email has been attempted to send
			$this->log_email_event('Success');
			unset($GLOBALS['current_email_log']);

		}
	}

	/**
	 * Hook into the wp_mail_failed action to handle email failures
	 * @param [type] $wp_error
	 */ 
	public function handle_failed_email($wp_error) {
		if (isset($GLOBALS['current_email_log'])) {
			$error_message = $wp_error->get_error_message();

			// Log the email as failed
			$this->log_email_event('Failed', $error_message);
			unset($GLOBALS['current_email_log']);
		}
	}

	/**
	 * Helper function to log email events
	 */
	public function log_email_event($status, $error_message = '') {
		$email_log = $this->get_current_email_log();
		
		if (!$email_log) {
			return; // If no email log data exists, do nothing
		}

		$email_type = $this->determine_email_type($email_log['subject']);
		$sender     = $this->get_sender_email();
		
		// Insert the email log as a custom post type entry
		$this->insert_email_log_post($email_log, $email_type, $sender, $status, $error_message);

		// Clear the global variable after logging
		unset($GLOBALS['current_email_log']);
	}

	/**
	 * Retrieve current email log from the global variable
	 */
	protected function get_current_email_log() {
		return isset($GLOBALS['current_email_log']) ? $GLOBALS['current_email_log'] : false;
	}

	/**
	 * Determine the email type based on the subject
	 */
	protected function determine_email_type($subject) {
		$email_type_mappings = array(
			'Password'      => 'Reset Password',
			'New User'      => 'New User',
			'Login Details' => 'Welcome Email',
			'Test Email'    => 'Test Email',
		);

		foreach ($email_type_mappings as $keyword => $type) {
			if (strpos($subject, $keyword) !== false) {
				return $type;
			}
		}

		return 'General';
	}

	/**
	 * Get the sender email address
	 */
	protected function get_sender_email() {
		$force_from_email = isset($this->settings['force_from_email']) ? 1 : 0;

		if ($force_from_email && isset($this->settings['from_email'])) {
			return $this->settings['from_email'];
		}

		return get_bloginfo('admin_email');
	}

	/**
	 * Insert the email log as a custom post type entry
	 */
	protected function insert_email_log_post($email_log, $email_type, $sender, $status, $error_message) {
		wp_insert_post(array(
			'post_title'   => $email_log['subject'],
			'post_type'    => 'email_logs',
			'post_status'  => 'publish',
			'post_content' => $email_log['email_body'],
			'post_date'    => current_time('mysql'),
			'meta_input'   => array(
				'email_type'          => $email_type,
				'sender'              => $sender,
				'recipient'           => $email_log['recipient'],
				'status'              => $status,
				'email_error_message' => $error_message,
			),
		));
	}

 

}
