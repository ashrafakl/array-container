<?php

declare(strict_types=1);

namespace ashrafakl\tools\arrays;

use ArrayAccess;
use Generator;

class ArrayContainer implements ArrayAccess
{
    private array $list;
    private const GENERATOR_SEND_STOP = -1;

    /**
     * ArrayContainer constructor.
     * @param array $list array elements
     */
    public function __construct(array $list)
    {
        $this->list = $list;
    }

    /**
     * Tested
     * Applies the callback to the elements of the [[list]] array
     * @param callable $callable function to run for each element in [[list]] array.
     * @return $this ArrayContainer object or array containing all the elements of [[list]]
     * after applying the callback function for each element.
     */
    public function map(callable $callable): ArrayContainer
    {
        $this->list = array_map($callable, $this->list);
        return $this;
    }

    /**
     * Tested
     * Applies the callback to the elements of the [[list]] array
     * If the callback function returns true, the current value in [[list]] array is returned into the result array
     * Iterates over each value in the [[list]] array
     * @param callable $callable
     * @param bool $reindex When true reindex the [[list]] array from zero.
     * @return $this ArrayContainer object or array containing all the elements of [[list]]
     * after applying the callback function for each element.
     */
    public function filter(callable $callable, bool $reindex = false): ArrayContainer
    {
        $this->list = array_filter($this->list, $callable);
        if ($reindex) {
            $this->list = $this->values();
        }
        return $this;
    }

    /**
     * Return all the values of [[list]] array
     * @return mixed
     */
    public function values()
    {
        return array_values($this->list);
    }

    /**
     * Tested
     * Pop the element off the end of [[list]] array
     * @return mixed
     */
    public function pop()
    {
        return array_pop($this->list);
    }

    /**
     * Tested
     * Push elements onto the end of the [[list]] array
     * @param mixed ...$elements The pushed variables
     * @return  ArrayContainer object
     */
    public function push(...$elements): ArrayContainer
    {
        $itemsCount = count($elements);
        if ($itemsCount === 1) {
            $this->list[] = $elements[0];
            return $this;
        }
        $this->list = array_merge($this->list, $elements);
        return $this;
    }

    /**
     * Tested
     * Shift an element off the beginning of the [[list]] array
     * @return mixed
     */
    public function shift()
    {
        return array_shift($this->list);
    }

    /**
     * Tested
     * Prepend one or more elements to the beginning of the [[list]] array
     * @param mixed ...$elements The values to prepend
     * @return  ArrayContainer object
     */
    public function unshift(...$elements): ArrayContainer
    {
        $itemsCount = count($elements);
        if ($itemsCount === 1) {
            array_unshift($this->list, $elements[0]);
            return $this;
        }
        $this->list = array_merge($elements, $this->list);
        return $this;
    }

    /**
     * Return all the keys of the [[list]] array
     * @return array an array of all the keys in the [[list]] array.
     */
    public function keys(): array
    {
        return array_keys($this->list);
    }

    /**
     * Iteratively reduce the [[list]] array to a single value using a callback function
     * @param callable $callable The callback function.
     * @param mixed|null $initial If the optional initial is available, it will
     * be used at the beginning of the process, or as a final result in case
     * the array is empty.
     * @return mixed|null the resulting value.
     */
    public function reduce(callable $callable, $initial = null)
    {
        return array_reduce($this->list, $callable, $initial);
    }

    /**
     * Calculate the product of values in the [[list]] array
     * @return mixed the resulting value.
     */
    public function product()
    {
        return array_product($this->list);
    }

    /**
     * Tested
     * Calculate the sum of values in the [[list]] array
     * @return int|float the resulting value.
     */
    public function sum()
    {
        return array_sum($this->list);
    }

    /**
     * Revers the[[list]] array elements
     * @param bool $preserveKeys If set to true numeric keys are preserved
     * @return $this
     */
    public function reverse(bool $preserveKeys = false): ArrayContainer
    {
        $this->list = array_reverse($this->list, $preserveKeys);
        return $this;
    }

    /**
     * Split the[[list]] array into chunks
     * @param int $length The size of each chunk
     * @param bool $preserveKeys When set to true keys will be preserved.
     * @return array a multidimensional numerically indexed array, starting with zero, with each dimension containing
     */
    public function chunk(int $length, bool $preserveKeys = false): array
    {
        return array_chunk($this->list, $length, $preserveKeys);
    }

    /**
     *  Sort the [[list]] array
     * @param callable|null $callable The callback function used to sort [[list]] array
     * @return $this
     */
    public function order(callable $callable): ArrayContainer
    {
        $this->list = $callable($this->list);
        return $this;
    }

    /**
     * Prints human-readable information about [[list]] array
     * @param bool $return true to capture the output of [[list]] array
     * @return true|string see php print_r function
     */
    public function printR(bool $return = false)
    {
        return print_r($this->list, $return);
    }

    /**
     * Return human-readable information about [[list]] array
     * @return string
     */
    public function __toString(): string
    {
        return $this->printR(true);
    }

    /**
     * Clone the ArrayContainer object to a new object
     * @return ArrayContainer
     */
    public function clone(): ArrayContainer
    {
        return clone $this;
    }

    /**
     * Tested
     * Count the [[list]] array elements
     * @return int
     */
    public function count(): int
    {
        return count($this->list);
    }

    /**
     * Iterates over each value in the [[list]] array
     * passing them to the callback function.
     * If the callback function returns true, the iteration will be stopped
     * @param callable $callable The callback function to use
     */
    public function forEach(callable $callable): void
    {
        $generator = $this->forEachGenerator();
        foreach ($generator as $index => $value) {
            if ($callable($value, $index) === false) {
                $generator->send(self::GENERATOR_SEND_STOP);
            }
        }
    }

    /**
     * Foreach generator method
     * @return Generator
     */
    protected function forEachGenerator(): Generator
    {
        foreach ($this->list as $index => $value) {
            $injected = yield $index => $value;
            if ($injected === self::GENERATOR_SEND_STOP) {
                break;
            }
        }
    }

    /**
     * Tested
     * Get the [[list]] array
     * @return array
     */
    public function getList(): array
    {
        return $this->list;
    }

    /**
     * Get the generator used in [[forEach]] method
     * @return Generator
     */
    public function getGenerator(): Generator
    {
        return  $this->forEachGenerator();
    }

    /**
     * @see PHP ArrayAccess
     * @link https://php.net/manual/en/class.arrayaccess.php
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->list[$offset]);
    }

    /**
     * @see PHP ArrayAccess
     * @link https://php.net/manual/en/class.arrayaccess.php
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return $this->list[$offset] ?? null;
    }

    /**
     * @see PHP ArrayAccess
     * @link https://php.net/manual/en/class.arrayaccess.php
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        if ($offset === null) {
            $this->list[] = $value;
        } else {
            $this->list[$offset] = $value;
        }
    }

    /**
     * @see PHP ArrayAccess
     * @link https://php.net/manual/en/class.arrayaccess.php
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->list[$offset]);
    }
}
