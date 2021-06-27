<?php

/**
 * NUKEVIET Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN')) {
    exit('Stop!!!');
}

//Ket noi ngon ngu cua module
$nv_Lang->loadModule($module_file, false, true);

$allow_func = [
    'main',
    'allbots',
    'allbrowsers',
    'allcountries',
    'allos',
    'allreferers',
    'referer'
];

$submenu['allbots'] = $nv_Lang->getModule('bot');
$submenu['allbrowsers'] = $nv_Lang->getModule('browser');
$submenu['allcountries'] = $nv_Lang->getModule('country');
$submenu['allos'] = $nv_Lang->getModule('os');
$submenu['allreferers'] = $nv_Lang->getModule('referer');

if (defined('NV_IS_GODADMIN')) {
    $allow_func[] = 'cleardata';
    $submenu['cleardata'] = $nv_Lang->getModule('cleardata');
}

$nv_Lang->changeLang();
