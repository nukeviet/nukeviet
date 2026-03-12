<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

// Guard: NV_MAINFILE (không phải NV_IS_BLOCK_THEME — xem bảng phân biệt)
if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

if (!nv_function_exists('nv_tenblock')) {
    /**
     * Form cấu hình block (hiển thị trong trang quản trị block)
     * Tên hàm: nv_{TEN}_config (khác với module block: nv_block_config_{TEN})
     */
    function nv_tenblock_config($module, $data_block)
    {
        global $nv_Lang;

        $html  = '<div class="form-group">';
        $html .= '<label>' . $nv_Lang->getModule('label') . '</label>';
        $html .= '<input type="text" name="config_numrow" value="' . $data_block['numrow'] . '">';
        $html .= '</div>';
        return $html;
    }

    /**
     * Xử lý submit form cấu hình block
     * Tên hàm: nv_{TEN}_submit
     */
    function nv_tenblock_submit()
    {
        global $nv_Request;
        return [
            'error'  => [],
            'config' => ['numrow' => $nv_Request->get_int('config_numrow', 'post', 5)]
        ];
    }

    /**
     * Render nội dung block
     * Tên hàm: nv_{TEN}($block_config)
     * Fallback tpl: module_theme → site_theme → default
     */
    function nv_tenblock($block_config)
    {
        global $global_config;

        // Tpl nằm trong blocks/ (không phải layout/)
        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/blocks/global.tenblock.tpl')) {
            $block_theme = $global_config['module_theme'];
        } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/blocks/global.tenblock.tpl')) {
            $block_theme = $global_config['site_theme'];
        } else {
            $block_theme = 'default';
        }

        $xtpl = new XTemplate('global.tenblock.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/blocks');
        $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
        $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
        $xtpl->parse('main');
        return $xtpl->text('main');
    }
}

// Entry point khi block được gọi từ hệ thống
if (defined('NV_SYSTEM')) {
    $content = nv_tenblock($block_config);
}
