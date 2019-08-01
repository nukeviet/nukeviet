<?php

/**
 * OAuth service factory.
 *
 * @category   OAuth
 * @author     And <and.webdev@gmail.com>
 */

namespace OAuth;

use Buzz\Browser;
use Buzz\Client\ClientInterface;
use Buzz\Client\Curl;
use OAuth\Common\Exception\Exception;

class HTTPTransporterFactory
{

    /**
     * Build HTTP Browser (Transporter) from given arguments
     *
     * @param $client
     * @param array $configuration
     * @param array $constructorArguments
     *
     * @return Browser
     */
    public static function buildTransporter($client, array $configuration = [], array $constructorArguments = [])
    {
        return new Browser(self::buildClient($client, $configuration, $constructorArguments), null);
    }

    /**
     * Build HTTP Buzz Client from given arguments (or just configure it)
     *
     * @param $client
     * @param array $configuration
     * @param array $constructorArguments
     *
     * @return ClientInterface
     *
     * @throws Common\Exception\Exception
     */
    public static function buildClient($client, array $configuration = [], array $constructorArguments = [])
    {
        // If client argument is a string, use autoloader to class from Buzz ns
        if (is_string($client)) {
            $possibleClass = '\\Buzz\\Client\\' . $client;
            if (!class_exists($possibleClass)) {
                throw new Exception("HTTP transporter class \"$possibleClass\" cannot be found!");
            }

            $reflClass = new \ReflectionClass($possibleClass);
            $client = $reflClass->newInstanceArgs($constructorArguments);
        }

        if (!$client instanceof ClientInterface) {
            throw new Exception('Client must be implement \\Buzz\\Client\\ClientInterface interface!');
        }

        // Well, we have valid client, just need to configure it at this point
        self::configureClient($client, $configuration);

        return $client;
    }

    protected static function configureClient(ClientInterface $client, array $configuration)
    {
        foreach ($configuration as $key => $value) {
            // Setter
            if (method_exists($client, 'set' . $key)) {
                $client->{'set' . $key}($value);
                unset($configuration[ $key ]);
            } elseif ($client instanceof Curl) {
                $client->setOption($key, $value);
                unset($configuration[ $key ]);
            }
        }

        $configuration = array_filter($configuration);

        if (count($configuration)) {
            throw new Exception('Configuration options "' . join(', ', array_keys($configuration)) . '" is unknown!');
        }
    }
}
