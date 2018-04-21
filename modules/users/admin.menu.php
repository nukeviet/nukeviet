<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 07/30/2013 10:27
 */

if (!defined('NV_ADMIN')) {
    die('Stop!!!');
}

$_mod_table = ($module_data == 'users') ? NV_USERS_GLOBALTABLE : $db_config['prefix'] . '_' . $module_data;
$access_admin = $db->query("SELECT content FROM " . $_mod_table . "_config WHERE config='access_admin'")->fetchColumn();
$access_admin = unserialize($access_admin);

$allow_func = array(
    'main',
    'getuserid'
);
$level = $admin_info['level'];
if (isset($access_admin['access_addus'][$level]) and $access_admin['access_addus'][$level] == 1) {
    $submenu['user_add'] = $lang_module['user_add'];
    $allow_func[] = 'user_add';
}
if (isset($access_admin['access_waiting'][$level]) and $access_admin['access_waiting'][$level] == 1) {
    $submenu['user_waiting'] = $lang_module['member_wating'];
    $allow_func[] = 'user_waiting';
    $allow_func[] = 'setactive';
    $allow_func[] = 'setofficial';
}
if (isset($access_admin['access_editus'][$level]) and $access_admin['access_editus'][$level] == 1) {
    $allow_func[] = 'edit';
    $allow_func[] = 'edit_2step';
    $allow_func[] = 'edit_oauth';
}
if (isset($access_admin['access_delus'][$level]) and $access_admin['access_delus'][$level] == 1) {
    $allow_func[] = 'del';
}
$access['checked_passus'] = (isset($access_admin['access_passus'][$level]) and $access_admin['access_passus'][$level] == 1) ? ' checked="checked" ' : '';
if (isset($access_admin['access_groups'][$level]) and $access_admin['access_groups'][$level] == 1) {
    $submenu['groups'] = $lang_global['mod_groups'];
    $allow_func[] = 'groups';
}

if ($module_data == 'users' and isset($admin_mods['authors'])) {
    $submenu['authors'] = $lang_global['mod_authors'];
    $allow_func[] = 'authors';
}

if (defined('NV_IS_SPADMIN')) {
    if (empty($global_config['idsite'])) {
        $submenu['question'] = $lang_module['question'];
        $submenu['siteterms'] = $lang_module['siteterms'];
        $allow_func[] = 'question';
        $allow_func[] = 'siteterms';
        if (defined('NV_IS_GODADMIN')) {
            $submenu['fields'] = $lang_module['fields'];
            $allow_func[] = 'fields';
        }
    }
    $submenu['config'] = $lang_module['config'];
    $allow_func[] = 'config';
}