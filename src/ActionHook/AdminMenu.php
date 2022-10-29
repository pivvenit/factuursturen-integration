<?php

namespace Pivvenit\FactuurSturen\ActionHook;

use Pivvenit\FactuurSturen\Controller\Admin\SettingsController;

/**
 * @action admin_menu
 */
class AdminMenu
{

    public function execute()
    {
        /*
         * Settings Page
         */
        $controller = new SettingsController();

        add_options_page(
            __('Factuursturen - API Settings', 'tmd-promotion-popup'),
            __('Factuursturen API', 'tmd-promotion-popup'),
            'manage_options',
            'fsi_settings_api',
            array( $controller, 'apiSettings' )
        );
    }
}
