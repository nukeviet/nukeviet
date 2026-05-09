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
    global $nv_Lang, $module_info, $module_name, $page_title;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('main.tpl'));

    $dep_list = [];
    if (!empty($departments)) {
        foreach ($departments as $dep) {
            if (!$is_specific and $dep['act'] == 2) {
                continue;
            }

            $dep['url'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $dep['alias'];

            $cd = [];

            if (!empty($dep['phone'])) {
                $items = [];
                foreach ($dep['phone'] as $num) {
                    if (count($num) == 2) {
                        $items[] = '<a href="tel:' . $num[1] . '">' . $num[0] . '</a>';
                    } else {
                        $items[] = $num[0];
                    }
                }
                $cd[] = [
                    'type' => 'phone',
                    'value' => implode(', ', $items)
                ];
            }

            if (!empty($dep['fax'])) {
                $cd[] = [
                    'type' => 'fax',
                    'value' => $dep['fax']
                ];
            }

            if (!empty($dep['email'])) {
                $email_links = [];
                foreach ($dep['email'] as $email) {
                    $email_links[] = '<a href="mailto:' . $email . '">' . $email . '</a>';
                }
                $cd[] = [
                    'type' => 'email',
                    'value' => $email_links
                ];
            }

            if ($is_specific and !empty($dep['others'])) {
                foreach ($dep['others'] as $key => $value) {
                    if (empty($value)) {
                        continue;
                    }
                    $type = strtolower($key);
                    if ($type === 'skype') {
                        $cd[] = [
                            'type' => 'skype',
                            'value' => array_map(function ($item) {
                                $item = trim($item);
                                return '<a href="skype:' . $item . '?call">' . $item . '</a>';
                            }, explode(',', $value))
                        ];
                    } elseif ($type === 'viber') {
                        $cd[] = [
                            'type' => 'viber',
                            'value' => array_map(function ($item) {
                                $item = trim($item);
                                return '<a href="viber://pa?chatURI=' . $item . '">' . $item . '</a>';
                            }, explode(',', $value))
                        ];
                    } elseif ($type === 'whatsapp') {
                        $cd[] = [
                            'type' => 'whatsapp',
                            'value' => array_map(function ($item) {
                                $item = trim($item);
                                return '<a href="https://wa.me/' . $item . '">' . $item . '</a>';
                            }, explode(',', $value))
                        ];
                    } elseif ($type === 'zalo') {
                        $cd[] = [
                            'type' => 'zalo',
                            'value' => array_map(function ($item) {
                                $item = trim($item);
                                return '<a href="https://zalo.me/' . $item . '">' . $item . '</a>';
                            }, explode(',', $value))
                        ];
                    } else {
                        $cd[] = [
                            'type' => ucfirst($key),
                            'value' => [
                                'is_url' => (bool) nv_is_url($value),
                                'content' => $value
                            ]
                        ];
                    }
                }
            }

            $dep['cd'] = $cd;
            $dep_list[] = $dep;
        }
    }

    $sup_list = [];
    if (!empty($supporters)) {
        foreach ($supporters as $supporter) {
            $cd = [];

            if (!empty($supporter['phone'])) {
                $items = [];
                foreach ($supporter['phone'] as $num) {
                    if (count($num) == 2) {
                        $items[] = '<a href="tel:' . $num[1] . '">' . $num[0] . '</a>';
                    } else {
                        $items[] = $num[0];
                    }
                }
                $cd[] = [
                    'type' => 'phone',
                    'value' => implode(', ', $items)
                ];
            }

            if (!empty($supporter['email'])) {
                $cd[] = [
                    'type' => 'email',
                    'value' => '<a href="mailto:' . $supporter['email'] . '">' . $supporter['email'] . '</a>'
                ];
            }

            if (!empty($supporter['others'])) {
                foreach ($supporter['others'] as $key => $value) {
                    if (empty($value)) {
                        continue;
                    }
                    $type = strtolower($key);
                    if ($type === 'skype') {
                        $cd[] = [
                            'type' => 'skype',
                            'value' => array_map(function ($item) {
                                $item = trim($item);
                                return '<a href="skype:' . $item . '?call">' . $item . '</a>';
                            }, explode(',', $value))
                        ];
                    } elseif ($type === 'viber') {
                        $cd[] = [
                            'type' => 'viber',
                            'value' => array_map(function ($item) {
                                $item = trim($item);
                                return '<a href="viber://pa?chatURI=' . $item . '">' . $item . '</a>';
                            }, explode(',', $value))
                        ];
                    } elseif ($type === 'whatsapp') {
                        $cd[] = [
                            'type' => 'whatsapp',
                            'value' => array_map(function ($item) {
                                $item = trim($item);
                                return '<a href="https://wa.me/' . $item . '">' . $item . '</a>';
                            }, explode(',', $value))
                        ];
                    } elseif ($type === 'zalo') {
                        $cd[] = [
                            'type' => 'zalo',
                            'value' => array_map(function ($item) {
                                $item = trim($item);
                                return '<a href="https://zalo.me/' . $item . '">' . $item . '</a>';
                            }, explode(',', $value))
                        ];
                    } else {
                        $cd[] = [
                            'type' => ucfirst($key),
                            'value' => [
                                'is_url' => (bool) nv_is_url($value),
                                'content' => $value
                            ]
                        ];
                    }
                }
            }

            $supporter['cd'] = $cd;
            $sup_list[] = $supporter;
        }
    }

    $form = contact_form_theme($array_content, $departments, $cats, $base_url, $checkss);

    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('PAGE_TITLE', $page_title);
    $tpl->assign('IS_SPECIFIC', $is_specific);
    $tpl->assign('DATA', $array_content);
    $tpl->assign('DEPARTMENTS', $dep_list);
    $tpl->assign('SUPPORTERS', $sup_list);
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
    global $nv_Lang, $global_config, $module_config, $module_name;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('form.tpl'));

    $cats_list = [];
    if (!empty($cats)) {
        foreach ($cats as $did => $cat) {
            $cat[$did . '_other'] = $nv_Lang->getModule('other_cat');
            $items = [];
            foreach ($cat as $key => $value) {
                $items[] = [
                    'val' => $key,
                    'name' => $value
                ];
            }
            $cats_list[] = [
                'name' => $departments[$did]['full_name'] ?? '',
                'items' => $items
            ];
        }
    }

    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('ACTION_FILE', $base_url);
    $tpl->assign('CHECKSS', $checkss);
    $tpl->assign('CAPTCHA_ATTRS', nv_captcha_form_attrs('fcode'));
    $tpl->assign('CONTENT', $array_content);
    $tpl->assign('MCONFIG', $module_config[$module_name]);
    $tpl->assign('GCONFIG', $global_config);
    $tpl->assign('CATS', $cats_list);

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
    global $nv_Lang, $global_config, $client_info;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('sendcontact.tpl'));

    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('SITE_NAME', $global_config['site_name']);
    $tpl->assign('SITE_URL', $global_config['site_url']);
    $tpl->assign('SENDINFO', $sendinfo);
    $tpl->assign('FEEDBACK', [
        'sender_name' => $feedback['sender_name'],
        'sender_email' => $feedback['sender_email'],
        'filter_sender_phone' => $feedback['filter_sender_phone'] ?? '',
        'category' => $feedback['category'] ?? '',
        'filter_title' => $feedback['filter_title'],
        'filter_content' => nv_htmlspecialchars($feedback['filter_content']),
    ]);
    $tpl->assign('PART', $departments[$feedback['department']]['full_name']);
    $tpl->assign('IP', $client_info['ip']);

    return $tpl->fetch('sendcontact.tpl');
}
