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

if (!nv_function_exists('nv_block_voting')) {
    /**
     * @param string $module
     * @param array  $data_block
     * @return string
     */
    function nv_block_voting_config($module, $data_block)
    {
        global $nv_Cache, $site_mods, $nv_Lang;

        [$block_theme, $dir] = get_block_tpl_dir('global.voting.config.tpl', $module, true);
        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir($dir);
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('TEMPLATE', $block_theme);
        $tpl->assign('CONFIG', $data_block);

        $sql = 'SELECT vid, question,acceptcm, groups_view, publ_time, exp_time
        FROM ' . NV_PREFIXLANG . '_' . $site_mods['voting']['module_data'] . ' WHERE act=1';
        $tpl->assign('ITEMS', $nv_Cache->db($sql, 'vid', $module));

        return $tpl->fetch('global.voting.config.tpl');
    }

    /**
     * @param string $module
     * @return array
     */
    function nv_block_voting_config_submit($module)
    {
        global $nv_Request;

        $return = [];
        $return['error'] = [];
        $return['config'] = [];
        $return['config']['vid'] = $nv_Request->get_int('config_vid', 'post', 0);
        $return['config']['show_type'] = $nv_Request->get_title('config_show_type', 'post', '');

        return $return;
    }

    /**
     * @param array $block_config
     * @return string|void
     */
    function nv_block_voting($block_config)
    {
        global $nv_Cache, $db, $site_mods, $global_config, $nv_Lang;

        $module = $block_config['module'];

        // Lấy hết các khảo sát đang hoạt động
        $sql = 'SELECT vid, question, link, acceptcm, active_captcha, groups_view, publ_time, exp_time
        FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . ' WHERE act=1';
        $list = $nv_Cache->db($sql, 'vid', $module);
        if (empty($list)) {
            return '';
        }

        $allowed = $is_update = [];
        foreach ($list as $row) {
            if ($row['exp_time'] > 0 and $row['exp_time'] < NV_CURRENTTIME) {
                $is_update[] = $row['vid'];
            } elseif ($row['publ_time'] <= NV_CURRENTTIME and nv_user_in_groups($row['groups_view'])) {
                $allowed[$row['vid']] = $row;
            }
        }

        // Cho hết hạn các khảo sát đã qua thời gian
        if (!empty($is_update)) {
            $is_update = implode(',', $is_update);

            $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . ' SET act=0 WHERE vid IN (' . $is_update . ')';
            $db->query($sql);

            $nv_Cache->delMod($module);
        }
        if (!$allowed) {
            return '';
        }

        if ($block_config['show_type'] == 'random') {
            $current_voting = $allowed[array_rand($allowed)];
        } else {
            $current_voting = $allowed[$block_config['vid']] ?? [];
        }
        if (empty($current_voting)) {
            return '';
        }

        $sql = 'SELECT id, vid, title, url FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_rows WHERE vid = ' . $current_voting['vid'] . ' ORDER BY id ASC';
        $list = $nv_Cache->db($sql, '', $module);
        if (empty($list)) {
            return '';
        }

        $nv_Lang->loadModule($site_mods[$module]['module_file'], false, true);
        addition_module_assets($module, 'js');

        [$block_theme, $dir] = get_block_tpl_dir('global.voting.tpl', $module, true);
        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir($dir);
        $tpl->assign('TEMPLATE', $block_theme);
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('MODULE', $module);
        $tpl->assign('UNIQUEID', $block_config['bid']);
        $tpl->assign('CAPTCHA_ATTRS', nv_captcha_form_attrs('fcode', nv_module_captcha($module)));

        $voting_array = [
            'checkss' => md5($current_voting['vid'] . NV_CHECK_SESSION),
            'accept' => (int) $current_voting['acceptcm'],
            'active_captcha' => (int) $current_voting['active_captcha'],
            'errsm' => (int) $current_voting['acceptcm'] > 1 ? $nv_Lang->getModule('voting_warning_all', (int) $current_voting['acceptcm']) : $nv_Lang->getModule('voting_warning_accept1'),
            'vid' => $current_voting['vid'],
            'question' => (empty($current_voting['link'])) ? $current_voting['question'] : '<a target="_blank" href="' . $current_voting['link'] . '">' . $current_voting['question'] . '</a>',
            'items' => $list,
        ];
        $tpl->assign('VOTING', $voting_array);

        $content = $tpl->fetch('global.voting.tpl');
        $nv_Lang->changeLang();
        return $content;
    }
}

if (defined('NV_SYSTEM')) {
    global $site_mods;

    $module = $block_config['module'];
    if (isset($site_mods[$module])) {
        $content = nv_block_voting($block_config);
    }
}
