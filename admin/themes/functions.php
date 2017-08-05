<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-2-2010 1:58
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    die('Stop!!!');
}

$menu_top = array(
    'title' => $module_name,
    'module_file' => '',
    'custom_title' => $lang_global['mod_themes']
);

define('NV_IS_FILE_THEMES', true);

//Document
$array_url_instruction['main'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:themes';
$array_url_instruction['config'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:themes:config';
$array_url_instruction['setuplayout'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:themes:setuplayout';
$array_url_instruction['blocks'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:themes:blocks';
$array_url_instruction['xcopyblock'] = 'https://wiki.nukeviet.vn/themes:xcopyblock';
$array_url_instruction['package_theme_module'] = 'https://wiki.nukeviet.vn/themes:package_theme_module';