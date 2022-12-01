<?php

namespace Pivvenit\FactuurSturen\Module\WoocommerceInvoices;

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
}
