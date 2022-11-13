<?php

use Pivvenit\FactuurSturen\Module\WoocommerceInvoices\ActionHook\WoocommercePaymentComplete;
use Pivvenit\FactuurSturen\Module\WoocommerceInvoices\InvoiceDownloadController;

if (! defined('ABSPATH')) {
    die('Access denied.');
}

return [
    'actions' => [
		'woocommerce_payment_complete' => [
			WoocommercePaymentComplete::class
		],
		'rest_api_init' => [
			InvoiceDownloadController::class
		]
	],
    'filters' => [
    // 'filter_name' => array(
    // '\\Pivvenit\FactuurSturen\\Module\\BasePopup\\Filter\\FilterName'
    // ),
	],
];
