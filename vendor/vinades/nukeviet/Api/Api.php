<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Api;

/**
 * NukeViet\Api\Api
 *
 * @package NukeViet\Api
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @version 4.5.00
 * @access public
 */
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
     * test()
     *
     * @param string $module
     * @return false|int
     */
    public static function test($module = '')
    {
        return preg_match('/^[^0-9]+[a-z0-9]{0,}$/', $module);
    }

    /**
     * testParamKey()
     *
     * @param string $key
     * @return bool
     */
    public static function testParamKey($key = '')
    {
        return !is_numeric($key) and preg_match('/^[a-zA-Z0-9\_\-]+$/', $key);
    }

    /**
     * setAdminLev()
     *
     * @param int $lev
     */
    public static function setAdminLev($lev)
    {
        self::$admin_lev = (int) $lev;
    }

    /**
     * setAdminId()
     *
     * @param int $id
     */
    public static function setAdminId($id)
    {
        self::$admin_id = (int) $id;
    }

    /**
     * setAdminName()
     *
     * @param string $username
     */
    public static function setAdminName($username)
    {
        self::$admin_username = $username;
    }

    /**
     * setModuleName()
     *
     * @param string $name
     */
    public static function setModuleName($name)
    {
        self::$module_name = $name;
    }

    /**
     * setModuleInfo()
     *
     * @param array $info
     */
    public static function setModuleInfo($info)
    {
        self::$module_info = $info;
    }

    /**
     * getAdminLev()
     *
     * @return int
     */
    public static function getAdminLev()
    {
        return self::$admin_lev;
    }

    /**
     * getAdminId()
     *
     * @return int
     */
    public static function getAdminId()
    {
        return self::$admin_id;
    }

    /**
     * getAdminName()
     *
     * @return string
     */
    public static function getAdminName()
    {
        return self::$admin_username;
    }

    /**
     * getModuleName()
     *
     * @return string
     */
    public static function getModuleName()
    {
        return self::$module_name;
    }

    /**
     * getModuleInfo()
     *
     * @return array
     */
    public static function getModuleInfo()
    {
        return self::$module_info;
    }

    /**
     * reset()
     */
    public static function reset()
    {
        self::$admin_id = 0;
        self::$admin_username = '';
        self::$admin_lev = 0;
        self::$module_name = '';
        self::$module_info = [];
    }
}
