<?php

if (!defined('ABSPATH')) exit;

/** Editable global Claudia Maria footer for Elementor. */
class CM_Elementor_Footer_Widget extends \Elementor\Widget_Base {
    public function get_name() { return 'cm-footer'; }
    public function get_title() { return esc_html__('Claudia Maria Footer', 'claudia-maria-wp-react'); }
    public function get_icon() { return 'eicon-footer'; }
    public function get_categories() { return array('general'); }
    public function get_keywords() { return array('footer', 'contact', 'navigation', 'claudia maria'); }

    public function get_script_depends() {
        $handles = cm_react_register_assets();
        return $handles['script'] ? array($handles['script']) : array();
    }

    public function get_style_depends() {
        $handles = cm_react_register_assets();
        return $handles['styles'];
    }

    protected function register_controls() {
        $this->start_controls_section('brand_section', array('label' => esc_html__('Brand & contact', 'claudia-maria-wp-react')));
        $this->add_control('logo_image', array('label' => esc_html__('Logo', 'claudia-maria-wp-react'), 'type' => \Elementor\Controls_Manager::MEDIA));
        $fields = array(
            'brand' => array('Logo alt text', 'CLAUDIA MARIA'),
            'bridge' => array('Bridge', 'Centrul Sakura by Claudia Maria'),
            'tagline' => array('Tagline', 'Un spațiu în care corpul tău este ascultat, înțeles și sprijinit.'),
            'phone' => array('Phone', '0769 231 018'),
            'email' => array('Email', 'claudiamariamed@gmail.com'),
            'contact_text' => array('Contact text', 'Programările se stabilesc telefonic, după ce discutăm despre nevoile tale.'),
            'copyright' => array('Copyright', '© Claudia Maria — Toate drepturile rezervate'),
        );
        foreach ($fields as $key => $field) {
            $this->add_control($key, array('label' => esc_html__($field[0], 'claudia-maria-wp-react'), 'type' => in_array($key, array('tagline', 'contact_text'), true) ? \Elementor\Controls_Manager::TEXTAREA : \Elementor\Controls_Manager::TEXT, 'default' => $field[1], 'label_block' => true));
        }
        $this->add_control('home_url', array('label' => esc_html__('Home URL', 'claudia-maria-wp-react'), 'type' => \Elementor\Controls_Manager::URL, 'default' => array('url' => home_url('/')), 'show_external' => false));
        $this->end_controls_section();

        $this->start_controls_section('payment_section', array('label' => esc_html__('Payment information', 'claudia-maria-wp-react')));
        $this->add_control('show_payment_info', array('label' => esc_html__('Show above footer', 'claudia-maria-wp-react'), 'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => ''));
        $this->add_control('payment_heading', array('label' => esc_html__('Heading', 'claudia-maria-wp-react'), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Informații despre plată', 'condition' => array('show_payment_info' => 'yes')));
        $this->add_control('payment_text', array('label' => esc_html__('Description', 'claudia-maria-wp-react'), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Detaliile privind plata și eventualele condiții de programare sunt comunicate înainte de confirmarea serviciului. Dacă pe site se activează plata online, aici pot fi afișate metodele de plată acceptate, folosind siglele oficiale corespunzătoare.', 'condition' => array('show_payment_info' => 'yes')));
        $payment_repeater = new \Elementor\Repeater();
        $payment_repeater->add_control('name', array('label' => esc_html__('Method', 'claudia-maria-wp-react'), 'type' => \Elementor\Controls_Manager::TEXT, 'label_block' => true));
        $this->add_control('payment_methods', array('label' => esc_html__('Payment placeholders', 'claudia-maria-wp-react'), 'type' => \Elementor\Controls_Manager::REPEATER, 'fields' => $payment_repeater->get_controls(), 'default' => array(array('name' => 'METODĂ DE PLATĂ'), array('name' => 'CARD — DACĂ ESTE ACTIV'), array('name' => 'PROCESATOR — DACĂ ESTE ACTIV')), 'title_field' => '{{{ name }}}', 'condition' => array('show_payment_info' => 'yes')));
        foreach (array(
            'consumer_protection' => array('Consumer protection image', 'Soluționarea alternativă a litigiilor'),
            'payment_processor' => array('Payment processor image', 'Metode de plată acceptate'),
            'online_disputes' => array('Online disputes image', 'Soluționarea online a litigiilor'),
        ) as $key => $image) {
            $this->add_control($key . '_image', array('label' => esc_html__($image[0], 'claudia-maria-wp-react'), 'type' => \Elementor\Controls_Manager::MEDIA, 'condition' => array('show_payment_info' => 'yes')));
            $this->add_control($key . '_alt', array('label' => esc_html__($image[0] . ' alt text', 'claudia-maria-wp-react'), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => $image[1], 'condition' => array('show_payment_info' => 'yes')));
            $this->add_control($key . '_link', array('label' => esc_html__($image[0] . ' link', 'claudia-maria-wp-react'), 'type' => \Elementor\Controls_Manager::URL, 'placeholder' => 'https://', 'condition' => array('show_payment_info' => 'yes')));
        }
        $this->add_control('payment_script', array(
            'label' => esc_html__('Payment logo script', 'claudia-maria-wp-react'),
            'type' => \Elementor\Controls_Manager::CODE,
            'language' => 'html',
            'rows' => 12,
            'default' => '<script src="https://mny.ro/npId.js?p=150276" type="text/javascript" data-version="orizontal" data-contrast-color="#ffffff"></script>',
            'description' => esc_html__('Paste only a trusted payment provider logo/embed snippet. HTML and JavaScript entered here will run on the public page.', 'claudia-maria-wp-react'),
            'condition' => array('show_payment_info' => 'yes'),
        ));
        $this->add_control('payment_note', array('label' => esc_html__('Implementation note', 'claudia-maria-wp-react'), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Notă pentru implementare: înlocuiți aceste marcaje cu siglele oficiale numai dacă metodele respective sunt efectiv disponibile. Verificați separat obligațiile de informare legală; elementele obligatorii nu trebuie ascunse sau eliminate.', 'condition' => array('show_payment_info' => 'yes')));
        $this->end_controls_section();

        $this->start_controls_section('navigation_section', array('label' => esc_html__('Navigation', 'claudia-maria-wp-react')));
        $this->add_control('explore_heading', array('label' => esc_html__('Heading', 'claudia-maria-wp-react'), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Explorează'));
        $this->add_control('navigation', array('label' => esc_html__('Links', 'claudia-maria-wp-react'), 'type' => \Elementor\Controls_Manager::REPEATER, 'fields' => $this->link_fields(), 'default' => $this->default_links(array('Despre mine' => '/despre-mine/', 'Cum lucrăm' => '/cum-lucram/', 'Terapii' => '/servicii/', 'Prețuri' => '/preturi/', 'Resurse' => '/resurse/', 'Cursuri' => '/cursuri/')), 'title_field' => '{{{ label }}}'));
        $this->end_controls_section();

        $this->start_controls_section('legal_section', array('label' => esc_html__('Legal links', 'claudia-maria-wp-react')));
        $this->add_control('legal_links', array('label' => esc_html__('Links', 'claudia-maria-wp-react'), 'type' => \Elementor\Controls_Manager::REPEATER, 'fields' => $this->link_fields(), 'default' => $this->default_links($this->footer_legal_links()), 'title_field' => '{{{ label }}}'));
        $this->end_controls_section();

        $this->start_controls_section('social_section', array('label' => esc_html__('Social media', 'claudia-maria-wp-react')));
        foreach (array('facebook' => 'Facebook', 'instagram' => 'Instagram', 'youtube' => 'YouTube') as $key => $label) {
            $this->add_control($key . '_url', array('label' => esc_html__($label . ' URL', 'claudia-maria-wp-react'), 'type' => \Elementor\Controls_Manager::URL, 'placeholder' => 'https://'));
        }
        $this->end_controls_section();

        $this->start_controls_section('footer_style', array('label' => esc_html__('Footer', 'claudia-maria-wp-react'), 'tab' => \Elementor\Controls_Manager::TAB_STYLE));
        foreach (array('background_color' => array('Background', '--cm-footer-navy'), 'text_color' => array('Text', '--cm-footer-link'), 'muted_color' => array('Muted text', '--cm-footer-muted'), 'accent_color' => array('Accent', '--cm-footer-gold'), 'divider_color' => array('Divider', '--cm-footer-line')) as $key => $style) {
            $this->add_control($key, array('label' => esc_html__($style[0], 'claudia-maria-wp-react'), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => array('{{WRAPPER}} .cm-footer-scope' => $style[1] . ': {{VALUE}};')));
        }
        $this->add_responsive_control('content_width', array('label' => esc_html__('Content width', 'claudia-maria-wp-react'), 'type' => \Elementor\Controls_Manager::SLIDER, 'size_units' => array('px', '%'), 'range' => array('px' => array('min' => 600, 'max' => 1800)), 'selectors' => array('{{WRAPPER}} .cm-footer__wrap' => 'max-width: {{SIZE}}{{UNIT}};')));
        $this->add_responsive_control('logo_width', array('label' => esc_html__('Logo width', 'claudia-maria-wp-react'), 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => array('px' => array('min' => 40, 'max' => 400)), 'selectors' => array('{{WRAPPER}} .cm-footer__brand img' => 'width: {{SIZE}}{{UNIT}};')));
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), array('name' => 'body_typography', 'label' => esc_html__('Body typography', 'claudia-maria-wp-react'), 'selector' => '{{WRAPPER}} .cm-footer-scope'));
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), array('name' => 'brand_typography', 'label' => esc_html__('Brand typography', 'claudia-maria-wp-react'), 'selector' => '{{WRAPPER}} .cm-footer__brand'));
        $this->end_controls_section();

        $this->start_controls_section('payment_style', array('label' => esc_html__('Payment information', 'claudia-maria-wp-react'), 'tab' => \Elementor\Controls_Manager::TAB_STYLE, 'condition' => array('show_payment_info' => 'yes')));
        $this->add_control('payment_background_color', array('label' => esc_html__('Background', 'claudia-maria-wp-react'), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => array('{{WRAPPER}} .cm-footer-payment' => 'background: {{VALUE}};')));
        $this->add_control('payment_heading_color', array('label' => esc_html__('Heading color', 'claudia-maria-wp-react'), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => array('{{WRAPPER}} .cm-footer-payment h2' => 'color: {{VALUE}};')));
        $this->add_control('payment_text_color', array('label' => esc_html__('Text color', 'claudia-maria-wp-react'), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => array('{{WRAPPER}} .cm-footer-payment p' => 'color: {{VALUE}};')));
        $this->end_controls_section();
    }

    private function link_fields() {
        $repeater = new \Elementor\Repeater();
        $repeater->add_control('label', array('label' => esc_html__('Label', 'claudia-maria-wp-react'), 'type' => \Elementor\Controls_Manager::TEXT, 'label_block' => true));
        $repeater->add_control('url', array('label' => esc_html__('Link', 'claudia-maria-wp-react'), 'type' => \Elementor\Controls_Manager::URL, 'show_external' => false));
        return $repeater->get_controls();
    }

    private function default_links($links) {
        $result = array();
        foreach ($links as $label => $url) $result[] = array('label' => $label, 'url' => array('url' => $url));
        return $result;
    }

    private function url($setting, $fallback = '') { return !empty($setting['url']) ? $setting['url'] : $fallback; }

    private function links($items) {
        $result = array();
        foreach ((array) $items as $item) {
            $label = sanitize_text_field($item['label'] ?? '');
            if ($label) $result[] = array('label' => $label, 'url' => esc_url_raw($this->url($item['url'] ?? array(), '#')));
        }
        return $result;
    }

    private function footer_legal_links() {
        return array(
            'Notificare legală' => 'https://centrulsakura.ro/notificare-legala/',
            'Privacy' => 'https://centrulsakura.ro/privacy/',
            'Drept autor' => 'https://centrulsakura.ro/drept-autor/',
            'Înștiințare cursuri' => 'https://centrulsakura.ro/instiintare-cursuri/',
            'Termeni și condiții' => 'https://centrulsakura.ro/termeni-si-conditii/',
            'Cookies' => 'https://centrulsakura.ro/cookies/',
        );
    }

    private function legal_links($items) {
        $links = $this->links($items);
        $legacy_paths = array('/politica-de-confidentialitate/', '/politica-de-cookies/', '/termeni-si-conditii/', '/informatii-legale/');
        $paths = array_map(function ($link) { return wp_parse_url($link['url'], PHP_URL_PATH); }, $links);
        $uses_legacy_defaults = 4 === count($paths) && !array_diff($paths, $legacy_paths) && !array_diff($legacy_paths, $paths);

        return (!$links || $uses_legacy_defaults) ? $this->links($this->default_links($this->footer_legal_links())) : $links;
    }

    private function navigation_links($items) {
        $links = $this->links($items);
        foreach ($links as &$link) {
            if ('Terapii' === $link['label'] && '/terapii/' === wp_parse_url($link['url'], PHP_URL_PATH)) {
                $link['url'] = '/servicii/';
            }
        }
        unset($link);
        return $links;
    }

    private function payment_methods($items) {
        $result = array();
        foreach ((array) $items as $item) {
            $name = sanitize_text_field($item['name'] ?? '');
            if ($name) $result[] = $name;
        }
        return $result;
    }

    private function payment_images($settings) {
        $result = array();
        foreach (array('consumer_protection', 'payment_processor', 'online_disputes') as $key) {
            $url = $settings[$key . '_image']['url'] ?? '';
            $result[] = array(
                'url' => esc_url_raw($url),
                'alt' => sanitize_text_field($settings[$key . '_alt'] ?? ''),
                'link' => esc_url_raw($this->url($settings[$key . '_link'] ?? array())),
            );
        }
        return $result;
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $attributes = array(
            'data-cm-footer-root' => '', 'data-brand' => $s['brand'] ?? '', 'data-bridge' => $s['bridge'] ?? '',
            'data-contact-heading' => 'Contact', 'data-contact-text' => $s['contact_text'] ?? '', 'data-copyright' => $s['copyright'] ?? '',
            'data-email' => sanitize_email($s['email'] ?? ''), 'data-explore-heading' => $s['explore_heading'] ?? 'Explorează',
            'data-facebook-url' => $this->url($s['facebook_url'] ?? array()), 'data-home-url' => $this->url($s['home_url'] ?? array(), home_url('/')),
            'data-instagram-url' => $this->url($s['instagram_url'] ?? array()), 'data-legal-links' => wp_json_encode($this->legal_links($s['legal_links'] ?? array())),
            'data-logo-image-url' => esc_url_raw($s['logo_image']['url'] ?? ''),
            'data-navigation' => wp_json_encode($this->navigation_links($s['navigation'] ?? array())), 'data-phone' => $s['phone'] ?? '',
            'data-payment-heading' => $s['payment_heading'] ?? '', 'data-payment-images' => wp_json_encode($this->payment_images($s)),
            'data-payment-methods' => wp_json_encode($this->payment_methods($s['payment_methods'] ?? array())),
            'data-payment-note' => $s['payment_note'] ?? '', 'data-payment-script' => $s['payment_script'] ?? '',
            'data-payment-text' => $s['payment_text'] ?? '',
            'data-show-payment-info' => 'yes' === ($s['show_payment_info'] ?? '') ? 'true' : 'false',
            'data-tagline' => $s['tagline'] ?? '', 'data-youtube-url' => $this->url($s['youtube_url'] ?? array()),
        );
        echo '<div';
        foreach ($attributes as $name => $value) printf(' %s="%s"', esc_attr($name), esc_attr($value));
        echo '></div>';
    }
}
