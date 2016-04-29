<?php

namespace Afosto\ApiClient\Components;

use Afosto\ApiClient\Components\Helpers\ApiHelper;

class Log {

    /**
     * The request method
     * @var string
     */
    public $method;

    /**
     * The request uri
     * @var string
     */
    public $uri;

    /**
     * The resulting request status
     * @var integer
     */
    public $status;

    /**
     * The request options
     * @var array
     */
    public $options = [];

    /**
     * The request response
     * @var array
     */
    public $response;

    /**
     * Return the log body
     * @return array
     */
    public function getBody() {
        $body = ApiHelper::toArray($this);
        if (isset($this->options['body'])) {
            return array_merge_recursive(['options' => ['raw_body' => $this->options['body']]],
                    $body);
        }
        return $body;
    }

}
