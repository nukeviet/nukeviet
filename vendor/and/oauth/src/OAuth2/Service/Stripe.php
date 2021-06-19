<?php

namespace OAuth\OAuth2\Service;

use OAuth\OAuth2\Token\StdOAuth2Token;
use OAuth\Common\Http\Exception\TokenResponseException;

class Stripe extends AbstractService
{
    const SCOPE_NON_EXPIRING_TOKEN = 'non-expiring';

    protected $baseApiUri = 'https://connect.stripe.com/';
    protected $authorizationEndpoint = 'https://connect.stripe.com/oauth/authorize';
    protected $accessTokenEndpoint = 'https://connect.stripe.com/oauth/token';

    /**
     * {@inheritdoc}
     */
    protected function parseAccessTokenResponse($responseBody)
    {
        $data = json_decode($responseBody, true);

        if (null === $data || ! is_array($data)) {
            throw new TokenResponseException('Unable to parse response.');
        } elseif (isset($data['error'])) {
            throw new TokenResponseException('Error in retrieving token: "' . $data['error'] . '"');
        }

        $token = new StdOAuth2Token();
        $token->setAccessToken($data['access_token']);

        unset($data['access_token']);

        $token->setExtraParams($data);

        return $token;
    }
}
