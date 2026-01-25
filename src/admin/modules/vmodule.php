<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_MODULES')) {
    exit('Stop!!!');
}

$array_site_cat_module = [];
if ($global_config['idsite']) {
    $_module = $db->query('SELECT module FROM ' . $db_config['dbsystem'] . '.' . $db_config['prefix'] . '_site_cat t1 INNER JOIN ' . $db_config['dbsystem'] . '.' . $db_config['prefix'] . '_site t2 ON t1.cid=t2.cid WHERE t2.idsite=' . $global_config['idsite'])->fetchColumn();

    if (!empty($_module)) {
        $array_site_cat_module = explode(',', $_module);
    }
}

$modules_site = nv_scandir(NV_ROOTDIR . '/modules', $global_config['check_module']);

if ($nv_Request->get_title('checkss', 'post') == NV_CHECK_SESSION) {
    $title = $nv_Request->get_title('title', 'post', '', 1);
    $title = strtolower(change_alias($title));
    $modfile = $nv_Request->get_title('m_file', 'post', '', 1);
    $note = $nv_Request->get_title('note', 'post', '', 1);

    if (empty($title)) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'title',
            'mess' => $nv_Lang->getGlobal('required_invalid'),
        ]);
    }
    if (empty($modfile)) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'm_file',
            'mess' => $nv_Lang->getGlobal('required_invalid'),
        ]);
    }

    $modules_admin = nv_scandir(NV_ROOTDIR . '/' . NV_ADMINDIR, $global_config['check_module']);

    // Kiểm tra tồn tại
    $sql = "SELECT * FROM " . $db_config['prefix'] . "_setup_extensions WHERE type='module' AND title=" . $db->quote($title);
    $is_exists = $db->query($sql)->fetch();

    if (!$is_exists and !in_array($title, $modules_site, true) and !in_array($title, $modules_admin, true) and preg_match($global_config['check_module'], $title) and preg_match($global_config['check_module'], $modfile)) {
        $version = '';
        $author = '';
        $note = nv_nl2br($note, '<br />');
        $module_data = preg_replace('/(\W+)/i', '_', $title);
        if (empty($array_site_cat_module) or in_array($modfile, $array_site_cat_module, true)) {
            try {
                $sth = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_setup_extensions (
                    type, title, is_sys, is_virtual, basename, table_prefix, version, addtime, author, note
                ) VALUES (
                    \'module\', :title, 0, 0, :basename, :table_prefix, :version, ' . NV_CURRENTTIME . ', :author, :note
                )');
                $sth->bindParam(':title', $title, PDO::PARAM_STR);
                $sth->bindParam(':basename', $modfile, PDO::PARAM_STR);
                $sth->bindParam(':table_prefix', $module_data, PDO::PARAM_STR);
                $sth->bindParam(':version', $version, PDO::PARAM_STR);
                $sth->bindParam(':author', $author, PDO::PARAM_STR);
                $sth->bindParam(':note', $note, PDO::PARAM_STR);
                if ($sth->execute()) {
                    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('vmodule_add') . ' ' . $module_data, '', $admin_info['userid']);
                }
                nv_jsonOutput([
                    'status' => 'success',
                    'redirect' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=setup&autosetup=' . $title
                ]);
            } catch (Throwable $e) {
                trigger_error($e->getMessage());
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => $e->getMessage(),
                ]);
            }
        }
    }

    nv_jsonOutput([
        'status' => 'error',
        'input' => 'title',
        'mess' => $nv_Lang->getModule('vmodule_exit'),
    ]);
}

$page_title = $nv_Lang->getModule('vmodule_add');

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('vmodule.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);

$sql = 'SELECT title FROM ' . $db_config['prefix'] . '_setup_extensions WHERE is_virtual=1 AND type=\'module\' ORDER BY addtime ASC';
$result = $db->query($sql);

$modfile = [];
while ([$modfile_i] = $result->fetch(3)) {
    if (in_array($modfile_i, $modules_site, true)) {
        if (!empty($array_site_cat_module) and !in_array($modfile_i, $array_site_cat_module, true)) {
            continue;
        }
        $modfile[] = $modfile_i;
    }
}
$tpl->assign('MODFILE', $modfile);

$contents = $tpl->fetch('vmodule.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
