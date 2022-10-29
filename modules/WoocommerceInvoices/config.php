<?php

use Pivvenit\FactuurSturen\Module\WoocommerceInvoices\ActionHook\WoocommercePaymentComplete;

if (! defined('ABSPATH')) {
    die('Access denied.');
}

return [
    'actions' => [
		'woocommerce_payment_complete' => [
			WoocommercePaymentComplete::class
		]
	],
    'filters' => [
    // 'filter_name' => array(
    // '\\Pivvenit\FactuurSturen\\Module\\BasePopup\\Filter\\FilterName'
    // ),
	],
];
