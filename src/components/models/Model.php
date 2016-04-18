<?php

namespace Afosto\ApiClient\Components\Models;

use Afosto\ApiClient\Components\Exceptions\ModelException;
use Afosto\ApiClient\Components\Component;

use Afosto\ApiClient\Components\ArrayList;
use Afosto\ApiClient\Components\Helpers\ApiHelper;
use Symfony\Component\VarDumper\VarDumper;

abstract class Model extends Component {

    /**
     * The list of attributes
     * @var array
     */
    private $_attributes = [];

    /**
     * Contains the relations for this model
     * @var Relation[]
     */
    private $_relations = [];

    /**
     * Rules for validation
     * @var array
     */
    private $_rules = [];

    /**
     * Returns the simple formatted relations
     */
    abstract public function getRelations();

    /**
     * Returns the simple formatted types
     */
    abstract public function getTypes();

    /**
     * Returns the simple formatted attributes
     */
    abstract public function getAttributes();

    /**
     * Constructor
     */
    public function __construct() {
        $this->_setMetaData();
    }

    /**
     * PHP getter magic method
     * @param string $name 
     * @return mixed
     */
    public function __get($name) {
        if (array_key_exists($name, $this->_attributes)) {
            if ($this->_attributes[$name] instanceof Link) {
                //Browse the API for the link
                $this->_attributes[$name] = $this->_attributes[$name]->getLink();
            } else if (is_array($this->_attributes[$name]) && !empty($this->_attributes[$name]) && current($this->_attributes[$name]) instanceof Link) {
                //Browse the API for all the links
                foreach ($this->_attributes[$name] as $key => $link) {
                    $this->_attributes[$name][$key] = $this->_attributes[$name][$key]->getLink();
                }
            }
            return $this->_attributes[$name];
        } else if (array_key_exists($name, $this->_relations)) {
            if ($this->_relations[$name]->type == 'many') {
                return array();
            } else {
                return null;
            }
        } else if (array_key_exists($name, $this->getMapping())) {
            $mappedKey = $this->getMapping()[$name];
            return $this->$mappedKey;
        } else {
            return parent::__get($name);
        }
    }

    /**
     * PHP setter magic method
     * @param string $name
     * @param string $value
     * @return bool|mixed
     * @throws ModelException
     */
    public function __set($name, $value) {
        if (($result = $this->setAttribute($name, $value)) === false) {
            return parent::__set($name, $value);
        }
        return $result;
    }

    /**
     * Set the attribute for the model
     * @param $name
     * @param $value
     * @return bool|mixed
     */
    public function setAttribute($name, $value) {
        if ($value === null || $name == '_links') {
            return true;
        } else if (array_key_exists($name, $this->_relations) && $this->_relations[$name]->type == 'many') {
            //Many relation
            $relation = $this->_relations[$name];
            foreach ($value as $data) {
                $object = new $relation->classPath;
                $object->setAttributes($data);
                $this->_attributes[$name][] = $object;
            }
        } else if (array_key_exists($name, $this->_relations) && $this->_relations[$name]->type == 'one') {
            //Single relation
            $relation = $this->_relations[$name];
            $object = new $relation->classPath;
            $object->setAttributes($value);
            $this->_attributes[$name] = $object;
        } else if (array_key_exists($name, $this->_attributes)) {
            $this->_attributes[$name] = $value;
        } else if (array_key_exists($name, $this->getMapping())) {
            $mappedKey = $this->getMapping()[$name];
            $this->$mappedKey = $value;
        } else {
            return false;
        }
        return true;
    }

    /**
     * Set an array of attributes for this model
     * @param $attributes
     * @return $this
     * @throws ModelException
     */
    public function setAttributes($attributes) {
        if (!is_array($attributes)) {
            throw new ModelException('Invalid attributes for [' . $this->getName() . ']');
        }
        foreach ($attributes as $name => $value) {
            $this->$name = $value;
        }
        return $this;
    }

    /**
     * Validate the model
     * @throws ModelException
     */
    public function validate() {        
        foreach ($this->_attributes as $attribute => $value) {
            if (in_array('required', $this->getAttributeRules($attribute))) {
                //Validate based on requirement
                if ($value instanceof Model) {
                    //Single relation object
                    $value->validate();
                } else if ($value instanceof ArrayList && $value->hasModels()) {
                    //Many relation object
                    foreach ($value as $object) {
                        $object->validate();
                    }

                } else if (is_null($value) || trim($value) == '') {
                    throw new ModelException("[$attribute] is required for [" . $this->getName() . "]");
                }
            }
        }
    }

    /**
     * Return the attributes of this class in an assoc array
     * @return array
     */
    public function getBody() {
        $body = [];
        foreach ($this->_attributes as $key => $attribute) {
            if ($attribute instanceof ArrayList) {
                foreach ($attribute as $objectKey => $object) {
                    $body[$key][$objectKey] = $object->getBody();
                }
            } else {
                $body[$key] = ApiHelper::toArray($attribute);
            }
        }
        return $body;
    }


    /**
     * Get the rules for a given attribute
     * @param string $attribute
     * @return array
     */
    protected function getAttributeRules($attribute) {
        if (!isset($this->_rules[$attribute])) {
            $rules = [];
            foreach ($this->getTypes() as $typeSet) {
                list($attributeSet, $rule) = $typeSet;
                foreach (explode(',', $attributeSet) as $key) {
                    if (trim($key) == $attribute) {
                        $rules[] = $rule;
                    }
                }
            }
            $this->_rules[$attribute] = $rules;
        }
        return $this->_rules[$attribute];
    }

    /**
     * Returns mapping, used to map values between the model and the api
     * @return array
     */
    protected function getMapping() {
        return [];
    }

    /**
     * Prepare the model
     */
    private function _setMetaData() {
        $this->_attributes = array_fill_keys(array_keys(array_flip($this->getAttributes())), null);
        foreach (array_keys($this->getRelations()) as $attribute) {
            $relation = new Relation($this, $attribute);
            if ($relation->type == 'many') {
                //Make sure the attributes are iterable
                $this->_attributes[$attribute] = new ArrayList();
            }
            $this->_relations[$attribute] = $relation;
        }
    }

}
