<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

date_default_timezone_set('Asia/Ho_Chi_Minh');

define('IP_FILEHEAD', "/**\n * @Project NUKEVIET 4.x\n * @This product includes GeoLite2 data created by MaxMind, available from http://www.maxmind.com\n * @Createdate " . gmdate("D, d M Y H:i:s") . " GMT\n */\n");

/**
 * nv_print_variable_ip()
 *
 * @param mixed $var_array
 * @return
 */
function nv_print_variable_ip($var_array)
{
    $data = array();
    foreach ($var_array as $k => $v) {
        $data[] = sprintf("%u", $k) . '=>array(' . sprintf("%u", $v[0]) . ',\'' . $v[1] . '\')';
    }
    $ct = 'array(' . implode(',', $data) . ')';
    /*
    $ct = print_r($var_array, true);
    $ct = str_replace('\r\n', '\n', $ct);
    $ct = preg_replace('/Array[\n\t\s]+\(/', "array(\n", $ct);
    $ct = preg_replace('/[\n\t\s]*\[0\] \=\> /', '', $ct);
    $ct = preg_replace('/[\n\t\s]*\[1\] \=\> /', ', \'', $ct);
    $ct = preg_replace('/([A-Z]{2})[\n\s\t]+\)/', '\\1\'),', $ct);
    $ct = preg_replace('/\[([0-9]+)\]/', '\\1', $ct);
    $ct = str_replace("\n\n", "\n", $ct);
    $ct = preg_replace('/\)\,([\n\s]+)\)/', ')\\1)', $ct);
    $ct = trim($ct, "\n");
    */
    return $ct;
}

/**
 * nv_print_variable_ip6()
 *
 * @param mixed $var_array
 * @return
 */
function nv_print_variable_ip6($var_array)
{
    $data = array();
    foreach ($var_array as $k => $v) {
        $data[] = '\'' . $k . '\'' . '=>\'' . $v . '\'';
    }
    $ct = 'array(' . implode(',', $data) . ')';
    /*
    $ct = print_r($var_array, true);
    $ct = str_replace('\r\n', '\n', $ct);
    $ct = preg_replace('/Array[\n\t\s]+\(/', "array(\n", $ct);
    $ct = str_replace('[', '\'', $ct);
    $ct = str_replace('] => ', '\' => \'', $ct);
    $ct = preg_replace('/\'([A-Z]{2})\n/', "'\\1',\n", $ct);
    $ct = preg_replace('/\'([A-Z]{2})\'\,([\n\s\t]+)\)/', "'\\1'\\2)", $ct);

    $ct = str_replace("\n\n", "\n", $ct);
    $ct = trim($ct, "\n");
    */
    return $ct;
}
