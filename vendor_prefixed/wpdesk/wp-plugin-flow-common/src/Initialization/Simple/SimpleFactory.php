<?php

namespace WoocommerceExportProductsCSVVendor\WPDesk\Plugin\Flow\Initialization\Simple;

use WoocommerceExportProductsCSVVendor\WPDesk\Plugin\Flow\Initialization\InitializationFactory;
use WoocommerceExportProductsCSVVendor\WPDesk\Plugin\Flow\Initialization\InitializationStrategy;
/**
 * Can decide if strategy is for free plugin or paid plugin
 */
class SimpleFactory implements \WoocommerceExportProductsCSVVendor\WPDesk\Plugin\Flow\Initialization\InitializationFactory
{
    /** @var bool */
    private $free;
    /**
     * @param bool $free True for free/repository plugin
     */
    public function __construct($free = \false)
    {
        $this->free = $free;
    }
    /**
     * Create strategy according to the given flag
     *
     * @param \WPDesk_Plugin_Info $info
     *
     * @return InitializationStrategy
     */
    public function create_initialization_strategy(\WoocommerceExportProductsCSVVendor\WPDesk_Plugin_Info $info)
    {
        if ($this->free) {
            return new \WoocommerceExportProductsCSVVendor\WPDesk\Plugin\Flow\Initialization\Simple\SimpleFreeStrategy($info);
        }
        return new \WoocommerceExportProductsCSVVendor\WPDesk\Plugin\Flow\Initialization\Simple\SimplePaidStrategy($info);
    }
}
