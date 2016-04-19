<?php

namespace Afosto\ApiClient;

use League\OAuth2\Client\Token\AccessToken;
use GuzzleHttp\Client;
use Afosto\ApiClient\Api\RateLimit;
use Afosto\ApiClient\Api\Provider;
use Afosto\ApiClient\Components\Storage\AbstractStorage;
use Afosto\ApiClient\Components\Exceptions\ApiException;
use Afosto\ApiClient\Components\Exceptions\LoginException;
use Afosto\ApiClient\Components\Exceptions\WebhookException;
use Afosto\ApiClient\Components\ErrorHandler;
use Afosto\ApiClient\Components\Helpers\ApiHelper;
use Whoops\Run as Whoops;

class App {

    /**
     * The base endpoint
     */
    const ENDPOINT = 'https://api.afosto.com/';

    /**
     * The targeted api version
     */
    const VERSION = 'v2';

    /**
     * The version of this client
     */
    const CLIENT_VERSION = '1.0';

    /**
     * Contains the cache options
     * @var AbstractStorage
     */
    public $storage;

    /**
     * The clientId
     * @var string
     */
    private $_clientId;

    /**
     * The clientSecret
     * @var string
     */
    private $_clientSecret;

    /**
     * The oauth provider
     * @var Provider
     */
    private $_provider;

    /**
     * The accessToken
     * @var AccessToken
     */
    private $_accessToken;

    /**
     * The loaded guzzle client
     * @var Client
     */
    private $_client;

    /**
     * The rate limit data
     * @var RateLimit
     */
    private $_rateLimit;

    /**
     * The app
     * @var App
     */
    private static $_app;

    /**
     * Build the app
     * @param AbstractStorage $storage
     * @param string $clientId
     * @param string $clientSecret
     */
    private function __construct(AbstractStorage $storage, $clientId, $clientSecret) {
        $this->storage = $storage;
        $this->_clientId = $clientId;
        $this->_clientSecret = $clientSecret;
        if (class_exists(Whoops::class)) {
            ErrorHandler::register();
        }
        //Set the accessToken from the cache if it is available
        if ($this->storage->get('token') !== false) {
            $this->_accessToken = $this->storage->get('token');
        }
    }

    /**
     * Init the app and put the cache in place
     * @param AbstractStorage $storage
     * @param $clientId
     * @param $clientSecret
     * @return App
     */
    public static function run(AbstractStorage $storage, $clientId, $clientSecret) {
        if (self::$_app == null) {
            self::$_app = new self($storage, $clientId, $clientSecret);
        }
        return self::$_app;
    }

    /**
     * Return the name of the webhook model based on the model parameter in the
     * called url
     * @return Model
     * @throws WebhookException
     */
    public static function getWebhookModel() {
        if (isset($_GET['model']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = ApiHelper::getModelPath($_GET['model']);
            if (class_exists($model)) {
                $webhook = new $model;
                if (method_exists($webhook, 'loadModel')) {
                    $webhook->loadModel();
                    return $webhook;
                }
                throw new WebhookException('Model [' . $model . '] is no webhook model');
            }
            throw new WebhookException('Model [' . $model . '] not found');
        }
        throw new WebhookException('No model given');
    }

    /**
     * Return the started instance
     * @return App
     * @throws ApiException
     */
    public static function getInstance() {
        if (self::$_app == null) {
            throw new ApiException('Not started');
        }
        return self::$_app;
    }

    /**
     * Flush the current access token and return the authorization url
     * @param $redirectUri
     * @return string
     */
    public function getAuthorizationUrl($redirectUri) {
        $this->_accessToken = null;
        $this->storage->flush('token');
        return $this->_getProvider(['redirectUri' => $redirectUri])->getAuthorizationUrl();
    }

    /**
     * Returns true when accessToken is still valid
     * @return boolean
     */
    public function hasToken() {
        if ($this->_accessToken === null || ($this->_accessToken->getExpires() !== null && $this->_accessToken->hasExpired())) {
            return false;
        }
        return true;
    }

    /**
     * Use this function to set the accessToken based on the authorization code 
     * that is received from Afosto when the redirectUrl is called
     * @param $authorization_code
     * @throws LoginException
     */
    public function authorize($authorization_code) {
        try {
            // Try to get an access token using the authorization code grant.
            $tokenProvider = $this->_getProvider()->getAccessToken('authorization_code', [
                'code' => $authorization_code
            ]);
            $this->login($tokenProvider->getToken());
        } catch (\Exception $e) {
            throw new LoginException('Login failed: authorization code invalid');
        }
    }

    /**
     * Set the accessToken
     * @param string $accessToken
     */
    public function login($accessToken) {
        $this->_accessToken = new AccessToken(['access_token' => $accessToken]);
        $this->storage->set('token', $this->_accessToken);
    }

    /**
     * Returns the client
     * @return Client
     */
    public function getClient() {
        if ($this->_client === null) {
            $this->_client = new Client([
                'base_uri' => App::ENDPOINT . App::VERSION . '/',
                'allow_redirects' => false,
                'headers' => [
                    'User-Agent' => 'ApiClient-php/' . self::CLIENT_VERSION,
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->_accessToken->getToken()
            ]]);
        }
        return $this->_client;
    }

    /**
     * Return rate limit data
     * @return RateLimit
     */
    public function getRateLimit() {
        if ($this->_rateLimit === null) {
            $this->_rateLimit = RateLimit::load();
        }
        return $this->_rateLimit;
    }
    
    /**
     * Returns the accessToken
     * @return string
     */
    public function getAccessToken() {
        return $this->_accessToken->getToken();
    }

    /**
     * Return the provider
     * @param array $options
     * @return Provider
     */
    private function _getProvider(array $options = array()) {
        if ($this->_provider === null) {
            $options = array_merge($options, ['clientId' => $this->_clientId, 'clientSecret' => $this->_clientSecret]);
            $this->_provider = new Provider($options);
        } return $this->_provider;
    }

}
