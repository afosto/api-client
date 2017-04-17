<?php

namespace Afosto\ApiClient\Models\_Base\Sales;

use Afosto\ApiClient\Components\Models\Model;

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
 * @property string $number
 * @property string $billed_at
 * @property string $payment_date
 * @property boolean $is_notified
 * @property integer $status_id
 * @property string $status
 * @property number $total_paid
 *
 * @property \Afosto\ApiClient\Models\Sales\PaymentMethods[] $methods
 * @property \Afosto\ApiClient\Models\Addresses\Address $address
**/
class BaseBilling extends Model {

    /**
     * Array of attributes where the key is the local name, and the value is the original name
     * @return array 
     */
    public function getAttributes() {
        return [
            'id',
            'number',
            'billed_at',
            'payment_date',
            'is_notified',
            'status_id',
            'status',
            'total_paid',
        ];
    }
    
    /**
     * Array with relations
     * @return array
     */
    public function getRelations() {
        return [
            'methods' => ['PaymentMethods', 'many'],
            'address' => ['Address', 'one'],
        ];
    }

    /**
     * Array with types, rules
     * @return array
     */
    public function getTypes() {
        return [
            ['billed_at, payment_date, is_notified, status, total_paid, address','required'],
            ['id, status_id','integer'],
            ['number, billed_at, payment_date, status','string'],
            ['is_notified','boolean'],
            ['total_paid','number'],
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