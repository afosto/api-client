<?php

namespace Afosto\ApiClient;

use Afosto\ApiClient\Components\Storage\SessionStorage;

require_once(dirname(__FILE__) . '/vendor/autoload.php');
require_once(dirname(__FILE__) . '/config.php');

//Set the caching parameters
$storage = new SessionStorage();
App::run($storage, CLIENT_ID, CLIENT_SECRET);



if (App::getInstance()->isConnected()) {
    
    //Interact with the API
    
} else if (isset($_GET['code'])) {
    App::getInstance()->login($_GET['code']);
    header('Location: ' . REDIRECT_URI);
} else {
    header('Location: ' . App::getInstance()->getAuthorizationUrl(REDIRECT_URI));
}
