<?php

namespace Pivvenit\FactuurSturen;

static $_fsi_popup_config;

final class Config
{

    /**
     * The config data array.
     *
     * @var array
     */
    protected $data;

    /**
     * Class constructor.
     */
    private function __construct()
    {
        $this->set_config((array) include FSI_PLUGIN_PATH . 'config.php');
    }

    /**
     * Override config
     *
     * @param array $config
     */
    public function set_config($config)
    {
        $this->data = $config;
    }

    /**
     * Get config data array.
     *
     * @return array
     * @static
     */
    public static function get_config()
    {
        $instance = self::get_instance();
        return apply_filters( 'fsi_plugin_config', $instance->data );
    }

    /**
     * Get class util instance.
     *
     * @return $this
     * @static
     */
    public static function get_instance()
    {
        static $instance;

        $called_class = get_called_class();

        if (empty($instance)) {
            $instance = new $called_class();
        }

        return $instance;
    }

    /**
     * Merge config array into plugin config
     *
     * @static
     */
    public static function merge_config($config)
    {
        $current_config = self::get_config();

        foreach ($config as $section => $section_data) {
            switch ($section) {
                default:
                    if (! isset($current_config[ $section ])) {
                        $current_config[ $section ] = array();
                    }

                    $current_config[ $section ] = array_merge_recursive($current_config[ $section ], $section_data);
                    break;
            }
        }

        $config_instance = self::get_instance();
        $config_instance->set_config($current_config);
    }
}
