<?php

namespace OAuth\OAuth1\Service;

use OAuth\Common\Http\Exception\TokenResponseException;
use OAuth\OAuth1\Token\StdOAuth1Token;

class Tumblr extends AbstractService
{

    protected $baseApiUri = 'https://api.tumblr.com/v2/';
    protected $requestTokenEndpoint = 'https://www.tumblr.com/oauth/request_token';
    protected $authorizationEndpoint = 'https://www.tumblr.com/oauth/authorize';
    protected $accessTokenEndpoint = 'https://www.tumblr.com/oauth/access_token';

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
