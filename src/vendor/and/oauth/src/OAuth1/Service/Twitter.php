<?php

namespace OAuth\OAuth1\Service;

use OAuth\Common\Exception\Exception;
use OAuth\Common\Http\Exception\TokenResponseException;
use OAuth\OAuth1\Token\StdOAuth1Token;

class Twitter extends AbstractService
{

    const ENDPOINT_AUTHENTICATE = "https://api.twitter.com/oauth/authenticate";
    const ENDPOINT_AUTHORIZE = "https://api.twitter.com/oauth/authorize";

    protected $baseApiUri = 'https://api.twitter.com/1.1/';
    protected $requestTokenEndpoint = 'https://api.twitter.com/oauth/request_token';
    protected $authorizationEndpoint = self::ENDPOINT_AUTHENTICATE;
    protected $accessTokenEndpoint = 'https://api.twitter.com/oauth/access_token';

    /**
     * @param $endpoint
     *
     * @return $this
     * @throws \OAuth\Common\Exception\Exception
     */
    public function setAuthorizationEndpoint($endpoint)
    {
        if (!in_array($endpoint, [self::ENDPOINT_AUTHENTICATE, self::ENDPOINT_AUTHORIZE])) {
            throw new Exception(
                sprintf("'%s' is not a correct Twitter authorization endpoint.", $endpoint)
            );
        }

        $this->authorizationEndpoint = $endpoint;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function parseRequestTokenResponse($responseBody)
    {
        parse_str($responseBody, $data);

        if (null === $data || !is_array($data)) {
            throw new TokenResponseException('Unable to parse response.');
        } elseif (!isset($data[ 'oauth_callback_confirmed' ]) || $data[ 'oauth_callback_confirmed' ] !== 'true') {
            throw new TokenResponseException('Error in retrieving token.');
        }

        return $this->parseAccessTokenResponse($responseBody);
    }

    /**
     * {@inheritdoc}
     */
    protected function parseAccessTokenResponse($responseBody)
    {
        parse_str($responseBody, $data);

        if (null === $data || !is_array($data)) {
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
}
