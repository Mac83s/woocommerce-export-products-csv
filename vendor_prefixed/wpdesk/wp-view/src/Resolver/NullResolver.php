<?php

namespace WoocommerceExportProductsCSVVendor\WPDesk\View\Resolver;

use WoocommerceExportProductsCSVVendor\WPDesk\View\Renderer\Renderer;
use WoocommerceExportProductsCSVVendor\WPDesk\View\Resolver\Exception\CanNotResolve;
/**
 * This resolver never finds the file
 *
 * @package WPDesk\View\Resolver
 */
class NullResolver implements \WoocommerceExportProductsCSVVendor\WPDesk\View\Resolver\Resolver
{
    public function resolve($name, \WoocommerceExportProductsCSVVendor\WPDesk\View\Renderer\Renderer $renderer = null)
    {
        throw new \WoocommerceExportProductsCSVVendor\WPDesk\View\Resolver\Exception\CanNotResolve('Null Cannot resolve');
    }
}
