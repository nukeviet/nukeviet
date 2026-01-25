<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN')) {
    exit('Stop!!!');
}

if (defined('NV_IS_GODADMIN')) {
    $submenu['roles'] = $nv_Lang->getModule('role_management');
    $submenu['credential'] = $nv_Lang->getModule('api_role_credential');
    $submenu['logs'] = $nv_Lang->getModule('logs');
    $submenu['config'] = $nv_Lang->getModule('config');
}
