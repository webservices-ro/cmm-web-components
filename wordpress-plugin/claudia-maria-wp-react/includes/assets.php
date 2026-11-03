<?php
if (!defined('ABSPATH')) exit;

function cm_react_module_script_tag($tag, $handle) {
    if ('cm-react-app' !== $handle) return $tag;
    if (preg_match('/\stype=(["\']).*?\1/i', $tag)) {
        return preg_replace('/\stype=(["\']).*?\1/i', ' type="module"', $tag, 1);
    }
    return preg_replace('/^<script\b/i', '<script type="module"', $tag, 1);
}
add_filter('script_loader_tag', 'cm_react_module_script_tag', 10, 2);

function cm_react_register_assets() {
    static $handles = null;
    if (null !== $handles) return $handles;
    $handles = array('script' => '', 'styles' => array());
    $manifest_path = CM_REACT_PLUGIN_DIR . 'dist/.vite/manifest.json';
    if (!is_readable($manifest_path)) return $handles;
    $manifest = json_decode(file_get_contents($manifest_path), true);
    $entry = is_array($manifest) ? ($manifest['src/main.jsx'] ?? null) : null;
    if (!is_array($entry) || empty($entry['file'])) return $handles;
    $base_url = plugin_dir_url(CM_REACT_PLUGIN_FILE) . 'dist/';
    wp_register_script('cm-react-app', $base_url . $entry['file'], array(), CM_REACT_VERSION, true);
    wp_script_add_data('cm-react-app', 'type', 'module');
    $handles['script'] = 'cm-react-app';
    foreach (($entry['css'] ?? array()) as $index => $css_file) {
        $style_handle = 'cm-react-app-' . $index;
        wp_register_style($style_handle, $base_url . $css_file, array(), CM_REACT_VERSION);
        $handles['styles'][] = $style_handle;
    }
    return $handles;
}

function cm_react_assets() {
    $handles = cm_react_register_assets();
    if ($handles['script']) wp_enqueue_script($handles['script']);
    foreach ($handles['styles'] as $style_handle) wp_enqueue_style($style_handle);
}
add_action('wp_enqueue_scripts', 'cm_react_register_assets', 5);
