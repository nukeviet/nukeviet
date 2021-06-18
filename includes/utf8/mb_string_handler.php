<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 22/8/2010, 19:32
 */

if (! defined('NV_MAINFILE')) {
    die('Stop!!!');
}

mb_internal_encoding($global_config['site_charset']);
mb_http_output($global_config['site_charset']);

/**
 * nv_internal_encoding()
 * 
 * @param mixed $encoding
 * @return
 */
function nv_internal_encoding($encoding)
{
    return mb_internal_encoding($encoding);
}

/**
 * nv_strlen()
 * 
 * @param mixed $string
 * @return
 */
function nv_strlen($string)
{
    global $global_config;

    return mb_strlen($string, $global_config['site_charset']);
}

/**
 * nv_substr()
 * 
 * @param mixed $string
 * @param mixed $start
 * @param mixed $length
 * @return
 */
function nv_substr($string, $start, $length)
{
    global $global_config;

    return mb_substr($string, $start, $length, $global_config['site_charset']);
}

/**
 * nv_substr_count()
 * 
 * @param mixed $haystack
 * @param mixed $needle
 * @return
 */
function nv_substr_count($haystack, $needle)
{
    return mb_substr_count($haystack, $needle);
}

/**
 * nv_strpos()
 * 
 * @param mixed $haystack
 * @param mixed $needle
 * @param integer $offset
 * @return
 */
function nv_strpos($haystack, $needle, $offset = 0)
{
    global $global_config;

    return mb_strpos($haystack, $needle, $offset, $global_config['site_charset']);
}

/**
 * nv_strrpos()
 * 
 * @param mixed $haystack
 * @param mixed $needle
 * @param integer $offset
 * @return
 */
function nv_strrpos($haystack, $needle, $offset = 0)
{
    global $global_config;

    return mb_strrpos($haystack, $needle, $offset, $global_config['site_charset']);
}

/**
 * nv_strtolower()
 * 
 * @param mixed $string
 * @return
 */
function nv_strtolower($string)
{
    global $global_config;

    return mb_strtolower($string, $global_config['site_charset']);
}

/**
 * nv_strtoupper()
 * 
 * @param mixed $string
 * @return
 */
function nv_strtoupper($string)
{
    global $global_config;

    return mb_strtoupper($string, $global_config['site_charset']);
}
