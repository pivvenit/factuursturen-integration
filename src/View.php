<?php

namespace Pivvenit\FactuurSturen;

use Pivvenit\FactuurSturen\View\Partial;

abstract class View
{

    /**
     *
     * @var string
     */
    protected $_filename;

    /**
     *
     * @var array
     */
    protected $_arguments = array();

    /**
     * View constructor
     *
     * @param string $filename
     * @param array  $arguments
     */
    public function __construct($filename, array $arguments = array())
    {
        $this->setFilename($filename);
        $this->setArguments($arguments);
    }

    /**
     * Retrieve argument
     *
     * @param string $key
     * @return mixed|NULL
     */
    public function __get($key)
    {
        if ($this->hasArgument($key)) {
            return $this->getArgument($key);
        }
        return null;
    }

    /**
     * Make empty() work on arguments
     *
     * @param type $key
     * @return boolean|NULL
     */
    public function __isset($key)
    {
        if ($this->hasArgument($key)) {
            return ( false === empty($this->getArgument($key)) );
        } else {
            return null;
        }
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->_filename;
    }

    /**
     * @return string
     */
    public function getArguments()
    {
        return $this->_arguments;
    }

    /**
     *
     * @param string $filename
     * @return View
     */
    public function setFilename($filename)
    {
        $this->_filename = $filename;
        return $this;
    }

    /**
     *
     * @param string $key
     * @return multitype
     */
    public function getArgument($key)
    {
        return $this->_arguments[ $key ];
    }

    /**
     *
     * @param string $key
     * @return boolean
     */
    public function hasArgument($key)
    {
        return isset($this->_arguments[ $key ]);
    }

    /**
     *
     * @param string $key
     * @param mixed  $value
     * @return Hapnij_Popup_View_AbstractView
     */
    public function setArgument($key, $value)
    {
        $this->arguments[ $key ] = $value;
        return $this;
    }

    /**
     *
     * @param array $arguments
     * @return View
     */
    public function setArguments(array $arguments)
    {
        $this->_arguments = array_merge($this->_arguments, $arguments);
        return $this;
    }

    /**
     *
     * @param type $path
     * @return type
     */
    protected function _renderFile($path)
    {
        ob_start();
        include $path;
        return ob_get_clean();
    }

    /**
     *
     * @param string $name
     * @param array  $arguments
     */
    public function partial($name, array $arguments = array(), $return = true)
    {
        $arguments = array_merge($arguments, $this->getArguments());
        $partial   = new Partial($name, $arguments);
        if ($return) {
            return $partial->render(true);
        } else {
            $partial->render();
        }
    }

    /**
     *
     * @return string|NULL
     */
    abstract public function render();
}
