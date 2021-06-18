<?php

namespace OAuth\OAuth1\Service;

use OAuth\Common\Http\Exception\TokenResponseException;
use OAuth\OAuth1\Token\StdOAuth1Token;
use OAuth\OAuth1\Token\TokenInterface;

class Flickr extends AbstractService
{

    protected $baseApiUri = 'https://api.flickr.com/services/rest/';
    protected $requestTokenEndpoint = 'https://www.flickr.com/services/oauth/request_token';
    protected $authorizationEndpoint = 'https://www.flickr.com/services/oauth/authorize';
    protected $accessTokenEndpoint = 'https://www.flickr.com/services/oauth/access_token';

    public function getAuthorizationUri(array $additionalParameters = [])
    {
        if (!isset($additionalParameters[ 'oauth_token' ])) {
            $additionalParameters[ 'oauth_token' ] = $this->requestRequestToken()->getAccessToken();
        }

        return parent::getAuthorizationUri($additionalParameters);
    }

    public function requestAccessToken($token, $verifier, $tokenSecret = null)
    {
        if (is_null($tokenSecret)) {
            /** @var TokenInterface $storedRequestToken */
            $storedRequestToken = $this->storage->retrieveAccessToken($this->service());
            $tokenSecret = $storedRequestToken->getAccessTokenSecret();
        }

        return parent::requestAccessToken($token, $verifier, $tokenSecret);
    }

    protected function parseRequestTokenResponse($responseBody)
    {
        parse_str($responseBody, $data);
        if (null === $data || !is_array($data)) {
            throw new TokenResponseException('Unable to parse response.');
        } elseif (!isset($data[ 'oauth_callback_confirmed' ]) || $data[ 'oauth_callback_confirmed' ] != 'true') {
            throw new TokenResponseException('Error in retrieving token.');
        }

        return $this->parseAccessTokenResponse($responseBody);
    }

    protected function parseAccessTokenResponse($responseBody)
    {
        parse_str($responseBody, $data);
        if ($data === null || !is_array($data)) {
            throw new TokenResponseException('Unable to parse response.');
        } elseif (isset($data[ 'error' ])) {
            throw new TokenResponseException('Error in retrieving token: "' . $data[ 'error' ] . '"');
        }

        $token = new StdOAuth1Token();
        $token->setRequestToken($data[ 'oauth_token' ]);
        $token->setRequestTokenSecret($data[ 'oauth_token_secret' ]);
        $token->setAccessToken($data[ 'oauth_token' ]);
        $token->setAccessTokenSecret($data[ 'oauth_token_secret' ]);
        $token->setEndOfLife(StdOAuth1Token::EOL_NEVER_EXPIRES);
        unset($data[ 'oauth_token' ], $data[ 'oauth_token_secret' ]);
        $token->setExtraParams($data);

        return $token;
    }

    public function request($path, array $body = [], $method = 'GET', array $extraHeaders = [])
    {
        $uri = $this->determineRequestUriFromPath('/');
        $uri->getQuery()->modify(['method' => $path]);

        $token = $this->storage->retrieveAccessToken($this->service());
        $extraHeaders = array_merge($this->getExtraApiHeaders(), $extraHeaders);
        $authorizationHeader = [
            'Authorization' => $this->buildAuthorizationHeaderForAPIRequest($method, $uri, $token, $body)
        ];
        $headers = array_merge($authorizationHeader, $extraHeaders);

        return $this->httpRequest($uri, $body, $headers, $method);
    }
}
