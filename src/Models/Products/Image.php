<?php

namespace Afosto\ApiClient\Models\Products;

use Afosto\ApiClient\Models\_Base\Products\BaseImage;

/**
 * Use this class for custom methods that extend the default functionality for 
 * the Image
 * 
 * This object cannot be called directly from the api and has therefore no api
 * operations. It is only used to format and maintain the data.
 */
class Image extends BaseImage {

    public function getAttributes() {
        return array_merge([
            'is_default'
        ], parent::getAttributes());
    }
    
}