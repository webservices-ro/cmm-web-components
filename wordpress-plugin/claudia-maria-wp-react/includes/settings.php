<?php
if (!defined('ABSPATH')) exit;

function cm_react_get_settings() {
    $defaults = array(
        'booking_action' => 'popup', 'nature_url' => 'https://claudiamaria.ro/shop/', 'popup_id' => 0, 'whatsapp_number' => '',
        'whatsapp_generic_message' => "Bună, Claudia!\n\nAm văzut site-ul și aș dori să discut cu tine. Am o întrebare. Mă poți ajuta?",
        'whatsapp_initial_message' => "Bună, Claudia!\n\nAm văzut pe site consultația inițială și aș dori să mă programez. Aș vrea să știu ce disponibilități ai.",
        'whatsapp_loyalty_message' => "Bună, Claudia!\n\nAm văzut programul de loialitate – „Pentru trupul tău. Pentru sănătatea ta.” și aș dori să aflu cum pot beneficia de el.",
        'whatsapp_online_message' => "Bună, Claudia!\n\nAm văzut pe site consultația online și aș dori să discutăm despre o programare.",
    );
    $saved = get_option('cm_react_settings', array());
    if (is_array($saved) && in_array(untrailingslashit($saved['nature_url'] ?? ''), array('https://claudiamaria.ro', 'https://claudiamaria.ro/produse'), true)) {
        $saved['nature_url'] = 'https://claudiamaria.ro/shop/';
    }
    return wp_parse_args(is_array($saved) ? $saved : array(), $defaults);
}

function cm_react_sanitize_settings($input) {
    $input = is_array($input) ? $input : array();
    return array(
        'booking_action' => 'whatsapp' === ($input['booking_action'] ?? '') ? 'whatsapp' : 'popup',
        'nature_url' => esc_url_raw($input['nature_url'] ?? ''), 'popup_id' => absint($input['popup_id'] ?? 0),
        'whatsapp_generic_message' => sanitize_textarea_field($input['whatsapp_generic_message'] ?? ''),
        'whatsapp_initial_message' => sanitize_textarea_field($input['whatsapp_initial_message'] ?? ''),
        'whatsapp_loyalty_message' => sanitize_textarea_field($input['whatsapp_loyalty_message'] ?? ''),
        'whatsapp_number' => preg_replace('/\D+/', '', (string) ($input['whatsapp_number'] ?? '')),
        'whatsapp_online_message' => sanitize_textarea_field($input['whatsapp_online_message'] ?? ''),
    );
}

function cm_react_register_settings() { register_setting('cm_react_settings_group', 'cm_react_settings', array('sanitize_callback' => 'cm_react_sanitize_settings', 'default' => array())); }
add_action('admin_init', 'cm_react_register_settings');

function cm_react_add_settings_page() { add_menu_page(__('Claudia Maria Components', 'claudia-maria-wp-react'), __('Claudia Maria', 'claudia-maria-wp-react'), 'manage_options', 'claudia-maria-components', 'cm_react_render_settings_page', 'dashicons-format-chat', 58); }
add_action('admin_menu', 'cm_react_add_settings_page');

function cm_react_render_settings_page() {
    if (!current_user_can('manage_options')) return;
    $settings = cm_react_get_settings();
    $messages = array('whatsapp_initial_message' => 'Consultație inițială', 'whatsapp_online_message' => 'Consultație online', 'whatsapp_loyalty_message' => 'Program de loialitate', 'whatsapp_generic_message' => 'Contact general');
    ?>
    <div class="wrap"><h1>Claudia Maria Components</h1><p>Configurează acțiunea implicită a butoanelor de pe pagina de prețuri. Atributele shortcode pot suprascrie aceste valori.</p>
    <form action="options.php" method="post"><?php settings_fields('cm_react_settings_group'); ?><table class="form-table" role="presentation">
    <tr><th><label for="cm-booking-action">Acțiunea butoanelor</label></th><td><select id="cm-booking-action" name="cm_react_settings[booking_action]"><option value="popup" <?php selected($settings['booking_action'], 'popup'); ?>>Popup Elementor</option><option value="whatsapp" <?php selected($settings['booking_action'], 'whatsapp'); ?>>Mesaj WhatsApp</option></select></td></tr>
    <tr id="cm-popup-id-row"><th><label for="cm-popup-id">ID Popup Elementor</label></th><td><input class="small-text" id="cm-popup-id" min="1" name="cm_react_settings[popup_id]" type="number" value="<?php echo esc_attr($settings['popup_id']); ?>"><p class="description">Introdu ID-ul numeric al popup-ului Elementor Pro.</p></td></tr>
    <tr><th><label for="cm-whatsapp-number">Număr WhatsApp</label></th><td><input class="regular-text" id="cm-whatsapp-number" name="cm_react_settings[whatsapp_number]" value="<?php echo esc_attr($settings['whatsapp_number']); ?>" placeholder="40712345678"><p class="description">Format internațional, fără +, spații sau semne de punctuație.</p></td></tr>
    <?php foreach ($messages as $field => $label) : ?><tr><th><label for="cm-<?php echo esc_attr($field); ?>"><?php echo esc_html($label); ?></label></th><td><textarea class="large-text" id="cm-<?php echo esc_attr($field); ?>" name="cm_react_settings[<?php echo esc_attr($field); ?>]" rows="4"><?php echo esc_textarea($settings[$field]); ?></textarea></td></tr><?php endforeach; ?>
    <tr><th><label for="cm-nature-url">URL Natură pentru sănătate</label></th><td><input class="regular-text code" id="cm-nature-url" name="cm_react_settings[nature_url]" type="url" value="<?php echo esc_attr($settings['nature_url']); ?>"></td></tr>
    </table><?php submit_button(); ?></form></div>
    <script>document.addEventListener('DOMContentLoaded',function(){var a=document.getElementById('cm-booking-action'),r=document.getElementById('cm-popup-id-row');if(!a||!r)return;function t(){r.hidden=a.value!=='popup'}a.addEventListener('change',t);t()});</script>
    <?php
}
