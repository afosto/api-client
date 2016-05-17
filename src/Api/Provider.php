<?php

namespace Afosto\ApiClient\Api;

use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Provider\AbstractProvider;

class Provider extends AbstractProvider {

    /**
     * Default value
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param type $data
     * @return boolean
     */
    protected function checkResponse(\Psr\Http\Message\ResponseInterface $response, $data) {
        return true;
    }

    /**
     * Create resource
     * @param array $response
     * @param AccessToken $token
     * @return type
     */
    protected function createResourceOwner(array $response, AccessToken $token) {
        return parent::createResourceOwner($response, $token);
    }

    /**
     * Returns default
     * @return array
     */
    protected function getDefaultScopes() {
        return ['all'];
    }

    /**
     * Returns the base access token url
     * @param array $params
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params) {
        return 'https://app.afosto.com/oauth/token';
    }

    
     /**
     * @inheritdoc
     */
    public function getAccessToken($grant = 'authorization_code', array $params = [])
    {
        return parent::getAccessToken($grant, $params);
    }
    
    /**
     * Returns the base auth url
     * @return string
     */
    public function getBaseAuthorizationUrl() {
        return 'https://app.afosto.com/oauth/authorize';
    }

    /**
     * Returns resource details
     * @param AccessToken $token
     * @return string
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token) {
        return parent::getResourceOwnerDetailsUrl($token);
    }
    
    /**
     * @inheritdoc
     */
    protected function getContentType(\Psr\Http\Message\ResponseInterface $response)
    {
        return parent::getContentType($response);
    }
    
    
}
