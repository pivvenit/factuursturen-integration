<?php

namespace Pivvenit\FactuurSturen\Module\WoocommerceInvoices\FilterHook;

class AppendInvoiceEmailFilterHook
{
	public function execute($fields)
	{
		$options = get_option('fsi_invoice_general');
		if (!array_key_exists('show-invoice-email', $options) || $options['show-invoice-email'] != 1) {
			return $fields;
		}
		$fields['billing']['billing_invoice_email'] = array(
			'label' => __('Factuur e-mailadres', 'woocommerce') ,
			'placeholder' => _x('Factuur e-mailadres', 'placeholder', 'woocommerce') ,
			'required' => false,
			'class' => array(
				'form-row-wide'
			) ,
			'clear' => true,
			'validate' => ['email']
		);
		return $fields;
	}

	public static function addInvoiceEmailFieldToBackendFields($fields)
	{
		$options = get_option('fsi_invoice_general');
		if (!array_key_exists('show-invoice-email', $options) || $options['show-invoice-email'] != 1) {
			return $fields;
		}
		$fields['invoice_email'] = [
			'label' => __('Factuur e-mailadres', 'woocommerce') ,
			'show' => true
		];
		return $fields;
	}
}
