<?php

namespace Pivvenit\FactuurSturen\Util;

class Backtrace
{

    /**
     * Get caller full class and method name.
     *
     * @return string
     */
    public static function getCallerClassMethod($trace = null)
    {
        if (!$trace) {
            $trace = debug_backtrace();
        }
        $caller = isset($trace[1]) ? $trace[1] : array('class' => '', 'function' => '');
        return $caller['class'] . '::' . $caller['function'];
    }
}
