<?php

namespace Afosto\ApiClient\Components\Storage;

use Afosto\ApiClient\Components\Exceptions\StorageException;

class FileStorage extends AbstractStorage {

    /**
     * The filename extension for the storage
     */
    const EXTENSION = '.bin';
    
    /**
     * The path to a writable directory
     * @var string
     */
    private $_storageDirectory;
    
    /**
     * The key for this specific user
     * @var string
     */
    private $_userKey;

    /**
     * Get from the storage
     * @param string $key
     * @return boolean|string|array
     */
    public function get($key) {
        if (file_exists($this->_storageDirectory . '/' . $this->getStorageKey($key))) {
            if (filemtime($this->_storageDirectory . '/' . $this->getStorageKey($key)) < time()) {
                $this->flush($key);
            } else {
                return unserialize(file_get_contents($this->_storageDirectory . '/' . $this->getStorageKey($key)));
            }
        }
        return false;
    }
    
    /**
     * Returns the storage key as a filename
     * @param string $key
     * @return string
     */
    public function getStorageKey($key) {
        return parent::getStorageKey($key . $this->_userKey) . self::EXTENSION;
    }

    /**
     * Write to the storage
     * @param string $key
     * @param string $value
     * @param integer $expiry
     * @throws StorageException
     */
    public function set($key, $value, $expiry = 0) {
        if ($expiry == 0) {
            //Store for 10 years
            $expiry = 60 * 60 * 24 * 365 * 10;
        }
        if (file_exists($this->_storageDirectory . '/' . $this->getStorageKey($key))) {
            $this->flush($key);
        }
        if (file_put_contents($this->_storageDirectory . '/' . $this->getStorageKey($key), serialize($value)) === FALSE) {
            throw new StorageException('File storage failed');
        }
        touch($this->_storageDirectory . '/' . $this->getStorageKey($key), (time() + (int) $expiry));
    }

    /**
     * Flush a storage key
     * @param type $key
     */
    public function flush($key) {
        if (file_exists($this->_storageDirectory . '/' . $this->getStorageKey($key))) {
            unlink($this->_storageDirectory . '/' . $this->getStorageKey($key));
        }
    }

    /**
     * Sets the options
     * @throws StorageException
     */
    protected function init() {
        if (!isset($this->options['directory'])) {
            throw new StorageException('Option [directory] is required');
        }
        if (!isset($this->options['userKey'])) {
            throw new StorageException('Option [userKey] is required');
        }
        $this->_storageDirectory = realpath($this->options['directory']);
        if (!is_writable($this->_storageDirectory)) {
            throw new StorageException('Storage directory [' . $this->_storageDirectory . '] not writable');
        }
        $this->_userKey = $this->options['userKey'];
    }

}
