<?php

namespace OAuth\OAuth1\Service;

use OAuth\Common\Exception\Exception;
use OAuth\Common\Http\Exception\TokenResponseException;
use OAuth\Common\Http\Url;
use OAuth\OAuth1\Token\StdOAuth1Token;

class Etsy extends AbstractService
{

    const SCOPE_EMAIL = 'email_r';
    const SCOPE_LISTINGS_READ = 'listings_r';
    const SCOPE_LISTINGS_WRITE = 'listings_w';
    const SCOPE_LISTINGS_DELETE = 'listings_d';
    const SCOPE_TRANSACT_READ = 'transactions_r';
    const SCOPE_TRANSACT_WRITE = 'transactions_w';
    const SCOPE_BILLING_READ = 'billing_r';
    const SCOPE_PROFILE_READ = 'profile_r';
    const SCOPE_PROFILE_WRITE = 'profile_w';
    const SCOPE_ADDRESS_READ = 'address_r';
    const SCOPE_ADDRESS_WRITE = 'address_w';
    const SCOPE_FAVOURITES = 'favorites_rw';
    const SCOPE_SHOPS = 'shops_rw';
    const SCOPE_CART = 'cart_rw';
    const SCOPE_RECOMMEND = 'recommend_rw';
    const SCOPE_FEEDBACK = 'feedback_r';
    const SCOPE_TREASURY_READ = 'treasury_r';
    const SCOPE_TREASURY_WRITE = 'treasury_w';

    protected $scopes = [];

    protected $baseApiUri = 'https://openapi.etsy.com/v2/';
    protected $accessTokenEndpoint = 'https://openapi.etsy.com/v2/oauth/access_token';

    /**
     * {@inheritdoc}
     */
    public function getRequestTokenEndpoint()
    {
        $uri = new Url($this->baseApiUri . 'oauth/request_token');
        if (count($this->getScopes())) {
            $uri->getQuery()->modify('scope=' . implode('%20', $this->getScopes()));
        }

        return $uri;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthorizationUri(array $additionalParameters = [])
    {
        $token = $this->requestRequestToken();
        $extraParam = $token->getExtraParams();

        if (!isset($extraParam[ 'login_url' ])) {
            throw new Exception('Unable to retrieve login_url!');
        }

        return new Url($extraParam[ 'login_url' ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function parseRequestTokenResponse($responseBody)
    {
        parse_str($responseBody, $data);

        if (null === $data || !is_array($data)) {
            throw new TokenResponseException('Unable to parse response.');
        } elseif (!isset($data[ 'oauth_callback_confirmed' ]) || $data[ 'oauth_callback_confirmed' ] !== 'true') {
            throw new TokenResponseException('Error in retrieving token.');
        }

        return $this->parseAccessTokenResponse($responseBody);
    }

    /**
     * {@inheritdoc}
     */
    protected function parseAccessTokenResponse($responseBody)
    {
        parse_str($responseBody, $data);

        if (null === $data || !is_array($data)) {
            throw new TokenResponseException('Unable to parse response.');
        } elseif (isset($data[ 'error' ])) {
            throw new TokenResponseException('Error in retrieving token: "' . $data[ 'error' ] . '"');
        }

        $token = new StdOAuth1Token();

        $token->setRequestToken($data[ 'oauth_token' ]);
        $token->setRequestTokenSecret($data[ 'oauth_token_secret' ]);
        $token->setAccessToken($data[ 'oauth_token' ]);
        $token->setAccessTokenSecret($data[ 'oauth_token_secret' ]);

        $token->setEndOfLife(StdOAuth1Token::EOL_NEVER_EXPIRES);
        unset($data[ 'oauth_token' ], $data[ 'oauth_token_secret' ]);
        $token->setExtraParams($data);

        return $token;
    }

    /**
     * Set the scopes for permissions
     *
     * @see https://www.etsy.com/developers/documentation/getting_started/oauth#section_permission_scopes
     *
     * @param array $scopes
     *
     * @return $this
     */
    public function setScopes(array $scopes)
    {
        if (!is_array($scopes)) {
            $scopes = [];
        }

        $this->scopes = $scopes;

        return $this;
    }

    /**
     * Return the defined scopes
     *
     * @return array
     */
    public function getScopes()
    {
        return $this->scopes;
    }
}
