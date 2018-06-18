<?php

namespace OAuth\OAuth2\Service;

use OAuth\Common\Http\Exception\TokenResponseException;
use OAuth\OAuth2\Token\StdOAuth2Token;

/**
 * Jawbone UP service.
 *
 * @author Andrii Gakhov <andrii.gakhov@gmail.com>
 * @link https://jawbone.com/up/developer/authentication
 */
class JawboneUP extends AbstractService
{

    /**
     * Defined scopes
     *
     * @link https://jawbone.com/up/developer/authentication
     */
    // general information scopes
    const SCOPE_BASIC_READ = 'basic_read';
    const SCOPE_EXTENDED_READ = 'extended_read';
    const SCOPE_LOCATION_READ = 'location_read';
    const SCOPE_FRIENDS_READ = 'friends_read';
    // mood scopes
    const SCOPE_MOOD_READ = 'mood_read';
    const SCOPE_MOOD_WRITE = 'mood_write';
    // move scopes
    const SCOPE_MOVE_READ = 'move_read';
    const SCOPE_MOVE_WRITE = 'move_write';
    // sleep scopes
    const SCOPE_SLEEP_READ = 'sleep_read';
    const SCOPE_SLEEP_WRITE = 'sleep_write';
    // meal scopes
    const SCOPE_MEAL_READ = 'meal_read';
    const SCOPE_MEAL_WRITE = 'meal_write';
    // weight scopes
    const SCOPE_WEIGHT_READ = 'weight_read';
    const SCOPE_WEIGHT_WRITE = 'weight_write';
    // generic event scopes
    const SCOPE_GENERIC_EVENT_READ = 'generic_event_read';
    const SCOPE_GENERIC_EVENT_WRITE = 'generic_event_write';

    protected $baseApiUri = 'https://jawbone.com/nudge/api/{apiVersion}/';
    protected $authorizationEndpoint = 'https://jawbone.com/auth/oauth2/auth';
    protected $accessTokenEndpoint = 'https://jawbone.com/auth/oauth2/token';
    protected $authorizationMethod = self::AUTHORIZATION_METHOD_HEADER_BEARER;
    protected $apiVersion = 'v.1.1';

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
        $token->setLifeTime($data[ 'expires_in' ]);

        if (isset($data[ 'refresh_token' ])) {
            $token->setRefreshToken($data[ 'refresh_token' ]);
            unset($data[ 'refresh_token' ]);
        }

        unset($data[ 'access_token' ]);
        unset($data[ 'expires_in' ]);

        $token->setExtraParams($data);

        return $token;
    }
}
