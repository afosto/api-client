<?php

namespace Afosto\ApiClient\Models\Suppliers;

use Afosto\ApiClient\Models\_Base\Suppliers\BaseSupplier;

/**
 * Use this class for custom methods that extend the default functionality for 
 * the Supplier
 * 
 * This class can interact with api, through methods like find and save.
 */
class Supplier extends BaseSupplier {
    
    /**
     * Array with types, rules
     * @return array
     */
    public function getTypes() {
        return [
            ['name','required'],
            ['id','integer'],
            ['name','string'],
            ['is_dropshipment','boolean'],
        ];
    }
    
}