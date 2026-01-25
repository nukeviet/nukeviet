<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_SEOTOOLS')) {
    exit('Stop!!!');
}

$page_title = $nv_Lang->getModule('rpc_setting');

if ($nv_Request->isset_request('submitprcservice', 'post')) {
    if ($nv_Request->get_title('checkss', 'post', '') !== NV_CHECK_SESSION) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => 'Error session!'
        ]);
    }

    $prcservice = $nv_Request->get_array('prcservice', 'post');
    $prcservice = implode(',', $prcservice);
    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = '" . NV_LANG_DATA . "' AND module = :module_name AND config_name = 'prcservice'");
    $sth->bindParam(':module_name', $module_name, PDO::PARAM_STR);
    $sth->bindParam(':config_value', $prcservice, PDO::PARAM_STR);
    $sth->execute();

    $nv_Cache->delMod('settings');
    nv_jsonOutput([
        'status' => 'success',
        'mess' => $nv_Lang->getGlobal('save_success'),
        'refresh' => 1
    ]);
}
$prcservice = (isset($module_config[$module_name]['prcservice'])) ? $module_config[$module_name]['prcservice'] : '';
$prcservice = (!empty($prcservice)) ? explode(',', $prcservice) : [];

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('rpc_setting.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);

require NV_ROOTDIR . '/' . NV_DATADIR . '/rpc_services.php';

$tpl->assign('SERVICES', $services);
$tpl->assign('IMGPATH', NV_STATIC_URL . 'themes/' . $global_config['module_theme'] . '/images/' . $module_file);
$tpl->assign('NO_CONFIG', (!isset($module_config[$module_name]['prcservice'])));
$tpl->assign('PRCSERVICE', $prcservice);

$contents = $tpl->fetch('rpc_setting.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
