<?php

namespace Pivvenit\FactuurSturen\Controller;

use Pivvenit\FactuurSturen\View\TemplateRenderTrait;

abstract class AbstractController
{
    use TemplateRenderTrait {
        renderTemplate as render;
    }

    /**
     * Construct
     */
    public function __construct()
    {
        // Bind before_filter and before_render
        if (method_exists($this, 'before_filter')) {
            add_action('fsi_controller_before_filter', array($this, 'before_filter'));
        }

        if (method_exists($this, 'before_render')) {
            add_action('fsi_controller_before_render', array($this, 'before_render'));
        }

        // Run before_filter
        do_action('fsi_controller_before_filter', get_called_class());
    }

    /**
     * Triggered after action bindings and before any action
     *
     * @param string $class current class name
     */
    public function before_filter($class)
    {
        /* -- */
    }
}
