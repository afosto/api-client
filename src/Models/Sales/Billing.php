<?php

namespace Afosto\ApiClient\Models\Sales;

use Afosto\ApiClient\Models\_Base\Sales\BaseBilling;

/**
 * Use this class for custom methods that extend the default functionality for
 * the Billing
 *
 * This object cannot be called directly from the api and has therefore no api
 * operations. It is only used to format and maintain the data.
 */
class Billing extends BaseBilling {

    /**
     * Array with relations
     * @return array
     */
    public function getRelations() {
        return array_merge(parent::getRelations(), [
            'methods' => ['PaymentMethods', 'many'],
            'address' => ['//Address', 'one'],
        ]);
    }
}