<?php

namespace Afosto\ApiClient\Components\Models;

class ErrorResponse extends Model {


    /**
     * Return a new model
     * @return static
     */
    public static function model() {
        return new static();
    }

    /**
     * Array of attributes where the key is the local name, and the value is the original name
     * @return array
     */
    public function getAttributes() {
        return [
            'message',
            'status',
            'version',
            'documentation',
        ];
    }

    /**
     * Array with relations
     * @return array
     */
    public function getRelations() {
        return [];
    }

    /**
     * Array with types, rules
     * @return array
     */
    public function getTypes() {
        return [
            ['message, status, version, documentation','required'],
            ['message, status, version, documentation','string'],
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