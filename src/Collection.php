<?php

namespace Collection;

use ArrayAccess;
use ArrayIterator;
use CachingIterator;
use Collection\Interfaces\Arrayable;
use Collection\Interfaces\Jsonable;
use Collection\Tools\Arr;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;
use JsonSerializable;

/**
 * Class Collection.
 */
class Collection implements ArrayAccess, Arrayable, Countable, IteratorAggregate, Jsonable, JsonSerializable
{
    /**
     * The items contained in the collection.
     *
     * @var array
     */
    protected $items = [];

    /**
     * Collection constructor.
     *
     * @param array $items Array
     */
    public function __construct($items = [])
    {
        $this->items = is_array($items) ? $items : $this->getArrayableItems($items);
    }

    /**
     * Create a new collection instance if the value isn't one already.
     *
     * @param mixed $items Array com os dados da colecao
     *
     * @return static
     */
    public static function make($items = [])
    {
        return new static($items);
    }

    /**
     * Get all of the items in the collection.
     *
     * @return array
     */
    public function all()
    {
        return $this->items;
    }

    /**
     * Collapse the collection of items into a single array.
     *
     * @return static
     */
    public function collapse()
    {
        return new static(Arr::collapse($this->items));
    }

    /**
     * Determine if an item exists in the collection.
     *
     * @param mixed $key Chave do array
     * @param mixed $value Valor
     *
     * @return boolean
     */
    public function contains($key, $value = null)
    {
        if (func_num_args() === 2) {
            return $this->contains(
                function (
                    $k,
                    $item
                ) use (
                    $key,
                    $value
                ) {
                    unset($k);

                    return Tools\Helpers::dataGet($item, $key) == $value;
                }
            );
        }

        if ($this->useAsCallable($key) === true) {
            return !($this->first($key) === null);
        }

        return in_array($key, $this->items);
    }

    /**
     * Diff the collection with the given items.
     *
     * @param mixed $items Dados da collection
     *
     * @return static
     */
    public function diff($items)
    {
        return new static(array_diff($this->items, $this->getArrayableItems($items)));
    }

    /**
     * Execute a callback over each item.
     *
     * @param callable $callback Funcao
     *
     * @return $this
     */
    public function each(callable $callback)
    {
        foreach (array_keys($this->items) as $key) {
            if ($callback($this->get($key), $key) === false) {
                break;
            }
        }

        return $this;
    }

    /**
     * Fetch a nested element of the collection.
     *
     * @param string $key Chave
     *
     * @return static
     *
     * @deprecated since version 5.1. Use pluck instead.
     */
    public function fetch($key)
    {
        return new static(Arr::fetch($this->items, $key));
    }

    /**
     * Run a filter over each of the items.
     *
     * @param callable|null $callback Funcao
     *
     * @return static
     */
    public function filter(callable $callback = null)
    {
        if ($callback !== null) {
            return new static(array_filter($this->items, $callback));
        }

        return new static(array_filter($this->items));
    }

    /**
     * Filter items by the given key value pair.
     *
     * @param string $key Chave
     * @param mixed $value Valor
     * @param boolean $strict Strict
     *
     * @return static
     */
    public function where($key, $value, $strict = true)
    {
        return $this->filter(
            function (
                $item
            ) use (
                $key,
                $value,
                $strict
            ) {
                $strictTrue = false;
                if (Tools\Helpers::dataGet($item, $key) === $value) {
                    $strictTrue = true;
                }

                $strictFalse = false;
                if (Tools\Helpers::dataGet($item, $key) === $value) {
                    $strictFalse = true;
                }

                return ($strict === true) ? $strictTrue : $strictFalse;
            }
        );
    }

    /**
     * Filter items by the given key value pair using loose comparison.
     *
     * @param string $key Chave
     * @param mixed $value Valor
     *
     * @return static
     */
    public function whereLoose($key, $value)
    {
        return $this->where($key, $value, false);
    }

