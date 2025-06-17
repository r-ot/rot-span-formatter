<?php
/**
 * SELFHOSTED Plugin Updater für rot-span-formatter
 */


add_filter('site_transient_update_plugins', 'rot_selfhosted_plugin_update_check');
add_filter('plugins_api', 'rot_selfhosted_plugin_info', 10, 3);

function rot_selfhosted_plugin_update_check($transient) {
    if (empty($transient->checked)) {
        return $transient;
    }

    $plugin_slug = 'rot-span-formatter/rot-span-formatter.php';
    $remote_json_url = 'https://r-ot.at/plugin-updates/rot-span-formatter.json';

    // $response = wp_remote_get($remote_json);
    // if (is_wp_error($response)) return $transient;

    // $remote = json_decode(wp_remote_retrieve_body($response));
    // if (!isset($remote->version)) return $transient;
	 $cache_key = 'rot_span_formatter_update_cache';

    $remote = get_transient($cache_key); //inital: empty

    if (!$remote) {
        $response = wp_remote_get($remote_json_url, ['timeout' => 3]);
        if (is_wp_error($response)) {
            return $transient; // Fallback, kein Update
        }

        $remote = json_decode(wp_remote_retrieve_body($response));
        if (!isset($remote->version)) {
            return $transient;
        }

        // Cache it for 6 hours
        set_transient($cache_key, $remote, 6 * HOUR_IN_SECONDS);
    }

    $current_version = get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin_slug)['Version'];

    if (version_compare($remote->version, $current_version, '>')) {
        $transient->response[$plugin_slug] = (object) [
            'slug' => 'rot-span-formatter',
            'plugin' => $plugin_slug,
            'new_version' => $remote->version,
            'url' => $remote->homepage ?? '',
            'package' => $remote->download_url,
        ];
    }

    return $transient;
}

function rot_selfhosted_plugin_info($result, $action, $args) {
    if ($action !== 'plugin_information' || $args->slug !== 'rot-span-formatter') {
        return $result;
    }

    $remote = wp_remote_get('https://r-ot.at/plugin-updates/rot-span-formatter.json');
    if (is_wp_error($remote)) return $result;

    $remote = json_decode(wp_remote_retrieve_body($remote));
    if (empty($remote)) return $result;

    return (object) [
        'name' => 'rot-span-formatter',
        'slug' => 'rot-span-formatter',
        'version' => $remote->version,
        'author' => '<a href="https://github.com/r-ot">r-ot</a>',
        'homepage' => $remote->homepage ?? '',
        'requires' => $remote->requires ?? '6.0',
        'tested' => $remote->tested ?? '6.8.1',
        'last_updated' => $remote->last_updated ?? '',
        'sections' => (array) ($remote->sections ?? []),
        'download_link' => $remote->download_url,
    ];
}

