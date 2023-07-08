<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
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
 * @return string
 */
function contact_main_theme($array_content, $is_specific, $departments, $cats, $supporters, $base_url, $checkss)
{
    global $nv_Lang, $module_info, $module_name, $page_title;

    $xtpl = new XTemplate('main.tpl', get_module_tpl_dir('main.tpl'));
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('CHECKSS', $checkss);
    $xtpl->assign('CONTENT', $array_content);
    $xtpl->assign('PAGE_TITLE', $page_title);
    $xtpl->assign('THEME_PAGE_TITLE', nv_html_page_title(false));

    if (!empty($array_content['bodytext'])) {
        $xtpl->parse('main.bodytext');
    }

    if (!empty($departments)) {
        $count = count($departments);
        foreach ($departments as $dep) {
            if (!$is_specific and $dep['act'] == 2) {
                // Không hiển thị các bộ phận theo cấu hình trong quản trị
                continue;
            }

            $dep['url'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $dep['alias'];

            $xtpl->assign('DEP', $dep);

            if ($count > 1) {
                $xtpl->parse('main.dep.header');
            } else {
                $xtpl->parse('main.dep.dep_header');
            }

            // Hiển thị hình
            if ($is_specific and !empty($dep['image'])) {
                $xtpl->parse('main.dep.image');
            }

            if (!empty($dep['note'])) {
                $xtpl->parse('main.dep.note');
            }

            // Hiển thị địa chỉ
            if (!empty($dep['address'])) {
                $xtpl->parse('main.dep.address');
            }

            if (!empty($dep['phone'])) {
                $items = [];
                foreach ($dep['phone'] as $num) {
                    if (count($num) == 2) {
                        $items[] = '<a href="tel:' . $num[1] . '">' . $num[0] . '</a>';
                    } else {
                        $items[] = $num[0];
                    }
                }
                $xtpl->assign('CD', [
                    'icon' => 'fa-phone',
                    'name' => $nv_Lang->getModule('phone'),
                    'value' => implode(', ', $items)
                ]);
                $xtpl->parse('main.dep.cd');
            }
            if (!empty($dep['fax'])) {
                $xtpl->assign('CD', [
                    'icon' => 'fa-fax',
                    'name' => $nv_Lang->getModule('fax'),
                    'value' => $dep['fax']
                ]);
                $xtpl->parse('main.dep.cd');
            }
            if (!empty($dep['email'])) {
                $items = [];
                foreach ($dep['email'] as $email) {
                    $items[] = '<a href="mailto:' . $email . '">' . $email . '</a>';
                }
                $xtpl->assign('CD', [
                    'icon' => 'fa-envelope',
                    'name' => $nv_Lang->getModule('email'),
                    'value' => implode(', ', $items)
                ]);
                $xtpl->parse('main.dep.cd');
            }

            if ($is_specific and !empty($dep['others'])) {
                foreach ($dep['others'] as $key => $value) {
                    if (!empty($value)) {
                        if (strtolower($key) == 'skype') {
                            $items = array_map(function ($item) {
                                $item = trim($item);

                                return '<a href="skype:' . $item . '?call">' . $item . '</a>';
                            }, explode(',', $value));
                            $xtpl->assign('CD', [
                                'icon' => 'fa-skype',
                                'name' => 'Skype',
                                'value' => implode(', ', $items)
                            ]);
                        } elseif (strtolower($key) == 'viber') {
                            $items = array_map(function ($item) {
                                $item = trim($item);

                                return '<a href="viber://pa?chatURI=' . $item . '">' . $item . '</a>';
                            }, explode(',', $value));
                            $xtpl->assign('CD', [
                                'icon' => 'icon-viber',
                                'name' => 'Viber',
                                'value' => implode(', ', $items)
                            ]);
                        } elseif (strtolower($key) == 'whatsapp') {
                            $items = array_map(function ($item) {
                                $item = trim($item);

                                return '<a href="https://wa.me/' . $item . '">' . $item . '</a>';
                            }, explode(',', $value));
                            $xtpl->assign('CD', [
                                'icon' => 'fa-whatsapp',
                                'name' => 'WhatsApp',
                                'value' => implode(', ', $items)
                            ]);
                        } elseif (strtolower($key) == 'zalo') {
                            $items = array_map(function ($item) {
                                $item = trim($item);

                                return '<a href="https://zalo.me/' . $item . '">' . $item . '</a>';
                            }, explode(',', $value));
                            $xtpl->assign('CD', [
                                'icon' => 'icon-zalo',
                                'name' => 'Zalo',
                                'value' => implode(', ', $items)
                            ]);
                        } else {
                            $xtpl->assign('CD', [
                                'icon' => '',
                                'name' => ucfirst($key),
                                'value' => nv_is_url($value) ? '<a href="' . $value . '">' . $value . '</a>' : $value
                            ]);
                        }
                        $xtpl->parse('main.dep.cd');
                    }
                }
            }

            $xtpl->parse('main.dep');
        }
    }

    if (!empty($supporters)) {
        foreach ($supporters as $supporter) {
            $xtpl->assign('SUPPORTER', $supporter);

            $items = [];
            foreach ($supporter['phone'] as $num) {
                if (count($num) == 2) {
                    $items[] = '<a href="tel:' . $num[1] . '">' . $num[0] . '</a>';
                } else {
                    $items[] = $num[0];
                }
            }
            $xtpl->assign('CD', [
                'icon' => 'fa-phone',
                'name' => $nv_Lang->getModule('phone'),
                'value' => implode(', ', $items)
            ]);
            $xtpl->parse('main.supporter_block.supporter.cd');

            if (!empty($supporter['email'])) {
                $xtpl->assign('CD', [
                    'icon' => 'fa-envelope',
                    'name' => $nv_Lang->getModule('email'),
                    'value' => '<a href="' . $supporter['email'] . '">' . $supporter['email'] . '</a>'
                ]);
                $xtpl->parse('main.supporter_block.supporter.cd');
            }

            if (!empty($supporter['others'])) {
                foreach ($supporter['others'] as $key => $value) {
                    if (!empty($value)) {
                        if (strtolower($key) == 'skype') {
                            $items = array_map(function ($item) {
                                $item = trim($item);

                                return '<a href="skype:' . $item . '?call">' . $item . '</a>';
                            }, explode(',', $value));
                            $xtpl->assign('CD', [
                                'icon' => 'fa-skype',
                                'name' => 'Skype',
                                'value' => implode(', ', $items)
                            ]);
                        } elseif (strtolower($key) == 'viber') {
                            $items = array_map(function ($item) {
                                $item = trim($item);

                                return '<a href="viber://pa?chatURI=' . $item . '">' . $item . '</a>';
                            }, explode(',', $value));
                            $xtpl->assign('CD', [
                                'icon' => 'icon-viber',
                                'name' => 'Viber',
                                'value' => implode(', ', $items)
                            ]);
                        } elseif (strtolower($key) == 'whatsapp') {
                            $items = array_map(function ($item) {
                                $item = trim($item);

                                return '<a href="https://wa.me/' . $item . '">' . $item . '</a>';
                            }, explode(',', $value));
                            $xtpl->assign('CD', [
                                'icon' => 'fa-whatsapp',
                                'name' => 'WhatsApp',
                                'value' => implode(', ', $items)
                            ]);
                        } elseif (strtolower($key) == 'zalo') {
                            $items = array_map(function ($item) {
                                $item = trim($item);

                                return '<a href="https://zalo.me/' . $item . '">' . $item . '</a>';
                            }, explode(',', $value));
                            $xtpl->assign('CD', [
                                'icon' => 'icon-zalo',
                                'name' => 'Zalo',
                                'value' => implode(', ', $items)
                            ]);
                        } else {
                            $xtpl->assign('CD', [
                                'icon' => '',
                                'name' => ucfirst($key),
                                'value' => nv_is_url($value) ? '<a href="' . $value . '">' . $value . '</a>' : $value
                            ]);
                        }
                        $xtpl->parse('main.supporter_block.supporter.cd');
                    }
                }
            }

            $xtpl->parse('main.supporter_block.supporter');
        }
        $xtpl->parse('main.supporter_block');
    }

    $form = contact_form_theme($array_content, $departments, $cats, $base_url, $checkss);
    $xtpl->assign('FORM', $form);

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * contact_form_theme()
 *
 * @param array  $array_content
 * @param arrat  $departments
 * @param array  $cats
 * @param string $base_url
 * @param string $checkss
 * @return string
 */
function contact_form_theme($array_content, $departments, $cats, $base_url, $checkss)
{
    global $nv_Lang, $module_info, $global_config, $module_config, $module_name, $module_captcha;

    $array_content['phone_required'] = $array_content['sender_phone_required'] ? ' required' : '';
    $array_content['address_required'] = $array_content['sender_address_required'] ? ' required' : '';
    list($template, $dir) = get_module_tpl_dir('form.tpl', true);
    $xtpl = new XTemplate('form.tpl', $dir);
    $xtpl->assign('CONTENT', $array_content);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('TEMPLATE', $template);
    $xtpl->assign('ACTION_FILE', $base_url);
    $xtpl->assign('CHECKSS', $checkss);

    if ($array_content['sendcopy']) {
        $xtpl->parse('main.sendcopy');
    }

    // Nếu dùng reCaptcha v3
    if ($module_captcha == 'recaptcha' and $global_config['recaptcha_ver'] == 3) {
        $xtpl->parse('main.recaptcha3');
    }
    // Nếu dùng reCaptcha v2
    elseif ($module_captcha == 'recaptcha' and $global_config['recaptcha_ver'] == 2) {
        $xtpl->assign('RECAPTCHA_ELEMENT', 'recaptcha' . nv_genpass(8));
        $xtpl->assign('N_CAPTCHA', $nv_Lang->getGlobal('securitycode1'));
        $xtpl->parse('main.recaptcha');
    } elseif ($module_captcha == 'captcha') {
        $xtpl->parse('main.captcha');
    }

    if (defined('NV_IS_USER')) {
        $xtpl->parse('main.iuser');
    } else {
        $xtpl->parse('main.iguest');
    }

    $count = count($cats);
    if ($count) {
        foreach ($cats as $did => $cat) {
            $cat[$did . '_other'] = $nv_Lang->getModule('other_cat');
            if ($count > 1) {
                $xtpl->assign('CATNAME', $departments[$did]['full_name']);
                foreach ($cat as $key => $value) {
                    $xtpl->assign('OPT', [
                        'val' => $key,
                        'name' => $value
                    ]);
                    $xtpl->parse('main.cats.optgroup.option');
                }
                $xtpl->parse('main.cats.optgroup');
            } else {
                foreach ($cat as $key => $value) {
                    $xtpl->assign('OPT', [
                        'val' => $key,
                        'name' => $value
                    ]);
                    $xtpl->parse('main.cats.option2');
                }
            }
        }
        $xtpl->parse('main.cats');
    }

    if (!empty($module_config[$module_name]['feedback_phone'])) {
        $xtpl->parse('main.feedback_phone');
    }

    if (!empty($module_config[$module_name]['feedback_address'])) {
        $xtpl->parse('main.feedback_address');
    }

    if (!empty($global_config['data_warning']) or !empty($global_config['antispam_warning'])) {
        if (!empty($global_config['data_warning'])) {
            $xtpl->assign('DATA_USAGE_CONFIRM', !empty($global_config['data_warning_content']) ? $global_config['data_warning_content'] : $nv_Lang->getGlobal('data_warning_content'));
            $xtpl->parse('main.confirm.data_sending');
        }

        if (!empty($global_config['antispam_warning'])) {
            $xtpl->assign('ANTISPAM_CONFIRM', !empty($global_config['antispam_warning_content']) ? $global_config['antispam_warning_content'] : $nv_Lang->getGlobal('antispam_warning_content'));
            $xtpl->parse('main.confirm.antispam');
        }
        $xtpl->parse('main.confirm');
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
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
    global $global_config, $module_info, $client_info;

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
