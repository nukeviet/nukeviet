<?php
/**
 * Contains EveOnline class.
 * PHP version 5.4
 *
 * @copyright 2014 Michael Cummings
 * @author    Michael Cummings <mgcummings@yahoo.com>
 */
namespace OAuth\OAuth2\Service;

use OAuth\Common\Http\Exception\TokenResponseException;
use OAuth\Common\Token\TokenInterface;
use OAuth\OAuth2\Token\StdOAuth2Token;

/**
 * Class EveOnline
 */
class EveOnline extends AbstractService
{

    protected $baseApiUri = 'https://login.eveonline.com';
    protected $authorizationEndpoint = 'https://login.eveonline.com/oauth/authorize';
    protected $accessTokenEndpoint = 'https://login.eveonline.com/oauth/token';
    protected $authorizationMethod = self::AUTHORIZATION_METHOD_HEADER_BEARER;

    /**
     * Parses the access token response and returns a TokenInterface.
     *
     * @param string $responseBody
     *
     * @return TokenInterface
     * @throws TokenResponseException
     */
    protected function parseAccessTokenResponse($responseBody)
    {
        $data = json_decode($responseBody, true);

        if (null === $data || !is_array($data)) {
            throw new TokenResponseException('Unable to parse response.');
        } elseif (isset($data[ 'error_description' ])) {
            throw new TokenResponseException('Error in retrieving token: "' . $data[ 'error_description' ] . '"');
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
