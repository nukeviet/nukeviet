<?php

/**
 * OAuth service factory.
 *
 * PHP version 5.4
 *
 * @category   OAuth
 * @author     David Desberg <david@daviddesberg.com>
 * @author     Pieter Hordijk <info@pieterhordijk.com>
 * @copyright  Copyright (c) 2013 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace OAuth;

use Buzz\Browser;
use Buzz\Client\ClientInterface;
use OAuth\Common\Consumer\CredentialsInterface;
use OAuth\Common\Exception\Exception;
use OAuth\Common\Http\Url;
use OAuth\Common\Storage\TokenStorageInterface;
use OAuth\OAuth1\Signature\Signature;

class ServiceFactory
{

    /**
     * @var Browser
     */
    protected $httpTransporter;

    /**
     * @var array
     */
    protected $serviceClassMap = [
        'OAuth1' => [],
        'OAuth2' => []
    ];

    /**
     * @var array
     */
    protected $serviceBuilders = [
        'OAuth2' => 'buildV2Service',
        'OAuth1' => 'buildV1Service',
    ];

    public static function construct()
    {
        return new static();
    }

    /**
     * @param ClientInterface|Browser|string $httpTransporter
     * @param array $configuration Client configuration
     * @param array $constructorArguments Client constructor arguments
     *
     * @return ServiceFactory
     */
    public function setHttpTransporter($httpTransporter, array$configuration = [], array $constructorArguments = [])
    {
        $this->httpTransporter = $httpTransporter instanceof Browser ?
            $httpTransporter :
            HTTPTransporterFactory::buildTransporter($httpTransporter, $configuration, $constructorArguments);

        return $this;
    }

    /**
     * @return Browser|null
     */
    public function getHttpTransporter()
    {
        // If not create httpTransporter, then we create it now
        // Common parameters are pass requirements in common situations
        if (!$this->httpTransporter) {
            $this->setHttpTransporter(
                'FileGetContents',
                [
                    'ignoreErrors' => true,
                    'maxRedirects' => 5,
                    'timeout'      => 5,
                    'verifyPeer'   => false
                ]
            );
        }

        return $this->httpTransporter;
    }

    /**
     * Register a custom service to classname mapping.
     *
     * @param string $serviceName Name of the service
     * @param string $className Class to instantiate     *
     *
     * @return ServiceFactory
     *
     * @throws Exception If the class is nonexistent or does not implement a valid ServiceInterface
     */
    public function registerService($serviceName, $className)
    {
        if (!class_exists($className)) {
            throw new Exception(sprintf('Service class %s does not exist.', $className));
        }

        $reflClass = new \ReflectionClass($className);

        foreach (['OAuth2', 'OAuth1'] as $version) {
            if ($reflClass->implementsInterface('OAuth\\' . $version . '\\Service\\ServiceInterface')) {
                $this->serviceClassMap[ $version ][ ucfirst($serviceName) ] = $className;

                return $this;
            }
        }

        throw new Exception(sprintf('Service class %s must implement ServiceInterface.', $className));
    }

    /**
     * Builds and returns oauth services
     * It will first try to build an OAuth2 service and if none found it will try to build an OAuth1 service
     *
     * @param string $serviceName Name of service to create
     * @param CredentialsInterface $credentials
     * @param TokenStorageInterface $storage
     * @param array|null $scopes If creating an oauth2 service, array of scopes
     * @param null|\OAuth\Common\Http\Url $baseApiUri
     * @param string $apiVersion version of the api call
     *
     * @return \OAuth\OAuth1\Service\ServiceInterface|\OAuth\OAuth2\Service\ServiceInterface
     */
    public function createService(
        $serviceName,
        CredentialsInterface $credentials,
        TokenStorageInterface $storage,
        $scopes = [],
        Url $baseApiUri = null,
        $apiVersion = ""
    ) {
        foreach ($this->serviceBuilders as $version => $buildMethod) {
            $fullyQualifiedServiceName = $this->getFullyQualifiedServiceName($serviceName, $version);

            if (class_exists($fullyQualifiedServiceName)) {
                return $this->$buildMethod(
                    $fullyQualifiedServiceName,
                    $credentials,
                    $storage,
                    $scopes,
                    $baseApiUri,
                    $apiVersion
                );
            }
        }

        return null;
    }

    /**
     * Gets the fully qualified name of the service
     *
     * @param string $serviceName The name of the service of which to get the fully qualified name
     * @param string $type The type of the service to get (either OAuth1 or OAuth2)
     *
     * @return string The fully qualified name of the service
     */
    private function getFullyQualifiedServiceName($serviceName, $type)
    {
        $serviceName = ucfirst($serviceName);

        if (isset($this->serviceClassMap[ $type ][ $serviceName ])) {
            return $this->serviceClassMap[ $type ][ $serviceName ];
        }

        return '\\' . __NAMESPACE__ . '\\' . $type . '\\Service\\' . $serviceName;
    }

    /**
     * Builds v2 services
     *
     * @param string $serviceName The fully qualified service name
     * @param CredentialsInterface $credentials
     * @param TokenStorageInterface $storage
     * @param array|null $scopes Array of scopes for the service
     * @param Url|null $baseApiUri
     * @param string $apiVersion
     *
     * @return \OAuth\OAuth2\Service\ServiceInterface
     */
    private function buildV2Service(
        $serviceName,
        CredentialsInterface $credentials,
        TokenStorageInterface $storage,
        array $scopes,
        Url $baseApiUri = null,
        $apiVersion = ""
    ) {
        return new $serviceName(
            $credentials,
            $this->getHttpTransporter(),
            $storage,
            $this->resolveScopes($serviceName, $scopes),
            $baseApiUri,
            $apiVersion
        );
    }

    /**
     * Resolves scopes for v2 services
     *
     * @param string $serviceName The fully qualified service name
     * @param array $scopes List of scopes for the service
     *
     * @return array List of resolved scopes
     */
    private function resolveScopes($serviceName, array $scopes)
    {
        $reflClass = new \ReflectionClass($serviceName);
        $constants = $reflClass->getConstants();

        $resolvedScopes = [];
        foreach ($scopes as $scope) {
            $key = strtoupper('SCOPE_' . $scope);

            if (array_key_exists($key, $constants)) {
                $resolvedScopes[ ] = $constants[ $key ];
            } else {
                $resolvedScopes[ ] = $scope;
            }
        }

        return $resolvedScopes;
    }

    /**
     * Builds v1 services
     *
     * @param string $serviceName The fully qualified service name
     * @param CredentialsInterface $credentials
     * @param TokenStorageInterface $storage
     * @param array $scopes
     * @param Url $baseApiUri
     *
     * @return \OAuth\OAuth1\Service\ServiceInterface
     *
     * @throws Exception
     */
    private function buildV1Service(
        $serviceName,
        CredentialsInterface $credentials,
        TokenStorageInterface $storage,
        $scopes,
        Url $baseApiUri = null
    ) {
        if (!empty($scopes)) {
            throw new Exception(
                'Scopes passed to ServiceFactory::createService but an OAuth1 service was requested.'
            );
        }

        return new $serviceName(
            $credentials,
            $this->getHttpTransporter(),
            $storage,
            new Signature($credentials),
            $baseApiUri
        );
    }
}
