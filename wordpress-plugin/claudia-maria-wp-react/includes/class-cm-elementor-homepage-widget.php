<?php

if (!defined('ABSPATH')) exit;

/**
 * Elementor bridge for the React homepage.
 * PHP supplies WordPress-managed URLs and media; React owns the UI markup.
 */
class CM_Elementor_Homepage_Widget extends \Elementor\Widget_Base {
    public function get_name() { return 'cm-homepage'; }
    public function get_title() { return esc_html__('Claudia Maria Homepage', 'claudia-maria-wp-react'); }
    public function get_icon() { return 'eicon-home-heart'; }
    public function get_categories() { return array('general'); }
    public function get_keywords() { return array('homepage', 'home', 'claudia maria', 'landing page'); }

    public function get_style_depends() {
        $handles = cm_react_register_assets();
        return $handles['styles'];
    }

    public function get_script_depends() {
        $handles = cm_react_register_assets();
        return $handles['script'] ? array($handles['script']) : array();
    }

    private function image_default($name) {
        return array('url' => plugins_url('dist/assets/' . $name . '.jpg', CM_REACT_PLUGIN_FILE));
    }

    private function homepage_content() {
        static $content = null;
        if (null === $content) {
            $path = CM_REACT_PLUGIN_DIR . 'content/homepage.json';
            $decoded = is_readable($path) ? json_decode((string) file_get_contents($path), true) : array();
            $content = is_array($decoded) ? $decoded : array();
        }
        return $content;
    }

    private function add_text($id, $label, $default = '', $type = null) {
        $this->add_control($id, array(
            'label' => esc_html__($label, 'claudia-maria-wp-react'),
            'type' => $type ?: \Elementor\Controls_Manager::TEXT,
            'default' => $default,
            'label_block' => true,
        ));
    }

    private function add_link($id, $label, $default) {
        $this->add_control($id, array(
            'label' => esc_html__($label, 'claudia-maria-wp-react'),
            'type' => \Elementor\Controls_Manager::URL,
            'default' => array('url' => $default),
            'show_external' => true,
        ));
    }

    private function add_image($id, $label, $asset) {
        $this->add_control($id, array(
            'label' => esc_html__($label, 'claudia-maria-wp-react'),
            'type' => \Elementor\Controls_Manager::MEDIA,
            'default' => $this->image_default($asset),
        ));
    }

