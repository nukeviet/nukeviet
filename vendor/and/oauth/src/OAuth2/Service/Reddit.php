<?php

namespace OAuth\OAuth2\Service;

use OAuth\Common\Http\Exception\TokenResponseException;
use OAuth\OAuth2\Token\StdOAuth2Token;

class Reddit extends AbstractService
{

    /**
     * Defined scopes
     *
     * @link http://www.reddit.com/dev/api/oauth
     */
    // User scopes
    const SCOPE_EDIT = 'edit';
    const SCOPE_HISTORY = 'history';
    const SCOPE_IDENTITY = 'identity';
    const SCOPE_MYSUBREDDITS = 'mysubreddits';
    const SCOPE_PRIVATEMESSAGES = 'privatemessages';
    const SCOPE_READ = 'read';
    const SCOPE_SAVE = 'save';
    const SCOPE_SUBMIT = 'submit';
    const SCOPE_SUBSCRIBE = 'subscribe';
    const SCOPE_VOTE = 'vote';
    // Mod Scopes
    const SCOPE_MODCONFIG = 'modconfig';
    const SCOPE_MODFLAIR = 'modflair';
    const SCOPE_MODLOG = 'modlog';
    const SCOPE_MODPOST = 'modpost';

    protected $baseApiUri = 'https://oauth.reddit.com';
    protected $authorizationEndpoint = 'https://ssl.reddit.com/api/{apiVersion}/authorize';
    protected $accessTokenEndpoint = 'https://ssl.reddit.com/api/{apiVersion}/access_token';
    protected $authorizationMethod = self::AUTHORIZATION_METHOD_HEADER_BEARER;
    protected $apiVersion = 'v1';

    /**
     * {@inheritdoc}
     */
    protected function parseAccessTokenResponse($responseBody)
    {
        $data = json_decode($responseBody, true);

        if (null === $data || !is_array($data)) {
            throw new TokenResponseException('Unable to parse response.');
        } elseif (isset($data[ 'error' ])) {
            throw new TokenResponseException('Error in retrieving token: "' . $data[ 'error' ] . '"');
        }

        $token = new StdOAuth2Token();
        $token->setAccessToken($data[ 'access_token' ]);
        $token->setLifeTime($data[ 'expires_in' ]);

        if (isset($data[ 'refresh_token' ])) {
            $token->setRefreshToken($data[ 'refresh_token' ]);
            unset($data[ 'refresh_token' ]);
        }

        unset($data[ 'access_token' ]);
        unset($data[ 'expires_in' ]);

        $token->setExtraParams($data);

        return $token;
    }

    public function getAuthorizationUri(array $additionalParameters = [])
    {
        $state = uniqid();
        $this->storage->storeAuthorizationState($this->service(), $state);

        return parent::getAuthorizationUri(
            array_merge(
                [
                    'state' => $state
                ],
                $additionalParameters
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getExtraOAuthHeaders()
    {
        // Reddit uses a Basic OAuth header
        return [
            'Authorization' => 'Basic ' .
                base64_encode($this->credentials->getConsumerId() . ':' . $this->credentials->getConsumerSecret())
        ];
    }
}
