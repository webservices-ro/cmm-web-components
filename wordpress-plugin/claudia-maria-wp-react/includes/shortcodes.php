<?php
if (!defined('ABSPATH')) exit;

function cm_pricing_shortcode($attributes = array()) {
    $s = cm_react_get_settings();
    $a = shortcode_atts(array('booking_action' => $s['booking_action'], 'booking_url' => '#', 'popup_id' => $s['popup_id'], 'contact_url' => '#', 'nature_url' => $s['nature_url'], 'use_module_styling' => 'false', 'whatsapp_generic_message' => $s['whatsapp_generic_message'], 'whatsapp_initial_message' => $s['whatsapp_initial_message'], 'whatsapp_loyalty_message' => $s['whatsapp_loyalty_message'], 'whatsapp_number' => $s['whatsapp_number'], 'whatsapp_online_message' => $s['whatsapp_online_message']), $attributes, 'cm_pricing');
    cm_react_assets();
    return sprintf('<div data-cm-pricing-root data-booking-action="%1$s" data-booking-url="%2$s" data-booking-popup-id="%3$d" data-contact-url="%4$s" data-nature-url="%5$s" data-use-module-styling="%6$s" data-whatsapp-generic-message="%7$s" data-whatsapp-initial-message="%8$s" data-whatsapp-loyalty-message="%9$s" data-whatsapp-number="%10$s" data-whatsapp-online-message="%11$s"></div>', 'whatsapp' === strtolower((string) $a['booking_action']) ? 'whatsapp' : 'popup', esc_url($a['booking_url']), absint($a['popup_id']), esc_url($a['contact_url']), esc_url($a['nature_url']), filter_var($a['use_module_styling'], FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false', esc_attr(sanitize_textarea_field($a['whatsapp_generic_message'])), esc_attr(sanitize_textarea_field($a['whatsapp_initial_message'])), esc_attr(sanitize_textarea_field($a['whatsapp_loyalty_message'])), esc_attr(preg_replace('/\D+/', '', $a['whatsapp_number'])), esc_attr(sanitize_textarea_field($a['whatsapp_online_message'])));
}
add_shortcode('cm_pricing', 'cm_pricing_shortcode');

function cm_react_mini_cart_html() {
    if (!function_exists('woocommerce_mini_cart')) return '<p class="woocommerce-mini-cart__empty-message">Coșul este gol.</p>';
    ob_start(); woocommerce_mini_cart(); return (string) ob_get_clean();
}

function cm_header_shortcode($attributes = array()) {
    $s = cm_react_get_settings();
    $a = shortcode_atts(array('account_url' => '/contul-meu/', 'booking_label' => 'PROGRAMEAZĂ', 'booking_url' => '/contact/', 'cart_drawer' => 'true', 'cart_url' => '', 'home_url' => '/', 'login_url' => '/contul-meu/', 'logout_url' => '', 'popup_id' => '', 'pricing_url' => '/preturi/', 'show_booking' => 'true', 'sticky' => 'true', 'whatsapp_message' => 'Bună, Claudia! Am văzut site-ul și aș dori să mă programez. Îmi poți spune ce disponibilități ai?', 'whatsapp_number' => $s['whatsapp_number']), $attributes, 'cm_header');
    cm_react_assets(); $cart_url = $a['cart_url']; $cart_count = 0;
    if (!$cart_url) $cart_url = function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/cos/');
    if (function_exists('WC') && WC()->cart) { $cart_count = WC()->cart->get_cart_contents_count(); wp_enqueue_script('wc-cart-fragments'); }
    $logout_url = $a['logout_url'] ?: wp_logout_url(home_url('/'));
    return sprintf('<div data-cm-header-root data-account-url="%1$s" data-booking-url="%2$s" data-booking-popup-id="%3$d" data-cart-count="%4$d" data-cart-url="%5$s" data-home-url="%6$s" data-is-logged-in="%7$s" data-login-url="%8$s" data-logout-url="%9$s" data-pricing-url="%10$s" data-cart-drawer="%11$s" data-mini-cart-html="%12$s" data-booking-label="%13$s" data-show-booking="%14$s" data-whatsapp-message="%15$s" data-whatsapp-number="%16$s" data-sticky="%17$s"></div>', esc_url($a['account_url']), esc_url($a['booking_url']), absint($a['popup_id']), absint($cart_count), esc_url($cart_url), esc_url($a['home_url']), is_user_logged_in() ? 'true' : 'false', esc_url($a['login_url']), esc_url($logout_url), esc_url($a['pricing_url']), filter_var($a['cart_drawer'], FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false', esc_attr(cm_react_mini_cart_html()), esc_attr($a['booking_label']), filter_var($a['show_booking'], FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false', esc_attr(sanitize_textarea_field($a['whatsapp_message'])), esc_attr(preg_replace('/\D+/', '', (string) $a['whatsapp_number'])), filter_var($a['sticky'], FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false');
}
add_shortcode('cm_header', 'cm_header_shortcode');

function cm_footer_shortcode($attributes = array()) {
    $a = shortcode_atts(array('brand' => 'CLAUDIA MARIA', 'bridge' => 'Centrul Sakura by Claudia Maria', 'contact_text' => 'Programările se stabilesc telefonic, după ce discutăm despre nevoile tale.', 'copyright' => '© Claudia Maria — Toate drepturile rezervate', 'email' => 'claudiamariamed@gmail.com', 'facebook_url' => '', 'home_url' => '/', 'instagram_url' => '', 'logo_image_url' => '', 'payment_heading' => 'Informații despre plată', 'payment_note' => 'Notă pentru implementare: înlocuiți aceste marcaje cu siglele oficiale numai dacă metodele respective sunt efectiv disponibile. Verificați separat obligațiile de informare legală; elementele obligatorii nu trebuie ascunse sau eliminate.', 'payment_text' => 'Detaliile privind plata și eventualele condiții de programare sunt comunicate înainte de confirmarea serviciului. Dacă pe site se activează plata online, aici pot fi afișate metodele de plată acceptate, folosind siglele oficiale corespunzătoare.', 'phone' => '0769 231 018', 'show_payment_info' => 'false', 'tagline' => 'Un spațiu în care corpul tău este ascultat, înțeles și sprijinit.', 'youtube_url' => ''), $attributes, 'cm_footer');
    cm_react_assets();
    return sprintf('<div data-cm-footer-root data-brand="%1$s" data-bridge="%2$s" data-contact-text="%3$s" data-copyright="%4$s" data-email="%5$s" data-facebook-url="%6$s" data-home-url="%7$s" data-instagram-url="%8$s" data-payment-heading="%9$s" data-payment-note="%10$s" data-payment-text="%11$s" data-phone="%12$s" data-show-payment-info="%13$s" data-tagline="%14$s" data-youtube-url="%15$s" data-logo-image-url="%16$s"></div>', esc_attr($a['brand']), esc_attr($a['bridge']), esc_attr($a['contact_text']), esc_attr($a['copyright']), esc_attr(sanitize_email($a['email'])), esc_url($a['facebook_url']), esc_url($a['home_url']), esc_url($a['instagram_url']), esc_attr($a['payment_heading']), esc_attr($a['payment_note']), esc_attr($a['payment_text']), esc_attr($a['phone']), filter_var($a['show_payment_info'], FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false', esc_attr($a['tagline']), esc_url($a['youtube_url']), esc_url($a['logo_image_url']));
}
add_shortcode('cm_footer', 'cm_footer_shortcode');

function cm_homepage_shortcode($attributes = array()) {
    $a = shortcode_atts(array('about_image_url' => '', 'booking_url' => '#contact', 'popup_id' => '', 'courses_image_url' => '', 'courses_url' => '#', 'hero_image_url' => '', 'manual_image_url' => '', 'nature_url' => 'https://claudiamaria.ro/shop/', 'raindrop_image_url' => '', 'reflexology_image_url' => '', 'ritual_image_url' => '', 'therapies_url' => '/servicii/'), $attributes, 'cm_homepage');
    cm_react_assets();
    return sprintf('<div data-cm-homepage-root data-about-image-url="%1$s" data-booking-url="%2$s" data-booking-popup-id="%3$d" data-courses-image-url="%4$s" data-courses-url="%5$s" data-hero-image-url="%6$s" data-manual-image-url="%7$s" data-nature-url="%8$s" data-reflexology-image-url="%9$s" data-therapies-url="%10$s" data-raindrop-image-url="%11$s" data-ritual-image-url="%12$s"></div>', esc_url($a['about_image_url']), esc_url($a['booking_url']), absint($a['popup_id']), esc_url($a['courses_image_url']), esc_url($a['courses_url']), esc_url($a['hero_image_url']), esc_url($a['manual_image_url']), esc_url($a['nature_url']), esc_url($a['reflexology_image_url']), esc_url($a['therapies_url']), esc_url($a['raindrop_image_url']), esc_url($a['ritual_image_url']));
}
add_shortcode('cm_homepage', 'cm_homepage_shortcode');