    /**
     * Get the first item from the collection.
     *
     * @param callable|null $callback Funcao
     * @param mixed $default Valor default
     *
     * @return mixed
     */
    public function first(callable $callback = null, $default = null)
    {
        if ($callback === null) {
            if (count($this->items) > 0) {
                return $this->toCollection(reset($this->items));
            }

            return $this->toCollection(null);
        }

        $value = Arr::first($this->items, $callback, $default);

        return $this->toCollection($value);
    }

    /**
     * Get a flattened array of the items in the collection.
     *
     * @return static
     */
    public function flatten()
    {
        return new static(Arr::flatten($this->items));
    }

    /**
     * Flip the items in the collection.
     *
     * @return static
     */
    public function flip()
    {
        return new static(array_flip($this->items));
    }

    /**
     * Remove an item from the collection by key.
     *
     * @param mixed $key Chave
     *
     * @return $this
     */
    public function forget($key)
    {
        $this->offsetUnset($key);

        return $this;
    }

    /**
     * Get an item from the collection by key.
     *
     * @param mixed $key Chave
     * @param mixed $default Default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if ($this->offsetExists($key) === true) {
            $value = $this->items[$key];

            if (is_array($value) === true) {
                return new static($value);
            }

            return $value;
        }

        return Tools\Helpers::value($default);
    }

    /**
     * Group an associative array by a field or using a callback.
     *
     * @param callable|string $groupBy Groupby
     * @param boolean $preserveKeys Preservar chave
     *
     * @return static
     */
    public function groupBy($groupBy, $preserveKeys = false)
    {
        $groupBy = $this->valueRetriever($groupBy);

        $results = [];

        foreach ($this->items as $key => $value) {
            $groupKey = $groupBy($value, $key);

            if (array_key_exists($groupKey, $results) === false) {
                $results[$groupKey] = new static();
            }

            $results[$groupKey]->offsetSet(null, $value);
            if ($preserveKeys === true) {
                $results[$groupKey]->offsetSet($key, $value);
            }
        }

        return new static($results);
    }

    /**
     * Key an associative array by a field or using a callback.
     *
     * @param callable|string $keyBy Chave
     *
     * @return static
     */
    public function keyBy($keyBy)
    {
        $keyBy = $this->valueRetriever($keyBy);

        $results = [];

        foreach ($this->items as $item) {
            $results[$keyBy($item)] = $item;
        }

        return new static($results);
    }

    /**
     * Determine if an item exists in the collection by key.
     *
     * @param mixed $key Chave
     *
     * @return boolean
     */
    public function has($key)
    {
        return $this->offsetExists($key);
    }

    /**
     * Concatenate values of a given key as a string.
     *
     * @param string $value Valor
     * @param string $glue Delimitador
     *
     * @return string
     */
    public function implode($value, $glue = null)
    {
        $first = $this->first();

        if (is_array($first) === true || is_object($first) === true) {
            return implode($glue, $this->pluck($value)->all());
        }

        return implode($value, $this->items);
    }

    /**
     * Intersect the collection with the given items.
     *
     * @param mixed $items Dados do array
     *
     * @return static
     */
    public function intersect($items)
    {
        return new static(array_intersect($this->items, $this->getArrayableItems($items)));
    }

    /**
     * Determine if the collection is empty or not.
     *
     * @return boolean
     */
    public function isEmpty()
    {
        return empty($this->items);
    }

    /**
     * Determine if the given value is callable, but not a string.
     *
     * @param mixed $value Valor
     *
     * @return boolean
     */
    protected function useAsCallable($value)
    {
        if (is_string($value) === false && is_callable($value) === true) {
            return true;
        }

        return false;
    }

    /**
     * Get the keys of the collection items.
     *
     * @return static
     */
    public function keys()
    {
        return new static(array_keys($this->items));
    }

