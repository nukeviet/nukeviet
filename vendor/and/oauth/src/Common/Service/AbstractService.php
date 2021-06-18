<?php

namespace OAuth\Common\Service;

use Buzz\Browser;
use Buzz\Client\ClientInterface;
use Buzz\Exception\RequestException;
use OAuth\Common\Consumer\Credentials;
use OAuth\Common\Consumer\CredentialsInterface;
use OAuth\Common\Exception\Exception;
use OAuth\Common\Http\Exception\TokenResponseException;
use OAuth\Common\Http\Url;
use OAuth\Common\Storage\TokenStorageInterface;
use OAuth\UserData\ExtractorFactory;
use OAuth\UserData\ExtractorFactoryInterface;

/**
 * Abstract OAuth service, version-agnostic
 */
abstract class AbstractService implements ServiceInterface
{

    /** @var Credentials */
    protected $credentials;

    /** @var Browser */
    protected $httpTransporter;

    /** @var TokenStorageInterface */
    protected $storage;

    /** @var Url|null */
    protected $baseApiUri;

    /** @var string */
    protected $authorizationEndpoint = '';

    /** @var string */
    protected $accessTokenEndpoint = '';

    /** @var ExtractorFactory */
    protected static $extractorFactory;

    /**
     * @param CredentialsInterface $credentials
     * @param Browser $httpTransporter
     * @param TokenStorageInterface $storage
     * @param $baseApiUrl
     */
    public function __construct(
        CredentialsInterface $credentials,
        Browser $httpTransporter,
        TokenStorageInterface $storage,
        $baseApiUrl
    ) {
        $this->credentials = $credentials;
        $this->httpTransporter = $httpTransporter;
        $this->storage = $storage;

        if ($baseApiUrl) {
            $this->baseApiUri = new Url($baseApiUrl);
        } elseif (is_string($this->baseApiUri)) {
            $this->baseApiUri = new Url($this->baseApiUri);
        }

        $this->initialize();
    }

    public function initialize()
    {
    }

    public function getBaseApiUri($clone = true)
    {
        if (null === $this->baseApiUri) {
            throw new Exception(
                'An absolute URI must be passed to ServiceInterface::request as no baseApiUri is set.'
            );
        }

        return !$clone ? $this->baseApiUri : clone $this->baseApiUri;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthorizationEndpoint()
    {
        if (!$this->authorizationEndpoint) {
            throw new Exception('Authorization endpoint isn\'t defined!');
        }

        return new Url($this->authorizationEndpoint);
    }

    /**
     * {@inheritdoc}
     */
    public function getAccessTokenEndpoint()
    {
        if (!$this->accessTokenEndpoint) {
            throw new Exception('Access token endpoint isn\'t defined!');
        }

        return new Url($this->accessTokenEndpoint);
    }

    /**
     * @param Url|string $path
     *
     * @return Url
     * @throws Exception
     */
    public function determineRequestUriFromPath($path)
    {
        if ($path instanceof Url) {
            $uri = $path;
        } elseif (stripos($path, 'http://') === 0 || stripos($path, 'https://') === 0) {
            $uri = new Url($path);
        } else {
            $path = (string) $path;
            $uri = $this->getBaseApiUri();

            if (false !== strpos($path, '?')) {
                $parts = explode('?', $path, 2);
                $path = $parts[ 0 ];
                $query = $parts[ 1 ];
                $uri->setQuery($query);
            }

            // Add path
            $uri->getPath()->append("$path");

            // Clean up double slashes
            $uri->setPath(array_filter($uri->getPath()->toArray()));
        }

        return $uri;
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
     * Accessor to the storage adapter to be able to make request
     *
     * @return ClientInterface
     */
    public function getHTTPTransporter()
    {
        return $this->httpTransporter;
    }

    /**
     * {@inheritdoc}
     */
    public function requestJSON($uri, array $body = [], $method = 'GET', array $extraHeaders = [])
    {
        $json = $this->request($uri, $body, $method, $extraHeaders);
        if (!is_string($json) or (
                0 !== strpos($json, '{') and
                !in_array($json, ['true', 'false'])
            )
        ) {
            throw new Exception('Wrong JSON! Got - ' . $json);
        }

        return json_decode($json, true);
    }

    /**
     * {@inheritdoc}
     */
    public function httpRequest($uri, array $body = [], array $headers = [], $method = 'POST')
    {
        try {
            $response = $this->httpTransporter->submit($uri, $body, $method, $headers);
        } catch (RequestException $e) {
            throw new TokenResponseException($e->getMessage() ? $e->getMessage() : 'Failed to request resource.');
        }

        return $response->getContent();
    }

    /**
     * @return string
     */
    public function service()
    {
        // get class name without backslashes
        return preg_replace('/^.*\\\\/', '', get_class($this));
    }

    /**
     * Get _POST + _GET
     *
     * @return array
     */
    protected function getGlobalRequestArguments()
    {
        return array_merge($_GET, (!empty($_POST) ? $_POST : []));
    }

    /**
     * {@inheritdoc}
     */
    public function constructExtractor(ExtractorFactoryInterface $extractorFactory = null)
    {
        if (!$extractorFactory) {
            if (!self::$extractorFactory) {
                self::$extractorFactory = new ExtractorFactory();
            }

            $extractorFactory = self::$extractorFactory;
        }

        return $extractorFactory->get($this);
    }

    /**
     * {@inheritdoc}
     */
    public function redirectToAuthorizationUri()
    {
        $url = $this->getAuthorizationUri();
        header('Location: ' . $url, true);

        return $this;
    }
}
