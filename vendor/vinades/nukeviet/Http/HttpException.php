<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Http;

/**
 * Exception tùy chỉnh mang theo mã HTTP status code.
 * Được sử dụng để thay thế trigger_error(..., E_USER_ERROR) đã bị deprecated và giữ lại HTTP response code.
 *
 * @package NukeViet\Http
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @version 4.x
 * @access public
 */
class HttpException extends \RuntimeException
{
    /**
     * @var int Mã HTTP status code
     */
    private $httpCode;

    /**
     * Constructor
     *
     * @param string $message Thông báo lỗi
     * @param int $httpCode Mã HTTP status code (mặc định: 500)
     */
    public function __construct($message = '', $httpCode = 500)
    {
        $this->httpCode = $httpCode;
        parent::__construct($message, 0, null);
    }

    /**
     * Lấy mã HTTP status code
     *
     * @return int
     */
    public function getHttpCode()
    {
        return $this->httpCode;
    }
}
