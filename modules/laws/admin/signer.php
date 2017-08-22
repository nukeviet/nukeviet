<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 24-06-2011 10:35
 */

if (!defined('NV_IS_FILE_ADMIN')) die('Stop!!!');

// Delete
if ($nv_Request->isset_request('del', 'post')) {
    if (!defined('NV_IS_AJAX')) die('Wrong URL');
    
    $id = $nv_Request->get_int('id', 'post', 0);
    
    if (empty($id)) {
        die('NO');
    }
    
    $sql = "SELECT title FROM " . NV_PREFIXLANG . "_" . $module_data . "_signer WHERE id=" . $id;
    $result = $db->query($sql);
    $title = $result->fetchColumn();
    
    if (empty($title)) {
        die('NO');
    }
    
    $sql = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_signer WHERE id=" . $id;
    $db->query($sql);
    
    $sql = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_row WHERE sgid=" . $id;
    $db->query($sql);
    
    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['scontent_delete'], $title, $admin_info['userid']);
    
    nv_htmlOutput('OK');
}

// Page title collum
$page_title = $lang_module['signer_list'];
$page = $nv_Request->get_int('page', 'get', 0);
$per_page = 30;
$array = array();

// Base data
$sql = "FROM " . NV_PREFIXLANG . "_" . $module_data . "_signer WHERE id!=0";
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;

$sql .= " ORDER BY id DESC";

// Get num row
$sql1 = "SELECT COUNT(*) " . $sql;
$result1 = $db->query($sql1);
$all_page = $result1->fetchColumn();

if ((!$all_page) and empty($data_search['type'])) {
    if ($page) {
        nv_redirect_location(NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=main");
    } else {
        nv_redirect_location(NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=scontent");
    }
}

// Build data
$i = 1;
$sql = "SELECT * " . $sql . " LIMIT " . $page . ", " . $per_page;
$result = $db->query($sql);

while ($row = $result->fetch()) {
    $array[] = array(
        "id" => $row['id'],
        "title" => $row['title'],
        "offices" => $row['offices'],
        "positions" => $row['positions'],
        "url_edit" => NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=scontent&amp;id=" . $row['id'],
        "class" => ($i % 2 == 0) ? " class=\"second\"" : ""
    );
    $i++;
}

$generate_page = nv_generate_page($base_url, $all_page, $per_page, $page);

$xtpl = new XTemplate("signer_list.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL);

foreach ($array as $row) {
    $xtpl->assign('ROW', $row);
    $xtpl->parse('main.row');
}

if (!empty($generate_page)) {
    $xtpl->assign('GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.generate_page');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';