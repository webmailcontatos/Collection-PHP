<?php

namespace Collection\Tools;

use ArrayAccess;
use Closure;

/*
    * To change this license header, choose License Headers in Project Properties.
    * To change this template file, choose Tools | Templates
    * and open the template in the editor.
 */

/**
 * Description of Helpers.
 */
class Helpers
{
    /**
     * Get an item from an array or object using "dot" notation.
     *
     * @param mixed        $target  Alvo
     * @param string|array $key     Chave
     * @param mixed        $default Default
     *
     * @return mixed
     */
    public static function dataGet($target, $key, $default = null)
    {
        if ($key === null) {
            return $target;
        }

        $key = is_array($key) ? $key : explode('.', $key);

        foreach ($key as $segment) {
            if (is_array($target) === true) {
                if (array_key_exists($segment, $target) === false) {
                    return self::value($default);
                }

                $target = $target[$segment];
            } elseif ($target instanceof ArrayAccess) {
                if (isset($target[$segment]) === false) {
                    return self::value($default);
                }

                $target = $target[$segment];
            } elseif (is_object($target) === true) {
                if (isset($target->{$segment}) === false) {
                    return self::value($default);
                }

                $target = $target->{$segment};
            } else {
                return self::value($default);
            }//end if
        }//end foreach

        return $target;
    }

// end dataGet()
    /**
     * Return the default value of the given value.
     *
     * @param mixed $value Valor
     *
     * @return mixed
     */
    public static function value($value)
    {
        if ($value instanceof Closure) {
            return $value();
        }

        return $value;
    }

// end value()
}//end class
