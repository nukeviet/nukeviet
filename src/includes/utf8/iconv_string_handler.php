<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 22/8/2010, 19:33
 */

if (! defined('NV_MAINFILE')) {
    die('Stop!!!');
}

iconv_set_encoding('input_encoding', $global_config['site_charset']);
iconv_set_encoding('internal_encoding', $global_config['site_charset']);
iconv_set_encoding('output_encoding', $global_config['site_charset']);

/**
 * nv_internal_encoding()
 * 
 * @param mixed $encoding
 * @return
 */
function nv_internal_encoding($encoding)
{
    return iconv_set_encoding('internal_encoding', $encoding);
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

    return iconv_strlen($string, $global_config['site_charset']);
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

    return iconv_substr($string, $start, $length, $global_config['site_charset']);
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
    $needle = preg_quote($needle, '/');
    preg_match_all('/' . $needle . '/u', $haystack, $dummy);
    return sizeof($dummy[0]);
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

    return iconv_strpos($haystack, $needle, $offset, $global_config['site_charset']);
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

    return iconv_strrpos($haystack, $needle, $offset, $global_config['site_charset']);
}

/**
 * nv_strtolower()
 * 
 * @param mixed $string
 * @return
 */
function nv_strtolower($string)
{
    include NV_ROOTDIR . '/includes/utf8/lookup.php' ;

    return strtr($string, $utf8_lookup['strtolower']);
}

/**
 * nv_strtoupper()
 * 
 * @param mixed $string
 * @return
 */
function nv_strtoupper($string)
{
    include NV_ROOTDIR . '/includes/utf8/lookup.php' ;

    return strtr($string, $utf8_lookup['strtoupper']);
}
