<?php

namespace Pivvenit\FactuurSturen\Module\WoocommerceInvoices\Util;

use Pivvenit\FactuurSturen\Util\FactuursturenClient;
use Pivvenit\FactuurSturen\Util\FactuursturenInvoice;

trait FactuursturenHelperTrait
{

    /**
     * Factuursturen invoice util for making requests.
     *
     * @var \Pivvenit\FactuurSturen\Util\FactuursturenInvoice
     */
    private static $invoiceUtil;

    /**
     * Factuursturen client util for making requests.
     *
     * @var \Pivvenit\FactuurSturen\Util\FactuursturenClient
     */
    private static $clientUtil;

    /**
     * Gets the invoice util.
     *
     * @return \Pivvenit\FactuurSturen\Util\FactuursturenInvoice
     */
    public static function getInvoiceUtil()
    {
        $called_class = get_called_class();

        if (empty(self::$invoiceUtil)) {
            self::$invoiceUtil = FactuursturenInvoice::createFromSettingsSection('fsi_api_woocommerce');
        }

        return self::$invoiceUtil;
    }

    /**
     * Gets the client util.
     *
     * @return \Pivvenit\FactuurSturen\Util\FactuursturenClient
     */
    public static function getClientUtil()
    {
        $called_class = get_called_class();

        if (empty(self::$clientUtil)) {
            self::$clientUtil = FactuursturenClient::createFromSettingsSection('fsi_api_woocommerce');
        }

        return self::$clientUtil;
    }

}
