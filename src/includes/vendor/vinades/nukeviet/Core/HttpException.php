<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Core;

/**
 * HttpException
 * 
 * Custom exception that carries an HTTP status code.
 * Used to replace deprecated trigger_error(..., E_USER_ERROR) while preserving HTTP response codes.
 *
 * @package NukeViet\Core
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @version 5.x
 * @access public
 */
class HttpException extends \RuntimeException
{
    /**
     * @var int HTTP status code
     */
    private $httpCode;

    /**
     * Constructor
     *
     * @param string $message Error message
     * @param int $httpCode HTTP status code (default: 500)
     */
    public function __construct($message = '', $httpCode = 500)
    {
        $this->httpCode = $httpCode;
        parent::__construct($message, 0, null);
    }

    /**
     * Get HTTP status code
     *
     * @return int
     */
    public function getHttpCode()
    {
        return $this->httpCode;
    }
}
