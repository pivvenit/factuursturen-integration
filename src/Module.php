<?php

namespace Pivvenit\FactuurSturen;

abstract class Module
{

    private static $_instances = array();

    /**
     * Called class absolute path
     *
     * @var string
     */
    protected $module_path;

    /**
     * Called class URL
     *
     * @var string
     */
    protected $module_url;

    /**
     * Bootstrap class
     *
     * @var LTC_Routes\Bootstrap
     */
    protected $bootstrap;

    /**
     * Make sure construct is defined in the subclasses
     */
    public function __construct()
    {
        $this->module_path = dirname($this->get_absolute_file()) . DIRECTORY_SEPARATOR;
        $this->module_url  = plugin_dir_url($this->get_absolute_file());
    }

    /**
     * Cloning is forbidden.
     */
    public function __clone()
    {
        wc_doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'factuursturen-integration'), '1.0.0');
    }

    /**
     * Unserializing instances of this class is forbidden.
     */
    public function __wakeup()
    {
        wc_doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'factuursturen-integration'), '1.0.0');
    }

    /**
     * Main Module Instance.
     *
     * @return object class instance.
     */
    public static function instance()
    {
        $class = get_called_class();
        if (! isset(self::$_instances[ $class ])) {
            self::$_instances[ $class ] = new $class();
        }
        return self::$_instances[ $class ];
    }

    /**
     * Get __FILE__ of called class
     *
     * @return string
     */
    public function get_absolute_file()
    {
        $reflector = new \ReflectionObject($this);
        return $reflector->getFilename();
    }
}
