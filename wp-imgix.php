<?php
/**
 * Plugin Name: WP Imgix
 * Description: Serve your images from Imgix CDN for faster page load times.
 * Version: 0.3.1
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
define( 'WPIMGIX_PLUGIN_VERSION', '0.3.1' );

/**
 * Composer setup
 */

require_once ( WPIMGIX_PLUGIN_DIR . 'vendor/autoload.php' );

/**
 * Bootstrap the plugin
 */

Plugin::instance();
Admin\PluginSettingsPage::instance();
register_activation_hook( __FILE__, __NAMESPACE__ . '\Plugin::activate');