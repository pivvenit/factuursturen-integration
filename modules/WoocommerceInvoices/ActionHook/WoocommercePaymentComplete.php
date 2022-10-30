<?php

namespace Pivvenit\FactuurSturen\Module\WoocommerceInvoices\ActionHook;


use Pivvenit\FactuurSturen\Module\WoocommerceInvoices\Util\WoocommerceFactuursturen;
use Pivvenit\FactuurSturen\Module\WoocommerceInvoices\Util\FactuursturenHelperTrait;
use Analog\Analog;

/**
 * Implements `woocommerce_payment_complete` action hook.
 */
class WoocommercePaymentComplete
{
    use FactuursturenHelperTrait;

    public static function execute($order_id)
    {
		if (!empty($_ENV['DISABLE_FACTUURSTUREN'])) {
			return;
		}
        Analog::notice(sprintf('WC_Order with ID: %d marked as completed, starting sending the invoice process...', $order_id));

        // WC data
        $wcOrder = wc_get_order($order_id);

        if($wcOrder instanceof \WC_Order && '' == $wcOrder->get_meta('_fsi_wc_id', true, 'fsi')) {
            // Converted FS data
            $fsInvoice = WoocommerceFactuursturen::convertWcOrderToInvoice($wcOrder);
			if ($fsInvoice == null) {
				Analog::error(sprintf('WC_Order with ID: %d could not be converted to FS_Invoice', $order_id));
				return;
			}
            Analog::notice(sprintf('Invoice prepared for order: %1$d, data: %2$s', $wcOrder->get_id(), json_encode($fsInvoice)));

            // Send invoice
            $response = self::getInvoiceUtil()->createInvoice($fsInvoice);
            $wcOrder->update_meta_data('_fsi_sent_date', time());
            $wcOrder->update_meta_data('_fsi_wc_id', $fsInvoice->getId());
            $wcOrder->save_meta_data();

            Analog::notice(sprintf('Invoice sent, response status code: %1$d, invoice ID: %2$s', $fsInvoice->getId(), $response->getStatusCode()));
        } else {
            Analog::error(sprintf('Woocommerce order with ID %d does not exist, invoice not sent!', $order_id));
        }
    }
}
