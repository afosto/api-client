<?php

namespace Afosto\ApiClient\Components\Storage;

class ArrayStorage extends AbstractStorage
{
    /**
     * @var array
     */
    private $store = [];

    /**
     * @param string $key
     */
    public function flush($key)
    {
        if ($this->get($key) !== false) {
            unset($this->store[$key]);
        }
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        if (array_key_exists($key, $this->store)) {
            if ($this->store[$key]['expiry'] < time()) {
                return unserialize($this->store[$key]['value']);
            }
        }
        return false;
    }

    /**
     * @param string $key
     * @param string $value
     * @param integer $expiry
     */
    public function set($key, $value, $expiry = 0)
    {
        $this->store[$key] = [
            'value' => serialize($value),
            'expiry' => time() + $expiry,
        ];
    }

    /**
     * Prepare the session
     */
    protected function init()
    {
        // NO-OP
    }
}