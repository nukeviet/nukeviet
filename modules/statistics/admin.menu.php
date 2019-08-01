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
if (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/language/' . NV_LANG_INTERFACE . '.php')) {
    require NV_ROOTDIR . '/modules/' . $module_file . '/language/' . NV_LANG_INTERFACE . '.php';
} elseif (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/language/' . NV_LANG_DATA . '.php')) {
    require NV_ROOTDIR . '/modules/' . $module_file . '/language/' . NV_LANG_DATA . '.php';
} elseif (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/language/en.php')) {
    require NV_ROOTDIR . '/modules/' . $module_file . '/language/en.php';
}

$allow_func = array(
    'main',
    'allbots',
    'allbrowsers',
    'allcountries',
    'allos',
    'allreferers',
    'referer'
);

$submenu['allbots'] = $lang_module['bot'];
$submenu['allbrowsers'] = $lang_module['browser'];
$submenu['allcountries'] = $lang_module['country'];
$submenu['allos'] = $lang_module['os'];
$submenu['allreferers'] = $lang_module['referer'];

if (defined('NV_IS_GODADMIN')) {
    $allow_func[] = 'cleardata';
    $submenu['cleardata'] = $lang_module['cleardata'];
}
