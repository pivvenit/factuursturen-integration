<?php

namespace Pivvenit\FactuurSturen\ActionHook;

use Pivvenit\FactuurSturen\Settings\Loader;

/**
 * Implements `admin_init` action hook.
 */
class AdminInit
{

    public function execute()
    {
        // Run settings loader
        $settingsLoader = new Loader();

//        $order = wc_get_order(43);
//        var_dump($order->get_shipping_tax() * 100);exit;
    }
}
