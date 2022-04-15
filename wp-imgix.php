<?php
/**
 * Plugin Name: WP Imgix
 * Description: Serve your images from Imgix CDN for faster page load times.
 * Version: 0.2.0
 * Author: Gavin McDonald
 */

namespace WPImgix;

/**
 * Disallow direct file access
 */

defined('ABSPATH') || die;

/**
 * Setup constants
 */

define( 'WPIMGIX_PLUGIN_DIR', plugin_dir_path(__FILE__) );
define( 'WPIMGIX_PLUGIN_URL', plugin_dir_url(__FILE__) );
define( 'WPIMGIX_PLUGIN_VERSION', '0.2.0' );

/**
 * Composer setup
 */

require_once ( WPIMGIX_PLUGIN_DIR . 'vendor/autoload.php' );

/**
 * Include framework code
 */

require_once ( WPIMGIX_PLUGIN_DIR . 'framework/class-singleton.php' );

/**
 * Plugin includes
 */

include_once ( WPIMGIX_PLUGIN_DIR . 'admin/class-plugin-settings-page.php' );
include_once ( WPIMGIX_PLUGIN_DIR . 'includes/class-imgix.php' );
include_once ( WPIMGIX_PLUGIN_DIR . 'includes/class-plugin.php' );

/**
 * Bootstrap the plugin
 */

if ( class_exists( __NAMESPACE__ . '\Plugin' ) ) {
    Plugin::instance();
    register_activation_hook( __FILE__, __NAMESPACE__ . '\Plugin::activate');
}

if ( class_exists( __NAMESPACE__ . '\Plugin_Settings_Page' ) ) {
    Plugin_Settings_Page::instance();
}