    /**
     * Get the last item from the collection.
     *
     * @param callable|null $callback Funcao
     * @param mixed $default Valor default
     *
     * @return mixed
     */
    public function last(callable $callback = null, $default = null)
    {
        if ($callback === null) {
            $value = Tools\Helpers::value($default);
            if ((count($this->items) > 0)) {
                $value = end($this->items);
            }

            return $this->toCollection($value);
        }

        $value = Arr::last($this->items, $callback, $default);

        return $this->toCollection($value);
    }

    /**
     * Get an array with the values of a given key.
     *
     * @param string $value Valor
     * @param string $key Chave
     *
     * @return static
     */
    public function pluck($value, $key = null)
    {
        return new static(Arr::pluck($this->items, $value, $key));
    }

    /**
     * Alias for the "pluck" method.
     *
     * @param string $value Valor
     * @param string $key Chave
     *
     * @return static
     */
    public function lists($value, $key = null)
    {
        return $this->pluck($value, $key);
    }

    /**
     * Run a map over each of the items.
     *
     * @param callable $callback Funcao
     *
     * @return static
     */
    public function map(callable $callback)
    {
        $keys = array_keys($this->items);

        $items = array_map($callback, $this->items, $keys);

        return new static(array_combine($keys, $items));
    }

    /**
     * Get the max value of a given key.
     *
     * @param string|null $key Chave
     *
     * @return mixed
     */
    public function max($key = null)
    {
        return $this->reduce(
            function (
                $result,
                $item
            ) use (
                $key
            ) {
                $value = Tools\Helpers::dataGet($item, $key);
                if (($result === null) || ($value > $result)) {
                    return $value;
                }

                return $result;
            }
        );
    }

    /**
     * Merge the collection with the given items.
     *
     * @param mixed $items Dados do array
     *
     * @return static
     */
    public function merge($items)
    {
        return new static(array_merge($this->items, $this->getArrayableItems($items)));
    }

    /**
     * Get the min value of a given key.
     *
     * @param string|null $key Chave
     *
     * @return mixed
     */
    public function min($key = null)
    {
        return $this->reduce(
            function (
                $result,
                $item
            ) use (
                $key
            ) {
                $value = Tools\Helpers::dataGet($item, $key);
                if (($result === null) || ($value < $result)) {
                    return $value;
                }

                return $result;
            }
        );
    }

    /**
     * "Paginate" the collection by slicing it into a smaller collection.
     *
     * @param integer $page Página
     * @param integer $perPage Por página
     *
     * @return static
     */
    public function forPage($page, $perPage)
    {
        return $this->slice((($page - 1) * $perPage), $perPage);
    }

    /**
     * Get and remove the last item from the collection.
     *
     * @return mixed
     */
    public function pop()
    {
        return array_pop($this->items);
    }

    /**
     * Push an item onto the beginning of the collection.
     *
     * @param mixed $value Valor
     *
     * @return $this
     */
    public function prepend($value)
    {
        array_unshift($this->items, $value);

        return $this;
    }

    /**
     * Push an item onto the end of the collection.
     *
     * @param mixed $value Valor
     *
     * @return $this
     */
    public function push($value)
    {
        $this->offsetSet(null, $value);

        return $this;
    }

    /**
     * Pulls an item from the collection.
     *
     * @param mixed $key Chave
     * @param mixed $default Default
     *
     * @return mixed
     */
    public function pull($key, $default = null)
    {
        return Arr::pull($this->items, $key, $default);
    }

    /**
     * Put an item in the collection by key.
     *
     * @param mixed $key Chave
     * @param mixed $value Valor
     *
     * @return $this
     */
    public function put($key, $value)
    {
        $this->offsetSet($key, $value);

        return $this;
    }

