<?php
/**
 * Plugin Name: Swift SMTP (formerly Welcome Email Editor)
 * Description: Swift SMTP is a free & simple SMTP Plugin for WordPress.
 * Version: 6.2
 * Author: David Vongries
 * Author URI: https://davidvongries.com/
 * Text Domain: welcome-email-editor
 *
 * @package Welcome_Email_Editor
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

// Plugin constants.
define( 'WEED_PLUGIN_DIR', rtrim( plugin_dir_path( __FILE__ ), '/' ) );
define( 'WEED_PLUGIN_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );
define( 'WEED_PLUGIN_VERSION', '6.2' );
define( 'WEED_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

// Helper classes.
require __DIR__ . '/helpers/class-screen-helper.php';
require __DIR__ . '/helpers/class-content-helper.php';
require __DIR__ . '/helpers/class-email-helper.php';

// Base module.
require __DIR__ . '/modules/base/class-base-module.php';
require __DIR__ . '/modules/base/class-base-output.php';

// Core classes.
require __DIR__ . '/class-backwards-compatibility.php';
require __DIR__ . '/class-vars.php';
require __DIR__ . '/wp-new-user-notification.php';
require __DIR__ . '/class-setup.php';

Weed\Backwards_Compatibility::init();
Weed\Setup::init();
