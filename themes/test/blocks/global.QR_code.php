<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

if (!nv_function_exists('nv_block_qr_code')) {
    /**
     * nv_block_qr_code()
     *
     * @param array $block_config
     * @return string
     */
    function nv_block_qr_code($block_config)
    {
        global $page_title, $global_config, $page_url, $module_name, $home, $op, $lang_global;

        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/blocks/global.QR_code.tpl')) {
            $block_theme = $global_config['module_theme'];
        } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/blocks/global.QR_code.tpl')) {
            $block_theme = $global_config['site_theme'];
        } else {
            $block_theme = 'default';
        }

        $xtpl = new XTemplate('global.QR_code.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/blocks');
        $xtpl->assign('LANG', $lang_global);
        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);

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

        $block_config['selfurl'] = NV_MY_DOMAIN . nv_url_rewrite($current_page_url, true);
        $block_config['title'] = 'QR-Code: ' . str_replace('"', '&quot;', ($page_title ? $page_title : $global_config['site_name']));
        $xtpl->assign('QRCODE', $block_config);

        $xtpl->parse('main');

        return $xtpl->text('main');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_block_qr_code($block_config);
}
