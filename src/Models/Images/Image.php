<?php

namespace Afosto\ApiClient\Models\Images;

use Afosto\ApiClient\Components\Helpers\ApiHelper;
use Afosto\ApiClient\Components\ModelTrait;
use Afosto\ApiClient\Models\_Base\Images\BaseImage;

/**
 * Use this class for custom methods that extend the default functionality for
 * the Image
 *
 * This object cannot be called directly from the api and has therefore no api
 * operations. It is only used to format and maintain the data.
 *
 * @property string $contents
 * @property string $filename
 */
class Image extends BaseImage {

    use ModelTrait;

    public function getAttributes() {
        return array_merge(parent::getAttributes(), [
            'contents',
            'filename',
        ]);
    }

    /**
     * The specific route for the image upload
     * @return string
     */
    public function getRoute() {
        return 'https://upload.afosto.com/v2/product';
    }

    /**
     * Send the file
     * @return $this
     */
    public function upload() {
        $this->setAttributes($this->api->request(ApiHelper::UPLOAD));
        return $this;
    }

    /**
     * Return the multipart data
     * @return array
     */
    public function getMultiPart() {
        return [
            [
                'name'     => 'file',
                'contents' => $this->contents,
                'filename' => $this->filename,
            ],
        ];
    }

}