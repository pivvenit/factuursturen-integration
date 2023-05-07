<?php

namespace Pivvenit\FactuurSturen;

use InvalidArgumentException;

class Bootstrap
{

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->_init_logger();
		$this->_install();
		$this->_uninstall();
		$this->_load_modules();
		$this->_add_actions();
		$this->_add_filters();
		$this->_load_functions();
	}

	/**
	 * Initialize logger
	 */
	public function _init_logger()
	{
		$config = Config::get_config();
		Logger::handler(function ($msg) {
			if (is_string($msg)) {
				error_log($msg);
			} else {
				error_log(print_r($msg, true));
			}
		});
	}

	/**
	 * Initialize submodules
	 */
	public function _load_modules()
	{
		// Filter active modules based on their requirements
		// Have to be added before we get config as the filter applies on Config::get_config().
		add_filter('fsi_plugin_config', array(new \Pivvenit\FactuurSturen\FilterHook\FSIPluginConfig(), 'execute'));

		$config = Config::get_config();
		if (isset($config['modules']) and !empty($config['modules'])) {
			foreach ($config['modules'] as $name) {
				$config_file = FSI_PLUGIN_PATH . 'modules' . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . 'config.php';
				if (file_exists($config_file)) {
					$config = apply_filters('fsi_popup_module_config', (array)include $config_file, $name);
					Config::merge_config($config);
				} else {
					throw new \Exception(sprintf(__('Module "%1$s" could not be loaded: %2$s', 'factuursturen-integration'), $name, __('Config file not found', 'factuursturen-integration')));
				}
			}
		}
	}

	/**
	 * Register activation hooks
	 */
	protected function _install()
	{
		$config = Config::get_config();
		$install_classes = !empty($config['install']) ? $config['install'] : array();

		foreach ($install_classes as $install_class) {
			register_activation_hook(FSI_PLUGIN_FILE, array($install_class, 'execute'));
		}
	}

	/**
	 * Register de-activation hooks
	 */
	protected function _uninstall()
	{
		$config = Config::get_config();
		$uninstall_classes = !empty($config['uninstall']) ? $config['uninstall'] : array();

		foreach ($uninstall_classes as $uninstall_class) {
			register_activation_hook(FSI_PLUGIN_FILE, array($uninstall_class, 'execute'));
		}
	}

	/**
	 * Register bootstrap/global actions
	 */
	protected function _add_actions()
	{
		$config = Config::get_config();

		$actions = !empty($config['actions']) ? $config['actions'] : array();

		foreach ($actions as $action_name => $action_classes) {
			foreach ($action_classes as $action_class) {
				$priority = 10;
				$class = $action_class;

				if (is_array($action_class) && $action_class['class']) {
					$priority = isset($action_class['priority']) ? $action_class['priority'] : $priority;
					$class = $action_class['class'] ? $action_class['class'] : $action_class;
				}
				add_action($action_name, array(new $class, 'execute'), $priority, 10);
			}
		}
	}

	/**
	 * Register bootstrap/global filters
	 */
	protected function _add_filters()
	{
		$config = Config::get_config();

		$filters = !empty($config['filters']) ? $config['filters'] : array();

		foreach ($filters as $filterName => $filterClasses) {
			$i = 0;
			foreach ($filterClasses as $priority => $filterClass) {
				if ($i < 10 and $i == $priority) {
					$priority = 10;
				}
				if (class_exists($filterClass)) {
					add_filter($filterName, array(new $filterClass, 'execute'), 10, 10);
				} elseif (function_exists($filterClass)) {
					add_filter($filterName, $filterClass, 10, 10);
				} elseif (is_callable($filterClass)) {
					add_filter($filterName, $filterClass, 10, 10);
				} else {
					throw new InvalidArgumentException("Unknown filter class: $filterClass");
				}
				$i++;
			}
		}
	}

	/**
	 * Load custom functions
	 */
	protected function _load_functions()
	{
		require_once FSI_PLUGIN_PATH . 'functions.php';
	}

}
