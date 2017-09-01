<?php

namespace Afosto\ApiClient\Models\_Base\Products;

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
 * @property string $ean
 * @property string $sku
 * @property string $suffix
 * @property number $cost
 * @property string $created_at
 * @property string $updated_at
 *
 * @property \Afosto\ApiClient\Models\Products\Inventory $inventory
 * @property \Afosto\ApiClient\Models\Products\Price[] $prices
 * @property \Afosto\ApiClient\Models\Products\Option[] $options
**/
class BaseItem extends Model {

    /**
     * Array of attributes where the key is the local name, and the value is the original name
     * @return array 
     */
    public function getAttributes() {
        return [
            'id',
            'ean',
            'sku',
            'suffix',
            'cost',
            'created_at',
            'updated_at',
        ];
    }
    
    /**
     * Array with relations
     * @return array
     */
    public function getRelations() {
        return [
            'inventory' => ['Inventory', 'one'],
            'prices' => ['Price', 'many'],
            'options' => ['Option', 'many'],
        ];
    }

    /**
     * Array with types, rules
     * @return array
     */
    public function getTypes() {
        return [
            ['ean, created_at, updated_at, inventory, prices, options, ','required'],
            ['id','integer'],
            ['ean, sku, suffix, created_at, updated_at','string'],
            ['cost','number'],
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