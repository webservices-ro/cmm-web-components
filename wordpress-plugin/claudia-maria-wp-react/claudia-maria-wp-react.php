<?php
/**
 * Plugin Name: Claudia Maria React Components
 * Plugin URI: https://github.com/alexbran8/claudia-maria-web-components-releases
 * Description: React components and pages for the Claudia Maria website.
 * Version: __PLUGIN_VERSION__
 * Author: webservices.ro
 * Author URI: https://webservices.ro/
 * Update URI: https://webservices.ro/claudia-maria-wp-react/
 * Text Domain: claudia-maria-wp-react
 */

if (!defined('ABSPATH')) exit;

define('CM_REACT_VERSION', '__PLUGIN_VERSION__');
define('CM_REACT_PLUGIN_FILE', __FILE__);
define('CM_REACT_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CM_REACT_UPDATE_MANIFEST_URL', 'https://github.com/alexbran8/claudia-maria-web-components-releases/releases/latest/download/update.json');

require_once CM_REACT_PLUGIN_DIR . 'includes/plugin-updates.php';
require_once CM_REACT_PLUGIN_DIR . 'includes/settings.php';
require_once CM_REACT_PLUGIN_DIR . 'includes/assets.php';
require_once CM_REACT_PLUGIN_DIR . 'includes/shortcodes.php';
require_once CM_REACT_PLUGIN_DIR . 'includes/elementor.php';
