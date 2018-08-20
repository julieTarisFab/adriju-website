<?php
/**
* Plugin Name: Slide Anything PRO - Modal Popups for Slide Anything
* Plugin URI: http://edgewebpages.com
* Description: Slide Anything PRO allows you to create modal POPUPS for your Slide Anything slides. These can be image popups, a video embed (YouTube/Vimeo) popup, or popups containing custom HTML code or WordPress shortcodes.
* Author: Simon Edge
* Version: 2.0
* License: GPLv2 or later
*/

if (!defined('ABSPATH')) exit; // EXIT IF ACCESSED DIRECTLY

// SET CONSTANT FOR PLUGIN PATH
define('SAPRO_PLUGIN_PATH', plugins_url('/', __FILE__));

require 'php/slide-anything-pro-functions.php';

/* ##### PLUGIN ACTIVATION HOOK ##### */
register_activation_hook(__FILE__, 'sa_pro_plugin_activation' );

/* ##### PLUGIN ACTION HOOKS FOR THE SETTINGS PAGE ##### */
add_action('admin_menu', 'sapro_register_options_page');
add_action('admin_init', 'sapro_register_settings_group');
?>