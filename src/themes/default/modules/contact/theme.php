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
    global $nv_Lang, $module_name, $page_title;

    if (!empty($departments)) {
        $keys = array_keys($departments);
        foreach ($keys as $key) {
            if (!$is_specific and $departments[$key]['act'] == 2) {
                // Không hiển thị các bộ phận theo cấu hình trong quản trị
                unset($departments[$key]);
                continue;
            }

            $departments[$key]['url'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $departments[$key]['alias'];

            $departments[$key]['cd'] = [];
            if (!empty($departments[$key]['phone'])) {
                $departments[$key]['cd'][] = [
                    'type' => 'phone',
                    'value' => $departments[$key]['phone']
                ];
            }
            if (!empty($departments[$key]['fax'])) {
                $departments[$key]['cd'][] = [
                    'type' => 'fax',
                    'value' => $departments[$key]['fax']
                ];
            }
            if (!empty($departments[$key]['email'])) {
                $departments[$key]['cd'][] = [
                    'type' => 'email',
                    'value' => $departments[$key]['email']
                ];
            }

            if ($is_specific and !empty($departments[$key]['others'])) {
                foreach ($departments[$key]['others'] as $k => $value) {
                    if (!empty($value)) {
                        $_k = strtolower($k);
                        if (in_array($_k, ['skype', 'viber', 'whatsapp', 'zalo'], true)) {
                            $departments[$key]['cd'][] = [
                                'type' => $_k,
                                'value' => array_map('trim', explode(',', $value))
                            ];
                        } else {
                            $departments[$key]['cd'][] = [
                                'type' => ucfirst($k),
                                'value' => ['is_url' => nv_is_url($value), 'content' => $value]
                            ];
                        }
                    }
                }
            }
        }
    }

    if (!empty($supporters)) {
        $keys = array_keys($supporters);
        foreach ($keys as $key) {
            $supporters[$key]['cd'] = [];
            if (!empty($supporters[$key]['phone'])) {
                $supporters[$key]['cd'][] = [
                    'type' => 'phone',
                    'value' => $supporters[$key]['phone']
                ];
            }

            if (!empty($supporters[$key]['email'])) {
                $supporters[$key]['cd'][] = [
                    'type' => 'email',
                    'value' => $supporters[$key]['email']
                ];
            }

            if (!empty($supporter['others'])) {
                foreach ($supporter['others'] as $k => $value) {
                    if (!empty($value)) {
                        $_k = strtolower($k);
                        if (in_array($_k, ['skype', 'viber', 'whatsapp', 'zalo'], true)) {
                            $supporter[$key]['cd'][] = [
                                'type' => $_k,
                                'value' => array_map('trim', explode(',', $value))
                            ];
                        } else {
                            $supporter[$key]['cd'][] = [
                                'type' => ucfirst($k),
                                'value' => ['is_url' => nv_is_url($value), 'content' => $value]
                            ];
                        }
                    }
                }
            }
        }
    }

    $stpl = new \NukeViet\Template\NVSmarty();
    $stpl->setTemplateDir(str_replace(DIRECTORY_SEPARATOR, '/', __DIR__) . '/smarty');
    $stpl->assign('LANG', $nv_Lang);
    $stpl->assign('IS_HOME', !$is_specific);
    $stpl->assign('BODYTEXT', $array_content['bodytext']);
    $stpl->assign('THEME_PAGE_TITLE', nv_html_page_title(false));
    $stpl->assign('PAGE_TITLE', $page_title);
    $stpl->assign('DEPARTMENTS', $departments);
    $stpl->assign('SUPPORTERS', $supporters);
    $stpl->assign('FORM', contact_form_theme($array_content, $departments, $cats, $base_url, $checkss));

    return $stpl->fetch('main.tpl');
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
    global $nv_Lang, $global_config, $module_config, $module_name, $module_captcha;

    $captcha = '';
    if ($module_captcha == 'recaptcha' and $global_config['recaptcha_ver'] == 3) {
        $captcha = 'recaptcha3';
    } elseif ($module_captcha == 'recaptcha' and $global_config['recaptcha_ver'] == 2) {
        $captcha = 'recaptcha';
    } elseif ($module_captcha == 'captcha') {
        $captcha = 'captcha';
    } elseif ($module_captcha == 'turnstile') {
        $captcha = 'turnstile';
    }

    $categories = [];
    if (count($cats)) {
        foreach ($cats as $did => $cat) {
            $categories[$did] = [
                'name' => $departments[$did]['full_name'],
                'items' => []
            ];
            $cat[$did . '_other'] = $nv_Lang->getModule('other_cat');
            foreach ($cat as $key => $value) {
                $categories[$did]['items'][] = [
                    'val' => $key,
                    'name' => $value
                ];
            }
        }
    }

    $stpl = new \NukeViet\Template\NVSmarty();
    $stpl->setTemplateDir(str_replace(DIRECTORY_SEPARATOR, '/', __DIR__) . '/smarty');
    $stpl->assign('LANG', $nv_Lang);
    $stpl->assign('CONTENT', $array_content);
    $stpl->assign('ACTION_FILE', $base_url);
    $stpl->assign('CHECKSS', $checkss);
    $stpl->assign('CAPTCHA', $captcha);
    $stpl->assign('CATS', $categories);
    $stpl->assign('FEEDBACK_PHONE', !empty($module_config[$module_name]['feedback_phone']));
    $stpl->assign('FEEDBACK_ADDRESS', !empty($module_config[$module_name]['feedback_address']));
    $stpl->assign('DATA_WARNING', [
        'active' => !empty($global_config['data_warning']),
        'mess' => !empty($global_config['data_warning_content']) ? $global_config['data_warning_content'] : $nv_Lang->getGlobal('data_warning_content')
    ]);
    $stpl->assign('ANTISPAM_WARNING', [
        'active' => !empty($global_config['antispam_warning']),
        'mess' => !empty($global_config['antispam_warning_content']) ? $global_config['antispam_warning_content'] : $nv_Lang->getGlobal('antispam_warning_content')
    ]);

    return $stpl->fetch('form.tpl');
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
    global $global_config, $client_info, $nv_Lang;

    $feedback['filter_content'] = nv_htmlspecialchars($feedback['filter_content']);

    $stpl = new \NukeViet\Template\NVSmarty();
    $stpl->setTemplateDir(str_replace(DIRECTORY_SEPARATOR, '/', __DIR__) . '/smarty');
    $stpl->assign('LANG', $nv_Lang);
    $stpl->assign('SITE_NAME', $global_config['site_name']);
    $stpl->assign('SITE_URL', $global_config['site_url']);
    $stpl->assign('FEEDBACK', $feedback);
    $stpl->assign('PART', $departments[$feedback['department']]['full_name']);
    $stpl->assign('SENDINFO', $sendinfo);
    $stpl->assign('IP', $client_info['ip']);

    return $stpl->fetch('sendcontact.tpl');
}
