<?php

namespace Collection\Tools;

use ArrayAccess;
use Collection\Collection;

/**
 * Class Arr.
 */
class Arr
{
    use Macroable;

    /**
     * Add an element to an array using "dot" notation if it doesn't exist.
     *
     * @param array  $array Array
     * @param string $key   Key
     * @param mixed  $value Valor
     *
     * @return array
     */
    public static function add(array $array, $key, $value)
    {
        if (static::get($array, $key) === null) {
            static::set($array, $key, $value);
        }

        return $array;
    }

//end add()

    /**
     * Build a new array using a callback.
     *
     * @param array    $array    Array
     * @param callable $callback Funcao
     *
     * @return array
     */
    public static function build(array $array, callable $callback)
    {
        $results = [];

        foreach ($array as $key => $value) {
            list($innerKey, $innerValue) = call_user_func($callback, $key, $value);

            $results[$innerKey] = $innerValue;
        }

        return $results;
    }

//end build()

    /**
     * Collapse an array of arrays into a single array.
     *
     * @param array|ArrayAccess $array Array
     *
     * @return array
     */
    public static function collapse($array)
    {
        $results = [];

        foreach ($array as $values) {
            if ($values instanceof Collection) {
                $values = $values->all();
            }

            $results = array_merge($results, $values);
        }

        return $results;
    }

//end collapse()

    /**
     * Divide an array into two arrays. One with keys and the other with values.
     *
     * @param array $array Array
     *
     * @return array
     */
    public static function divide(array $array)
    {
        return [
                array_keys($array),
                array_values($array),
               ];
    }

//end divide()

    /**
     * Flatten a multi-dimensional associative array with dots.
     *
     * @param array  $array   Array
     * @param string $prepend Prepende
     *
     * @return array
     */
    public static function dot(array $array, $prepend = '')
    {
        $results = [];

        foreach ($array as $key => $value) {
            if (is_array($value) === true) {
                $results = array_merge($results, static::dot($value, $prepend.$key.'.'));
            } else {
                $results[$prepend.$key] = $value;
            }
        }

        return $results;
    }

//end dot()

    /**
     * Get all of the given array except for a specified array of items.
     *
     * @param array        $array Array
     * @param array|string $keys  Chave
     *
     * @return array
     */
    public static function except(array $array, $keys)
    {
        static::forget($array, $keys);

        return $array;
    }

//end except()

    /**
     * Fetch a flattened array of a nested array element.
     *
     * @param array  $array Array
     * @param string $key   Chave
     *
     * @return array
     *
     * @deprecated since version 5.1. Use pluck instead.
     */
    public static function fetch(array $array, $key)
    {
        foreach (explode('.', $key) as $segment) {
            $results = [];

            foreach ($array as $value) {
                $value = (array) $value;
                if (array_key_exists($segment, $value) === true) {
                    $results[] = $value[$segment];
                }
            }

            $array = array_values($results);
        }

        return array_values($results);
    }

//end fetch()

    /**
     * Return the first element in an array passing a given truth test.
     *
     * @param array    $array    Array
     * @param callable $callback Funcao
     * @param mixed    $default  Default
     *
     * @return mixed
     */
    public static function first(array $array, callable $callback, $default = null)
    {
        foreach ($array as $key => $value) {
            if (call_user_func($callback, $key, $value) !== false) {
                return $value;
            }
        }

        return Helpers::value($default);
    }

//end first()

    /**
     * Return the last element in an array passing a given truth test.
     *
     * @param array    $array    Array
     * @param callable $callback Funcao
     * @param mixed    $default  Default
     *
     * @return mixed
     */
    public static function last(array $array, callable $callback, $default = null)
    {
        return static::first(array_reverse($array), $callback, $default);
    }

//end last()

    /**
     * Flatten a multi-dimensional array into a single level.
     *
     * @param array $array Array
     *
     * @return array
     */
    public static function flatten(array $array)
    {
        $return = [];

        array_walk_recursive(
            $array,
            function ($x) use (&$return) {
                $return[] = $x;
            }
        );

        return $return;
    }

//end flatten()

    /**
     * Remove one or many array items from a given array using "dot" notation.
     *
     * @param array        $array Array
     * @param array|string $keys  Chave
     *
     * @return void
     */
    public static function forget(array &$array, $keys)
    {
        $original = &$array;

        foreach ((array) $keys as $key) {
            $parts = explode('.', $key);

            unset($array[array_shift($parts)]);

            // Clean up after each pass
            $array = &$original;
        }
    }

//end forget()

    /**
     * Get an item from an array using "dot" notation.
     *
     * @param array  $array   Array
     * @param string $key     Chave
     * @param mixed  $default Default
     *
     * @return mixed
     */
    public static function get(array $array, $key, $default = null)
    {
        if ($key === null) {
            return $array;
        }

        if (isset($array[$key]) === true) {
            return $array[$key];
        }

        foreach (explode('.', $key) as $segment) {
            if (array_key_exists($segment, $array) === false) {
                return Helpers::value($default);
            }
        }
    }

//end get()

