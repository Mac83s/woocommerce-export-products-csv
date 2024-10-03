<?php

namespace WoocommerceExportProductsCSVVendor\WPDesk\PluginBuilder\BuildDirector;

use WoocommerceExportProductsCSVVendor\WPDesk\PluginBuilder\Builder\AbstractBuilder;
use WoocommerceExportProductsCSVVendor\WPDesk\PluginBuilder\Plugin\AbstractPlugin;
use WoocommerceExportProductsCSVVendor\WPDesk\PluginBuilder\Storage\StorageFactory;
class LegacyBuildDirector
{
    /** @var AbstractBuilder */
    private $builder;
    public function __construct(\WoocommerceExportProductsCSVVendor\WPDesk\PluginBuilder\Builder\AbstractBuilder $builder)
    {
        $this->builder = $builder;
    }
    /**
     * Builds plugin
     */
    public function build_plugin()
    {
        $this->builder->build_plugin();
        $this->builder->init_plugin();
        $storage = new \WoocommerceExportProductsCSVVendor\WPDesk\PluginBuilder\Storage\StorageFactory();
        $this->builder->store_plugin($storage->create_storage());
    }
    /**
     * Returns built plugin
     *
     * @return AbstractPlugin
     */
    public function get_plugin()
    {
        return $this->builder->get_plugin();
    }
}
