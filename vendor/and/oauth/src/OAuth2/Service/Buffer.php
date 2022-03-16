<?php

namespace OAuth\OAuth2\Service;

use OAuth\Common\Http\Exception\TokenResponseException;
use OAuth\OAuth2\Token\StdOAuth2Token;

/**
 * Buffer API.
 *
 * @author  Sumukh Sridhara <@sumukhsridhara>
 * @link https://bufferapp.com/developers/api
 */
class Buffer extends AbstractService
{

    protected $baseApiUri = 'https://api.bufferapp.com/1/';
    protected $authorizationEndpoint = 'https://bufferapp.com/oauth2/authorize';
    protected $accessTokenEndpoint = 'https://api.bufferapp.com/1/oauth2/token.json';
    protected $authorizationMethod = self::AUTHORIZATION_METHOD_QUERY_STRING;

    /**
     * {@inheritdoc}
     */
    public function getAuthorizationUri(array $additionalParameters = [])
    {
        $parameters = array_merge(
            $additionalParameters,
            [
                'client_id'     => $this->credentials->getConsumerId(),
                'redirect_uri'  => $this->credentials->getCallbackUrl(),
                'response_type' => 'code',
            ]
        );

        // Build the url
        $url = clone $this->getAuthorizationEndpoint();
        $url->getQuery()->modify($parameters);

        return $url;
    }

    public function requestAccessToken($code)
    {
        $bodyParams = [
            'client_id'     => $this->credentials->getConsumerId(),
            'client_secret' => $this->credentials->getConsumerSecret(),
            'redirect_uri'  => $this->credentials->getCallbackUrl(),
            'code'          => $code,
            'grant_type'    => 'authorization_code',
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
        $data = json_decode($responseBody, true);

        if ($data === null || !is_array($data)) {
            throw new TokenResponseException('Unable to parse response.');
        } elseif (isset($data[ 'error' ])) {
            throw new TokenResponseException('Error in retrieving token: "' . $data[ 'error' ] . '"');
        }

        $token = new StdOAuth2Token();
        $token->setAccessToken($data[ 'access_token' ]);

        $token->setEndOfLife(StdOAuth2Token::EOL_NEVER_EXPIRES);
        unset($data[ 'access_token' ]);
        $token->setExtraParams($data);

        return $token;
    }

    /**
     * {@inheritdoc}
     */
    public function requestRequestToken()
    {
        $responseBody = $this->httpRequest(
            $this->getAuthorizationEndpoint(),
            [
                'client_key'    => $this->credentials->getConsumerId(),
                'redirect_uri'  => $this->credentials->getCallbackUrl(),
                'response_type' => 'code',
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
}
