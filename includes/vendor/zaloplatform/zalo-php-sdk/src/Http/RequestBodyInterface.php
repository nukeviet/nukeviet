<?php
/**
 * Zalo © 2019
 *
 */

namespace Zalo\Http;

/**
 * Interface
 *
 * @package Zalo
 */
interface RequestBodyInterface
{
    /**
     * Get the body of the request to send to Graph.
     *
     * @return string
     */
    public function getBody();
}
