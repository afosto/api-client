<?php

namespace Afosto\ApiClient;

use Afosto\ApiClient\Components\Storage\FileStorage;
require_once(dirname(__FILE__) . '/vendor/autoload.php');
require_once(dirname(__FILE__) . '/config.php');

/**
 * Use this file as a starting point to receive incoming webhooks from the API.
 * 
 * Developing on your local machine? Install ngrok to transfer webhooks to your local machine.
 * https://ngrok.com/
 */

$cache = new FileStorage(['directory' => CACHE_DIRECTORY, 'userKey' => 234]);
App::run($cache, CLIENT_ID, CLIENT_SECRET);

$object = App::getWebhookModel();

switch ($object->getName()) {
    case 'Product':
        //Do things with this product
        $cache->set(time(), $object);
        break;
    case 'Sale':
        $cache->set(time(), $object);
}