<?php
/**
 * Plugin Name: CMM Components
 * Plugin URI: https://github.com/alexbran8/cmm-web-components-releases
 * Description: Pagină permanentă și vitrină WooCommerce pentru atelierele terapeutice Claudia Maria.
 * Version: __PLUGIN_VERSION__
 * Update URI: https://github.com/alexbran8/cmm-web-components-releases
 * Text Domain: cmm-components
 * Requires at least: 6.0
 * Requires PHP: 7.4
 */

if (!defined('ABSPATH')) {
    exit;
}

define('CMMC_VERSION', '__PLUGIN_VERSION__');
define('CMMC_PLUGIN_FILE', __FILE__);
define('CMMC_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CMMC_UPDATE_MANIFEST_URL', 'https://github.com/alexbran8/cmm-web-components-releases/releases/latest/download/update.json');

function cmmc_get_release_data() {
    static $release = null;
    static $requested = false;

    if ($requested) {
        return $release;
    }

    $requested = true;
    $response = wp_remote_get(CMMC_UPDATE_MANIFEST_URL, array(
        'timeout' => 10,
        'headers' => array('Accept' => 'application/json'),
    ));

    if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {
        return $release = false;
    }

    $decoded = json_decode(wp_remote_retrieve_body($response), true);
    return $release = is_array($decoded) ? $decoded : false;
}

function cmmc_check_for_update($update, $plugin_data, $plugin_file, $locales) {
    unset($locales);

    if (plugin_basename(CMMC_PLUGIN_FILE) !== $plugin_file) {
        return $update;
    }

    $release = cmmc_get_release_data();
    if (
        !is_array($release)
        || empty($release['version'])
        || empty($release['download_url'])
        || !version_compare($plugin_data['Version'], $release['version'], '<')
    ) {
        return $update;
    }

    return array(
        'slug' => 'cmm-components',
        'version' => sanitize_text_field($release['version']),
        'url' => esc_url_raw($release['details_url'] ?? ''),
        'package' => esc_url_raw($release['download_url']),
        'tested' => sanitize_text_field($release['tested'] ?? ''),
        'requires_php' => sanitize_text_field($release['requires_php'] ?? ''),
    );
}
add_filter('update_plugins_github.com', 'cmmc_check_for_update', 10, 4);

