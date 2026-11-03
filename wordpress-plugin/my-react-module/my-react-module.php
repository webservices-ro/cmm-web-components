<?php
/**
 * Plugin Name: My React Module
 * Plugin URI: https://github.com/owner/my-react-module
 * Description: A minimal React module for WordPress.
 * Version: __PLUGIN_VERSION__
 * Update URI: https://github.com/owner/my-react-module
 * Text Domain: my-react-module
 * Requires at least: 6.0
 * Requires PHP: 7.4
 */

if (!defined('ABSPATH')) {
    exit;
}

define('MRM_VERSION', '__PLUGIN_VERSION__');
define('MRM_PLUGIN_FILE', __FILE__);
define('MRM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MRM_UPDATE_MANIFEST_URL', 'https://github.com/owner/my-react-module/releases/latest/download/update.json');

function mrm_get_release_data() {
    static $release = null;
    static $requested = false;

    if ($requested) {
        return $release;
    }

    $requested = true;
    $response = wp_remote_get(MRM_UPDATE_MANIFEST_URL, array(
        'timeout' => 10,
        'headers' => array('Accept' => 'application/json'),
    ));

    if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {
        return $release = false;
    }

    $decoded = json_decode(wp_remote_retrieve_body($response), true);
    return $release = is_array($decoded) ? $decoded : false;
}

function mrm_check_for_update($update, $plugin_data, $plugin_file, $locales) {
    unset($locales);

    if (plugin_basename(MRM_PLUGIN_FILE) !== $plugin_file) {
        return $update;
    }

    $release = mrm_get_release_data();
    if (
        !is_array($release)
        || empty($release['version'])
        || empty($release['download_url'])
        || !version_compare($plugin_data['Version'], $release['version'], '<')
    ) {
        return $update;
    }

    return array(
        'slug' => 'my-react-module',
        'version' => sanitize_text_field($release['version']),
        'url' => esc_url_raw($release['details_url'] ?? ''),
        'package' => esc_url_raw($release['download_url']),
        'tested' => sanitize_text_field($release['tested'] ?? ''),
        'requires_php' => sanitize_text_field($release['requires_php'] ?? ''),
    );
}
add_filter('update_plugins_github.com', 'mrm_check_for_update', 10, 4);

function mrm_plugin_information($result, $action, $args) {
    if ('plugin_information' !== $action || empty($args->slug) || 'my-react-module' !== $args->slug) {
        return $result;
    }

    $release = mrm_get_release_data();
    if (!is_array($release) || empty($release['version'])) {
        return $result;
    }

    $sections = is_array($release['sections'] ?? null) ? $release['sections'] : array();
    return (object) array(
        'name' => sanitize_text_field($release['name'] ?? 'My React Module'),
        'slug' => 'my-react-module',
        'version' => sanitize_text_field($release['version']),
        'homepage' => esc_url_raw($release['details_url'] ?? ''),
        'requires' => sanitize_text_field($release['requires'] ?? '6.0'),
        'tested' => sanitize_text_field($release['tested'] ?? ''),
        'requires_php' => sanitize_text_field($release['requires_php'] ?? '7.4'),
        'download_link' => esc_url_raw($release['download_url'] ?? ''),
        'sections' => array(
            'description' => wp_kses_post($sections['description'] ?? ''),
            'installation' => wp_kses_post($sections['installation'] ?? ''),
            'changelog' => wp_kses_post($sections['changelog'] ?? ''),
        ),
    );
}
add_filter('plugins_api', 'mrm_plugin_information', 10, 3);

function mrm_register_assets() {
    static $handles = null;
    if (null !== $handles) {
        return $handles;
    }

    $handles = array('script' => '', 'styles' => array());
    $manifest_path = MRM_PLUGIN_DIR . 'dist/.vite/manifest.json';
    if (!is_readable($manifest_path)) {
        return $handles;
    }

    $manifest = json_decode(file_get_contents($manifest_path), true);
    $entry = is_array($manifest) ? ($manifest['src/main.jsx'] ?? null) : null;
    if (!is_array($entry) || empty($entry['file'])) {
        return $handles;
    }

    $base_url = plugin_dir_url(MRM_PLUGIN_FILE) . 'dist/';
    $script_handle = 'my-react-module-app';
    wp_register_script($script_handle, $base_url . $entry['file'], array(), MRM_VERSION, true);
    wp_script_add_data($script_handle, 'type', 'module');
    $handles['script'] = $script_handle;

    foreach (($entry['css'] ?? array()) as $index => $css_file) {
        $style_handle = 'my-react-module-app-' . $index;
        wp_register_style($style_handle, $base_url . $css_file, array(), MRM_VERSION);
        $handles['styles'][] = $style_handle;
    }

    return $handles;
}

function mrm_render_shortcode() {
    $handles = mrm_register_assets();

    if ($handles['script']) {
        wp_enqueue_script($handles['script']);
    }
    foreach ($handles['styles'] as $style_handle) {
        wp_enqueue_style($style_handle);
    }

    return '<div data-my-react-module-root></div>';
}
add_shortcode('mrm', 'mrm_render_shortcode');
