<?php

namespace Afosto\ApiClient\Models\_Base\Imports;

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
 * @property string $filename
 * @property string $delimiter
 * @property string $sub_delimiter
 * @property boolean $is_update
 * @property boolean $is_update_child_only
 * @property string $match_key
 * @property boolean $is_ready
 * @property integer $last_row
 * @property integer $total_rows
 * @property integer $successful_rows
 * @property integer $cycles
 * @property number $average_cycle_duration
 * @property boolean $is_finished
 * @property boolean $is_undone
 * @property string $created_at
 * @property string $updated_at
 * 
 * @property \Afosto\ApiClient\Models\Warehouses\Warehouse $warehouse
**/
class BaseImport extends Model {

    use ModelTrait;

    /**
     * Array of attributes where the key is the local name, and the value is the original name
     * @return array 
     */
    public function getAttributes() {
        return [
            'id',
            'name',
            'filename',
            'delimiter',
            'sub_delimiter',
            'is_update',
            'is_update_child_only',
            'match_key',
            'is_ready',
            'last_row',
            'total_rows',
            'successful_rows',
            'cycles',
            'average_cycle_duration',
            'is_finished',
            'is_undone',
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
            'warehouse' => ['WarehouseRel', 'one'],
        ];
    }

    /**
     * Array with types, rules
     * @return array
     */
    public function getTypes() {
        return [
            ['id, name','required'],
            ['id, last_row, total_rows, successful_rows, cycles','integer'],
            ['name, filename, delimiter, sub_delimiter, match_key, created_at, updated_at','string'],
            ['is_update, is_update_child_only, is_ready, is_finished, is_undone','boolean'],
            ['average_cycle_duration','number'],
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