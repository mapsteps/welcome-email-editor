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
	 * Module constructor.
	 */
	public function __construct() {

		parent::__construct();

		$this->url = WEED_PLUGIN_URL . '/modules/logs';

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

		add_action( 'init', array( $this, 'register_email_logs_cpt' ), 20 ); 
 		add_action( 'admin_menu', array( $this, 'email_logs_submenu' ), 20 );		 
		add_action( 'phpmailer_init', array( $this, 'update_email_status_to_cpt') );
		add_filter( 'wp_mail', array($this, 'capture_email_details_for_logging'), 10, 1 ); 

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
 
}
