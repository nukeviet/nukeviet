<?php

namespace OAuth\OAuth1\Signature;

use OAuth\Common\Http\Url;

interface SignatureInterface
{

    /**
     * @param string $algorithm
     */
    public function setHashingAlgorithm($algorithm);

    /**
     * @param string $token
     */
    public function setTokenSecret($token);

    /**
     * @param Url $uri
     * @param array $params
     * @param string $method
     *
     * @return string
     */
    public function getSignature(Url $uri, array $params, $method = 'POST');
}
