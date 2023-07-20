<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Uapi;

/**
 * NukeViet\Uapi\Uapi
 *
 * @package NukeViet\Uapi
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @version 4.6.00
 * @access public
 */
class Uapi
{
    private static $user_id = 0;
    private static $user_username = '';
    private static $user_groups = [];

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
        return preg_match('/^[a-z]+[a-z0-9]*$/i', $module);
    }

    /**
     * testParamKey()
     *
     * @param string $key
     * @return bool
     */
    public static function testParamKey($key = '')
    {
        return !is_numeric($key) and preg_match('/^[a-z0-9\_\-]+$/i', $key);
    }

    /**
     * setUserId()
     *
     * @param int $id
     */
    public static function setUserId($id)
    {
        self::$user_id = (int) $id;
    }

    /**
     * setUserName()
     *
     * @param string $username
     */
    public static function setUserName($username)
    {
        self::$user_username = $username;
    }

    /**
     * setUserGroups()
     *
     * @param array $groups
     */
    public static function setUserGroups($groups)
    {
        self::$user_groups = $groups;
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
     * getUserId()
     *
     * @return int
     */
    public static function getUserId()
    {
        return self::$user_id;
    }

    /**
     * getUserName()
     *
     * @return string
     */
    public static function getUserName()
    {
        return self::$user_username;
    }

    /**
     * getUserGroups()
     *
     * @return array
     */
    public static function getUserGroups()
    {
        return self::$user_groups;
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
        self::$user_id = 0;
        self::$user_username = '';
        self::$module_name = '';
        self::$module_info = [];
    }
}
