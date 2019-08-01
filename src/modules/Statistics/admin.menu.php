<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 07/30/2013 10:27
 */

if (!defined('NV_ADMIN')) {
    die('Stop!!!');
}

//Ket noi ngon ngu cua module
$nv_Lang->loadModule($module_file, false, true);

$allow_func = array(
    'main',
    'allbots',
    'allbrowsers',
    'allcountries',
    'allos',
    'allreferers',
    'referer'
);

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
