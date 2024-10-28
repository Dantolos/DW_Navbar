<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

$plugin_data = get_file_data( __FILE__, array( 'Version' => 'Version' ) );
$plugin_version = $plugin_data['Version'];

// Funktion zum Hinzufügen der Optionsseite im Admin-Menü
function dw_global_navigation_add_admin_menu() {
     add_options_page(
         'DW Global Navigation Einstellungen',  // Seitentitel
         'DW Navigation',                       // Menü-Titel
         'manage_options',                      // Berechtigung
         'dw_global_navigation',                // Menü-Slug
         'dw_global_navigation_options_page'    // Callback-Funktion
     );
 }
 add_action('admin_menu', 'dw_global_navigation_add_admin_menu');
 
 // Funktion zum Registrieren der Plugin-Einstellungen
 function dw_global_navigation_settings_init() {
     // Navigation Aktivieren/Deaktivieren
     register_setting('dw_global_navigation_settings_group', 'dw_navigation_activate');
     // API URL
     register_setting('dw_global_navigation_settings_group', 'dw_navigation_api_url');
 
     add_settings_section(
         'dw_global_navigation_settings_section', // ID der Sektion
         'Globale Navigationseinstellungen',      // Titel der Sektion
         'dw_global_navigation_settings_section_callback', // Beschreibung (optional)
         'dw_global_navigation'                   // Seite (Slug), der die Sektion hinzugefügt wird
     );
     
     add_settings_field(
          'dw_navigation_activate',              // ID des Felds
          'Navigation aktivieren/deaktivieren',  // Label des Felds
          'dw_navigation_activate_render',       // Callback-Funktion zum Rendern des Felds
          'dw_global_navigation',                // Seite (Slug)
          'dw_global_navigation_settings_section'// Sektion
     );
 
     // CSS-Link-Feld
     add_settings_field(
         'dw_navigation_api_url',              // ID des Felds
         'API URL',             // Label des Felds
         'dw_navigation_api_url_render',       // Callback-Funktion zum Rendern des Felds
         'dw_global_navigation',                // Seite (Slug)
         'dw_global_navigation_settings_section'// Sektion
     );
 
  
 }
 add_action('admin_init', 'dw_global_navigation_settings_init');
 
 // Callback für die Sektion (optional)
 function dw_global_navigation_settings_section_callback() {
     echo '<p><strong>'.$plugin_version.'</strong></p>';
     echo '<p>Konfigurieren Sie hier die globale Navigation.</p>';
 }
 
 function dw_navigation_activate_render() {
      $activate = get_option('dw_navigation_activate');
      ?>
      <label for="dw_navigation_activate">
        <input type="checkbox" id="dw_navigation_activate" name="dw_navigation_activate" value="1" <?php checked(1, $activate, true); ?> />
        Navigation aktivieren
    </label>
     <?php
 }
 
 // Render-Funktion für das CSS-Link-Eingabefeld
 function dw_navigation_api_url_render() {
     $api_url = get_option('dw_navigation_api_url');
     ?>
     <input type="url" name="dw_navigation_api_url" value="<?php echo esc_url($api_url); ?>" placeholder="URL API Endpoint" />
     <?php
 }
 
 
 // Funktion zum Rendern der Optionsseite
 function dw_global_navigation_options_page() {
     ?>
     <form action="options.php" method="post">
         <?php
         settings_fields('dw_global_navigation_settings_group'); // Sicherheit für das Formular
         do_settings_sections('dw_global_navigation');           // Abschnitt und Felder rendern
         submit_button('Einstellungen speichern');               // Speichern-Button
         ?>
     </form>
     <?php
 }
 
 // Funktion zum Einfügen der globalen Navigation im Header
 function dw_insert_global_navigation() {
     // CSS und JS von den gespeicherten Optionen laden
     $css_link = get_option('dw_navigation_css_link');
     $js_link = get_option('dw_navigation_js_link');
     $html = get_option('dw_navigation_html');
 
     // CSS einfügen
     if (!empty($css_link)) {
         wp_enqueue_style('dw-navigation-style', esc_url($css_link));
     }
 
     // JS einfügen
     if (!empty($js_link)) {
         wp_enqueue_script('dw-navigation-script', esc_url($js_link), array(), null, true);
     }
 
     // HTML der Navigation ausgeben
     if (!empty($html)) {
         echo '<div id="dw__global_navbar">' . $html . '</div>';
     }
 }
 
 // Navigation im Header anzeigen
 add_action('wp_head', 'dw_insert_global_navigation');
