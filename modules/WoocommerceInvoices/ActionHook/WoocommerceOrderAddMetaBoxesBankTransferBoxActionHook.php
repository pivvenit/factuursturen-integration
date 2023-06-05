<?php

namespace Pivvenit\FactuurSturen\Module\WoocommerceInvoices\ActionHook;


use Pivvenit\FactuurSturen\Module\WoocommerceInvoices\Util\FactuursturenHelperTrait;
use Pivvenit\FactuurSturen\View\TemplateRenderTrait;

/**
 * Implements the 'add_meta_boxes' action hook to add the bank transfer box to the order edit page.
 */
class WoocommerceOrderAddMetaBoxesBankTransferBoxActionHook
{
    use FactuursturenHelperTrait, TemplateRenderTrait;

    /**
     * Adds the bank transfer box to the order edit page.
     */
    public function execute() {
        add_meta_box(
            'woocommerce-order-bank-transfer',
            __('Factuursturen', 'woocommerce-factuursturen'),
            [$this, 'renderBankTransferBox'],
            'shop_order',
            'side',
            'high'
        );
    }

    /**
     * Renders the bank transfer box.
     */
    public function renderBankTransferBox($order) {
        // Render the template
        $this->renderTemplate('admin/metabox', [
            'post' => $order
        ]);
    }
}
