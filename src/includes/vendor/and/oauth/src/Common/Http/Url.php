<?php

namespace OAuth\Common\Http;

use League\Url\Components\AbstractSegment;
use League\Url\Url as BaseUrl;

class Url extends BaseUrl
{

    /**
     * @param string $uri URI to be parsed
     */
    public function __construct($uri)
    {
        // Hack, prevent infinite recursion
        if (!$uri instanceof AbstractSegment and 1 == func_num_args()) {
            $uri = static::createFromUrl($uri);

            parent::__construct(
                $uri->getScheme(),
                $uri->getUser(),
                $uri->getPass(),
                $uri->getHost(),
                $uri->getPort(),
                $uri->getPath(),
                $uri->getQuery(),
                $uri->getFragment()
            );
        } // Recursion here
        else {
            call_user_func_array('parent::__construct', func_get_args());
        }
    }

    public function __clone()
    {
        foreach (['scheme', 'user', 'pass', 'host', 'port', 'path', 'query', 'fragment'] as $prop) {
            if ($this->$prop and is_object($this->$prop)) {
                $this->$prop = clone $this->$prop;
            }
        }
    }
}
