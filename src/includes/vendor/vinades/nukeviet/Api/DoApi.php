<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Api;

use NukeViet\Http\Http;

/**
 * NukeViet\Api\DoApi
 *
 * @package NukeViet\Api
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @version 5.x
 * @access public
 */
class DoApi
{
    /**
     * @var string API url
     */
    private $apiurl;

    /**
     * @var string khóa truy cập
     */
    private $apikey;

    /**
     * @var string mã bí mật
     */
    private $apisecret;

    /**
     * @var array data request
     */
    private $data = [
        'action' => '',
        'module' => '',
        'language' => 'vi'
    ];

    /**
     * @var string message lỗi
     */
    private $error = '';

    /**
     * @var boolean gọi api rewrite hay không
     */
    protected $rewrite_support;

    /**
     * @param string $apiurl
     * @param string $apikey
     * @param string $apisecret
     * @param boolean $rewrite
     */
    public function __construct($apiurl, $apikey, $apisecret, $rewrite = false)
    {
        $this->apikey = $apikey;
        $this->apisecret = $apisecret;
        $this->apiurl = $apiurl;
        $this->rewrite_support = $rewrite;
    }

    /**
     * @param array $array
     * @return \NukeViet\Api\DoApi
     */
    public function setData($array)
    {
        $this->data = array_merge($array, $this->data);

        return $this;
    }

    /**
     * @param string $module
     * @return \NukeViet\Api\DoApi
     */
    public function setModule($module)
    {
        $this->data['module'] = $module;

        return $this;
    }

    /**
     * @param string $action
     * @return \NukeViet\Api\DoApi
     */
    public function setAction($action)
    {
        $this->data['action'] = $action;

        return $this;
    }

    /**
     * @param string $lang
     * @return \NukeViet\Api\DoApi
     */
    public function setLang($lang)
    {
        $this->data['language'] = $lang;

        return $this;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @return array|mixed
     */
    public function execute()
    {
        global $global_config, $client_info;

        $this->error = '';
        $timestamp = time();
        $request = [
            'apikey' => $this->apikey,
            'timestamp' => $timestamp,
            'hashsecret' => password_hash($this->apisecret . '_' . $timestamp, PASSWORD_DEFAULT),
        ];
        $args = [
            'headers' => [
                'Referer' => $client_info['selfurl']
            ],
            'body' => array_merge($request, $this->data),
            'timeout' => 0,
            'decompress' => false,
            'sslverify' => false
        ];

        // Xử lý nếu gọi API rewrite
        $api_url = $this->apiurl;
        if ($this->rewrite_support and !empty($args['body']['action'])) {
            $url_info = parse_url($api_url);
            if (!isset($url_info['scheme'], $url_info['host'], $url_info['path']) and substr($url_info['path'], -7) != 'api.php') {
                throw new \Exception('Wrong apiurl!!!');
            }
            $api_url = $url_info['scheme'] . '://' . $url_info['host'];
            if (isset($url_info['port'])) {
                $api_url .= ':' . $url_info['port'];
            }
            $api_url .= substr($url_info['path'], 0, -7);
            $getVars = ['api'];
            if (!empty($args['body']['language'])) {
                $getVars[] = $args['body']['language'];
                unset($args['body']['language']);
            }
            if (!empty($args['body']['module'])) {
                $getVars[] = $args['body']['module'];
                unset($args['body']['module']);
            }
            $getVars[] = $args['body']['action'];
            unset($args['body']['action']);

            $api_url .= implode('/', $getVars);
        }

        $http = new Http($global_config, NV_TEMP_DIR);
        $http->reset();
        $responsive = $http->post($api_url, $args);

        if (!empty(Http::$error)) {
            $this->error = 'Error Code ' . Http::$error['code'] . ': ' . Http::$error['message'];

            return [];
        }
        if (!is_array($responsive)) {
            $this->error = 'Error request API';

            return [];
        }
        if (empty($responsive['body'])) {
            $this->error = 'No respon body';

            return [];
        }

        $res = json_decode($responsive['body'], true);

        if (!is_array($res)) {
            $this->error = 'Not Json respon';

            return [];
        }

        return $res;
    }
}
