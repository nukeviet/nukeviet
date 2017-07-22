<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    die('Stop!!!');
}

/**
 * nv_save_file_banip()
 *
 * @return
 */
function nv_save_file_banip()
{
    global $db, $db_config;

    $content_config_site = '';
    $content_config_admin = '';

    $result = $db->query('SELECT ip, mask, area, begintime, endtime FROM ' . $db_config['prefix'] . '_banip');
    while (list($dbip, $dbmask, $dbarea, $dbbegintime, $dbendtime) = $result->fetch(3)) {
        $dbendtime = intval($dbendtime);
        $dbarea = intval($dbarea);

        if ($dbendtime == 0 or $dbendtime > NV_CURRENTTIME) {
            switch ($dbmask) {
                case 3:
                    $ip_mask = '/\.[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}$/';
                    break;
                case 2:
                    $ip_mask = '/\.[0-9]{1,3}.[0-9]{1,3}$/';
                    break;
                case 1:
                    $ip_mask = '/\.[0-9]{1,3}$/';
                    break;
                default:
                    $ip_mask = '//';
            }

            if ($dbarea == 1 or $dbarea == 3) {
                $content_config_site .= "\$array_banip_site['" . $dbip . "'] = array( 'mask' => \"" . $ip_mask . "\", 'begintime' => " . $dbbegintime . ", 'endtime' => " . $dbendtime . " );\n";
            }

            if ($dbarea == 2 or $dbarea == 3) {
                $content_config_admin .= "\$array_banip_admin['" . $dbip . "'] = array( 'mask' => \"" . $ip_mask . "\", 'begintime' => " . $dbbegintime . ", 'endtime' => " . $dbendtime . " );\n";
            }
        }
    }

    if (!$content_config_site and !$content_config_admin) {
        nv_deletefile(NV_ROOTDIR . '/' . NV_DATADIR . '/banip.php');
        return true;
    }

    $content_config = "<?php\n\n";
    $content_config .= NV_FILEHEAD . "\n\n";
    $content_config .= "if (!defined('NV_MAINFILE'))\n    die('Stop!!!');\n\n";
    $content_config .= "\$array_banip_site = array();\n";
    $content_config .= $content_config_site;
    $content_config .= "\n";
    $content_config .= "\$array_banip_admin = array();\n";
    $content_config .= $content_config_admin;

    $write = file_put_contents(NV_ROOTDIR . '/' . NV_DATADIR . '/banip.php', $content_config, LOCK_EX);

    if ($write === false) {
        return $content_config;
    }

    return true;
}

$proxy_blocker_array = array(
    0 => $lang_module['proxy_blocker_0'],
    1 => $lang_module['proxy_blocker_1'],
    2 => $lang_module['proxy_blocker_2'],
    3 => $lang_module['proxy_blocker_3']
);

$captcha_array = array(
    0 => $lang_module['captcha_0'],
    1 => $lang_module['captcha_1'],
    2 => $lang_module['captcha_2'],
    3 => $lang_module['captcha_3'],
    4 => $lang_module['captcha_4'],
    5 => $lang_module['captcha_5'],
    6 => $lang_module['captcha_6'],
    7 => $lang_module['captcha_7']
);

$captcha_type_array = array(0 => $lang_module['captcha_type_0'], 1 => $lang_module['captcha_type_1'], 2 => $lang_module['captcha_type_2']);
$recaptcha_type_array = array('image' => $lang_module['recaptcha_type_image'], 'audio' => $lang_module['recaptcha_type_audio']);

