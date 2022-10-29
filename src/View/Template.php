<?php

namespace Pivvenit\FactuurSturen\View;

use Pivvenit\FactuurSturen\View;
use Pivvenit\FactuurSturen\Config;

class Template extends View
{

    /**
     * Render template view.
     *
     * @param boolean $return True to return the content or false to echo. Default false.
     * @param array   $viewData Data to extract for view.
     * @return string|NULL
     */
    public function render($return = false, $viewData = array())
    {
        $config       = Config::get_config();
        $filename     = $this->getFilename();
        $templatePath = $this->_getTemplatePath();

        if (false === $templatePath) {
            wp_die(sprintf(__('View "%1$s" not found in path: %2$s!', 'factuursturen-integration'), $filename . '.php', $config['view_path']));
        } else {
            if (! empty($viewData)) {
                $this->setArguments($viewData);
            }

            if ($return) {
                return $this->_renderFile($templatePath);
            } else {
                echo $this->_renderFile($templatePath);
            }
        }
    }

    /**
     * Get absolute view path. Theme file will be returned first if exists and local plugin path next. Returns false if both files doesn't exist.
     *
     * @return string Full view path
     */
    protected function _getTemplatePath()
    {
        $filename  = $this->getFilename();
        $localPath = $this->_getLocalePath($filename);
        $themePath = $this->_getThemePath($filename);

        // Check theme file first
        if (file_exists($themePath)) {
            return $themePath;
        } elseif (file_exists($localPath)) {
            return $localPath;
        } else {
            return false;
        }
    }

    /**
     * Get path to template file located in this plugin
     *
     * @param string $filename file name
     * @return string path
     */
    protected function _getLocalePath($filename)
    {
        $config = Config::get_config();
        return $config['view_path'] . '/' . $filename . '.php';
    }

    /**
     * Get path to template file located in active theme
     *
     * @param string $filename file name
     * @return string path
     */
    protected function _getThemePath($filename)
    {
        return get_template_directory() . '/factuursturen-integration/' . $filename . '.php';
    }
}
