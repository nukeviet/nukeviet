<?php

namespace OAuth\OAuth2\Service;

use OAuth\Common\Http\Exception\TokenResponseException;
use OAuth\OAuth2\Token\StdOAuth2Token;

class Bitly extends AbstractService
{

    protected $baseApiUri = 'https://api-ssl.bitly.com/v3/';
    protected $authorizationEndpoint = 'https://bitly.com/oauth/authorize';
    protected $accessTokenEndpoint = 'https://api-ssl.bitly.com/oauth/access_token';
    protected $authorizationMethod = self::AUTHORIZATION_METHOD_QUERY_STRING;

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
        // I'm invinsible!!!
        $token->setEndOfLife(StdOAuth2Token::EOL_NEVER_EXPIRES);
        unset($data[ 'access_token' ]);

        $token->setExtraParams($data);

        return $token;
    }

    /**
     * {@inheritdoc}
     */
    public function requestAccessToken($code, $state = null)
    {
        if (null !== $state) {
            $this->validateAuthorizationState($state);
        }

        $bodyParams = [
            'code'          => $code,
            'client_id'     => $this->credentials->getConsumerId(),
            'client_secret' => $this->credentials->getConsumerSecret(),
            'redirect_uri'  => $this->credentials->getCallbackUrl(),
            'grant_type'    => 'authorization_code',
        ];

        $responseBody = $this->httpRequest(
            $this->getAccessTokenEndpoint(),
            $bodyParams,
            $this->getExtraOAuthHeaders()
        );

        // we can scream what we want that we want bitly to return a json encoded string (format=json), but the
        // WOAH WATCH YOUR LANGUAGE ;) service doesn't seem to like screaming, hence we need to manually
        // parse the result
        $parsedResult = [];
        parse_str($responseBody, $parsedResult);

        $token = $this->parseAccessTokenResponse(json_encode($parsedResult));
        $this->storage->storeAccessToken($this->service(), $token);

        return $token;
    }
}
