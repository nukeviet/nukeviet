<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (!defined('NV_IS_FILE_SEOTOOLS')) {
    die('Stop!!!');
}

$page_title = $nv_Lang->getModule('rpc_setting');

$tpl = new \NukeViet\Template\Smarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);

$server_supported = false;
if (nv_function_exists('curl_init') and nv_function_exists('curl_exec')) {
    $server_supported = true;

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
    $prcservice = (!empty($prcservice)) ? explode(',', $prcservice) : array();

    require NV_ROOTDIR . '/' . NV_DATADIR . '/rpc_services.php';

    $tpl->assign('IMGPATH', NV_BASE_SITEURL . 'themes/' . $global_config['module_theme'] . '/images/' . $module_file);
    $tpl->assign('SERVICES', $services);
    $tpl->assign('PRCSERVICE', $prcservice);
    $tpl->assign('CHECKOPT1', !isset($module_config[$module_name]['prcservice']));
}

$tpl->assign('SERVER_SUPPORTED', $server_supported);

$contents = $tpl->fetch('rpc_setting.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
