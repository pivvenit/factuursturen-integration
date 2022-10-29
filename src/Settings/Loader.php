<?php

namespace Pivvenit\FactuurSturen\Settings;

use Pivvenit\FactuurSturen\Config;

class Loader
{

    private $_multilingual_section = array();

    /**
     * Register sections and fields based on config
     */
    public function __construct()
    {
        $config = Config::get_config();

        if (isset($config['settings'])) {
            $config_settings = apply_filters('fsi_settings', $config['settings']);

            foreach ($config_settings as $settings) {
                foreach ($settings['sections'] as $section) {
                    if (class_exists($section['callback'])) {
                        $section['instance'] = new $section['callback']();
                    } else {
                        throw new \RuntimeException(printf('Field class: %s not found!', $section['callback']));
                    }

                    $this->_register_section($section);
                    register_setting($section['menu_slug'], $section['id']);
                }

                foreach ($settings['fields'] as $field) {
                    if (class_exists($field['callback'])) {
                        $field['instance'] = new $field['callback']();
                    } else {
                        throw new \RuntimeException(printf('Field class: %s not found!', $field['callback']));
                    }

                    $this->_register_field($field);
                    $this->_handle_save($field);
                }
            }
        }
    }

    /**
     * Field callback mapping function
     */
    public function field_render($data)
    {
        $settings = get_option($data['section']);
        $field_name = $data['section'] . '[' . $data['id'] . ']';
        $field_id = $data['section'] . '_' . $data['id'];
        $value = isset($settings[$data['id']]) ? $settings[$data['id']] : ( isset($data['default_value']) ? $data['default_value'] : '' );

        call_user_func(array($data['instance'], 'render'), $field_name, $field_id, $value);
    }

    /**
     * Register settings section. add_settings_section function alias.
     *
     * @param array $data Section config array
     */
    protected function _register_section($data)
    {
        add_settings_section(
            $data['id'],
            $data['label'],
            array($data['instance'], 'render'),
            $data['menu_slug']
        );

        if (isset($data['multilingual']) && $data['multilingual']) {
            $this->_multilingual_section[] = $data['id'];
        }
    }

    /**
     * Register settings field. add_settings_field function alias.
     *
     * @param array $data Field config array
     */
    protected function _register_field($data)
    {
        add_settings_field(
            $data['id'],
            $data['label'],
            array($this, 'field_render'),
            $data['menu_slug'],
            $data['section'],
            $data
        );
    }

    /**
     * Triggers actions for field id
     *
     * @param array $data Field config array
     */
    protected function _handle_save($data)
    {
        $filter = filter_input(INPUT_POST, $data['id']);
        if (!empty($_POST) && !is_null($filter) && false !== $filter && method_exists($data['callback'], 'save')) {
            call_user_func(array($data['instance'], 'save'), $data);
        }
    }
}
