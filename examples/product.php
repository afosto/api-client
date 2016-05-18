<?php
/**
 * This samplefile demostrates the retreival of a product, adjustment of 
 * the descriptor and mutation of the inventory
 */
namespace Afosto\ApiClient;

use Afosto\ApiClient\Components\Storage\SessionStorage;
use Afosto\ApiClient\Models\Products\Product;
use Afosto\ApiClient\Models\Products\Descriptor;

//Change these paths accordingly
require_once(dirname(__FILE__) . '/vendor/autoload.php');
require_once(dirname(__FILE__) . '/config.php');

//Set the caching parameters
$storage = new SessionStorage();
App::run($storage, CLIENT_ID, CLIENT_SECRET);

//Verify if we allready have a token or not, otherwise try to connect
if (App::getInstance()->hasToken()) {

    //Change the product id
    $product = Product::model()->find(1);

    $descriptor = new Descriptor();
    $descriptor->name = 'New name, changed at: ' . time();
    $descriptor->short_description = 'New short description: ' . time();
    $descriptor->description = 'new long description: ' . time();
    $product->descriptors = [$descriptor];
    
    //Add two items to the inventory amount (make sure product that you searched
    //keeps inventory
    foreach ($product->items as $item) {
        foreach ($item->inventory->warehouses as $warehouse) {
            $warehouse->amount += 2;
        }
    }
    $product->save();
}