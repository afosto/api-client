<?php

namespace Afosto\ApiClient\Models\EAVs;

use Afosto\ApiClient\Models\_Base\EAVs\BaseEAV;

/**
 * Use this class for custom methods that extend the default functionality for
 * the EAV
 *
 * This object cannot be called directly from the api and has therefore no api
 * operations. It is only used to format and maintain the data.
 */
class EAV extends BaseEAV {

    /**
     * Returns the attributes
     * @return array
     */
    public function getAttributes() {
        return array_merge(parent::getAttributes(), [
            'name',
        ]);
    }

}