<?php

/**
 * Plugin Name:     WooCommerce factuursturen
 * Description:     Sending invoices after payment through Factuursturen.nl service. Compatible with Woocommerce. Forked from https://wordpress.org/plugins/factuursturen-integration/
 * Author:          PivvenIT
 * Author URI:      https://pivvenit.nl/
 * Text Domain:     woocommerce-factuursturen
 * Domain Path:     /languages
 * Version:         dev-master
 *
 * @package         PivvenIT\FactuurSturen
 */

use Pivvenit\FactuurSturen\Bootstrap;

if (!defined('ABSPATH')) {
    die('Access denied.');
}

require_once __DIR__."/updater.php";
$updater = new PivvenitOpenSourceUpdater(__FILE__);
$updater->set_username('pivvenit');
$updater->set_repository('factuursturen-integration');
$updater->initialize();

if (!defined('FSI_TESTS_RUNNING')) {
    define('FSI_TESTS_RUNNING', false);
}

define('FSI_NAME', 'WooCommerce Factuursturen');
define('FSI_VERSION', '1');
define('FSI_REQUIRED_PHP_VERSION', '5.6');
define('FSI_REQUIRED_WP_VERSION', '4.9');
define('FSI_PLUGIN_FILE', __FILE__);
define('FSI_PLUGIN_URL', plugin_dir_url(__FILE__));
define('FSI_PLUGIN_PATH', plugin_dir_path(__FILE__));

/**
 * Checks if the system requirements are met
 *
 * @return bool True if system requirements are met, false if not
 */
function fsi_requirements_met()
{
    global $wp_version;
    require_once(ABSPATH . '/wp-admin/includes/plugin.php');  // to get is_plugin_active() early

    if (version_compare(PHP_VERSION, FSI_REQUIRED_PHP_VERSION, '<')) {
        return false;
    }

    if (version_compare($wp_version, FSI_REQUIRED_WP_VERSION, '<')) {
        return false;
    }

    return true;
}

/**
 * Prints an error that the system requirements weren't met.
 */
function fsi_requirements_error()
{
    require_once(dirname(__FILE__) . '/views/requirements-error.php');
}

/*
 * Check requirements and load main class
 * The main program needs to be in a separate file that only gets loaded if the plugin requirements are met. Otherwise older PHP installations could crash when trying to parse it.
 */
if (fsi_requirements_met()) {
    require_once FSI_PLUGIN_PATH . 'vendor/autoload.php';
    $bootstrap = new Bootstrap();
} else {
    add_action('admin_notices', 'fsi_requirements_error');
}
