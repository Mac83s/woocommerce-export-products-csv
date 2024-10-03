<?php

namespace WoocommerceExportProductsCSVVendor\WPDesk\PluginBuilder\Storage;

class StorageFactory
{
    /**
     * @return PluginStorage
     */
    public function create_storage()
    {
        return new \WoocommerceExportProductsCSVVendor\WPDesk\PluginBuilder\Storage\WordpressFilterStorage();
    }
}
