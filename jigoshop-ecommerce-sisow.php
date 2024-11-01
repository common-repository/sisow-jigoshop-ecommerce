<?php
/**
 * Plugin Name: Jigoshop Ecommere Sisow Payment Methods
 * Plugin URI: https://wordpress.org/plugins/sisow-for-jigoshop/
 * Description: Sisow payment methods for Jigoshop Ecommerce
 * Version: 4.9.0
 * Author: Sisow
 * Author URI: http://www.sisow.nl
 * Requires at least: 3.0.1
 * Tested up to: 4.8.3
 *
 * Text Domain: jigoshop2-sisow
 * Domain Path: /languages/
 */
 
 //textdomain inladen
load_plugin_textdomain( 'jigoshop2-sisow', false, plugin_basename( dirname( __FILE__ ) ) . "/languages" );
 
// Define plugin directory for inclusions
define('JIGOSHOP_SISOW_GATEWAY_DIR', dirname(__FILE__));
// Define plugin URL for assets
define('JIGOSHOP_SISOW_GATEWAY_URL', plugins_url('', __FILE__));

add_action('plugins_loaded', function () {
	require_once(JIGOSHOP_SISOW_GATEWAY_DIR . '/src/Jigoshop/Extension/SisowGateway/Gateways.php');
});
