<?php

namespace WoocommerceExportProductsCSVVendor\WPDesk\PluginBuilder\Plugin;

interface HookablePluginDependant extends \WoocommerceExportProductsCSVVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * Set Plugin.
     *
     * @param AbstractPlugin $plugin Plugin.
     *
     * @return null
     */
    public function set_plugin(\WoocommerceExportProductsCSVVendor\WPDesk\PluginBuilder\Plugin\AbstractPlugin $plugin);
    /**
     * Get plugin.
     *
     * @return AbstractPlugin.
     */
    public function get_plugin();
}
