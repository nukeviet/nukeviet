<?php
/**
 * Zalo © 2019
 *
 */

namespace Zalo;

use Zalo\Exceptions\ZaloSDKException;
use Zalo\HttpClients\ZaloCurlHttpClient;
use Zalo\HttpClients\ZaloHttpClientInterface;

/**
 * Class ZaloClient
 *
 * @package Zalo
 */
class ZaloClient
{

    /**
     * @const int The timeout in seconds for a normal request.
     */
    const DEFAULT_REQUEST_TIMEOUT = 60;

    /**
     * @var bool Toggle to use  beta url.
     */
    protected $enableBetaMode = false;

    /**
     * @var ZaloHttpClientInterface HTTP client handler.
     */
    protected $httpClientHandler;

    /**
     * @var int The number of calls that have been made to .
     */
    public static $requestCount = 0;

    /**
     * Instantiates a new ZaloClient object.
     *
     * @param ZaloHttpClientInterface|null $httpClientHandler
     * @param boolean $enableBeta
     */
    public function __construct(ZaloHttpClientInterface $httpClientHandler = null, $enableBeta = false)
    {
        $this->httpClientHandler = $httpClientHandler ?: $this->detectHttpClientHandler();
        $this->enableBetaMode = $enableBeta;
    }

    /**
     * Sets the HTTP client handler.
     *
     * @param ZaloHttpClientInterface $httpClientHandler
     */
    public function setHttpClientHandler(ZaloHttpClientInterface $httpClientHandler)
    {
        $this->httpClientHandler = $httpClientHandler;
    }

    /**
     * Returns the HTTP client handler.
     *
     * @return ZaloHttpClientInterface
     */
    public function getHttpClientHandler()
    {
        return $this->httpClientHandler;
    }

    /**
     * Detects which HTTP client handler to use.
     *
     * @return ZaloHttpClientInterface
     */
    public function detectHttpClientHandler()
    {
        return new ZaloCurlHttpClient();
    }

    /**
     * Toggle beta mode.
     *
     * @param boolean $betaMode
     */
    public function enableBetaMode($betaMode = true)
    {
        $this->enableBetaMode = $betaMode;
    }

    /**
     * Prepares the request for sending to the client handler.
     *
     * @param ZaloRequest $request
     *
     * @return array
     */
    public function prepareRequestMessage(ZaloRequest $request)
    {
        $url = $request->getUrl();
        if ($request->getAccessToken()) {
            $request->setHeaders([
                'access_token' => $request->getAccessToken()
            ]);
        }

        $contentType = $request->getContentType();
        if ($contentType === null) {
            // If we're sending files they should be sent as multipart/form-data
            if ($request->containsFileUploads()) {
                $contentType = 'multipart/form-data';
            } else if ($request->getMethod() === 'GET' || $request->isGraph() === true) {
                $contentType = 'application/x-www-form-urlencoded';
            } else {
                $contentType = 'application/json';
            }
        }

        switch ($contentType) {
            case 'multipart/form-data':
            {
                $requestBody = $request->getMultipartBody();
                $request->setHeaders([
                    'Content-Type' => 'multipart/form-data; boundary=' . $requestBody->getBoundary(),
                ]);
                break;
            }
            case 'application/x-www-form-urlencoded':
            {
                $requestBody = $request->getUrlEncodedBody();
                $request->setHeaders([
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ]);
                break;
            }
            case 'application/json':
            default:
            {
                $requestBody = $request->getRawBody();
                $request->setHeaders([
                    'Content-Type' => 'application/json',
                ]);
                break;
            }
        }

        return [
            $url,
            $request->getMethod(),
            $request->getHeaders(),
            $requestBody->getBody(),
        ];
    }

    /**
     * Makes the request to  and returns the result.
     *
     * @param ZaloRequest $request
     *
     * @return ZaloResponse
     *
     * @throws ZaloSDKException
     */
    public function sendRequest(ZaloRequest $request)
    {
        $request->validateAccessToken();

        list($url, $method, $headers, $body) = $this->prepareRequestMessage($request);
        // Since file uploads can take a while, we need to give more time for uploads
        $timeOut = static::DEFAULT_REQUEST_TIMEOUT;

        // Should throw `ZaloSDKException` exception on HTTP client error.
        // Don't catch to allow it to bubble up.
        $rawResponse = $this->httpClientHandler->send($url, $method, $body, $headers, $timeOut);
        static::$requestCount++;

        $returnResponse = new ZaloResponse(
            $request, $rawResponse->getBody(), $rawResponse->getHttpResponseCode(), $rawResponse->getHeaders()
        );

        if ($returnResponse->isError()) {
            throw $returnResponse->getThrownException();
        }

        return $returnResponse;
    }

    /**
     * Make the request without the access_token header parameter and return the result.
     *
     * @param ZaloRequest $request
     *
     * @return ZaloResponse
     *
     * @throws ZaloSDKException
     */
    public function sendRequestWithoutAccessToken(ZaloRequest $request)
    {
        list($url, $method, $headers, $body) = $this->prepareRequestMessage($request);
        // Since file uploads can take a while, we need to give more time for uploads
        $timeOut = static::DEFAULT_REQUEST_TIMEOUT;

        // Should throw `ZaloSDKException` exception on HTTP client error.
        // Don't catch to allow it to bubble up.
        $rawResponse = $this->httpClientHandler->send($url, $method, $body, $headers, $timeOut);
        static::$requestCount++;

        $returnResponse = new ZaloResponse(
            $request, $rawResponse->getBody(), $rawResponse->getHttpResponseCode(), $rawResponse->getHeaders()
        );

        if ($returnResponse->isError()) {
            throw $returnResponse->getThrownException();
        }

        return $returnResponse;
    }
}
