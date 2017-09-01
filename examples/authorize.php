<?php
/**
 * This sample file demonstrates to flow when you want to authorize your application
 * to connect to Afosto on behalf of the Afosto user.
 * 
 * Session storage is used to demonstrate the storage of the accessToken and 
 * demostrate persistence of the authorization
 */

namespace Afosto\ApiClient;

use Afosto\ApiClient\Components\Storage\SessionStorage;

//Change these paths accordingly
require_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__ . '/config.php');

//Set the caching parameters
$storage = new SessionStorage();
App::run($storage, CLIENT_ID, CLIENT_SECRET);

//Verify if we allready have a token or not, otherwise try to connect
if (App::getInstance()->hasToken()) {
    //Interact with the API
    echo App::getInstance()->getAccessToken();
    exit();
} else if (isset($_GET['code'])) {
    //Obtain the code from the uri
    App::getInstance()->authorize($_GET['code']);
    exit('Authorized, you can refresh the page as the accessToken is now stored in the cache');
} else {
    //No code give, no authorzation in place, redirect to the application to
    //obtain grant
    header('Location: ' . App::getInstance()->getAuthorizationUrl(REDIRECT_URI));
}
