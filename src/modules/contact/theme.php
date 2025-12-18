<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_CONTACT')) {
    exit('Stop!!!');
}

/**
 * contact_main_theme()
 *
 * @param array  $array_content
 * @param bool   $is_specific
 * @param array  $departments
 * @param array  $cats
 * @param string $base_url
 * @param string $checkss
 * @param mixed  $supporters
 * @return string
 */
function contact_main_theme($array_content, $is_specific, $departments, $cats, $supporters, $base_url, $checkss)
{
    global $nv_Lang, $page_title;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('main.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('PAGE_TITLE', $page_title);
    $tpl->assign('DATA', $array_content);
    $tpl->assign('IS_SPECIFIC', $is_specific);

    $deps = [];
    foreach ($departments as $dep) {
        $item = $dep;
        $item['url'] = $is_specific ? $base_url : ($base_url . '&amp;' . NV_OP_VARIABLE . '=' . $dep['alias']);
        $cd = [];
        if (!empty($dep['phone'])) {
            if (is_array($dep['phone'])) {
                $items = [];
                foreach ($dep['phone'] as $num) {
                    $items[] = is_array($num) ? ($num[0] ?? '') : $num;
                }
                $phoneVal = implode(', ', array_filter($items, function ($s) { return $s !== ''; }));
            } else {
                $phoneVal = $dep['phone'];
            }
            $cd[] = ['type' => 'phone', 'value' => $phoneVal];
        }
        if (!empty($dep['fax'])) {
            $faxVal = is_array($dep['fax']) ? implode(', ', $dep['fax']) : $dep['fax'];
            $cd[] = ['type' => 'fax', 'value' => $faxVal];
        }
        if (!empty($dep['email'])) {
            $emailVal = is_array($dep['email']) ? implode(', ', $dep['email']) : $dep['email'];
            $cd[] = ['type' => 'email', 'value' => $emailVal];
        }
        if (!empty($dep['others'])) {
            foreach ($dep['others'] as $key => $value) {
                if (empty($value)) {
                    continue;
                }
                $low = strtolower($key);
                if (in_array($low, ['skype', 'viber', 'whatsapp', 'zalo'], true)) {
                    $vals = array_map(function ($s) { return trim($s); }, explode(',', $value));
                    $cd[] = ['type' => $low, 'value' => $vals];
                } else {
                    $isUrl = nv_is_url($value);
                    $cd[] = ['type' => ucfirst($key), 'value' => ['is_url' => $isUrl, 'content' => $value]];
                }
            }
        }
        $item['cd'] = $cd;
        $deps[] = $item;
    }
    $tpl->assign('DEPARTMENTS', $deps);

    $sups = [];
    if (!is_array($supporters)) {
        $supporters = [];
    }
    foreach ($supporters as $supporter) {
        $item = $supporter;
        $cd = [];
        if (!empty($supporter['phone'])) {
            if (is_array($supporter['phone'])) {
                $items = [];
                foreach ($supporter['phone'] as $num) {
                    $items[] = is_array($num) ? ($num[0] ?? '') : $num;
                }
                $phoneVal = implode(', ', array_filter($items, function ($s) { return $s !== ''; }));
            } else {
                $phoneVal = $supporter['phone'];
            }
            $cd[] = ['type' => 'phone', 'value' => $phoneVal];
        }
        if (!empty($supporter['email'])) {
            $emailVal = is_array($supporter['email']) ? implode(', ', $supporter['email']) : $supporter['email'];
            $cd[] = ['type' => 'email', 'value' => $emailVal];
        }
        if (!empty($supporter['others'])) {
            foreach ($supporter['others'] as $key => $value) {
                if (empty($value)) {
                    continue;
                }
                $low = strtolower($key);
                if (in_array($low, ['skype', 'viber', 'whatsapp', 'zalo'], true)) {
                    $vals = array_map(function ($s) { return trim($s); }, explode(',', $value));
                    $cd[] = ['type' => $low, 'value' => $vals];
                } else {
                    $isUrl = nv_is_url($value);
                    $cd[] = ['type' => ucfirst($key), 'value' => ['is_url' => $isUrl, 'content' => $value]];
                }
            }
        }
        $item['cd'] = $cd;
        $sups[] = $item;
    }
    $tpl->assign('SUPPORTERS', $sups);

    $form = contact_form_theme($array_content, $departments, $cats, $base_url, $checkss);
    $tpl->assign('FORM', $form);

    return $tpl->fetch('main.tpl');
}

/**
 * contact_form_theme()
 *
 * @param array $array_content
 * @param array $departments
 * @param array $cats
 * @param string $base_url
 * @param string $checkss
 * @return string
 */
function contact_form_theme($array_content, $departments, $cats, $base_url, $checkss)
{
    global $nv_Lang, $global_config, $module_name, $module_config, $module_captcha;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('form.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MCONFIG', $module_config[$module_name]);
    $tpl->assign('MODULE_CAPTCHA', $module_captcha);
    $tpl->assign('GCONFIG', $global_config);
    $tpl->assign('CAPTCHA_ATTRS', nv_captcha_form_attrs('fcode'));
    $tpl->assign('ACTION_FILE', $base_url);
    $tpl->assign('CHECKSS', $checkss);
    $tpl->assign('CONTENT', $array_content);

    $count = count($cats);
    if ($count) {
        $smCats = [];
        foreach ($cats as $did => $cat) {
            $cat[$did . '_other'] = $nv_Lang->getModule('other_cat');
            $items = [];
            foreach ($cat as $key => $value) {
                $items[] = ['val' => $key, 'name' => $value];
            }
            $smCats[] = [
                'name' => $departments[$did]['full_name'],
                'items' => $items
            ];
        }
        $tpl->assign('CATS', $smCats);
    }

    $tpl->assign('MCONFIG', $module_config[$module_name]);

    return $tpl->fetch('form.tpl');
}

/**
 * contact_sendcontact()
 *
 * @param array $feedback
 * @param array $departments
 * @param bool  $sendinfo
 * @return string
 */
function contact_sendcontact($feedback, $departments, $sendinfo = true)
{
    global $global_config, $client_info;

    $xtpl = new XTemplate('sendcontact.tpl', get_module_tpl_dir('sendcontact.tpl'));
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('SITE_NAME', $global_config['site_name']);
    $xtpl->assign('SITE_URL', $global_config['site_url']);
    $xtpl->assign('FULLNAME', $feedback['sender_name']);
    $xtpl->assign('EMAIL', $feedback['sender_email']);
    $xtpl->assign('PART', $departments[$feedback['department']]['full_name']);
    $xtpl->assign('IP', $client_info['ip']);
    $xtpl->assign('TITLE', $feedback['filter_title']);
    $xtpl->assign('CONTENT', nv_htmlspecialchars($feedback['filter_content']));

    if ($sendinfo) {
        if (!empty($feedback['category'])) {
            $xtpl->assign('CAT', $feedback['category']);
            $xtpl->parse('main.sendinfo.cat');
        }
        if (!empty($feedback['filter_sender_phone'])) {
            $xtpl->assign('PHONE', $feedback['filter_sender_phone']);
            $xtpl->parse('main.sendinfo.phone');
        }
        $xtpl->parse('main.sendinfo');
    } else {
        if (!empty($feedback['category'])) {
            $xtpl->assign('CAT', $feedback['category']);
            $xtpl->parse('main.mysendinfo.cat');
        }
        if (!empty($feedback['filter_sender_phone'])) {
            $xtpl->assign('PHONE', $feedback['filter_sender_phone']);
            $xtpl->parse('main.mysendinfo.phone');
        }
        $xtpl->parse('main.mysendinfo');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}
