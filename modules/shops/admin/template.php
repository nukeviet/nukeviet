<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

if (!defined('NV_IS_SPADMIN')) {
    Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    die();
}

$page_title = $lang_module['template'];
$error = "";
$savecat = 0;

$data = array(
    "title" => "",
    'alias' => ""
);

$table_name = $db_config['prefix'] . '_' . $module_data . '_template';
$data['id'] = $nv_Request->get_int('id', 'post,get', 0);
$savecat = $nv_Request->get_int('savecat', 'post', 0);

if ($nv_Request->isset_request('change_active', 'post')) {
    $id = $nv_Request->get_int('id', 'post', 0);

    $id = $db->query('SELECT id FROM ' . $table_name . ' WHERE id=' . $id)->fetchColumn();
    if (empty($id)) {
        die('NO_' . $id);
    }

    $new_status = $nv_Request->get_bool('new_status', 'post');
    $new_status = ( int )$new_status;

    $sql = 'UPDATE ' . $table_name . ' SET status=' . $new_status . ' WHERE id=' . $id;
    $db->query($sql);

    $nv_Cache->delMod($module_name);
    die('OK_' . $pid);
}

if (!empty($savecat)) {
    $preg_replace = array(
        'pattern' => '/[^a-zA-Z0-9\_]/',
        'replacement' => '_'
    );

    $data['title'] = nv_substr($nv_Request->get_title('title', 'post', ''), 0, 50);
    $data['alias'] = strtolower(change_alias($data['title']));

    $count = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_template WHERE alias=' . $db->quote($data['alias']) . ' AND id!=' . $data['id'])->fetchColumn();
    if ($count > 0) {
        $_tem_id = $db->query('SELECT MAX(id) FROM ' . $db_config['prefix'] . '_' . $module_data . '_template WHERE alias=' . $db->quote($data['alias']))->fetchColumn();
        $data['alias'] = $data['alias'] . '-' . $_tem_id;
    }

    if (empty($data['title'])) {
        $error = $lang_module['template_error_name'];
    } else {
        if ($data['id'] == 0) {
            $listfield = "";
            $listvalue = "";

            $sql = "INSERT INTO " . $table_name . " (status, " . NV_LANG_DATA . "_title, alias) VALUES (1, " . $db->quote($data['title']) . ", " . $db->quote($data['alias']) . ")";
            $templaid = $db->insert_id($sql);
            if ($templaid != 0) {
                $nv_Cache->delMod($module_name);
                Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
                die();
            } else {
                $error = $lang_module['errorsave'];
            }
        } else {
            $stmt = $db->prepare("UPDATE " . $table_name . " SET " . NV_LANG_DATA . "_title= :title WHERE id =" . $data['id']);
            $stmt->bindParam(':title', $data['title'], PDO::PARAM_STR);
            if ($stmt->execute()) {
                $error = $lang_module['saveok'];

                $nv_Cache->delMod($module_name);
                Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
                die();
            } else {
                $error = $lang_module['errorsave'];
            }
        }
    }
} else {
    if ($data['id'] > 0) {
        $data_old = $db->query("SELECT * FROM " . $table_name . " WHERE id=" . $data['id'])->fetch();
        $data = array(
            "id" => $data_old['id'],
            "title" => $data_old[NV_LANG_DATA . '_title'],
            "alias" => $data_old['alias']
        );
    }
}

$xtpl = new XTemplate("template.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('DATA', $data);
$xtpl->assign('caption', empty($data['id']) ? $lang_module['template_add'] : $lang_module['template_edit']);
$xtpl->assign('TEM_ADD', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=template#add");

$count = 0;
$result = $db->query("SELECT id, " . NV_LANG_DATA . "_title, alias, status FROM " . $table_name . " ORDER BY id DESC");

while (list($id, $title, $alias, $status) = $result->fetch(3)) {
    $xtpl->assign('title', $title);
    $xtpl->assign('alias', $alias);
    $xtpl->assign('active', $status ? 'checked="checked"' : '');
    $xtpl->assign('FIELD_TAB', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=field_tab&template=". $id);
    $xtpl->assign('link_edit', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&id=" . $id);
    $xtpl->assign('link_del', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=detemplate&id=" . $id);
    $xtpl->assign('id', $id);
    $xtpl->parse('main.data.row');
    ++$count;
}

$xtpl->assign('FIELD_ADD', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=fields#ffields");
$xtpl->assign('URL_DEL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=detemplate");
$xtpl->assign('URL_DEL_BACK', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);

if ($count > 0) {
    $xtpl->parse('main.data');
}

if ($error != '') {
    $xtpl->assign('error', $error);
    $xtpl->parse('main.error');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
