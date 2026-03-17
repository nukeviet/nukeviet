<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

$page_title = $nv_Lang->getModule('siteterms');

$content = '';

$sql = 'SELECT content FROM ' . NV_MOD_TABLE . "_config WHERE config='siteterms_" . NV_LANG_DATA . "'";
$row = $db->query($sql)->fetch();
if (empty($row)) {
    $mode = 'add';
} else {
    $content = $row['content'];
    $mode = 'edit';
}

if ($nv_Request->get_int('save', 'post') == 1) {
    $respon = [
        'status' => 'error',
        'mess' => ''
    ];

    $post_checkss = $nv_Request->get_string('checkss', 'post');
    if (!csrf_check($post_checkss, $csrf_key)) {
        $respon['mess'] = 'Wrong session';
        nv_jsonOutput($respon);
    }

    $content = $nv_Request->get_editor('content', '', NV_ALLOWED_HTML_TAGS);

    if (empty($content)) {
        $respon['mess'] = $nv_Lang->getModule('error_content');
        $respon['input'] = 'content';
        nv_jsonOutput($respon);
    }

    try {
        if ($mode == 'edit') {
            $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . "_config SET
                content = :content,
                edit_time = " . NV_CURRENTTIME . "
                WHERE config = 'siteterms_" . NV_LANG_DATA . "'");
        } else {
            $stmt = $db->prepare('INSERT INTO ' . NV_MOD_TABLE . "_config VALUES (
                'siteterms_" . NV_LANG_DATA . "', :content, " . NV_CURRENTTIME . ')');
        }

        $stmt->bindParam(':content', $content, PDO::PARAM_STR, strlen($content));
        $stmt->execute();

        nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('siteterms'), '', $admin_info['userid']);

        $respon['status'] = 'success';
        $respon['mess'] = $nv_Lang->getModule('saveok');
        nv_jsonOutput($respon);
    } catch (Throwable $e) {
        $respon['mess'] = $nv_Lang->getModule('errorsave');
        nv_jsonOutput($respon);
    }
}

$content = htmlspecialchars(nv_editor_br2nl($content));

if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $editor = nv_aleditor('content', '100%', '300px', $content);
} else {
    $editor = '<textarea style="width: 100%" name="content" id="content" cols="20" rows="8">' . $content . '</textarea>';
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('siteterms.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('CHECKSS', csrf_create($csrf_key));
$tpl->assign('EDITOR', $editor);

$contents = $tpl->fetch('siteterms.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
