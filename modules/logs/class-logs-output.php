<?php
/**
 * Logging module output.
 *
 * @package Welcome_Email_Editor
 */

namespace Weed\Logs;

use PHPMailer\PHPMailer\PHPMailer;
use Weed\Base\Base_Output;
use Weed\Vars;
use WP_Error;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Class to set up Logging output.
 */
class Logs_Output extends Base_Output {

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
	 * Init the class setup.
	 */
	public static function init() {

		$class = new self();
		$class->setup();

	}

	/**
	 * Setup SMTP output.
	 */
	public function setup() {
 
		add_filter('manage_email_logs_posts_columns', array( $this, 'set_custom_email_logs_columns' ));
		add_action('manage_email_logs_posts_custom_column', array( $this, 'custom_email_logs_column'), 10, 2);

	}
  
	/**
	 * Display Custom Columns in the Email Logs List Table
	 *
	 * @param $columns.
	 */
	public function set_custom_email_logs_columns($columns) {
		unset($columns['date']);

		$columns['email_type'] = __('Email Type', 'weed');
		$columns['email_body'] = __('Email Body', 'weed');
		$columns['recipient']  = __('Recipient', 'weed');
		$columns['status']     = __('Status', 'weed');
		$columns['date_time']  = __('Date/Time', 'weed');

		return $columns;
	}

	/**
	 * Display Custom Columns Values in the Email Logs List Table
	 *
	 * @param $column, $post_id.
	 */
	public function custom_email_logs_column($column, $post_id) {
		switch ($column) {
			case 'email_type':
				echo esc_html(get_post_meta($post_id, 'email_type', true));
				break;
			case 'email_body':
				echo esc_html(get_post_meta($post_id, 'email_body', true));
				break;
			case 'recipient':
				echo esc_html(get_post_meta($post_id, 'recipient', true));
				break;
			case 'status':
				echo esc_html(get_post_meta($post_id, 'status', true));
				break;
			case 'date_time':
				echo esc_html(get_post_meta($post_id, 'date_time', true));
				break;
		}
	}
}
