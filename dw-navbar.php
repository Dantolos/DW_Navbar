<?php
/**
 * @link              https://github.com/Dantolos
 * @since             1.0.0
 * @package           Dw_Navbar
 *
 * @wordpress-plugin
 * Plugin Name:       DW Navbar
 * Plugin URI:        https://github.com/Dantolos/DW_Navbar/
 * Description:       Wordpress Plugin to integrate global navigation bar from demenzworld.com
 * Version:           1.0.5
 * Author:            Aaron
 * Author URI:        https://github.com/Dantolos/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       dw-navbar
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
 

define( 'DW_NAVBAR_VERSION', '1.0.0' );

// Load Settings-Page for Wordpress-Backend
require_once(__DIR__.'/options.php');


// Initial Function
function load_navbar_from_api() {
   
     // Check if Navigation is activated on options page - if not; escape
     $active = get_option('dw_navigation_activate');
     if( !$active ){ return; }

     $api_url = get_option('dw_navigation_api_url') ?: 'http://localhost:10038/wp-json/dw/navbar';
     $response = wp_remote_get($api_url);
 
     // escape, if api doesn't work
     if (is_wp_error($response)) { return; }
  
     $body = wp_remote_retrieve_body($response);
     $data = json_decode($body, true);
     
 
     // Enqueue scripts & styles 
     wp_enqueue_style('toplevel-navbar-style', esc_url($data['style_link']), array(), null);
     wp_enqueue_style('toplevel-navbar-style-plugin', plugin_dir_url(__FILE__).'style.css', array(), null);
     wp_enqueue_script('toplevel-navbar-script', esc_url($data['script_link']), array(), null, true);
     wp_enqueue_script('toplevel-navbar-script-plugin', plugin_dir_url(__FILE__).'script.js', array(), null, true);
  
     //RENDER 
     echo '<div id="DW__GLOBAL_NAVBAR" class="initial_hide">'.$data['content'].'</div>';
 } 
 add_action('wp_head',  'load_navbar_from_api');