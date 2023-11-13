<?php
/**
 * Zalo © 2019
 *
 */

namespace Zalo\HttpClients;

/**
 * Interface ZaloHttpClientInterface
 *
 * @package Zalo
 */
interface ZaloHttpClientInterface
{
    /**
     * Sends a request to the server and returns the raw response.
     *
     * @param string $url     The endpoint to send the request to.
     * @param string $method  The request method.
     * @param string $body    The body of the request.
     * @param array  $headers The request headers.
     * @param int    $timeOut The timeout in seconds for the request.
     *
     * @return \Zalo\Http\GraphRawResponse Raw response from the server.
     *
     * @throws \Zalo\Exceptions\ZaloSDKException
     */
    public function send($url, $method, $body, array $headers, $timeOut);
}
