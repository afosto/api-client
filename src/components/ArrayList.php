<?php

namespace Afosto\ApiClient\Components;

use Afosto\ApiClient\Components\Exceptions\ModelException;
use Afosto\ApiClient\Components\Models\Model;

class ArrayList implements \ArrayAccess, \Iterator, \Countable {

    /**
     * Internal data object
     * @var array
     */
    private $_data;

    /**
     * Pointer for iterator
     * @var int
     */
    private $_key;

    /**
     * ArrayList constructor.
     */
    public function __construct() {
        $this->_data = [];
        $this->_key = 0;
    }

    /**
     * Test if offset is available
     * @param mixed $offset
     * @return mixed
     */
    public function offsetExists ($offset) {
        return array_key_exists($offset, $this->_data);
    }

    /**
     * Retrieve the offset
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet ($offset) {
        return $this->_data[$offset];
    }

    /**
     * Set the data
     * Handle NULL by incrementing accordingly to the count, to ensure the data is compact
     * @param mixed $offset
     * @param mixed $value
     * @throws ModelException
     */
    public function offsetSet ($offset, $value) {
        if ($offset === null) {
            $offset = count($this->_data);
        } else {
            throw new ModelException('Use only NULL offsets are allowed to insert data');
        }
        $this->_data[$offset] = $value;
    }

    /**
     * Unset a value
     * @param mixed $offset
     */
    public function offsetUnset ($offset) {
        unset($this->_data[$offset]);
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current() {
        return $this->_data[$this->_key];
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next() {
        $this->_key++;
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key() {
        return $this->_key;
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid() {
        return array_key_exists($this->_key, $this->_data);
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind() {
        $this->_key = 0;
    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count() {
        return count($this->_data);
    }

    /**
     * Return true when this list has models
     * @return bool
     */
    public function hasModels() {
        if ($this->count() > 0 && $this->current() instanceof Model) {
            return true;
        }
        return false;
    }
    
    /**
     * Reset the values
     */
    public function reset() {
        $this->_data = [];
        $this->_key = 0;
    }
}
