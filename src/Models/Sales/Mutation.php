<?php

namespace Afosto\ApiClient\Models\Sales;

use Afosto\ApiClient\Models\_Base\Sales\BaseMutation;

/**
 * Use this class for custom methods that extend the default functionality for 
 * the Mutation
 * 
 * This object cannot be called directly from the api and has therefore no api
 * operations. It is only used to format and maintain the data.
 */
class Mutation extends BaseMutation {

    public function getAttributes() {
        return array_merge([
            'shipment_id'
        ], parent::getAttributes());
    }
    
}