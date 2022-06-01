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

$page_title = $lang_module['edit_title'];
$cid = $nv_Request->get_int('cid', 'get,post');
$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE cid=' . $cid;
$row = $db->query($sql)->fetch();

if (empty($row) or !isset($site_mod_comm[$row['module']])) {
    nv_redirect_location('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$dir = date('Y_m');
if (!is_dir(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $dir)) {
    $mk = nv_mkdir(NV_UPLOADS_REAL_DIR . '/' . $module_upload, $dir);
    if ($mk[0] > 0) {
        try {
            $db->query('INSERT INTO ' . NV_UPLOAD_GLOBALTABLE . "_dir (dirname, time) VALUES ('" . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $dir . "', 0)");
        } catch (PDOException $e) {
            trigger_error($e->getMessage());
        }
    }
}

if ($nv_Request->isset_request('save', 'post')) {
    $delete = $nv_Request->get_int('delete', 'post', 0);
    if ($delete) {
        if (!empty($row['attach'])) {
            nv_deletefile(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['attach']);
        }
        $count = $db->exec('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE cid=' . $cid);
    } else {
        $content = nv_editor_nl2br($nv_Request->get_editor('content', '', NV_ALLOWED_HTML_TAGS));
        $active = $nv_Request->get_int('active', 'post', 0);
        $active = ($active == 1) ? 1 : 0;
        $attach = $nv_Request->get_string('attach', 'post', '', true);
        if (!empty($attach)) {
            $attach = substr($attach, strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/'));
        }

        $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET content= :content, attach=:attach, status=' . $active . ' WHERE cid=' . $cid);
        $stmt->bindParam(':content', $content, PDO::PARAM_STR);
        $stmt->bindParam(':attach', $attach, PDO::PARAM_STR);
        $stmt->execute();
        $count = $stmt->rowCount();

        // Xóa file đính kèm cũ
        if ($attach != $row['attach'] and !empty($row['attach'])) {
            nv_deletefile(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['attach']);
        }
    }

    if ($count) {
        nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['edit_title'] . ': ' . $row['module'] . ', id: ' . $row['id'] . ', cid: ' . $row['cid'], $row['content'], $admin_info['userid']);

        if (isset($site_mods[$row['module']])) {
            $mod_info = $site_mods[$row['module']];
            if (file_exists(NV_ROOTDIR . '/modules/' . $mod_info['module_file'] . '/comment.php')) {
                include NV_ROOTDIR . '/modules/' . $mod_info['module_file'] . '/comment.php';
                $nv_Cache->delMod($row['module']);
            }
        }
    }
    header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    exit();
}

nv_status_notification(NV_LANG_DATA, $module_name, 'comment_queue', $cid);

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

$row['content'] = nv_htmlspecialchars(nv_editor_br2nl($row['content']));

if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $row['content'] = nv_aleditor('content', '100%', '250px', $row['content']);
} else {
    $row['content'] = '<textarea style="width:100%;height:250px" name="content">' . $row['content'] . '</textarea>';
}

$row['status'] = ($row['status']) ? 'checked="checked"' : '';

if (!empty($row['attach'])) {
    $row['attach'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['attach'];
}

$xtpl = new XTemplate('edit.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('OP', $op);
$xtpl->assign('CID', $cid);
$xtpl->assign('ROW', $row);
$xtpl->assign('UPLOADS_DIR', NV_UPLOADS_DIR . '/' . $module_upload);
$xtpl->assign('CURRENT_DIR', NV_UPLOADS_DIR . '/' . $module_upload . '/' . $dir);

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
