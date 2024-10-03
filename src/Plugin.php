<?php

/**
 * Plugin main class.
 */

namespace WPDesk\WoocommerceExportProductsCSV;

use WoocommerceExportProductsCSVVendor\WPDesk\PluginBuilder\Plugin\AbstractPlugin;
use WoocommerceExportProductsCSVVendor\WPDesk\PluginBuilder\Plugin\HookableCollection;
use WoocommerceExportProductsCSVVendor\WPDesk\PluginBuilder\Plugin\HookableParent;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use League\Csv\Writer; // Import League CSV Writer

/**
 * Main plugin class.
 *
 * @codeCoverageIgnore
 */

class Plugin extends AbstractPlugin implements LoggerAwareInterface, HookableCollection
{

	use LoggerAwareTrait;
	use HookableParent;

	/**
	 * Init hooks.
	 *
	 * @return void
	 */
	public function hooks(): void
	{
		parent::hooks();
		$this->hooks_on_hookable_objects();

		add_action('manage_posts_extra_tablenav', [$this, 'add_export_button'], 20, 1);
		add_action('admin_init', [$this, 'maybe_export_csv']);
	}

	/**
	 * Add the export button to WooCommerce product list page.
	 *
	 * @param string $which
	 */
	public function add_export_button($which)
	{
		if ('top' === $which && 'product' === get_post_type()) {
?>
			<div class="alignleft actions">
				<form method="get" action="<?php echo esc_url(admin_url('admin.php')); ?>">
					<input type="hidden" name="action" value="export_products_csv" />
					<input type="submit" name="export_csv" class="button-primary" value="<?php esc_attr_e('Export Products to CSV', 'woocommerce-export-products-csv'); ?>" />
				</form>
			</div>
<?php
		}
	}

	/**
	 * Check if the CSV export action is triggered, and generate CSV if so.
	 */
	public function maybe_export_csv()
	{
		if (isset($_GET['action']) && $_GET['action'] === 'export_products_csv') {
			$this->generate_csv();
		}
	}

	/**
	 * Generate CSV file with WooCommerce products using League CSV.
	 */
	public function generate_csv()
	{
		if (! current_user_can('manage_woocommerce')) {
			wp_die(__('You do not have sufficient permissions to access this action.', 'woocommerce-export-products-csv'));
		}

		$args = [
			'post_type'      => 'product',
			'posts_per_page' => -1,
		];
		$products = new \WP_Query($args);

		$csv = Writer::createFromFileObject(new \SplTempFileObject());

		$csv->insertOne(['Product ID', 'Product Name', 'Variant Name', 'Product Type', 'Categories', 'SKU', 'Price', 'Regular Price']);

		if ($products->have_posts()) {
			while ($products->have_posts()) {
				$products->the_post();
				$product = wc_get_product(get_the_ID());

				$id            = $product->get_id();
				$name          = $product->get_name();
				$categories    = strip_tags(wc_get_product_category_list($product->get_id(), ', '));
				$sku           = $product->get_sku() ? $product->get_sku() : '';
				$price         = $product->get_price();
				$regular_price = $product->get_regular_price();
				$product_type  = $product->get_type();

				if ($product->is_type('variable')) {
					$variations = $product->get_available_variations();
					foreach ($variations as $variation_data) {
						$variation = wc_get_product($variation_data['variation_id']);

						$variation_id      = $variation->get_id();
						$variation_name    = implode(', ', $variation->get_attributes());
						$variation_sku     = $variation->get_sku() ? $variation->get_sku() : $sku;
						$variation_price   = $variation->get_price();
						$variation_regular_price = $variation->get_regular_price();

						$csv->insertOne([
							$variation_id,
							$name,
							$variation_name,
							'variation',
							$categories,
							$variation_sku,
							$variation_price,
							$variation_regular_price
						]);
					}
				} else {
					$csv->insertOne([
						$id,
						$name,
						'',
						$product_type,
						$categories,
						$sku,
						$price,
						$regular_price
					]);
				}
			}
		}

		wp_reset_postdata();

		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename="woocommerce_products_export_' . date('Y-m-d') . '.csv"');
		$csv->output();
		exit;
	}
}
