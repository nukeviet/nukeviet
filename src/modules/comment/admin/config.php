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

$mod_name = $nv_Request->get_title('mod_name', 'post,get', '');

$groups_list = nv_groups_list();

if ($nv_Request->isset_request('save', 'post') and isset($site_mod_comm[$mod_name])) {
    $array_config = [];
    $array_config['emailcomm'] = $nv_Request->get_int('emailcomm', 'post', 0);
    $array_config['auto_postcomm'] = $nv_Request->get_int('auto_postcomm', 'post', 0);
    $array_config['activecomm'] = $nv_Request->get_int('activecomm', 'post', 0);
    $array_config['sortcomm'] = $nv_Request->get_int('sortcomm', 'post', 0);
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

    $_groups_com = $nv_Request->get_array('allowed_comm', 'post', []);
    if (in_array('-1', $_groups_com, true)) {
        $array_config['allowed_comm'] = '-1';
    } else {
        $array_config['allowed_comm'] = !empty($_groups_com) ? implode(',', nv_groups_post(array_intersect($_groups_com, array_keys($groups_list)))) : '';
    }

    $_groups_com = $nv_Request->get_array('view_comm', 'post', []);
    $array_config['view_comm'] = !empty($_groups_com) ? implode(',', nv_groups_post(array_intersect($_groups_com, array_keys($groups_list)))) : '';

    $_groups_com = $nv_Request->get_array('setcomm', 'post', []);
    $array_config['setcomm'] = !empty($_groups_com) ? implode(',', nv_groups_post(array_intersect($_groups_com, array_keys($groups_list)))) : '';

    $admins_mod_name = explode(',', $site_mod_comm[$mod_name]['admins']);
    $admins_module_name = explode(',', $site_mods[$module_name]['admins']);
    $admins_module_name = array_unique(array_merge($admins_mod_name, $admins_module_name));

    $adminscomm = $nv_Request->get_typed_array('adminscomm', 'post', 'int');
    $adminscomm = array_intersect($adminscomm, $admins_module_name);
    $array_config['adminscomm'] = implode(',', $adminscomm);

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = '" . NV_LANG_DATA . "' and module = :module_name and config_name = :config_name");
    $sth->bindParam(':module_name', $mod_name, PDO::PARAM_STR);
    foreach ($array_config as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }
    $nv_Cache->delMod('settings');
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
}

