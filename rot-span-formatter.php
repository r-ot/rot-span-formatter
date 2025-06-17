<?php
/**
 * Plugin Name: Rot Span Formatter
 * Description: Fügt dem Gutenberg-Editor eine Möglichkeit hinzu, Text in <span> mit eigener CSS-Klasse zu wrappen. Pluginordner-Name muss "rot-span-formatter" sein!
 * Version: 1.0.2
 * Author: r.ot
 */

add_action('enqueue_block_editor_assets', function () {
    wp_enqueue_script(
        'rot-span-formatter',
        plugin_dir_url(__FILE__) . 'block-editor.js',
        [ 'wp-rich-text', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n' ],
        filemtime(plugin_dir_path(__FILE__) . 'block-editor.js'),
        true
    );
});


require_once plugin_dir_path(__FILE__) . 'github-updater.php';

//für updater prüfen ob der verzeichnisname stimmt
//für updater prüfen ob der verzeichnisname stimmt
//für updater prüfen ob der verzeichnisname stimmt
add_action('admin_init', 'rot_check_plugin_directory_name');
function rot_check_plugin_directory_name() {
    // Plugin-Dateipfad
    $plugin_path = plugin_basename(__FILE__); // z.B. rot-span-formatter/rot-span-formatter.php

    // Extrahiere Verzeichnisname
    $expected_folder = 'rot-span-formatter';
    $actual_folder = dirname($plugin_path);

    if ($actual_folder !== $expected_folder) {
        add_action('admin_notices', function() use ($actual_folder, $expected_folder) {
            echo '<div class="notice notice-error"><p><strong>Achtung:</strong> Das Plugin <code>rot-span-formatter</code> liegt im Verzeichnis <code>' . esc_html($actual_folder) . '</code>, es sollte aber <code>' . esc_html($expected_folder) . '</code> heißen. Bitte benenne den Ordner um und aktivier das plugin neu, sonst funktionieren automatische Updates über GitHub nicht korrekt.</p></div>';
        });
    }else{
		add_action('admin_notices', function() use ($actual_folder, $expected_folder) {
            echo '<div class="notice notice-error"><p><strong>Geil:</strong> Das Plugin <code>rot-span-formatter</code> liegt im Verzeichnis <code>' . esc_html($actual_folder) . '</code>, und soll in  <code>' . esc_html($expected_folder) . '</code> sein. Coole Sache.</p></div>';
        });
	}
}