<?php

namespace Afosto\ApiClient\Models\Products;

use Afosto\ApiClient\Models\_Base\Products\BaseProduct;
use Afosto\ApiClient\Components\WebhookTrait;

/**
 * Use this class for custom methods that extend the default functionality for 
 * the Product
 * 
 * This class can interact with api, through methods like find and save.
 */
class Product extends BaseProduct {
    
    use WebhookTrait;

     /**
     * Array with types, rules
     * @return array
     */
    public function getTypes() {
        return [
            ['is_tracking_inventory, descriptors, items, collections, settings','required'],
            ['id, weight','integer'],
            ['cost','number'],
            ['is_tracking_inventory','boolean'],
            ['created_at, updated_at','string'],
        ];
    }
}