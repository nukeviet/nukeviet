<?php
/**
 * Zalo © 2019
 *
 */

namespace Zalo\Authentication;

use Zalo\Exceptions\ZaloResponseException;
use Zalo\Exceptions\ZaloSDKException;
use Zalo\ZaloApp;
use Zalo\ZaloClient;
use Zalo\ZaloRequest;
use Zalo\ZaloResponse;

/**
 * Class OAuth2Client
 *
 * @package Zalo
 */
class OAuth2Client
{
    /**
     * @const string The base authorization URL.
     */
    const BASE_AUTHORIZATION_URL = 'https://oauth.zaloapp.com';

    /**
     * @const string Default OAuth API version for requests.
     */
    const DEFAULT_OAUTH_VERSION = 'v4';

    /**
     * @const string Default Content Type for requests.
     */
    const DEFAULT_CONTENT_TYPE = 'application/x-www-form-urlencoded';

    /**
     * The ZaloApp entity.
     *
     * @var ZaloApp
     */
    protected $app;

    /**
     * The Zalo client.
     *
     * @var ZaloClient
     */
    protected $client;

    /**
     * The last request sent to Graph.
     *
     * @var ZaloRequest|null
     */
    protected $lastRequest;

    /**
     * @param ZaloApp $app
     * @param ZaloClient $client
     */
    public function __construct(ZaloApp $app, ZaloClient $client)
    {
        $this->app = $app;
        $this->client = $client;
    }

    /**
     * Returns the last ZaloRequest that was sent.
     * Useful for debugging and testing.
     *
     * @return ZaloRequest|null
     */
    public function getLastRequest()
    {
        return $this->lastRequest;
    }

    /**
     * Generates an authorization URL to begin the process of authenticating a user.
     *
     * @param string $redirectUrl The callback URL to redirect to.
     * @param string $codeChallenge The code challenge is a Base64-URL-encoded string of the SHA256 hash of the code verifier.
     * @param string $state The CSPRNG-generated CSRF value.
     * @param string $separator The separator to use in http_build_query().
     *
     * @return string
     */
    public function getAuthorizationUrlByUser($redirectUrl, $codeChallenge, $state, $separator = '&')
    {
        $params = [
            'app_id' => $this->app->getId(),
            'redirect_uri' => $redirectUrl,
            'code_challenge' => $codeChallenge,
            'state' => $state
        ];

        return static::BASE_AUTHORIZATION_URL . '/' . static::DEFAULT_OAUTH_VERSION . '/permission?' . http_build_query($params, null, $separator);
    }

    /**
     * Generates an authorization URL to begin the process of authenticating a official account.
     *
     * @param string $redirectUrl The callback URL to redirect to.
     * @param string $codeChallenge The code challenge is a Base64-URL-encoded string of the SHA256 hash of the code verifier.
     * @param string $state The CSPRNG-generated CSRF value.
     * @param string $separator The separator to use in http_build_query().
     *
     * @return string
     */
    public function getAuthorizationUrlByOA($redirectUrl, $codeChallenge, $state, $separator = '&')
    {
        $params = [
            'app_id' => $this->app->getId(),
            'redirect_uri' => $redirectUrl,
            'code_challenge' => $codeChallenge,
            'state' => $state
        ];

        return static::BASE_AUTHORIZATION_URL . '/' . static::DEFAULT_OAUTH_VERSION . '/oa/permission?' . http_build_query($params, null, $separator);
    }

    /**
     * Get Zalo Token by user from a oauth code.
     *
     * @param string $code
     * @param string $codeVerifier
     *
     * @return ZaloToken
     *
     * @throws ZaloResponseException
     * @throws ZaloSDKException
     */
    public function getZaloTokenFromCodeByUser($code, $codeVerifier)
    {
        $endpoint = '/access_token';
        return $this->getZaloTokenFromCode($code, $codeVerifier, $endpoint);
    }

