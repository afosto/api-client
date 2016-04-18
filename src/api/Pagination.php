<?php

namespace Afosto\ApiClient\Api;

use Afosto\ApiClient\Components\Component;
use Afosto\ApiClient\Components\Models\Model;
use Afosto\ApiClient\App;

/**
 * @property integer $pageSize
 */
class Pagination extends Component implements \Iterator, \Countable {

    /**
     * The current page
     * @var integer
     */
    public $page;

    /**
     * The amount of items on the page
     * @var integer
     */
    private $_pageSize;

    /**
     * The amount of available models
     * Only filled in a findAll request
     * @var integer
     */
    private $_totalCount;

    /**
     * The name of the model, used as storage key
     * @var Model
     */
    private $_owner;

    /**
     * Pagination constructor.
     * @param Model|\Afosto\ApiClient\Resources\Model $owner
     * @param int $pageSize
     * @throws \Afosto\ApiClient\Exceptions\ApiException
     */
    public function __construct(Model $owner, $pageSize = PAGE_SIZE) {
        $this->_owner = $owner;
        $this->rewind();
        $this->setPageSize($pageSize);
        if (($count = App::getInstance()->storage->get('pagination' . $this->_owner->getName())) !== false) {
            $this->_totalCount = (int) $count;
        }
    }

    /**
     * Sets the size
     * @param integer $size
     */
    public function setPageSize($size) {
        if ($size === null || (int)$size == 0) {
            $this->_pageSize = PAGE_SIZE;
        } else {
            $this->_pageSize = (int) $size;
        }
    }
    
    /**
     * Returns the pageSize
     * @return integer
     */
    public function getPageSize() {
        return $this->_pageSize;
    }

    /**
     * Update the totalCount
     * @param integer $totalCount
     */
    public function update($totalCount) {
        $this->_totalCount = (int) $totalCount;
        App::getInstance()->storage->set('pagination' . $this->_owner->getName(), $this->_totalCount);
    }

    /**
     * Return the current page
     * @return static[]
     */
    public function current() {
        return $this->_owner->findPage();
    }

    /**
     * Array key
     * @return integer
     */
    public function key() {
        return $this->page - 1;
    }

    /**
     * Reset the array
     */
    public function rewind() {
        $this->page = 1;
    }

    /**
     * Returns true when page exists
     * @return boolean
     */
    public function valid() {
        if ((($this->page * $this->pageSize) - $this->pageSize) < $this->getTotalCount()) {
            return true;
        }
        return false;
    }

    /**
     * Go to the next page
     */
    public function next() {
        $this->page++;
    }

    /**
     * Returns the totalCount
     * @return integer
     */
    public function getTotalCount() {
        if (is_null($this->_totalCount)) {
            $count = $this->_owner->findCount();
            $this->_totalCount = $count->total;
        }
        return $this->_totalCount;
    }

    /**
     * Return the total page count in respect to the pageSize
     * @param string $mode
     * @return int
     */
    public function count($mode = 'COUNT_NORMAL') {
        return (int) ceil($this->getTotalCount() / $this->pageSize);
    }

}
