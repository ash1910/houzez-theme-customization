<?php
/*
Plugin Name: Houzez Theme - Developer
Plugin URI:  http://themeforest.net/user/favethemes
Description: Adds Developer Post Type functionality to Favethemes Themes
Version:     0.0.1
Author:      Favethemes
Author URI:  http://themeforest.net/user/favethemes
License:     GPL2
*/
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'HOUZEZ_DEV_PLUGIN_URL',               plugin_dir_url( __FILE__ )); // Plugin directory URL
define( 'HOUZEZ_DEV_PLUGIN_DIR', 			   plugin_dir_path( __FILE__ ) ); // Plugin directory path
define( 'HOUZEZ_DEV_PLUGIN_PATH',              dirname( __FILE__ ));
define( 'HOUZEZ_DEV_PLUGIN_IMAGES_URL',        HOUZEZ_DEV_PLUGIN_URL  . 'assets/images/');
define( 'HOUZEZ_DEV_TEMPLATES',                HOUZEZ_DEV_PLUGIN_PATH . '/templates/');
define( 'HOUZEZ_DEV_DS',                       DIRECTORY_SEPARATOR);
define( 'HOUZEZ_DEV_PLUGIN_BASENAME',          plugin_basename(__FILE__));
define( 'HOUZEZ_DEV_VERSION', '0.0.1' );
define( 'HOUZEZ_DEV_DB_VERSION', '1.0' );
define( 'HOUZEZ_DEV_PLUGIN_CORE_VERSION', '3.2.3' );

//Main plugin file
require_once 'classes/class-houzez-init.php';

//register_activation_hook( __FILE__, array( 'Houzez_Dev', 'houzez_plugin_activation' ) );
//register_deactivation_hook( __FILE__, array( 'Houzez_Dev', 'houzez_plugin_deactivate' ) );

/**
 * Load plugin textdomain.
 *
 * @since 1.0.0
 */
// function houzez_textdomain() {
//     load_plugin_textdomain( 'houzez-theme-functionality', false, basename( dirname( __FILE__ ) ) . '/languages' );
// }
// add_action( 'init', 'houzez_textdomain' );

// Initialize plugin.
Houzez_Dev::run();