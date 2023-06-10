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

$sql = 'SELECT f.func_name AS func_title,f.description,m.custom_title AS mod_custom_title FROM ' . NV_MODFUNCS_TABLE . ' AS f, ' . NV_MODULES_TABLE . ' AS m WHERE f.func_id=' . $id . ' AND f.in_module=m.title';
$row = $db->query($sql)->fetch();

if (empty($row)) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => 'NO_' . $id
    ]);
}

if ($nv_Request->get_int('save', 'post') == '1') {
    $description = $nv_Request->get_title('newvalue', 'post', '');

    if ($description != $row['description']) {
        $sth = $db->prepare('UPDATE ' . NV_MODFUNCS_TABLE . ' SET description= :description WHERE func_id=' . $id);
        $sth->bindParam(':description', $description, PDO::PARAM_STR);
        $sth->execute();

        $nv_Cache->delMod('modules');
    }

    nv_jsonOutput([
        'status' => 'OK',
        'reload' => 'show_funcs'
    ]);
}

nv_jsonOutput([
    'label' => $nv_Lang->getModule('change_func_description', $row['func_title'], $row['mod_custom_title']),
    'title' => $nv_Lang->getModule('description'),
    'value' => $row['description'],
    'maxlength' => !empty($global_config['description_length']) ? $global_config['description_length'] : 255,
    'type' => 'change_description',
    'id' => $id
]);
