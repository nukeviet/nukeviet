<?php
/**
 * Zalo © 2019
 *
 */

namespace Zalo\HttpClients;

use Zalo\HttpClients\ZaloCurlHttpClient;

/**
 * Class HttpClientsFactory
 *
 * @package Zalo
 */
class HttpClientsFactory {

    private function __construct() {
        // a factory constructor should never be invoked
    }

    /**
     * HTTP client generation.
     *
     * @param ZaloHttpClientInterface|Client|string|null $handler
     *
     * @throws Exception               
     * @throws InvalidArgumentException If the http client handler isn't "curl", "stream", or an instance of Zalo\HttpClients\ZaloHttpClientInterface.
     *
     * @return ZaloHttpClientInterface
     */
    public static function createHttpClient($handler) {
        if (!$handler) {
            return self::detectDefaultClient();
        }

        if ($handler instanceof ZaloHttpClientInterface) {
            return $handler;
        }

        if ('curl' === $handler) {
            if (!extension_loaded('curl')) {
                throw new Exception('The cURL extension must be loaded in order to use the "curl" handler.');
            }

            return new ZaloCurlHttpClient();
        }

        throw new InvalidArgumentException('The http client handler must be set to "curl" be an instance of Zalo\HttpClients\ZaloHttpClientInterface');
    }

    /**
     * Detect default HTTP client.
     *
     * @return ZaloHttpClientInterface
     */
    private static function detectDefaultClient() {
        return new ZaloCurlHttpClient();
    }

}
