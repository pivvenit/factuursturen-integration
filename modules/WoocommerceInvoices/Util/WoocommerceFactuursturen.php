<?php

namespace Pivvenit\FactuurSturen\Module\WoocommerceInvoices\Util;

use Pivvenit\FactuurSturen\Util\LogManager;
use WC_Customer;
use WC_Order;
use WC_Coupon;
use Pivvenit\FactuurSturen\RequestData\Client;
use Pivvenit\FactuurSturen\RequestData\ClientCollectTypeEnum;
use Pivvenit\FactuurSturen\RequestData\ClientPaymentMethodEnum;
use Pivvenit\FactuurSturen\RequestData\ClientTaxTypeEnum;
use Pivvenit\FactuurSturen\RequestData\InvoiceActionEnum;
use Pivvenit\FactuurSturen\RequestData\InvoiceLine;
use Pivvenit\FactuurSturen\RequestData\InvoiceSendMethodEnum;
use Pivvenit\FactuurSturen\RequestData\Invoice;
use Pivvenit\FactuurSturen\Module\WoocommerceInvoices\Util\FactuursturenHelperTrait;
use Pivvenit\FactuurSturen\Util\Settings;
use Pivvenit\FactuurSturen\Util\ValueExtractor;

class WoocommerceFactuursturen
{
	use FactuursturenHelperTrait;

	/**
	 * Converts \WC_Customer and \WC_Order to \Pivvenit\FactuurSturen\RequestData\Client.
	 *
	 * @param \WC_Customer $customer
	 * @param \WC_Order $order
	 * @return \Pivvenit\FactuurSturen\RequestData\Client
	 */
	public static function convertWcCustomerToClient(\WC_Customer $customer, \WC_Order $order)
	{
		$wc = \WooCommerce::instance();
		$wc_countries = $wc->countries->get_countries();
		$options = get_option('fsi_api_woocommerce');
		if ($options['tax-field'] != "") {
			$taxnumber = get_post_meta($order->get_id('fsi'), $options['tax-field'], true);
		}

		$invoiceOptions = get_option('fsi_invoice_general');
		if (array_key_exists('show-invoice-email', $invoiceOptions) && $invoiceOptions['show-invoice-email'] == 1) {
			$email = get_post_meta($order->get_id('fsi'), '_billing_invoice_email', true);
		}
		if (empty($email)) {
			$email = $customer->get_email('fsi');
		}

		$client = new Client();
		$client
			->setActive(true)
			->setAddress($customer->get_billing_address('fsi') . "\n" . $customer->get_billing_address_2('fsi'))
			->setCity($customer->get_billing_city('fsi'))
			->setCompany($customer->get_billing_company('fsi'))
			->setContact($customer->get_display_name('fsi'))
			->setCountry(ValueExtractor::getValueWithDefault($wc_countries, $customer->get_billing_country('fsi'), ''))
			->setCurrency($order->get_currency('fsi'))
			->setDefaultDoclang(Settings::get_user_doclang($order->get_customer_id())) // empty to default language (In what language the invoice will be generated for this client.)
			->setEmail($email)
			->setMailintro(Settings::get_option('mailintro', 'fsi_invoice', '')) // The first line used in the e-mail to address the recipient.
			->setPhone($customer->get_billing_phone('fsi'))
			->setNotes($order->get_customer_note('fsi'))
			->setPaymentmethod(ClientPaymentMethodEnum::METHOD_AUTOCOLLECT())
			->setSendmethod(InvoiceSendMethodEnum::METHOD_EMAIL())
			->setShowcontact(true)
			->setTaxShifted(false)
			->setTaxType(ClientTaxTypeEnum::TYPE_EXTAX())
			->setTaxnumber($taxnumber)
			->setZipcode($customer->get_billing_postcode('fsi'));

		return $client;
	}

