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

        $html = '';
        $html .= '<div class="row mb-3">';
        $html .= '<label class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium">' . $nv_Lang->getModule('show_type') . ':</label>';
        $html .= '<div class="col-sm-5"><select name="config_show_type" class="form-select">';
        $sel_random = ($data_block['show_type'] == 'random') ? ' selected' : '';
        $sel_fixed = ($data_block['show_type'] == 'fixed') ? ' selected' : '';
        $html .= '<option value="random"' . $sel_random . '>' . $nv_Lang->getModule('show_type_random') . '</option>';
        $html .= '<option value="fixed"' . $sel_fixed . '>' . $nv_Lang->getModule('show_type_fixed') . '</option>';
        $html .= '</select></div>';
        $html .= '</div>';
        $html .= '<div class="row mb-3">';
        $html .= '<label class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium">' . $nv_Lang->getModule('vid') . ':</label>';
        $html .= '<div class="col-sm-5"><select name="config_vid" class="form-select">';
        $sql = 'SELECT vid, question,acceptcm, groups_view, publ_time, exp_time FROM ' . NV_PREFIXLANG . '_' . $site_mods['voting']['module_data'] . ' WHERE act=1';
        $list = $nv_Cache->db($sql, 'vid', $module);
        foreach ($list as $l) {
            $sel = ($data_block['vid'] == $l['vid']) ? ' selected' : '';
            $html .= '<option value="' . $l['vid'] . '"' . $sel . '>' . $l['question'] . '</option>';
        }
        $html .= '</select></div>';
        $html .= '<div class="col-sm-12"><small class="text-muted">' . $nv_Lang->getModule('vid_help') . '</small></div>';
        $html .= '</div>';

        return $html;
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
        global $nv_Cache, $global_config, $site_mods, $nv_Lang, $db;

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

        $block_theme = get_tpl_dir([$global_config['module_theme'], $global_config['site_theme']], 'default', '/modules/' . $site_mods['voting']['module_file'] . '/global.voting.tpl');
        $action = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=voting';
        $voting_array = [
            'checkss' => md5($current_voting['vid'] . NV_CHECK_SESSION),
            'accept' => (int) $current_voting['acceptcm'],
            'active_captcha' => (int) $current_voting['active_captcha'],
            'errsm' => $current_voting['acceptcm'] > 1 ? $nv_Lang->getModule('voting_warning_all', $current_voting['acceptcm']) : $nv_Lang->getModule('voting_warning_accept1'),
            'vid' => $current_voting['vid'],
            'question' => (empty($current_voting['link'])) ? $current_voting['question'] : '<a target="_blank" href="' . $current_voting['link'] . '">' . $current_voting['question'] . '</a>',
            'action' => $action,
            'langresult' => $nv_Lang->getModule('voting_result'),
            'langsubmit' => $nv_Lang->getModule('voting_hits')
        ];

        $xtpl = new XTemplate('global.voting.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $site_mods['voting']['module_file']);
        $xtpl->assign('VOTING', $voting_array);
        $xtpl->assign('LANG', \NukeViet\Core\Language::$tmplang_module);
        $xtpl->assign('TEMPLATE', $block_theme);
        $xtpl->assign('CONFIG', $block_config);
        $xtpl->assign('FORM_ACTION', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module);

        foreach ($list as $row) {
            if (!empty($row['url'])) {
                $row['title'] = '<a target="_blank" href="' . $row['url'] . '">' . $row['title'] . '</a>';
            }
            $xtpl->assign('RESULT', $row);
            if ((int) $current_voting['acceptcm'] > 1) {
                $xtpl->parse('main.resultn');
            } else {
                $xtpl->parse('main.result1');
            }
        }

        if ($voting_array['active_captcha']) {
            $module_captcha = nv_module_captcha($module);

            if ($module_captcha == 'recaptcha' and $global_config['recaptcha_ver'] == 3) {
                $xtpl->parse('main.recaptcha3');
            } elseif (($module_captcha == 'recaptcha' and $global_config['recaptcha_ver'] == 2) or $module_captcha == 'captcha') {
                if ($module_captcha == 'recaptcha' and $global_config['recaptcha_ver'] == 2) {
                    $xtpl->assign('RECAPTCHA_ELEMENT', 'recaptcha' . nv_genpass(8));
                    $xtpl->assign('N_CAPTCHA', $nv_Lang->getGlobal('securitycode1'));
                    $xtpl->parse('main.has_captcha.recaptcha');
                } else {
                    $xtpl->assign('N_CAPTCHA', $nv_Lang->getGlobal('securitycode'));
                    $xtpl->parse('main.has_captcha.basic');
                }
                $xtpl->parse('main.has_captcha');
            }
        }

        $xtpl->parse('main');
        $content = $xtpl->text('main');

        $nv_Lang->changeLang();
        return $content;
    }
}

if (defined('NV_SYSTEM')) {
    global $site_mods, $module_name;

    $module = $block_config['module'];
    if (isset($site_mods[$module])) {
        $content = nv_block_voting($block_config);
    }
}
