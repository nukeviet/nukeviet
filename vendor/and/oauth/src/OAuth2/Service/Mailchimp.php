<?php

namespace OAuth\OAuth2\Service;

use OAuth\Common\Http\Exception\TokenResponseException;
use OAuth\Common\Http\Url;
use OAuth\Common\Token\TokenInterface;
use OAuth\OAuth2\Token\StdOAuth2Token;

class Mailchimp extends AbstractService
{

    protected $authorizationEndpoint = 'https://login.mailchimp.com/oauth2/authorize';
    protected $accessTokenEndpoint = 'https://login.mailchimp.com/oauth2/token';
    protected $authorizationMethod = self::AUTHORIZATION_METHOD_QUERY_STRING_V3;
    protected $apiVersion = '2.0';

    public function initialize()
    {
        if (!$this->baseApiUri && $this->getStorage()->hasAccessToken($this->service())) {
            $this->setBaseApiUri($this->getStorage()->retrieveAccessToken($this->service()));
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function parseAccessTokenResponse($responseBody)
    {
        // Parse JSON
        $data = json_decode($responseBody, true);

        // Do validation.
        if (null === $data || !is_array($data)) {
            throw new TokenResponseException('Unable to parse response.');
        } elseif (isset($data[ 'error' ])) {
            throw new TokenResponseException('Error in retrieving token: "' . $data[ 'error' ] . '"');
        }

        // Create token object.
        $token = new StdOAuth2Token($data[ 'access_token' ]);

        // Set the right API endpoint.
        $this->setBaseApiUri($token);

        // Mailchimp tokens evidently never expire...
        $token->setEndOfLife(StdOAuth2Token::EOL_NEVER_EXPIRES);

        return $token;
    }

    /**
     * {@inheritdoc}
     */
    public function request($path, array $body = [], $method = 'GET', array $extraHeaders = [])
    {
        if (is_null($this->baseApiUri)) {
            $this->setBaseApiUri($this->storage->retrieveAccessToken($this->service()));
        }

        return parent::request($path, $body, $method, $extraHeaders);
    }

    /**
     * Set the right base endpoint.
     *
     * @param \OAuth\Common\Token\TokenInterface $token
     *
     * @return $this
     */
    protected function setBaseApiUri(TokenInterface $token)
    {
        // Make request uri.
        $endpoint = 'https://login.mailchimp.com/oauth2/metadata?oauth_token=' . $token->getAccessToken();

        // Grab meta data about the token.
        $response = $this->httpRequest($endpoint, [], [], 'GET');

        // Parse JSON.
        $meta = json_decode($response, true);

        // Set base api uri.
        $this->baseApiUri = new Url('https://' . $meta[ 'dc' ] . '.api.mailchimp.com/{apiVersion}/');

        // Allow chaining.
        return $this;
    }
}