	/**
	 * Converts \WC_Order to \Pivvenit\FactuurSturen\RequestData\Client.
	 *
	 * @param \WC_Order $order
	 * @return \Pivvenit\FactuurSturen\RequestData\Client
	 */
	public static function convertWcOrderToClient(\WC_Order $order)
	{
		$wc = \WooCommerce::instance();
		$wc_countries = $wc->countries->get_countries();
		$options = get_option('fsi_api_woocommerce');
		if ($options['tax-field'] != "") {
			$taxnumber = get_post_meta($order->get_id('fsi'), $options['tax-field'], true);
		}

		$invoiceOptions = get_option('fsi_invoice_general');
		if (array_key_exists('show-invoice-email', $invoiceOptions) && $invoiceOptions['show-invoice-email'] == 1) {
			$email = get_post_meta($order->get_id('fsi'), '_billing_invoice_email', true);
		}
		if (empty($email)) {
			$email = $order->get_billing_email('fsi');
		}

		$client = new Client();
		$client
			->setActive(true)
			->setAddress($order->get_billing_address_1('fsi') . "\n" . $order->get_billing_address_2('fsi'))
			->setCity($order->get_billing_city('fsi'))
			->setCompany($order->get_billing_company('fsi'))
			->setContact($order->get_billing_first_name('fsi') . ' ' . $order->get_billing_last_name('fsi'))
			->setCountry(ValueExtractor::getValueWithDefault($wc_countries, $order->get_billing_country('fsi'), ''))
			->setCurrency($order->get_currency('fsi'))
			->setDefaultDoclang(Settings::get_user_doclang($order->get_customer_id())) // empty to default language (In what language the invoice will be generated for this client.)
			->setEmail($email)
			->setMailintro(Settings::get_option('mailintro', 'fsi_invoice', '')) // The first line used in the e-mail to address the recipient.
			->setPhone($order->get_billing_phone('fsi'))
			->setNotes($order->get_customer_note('fsi'))
			->setPaymentmethod(ClientPaymentMethodEnum::METHOD_AUTOCOLLECT())
			->setSendmethod(InvoiceSendMethodEnum::METHOD_EMAIL())
			->setShowcontact(true)
			->setTaxShifted(false)
			->setTaxType(ClientTaxTypeEnum::TYPE_EXTAX())
			->setTaxnumber($taxnumber)
			->setZipcode($order->get_billing_postcode('fsi'))
			->setCollecttype(ClientCollectTypeEnum::TYPE_DD_SINGLE());

		return $client;
	}

	/**
	 * Converts the \WC_Order instance to \Pivvenit\FactuurSturen\RequestData\InvoiceInterface
	 *
	 * @param \WC_Order $order
	 * @return \Pivvenit\FactuurSturen\RequestData\InvoiceInterface
	 */
	public static function convertWcOrderToInvoice(WC_Order $order)
	{
		$convert_to_euro = Settings::get_option('convert-to-euro', 'fsi_invoice', 0) == 1;

		if ($order->get_payment_method() == 'cod' && $order->has_status(['processing', 'on-hold', 'pending', 'ready-not-paid'])) {
			// A cash on delivery order is marked as paid way too early, so we need to check the status
			// to see if it's actually paid or not.
			return null;
		}

		// Prepare invoice instance
		$invoice = new Invoice();

		$invoice
			->setLineItems(self::getWcOrderToLineItems($order))
			->setAction(InvoiceActionEnum::ACTION_SEND())
			->setSendMethod(InvoiceSendMethodEnum::METHOD_EMAIL())
			->setConvertPricesToEuro($convert_to_euro);

		if ($order->get_payment_method() != 'bacs') {
			$invoice
				->setAlreadyPaid('full')
				->setAlreadyPaidMethod($order->get_payment_method_title());
		}

		// Get or create client instance and assign it to the invoice
		if (($user_id = $order->get_user_id('fsi'))) {
			$fs_client_id = get_user_meta($user_id, '_fsi_clientnr', true);

			if (!$fs_client_id) {
				try {
					$customer = new WC_Customer($order->get_user_id('fsi'));
					$client = self::convertWcCustomerToClient($customer, $order);
				} catch (\Exception $e) {
					LogManager::getLogger()->error('Could not retrieve customer with ID {customer_id}', ['customer_id' => $order->get_user_id('fsi'), 'exception' => $e]);
					$client = self::convertWcOrderToClient($order);
				}
			} else {
				self::getClientUtil()->fetchClient($fs_client_id, $client);
			}
		} else {
			LogManager::getLogger()->info('Could not retrieve customer for order: {order_id}, using order data to create new client', ['order_id' => $order->get_id()]);
			$client = self::convertWcOrderToClient($order);
		}


		if (!isset($fs_client_id) || empty($fs_client_id)) {
			// Create client in factuursturen service
			self::getClientUtil()->createClient($client);
		}

		// Save the clientnr to use it for future invoices
		update_user_meta($user_id, '_fsi_clientnr', $client->getId());

		$invoice->setClient($client);

		return $invoice;
	}

