<?php

namespace Pivvenit\FactuurSturen\Module\WoocommerceInvoices\ActionHook;


use Pivvenit\FactuurSturen\Module\WoocommerceInvoices\Util\WoocommerceFactuursturen;
use Pivvenit\FactuurSturen\Module\WoocommerceInvoices\Util\FactuursturenHelperTrait;
use Pivvenit\FactuurSturen\Util\LogManager;

/**
 * Implements `woocommerce_payment_complete` action hook.
 */
class WoocommercePaymentComplete
{
	use FactuursturenHelperTrait;

	public static function execute($order_id)
	{
		$logger = LogManager::getLogger();
		if (!empty($_ENV['DISABLE_FACTUURSTUREN'])) {
			return;
		}
		$logger->info('WC_Order with ID: {order_id} marked as completed, starting sending the invoice process.', ['order_id' => $order_id]);

		// WC data
		$wcOrder = wc_get_order($order_id);

		if (!($wcOrder instanceof \WC_Order)) {
			$logger->error('Woocommerce order with ID {order_id} does not exist, invoice not sent', ['order_id' => $order_id]);
			return;
		}

		if ($wcOrder->get_meta('_fsi_wc_id', true, 'fsi') != '') {
			$logger->info('Woocommerce order with ID {order_id} has already been sent to Factuursturen', ['order_id' => $order_id, 'payment_method' => $wcOrder->get_payment_method(), 'existing_fsi_id' => $wcOrder->get_meta('_fsi_wc_id', true, 'fsi')]);
			return;
		}

		// Converted FS data
		$fsInvoice = WoocommerceFactuursturen::convertWcOrderToInvoice($wcOrder);
		if ($fsInvoice == null) {
			$wcOrder->add_order_note('Kon geen factuursturen factuur aanmaken.');
			$logger->error('WC_Order with ID: {order_id} could not be converted to FS_Invoice, exiting', ['order_id' => $order_id, 'payment_method' => $wcOrder->get_payment_method()]);
			return;
		}
		$logger->info('Factuursturen Invoice object created for order {order_id}', ['order_id' => $order_id, 'payment_method' => $wcOrder->get_payment_method()]);

		// Send invoice
		$response = self::getInvoiceUtil()->createInvoice($fsInvoice);
		if ($response == null) {
			$wcOrder->add_order_note('Kon geen factuursturen factuur aanmaken.');
			$logger->error('WC_Order with ID: {order_id} could not be sent to Factuursturen, exiting', ['order_id' => $order_id, 'payment_method' => $wcOrder->get_payment_method()]);
			return;
		}
		if ($response->getStatusCode() != 201) {
			$wcOrder->add_order_note(sprintf('Kon geen factuursturen factuur aanmaken. Status code: %d', $response->getStatusCode()));
			$logger->error('WC_Order with ID: {order_id} could not be sent to Factuursturen, exiting', ['order_id' => $order_id, 'body' => $response->getBody()->getContents(), 'payment_method' => $wcOrder->get_payment_method()]);
			return;
		}
		$wcOrder->update_meta_data('_fsi_sent_date', time());
		$wcOrder->update_meta_data('_fsi_wc_id', $fsInvoice->getId());
		$wcOrder->save_meta_data();
		$wcOrder->add_order_note(sprintf('Factuursturen factuur aangemaakt %2$s (code: %1$d)', $fsInvoice->getId(), $response->getStatusCode()));

		$logger->info('Invoice sent, response status code: {status_code}, invoice ID: {order_id}', ['status_code' => $response->getStatusCode(), 'order_id' => $order_id, 'payment_method' => $wcOrder->get_payment_method()]);
	}
}
