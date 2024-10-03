<?php

/**
 * Plugin Name: Woocommerce Export Products To CSV
 * Plugin URI: https://www.wpdesk.net/products/woocommerce-export-products-csv/
 * Description: Woocommerce Export Products To CSV
 * Version: 1.0.0
 * Author: WP Desk
 * Author URI: https://www.wpdesk.net/
 * Text Domain: woocommerce-export-products-csv
 * Domain Path: /lang/
 * ​
 * Requires at least: 5.7
 * Tested up to: 6.0
 * WC requires at least: 6.6
 * WC tested up to: 7.0
 * Requires PHP: 7.2
 * ​
 * Copyright 2022 WP Desk Ltd.
 * ​
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

use WPDesk\WoocommerceExportProductsCSV\Plugin;

defined('ABSPATH') || exit;

require_once __DIR__ . '/vendor/autoload.php';

add_action('before_woocommerce_init', function () {
	if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
	}
});

/* THESE TWO VARIABLES CAN BE CHANGED AUTOMATICALLY */
$plugin_version = '1.0.0';

$plugin_name        = 'Woocommerce Export Products To CSV';
$plugin_class_name  = Plugin::class;
$plugin_text_domain = 'woocommerce-export-products-csv';
$product_id         = 'Woocommerce Export Products To CSV';
$plugin_file        = __FILE__;
$plugin_dir         = __DIR__;

// todo: only for paid plugins.
$plugin_shops = [
	'default' => 'https://www.wpdesk.net/',
];

$requirements = [
	'php'          => '7.2',
	'wp'           => '5.7',
	'repo_plugins' => [
		[
			'name'      => 'woocommerce/woocommerce.php',
			'nice_name' => 'WooCommerce',
			'version'   => '6.6',
		],
	],
];

// todo: only for free plugins. For paid plugins use plugin-init-php52.php.
require __DIR__ . '/vendor_prefixed/wpdesk/wp-plugin-flow-common/src/plugin-init-php52-free.php';
