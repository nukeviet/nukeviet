<?php

/**
 * @Project NukeViet
 * @Author VN
 * @Copyright (C) 2025 VN. All rights reserved
 * @License GNU/GPL version 2 or any later version
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

if (!nv_function_exists('nv_block_theme_example')) {
    /**
     * nv_block_theme_example()
     *
     * @param array $block_config
     * @return string
     */
    function nv_block_theme_example($block_config)
    {
        global $global_config, $page_title;

        // Thêm trường title vào config truyền ra ngoài nếu cần
        $block_config['title'] = "Custom Title";

        // Khởi tạo Smarty template engine cho khối Theme
        $stpl = new \NukeViet\Template\NVSmarty();
        $stpl->setTemplateDir($block_config['real_path'] . '/smarty');
        
        // Gán mảng dữ liệu vào biến SMARTY
        $stpl->assign('DATA', $block_config);

        return $stpl->fetch('global.theme_example.tpl');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_block_theme_example($block_config);
}
