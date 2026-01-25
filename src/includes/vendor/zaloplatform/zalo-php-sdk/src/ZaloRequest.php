<?php
/**
 * Zalo © 2019
 *
 */

namespace Zalo;

use Zalo\Authentication\AccessToken;
use Zalo\Authentication\ZaloToken;
use Zalo\Exceptions\ZaloSDKException;
use Zalo\FileUpload\ZaloFile;
use Zalo\Http\RequestBodyMultipart;
use Zalo\Http\RequestBodyRaw;
use Zalo\Http\RequestBodyUrlEncoded;
use Zalo\Url\ZaloUrlManipulator;

/**
 * Class Request
 *
 * @package Zalo
 */
class ZaloRequest
{
    /**
     * @var string|null The access token to use for this request.
     */
    protected $accessToken;

    /**
     * @var string The HTTP method for this request.
     */
    protected $method;

    /**
     * @var string The url for this request.
     */
    protected $url;

    /**
     * @var array The headers to send with this request.
     */
    protected $headers = [];

    /**
     * @var array The parameters to send with this request.
     */
    protected $params = [];

    /**
     * @var array The files to send with this request.
     */
    protected $files = [];

    /**
     * @var string ETag to send with this request.
     */
    protected $eTag;

    /**
     * @var string The content type to send with this request.
     */
    protected $contentType;

    /**
     * Creates a new Request entity.
     *
     * @param ZaloToken|string|null $accessToken
     * @param string|null $method
     * @param string|null $url
     * @param array|null $params
     * @param string|null $eTag
     *
     * @throws ZaloSDKException
     */
    public function __construct($accessToken = null, $method = null, $url = null, array $params = [], $eTag = null, $contentType = null)
    {
        $this->setAccessToken($accessToken);
        $this->setMethod($method);
        $this->setUrl($url);
        $this->setParams($params);
        $this->setETag($eTag);
        $this->setContentType($contentType);
    }

    /**
     * Set the access token for this request.
     *
     * @param ZaloToken|string|null
     *
     * @return ZaloRequest
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
        if ($accessToken instanceof ZaloToken) {
            $this->accessToken = $accessToken->getAccessToken();
        }
        return $this;
    }

    /**
     * Sets the access token with one harvested from a URL or POST params.
     *
     * @param string $accessToken The access token.
     *
     * @return ZaloRequest
     *
     * @throws ZaloSDKException
     */
    public function setAccessTokenFromParams($accessToken)
    {
        $existingAccessToken = $this->getAccessToken();
        if (!$existingAccessToken) {
            $this->setAccessToken($accessToken);
        } elseif ($accessToken !== $existingAccessToken) {
            throw new ZaloSDKException('Access token mismatch.');
        }

        return $this;
    }

