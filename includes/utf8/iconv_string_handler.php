<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

// https://www.php.net/manual/en/function.iconv-set-encoding.php#119888
// https://github.com/nukeviet/nukeviet/issues/3376
if (PHP_VERSION_ID < 50600) {
    iconv_set_encoding('input_encoding', $global_config['site_charset']);
    iconv_set_encoding('internal_encoding', $global_config['site_charset']);
    iconv_set_encoding('output_encoding', $global_config['site_charset']);
} else {
    ini_set('default_charset', $global_config['site_charset']);
}

/**
 * nv_internal_encoding()
 *
 * @param string $encoding
 * @return bool
 */
function nv_internal_encoding($encoding)
{
    if (PHP_VERSION_ID < 50600) {
        return iconv_set_encoding('internal_encoding', $encoding);
    }

    return true;
}

/**
 * nv_strlen()
 *
 * @param string $string
 * @return false|int
 */
function nv_strlen($string)
{
    global $global_config;

    return iconv_strlen($string, $global_config['site_charset']);
}

/**
 * nv_substr()
 *
 * @param string $string
 * @param int    $start
 * @param int    $length
 * @return false|string
 */
function nv_substr($string, $start, $length)
{
    global $global_config;

    return iconv_substr($string, $start, $length, $global_config['site_charset']);
}

/**
 * nv_substr_count()
 *
 * @param string $haystack
 * @param string $needle
 * @return int
 */
function nv_substr_count($haystack, $needle)
{
    $needle = preg_quote($needle, '/');
    preg_match_all('/' . $needle . '/u', $haystack, $dummy);

    return sizeof($dummy[0]);
}

/**
 * nv_strpos()
 *
 * @param string $haystack
 * @param string $needle
 * @param int    $offset
 * @return false|int
 */
function nv_strpos($haystack, $needle, $offset = 0)
{
    global $global_config;

    return iconv_strpos($haystack, $needle, $offset, $global_config['site_charset']);
}

/**
 * nv_strrpos()
 *
 * @param string $haystack
 * @param string $needle
 * @param int    $offset
 * @return false|int
 */
function nv_strrpos($haystack, $needle, $offset = 0)
{
    global $global_config;

    return iconv_strrpos($haystack, $needle, $offset, $global_config['site_charset']);
}

/**
 * nv_strtolower()
 *
 * @param string $string
 * @return string
 */
function nv_strtolower($string)
{
    include NV_ROOTDIR . '/includes/utf8/lookup.php';

    return strtr($string, $utf8_lookup['strtolower']);
}

/**
 * nv_strtoupper()
 *
 * @param string $string
 * @return string
 */
function nv_strtoupper($string)
{
    include NV_ROOTDIR . '/includes/utf8/lookup.php';

    return strtr($string, $utf8_lookup['strtoupper']);
}

/**
 * nv_utf8_encode()
 * function thay thế cho utf8_encode đã lỗi thời
 *
 * @param string $string
 * @return string
 */
function nv_utf8_encode($string)
{
    return iconv('ISO-8859-1', 'UTF-8', $string);
}

/**
 * nv_utf8_decode()
 * function thay thế cho utf8_decode đã lỗi thời
 *
 * @param string $string
 * @return string
 */
function nv_utf8_decode($string)
{
    return iconv('UTF-8', 'ISO-8859-1', $string);
}
