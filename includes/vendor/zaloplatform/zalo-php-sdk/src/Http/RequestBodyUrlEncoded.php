<?php
/**
 * Zalo © 2019
 *
 */

namespace Zalo\Http;

use Zalo\Http\RequestBodyInterface;

/**
 * Class RequestBodyUrlEncoded
 *
 * @package Zalo
 */
class RequestBodyUrlEncoded implements RequestBodyInterface
{
    /**
     * @var array The parameters to send with this request.
     */
    protected $params = [];

    /**
     * Creates a new GraphUrlEncodedBody entity.
     *
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * @inheritdoc
     */
    public function getBody()
    {
        return http_build_query($this->params, null, '&');
    }
}
