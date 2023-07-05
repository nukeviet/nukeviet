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

if (!nv_function_exists('nv_block_voting_select')) {
    /**
     * nv_block_voting_select_config()
     *
     * @param string $module
     * @param array  $data_block
     * @return string
     */
    function nv_block_voting_select_config($module, $data_block)
    {
        global $nv_Cache, $site_mods, $nv_Lang;

        $html = '';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $nv_Lang->getModule('vid') . ':</label>';
        $html .= '<div class="col-sm-9"><select name="vid" class="form-control">';
        $sql = 'SELECT vid, question,acceptcm, groups_view, publ_time, exp_time FROM ' . NV_PREFIXLANG . '_' . $site_mods['voting']['module_data'] . ' WHERE act=1';
        $list = $nv_Cache->db($sql, 'vid', $module);
        foreach ($list as $l) {
            $sel = ($data_block['vid'] == $l['vid']) ? ' selected' : '';
            $html .= '<option value="' . $l['vid'] . '" ' . $sel . '>' . $l['question'] . '</option>';
        }
        $html .= '</select></div>';
        $html .= '</div>';

        return $html;
    }

    /**
     * nv_block_voting_select_config_submit()
     *
     * @param string $module
     * @return array
     */
    function nv_block_voting_select_config_submit($module)
    {
        global $nv_Request;

        $return = [];
        $return['error'] = [];
        $return['config'] = [];
        $return['config']['vid'] = $nv_Request->get_int('vid', 'post', 0);

        return $return;
    }

    /**
     * nv_block_voting_select()
     *
     * @param array $block_config
     * @param array $global_array_cat
     * @return string|void
     */
    function nv_block_voting_select($block_config, $global_array_cat)
    {
        global $nv_Cache, $global_config, $site_mods, $my_footer, $nv_Lang, $module_config;

        $module = $block_config['module'];

        $sql = 'SELECT vid, question, link, acceptcm, active_captcha, groups_view, publ_time, exp_time FROM ' . NV_PREFIXLANG . '_' . $site_mods['voting']['module_data'] . ' WHERE act=1';
        $list = $nv_Cache->db($sql, 'vid', 'voting');
        if (isset($list[$block_config['vid']])) {
            $current_voting = $list[$block_config['vid']];
            if ($current_voting['publ_time'] <= NV_CURRENTTIME and nv_user_in_groups($current_voting['groups_view'])) {
                $sql = 'SELECT id, vid, title, url FROM ' . NV_PREFIXLANG . '_' . $site_mods['voting']['module_data'] . '_rows WHERE vid = ' . $block_config['vid'] . ' ORDER BY id ASC';
                $list = $nv_Cache->db($sql, '', 'voting');

                if (empty($list)) {
                    return '';
                }

                $nv_Lang->loadModule($site_mods['voting']['module_file'], false, true);

                $block_theme = get_tpl_dir([$global_config['module_theme'], $global_config['site_theme']], 'default', '/modules/' . $site_mods['voting']['module_file'] . '/global.voting.tpl');

                $action = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=voting';

                $voting_array = [
                    'checkss' => md5($current_voting['vid'] . NV_CHECK_SESSION),
                    'accept' => $current_voting['acceptcm'],
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
                    $captcha_type = (empty($module_config[$module]['captcha_type']) or in_array($module_config[$module]['captcha_type'], ['captcha', 'recaptcha'], true)) ? $module_config[$module]['captcha_type'] : 'captcha';
                    if ($captcha_type == 'recaptcha' and (empty($global_config['recaptcha_sitekey']) or empty($global_config['recaptcha_secretkey']))) {
                        $captcha_type = 'captcha';
                    }

                    if ($captcha_type == 'recaptcha' and $global_config['recaptcha_ver'] == 3) {
                        $xtpl->parse('main.recaptcha3');
                    } elseif (($captcha_type == 'recaptcha' and $global_config['recaptcha_ver'] == 2) or $captcha_type == 'captcha') {
                        if ($captcha_type == 'recaptcha' and $global_config['recaptcha_ver'] == 2) {
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
    }
}

if (defined('NV_SYSTEM')) {
    global $site_mods, $module_name, $global_array_cat;

    $module = $block_config['module'];
    if (isset($site_mods[$module])) {
        $content = nv_block_voting_select($block_config, $global_array_cat);
    }
}
