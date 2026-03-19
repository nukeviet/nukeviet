<?php
 
/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

// Hàm config block của Module — nhận $lang_block từ tham số
function nv_block_config_tenblock($module, $data_block, $lang_block)
{
    // $lang_block['numrow'] → "Số dòng hiển thị" (từ JSON i18n)
    $html  = '<div class="form-group">';
    $html .= '<label>' . $lang_block['numrow'] . '</label>';
    $html .= '<input type="text" name="config_numrow" value="' . $data_block['numrow'] . '">';
    $html .= '</div>';
    return $html;
}

// Hàm render block — dùng $lang_global và $lang_module
function nv_tenblock($block_config)
{
    global $lang_global, $lang_module, $nv_Lang;

    // Nếu cần ngôn ngữ của module khác — load thủ công
    // $nv_Lang->loadModule('ten-module-khac');

    $xtpl = new XTemplate('block.tenblock.tpl', /* path ... */ '');
    $xtpl->assign('LANG', $lang_module);    // {LANG.key}
    $xtpl->assign('GLANG', $lang_global);   // {GLANG.save}
    $xtpl->parse('main');
    return $xtpl->text('main');
}

// Hàm render block Theme bằng NVSmarty
function nv_block_theme_example($block_config)
{
    global $lang_global;

    $stpl = new \NukeViet\Template\NVSmarty();
    $stpl->setTemplateDir($block_config['real_path'] . '/smarty');
    $stpl->assign('GLANG', $lang_global);  // {$GLANG.save}
    $stpl->assign('DATA', $block_config);

    return $stpl->fetch('global.theme_example.tpl');
}
