<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

/**
 * @param int $number
 * @param string $word
 * @return string
 */
function plural($number, $word)
{
    $wordObj = array_map('trim', explode(',', $word));
    return $number . ' ' . $wordObj[0];
}

/**
 * @param string $str
 * @return string
 */
function searchPatternByLang($str)
{
    $unicode = [
        'a' => '(a|á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ)',
        'd' => '(d|đ|Đ)', // Trong mot so truong hop MySQL khong coi Đ la chu in hoa cua đ
        'e' => '(e|é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ)',
        'i' => '(i|í|ì|ỉ|ĩ|ị)',
        'o' => '(o|ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ)',
        'u' => '(u|ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự)',
        'y' => '(y|ý|ỳ|ỷ|ỹ|ỵ)'
    ];
    $str = strtolower($str);
    return strtr($str, $unicode);
}

/**
 * @param string $keyword
 * @return string
 */
function searchKeywordforSQL($keyword)
{
    return searchPatternByLang(nv_EncString($keyword));
}
