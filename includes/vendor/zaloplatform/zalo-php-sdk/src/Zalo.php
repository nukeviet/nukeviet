<?php
/**
 * Zalo © 2019
 *
 */

namespace Zalo;

use Zalo\Authentication\AccessToken;
use Zalo\Authentication\OAuth2Client;
use Zalo\Authentication\ZaloRedirectLoginHelper;
use Zalo\Authentication\ZaloToken;
use Zalo\Exceptions\ZaloSDKException;
use Zalo\HttpClients\HttpClientsFactory;
use Zalo\Url\UrlDetectionInterface;
use Zalo\Url\ZaloUrlDetectionHandler;

/**
 * Class Zalo
 *
 * @package Zalo
 */
class Zalo
{
    /**
     * @const string Version number of the Zalo PHP SDK.
     */
    const VERSION = '4.0.2';
    /**
     * @var ZaloClient The Zalo client service.
     */
    protected $client;
    /**
     * @var ZaloApp The ZaloApp entity.
     */
    protected $app;
    /**
     * @var UrlDetectionInterface|null The URL detection handler.
     */
    protected $urlDetectionHandler;
    /**
     * @var ZaloToken|null The default zalo token to use with requests.
     */
    protected $defaultZaloToken;
    /**
     * @var ZaloResponse|ZaloBatchResponse|null Stores the last request made to Graph.
     */
    protected $lastResponse;
    /**
     * @var OAuth2Client The OAuth 2.0 client service.
     */
    protected $oAuth2Client;

    /**
     * Instantiates a new Zalo super-class object.
     *
     * @param array $config
     *
     * @throws ZaloSDKException
     */
    public function __construct(array $config = [])
    {
        $config = array_merge([
            'enable_beta_mode' => false,
            'http_client_handler' => 'curl',
            'url_detection_handler' => null,
        ], $config);
        $this->client = new ZaloClient(
            HttpClientsFactory::createHttpClient($config['http_client_handler']),
            $config['enable_beta_mode']
        );
        $this->app = new ZaloApp($config['app_id'], $config['app_secret']);
        $this->setUrlDetectionHandler($config['url_detection_handler'] ?: new ZaloUrlDetectionHandler());
        if (isset($config['default_zalo_token'])) {
            $this->setDefaultZaloToken($config['default_zalo_token']);
        }
    }

    /**
     * Returns the last response returned from Graph.
     *
     * @return ZaloResponse|null
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    /**
     * Returns the URL detection handler.
     *
     * @return UrlDetectionInterface
     */
    public function getUrlDetectionHandler()
    {
        return $this->urlDetectionHandler;
    }

    /**
     * Changes the URL detection handler.
     *
     * @param UrlDetectionInterface $urlDetectionHandler
     */
    private function setUrlDetectionHandler(UrlDetectionInterface $urlDetectionHandler)
    {
        $this->urlDetectionHandler = $urlDetectionHandler;
    }

    /**
     * Returns the default ZaloToken entity.
     *
     * @return ZaloToken|null
     */
    public function getDefaultZaloToken()
    {
        return $this->defaultZaloToken;
    }

    /**
     * Sets the default zalo token to use with requests.
     *
     * @param ZaloToken|string $zaloToken The zalo token to save.
     *
     * @throws \InvalidArgumentException
     */
    public function setDefaultZaloToken($zaloToken)
    {
        if ($zaloToken instanceof ZaloToken) {
            $this->defaultZaloToken = $zaloToken;
            return;
        }
        throw new \InvalidArgumentException('The default zalo token must be of type Zalo\ZaloToken');
    }

    /**
     * Sends a GET request to Graph and returns the result.
     *
     * @param string $url
     * @param ZaloToken|string|null $accessToken
     * @param string|null $eTag
     *
     * @return ZaloResponse
     *
     * @throws ZaloSDKException
     */
    public function get($url, $accessToken = null, array $params = [], $eTag = null)
    {
        return $this->sendRequest(
            'GET',
            $url,
            $params,
            $accessToken,
            $eTag
        );
    }

    /**
     * Sends a POST request to Graph and returns the result.
     *
     * @param string $url
     * @param AccessToken|string|null $accessToken
     * @param array $params
     * @param string|null $eTag
     *
     * @return ZaloResponse
     *
     * @throws ZaloSDKException
     */
    public function post($url, $accessToken = null, $params = [], $eTag = null)
    {
        return $this->sendRequest(
            'POST',
            $url,
            $params,
            $accessToken,
            $eTag
        );
    }

    /**
     * Sends a request to Graph and returns the result.
     *
     * @param string $method
     * @param string $url
     * @param array $params
     * @param ZaloToken|string|null $accessToken
     * @param string|null $eTag
     *
     * @return ZaloResponse
     *
     * @throws ZaloSDKException
     */
    public function sendRequest($method, $url, array $params = [], $accessToken = null, $eTag = null)
    {
        $request = $this->request($method, $url, $params, $accessToken, $eTag);
        return $this->lastResponse = $this->client->sendRequest($request);
    }

    /**
     * Instantiates a new ZaloRequest entity.
     *
     * @param string $method
     * @param string $url
     * @param array $params
     * @param ZaloToken|string|null $accessToken
     * @param string|null $eTag
     * @param string|null $contentType
     *
     * @return ZaloRequest
     *
     * @throws ZaloSDKException
     */
    public function request($method, $url, array $params = [], $accessToken = null, $eTag = null, $contentType = null)
    {
        $request = new ZaloRequest(
            $accessToken,
            $method,
            $url,
            $params,
            $eTag,
            $contentType
        );
        return $request;
    }

    /**
     * Returns the ZaloApp entity.
     *
     * @return ZaloApp
     */
    public function getApp()
    {
        return $this->app;
    }

    /**
     * Returns the ZaloClient service.
     *
     * @return ZaloClient
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Returns the OAuth 2.0 client service.
     *
     * @return OAuth2Client
     */
    public function getOAuth2Client()
    {
        if (!$this->oAuth2Client instanceof OAuth2Client) {
            $app = $this->getApp();
            $client = $this->getClient();
            $this->oAuth2Client = new OAuth2Client($app, $client);
        }
        return $this->oAuth2Client;
    }

    /**
     * Returns Login helper.
     *
     * @return ZaloRedirectLoginHelper
     */
    public function getRedirectLoginHelper()
    {
        return new ZaloRedirectLoginHelper(
            $this->getOAuth2Client(),
            $this->urlDetectionHandler
        );
    }
}