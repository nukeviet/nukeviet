<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * utf8_to_unicode()
 * Vie^.t Nam => Array ([0] => 86 [1] => 105 [2] => 7879 [3] => 116 [4] => 32 [5] => 78 [6] => 97 [7] => 109)
 *
 * @param mixed $str
 * @return array
 */
function utf8_to_unicode($str)
{
    $unicode = [];
    $values = [];
    $lookingFor = 1;
    $strlen = strlen($str);

    for ($i = 0; $i < $strlen; ++$i) {
        $thisValue = ord($str[$i]);

        if ($thisValue < 128) {
            $unicode[] = $thisValue;
        } else {
            if (count($values) == 0) {
                $lookingFor = ($thisValue < 224) ? 2 : 3;
            }

            $values[] = $thisValue;

            if (count($values) == $lookingFor) {
                $number = ($lookingFor == 3) ? (($values[0] % 16) * 4096) + (($values[1] % 64) * 64) + ($values[2] % 64) : (($values[0] % 32) * 64) + ($values[1] % 64);

                $unicode[] = $number;
                $values = [];
                $lookingFor = 1;
            }
        }
    }

    return $unicode;
}

/**
 * unicode_to_entities()
 * Array ( [0] => 86 [1] => 105 [2] => 7879 [3] => 116 [4] => 32 [5] => 78 [6] => 97 [7] => 109 )
 * => &#86;&#105;&#7879;&#116;&#32;&#78;&#97;&#109;
 *
 * @param array $unicode
 * @return string
 */
function unicode_to_entities($unicode)
{
    $entities = '';
    foreach ($unicode as $value) {
        $entities .= '&#' . $value . ';';
    }

    return $entities;
}

/**
 * unicode_to_entities_preserving_ascii()
 * Array ( [0] => 86 [1] => 105 [2] => 7879 [3] => 116 [4] => 32 [5] => 78 [6] => 97 [7] => 109 )
 * => Vi&#7879;t Nam
 *
 * @param array $unicode
 * @return string
 */
function unicode_to_entities_preserving_ascii($unicode)
{
    $entities = '';
    foreach ($unicode as $value) {
        $entities .= ($value > 127) ? '&#' . $value . ';' : chr($value);
    }

    return $entities;
}

/**
 * unicode_to_utf8()
 * Array ( [0] => 86 [1] => 105 [2] => 7879 [3] => 116 [4] => 32 [5] => 78 [6] => 97 [7] => 109 )
 * => Vie^.t Nam
 *
 * @param array $str
 * @return string
 */
function unicode_to_utf8($str)
{
    $utf8 = '';

    foreach ($str as $unicode) {
        if ($unicode < 128) {
            $utf8 .= chr($unicode);
        } elseif ($unicode < 2048) {
            $utf8 .= chr(192 + (($unicode - ($unicode % 64)) / 64));
            $utf8 .= chr(128 + ($unicode % 64));
        } else {
            $utf8 .= chr(224 + (($unicode - ($unicode % 4096)) / 4096));
            $utf8 .= chr(128 + ((($unicode % 4096) - ($unicode % 64)) / 64));
            $utf8 .= chr(128 + ($unicode % 64));
        }
    }

    return $utf8;
}

/**
 * nv_str_split()
 *
 * @param string $str
 * @param int    $split_len
 * @return array|false
 */
function nv_str_split($str, $split_len = 1)
{
    if (!is_int($split_len) or $split_len < 1) {
        return false;
    }

    $len = nv_strlen($str);
    if ($len <= $split_len) {
        return [$str];
    }

    preg_match_all('/.{' . $split_len . '}|[^\x00]{1,' . $split_len . '}$/us', $str, $ar);

    return $ar[0];
}

/**
 * nv_strspn()
 *
 * @param string   $str
 * @param string   $mask
 * @param int|null $start
 * @param int|null $length
 * @return false|int|null
 */
function nv_strspn($str, $mask, $start = null, $length = null)
{
    if ($start !== null or $length !== null) {
        $str = nv_substr($str, $start, $length);
    }

    preg_match('/^[' . $mask . ']+/u', $str, $matches);

    if (isset($matches[0])) {
        return nv_strlen($matches[0]);
    }

    return 0;
}

/**
 * nv_ucfirst()
 *
 * @param string $str
 * @return false|string|null
 */
function nv_ucfirst($str)
{
    switch (nv_strlen($str)) {
        case 0:
            return '';
            break;
        case 1:
            return nv_strtoupper($str);
            break;
        default:
            preg_match('/^(.{1})(.*)$/us', $str, $matches);

            return nv_strtoupper($matches[1]) . $matches[2];
            break;
    }
}

/**
 * nv_ltrim()
 *
 * @param string $str
 * @param bool   $charlist
 * @return string|null
 */
function nv_ltrim($str, $charlist = false)
{
    if ($charlist === false) {
        return ltrim($str);
    }

    $charlist = preg_replace('!([\\\\\\-\\]\\[/^])!', '\\\${1}', $charlist);

    return preg_replace('/^[' . $charlist . ']+/u', '', $str);
}

