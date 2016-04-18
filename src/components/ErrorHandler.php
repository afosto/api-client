<?php

namespace Afosto\ApiClient\Components;

use Afosto\ApiClient\App;
use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;
use GuzzleHttp\Exception\ClientException;
use Afosto\ApiClient\Api\Api;
use Afosto\ApiClient\Components\Models\ErrorResponse;

class ErrorHandler extends PrettyPageHandler {

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        if (defined('EDITOR')) {
            $this->setEditor(EDITOR);
        }
    }

    /**
     * Register the error handler
     */
    public static function register() {
        $whoops = new Run;
        $whoops->pushHandler(new ErrorHandler());
        $whoops->register();
    }

    /**
     * Add data to the page
     */
    public function handle() {
        $exception = $this->getException();

        //Catch the client exception
        if ($exception instanceof ClientException) {
            $error = ErrorResponse::model()->setAttributes(json_decode((string)$exception->getResponse()->getBody(), true));
            $this->addDataTable('API result', $error->getBody());
        }

        //Read from the api request log and show the data
        foreach (array_reverse(Api::getRequestLog(), true) as $key => $log) {
            $this->addDataTable("API Request #" . ($key+1), $log->getBody());
        }
        
        //Add rate limits
        $this->addDataTable('Rate limits', [
            'limit' => App::getInstance()->getRateLimit()->limit,
            'remaining' => App::getInstance()->getRateLimit()->remaining,
            'reset' => App::getInstance()->getRateLimit()->getDateTime()
        ]);
        
        $this->setPageTitle('Error ' . $this->getException()->getMessage());
        parent::handle();
    }

}
