<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    exit('Stop!!!');
}

$selectedtab = $nv_Request->get_int('selectedtab', 'get,post', 0);
if ($selectedtab < 0 or $selectedtab > 2) {
    $selectedtab = 0;
}
$checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid']);

// Xử lý thiết lập CSP
if ($nv_Request->isset_request('submitcsp', 'post') and $checkss == $nv_Request->get_string('checkss', 'post')) {
    $directives = $nv_Request->get_typed_array('directives', 'post', 'textarea');
    $array_config_csp = [];
    $array_config_csp['nv_csp'] = '';
    foreach ($directives as $key => $directive) {
        $directive = trim(strip_tags($directive));
        if (!empty($directive)) {
            $directive = str_replace(["\r\n", "\r", "\n"], ' ', $directive);
            $array_config_csp['nv_csp'] .= $key . ' ' . preg_replace('/[ ]+/', ' ', $directive) . ';';
        }
    }
    $array_config_csp['nv_csp_act'] = (int) $nv_Request->get_bool('nv_csp_act', 'post', false);

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'site' AND config_name = :config_name");
    foreach ($array_config_csp as $config_name => $config_value) {
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
        $sth->execute();
    }

    $nv_Cache->delMod('settings');

    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&selectedtab=' . $selectedtab . '&rand=' . nv_genpass());
} else {
    $directives = !empty($global_config['nv_csp']) ? nv_unhtmlspecialchars($global_config['nv_csp']) : '';
    if (!empty($directives)) {
        $matches = [];
        preg_match_all("/([a-zA-Z0-9\-]+)[\s]+([^\;]+)/i", $directives, $matches);
        $directives = [];
        foreach ($matches[1] as $key => $name) {
            $directives[$name] = trim($matches[2][$key]);
        }
    } else {
        $directives = [];
    }
}

// Xử lý thiết lập RP
//rp_directive
$_rp_directives = [
    'no-referrer' => $lang_module['rp_no_referrer'],
    'no-referrer-when-downgrade' => $lang_module['rp_no_referrer_when_downgrade'],
    'origin' => $lang_module['rp_origin'],
    'origin-when-cross-origin' => $lang_module['rp_origin_when_cross_origin'],
    'same-origin' => $lang_module['rp_same_origin'],
    'strict-origin' => $lang_module['rp_strict_origin'],
    'strict-origin-when-cross-origin' => $lang_module['rp_strict_origin_when_cross_origin'],
    'unsafe-url' => $lang_module['rp_unsafe_url']
];
if ($nv_Request->isset_request('submitrp', 'post') and $checkss == $nv_Request->get_string('checkss', 'post')) {
    $array_config_rp = [];
    $array_config_rp['nv_rp'] = [];
    $nv_rp = $nv_Request->get_title('nv_rp', 'post', '');
    if (!empty($nv_rp)) {
        $nv_rp = preg_replace("/[^a-zA-Z\-]/", ' ', $nv_rp);
        $nv_rp = preg_replace("/[\s]+/", ' ', $nv_rp);
    }
    $nv_rp = !empty($nv_rp) ? array_map('trim', explode(' ', $nv_rp)) : [];
    foreach ($nv_rp as $rp) {
        if (!empty($rp) and isset($_rp_directives[$rp]) and $rp != 'no-referrer') {
            $array_config_rp['nv_rp'][] = $rp;
        }
    }
    $array_config_rp['nv_rp'] = !empty($array_config_rp['nv_rp']) ? implode(', ', $array_config_rp['nv_rp']) : '';
    $array_config_rp['nv_rp_act'] = (int) $nv_Request->get_bool('nv_rp_act', 'post', false);

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'site' AND config_name = :config_name");
    foreach ($array_config_rp as $config_name => $config_value) {
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
        $sth->execute();
    }

    $nv_Cache->delMod('settings');

    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&selectedtab=' . $selectedtab . '&rand=' . nv_genpass());
}

