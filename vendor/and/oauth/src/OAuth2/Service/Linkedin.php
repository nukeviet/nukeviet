<?php

namespace OAuth\OAuth2\Service;

use OAuth\Common\Http\Exception\TokenResponseException;
use OAuth\OAuth2\Token\StdOAuth2Token;

/**
 * Linkedin service.
 *
 * @author Antoine Corcy <contact@sbin.dk>
 * @link http://developer.linkedin.com/documents/authentication
 */
class Linkedin extends AbstractService
{

    /**
     * Defined scopes
     *
     * @link http://developer.linkedin.com/documents/authentication#granting
     */
    const SCOPE_R_BASICPROFILE = 'r_basicprofile';
    const SCOPE_R_FULLPROFILE = 'r_fullprofile';
    const SCOPE_R_EMAILADDRESS = 'r_emailaddress';
    const SCOPE_R_NETWORK = 'r_network';
    const SCOPE_R_CONTACTINFO = 'r_contactinfo';
    const SCOPE_RW_NUS = 'rw_nus';
    const SCOPE_RW_COMPANY_ADMIN = 'rw_company_admin';
    const SCOPE_RW_GROUPS = 'rw_groups';
    const SCOPE_W_MESSAGES = 'w_messages';
    const SCOPE_W_SHARE = 'w_share';

    protected $baseApiUri = 'https://api.linkedin.com/{apiVersion}/';
    protected $authorizationEndpoint = 'https://www.linkedin.com/uas/oauth2/authorization';
    protected $accessTokenEndpoint = 'https://www.linkedin.com/uas/oauth2/accessToken';
    protected $authorizationMethod = self::AUTHORIZATION_METHOD_QUERY_STRING_V2;
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
