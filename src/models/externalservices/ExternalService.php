<?php

namespace Afosto\ApiClient\Models\ExternalServices;

use Afosto\ApiClient\Models\_Base\ExternalServices\BaseExternalService;

/**
 * Use this class for custom methods that extend the default functionality for 
 * the ExternalService
 * 
 * This class can interact with api, through methods like find and save.
 */
class ExternalService extends BaseExternalService {
    
     public function getAttributes() {
        return array_merge([
            'price_group_id',
            'meta_group_id',
            'is_ssl',
            'is_test_mode',
            'is_password_protected',
            'is_setup_completed'
            ], parent::getAttributes());
    }
    
}