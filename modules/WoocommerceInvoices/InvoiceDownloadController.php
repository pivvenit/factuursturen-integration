<?php

namespace Pivvenit\FactuurSturen\Module\WoocommerceInvoices;

use Pivvenit\FactuurSturen\Module\WoocommerceInvoices\ActionHook\WoocommercePaymentComplete;
use Pivvenit\FactuurSturen\Module\WoocommerceInvoices\Util\FactuursturenHelperTrait;
use Pivvenit\FactuurSturen\Module\WoocommerceInvoices\Util\WoocommerceFactuursturen;
use Pivvenit\FactuurSturen\Util\LogManager;
use WP_Error;
use WP_REST_Request;

class InvoiceDownloadController
{
	use FactuursturenHelperTrait;
	static function execute() {
		register_rest_route('factuursturen/v1', '/invoice/(?<invoice>[0-9]+)', [
			'methods' => 'GET',
			'callback' => [__CLASS__, 'download_invoice'],
			'permission_callback' => function () {
				if (!current_user_can('edit_others_shop_orders')) {
					return new WP_Error('rest_forbidden', esc_html__('Viewing an order API.', 'fsi'), ['status' => 401]);
				}
				return true;
			}
		]);
	}

	static function download_invoice(WP_REST_Request $request) {
		// Download the invoice from factuursturen
		$order = wc_get_order($request->get_param('invoice'));
		if (!$order) {
			return new WP_Error('rest_notfound', esc_html__('Order not found.', 'feestenboel'), array('status' => 404));
		}
		$factuurSturenid = $order->get_meta('_fsi_wc_id', true);
		if (!$factuurSturenid) {
			return new WP_Error('invoice_notfound', esc_html__('Invoice not found.', 'feestenboel'), array('status' => 404));
		}
		try {
			$invoice = self::getInvoiceUtil()->makeRequest("GET", "invoices_pdf/$factuurSturenid");
		} catch (\Exception $e) {
			return new WP_Error('invoice_notfound', esc_html__('Invoice not found.', 'feestenboel'), array('status' => 500));
		}
		return new \WP_REST_Response(base64_encode($invoice->getBody()->getContents()), 200, [
			'Content-Type' => 'text/base64'
		]);
	}

	public static function view_invoice() {
		// wp_ajax_fsi_view_invoice
		// Validate that the user has access
		if (!current_user_can('edit_others_shop_orders')) {
			LogManager::getLogger()->info('Invalid request, insufficient permissions.');

			wp_die(esc_html__('You do not have permission to view this invoice.', 'fsi'));
		}
		// Check if order_id is set
		if (!isset($_GET['order_id']) || !is_numeric($_GET['order_id'])) {
			LogManager::getLogger()->info('Invalid request, missing order_id.');
			wp_die(esc_html__('Invalid request', 'fsi'));
		}
		// Get the order
		$order = wc_get_order($_GET['order_id']);
		if (!$order) {
			LogManager::getLogger()->info('Invalid request, order with id {order_id} is not found.', ['order_id' => $order->get_id()]);

			wp_die(esc_html__('Order not found.', 'fsi'));
		}
		// Get the invoice id
		$factuurSturenid = $order->get_meta('_fsi_wc_id', \true);
		if (!$factuurSturenid) {
			LogManager::getLogger()->info('Invalid request, invoice Id {invoice_id} does not exist for order {order_id}.', ['order_id' => $order->get_id(), 'invoice_id' => $factuurSturenid]);
			wp_die(esc_html__('Invoice Id does not exist.', 'fsi'));
		}
		// Get the invoice
		try {
			$invoice = self::getInvoiceUtil()->makeRequest("GET", "invoices_pdf/{$factuurSturenid}");
		} catch (\Exception $e) {
			LogManager::getLogger()->error($e->getMessage());
			wp_die(esc_html__('Invoice does not exist.', 'fsi'));
		}
		// Output the invoice
		header('Content-type: application/pdf');
		header('Content-Disposition: inline; filename="'.$factuurSturenid.'.pdf"');
		echo $invoice->getBody()->getContents();

		die();
	}

