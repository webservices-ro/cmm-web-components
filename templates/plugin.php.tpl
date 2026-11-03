<?php
/**
 * Plugin Name: {{PLUGIN_NAME}}
 * Plugin URI: {{UPDATE_URI}}
 * Description: A minimal React module for WordPress.
 * Version: __PLUGIN_VERSION__
 * Update URI: {{UPDATE_URI}}
 * Text Domain: {{SLUG}}
 * Requires at least: {{REQUIRES_WORDPRESS}}
 * Requires PHP: {{REQUIRES_PHP}}
 */

if (!defined('ABSPATH')) {
    exit;
}

define('{{PREFIX_UPPER}}_VERSION', '__PLUGIN_VERSION__');
define('{{PREFIX_UPPER}}_PLUGIN_FILE', __FILE__);
define('{{PREFIX_UPPER}}_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('{{PREFIX_UPPER}}_UPDATE_MANIFEST_URL', '{{MANIFEST_URL}}');

function {{PREFIX}}_get_release_data() {
    static $release = null;
    static $requested = false;

    if ($requested) {
        return $release;
    }

    $requested = true;
    $response = wp_remote_get({{PREFIX_UPPER}}_UPDATE_MANIFEST_URL, array(
        'timeout' => 10,
        'headers' => array('Accept' => 'application/json'),
    ));

    if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {
        return $release = false;
    }

    $decoded = json_decode(wp_remote_retrieve_body($response), true);
    return $release = is_array($decoded) ? $decoded : false;
}

function {{PREFIX}}_check_for_update($update, $plugin_data, $plugin_file, $locales) {
    unset($locales);

    if (plugin_basename({{PREFIX_UPPER}}_PLUGIN_FILE) !== $plugin_file) {
        return $update;
    }

    $release = {{PREFIX}}_get_release_data();
    if (
        !is_array($release)
        || empty($release['version'])
        || empty($release['download_url'])
        || !version_compare($plugin_data['Version'], $release['version'], '<')
    ) {
        return $update;
    }

    return array(
        'slug' => '{{SLUG}}',
        'version' => sanitize_text_field($release['version']),
        'url' => esc_url_raw($release['details_url'] ?? ''),
        'package' => esc_url_raw($release['download_url']),
        'tested' => sanitize_text_field($release['tested'] ?? ''),
        'requires_php' => sanitize_text_field($release['requires_php'] ?? ''),
    );
}
add_filter('update_plugins_github.com', '{{PREFIX}}_check_for_update', 10, 4);

function {{PREFIX}}_plugin_information($result, $action, $args) {
    if ('plugin_information' !== $action || empty($args->slug) || '{{SLUG}}' !== $args->slug) {
        return $result;
    }

    $release = {{PREFIX}}_get_release_data();
    if (!is_array($release) || empty($release['version'])) {
        return $result;
    }

    $sections = is_array($release['sections'] ?? null) ? $release['sections'] : array();
    return (object) array(
        'name' => sanitize_text_field($release['name'] ?? '{{PLUGIN_NAME}}'),
        'slug' => '{{SLUG}}',
        'version' => sanitize_text_field($release['version']),
        'homepage' => esc_url_raw($release['details_url'] ?? ''),
        'requires' => sanitize_text_field($release['requires'] ?? '{{REQUIRES_WORDPRESS}}'),
        'tested' => sanitize_text_field($release['tested'] ?? ''),
        'requires_php' => sanitize_text_field($release['requires_php'] ?? '{{REQUIRES_PHP}}'),
        'download_link' => esc_url_raw($release['download_url'] ?? ''),
        'sections' => array(
            'description' => wp_kses_post($sections['description'] ?? ''),
            'installation' => wp_kses_post($sections['installation'] ?? ''),
            'changelog' => wp_kses_post($sections['changelog'] ?? ''),
        ),
    );
}
add_filter('plugins_api', '{{PREFIX}}_plugin_information', 10, 3);

function {{PREFIX}}_register_assets() {
    static $handles = null;
    if (null !== $handles) {
        return $handles;
    }

    $handles = array('script' => '', 'styles' => array());
    $manifest_path = {{PREFIX_UPPER}}_PLUGIN_DIR . 'dist/.vite/manifest.json';
    if (!is_readable($manifest_path)) {
        return $handles;
    }

    $manifest = json_decode(file_get_contents($manifest_path), true);
    $entry = is_array($manifest) ? ($manifest['src/main.jsx'] ?? null) : null;
    if (!is_array($entry) || empty($entry['file'])) {
        return $handles;
    }

    $base_url = plugin_dir_url({{PREFIX_UPPER}}_PLUGIN_FILE) . 'dist/';
    $script_handle = '{{SLUG}}-app';
    wp_register_script($script_handle, $base_url . $entry['file'], array(), {{PREFIX_UPPER}}_VERSION, true);
    wp_script_add_data($script_handle, 'type', 'module');
    $handles['script'] = $script_handle;

    foreach (($entry['css'] ?? array()) as $index => $css_file) {
        $style_handle = '{{SLUG}}-app-' . $index;
        wp_register_style($style_handle, $base_url . $css_file, array(), {{PREFIX_UPPER}}_VERSION);
        $handles['styles'][] = $style_handle;
    }

    return $handles;
}

function {{PREFIX}}_render_shortcode() {
    $handles = {{PREFIX}}_register_assets();

    if ($handles['script']) {
        wp_enqueue_script($handles['script']);
    }
    foreach ($handles['styles'] as $style_handle) {
        wp_enqueue_style($style_handle);
    }

    return '<div data-{{SLUG}}-root></div>';
}
add_shortcode('{{PREFIX}}', '{{PREFIX}}_render_shortcode');
