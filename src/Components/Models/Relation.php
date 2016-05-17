<?php

namespace Afosto\ApiClient\Components\Models;

use Afosto\ApiClient\Components\Component;
use Afosto\ApiClient\Components\Helpers\ApiHelper;
use Doctrine\Common\Inflector\Inflector;

class Relation extends Component {

    /**
     * The modelname
     * @var string 
     */
    public $model;
    
    /**
     * Has one or has may
     * @var string 
     */
    public $type;
    
    /**
     * The path
     * @var string
     */
    public $classPath;

    /**
     * Relation constructor.
     * @param Model $source
     * @param $attribute
     */
    public function __construct(Model $source, $attribute) {
        list($this->model, $this->type) = $source->getRelations()[$attribute];
        if ($this->isRel()) {
            $this->classPath = 'Afosto\ApiClient\Models\\' . Inflector::pluralize($this->_getModelForRel()) . '\\' . $this->model;
        } else if ($this->isReference()) {
            $this->classPath = 'Afosto\ApiClient\Models\\' . Inflector::pluralize($this->_getModelForReference()) . '\\' . $this->_getModelForReference();
        } else {
            $this->classPath = $source->getNameSpace() . '\\' . $this->model;
        }
    }

    /**
     * Return true for rel models
     * @return boolean
     */
    public function isRel() {
        return substr($this->model, -3) == "Rel";
    }
    
    /**
     * Return true for rel models
     * @return boolean
     */
    public function isReference() {
        return substr($this->model, 0, 2) == "//";
    }

    /**
     * Return the linked modelName
     * @return string
     */
    private function _getModelForRel() {
        return substr($this->model, 0, -3);
    }
    
    /**
     * Return the linked modelName
     * @return string
     */
    private function _getModelForReference() {
        return substr($this->model, 2);
    }

}
