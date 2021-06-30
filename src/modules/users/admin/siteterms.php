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

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

$page_title = $lang_module['siteterms'];

$error = $content = '';

$sql = 'SELECT content FROM ' . NV_MOD_TABLE . "_config WHERE config='siteterms_" . NV_LANG_DATA . "'";
$row = $db->query($sql)->fetch();
if (empty($row)) {
    $mode = 'add';
} else {
    $content = $row['content'];
    $mode = 'edit';
}

$checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . NV_LANG_DATA);
if ($nv_Request->get_int('save', 'post') == 1) {
    $content = $nv_Request->get_editor('content', '', NV_ALLOWED_HTML_TAGS);

    if (empty($content)) {
        $error = $lang_module['error_content'];
    } elseif ($checkss == $nv_Request->get_string('checkss', 'post')) {
        if ($mode == 'edit') {
            $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . "_config SET
				content= :content,
				edit_time='" . NV_CURRENTTIME . "'
				WHERE config ='siteterms_" . NV_LANG_DATA . "'");

            $stmt->bindParam(':content', $content, PDO::PARAM_STR, strlen($content));
            $stmt->execute();
        } else {
            $stmt = $db->prepare('INSERT INTO ' . NV_MOD_TABLE . "_config VALUES (
				'siteterms_" . NV_LANG_DATA . "', :content, " . NV_CURRENTTIME . ')');
        }

        $stmt->bindParam(':content', $content, PDO::PARAM_STR, strlen($content));
        if ($stmt->execute()) {
            $error = $lang_module['saveok'];
        } else {
            $error = $lang_module['errorsave'];
        }
    }
}

$content = htmlspecialchars(nv_editor_br2nl($content));

$xtpl = new XTemplate('siteterms.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('CHECKSS', $checkss);

$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);

if (!empty($error)) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}

if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $data = nv_aleditor('content', '100%', '300px', $content);
} else {
    $data = '<textarea style="width: 100%" name="content" id="content" cols="20" rows="8">' . $content . '</textarea>';
}

$xtpl->assign('DATA', $data);

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
