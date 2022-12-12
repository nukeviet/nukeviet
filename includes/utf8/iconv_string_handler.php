<?php

/**
 * @Project NUKEVIET 4.x
 * @author VINADES.,JSC (contact@vinades.vn)
 * @copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @createdate 22/8/2010, 19:33
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
 * @param mixed $string
 */
function nv_strlen($string)
{
    global $global_config;

    return iconv_strlen($string, $global_config['site_charset']);
}

/**
 * nv_substr()
 *
 * @param mixed $string
 * @param mixed $start
 * @param mixed $length
 */
function nv_substr($string, $start, $length)
{
    global $global_config;

    return iconv_substr($string, $start, $length, $global_config['site_charset']);
}

/**
 * nv_substr_count()
 *
 * @param mixed $haystack
 * @param mixed $needle
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
 * @param mixed $haystack
 * @param mixed $needle
 * @param int   $offset
 */
function nv_strpos($haystack, $needle, $offset = 0)
{
    global $global_config;

    return iconv_strpos($haystack, $needle, $offset, $global_config['site_charset']);
}

/**
 * nv_strrpos()
 *
 * @param mixed $haystack
 * @param mixed $needle
 * @param int   $offset
 */
function nv_strrpos($haystack, $needle, $offset = 0)
{
    global $global_config;

    return iconv_strrpos($haystack, $needle, $offset, $global_config['site_charset']);
}

/**
 * nv_strtolower()
 *
 * @param mixed $string
 */
function nv_strtolower($string)
{
    include NV_ROOTDIR . '/includes/utf8/lookup.php';

    return strtr($string, $utf8_lookup['strtolower']);
}

/**
 * nv_strtoupper()
 *
 * @param mixed $string
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
