<?php

namespace Afosto\ApiClient\Models\Addresses;

use Afosto\ApiClient\Models\_Base\Addresses\BaseAddress;

/**
 * Use this class for custom methods that extend the default functionality for 
 * the Address
 * 
 * This object cannot be called directly from the api and has therefore no api
 * operations. It is only used to format and maintain the data.
 */
class Address extends BaseAddress {

    public function getAttributes() {
        return array_merge([
            'email'
        ], parent::getAttributes());
    }
    
}