$errormess = '';
if ($nv_Request->isset_request('submitcaptcha', 'post')) {
    $array_config_global = array();

    $proxy_blocker = $nv_Request->get_int('proxy_blocker', 'post');
    if (isset($proxy_blocker_array[$proxy_blocker])) {
        $array_config_global['proxy_blocker'] = $proxy_blocker;
    }

    $gfx_chk = $nv_Request->get_int('gfx_chk', 'post');
    if (isset($captcha_array[$gfx_chk])) {
        $array_config_global['gfx_chk'] = $gfx_chk;
    } else {
        $array_config_global['gfx_chk'] = 0;
    }
    $captcha_type = $nv_Request->get_int('captcha_type', 'post');
    if (isset($captcha_type_array[$captcha_type])) {
        $array_config_global['captcha_type'] = $captcha_type;
    } else {
        $array_config_global['captcha_type'] = $captcha_type_array[0];
    }
    $array_config_global['str_referer_blocker'] = (int)$nv_Request->get_bool('str_referer_blocker', 'post');
    $array_config_global['is_flood_blocker'] = (int)$nv_Request->get_bool('is_flood_blocker', 'post');
    $array_config_global['max_requests_60'] = $nv_Request->get_int('max_requests_60', 'post');
    $array_config_global['max_requests_300'] = $nv_Request->get_int('max_requests_300', 'post');
    $array_config_global['is_login_blocker'] = (int)$nv_Request->get_bool('is_login_blocker', 'post', false);
    $array_config_global['login_number_tracking'] = $nv_Request->get_int('login_number_tracking', 'post', 0);
    $array_config_global['login_time_tracking'] = $nv_Request->get_int('login_time_tracking', 'post', 0);
    $array_config_global['login_time_ban'] = $nv_Request->get_int('login_time_ban', 'post', 0);
    $array_config_global['two_step_verification'] = $nv_Request->get_int('two_step_verification', 'post', 0);
    $array_config_global['recaptcha_sitekey'] = $nv_Request->get_title('recaptcha_sitekey', 'post', '');
    $array_config_global['recaptcha_secretkey'] = $nv_Request->get_title('recaptcha_secretkey', 'post', '');
    $array_config_global['recaptcha_type'] = $nv_Request->get_title('recaptcha_type', 'post', '');

    if ($array_config_global['login_number_tracking'] < 1) {
        $array_config_global['login_number_tracking'] = 5;
    }
    if ($array_config_global['login_time_tracking'] <= 0) {
        $array_config_global['login_time_tracking'] = 5;
    }
    if ($array_config_global['two_step_verification'] < 0 or $array_config_global['two_step_verification'] > 3) {
        $array_config_global['two_step_verification'] = 0;
    }

    if ($array_config_global['captcha_type'] == 2 and (empty($array_config_global['recaptcha_sitekey']) or empty($array_config_global['recaptcha_secretkey']))) {
        $array_config_global['captcha_type'] = 0;
    }
    if (!isset($recaptcha_type_array[$array_config_global['recaptcha_type']])) {
        $array_config_global['recaptcha_type'] = array_keys($array_config_global['recaptcha_type']);
        $array_config_global['recaptcha_type'] = $array_config_global['recaptcha_type'][0];
    }
    if (!empty($array_config_global['recaptcha_secretkey'])) {
        $array_config_global['recaptcha_secretkey'] = $crypt->encrypt($array_config_global['recaptcha_secretkey']);
    }

    $sth = $db->prepare("UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'global' AND config_name = :config_name");
    foreach ($array_config_global as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    $array_config_define = array();
    $array_config_define['nv_anti_agent'] = (int)$nv_Request->get_bool('nv_anti_agent', 'post');
    $array_config_define['nv_gfx_num'] = $nv_Request->get_int('nv_gfx_num', 'post');
    $array_config_define['nv_gfx_width'] = $nv_Request->get_int('nv_gfx_width', 'post');
    $array_config_define['nv_gfx_height'] = $nv_Request->get_int('nv_gfx_height', 'post');
    $array_config_define['nv_anti_iframe'] = (int)$nv_Request->get_bool('nv_anti_iframe', 'post');
    $variable = $nv_Request->get_string('nv_allowed_html_tags', 'post');
    $variable = str_replace(';', ',', strtolower($variable));
    $variable = explode(',', $variable);
    $nv_allowed_html_tags = array();
    foreach ($variable as $value) {
        $value = trim($value);
        if (preg_match('/^[a-z0-9]+$/', $value) and !in_array($value, $nv_allowed_html_tags)) {
            $nv_allowed_html_tags[] = $value;
        }
    }
    $array_config_define['nv_allowed_html_tags'] = implode(', ', $nv_allowed_html_tags);

    $sth = $db->prepare("UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'define' AND config_name = :config_name");
    foreach ($array_config_define as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    nv_save_file_config_global();
    $save_config = nv_server_config_change($array_config_define);

    if ($save_config[0] !== true) {
        $errormess = sprintf($lang_module['err_save_sysconfig'], $save_config[1]);
    }

    if (empty($errormess)) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
    }
} else {
    $array_config_global = $global_config;
    $array_config_define = array();
    $array_config_define['nv_anti_agent'] = NV_ANTI_AGENT;
    $array_config_define['nv_gfx_num'] = NV_GFX_NUM;
    $array_config_define['nv_gfx_width'] = NV_GFX_WIDTH;
    $array_config_define['nv_gfx_height'] = NV_GFX_HEIGHT;
    $array_config_define['nv_anti_iframe'] = NV_ANTI_IFRAME;
    $array_config_define['nv_allowed_html_tags'] = NV_ALLOWED_HTML_TAGS;
}

$lang_module['two_step_verification_note'] = sprintf($lang_module['two_step_verification_note'], $lang_module['two_step_verification0'], NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=groups');

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);

$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);

