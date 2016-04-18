<?php

namespace Afosto\ApiClient\Components\Storage;

abstract class AbstractStorage {


    /**
     * Options
     * @var array 
     */
    protected $options;

    /**
     * Constructor
     * @param array $options
     */
    public function __construct(array $options = array()) {
        $this->options = $options;   
        $this->init();
    }

    /**
     * Write to the cache
     * @param string $key
     * @param string|array $value
     */
    abstract public function set($key, $value, $expiry = 0);

    /**
     * Read from the cache
     */
    abstract public function get($key);
    
    /**
     * Flush from the cache
     */
    abstract public function flush($key);
    
    /**
     * Called after constructor
     */
    abstract protected function init();

    /**
     * Returns the user based cache key
     * @return string
     */
    protected function getStorageKey($key) {
        return md5($key);
    }

}
