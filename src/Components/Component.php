<?php

namespace Afosto\ApiClient\Components;

use Afosto\ApiClient\Components\Exceptions\ModelException;
use Afosto\ApiClient\Components\Helpers\ApiHelper;

abstract class Component {

    /**
     * Magic getter method
     * @param string $name
     * @return mixed
     * @throws ModelException
     */
    public function __get($name) {
        $getter = 'get' . ucfirst($name);
        if (method_exists($this, $getter)) {
            return $this->$getter();
        } else if (property_exists($this, $name)) {
            return $this->$name;
        }
        throw new ModelException('[' . $getter . '] not defined in [' . get_class($this) . ']');
    }

    /**
     * Magic setter method
     * @param string $name
     * @param string $value
     * @return mixed
     * @throws ModelException
     */
    public function __set($name, $value) {
        $setter = 'set' . ucfirst($name);
        if (method_exists($this, $setter)) {
            return $this->$setter($value);
        }  else if (property_exists($this, $name)) {
            //Look for functions
            $this->$name = $value;
        }
    }
    
    /**
     * Returns the name of the model that is being called
     * @return string
     */
    public function getName() {
        return ApiHelper::getName(get_called_class());
    }

    /**
     * Returns the current namespace
     * @return string
     */
    public function getNameSpace() {
        return ApiHelper::getNameSpace(get_called_class());
    }

}
