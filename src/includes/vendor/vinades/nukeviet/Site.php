<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet;

/**
 * NukeViet\Site
 *
 * @package NukeViet
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @version 5.x
 * @access public
 */
class Site
{
    /**
     * getEnv()
     *
     * @param mixed       $key
     * @param bool|string $negative_result
     * @return mixed
     */
    public static function getEnv($key, $negative_result = '')
    {
        if (!is_array($key)) {
            $key = [$key];
        }

        foreach ($key as $k) {
            if (isset($_SERVER[$k])) {
                return $_SERVER[$k];
            }
            if (isset($_ENV[$k])) {
                return $_ENV[$k];
            }
            if (@getenv($k)) {
                return @getenv($k);
            }
            if (function_exists('apache_getenv') and apache_getenv($k, true)) {
                return apache_getenv($k, true);
            }
        }

        return $negative_result;
    }

    /**
     * unhtmlentities()
     *
     * @param string $value
     * @return string
     */
    public static function unhtmlentities($value)
    {
        $value = preg_replace('/%3A%2F%2F/', '', $value); // :// to empty

        // Gom class đúng cú pháp: Loại bỏ các Control Characters (Null Byte, Vertical Tab...) an toàn
        $value = preg_replace('/([\x00-\x08\x0b-\x0c\x0e-\x1f])/', '', $value);

        $value = preg_replace('/%u0([a-z0-9]{3})/i', '&#x\1;', $value);
        $value = preg_replace('/%([a-z0-9]{2})/i', '&#x\1;', $value);

        // Loại bỏ các comment và ký tự ngắt dòng
        $value = str_ireplace(['/*', '*/', '<!--', '-->', '<!-- -->', '&#x0A;', '&#x0D;', '&#x09;'], '', $value);
        $value = str_replace(['&colon;', '&lpar;', '&rpar;', '&Tab;', '&NewLine;'], [':', '(', ')', '', ''], $value);

        // Ngăn chặn mã hóa SCRIPT/JAVASCRIPT với regex linh hoạt: Bắt tùy chọn dấu chấm phẩy và không giới hạn số 0
        $value = preg_replace('/(&#[xX]0*53;?|&#0*83;?)(&#[xX]0*43;?|&#0*67;?)(&#[xX]0*52;?|&#0*82;?)(&#[xX]0*49;?|&#0*73;?)(&#[xX]0*50;?|&#0*80;?)(&#[xX]0*54;?|&#0*84;?)/i', '', $value);
        $value = preg_replace('/(&#[xX]0*6a;?|&#0*106;?)(&#[xX]0*61;?|&#0*97;?)(&#[xX]0*76;?|&#0*118;?)(&#[xX]0*61;?|&#0*97;?)(&#[xX]0*73;?|&#0*115;?)(&#[xX]0*63;?|&#0*99;?)(&#[xX]0*72;?|&#0*114;?)(&#[xX]0*69;?|&#0*105;?)(&#[xX]0*70;?|&#0*112;?)(&#[xX]0*74;?|&#0*116;?)/i', '', $value);

        // Hỗ trợ giải mã không giới hạn số 0 (0*) thay vì giới hạn 0{0,8}
        $searchHex = '/&#[xX]0*(21|22|23|24|25|26|27|28|29|2a|2b|2d|2f|30|31|32|33|34|35|36|37|38|39|3a|3b|3d|3f|40|41|42|43|44|45|46|47|48|49|4a|4b|4c|4d|4e|4f|50|51|52|53|54|55|56|57|58|59|5a|5b|5c|5d|5e|5f|60|61|62|63|64|65|66|67|68|69|6a|6b|6c|6d|6e|6f|70|71|72|73|74|75|76|77|78|79|7a|7b|7c|7d|7e);?/i';
        $value = preg_replace_callback($searchHex, function ($m) {
            return chr(hexdec($m[1]));
        }, $value);

        $searchDec = '/&#0*(33|34|35|36|37|38|39|40|41|42|43|45|47|48|49|50|51|52|53|54|55|56|57|58|59|61|63|64|65|66|67|68|69|70|71|72|73|74|75|76|77|78|79|80|81|82|83|84|85|86|87|88|89|90|91|92|93|94|95|96|97|98|99|100|101|102|103|104|105|106|107|108|109|110|111|112|113|114|115|116|117|118|119|120|121|122|123|124|125|126);?/i';
        $value = preg_replace_callback($searchDec, function ($m) {
            return chr($m[1]);
        }, $value);

        // Thay thế toàn bộ mảng thay thế '<' tĩnh khổng lồ bằng regex thông minh xử lý mọi định dạng
        $value = preg_replace('/(&#[xX]0*3c;?|&#0*60;?|\\\\x3c|\\\\u003c)/i', '<', $value);

        return $value;
    }

    /**
     * unhtmlspecialchars()
     *
     * @param mixed $string
     * @return mixed
     */
    public static function unhtmlspecialchars($string)
    {
        if (empty($string)) {
            return $string;
        }

        if (is_array($string)) {
            $array_keys = array_keys($string);

            foreach ($array_keys as $key) {
                $string[$key] = self::unhtmlspecialchars($string[$key]);
            }
        } else {
            $string = str_replace(
                ['&amp;', '&#039;', '&quot;', '&lt;', '&gt;', '&#x005C;', '&#x002F;', '&#40;', '&#41;', '&#42;', '&#91;', '&#93;', '&#33;', '&#x3D;', '&#x23;', '&#x25;', '&#x5E;', '&#x3A;', '&#x7B;', '&#x7D;', '&#x60;', '&#x7E;'],
                ['&', '\'', '"', '<', '>', '\\', '/', '(', ')', '*', '[', ']', '!', '=', '#', '%', '^', ':', '{', '}', '`', '~'],
                $string
            );
        }

        return $string;
    }

    /**
     * function_exists()
     *
     * @param mixed $funcName
     * @param bool  $extension_loaded
     * @return bool
     */
    public static function function_exists($funcName, $extension_loaded = false)
    {
        $disable_functions = ini_get('disable_functions');
        $disable_functions = !empty($disable_functions) ? array_map('trim', preg_split("/[\s,]+/", $disable_functions)) : [];

        if (extension_loaded('suhosin')) {
            $disable_functions = array_merge($disable_functions, array_map('trim', preg_split("/[\s,]+/", ini_get('suhosin.executor.func.blacklist'))));
        }

        if ($extension_loaded) {
            return extension_loaded($funcName) and (empty($disable_functions) or (!empty($disable_functions) and !preg_grep('/^' . $funcName . '\_/', $disable_functions)));
        }

        return function_exists($funcName) and (empty($disable_functions) or !in_array($funcName, $disable_functions, true));
    }

    /**
     * class_exists()
     *
     * @param mixed $clName
     * @return bool
     */
    public static function class_exists($clName)
    {
        $disable_classes = ini_get('disable_classes');
        $disable_classes = !empty($disable_classes) ? array_map('trim', preg_split("/[\s,]+/", $disable_classes)) : [];

        return class_exists($clName, false) and (empty($disable_classes) or (!empty($disable_classes) and !in_array($clName, $disable_classes, true)));
    }
}
