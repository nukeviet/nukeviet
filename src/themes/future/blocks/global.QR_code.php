<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

global $page_title, $global_config, $page_url, $module_name, $home, $op, $nv_Lang;

if (empty($page_url)) {
    if ($home) {
        $current_page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA;
    } else {
        $current_page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
        if ($op != 'main') {
            $current_page_url .= '&amp;' . NV_OP_VARIABLE . '=' . $op;
        }
    }
} else {
    $current_page_url = $page_url;
}

str_starts_with($current_page_url, NV_MY_DOMAIN) && $current_page_url = substr($current_page_url, strlen(NV_MY_DOMAIN));
$block_config['selfurl'] = urlRewriteWithDomain($current_page_url, NV_MY_DOMAIN);
$block_config['title'] = 'QR-Code: ' . str_replace('"', '&quot;', ($page_title ?: $global_config['site_name']));

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir($block_config['real_path']);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('QRCODE', $block_config);

$content = $tpl->fetch('global.QR_code.tpl');
