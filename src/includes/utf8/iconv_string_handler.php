<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

iconv_set_encoding('input_encoding', $global_config['site_charset']);
iconv_set_encoding('internal_encoding', $global_config['site_charset']);
iconv_set_encoding('output_encoding', $global_config['site_charset']);

/**
 * nv_internal_encoding()
 *
 * @param string $encoding
 * @return bool
 */
function nv_internal_encoding($encoding)
{
    return iconv_set_encoding('internal_encoding', $encoding);
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
