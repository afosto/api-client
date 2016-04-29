<?php

namespace Afosto\ApiClient\Models\_Base\Sales;

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
 * @property integer $status_id
 * @property string $status
 * @property string $vat
 * @property boolean $is_notified
 * @property string $billing_number
 * @property string $order_number
 * @property number $payment_costs_gross
 * @property number $shipping_costs_gross
 * @property number $discount_total_gross
 * @property number $subtotal_gross
 * @property number $tax_total
 * @property number $total
 * @property string $ordered_at
 * @property string $created_at
 * @property string $updated_at
 * @property array $sale_notes
 * 
 * @property \Afosto\ApiClient\Models\ExternalServices\ExternalService $external_service
 * @property Billing $billing
 * @property Shipping[] $shipping
 * @property Item[] $items
 * @property \Afosto\ApiClient\Models\Customers\Customer $customer
**/
class BaseSale extends Model {

    use ModelTrait;

    /**
     * Array of attributes where the key is the local name, and the value is the original name
     * @return array 
     */
    public function getAttributes() {
        return [
            'id',
            'status_id',
            'status',
            'vat',
            'is_notified',
            'billing_number',
            'order_number',
            'payment_costs_gross',
            'shipping_costs_gross',
            'discount_total_gross',
            'subtotal_gross',
            'tax_total',
            'total',
            'ordered_at',
            'created_at',
            'updated_at',
            'sale_notes',
        ];
    }
    
    /**
     * Array with relations
     * @return array
     */
    public function getRelations() {
        return [
            'external_service' => ['ExternalServiceRel', 'one'],
            'billing' => ['Billing', 'one'],
            'shipping' => ['Shipping', 'many'],
            'items' => ['Item', 'many'],
            'customer' => ['CustomerRel', 'one'],
        ];
    }

    /**
     * Array with types, rules
     * @return array
     */
    public function getTypes() {
        return [
            ['status, subtotal_gross, total, tax_total, billing, items','required'],
            ['id, status_id','integer'],
            ['status, vat, billing_number, order_number, ordered_at, created_at, updated_at','string'],
            ['is_notified','boolean'],
            ['payment_costs_gross, shipping_costs_gross, discount_total_gross, subtotal_gross, tax_total, total','number'],
            ['sale_notes','array'],
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