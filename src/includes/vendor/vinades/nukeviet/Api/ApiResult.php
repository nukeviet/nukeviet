<?php

/**
 * NUKEVIET Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Api;

class ApiResult
{
    public const API_ERROR = 'error';
    public const API_SUCCESS = 'success';

    public const CODE_UNKONW = '0000';
    public const CODE_REQUIRE_ADMINIDENT = '0001';
    public const CODE_WRONG_TYPE = '0002';
    public const CODE_MISSING_FUNCTION = '0003';
    public const CODE_API_NOT_EXISTS = '0004';
    public const CODE_MODULE_NOT_EXISTS = '0005';
    public const CODE_MODULE_INVALID = '0006';
    public const CODE_NO_ADMIN_IDENT = '0007';
    public const CODE_NO_ADMIN_FOUND = '0008';
    public const CODE_NO_MODADMIN_RIGHT = '0009';
    public const CODE_ADMINLEV_NOT_ENOUGH = '0010';
    public const CODE_REMOTE_OFF = '0011';
    public const CODE_SYS_ERROR = '0012';
    public const CODE_NO_CREDENTIAL_FOUND = '0013';
    public const CODE_AUTH_FAIL = '0014';
    public const CODE_MISSING_REQUEST_CMD = '0015';
    public const CODE_LANG_NOT_EXISTS = '0016';
    public const CODE_WRONG_LANG = '0017';
    public const CODE_MISSING_LANG = '0018';

    private const CODE_PATTERN = '/^[0-9]{4}$/';

    private $result = [];

    private $resultDefault = [
        'status' => '',
        'code' => '',
        'message' => ''
    ];

    public function __construct()
    {
        $this->result = $this->resultDefault;
        $this->result['status'] = self::API_ERROR;
        $this->result['code'] = self::CODE_UNKONW;
    }

    /**
     * @return \NukeViet\Api\ApiResult
     */
    public function setError()
    {
        $this->result['status'] = self::API_ERROR;

        return $this;
    }

    /**
     * @return \NukeViet\Api\ApiResult
     */
    public function setSuccess()
    {
        $this->result['status'] = self::API_SUCCESS;

        return $this;
    }

    /**
     * @param string $code
     * @throws Exception
     * @return \NukeViet\Api\ApiResult
     */
    public function setCode($code)
    {
        if (!preg_match(self::CODE_PATTERN, $code)) {
            throw new Exception('Wrong code type!!!', self::CODE_WRONG_TYPE);
        }
        $this->result['code'] = $code;

        return $this;
    }

    /**
     * @param string $message
     * @throws Exception
     * @return \NukeViet\Api\ApiResult
     */
    public function setMessage($message)
    {
        if (!is_string($message)) {
            throw new Exception('Wrong message type!!!', self::CODE_WRONG_TYPE);
        }
        $this->result['message'] = $message;

        return $this;
    }

    /**
     * @param string $key
     * @param []     $data
     * @throws Exception
     * @return \NukeViet\Api\ApiResult
     */
    public function set($key, $data)
    {
        if (!is_string($key)) {
            throw new Exception('Invaild Data Key');
        }
        if (is_null($data) or is_resource($data) or is_object($data)) {
            throw new Exception('Invaild Data Type');
        }
        $this->result[$key] = $data;

        return $this;
    }

    /**
     * @return string
     */
    public function getResult()
    {
        return json_encode($this->result);
    }

    /**
     * @throws Exception
     */
    public function returnResult()
    {
        if (!function_exists('nv_jsonOutput')) {
            throw new Exception('Missing function nv_jsonOutput!!!', self::CODE_MISSING_FUNCTION);
        }

        return nv_jsonOutput($this->result);
    }
}
