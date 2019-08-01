<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1-27-2010 5:25
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    die('Stop!!!');
}

$menu_top = array(
    'title' => $module_name,
    'module_file' => '',
    'custom_title' => $nv_Lang->getGlobal('mod_database')
);

$allow_func = array(
    'main',
    'savefile',
    'download',
    'optimize',
    'file',
    'getfile',
    'delfile'
);
if (defined('NV_IS_GODADMIN')) {
    $allow_func[] = 'setting';
    $allow_func[] = 'sampledata';
}
unset($page_title, $select_options);

define('NV_IS_FILE_DATABASE', true);

//Document
$array_url_instruction['main'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:database';
$array_url_instruction['file'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:database:file';
$array_url_instruction['setting'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:database:setting';
$array_url_instruction['sampledata'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:database:sampledata';

/**
 * nv_highlight_string()
 *
 * @param mixed $tab
 * @param string $type
 * @return
 */
function nv_highlight_string($tab, $type = 'sql')
{
    global $db;

    $db->query('SET SQL_QUOTE_SHOW_CREATE = 1');
    $show = $db->query('SHOW CREATE TABLE ' . $tab)->fetchColumn(1);
    $show = preg_replace('/(KEY[^\(]+)(\([^\)]+\))[\s\r\n\t]+(USING BTREE)/i', '\\1\\3 \\2', $show);
    $show = preg_replace('/[\s]+COLLATE[\s]+([a-zA-Z0-9\_]+)/i', '', $show);
    $show = preg_replace('/(default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP|DEFAULT CHARSET=\w+|COLLATE=\w+|character set \w+|collate \w+|AUTO_INCREMENT=\w+)/i', ' \\1', $show);

    if ($type == 'sql') {
        return highlight_string($show . ';', 1);
    } else {
        return highlight_string("<?php\n\n\$sql = \"" . $show . "\";\n\n?>", 1);
    }
}
