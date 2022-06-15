<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

$module_version = [
    'name' => 'Statistics',
    'modfuncs' => 'main,allreferers,allcountries,allbrowsers,allos,allbots,referer',
    'change_alias' => 'allreferers,allcountries,allbrowsers,allos,allbots,referer',
    'submenu' => 'main,allreferers,allcountries,allbrowsers,allos,allbots',
    'layoutdefault' => 'body:main,allreferers,allcountries,allbrowsers,allos,allbots',
    'is_sysmod' => 0,
    'virtual' => 2,
    'version' => '4.5.02',
    'date' => 'Monday, June 20, 2022 4:00:00 PM GMT+07:00',
    'author' => 'VINADES.,JSC <contact@vinades.vn>',
    'note' => ''
];
