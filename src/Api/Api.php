<?php

namespace Afosto\ApiClient\Api;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use Afosto\ApiClient\Components\Component;
use Afosto\ApiClient\Components\Helpers\ApiHelper;
use Afosto\ApiClient\Components\Exceptions\ApiException;
use Afosto\ApiClient\Components\Exceptions\RateLimitException;
use Afosto\ApiClient\Components\Exceptions\ModelException;
use Afosto\ApiClient\Components\Models\Model;
use Afosto\ApiClient\Components\Log;
use Afosto\ApiClient\App;

class Api extends Component {

    /**
     * The formatted result
     * @var array
     */
    private $_result;

    /**
     * The response from the Client
     * @var Response
     */
    private $_response;

    /**
     * The modeldata
     * @var Model
     */
    private $_owner;

    /**
     * Options that are given along with the request
     * @var array
     */
    private $_options = [];

    /**
     * The last request
     * @var Request
     */
    private $_request;

    /**
     * Contains a log of all requests, can be used for debuggin purposes
     * @var Log[]
     */
    private static $_log = [];

    /**
     * Returns log of all fired requests
     * @return Log[]
     */
    public static function getRequestLog() {
        return self::$_log;
    }

    /**
     * Build the api resource
     * @param Model $model
     */
    public function __construct(Model $model) {
        $this->_owner = $model;
    }

    /**
     * Run a request on the api
     * @param $queryType
     * @return mixed
     * @throws ApiException
     * @throws RateLimitException
     */
    public function request($queryType) {
        //Prepare the request
        $this->_request = new Request(ApiHelper::getMethod($queryType), $this->_owner->getRoute() . '/' . $this->_getUri($queryType));
        $this->beforeRequest($queryType);
        //Run the request
        $this->_response = App::getInstance()->getClient()->send($this->_request, $this->_options);
        //Do administration
        $this->afterRequest($queryType);
        return $this->_result;
    }

    /**
     * Called before sending a request
     * @param $queryType
     * @throws ApiException
     * @throws RateLimitException
     * @throws ModelException
     */
    protected function beforeRequest($queryType) {
        //Reset the options
        $this->_owner->beforeRequest();
        $this->_setBody($queryType);

        if (App::getInstance()->getRateLimit()->hasExceeded()) {
            throw new RateLimitException('Out of limits');
        }
        if ($queryType == ApiHelper::PAGE) {
            $this->_addHeaders([
                'page'     => $this->_owner->getPagination()->page,
                'pagesize' => $this->_owner->getPagination()->pageSize,
            ]);
        }
        $this->_addLog();
    }

    /**
     * Called after a request
     * @param $queryType
     */
    protected function afterRequest($queryType) {
        //Format the response stream
        if (!$this->_owner->hasErrors) {
            $this->_result = json_decode((string)$this->_response->getBody(), true);
            $this->_updateRateLimits();
            if ($queryType == ApiHelper::PAGE) {
                $this->_updatePagination();
            }
        }
        $this->_options = [];
        $this->_updateLog();
    }

    /**
     * Set the body for the request
     *
     * @param $queryType
     */
    private function _setBody($queryType) {
        if ($queryType == ApiHelper::SAVE || $queryType == ApiHelper::UPDATE) {
            $this->_options['body'] = json_encode($this->_owner->getBody());
        } else if ($queryType == ApiHelper::UPLOAD) {
            $this->_options['multipart'] = $this->_owner->getMultiPart();
        }
    }

    /**
     * Adds headers to the future request. Headers need to be set before each
     * request, as they are reset after each request
     * @param array $headers
     */
    private function _addHeaders(array $headers) {
        if (!isset($this->_options['headers'])) {
            $this->_options['headers'] = $headers;
        } else {
            $this->_options = array_merge($this->_options['headers'], $headers);
        }
    }

    /**
     * Return the uri for this query type
     * @param string $queryType
     * @return string
     * @throws ApiException
     */
    private function _getUri($queryType) {
        switch ($queryType) {
            case ApiHelper::PAGE:
            case ApiHelper::SAVE:
            case ApiHelper::UPDATE:
            case ApiHelper::UPLOAD:
                $path = null;
                break;
            case ApiHelper::COUNT:
                $path = 'count';
                break;
            case ApiHelper::FIND:
            case ApiHelper::DELETE:
                $path = $this->_owner->id;
                break;
            default:
                throw new ApiException('Invalid queryType');
        }
        return $path;
    }

    /**
     * Update the pagination data accordingly to the results of the last request
     */
    private function _updatePagination() {
        if (!empty($this->_response->getHeader(ApiHelper::HEADER_TOTAL_COUNT))) {
            $this->_owner->getPagination()->update(current($this->_response->getHeader(ApiHelper::HEADER_TOTAL_COUNT)));
        }
    }

    /**
     * After each request update the rate limits that are received in the headers
     */
    private function _updateRateLimits() {
        App::getInstance()->getRateLimit()->update(
            current($this->_response->getHeader(ApiHelper::HEADER_RATE_LIMIT)),
            current($this->_response->getHeader(ApiHelper::HEADER_RATE_LIMIT_REMAINING)),
            current($this->_response->getHeader(ApiHelper::HEADER_RATE_LIMIT_RESET)));
    }

    /**
     * Insert a log
     */
    private function _addLog() {
        $log = new Log();
        $log->method = $this->_request->getMethod();
        $log->uri = (string)$this->_request->getUri();
        $config = App::getInstance()->getClient()->getConfig();
        $log->options = array_merge_recursive(['headers' => $config['headers']], $this->_options);
        self::$_log[] = $log;
        //Keep only the last three requests in the log
        self::$_log = array_slice(self::$_log, -3);
    }

    /**
     * Updates the last log
     */
    private function _updateLog() {
        $log = end(self::$_log);
        $log->status = $this->_response->getStatusCode();
        $log->response = $this->_result;
    }

}
