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

$checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid']);
if (defined('NV_IS_AJAX') and $checkss == $nv_Request->get_string('checkss', 'post')) {
    // Thay đổi thứ tự
    if ($nv_Request->isset_request('changeweight', 'post')) {
        $respon = [
            'error' => 0,
            'message' => '',
        ];

        $mid = $nv_Request->get_int('changeweight', 'post', 0);
        $new_vid = $nv_Request->get_int('new_vid', 'post', 0);

        $query = 'SELECT mid FROM ' . NV_AUTHORS_GLOBALTABLE . '_module WHERE mid!=' . $mid . ' ORDER BY weight ASC';
        $result = $db->query($query);
        $weight = 0;
        while ($row = $result->fetch()) {
            ++$weight;
            if ($weight == $new_vid) {
                ++$weight;
            }
            $db->query('UPDATE ' . NV_AUTHORS_GLOBALTABLE . '_module SET weight=' . $weight . ' WHERE mid=' . $row['mid']);
        }
        $db->query('UPDATE ' . NV_AUTHORS_GLOBALTABLE . '_module SET weight=' . $new_vid . ' WHERE mid=' . $mid);
        $nv_Cache->delMod('authors');

        nv_jsonOutput($respon);
    }

    // Thay đổi quyền sử dụng
    if ($nv_Request->isset_request('changact', 'post')) {
        $mid = $nv_Request->get_int('mid', 'post', 0);
        $act = $nv_Request->get_int('changact', 'post', 1);
        $query = 'SELECT * FROM ' . NV_AUTHORS_GLOBALTABLE . '_module WHERE mid=' . $mid;
        $row = $db->query($query)->fetch();

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
                $db->query('UPDATE ' . NV_AUTHORS_GLOBALTABLE . '_module SET act_' . $act . " = '" . $act_val . "', checksum = '" . $checksum . "' WHERE mid = " . $mid);
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
$tpl->assign('CHECKSS', $checkss);

$contents = $tpl->fetch('module.tpl');

if (!defined('NV_IS_AJAX')) {
    $contents = nv_admin_theme($contents);
}

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