    /**
     * Get one or more items randomly from the collection.
     *
     * @param integer $amount Quantidade
     *
     * @throws InvalidArgumentException Se a quantidade for inválida.
     *
     * @return mixed
     */
    public function random($amount = 1)
    {
        $count = $this->count();
        if ($amount > ($count)) {
            throw new InvalidArgumentException(
                'You requested ' . $amount . ' items, but there are only ' . $count . ' items in the collection'
            );
        }

        $keys = array_rand($this->items, $amount);

        if ($amount === 1) {
            return $this->items[$keys];
        }

        return new static(array_intersect_key($this->items, array_flip($keys)));
    }

    /**
     * Reduce the collection to a single value.
     *
     * @param callable $callback Funcao
     * @param mixed $initial Inicial
     *
     * @return mixed
     */
    public function reduce(callable $callback, $initial = null)
    {
        return array_reduce($this->items, $callback, $initial);
    }

    /**
     * Create a collection of all elements that do not pass a given truth test.
     *
     * @param callable|mixed $callback Funcao
     *
     * @return static
     */
    public function reject($callback)
    {
        if ($this->useAsCallable($callback) === true) {
            return $this->filter(
                function (
                    $item
                ) use (
                    $callback
                ) {
                    return !$callback($item);
                }
            );
        }

        return $this->filter(
            function (
                $item
            ) use (
                $callback
            ) {
                return $item != $callback;
            }
        );
    }

    /**
     * Reverse items order.
     *
     * @return static
     */
    public function reverse()
    {
        return new static(array_reverse($this->items));
    }

    /**
     * Search the collection for a given value and return the corresponding key if successful.
     *
     * @param mixed $value Valor
     * @param boolean $strict Estrito
     *
     * @return mixed
     */
    public function search($value, $strict = false)
    {
        if ($this->useAsCallable($value) === false) {
            return array_search($value, $this->items, $strict);
        }

        foreach ($this->items as $key => $item) {
            if (call_user_func($value, $item, $key) !== false) {
                return $key;
            }
        }

        return false;
    }

    /**
     * Get and remove the first item from the collection.
     *
     * @return mixed
     */
    public function shift()
    {
        return array_shift($this->items);
    }

    /**
     * Shuffle the items in the collection.
     *
     * @return static
     */
    public function shuffle()
    {
        $items = $this->items;

        shuffle($items);

        return new static($items);
    }

    /**
     * Slice the underlying collection array.
     *
     * @param integer $offset Offset
     * @param integer $length Length
     * @param boolean $preserveKeys Preservar chaves
     *
     * @return static
     */
    public function slice($offset, $length = null, $preserveKeys = false)
    {
        return new static(array_slice($this->items, $offset, $length, $preserveKeys));
    }

    /**
     * Chunk the underlying collection array.
     *
     * @param integer $size Tamanho
     * @param boolean $preserveKeys Preservar chaves
     *
     * @return static
     */
    public function chunk($size, $preserveKeys = false)
    {
        $chunks = [];

        foreach (array_chunk($this->items, $size, $preserveKeys) as $chunk) {
            $chunks[] = new static($chunk);
        }

        return new static($chunks);
    }

    /**
     * Sort through each item with a callback.
     *
     * @param callable|null $callback Funcao
     *
     * @return static
     */
    public function sort(callable $callback = null)
    {
        $items = $this->items;

        ($callback === true) ? uasort($items, $callback) : natcasesort($items);

        return new static($items);
    }

    /**
     * Sort the collection using the given callback.
     *
     * @param callable|string $callback Funcao
     * @param integer $options Opções
     * @param boolean $descending Descendente
     *
     * @return static
     */
    public function sortBy($callback, $options = SORT_REGULAR, $descending = false)
    {
        $results = [];

        $callback = $this->valueRetriever($callback);

        // First we will loop through the items and get the comparator from a callback
        // function which we were given. Then, we will sort the returned values and
        // and grab the corresponding values for the sorted keys from this array.
        foreach ($this->items as $key => $value) {
            $results[$key] = $callback($value, $key);
        }

        ($descending === true) ? arsort($results, $options) : asort($results, $options);
        // Once we have sorted all of the keys in the array, we will loop through them
        // and grab the corresponding model so we can set the underlying items list
        // to the sorted version. Then we'll just return the collection instance.
        foreach (array_keys($results) as $key) {
            $results[$key] = $this->items[$key];
        }

        return new static($results);
    }

