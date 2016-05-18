<?php

namespace Afosto\ApiClient\Generator;

use Doctrine\Common\Inflector\Inflector;

class Definition {

    /**
     * The modelname
     * @var string
     */
    public $name;
    
    /**
     * The attributes for this model
     * @var array
     */
    public $attributes;
    
    /**
     * The namespace
     * @var string
     */
    public $namespace;
    
    /**
     * Relations for this model
     * @var array
     */
    public $relations;
    
    /**
     * Required attributes
     * @var array
     */
    public $required;
    
    /**
     * The targeted rel class name
     * @var string
     */
    public $propertyClass;
    
    /**
     * Array of attributes grouped by type
     * @var array
     */
    public $types;

    /**
     * Constructor
     * @param string $name
     * @param array $definitions
     */
    public function __construct($name, $definitions) {
        $this->name = $this->_getName($name);
        $this->propertyClass = $this->_getPropertyClass($this->name);
        $this->namespace = $this->_getNameSpace($name);
        $this->_formatAttributes($definitions);
    }

    /**
     * Returns the simplified array
     * @return array
     */
    public function getArray() {
        return json_decode(json_encode($this));
    }

    /**
     * Format the attributes into relations
     * @param array $definitions
     */
    private function _formatAttributes($definitions) {
        foreach ($definitions['properties'] as $attribute => $definition) {

            if ($attribute == '_links') {
                continue;
            }
            if (array_key_exists('$ref', $definition)) {
                //Relation
                $this->relations[] = [
                    'key' => $attribute,
                    'class' => $this->_getName(str_replace('#/definitions/', '', $definition['$ref'])),
                    'propertyClass' => $this->_getPropertyClass($this->_getName(str_replace('#/definitions/', '', $definition['$ref']))),
                    'namespace' => $this->_getNameSpace(str_replace('#/definitions/', '', $definition['$ref'])),
                    'needsNamespace' => (($this->_getNameSpace(str_replace('#/definitions/', '', $definition['$ref'])) == $this->namespace) ? false : true),
                    'type' => 'one'
                ];
            } else if (array_key_exists('items', $definition) && array_key_exists('$ref', $definition['items'])) {
                //Internal relation (items)
                $this->relations[] = [
                    'key' => $attribute,
                    'type' => $definition['type'] == 'array' ? 'many' : 'one',
                    'array' => $definition['type'] == 'array' ? true : false,
                    'class' => $this->_getName(str_replace('#/definitions/', '', $definition['items']['$ref'])),
                    'propertyClass' => $this->_getPropertyClass($this->_getName(str_replace('#/definitions/', '', $definition['items']['$ref']))),
                    'namespace' => $this->_getNameSpace(str_replace('#/definitions/', '', $definition['items']['$ref'])),
                    'needsNamespace' => (($this->_getNameSpace(str_replace('#/definitions/', '', $definition['items']['$ref'])) == $this->namespace) ? false : true)
                ];
            } else {
                //Property
                $this->attributes[] = [
                    'key' => $attribute,
                    'type' => $definition['type']
                ];
                $this->types[$definition['type']]['attributes'][] = $attribute;
                $this->types[$definition['type']]['type'] = $definition['type'];
            }
        }
        
        $this->_formatTypes();
        
        if (isset($definitions['required'])) {
            foreach ($definitions['required'] as $key => $definition) {
                if ($definition == '_links') {
                    continue;
                }
                $this->required[] = [
                    'key' => $definition,
                    'comma' => !(count($definitions['required']) == ($key + 1))
                ];
            }
        }
    }

    /**
     * Returns the modelname
     * @param string $name
     * @return string
     */
    private function _getName($name) {
        if (substr($name, -3) == "Rel") {
            return str_replace('_', '', $name);
        }
        return end(explode('_', $name));
    }

    /**
     * Returns the namespace for this model
     * @param string $name
     * @return string
     */
    private function _getNameSpace($name) {
        return Inflector::pluralize(current(explode('_', $name)));
    }
    
    /**
     * Returns the targeted model name
     * @param type $name
     * @return type
     */
    private function _getPropertyClass($name) {
        if (substr($name, -3) == "Rel") {
            return substr($name, 0, -3);
        }
        return $name;
    }

    /**
     * Format the types (simplify the data for mustache)
     */
    private function _formatTypes() {
        $types = array();
        foreach ($this->types as $type => $values) {
            $types[] = [
                'attributes' => implode(', ', $values['attributes']),
                'type' => $type
            ];
        }
        $this->types = $types;
    }

}
