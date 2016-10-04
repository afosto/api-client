<?php

namespace Afosto\ApiClient\Models\Customers;

use Afosto\ApiClient\Models\_Base\Customers\BasePhonenumber;

/**
 * Use this class for custom methods that extend the default functionality for 
 * the Phonenumber
 * 
 * This object cannot be called directly from the api and has therefore no api
 * operations. It is only used to format and maintain the data.
 */
class Phonenumber extends BasePhonenumber {

    public function getAttributes() {
        return array_merge([
            'number'
        ], parent::getAttributes());
    }

}