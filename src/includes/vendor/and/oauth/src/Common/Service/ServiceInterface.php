<?php

namespace OAuth\Common\Service;

use OAuth\Common\Http\Exception\TokenResponseException;
use OAuth\Common\Http\Url;
use OAuth\UserData\Extractor\ExtractorInterface;
use OAuth\UserData\ExtractorFactoryInterface;

/**
 * Defines methods common among all OAuth services.
 */
interface ServiceInterface
{

    /**
     * Sends an authenticated API request to the path provided.
     * If the path provided is not an absolute URI, the base API Uri (service-specific) will be used.
     *
     * @param string|Url $path
     * @param array $body Request body if applicable (an associative array will
     * @param string $method HTTP method
     *                                          automatically be converted into a urlencoded body)
     * @param array $extraHeaders Extra headers if applicable. These will override service-specific
     *                                          any defaults.
     *
     * @return string
     */
    public function request($path, array $body = [], $method = 'GET', array $extraHeaders = []);

    /**
     * Shortcut for json_decode($this->request(...
     *
     * @param $uri
     * @param array $body
     * @param string $method
     * @param array $extraHeaders
     *
     * @return array
     */
    public function requestJSON($uri, array $body = [], $method = 'GET', array $extraHeaders = []);

    /**
     * Sends an authenticated API request to the path provided.
     * If the path provided is not an absolute URI, the base API Uri (must be passed into constructor) will be used.
     *
     * @param Url|string $uri
     * @param array $body Request body if applicable (key/value pairs)
     * @param array $headers Extra headers if applicable.
     * @param string $method HTTP method
     *
     * @throws TokenResponseException
     * @return string
     */
    public function httpRequest($uri, array $body = [], array $headers = [], $method = 'POST');

    /**
     * Returns the url to redirect to for authorization purposes.
     *
     * @param array $additionalParameters
     *
     * @return Url
     */
    public function getAuthorizationUri(array $additionalParameters = []);

    /**
     * Returns the authorization API endpoint.
     *
     * @return Url
     */
    public function getAuthorizationEndpoint();

    /**
     * Returns the access token API endpoint.
     *
     * @return Url
     */
    public function getAccessTokenEndpoint();

    /**
     * Get Extractor for service
     *
     * @param ExtractorFactoryInterface $extractorFactory
     *
     * @return ExtractorInterface
     */
    public function constructExtractor(ExtractorFactoryInterface $extractorFactory = null);

    /**
     * Redirect user to authorization uri
     *
     * @return $this
     */
    public function redirectToAuthorizationUri();
}
