<?php

namespace Pivvenit\FactuurSturen\View;

use Pivvenit\FactuurSturen\View\Template;

trait TemplateRenderTrait
{

    /**
     * Shortcut for rendering using template class
     *
     * @param string|null $view
     * @param array $data
     */
    public function renderTemplate($view = null, $data = array(), $return = false)
    {
        do_action('fsi_before_template_render', $view, $data, get_called_class());

        $template = new Template($view);

        if ($return) {
            return $template->render($return, $data);
        } else {
            $template->render($return, $data);
        }
    }
}
