<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-1-2010 21:35
 */

if (!defined('NV_IS_FILE_SETTINGS')) {
    die('Stop!!!');
}

$error = '';

if ($nv_Request->get_int('save', 'post') == '1') {
    $cron_name = $nv_Request->get_title('cron_name', 'post', '', 1);
    $run_file = $nv_Request->get_title('run_file', 'post', '');
    $run_func = $nv_Request->get_title('run_func_iavim', 'post', '');
    $params = $nv_Request->get_title('params_iavim', 'post', '');
    $interval = $nv_Request->get_int('interval_iavim', 'post', 0);
    $del = $nv_Request->get_int('del', 'post', 0);

    $min = $nv_Request->get_int('min', 'post', 0);
    $hour = $nv_Request->get_int('hour', 'post', 0);

    if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string('start_date', 'post'), $m)) {
        $start_time = mktime($hour, $min, 0, $m[2], $m[1], $m[3]);
    } else {
        $start_time = NV_CURRENTTIME;
    }

    if (empty($cron_name)) {
        $error = $nv_Lang->getModule('cron_name_empty');
    } elseif (!empty($run_file) and !nv_is_file(NV_BASE_SITEURL . 'includes/cronjobs/' . $run_file, 'includes/cronjobs')) {
        $error = $nv_Lang->getModule('file_not_exist');
    } elseif (empty($run_func) or !preg_match($global_config['check_cron'], $run_func)) {
        $error = $nv_Lang->getModule('func_name_invalid');
    } else {
        if (!empty($run_file) and preg_match('/^([a-zA-Z0-9\-\_\.]+)\.php$/', $run_file) and file_exists(NV_ROOTDIR . '/includes/cronjobs/' . $run_file)) {
            if (!defined('NV_IS_CRON')) {
                define('NV_IS_CRON', true);
            }
            require_once NV_ROOTDIR . '/includes/cronjobs/' . $run_file;
        }

        if (!nv_function_exists($run_func)) {
            $error = $nv_Lang->getModule('func_name_not_exist');
        } else {
            if (!empty($params)) {
                $params = explode(',', $params);
                $params = array_map('trim', $params);
                $params = implode(',', $params);
            }

            $_sql = 'INSERT INTO ' . NV_CRONJOBS_GLOBALTABLE . '
                (start_time, inter_val, run_file, run_func, params, del, is_sys, act, last_time, last_result, ' . NV_LANG_INTERFACE . '_cron_name) VALUES
                (' . $start_time . ', ' . $interval . ', :run_file, :run_func, :params, ' . $del . ', 0, 1, 0, 0, :cron_name)';
            $data = array();
            $data['run_file'] = $run_file;
            $data['run_func'] = $run_func;
            $data['params'] = $params;
            $data['cron_name'] = $cron_name;
            $id = $db->insert_id($_sql, 'id', $data);

            if ($id) {
                nv_insert_logs(NV_LANG_DATA, $module_name, 'log_cronjob_add', 'id ' . $id, $admin_info['userid']);

                $sql = "SELECT lang FROM " . $db_config['prefix'] . "_setup_language where lang!='" . NV_LANG_INTERFACE . "'";
                $result = $db->query($sql);
                while (list($lang_i) = $result->fetch(3)) {
                    $sth = $db->prepare('UPDATE ' . NV_CRONJOBS_GLOBALTABLE . ' SET ' . $lang_i . '_cron_name= :run_func WHERE id=' . $id);
                    $sth->bindParam(':run_func', $run_func, PDO::PARAM_STR);
                    $sth->execute();
                }

                nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cronjobs');
            }
        }
    }
} else {
    $min = intval(date('i', NV_CURRENTTIME));
    $hour = date('G', NV_CURRENTTIME);
    $start_time = NV_CURRENTTIME;
    $interval = 60;
    $cron_name = $run_file = $run_func = $params = '';
    $del = 0;
}

$tpl = new \NukeViet\Template\Smarty();
$tpl->registerPlugin('modifier', 'date', 'nv_date');
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$tpl->assign('ERROR', $error);
$tpl->assign('FILELIST', nv_scandir(NV_ROOTDIR . '/includes/cronjobs', '/^([a-zA-Z0-9\_\.]+)\.php$/'));
$tpl->assign('DATA', [
    'cron_name' => $cron_name,
    'run_file' => $run_file,
    'run_func' => $run_func,
    'params' => $params,
    'start_time' => $start_time,
    'min' => $min,
    'hour' => $hour,
    'interval' => $interval,
    'del' => $del
]);
$tpl->assign('IS_ADD', true);

$page_title = $nv_Lang->getGlobal('mod_cronjobs') . ' -> ' . $nv_Lang->getModule('nv_admin_add');
$set_active_op = 'cronjobs';

$contents = $tpl->fetch('cronjobs_add.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
