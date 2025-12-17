<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_VOTING')) {
    exit('Stop!!!');
}

/**
 * Danh sách các thăm dò ý kiến
 *
 * @param array $votings
 * @return string
 */
function voting_main(array $votings): string
{
    global $module_captcha, $nv_Lang, $global_config, $module_name;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('main.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('ITEMS', $votings);
    $tpl->assign('UNIQUEID', 'main');
    $tpl->assign('MODULE', $module_name);
    $tpl->assign('CAPTCHA_ATTRS', nv_captcha_form_attrs('fcode'));

    return $tpl->fetch('main.tpl');
}

/**
 * Chi tiết kết quả một thăm dò ý kiến
 *
 * @param array $voting
 * @return string
 */
function voting_result(array $voting): string
{
    global $nv_Lang;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('detail.tpl'));
    $tpl->registerPlugin('modifier', 'dnumber', 'nv_number_format');
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('VOTING', $voting);

    return $tpl->fetch($voting['ajax'] ? 'voting.result.tpl' : 'detail.tpl');
}