// Xử lý thiết lập PP
$pp_directives = [
    'accelerometer' => $lang_module['pp_accelerometer'],
    'ambient-light-sensor' => $lang_module['pp_ambient_light_sensor'],
    'autoplay' => $lang_module['pp_autoplay'],
    'battery' => $lang_module['pp_battery'],
    'browsing-topics' => $lang_module['pp_browsing_topics'], // Thay thế cho interest-cohort
    'camera' => $lang_module['pp_camera'],
    'display-capture' => $lang_module['pp_display_capture'],
    'document-domain' => $lang_module['pp_document_domain'],
    'encrypted-media' => $lang_module['pp_encrypted_media'],
    'execution-while-not-rendered' => $lang_module['pp_execution_while_not_rendered'],
    'execution-while-out-of-viewport' => $lang_module['pp_execution_while_out_of_viewport'],
    'fullscreen' => $lang_module['pp_fullscreen'],
    'gamepad' => $lang_module['pp_gamepad'],
    'geolocation' => $lang_module['pp_geolocation'],
    'gyroscope' => $lang_module['pp_gyroscope'],
    'hid' => $lang_module['pp_hid'],
    'identity-credentials-get' => $lang_module['pp_identity_credentials_get'],
    'idle-detection' => $lang_module['pp_idle_detection'],
    'local-fonts' => $lang_module['pp_local_fonts'],
    'magnetometer' => $lang_module['pp_magnetometer'],
    'microphone' => $lang_module['pp_microphone'],
    'midi' => $lang_module['pp_midi'],
    'otp-credentials' => $lang_module['pp_otp_credentials'],
    'payment' => $lang_module['pp_payment'],
    'picture-in-picture' => $lang_module['pp_picture_in_picture'],
    'publickey-credentials-create' => $lang_module['pp_publickey_credentials_create'],
    'publickey-credentials-get' => $lang_module['pp_publickey_credentials_get'],
    'screen-wake-lock' => $lang_module['pp_screen_wake_lock'],
    'serial' => $lang_module['pp_serial'],
    'speaker-selection' => $lang_module['pp_speaker_selection'],
    'storage-access' => $lang_module['pp_storage_access'],
    'usb' => $lang_module['pp_usb'],
    'web-share' => $lang_module['pp_web_share'],
    'window-management' => $lang_module['pp_window_management'],
    'xr-spatial-tracking' => $lang_module['pp_xr_spatial_tracking'],
];
$pp_allowlist_tags = ['*', 'self', '()'];
if ($nv_Request->isset_request('submitpp', 'post') and $checkss == $nv_Request->get_string('checkss', 'post')) {
    $array_config_pp = [];
    $array_config_pp['nv_pp'] = [];
    $array_config_pp['nv_fp'] = [];
    $array_config_pp['nv_pp_act'] = (int) $nv_Request->get_bool('nv_pp_act', 'post', false);
    $array_config_pp['nv_fp_act'] = (int) $nv_Request->get_bool('nv_fp_act', 'post', false);

    $pp_val_directives = $nv_Request->get_typed_array('directives', 'post', 'textarea');
    foreach ($pp_directives as $directive => $directive_name) {
        $_directive_value = isset($pp_val_directives[$directive]) ? array_filter(array_unique(array_map('trim', explode('<nv>', nv_nl2br(nv_strtolower(strip_tags($pp_val_directives[$directive])), '<nv>'))))) : [];
        $dir_value = [];
        $ft_dir_value = [];

        // Duyệt xử lý từng dòng
        foreach ($_directive_value as $_dv) {
            if (in_array($_dv, $pp_allowlist_tags)) {
                $dir_value[] = $_dv;
                $ft_dir_value[] = $_dv == 'self' ? "'self'" : $_dv;
            } elseif (preg_match('/^[a-z]+\:\/\/[\w\.\-\*]+$/u', $_dv)) {
                $dir_value[] = '"' . $_dv . '"';

                // FP không hỗ trợ wildcard do đó loại bỏ các dòng wildcard
                if (strpos($_dv, '*') === false) {
                    $ft_dir_value[] = $_dv;
                }
            }
        }

        if (in_array('*', $dir_value)) {
            $dir_value = '*';
            $ft_dir_value = '*';
        } elseif (in_array('()', $dir_value)) {
            $dir_value = '()';
            $ft_dir_value = "'none'";
        } elseif (!empty($dir_value)) {
            $dir_value = '(' . implode(' ', $dir_value) . ')';
        }
        if (!empty($ft_dir_value) and is_array($ft_dir_value)) {
            $ft_dir_value = implode(' ', $ft_dir_value);
        }
        if (!empty($dir_value)) {
            $array_config_pp['nv_pp'][] = $directive . '=' . $dir_value;
        }
        if (!empty($ft_dir_value)) {
            $array_config_pp['nv_fp'][] = $directive . ' ' . $ft_dir_value;
        }
    }
    $array_config_pp['nv_pp'] = empty($array_config_pp['nv_pp']) ? '' : implode(', ', $array_config_pp['nv_pp']);
    $array_config_pp['nv_fp'] = empty($array_config_pp['nv_fp']) ? '' : implode('; ', $array_config_pp['nv_fp']);

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'site' AND config_name = :config_name");
    foreach ($array_config_pp as $config_name => $config_value) {
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
        $sth->execute();
    }

    $nv_Cache->delMod('settings');
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&selectedtab=' . $selectedtab . '&rand=' . nv_genpass());
} else {
    $pp_val_directives = [];
    $_directives = !empty($global_config['nv_pp']) ? array_map('trim', explode(',', $global_config['nv_pp'])) : [];
    foreach ($_directives as $_dvs) {
        if (preg_match('/^([a-z0-9\-]+)[\s]*\=[\s]*(.*?)$/i', $_dvs, $m)) {
            if (!isset($pp_directives[$m[1]])) {
                continue;
            }
            $_dv = trim($m[2]);
            if (in_array($_dv, $pp_allowlist_tags)) {
                $pp_val_directives[$m[1]] = $_dv;
            } elseif (!empty($_dv)) {
                $pp_val_directives[$m[1]] = preg_replace('/[\s]+/', "\n", trim(str_replace(['(', ')', '"'], '', $_dv)));
            }
        }
    }
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$xtpl->assign('SELECTEDTAB', $selectedtab);
$xtpl->assign('CHECKSS', $checkss);

for ($i = 0; $i <= 2; ++$i) {
    $xtpl->assign('TAB' . $i . '_ACTIVE', $i == $selectedtab ? ' active' : '');
}

//csp_directive
$csp_directives = [
    'default-src' => $lang_module['csp_default_src'],
    'script-src' => $lang_module['csp_script_src'],
    'object-src' => $lang_module['csp_object_src'],
    'style-src' => $lang_module['csp_style_src'],
    'img-src' => $lang_module['csp_img_src'],
    'media-src' => $lang_module['csp_media_src'],
    'frame-src' => $lang_module['csp_frame_src'],
    'font-src' => $lang_module['csp_font_src'],
    'connect-src' => $lang_module['csp_connect_src'],
    'form-action' => $lang_module['csp_form_action'],
    'base-uri' => $lang_module['csp_base_uri']
];
foreach ($csp_directives as $name => $desc) {
    $direct = [
        'name' => $name,
        'desc' => $desc,
        'value' => !empty($directives[$name]) ? preg_replace("/[\s]+/", "\n", $directives[$name]) : ''
    ];
    $xtpl->assign('DIRECTIVE', $direct);
    $xtpl->assign('CSP_ACT', $global_config['nv_csp_act'] ? ' checked="checked"' : '');
    $xtpl->parse('main.csp_directive');
}

$xtpl->assign('RP', $global_config['nv_rp']);
$xtpl->assign('RP_ACT', $global_config['nv_rp_act'] ? ' checked="checked"' : '');
foreach ($_rp_directives as $name => $desc) {
    $rp_direct = [
        'name' => $name,
        'desc' => $desc
    ];
    $xtpl->assign('RP_DIRECTIVE', $rp_direct);
    $xtpl->parse('main.rp_directive');
}

$xtpl->assign('PP_ACT', empty($global_config['nv_pp_act']) ? '' : ' checked="checked"');
$xtpl->assign('FP_ACT', empty($global_config['nv_fp_act']) ? '' : ' checked="checked"');
foreach ($pp_directives as $name => $desc) {
    $pp_direct = [
        'name' => $name,
        'desc' => $desc,
        'value' => isset($pp_val_directives[$name]) ? nv_htmlspecialchars($pp_val_directives[$name]) : ''
    ];
    $xtpl->assign('DIRECTIVE', $pp_direct);
    $xtpl->parse('main.pp_directive');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['security'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
