<?php

namespace OAuth\OAuth2\Service;

use Buzz\Browser;
use OAuth\Common\Consumer\CredentialsInterface;
use OAuth\Common\Exception\Exception;
use OAuth\Common\Http\Exception\TokenResponseException;
use OAuth\Common\Http\Url;
use OAuth\Common\Service\AbstractService as BaseAbstractService;
use OAuth\Common\Storage\TokenStorageInterface;
use OAuth\Common\Token\AbstractToken;
use OAuth\Common\Token\Exception\ExpiredTokenException;
use OAuth\Common\Token\TokenInterface;
use OAuth\OAuth2\Service\Exception\InvalidAuthorizationStateException;
use OAuth\OAuth2\Service\Exception\InvalidScopeException;
use OAuth\OAuth2\Service\Exception\MissingRefreshTokenException;

abstract class AbstractService extends BaseAbstractService implements ServiceInterface
{

    /** @const OAUTH_VERSION */
    const OAUTH_VERSION = 2;
    /** @var array */
    protected $scopes;
    /** @var bool */
    protected $stateParameterInAuthUrl;
    /** @var string */
    protected $apiVersion;
    /** @var int */
    protected $authorizationMethod = self::AUTHORIZATION_METHOD_HEADER_OAUTH;
    /** @var array */
    protected $extraApiHeaders = [];
    /** @var array */
    protected $extraOAuthHeaders = [];

