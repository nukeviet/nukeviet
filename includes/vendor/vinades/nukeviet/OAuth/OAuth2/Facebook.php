<?php

/**
 * NUKEVIET Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\OAuth\OAuth2;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Facebook Oauth 2.0 Client
 */
class Facebook extends AbstractProvider
{
    /**
     * @var string phiên bản API
     */
    public const API_VERSION = 'v2.10';

    /**
     * Graph API URL.
     *
     * @const string
     */
    protected const BASE_FACEBOOK_URL = 'https://www.facebook.com/';

    /**
     * Check phiên bản đồ thị API đúng định dạng
     *
     * @const string
     */
    protected const GRAPH_API_VERSION_REGEX = '~^v\d+\.\d+$~';

    /**
     * Graph API URL.
     *
     * @const string
     */
    protected const BASE_GRAPH_URL = 'https://graph.facebook.com/';

    /**
     * Phiên bản Graph API sử dụng
     *
     * @var string
     */
    protected $graphApiVersion;

    /**
     * Các trường dữ liệu cần lấy thông tin từ người dùng
     *
     * @var string[]
     */
    protected $fields;

    /**
     * @param array $options
     * @param array $collaborators
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($options = [], array $collaborators = [])
    {
        parent::__construct($options, $collaborators);

        if (empty($options['graphApiVersion'])) {
            $message = 'The "graphApiVersion" option not set. Please set a default Graph API version.';
            throw new \InvalidArgumentException($message);
        }

        if (!preg_match(self::GRAPH_API_VERSION_REGEX, $options['graphApiVersion'])) {
            $message = 'The "graphApiVersion" must start with letter "v" followed by version number, ie: "v2.4".';
            throw new \InvalidArgumentException($message);
        }

        $this->graphApiVersion = $options['graphApiVersion'];

        if (!empty($options['fields']) && is_array($options['fields'])) {
            $this->fields = $options['fields'];
        } else {
            $this->fields = [
                'id', 'name', 'first_name', 'last_name',
                'email', 'hometown', 'picture.type(large){url,is_silhouette}',
                'gender', 'age_range'
            ];

            // Tương thích API < 2.8
            if (version_compare(substr($this->graphApiVersion, 1), '2.8') < 0) {
                $this->fields[] = 'bio';
            }
        }
    }

    /**
     * {@inheritDoc}
     * @see \League\OAuth2\Client\Provider\AbstractProvider::getBaseAuthorizationUrl()
     */
    public function getBaseAuthorizationUrl(): string
    {
        return static::BASE_FACEBOOK_URL . $this->graphApiVersion . '/dialog/oauth';
    }

    /**
     * {@inheritDoc}
     * @see \League\OAuth2\Client\Provider\AbstractProvider::getBaseAccessTokenUrl()
     */
    public function getBaseAccessTokenUrl(array $params): string
    {
        return static::BASE_GRAPH_URL . $this->graphApiVersion . '/oauth/access_token';
    }

    /**
     * {@inheritDoc}
     * @see \League\OAuth2\Client\Provider\AbstractProvider::getDefaultScopes()
     */
    public function getDefaultScopes(): array
    {
        return ['public_profile', 'email'];
    }

    /**
     * {@inheritDoc}
     * @see \League\OAuth2\Client\Provider\AbstractProvider::getResourceOwnerDetailsUrl()
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        $appSecretProof = hash_hmac('sha256', $token->getToken(), $this->clientSecret);

        return static::BASE_GRAPH_URL . $this->graphApiVersion . '/me?fields=' . implode(',', $this->fields) . '&access_token=' . $token . '&appsecret_proof=' . $appSecretProof;
    }

    /**
     * {@inheritDoc}
     * @see \League\OAuth2\Client\Provider\AbstractProvider::getAccessToken()
     */
    public function getAccessToken($grant = 'authorization_code', array $params = []): AccessTokenInterface
    {
        if (isset($params['refresh_token'])) {
            throw new \Exception('Facebook does not support token refreshing.');
        }

        return parent::getAccessToken($grant, $params);
    }

    /**
     * @param string $accessToken
     * @return AccessTokenInterface
     */
    public function getLongLivedAccessToken(string $accessToken): AccessTokenInterface
    {
        $params = [
            'fb_exchange_token' => $accessToken,
        ];

        return $this->getAccessToken('fb_exchange_token', $params);
    }

    /**
     * {@inheritDoc}
     * @see \League\OAuth2\Client\Provider\AbstractProvider::createResourceOwner()
     */
    protected function createResourceOwner(array $response, AccessToken $token): FacebookUser
    {
        return new FacebookUser($response);
    }

    /**
     * {@inheritDoc}
     * @see \League\OAuth2\Client\Provider\AbstractProvider::checkResponse()
     */
    protected function checkResponse(ResponseInterface $response, $data): void
    {
        if (empty($data['error'])) {
            return;
        }

        $message = $data['error']['type'] . ': ' . $data['error']['message'];
        throw new IdentityProviderException($message, $data['error']['code'], $data);
    }

    /**
     * {@inheritDoc}
     * @see \League\OAuth2\Client\Provider\AbstractProvider::getContentType()
     */
    protected function getContentType(ResponseInterface $response): string
    {
        $type = parent::getContentType($response);

        if (strpos($type, 'javascript') !== false) {
            return 'application/json';
        }
        if (strpos($type, 'plain') !== false) {
            return 'application/x-www-form-urlencoded';
        }

        return $type;
    }
}
