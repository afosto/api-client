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
 * @property string $name
 * @property integer $amount_ordered
 * @property integer $amount_invoiced
 * @property number $tax_rate
 * @property number $price
 * @property number $price_gross
 * @property number $subtotal
 * @property number $subtotal_gross
 * @property number $discount_rate
 * @property number $discount_value
 * @property number $discount_value_gross
 * @property number $discount_total
 * @property number $discount_total_gross
 *
 * @property \Afosto\ApiClient\Models\Products\Product $product
 * @property Mutation $mutation
**/
class BaseItem extends Model {

    /**
     * Array of attributes where the key is the local name, and the value is the original name
     * @return array 
     */
    public function getAttributes() {
        return [
            'id',
            'name',
            'amount_ordered',
            'amount_invoiced',
            'tax_rate',
            'price',
            'price_gross',
            'subtotal',
            'subtotal_gross',
            'discount_rate',
            'discount_value',
            'discount_value_gross',
            'discount_total',
            'discount_total_gross',
        ];
    }
    
    /**
     * Array with relations
     * @return array
     */
    public function getRelations() {
        return [
            'product' => ['ProductRel', 'one'],
            'mutation' => ['Mutation', 'one'],
        ];
    }

    /**
     * Array with types, rules
     * @return array
     */
    public function getTypes() {
        return [
            ['amount_ordered, amount_invoiced, tax_rate, product','required'],
            ['id, amount_ordered, amount_invoiced','integer'],
            ['name','string'],
            ['tax_rate, price, price_gross, subtotal, subtotal_gross, discount_rate, discount_value, discount_value_gross, discount_total, discount_total_gross','number'],
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