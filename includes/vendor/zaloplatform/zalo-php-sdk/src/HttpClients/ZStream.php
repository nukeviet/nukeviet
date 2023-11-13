<?php
/**
 * Zalo © 2019
 *
 */

namespace Zalo\HttpClients;

/**
 * Class ZaloStream
 *
 * Abstraction for the procedural stream elements so that the functions can be
 * mocked and the implementation can be tested.
 *
 * @package Zalo
 */
class ZaloStream
{
    /**
     * @var resource Context stream resource instance
     */
    protected $stream;

    /**
     * @var array Response headers from the stream wrapper
     */
    protected $responseHeaders = [];

    /**
     * Make a new context stream reference instance
     *
     * @param array $options
     */
    public function streamContextCreate(array $options)
    {
        $this->stream = stream_context_create($options);
    }

    /**
     * The response headers from the stream wrapper
     *
     * @return array
     */
    public function getResponseHeaders()
    {
        return $this->responseHeaders;
    }

    /**
     * Send a stream wrapped request
     *
     * @param string $url
     *
     * @return mixed
     */
    public function fileGetContents($url)
    {
        $rawResponse = file_get_contents($url, false, $this->stream);
        $this->responseHeaders = $http_response_header ?: [];

        return $rawResponse;
    }
}
