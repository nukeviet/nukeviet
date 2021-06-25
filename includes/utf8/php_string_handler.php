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

/**
 * nv_internal_encoding()
 *
 * @param mixed $encoding
 * @return false
 */
function nv_internal_encoding($encoding)
{
    return false;
}

/**
 * nv_strlen()
 *
 * @param string $string
 * @return false|int|null
 */
function nv_strlen($string)
{
    return preg_match_all('/./u', $string, $tmp);
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
    $nv_strlen = nv_strlen($string);
    if ($start < 0) {
        $start = $nv_strlen + $start;
    }
    if ($length < 0) {
        $length = $nv_strlen - $start + $length;
    }
    $xlen = $nv_strlen - $start;
    $length = ($length > $xlen) ? $xlen : $length;
    preg_match('/^.{' . $start . '}(.{0,' . $length . '})/us', $string, $tmp);

    return (isset($tmp[1])) ? $tmp[1] : false;
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
    $offset = ($offset < 0) ? 0 : $offset;
    if ($offset > 0) {
        preg_match('/^.{' . $offset . '}(.*)/us', $haystack, $dummy);
        $haystack = (isset($dummy[1])) ? $dummy[1] : '';
    }

    $p = strpos($haystack, $needle);
    if ($haystack == '' or $p === false) {
        return false;
    }
    $r = $offset;
    $i = 0;

    while ($i < $p) {
        if (ord($haystack[$i]) < 128) {
            $i = $i + 1;
        } else {
            $bvalue = decbin(ord($haystack[$i]));
            $i = $i + strlen(preg_replace('/^(1+)(.+)$/', '\1', $bvalue));
        }
        ++$r;
    }

    return $r;
}

/**
 * nv_strrpos()
 *
 * @param string     $haystack
 * @param string     $needle
 * @param mixed|null $offset
 * @return mixed
 */
function nv_strrpos($haystack, $needle, $offset = null)
{
    if ($offset === null) {
        $ar = explode($needle, $haystack);

        if (sizeof($ar) > 1) {
            array_pop($ar);
            $haystack = join($needle, $ar);

            return nv_strlen($haystack);
        }

        return false;
    }
    if (!is_int($offset)) {
        trigger_error('nv_strrpos expects parameter 3 to be long', E_USER_WARNING);

        return false;
    }

    $haystack = nv_substr($haystack, $offset);

    if (false !== ($pos = nv_strrpos($haystack, $needle))) {
        return $pos + $offset;
    }

    return false;
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