    /**
     * Sort the collection in descending order using the given callback.
     *
     * @param callable|string $callback Funcao
     * @param integer $options Opções
     *
     * @return static
     */
    public function sortByDesc($callback, $options = SORT_REGULAR)
    {
        return $this->sortBy($callback, $options, true);
    }

    /**
     * Splice a portion of the underlying collection array.
     *
     * @param integer $offset Offset
     * @param integer|null $length Tamanho
     * @param mixed $replacement Subtituição
     *
     * @return static
     */
    public function splice($offset, $length = null, $replacement = [])
    {
        if (func_num_args() === 1) {
            return new static(array_splice($this->items, $offset));
        }

        return new static(array_splice($this->items, $offset, $length, $replacement));
    }

    /**
     * Get the sum of the given values.
     *
     * @param callable|string|null $callback Funcao
     *
     * @return mixed
     */
    public function sum($callback = null)
    {
        if ($callback === null) {
            return array_sum($this->items);
        }

        $callback = $this->valueRetriever($callback);

        return $this->reduce(
            function (
                $result,
                $item
            ) use (
                $callback
            ) {
                return $result += $callback($item);
            },
            0
        );
    }

    /**
     * Take the first or last {$limit} items.
     *
     * @param integer $limit Limite
     *
     * @return static
     */
    public function take($limit)
    {
        if ($limit < 0) {
            return $this->slice($limit, abs($limit));
        }

        return $this->slice(0, $limit);
    }

    /**
     * Transform each item in the collection using a callback.
     *
     * @param callable $callback Funcao
     *
     * @return $this
     */
    public function transform(callable $callback)
    {
        $this->items = $this->map($callback)->all();

        return $this;
    }

    /**
     * Return only unique items from the collection array.
     *
     * @param string|callable|null $key Chave
     *
     * @return static
     */
    public function unique($key = null)
    {
        if ($key === true) {
            return new static(array_unique($this->items, SORT_REGULAR));
        }

        $key = $this->valueRetriever($key);

        $exists = [];

        return $this->reject(
            function (
                $item
            ) use (
                $key,
                &$exists
            ) {
                $id = $key($item);
                if (in_array($id, $exists) === true) {
                    return true;
                }

                $exists[] = $id;
            }
        );
    }

    /**
     * Reset the keys on the underlying array.
     *
     * @return static
     */
    public function values()
    {
        return new static(array_values($this->items));
    }

    /**
     * Get a value retrieving callback.
     *
     * @param string $value Valor
     *
     * @return callable
     */
    protected function valueRetriever($value)
    {
        if ($this->useAsCallable($value) === true) {
            return $value;
        }

        return function ($item) use ($value) {
            return Tools\Helpers::dataGet($item, $value);
        };
    }

    /**
     * Zip the collection together with one or more arrays.
     *
     * E.g. new Collection([1, 2, 3])->zip([4, 5, 6]);
     *      => [[1, 4], [2, 5], [3, 6]]
     *
     * @param mixed $items Dados do array
     *
     * @return static
     */
    public function zip($items)
    {
        $arrayableItems = array_map(
            function (
                $items
            ) {
                return $this->getArrayableItems($items);
            },
            func_get_args()
        );
        $function = function () {
            return new static(func_get_args());
        };

        $params = array_merge(
            [
             $function,
             $this->items,
            ],
            $arrayableItems
        );

        return new static(call_user_func_array('array_map', $params));
    }

