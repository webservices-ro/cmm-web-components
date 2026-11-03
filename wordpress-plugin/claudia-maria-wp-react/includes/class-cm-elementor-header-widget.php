<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Editable Claudia Maria header for Elementor.
 */
class CM_Elementor_Header_Widget extends \Elementor\Widget_Base {
    public function get_name() {
        return 'cm-header';
    }

    public function get_title() {
        return esc_html__('Claudia Maria Header', 'claudia-maria-wp-react');
    }

    public function get_icon() {
        return 'eicon-header';
    }

    public function get_categories() {
        return array('general');
    }

    public function get_keywords() {
        return array('header', 'navigation', 'menu', 'claudia maria');
    }

    public function get_script_depends() {
        $handles = cm_react_register_assets();
        return $handles['script'] ? array($handles['script']) : array();
    }

    public function get_style_depends() {
        $handles = cm_react_register_assets();
        return $handles['styles'];
    }

    protected function register_controls() {
        $this->register_brand_controls();
        $this->register_navigation_controls();
        $this->register_account_controls();
        $this->register_cart_booking_controls();
        $this->register_style_controls();
    }

    private function register_brand_controls() {
        $this->start_controls_section('brand_section', array(
            'label' => esc_html__('Brand', 'claudia-maria-wp-react'),
        ));

        $this->add_control('logo_text', array(
            'label' => esc_html__('Logo text', 'claudia-maria-wp-react'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'CLAUDIA MARIA',
            'label_block' => true,
        ));
        $this->add_control('logo_image', array(
            'label' => esc_html__('Logo image', 'claudia-maria-wp-react'),
            'type' => \Elementor\Controls_Manager::MEDIA,
        ));
        $this->add_control('show_logo_desktop', array(
            'label' => esc_html__('Show logo on desktop', 'claudia-maria-wp-react'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => '',
        ));
        $this->add_control('home_url', array(
            'label' => esc_html__('Home URL', 'claudia-maria-wp-react'),
            'type' => \Elementor\Controls_Manager::URL,
            'default' => array('url' => home_url('/')),
            'show_external' => false,
        ));
        $this->add_control('sticky', array(
            'label' => esc_html__('Sticky header', 'claudia-maria-wp-react'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ));
        $this->end_controls_section();
    }

    private function register_navigation_controls() {
        $this->start_controls_section('navigation_section', array(
            'label' => esc_html__('Navigation', 'claudia-maria-wp-react'),
        ));

        $menu_options = array('' => esc_html__('Select a menu', 'claudia-maria-wp-react'));
        foreach (wp_get_nav_menus() as $menu) {
            $menu_options[(string) $menu->term_id] = $menu->name;
        }

        $this->add_control('navigation_source', array(
            'label' => esc_html__('Navigation source', 'claudia-maria-wp-react'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'custom',
            'options' => array(
                'custom' => esc_html__('Custom items', 'claudia-maria-wp-react'),
                'wordpress' => esc_html__('WordPress menu', 'claudia-maria-wp-react'),
            ),
        ));
        $this->add_control('wordpress_menu', array(
            'label' => esc_html__('Menu', 'claudia-maria-wp-react'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => $menu_options,
            'condition' => array('navigation_source' => 'wordpress'),
            'description' => esc_html__('Menus are managed under Appearance → Menus in WordPress.', 'claudia-maria-wp-react'),
        ));

        $repeater = new \Elementor\Repeater();
        $repeater->add_control('label', array(
            'label' => esc_html__('Label', 'claudia-maria-wp-react'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => esc_html__('Menu item', 'claudia-maria-wp-react'),
            'label_block' => true,
        ));
        $repeater->add_control('url', array(
            'label' => esc_html__('Link', 'claudia-maria-wp-react'),
            'type' => \Elementor\Controls_Manager::URL,
            'default' => array('url' => '#'),
            'show_external' => false,
        ));
        $repeater->add_control('parent', array(
            'label' => esc_html__('Parent label', 'claudia-maria-wp-react'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'description' => esc_html__('Leave empty for a top-level item. To make this a dropdown item, enter the exact label of its parent.', 'claudia-maria-wp-react'),
            'label_block' => true,
        ));

        $defaults = array(
            array('label' => 'Home', 'url' => array('url' => '/')),
            array('label' => 'Cursuri', 'url' => array('url' => '/cursuri/')),
            array('label' => 'Programe', 'url' => array('url' => '/programe/')),
            array('label' => 'Servicii', 'url' => array('url' => '/servicii/')),
            array('label' => 'Masaje', 'url' => array('url' => '/masaje/'), 'parent' => 'Servicii'),
            array('label' => 'Osteoterapii', 'url' => array('url' => '/osteoterapii/'), 'parent' => 'Servicii'),
            array('label' => 'Terapii medicale alternative', 'url' => array('url' => '/terapii-medicale-alternative/'), 'parent' => 'Servicii'),
            array('label' => 'Despre mine', 'url' => array('url' => '/despre-mine/')),
            array('label' => 'Certificări', 'url' => array('url' => '/certificari/'), 'parent' => 'Despre mine'),
            array('label' => 'Resurse', 'url' => array('url' => '/resurse/')),
            array('label' => 'Testimoniale', 'url' => array('url' => '/testimoniale/')),
            array('label' => 'Blog', 'url' => array('url' => '/blog/')),
            array('label' => 'Prețuri', 'url' => array('url' => '/preturi/')),
            array('label' => 'Contact', 'url' => array('url' => '/contact/')),
        );

        $this->add_control('menu_items', array(
            'label' => esc_html__('Menu items', 'claudia-maria-wp-react'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => $defaults,
            'title_field' => '{{{ parent ? "↳ " : "" }}}{{{ label }}}',
            'condition' => array('navigation_source' => 'custom'),
        ));
        $this->end_controls_section();
    }

    private function register_account_controls() {
        $this->start_controls_section('account_section', array(
            'label' => esc_html__('Account bar', 'claudia-maria-wp-react'),
        ));
        $this->add_control('show_account', array(
            'label' => esc_html__('Show account bar', 'claudia-maria-wp-react'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ));
        foreach (array(
            'login' => array('Login', '/contul-meu/'),
            'logout' => array('Logout', ''),
            'account' => array('Contul meu', '/contul-meu/'),
        ) as $key => $value) {
            $this->add_control($key . '_label', array(
                'label' => sprintf(esc_html__('%s label', 'claudia-maria-wp-react'), $value[0]),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => $value[0],
                'condition' => array('show_account' => 'yes'),
            ));
            $this->add_control($key . '_url', array(
                'label' => sprintf(esc_html__('%s URL', 'claudia-maria-wp-react'), $value[0]),
                'type' => \Elementor\Controls_Manager::URL,
                'default' => array('url' => $value[1]),
                'show_external' => false,
                'condition' => array('show_account' => 'yes'),
            ));
        }
        $this->end_controls_section();
    }

    private function register_cart_booking_controls() {
        $this->start_controls_section('actions_section', array(
            'label' => esc_html__('Cart & booking', 'claudia-maria-wp-react'),
        ));
        $this->add_control('show_cart', array(
            'label' => esc_html__('Show WooCommerce cart', 'claudia-maria-wp-react'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ));
        $this->add_control('cart_url', array(
            'label' => esc_html__('Custom cart URL', 'claudia-maria-wp-react'),
            'type' => \Elementor\Controls_Manager::URL,
            'description' => esc_html__('Leave empty to use the WooCommerce cart URL.', 'claudia-maria-wp-react'),
            'show_external' => false,
            'condition' => array('show_cart' => 'yes'),
        ));
        $this->add_control('cart_drawer', array(
            'label' => esc_html__('Open desktop cart drawer', 'claudia-maria-wp-react'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
            'condition' => array('show_cart' => 'yes'),
        ));
        $this->add_control('show_booking', array(
            'label' => esc_html__('Show booking button', 'claudia-maria-wp-react'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ));
        $this->add_control('booking_label', array(
            'label' => esc_html__('Button label', 'claudia-maria-wp-react'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'PROGRAMEAZĂ',
            'condition' => array('show_booking' => 'yes'),
        ));
        $this->add_control('booking_url', array(
            'label' => esc_html__('Booking URL', 'claudia-maria-wp-react'),
            'type' => \Elementor\Controls_Manager::URL,
            'default' => array('url' => '/contact/'),
            'condition' => array('show_booking' => 'yes'),
        ));
        $this->add_control('popup_id', array(
            'label' => esc_html__('Elementor popup ID', 'claudia-maria-wp-react'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 1,
            'condition' => array('show_booking' => 'yes'),
        ));
        $this->add_control('whatsapp_number', array(
            'label' => esc_html__('WhatsApp number', 'claudia-maria-wp-react'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => cm_react_get_settings()['whatsapp_number'] ?? '',
            'description' => esc_html__('International format, without +, spaces, or punctuation. When empty, the booking URL or popup is used.', 'claudia-maria-wp-react'),
            'condition' => array('show_booking' => 'yes'),
        ));
        $this->add_control('whatsapp_message', array(
            'label' => esc_html__('WhatsApp message', 'claudia-maria-wp-react'),
            'type' => \Elementor\Controls_Manager::TEXTAREA,
            'default' => 'Bună, Claudia! Am văzut site-ul și aș dori să mă programez. Îmi poți spune ce disponibilități ai?',
            'condition' => array('show_booking' => 'yes'),
        ));
        $this->end_controls_section();
    }

    private function register_style_controls() {
        $this->start_controls_section('header_style', array(
            'label' => esc_html__('Header', 'claudia-maria-wp-react'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ));
        $this->add_control('background_color', array(
            'label' => esc_html__('Background', 'claudia-maria-wp-react'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => array('{{WRAPPER}} .cm-home' => '--cm-home-white: {{VALUE}};'),
        ));
        $this->add_control('text_color', array(
            'label' => esc_html__('Navigation color', 'claudia-maria-wp-react'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => array(
                '{{WRAPPER}} .cm-home-nav__link, {{WRAPPER}} .cm-home-submenu a' => 'color: {{VALUE}} !important;',
                '{{WRAPPER}} .cm-home-cart, {{WRAPPER}} .cm-home-menu-toggle, {{WRAPPER}} .cm-home-submenu-toggle' => 'color: {{VALUE}} !important;',
            ),
        ));
        $this->add_control('accent_color', array(
            'label' => esc_html__('Accent color', 'claudia-maria-wp-react'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => array('{{WRAPPER}} .cm-home' => '--cm-home-blue: {{VALUE}};'),
        ));
        $this->add_control('border_color', array(
            'label' => esc_html__('Border color', 'claudia-maria-wp-react'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => array('{{WRAPPER}} .cm-home' => '--cm-home-line: {{VALUE}};'),
        ));
        $this->add_responsive_control('content_width', array(
            'label' => esc_html__('Content width', 'claudia-maria-wp-react'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => array('px', '%'),
            'range' => array('px' => array('min' => 600, 'max' => 1800)),
            'selectors' => array('{{WRAPPER}} .cm-home-container' => 'width: min({{SIZE}}{{UNIT}}, 100%);'),
        ));
        $this->add_responsive_control('menu_gap', array(
            'label' => esc_html__('Menu gap', 'claudia-maria-wp-react'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => array('px' => array('min' => 0, 'max' => 50)),
            'selectors' => array('{{WRAPPER}} .cm-home-nav' => 'gap: {{SIZE}}{{UNIT}};'),
        ));
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), array(
            'name' => 'navigation_typography',
            'label' => esc_html__('Navigation typography', 'claudia-maria-wp-react'),
            'selector' => '{{WRAPPER}} .cm-home-nav__link, {{WRAPPER}} .cm-home-submenu a',
        ));
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), array(
            'name' => 'account_typography',
            'label' => esc_html__('Account typography', 'claudia-maria-wp-react'),
            'selector' => '{{WRAPPER}} .cm-home-account-bar a',
        ));
        $this->end_controls_section();
    }

    private function link_url($setting, $fallback = '') {
        return isset($setting['url']) && '' !== $setting['url'] ? $setting['url'] : $fallback;
    }

    private function navigation_data($items) {
        $navigation = array();
        $parents = array();

        foreach ((array) $items as $item) {
            $parent = trim((string) ($item['parent'] ?? ''));
            if ('' !== $parent) {
                continue;
            }
            $label = sanitize_text_field($item['label'] ?? '');
            if ('' === $label) {
                continue;
            }
            $parents[$label] = count($navigation);
            $navigation[] = array(
                'label' => $label,
                'route' => esc_url_raw($this->link_url($item['url'] ?? array(), '#')),
                'children' => array(),
            );
        }

        foreach ((array) $items as $item) {
            $parent = trim((string) ($item['parent'] ?? ''));
            if ('' === $parent || !isset($parents[$parent])) {
                continue;
            }
            $label = sanitize_text_field($item['label'] ?? '');
            if ('' === $label) {
                continue;
            }
            $navigation[$parents[$parent]]['children'][] = array(
                'label' => $label,
                'route' => esc_url_raw($this->link_url($item['url'] ?? array(), '#')),
            );
        }

        return $navigation;
    }

    private function wordpress_menu_data($menu_id) {
        $items = wp_get_nav_menu_items(absint($menu_id));
        if (!is_array($items)) {
            return array();
        }

        $navigation = array();
        $top_level_indexes = array();
        foreach ($items as $item) {
            if (0 !== (int) $item->menu_item_parent) {
                continue;
            }
            $top_level_indexes[(int) $item->ID] = count($navigation);
            $navigation[] = array(
                'label' => sanitize_text_field($item->title),
                'route' => esc_url_raw($item->url),
                'children' => array(),
            );
        }

        $top_parent = function ($item) use ($items) {
            $parent_id = (int) $item->menu_item_parent;
            $seen = array();
            while ($parent_id && !isset($seen[$parent_id])) {
                $seen[$parent_id] = true;
                $parent = null;
                foreach ($items as $candidate) {
                    if ((int) $candidate->ID === $parent_id) {
                        $parent = $candidate;
                        break;
                    }
                }
                if (!$parent || 0 === (int) $parent->menu_item_parent) {
                    return $parent_id;
                }
                $parent_id = (int) $parent->menu_item_parent;
            }
            return 0;
        };

        foreach ($items as $item) {
            if (0 === (int) $item->menu_item_parent) {
                continue;
            }
            $parent_id = $top_parent($item);
            if (!isset($top_level_indexes[$parent_id])) {
                continue;
            }
            $navigation[$top_level_indexes[$parent_id]]['children'][] = array(
                'label' => sanitize_text_field($item->title),
                'route' => esc_url_raw($item->url),
            );
        }

        return $navigation;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $navigation = 'wordpress' === ($settings['navigation_source'] ?? 'custom')
            ? $this->wordpress_menu_data($settings['wordpress_menu'] ?? 0)
            : $this->navigation_data($settings['menu_items'] ?? array());

        $cart_url = $this->link_url($settings['cart_url'] ?? array());
        if (!$cart_url) {
            $cart_url = function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/cos/');
        }
        $cart_count = function_exists('WC') && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
        if (function_exists('WC') && WC()->cart) {
            wp_enqueue_script('wc-cart-fragments');
        }
        $logout_url = $this->link_url($settings['logout_url'] ?? array());
        if (!$logout_url) {
            $logout_url = wp_logout_url(home_url('/'));
        }

        $attributes = array(
            'data-cm-header-root' => '',
            'data-account-label' => $settings['account_label'] ?? 'Contul meu',
            'data-account-url' => $this->link_url($settings['account_url'] ?? array(), '/contul-meu/'),
            'data-booking-label' => $settings['booking_label'] ?? 'PROGRAMEAZĂ',
            'data-booking-url' => $this->link_url($settings['booking_url'] ?? array(), '/contact/'),
            'data-booking-popup-id' => absint($settings['popup_id'] ?? 0),
            'data-cart-count' => absint($cart_count),
            'data-cart-drawer' => 'yes' === ($settings['cart_drawer'] ?? '') ? 'true' : 'false',
            'data-cart-url' => $cart_url,
            'data-home-url' => $this->link_url($settings['home_url'] ?? array(), home_url('/')),
            'data-is-logged-in' => is_user_logged_in() ? 'true' : 'false',
            'data-login-label' => $settings['login_label'] ?? 'Login',
            'data-login-url' => $this->link_url($settings['login_url'] ?? array(), '/contul-meu/'),
            'data-logo-image-url' => $settings['logo_image']['url'] ?? '',
            'data-logo-text' => $settings['logo_text'] ?? 'CLAUDIA MARIA',
            'data-logout-label' => $settings['logout_label'] ?? 'Logout',
            'data-logout-url' => $logout_url,
            'data-mini-cart-html' => cm_react_mini_cart_html(),
            'data-navigation' => wp_json_encode($navigation),
            'data-show-account' => 'yes' === ($settings['show_account'] ?? '') ? 'true' : 'false',
            'data-show-booking' => 'yes' === ($settings['show_booking'] ?? '') ? 'true' : 'false',
            'data-show-cart' => 'yes' === ($settings['show_cart'] ?? '') ? 'true' : 'false',
            'data-show-logo-desktop' => 'yes' === ($settings['show_logo_desktop'] ?? '') ? 'true' : 'false',
            'data-sticky' => 'yes' === ($settings['sticky'] ?? '') ? 'true' : 'false',
            'data-whatsapp-message' => sanitize_textarea_field($settings['whatsapp_message'] ?? ''),
            'data-whatsapp-number' => preg_replace('/\D+/', '', (string) ($settings['whatsapp_number'] ?? '')),
        );

        echo '<div';
        foreach ($attributes as $name => $value) {
            printf(' %s="%s"', esc_attr($name), esc_attr($value));
        }
        echo '></div>';
    }
}
