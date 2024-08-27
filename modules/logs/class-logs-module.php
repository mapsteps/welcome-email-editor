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

		$module     = new Settings_Module; 

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

		if (!$is_checked) {
			return;
		}

		add_action( 'init', array( $this, 'register_email_logs_cpt' ), 20 ); 
 		add_action( 'admin_menu', array( $this, 'email_logs_submenu' ), 20 );		 
		add_action( 'phpmailer_init', array( $this, 'update_email_status_to_cpt') );
		add_filter( 'wp_mail', array($this, 'capture_email_details_for_logging'), 10, 1 );
		add_action( 'phpmailer_init', array($this, 'handle_sent_email') );
		add_action( 'phpmailer_init', array($this, 'handle_failed_email') );

		// The module output.
		require_once __DIR__ . '/class-logs-output.php';
		Logs_Output::init();

	}

	/**
	 * Register a Custom Post Type for Email Logs
	 */ 
	public function register_email_logs_cpt() {
		$labels = array(
			'name'               => _x( 'Email Logs', 'post type general name', 'weed' ),
			'singular_name'      => _x( 'Email Log', 'post type singular name', 'weed' ),
			'menu_name'          => _x( 'Email Logs', 'admin menu', 'weed' ),
			'name_admin_bar'     => _x( 'Email Log', 'add new on admin bar', 'weed' ),
			'add_new'            => _x( 'Add New', 'email log', 'weed' ),
			'add_new_item'       => __( 'Add New Email Log', 'weed' ),
			'new_item'           => __( 'New Email Log', 'weed' ),
			'edit_item'          => __( 'Edit Email Log', 'weed' ),
			'view_item'          => __( 'View Email Log', 'weed' ),
			'all_items'          => __( 'All Email Logs', 'weed' ),
			'search_items'       => __( 'Search Email Logs', 'weed' ),
			'not_found'          => __( 'No email logs found.', 'weed' ),
			'not_found_in_trash' => __( 'No email logs found in Trash.', 'weed' )
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
			'supports'           => array( 'title', 'editor', 'custom-fields' )
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
	 * Capture email status (success or failure) using phpmailer_init
	 */
	public function update_email_status_to_cpt($phpmailer) {
		if (isset($GLOBALS['current_email_log_post_id'])) {
			$status = $phpmailer->ErrorInfo ? 'Failed' : 'Sent';

			update_post_meta($GLOBALS['current_email_log_post_id'], 'status', $status);
			unset($GLOBALS['current_email_log_post_id']); // Clear the global variable after updating
		}
	}
 
	/**
	 * Hook into wp_mail to capture email details before sending
	 */
	public function capture_email_details_for_logging($args) {
	 
		$GLOBALS['current_email_log'] = array(
			'subject'     => $args['subject'], 
			'email_body'  => $args['message'],
			'recipient'   => is_array($args['to']) ? implode(', ', $args['to']) : $args['to'],
			'headers'     => $args['headers'],
			'attachments' => $args['attachments'],
		);
		
		return $args;
	}

	/**
	 * Action to handle sent emails (success).
	 */
	public function handle_sent_email($phpmailer) {
		if (isset($GLOBALS['current_email_log'])) {
			// Check if the email was sent successfully
			if (empty($phpmailer->ErrorInfo)) {
				// Log the email as sent
				$this->log_email_event('Sent');
			}
		}
	}
 
	/**
	 * Action to handle failed emails.
	 */
	public function handle_failed_email($phpmailer) {
		if (isset($GLOBALS['current_email_log'])) {
			// Check if the email failed to send
			if (!empty($phpmailer->ErrorInfo)) {
				// Log the email as failed
				$this->log_email_event('Failed');
			}
		}
	}

	/**
	 * Helper function to log email events
	 */ 
	public function log_email_event($status) {
		// Use the captured email details from the global variable
		$email_log = $GLOBALS['current_email_log'];

		$force_from_email = $this->settings['force_from_email'];
		$sender           = ($force_from_email && isset( $this->settings['from_email'] )) ? $this->settings['from_email'] : get_bloginfo('admin_email');
	 
		// Determine the type of email based on the subject (customize as needed)
		$email_type = '';
		if (strpos($email_log['subject'], 'Password') !== false) {
			$email_type = 'Reset Password ';
		} elseif (strpos($email_log['subject'], 'New User') !== false) {
			$email_type = 'New User';
		} elseif (strpos($email_log['subject'], 'Login Details') !== false) {
			$email_type = 'Welcome Email';
		} elseif (strpos($email_log['subject'], 'Test Email') !== false) {
			$email_type = 'Test Email';
		} else {
			$email_type = 'General';
		}

		// Insert the email log as a custom post type entry
		wp_insert_post(array(
			'post_title'   => $email_log['subject'],
			'post_type'    => 'email_logs',
			'post_status'  => 'publish',
			'post_content' => $email_log['email_body'],
			'post_date'    => current_time('mysql'),
			'meta_input'   => array(
				'email_type' => $email_type,
				'sender'     => $sender,
				'recipient'  => $email_log['recipient'],
				'status'     => $status
			),
		));

		// Clear the global variable after logging
		unset($GLOBALS['current_email_log']);
	}
 

}
