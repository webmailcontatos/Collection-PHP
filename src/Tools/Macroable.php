<?php

namespace Collection\Tools;

use BadMethodCallException;
use Closure;

trait Macroable
{
    /**
     * The registered string macros.
     *
     * @var array
     */
    protected static $macros = [];

    /**
     * Register a custom macro.
     *
     * @param string   $name  Nome
     * @param callable $macro Macro function
     *
     * @return void
     */
    public static function macro($name, callable $macro)
    {
        static::$macros[$name] = $macro;
    }

//end macro()

    /**
     * Checks if macro is registered.
     *
     * @param string $name Nome
     *
     * @return bool
     */
    public static function hasMacro($name)
    {
        return isset(static::$macros[$name]);
    }

//end hasMacro()

    /**
     * Dynamically handle calls to the class.
     *
     * @param string $method     Método
     * @param array  $parameters Parametros
     *
     * @throws BadMethodCallException Caso o método não exista.
     *
     * @return mixed
     */
    public static function __callStatic($method, array $parameters)
    {
        if (static::hasMacro($method) === true) {
            if (static::$macros[$method] instanceof Closure) {
                return call_user_func_array(
                    Closure::bind(
                        static::$macros[$method],
                        null,
                        get_called_class()
                    ),
                    $parameters
                );
            } else {
                return call_user_func_array(static::$macros[$method], $parameters);
            }
        }

        throw new BadMethodCallException('Method '.$method.' does not exist.');
    }

//end __callStatic()

    /**
     * Dynamically handle calls to the class.
     *
     * @param string $method     Método
     * @param array  $parameters Parametros
     *
     * @throws BadMethodCallException Caso o metódo não exista.
     *
     * @return mixed
     */
    public function __call($method, array $parameters)
    {
        if (static::hasMacro($method) === true) {
            if (static::$macros[$method] instanceof Closure) {
                return call_user_func_array(static::$macros[$method]->bindTo($this, get_class($this)), $parameters);
            } else {
                return call_user_func_array(static::$macros[$method], $parameters);
            }
        }

        throw new BadMethodCallException('Method '.$method.' does not exist.');
    }

//end __call()
}
