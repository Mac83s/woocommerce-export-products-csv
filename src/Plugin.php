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
 * Main plugin class. The most important flow decisions are made here.
 *
 * @codeCoverageIgnore
 */
class Plugin extends AbstractPlugin implements LoggerAwareInterface, HookableCollection {


	use LoggerAwareTrait;
	use HookableParent;

	/**
	 * Init hooks.
	 *
	 * @return void
	 */
	public function hooks(): void {
		parent::hooks();

		$this->hooks_on_hookable_objects();

		// Dodaj hook do admin_menu, aby dodać menu eksportu.
		add_action( 'admin_menu', [ $this, 'add_export_menu' ] );
	}

	/**
	 * Dodaj menu w WooCommerce dla eksportu produktów.
	 */
	public function add_export_menu() {
		add_submenu_page(
			'woocommerce',
			__( 'Eksportuj Produkty do CSV', 'woocommerce-export-products-csv' ),
			__( 'Eksport CSV', 'woocommerce-export-products-csv' ),
			'manage_woocommerce',
			'wc-export-products-csv',
			[ $this, 'export_page' ]
		);
	}

	/**
	 * Strona eksportu CSV.
	 */
	public function export_page() {         ?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Eksportuj Produkty do CSV', 'woocommerce-export-products-csv' ); ?></h1>
			<form method="post" action="">
				<input type="submit" name="export_csv" class="button-primary" value="<?php esc_attr_e( 'Pobierz CSV', 'woocommerce-export-products-csv' ); ?>" />
			</form>
		</div>
		<?php

		if ( isset( $_POST['export_csv'] ) ) {
			$this->generate_csv();
		}
	}

	/**
	 * Generowanie pliku CSV z produktami WooCommerce przy użyciu League CSV.
	 */
	public function generate_csv() {
		// Pobieranie produktów WooCommerce
		$args     = [
			'post_type'      => 'product',
			'posts_per_page' => -1,
		];
		$products = new \WP_Query( $args );

		// Inicjalizacja League CSV Writer
		$csv = Writer::createFromFileObject( new \SplTempFileObject() ); // Tworzenie tymczasowego pliku CSV

		// Dodanie nagłówków CSV
		$csv->insertOne( [ 'Nazwa produktu', 'Kategorie', 'SKU', 'Cena', 'Cena bez zniżki' ] );

		if ( $products->have_posts() ) {
			while ( $products->have_posts() ) {
				$products->the_post();
				$product = wc_get_product( get_the_ID() );

				// Pobieranie danych produktu
				$name          = $product->get_name();
				$categories    = wc_get_product_category_list( $product->get_id(), ', ' );
				$sku           = $product->get_sku();
				$price         = $product->get_price();
				$regular_price = $product->get_regular_price();

				// Dodanie wiersza z danymi produktu do CSV
				$csv->insertOne( [ $name, $categories, $sku, $price, $regular_price ] );
			}
		}

		wp_reset_postdata();

		// Wysyłanie CSV do przeglądarki
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename="woocommerce_products_export_' . date( 'Y-m-d' ) . '.csv"' );
		$csv->output();
		exit;
	}
}
