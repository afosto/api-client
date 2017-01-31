<?php

namespace Afosto\ApiClient;

use Afosto\ApiClient\Models\Images\Image;
use Afosto\ApiClient\Models\Products\Product;


$path = 'path/to/image.jpg';
$product_id = 0; //The product id that should get the image

$image = new Image();
$image->contents = file_get_contents($path);
$image->filename = $path;

//Upload the image and retrieve an image id
$image->upload();

$product = Product::model()->find($product_id);
$product->addImage($image->image_id);

if ($product->save()) {
    //The image was attached to the product
}