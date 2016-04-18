<?php

namespace Afosto\ApiClient\Api;

use Afosto\ApiClient\Components\Component;
use Afosto\ApiClient\App;

class RateLimit extends Component {

    /**
     * The default limit
     * @var integer
     */
    public $limit;
    
    /**
     * The remaining limits
     * @var integer
     */
    public $remaining;
    
    /**
     * The time to reset (timestamp)
     * @var integer 
     */
    public $reset;

    /**
     * Constructor loads last know limits from cache
     */
    public static function load() {
        if (App::getInstance()->storage->get('rateLimits') !== false) {
            return App::getInstance()->storage->get('rateLimits');
        } else {
            return new self();
        }
    }

    /**
     * Store the new limits
     * @param integer $limit
     * @param integer $remaining
     * @param integer $reset Timestamp
     */
    public function update($limit, $remaining, $reset) {
        $this->limit = $limit;
        $this->remaining = $remaining;
        $this->reset = $reset;        
        App::getInstance()->storage->set('rateLimits', $this, $reset - time());
    }
    
    /**
     * Returns true when out of limits
     * @return boolean
     */
    public function hasExceeded() {
        if ($this->remaining === 0) {
            return true;
        }
        return false;
    }

    /**
     * Returns datetime
     * @return string
     */
    public function getDateTime() {
        if (is_null($this->reset)) {
            return null;
        } else {
            return date('Y/m/d H:i:s', $this->reset);
        }
    }
    
}
