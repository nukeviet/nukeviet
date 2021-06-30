<?php

namespace OAuth\OAuth1\Service;

use OAuth\Common\Http\Exception\TokenResponseException;
use OAuth\Common\Http\Url;
use OAuth\Common\Service\ServiceInterface as BaseServiceInterface;
use OAuth\Common\Token\TokenInterface;

/**
 * Defines the common methods across OAuth 1 services.
 */
interface ServiceInterface extends BaseServiceInterface
{

    /**
     * Retrieves and stores/returns the OAuth1 request token obtained from the service.
     *
     * @return TokenInterface $token
     * @throws TokenResponseException
     */
    public function requestRequestToken();

    /**
     * Retrieves and stores/returns the OAuth1 access token after a successful authorization.
     *
     * @param string $token The request token from the callback.
     * @param string $verifier
     * @param string $tokenSecret
     *
     * @return TokenInterface $token
     * @throws TokenResponseException
     */
    public function requestAccessToken($token, $verifier, $tokenSecret);

    /**
     * @return Url
     */
    public function getRequestTokenEndpoint();

    /**
     * Check, does arguments has code
     *
     * @param array $request
     *
     * @return boolean
     */
    public function isRequestArgumentsPassed(array $request);

    /**
     * Retrieves and stores the OAuth2 access token after a successful authorization
     *
     * @param array $request GET + POST array
     *
     * @return $this
     */
    public function retrieveAccessTokenByReqArgs(array $request);

    /**
     * Check, does global arguments has code
     *
     * @return boolean
     */
    public function isGlobalRequestArgumentsPassed();

    /**
     * Retrieves and stores the OAuth2 access token after a successful authorization
     * Silent version (because it uses global variables - _GET, _POST)
     *
     * @return $this
     */
    public function retrieveAccessTokenByGlobReqArgs();

    /**
     * Get last retrieved token
     *
     * @return TokenInterface $token
     */
    public function getAccessToken();
}
