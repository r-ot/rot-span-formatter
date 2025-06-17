<?php
/**
 * GitHub Plugin Updater für rot-span-formatter
 */

add_filter('site_transient_update_plugins', 'rot_check_for_github_update');
add_filter('plugins_api', 'rot_github_plugin_api_call', 10, 3);

function rot_check_for_github_update($transient) {
    if (empty($transient->checked)) {
        return $transient;
    }

    $plugin_slug = 'rot-span-formatter/rot-span-formatter.php';
    $github_user = 'r-ot';
    $github_repo = 'rot-span-formatter';

    $github_api = 'https://api.github.com/repos/'.$github_user.'/'.$github_repo.'/releases/latest';
    $response = wp_remote_get($github_api, ['headers' => ['User-Agent' => 'WordPress']]);

    if (is_wp_error($response)) {
        return $transient;
    }

    $release = json_decode(wp_remote_retrieve_body($response));
    if (!isset($release->tag_name)) {
        return $transient;
    }

    $latest_version = ltrim($release->tag_name, 'v');
    $current_version = get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin_slug)['Version'];

    if (version_compare($latest_version, $current_version, '>')) {
        $plugin_data = [
            'slug' => 'rot-span-formatter',
            'plugin' => $plugin_slug,
            'new_version' => $latest_version,
            'url' => $release->html_url,
            'package' => $release->zipball_url,
        ];
        $transient->response[$plugin_slug] = (object) $plugin_data;
    }

    return $transient;
}

function rot_github_plugin_api_call($result, $action, $args) {
    if ($action !== 'plugin_information' || $args->slug !== 'rot-span-formatter') {
        return $result;
    }

    return (object) [
        'name' => 'rot-span-formatter',
        'slug' => 'rot-span-formatter',
        'version' => '1.0.0', // Platzhalter
        'author' => '<a href="https://github.com/r-ot">r-ot</a>',
        'homepage' => 'https://github.com/r-ot/rot-span-formatter',
        'sections' => [
            'description' => 'Füge benutzerdefinierte <span>-Formatierung in Gutenberg-Absätzen hinzu.',
        ],
    ];
}
