<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 16-12-2012 15:48
 */

if (!defined('NV_IS_FILE_AUTHORS')) {
    die('Stop!!!');
}

$page_title = $nv_Lang->getModule('module_admin');

if (defined('NV_IS_AJAX')) {
    if ($nv_Request->isset_request('changeweight', 'post')) {
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
    } elseif ($nv_Request->isset_request('changact', 'post')) {
        $mid = $nv_Request->get_int('mid', 'post', 0);
        $act = $nv_Request->get_int('changact', 'post', 1);
        $query = 'SELECT * FROM ' . NV_AUTHORS_GLOBALTABLE . '_module WHERE mid=' . $mid;
        $row = $db->query($query)->fetch();
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
                $db->query("UPDATE " . NV_AUTHORS_GLOBALTABLE . "_module SET act_" . $act . " = '" . $act_val . "', checksum = '" . $checksum . "' WHERE mid = " . $mid);
            }
        }
        nv_htmlOutput('OK');
    }

    nv_htmlOutput('');
}

$tpl = new \NukeViet\Template\Smarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);

$rows = $db->query('SELECT * FROM ' . NV_AUTHORS_GLOBALTABLE . '_module ORDER BY weight ASC')->fetchAll();
$array = [];
foreach ($rows as $row) {
    if ($row['module'] == 'siteinfo') {
        continue;
    }
    $row['custom_title'] = $nv_Lang->existsGlobal($row['lang_key']) ? $nv_Lang->get($row['lang_key']) : $row['module'];
    $array[] = $row;
}

$tpl->assign('NUM_MODULES', sizeof($rows));
$tpl->assign('ARRAY', $array);

$contents = $tpl->fetch('module.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
