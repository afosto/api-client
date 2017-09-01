<?php
/**
 * This sample file demonstrates the functionality to receive incoming 
 * webhooks. 
 * Use this file as a starting point to receive incoming webhooks from the API.
 * 
 * Developing on your local machine? Install ngrok to transfer webhooks to your local machine.
 * https://ngrok.com/
 */
namespace Afosto\ApiClient;

use Afosto\ApiClient\Components\Storage\SessionStorage;

require_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__ . '/config.php');

$cache = new SessionStorage();
App::run($cache, CLIENT_ID, CLIENT_SECRET);

$object = App::getWebhookModel();

switch ($object->getName()) {
    case 'Product':
        //Do things with this product        
        break;
    case 'Sale':
        //Do things with this sale
}