$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);

$error = array();
$contents = '';

$cid = $nv_Request->get_int('id', 'get');
$del = $nv_Request->get_int('del', 'get');

if (!empty($del) and !empty($cid)) {
    $db->query('DELETE FROM ' . $db_config['prefix'] . '_banip WHERE id=' . $cid);
    nv_save_file_banip();
}

if ($nv_Request->isset_request('submit', 'post')) {
    $cid = $nv_Request->get_int('cid', 'post', 0);
    $ip = $nv_Request->get_title('ip', 'post', '', 1);
    $area = $nv_Request->get_int('area', 'post', 0);
    $mask = $nv_Request->get_int('mask', 'post', 0);

    if (empty($ip) or !$ips->nv_validip($ip)) {
        $error[] = $lang_module['banip_error_validip'];
    }

    if (empty($area)) {
        $error[] = $lang_module['banip_error_area'];
    }

    if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string('begintime', 'post'), $m)) {
        $begintime = mktime(0, 0, 0, $m[2], $m[1], $m[3]);
    } else {
        $begintime = NV_CURRENTTIME;
    }

    if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string('endtime', 'post'), $m)) {
        $endtime = mktime(0, 0, 0, $m[2], $m[1], $m[3]);
    } else {
        $endtime = 0;
    }

    $notice = $nv_Request->get_title('notice', 'post', '', 1);

    if (empty($error)) {
        if ($cid > 0) {
            $sth = $db->prepare('UPDATE ' . $db_config['prefix'] . '_banip
				SET ip= :ip, mask= :mask,area=' . $area . ', begintime=' . $begintime . ', endtime=' . $endtime . ', notice= :notice
				WHERE id=' . $cid);
            $sth->bindParam(':ip', $ip, PDO::PARAM_STR);
            $sth->bindParam(':mask', $mask, PDO::PARAM_STR);
            $sth->bindParam(':notice', $notice, PDO::PARAM_STR);
            $sth->execute();
        } else {
            $result = $db->query('DELETE FROM ' . $db_config['prefix'] . '_banip WHERE ip=' . $db->quote($ip));
            if ($result) {
                $sth = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_banip ( ip, mask, area, begintime, endtime, notice) VALUES ( :ip, :mask, ' . $area . ', ' . $begintime . ', ' . $endtime . ', :notice )');
                $sth->bindParam(':ip', $ip, PDO::PARAM_STR);
                $sth->bindParam(':mask', $mask, PDO::PARAM_STR);
                $sth->bindParam(':notice', $notice, PDO::PARAM_STR);
                $sth->execute();
            }
        }

        $save = nv_save_file_banip();

        if ($save !== true) {
            $xtpl->assign('MESSAGE', sprintf($lang_module['banip_error_write'], NV_DATADIR, NV_DATADIR));
            $xtpl->assign('CODE', str_replace(array('\n', '\t'), array("<br />", "&nbsp;&nbsp;&nbsp;&nbsp;"), nv_htmlspecialchars($save)));
            $xtpl->parse('main.manual_save');
        } else {
            nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
        }
    } else {
        $xtpl->assign('ERROR', implode('<br/>', $error));
        $xtpl->parse('main.error');
    }
} else {
    $id = $ip = $mask = $area = $begintime = $endtime = $notice = '';
}

