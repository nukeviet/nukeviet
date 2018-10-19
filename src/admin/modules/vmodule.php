<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-2-2010 12:55
 */

if (!defined('NV_IS_FILE_MODULES')) {
    die('Stop!!!');
}

$array_site_cat_module = array();
if ($global_config['idsite']) {
    $_module = $db->query('SELECT module FROM ' . $db_config['dbsystem'] . '.' . $db_config['prefix'] . '_site_cat t1 INNER JOIN ' . $db_config['dbsystem'] . '.' . $db_config['prefix'] . '_site t2 ON t1.cid=t2.cid WHERE t2.idsite=' . $global_config['idsite'])->fetchColumn();

    if (!empty($_module)) {
        $array_site_cat_module = explode(',', $_module);
    }
}

$title = $note = $modfile = $error = '';
$modules_site = array_map('strtolower', nv_scandir(NV_ROOTDIR . '/modules', $global_config['check_module']));

if ($nv_Request->get_title('checkss', 'post') == NV_CHECK_SESSION) {
    $title = $nv_Request->get_title('title', 'post', '', 1);
    $modfile = $nv_Request->get_title('module_file', 'post', '', 1);
    $note = $nv_Request->get_title('note', 'post', '', 1);
    $title = strtolower(change_alias($title));

    $modules_admin = nv_scandir(NV_ROOTDIR . '/' . NV_ADMINDIR, $global_config['check_module']);

    if (empty($title)) {
        $error = $nv_Lang->getModule('vmodule_no_title');
    } elseif (empty($modfile) or !preg_match($global_config['check_module'], $modfile)) {
        $error = $nv_Lang->getModule('vmodule_no_file');
    } elseif (in_array($title, $modules_site) or in_array($title, $modules_admin) or !preg_match($global_config['check_module'], $title)) {
        $error = $nv_Lang->getModule('vmodule_exit');
    } else {
        $version = '';
        $author = '';
        $note = nv_nl2br($note, '<br />');
        $module_data = preg_replace('/(\W+)/i', '_', $title);
        if (empty($array_site_cat_module) or in_array($modfile, $array_site_cat_module)) {
            // Xác định lại row trong CSDL
            $sql = "SELECT * FROM " . $db_config['prefix'] . "_setup_extensions WHERE is_virtual=1 AND type='module' AND title=:title";
            $sth = $db->prepare($sql);
            $sth->bindParam(':title', $modfile, PDO::PARAM_STR);
            $sth->execute();
            $row_extension = $sth->fetch();
            if (empty($row_extension)) {
                nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
            }

            try {
                $sth = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_setup_extensions (
                    type, title, is_sys, is_virtual, basename, table_prefix, version, addtime, author, note
                ) VALUES ( \'module\', :title, 0, 0, :basename, :table_prefix, :version, ' . NV_CURRENTTIME . ', :author, :note)');
                $sth->bindParam(':title', $title, PDO::PARAM_STR);
                $sth->bindParam(':basename', $row_extension['basename'], PDO::PARAM_STR);
                $sth->bindParam(':table_prefix', $module_data, PDO::PARAM_STR);
                $sth->bindParam(':version', $version, PDO::PARAM_STR);
                $sth->bindParam(':author', $author, PDO::PARAM_STR);
                $sth->bindParam(':note', $note, PDO::PARAM_STR);
                if ($sth->execute()) {
                    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('vmodule_add') . ' ' . $module_data, '', $admin_info['userid']);
                    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=setup&setmodule=' . $title . '&checkss=' . md5($title . NV_CHECK_SESSION));
                }
            } catch (PDOException $e) {
                trigger_error($e->getMessage());
            }
        }
    }
}

$page_title = $nv_Lang->getModule('vmodule_add');

$tpl = new \NukeViet\Template\Smarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);

$tpl->assign('NV_CHECK_SESSION', NV_CHECK_SESSION);
$tpl->assign('ERROR', $error);
$tpl->assign('DATA', [
    'title' => $title,
    'note' => $note,
    'modfile' => $modfile
]);

$sql = 'SELECT title FROM ' . $db_config['prefix'] . '_setup_extensions WHERE is_virtual=1 AND type=\'module\' ORDER BY addtime ASC';
$result = $db->query($sql);

$array_module = [];
while (list($modfile_i) = $result->fetch(3)) {
    if (in_array($modfile_i, $modules_site)) {
        if (!empty($array_site_cat_module) and !in_array($modfile_i, $array_site_cat_module)) {
            continue;
        }
        $array_module[] = $modfile_i;
    }
}

$tpl->assign('ARRAY_MODULE', $array_module);

$contents = $tpl->fetch('vmodule.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
