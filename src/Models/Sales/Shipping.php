<?php

namespace Afosto\ApiClient\Models\Sales;

use Afosto\ApiClient\Models\_Base\Sales\BaseShipping;

/**
 * Use this class for custom methods that extend the default functionality for
 * the Shipping
 *
 * This object cannot be called directly from the api and has therefore no api
 * operations. It is only used to format and maintain the data.
 */
class Shipping extends BaseShipping {

    /**
     * Array with relations
     * @return array
     */
    public function getRelations() {
        return array_merge(parent::getRelations(),[
            'address' => ['//Address', 'one'],
            'method' => ['ShippingMethodRel', 'one'],
        ]);
    }

}