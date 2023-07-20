<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Client;

use NukeViet\Http\Http;
use NukeViet\Site;

/**
 * NukeViet\Client\UrlGetContents
 *
 * @package NukeViet\Client
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @version 4.5.00
 * @access public
 */
class UrlGetContents
{
    private $allow_methods = [];
    public static $user_agent = '';
    public static $open_basedir = false;
    private $url_info = false;
    private $login = '';
    private $password = '';
    private $ref = '';
    private $redirectCount = 0;
    public $time_limit = 60;

    /**
     * __construct()
     *
     * @param array $global_config
     * @param int   $time_limit
     */
    public function __construct($global_config, $time_limit = 60)
    {
        $userAgents = [
            'Mozilla/5.0 (Windows; U; Windows NT 5.1; pl; rv:1.9) Gecko/2008052906 Firefox/3.0',
            'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
            'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)',
            'Mozilla/4.8 [en] (Windows NT 6.0; U)',
            'Opera/9.25 (Windows NT 6.0; U; en)'
        ];
        $rand = array_rand($userAgents);
        self::$user_agent = $userAgents[$rand];

        self::$open_basedir = (ini_get('open_basedir') == '1' or strtolower(ini_get('open_basedir')) == 'on') ? true : false;
        $this->time_limit = (int) $time_limit;
        if (Site::function_exists('set_time_limit')) {
            set_time_limit($this->time_limit);
        }

        if (Site::function_exists('ini_set')) {
            ini_set('default_socket_timeout', $this->time_limit);
        }

        if (Site::function_exists('curl', true)) {
            $this->allow_methods[] = 'curl';
        }

        if (Site::function_exists('fsockopen')) {
            $this->allow_methods[] = 'fsockopen';
        }

        if (ini_get('allow_url_fopen') == '1' or strtolower(ini_get('allow_url_fopen')) == 'on') {
            if (Site::function_exists('fopen')) {
                $this->allow_methods[] = 'fopen';
            }

            if (Site::function_exists('file_get_contents')) {
                $this->allow_methods[] = 'file_get_contents';
            }
        }

        if (Site::function_exists('file')) {
            $this->allow_methods[] = 'file';
        }
    }

