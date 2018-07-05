<?php
/*
Plugin Name: WordPress Edje Web Push
Description: [Require Coding] Enable this site to relay Web Push VAPID notification.
Plugin URI: https://github.com/hrsetyono/wp-web-push
Author: Henner Setyono
Author URI: http://github.com/hrsetyono
Version: 1.0.0
*/

// If this file is called directly, abort.
if( !defined( 'WPINC' ) ) { die; }
define( 'H_WEBPUSH_VERSION', '0.1.0' );
define( 'H_WEBPUSH_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );

require_once 'vendor/autoload.php';
require_once 'module-web-push/_run.php';

new H_WebPush();
class H_WebPush {
	function __construct() {
		register_activation_hook( __FILE__, array($this, 'activation_hook') );
		register_deactivation_hook( __FILE__, array($this, 'deactivation_hook') );

		$this->load();
		is_admin() ? $this->admin_load() : $this->public_load();
	}

	/*
		Load the required dependencies for BOTH Admin and Public pages.
	*/
	function load() {

	}

	/*
		Load the required dependencies for Admin pages.
	*/
	function admin_load() {

	}

	/*
		Load the required dependencies for Public pages.
	*/
	function public_load() {

	}

	/*
	  The code that runs during plugin activation.
	*/
	function activation_hook() {

	}

	/*
	  The code that runs during plugin deactivation.
	*/
	function deactivation_hook() {

	}
}
