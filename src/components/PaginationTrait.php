<?php

namespace Afosto\ApiClient\Components;

use Afosto\ApiClient\Api\Pagination;

trait PaginationTrait {
    
    /**
     * Holds the pagination
     * @var Pagination
     */
    private $_pagination;
    
    /**
     * Makes the models browse-able
     * @param integer $pageSize
     * @return Pagination
     */
    public function paginate($pageSize = null) {        
        $this->_pagination = new Pagination($this, $pageSize);
        return $this->_pagination;
    }
    
    /**
     * Get pagination object
     * @return Pagination
     */
    public function getPagination() {
        if (is_null($this->_pagination)) {
            $this->_pagination = new Pagination($this, null);
        }
        return $this->_pagination;
        
    }
}
