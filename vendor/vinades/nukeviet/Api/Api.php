<?php

/**
 * @Project NUKEVIET 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @createdate 2/3/2012, 9:10
 */

namespace NukeViet\Api;

class Api
{
    const ADMIN_LEV_GOD = 1;
    const ADMIN_LEV_SP = 2;
    const ADMIN_LEV_MOD = 3;

    private static $admin_id = 0;
    private static $admin_username = '';
    private static $admin_lev = 0;

    private static $module_name = '';
    private static $module_info = [];

    /**
     * @param string $module
     * @return number
     */
    public static function test($module = '')
    {
        return preg_match('/^[^0-9]+[a-z0-9]{0,}$/', $module);
    }

    /**
     * @param string $key
     * @return number
     */
    public static function testParamKey($key = '')
    {
        return !is_numeric($key) and preg_match('/^[a-zA-Z0-9\_\-]+$/', $key);
    }

    /**
     * @param int $lev
     */
    public static function setAdminLev($lev)
    {
        self::$admin_lev = (int) $lev;
    }

    /**
     * @param int $id
     */
    public static function setAdminId($id)
    {
        self::$admin_id = (int) $id;
    }

    /**
     * @param string $username
     */
    public static function setAdminName($username)
    {
        self::$admin_username = $username;
    }

    /**
     * @param string $name
     */
    public static function setModuleName($name)
    {
        self::$module_name = $name;
    }

    /**
     * @param array $info
     */
    public static function setModuleInfo($info)
    {
        self::$module_info = $info;
    }

    /**
     * @return number
     */
    public static function getAdminLev()
    {
        return self::$admin_lev;
    }

    /**
     * @return number
     */
    public static function getAdminId()
    {
        return self::$admin_id;
    }

    /**
     * @return string
     */
    public static function getAdminName()
    {
        return self::$admin_username;
    }

    /**
     * @return string
     */
    public static function getModuleName()
    {
        return self::$module_name;
    }

    /**
     * @return array
     */
    public static function getModuleInfo()
    {
        return self::$module_info;
    }

    public static function reset()
    {
        self::$admin_id = 0;
        self::$admin_username = '';
        self::$admin_lev = 0;
        self::$module_name = '';
        self::$module_info = [];
    }
}