    /**
     * Get the collection of items as a plain array.
     *
     * @return array
     */
    public function toArray()
    {
        return array_map(
            function (
                $value
            ) {
                if ($value instanceof Arrayable) {
                    return $value->toArray();
                }

                return $value;
            },
            $this->items
        );
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Get the collection of items as JSON.
     *
     * @param integer $options Inteiro
     *
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Get an iterator for the items.
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }

    /**
     * Get a CachingIterator instance.
     *
     * @param integer $flags Bandeira
     *
     * @return \CachingIterator
     */
    public function getCachingIterator($flags = CachingIterator::CALL_TOSTRING)
    {
        return new CachingIterator($this->getIterator(), $flags);
    }

    /**
     * Count the number of items in the collection.
     *
     * @return integer
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * Determine if an item exists at an offset.
     *
     * @param mixed $key Chave
     *
     * @return boolean
     */
    public function offsetExists($key)
    {
        return array_key_exists($key, $this->items);
    }

    /**
     * Get an item at a given offset.
     *
     * @param mixed $key Chave
     *
     * @return mixed
     */
    public function offsetGet($key)
    {
        if (isset($this->items[$key])) {
            return $this->items[$key];
        }
    }

    /**
     * Set the item at a given offset.
     *
     * @param mixed $key Chave
     * @param mixed $value Valor
     *
     * @return void
     */
    public function offsetSet($key, $value)
    {
        if ($key === null) {
            $this->items[] = $value;
        } else {
            $this->items[$key] = $value;
        }
    }

    /**
     * Unset the item at a given offset.
     *
     * @param string $key Chave
     *
     * @return void
     */
    public function offsetUnset($key)
    {
        unset($this->items[$key]);
    }

    /**
     * Convert the collection to its string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * Results array of items from Collection or Arrayable.
     *
     * @param mixed $items Dados do array
     *
     * @return array
     */
    protected function getArrayableItems($items)
    {
        if ($items instanceof self) {
            return $items->all();
        } elseif ($items instanceof Arrayable) {
            return $items->toArray();
        } elseif ($items instanceof Jsonable) {
            return json_decode($items->toJson(), true);
        }

        return (array)$items;
    }

    /**
     * Convert um array para Collection.
     *
     * @param mixed $value Dados da colecao
     *
     * @return static
     */
    private function toCollection($value)
    {
        return is_array($value) ? new static($value) : $value;
    }

    /**
     * Obtem um valor e o remove.
     *
     * @param mixed $key Chave
     * @param mixed $default Valor default
     *
     * @return mixed $default
     */
    public function getAndRemove($key, $default = null)
    {
        $value = $this->get($key, $default);
        $this->offsetUnset($key);

        return $value;
    }

    /**
     * Método que possibilita move items dentro de array.
     *
     * @param mixed $toMove Elemento que deseja mover
     * @param mixed $targetIndex Alvo
     *
     * @return Collection
     */
    public function moveElement($toMove, $targetIndex)
    {
        $array = $this->toArray();
        if (is_int($toMove) === true) {
            $tmp = array_splice($array, $toMove, 1);
            array_splice($array, $targetIndex, 0, $tmp);
            $output = $array;
        } elseif (is_string($toMove) === true) {
            $indexToMove = array_search($toMove, array_keys($array));
            $itemToMove = $array[$toMove];
            array_splice($array, $indexToMove, 1);
            $i = 0;
            $output = [];
            foreach ($array as $key => $item) {
                if ($i === $targetIndex) {
                    $output[$toMove] = $itemToMove;
                }

                $output[$key] = $item;
                $i++;
            }
        }

        return $this->toCollection($output);
    }

    /**
     * Cria uma nova collection com os valores retornados pelo callback.
     *
     * @param callable $callback Funcao
     *
     * @return $this
     */
    public function modify(callable $callback)
    {
        return $this->map($callback)->reject(
            function (
                $value
            ) {
                return $value === null;
            }
        );
    }
}
