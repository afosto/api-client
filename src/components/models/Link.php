<?php

namespace Afosto\ApiClient\Components\Models;

/**
 * @property integer $id
 */
abstract class Link extends Model {

    /**
     * Used to cache the results
     * @var array
     */
    static $models = array();

    /**
     * Required function to return the linked model
     */
    abstract public function getModel();

    /**
     * Improve performance by caching results for this request
     * @return static
     */
    public function getLink() {
        if ($this->id !== null) {
            if (array_key_exists($this->id, self::$models)) {
                $object = self::$models[$this->id];
            } else {
                $object = $this->getModel();
                $object->find($this->id);
                self::$models[$this->id] = $object;
            }
        } else {
            $object = $this->getModel();
        }
        return $object;
    }

    /**
     * Return the attribtes for this model
     * Each link model consists of an id
     * @return array
     */
    public function getAttributes() {
        return ['id'];
    }

    /**
     * Return the relations
     * @return array
     */
    public function getRelations() {
        return [];
    }

    /**
     * Return types, used for validation
     * @return array
     */
    public function getTypes() {
        return ['id', 'integer'];
    }

}
