<?php
if (!defined('ABSPATH')) exit;

function cm_react_register_elementor_widgets($widgets_manager) {
    require_once CM_REACT_PLUGIN_DIR . 'includes/class-cm-elementor-header-widget.php';
    require_once CM_REACT_PLUGIN_DIR . 'includes/class-cm-elementor-footer-widget.php';
    require_once CM_REACT_PLUGIN_DIR . 'includes/class-cm-elementor-homepage-widget.php';
    require_once CM_REACT_PLUGIN_DIR . 'includes/class-cm-elementor-newsletter-widget.php';
    $widgets_manager->register(new CM_Elementor_Header_Widget());
    $widgets_manager->register(new CM_Elementor_Footer_Widget());
    $widgets_manager->register(new CM_Elementor_Homepage_Widget());
    $widgets_manager->register(new CM_Elementor_Newsletter_Widget());
}
add_action('elementor/widgets/register', 'cm_react_register_elementor_widgets');
