<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 24-06-2011 10:35
 */
if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}
$xtpl = new XTemplate('album_management.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$page_title = "Quản lý album";

// $xtpl->assign('LANG', $lang_module);
// $xtpl->assign('GLANG', $lang_global);
// $xtpl->assign('rowcontent', $rowcontent);
// $xtpl->assign('ISCOPY', $copy);
// $xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
// $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
// $xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
// $xtpl->assign('MODULE_NAME', $module_name);
// $xtpl->assign('MODULE_DATA', $module_data);
// $xtpl->assign('OP', $op);

// $page_title = "Quản lý album";
// if($nv_Request->get_title('them', 'post'))
// {
//     $value = $nv_Request->get_title('tentheloai', 'post');
//     $xtpl->assign('tenalbum',$value);  
//     $sql = "INSERT INTO `nv4_vi_album_albums`(`description`, `status`, `userID`, `time`, `name_album`) VALUES (:mota,:add_time,:tenalbum)";
//     $sth = $db->prepare($sql);
//     $sth->bindParam("tenalbum",$value);
//     $sth->bindValue("add_time",time());
//     $sth->bindValue("userID",);
//     $ext = $sth->execute();
//     if($ext)
//     {
//         $err[] = "Thêm thành công";
//     } else {
//         $err[] = "Thêm thất bại";
//     }
// } 

// else if($nv_Request->get_title('sua', 'post'))
// {
//     $id = $nv_Request->get_title('id_cat', 'post');
//     $name_cat = $nv_Request->get_title('tentheloai', 'post');
//     $sql = "UPDATE `nv4_vi_album_albums` SET cat_name=:cat_name,update_time=:update_time WHERE id=:id";
//     $sth = $db->prepare($sql);
//     $sth->bindParam("cat_name",$name_cat);
//     $sth->bindParam("id",$id);
//     $sth->bindValue("update_time",time());
//     $ext = $sth->execute();
//     if($ext)
//     {
//         $err[] = "Cập nhật thành công";
//     } else {
//         $err[] = "Cập nhật thất bại";
//     }
// } else if($nv_Request->get_title('delete', 'post'))
// {
//     $id = $nv_Request->get_title('delete', 'post');
//     $sql = "DELETE FROM `nv4_vi_album_albums` WHERE id_album=:id";
//     $sth = $db->prepare($sql);
//     $sth->bindParam("id_album",$id);
//     $ext = $sth->execute();
//     if($ext)
//     {
//         $err[] = "Xoá thành công";
//     } else {
//         $err[] = "Xoá thất bại";
//     }
// }



$sql = "SELECT * FROM nv4_vi_album_albums";

$result = $db->query($sql);
while ($res = $result->fetch()) {
    $xtpl->assign('res',$res);
    $xtpl->parse('main.album_list');
}




$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
