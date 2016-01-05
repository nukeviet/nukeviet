<?php

namespace OAuth\OAuth2\Service;

use OAuth\Common\Http\Exception\TokenResponseException;
use OAuth\Common\Service\ServiceInterface as BaseServiceInterface;
use OAuth\Common\Token\TokenInterface;

/**
 * Defines the common methods across OAuth 2 services.
 */
interface ServiceInterface extends BaseServiceInterface
{

    /**
     * Authorization methods for various services
     */
    const AUTHORIZATION_METHOD_HEADER_OAUTH = 0;
    const AUTHORIZATION_METHOD_HEADER_BEARER = 1;
    const AUTHORIZATION_METHOD_QUERY_STRING = 2;
    const AUTHORIZATION_METHOD_QUERY_STRING_V2 = 3;
    const AUTHORIZATION_METHOD_QUERY_STRING_V3 = 4;

    /**
     * Retrieves and stores/returns the OAuth2 access token after a successful authorization.
     *
     * @param string $code The access code from the callback.
     *
     * @return TokenInterface $token
     * @throws TokenResponseException
     */
    public function requestAccessToken($code);

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
