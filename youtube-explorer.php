<?php
/**
 * Plugin Name:     Youtube Explorer
 * Plugin URI:      https://github.com/ko31/youtube-explorer
 * Description:     Do you want me to help you search YouTube?
 * Author:          ko31
 * Author URI:      https://go-sign.info
 * Text Domain:     youtube-explorer
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Youtube_Explorer
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Initialize plugin.
 */
add_action( 'plugins_loaded', function () {
	load_plugin_textdomain(
		'youtube-explorer',
		false,
		dirname( plugin_basename( __FILE__ ) ) . '/languages'
	);
	require_once( dirname( __FILE__ ) . '/vendor/autoload.php' );
	Gosign\YoutubeExplorer::get_instance()->register();
} );

/**
 * Deactivation.
 */
register_deactivation_hook( __FILE__, function () {
	Gosign\YoutubeExplorer::get_instance()->deactivation();
} );