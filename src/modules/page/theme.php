<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_PAGE')) {
    exit('Stop!!!');
}

/**
 * Giao diện chi tiết bài giới thiệu
 *
 * @param array  $row
 * @param array  $ab_links
 * @param string $content_comment
 * @return string
 */
function nv_page_main(array $row, array $ab_links, string $content_comment): string
{
    global $module_name, $nv_Lang, $page_config, $global_config;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('detail.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('DATA', $row);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('OTHERS', $ab_links);
    $tpl->assign('CONFIG', $page_config);
    $tpl->assign('GCONFIG', $global_config);
    $tpl->assign('COMMENT', $content_comment);

    return $tpl->fetch('detail.tpl');
}

/**
 * Giao diện danh sách bài giới thiệu
 *
 * @param array  $array_data
 * @param string $generate_page
 * @return string
 */
function nv_page_main_list(array $array_data, string $generate_page) : string
{
    global $module_name, $nv_Lang;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('main.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('DATA', $array_data);
    $tpl->assign('GENERATE_PAGE', $generate_page);

    return $tpl->fetch('main.tpl');
}
