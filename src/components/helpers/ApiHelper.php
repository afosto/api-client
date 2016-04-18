<?php

namespace Afosto\ApiClient\Components\Helpers;


use Doctrine\Common\Inflector\Inflector;

class ApiHelper {

    /**
     * Total model count header
     */
    const HEADER_TOTAL_COUNT = 'X-Total-Count';

    /**
     * Rate limit (default value)
     */
    const HEADER_RATE_LIMIT = 'X-Rate-Limit-Limit';

    /**
     * Remaining limit
     */
    const HEADER_RATE_LIMIT_REMAINING = 'X-Rate-Limit-Remaining';

    /**
     * Time to reset header
     */
    const HEADER_RATE_LIMIT_RESET = 'X-Rate-Limit-Reset';

    /**
     * Used in paginated queries
     */
    const PAGE = 'all';

    /**
     * Used in model count queries
     */
    const COUNT = 'count';

    /**
     * Used in single model queries
     */
    const FIND = 'single';

    /**
     * Used in save model queries
     */
    const SAVE = 'post';

    /**
     * Used to delete models
     */
    const DELETE = 'delete';

    /**
     * Returns assoc array from object
     * @param $object
     * @return array
     */
    public static function toArray($object) {
        if (is_array($object) || is_object($object)) {
            $result = [];
            foreach ($object as $key => $value) {
                $result[$key] = self::toArray($value);
            }
            return $result;
        }
        else if (self::isJson($object)) {
            return json_decode($object, true);
        }
        return $object;
    }

    /**
     * True for json data
     * @link http://stackoverflow.com/questions/6041741/fastest-way-to-check-if-a-string-is-json-in-php
     * @param $string
     * @return bool
     */
    public static function isJson($string) {
        return is_array(json_decode($string, true));
    }

    /**
     * Return the request method for the queryType
     * @param string $queryType
     * @return string
     * @throws ApiException
     */
    public static function getMethod($queryType) {
        switch ($queryType) {
            case self::PAGE:
            case self::COUNT:
            case self::FIND:
                $method = 'GET';
                break;
            case self::SAVE:
                $method = 'POST';
                break;
            case self::DELETE:
                $method = 'DELETE';
                break;
            default:
                throw new ApiException('Invalid queryType');
        }
        return $method;
    }

    /**
     * Get the classPath from a modelName
     * @return string
     */
    public static function getModelPath($name) {
        $model = Inflector::classify($name);
        return "Afosto\ApiClient\Models\\" . Inflector::pluralize($model) . "\\" . $model;
    }

    /**
     * Returns the namespace of the classPath
     * @param $classPath
     * @return mixed
     */
    public static function getNameSpace($classPath) {
        return substr($classPath, 0, strrpos($classPath, '\\') - strlen($classPath));
    }

    /**
     * Returns the name of the classPath
     * @param $classPath
     * @return mixed
     */
    public static function getName($classPath) {
        return strrpos($classPath, '\\') === false ? $classPath : substr($classPath, strrpos($classPath, '\\') + 1);
    }
}