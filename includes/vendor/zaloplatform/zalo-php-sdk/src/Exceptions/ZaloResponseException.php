<?php
/**
 * Zalo © 2019
 *
 */

namespace Zalo\Exceptions;

use Zalo\ZaloResponse;

/**
 * Class ZaloResponseException
 *
 * @package Zalo
 */
class ZaloResponseException extends ZaloSDKException
{

    /**
     * @var ZaloResponse The response that threw the exception.
     */
    protected $response;

    /**
     * @var array Decoded response.
     */
    protected $responseData;

    /**
     * Creates a ZaloResponseException.
     *
     * @param ZaloResponse $response The response that threw the exception.
     * @param ZaloSDKException $previousException The more detailed exception.
     */
    public function __construct(ZaloResponse $response, ZaloSDKException $previousException = null)
    {
        $defaultErrorCode = -1;
        $defaultErrorMessage = 'Unknown error from Graph.';
        if ($previousException !== null) {
            $defaultErrorCode = $previousException->getCode();
            $defaultErrorMessage = $previousException->getMessage();
        }

        $this->response = $response;
        $this->responseData = $response->getDecodedBody();
        $errorCode = $this->get('error', $defaultErrorCode);
        $errorMessage = $this->get('message', $defaultErrorMessage);
        parent::__construct($errorMessage, $errorCode, $previousException);
    }

    /**
     * A factory for creating the appropriate exception based on the response from Graph.
     *
     * @param ZaloResponse $response The response that threw the exception.
     *
     * @return ZaloResponseException
     */
    public static function create(ZaloResponse $response)
    {
        $data = $response->getDecodedBody();

        $code = isset($data['error']) ? $data['error'] : -1;
        $message = isset($data['message']) ? $data['message'] : null;
        if (!$message) {
            $message = isset($data['error_name']) ? $data['error_name'] : 'Unknown error from Graph.';
        }
        if (!$message) {
            $message = 'Unknown error from Graph.';
        }
        if ($code < 0) {
            return new static($response, new ZaloOAException($message, $code));
        }
        return new static($response, new ZaloOtherException($message, $code));
    }

    /**
     * Checks isset and returns that or a default value.
     *
     * @param string $key
     * @param mixed $default
     *
     * @return mixed
     */
    private function get($key, $default = null)
    {
        if (isset($this->responseData['error'][$key])) {
            return $this->responseData['error'][$key];
        }

        return $default;
    }

    /**
     * Returns the HTTP status code
     *
     * @return int
     */
    public function getHttpStatusCode()
    {
        return $this->response->getHttpStatusCode();
    }

    /**
     * Returns the sub-error code
     *
     * @return int
     */
    public function getSubErrorCode()
    {
        return $this->get('error_subcode', -1);
    }

    /**
     * Returns the error type
     *
     * @return string
     */
    public function getErrorType()
    {
        return $this->get('type', '');
    }

    /**
     * Returns the raw response used to create the exception.
     *
     * @return string
     */
    public function getRawResponse()
    {
        return $this->response->getBody();
    }

    /**
     * Returns the decoded response used to create the exception.
     *
     * @return array
     */
    public function getResponseData()
    {
        return $this->responseData;
    }

    /**
     * Returns the response entity used to create the exception.
     *
     * @return ZaloResponse
     */
    public function getResponse()
    {
        return $this->response;
    }

}
