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
 * Version:           1.1.21
 * Author:            Aaron
 * Author URI:        https://github.com/Dantolos/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       dw-navbar
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined("WPINC")) {
    die();
}

define("DW_NAVBAR_VERSION", "1.0.0");

// Load Settings-Page for Wordpress-Backend
require_once __DIR__ . "/options.php";

function get_domain_name($url)
{
    $parsed = parse_url($url);
    return $parsed["host"];
}

function dw__request_api(string $slug, string $domain = ""): ?array
{
    $api_url = get_option("dw_navigation_api_url")
        ? get_option("dw_navigation_api_url") . $slug
        : "https://demenzworld.com/wp-json/dw/" . $slug;

    //add current domain
    $domainname = false;
    if (strlen($domain) > 0) {
        //$domain = "https://demenzjournal.com/"; // test-domain
        $domain = "https://" . $_SERVER["SERVER_NAME"];
        $domainname = get_domain_name($domain);
        $api_url = $api_url . "?dom=" . str_replace(".", "", $domainname);
    }
    //echo $api_url;
    $response = wp_remote_get($api_url);

    //var_dump($response);

    // escape, if api doesn't work
    if (is_wp_error($response)) {
        echo var_dump(
            "Error fetching navbar: " . $response->get_error_message()
        );
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    return $data;
}

// Initial Function
function load_navbar_from_api()
{
    $plugin_version = get_file_data(__FILE__, ["Version" => "Version"])[
        "Version"
    ];

    // Check if Navigation is activated on options page - if not; escape
    $active = get_option("dw_global_plugin");
    if (!$active) {
        return;
    }

    if (get_option("dw_navbar_activate")) {
        $data = dw__request_api("navbar", $_SERVER["HTTP_HOST"]);
        //var_dump($data);

        // Enqueue scripts & styles
        wp_enqueue_style(
            "toplevel-navbar-style",
            esc_url($data["style_link"]),
            [],
            $plugin_version
        );
        wp_enqueue_style(
            "toplevel-navbar-style-plugin",
            plugin_dir_url(__FILE__) . "style.css",
            [],
            $plugin_version
        );

        // Enqueue scripts
        wp_enqueue_script(
            "toplevel-navbar-script",
            esc_url($data["script_link"]),
            [],
            $plugin_version,
            true
        );
        wp_enqueue_script(
            "toplevel-navbar-script-plugin",
            plugin_dir_url(__FILE__) . "script.js",
            [],
            $plugin_version,
            true
        );

        //RENDER
        echo '<div id="DW__GLOBAL_NAVBAR" class="initial_hide">' .
            $data["content"] .
            "</div>";
    }

    if (get_option("dw_footer_activate")) {
        //hide default footer in themes for journal and wiki
        add_filter("display_default_footer", "__return_false");

        $data_footer = dw__request_api("footer");

        // Enqueue scripts & styles for footer
        wp_enqueue_style(
            "toplevel-footer-style",
            esc_url($data_footer["style_link"]),
            [],
            $plugin_version
        );
        wp_enqueue_script(
            "toplevel-footer-script",
            esc_url($data_footer["script_link"]),
            [],
            $plugin_version,
            true
        );

        //RENDER
        echo '<div id="DW__GLOBAL_FOOTER" class="initial_hide">' .
            $data_footer["content"] .
            "</div>";
    }
}
add_action("wp_head", "load_navbar_from_api");
