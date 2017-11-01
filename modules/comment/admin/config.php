<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Mon, 27 Jan 2014 00:08:04 GMT
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$mod_name = $nv_Request->get_title('mod_name', 'post,get', '');

$captcha_array = array(
    0 => $lang_module['captcha_0'],
    1 => $lang_module['captcha_1'],
    2 => $lang_module['captcha_2'],
    3 => $lang_module['captcha_3']
);

$groups_list = nv_groups_list();

if ($nv_Request->isset_request('submit', 'post') and isset($site_mod_comm[$mod_name])) {
    $array_config = array();
    $array_config['emailcomm'] = $nv_Request->get_int('emailcomm', 'post', 0);
    $array_config['auto_postcomm'] = $nv_Request->get_int('auto_postcomm', 'post', 0);
    $array_config['activecomm'] = $nv_Request->get_int('activecomm', 'post', 0);
    $array_config['sortcomm'] = $nv_Request->get_int('sortcomm', 'post', 0);
    $array_config['captcha'] = $nv_Request->get_int('captcha', 'post', 0);
    $array_config['perpagecomm'] = $nv_Request->get_int('perpagecomm', 'post', 0);
    $array_config['timeoutcomm'] = $nv_Request->get_int('timeoutcomm', 'post', 0);
    $array_config['allowattachcomm'] = ($nv_Request->get_int('allowattachcomm', 'post', 0) == 1 ? 1 : 0);
    $array_config['alloweditorcomm'] = ($nv_Request->get_int('alloweditorcomm', 'post', 0) == 1 ? 1 : 0);

    if ($array_config['perpagecomm'] < 1 or $array_config['perpagecomm'] > 1000) {
        $array_config['perpagecomm'] = 5;
    }

    if ($array_config['timeoutcomm'] < 0) {
        $array_config['timeoutcomm'] = 360;
    }

    $_groups_com = $nv_Request->get_array('allowed_comm', 'post', array());
    if (in_array(-1, $_groups_com)) {
        $array_config['allowed_comm'] = '-1';
    } else {
        $array_config['allowed_comm'] = ! empty($_groups_com) ? implode(',', nv_groups_post(array_intersect($_groups_com, array_keys($groups_list)))) : '';
    }

    $_groups_com = $nv_Request->get_array('view_comm', 'post', array());
    $array_config['view_comm'] = ! empty($_groups_com) ? implode(',', nv_groups_post(array_intersect($_groups_com, array_keys($groups_list)))) : '';

    $_groups_com = $nv_Request->get_array('setcomm', 'post', array());
    $array_config['setcomm'] = ! empty($_groups_com) ? implode(',', nv_groups_post(array_intersect($_groups_com, array_keys($groups_list)))) : '';

    $admins_mod_name = explode(',', $site_mod_comm[$mod_name]['admins']);
    $admins_module_name = explode(',', $site_mods[$module_name]['admins']);
    $admins_module_name = array_unique(array_merge($admins_mod_name, $admins_module_name));

    $adminscomm = $nv_Request->get_typed_array('adminscomm', 'post', 'int');
    $adminscomm = array_intersect($adminscomm, $admins_module_name);
    $array_config['adminscomm'] = implode(',', $adminscomm);

    $sth = $db->prepare("UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = '" . NV_LANG_DATA . "' and module = :module_name and config_name = :config_name");
    $sth->bindParam(':module_name', $mod_name, PDO::PARAM_STR);
    foreach ($array_config as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }
    $nv_Cache->delMod('settings');
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);

