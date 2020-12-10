<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (! defined('NV_IS_FILE_SEOTOOLS')) {
    die('Stop!!!');
}

$page_title = $lang_module['rpc_setting'];
if (nv_function_exists('curl_init') and nv_function_exists('curl_exec')) {
    if ($nv_Request->isset_request('submitprcservice', 'post')) {
        $prcservice = $nv_Request->get_array('prcservice', 'post');
        $prcservice = implode(',', $prcservice);
        $sth = $db->prepare("UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = '" . NV_LANG_DATA . "' AND module = :module_name AND config_name = 'prcservice'");
        $sth->bindParam(':module_name', $module_name, PDO::PARAM_STR);
        $sth->bindParam(':config_value', $prcservice, PDO::PARAM_STR);
        $sth->execute();

        $nv_Cache->delMod('settings');
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
    }
    $prcservice = (isset($module_config[$module_name]['prcservice'])) ? $module_config[$module_name]['prcservice'] : '';
    $prcservice = (! empty($prcservice)) ? explode(',', $prcservice) : array();

    $xtpl = new XTemplate('rpc_setting.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);

    $xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
    $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('OP', $op);

    $xtpl->assign('HOME', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    $xtpl->assign('IMGPATH', NV_BASE_SITEURL . 'themes/' . $global_config['module_theme'] . '/images/' . $module_file);
    $a = 0;

    require NV_ROOTDIR . '/' . NV_DATADIR . '/rpc_services.php';
    foreach ($services as $key => $service) {
        $a++;
        $xtpl->assign('SERVICE', array(
            'id' => $key,
            'title' => $service[1],
            'checked' => (! isset($module_config[$module_name]['prcservice']) or in_array($service[1], $prcservice)) ? 'checked="checked"' : '',
            'icon' => (isset($service[3]) ? $service[3] : '')
        ));
        if (isset($service[3]) and ! empty($service[3])) {
            $xtpl->parse('main.service.icon');
        } else {
            $xtpl->parse('main.service.noticon');
        }
        $xtpl->parse('main.service');
    }

    $xtpl->parse('main');
    $contents = $xtpl->text('main');
} else {
    $contents = 'System not support function php "curl_init" !';
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
