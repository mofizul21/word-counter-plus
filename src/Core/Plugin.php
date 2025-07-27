<?php

namespace WCP\Core;

use WCP\Admin\Admin_Column;
use WCP\Admin\Admin_Filter;

/**
 * Class Plugin
 *
 * @package WCP\Core
 *
 * @since 1.2.0
 */
class Plugin
{
    /**
     * Run the plugin.
     *
     * @since 1.2.0
     * @return void
     */
    public function run(): void
    {
        $this->load_textdomain();
        $this->register_hooks();
        $this->init_admin();
    }

    /**
     * Load the plugin text domain.
     *
     * @since 1.2.0
     * @return void
     */
    public function load_textdomain(): void
    {
        load_plugin_textdomain('word-counter-plus', false, dirname(plugin_basename(WCP_PLUGIN_FILE)) . '/languages');
    }

    /**
     * Register the activation and deactivation hooks.
     */
    public function register_hooks(): void
    {
        register_activation_hook(WCP_PLUGIN_FILE, [$this, 'activate']);
        register_deactivation_hook(WCP_PLUGIN_FILE, [$this, 'deactivate']);
    }

    /**
     * Initialize the admin functionality.
     */
    public function init_admin(): void
    {
        new Admin_Column();
        new Admin_Filter();
    }

    /**
     * Plugin activation hook.
     */
    public function activate(): void
    {
        if (!wp_next_scheduled('wcp_schedule_initial_wordcount')) {
            wp_schedule_single_event(time() + 3, 'wcp_schedule_initial_wordcount');
        }
    }

    /**
     * Plugin deactivation hook.
     */
    public function deactivate(): void
    {
        wp_clear_scheduled_hook('wcp_schedule_initial_wordcount');
    }
}