if (!empty($errormess)) {
    $xtpl->assign('ERROR_SAVE', $errormess);
    $xtpl->parse('main.error_save');
}

$xtpl->assign('ANTI_AGENT', $array_config_define['nv_anti_agent'] ? ' checked="checked"' : '');
foreach ($proxy_blocker_array as $proxy_blocker_i => $proxy_blocker_v) {
    $xtpl->assign('PROXYSELECTED', ($array_config_global['proxy_blocker'] == $proxy_blocker_i) ? ' selected="selected"' : '');
    $xtpl->assign('PROXYOP', $proxy_blocker_i);
    $xtpl->assign('PROXYVALUE', $proxy_blocker_v);
    $xtpl->parse('main.proxy_blocker');
}
$xtpl->assign('REFERER_BLOCKER', ($array_config_global['str_referer_blocker']) ? ' checked="checked"' : '');
$xtpl->assign('IS_FLOOD_BLOCKER', ($array_config_global['is_flood_blocker']) ? ' checked="checked"' : '');
$xtpl->assign('MAX_REQUESTS_60', $array_config_global['max_requests_60']);
$xtpl->assign('MAX_REQUESTS_300', $array_config_global['max_requests_300']);
$xtpl->assign('ANTI_IFRAME', $array_config_define['nv_anti_iframe'] ? ' checked="checked"' : '');

$xtpl->assign('IS_LOGIN_BLOCKER', ($array_config_global['is_login_blocker']) ? ' checked="checked"' : '');
$xtpl->assign('LOGIN_NUMBER_TRACKING', $array_config_global['login_number_tracking']);
$xtpl->assign('LOGIN_TIME_TRACKING', $array_config_global['login_time_tracking']);
$xtpl->assign('LOGIN_TIME_BAN', $array_config_global['login_time_ban']);

foreach ($captcha_array as $gfx_chk_i => $gfx_chk_lang) {
    $array = array(
        "value" => $gfx_chk_i,
        "select" => ($array_config_global['gfx_chk'] == $gfx_chk_i) ? ' selected="selected"' : '',
        "text" => $gfx_chk_lang
    );
    $xtpl->assign('OPTION', $array);
    $xtpl->parse('main.opcaptcha');
}

foreach ($captcha_type_array as $captcha_type_i => $captcha_type_lang) {
    $array = array(
        'value' => $captcha_type_i,
        'select' => ($array_config_global['captcha_type'] == $captcha_type_i) ? ' selected="selected"' : '',
        'text' => $captcha_type_lang
    );
    $xtpl->assign('OPTION', $array);
    $xtpl->parse('main.captcha_type');
}

$xtpl->assign('RECAPTCHA_SITEKEY', $array_config_global['recaptcha_sitekey']);
$xtpl->assign('RECAPTCHA_SECRETKEY', $array_config_global['recaptcha_secretkey'] ? $crypt->decrypt($array_config_global['recaptcha_secretkey']) : '');

$xtpl->assign('DISPLAY_CAPTCHA_BASIC', ($array_config_global['captcha_type'] == 2) ? ' style="display:none;"' : '');
$xtpl->assign('DISPLAY_CAPTCHA_RECAPTCHA', ($array_config_global['captcha_type'] == 2) ? '' : ' style="display:none;"');

foreach ($recaptcha_type_array as $recaptcha_type_key => $recaptcha_type_title) {
    $array = array(
        'value' => $recaptcha_type_key,
        'select' => ($array_config_global['recaptcha_type'] == $recaptcha_type_key) ? ' selected="selected"' : '',
        'text' => $recaptcha_type_title
    );
    $xtpl->assign('RECAPTCHA_TYPE', $array);
    $xtpl->parse('main.recaptcha_type');
}

