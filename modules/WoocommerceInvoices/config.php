<?php

use Pivvenit\FactuurSturen\Module\WoocommerceInvoices\ActionHook\WoocommerceOrderAddMetaBoxesBankTransferBoxActionHook;
use Pivvenit\FactuurSturen\Module\WoocommerceInvoices\FilterHook\AppendInvoiceEmailFilterHook;
use Pivvenit\FactuurSturen\Module\WoocommerceInvoices\ActionHook\WoocommerceOnHoldBankTransferOrder;
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
		'woocommerce_order_status_on-hold' => [
			WoocommerceOnHoldBankTransferOrder::class
		],
		'rest_api_init' => [
			InvoiceDownloadController::class
		],
		'wp_ajax_fsi_view_invoice' => [InvoiceDownloadController::class . '::view_invoice'],
		'wp_ajax_fsi_create_invoice' => [InvoiceDownloadController::class . '::create_invoice'],
		'add_meta_boxes_shop_order' => [WoocommerceOrderAddMetaBoxesBankTransferBoxActionHook::class],
		'admin_enqueue_scripts' => [InvoiceDownloadController::class . '::enqueueScripts'],
	],
    'filters' => [
		'woocommerce_checkout_fields' => [
			AppendInvoiceEmailFilterHook::class
		],
		'woocommerce_admin_billing_fields' => [
			AppendInvoiceEmailFilterHook::class . '::addInvoiceEmailFieldToBackendFields'
		],
	],
];
