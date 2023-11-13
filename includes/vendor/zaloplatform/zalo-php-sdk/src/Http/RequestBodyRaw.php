<?php
/**
 * Zalo © 2019
 *
 */

namespace Zalo\Http;

use Zalo\Http\RequestBodyInterface;

/**
 * Class RequestBodyRaw
 *
 * @package Zalo
 */
class RequestBodyRaw implements RequestBodyInterface
{
    /**
     * @var array The parameters to send with this request.
     */
    protected $params = [];

    /**
     * Creates a new RequestBodyRaw entity.
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
        return json_encode($this->params);
    }
}
