<?php

namespace Afosto\ApiClient\Models\Sales;

use Afosto\ApiClient\Models\_Base\Sales\BasePaymentMethods;

/**
 * Use this class for custom methods that extend the default functionality for 
 * the PaymentMethods
 * 
 * This object cannot be called directly from the api and has therefore no api
 * operations. It is only used to format and maintain the data.
 */
class PaymentMethods extends BasePaymentMethods {

    /**
     * Array with relations
     * @return array
     */
    public function getRelations() {
        return [
            'method' => ['PaymentMethodRel', 'one'],
        ];
    }
    
}