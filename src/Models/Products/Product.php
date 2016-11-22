<?php

namespace Afosto\ApiClient\Models\Products;

use Afosto\ApiClient\Models\_Base\Products\BaseProduct;
use Afosto\ApiClient\Components\WebhookTrait;

/**
 * Use this class for custom methods that extend the default functionality for
 * the Product
 *
 * This class can interact with api, through methods like find and save.
 */
class Product extends BaseProduct {

    use WebhookTrait;

    /**
     * Add an image to the product
     *
     * @param $image_id
     */
    public function addImage($image_id) {
        $images[] = [
            'id' => (int)$image_id,
        ];
        foreach ($this->images as $image) {
            $images[] = [
                'id' => $image->id,
            ];
        }
        $this->images = $images;
    }

}