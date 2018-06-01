<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2/3/2012, 9:10
 */

namespace NukeViet\Api;

class Api
{
    const ADMIN_LEV_GOD = 1;
    const ADMIN_LEV_SP = 2;
    const ADMIN_LEV_MOD = 3;

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
        return (!is_numeric($key) and preg_match('/^[a-zA-Z0-9\_\-]+$/', $key));
    }
}
