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
 
		add_filter('manage_email_logs_posts_columns', array( $this, 'set_custom_email_logs_columns'));
		add_action('manage_email_logs_posts_custom_column', array( $this, 'custom_email_logs_column'), 10, 2);
		add_action('restrict_manage_posts', array( $this, 'filter_email_logs_by_status'));
		add_action('pre_get_posts', array( $this, 'filter_email_logs_query_by_status'));
		add_action('admin_head', array( $this, 'custom_email_logs_status_styles'));

	}
  
	/**
	 * Display Custom Columns in the Email Logs List Table
	 *
	 * @param $columns.
	 */
	public function set_custom_email_logs_columns($columns) {
		unset($columns['date']);

		$columns['email_type'] = __('Email Type', 'weed');
		$columns['sender']     = __('Sender', 'weed');
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
			case 'sender':
				echo esc_html(get_post_meta($post_id, 'sender', true));
				break;
			case 'email_body':
				echo esc_html(get_post_meta($post_id, 'email_body', true));
				break;
			case 'recipient':
				echo esc_html(get_post_meta($post_id, 'recipient', true));
				break;
			case 'status':
				$status = get_post_meta($post_id, 'status', true);
				// Conditionally style the status
				if ($status == 'Sent') {
					echo '<span style="color: green; font-weight: bold;">' . esc_html($status) . '</span>';
				} elseif ($status == 'Failed') {
					echo '<span style="color: red; font-weight: bold;">' . esc_html($status) . '</span>';
				} else {
					echo esc_html($status);
				}
				break;
			case 'date_time':
				echo esc_html(get_post_meta($post_id, 'date_time', true));
				break;
		}
	}

	/**
	 * Add inline CSS for better styling.
	 */
	public function custom_email_logs_status_styles() {
		echo '
		<style>
			.column-email_status { width: 150px; }
		</style>
		';
	}

	/**
	 * Add the filter dropdown to the admin list view
	 * 
	 */ 
	public function filter_email_logs_by_status() {
		global $typenow;

		// Only add the filter for the 'email_logs' post type
		if ( $typenow == 'email_logs' ) {
			$selected = isset($_GET['email_status']) ? $_GET['email_status'] : '';
			
			// Dropdown options for email status
			?>
			<select name="email_status" id="email_status">
				<option value=""><?php _e( 'All Statuses', 'weed' ); ?></option>
				<option value="Sent" <?php selected( $selected, 'Sent' ); ?>><?php _e( 'Sent', 'weed' ); ?></option>
				<option value="Failed" <?php selected( $selected, 'Failed' ); ?>><?php _e( 'Failed', 'weed' ); ?></option>
			</select>
			<?php
		}
	}

	/**
	 * Modify the query based on the selected email status
	 */
	public function filter_email_logs_query_by_status( $query ) {
		global $pagenow;
		$post_type = isset( $_GET['post_type'] ) ? $_GET['post_type'] : '';

		if ( $post_type == 'email_logs' && is_admin() && $pagenow == 'edit.php' && isset( $_GET['email_status'] ) && $_GET['email_status'] != '' ) {
			$meta_query = array(
				array(
					'key'   => 'status',
					'value' => sanitize_text_field( $_GET['email_status'] ),
				)
			);
			$query->set( 'meta_query', $meta_query );
		}
	}

}
