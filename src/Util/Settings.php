<?php

namespace Pivvenit\FactuurSturen\Util;

use Pronamic\WordPress\Pay\Payments\Payment;
use Pivvenit\FactuurSturen\Util\Factuursturen;

class Settings
{
    const FIELD_FS_USER = 'fs-user';
    const FIELD_FS_API_KEY = 'fs-api-key';

    /**
     * Get setting value
     *
     * @param string $key
     * @param string $section
     * @param mixed  $default
     * @return mixed
     */
    public static function get_option($key, $section = 'fsi_api', $default = null)
    {
        $section_data = get_option($section, array());
        return isset($section_data[$key]) ? $section_data[$key] : $default;
    }

    /**
     * Format date based on WordPress settings
     *
     * @param string $date date in any format except timestamp
     * @return string
     */
    public static function format_date($date)
    {
        $date_format = get_option('date_format', 'Y-m-d');
        $time_format = get_option('time_format', 'H:i:s');

        return date($date_format . ' ' . $time_format, strtotime($date));
    }

    /**
     * Get user 2-letter long locale
     *
     * @param string $user_id
     * @return string
     */
    public static function get_user_doclang($user_id = null)
    {
        $user_lang = substr(get_user_locale($user_id), 0, 2);
        return ($user_id && in_array($user_lang, Factuursturen::getDoclangs())) ? $user_lang : Settings::get_option('default-doclang', 'fsi_invoice', 'nl');
    }
}
