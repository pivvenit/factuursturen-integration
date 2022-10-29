<?php

namespace Pivvenit\FactuurSturen\FilterHook;

/**
 * @filter admin_menu
 */
class FSIPluginConfig
{

    public function execute(array $config)
    {
        if (!FSI_TESTS_RUNNING) {
            if (!is_plugin_active('pronamic-ideal/pronamic-ideal.php')) {
                if (($pronamic_key = array_search('PronamicInvoices', $config['modules'])) !== false) {
                    unset($config['modules'][$pronamic_key]);
                }

                if (($pronamic_settings_key = array_search('fsi_api_pronamic', array_column($config['settings']['general']['sections'], 'id'))) !== false) {
                    unset($config['settings']['general']['sections'][$pronamic_settings_key]);
                }
            }

            if (!is_plugin_active('woocommerce/woocommerce.php')) {
                if (($woocommerce_key = array_search('WoocommerceInvoices', $config['modules'])) !== false) {
                    unset($config['modules'][$woocommerce_key]);
                }

                if (($woocommerce_settings_key = array_search('fsi_api_woocommerce', array_column($config['settings']['general']['sections'], 'id'))) !== false) {
                    unset($config['settings']['general']['sections'][$woocommerce_settings_key]);
                }
            }
        }

        return $config;
    }

}