if (!empty($mod_name)) {
    $xtpl->assign('MOD_NAME', $mod_name);
    $xtpl->assign('DATA', $module_config[$mod_name]);
    $xtpl->assign('ACTIVECOMM', $module_config[$mod_name]['activecomm'] ? ' checked="checked"' : '');
    $xtpl->assign('EMAILCOMM', $module_config[$mod_name]['emailcomm'] ? ' checked="checked"' : '');
    $xtpl->assign('ALLOWATTACHCOMM', empty($module_config[$mod_name]['allowattachcomm']) ? '' : ' checked="checked"');
    $xtpl->assign('ALLOWEDITORCOMM', empty($module_config[$mod_name]['alloweditorcomm']) ? '' : ' checked="checked"');

    $admins_mod_name = explode(',', $site_mod_comm[$mod_name]['admins']);
    $admins_module_name = explode(',', $site_mods[$module_name]['admins']);
    $admins_module_name = array_unique(array_merge($admins_mod_name, $admins_module_name));
    if (! empty($admins_module_name)) {
        $adminscomm = explode(',', $module_config[$mod_name]['adminscomm']);

        $admins_module_name = array_map('intval', $admins_module_name);
        $_sql = 'SELECT userid, username, first_name, last_name FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid IN (' . implode(',', $admins_module_name) . ')';
        $_query = $db->query($_sql);

        while ($row = $_query->fetch()) {
            if (! empty($row['first_name'])) {
                $row['username'] .= ' (' . $row['first_name'] . ')';
            }
            $xtpl->assign('OPTION', array(
                'key' => $row['userid'],
                'title' => $row['username'],
                'checked' => (in_array($row['userid'], $adminscomm)) ? ' checked="checked"' : ''
            ));
            $xtpl->parse('main.config.adminscomm');
        }
    }

    for ($i = 0; $i <= 2; ++$i) {
        $xtpl->assign('OPTION', array(
            'key' => $i,
            'title' => $lang_module['auto_postcomm_' . $i],
            'selected' => $i == $module_config[$mod_name]['auto_postcomm'] ? ' selected="selected"' : ''
        ));
        $xtpl->parse('main.config.auto_postcomm');
    }

    $array_allowed_comm =explode(',', $module_config[$mod_name]['allowed_comm']);
    $array_view_comm = explode(',', $module_config[$mod_name]['view_comm']);
    $array_setcomm = explode(',', $module_config[$mod_name]['setcomm']);

    $xtpl->assign('OPTION', array(
        'value' => -1,
        'checked' => in_array(-1, $array_allowed_comm) ? ' checked="checked"' : '',
        'title' => $lang_module['allowed_comm_item']
    ));
    $xtpl->parse('main.config.allowed_comm');

    foreach ($groups_list as $_group_id => $_title) {
        $xtpl->assign('OPTION', array(
            'value' => $_group_id,
            'checked' => in_array($_group_id, $array_allowed_comm) ? ' checked="checked"' : '',
            'title' => $_title
        ));
        $xtpl->parse('main.config.allowed_comm');

        $xtpl->assign('OPTION', array(
            'value' => $_group_id,
            'checked' => in_array($_group_id, $array_view_comm) ? ' checked="checked"' : '',
            'title' => $_title
        ));
        $xtpl->parse('main.config.view_comm');

        $xtpl->assign('OPTION', array(
            'value' => $_group_id,
            'checked' => in_array($_group_id, $array_setcomm) ? ' checked="checked"' : '',
            'title' => $_title
        ));
        $xtpl->parse('main.config.setcomm');
    }

    // Order by comm
    for ($i = 0; $i <= 2; ++$i) {
        $xtpl->assign('OPTION', array(
            'key' => $i,
            'title' => $lang_module['sortcomm_' . $i],
            'selected' => $i == $module_config[$mod_name]['sortcomm'] ? ' selected="selected"' : ''
        ));
        $xtpl->parse('main.config.sortcomm');
    }

    // Thao luan mac dinh khi tao bai viet moi
    foreach ($captcha_array as $i => $title_i) {
        $xtpl->assign('OPTION', array(
            'key' => $i,
            'title' => $title_i,
            'selected' => $i == $module_config[$mod_name]['captcha'] ? ' selected="selected"' : ''
        ));
        $xtpl->parse('main.config.captcha');
    }
    $xtpl->parse('main.config');

    $page_title = sprintf($lang_module['config_mod_name'], $site_mod_comm[$mod_name]['custom_title']);
} else {
    $page_title = $lang_module['config'];

    $weight = 0;
    foreach ($site_mod_comm as $mod_name => $row_mod) {
        $admin_title = (! empty($row_mod['admin_title'])) ? $row_mod['admin_title'] : $row_mod['custom_title'];

        $array_allowed_comm = (! empty($module_config[$mod_name]['allowed_comm'])) ? explode(',', $module_config[$mod_name]['allowed_comm']) : array();
        $array_view_comm = (! empty($module_config[$mod_name]['view_comm'])) ? explode(',', $module_config[$mod_name]['view_comm']) : array();

        if (in_array(-1, $array_allowed_comm)) {
            $allowed_comm = $lang_module['allowed_comm_item'];
        } else {
            $allowed_comm = array();
            foreach ($array_allowed_comm as $_group_id) {
                $allowed_comm[] = $groups_list[$_group_id];
            }
            $allowed_comm = implode('<br>', $allowed_comm);
        }

        $view_comm = array();
        foreach ($array_view_comm as $_group_id) {
            $view_comm[] = $groups_list[$_group_id];
        }
        $view_comm = implode('<br>', $view_comm);

        $row = array();
        $row['weight'] = ++$weight;
        $row['mod_name'] = $mod_name;
        $row['admin_title'] = $admin_title;
        $row['allowed_comm'] =$allowed_comm;
        $row['view_comm'] = $view_comm;
        $row['auto_postcomm'] = $lang_module['auto_postcomm_' . $module_config[$mod_name]['auto_postcomm']];
        $row['activecomm'] = $module_config[$mod_name]['activecomm'] ? 'check' : 'circle-o';
        $row['emailcomm'] = $module_config[$mod_name]['emailcomm'] ? 'check' : 'circle-o';
        $xtpl->assign('ROW', $row);
        $xtpl->parse('main.list.loop');
    }

    $xtpl->parse('main.list');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';