    /**
     * Return the access token for this request.
     *
     * @return string|null
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Validate that an access token exists for this request.
     *
     * @throws ZaloSDKException
     */
    public function validateAccessToken()
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            throw new ZaloSDKException('You must provide an access token.');
        }
    }

    /**
     * Set the HTTP method for this request.
     *
     * @param string
     */
    public function setMethod($method)
    {
        $this->method = strtoupper($method);
    }

    /**
     * Return the HTTP method for this request.
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Validate that the HTTP method is set.
     *
     * @throws ZaloSDKException
     */
    public function validateMethod()
    {
        if (!$this->method) {
            throw new ZaloSDKException('HTTP method not specified.');
        }

        if (!in_array($this->method, ['GET', 'POST'])) {
            throw new ZaloSDKException('Invalid HTTP method specified.');
        }
    }

    /**
     * Set the url for this request.
     *
     * @param string
     *
     * @return ZaloRequest
     *
     * @throws ZaloSDKException
     */
    public function setUrl($url)
    {
        // Harvest the access token from the url to keep things in sync
        $params = ZaloUrlManipulator::getParamsAsArray($url);
        if (isset($params['access_token'])) {
            $this->setAccessTokenFromParams($params['access_token']);
        }

        // Clean the token & app secret proof from the url.
        $filterParams = ['access_token', 'appsecret_proof'];
        $this->url = ZaloUrlManipulator::removeParamsFromUrl($url, $filterParams);
        return $this;
    }

    /**
     * Generate and return the headers for this request.
     *
     * @return array
     */
    public function getHeaders()
    {
        $headers = static::getDefaultHeaders();

        if ($this->eTag) {
            $headers['If-None-Match'] = $this->eTag;
        }

        return array_merge($this->headers, $headers);
    }

    /**
     * Set the headers for this request.
     *
     * @param array $headers
     */
    public function setHeaders(array $headers)
    {
        $this->headers = array_merge($this->headers, $headers);
    }

    /**
     * Sets the eTag value.
     *
     * @param string $eTag
     */
    public function setETag($eTag)
    {
        $this->eTag = $eTag;
    }

    /**
     * Set the params for this request.
     *
     * @param array $params
     *
     * @return ZaloRequest
     *
     * @throws ZaloSDKException
     */
    public function setParams(array $params = [])
    {
        if (isset($params['access_token'])) {
            $this->setAccessTokenFromParams($params['access_token']);
        }

        // Don't let these buggers slip in.
        unset($params['access_token']);

        // @TODO Refactor code above with this
        $params = $this->sanitizeFileParams($params);
        $this->dangerouslySetParams($params);

        return $this;
    }

    /**
     * Set the params for this request without filtering them first.
     *
     * @param array $params
     *
     * @return ZaloRequest
     */
    public function dangerouslySetParams(array $params = [])
    {
        $this->params = array_merge($this->params, $params);
        if ($this->containsFileUploads()) {
            $this->params["access_token"] = $this->accessToken;
        }
        return $this;
    }

    /**
     * Iterate over the params and pull out the file uploads.
     *
     * @param array $params
     *
     * @return array
     */
    public function sanitizeFileParams(array $params)
    {
        foreach ($params as $key => $value) {
            if ($value instanceof ZaloFile) {
                $this->addFile($key, $value);
                unset($params[$key]);
            }
        }
        return $params;
    }

    /**
     * Add a file to be uploaded.
     *
     * @param string $key
     * @param ZaloFile $file
     */
    public function addFile($key, ZaloFile $file)
    {
        $this->files[$key] = $file;
    }

    /**
     * Removes all the files from the upload queue.
     */
    public function resetFiles()
    {
        $this->files = [];
    }

    /**
     * Get the list of files to be uploaded.
     *
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Let's us know if there is a file upload with this request.
     *
     * @return boolean
     */
    public function containsFileUploads()
    {
        return !empty($this->files);
    }

    /**
     * Returns the body of the request as multipart/form-data.
     *
     * @return RequestBodyMultipart
     */
    public function getMultipartBody()
    {
        $params = $this->getPostParams();
        return new RequestBodyMultipart($params, $this->files);
    }

    /**
     * Returns the body of the request as URL-encoded.
     *
     * @return RequestBodyUrlEncoded
     */
    public function getUrlEncodedBody()
    {
        $params = $this->getPostParams();
        return new RequestBodyUrlEncoded($params);
    }

    /**
     * Returns the body of the request as URL-encoded.
     *
     * @return RequestBodyRaw
     */
    public function getRawBody()
    {
        $params = $this->getPostParams();
        return new RequestBodyRaw($params);
    }

    /**
     * Generate and return the params for this request.
     *
     * @return array
     */
    public function getParams()
    {
        $params = $this->params;
        return $params;
    }

    /**
     * Only return params on POST requests.
     *
     * @return array
     */
    public function getPostParams()
    {
        if ($this->getMethod() === 'POST') {
            return $this->getParams();
        }
        return [];
    }

    /**
     * Generate and return the URL for this request.
     *
     * @return string
     */
    public function getUrl()
    {
        $this->validateMethod();
        $url = $this->url;
        if ($this->getMethod() !== 'POST') {
            $params = $this->getParams();
            $url = ZaloUrlManipulator::appendParamsToUrl($url, $params);
        } else {
            $params = $this->getParams();
            foreach ($params as $key => $value) {
                if ($key === 'upload_type') {
                    $url = ZaloUrlManipulator::appendParamsToUrl($url, [$key => $value]);
                }
            }
            $url = urldecode($url);
        }
        return $url;
    }

    /**
     * Return the default headers that every request should use.
     *
     * @return array
     */
    public static function getDefaultHeaders()
    {
        return [
            'SDK-Source' => 'ZALO-PHP-SDK-v' . Zalo::VERSION,
            'User-Agent' => 'Zalo PHP Sdk',
            'SDK_VERSION' => Zalo::VERSION,
            'Accept-Encoding' => '*',
        ];
    }

    /**
     * Check domain is graph api
     *
     * @return array
     */
    public function isGraph()
    {
        return strpos($this->url, 'graph') !== false;
    }

    /**
     * Get content type
     *
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * Set content type
     *
     * @param $contentType
     * @return void
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    }
}
