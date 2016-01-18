<?php

namespace OAuth\OAuth1\Service;

use Buzz\Browser;
use OAuth\Common\Consumer\CredentialsInterface;
use OAuth\Common\Exception\Exception;
use OAuth\Common\Http\Exception\TokenResponseException;
use OAuth\Common\Http\Url;
use OAuth\Common\Service\AbstractService as BaseAbstractService;
use OAuth\Common\Storage\TokenStorageInterface;
use OAuth\OAuth1\Signature\SignatureInterface;
use OAuth\OAuth1\Token\StdOAuth1Token;
use OAuth\OAuth1\Token\TokenInterface;

abstract class AbstractService extends BaseAbstractService implements ServiceInterface
{

    /** @const OAUTH_VERSION */
    const OAUTH_VERSION = 1;

    /** @var SignatureInterface */
    protected $signature;

    /** @var string */
    protected $signatureMethod = 'HMAC-SHA1';

    /** @var string */
    protected $requestTokenEndpoint = '';

    /** @var array */
    protected $extraApiHeaders = [];

    /** @var array */
    protected $extraOAuthHeaders = [];

    /**
     * {@inheritDoc}
     */
    public function __construct(
        CredentialsInterface $credentials,
        Browser $httpTransporter,
        TokenStorageInterface $storage,
        SignatureInterface $signature,
        $baseApiUri = null
    ) {
        parent::__construct($credentials, $httpTransporter, $storage, $baseApiUri);

        $this->signature = $signature;
        $this->signature->setHashingAlgorithm($this->getSignatureMethod());
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestTokenEndpoint()
    {
        return new Url($this->requestTokenEndpoint);
    }

    /**
     * {@inheritDoc}
     */
    public function requestRequestToken()
    {
        $authorizationHeader = ['Authorization' => $this->buildAuthorizationHeaderForTokenRequest()];
        $headers = array_merge($authorizationHeader, $this->getExtraOAuthHeaders());

        $responseBody = $this->httpRequest($this->getRequestTokenEndpoint(), [], $headers);

        $token = $this->parseRequestTokenResponse($responseBody);
        $this->storage->storeAccessToken($this->service(), $token);

        return $token;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthorizationUri(array $additionalParameters = [])
    {
        if (!isset($additionalParameters[ 'oauth_token' ])) {
            $additionalParameters[ 'oauth_token' ] = $this->requestRequestToken()->getRequestToken();
        }

        // Build the url
        $url = $this->getAuthorizationEndpoint();
        $url->getQuery()->modify($additionalParameters);

        return $url;
    }

    /**
     * {@inheritDoc}
     */
    public function requestAccessToken($token, $verifier, $tokenSecret = null)
    {
        if (is_null($tokenSecret)) {
            /** @var TokenInterface $storedRequestToken */
            $storedRequestToken = $this->storage->retrieveAccessToken($this->service());
            $tokenSecret = $storedRequestToken->getRequestTokenSecret();
        }
        $this->signature->setTokenSecret($tokenSecret);

        $bodyParams = [
            'oauth_verifier' => $verifier,
        ];

        $authorizationHeader = [
            'Authorization' => $this->buildAuthorizationHeaderForAPIRequest(
                'POST',
                $this->getAccessTokenEndpoint(),
                $this->storage->retrieveAccessToken($this->service()),
                $bodyParams
            )
        ];

        $headers = array_merge($authorizationHeader, $this->getExtraOAuthHeaders());

        $responseBody = $this->httpRequest($this->getAccessTokenEndpoint(), $bodyParams, $headers);

        $token = $this->parseAccessTokenResponse($responseBody);
        $this->storage->storeAccessToken($this->service(), $token);

        return $token;
    }

    /**
     * {@inheritdoc}
     */
    public function request($path, array $body = [], $method = 'GET', array $extraHeaders = [])
    {
        $uri = $this->determineRequestUriFromPath($path);

        /** @var $token StdOAuth1Token */
        $token = $this->storage->retrieveAccessToken($this->service());
        $extraHeaders = array_merge($this->getExtraApiHeaders(), $extraHeaders);
        $authorizationHeader = [
            'Authorization' => $this->buildAuthorizationHeaderForAPIRequest($method, $uri, $token, $body)
        ];
        $headers = array_merge($authorizationHeader, $extraHeaders);

        return $this->httpRequest($uri, $body, $headers, $method);
    }

    /**
     * Return any additional headers always needed for this service implementation's OAuth calls.
     *
     * @return array
     */
    protected function getExtraOAuthHeaders()
    {
        return $this->extraOAuthHeaders;
    }

    /**
     * Return any additional headers always needed for this service implementation's API calls.
     *
     * @return array
     */
    protected function getExtraApiHeaders()
    {
        return $this->extraApiHeaders;
    }

    /**
     * Builds the authorization header for getting an access or request token.
     *
     * @param array $extraParameters
     *
     * @return string
     */
    protected function buildAuthorizationHeaderForTokenRequest(array $extraParameters = [])
    {
        $parameters = $this->getBasicAuthorizationHeaderInfo();
        $parameters = array_merge($parameters, $extraParameters);
        $parameters[ 'oauth_signature' ] = $this->signature->getSignature(
            $this->getRequestTokenEndpoint(),
            $parameters,
            'POST'
        );

        $authorizationHeader = 'OAuth ';
        $delimiter = '';
        foreach ($parameters as $key => $value) {
            $authorizationHeader .= $delimiter . rawurlencode($key) . '="' . rawurlencode($value) . '"';

            $delimiter = ', ';
        }

        return $authorizationHeader;
    }

    /**
     * Builds the authorization header for an authenticated API request
     *
     * @param string $method
     * @param Url $uri The uri the request is headed
     * @param TokenInterface $token
     * @param array $bodyParams Request body if applicable (key/value pairs)
     *
     * @return string
     */
    protected function buildAuthorizationHeaderForAPIRequest(
        $method,
        Url $uri,
        TokenInterface $token,
        $bodyParams = null
    ) {
        $this->signature->setTokenSecret($token->getAccessTokenSecret());
        $authParameters = $this->getBasicAuthorizationHeaderInfo();
        if (isset($authParameters[ 'oauth_callback' ])) {
            unset($authParameters[ 'oauth_callback' ]);
        }

        $authParameters = array_merge($authParameters, ['oauth_token' => $token->getAccessToken()]);

        $signatureParams = (is_array($bodyParams)) ? array_merge($authParameters, $bodyParams) : $authParameters;
        $authParameters[ 'oauth_signature' ] = $this->signature->getSignature($uri, $signatureParams, $method);

        $authorizationHeader = 'OAuth ';
        $delimiter = '';

        foreach ($authParameters as $key => $value) {
            $authorizationHeader .= $delimiter . rawurlencode($key) . '="' . rawurlencode($value) . '"';
            $delimiter = ', ';
        }

        return $authorizationHeader;
    }

    /**
     * Builds the authorization header array.
     *
     * @return array
     */
    protected function getBasicAuthorizationHeaderInfo()
    {
        $dateTime = new \DateTime();
        $headerParameters = [
            'oauth_callback'         => $this->credentials->getCallbackUrl(),
            'oauth_consumer_key'     => $this->credentials->getConsumerId(),
            'oauth_nonce'            => $this->generateNonce(),
            'oauth_signature_method' => $this->getSignatureMethod(),
            'oauth_timestamp'        => $dateTime->format('U'),
            'oauth_version'          => $this->getVersion(),
        ];

        return $headerParameters;
    }

    /**
     * Pseudo random string generator used to build a unique string to sign each request
     *
     * @param int $length
     *
     * @return string
     */
    protected function generateNonce($length = 32)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';

        $nonce = '';
        $maxRand = strlen($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $nonce .= $characters[ rand(0, $maxRand) ];
        }

        return $nonce;
    }

    /**
     * @return string
     */
    protected function getSignatureMethod()
    {
        return $this->signatureMethod;
    }

    /**
     * This returns the version used in the authorization header of the requests
     *
     * @return string
     */
    protected function getVersion()
    {
        return '1.0';
    }

    /**
     * {@inheritdoc}
     */
    public function isRequestArgumentsPassed(array $request)
    {
        return !empty($request[ 'oauth_token' ]) and !empty($request[ 'oauth_verifier' ]);
    }

    /**
     * {@inheritdoc}
     */
    public function retrieveAccessTokenByReqArgs(array $request)
    {
        if (!$this->isRequestArgumentsPassed($request)) {
            throw new Exception('oauth_token and/or oauth_verifier has not passed to request arguments!');
        }

        $oauthToken = $request[ 'oauth_token' ];
        $oauthVerifier = $request[ 'oauth_verifier' ];

        $this->requestAccessToken($oauthToken, $oauthVerifier);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isGlobalRequestArgumentsPassed()
    {
        return $this->isRequestArgumentsPassed($this->getGlobalRequestArguments());
    }

    /**
     * {@inheritdoc}
     */
    public function retrieveAccessTokenByGlobReqArgs()
    {
        return $this->retrieveAccessTokenByReqArgs($this->getGlobalRequestArguments());
    }

    /**
     * {@inheritdoc}
     */
    public function getAccessToken()
    {
        return $this->storage->retrieveAccessToken($this->service());
    }

    /**
     * Parses the request token response and returns a TokenInterface.
     * This is only needed to verify the `oauth_callback_confirmed` parameter. The actual
     * parsing logic is contained in the access token parser.
     *
     * @abstract
     *
     * @param string $responseBody
     *
     * @return TokenInterface
     * @throws TokenResponseException
     */
    abstract protected function parseRequestTokenResponse($responseBody);

    /**
     * Parses the access token response and returns a TokenInterface.
     *
     * @abstract
     *
     * @param string $responseBody
     *
     * @return TokenInterface
     * @throws TokenResponseException
     */
    abstract protected function parseAccessTokenResponse($responseBody);
}