    /**
     * check_url()
     *
     * @param int $is_200
     * @return mixed
     */
    private function check_url($is_200 = 0)
    {
        $allow_url_fopen = (ini_get('allow_url_fopen') == '1' or strtolower(ini_get('allow_url_fopen')) == 'on') ? 1 : 0;

        if (Site::function_exists('get_headers') and $allow_url_fopen == 1) {
            if (version_compare(PHP_VERSION, '7.1.0', '>=')) {
                $context = stream_context_create([
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ],
                ]);
                $res = get_headers($this->url_info['uri'], 0, $context);
            } else {
                stream_context_set_default([
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ],
                ]);
                $res = get_headers($this->url_info['uri']);
            }
        } elseif (Site::function_exists('curl_init') and Site::function_exists('curl_exec')) {
            $url_info = parse_url($this->url_info['uri']);
            $port = isset($url_info['port']) ? (int) ($url_info['port']) : 80;

            $userAgents = [
                'Mozilla/5.0 (Windows; U; Windows NT 5.1; pl; rv:1.9) Gecko/2008052906 Firefox/3.0',
                'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
                'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)',
                'Mozilla/4.8 [en] (Windows NT 6.0; U)',
                'Opera/9.25 (Windows NT 6.0; U; en)'
            ];
            mt_srand(microtime(true) * 1000000);
            $rand = array_rand($userAgents);
            $agent = $userAgents[$rand];

            $curl = curl_init($this->url_info['uri']);
            curl_setopt($curl, CURLOPT_HEADER, true);
            curl_setopt($curl, CURLOPT_NOBODY, true);

            curl_setopt($curl, CURLOPT_PORT, $port);
            if (!self::$open_basedir) {
                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            }

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($curl, CURLOPT_TIMEOUT, 15);
            curl_setopt($curl, CURLOPT_USERAGENT, $agent);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

            $response = curl_exec($curl);
            curl_close($curl);

            if ($response === false) {
                return false;
            }
            $res = explode("\n", $response);
        } elseif (Site::function_exists('fsockopen') and Site::function_exists('fgets')) {
            $res = [];
            $url_info = parse_url($this->url_info['uri']);
            $port = isset($url_info['port']) ? (int) ($url_info['port']) : 80;
            $fp = fsockopen($url_info['host'], $port, $errno, $errstr, 15);
            if ($fp) {
                $path = !empty($url_info['path']) ? $url_info['path'] : '/';
                $path .= !empty($url_info['query']) ? '?' . $url_info['query'] : '';

                fputs($fp, 'HEAD ' . $path . " HTTP/1.0\r\n");
                fputs($fp, 'Host: ' . $url_info['host'] . ':' . $port . "\r\n");
                fputs($fp, "Connection: close\r\n\r\n");

                while (!feof($fp)) {
                    if ($header = trim(fgets($fp, 1024))) {
                        $res[] = $header;
                    }
                }
            } else {
                return false;
            }
        } else {
            return false;
        }

        if (!$res) {
            return false;
        }
        if (preg_match('/(200)/', $res[0])) {
            return true;
        }
        if ($is_200 > 5) {
            return false;
        }
        if (preg_match('/(301)|(302)|(303)/', $res[0])) {
            foreach ($res as $v) {
                if (preg_match("/location:\s(.*?)$/is", $v, $matches)) {
                    ++$is_200;
                    $location = trim($matches[1]);
                    if (substr($location, 0, 1) == '/') {
                        $location = $this->url_info['scheme'] . '://' . $this->url_info['host'] . $location;
                    }
                    $this->url_info = Http::parse_url($location);
                    if (!$this->url_info) {
                        return false;
                    }

                    return $this->check_url($is_200);
                }
            }
        }

        return false;
    }

    /**
     * generate_newUrl()
     *
     * @param string $url
     * @return string
     */
    private function generate_newUrl($url)
    {
        $m = trim($url);

        if (substr($m, 0, 1) == '/') {
            $newurl = $this->url_info['scheme'] . '://' . $this->url_info['host'] . $m;
        } else {
            $newurl = $m;
        }

        return $newurl;
    }

    /**
     * curl_Get()
     *
     * @return mixed
     */
    private function curl_Get()
    {
        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_ENCODING, '');
        curl_setopt($curlHandle, CURLOPT_URL, $this->url_info['uri']);
        curl_setopt($curlHandle, CURLOPT_HEADER, true);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);

        if (!empty($this->login)) {
            curl_setopt($curlHandle, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($curlHandle, CURLOPT_USERPWD, $this->login . ':' . $this->password);
        }

        curl_setopt($curlHandle, CURLOPT_USERAGENT, self::$user_agent);

        if (!empty($this->ref)) {
            curl_setopt($curlHandle, CURLOPT_REFERER, urlencode($this->ref));
        } else {
            curl_setopt($curlHandle, CURLOPT_REFERER, $this->url_info['uri']);
        }

        if (!self::$open_basedir) {
            curl_setopt($curlHandle, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($curlHandle, CURLOPT_MAXREDIRS, 10);
        }

        curl_setopt($curlHandle, CURLOPT_TIMEOUT, 30);

        $result = curl_exec($curlHandle);

        if (curl_errno($curlHandle) == 23 or curl_errno($curlHandle) == 61) {
            curl_setopt($curlHandle, CURLOPT_ENCODING, 'none');
            $result = curl_exec($curlHandle);
        }

        if (curl_errno($curlHandle)) {
            curl_close($curlHandle);

            return false;
        }

        [$header, $result] = preg_split("/\r?\n\r?\n/", $result, 2);

        $response = curl_getinfo($curlHandle);

        if (self::$open_basedir) {
            if ($response['http_code'] == 301 or $response['http_code'] == 302 or $response['http_code'] == 303) {
                if (preg_match('/^(Location:|URI:)[\s]*(.*?)$/m', $header, $matches) and $this->redirectCount <= 5) {
                    ++$this->redirectCount;

                    $newurl = $this->generate_newUrl($matches[2]);

                    curl_setopt($curlHandle, CURLOPT_URL, $newurl);

                    $this->url_info = Http::parse_url($newurl);

                    if (!$this->url_info) {
                        return false;
                    }

                    return $this->curl_Get();
                }
            }
        }

        if (($response['http_code'] < 200) or (300 <= $response['http_code'])) {
            curl_close($curlHandle);

            return false;
        }

        curl_close($curlHandle);

        if (preg_match('/(<meta http-equiv=)(.*?)(refresh)(.*?)(url=)([^\'\"]+)[\'|"]\s*[\/]*>/is', $result, $matches) and $this->redirectCount <= 5) {
            ++$this->redirectCount;

            $newurl = $this->generate_newUrl($matches[6]);

            curl_setopt($curlHandle, CURLOPT_URL, $newurl);

            $this->url_info = Http::parse_url($newurl);

            if (!$this->url_info) {
                return false;
            }

            return $this->curl_Get();
        }

        return $result;
    }

    /**
     * fsockopen_Get()
     *
     * @return mixed
     */
    private function fsockopen_Get()
    {
        if (strtolower($this->url_info['scheme']) == 'https') {
            $this->url_info['host'] = 'ssl://' . $this->url_info['host'];
            $this->url_info['port'] = 443;
        }

        $fp = @fsockopen($this->url_info['host'], $this->url_info['port'], $errno, $errstr, 30);
        if (!$fp) {
            return false;
        }

        $request = 'GET ' . $this->url_info['path'] . $this->url_info['query'];
        $request .= " HTTP/1.0\r\n";
        $request .= 'Host: ' . $this->url_info['host'];

        if ($this->url_info['port'] != 80) {
            $request .= ':' . $this->url_info['port'];
        }
        $request .= "\r\n";

        $request .= "Connection: Close\r\n";
        $request .= 'User-Agent: ' . self::$user_agent . "\r\n\r\n";

        if (Site::function_exists('gzinflate')) {
            $request .= "Accept-Encoding: gzip,deflate\r\n";
        }

        $request .= "Accept: */*\r\n";

        if (!empty($this->ref)) {
            $request .= 'Referer: ' . urlencode($this->ref) . "\r\n";
        } else {
            $request .= 'Referer: ' . $this->url_info['uri'] . "\r\n";
        }

        if (!empty($this->login)) {
            $request .= 'Authorization: Basic ';
            $request .= base64_encode($this->login . ':' . $this->password);
            $request .= "\r\n";
        }

        $request .= "\r\n";

        if (@fwrite($fp, $request) === false) {
            @fclose($fp);

            return false;
        }

        @stream_set_blocking($fp, true);
        @stream_set_timeout($fp, 30);
        $in_f = @stream_get_meta_data($fp);

        $response = '';

        while ((!@feof($fp)) and (!$in_f['timed_out'])) {
            $response .= @fgets($fp, 4096);
            $inf = @stream_get_meta_data($fp);
            if ($inf['timed_out']) {
                @fclose($fp);

                return false;
            }
        }

        if (Site::function_exists('gzinflate') and substr($response, 0, 8) == "\x1f\x8b\x08\x00\x00\x00\x00\x00") {
            $response = substr($response, 10);
            $response = gzinflate($response);
        }

        @fclose($fp);

        [$header, $result] = preg_split("/\r?\n\r?\n/", $response, 2);

        if (preg_match('/^(Location:|URI:)[\s]*(.*?)$/m', $header, $matches) and $this->redirectCount <= 5) {
            ++$this->redirectCount;

            $newurl = $this->generate_newUrl($matches[2]);

            $this->url_info = Http::parse_url($newurl);

            if (!$this->url_info) {
                return false;
            }

            return $this->fsockopen_Get();
        }

        preg_match("/^HTTP\/[0-9\.]+\s+(\d+)\s+/", $header, $matches);
        if ($matches == []) {
            return false;
        }
        if ($matches[1] != 200) {
            return false;
        }

        if (preg_match('/(<meta http-equiv=)(.*?)(refresh)(.*?)(url=)([^\'\"]+)[\'|"]\s*[\/]*>/is', $result, $matches) and $this->redirectCount <= 5) {
            ++$this->redirectCount;

            $newurl = $this->generate_newUrl($matches[6]);

            $this->url_info = Http::parse_url($newurl);

            if (!$this->url_info) {
                return false;
            }

            return $this->fsockopen_Get();
        }

        return $result;
    }

    /**
     * fopen_Get()
     *
     * @return false|string
     */
    private function fopen_Get()
    {
        $ctx = stream_context_create(['http' => [
            'method' => 'GET',
            'max_redirects' => '2',
            'ignore_errors' => '0',
            'timeout' => 30
        ]]);

        if (($fd = @fopen($this->url_info['uri'], 'rb', 0, $ctx)) === false) {
            return false;
        }

        $result = '';
        while (($data = fread($fd, 4096)) != '') {
            $result .= $data;
        }
        fclose($fd);

        return $result;
    }

    /**
     * file_get_contents_Get()
     *
     * @return false|string
     */
    private function file_get_contents_Get()
    {
        $ctx = stream_context_create(['http' => [
            'method' => 'GET',
            'max_redirects' => '5',
            'ignore_errors' => '0',
            'timeout' => 30
        ]]);

        return file_get_contents($this->url_info['uri'], 0, $ctx);
    }

    /**
     * file_Get()
     *
     * @return string
     */
    private function file_Get()
    {
        $ctx = stream_context_create(['http' => [
            'method' => 'GET',
            'max_redirects' => '5',
            'ignore_errors' => '0',
            'timeout' => 30
        ]]);

        $result = file($this->url_info['uri'], 0, $ctx);

        if ($result) {
            return implode($result);
        }

        return '';
    }

    /**
     * get()
     *
     * @param string $url
     * @param string $login
     * @param string $password
     * @param string $ref
     * @return mixed
     */
    public function get($url, $login = '', $password = '', $ref = '')
    {
        $this->url_info = Http::parse_url($url);

        if (!$this->url_info) {
            return false;
        }

        if ($this->check_url() === false) {
            return false;
        }

        $this->login = (string) $login;
        $this->password = (string) $password;
        $this->ref = (string) $ref;

        if (!empty($this->allow_methods)) {
            foreach ($this->allow_methods as $method) {
                $result = call_user_func([
                    &$this,
                    $method . '_Get'
                ]);

                if (!empty($result)) {
                    return $result;
                    break;
                }
            }
        }

        return '';
    }
}
