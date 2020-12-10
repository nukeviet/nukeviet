<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if (! defined('NV_SYSTEM')) {
    die('Stop!!!');
}

define('NV_IS_MOD_SEARCH', true);

/**
 * LoadModulesSearch()
 *
 * @return
 */
function LoadModulesSearch()
{
    global $site_mods;

    $arrayfolder = array();
    foreach ($site_mods as $mod => $arr_mod) {
        if (file_exists(NV_ROOTDIR . '/modules/' . $arr_mod['module_file'] . '/search.php')) {
            $arrayfolder[$mod] = array(
                'module_name' => $mod,
                'module_file' => $arr_mod['module_file'],
                'module_data' => $arr_mod['module_data'],
                'custom_title' => $arr_mod['custom_title'],
                'adv_search' => isset($arr_mod['funcs']['search']) ? true : false,
            );
        }
    }
    return $arrayfolder;
}

/**
 * nv_substr_clean()
 *
 * @param mixed $string
 * @param string $mode
 * @return
 */
function nv_substr_clean($string, $mode = 'lr')
{
    $strlen = nv_strlen($string);
    $pos_bg = nv_strpos($string, ' ') + 1;
    $pos_en = nv_strrpos($string, ' ');
    if ($mode == 'l') {
        $string = '...' . nv_substr($string, $pos_bg, $strlen - $pos_bg);
    } elseif ($mode == 'r') {
        $string = nv_substr($string, 0, $strlen - $pos_en) . '...';
    } elseif ($mode == 'lr') {
        $string = '...' . nv_substr($string, $pos_bg, $pos_en - $pos_bg) . '...';
    }

    return $string;
}

/**
 * BoldKeywordInStr()
 *
 * @param mixed $str
 * @param mixed $keyword
 * @return
 */
function BoldKeywordInStr($str, $keyword, $logic)
{
    $str = nv_br2nl($str);
    $str = nv_nl2br($str, ' ');
    $str = nv_unhtmlspecialchars(strip_tags(trim($str)));

    $pos = false;

    if ($logic == 'AND') {
        $array_keyword = array( $keyword, nv_EncString($keyword) );
    } else {
        $keyword .= ' ' . nv_EncString($keyword);
        $array_keyword = explode(' ', $keyword);
        $array_keyword = array_unique($array_keyword);
    }

    foreach ($array_keyword as $k) {
        if (preg_match('/^(.*?)' . nv_preg_quote($k) . '/uis', $str, $matches)) {
            $strlen = nv_strlen($str);
            $kstrlen = nv_strlen($k);
            $residual = $strlen - 300;
            if ($residual > 0) {
                $lstrlen = nv_strlen($matches[1]);
                $rstrlen = $strlen - $lstrlen - $kstrlen;

                $medium = round((300 - $kstrlen) / 2);
                if ($lstrlen <= $medium) {
                    $str = nv_clean60($str, 300);
                } elseif ($rstrlen <= $medium) {
                    $str = nv_substr($str, $residual, 300);
                    $str = nv_substr_clean($str, 'l');
                } else {
                    $str = nv_substr($str, $lstrlen - $medium, $strlen - $lstrlen + $medium);
                    $str = nv_substr($str, 0, 300);
                    $str = nv_substr_clean($str, 'lr');
                }
            }

            $pos = true;
            break;
        }
    }

    if (! $pos) {
        return nv_clean60($str, 300);
    }

    $pattern = array();
    foreach ($array_keyword as $k) {
        $pattern[] = '/(' . nv_preg_quote($k) . ')/uis';
    }

    $str = preg_replace($pattern, '{\\1}', $str);
    $str = str_replace(array( '{', '}' ), array( '<span class="keyword">', '</span>' ), $str);

    return $str;
}

/**
 * nv_like_logic()
 *
 * @param mixed $field
 * @param mixed $dbkeyword
 * @param mixed $logic
 * @return
 */
function nv_like_logic($field, $dbkeyword, $logic)
{
    if ($logic == 'AND') {
        $return = $field . " LIKE '%" . $dbkeyword . "%'";
    } else {
        $return = $field . " LIKE '%" . str_replace(" ", "%' OR " . $field . " LIKE '%", $dbkeyword) . "%'";
    }
    return $return;
}
