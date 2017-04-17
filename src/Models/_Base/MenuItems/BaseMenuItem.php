<?php

namespace Afosto\ApiClient\Models\_Base\MenuItems;

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
 * @property string $url
 * @property string $label
 * @property integer $position
 * @property string $sort
 * 
 * @property \Afosto\ApiClient\Models\Menus\Menu $menu
 * @property \Afosto\ApiClient\Models\Collections\Collection[] $categories
 * @property \Afosto\ApiClient\Models\MenuItems\MenuItem[] $children
**/
class BaseMenuItem extends Model {

    use ModelTrait;

    /**
     * Array of attributes where the key is the local name, and the value is the original name
     * @return array 
     */
    public function getAttributes() {
        return [
            'id',
            'url',
            'label',
            'position',
            'sort',
        ];
    }
    
    /**
     * Array with relations
     * @return array
     */
    public function getRelations() {
        return [
            'menu' => ['MenuRel', 'one'],
            'categories' => ['CollectionRel', 'many'],
            'children' => ['MenuItem', 'many'],
        ];
    }

    /**
     * Array with types, rules
     * @return array
     */
    public function getTypes() {
        return [
            ['','required'],
            ['id, position','integer'],
            ['url, label, sort','string'],
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