function cmmc_plugin_information($result, $action, $args) {
    if ('plugin_information' !== $action || empty($args->slug) || 'cmm-components' !== $args->slug) {
        return $result;
    }

    $release = cmmc_get_release_data();
    if (!is_array($release) || empty($release['version'])) {
        return $result;
    }

    $sections = is_array($release['sections'] ?? null) ? $release['sections'] : array();
    return (object) array(
        'name' => sanitize_text_field($release['name'] ?? 'CMM Components'),
        'slug' => 'cmm-components',
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
add_filter('plugins_api', 'cmmc_plugin_information', 10, 3);

function cmmc_register_assets() {
    static $handles = null;
    if (null !== $handles) {
        return $handles;
    }

    $handles = array('script' => '', 'styles' => array());
    $manifest_path = CMMC_PLUGIN_DIR . 'dist/.vite/manifest.json';
    if (!is_readable($manifest_path)) {
        return $handles;
    }

    $manifest = json_decode(file_get_contents($manifest_path), true);
    $entry = is_array($manifest) ? ($manifest['src/main.jsx'] ?? null) : null;
    if (!is_array($entry) || empty($entry['file'])) {
        return $handles;
    }

    $base_url = plugin_dir_url(CMMC_PLUGIN_FILE) . 'dist/';
    $script_handle = 'cmm-components-app';
    wp_register_script($script_handle, $base_url . $entry['file'], array(), CMMC_VERSION, true);
    wp_script_add_data($script_handle, 'type', 'module');
    $handles['script'] = $script_handle;

    foreach (($entry['css'] ?? array()) as $index => $css_file) {
        $style_handle = 'cmm-components-app-' . $index;
        wp_register_style($style_handle, $base_url . $css_file, array(), CMMC_VERSION);
        $handles['styles'][] = $style_handle;
    }

    return $handles;
}

function cmat_get_workshops_page_id() {
    $page_id = absint(get_option('cmat_workshops_page_id'));
    if ($page_id && 'page' === get_post_type($page_id)) {
        return $page_id;
    }

    $page = get_page_by_path('ateliere-terapeutice');
    return $page ? (int) $page->ID : 0;
}

function cmat_activate_plugin() {
    if (taxonomy_exists('product_cat') && !term_exists('ateliere-terapeutice', 'product_cat')) {
        wp_insert_term('Ateliere terapeutice', 'product_cat', array(
            'slug' => 'ateliere-terapeutice',
            'description' => 'Atelierele afișate automat în pagina Ateliere terapeutice.',
        ));
    }

    $page = get_page_by_path('ateliere-terapeutice');
    if (!$page) {
        $page_id = wp_insert_post(array(
            'post_title' => 'Ateliere terapeutice',
            'post_name' => 'ateliere-terapeutice',
            'post_content' => '[cmat_workshops]',
            'post_status' => 'publish',
            'post_type' => 'page',
        ));
        if (!is_wp_error($page_id)) {
            update_option('cmat_workshops_page_id', (int) $page_id);
        }
    } else {
        update_option('cmat_workshops_page_id', (int) $page->ID);
    }
}
register_activation_hook(CMMC_PLUGIN_FILE, 'cmat_activate_plugin');

function cmat_get_workshops() {
    if (!function_exists('wc_get_products')) {
        return array();
    }

    $products = wc_get_products(array(
        'status' => 'publish',
        'limit' => -1,
        'category' => array('ateliere-terapeutice'),
        'orderby' => 'date',
        'order' => 'DESC',
    ));

    return array_map(static function ($product) {
        $image_id = $product->get_image_id();
        $image = $image_id ? wp_get_attachment_image_url($image_id, 'woocommerce_single') : '';
        $image_alt = $image_id ? get_post_meta($image_id, '_wp_attachment_image_alt', true) : '';
        $can_add_to_cart = $product->is_type('simple') && $product->is_purchasable() && $product->is_in_stock();

        return array(
            'id' => $product->get_id(),
            'name' => $product->get_name(),
            'url' => $product->get_permalink(),
            'image' => $image ?: '',
            'imageAlt' => $image_alt ?: $product->get_name(),
            'description' => wp_trim_words(wp_strip_all_tags($product->get_short_description()), 38),
            'priceHtml' => wp_kses_post($product->get_price_html()),
            'addToCartUrl' => $can_add_to_cart ? $product->add_to_cart_url() : $product->get_permalink(),
            'buttonText' => $can_add_to_cart ? 'Adaugă în coș' : 'Vezi opțiunile',
            'reviewsEnabled' => (bool) $product->get_reviews_allowed(),
            'reviewCount' => (int) $product->get_review_count(),
        );
    }, $products);
}

function cmat_render_workshops_shortcode($attributes = array()) {
    $handles = cmmc_register_assets();

    if ($handles['script']) {
        wp_enqueue_script($handles['script']);
    }
    foreach ($handles['styles'] as $style_handle) {
        wp_enqueue_style($style_handle);
    }

    $attributes = shortcode_atts(array(
        'hero_image' => '',
        'divider_image' => '',
    ), $attributes, 'cmat_workshops');

    $page_id = get_queried_object_id();
    $hero_image = esc_url_raw($attributes['hero_image']);
    if (!$hero_image && $page_id && has_post_thumbnail($page_id)) {
        $hero_image = wp_get_attachment_image_url(get_post_thumbnail_id($page_id), 'full');
    }

    $config = array(
        'heroImage' => $hero_image ?: '',
        'dividerImage' => esc_url_raw($attributes['divider_image']),
        'workshops' => cmat_get_workshops(),
    );

    return sprintf(
        '<div data-cmm-components-root data-config="%s"></div>',
        esc_attr(wp_json_encode($config, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
    );
}
add_shortcode('cmat_workshops', 'cmat_render_workshops_shortcode');

function cmat_add_body_class($classes) {
    $page_id = cmat_get_workshops_page_id();
    if ($page_id && is_page($page_id)) {
        $classes[] = 'cmat-workshops-page';
    }
    return $classes;
}
add_filter('body_class', 'cmat_add_body_class');

function cmat_insert_workshops_menu_item($items, $args) {
    unset($args);
    $page_id = cmat_get_workshops_page_id();
    if (!$page_id || 'publish' !== get_post_status($page_id)) {
        return $items;
    }

    $philosophy_index = null;
    $has_blog = false;
    foreach ($items as $index => $item) {
        if ((int) ($item->object_id ?? 0) === $page_id) {
            return $items;
        }
        $title = strtolower(remove_accents(wp_strip_all_tags($item->title ?? '')));
        if (false !== strpos($title, 'filosofia mea')) {
            $philosophy_index = $index;
        }
        if ('blog' === trim($title)) {
            $has_blog = true;
        }
    }

    if (null === $philosophy_index || !$has_blog) {
        return $items;
    }

    $menu_item = (object) array(
        'ID' => -$page_id,
        'db_id' => 0,
        'menu_item_parent' => 0,
        'object_id' => $page_id,
        'object' => 'page',
        'type' => 'post_type',
        'type_label' => 'Pagină',
        'title' => 'Ateliere terapeutice',
        'url' => get_permalink($page_id),
        'target' => '',
        'attr_title' => '',
        'description' => '',
        'classes' => array('menu-item', 'menu-item-type-post_type', 'menu-item-object-page', 'cmat-menu-item'),
        'xfn' => '',
        'status' => 'publish',
    );

    array_splice($items, $philosophy_index + 1, 0, array($menu_item));
    return $items;
}
add_filter('wp_nav_menu_objects', 'cmat_insert_workshops_menu_item', 20, 2);
