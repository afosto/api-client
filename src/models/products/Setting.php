<?php

namespace Afosto\ApiClient\Models\Products;

use Afosto\ApiClient\Models\_Base\Products\BaseSetting;

/**
 * Use this class for custom methods that extend the default functionality for 
 * the Setting
 * 
 * This object cannot be called directly from the api and has therefore no api
 * operations. It is only used to format and maintain the data.
 */
class Setting extends BaseSetting {

    
    public function getTypes() {
        return [
            ['external_service, position, menu_items','required'],
            ['position','integer'],
            ['slug','string'],
        ];
    }
}