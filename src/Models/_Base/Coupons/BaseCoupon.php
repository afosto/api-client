<?php

namespace Afosto\ApiClient\Models\_Base\Coupons;

use Afosto\ApiClient\Components\Models\Model;
use Afosto\ApiClient\Components\ModelTrait;

/**
 * NOTE: Do not overwrite this model, as it is the base class and auto-generated 
 * by the generator in the dev folder, based on the SwaggerJSON for the Afosto 
 * API V2 (https://api.afosto.com/v2).
 * 
 * @category Class
 * @package  Afosto\ApiClient
 * @author   https://afosto.com
 * 
 * Copyright 2016 Afosto SaaS BV
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * 
 * @property integer $id
 * @property boolean $is_unlimited
 * @property integer $exchange_rate
 * @property number $exchange_value
 * @property integer $available
 * @property integer $used
 * @property string $expires_at
 * @property boolean $is_applicable_to_sale
 * @property boolean $is_verification_required
 * @property string $code
 * @property integer $label
 * 
 * @property \Afosto\ApiClient\Models\Customers\Customer $customer
**/
class BaseCoupon extends Model {

    use ModelTrait;

    /**
     * Array of attributes where the key is the local name, and the value is the original name
     * @return array 
     */
    public function getAttributes() {
        return [
            'id',
            'is_unlimited',
            'exchange_rate',
            'exchange_value',
            'available',
            'used',
            'expires_at',
            'is_applicable_to_sale',
            'is_verification_required',
            'code',
            'label',
        ];
    }
    
    /**
     * Array with relations
     * @return array
     */
    public function getRelations() {
        return [
            'customer' => ['CustomerRel', 'one'],
        ];
    }

    /**
     * Array with types, rules
     * @return array
     */
    public function getTypes() {
        return [
            ['id, code, ','required'],
            ['id, exchange_rate, available, used, label','integer'],
            ['is_unlimited, is_applicable_to_sale, is_verification_required','boolean'],
            ['exchange_value','number'],
            ['expires_at, code','string'],
        ];
    }

    /**
     * Map model data
     * @return array
     */
    protected function getMapping() {
        return array_merge([], parent::getMapping());
    }
}