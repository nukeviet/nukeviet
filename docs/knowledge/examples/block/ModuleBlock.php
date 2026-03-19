<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_SYSTEM')) {
    exit('Stop!!!');
}

if (!nv_function_exists('nv_block_about_example')) {
    /**
     * nv_block_about_example()
     *
     * @param array $block_config
     * @return string
     */
    function nv_block_about_example($block_config)
    {
        global $global_config, $db_slave, $module_name;

        // Logic của bạn
        $title = "Tiêu đề mẫu";
        $link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA;

        // XPATH tìm block.about.tpl trong hệ thống theme 
        $block_theme = get_tpl_dir([$global_config['module_theme'], $global_config['site_theme']], 'default', '/modules/' . $block_config['module'] . '/block.about.tpl');
        
        $xtpl = new XTemplate('block.about.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $block_config['module']);
        $xtpl->assign('TITLE', $title);
        $xtpl->assign('LINK', $link);

        $xtpl->parse('main');
        return $xtpl->text('main');
    }
}

// Bắt buộc bước gọi hàm cuối cùng này
if (defined('NV_SYSTEM')) {
    $content = nv_block_about_example($block_config);
}
