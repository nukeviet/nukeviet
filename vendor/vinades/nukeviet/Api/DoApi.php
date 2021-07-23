<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
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
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @version 4.5.01
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
     * @param string $apiurl
     * @param string $apikey
     * @param string $apisecret
     */
    public function __construct($apiurl, $apikey, $apisecret)
    {
        $this->apikey = $apikey;
        $this->apisecret = $apisecret;
        $this->apiurl = $apiurl;
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
            'decompress' => false
        ];

        $http = new Http($global_config, NV_TEMP_DIR);
        $responsive = $http->post($this->apiurl, $args);

        if (!empty(Http::$error)) {
            $this->error = Http::$error;
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
