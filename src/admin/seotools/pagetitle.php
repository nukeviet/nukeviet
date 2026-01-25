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

$page_title = $nv_Lang->getModule('pagetitle');

$array_config = [];
$checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid']);
if ($checkss == $nv_Request->get_string('checkss', 'post')) {
    $pageTitleMode = $nv_Request->get_title('pageTitleMode', 'post', '', 1);
    if (isset($global_config['pageTitleMode'])) {
        $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'site' AND config_name = 'pageTitleMode'");
    } else {
        $sth = $db->prepare('INSERT INTO ' . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'pageTitleMode', :config_value)");
    }
    $sth->bindParam(':config_value', $pageTitleMode, PDO::PARAM_STR, 255);
    $sth->execute();

    $nv_Cache->delAll(false);
    nv_jsonOutput([
        'status' => 'success',
        'mess' => $nv_Lang->getGlobal('save_success'),
        'refresh' => 1
    ]);
}

if (!isset($global_config['pageTitleMode']) or empty($global_config['pageTitleMode'])) {
    $global_config['pageTitleMode'] = 'pagetitle - sitename';
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('pagetitle.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);

$tpl->assign('GCONFIG', $global_config);
$tpl->assign('CHECKSS', $checkss);

$contents = $tpl->fetch('pagetitle.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
