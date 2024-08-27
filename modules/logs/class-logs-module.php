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

}
