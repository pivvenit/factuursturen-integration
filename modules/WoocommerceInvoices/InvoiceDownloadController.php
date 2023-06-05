<?php

namespace Pivvenit\FactuurSturen\Module\WoocommerceInvoices;

use Pivvenit\FactuurSturen\Module\WoocommerceInvoices\ActionHook\WoocommercePaymentComplete;
use Pivvenit\FactuurSturen\Module\WoocommerceInvoices\Util\FactuursturenHelperTrait;
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
			wp_die(esc_html__('You do not have permission to view this invoice.', 'fsi'));
		}
		// Check if order_id is set
		if (!isset($_GET['order_id']) || !is_numeric($_GET['order_id'])) {
			wp_die(esc_html__('Invalid request', 'fsi'));
		}
		// Get the order
		$order = wc_get_order($_GET['order_id']);
		if (!$order) {
			wp_die(esc_html__('Order not found.', 'fsi'));
		}
		// Get the invoice id
		$factuurSturenid = $order->get_meta('_fsi_wc_id', \true);
		if (!$factuurSturenid) {
			wp_die(esc_html__('Invoice Id does not exist.', 'fsi'));
		}
		// Get the invoice
		try {
			$invoice = self::getInvoiceUtil()->makeRequest("GET", "invoices_pdf/{$factuurSturenid}");
		} catch (\Exception $e) {
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
			wp_die(esc_html__('You do not have permission to view this invoice.', 'fsi'));
		}
		// Check if order_id is set
		if (!isset($_POST['order_id']) || !is_numeric($_POST['order_id'])) {
			wp_die(esc_html__('Invalid request', 'fsi'));
		}
		// Get the order
		$order = wc_get_order($_POST['order_id']);
		if (!$order) {
			wp_die(esc_html__('Order not found.', 'fsi'));
		}
		WoocommercePaymentComplete::execute($order->get_id());
		if ($order->get_meta('_fsi_wc_id', \true, 'fsi') == '') {
			// return an http 500 error
			wp_die(esc_html__('Invoice could not be created.', 'fsi'));
		}
		die();
	}

	public static function enqueueScripts() {
		wp_enqueue_script('fsi-invoice', plugins_url('../../assets/js/view_invoice.js', __FILE__), ['jquery'], '1.0.0', true);
	}
}
