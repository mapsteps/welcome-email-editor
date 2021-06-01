<?php
/**
 * Plugin Name: Welcome Email Editor
 * Plugin URI: http://wordpress.org/plugins/welcome-email-editor/
 * Description: Welcome Email Editor allows you to change the content, layout, and even add an attachment for many of the built-in WordPress emails.
 * Version: 5.0
 * Author: David Vongries
 * Author URI: https://ultimatedashboard.com
 * Text Domain: welcome-email-editor
 * Domain Path: /languages
 *
 * @package Welcome_Email_Editor
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

// Plugin constants.
define( 'WEED_PLUGIN_DIR', rtrim( plugin_dir_path( __FILE__ ), '/' ) );
define( 'WEED_PLUGIN_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );
define( 'WEED_PLUGIN_VERSION', '5.0' );
define( 'WEED_PLUGIN_NAME', 'Welome Email Editor' );
define( 'WEED_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

// Helper classes.
require __DIR__ . '/helpers/class-screen-helper.php';
require __DIR__ . '/helpers/class-content-helper.php';

// Base module.
require __DIR__ . '/modules/base/class-base-module.php';
require __DIR__ . '/modules/base/class-base-output.php';

// Core classes.
require __DIR__ . '/class-vars.php';
require __DIR__ . '/class-setup.php';

Weed\Setup::init();
