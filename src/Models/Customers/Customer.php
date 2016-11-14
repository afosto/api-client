<?php

namespace Afosto\ApiClient\Models\Customers;

use Afosto\ApiClient\Models\_Base\Customers\BaseCustomer;

/**
 * Use this class for custom methods that extend the default functionality for
 * the Customer
 *
 * This class can interact with api, through methods like find and save.
 */
class Customer extends BaseCustomer {

    public function getAttributes() {
        return array_merge([
            'name',
        ], parent::getAttributes());
    }

    /**
     * Array with relations
     * @return array
     */
    public function getRelations() {
        return array_merge(parent::getRelations(), [
            'billing_address'  => ['//Address', 'one'],
            'shipping_address' => ['//Address', 'one'],
            'extra_fields' => ['//EAV', 'many'],
        ]);
    }

}