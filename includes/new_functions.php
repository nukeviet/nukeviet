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

// Dành cho các phiên bản php nhỏ hơn 8.1
if (!function_exists('array_is_list')) {
    /**
     * array_is_list()
     * Kiểm tra mảng có phải có dạng danh sách hay không (key từ 0 đến n)
     *
     * @param mixed $a
     * @return bool
     */
    function array_is_list($a)
    {
        return is_array($a) && ($a === [] || (array_keys($a) === range(0, count($a) - 1)));
    }
}

// Dành cho các phiên bản php nhỏ hơn 7.3.0
if (!function_exists('array_key_first')) {
    /**
     * array_key_first()
     * Xuất ra key đầu tiền của mảng
     * 
     * @param mixed $array 
     * @return string|int|null|void 
     */
    function array_key_first($array)
    {
        if (!is_array($array) || empty($array)) {
            return null;
        }

        foreach ($array as $key => $unused) {
            return $key;
        }
    }
}

// Dành cho các phiên bản php nhỏ hơn 7.3.0
if (!function_exists('array_key_last')) {
    /**
     * array_key_last()
     * Xuất ra key cuối cùng của mảng
     * 
     * @param mixed $array 
     * @return int|string|null 
     */
    function array_key_last($array)
    {
        if (!is_array($array) || empty($array)) {
            return null;
        }

        return array_keys($array)[count($array) - 1];
    }
}
