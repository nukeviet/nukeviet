<?php

namespace OAuth\OAuth2\Service;

use OAuth\Common\Http\Exception\TokenResponseException;
use OAuth\OAuth2\Token\StdOAuth2Token;

class Foursquare extends AbstractService
{

    protected $baseApiUri = 'https://api.foursquare.com/{apiVersion}/';
    protected $authorizationEndpoint = 'https://foursquare.com/oauth2/authenticate';
    protected $accessTokenEndpoint = 'https://foursquare.com/oauth2/access_token';
    protected $apiVersion = 'v2';
    private $apiVersionDate = '20130829';

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
        // Foursquare tokens evidently never expire...
        $token->setEndOfLife(StdOAuth2Token::EOL_NEVER_EXPIRES);
        unset($data[ 'access_token' ]);

        $token->setExtraParams($data);

        return $token;
    }

    /**
     * {@inheritdoc}
     */
    public function request($path, array $body = [], $method = 'GET', array $extraHeaders = [])
    {
        $uri = $this->determineRequestUriFromPath($path);
        $uri->getQuery()->modify(['v' => $this->apiVersionDate]);

        return parent::request($uri, $body, $method, $extraHeaders);
    }
}
