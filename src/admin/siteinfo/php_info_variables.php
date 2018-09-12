<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-1-2010 22:4
 */

if (!defined('NV_IS_FILE_SITEINFO')) {
    die('Stop!!!');
}

require_once NV_ROOTDIR . '/includes/core/phpinfo.php';

$page_title = $nv_Lang->getModule('variables_php');
$array = phpinfo_array(32, 1);

$array_key_no_show = [];
$array_key_no_show[] = '$_SERVER[\'HTTP_COOKIE\']';
$array_key_no_show[] = '$_SERVER[\'PHP_AUTH_USER\']';
$array_key_no_show[] = '$_SERVER[\'REMOTE_USER\']';
$array_key_no_show[] = '$_SERVER[\'AUTH_USER\']';
$array_key_no_show[] = '$_SERVER[\'HTTP_AUTHORIZATION\']';
$array_key_no_show[] = '$_SERVER[\'Authorization\']';
$array_key_no_show[] = '$_SERVER[\'PHP_AUTH_PW\']';
$array_key_no_show[] = '$_SERVER[\'REMOTE_PASSWORD\']';
$array_key_no_show[] = '$_SERVER[\'AUTH_PASSWORD\']';

$tpl = new \NukeViet\Template\Smarty();
$tpl->registerPlugin('modifier', 'substr', 'substr');
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('DATA', $array);
$tpl->assign('NO_SHOW', $array_key_no_show);

$contents = $tpl->fetch('variables_php.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
