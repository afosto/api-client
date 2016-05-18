<?php

namespace Afosto\ApiClient\Components\Storage;

class SessionStorage extends AbstractStorage {

    /**
     * Remove from storage
     * @param string $key
     */
    public function flush($key) {
        if ($this->get($key) !== false) {
            unset($_SESSION[$this->getStorageKey($key)]);
        }
    }

    /**
     * Get from storage
     * @param string $key
     * @return boolean
     */
    public function get($key) {
        if (array_key_exists($this->getStorageKey($key), $_SESSION)) {
            if ($_SESSION[$this->getStorageKey($key)]['expiry'] == 0 || $_SESSION[$this->getStorageKey($key)]['expiry'] < time()) {
                return unserialize($_SESSION[$this->getStorageKey($key)]['value']);
            }
        }
        return false;
    }

    /**
     * Retrieve from storage
     * @param string $key
     * @param string $value
     * @param integer $expiry
     */
    public function set($key, $value, $expiry = 0) {
        $_SESSION[$this->getStorageKey($key)]['value'] = serialize($value);
        $_SESSION[$this->getStorageKey($key)]['expiry'] = time() + $expiry;
    }

    /**
     * Prepare the session
     */
    protected function init() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

}