    /**
     * Get Zalo Token by OA from a oauth code.
     *
     * @param string $code
     * @param string $codeVerifier
     *
     * @return ZaloToken
     *
     * @throws ZaloResponseException
     * @throws ZaloSDKException
     */
    public function getZaloTokenFromCodeByOA($code, $codeVerifier)
    {
        $endpoint = '/oa/access_token';
        return $this->getZaloTokenFromCode($code, $codeVerifier, $endpoint);
    }

    /**
     *
     * Get Zalo Token from a oauth code.
     *
     * @param string $code
     * @param string $codeVerifier
     * @param string $endpoint
     *
     * @return ZaloToken
     *
     * @throws ZaloResponseException
     * @throws ZaloSDKException
     */
    public function getZaloTokenFromCode($code, $codeVerifier, $endpoint)
    {
        $params = [
            'code' => $code,
            'app_id' => $this->app->getId(),
            'grant_type' => 'authorization_code',
            'code_verifier' => $codeVerifier
        ];

        $response = $this->sendRequest($endpoint, $params);
        return $this->buildZaloTokenFromZaloResponse($response);
    }

    /**
     * Get Zalo Token by user from a refresh token.
     *
     * @param string $refreshToken
     *
     * @return ZaloToken
     * @throws ZaloResponseException
     * @throws ZaloSDKException
     */
    public function getZaloTokenFromRefreshTokenByUser($refreshToken)
    {
        $endpoint = '/access_token';
        return $this->getZaloTokenFromRefreshToken($refreshToken, $endpoint);
    }

    /**
     * Get Zalo Token by OA from a refresh token.
     *
     * @param string $refreshToken
     *
     * @return ZaloToken
     * @throws ZaloResponseException
     * @throws ZaloSDKException
     */
    public function getZaloTokenFromRefreshTokenByOA($refreshToken)
    {
        $endpoint = '/oa/access_token';
        return $this->getZaloTokenFromRefreshToken($refreshToken, $endpoint);
    }

    /**
     * Get a ZaloToken from a refresh token.
     *
     * @param string $refreshToken
     * @param string $endpoint
     *
     * @return ZaloToken
     *
     * @throws ZaloResponseException
     * @throws ZaloSDKException
     */
    public function getZaloTokenFromRefreshToken($refreshToken, $endpoint)
    {
        $params = [
            'refresh_token' => $refreshToken,
            'app_id' => $this->app->getId(),
            'grant_type' => 'refresh_token'
        ];

        $response = $this->sendRequest($endpoint, $params);
        return $this->buildZaloTokenFromZaloResponse($response);
    }

    /**
     * Build a ZaloToken from a ZaloResponse
     *
     * @param ZaloResponse $response
     *
     * @return ZaloToken
     * @throws ZaloSDKException
     */
    protected function buildZaloTokenFromZaloResponse($response)
    {
        $data = $response->getDecodedBody();

        if (!isset($data['access_token'])) {
            throw new ZaloSDKException('Access token was not returned from request.', 401);
        }
        $accessToken = $data['access_token'];

        if (!isset($data['refresh_token'])) {
            throw new ZaloSDKException('Refresh token was not returned from request.', 401);
        }
        $refreshToken = $data['refresh_token'];

        $accessTokenExpiresIn = 0;
        if (isset($data['expires_in'])) {
            $accessTokenExpiresIn = $data['expires_in'];
        }

        return new ZaloToken($accessToken, $refreshToken, $accessTokenExpiresIn);
    }

    /**
     * Send a request.
     *
     * @param string $endpoint
     * @param array $params
     * @param string $contentType
     *
     * @return ZaloResponse
     *
     * @throws ZaloResponseException
     * @throws ZaloSDKException
     */
    protected function sendRequest($endpoint, array $params, $contentType = self::DEFAULT_CONTENT_TYPE)
    {
        $url = static::BASE_AUTHORIZATION_URL . '/' . static::DEFAULT_OAUTH_VERSION . $endpoint;
        $this->lastRequest = new ZaloRequest(
            null,
            'POST',
            $url,
            $params,
            null,
            $contentType
        );

        $headers = [
            'secret_key' => $this->app->getSecret()
        ];
        $this->lastRequest->setHeaders($headers);

        return $this->client->sendRequestWithoutAccessToken($this->lastRequest);
    }
}