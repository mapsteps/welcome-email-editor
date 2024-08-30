<?php
/**
 * Logging module output.
 *
 * @package Welcome_Email_Editor
 */

namespace Weed\Logs;
 
use Weed\Base\Base_Output; 

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
	 * Setup Logging output.
	 */
	public function setup() {
 
		add_filter( 'manage_email_logs_posts_columns', array( $this, 'set_custom_email_logs_columns' ) );
		add_action( 'manage_email_logs_posts_custom_column', array( $this, 'custom_email_logs_column' ), 10, 2 );
		add_action( 'restrict_manage_posts', array( $this, 'filter_email_logs_by_status' ) );
		add_action( 'pre_get_posts', array( $this, 'filter_email_logs_query_by_status' ) );
		add_action( 'admin_head', array( $this, 'custom_email_logs_status_styles' ) ); 
		add_action( 'current_screen', array( $this, 'restrict_access_to_email_logs' ) );
		add_action( 'admin_menu', array( $this, 'hide_email_logs_menu' ), 999 ); 
		add_action( 'add_meta_boxes', array( $this, 'add_email_logs_metabox' ) );
	}
  
	/**
	 * Display Custom Columns in the Email Logs List Table
	 *
	 * @param $columns.
	 */
	public function set_custom_email_logs_columns($columns) {
		unset($columns['date']);

		$columns['email_type'] = __('Email Type', 'welcome-email-editor');
		$columns['sender']     = __('Sender', 'welcome-email-editor');
		$columns['recipient']  = __('Recipient', 'welcome-email-editor');
		$columns['status']     = __('Status', 'welcome-email-editor');
		$columns['date']       = __('Date/Time', 'welcome-email-editor');

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
			case 'recipient':
				echo esc_html(get_post_meta($post_id, 'recipient', true));
				break;
			case 'status':
				$status = get_post_meta($post_id, 'status', true);
				// Conditionally style the status
				if ($status == 'Success') {
					echo '<span style="color: green; font-weight: bold;">' . esc_html($status) . '</span>';
				} elseif ($status == 'Failed') {
					echo '<span style="color: red; font-weight: bold;">' . esc_html($status) . '</span>';
				} else {
					echo esc_html($status);
				}
				break;
			case 'date': 
				echo esc_html(get_the_date('', $post_id));
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
				<option value=""><?php _e( 'All Statuses', 'welcome-email-editor' ); ?></option>
				<option value="Success" <?php selected( $selected, 'Success' ); ?>><?php _e( 'Success', 'welcome-email-editor' ); ?></option>
				<option value="Failed" <?php selected( $selected, 'Failed' ); ?>><?php _e( 'Failed', 'welcome-email-editor' ); ?></option>
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

	/**
	 * Restrict access to the email logs menu page
	 */
	public function restrict_access_to_email_logs() {
		// Get the current screen object
    	$screen = get_current_screen();
 
		// Check if the current screen is related to the 'email_logs' post type
		if ( $screen && $screen->post_type === 'email_logs' ) {
			// Check if the user has the 'manage_options' capability
			if ( !current_user_can('manage_options') ) {
				// If not, terminate the script with an error message
				wp_die(__('You do not have sufficient permissions to access this page.', 'welcome-email-editor'));
				
			}
		}
	}

	/**
	 * Hide the email logs menu page from the admin menu 
	 */
	public function hide_email_logs_menu() {
		// Check if the user does not have the 'manage_options' capability
		if ( !current_user_can('manage_options') ) {
			// Remove the 'email_logs' menu page
			remove_menu_page('edit.php?post_type=email_logs');
		}
	}

	/**
	 * Add metabox to the email logs post type
	 */
	public function add_email_logs_metabox() {
		add_meta_box(
			'email_logs_metabox',      
			__('Email Log Details'),   
			array($this, 'email_logs_metabox_callback'),
			'email_logs',              
			'normal',                  
			'high'                     
		);
	}

	/**
	 * Render the metabox content for the email logs post type
	 *
	 * @param [object] $post The post object.
	 */
	public function email_logs_metabox_callback($post) {
		// Retrieve meta data
		$email_type 			= get_post_meta($post->ID, 'email_type', true); 
		$recipient 				= get_post_meta($post->ID, 'recipient', true);
		$status 				= get_post_meta($post->ID, 'status', true);
		$sender 				= get_post_meta($post->ID, 'sender', true);
		$email_error_message 	= get_post_meta($post->ID, 'email_error_message', true);

		// Display the meta data in a table or any structured format
		?>
		<table class="form-table">
			<tr>
				<th><label for="email_type"><?php _e('Email Type'); ?></label></th>
				<td><input type="text" id="email_type" value="<?php echo esc_attr($email_type); ?>" readonly /></td>
			</tr>
			<tr>
				<th><label for="recipient"><?php _e('Recipient'); ?></label></th>
				<td><input type="text" id="recipient" value="<?php echo esc_attr($recipient); ?>" readonly /></td>
			</tr>
		
			<tr>
				<th><label for="sender"><?php _e('Sender'); ?></label></th>
				<td><input type="text" id="sender" value="<?php echo esc_attr($sender); ?>" readonly /></td>
			</tr>
			<tr>
				<th><label for="status"><?php _e('Status'); ?></label></th>
				<td><input type="text" id="status" value="<?php echo esc_attr($status); ?>" readonly /></td>
			</tr>
			<?php if ($email_error_message): // Only show this if there's an error ?>
			<tr>
				<th><label for="email_error_message"><?php _e('Error Message'); ?></label></th>
				<td><textarea id="email_error_message" rows="3" cols="40" readonly><?php echo esc_textarea($email_error_message); ?></textarea></td>
			</tr>
			<?php endif; ?>
		</table>
		<?php
	}

}
