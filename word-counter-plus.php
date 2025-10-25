<?php
/*
Plugin Name: Word Counter Plus
Description: Track and display word count for WordPress posts, aiding in content creation and editing efficiency. Count words from Dashboard → Posts column.
Version: 1.2.5
Author: Mofizul Islam
Author URI: https://mofizul.com/
License: GPL2 or later
Text Domain: word-counter-plus
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define WCP_PLUGIN_FILE
if (!defined('WCP_PLUGIN_FILE')) {
    define('WCP_PLUGIN_FILE', __FILE__);
}

// Include the autoloader
require_once __DIR__ . '/vendor/autoload.php';

/**
 * Initialize the plugin.
 *
 * @since 1.2.0
 * @return void
 */
function wcp_word_counter_plus_init(): void
{
    $plugin = new WCP\Core\Plugin();
    $plugin->run();
}

wcp_word_counter_plus_init();
