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

mb_internal_encoding($global_config['site_charset']);
mb_http_output($global_config['site_charset']);

/**
 * nv_internal_encoding()
 *
 * @param string $encoding
 * @return mixed
 */
function nv_internal_encoding($encoding)
{
    return mb_internal_encoding($encoding);
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

    return mb_strlen($string, $global_config['site_charset']);
}

/**
 * nv_substr()
 *
 * @param string $string
 * @param int    $start
 * @param int    $length
 * @return string
 */
function nv_substr($string, $start, $length)
{
    global $global_config;

    return mb_substr($string, $start, $length, $global_config['site_charset']);
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
    return mb_substr_count($haystack, $needle);
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

    return mb_strpos($haystack, $needle, $offset, $global_config['site_charset']);
}

/**
 * nv_strrpos()
 *
 * @param string $haystack
 * @param string $needle
 * @param int    $offset
 * @return mixed
 */
function nv_strrpos($haystack, $needle, $offset = 0)
{
    global $global_config;

    return mb_strrpos($haystack, $needle, $offset, $global_config['site_charset']);
}

/**
 * nv_strtolower()
 *
 * @param string $string
 * @return false|string|null
 */
function nv_strtolower($string)
{
    global $global_config;

    return mb_strtolower($string, $global_config['site_charset']);
}

/**
 * nv_strtoupper()
 *
 * @param string $string
 * @return false|string|null
 */
function nv_strtoupper($string)
{
    global $global_config;

    return mb_strtoupper($string, $global_config['site_charset']);
}
