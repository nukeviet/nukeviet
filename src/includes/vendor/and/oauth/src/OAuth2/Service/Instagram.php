<?php

namespace OAuth\OAuth2\Service;

use OAuth\Common\Http\Exception\TokenResponseException;
use OAuth\OAuth2\Token\StdOAuth2Token;

class Instagram extends AbstractService
{

    /**
     * Defined scopes
     *
     * @link http://instagram.com/developer/authentication/#scope
     */
    const SCOPE_BASIC = 'basic';
    const SCOPE_COMMENTS = 'comments';
    const SCOPE_RELATIONSHIPS = 'relationships';
    const SCOPE_LIKES = 'likes';

    protected $baseApiUri = 'https://api.instagram.com/{apiVersion}/';
    protected $authorizationEndpoint = 'https://api.instagram.com/oauth/authorize/';
    protected $accessTokenEndpoint = 'https://api.instagram.com/oauth/access_token';
    protected $authorizationMethod = self::AUTHORIZATION_METHOD_QUERY_STRING;
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
        // Instagram tokens evidently never expire...
        $token->setEndOfLife(StdOAuth2Token::EOL_NEVER_EXPIRES);
        unset($data[ 'access_token' ]);

        $token->setExtraParams($data);

        return $token;
    }
}
