<?php

namespace Pivvenit\FactuurSturen\Module\WoocommerceInvoices\ActionHook;


use Pivvenit\FactuurSturen\Module\WoocommerceInvoices\Util\WoocommerceFactuursturen;
use Pivvenit\FactuurSturen\Module\WoocommerceInvoices\Util\FactuursturenHelperTrait;
use Pivvenit\FactuurSturen\Util\LogManager;

/**
 * Implements `woocommerce_order_status_on-hold` action hook. Only performs actual work for 'bacs' (bank transfer) orders to create an invoice for the user.
 */
class WoocommerceOnHoldBankTransferOrder
{
	use FactuursturenHelperTrait;

	public static function execute($order_id)
	{
		$logger = LogManager::getLogger();
		if (!empty($_ENV['DISABLE_FACTUURSTUREN'])) {
			$logger->notice('Factuursturen integration is disabled, exiting');
			return;
		}
		$logger->info('WC_Order with ID: {order_id} marked as on-hold, starting sending the invoice process.', ['order_id' => $order_id]);
		// WC data
		$wcOrder = wc_get_order($order_id);

		if (!($wcOrder instanceof \WC_Order)) {
			$logger->error('Woocommerce order with ID {order_id} does not exist, invoice not sent', ['order_id' => $order_id]);
			return;
		}

		if ($wcOrder->get_payment_method() != 'bacs') {
			$logger->info('Woocommerce order with ID {order_id} is not a bank transfer order but {payment_method}, exiting', ['order_id' => $order_id, 'payment_method' => $wcOrder->get_payment_method()]);
			return;
		}

		if ($wcOrder->get_meta('_fsi_wc_id', true, 'fsi') == '') {
			$logger->info('Woocommerce order with ID {order_id} has already been sent to Factuursturen', ['order_id' => $order_id, 'payment_method' => $wcOrder->get_payment_method()]);
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
		$wcOrder->update_meta_data('_fsi_sent_date', time());
		$wcOrder->update_meta_data('_fsi_wc_id', $fsInvoice->getId());
		$wcOrder->save_meta_data();
		$wcOrder->add_order_note(sprintf('Factuursturen factuur aangemaakt %1$s (statusCode: %2$d)', $fsInvoice->getId(), $response->getStatusCode()));

		$logger->info('Invoice sent, response status code: {status_code}, invoice ID: {order_id}', ['status_code' => $response->getStatusCode(), 'order_id' => $order_id, 'payment_method' => $wcOrder->get_payment_method()]);
	}
}
