<?php

namespace Afosto\ApiClient\Models\Sales;

use Afosto\ApiClient\Models\_Base\Sales\BaseSale;
use Afosto\ApiClient\Components\WebhookTrait;

/**
 * Use this class for custom methods that extend the default functionality for 
 * the Sale
 * 
 * This class can interact with api, through methods like find and save.
 */
class Sale extends BaseSale {

    use WebhookTrait;

    public function getAttributes() {
        return array_merge([
            'status_id'
        ], parent::getAttributes());
    }
    
}