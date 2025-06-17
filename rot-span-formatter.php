<?php
/**
 * Plugin Name: Rot Span Formatter
 * Description: Fügt dem Gutenberg-Editor eine Möglichkeit hinzu, Text in <span> mit eigener CSS-Klasse zu wrappen.
 * Version: 1.0.0
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
