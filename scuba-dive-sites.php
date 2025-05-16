<?php
/**
 * Plugin Name: Scuba Dive Sites
 * Plugin URI: https://yourwebsite.com
 * Description: Custom plugin for managing scuba diving sites for beginners
 * Version: 1.0.0
 * Author: Joseph Cox
 * Author URI: https://diveworld.info.com
 * Text Domain: scuba-dive-sites
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('SDS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SDS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SDS_PLUGIN_VERSION', '1.0.0');

// Include required files
require_once SDS_PLUGIN_DIR . 'includes/post-types.php';
require_once SDS_PLUGIN_DIR . 'includes/taxonomies.php';
require_once SDS_PLUGIN_DIR . 'includes/meta-boxes.php';
require_once SDS_PLUGIN_DIR . 'includes/shortcodes.php';

// Plugin activation hook
register_activation_hook(__FILE__, 'sds_activate');
function sds_activate() {
    // Code to run on plugin activation
    // Flush rewrite rules after creating custom post types
    flush_rewrite_rules();
}

// Plugin deactivation hook
register_deactivation_hook(__FILE__, 'sds_deactivate');
function sds_deactivate() {
    // Code to run on plugin deactivation
    flush_rewrite_rules();
}
