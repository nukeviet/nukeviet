<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_SITEINFO')) {
    exit('Stop!!!');
}

$page_title = $nv_Lang->getModule('variables_php');

require_once NV_ROOTDIR . '/includes/core/phpinfo.php';

$array = phpinfo_array(32, 1);

if (!empty($array['PHP Variables'])) {
    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('variables_php.tpl'));
    $tpl->assign('LANG', $nv_Lang);

    $ignore_keys = [];
    $ignore_keys[] = '_SERVER["HTTP_COOKIE"]';
    $ignore_keys[] = '_SERVER["PHP_AUTH_USER"]';
    $ignore_keys[] = '_SERVER["REMOTE_USER"]';
    $ignore_keys[] = '_SERVER["AUTH_USER"]';
    $ignore_keys[] = '_SERVER["HTTP_AUTHORIZATION"]';
    $ignore_keys[] = '_SERVER["Authorization"]';
    $ignore_keys[] = '_SERVER["PHP_AUTH_PW"]';
    $ignore_keys[] = '_SERVER["REMOTE_PASSWORD"]';
    $ignore_keys[] = '_SERVER["AUTH_PASSWORD"]';

    $tpl->assign('IGNORE_KEYS', $ignore_keys);
    $tpl->assign('ARRAY', $array['PHP Variables']);

    $contents = $tpl->fetch('variables_php.tpl');
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
