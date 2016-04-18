<?php

namespace Afosto\ApiClient\Models\Collections;

use Afosto\ApiClient\Models\_Base\Collections\BaseCollection;

/**
 * Use this class for custom methods that extend the default functionality for 
 * the Collection
 * 
 * This class can interact with api, through methods like find and save.
 */
class Collection extends BaseCollection {
    
    public function getAttributes() {
        return array_merge([
            'created_at',
            'updated_at'
        ], parent::getAttributes());
    }
}