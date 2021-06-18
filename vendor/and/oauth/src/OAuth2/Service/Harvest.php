<?php

namespace OAuth\OAuth2\Service;

use OAuth\Common\Http\Exception\TokenResponseException;
use OAuth\Common\Token\TokenInterface;
use OAuth\OAuth2\Service\Exception\MissingRefreshTokenException;
use OAuth\OAuth2\Token\StdOAuth2Token;

class Harvest extends AbstractService
{

    protected $baseApiUri = 'https://api.harvestapp.com/';
    protected $authorizationEndpoint = 'https://api.harvestapp.com/oauth2/authorize';
    protected $accessTokenEndpoint = 'https://api.harvestapp.com/oauth2/token';
    protected $authorizationMethod = self::AUTHORIZATION_METHOD_QUERY_STRING;
    protected $extraOAuthHeaders = ['Accept' => 'application/json'];
    protected $extraApiHeaders = ['Accept' => 'application/json'];

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
                'state'         => 'optional-csrf-token',
                'response_type' => 'code',
            ]
        );

        // Build the url
        $url = $this->getAuthorizationEndpoint();
        $url->getQuery()->modify($parameters);

        return $url;
    }

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
        $token->setLifetime($data[ 'expires_in' ]);
        $token->setRefreshToken($data[ 'refresh_token' ]);

        unset($data[ 'access_token' ]);

        $token->setExtraParams($data);

        return $token;
    }

    /**
     * Refreshes an OAuth2 access token.
     *
     * @param TokenInterface $token
     *
     * @return TokenInterface $token
     * @throws MissingRefreshTokenException
     */
    public function refreshAccessToken(TokenInterface $token)
    {
        $refreshToken = $token->getRefreshToken();

        if (empty($refreshToken)) {
            throw new MissingRefreshTokenException();
        }

        $parameters = [
            'grant_type'    => 'refresh_token',
            'type'          => 'web_server',
            'client_id'     => $this->credentials->getConsumerId(),
            'client_secret' => $this->credentials->getConsumerSecret(),
            'refresh_token' => $refreshToken,
        ];

        $responseBody = $this->httpRequest(
            $this->getAccessTokenEndpoint(),
            $parameters,
            $this->getExtraOAuthHeaders()
        );
        $token = $this->parseAccessTokenResponse($responseBody);
        $this->storage->storeAccessToken($this->service(), $token);

        return $token;
    }
}
