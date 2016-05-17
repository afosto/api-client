<?php

namespace Afosto\ApiClient\Models\Sales;

use Afosto\ApiClient\Models\_Base\Sales\BaseItem;

/**
 * Use this class for custom methods that extend the default functionality for 
 * the Item
 * 
 * This object cannot be called directly from the api and has therefore no api
 * operations. It is only used to format and maintain the data.
 */
class Item extends BaseItem {

    public function getRelations() {
        return array_merge([
            'shipment' => ['Shipment', 'one']
                ], parent::getRelations());
    }
    
}