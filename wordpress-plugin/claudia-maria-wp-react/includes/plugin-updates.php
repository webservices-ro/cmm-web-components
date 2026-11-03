<?php
if (!defined('ABSPATH')) exit;

function cm_react_get_release_data() {
    static $release = null;
    static $requested = false;
    if ($requested) return $release;
    $requested = true;
    $response = wp_remote_get(CM_REACT_UPDATE_MANIFEST_URL, array('timeout' => 10, 'headers' => array('Accept' => 'application/json')));
    if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) return $release = false;
    $decoded = json_decode(wp_remote_retrieve_body($response), true);
    return $release = is_array($decoded) ? $decoded : false;
}

function cm_react_check_for_update($update, $plugin_data, $plugin_file, $locales) {
    unset($locales);
    if (plugin_basename(CM_REACT_PLUGIN_FILE) !== $plugin_file) return $update;
    $release = cm_react_get_release_data();
    if (!is_array($release) || empty($release['version']) || empty($release['download_url']) || !version_compare($plugin_data['Version'], $release['version'], '<')) return false;
    return array('slug' => 'claudia-maria-wp-react', 'version' => sanitize_text_field($release['version']), 'url' => esc_url_raw($release['details_url'] ?? ''), 'package' => esc_url_raw($release['download_url']), 'tested' => sanitize_text_field($release['tested'] ?? ''), 'requires_php' => sanitize_text_field($release['requires_php'] ?? '7.4'));
}
add_filter('update_plugins_webservices.ro', 'cm_react_check_for_update', 10, 4);

function cm_react_plugin_information($result, $action, $args) {
    if ('plugin_information' !== $action || empty($args->slug) || 'claudia-maria-wp-react' !== $args->slug) return $result;
    $release = cm_react_get_release_data();
    if (!is_array($release) || empty($release['version'])) return $result;
    $sections = is_array($release['sections'] ?? null) ? $release['sections'] : array();
    return (object) array(
        'name' => sanitize_text_field($release['name'] ?? 'Claudia Maria React Components'), 'slug' => 'claudia-maria-wp-react', 'version' => sanitize_text_field($release['version']),
        'author' => '<a href="https://webservices.ro/">webservices.ro</a>', 'author_profile' => 'https://webservices.ro/', 'homepage' => esc_url_raw($release['details_url'] ?? ''),
        'requires' => sanitize_text_field($release['requires'] ?? '6.0'), 'tested' => sanitize_text_field($release['tested'] ?? '6.8'), 'requires_php' => sanitize_text_field($release['requires_php'] ?? '7.4'), 'download_link' => esc_url_raw($release['download_url'] ?? ''),
        'sections' => array('description' => wp_kses_post($sections['description'] ?? 'React components and pages for the Claudia Maria website.'), 'installation' => wp_kses_post($sections['installation'] ?? 'Install and activate the plugin, then add the <code>[cm_pricing]</code> shortcode to a page.'), 'changelog' => wp_kses_post($sections['changelog'] ?? ('<h4>' . esc_html($release['version']) . '</h4><p>Latest release.</p>'))),
    );
}
add_filter('plugins_api', 'cm_react_plugin_information', 10, 3);
