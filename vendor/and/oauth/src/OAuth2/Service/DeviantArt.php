<?php

namespace OAuth\OAuth2\Service;

use OAuth\Common\Http\Exception\TokenResponseException;
use OAuth\OAuth2\Token\StdOAuth2Token;

/**
 * Class DeviantArt
 *
 * @package OAuth\OAuth2\Service
 * @author Charlotte Genevier (https://github.com/cgenevier)
 */
class DeviantArt extends AbstractService
{

    /**
     * DeviantArt www url - used to build dialog urls
     */
    const WWW_URL = 'https://www.deviantart.com/';

    protected $baseApiUri = 'https://www.deviantart.com/api/{apiVersion}/oauth2/';
    protected $authorizationEndpoint = 'https://www.deviantart.com/oauth2/authorize';
    protected $accessTokenEndpoint = 'https://www.deviantart.com/oauth2/token';
    protected $apiVersion = 'v1';

    /**
     * Defined scopes
     *
     * If you don't think this is scary you should not be allowed on the web at all
     *
     * @link https://www.deviantart.com/developers/authentication
     * @link https://www.deviantart.com/developers/http/v1/20150217
     */
    const SCOPE_FEED = 'feed';
    const SCOPE_BROWSE = 'browse';
    const SCOPE_COMMENT = 'comment.post';
    const SCOPE_STASH = 'stash';
    const SCOPE_USER = 'user';
    const SCOPE_USERMANAGE = 'user.manage';

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
            $token->setLifeTime($data[ 'expires_in' ]);
        }

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
