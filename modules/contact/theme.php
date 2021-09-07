<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
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
 * @param array  $array_department
 * @param array  $catsName
 * @param string $base_url
 * @param string $checkss
 * @return string
 */
function contact_main_theme($array_content, $array_department, $catsName, $base_url, $checkss)
{
    global $lang_global, $lang_module, $module_info, $alias_url;

    $xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('CHECKSS', $checkss);
    $xtpl->assign('CONTENT', $array_content);

    if (!empty($array_content['bodytext'])) {
        $xtpl->parse('main.bodytext');
    }

    if (!empty($array_department)) {
        foreach ($array_department as $dep) {
            if (empty($alias_url) and $dep['act'] == 2) {
                // Không hiển thị các bộ phận theo cấu hình trong quản trị
                continue;
            }

            // Hiển thị hình
            if (!empty($dep['image'])) {
                $dep['srcset'] = '';
                if (!nv_is_url($dep['image'])) {
                    if (file_exists(NV_ROOTDIR . '/' . NV_MOBILE_FILES_DIR . '/' . $module_info['module_upload'] . '/' . $dep['image'])) {
                        $imagesize = @getimagesize(NV_UPLOADS_REAL_DIR . '/' . $module_info['module_upload'] . '/' . $dep['image']);
                        $dep['srcset'] = NV_BASE_SITEURL . NV_MOBILE_FILES_DIR . '/' . $module_info['module_upload'] . '/' . $dep['image'] . ' ' . NV_MOBILE_MODE_IMG . 'w, ';
                        $dep['srcset'] .= NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_info['module_upload'] . '/' . $dep['image'] . ' ' . $imagesize[0] . 'w';
                    }
                    $dep['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_info['module_upload'] . '/' . $dep['image'];
                }
            }

            $xtpl->assign('DEP', $dep);

            // Hiển thị hình
            if (!empty($dep['image'])) {
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
                $nums = array_map('trim', explode('|', nv_unhtmlspecialchars($dep['phone'])));
                foreach ($nums as $k => $num) {
                    unset($m);
                    if (preg_match("/^(.*)\s*\[([0-9\+\.\,\;\*\#]+)\]$/", $num, $m)) {
                        $phone = [
                            'number' => nv_htmlspecialchars($m[1]),
                            'href' => $m[2]
                        ];
                        $xtpl->assign('PHONE', $phone);
                        $xtpl->parse('main.dep.phone.item.href');
                        $xtpl->parse('main.dep.phone.item.href2');
                    } else {
                        $num = preg_replace("/\[[^\]]*\]/", '', $num);
                        $phone = [
                            'number' => nv_htmlspecialchars($num)
                        ];
                        $xtpl->assign('PHONE', $phone);
                    }
                    if ($k) {
                        $xtpl->parse('main.dep.phone.item.comma');
                    }
                    $xtpl->parse('main.dep.phone.item');
                }

                $xtpl->parse('main.dep.phone');
            }
            if (!empty($dep['fax'])) {
                $xtpl->parse('main.dep.fax');
            }
            if (!empty($dep['email'])) {
                $emails = array_map('trim', explode(',', $dep['email']));
                foreach ($emails as $k => $email) {
                    $xtpl->assign('EMAIL', $email);
                    if ($k) {
                        $xtpl->parse('main.dep.email.item.comma');
                    }
                    $xtpl->parse('main.dep.email.item');
                }

                $xtpl->parse('main.dep.email');
            }

            if (!empty($dep['others'])) {
                $others = json_decode($dep['others'], true);

                if (!empty($others)) {
                    foreach ($others as $key => $value) {
                        if (!empty($value)) {
                            if (strtolower($key) == 'yahoo') {
                                $ys = array_map('trim', explode(',', $value));
                                foreach ($ys as $k => $y) {
                                    $xtpl->assign('YAHOO', [
                                        'name' => $key,
                                        'value' => $y
                                    ]);
                                    if ($k) {
                                        $xtpl->parse('main.dep.yahoo.item.comma');
                                    }
                                    $xtpl->parse('main.dep.yahoo.item');
                                }
                                $xtpl->parse('main.dep.yahoo');
                            } elseif (strtolower($key) == 'skype') {
                                $ss = array_map('trim', explode(',', $value));
                                foreach ($ss as $k => $s) {
                                    $xtpl->assign('SKYPE', [
                                        'name' => $key,
                                        'value' => $s
                                    ]);
                                    if ($k) {
                                        $xtpl->parse('main.dep.skype.item.comma');
                                    }
                                    $xtpl->parse('main.dep.skype.item');
                                }
                                $xtpl->parse('main.dep.skype');
                            } elseif (strtolower($key) == 'viber') {
                                $ss = array_map('trim', explode(',', $value));
                                foreach ($ss as $k => $s) {
                                    $xtpl->assign('VIBER', [
                                        'name' => $key,
                                        'value' => $s
                                    ]);
                                    if ($k) {
                                        $xtpl->parse('main.dep.viber.item.comma');
                                    }
                                    $xtpl->parse('main.dep.viber.item');
                                }
                                $xtpl->parse('main.dep.viber');
                            } elseif (strtolower($key) == 'icq') {
                                $ss = array_map('trim', explode(',', $value));
                                foreach ($ss as $k => $s) {
                                    $xtpl->assign('ICQ', [
                                        'name' => $key,
                                        'value' => $s
                                    ]);
                                    if ($k) {
                                        $xtpl->parse('main.dep.icq.item.comma');
                                    }
                                    $xtpl->parse('main.dep.icq.item');
                                }
                                $xtpl->parse('main.dep.icq');
                            } elseif (strtolower($key) == 'whatsapp') {
                                $ss = array_map('trim', explode(',', $value));
                                foreach ($ss as $k => $s) {
                                    $xtpl->assign('WHATSAPP', [
                                        'name' => $key,
                                        'value' => $s
                                    ]);
                                    if ($k) {
                                        $xtpl->parse('main.dep.whatsapp.item.comma');
                                    }
                                    $xtpl->parse('main.dep.whatsapp.item');
                                }
                                $xtpl->parse('main.dep.whatsapp');
                            } else {
                                $xtpl->assign('OTHER', [
                                    'name' => $key,
                                    'value' => $value
                                ]);
                                if (nv_is_url($value)) {
                                    $xtpl->parse('main.dep.other.url');
                                } else {
                                    $xtpl->parse('main.dep.other.text');
                                }
                                $xtpl->parse('main.dep.other');
                            }
                        }
                    }
                }
            }

            $xtpl->parse('main.dep');
        }
    }

    $form = contact_form_theme($array_content, $catsName, $base_url, $checkss);
    $xtpl->assign('FORM', $form);

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * contact_form_theme()
 *
 * @param array  $array_content
 * @param array  $catsName
 * @param string $base_url
 * @param string $checkss
 * @return string
 */
function contact_form_theme($array_content, $catsName, $base_url, $checkss)
{
    global $lang_global, $lang_module, $module_info, $global_config, $module_config, $module_name, $module_captcha;

    $xtpl = new XTemplate('form.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('CONTENT', $array_content);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
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
        $xtpl->assign('N_CAPTCHA', $lang_global['securitycode1']);
        $xtpl->parse('main.recaptcha');
    } elseif ($module_captcha == 'captcha') {
        $xtpl->assign('GFX_WIDTH', NV_GFX_WIDTH);
        $xtpl->assign('GFX_HEIGHT', NV_GFX_HEIGHT);
        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
        $xtpl->assign('CAPTCHA_REFRESH', $lang_global['captcharefresh']);
        $xtpl->assign('NV_GFX_NUM', NV_GFX_NUM);
        $xtpl->parse('main.captcha');
    }

    if (defined('NV_IS_USER')) {
        $xtpl->parse('main.iuser');
    } else {
        $xtpl->parse('main.iguest');
    }

    if (!empty($catsName)) {
        foreach ($catsName as $key => $cat) {
            $xtpl->assign('SELECTVALUE', $key);
            $xtpl->assign('SELECTNAME', $cat);
            $xtpl->parse('main.cats.select_option_loop');
        }
        $xtpl->parse('main.cats');
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * contact_sendcontact()
 *
 * @param int    $row_id
 * @param mixed  $fcat
 * @param string $ftitle
 * @param string $fname
 * @param string $femail
 * @param string $fphone
 * @param string $fcon
 * @param mixed  $fpart
 * @param bool   $sendinfo
 * @return string
 */
function contact_sendcontact($row_id, $fcat, $ftitle, $fname, $femail, $fphone, $fcon, $fpart, $sendinfo = true)
{
    global $global_config, $lang_module, $module_info, $array_department, $client_info;

    $xtpl = new XTemplate('sendcontact.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('SITE_NAME', $global_config['site_name']);
    $xtpl->assign('SITE_URL', $global_config['site_url']);
    $xtpl->assign('FULLNAME', $fname);
    $xtpl->assign('EMAIL', $femail);
    $xtpl->assign('PART', $array_department[$fpart]['full_name']);
    $xtpl->assign('IP', $client_info['ip']);
    $xtpl->assign('TITLE', $ftitle);
    $xtpl->assign('CONTENT', nv_htmlspecialchars($fcon));

    if ($sendinfo) {
        if (!empty($fcat)) {
            $xtpl->assign('CAT', $fcat);
            $xtpl->parse('main.sendinfo.cat');
        }

        if (!empty($fphone)) {
            $xtpl->assign('PHONE', $fphone);
            $xtpl->parse('main.sendinfo.phone');
        }
        $xtpl->parse('main.sendinfo');
    } else {
        if (!empty($fcat)) {
            $xtpl->assign('CAT', $fcat);
            $xtpl->parse('main.mysendinfo.cat');
        }

        if (!empty($fphone)) {
            $xtpl->assign('PHONE', $fphone);
            $xtpl->parse('main.mysendinfo.phone');
        }
        $xtpl->parse('main.mysendinfo');
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
}
