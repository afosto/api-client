<?php
/**
 * Login to the api using an earlier obtained accessToken, probably how you
 * want to use the client in production.
 */
namespace Afosto\ApiClient;

use Afosto\ApiClient\Components\Storage\SessionStorage;

//Change these paths accordingly
require_once(dirname(__FILE__) . '/vendor/autoload.php');
require_once(dirname(__FILE__) . '/config.php');

//Set the caching parameters
$storage = new SessionStorage();
App::run($storage, CLIENT_ID, CLIENT_SECRET);
//Place your obtained access token here
App::getInstance()->login($accessToken);

