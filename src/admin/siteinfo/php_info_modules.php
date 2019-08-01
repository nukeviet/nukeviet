<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-1-2010 22:0
 */

if (! defined('NV_IS_FILE_SITEINFO')) {
    die('Stop!!!');
}

$page_title = $nv_Lang->getModule('extensions_php');

require_once NV_ROOTDIR . '/includes/core/phpinfo.php';

$array = phpinfo_array(8, 1);
unset($array['Apache Environment']['HTTP_COOKIE']);
unset($array['HTTP Headers Information']['Cookie']);
unset($array['HTTP Headers Information']['Set-Cookie']);

$tpl = new \NukeViet\Template\Smarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('DATA', $array);

$contents = $tpl->fetch('extensions_php.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
