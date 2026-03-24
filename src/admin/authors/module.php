<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_AUTHORS')) {
    exit('Stop!!!');
}

$page_title = $nv_Lang->getModule('module_admin');

if (defined('NV_IS_AJAX')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }
    // Thay đổi thứ tự
    if ($nv_Request->isset_request('changeweight', 'post')) {
        $respon = [
            'error' => 0,
            'message' => '',
        ];

        $mid = $nv_Request->get_int('changeweight', 'post', 0);
        $new_vid = $nv_Request->get_int('new_vid', 'post', 0);

        $stmt = $db->prepare('SELECT mid FROM ' . NV_AUTHORS_GLOBALTABLE . '_module WHERE mid != :mid ORDER BY weight ASC');
        $stmt->bindValue(':mid', $mid, PDO::PARAM_INT);
        $stmt->execute();
        $weight = 0;
        $stmt_update = $db->prepare('UPDATE ' . NV_AUTHORS_GLOBALTABLE . '_module SET weight = :weight WHERE mid = :mid');
        while ($row = $stmt->fetch()) {
            ++$weight;
            if ($weight == $new_vid) {
                ++$weight;
            }
            $stmt_update->bindValue(':weight', $weight, PDO::PARAM_INT);
            $stmt_update->bindValue(':mid', $row['mid'], PDO::PARAM_INT);
            $stmt_update->execute();
        }
        $stmt->closeCursor();
        $stmt_update->bindValue(':weight', $new_vid, PDO::PARAM_INT);
        $stmt_update->bindValue(':mid', $mid, PDO::PARAM_INT);
        $stmt_update->execute();
        $nv_Cache->delMod('authors');

        nv_jsonOutput($respon);
    }

    // Thay đổi quyền sử dụng
    if ($nv_Request->isset_request('changact', 'post')) {
        $mid = $nv_Request->get_int('mid', 'post', 0);
        $act = $nv_Request->get_int('changact', 'post', 1);
        $stmt = $db->prepare('SELECT * FROM ' . NV_AUTHORS_GLOBALTABLE . '_module WHERE mid = :mid');
        $stmt->bindValue(':mid', $mid, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        $stmt->closeCursor();

        $respon = [
            'error' => 1,
            'message' => 'Not allow!!!',
        ];

        if (!empty($row)) {
            $save = true;
            if ($act == 3 and ($row['module'] == 'database' or $row['module'] == 'settings' or $row['module'] == 'site')) {
                $save = false;
            } elseif ($act == 1 and ($row['module'] == 'authors' or $row['module'] == 'siteinfo')) {
                $save = false;
            }

            if ($save) {
                $act_val = ($row['act_' . $act]) ? 0 : 1;
                $checksum = md5($row['module'] . '#' . $row['act_1'] . '#' . $row['act_2'] . '#' . $row['act_3'] . '#' . $global_config['sitekey']);
                $stmt_update = $db->prepare('UPDATE ' . NV_AUTHORS_GLOBALTABLE . '_module SET act_' . $act . ' = :act_val, checksum = :checksum WHERE mid = :mid');
                $stmt_update->bindValue(':act_val', $act_val, PDO::PARAM_INT);
                $stmt_update->bindValue(':checksum', $checksum, PDO::PARAM_STR);
                $stmt_update->bindValue(':mid', $mid, PDO::PARAM_INT);
                $stmt_update->execute();
                $nv_Cache->delMod('authors');
                $respon['error'] = 0;
            }
        }

        nv_jsonOutput($respon);
    }
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('module.tpl'));
$tpl->assign('LANG', $nv_Lang);

$rows = $db->query('SELECT * FROM ' . NV_AUTHORS_GLOBALTABLE . '_module ORDER BY weight ASC')->fetchAll();
$numrows = count($rows);

$tpl->assign('ARRAY', $rows);
$tpl->assign('NUMROWS', $numrows);
$tpl->assign('CHECKSS', csrf_create($csrf_key));

$contents = $tpl->fetch('module.tpl');

if (!defined('NV_IS_AJAX')) {
    $contents = nv_admin_theme($contents);
}

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
