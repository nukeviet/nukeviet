<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2/3/2012, 9:10
 */

namespace NukeViet\Api;

class ApiResult
{
    const API_ERROR = 'error';
    const API_SUCCESS = 'success';

    const CODE_UNKONW = '0000';
    const CODE_REQUIRE_ADMINIDENT = '0001';
    const CODE_WRONG_TYPE = '0002';
    const CODE_MISSING_FUNCTION = '0003';
    const CODE_API_NOT_EXISTS = '0004';

    private const CODE_PATTERN = '/^[0-9]{4}$/';

    private $result = [
        'status' => '',
        'code' => '',
        'message' => '',
        'data' => []
    ];

    /**
     *
     */
    public function __construct()
    {
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
     * @throws Exception
     */
    public function getResult()
    {
        if (!function_exists('nv_jsonOutput')) {
            throw new Exception('Missing function nv_jsonOutput!!!', self::CODE_MISSING_FUNCTION);
        }
        return nv_jsonOutput($this->result);
    }
}
