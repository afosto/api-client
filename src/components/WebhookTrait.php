<?php

namespace Afosto\ApiClient\Components;


use Afosto\ApiClient\Components\Exceptions\WebhookException;
use Doctrine\Common\Inflector\Inflector;

trait WebhookTrait {

    /**
     * The type of webhook action
     * Can be create, update or delete
     * @var string
     */
    public $action;

    /**
     * @return string
     */
    abstract public function getName();

    /**
     * @return mixed
     */
    abstract public function setAttributes($attributes);

    /**
     * Returns the url for the webhook
     * @param $action Can be create, update or delete
     * @return string
     */
    public function getWebhookUrl($action) {
        $params = [
            'model' => Inflector::tableize($this->getName()),
            'action' => $action,
        ];
        if (defined('WEBHOOK_KEY')) {
            $params = array_merge(['key' => WEBHOOK_KEY], $params);
        }
        $query = http_build_query($params);
        return WEBHOOK_BASE . '/?' . $query;
    }

    /**
     * Read the posted input, format it and set the attributes on the model
     * @return $this
     * @throws WebhookException
     */
    public function loadModel() {
        if (defined('WEBHOOK_KEY')) {
            if (!isset($_GET['key']) || $_GET['key'] != WEBHOOK_KEY) {
                throw new WebhookException('Invalid credentials, no valid webhook key');
            }
        }
        if (!isset($_POST['payload'])) {
            throw new WebhookException('Payload not specified');
        }
        $data = $_POST['payload'];
        //Set the action from the url
        $this->action = $_GET['action'];
        if ($data !== null) {
             $this->setAttributes(json_decode($data, true));
        }
        return $this;
    }

}