    /**
     * @param CredentialsInterface $credentials
     * @param \Buzz\Browser| $httpTransporter
     * @param TokenStorageInterface $storage
     * @param array $scopes
     * @param Url|string|null $baseApiUri
     * @param bool $stateParameterInAuthUrl
     * @param string $apiVersion
     *
     * @throws InvalidScopeException
     */
    public function __construct(
        CredentialsInterface $credentials,
        Browser $httpTransporter,
        TokenStorageInterface $storage,
        array $scopes = [],
        $baseApiUri = null,
        $apiVersion = "",
        $stateParameterInAuthUrl = false
    ) {
        parent::__construct($credentials, $httpTransporter, $storage, $baseApiUri);
        $this->stateParameterInAuthUrl = $stateParameterInAuthUrl;

        foreach ($scopes as $scope) {
            if (!$this->isValidScope($scope)) {
                throw new InvalidScopeException('Scope ' . $scope . ' is not valid for service ' . get_class($this));
            }
        }

        $this->scopes = $scopes;

        if ($apiVersion) {
            $this->apiVersion = $apiVersion;
        }

        if ($this->baseApiUri) {
            $this->injectApiVersionToUri($this->baseApiUri);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthorizationEndpoint()
    {
        return $this->injectApiVersionToUri(parent::getAuthorizationEndpoint());
    }

    /**
     * {@inheritdoc}
     */
    public function getAccessTokenEndpoint()
    {
        return $this->injectApiVersionToUri(parent::getAccessTokenEndpoint());
    }

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

    /**
     * {@inheritdoc}
     */
    public function getAuthorizationUri(array $additionalParameters = [])
    {
        $parameters = array_merge(
            [
                'type'          => 'web_server',
                'client_id'     => $this->credentials->getConsumerId(),
                'redirect_uri'  => $this->credentials->getCallbackUrl(),
                'response_type' => 'code',
            ],
            $additionalParameters
        );

        $parameters[ 'scope' ] = implode(' ', $this->scopes);

        if ($this->needsStateParameterInAuthUrl()) {
            if (!isset($parameters[ 'state' ])) {
                $parameters[ 'state' ] = $this->generateAuthorizationState();
            }
            $this->storeAuthorizationState($parameters[ 'state' ]);
        }

        // Build the url
        $url = clone $this->getAuthorizationEndpoint();
        $url->getQuery()->modify($parameters);

        return $url;
    }

    /**
     * {@inheritdoc}
     * @throws ExpiredTokenException
     * @throws Exception
     */
    public function request($path, array $body = [], $method = 'GET', array $extraHeaders = [])
    {
        $uri = $this->determineRequestUriFromPath($path);
        /** @var AbstractToken $token */
        $token = $this->storage->retrieveAccessToken($this->service());

        if ($token->isExpired()) {
            throw new ExpiredTokenException(
                sprintf(
                    'Token expired on %s at %s',
                    date('m/d/Y', $token->getEndOfLife()),
                    date('h:i:s A', $token->getEndOfLife())
                )
            );
        }

        // add the token where it may be needed
        if (static::AUTHORIZATION_METHOD_HEADER_OAUTH === $this->getAuthorizationMethod()) {
            $extraHeaders = array_merge(['Authorization' => 'OAuth ' . $token->getAccessToken()], $extraHeaders);
        } elseif (static::AUTHORIZATION_METHOD_QUERY_STRING === $this->getAuthorizationMethod()) {
            $uri->getQuery()->modify(['access_token' => $token->getAccessToken()]);
        } elseif (static::AUTHORIZATION_METHOD_QUERY_STRING_V2 === $this->getAuthorizationMethod()) {
            $uri->getQuery()->modify(['oauth2_access_token' => $token->getAccessToken()]);
        } elseif (static::AUTHORIZATION_METHOD_QUERY_STRING_V3 === $this->getAuthorizationMethod()) {
            $uri->getQuery()->modify(['apikey' => $token->getAccessToken()]);
        } elseif (static::AUTHORIZATION_METHOD_HEADER_BEARER === $this->getAuthorizationMethod()) {
            $extraHeaders = array_merge(['Authorization' => 'Bearer ' . $token->getAccessToken()], $extraHeaders);
        }

        $extraHeaders = array_merge($this->getExtraApiHeaders(), $extraHeaders);

        return $this->httpRequest($uri, $body, $extraHeaders, $method);
    }

    /**
     * Accessor to the storage adapter to be able to retrieve tokens
     *
     * @return TokenStorageInterface
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * {@inheritdoc}
     */
    public function requestAccessToken($code, $state = null)
    {
        if (null !== $state) {
            $this->validateAuthorizationState($state);
        }

        $bodyParams = [
            'code'          => $code,
            'client_id'     => $this->credentials->getConsumerId(),
            'client_secret' => $this->credentials->getConsumerSecret(),
            'redirect_uri'  => $this->credentials->getCallbackUrl(),
            'grant_type'    => 'authorization_code',
        ];

        $responseBody = $this->httpRequest(
            $this->getAccessTokenEndpoint(),
            $bodyParams,
            $this->getExtraOAuthHeaders()
        );

        $token = $this->parseAccessTokenResponse($responseBody);
        $this->storage->storeAccessToken($this->service(), $token);

        return $token;
    }

    /**
     * Refreshes an OAuth2 access token.
     *
     * @param TokenInterface $token
     *
     * @return TokenInterface $token
     * @throws MissingRefreshTokenException
     */
    public function refreshAccessToken(TokenInterface $token)
    {
        $refreshToken = $token->getRefreshToken();

        if (empty($refreshToken)) {
            throw new MissingRefreshTokenException();
        }

        $parameters = [
            'grant_type'    => 'refresh_token',
            'type'          => 'web_server',
            'client_id'     => $this->credentials->getConsumerId(),
            'client_secret' => $this->credentials->getConsumerSecret(),
            'refresh_token' => $refreshToken,
        ];

        $responseBody = $this->httpRequest(
            $this->getAccessTokenEndpoint(),
            $parameters,
            $this->getExtraOAuthHeaders()
        );
        $token = $this->parseAccessTokenResponse($responseBody);
        $this->storage->storeAccessToken($this->service(), $token);

        return $token;
    }

    /**
     * Return whether or not the passed scope value is valid.
     *
     * @param string $scope
     *
     * @return bool
     */
    public function isValidScope($scope)
    {
        $reflectionClass = new \ReflectionClass(get_class($this));

        return in_array($scope, $reflectionClass->getConstants(), true);
    }

    /**
     * Check if the given service need to generate a unique state token to build the authorization url
     *
     * @return bool
     */
    public function needsStateParameterInAuthUrl()
    {
        return $this->stateParameterInAuthUrl;
    }

    /**
     * Validates the authorization state against a given one
     *
     * @param string $state
     *
     * @throws InvalidAuthorizationStateException
     */
    protected function validateAuthorizationState($state)
    {
        if ($this->retrieveAuthorizationState() !== $state) {
            throw new InvalidAuthorizationStateException();
        }
    }

    /**
     * Generates a random string to be used as state
     *
     * @return string
     */
    protected function generateAuthorizationState()
    {
        return md5(rand());
    }

    /**
     * Retrieves the authorization state for the current service
     *
     * @return string
     */
    protected function retrieveAuthorizationState()
    {
        return $this->storage->retrieveAuthorizationState($this->service());
    }

    /**
     * Stores a given authorization state into the storage
     *
     * @param string $state
     */
    protected function storeAuthorizationState($state)
    {
        $this->storage->storeAuthorizationState($this->service(), $state);
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
     * Returns a class constant from ServiceInterface defining the authorization method used for the API
     * Header is the sane default.
     *
     * @return int
     */
    protected function getAuthorizationMethod()
    {
        return $this->authorizationMethod;
    }

    /**
     * Returns api version string if is set else retrun empty string
     *
     * @return string
     */
    protected function getApiVersionString()
    {
        return !(empty($this->apiVersion)) ? "/" . $this->apiVersion : "";
    }

    /**
     * Replaces "/{apiVersion}" to configured proper api version, or remove version from URI if it's have not configured
     *
     * @param Url $uri
     *
     * @return Url
     */
    protected function injectApiVersionToUri(Url $uri)
    {
        $uri->setPath(str_replace('/{apiVersion}', $this->getApiVersionString(), '/' . urldecode($uri->getPath())));

        return $uri;
    }

    /**
     * {@inheritdoc}
     */
    public function isRequestArgumentsPassed(array $request)
    {
        return !empty($request[ 'code' ]);
    }

    /**
     * {@inheritdoc}
     */
    public function retrieveAccessTokenByReqArgs(array $request)
    {
        if (!$this->isRequestArgumentsPassed($request)) {
            throw new Exception('Code has not passed to request arguments!');
        }

        $code = $request[ 'code' ];
        $state = !empty($request[ 'state' ]) ? $request[ 'state' ] : null;

        $this->requestAccessToken($code, $state);

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
}