for ($i = 2; $i < 10; $i++) {
    $array = array(
        'value' => $i,
        'select' => ($i == $array_config_define['nv_gfx_num']) ? ' selected="selected"' : '',
        'text' => $i
    );
    $xtpl->assign('OPTION', $array);
    $xtpl->parse('main.nv_gfx_num');
}
$xtpl->assign('NV_GFX_WIDTH', $array_config_define['nv_gfx_width']);
$xtpl->assign('NV_GFX_HEIGHT', $array_config_define['nv_gfx_height']);
$xtpl->assign('NV_ALLOWED_HTML_TAGS', $array_config_define['nv_allowed_html_tags']);

$mask_text_array = array();
$mask_text_array[0] = '255.255.255.255';
$mask_text_array[3] = '255.255.255.xxx';
$mask_text_array[2] = '255.255.xxx.xxx';
$mask_text_array[1] = '255.xxx.xxx.xxx';

$banip_area_array = array();
$banip_area_array[0] = $lang_module['banip_area_select'];
$banip_area_array[1] = $lang_module['banip_area_front'];
$banip_area_array[2] = $lang_module['banip_area_admin'];
$banip_area_array[3] = $lang_module['banip_area_both'];

$sql = 'SELECT id, ip, mask, area, begintime, endtime FROM ' . $db_config['prefix'] . '_banip ORDER BY ip DESC';
$result = $db->query($sql);
$i = 0;
while (list($dbid, $dbip, $dbmask, $dbarea, $dbbegintime, $dbendtime) = $result->fetch(3)) {
    ++$i;
    $xtpl->assign('ROW', array(
        'dbip' => $dbip,
        'dbmask' => $mask_text_array[$dbmask],
        'dbarea' => $banip_area_array[$dbarea],
        'dbbegintime' => !empty($dbbegintime) ? date('d/m/Y', $dbbegintime) : '',
        'dbendtime' => !empty($dbendtime) ? date('d/m/Y', $dbendtime) : $lang_module['banip_nolimit'],
        'url_edit' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $dbid,
        'url_delete' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;del=1&amp;id=' . $dbid
    ));

    $xtpl->parse('main.listip.loop');
}
if ($i) {
    $xtpl->parse('main.listip');
}

if (!empty($cid)) {
    list($id, $ip, $mask, $area, $begintime, $endtime, $notice) = $db->query('SELECT id, ip, mask, area, begintime, endtime, notice FROM ' . $db_config['prefix'] . '_banip WHERE id=' . $cid)->fetch(3);
    $lang_module['banip_add'] = $lang_module['banip_edit'];
}

$xtpl->assign('MASK_TEXT_ARRAY', $mask_text_array);
$xtpl->assign('BANIP_AREA_ARRAY', $banip_area_array);
$xtpl->assign('BANIP_TITLE', ($cid) ? $lang_module['banip_title_edit'] : $lang_module['banip_title_add']);

$xtpl->assign('DATA', array(
    'cid' => $cid,
    'ip' => $ip,
    'selected3' => ($mask == 3) ? ' selected="selected"' : '',
    'selected2' => ($mask == 2) ? ' selected="selected"' : '',
    'selected1' => ($mask == 1) ? ' selected="selected"' : '',
    'selected_area_1' => ($area == 1) ? ' selected="selected"' : '',
    'selected_area_2' => ($area == 2) ? ' selected="selected"' : '',
    'selected_area_3' => ($area == 3) ? ' selected="selected"' : '',
    'begintime' => !empty($begintime) ? date('d/m/Y', $begintime) : '',
    'endtime' => !empty($endtime) ? date('d/m/Y', $endtime) : '',
    'endtime' => $notice
));

for ($i = 0; $i <= 3; $i++) {
    $two_step_verification = array(
        'key' => $i,
        'title' => $lang_module['two_step_verification' . $i],
        'selected' => $i == $array_config_global['two_step_verification'] ? ' selected="selected"' : ''
    );
    $xtpl->assign('TWO_STEP_VERIFICATION', $two_step_verification);
    $xtpl->parse('main.two_step_verification');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['security'];
include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
