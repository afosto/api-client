<?php

namespace Afosto\ApiClient\Models\_Base\ExternalServices;

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
 * @property string $name
 * @property string $domain
 * @property string $description
 * @property boolean $is_active
 * @property boolean $is_ssl
 * @property boolean $is_test_mode
 * @property boolean $is_password_protected
 * @property boolean $is_setup_completed
 * @property boolean $completed
 * @property string $vat
 * @property string $created_at
 * @property string $updated_at
 * 
 * @property \Afosto\ApiClient\Models\Modules\Module $module
 * @property \Afosto\ApiClient\Models\PriceGroups\PriceGroup $price_group
 * @property \Afosto\ApiClient\Models\MetaGroups\MetaGroup $meta_group
 * @property \Afosto\ApiClient\Models\ExternalServices\Settings $settings
**/
class BaseExternalService extends Model {

    use ModelTrait;

    /**
     * Array of attributes where the key is the local name, and the value is the original name
     * @return array 
     */
    public function getAttributes() {
        return [
            'id',
            'name',
            'domain',
            'description',
            'is_active',
            'is_ssl',
            'is_test_mode',
            'is_password_protected',
            'is_setup_completed',
            'completed',
            'vat',
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
            'module' => ['ModuleRel', 'one'],
            'price_group' => ['PriceGroupRel', 'one'],
            'meta_group' => ['MetaGroupRel', 'one'],
            'settings' => ['Settings', 'one'],
        ];
    }

    /**
     * Array with types, rules
     * @return array
     */
    public function getTypes() {
        return [
            ['id, name, description','required'],
            ['id','integer'],
            ['name, domain, description, vat, created_at, updated_at','string'],
            ['is_active, is_ssl, is_test_mode, is_password_protected, is_setup_completed, completed','boolean'],
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