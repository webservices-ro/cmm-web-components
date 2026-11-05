<?php
/**
 * Plugin Name: CMM Components
 * Plugin URI: https://github.com/alexbran8/cmm-web-components-releases
 * Description: Pagini și componente pentru atelierele terapeutice Claudia Maria.
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

function cmmc_mark_app_script_as_module($tag, $handle, $src) {
    if ('cmm-components-app' !== $handle) {
        return $tag;
    }

    return sprintf(
        '<script type="module" id="%s-js" src="%s"></script>' . "\n",
        esc_attr($handle),
        esc_url($src)
    );
}
add_filter('script_loader_tag', 'cmmc_mark_app_script_as_module', 10, 3);

function cmat_get_workshops_page_id() {
    $page_id = absint(get_option('cmat_workshops_page_id'));
    if ($page_id && 'page' === get_post_type($page_id)) {
        return $page_id;
    }

    $page = get_page_by_path('ateliere-terapeutice');
    return $page ? (int) $page->ID : 0;
}

function cmmc_register_workshop_post_type() {
    register_post_type('cmm_atelier', array(
        'labels' => array(
            'name' => 'Ateliere',
            'singular_name' => 'Atelier',
            'add_new' => 'Adaugă atelier',
            'add_new_item' => 'Adaugă atelier nou',
            'edit_item' => 'Editează atelierul',
            'new_item' => 'Atelier nou',
            'view_item' => 'Vezi atelierul',
            'search_items' => 'Caută ateliere',
            'not_found' => 'Nu a fost găsit niciun atelier.',
            'not_found_in_trash' => 'Nu există ateliere în gunoi.',
            'all_items' => 'Toate atelierele',
            'menu_name' => 'Ateliere',
        ),
        'public' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-palmtree',
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'page-attributes'),
        'has_archive' => false,
        'rewrite' => array(
            'slug' => 'atelier',
            'with_front' => false,
        ),
    ));
}
add_action('init', 'cmmc_register_workshop_post_type');

function cmmc_maybe_flush_workshop_rewrites() {
    if (get_option('cmmc_rewrite_version') === CMMC_VERSION) {
        return;
    }

    flush_rewrite_rules();
    update_option('cmmc_rewrite_version', CMMC_VERSION, false);
}
add_action('init', 'cmmc_maybe_flush_workshop_rewrites', 20);

function cmat_activate_plugin() {
    cmmc_register_workshop_post_type();

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

    flush_rewrite_rules();
    update_option('cmmc_rewrite_version', CMMC_VERSION, false);
}
register_activation_hook(CMMC_PLUGIN_FILE, 'cmat_activate_plugin');

function cmmc_deactivate_plugin() {
    flush_rewrite_rules();
}
register_deactivation_hook(CMMC_PLUGIN_FILE, 'cmmc_deactivate_plugin');

function cmat_get_workshops() {
    $workshops = get_posts(array(
        'post_type' => 'cmm_atelier',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC',
    ));

    return array_map(static function ($workshop) {
        $image_id = get_post_thumbnail_id($workshop);
        $image = $image_id ? wp_get_attachment_image_url($image_id, 'large') : '';
        $image_alt = $image_id ? get_post_meta($image_id, '_wp_attachment_image_alt', true) : '';
        $description = has_excerpt($workshop)
            ? get_the_excerpt($workshop)
            : wp_trim_words(wp_strip_all_tags(strip_shortcodes($workshop->post_content)), 38);

        return array(
            'id' => (int) $workshop->ID,
            'name' => get_the_title($workshop),
            'url' => get_permalink($workshop),
            'image' => $image ?: '',
            'imageAlt' => $image_alt ?: get_the_title($workshop),
            'description' => $description,
        );
    }, $workshops);
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
        'divider_image' => 'https://claudiamaria.ro/wp-content/uploads/2026/03/Linie-ornamentala.webp',
    ), $attributes, 'cmat_workshops');

    $page_id = get_queried_object_id();
    $hero_image = esc_url_raw($attributes['hero_image']);
    if (!$hero_image && $page_id && has_post_thumbnail($page_id)) {
        $hero_image = wp_get_attachment_image_url(get_post_thumbnail_id($page_id), 'full');
    }

    $config = array(
        'component' => 'workshops',
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

/**
 * Render one of the React pages inside any WordPress post type.
 *
 * Usage: [cmm_component page="daruri-sacre-2026"]
 */
