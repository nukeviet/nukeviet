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
