<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

// Guard: NV_MAINFILE (không phải NV_IS_BLOCK_THEME — cái đó dành cho block của theme)
if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

// Dùng nv_function_exists() để tránh khai báo trùng khi block được load nhiều lần
if (!nv_function_exists('nv_block_config_tenblock')) {
    /**
     * Form cấu hình block (hiển thị trong trang quản trị block)
     */
    function nv_block_config_tenblock($module, $data_block)
    {
        global $nv_Lang;

        $html  = '<div class="form-group">';
        $html .= '<label>' . $nv_Lang->getModule('numrow') . '</label>';
        $html .= '<input type="text" name="config_numrow" value="' . $data_block['numrow'] . '">';
        $html .= '</div>';
        return $html;
    }

    /**
     * Xử lý submit form cấu hình block
     */
    function nv_block_config_tenblock_submit($module)
    {
        global $nv_Request;
        return [
            'error'  => [],
            'config' => [
                'numrow' => $nv_Request->get_int('config_numrow', 'post', 5)
            ]
        ];
    }

    /**
     * Render nội dung block
     */
    function nv_tenblock($block_config)
    {
        global $nv_Cache, $db, $global_config;
        $module = $block_config['module'];

        // Dùng Query Builder + cache cho SELECT. Xem chi tiết tại [tài liệu Cache](../nukeviet-cache/SKILL.md)
        $db->sqlreset()
            ->select('id, title, alias')
            ->from(NV_PREFIXLANG . '_' . $block_config['module_data'])
            ->where('status = 1')
            ->order('weight ASC')
            ->limit($block_config['numrow']);
        $list = $nv_Cache->db($db->sql(), 'id', $module);

        if (empty($list)) {
            return '';
        }

        // Fallback theme 3 cấp: module_theme → site_theme → default
        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module . '/block.tenblock.tpl')) {
            $block_theme = $global_config['module_theme'];
        } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/' . $module . '/block.tenblock.tpl')) {
            $block_theme = $global_config['site_theme'];
        } else {
            $block_theme = 'default';
        }

        $xtpl = new XTemplate('block.tenblock.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $module);
        foreach ($list as $row) {
            $xtpl->assign('ROW', $row);
            $xtpl->parse('main.loop');
        }
        $xtpl->parse('main');
        return $xtpl->text('main');
    }
}

// Entry point khi block được gọi từ hệ thống
if (defined('NV_SYSTEM')) {
    $content = nv_tenblock($block_config);
}
