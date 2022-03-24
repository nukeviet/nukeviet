<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Http;

/**
 * NukeViet\Http\Cookie
 *
 * @package NukeViet\Http
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @version 4.5.00
 * @access public
 */
class Cookie
{
    /**
     * Cookie name.
     * @var string
     */
    public $name;

    /**
     * Cookie value.
     * @var string
     */
    public $value;

    /**
     * When the cookie expires.
     * @var string
     */
    public $expires;

    /**
     * Cookie URL path.
     * @var string
     */
    public $path;

    /**
     * Cookie Domain.
     * @var string
     */
    public $domain;

    /**
     * __construct()
     *
     * @param mixed  $data
     * @param string $requested_url
     * @return false|void
     */
    public function __construct($data, $requested_url = '')
    {
        if ($requested_url) {
            $arrURL = parse_url($requested_url);
        }

        if (isset($arrURL['host'])) {
            $this->domain = $arrURL['host'];
        }

        $this->path = isset($arrURL['path']) ? $arrURL['path'] : '/';

        if ('/' != substr($this->path, -1)) {
            $this->path = dirname($this->path) . '/';
        }

        if (is_string($data)) {
            // Assume it's a header string direct from a previous request
            $pairs = explode(';', $data);

            // Special handling for first pair; name=value. Also be careful of "=" in value
            $name = trim(substr($pairs[0], 0, strpos($pairs[0], '=')));
            $value = substr($pairs[0], strpos($pairs[0], '=') + 1);
            $this->name = $name;
            $this->value = urldecode($value);
            array_shift($pairs); //Removes name=value from items.

            // Set everything else as a property
            foreach ($pairs as $pair) {
                $pair = rtrim($pair);

                if (empty($pair)) {
                    // Handles the cookie ending in ; which results in a empty final pair
                    continue;
                }

                list($key, $val) = strpos($pair, '=') ? explode('=', $pair) : [$pair, ''];
                $key = strtolower(trim($key));

                if ($key == 'expires') {
                    $val = strtotime($val);
                }

                $this->$key = $val;
            }
        } else {
            if (!isset($data['name'])) {
                return false;
            }

            // Set properties based directly on parameters
            foreach (['name', 'value', 'path', 'domain', 'port'] as $field) {
                if (isset($data[$field])) {
                    $this->$field = $data[$field];
                }
            }

            if (isset($data['expires'])) {
                $this->expires = is_int($data['expires']) ? $data['expires'] : strtotime($data['expires']);
            } else {
                $this->expires = null;
            }
        }
    }

    /**
     * test()
     *
     * @param mixed $url
     * @return bool
     */
    public function test($url)
    {
        if (is_null($this->name)) {
            return false;
        }

        // Expires - if expired then nothing else matters
        if (isset($this->expires) and time() > $this->expires) {
            return false;
        }

        // Get details on the URL we're thinking about sending to
        $url = parse_url($url);
        $url['port'] = isset($url['port']) ? $url['port'] : ($url['scheme'] == 'https' ? 443 : 80);
        $url['path'] = isset($url['path']) ? $url['path'] : '/';

        // Values to use for comparison against the URL
        $path = isset($this->path) ? $this->path : '/';
        $port = isset($this->port) ? $this->port : null;
        $domain = isset($this->domain) ? strtolower($this->domain) : strtolower($url['host']);

        if (stripos($domain, '.') === false) {
            $domain .= '.local';
        }

        // Host - very basic check that the request URL ends with the domain restriction (minus leading dot)
        $domain = substr($domain, 0, 1) == '.' ? substr($domain, 1) : $domain;
        if (substr($url['host'], -strlen($domain)) != $domain) {
            return false;
        }

        // Port - supports "port-lists" in the format: "80,8000,8080"
        if (!empty($port) and !in_array((int) $url['port'], array_map('intval', explode(',', $port)), true)) {
            return false;
        }

        // Path - request path must start with path restriction
        if (substr($url['path'], 0, strlen($path)) != $path) {
            return false;
        }

        return true;
    }

    /**
     * getHeaderValue()
     *
     * @return string
     */
    public function getHeaderValue()
    {
        if (!isset($this->name) or !isset($this->value)) {
            return '';
        }

        return $this->name . '=' . $this->value;
    }

    /**
     * getFullHeader()
     *
     * @return string
     */
    public function getFullHeader()
    {
        return 'Cookie: ' . $this->getHeaderValue();
    }
}
