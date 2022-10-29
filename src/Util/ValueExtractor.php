<?php

namespace Pivvenit\FactuurSturen\Util;

/**
 * Class ValueExtractor.
 */
class ValueExtractor
{

    /**
     * Extract value from single-dimension array with fallback to default value.
     *
     * @param array $array
     * @param string $key
     * @param mixed $default
     */
    public static function getValueWithDefault(array $array, $key, $default = null)
    {
        return isset($array[$key]) ? $array[$key] : $default;
    }
}
