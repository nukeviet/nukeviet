<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = $lang_module['location'];

$table_name = $db_config['prefix'] . '_' . $module_data . '_location';
$error = $admins = '';
$savelocation = 0;
$data = array();
list($data['id'], $data['parentid'], $data['title']) = array( 0, 0, '' );

$page = $nv_Request->get_int('page', 'get', 1);
$per_page = 20;
$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=location';

$savelocation = $nv_Request->get_int('savelocation', 'post', 0);

if (! empty($savelocation)) {
    $data['id'] = $nv_Request->get_int('id', 'post', 0);
    $data['parentid_old'] = $nv_Request->get_int('parentid_old', 'post', 0);
    $data['parentid'] = $nv_Request->get_int('parentid', 'post', 0);
    $data['title'] = nv_substr($nv_Request->get_title('title', 'post', '', 1), 0, 255);

    if ($data['title'] == '') {
        $error = $lang_module['location_name_empty'];
    }

    if ($data['id'] == 0 and $data['title'] != '' and $error == '') {
        $_sql = 'SELECT max(weight) FROM ' . $table_name . ' WHERE parentid=' . $data['parentid'];
        $weight = $db->query($_sql)->fetchColumn();
        $weight = intval($weight) + 1;

        $subid = '';

        $sql = "INSERT INTO " . $table_name . " (parentid, title, weight, sort, lev, numsub, subid ) VALUES (" . $data['parentid'] . ", :title ," . (int)$weight . ", '0', '0', '0', :subid )";

        $data_insert = array();
        $data_insert['subid'] = $subid;
        $data_insert['title'] = $data['title'];
        $newid = intval($db->insert_id($sql, 'id', $data_insert));

        if ($newid > 0) {
            nv_insert_logs(NV_LANG_DATA, $module_name, 'log_add_location', 'id ' . $newid, $admin_info['userid']);
            nv_fix_location_order();
            $nv_Cache->delMod($module_name);
            Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&parentid=' . $data['parentid']);
            die();
        } else {
            $error = $lang_module['errorsave'];
        }
    } elseif ($data['id'] > 0 and $data['title'] != '' and $error == '') {
        try {
            $stmt = $db->prepare('UPDATE ' . $table_name . ' SET parentid=' . $data['parentid'] . ', title= :title WHERE id =' . $data['id']);
            $stmt->bindParam(':title', $data['title'], PDO::PARAM_STR);
            if ($stmt->execute()) {
                nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['location_edit'], $data['title'], $admin_info['userid']);
                if ($data['parentid'] != $data['parentid_old']) {
                    $stmt = $db->prepare('SELECT max(weight) FROM ' . $table_name . ' WHERE parentid= :parentid');
                    $stmt->bindParam(':parentid', $data['parentid'], PDO::PARAM_INT);
                    $stmt->execute();
                    $weight = $stmt->fetchColumn();
                    $weight = intval($weight) + 1;
                    $sql = 'UPDATE ' . $table_name . ' SET weight=' . $weight . ' WHERE id=' . intval($data['id']);
                    $db->query($sql);
                    nv_fix_location_order();
                }

                $nv_Cache->delMod($module_name);

                Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&parentid=' . $data['parentid']);
                die();
            }
        } catch (PDOException $e) {
            $error = $lang_module['errorsave'];
        }
    }
}

$data['parentid'] = $nv_Request->get_int('parentid', 'get,post', 0);
$data['id'] = $nv_Request->get_int('id', 'get', 0);

if ($data['id'] > 0) {
    list($data['id'], $data['parentid'], $data['title']) = $db->query('SELECT id, parentid, title FROM ' . $table_name . ' where id=' . $data['id'])->fetch(3);
    $caption = $lang_module['location_edit'];
} else {
    $caption = $lang_module['location_add'];
}

$sql = "SELECT id, title, lev FROM " . $table_name . " WHERE id !='" . $data['id'] . "' ORDER BY sort ASC";
$result = $db->query($sql);
$array_location_list = array();
$array_location_list[0] = array( '0', $lang_module['location_not_in'] );

while (list($id_i, $title_i, $lev_i) = $result->fetch(3)) {
    $xtitle_i = '';
    if ($lev_i > 0) {
        $xtitle_i .= '&nbsp;';
        for ($i = 1; $i <= $lev_i; $i++) {
            $xtitle_i .= '---';
        }
    }
    $xtitle_i .= $title_i;
    $array_location_list[] = array( $id_i, $xtitle_i );
}

$xtpl = new XTemplate('location.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('CAPTION', $caption);
$xtpl->assign('DATA', $data);
$xtpl->assign('LOCATION_LIST', shops_show_location_list($data['parentid'], $page, $per_page, $base_url));
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $data['id'] . '&amp;parentid=' . $data['parentid']);

$xtpl->assign('LOCALTION_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=location');
$xtpl->assign('CARRIER_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=carrier');
$xtpl->assign('CONFIG_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=carrier_config');
$xtpl->assign('SHOPS_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=shops');

if ($error != '') {
    $xtpl->assign('error', $error);
    $xtpl->parse('main.error');
}

foreach ($array_location_list as $rows_i) {
    $sl = ($rows_i[0] == $data['parentid']) ? ' selected="selected"' : '';
    $xtpl->assign('plocal_i', $rows_i[0]);
    $xtpl->assign('ptitle_i', $rows_i[1]);
    $xtpl->assign('pselect', $sl);
    $xtpl->parse('main.parent_loop');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
