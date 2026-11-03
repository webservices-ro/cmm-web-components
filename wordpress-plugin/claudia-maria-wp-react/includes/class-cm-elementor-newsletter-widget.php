<?php

if (!defined('ABSPATH')) exit;

/** Elementor data bridge for the React newsletter section. */
class CM_Elementor_Newsletter_Widget extends \Elementor\Widget_Base {
    public function get_name() { return 'cm-newsletter'; }
    public function get_title() { return esc_html__('Claudia Maria Newsletter', 'claudia-maria-wp-react'); }
    public function get_icon() { return 'eicon-mail'; }
    public function get_categories() { return array('general'); }
    public function get_keywords() { return array('newsletter', 'email', 'claudia maria'); }

    public function get_style_depends() {
        $handles = cm_react_register_assets();
        return $handles['styles'];
    }

    public function get_script_depends() {
        $handles = cm_react_register_assets();
        return $handles['script'] ? array($handles['script']) : array();
    }

    protected function register_controls() {
        $this->start_controls_section('content_section', array('label' => esc_html__('Newsletter', 'claudia-maria-wp-react')));
        $this->add_control('kicker', array('label' => esc_html__('Kicker', 'claudia-maria-wp-react'), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Newsletter'));
        $this->add_control('title', array('label' => esc_html__('Heading', 'claudia-maria-wp-react'), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Vrei să îți redobândești sănătatea și să previi bolile?'));
        $this->add_control('body', array('label' => esc_html__('Content', 'claudia-maria-wp-react'), 'type' => \Elementor\Controls_Manager::WYSIWYG, 'default' => '<p>Îți trimit permanent sfaturi utile, noutăți, remedii și tehnici terapeutice, invitații la un webinar sau un anunț despre un eveniment, și multă inspirație pe email.</p>'));
        $this->add_control('image', array('label' => esc_html__('Image', 'claudia-maria-wp-react'), 'type' => \Elementor\Controls_Manager::MEDIA, 'default' => array('url' => '')));
        $this->add_control('image_alt', array('label' => esc_html__('Image alt text', 'claudia-maria-wp-react'), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Newsletter Claudia Maria'));
        $this->end_controls_section();

        $this->start_controls_section('style_section', array('label' => esc_html__('Colors & width', 'claudia-maria-wp-react'), 'tab' => \Elementor\Controls_Manager::TAB_STYLE));
        foreach (array('navy' => array('Navy', '#183b61'), 'blue' => array('Blue', '#35698f'), 'text' => array('Text', '#304457'), 'cream' => array('Background', '#faf8f2')) as $key => $color) {
            $this->add_control('color_' . $key, array('label' => esc_html__($color[0], 'claudia-maria-wp-react'), 'type' => \Elementor\Controls_Manager::COLOR, 'default' => $color[1], 'selectors' => array('{{WRAPPER}} .cm-home' => '--cm-home-' . $key . ': {{VALUE}};')));
        }
        $this->add_responsive_control('content_width', array('label' => esc_html__('Content width', 'claudia-maria-wp-react'), 'type' => \Elementor\Controls_Manager::SLIDER, 'size_units' => array('px'), 'range' => array('px' => array('min' => 600, 'max' => 1600)), 'default' => array('size' => 1180, 'unit' => 'px'), 'selectors' => array('{{WRAPPER}} .cm-home-container' => 'width: min({{SIZE}}{{UNIT}}, 92%);')));
        $this->add_responsive_control('vertical_spacing', array(
            'label' => esc_html__('Vertical section spacing', 'claudia-maria-wp-react'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => array('px'),
            'range' => array('px' => array('min' => 0, 'max' => 160)),
            'default' => array('size' => 82, 'unit' => 'px'),
            'tablet_default' => array('size' => 56, 'unit' => 'px'),
            'mobile_default' => array('size' => 56, 'unit' => 'px'),
            'selectors' => array('{{WRAPPER}} .cm-home' => '--cm-home-section-spacing: {{SIZE}}{{UNIT}};'),
        ));
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        cm_react_assets();

        $attributes = array(
            'data-cm-newsletter-root' => '',
            'data-body' => wp_kses_post($settings['body'] ?? ''),
            'data-image-alt' => sanitize_text_field($settings['image_alt'] ?? ''),
            'data-image-url' => esc_url_raw($settings['image']['url'] ?? ''),
            'data-kicker' => sanitize_text_field($settings['kicker'] ?? ''),
            'data-title' => sanitize_text_field($settings['title'] ?? ''),
        );

        echo '<div';
        foreach ($attributes as $name => $value) {
            printf(' %s="%s"', esc_attr($name), esc_attr($value));
        }
        echo '><noscript>' . esc_html__('Această secțiune necesită JavaScript.', 'claudia-maria-wp-react') . '</noscript></div>';
    }
}
