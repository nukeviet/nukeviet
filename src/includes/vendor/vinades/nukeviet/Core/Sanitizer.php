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

use NukeViet\Site;

/**
 * NukeViet\Core\Sanitizer
 *
 * @package NukeViet\Core
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @version 5.x
 * @access public
 */
class Sanitizer
{
    /**
     * Mã lệnh / hàm bị cấm xuất hiện
     */
    public const DISABLE_COMMANDS = [
        'base64_decode',
        'cmd',
        'passthru',
        'eval',
        'exec',
        'system',
        'fopen',
        'fsockopen',
        'file',
        'file_get_contents',
        'readfile',
        'unlink'
    ];

    /**
     * Bảng chuẩn hóa các từ khóa nguy hiểm
     */
    private const XSS_KEYWORDS = [
        'expression' => '/e\s*x\s*p\s*r\s*e\s*s\s*s\s*i\s*o\s*n/si',
        'javascript' => '/j\s*a\s*v\s*a\s*s\s*c\s*r\s*i\s*p\s*t/si',
        'livescript' => '/l\s*i\s*v\s*e\s*s\s*c\s*r\s*i\s*p\s*t/si',
        'behavior' => '/b\s*e\s*h\s*a\s*v\s*i\s*o\s*r/si',
        'behaviour' => '/b\s*e\s*h\s*a\s*v\s*i\s*o\s*u\s*r/si',
        'vbscript' => '/v\s*b\s*s\s*c\s*r\s*i\s*p\s*t/si',
        'script' => '/s\s*c\s*r\s*i\s*p\s*t/si',
        'applet' => '/a\s*p\s*p\s*l\s*e\s*t/si',
        'alert' => '/a\s*l\s*e\s*r\s*t/si',
        'document' => '/d\s*o\s*c\s*u\s*m\s*e\s*n\s*t/si',
        'write' => '/w\s*r\s*i\s*t\s*e/si',
        'cookie' => '/c\s*o\s*o\s*k\s*i\s*e/si',
        'window' => '/w\s*i\s*n\s*d\s*o\s*w/si',
        'data:' => '/d\s*a\s*t\s*a\s*\:/si',
        '@import' => '/@\s*i\s*m\s*p\s*o\s*r\s*t/si'
    ];

    /**
     * Chuẩn hóa, lấy giá trị gốc các từ nguy hiểm
     *
     * @param string $value
     * @return string
     */
    public static function normalizeXssKeywords($value)
    {
        return preg_replace(array_values(self::XSS_KEYWORDS), array_keys(self::XSS_KEYWORDS), $value);
    }

    /**
     * Kiểm tra chuỗi có chứa scheme/CSS nguy hiểm không.
     *
     * @param string $value
     * @return bool true nếu phát hiện nguy hiểm
     */
    public static function hasDangerousScheme($value)
    {
        return (bool) (preg_match('/(expression|javascript|behaviou?r|vbscript|mocha|livescript)(\:*)/', $value) or preg_match('/@import/i', $value));
    }

    /**
     * Kiểm tra chuỗi có chứa lệnh, lời gọi tới hàm PHP bị cấm không.
     *
     * @param string $value
     * @return bool true nếu phát hiện lời gọi bị cấm
     */
    public static function hasDisabledCommand($value)
    {
        return !empty(self::DISABLE_COMMANDS) and (bool) preg_match('#(' . implode('|', self::DISABLE_COMMANDS) . ')(\s*)\((.*?)\)#si', $value);
    }

    /**
     * Kiểm tra chuỗi có XSS hay không.
     *
     * @param string $value
     * @return bool true nếu chuỗi an toàn
     */
    public static function xssValid($value)
    {
        $value = Site::unhtmlentities($value);
        $value = self::normalizeXssKeywords($value);

        if (self::hasDangerousScheme($value)) {
            return false;
        }

        if (strcasecmp($value, strip_tags($value)) !== 0) {
            return false;
        }

        return !self::hasDisabledCommand($value);
    }
}
