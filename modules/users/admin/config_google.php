<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

if ($nv_Request->isset_request('save', 'post')) {
    $array_config['oauth_client_id'] = (string) $nv_Request->get_title('oauth_client_id', 'post', '');
    $array_config['oauth_client_secret'] = $nv_Request->get_title('oauth_client_secret', 'post', '');
    if ($checkss == $nv_Request->get_string('checkss', 'post')) {
        $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'site' AND config_name = :config_name");

        $sth->bindValue(':config_name', 'google_client_id', PDO::PARAM_STR);
        $sth->bindParam(':config_value', $array_config['oauth_client_id'], PDO::PARAM_STR);
        $sth->execute();

        $sth->bindValue(':config_name', 'google_client_secret', PDO::PARAM_STR);
        $sth->bindParam(':config_value', $array_config['oauth_client_secret'], PDO::PARAM_STR);
        $sth->execute();

        nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['config'], $page_title, $admin_info['userid']);
        $nv_Cache->delAll();
    }
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&oauth_config=' . $oauth_config . '&rand=' . nv_genpass());
} else {
    $array_config['oauth_client_id'] = $global_config['google_client_id'];
    $array_config['oauth_client_secret'] = $global_config['google_client_secret'];
    $array_config['checkss'] = $checkss;

    $xtpl = new XTemplate('config_oauth.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;oauth_config=' . $oauth_config);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('DATA', $array_config);

    $xtpl->parse('main');
    $contents = $xtpl->text('main');

    $array_url_instruction['config'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:users:oauth#c%E1%BA%A5u_hinh_v%E1%BB%9Bi_oauth_google';
}
