<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$contents = [];
$page_title = $lang_module['add_image'];
$posterID = $admin_info['admin_id'];
$array = array();
$error = "";
$time = time();
//them anh
if ($nv_Request->isset_request('submit', 'post')) {
    $array['title'] = $nv_Request->get_title('title', 'post', '', 1);
    $array['albumid'] = $nv_Request->get_int('albumid', 'post', 0);
    $array['status'] = $nv_Request->get_title("status", "post") ? 1 : 0;
    $imageUrl = '';

    if (isset($_FILES, $_FILES['image'], $_FILES['image']['tmp_name']) and is_uploaded_file($_FILES['image']['tmp_name'])) {
        // Khởi tạo Class upload
        $upload = new NukeViet\Files\Upload($admin_info['allow_files_type'], $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT);
        
        // Thiết lập ngôn ngữ, nếu không có dòng này thì ngôn ngữ trả về toàn tiếng Anh
        $upload->setLanguage($lang_global);
        
        // Tải file lên server
        $upload_info = $upload->save_file($_FILES['image'], NV_UPLOADS_REAL_DIR.'/album', false, $global_config['nv_auto_resize']);
        if(!$upload_info['error'])
        {
            $imageUrl = '/nukeviet/uploads/album/'. $upload_info['basename'];
        }
    }

    $sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_image (ten_anh, id_album, url_anh, time, trangthai,id_user) VALUES (
        " . $db->quote($array['title']) . ",
        " . $db->quote($array['albumid']) . ",
        " . $db->quote($imageUrl) . ",
        " . $db->quote($time) . ",
        " . $db->quote($array['status']) . ",
        " . $posterID . ")";

        $id = $db->insert_id($sql);
        if (!$id) {
            $error = $lang_module['error_image'];
            $is_error = true;
        } else {
            nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['add_image'], "ID " . $id, $admin_info['userid']);
            Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
            exit();
        }
}
//sua anh
if ($nv_Request->isset_request('edit', 'get')) {
    $is_error = false;
    $array['id'] = $nv_Request->get_int('id', 'get', 0);
    $action = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;edit=1&amp;id=" . $array['id'];

    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_image WHERE id=" . $array['id'];
    $result1 = $db->query($sql);
    $row = $result1->fetch();
    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id=" . $row['id_album'];
    $row1 = $db->query($sql)->fetch();

    if ($nv_Request->isset_request('submit', 'post')) {
        $array['title'] = $nv_Request->get_title('title', 'post', '', 1);
        $array['albumid'] = $nv_Request->get_int('albumid', 'post', 0);
        $array['status'] = $nv_Request->get_title("status", "post") ? 1 : 0;
        $imageUrl = '';
    
        if (isset($_FILES, $_FILES['image'], $_FILES['image']['tmp_name']) and is_uploaded_file($_FILES['image']['tmp_name'])) {
            // Khởi tạo Class upload
            $upload = new NukeViet\Files\Upload($admin_info['allow_files_type'], $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT);
            
            // Thiết lập ngôn ngữ, nếu không có dòng này thì ngôn ngữ trả về toàn tiếng Anh
            $upload->setLanguage($lang_global);
            
            // Tải file lên server
            $upload_info = $upload->save_file($_FILES['image'], NV_UPLOADS_REAL_DIR.'/album', false, $global_config['nv_auto_resize']);
            if(!$upload_info['error'])
            {
                $imageUrl = '/nukeviet/uploads/album/'. $upload_info['basename'];
            }
        }
        // if($imageUrl == "")
        // {
        //     $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_songs SET
        //     song_name=" . $db->quote($array['title']) . ",
        //     singer_name=" . $db->quote($array['singer_name']) . ",
        //     cat_name=" . $db->quote($array['topic_parent']) . ",
        //     WHERE id=" . $array['id'];
        // } else {
        //     $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_songs SET
        //     song_name=" . $db->quote($array['title']) . ",
        //     path=" . $db->quote($imageUrl) . ",
        //     singer_name=" . $db->quote($array['singer_name']) . ",
        //     cat_name=" . $db->quote($array['topic_parent']) . ",
        //     WHERE id=" . $array['id'];
        // }
    }else{
        $array['title'] = $row['ten_anh'];
        $array['albumid'] = $row1['id'];
        $array['status'] = $row['trangthai'];
    }
   
}
//xoa anh
if ($nv_Request->isset_request('action', 'post,get')) {
    $id = $nv_Request->get_int('id', 'post,get', 0);
    $checksess = $nv_Request->get_title('checksess', 'post,get', '');
    if ($id > 0 and $checksess == md5($id . NV_CHECK_SESSION)) {
        $sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_image WHERE id = ' . $id;
        $db->query($sql);
    }
}


$xtpl = new XTemplate('add_image.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php');
$xtpl->assign('UPLOADS_DIR_USER', NV_UPLOADS_DIR . '/' . $module_upload);
$xtpl->assign('UPLOAD_CURRENT', NV_UPLOADS_DIR . '/' . $module_upload);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('CONTENTS', $contents);
$xtpl->assign('DATA', $array);
$xtpl->assign('FORM_ACTION', $action);

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data;
$_rows = $db->query($sql)->fetchAll();

$array_album = [];
foreach ($_rows as $key => $val) {
    $array_album = [
        'key' => $key+1,
        'val' => $val['ten_album'],
        'selected' => $key == $post['tid'] ? " selected=\"selected\"" : ""
    ];
    $xtpl->assign('ALBUM', $array_album);
    $xtpl->parse('main.album');
}

$sql = "SELECT a.id, a.ten_anh, a.id_album, a.url_anh, a.trangthai, b.username, c.ten_album FROM " . NV_PREFIXLANG . "_" . $module_data . "_image a, nv4_users b, nv4_vi_album c
        WHERE a.id_user=b.userid and a.id_album=c.id";
$result = $db->query($sql);
if (sizeof($result) < 1) {
    $xtpl->parse('main.empty');
} else {
    $i = 1;
    foreach ($result as $row) {
        $row['stt'] = $i;
        $row['thoigian'] = date("d/m/Y", $row['time']);
        $xtpl->assign('ROW', $row);
        $xtpl->assign('EDIT_URL', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;edit=1&amp;id=" . $row['id']);
        $xtpl->assign('DELETE_URL', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;delete=1&amp;id=" . $row['id'] . "&action=delete&checksess=" . md5($row['id'] . NV_CHECK_SESSION));
        $xtpl->parse('main.row.loop');
       
        $i++;   
        
    }
    $xtpl->parse('main.row');
    
}


$xtpl->parse('main');

$contents = $xtpl->text('main');
$page_title = $lang_module['main_caption'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
