<?php
/**
 * @link              https://github.com/Dantolos
 * @since             1.0.0
 * @package           Dw_Navbar
 *
 * @wordpress-plugin
 * Plugin Name:       DW_Navbar
 * Plugin URI:        https://github.com/Dantolos/DW_Navbar/
 * Description:       Wordpress Plugin to integrate global navigation bar from demenzworld.com
 * Version:           1.0.0
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

function dw_navbat() {
     echo get_style();
     echo '<div id="dw__global_navbar">Dies ist ein benutzerdefiniertes Div im Footer</div>';
     echo get_script();
}
 
// Füge das Div im Footer-Bereich hinzu
add_action('wp_head', 'dw_navbat');


//GET CSS File Content in a style-tag
function get_style() {
     $cssFile = __DIR__.'/style.css';
     $style_return = '<style type="text/css">';
     if (file_exists($cssFile)) {
          $style_return .= file_get_contents($cssFile);
     }    
     $style_return .= '</style>'; 
     return $style_return;
}

//GET JS File Content in a script-tag
function get_script() {
     $jsFile = __DIR__.'/script.js';
     $js_return = '<script type="text/javascript">';
     if (file_exists($jsFile)) {
          $js_return .= file_get_contents($jsFile);
     }
     $js_return .= '</script>'; 
     return $js_return;
}