    /**
     * Check if an item exists in an array using "dot" notation.
     *
     * @param array  $array Array
     * @param string $key   Chave
     *
     * @return bool
     */
    public static function has(array $array, $key)
    {
        if (array_key_exists($key, $array) === true) {
            return true;
        }

        foreach (explode('.', $key) as $segment) {
            if (is_array($array) === false || array_key_exists($segment, $array) === false) {
                return false;
            }
        }
    }

//end has()

    /**
     * Determines if an array is associative.
     *
     * An array is "associative" if it doesn't have sequential numerical keys beginning with zero.
     *
     * @param array $array Array
     *
     * @return bool
     */
    public static function isAssoc(array $array)
    {
        $keys = array_keys($array);

        return array_keys($keys) !== $keys;
    }

//end isAssoc()

    /**
     * Get a subset of the items from the given array.
     *
     * @param array        $array Array
     * @param array|string $keys  Chave
     *
     * @return array
     */
    public static function only(array $array, $keys)
    {
        return array_intersect_key($array, array_flip((array) $keys));
    }

//end only()

    /**
     * Pluck an array of values from an array.
     *
     * @param array             $array Array
     * @param string|array      $value Valor
     * @param string|array|null $key   Chave
     *
     * @return array
     */
    public static function pluck(array $array, $value, $key = null)
    {
        $results = [];

        list($value, $key) = static::explodePluckParameters($value, $key);

        foreach ($array as $item) {
            $itemValue = Helpers::dataGet($item, $value);

            // If the key is "null", we will just append the value to the array and keep
            // looping. Otherwise we will key the array using the value of the key we
            // received from the developer. Then we'll return the final array form.
            if ($key === null) {
                $results[] = $itemValue;
            } else {
                $itemKey = Helpers::dataGet($item, $key);

                $results[$itemKey] = $itemValue;
            }
        }

        return $results;
    }

//end pluck()

    /**
     * Explode the "value" and "key" arguments passed to "pluck".
     *
     * @param string|array      $value Valor
     * @param string|array|null $key   Chave
     *
     * @return array
     */
    protected static function explodePluckParameters($value, $key)
    {
        $value = is_array($value) ? $value : explode('.', $value);

        if (($key === null || is_array($key) === true) === false) {
            $key = explode('.', $key);
        }

        return [
                $value,
                $key,
               ];
    }

//end explodePluckParameters()

    /**
     * Get a value from the array, and remove it.
     *
     * @param array  $array   Array
     * @param string $key     Chave
     * @param mixed  $default Default
     *
     * @return mixed
     */
    public static function pull(array &$array, $key, $default = null)
    {
        $value = static::get($array, $key, $default);

        static::forget($array, $key);

        return $value;
    }

//end pull()

    /**
     * Set an array item to a given value using "dot" notation.
     *
     * If no key is given to the method, the entire array will be replaced.
     *
     * @param array  $array Array
     * @param string $key   Chave
     * @param mixed  $value Valor
     *
     * @return array
     */
    public static function set(array &$array, $key, $value)
    {
        if ($key === null) {
            return $array = $value;
        }

        $keys = explode('.', $key);

        while (count($keys) > 1) {
            $key = array_shift($keys);

            // If the key doesn't exist at this depth, we will just create an empty array
            // to hold the next value, allowing us to create the arrays to hold final
            // values at the correct depth. Then we'll keep digging into the array.
            if (isset($array[$key]) === false || is_array($array[$key]) === false) {
                $array[$key] = [];
            }

            $array = &$array[$key];
        }

        $array[array_shift($keys)] = $value;

        return $array;
    }

//end set()

    /**
     * Sort the array using the given callback.
     *
     * @param array    $array    Array
     * @param callable $callback Funcao
     *
     * @return array
     */
    public static function sort(array $array, callable $callback)
    {
        return Collection::make($array)->sortBy($callback)->all();
    }

//end sort()

    /**
     * Recursively sort an array by keys and values.
     *
     * @param array $array Array
     *
     * @return array
     */
    public static function sortRecursive(array $array)
    {
        foreach ($array as &$value) {
            if (is_array($value) === true) {
                $value = self::sortRecursive($value);
            }
        }

        sort($array);

        return $array;
    }

//end sortRecursive()

    /**
     * Filter the array using the given callback.
     *
     * @param array    $array    Array
     * @param callable $callback Funcao
     *
     * @return array
     */
    public static function where(array $array, callable $callback)
    {
        $filtered = [];

        foreach ($array as $key => $value) {
            if (call_user_func($callback, $key, $value) !== false) {
                $filtered[$key] = $value;
            }
        }

        return $filtered;
    }

//end where()
}//end class