/**
 * nv_rtrim()
 *
 * @param string $str
 * @param bool   $charlist
 * @return string|null
 */
function nv_rtrim($str, $charlist = false)
{
    if ($charlist === false) {
        return rtrim($str);
    }

    $charlist = preg_replace('!([\\\\\\-\\]\\[/^])!', '\\\${1}', $charlist);

    return preg_replace('/[' . $charlist . ']+$/u', '', $str);
}

/**
 * nv_trim()
 *
 * @param string $str
 * @param bool   $charlist
 * @return string|null
 */
function nv_trim($str, $charlist = false)
{
    if ($charlist === false) {
        return trim($str);
    }

    return nv_ltrim(nv_rtrim($str, $charlist), $charlist);
}

/**
 * nv_EncString()
 *
 * @param string $string
 * @return string
 */
function nv_EncString($string)
{
    if (file_exists(NV_ROOTDIR . '/includes/utf8/lookup_' . NV_LANG_DATA . '.php')) {
        include NV_ROOTDIR . '/includes/utf8/lookup_' . NV_LANG_DATA . '.php';
        $string = strtr($string, $utf8_lookup_lang);
    }

    include NV_ROOTDIR . '/includes/utf8/lookup.php';

    return strtr($string, $utf8_lookup['romanize']);
}

/**
 * change_alias()
 *
 * @param string $alias
 * @return string|null
 */
function change_alias($alias)
{
    $alias = preg_replace('/[\x{0300}\x{0301}\x{0303}\x{0309}\x{0323}]/u', '', $alias); // fix unicode consortium for Vietnamese
    $search = ['&amp;', '&#039;', '&quot;', '&lt;', '&gt;', '&#x005C;', '&#x002F;', '&#40;', '&#41;', '&#42;', '&#91;', '&#93;', '&#33;', '&#x3D;', '&#x23;', '&#x25;', '&#x5E;', '&#x3A;', '&#x7B;', '&#x7D;', '&#x60;', '&#x7E;'];

    return preg_replace(['/[^a-zA-Z0-9]/', '/[ ]+/', '/^[\-]+/', '/[\-]+$/'], [' ', '-', '', ''], str_replace($search, ' ', nv_EncString($alias)));
}

/**
 * change_alias_tags()
 *
 * @param string $alias
 * @return string|null
 */
function change_alias_tags($alias)
{
    // Pho phép dấu .
    $search = ['&lt;lt;', '&gt;gt;', '&#039;', '&quot;', '&lt;', '&gt;', '!', '*', '\'', '(', ')', ';', ':', '@', '&', '=', '+', '$', ',', '/', '?', '#', '[', ']', '"', '%', '-', '_', '<', '>', '\\', '^', '`', '{', '|', '}', '~'];

    return preg_replace(['/[ ]+/u', '/^[\-]+/u', '/[\-]+$/u'], ['-', '', ''], str_replace($search, ' ', $alias));
}

/**
 * nv_clean60()
 *
 * @param string $string
 * @param int    $num
 * @param bool   $specialchars
 * @return false|string
 */
function nv_clean60($string, $num = 60, $specialchars = true)
{
    $string = nv_unhtmlspecialchars(str_replace('&nbsp;', ' ', $string));

    $len = nv_strlen($string);
    if ($num and $num < $len) {
        if (!str_contains($string, ' ')) {
            $string = nv_substr($string, 0, $num);
        } else {
            while (ord(nv_substr($string, $num, 1)) != 32) {
                --$num;
            }
            $string = nv_substr($string, 0, $num) . '...';
        }
    }

    if ($specialchars) {
        $string = nv_htmlspecialchars($string);
    }

    return $string;
}

if (!function_exists('str_contains')) {
    /**
     * str_contains()
     *
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    function str_contains($haystack, $needle)
    {
        return $needle === '' or nv_strpos($haystack, $needle) !== false;
    }
}

if (!function_exists('str_starts_with')) {
    /**
     * str_starts_with()
     *
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    function str_starts_with($haystack, $needle)
    {
        return $needle === '' or nv_strpos($haystack, $needle) === 0;
    }
}

if (!function_exists('str_ends_with')) {
    /**
     * str_ends_with()
     *
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    function str_ends_with($haystack, $needle)
    {
        $length = nv_strlen($needle);

        return $length > 0 ? nv_substr($haystack, -$length, $length) === $needle : true;
    }
}

/**
 * text_split()
 * Hàm chia đoạn text ra làm 2 phần,
 * phần đầu có số lượng ký tự nhỏ hơn hoặc bằng giá trị $limit.
 * Không tách text ở vị trí giữa từ, mà  chỉ ở chỗ có dấu cách
 *
 * @param string $text
 * @param int    $limit
 * @return array
 */
function text_split($text, $limit = 200)
{
    if (nv_strlen($text) <= $limit) {
        return [$text];
    }

    for ($i = 0; $i < $limit; ++$i) {
        $rpos = nv_strrpos(nv_substr($text, 0, ($limit - $i)), ' ');
        if ($rpos !== false) {
            break;
        }
    }

    return [nv_substr($text, 0, $rpos), nv_substr($text, $rpos, null)];
}
