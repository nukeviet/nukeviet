<?php

namespace OAuth\OAuth2\Service;

use OAuth\Common\Http\Exception\TokenResponseException;
use OAuth\OAuth2\Token\StdOAuth2Token;

class SoundCloud extends AbstractService
{

    const SCOPE_NON_EXPIRING_TOKEN = 'non-expiring';

    protected $baseApiUri = 'https://api.soundcloud.com/';
    protected $authorizationEndpoint = 'https://soundcloud.com/connect';
    protected $accessTokenEndpoint = 'https://api.soundcloud.com/oauth2/token';

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

        if (isset($data[ 'expires_in' ])) {
            $token->setLifetime($data[ 'expires_in' ]);
            unset($data[ 'expires_in' ]);
        }

        if (isset($data[ 'refresh_token' ])) {
            $token->setRefreshToken($data[ 'refresh_token' ]);
            unset($data[ 'refresh_token' ]);
        }

        unset($data[ 'access_token' ]);

        $token->setExtraParams($data);

        return $token;
    }
}
