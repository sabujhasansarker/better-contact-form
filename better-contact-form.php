<?php
/**
 * Plugin Name: Better Contact Form
 * Description: A simple contact form plugin that sends an email with the submitted data.
 * Version: 1.0
 * Plugin URI: https://example.com/better-contact-form
 * Author: Your Name
 * Author URI: https://example.com
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * This plugin is a simple contact form that sends an email with the submitted data.
 * It is designed to be easy to use and customize.
 */

/** Exit if accessed directly. */
defined( 'ABSPATH' ) || exit;

define( 'BCF_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'BCF_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
! defined( 'BCF_PLUGIN_VERSION' ) && define( 'BCF_PLUGIN_VERSION',
	preg_match( '/localhost|\.test|\.local/', home_url() )
	? time()
	: ( get_file_data( __FILE__, [ 'Version' => 'Version' ] )['Version'] ?: '1.0.0' )
);

require_once BCF_PLUGIN_PATH . 'includes/class-bcf-shortcode.php';
require_once BCF_PLUGIN_PATH . 'includes/class-bcf-assets.php';
require_once BCF_PLUGIN_PATH . 'includes/class-bcf-post-type.php';
require_once BCF_PLUGIN_PATH . 'admin/class-bcf-admin.php';
require_once BCF_PLUGIN_PATH . 'includes/class-bcf-ajax.php';

class Better_contact_form {
	public function __construct() {
		add_action( 'init', [ $this, 'init' ] );
	}
	public function init() {
		$bcf = new BCF_Post_Type();
		$bcf->register_post_type();
		new BCF_Shortcode();
		new BCF_Ajax();
		new BCF_Assets();
		if ( is_admin() ) {
			new BCF_Admin();
		}

	}
}
new Better_contact_form();