	/**
	 * Get line items for invoice.
	 *
	 * @param \WC_Order $order
	 * @return InvoiceLine[]
	 */
	public static function getWcOrderToLineItems(\WC_Order $order)
	{
		$output = array();

		// Get tax
		$tax_rate = 0;
		$taxes = $order->get_taxes();
		if (!empty($taxes)) {
			$tax_id = reset($taxes)->get_rate_id();
			$tax = \WC_Tax::_get_tax_rate($tax_id);
			$tax_rate = intval($tax['tax_rate']);
		} else {
			$tax_rate = Settings::get_option('default-tax-rate', 'fsi_invoice', 0);
		}

		// Add all items from order as separate invoice line items.
		foreach ($order->get_items() as $item) {
			/* @var $item \WC_Order_Item */
			/* @var $item \WC_Order_Item_Product */
			$product = $item->get_product();
			$new_line = new InvoiceLine();
			$priceData = $item->get_meta('rnb_price_breakdown', true);
			$price = $product->is_on_sale('fsi') ? $product->get_sale_price('fsi') : $product->get_price('fsi');
			if (!empty($priceData) && is_array($priceData) && array_key_exists('total', $priceData)) {
				$price = (float)$priceData['total'];
			}
			$new_line
				->setAmount($item->get_quantity('fsi'))
				->setDescription($item->get_name('fsi'))
				->setDiscountPct(0) // not sure where to get it from
				->setPrice($price)
				->setTaxRate($item->get_tax_status() == 'taxable' ? $tax_rate : 0);

			$output[] = $new_line;
		}

		// Add coupon discount.
		foreach ($order->get_coupon_codes() as $coupon_code) {
			$coupon = new WC_Coupon($coupon_code);
			$new_line = new InvoiceLine();
			$new_line
				->setAmount(1)
				->setDescription('Korting')
				->setDiscountPct(0) // not sure where to get it from
				->setPrice(-($coupon->get_amount()))
				->setTaxRate($tax_rate);

			$output[] = $new_line;
		}

		// Add shipping as line item (there is no separate options for shipping for invoices).
		if ($order->get_shipping_total('fsi') > 0) {
			$shipping_methods = $order->get_shipping_methods();
			foreach ($shipping_methods as $shipping_method) {
				$shipping_line = new InvoiceLine();
				$total = $shipping_method->get_total('fsi');
				$total_tax = $shipping_method->get_total_tax('fsi');
				$tax_rate = 0;
				if ($total != 0) {
					$tax_rate = round(($total_tax / $total) * 100);
				}

				$shipping_line
					->setAmount($shipping_method->get_quantity() ?? 1)
					->setDiscountPct(0) // not sure where to get it from
					->setDescription($shipping_method->get_name('fsi'))
					->setPrice($total)
					->setTaxRate($tax_rate);

				$output[] = $shipping_line;
			}
		}

		$options = get_option('fsi_api_woocommerce');
		if ($options['show-woo-order'] == 1) {
			$invoicenote = '#' . $order->get_id('fsi');
			$new_line = new InvoiceLine();
			$new_line
				->setAmount(0)
				->setDescription('#' . $order->get_id('fsi'))
				->setDiscountPct(0) // not sure where to get it from
				->setPrice(0)
				->setTaxRate(0);

			$output[] = $new_line;
		}

		return $output;
	}

}
