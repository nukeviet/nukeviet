<?php

namespace OAuth\OAuth2\Service;

use OAuth\Common\Http\Exception\TokenResponseException;
use OAuth\Common\Http\Url;
use OAuth\OAuth2\Token\StdOAuth2Token;

class Pocket extends AbstractService
{

    protected $baseApiUri = 'https://getpocket.com/{apiVersion}/';
    protected $authorizationEndpoint = 'https://getpocket.com/auth/authorize';
    protected $accessTokenEndpoint = 'https://getpocket.com/{apiVersion}/oauth/authorize';
    protected $apiVersion = 'v3';
    protected $requestTokenEndpoint = 'https://getpocket.com/{apiVersion}/oauth/request';

    public function getRequestTokenEndpoint()
    {
        return $this->injectApiVersionToUri(new Url($this->requestTokenEndpoint));
    }

    public function getAuthorizationUri(array $additionalParameters = [])
    {
        $requestToken = empty($additionalParameters[ 'request_token' ]) ?
            $this->requestRequestToken() :
            $additionalParameters[ 'request_token' ];

        $redirectUri = new Url($this->credentials->getCallbackUrl());
        $redirectUri->getQuery()->modify(['code' => $requestToken]);

        $parameters = array_merge(
            [
                'request_token' => $requestToken,
                'redirect_uri'  => (string) $redirectUri
            ],
            $additionalParameters
        );

        // Build the url
        $url = $this->getAuthorizationEndpoint();
        $url->getQuery()->modify($parameters);

        return $url;
    }

    public function requestRequestToken()
    {
        $responseBody = $this->httpRequest(
            $this->getRequestTokenEndpoint(),
            [
                'consumer_key' => $this->credentials->getConsumerId(),
                'redirect_uri' => $this->credentials->getCallbackUrl(),
            ]
        );

        $code = $this->parseRequestTokenResponse($responseBody);

        return $code;
    }

    protected function parseRequestTokenResponse($responseBody)
    {
        parse_str($responseBody, $data);

        if (null === $data || !is_array($data)) {
            throw new TokenResponseException('Unable to parse response.');
        } elseif (!isset($data[ 'code' ])) {
            throw new TokenResponseException('Error in retrieving code.');
        }

        return $data[ 'code' ];
    }

    public function requestAccessToken($code)
    {
        $bodyParams = [
            'consumer_key' => $this->credentials->getConsumerId(),
            'code'         => $code,
        ];

        $responseBody = $this->httpRequest(
            $this->getAccessTokenEndpoint(),
            $bodyParams,
            $this->getExtraOAuthHeaders()
        );
        $token = $this->parseAccessTokenResponse($responseBody);
        $this->storage->storeAccessToken($this->service(), $token);

        return $token;
    }

    protected function parseAccessTokenResponse($responseBody)
    {
        parse_str($responseBody, $data);

        if ($data === null || !is_array($data)) {
            throw new TokenResponseException('Unable to parse response.');
        } elseif (isset($data[ 'error' ])) {
            throw new TokenResponseException('Error in retrieving token: "' . $data[ 'error' ] . '"');
        }

        $token = new StdOAuth2Token();
        #$token->setRequestToken($data['access_token']);
        $token->setAccessToken($data[ 'access_token' ]);
        $token->setEndOfLife(StdOAuth2Token::EOL_NEVER_EXPIRES);
        unset($data[ 'access_token' ]);
        $token->setExtraParams($data);

        return $token;
    }
}
