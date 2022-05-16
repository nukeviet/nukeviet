<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet;

/**
 * NukeViet\Site
 *
 * @package NukeViet
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @version 4.6.00
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
        $value = preg_replace('/([\x00-\x08][\x0b-\x0c][\x0e-\x20])/', '', $value);
        $value = preg_replace('/%u0([a-z0-9]{3})/i', '&#x\\1;', $value);
        $value = preg_replace('/%([a-z0-9]{2})/i', '&#x\\1;', $value);
        $value = str_ireplace(['&#x53;&#x43;&#x52;&#x49;&#x50;&#x54;', '&#x26;&#x23;&#x78;&#x36;&#x41;&#x3B;&#x26;&#x23;&#x78;&#x36;&#x31;&#x3B;&#x26;&#x23;&#x78;&#x37;&#x36;&#x3B;&#x26;&#x23;&#x78;&#x36;&#x31;&#x3B;&#x26;&#x23;&#x78;&#x37;&#x33;&#x3B;&#x26;&#x23;&#x78;&#x36;&#x33;&#x3B;&#x26;&#x23;&#x78;&#x37;&#x32;&#x3B;&#x26;&#x23;&#x78;&#x36;&#x39;&#x3B;&#x26;&#x23;&#x78;&#x37;&#x30;&#x3B;&#x26;&#x23;&#x78;&#x37;&#x34;&#x3B;', '/*', '*/', '<!--', '-->', '<!-- -->', '&#x0A;', '&#x0D;', '&#x09;', ''], '', $value);

        $search = '/&#[xX]0{0,8}(21|22|23|24|25|26|27|28|29|2a|2b|2d|2f|30|31|32|33|34|35|36|37|38|39|3a|3b|3d|3f|40|41|42|43|44|45|46|47|48|49|4a|4b|4c|4d|4e|4f|50|51|52|53|54|55|56|57|58|59|5a|5b|5c|5d|5e|5f|60|61|62|63|64|65|66|67|68|69|6a|6b|6c|6d|6e|6f|70|71|72|73|74|75|76|77|78|79|7a|7b|7c|7d|7e);?/i';
        $value = preg_replace_callback($search, function ($m) {
            return chr(hexdec($m[1]));
        }, $value);

        $search = '/&#0{0,8}(33|34|35|36|37|38|39|40|41|42|43|45|47|48|49|50|51|52|53|54|55|56|57|58|59|61|63|64|65|66|67|68|69|70|71|72|73|74|75|76|77|78|79|80|81|82|83|84|85|86|87|88|89|90|91|92|93|94|95|96|97|98|99|100|101|102|103|104|105|106|107|108|109|110|111|112|113|114|115|116|117|118|119|120|121|122|123|124|125|126);?/i';
        $value = preg_replace_callback($search, function ($m) {
            return chr($m[1]);
        }, $value);

        $search = ['&#60', '&#060', '&#0060', '&#00060', '&#000060', '&#0000060', '&#60;', '&#060;', '&#0060;', '&#00060;', '&#000060;', '&#0000060;', '&#x3c', '&#x03c', '&#x003c', '&#x0003c', '&#x00003c', '&#x000003c', '&#x3c;', '&#x03c;', '&#x003c;', '&#x0003c;', '&#x00003c;', '&#x000003c;', '&#X3c', '&#X03c', '&#X003c', '&#X0003c', '&#X00003c', '&#X000003c', '&#X3c;', '&#X03c;', '&#X003c;', '&#X0003c;', '&#X00003c;', '&#X000003c;', '&#x3C', '&#x03C', '&#x003C', '&#x0003C', '&#x00003C', '&#x000003C', '&#x3C;', '&#x03C;', '&#x003C;', '&#x0003C;', '&#x00003C;', '&#x000003C;', '&#X3C', '&#X03C', '&#X003C', '&#X0003C', '&#X00003C', '&#X000003C', '&#X3C;', '&#X03C;', '&#X003C;', '&#X0003C;', '&#X00003C;', '&#X000003C;', '\x3c', '\x3C', '\u003c', '\u003C'];

        return str_ireplace($search, '<', $value);
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
