<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_MODULES')) {
    exit('Stop!!!');
}

if (!$nv_Request->isset_request('id', 'post,get')) {
    exit('Stop!!!');
}

$id = $nv_Request->get_int('id', 'post,get', 0);

$sql = 'SELECT f.func_name AS func_title,f.func_custom_name AS func_custom_title,f.alias AS fun_alias, m.custom_title AS mod_custom_title FROM ' . NV_MODFUNCS_TABLE . ' AS f, ' . NV_MODULES_TABLE . ' AS m WHERE f.func_id=' . $id . ' AND f.in_module=m.title';
$row = $db->query($sql)->fetch();

if (!isset($row['func_title']) or $row['func_title'] == 'main') {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => 'NO_' . $id
    ]);
}

if ($nv_Request->get_int('save', 'post') == '1') {
    $fun_alias = $nv_Request->get_title('newvalue', 'post', '', 1);

    if (empty($fun_alias)) {
        $fun_alias = $row['func_title'];
    }
    $fun_alias = strtolower(change_alias($fun_alias));

    if ($fun_alias != $row['fun_alias']) {
        $sth = $db->prepare('UPDATE ' . NV_MODFUNCS_TABLE . ' SET alias= :alias WHERE func_id=' . $id);
        $sth->bindParam(':alias', $fun_alias, PDO::PARAM_STR);
        $sth->execute();

        $nv_Cache->delMod('modules');
    }

    nv_jsonOutput([
        'status' => 'OK',
        'reload' => 'show_funcs'
    ]);
}

nv_jsonOutput([
    'label' => sprintf($lang_module['change_fun_alias'], $row['func_title'], $row['mod_custom_title']),
    'title' => $lang_module['funcs_alias'],
    'value' => $row['fun_alias'],
    'maxlength' => 255,
    'type' => 'change_alias',
    'id' => $id
]);