    private function add_cards($id, $label, $defaults, $with_link = false, $with_card_link = false) {
        $repeater = new \Elementor\Repeater();
        $repeater->add_control('title', array('label' => esc_html__('Title', 'claudia-maria-wp-react'), 'type' => \Elementor\Controls_Manager::TEXT, 'label_block' => true));
        $repeater->add_control('text', array('label' => esc_html__('Text', 'claudia-maria-wp-react'), 'type' => \Elementor\Controls_Manager::WYSIWYG));
        if ($with_link) {
            $repeater->add_control('link_label', array('label' => esc_html__('Link label', 'claudia-maria-wp-react'), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'DESCOPERĂ →'));
            $repeater->add_control('link', array('label' => esc_html__('Link', 'claudia-maria-wp-react'), 'type' => \Elementor\Controls_Manager::URL, 'default' => array('url' => '/terapii/')));
            $repeater->add_control('featured', array('label' => esc_html__('Featured', 'claudia-maria-wp-react'), 'type' => \Elementor\Controls_Manager::SWITCHER));
        }
        if ($with_card_link) {
            $repeater->add_control('card_link', array('label' => esc_html__('Card link', 'claudia-maria-wp-react'), 'type' => \Elementor\Controls_Manager::URL, 'show_external' => false));
        }
        $this->add_control($id, array(
            'label' => esc_html__($label, 'claudia-maria-wp-react'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => $defaults,
            'title_field' => '{{{ title }}}',
        ));
    }

    protected function register_controls() {
        $plugin_settings = cm_react_get_settings();
        $homepage_content = $this->homepage_content();
        $audience = $homepage_content['audience'];
        $process = $homepage_content['process'];
        $therapies = $homepage_content['therapies'];

        $this->start_controls_section('hero_section', array('label' => esc_html__('Hero', 'claudia-maria-wp-react')));
        $this->add_text('hero_eyebrow', 'Eyebrow', 'balneofiziokinetoterapeut / REFLEXOTERAPEUT / FORMATOR');
        $this->add_text('hero_title', 'Heading (H1)', 'Nu trebuie să știi<br>de ce terapie ai nevoie.', \Elementor\Controls_Manager::TEXTAREA);
        $this->add_text('hero_lead', 'Lead', 'Vii cu ceea ce simți.<br><strong>Începem de acolo.</strong>', \Elementor\Controls_Manager::TEXTAREA);
        $this->add_text('hero_text', 'Description', '<em>Te ascult, privim împreună și descoperim, pas cu pas, de ce are nevoie corpul tău acum.</em>', \Elementor\Controls_Manager::TEXTAREA);
        $this->add_text('hero_meta', 'Highlights', 'PREVENȚIE • RECUPERARE • ABORDARE INTEGRATIVĂ');
        $this->add_text('booking_label', 'Booking button label', 'PROGRAMEAZĂ O CONSULTAȚIE');
        $this->add_link('booking_link', 'Booking link', '#contact');
        $this->add_control('booking_action', array(
            'label' => esc_html__('Booking action', 'claudia-maria-wp-react'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'whatsapp',
            'options' => array(
                'whatsapp' => esc_html__('Open WhatsApp', 'claudia-maria-wp-react'),
                'popup' => esc_html__('Elementor popup', 'claudia-maria-wp-react'),
                'link' => esc_html__('Booking link', 'claudia-maria-wp-react'),
            ),
        ));
        $this->add_control('whatsapp_number', array(
            'label' => esc_html__('WhatsApp number', 'claudia-maria-wp-react'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => $plugin_settings['whatsapp_number'] ?? '',
            'description' => esc_html__('Use the international format without +, spaces, or punctuation.', 'claudia-maria-wp-react'),
            'condition' => array('booking_action' => 'whatsapp'),
        ));
        $this->add_control('whatsapp_message', array(
            'label' => esc_html__('WhatsApp message', 'claudia-maria-wp-react'),
            'type' => \Elementor\Controls_Manager::TEXTAREA,
            'default' => $plugin_settings['whatsapp_initial_message'] ?? '',
            'condition' => array('booking_action' => 'whatsapp'),
        ));
        $this->add_control('popup_id', array('label' => esc_html__('Elementor popup ID', 'claudia-maria-wp-react'), 'type' => \Elementor\Controls_Manager::NUMBER, 'min' => 1, 'description' => esc_html__('Optional. When set, the booking buttons open this Elementor Pro popup.', 'claudia-maria-wp-react'), 'condition' => array('booking_action' => 'popup')));
        $this->add_image('hero_image', 'Hero image', 'hero');
        $this->add_text('hero_alt', 'Hero image alt text', 'Claudia Maria în cabinet, așezată într-un fotoliu galben, cu element anatomic cranio-facial în fundal');
        $this->end_controls_section();

        $this->start_controls_section('central_section', array('label' => esc_html__('Central idea', 'claudia-maria-wp-react')));
        $this->add_text('central_kicker', 'Kicker', 'CONCEPT ȘI ABORDARE');
        $this->add_text('central_title', 'Heading', 'Nu terapia este punctul de plecare. Tu ești.');
        $this->add_text('central_body', 'Content', '<p>Nu cred că există o singură terapie potrivită pentru toți oamenii. Fiecare om are povestea lui. Fiecare corp se adaptează diferit. Iar ceea ce simțim astăzi poate avea în spate mai multe lucruri, atât emoțional cât și somatic, care merită privite și abordate împreună.</p><p>De aceea, nu aleg o terapie înainte să te cunosc. Te ascult. Te evaluez. Privesc ansamblul. Apoi alegem împreună direcția și abordarea de lucru.</p>', \Elementor\Controls_Manager::WYSIWYG);
        $this->add_text('central_quote', 'Quote', 'Pentru mine, terapia este doar un instrument.<br><strong>Omul este punctul de plecare.</strong>', \Elementor\Controls_Manager::TEXTAREA);
        $this->add_text('central_steps', 'Summary steps (one per line)', "ASCULT\nEVALUEZ\nALEG\nLUCREZ\nREEVALUEZ", \Elementor\Controls_Manager::TEXTAREA);
        $this->end_controls_section();

        $this->start_controls_section('about_section', array('label' => esc_html__('About', 'claudia-maria-wp-react')));
        $this->add_text('about_kicker', 'Kicker', 'Despre mine');
        $this->add_text('about_title', 'Heading', 'De ce fac ceea ce fac');
        $this->add_text('about_body', 'Content', '<p>Sunt Claudia Maria Murariu, balneofiziokinetoterapeut și formator. Curiozitatea pentru corpul uman a început pentru mine cu mult înainte să devină profesie. Am vrut mereu să înțeleg de ce ne îmbolnăvim, de ce ne doare (căci eu de mică am trăit cu dureri și afecțiuni pe care mi le-am diagnosticat singură), de ce fiecare om este diferit și, mai ales, ce îl ajută să se adapteze și să se refacă.</p><p>Am studiat științele naturii, apoi balneofiziokinetoterapie și recuperare medicală la universitatea de medicină și farmacie, apoi master în prevenția și recuperarea cardiovasculară și pulmonară, iar de-a lungul anilor am continuat să învăț și să caut.</p><p>Am trecut prin foarte multe cursuri și numeroase formări, mulți ani de practică în terapii manuale, osteopatie și diferite abordări terapeutice complementare. Cu cât am învățat mai mult, cu atât am înțeles că organismul uman este mult mai complex decât pare. Și tocmai de aceea nu caut să pun omul într-o metodă sau într-un protocol, căci fiecare om este unic. Caut să înțeleg omul din fața mea. Lucrez personalizat cu fiecare.</p>', \Elementor\Controls_Manager::WYSIWYG);
        $this->add_link('about_link', 'About link', '/despre-mine/');
        $this->add_image('about_image', 'About image', 'about');
        $this->add_text('about_alt', 'About image alt text', 'Claudia Maria în cabinet, lucrând cu un model anatomic al coloanei');
        $this->end_controls_section();

        $this->start_controls_section('audience_section', array('label' => esc_html__('Audience', 'claudia-maria-wp-react')));
        $this->add_text('audience_title', 'Heading', $audience['title']);
        $this->add_cards('audiences', 'Audience cards', $audience['cards']);
        $this->add_text('audience_quote', 'Quote', $audience['quote']);
        $this->end_controls_section();

        $this->start_controls_section('therapies_section', array('label' => esc_html__('Therapies', 'claudia-maria-wp-react')));
        $this->add_text('therapies_title', 'Heading', $therapies['title']);
        $this->add_text('therapies_intro', 'Introduction', $therapies['intro'], \Elementor\Controls_Manager::TEXTAREA);
        $this->add_image('manual_image', 'Manual therapy image', 'therapy-manual');
        $this->add_image('reflexology_image', 'Reflexology image', 'therapy-reflexology');
        $this->add_cards('therapies', 'Therapy cards', $therapies['cards'], true);
        $this->add_text('natural_title', 'Natural-care title', $therapies['natural']['title']);
        $this->add_text('natural_body', 'Natural-care content', $therapies['natural']['body'], \Elementor\Controls_Manager::WYSIWYG);
        $this->add_link('nature_link', 'Nature link', 'https://claudiamaria.ro/shop/');
        $this->add_control('raindrop_image', array(
            'label' => esc_html__('Raindrop image', 'claudia-maria-wp-react'),
            'type' => \Elementor\Controls_Manager::MEDIA,
            'default' => array('url' => ''),
        ));
        $this->add_text('raindrop_image_alt', 'Nature image alt text', 'Natură pentru sănătate');
        $this->add_control('ritual_image', array(
            'label' => esc_html__('Raindrop ritual image', 'claudia-maria-wp-react'),
            'type' => \Elementor\Controls_Manager::MEDIA,
            'default' => array('url' => ''),
        ));
        $this->add_text('ritual_image_alt', 'Raindrop ritual image alt text', 'Claudia Maria în timpul ritualului Raindrop');
        $this->end_controls_section();

        $this->start_controls_section('process_section', array('label' => esc_html__('Process', 'claudia-maria-wp-react')));
        $this->add_text('process_title', 'Heading', $process['title']);
        $this->add_cards('process_steps', 'Process steps', $process['steps']);
        $this->end_controls_section();

        $this->start_controls_section('integrative_section', array('label' => esc_html__('Integrative medicine', 'claudia-maria-wp-react')));
        $this->add_text('integrative_title', 'Heading', 'Medicina integrativă înseamnă să privim omul ca întreg.');
        $this->add_text('integrative_body', 'Content', '<p>Cred că sănătatea nu se construiește într-un singur loc și nici printr-o singură metodă. Omul este unic și complex și trebuie văzut și abordat ca atare.</p><p>Uneori avem nevoie de medicina convențională. Alteori, putem adăuga și alte forme de sprijin, atunci când ele sunt potrivite pentru noi.</p><p>Dacă ai un diagnostic sau urmezi un tratament medical, acesta este parte din povestea ta și țin cont de el atunci când lucrăm.</p>', \Elementor\Controls_Manager::WYSIWYG);
        $this->add_text('integrative_quote', 'Quote', '<strong>Nu înlocuiesc medicina convențională. Sunt aici să aduc un sprijin în plus, acolo unde poate fi de folos.</strong>', \Elementor\Controls_Manager::TEXTAREA);
        $this->end_controls_section();

        $this->start_controls_section('testimonials_section', array('label' => esc_html__('Testimonials', 'claudia-maria-wp-react')));
        $this->add_control('testimonials_source', array(
            'label' => esc_html__('Source', 'claudia-maria-wp-react'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'posts',
            'options' => array(
                'posts' => esc_html__('Testimonial posts', 'claudia-maria-wp-react'),
                'manual' => esc_html__('Manual entries', 'claudia-maria-wp-react'),
            ),
        ));
        $this->add_control('testimonials_count', array(
            'label' => esc_html__('Number of testimonials', 'claudia-maria-wp-react'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 9,
            'min' => 1,
            'max' => 12,
            'condition' => array('testimonials_source' => 'posts'),
        ));
        $this->add_cards('testimonials', 'Testimonials', array(
            array('title' => 'Nume / inițiale', 'text' => '„Aici va intra un testimonial real.”'),
            array('title' => 'Nume / inițiale', 'text' => '„Aici va intra un testimonial real.”'),
            array('title' => 'Nume / inițiale', 'text' => '„Aici va intra un testimonial real.”'),
        ));
        $this->end_controls_section();

        $this->start_controls_section('resources_section', array('label' => esc_html__('Resources', 'claudia-maria-wp-react')));
        $this->add_text('resources_title', 'Heading', 'Cred că atunci când înțelegi, începi să ai mai multă putere și speranță.');
        $this->add_text('resources_intro', 'Introduction', 'De aceea, vreau ca aici să găsești informații care să te ajute să-ți înțelegi mai bine corpul și să privești sănătatea cu mai multă claritate.', \Elementor\Controls_Manager::TEXTAREA);
        $this->add_cards('resources', 'Resource cards', array(
            array('title' => 'Articole', 'text' => 'Despre corp, sănătate, recuperare și lucrurile pe care le descopăr în practica mea.', 'card_link' => array('url' => '/blog/')),
            array('title' => 'Ghiduri', 'text' => 'Materiale practice pe care le poți descărca și folosi.', 'card_link' => array('url' => '/resurse/')),
            array('title' => 'Meditații', 'text' => 'Practici gratuite și materiale pe care le poți aprofunda.', 'card_link' => array('url' => '/resurse/')),
        ), false, true);
        $this->end_controls_section();

        $this->start_controls_section('courses_section', array('label' => esc_html__('Courses', 'claudia-maria-wp-react')));
        $this->add_text('courses_title', 'Heading', 'Și dacă vrei să înveți, te aștept și acolo.');
        $this->add_text('courses_body', 'Content', '<p>De-a lungul anilor, experiența mea nu a rămas doar în cabinet. Am început să predau, să împărtășesc ceea ce am învățat și să creez propriile cursuri și formări pentru oameni care vor să înțeleagă corpul dintr-o altă perspectivă.</p><p><strong>Workshop-uri • Cursuri profesionale • Formări</strong></p>', \Elementor\Controls_Manager::WYSIWYG);
        $this->add_link('courses_link', 'Courses link', '/cursuri/');
        $this->add_image('courses_image', 'Courses image', 'courses');
        $this->end_controls_section();

        $this->start_controls_section('student_testimonials_section', array('label' => esc_html__('Student testimonials', 'claudia-maria-wp-react')));
        $this->add_text('student_testimonials_title', 'Heading', 'Ce spun cei care au învățat alături de mine');
        $this->add_cards('student_testimonials', 'Student testimonials', array(
            array('title' => 'Alina', 'text' => 'Energia de la Modulul 1 a fost incredibil de relaxantă. Datorită stilului tău de a preda, mi-ai trezit o curiozitate profundă, care m-a ținut cu totul conectată și atentă.'),
            array('title' => 'Anne', 'text' => 'A trecut foarte mult timp de când nu am mai simțit o energie atât de pozitivă și de calmă. Informațiile și tehnicile predate mi-au adus beneficii directe atât personal, cât și profesional.'),
            array('title' => 'Mirela', 'text' => 'A fost un curs excelent, care a răspuns nevoilor și așteptărilor mele. Informațiile au fost clare, concise și explicate foarte bine, pe înțelesul tuturor.'),
        ));
        $this->add_link('student_testimonials_link', 'All student testimonials link', 'https://centrulsakura.ro/cursuri/curs-biodinamica-craniosacrala-miofasciala-modulul-i-16-18-octombrie-2026-bucuresti/#recenzii');
        $this->end_controls_section();

        $this->start_controls_section('final_section', array('label' => esc_html__('Final call to action', 'claudia-maria-wp-react')));
        $this->add_text('final_title', 'Heading', 'Nu trebuie să știi de unde să începi.');
        $this->add_text('final_text', 'Text', 'Vii cu ceea ce simți.<br>De acolo începem, împreună.', \Elementor\Controls_Manager::TEXTAREA);
        $this->end_controls_section();

        $this->start_controls_section('style_section', array('label' => esc_html__('Colors & width', 'claudia-maria-wp-react'), 'tab' => \Elementor\Controls_Manager::TAB_STYLE));
        foreach (array('navy' => array('Navy', '#183b61'), 'blue' => array('Blue', '#35698f'), 'gold' => array('Gold', '#c8aa70'), 'text' => array('Text', '#304457'), 'cream' => array('Cream background', '#faf8f2'), 'ice' => array('Blue background', '#eef5f8')) as $key => $color) {
            $this->add_control('color_' . $key, array('label' => esc_html__($color[0], 'claudia-maria-wp-react'), 'type' => \Elementor\Controls_Manager::COLOR, 'default' => $color[1], 'selectors' => array('{{WRAPPER}} .cm-home' => '--cm-home-' . $key . ': {{VALUE}};')));
        }
        $this->add_responsive_control('content_width', array('label' => esc_html__('Content width', 'claudia-maria-wp-react'), 'type' => \Elementor\Controls_Manager::SLIDER, 'size_units' => array('px'), 'range' => array('px' => array('min' => 800, 'max' => 1600)), 'default' => array('size' => 1180, 'unit' => 'px'), 'selectors' => array('{{WRAPPER}} .cm-home-container' => 'width: min({{SIZE}}{{UNIT}}, 92%);')));
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

    private function url($setting, $fallback = '#') {
        return !empty($setting['url']) ? $setting['url'] : $fallback;
    }

    private function link_attrs($setting) {
        $attrs = '';
        if (!empty($setting['is_external'])) $attrs .= ' target="_blank"';
        if (!empty($setting['nofollow'])) $attrs .= ' rel="nofollow"';
        return $attrs;
    }

    private function button($label, $link, $secondary = false, $popup_id = 0) {
        $class = 'cm-home-button ' . ($secondary ? 'cm-home-button--secondary' : 'cm-home-button--primary');
        $popup = $popup_id ? ' onclick="event.preventDefault();if(window.elementorProFrontend){elementorProFrontend.modules.popup.showPopup({id:' . absint($popup_id) . '});}"' : '';
        printf('<a class="%1$s" href="%2$s"%3$s%4$s>%5$s</a>', esc_attr($class), esc_url($this->url($link)), $this->link_attrs($link), $popup, esc_html($label));
    }

    private function therapy_link($item, $index) {
        $link = $item['link'] ?? array();
        $current_url = $this->url($link, '');
        if ($current_url && '/terapii/' !== $current_url) return $link;

        $urls = array(
            'https://centrulsakura.ro/osteoterapii/',
            'https://centrulsakura.ro/terapii/hirudoterapie-terapie-cu-lipitori-medicinale/',
            'https://centrulsakura.ro/terapii/reflexoterapia-plantara/',
            'https://centrulsakura.ro/terapii/osteoterapie-pediatrica-bebelusi/',
            'https://centrulsakura.ro/terapii/osteoterapie-generala/',
        );
        $link['url'] = $urls[$index] ?? '/terapii/';
        return $link;
    }

    private function nature_link($link) {
        $current_url = untrailingslashit($this->url($link, ''));
        if (in_array($current_url, array('https://claudiamaria.ro', 'https://claudiamaria.ro/produse'), true)) {
            $link['url'] = 'https://claudiamaria.ro/shop/';
        }
        return $link;
    }

    private function testimonials($settings) {
        if ('posts' !== ($settings['testimonials_source'] ?? 'posts')) {
            return $settings['testimonials'] ?? array();
        }

        $posts = get_posts(array(
            'post_type' => 'testimonial',
            'post_status' => 'publish',
            'posts_per_page' => max(1, min(12, absint($settings['testimonials_count'] ?? 9))),
            'orderby' => 'date',
            'order' => 'DESC',
            'no_found_rows' => true,
        ));

        if (!$posts) {
            return $settings['testimonials'] ?? array();
        }

        return array_map(function ($post) {
            $text = get_post_meta($post->ID, 'continut', true);
            if ('' === trim((string) $text)) $text = $post->post_content;
            if ('' === trim((string) $text)) $text = get_post_meta($post->ID, 'review', true);

            $author = get_post_meta($post->ID, 'short_name', true);
            if ('' === trim((string) $author)) $author = get_post_meta($post->ID, 'autor', true);
            if ('' === trim((string) $author)) $author = $post->post_title;

            return array(
                'title' => $author,
                'text' => $text,
                'rating' => get_post_meta($post->ID, 'rating', true),
                'icon' => get_post_meta($post->ID, 'testimonial_icon', true),
            );
        }, $posts);
    }

    private function booking_target($settings) {
        $action = $settings['booking_action'] ?? 'whatsapp';
        $popup_id = 'popup' === $action ? absint($settings['popup_id'] ?? 0) : 0;

        if ('whatsapp' === $action) {
            $number = preg_replace('/\D+/', '', (string) ($settings['whatsapp_number'] ?? ''));
            if ($number) {
                return array(
                    'link' => array(
                        'url' => 'https://wa.me/' . $number . '?text=' . rawurlencode(sanitize_textarea_field($settings['whatsapp_message'] ?? '')),
                        'is_external' => true,
                    ),
                    'popup_id' => 0,
                );
            }
        }

        return array('link' => $settings['booking_link'], 'popup_id' => $popup_id);
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $booking = $this->booking_target($settings);
        $nature_link = $this->nature_link($settings['nature_link']);

        cm_react_assets();
        ?>
        <div
            data-cm-homepage-root
            data-about-image-url="<?php echo esc_url($settings['about_image']['url'] ?? ''); ?>"
            data-booking-popup-id="<?php echo esc_attr($booking['popup_id']); ?>"
            data-booking-url="<?php echo esc_url($booking['link']['url'] ?? '#contact'); ?>"
            data-courses-image-url="<?php echo esc_url($settings['courses_image']['url'] ?? ''); ?>"
            data-courses-url="<?php echo esc_url($this->url($settings['courses_link'] ?? array(), '#')); ?>"
            data-hero-image-url="<?php echo esc_url($settings['hero_image']['url'] ?? ''); ?>"
            data-manual-image-url="<?php echo esc_url($settings['manual_image']['url'] ?? ''); ?>"
            data-nature-url="<?php echo esc_url($this->url($nature_link, 'https://claudiamaria.ro/shop/')); ?>"
            data-raindrop-image-url="<?php echo esc_url($settings['raindrop_image']['url'] ?? ''); ?>"
            data-reflexology-image-url="<?php echo esc_url($settings['reflexology_image']['url'] ?? ''); ?>"
            data-ritual-image-url="<?php echo esc_url($settings['ritual_image']['url'] ?? ''); ?>"
            data-therapies-url="/servicii/"
        >
            <noscript><?php esc_html_e('Această pagină necesită JavaScript pentru afișarea conținutului.', 'claudia-maria-wp-react'); ?></noscript>
        </div>
        <?php
    }
}
