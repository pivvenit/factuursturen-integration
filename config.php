<?php

if (!defined('ABSPATH')) {
    die('Access denied.');
}

return array(
    // Config
    'factuursturen_endpoint' => 'https://www.factuursturen.nl/api/v1/',
    'log_path' => sys_get_temp_dir(),
    'assets_uri' => FSI_PLUGIN_URL . 'assets/',
    'view_path' => FSI_PLUGIN_PATH . '/templates/',
    // Hooks
    'install' => array(
        10 => '\\Pivvenit\FactuurSturen\\Activation',
    ),
    'uninstall' => array(
        10 => '\\Pivvenit\FactuurSturen\\Deactivation',
    ),
    'modules' => array(
        // see
        'WoocommerceInvoices'
    ),
    'actions' => array(
        'admin_init' => array(
            '\\Pivvenit\FactuurSturen\\ActionHook\\AdminInit',
        ),
        'admin_menu' => array(
            '\\Pivvenit\FactuurSturen\\ActionHook\\AdminMenu',
        ),
    ),
    'filters' => array(
        //
    ),
    'settings' => array(
        /**
         * Admin/Settings
         */
        'general' => array(
            'sections' => array(
                array(
                    'id' => 'fsi_api_pronamic',
                    'label' => __('Factuursturen API Settings for Pronamic', 'factuursturen-integration'),
                    'callback' => '\\Pivvenit\FactuurSturen\\Settings\\Section\\FSApi',
                    'menu_slug' => 'fsi_settings_api',
                ),
                array(
                    'id' => 'fsi_api_woocommerce',
                    'label' => __('Factuursturen API Settings for Woocommerce', 'factuursturen-integration'),
                    'callback' => '\\Pivvenit\FactuurSturen\\Settings\\Section\\FSApi',
                    'menu_slug' => 'fsi_settings_api',
                ),
                array(
                    'id' => 'fsi_invoice_general',
                    'label' => __('Invoice Settings', 'factuursturen-integration'),
                    'callback' => '\\Pivvenit\FactuurSturen\\Settings\\Section\\FSInvoice',
                    'menu_slug' => 'fsi_settings_api',
                ),
            ),
            'fields' => array(
                /*
                 * Factuursturen API Settings for Pronamic
                 */
                array(
                    'id' => 'fs-user',
                    'label' => __('Username', 'factuursturen-integration'),
                    'callback' => '\\Pivvenit\FactuurSturen\\Settings\\Field\\FSUser',
                    'menu_slug' => 'fsi_settings_api',
                    'section' => 'fsi_api_pronamic',
                ),
                array(
                    'id' => 'fs-api-key',
                    'label' => __('API Key', 'factuursturen-integration'),
                    'callback' => '\\Pivvenit\FactuurSturen\\Settings\\Field\\FSApiKey',
                    'menu_slug' => 'fsi_settings_api',
                    'section' => 'fsi_api_pronamic',
                ),
                /*
                 * Factuursturen API Settings for Woocommerce
                 */
                array(
                    'id' => 'fs-user',
                    'label' => __('Username', 'factuursturen-integration'),
                    'callback' => '\\Pivvenit\FactuurSturen\\Settings\\Field\\FSUser',
                    'menu_slug' => 'fsi_settings_api',
                    'section' => 'fsi_api_woocommerce',
                ),
                array(
                    'id' => 'fs-api-key',
                    'label' => __('API Key', 'factuursturen-integration'),
                    'callback' => '\\Pivvenit\FactuurSturen\\Settings\\Field\\FSApiKey',
                    'menu_slug' => 'fsi_settings_api',
                    'section' => 'fsi_api_woocommerce',
                ),
				array(
                    'id' => 'tax-field',
                    'label' => __('Tax number field', 'factuursturen-integration'),
                    'callback' => '\\Pivvenit\FactuurSturen\\Settings\\Field\\FSTaxNumber',
                    'menu_slug' => 'fsi_settings_api',
                    'section' => 'fsi_api_woocommerce',
                ),
				array(
                    'id' => 'show-woo-order',
                    'label' => __('Show Woocommerce order number on invoice', 'factuursturen-integration'),
                    'callback' => '\\Pivvenit\FactuurSturen\\Settings\\Field\\FSWooNumber',
                    'menu_slug' => 'fsi_settings_api',
                    'section' => 'fsi_api_woocommerce',
                ),
                /*
                 * General Invoice Settings
                 */
                array(
                    'id' => 'default-tax-rate',
                    'label' => __('Default Tax Rate', 'factuursturen-integration'),
                    'callback' => '\\Pivvenit\FactuurSturen\\Settings\\Field\\FSDefaultTaxRate',
                    'menu_slug' => 'fsi_settings_api',
                    'section' => 'fsi_invoice_general',
                ),
                array(
                    'id' => 'default-doclang',
                    'label' => __('Default Invoice Language', 'factuursturen-integration'),
                    'callback' => '\\Pivvenit\FactuurSturen\\Settings\\Field\\FSDefaultDoclang',
                    'menu_slug' => 'fsi_settings_api',
                    'section' => 'fsi_invoice_general',
                ),
                array(
                    'id' => 'convert-to-euro',
                    'label' => __('Convert Prices to EURO', 'factuursturen-integration'),
                    'callback' => '\\Pivvenit\FactuurSturen\\Settings\\Field\\FSPriceToEuro',
                    'menu_slug' => 'fsi_settings_api',
                    'section' => 'fsi_invoice_general',
                ),
                array(
                    'id' => 'mailintro',
                    'label' => __('Mail intro', 'factuursturen-integration'),
                    'callback' => '\\Pivvenit\FactuurSturen\\Settings\\Field\\FSMailIntro',
                    'menu_slug' => 'fsi_settings_api',
                    'section' => 'fsi_invoice_general',
                ),
            ),
        ),
    ),
);
