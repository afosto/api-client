<?php

namespace Afosto\ApiClient\Components;

use Afosto\ApiClient\Api\Api;
use Afosto\ApiClient\Components\Exceptions\ModelException;
use Afosto\ApiClient\Components\Helpers\ApiHelper;
use Afosto\ApiClient\Models\Products\Product;
use Afosto\ApiClient\Components\Models\Count;
use Doctrine\Common\Inflector\Inflector;
/**
 * @property Api $api
 * @property boolean $hasErrors
 * @property boolean $isNewRecord
 */
trait ModelTrait {

    use PaginationTrait;

    /**
     * Contains the api handler
     * @var Api
     */
    private $_api;
    
    /**
     * Error array
     */
    private $_errors = [];

    /**
     * Return a new model
     * @return static
     */
    public static function model() {
        return new static();
    }
    
    /**
     * Require a validation function on these models
     */
    abstract function validate();

    /**
     * @param $attributes
     * @return mixed
     */
    abstract function setAttributes($attributes);

    /**
     * @return string
     */
    abstract function getName();

    /**
     * Find a model
     * @param integer $id
     * @return Product
     */
    public function find($id) {
        $this->id = (int) $id;
        $this->setAttributes($this->api->request(ApiHelper::FIND));
        return $this;
    }

    /**
     * Returns array of models for this page
     * @return static[]
     */
    public function findPage($page = null, $pageSize = null) {
        $models = array();
        if ($page !== null) {
            $this->getPagination()->page = $page;
            $this->getPagination()->setPageSize($pageSize);
        }
        foreach ($this->api->request(ApiHelper::PAGE) as $attributes) {
            $name = get_called_class();
            $model = new $name;
            $model->setAttributes($attributes);
            array_push($models, $model);
        }
        return $models;
    }

    /**
     * Returns a count model for this model
     * @return Count
     */
    public function findCount() {
        return Count::model()->setAttributes($this->api->request(ApiHelper::COUNT));
    }

    /**
     * Returns an array of models of up to $pageSize items
     *
     * Returns an array of models of up to $pageSize items. This function provides an easy way to access small
     * collections. If a collection has more items, you can use findPage($page) to retrieve other pages or use the
     * paginate() method to iterate over pages.
     * 
     * @param int $pageSize
     * @return static[]
     */
    public function findAll($pageSize = null)
    {
        return $this->findPage(1, $pageSize);
    }
    
    /**
     * Stores a model
     */
    public function save() {
        if ($this->isNewRecord) {
            $result = $this->api->request(ApiHelper::SAVE);
        } else {
            $result = $this->api->request(ApiHelper::UPDATE);
        }
        $this->setAttributes($result);
    }

    /**
     * Deletes a model
     * @throws ModelException
     */
    public function delete() {
        if ($this->isNewRecord) {
            throw new ModelException('Cannot delete new record, specify an [id]');
        }
        $this->api->request(ApiHelper::DELETE);
        $this->id = null;
    }
    
    /**
     * Returns the path to the model
     * @return string
     */
    public function getRoute() {
        return strtolower(Inflector::pluralize($this->getName()));
    }

    /**
     * Contains the errors
     * @param string $message
     */
    public function addError($message) {
        $this->_errors[] = $message;
    }
    
    public function getErrors() {
        return $this->_errors;
    }
    
    /**
     * Called before each request
     */
    public function beforeRequest() {
        $this->_errors = [];
    }
    
    /**
     * Returns the error state
     * @return boolean
     */
    public function getHasErrors() {
        return !empty($this->_errors);
    }
    
    /**
     * Returns true when model is new (has no ID)
     * @return boolean
     */
    public function getIsNewRecord() {
        if ($this->id === null) {
            return true;
        }
        return false;
    }
    
    /**
     * Returns the api class
     * @return Api
     */
    protected function getApi() {
        if ($this->_api === null) {
            $this->_api = new Api($this);
        }
        return $this->_api;
    }

}