if (!empty($mod_name)) {
    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('config-edit.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('OP', $op);
    $tpl->assign('MOD_NAME', $mod_name);
    $tpl->assign('DATA', $module_config[$mod_name]);
    $tpl->assign('GROUPS', $groups_list);
    // $tpl->assign('ACTIVECOMM', $module_config[$mod_name]['activecomm'] ? ' checked="checked"' : '');
    // $tpl->assign('EMAILCOMM', $module_config[$mod_name]['emailcomm'] ? ' checked="checked"' : '');
    // $tpl->assign('ALLOWATTACHCOMM', empty($module_config[$mod_name]['allowattachcomm']) ? '' : ' checked="checked"');
    // $tpl->assign('ALLOWEDITORCOMM', empty($module_config[$mod_name]['alloweditorcomm']) ? '' : ' checked="checked"');

    $admins_mod_name = explode(',', $site_mod_comm[$mod_name]['admins']);
    $admins_module_name = explode(',', $site_mods[$module_name]['admins']);
    $admins_module_name = array_unique(array_merge($admins_mod_name, $admins_module_name));
    if (!empty($admins_module_name)) {
        // $adminscomm = array_map('intval', explode(',', $module_config[$mod_name]['adminscomm']));

        $admins_module_name = array_map('intval', $admins_module_name);
        $_sql = 'SELECT userid, username, first_name, last_name FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid IN (' . implode(',', $admins_module_name) . ')';
        $_query = $db->query($_sql);
        $_adminscom = $_query->fetchAll();
        $_query->closeCursor();
        $tpl->assign('ADMINSCOM', $_adminscom);
        // while ($row = $_query->fetch()) {
        //     if (!empty($row['first_name'])) {
        //         $row['username'] .= ' (' . $row['first_name'] . ')';
        //     }
        //     $xtpl->assign('OPTION', [
        //         'key' => $row['userid'],
        //         'title' => $row['username'],
        //         'checked' => (in_array((int) $row['userid'], $adminscomm, true)) ? ' checked="checked"' : ''
        //     ]);
        //     $xtpl->parse('main.config.adminscomm');
        // }
    }

    // for ($i = 0; $i <= 2; ++$i) {
    //     $xtpl->assign('OPTION', [
    //         'key' => $i,
    //         'title' => $nv_Lang->getModule('auto_postcomm_' . $i),
    //         'selected' => $i == $module_config[$mod_name]['auto_postcomm'] ? ' selected="selected"' : ''
    //     ]);
    //     $xtpl->parse('main.config.auto_postcomm');
    // }

    // $array_allowed_comm = array_map('intval', explode(',', $module_config[$mod_name]['allowed_comm']));
    // $array_view_comm = array_map('intval', explode(',', $module_config[$mod_name]['view_comm']));
    // $array_setcomm = array_map('intval', explode(',', $module_config[$mod_name]['setcomm']));

    // $xtpl->assign('OPTION', [
    //     'value' => -1,
    //     'checked' => in_array(-1, $array_allowed_comm, true) ? ' checked="checked"' : '',
    //     'title' => $nv_Lang->getModule('allowed_comm_item')
    // ]);
    // $xtpl->parse('main.config.allowed_comm');

    // foreach ($groups_list as $_group_id => $_title) {
        // $xtpl->assign('OPTION', [
        //     'value' => $_group_id,
        //     'checked' => in_array((int) $_group_id, $array_allowed_comm, true) ? ' checked="checked"' : '',
        //     'title' => $_title
        // ]);
        // $xtpl->parse('main.config.allowed_comm');

    //     $xtpl->assign('OPTION', [
    //         'value' => $_group_id,
    //         'checked' => in_array((int) $_group_id, $array_view_comm, true) ? ' checked="checked"' : '',
    //         'title' => $_title
    //     ]);
    //     $xtpl->parse('main.config.view_comm');

    //     $xtpl->assign('OPTION', [
    //         'value' => $_group_id,
    //         'checked' => in_array((int) $_group_id, $array_setcomm, true) ? ' checked="checked"' : '',
    //         'title' => $_title
    //     ]);
    //     $xtpl->parse('main.config.setcomm');
    // }

    // Order by comm
    // for ($i = 0; $i <= 2; ++$i) {
    //     $xtpl->assign('OPTION', [
    //         'key' => $i,
    //         'title' => $nv_Lang->getModule('sortcomm_' . $i),
    //         'selected' => $i == $module_config[$mod_name]['sortcomm'] ? ' selected="selected"' : ''
    //     ]);
    //     $xtpl->parse('main.config.sortcomm');
    // }

    // $xtpl->parse('main.config');

    $page_title = $nv_Lang->getModule('config_mod_name', $site_mod_comm[$mod_name]['custom_title']);

    $tpl->registerPlugin('modifier', 'in_array', 'in_array');
    $tpl->registerPlugin('modifier', 'intval', 'intval');
    $tpl->registerPlugin('modifier', 'array_map', 'array_map');
    $contents = $tpl->fetch('config-edit.tpl');
} else {
    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('config.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('OP', $op);
    $tpl->assign('SITE_MOD_COMM', $site_mod_comm);
    $tpl->assign('MODULE_CONFIG', $module_config);
    $tpl->assign('GROUPS', $groups_list);

    $page_title = $nv_Lang->getModule('config');
    $tpl->registerPlugin('modifier', 'in_array', 'in_array');
    $tpl->registerPlugin('modifier', 'intval', 'intval');
    $tpl->registerPlugin('modifier', 'array_map', 'array_map');
    $contents = $tpl->fetch('config.tpl');
}



// $xtpl->parse('main');
// $contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
