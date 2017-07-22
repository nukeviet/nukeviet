<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 08 Jan 2017 01:38:09 GMT
 */
if (! defined('NV_IS_FILE_ADMIN'))
    die('Stop!!!');

// change status
if ($nv_Request->isset_request('change_status', 'post, get')) {
    $id = $nv_Request->get_int('id', 'post, get', 0);
    $content = 'NO_' . $id;

    $query = 'SELECT act FROM ' . NV_PREFIXLANG . '_' . $module_data . '_supporter WHERE id=' . $id;
    $row = $db->query($query)->fetch();
    if (isset($row['act'])) {
        $act = ($row['act']) ? 0 : 1;
        $query = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_supporter SET act=' . intval($act) . ' WHERE id=' . $id;
        $db->query($query);
        $content = 'OK_' . $id;
    }
    $nv_Cache->delMod($module_name);
    include NV_ROOTDIR . '/includes/header.php';
    echo $content;
    include NV_ROOTDIR . '/includes/footer.php';
    exit();
}

if ($nv_Request->isset_request('ajax_action', 'post')) {
    $id = $nv_Request->get_int('id', 'post', 0);
    $departmentid = $nv_Request->get_int('departmentid', 'post', 0);
    $new_vid = $nv_Request->get_int('new_vid', 'post', 0);
    $content = 'NO_' . $id;
    if ($new_vid > 0) {
        $sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_supporter WHERE id!=' . $id . ' AND departmentid=' . $departmentid . ' ORDER BY weight ASC';
        $result = $db->query($sql);
        $weight = 0;
        while ($row = $result->fetch()) {
            ++ $weight;
            if ($weight == $new_vid)
                ++ $weight;
            $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_supporter SET weight=' . $weight . ' WHERE id=' . $row['id'] . ' AND departmentid=' . $departmentid;
            $db->query($sql);
        }
        $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_supporter SET weight=' . $new_vid . ' WHERE id=' . $id  . ' AND departmentid=' . $departmentid;
        $db->query($sql);
        $content = 'OK_' . $id;
    }
    $nv_Cache->delMod($module_name);
    include NV_ROOTDIR . '/includes/header.php';
    echo $content;
    include NV_ROOTDIR . '/includes/footer.php';
    exit();
}

if ($nv_Request->isset_request('delete_id', 'get') and $nv_Request->isset_request('delete_checkss', 'get')) {
    $id = $nv_Request->get_int('delete_id', 'get');
    $departmentid = $nv_Request->get_int('departmentid', 'get');
    $delete_checkss = $nv_Request->get_string('delete_checkss', 'get');
    if ($id > 0 and $delete_checkss == md5($id . NV_CACHE_PREFIX . $client_info['session_id'])) {
        $weight = 0;
        $sql = 'SELECT weight FROM ' . NV_PREFIXLANG . '_' . $module_data . '_supporter WHERE id =' . $db->quote($id) . ' AND departmentid=' . $departmentid;
        $result = $db->query($sql);
        list ($weight) = $result->fetch(3);

        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_supporter  WHERE id = ' . $db->quote($id));
        if ($weight > 0) {
            $sql = 'SELECT id, weight FROM ' . NV_PREFIXLANG . '_' . $module_data . '_supporter WHERE weight >' . $weight . ' AND departmentid=' . $departmentid;
            $result = $db->query($sql);
            while (list ($id, $weight) = $result->fetch(3)) {
                $weight --;
                $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_supporter SET weight=' . $weight . ' WHERE id=' . intval($id)) . ' AND departmentid=' . $departmentid;
            }
        }
        $nv_Cache->delMod($module_name);
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
    }
}

$array_data = array();
$error = array();

$sql = 'SELECT id, full_name FROM ' . NV_PREFIXLANG . '_' . $module_data . '_department';
$array_department = $nv_Cache->db($sql, 'id', $module_name);

if (! empty($array_department)) {
    $departmentid = $nv_Request->get_int('departmentid', 'get', 0);

    if(empty($departmentid)){
        $departmentid = array_keys($array_department)[0];
    }

    $per_page = 20;
    $page = $nv_Request->get_int('page', 'post,get', 1);
    $db->sqlreset()
        ->select('COUNT(*)')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_supporter')
        ->where('departmentid=' . $departmentid);

    $sth = $db->prepare($db->sql());

    $sth->execute();
    $num_items = $sth->fetchColumn();

    $db->select('*')
        ->order('weight ASC')
        ->limit($per_page)
        ->offset(($page - 1) * $per_page);
    $sth = $db->prepare($db->sql());

    $sth->execute();

    while ($view = $sth->fetch()) {
        $array_data[] = $view;
    }
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('MODULE_UPLOAD', $module_upload);
$xtpl->assign('OP', $op);
$xtpl->assign('ROW', $row);
$xtpl->assign('URL_ADD_SUPPORTER', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=supporter-content&departmentid=' . $departmentid);

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
$generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);
if (! empty($generate_page)) {
    $xtpl->assign('NV_GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.generate_page');
}
$number = $page > 1 ? ($per_page * ($page - 1)) + 1 : 1;
if (! empty($array_data)) {
    foreach ($array_data as $view) {
        for ($i = 1; $i <= $num_items; ++ $i) {
            $xtpl->assign('WEIGHT', array(
                'key' => $i,
                'title' => $i,
                'selected' => ($i == $view['weight']) ? ' selected="selected"' : ''
            ));
            $xtpl->parse('main.loop.weight_loop');
        }
        $xtpl->assign('CHECK', $view['act'] == 1 ? 'checked' : '');
        $view['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=supporter-content&amp;id=' . $view['id'];
        $view['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_id=' . $view['id'] . '&amp;departmentid=' . $departmentid . '&amp;delete_checkss=' . md5($view['id'] . NV_CACHE_PREFIX . $client_info['session_id']);
        $xtpl->assign('VIEW', $view);
        $xtpl->parse('main.loop');
    }
}

if (! empty($array_department)) {
    foreach ($array_department as $department) {
        $department['selected'] = $department['id'] == $departmentid ? 'selected="selected"' : '';
        $xtpl->assign('DEPARTMENT', $department);
        $xtpl->parse('main.department');
    }
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['supporter'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';