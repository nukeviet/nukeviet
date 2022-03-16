<?php

namespace OAuth\OAuth2\Service;

use OAuth\Common\Http\Exception\TokenResponseException;
use OAuth\OAuth2\Token\StdOAuth2Token;

/**
 * RunKeeper service.
 *
 * @link http://runkeeper.com/developer/healthgraph/registration-authorization
 */
class RunKeeper extends AbstractService
{

    protected $baseApiUri = 'https://api.runkeeper.com/';
    protected $authorizationEndpoint = 'https://runkeeper.com/apps/authorize';
    protected $accessTokenEndpoint = 'https://runkeeper.com/apps/token';
    protected $authorizationMethod = self::AUTHORIZATION_METHOD_HEADER_BEARER;

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

        $parameters[ 'scope' ] = implode(' ', $this->scopes);

        // Build the url
        $url = clone $this->getAuthorizationEndpoint();
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

        unset($data[ 'access_token' ]);

        $token->setExtraParams($data);

        return $token;
    }
}