function cmmc_render_component_shortcode($attributes = array()) {
    $attributes = shortcode_atts(array(
        'page' => '',
        'reservation_url' => '',
        'presentation_url' => '',
        'product_id' => '2047',
    ), $attributes, 'cmm_component');

    $page = sanitize_key($attributes['page']);
    $allowed_pages = array('daruri-sacre-2026', 'daruri-sacre-product-description', 'workshops');
    if (!in_array($page, $allowed_pages, true)) {
        return '';
    }

    if ('workshops' === $page) {
        return cmat_render_workshops_shortcode();
    }

    $handles = cmmc_register_assets();
    if ($handles['script']) {
        wp_enqueue_script($handles['script']);
    }
    foreach ($handles['styles'] as $style_handle) {
        wp_enqueue_style($style_handle);
    }

    $reservation_url = esc_url_raw($attributes['reservation_url']);
    $product_id = absint($attributes['product_id']);
    if (!$reservation_url && 'daruri-sacre-2026' === $page && $product_id && function_exists('wc_get_product')) {
        $product = wc_get_product($product_id);
        if ($product && $product->is_purchasable() && $product->is_in_stock()) {
            $reservation_url = $product->add_to_cart_url();
        }
    }

    $config = array(
        'component' => $page,
        'reservationUrl' => esc_url_raw($reservation_url),
        'presentationUrl' => esc_url_raw($attributes['presentation_url']),
    );

    return sprintf(
        '<div data-cmm-components-root data-config="%s"></div>',
        esc_attr(wp_json_encode($config, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
    );
}
add_shortcode('cmm_component', 'cmmc_render_component_shortcode');

/**
 * Return the Daruri Sacre line items from an order.
 *
 * The filter keeps the email reusable if the WooCommerce product is replaced.
 */
function cmmc_get_daruri_sacre_order_items($order) {
    if (!is_a($order, 'WC_Order')) {
        return array();
    }

    $product_id = (int) apply_filters('cmmc_daruri_sacre_product_id', 2047);
    return array_filter($order->get_items('line_item'), static function ($item) use ($product_id) {
        return $product_id === (int) $item->get_product_id()
            || $product_id === (int) $item->get_variation_id();
    });
}

/**
 * Send the workshop confirmation once WooCommerce confirms payment.
 *
 * WC_Mailer::wrap_message() deliberately uses the shop's current WooCommerce
 * email header, footer and styles instead of introducing a separate design.
 */
function cmmc_send_daruri_sacre_confirmation_email($order_id) {
    if (!function_exists('wc_get_order') || !function_exists('WC')) {
        return;
    }

    $order = wc_get_order($order_id);
    $items = cmmc_get_daruri_sacre_order_items($order);
    if (!$order || !$items || 'yes' === $order->get_meta('_cmmc_daruri_confirmation_sent')) {
        return;
    }

    $recipient = $order->get_billing_email();
    if (!$recipient) {
        return;
    }

    $first_name = trim($order->get_billing_first_name());
    if (!$first_name) {
        $first_name = trim($order->get_shipping_first_name());
    }

    $contribution = 0.0;
    foreach ($items as $item) {
        $contribution += (float) $item->get_total() + (float) $item->get_total_tax();
    }

    $product_id = (int) apply_filters('cmmc_daruri_sacre_product_id', 2047);
    $conditions_url = get_permalink($product_id);
    $greeting = $first_name ? sprintf('Bună, %s,', esc_html($first_name)) : 'Bună,';
    $formatted_contribution = wc_price($contribution, array('currency' => $order->get_currency()));

    ob_start();
    ?>
    <p><?php echo $greeting; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
    <p>Îți mulțumesc pentru înscriere și pentru alegerea de a lua parte la <strong>Daruri Sacre – Ediția 2026</strong>.</p>
    <p>Mă bucur că ne vom întâlni pe 12 decembrie, într-un spațiu dedicat aromelor botanice, creativității și reconectării senzoriale.</p>
    <h2>Locul tău este rezervat!</h2>
    <p>Iată detaliile întâlnirii noastre:</p>
    <table cellspacing="0" cellpadding="6" style="width:100%; border:1px solid #e5e5e5; margin-bottom:24px;" border="1" bordercolor="#e5e5e5">
        <tr>
            <td colspan="2"><strong>DARURI SACRE – EDIȚIA 2026</strong><br>Atelier de parfumerie botanică și reconectare senzorială</td>
        </tr>
        <tr><th scope="row" style="text-align:left;">Data</th><td>12 decembrie 2026</td></tr>
        <tr><th scope="row" style="text-align:left;">Interval orar</th><td>10:00–15:00</td></tr>
        <tr><th scope="row" style="text-align:left;">Locația</th><td>București</td></tr>
        <tr><th scope="row" style="text-align:left;">Contribuție achitată</th><td><?php echo wp_kses_post($formatted_contribution); ?></td></tr>
        <tr><th scope="row" style="text-align:left;">Status rezervare</th><td><strong>Confirmată</strong></td></tr>
    </table>
    <p>Te rog să păstrezi acest e-mail ca dovadă a rezervării.</p>
    <p>Dacă, din motive personale, nu vei mai putea participa, te rog să mă informezi în scris. Se aplică condițiile de rezervare și anulare prezentate pe pagina atelierului<?php if ($conditions_url) : ?>, pe care le poți consulta <a href="<?php echo esc_url($conditions_url); ?>">aici</a><?php endif; ?>.</p>
    <p>Înainte de eveniment îți voi trimite un nou mesaj cu adresa exactă, câteva informații practice și toate detaliile de care ai nevoie pentru întâlnirea noastră.</p>
    <p>Până atunci, îți doresc să păstrezi puțin din curiozitatea și bucuria cu care ai ales această experiență. Dacă între timp ai întrebări pentru mine, sunt la un e-mail distanță.</p>
    <p>Abia aștept să ne întâlnim și să descoperim împreună universul fascinant al aromelor botanice.</p>
    <p>Cu bucurie,<br><strong>Claudia Maria Munariu</strong></p>
    <p><em>Onorează natura din tine!</em></p>
    <?php
    $content = ob_get_clean();
    $mailer = WC()->mailer();
    $heading = 'Locul tău este rezervat!';
    $message = $mailer->wrap_message($heading, $content);
    $message = $mailer->style_inline($message);
    $subject = 'Confirmarea rezervării tale la Daruri Sacre – Ediția 2026';

    if ($mailer->send($recipient, $subject, $message, "Content-Type: text/html\r\n")) {
        $order->update_meta_data('_cmmc_daruri_confirmation_sent', 'yes');
        $order->save();
        $order->add_order_note('E-mailul personalizat de confirmare Daruri Sacre a fost trimis.');
    }
}
add_action('woocommerce_payment_complete', 'cmmc_send_daruri_sacre_confirmation_email');

/**
 * Treat paid Processing and Completed orders as confirmed orders.
 */
function cmmc_maybe_send_daruri_sacre_confirmation_email($order_id, $old_status, $new_status, $order) {
    unset($old_status);

    if (
        !in_array($new_status, array('processing', 'completed'), true)
        || !is_a($order, 'WC_Order')
        || !$order->get_date_paid()
    ) {
        return;
    }

    cmmc_send_daruri_sacre_confirmation_email($order_id);
}
add_action('woocommerce_order_status_changed', 'cmmc_maybe_send_daruri_sacre_confirmation_email', 10, 4);

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