	public static function create_invoice() {
		if (!current_user_can('edit_others_shop_orders')) {
			LogManager::getLogger()->info('Invalid request, unsufficient permissions.');
			wp_die(esc_html__('You do not have permission to view this invoice.', 'fsi'));
		}
		// Check if order_id is set
		if (!isset($_POST['order_id']) || !is_numeric($_POST['order_id'])) {
			LogManager::getLogger()->info('Invalid request, order_id not found.');
			wp_die(esc_html__('Invalid request', 'fsi'));
		}
		// Get the order
		$wcOrder = wc_get_order($_POST['order_id']);
		$order_id = $_POST['order_id'];
		if (!$wcOrder) {
			LogManager::getLogger()->info('Invalid request, order not found.');
			wp_die(esc_html__('Order not found.', 'fsi'));
		}

		$logger = LogManager::getLogger();
		if (!empty($_ENV['DISABLE_FACTUURSTUREN'])) {
			return;
		}
		// WC data

		if (!($wcOrder instanceof \WC_Order)) {
			$logger->error('Woocommerce order with ID {order_id} does not exist, invoice not sent', ['order_id' => $order_id, 'source' => 'manual']);
			return;
		}

		if ($wcOrder->get_meta('_fsi_wc_id', true, 'fsi') != '') {
			$logger->info('Woocommerce order with ID {order_id} has already been sent to Factuursturen', ['order_id' => $order_id, 'source' => 'manual', 'payment_method' => $wcOrder->get_payment_method(), 'existing_fsi_id' => $wcOrder->get_meta('_fsi_wc_id', true, 'fsi')]);
			return;
		}

		// Converted FS data
		$fsInvoice = WoocommerceFactuursturen::convertWcOrderToInvoice($wcOrder);
		if ($fsInvoice == null) {
			$wcOrder->add_order_note('Kon geen factuursturen factuur aanmaken.');
			$logger->error('WC_Order with ID: {order_id} could not be converted to FS_Invoice, exiting', ['order_id' => $order_id, 'source' => 'manual', 'payment_method' => $wcOrder->get_payment_method()]);
			return;
		}
		$logger->info('Factuursturen Invoice object created for order {order_id}', ['order_id' => $order_id, 'source' => 'manual', 'payment_method' => $wcOrder->get_payment_method()]);

		// Send invoice
		$response = self::getInvoiceUtil()->createInvoice($fsInvoice);
		if ($response == null) {
			$wcOrder->add_order_note('Kon geen factuursturen factuur aanmaken.');
			$logger->error('WC_Order with ID: {order_id} could not be sent to Factuursturen, exiting', ['order_id' => $order_id, 'source' => 'manual', 'payment_method' => $wcOrder->get_payment_method()]);
			return;
		}
		if ($response->getStatusCode() != 201) {
			$wcOrder->add_order_note(sprintf('Kon geen factuursturen factuur aanmaken. Status code: %d', $response->getStatusCode()));
			$logger->error('WC_Order with ID: {order_id} could not be sent to Factuursturen, exiting', ['order_id' => $order_id, 'source' => 'manual', 'body' => $response->getBody()->getContents(), 'payment_method' => $wcOrder->get_payment_method()]);
			return;
		}
		$wcOrder->update_meta_data('_fsi_sent_date', time());
		$wcOrder->update_meta_data('_fsi_wc_id', $fsInvoice->getId());
		$wcOrder->save_meta_data();
		$wcOrder->add_order_note(sprintf('Factuursturen factuur aangemaakt %2$s (code: %1$d)', $fsInvoice->getId(), $response->getStatusCode()));

		$logger->info('Invoice sent, response status code: {status_code}, invoice ID: {order_id}', ['status_code' => $response->getStatusCode(), 'source' => 'manual', 'order_id' => $order_id, 'payment_method' => $wcOrder->get_payment_method()]);

		die();
	}

	public static function enqueueScripts() {
		wp_enqueue_script('fsi-invoice', plugins_url('../../assets/js/view_invoice.js', __FILE__), ['jquery'], '1.0.0', true);
	}
}
