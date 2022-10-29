<?php

namespace Pivvenit\FactuurSturen\Controller\Admin;

use Pivvenit\FactuurSturen\Controller\AbstractController;

class SettingsController extends AbstractController
{

    /**
     * API Settings page
     */
    public function apiSettings()
    {
        $this->render('admin/settings', array(
            'title' => __('Settings', 'factuursturen-integration'),
            'settings_key' => 'fsi_settings_api',
        ));
    }
}
