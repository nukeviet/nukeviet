<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */

if (!defined('NV_IS_FILE_SETTINGS'))
    die('Stop!!!');

$page_title = $lang_module['global_config'];

$adminThemes = array('');
$adminThemes = array_merge($adminThemes, nv_scandir(NV_ROOTDIR . "/themes", $global_config['check_theme_admin']));
unset($adminThemes[0]);

$closed_site_Modes = array('0' => $lang_module['closed_site_0'], '1' => $lang_module['closed_site_1'], '2' => $lang_module['closed_site_2'], '3' => $lang_module['closed_site_3']);

$optActive_Modes = array('0' => $lang_module['optActive_no'], '1' => $lang_module['optActive_all'], '2' => $lang_module['optActive_site'], '3' => $lang_module['optActive_admin']);

$admin_theme = (isset($global_config['admin_theme']) and !empty($global_config['admin_theme']) and in_array($global_config['admin_theme'], $adminThemes)) ? $global_config['admin_theme'] : "admin_default";

$submit = $nv_Request->get_string('submit', 'post');

$errormess = "";
$array_config_global = $global_config;
$allow_sitelangs = array();
foreach ($global_config['allow_sitelangs'] as $lang_i)
{
    if (file_exists(NV_ROOTDIR . "/language/" . $lang_i . "/global.php"))
    {
        $allow_sitelangs[] = $lang_i;
    }
}

$proxy_blocker_array = array(//
    0 => $lang_module['proxy_blocker_0'], //
    1 => $lang_module['proxy_blocker_1'], //
    2 => $lang_module['proxy_blocker_2'], //
    3 => $lang_module['proxy_blocker_3']);
//

if ($submit)
{
    $array_config_global = array();

    $admin_theme = $nv_Request->get_string('admin_theme', 'post');
    if ($admin_theme and in_array($admin_theme, $adminThemes))
    {
        $array_config_global['admin_theme'] = $admin_theme;
    }

    $closed_site = $nv_Request->get_int('closed_site', 'post');
    if (isset($closed_site_Modes[$closed_site]))
    {
        $array_config_global['closed_site'] = $closed_site;
    }

    $array_config_global['gfx_chk'] = $nv_Request->get_int('gfx_chk', 'post');

    $array_config_global['site_email'] = filter_text_input('site_email', 'post', '', 1, 255);
    if (nv_check_valid_email($array_config_global['site_email']) != '')
    {
        $array_config_global['site_email'] = $global_config['site_email'];
    }
    $array_config_global['error_send_email'] = filter_text_input('error_send_email', 'post', '', 1, 255);
    if (nv_check_valid_email($array_config_global['error_send_email']) != '')
    {
        $array_config_global['error_send_email'] = $global_config['error_send_email'];
    }

    $array_config_global['site_phone'] = filter_text_input('site_phone', 'post', '', 1, 255);
    $array_config_global['site_lang'] = filter_text_input('site_lang', 'post', $global_config['site_lang'], 1, 255);
    if (!in_array($array_config_global['site_lang'], $allow_sitelangs))
    {
        $array_config_global['site_lang'] = 'vi';
    }

    $array_config_global['site_timezone'] = filter_text_input('site_timezone', 'post', '', 1, 255);
    $array_config_global['date_pattern'] = filter_text_input('date_pattern', 'post', '', 1, 255);
    $array_config_global['time_pattern'] = filter_text_input('time_pattern', 'post', '', 1, 255);
    $array_config_global['my_domains'] = filter_text_input('my_domains', 'post', '', 1, 255);

    $my_domains = array(NV_SERVER_NAME);
    if (!empty($array_config_global['my_domains']))
    {
        $array_config_global['my_domains'] = array_map("trim", explode(",", $array_config_global['my_domains']));
        foreach ($array_config_global['my_domains'] as $dm)
        {
            if (!empty($dm))
            {
                $dm2 = (!preg_match("/^(http|https|ftp|gopher)\:\/\//", $dm)) ? "http://" . $dm : $dm;
                if (nv_is_url($dm2) or $dm == "localhost")
                {
                    $my_domains[] = $dm;
                }
            }
        }
    }
    $my_domains = array_unique($my_domains);
    $array_config_global['my_domains'] = implode(",", $my_domains);

    $array_config_global['cookie_prefix'] = filter_text_input('cookie_prefix', 'post', '', 1, 255);
    $array_config_global['session_prefix'] = filter_text_input('session_prefix', 'post', '', 1, 255);
    $array_config_global['googleAnalyticsID'] = filter_text_input('googleAnalyticsID', 'post', '', 1, 20);
    if (!preg_match('/^UA-\d{4,}-\d+$/', $array_config_global['googleAnalyticsID']))
    {
        $array_config_global['googleAnalyticsID'] = "";
    }
    $array_config_global['googleAnalyticsSetDomainName'] = $nv_Request->get_int('googleAnalyticsSetDomainName', 'post');
    $array_config_global['searchEngineUniqueID'] = filter_text_input('searchEngineUniqueID', 'post', '');
    if (preg_match("/[^a-zA-Z0-9\:\-\_\.]/",$array_config_global['searchEngineUniqueID'])) $array_config_global['searchEngineUniqueID'] = "";

    $array_config_global['gzip_method'] = $nv_Request->get_int('gzip_method', 'post');
    $array_config_global['online_upd'] = $nv_Request->get_int('online_upd', 'post');
    $array_config_global['statistic'] = $nv_Request->get_int('statistic', 'post');
    $array_config_global['lang_multi'] = $nv_Request->get_int('lang_multi', 'post');
    $array_config_global['optActive'] = $nv_Request->get_int('optActive', 'post');
    $array_config_global['proxy_blocker'] = $nv_Request->get_int('proxy_blocker', 'post');
    $array_config_global['getloadavg'] = $nv_Request->get_int('getloadavg', 'post');

    if (!isset($proxy_blocker_array[$array_config_global['proxy_blocker']]))
    {
        $array_config_global['proxy_blocker'] = 0;
    }
    $array_config_global['str_referer_blocker'] = $nv_Request->get_int('str_referer_blocker', 'post');

    $array_config_global['is_url_rewrite'] = $nv_Request->get_int('is_url_rewrite', 'post', 0);
    if ($array_config_global['lang_multi'] == 0)
    {
        $array_config_global['rewrite_optional'] = $nv_Request->get_int('rewrite_optional', 'post', 0);
        $array_config_global['lang_geo'] = 0;

    }
    else
    {
        $array_config_global['rewrite_optional'] = 0;
        $array_config_global['lang_geo'] = $nv_Request->get_int('lang_geo', 'post', 0);
    }

    foreach ($array_config_global as $config_name => $config_value)
    {
        $db->sql_query("REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', '" . mysql_real_escape_string($config_name) . "', " . $db->dbescape($config_value) . ")");
    }

    nv_save_file_config_global();
    if (empty($errormess))
    {
        Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
        exit();
    }
    else
    {
        $sql = $db->constructQuery("SELECT `module`, `config_name`, `config_value` FROM `" . NV_CONFIG_GLOBALTABLE . "` 
        WHERE `lang`='" . NV_LANG_DATA . "' ORDER BY `module` ASC", NV_LANG_DATA);
        $result = $db->sql_query($sql);
        while (list($c_module, $c_config_name, $c_config_value) = $db->sql_fetchrow($result))
        {
            if ($c_module == "global")
            {
                $global_config[$c_config_name] = $c_config_value;
            }
            else
            {
                $module_config[$c_module][$c_config_name] = $c_config_value;
            }
        }
    }
}

$captcha_array = array(//
    0 => $lang_module['captcha_0'], //
    1 => $lang_module['captcha_1'], //
    2 => $lang_module['captcha_2'], //
    3 => $lang_module['captcha_3'], //
    4 => $lang_module['captcha_4'], //
    5 => $lang_module['captcha_5'], //
    6 => $lang_module['captcha_6'], //
    7 => $lang_module['captcha_7']	//
);

$array_config_global['gzip_method'] = ($global_config['gzip_method']) ? ' checked="checked"' : '';
$array_config_global['online_upd'] = ($global_config['online_upd']) ? ' checked="checked"' : '';
$array_config_global['statistic'] = ($global_config['statistic']) ? ' checked="checked"' : '';
$array_config_global['lang_multi'] = ($global_config['lang_multi']) ? ' checked="checked"' : '';
$array_config_global['str_referer_blocker'] = ($global_config['str_referer_blocker']) ? ' checked="checked"' : '';
$array_config_global['getloadavg'] = ($global_config['getloadavg']) ? ' checked="checked"' : '';
$array_config_global['my_domains'] = implode(",", $global_config['my_domains']);
$array_config_global['searchEngineUniqueID'] = isset($global_config['searchEngineUniqueID']) ? $global_config['searchEngineUniqueID'] : "";

$xtpl = new XTemplate("system.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file . "");
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('DATA', $array_config_global);

foreach ($adminThemes as $name)
{
    $xtpl->assign('THEME_NAME', $name);
    $xtpl->assign('THEME_SELECTED', ($name == $admin_theme ? " selected=\"selected\"" : ""));
    $xtpl->parse('main.admin_theme');
}

foreach ($closed_site_Modes as $value => $name)
{
    $xtpl->assign('MODE_VALUE', $value);
    $xtpl->assign('MODE_NAME', $name);
    $xtpl->assign('MODE_SELECTED', ($value == $global_config['closed_site'] ? " selected=\"selected\"" : ""));
    $xtpl->parse('main.closed_site_mode');
}

foreach ($captcha_array as $gfx_chk_i => $gfx_chk_lang)
{
    $xtpl->assign('GFX_CHK_SELECTED', ($global_config['gfx_chk'] == $gfx_chk_i) ? ' selected="selected"' : '');
    $xtpl->assign('GFX_CHK_VALUE', $gfx_chk_i);
    $xtpl->assign('GFX_CHK_TITLE', $gfx_chk_lang);
    $xtpl->parse('main.opcaptcha');
}

foreach ($proxy_blocker_array as $proxy_blocker_i => $proxy_blocker_v)
{
    $xtpl->assign('PROXYSELECTED', ($global_config['proxy_blocker'] == $proxy_blocker_i) ? ' selected="selected"' : '');
    $xtpl->assign('PROXYOP', $proxy_blocker_i);
    $xtpl->assign('PROXYVALUE', $proxy_blocker_v);
    $xtpl->parse('main.proxy_blocker');
}

$xtpl->assign('CHECKED1', ($global_config['is_url_rewrite'] == 1) ? ' checked ' : '');
if ($global_config['lang_multi'] == 0)
{
    $xtpl->assign('CHECKED2', ($global_config['rewrite_optional'] == 1) ? ' checked ' : '');
    $xtpl->parse('main.rewrite_optional');
}
if ($global_config['lang_multi'] and sizeof($global_config['allow_sitelangs']) > 1)
{
    $xtpl->assign('CONFIG_LANG_GEO', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=language&op&' . NV_OP_VARIABLE . '=countries');
    $xtpl->assign('CHECKED_LANG_GEO', ($global_config['lang_geo'] == 1) ? ' checked ' : '');

    foreach ($allow_sitelangs as $lang_i)
    {
        $xtpl->assign('LANGOP', $lang_i);
        $xtpl->assign('SELECTED', ($lang_i == $global_config['site_lang']) ? "selected='selected'" : "");
        $xtpl->assign('LANGVALUE', $language_array[$lang_i]['name']);
        $xtpl->parse('main.lang_multi.site_lang_option');
    }
    $xtpl->parse('main.lang_multi');
}
foreach ($optActive_Modes as $key => $value)
{
    $xtpl->assign('OPTACTIVE_OP', $key);
    $xtpl->assign('OPTACTIVE_SELECTED', ($key == $global_config['optActive']) ? "selected='selected'" : "");
    $xtpl->assign('OPTACTIVE_TEXT', $value);
    $xtpl->parse('main.optActive');
}

$timezone_array = array_keys(nv_parse_ini_file(NV_ROOTDIR . '/includes/ini/timezone.ini', true));

foreach ($timezone_array as $site_timezone_i)
{
    $xtpl->assign('TIMEZONEOP', $site_timezone_i);
    $xtpl->assign('TIMEZONESELECTED', ($site_timezone_i == $global_config['site_timezone']) ? "selected='selected'" : "");
    $xtpl->assign('TIMEZONELANGVALUE', $site_timezone_i);
    $xtpl->parse('main.opsite_timezone');
}

for ($i = 0; $i < 3; ++$i)
{
    $xtpl->assign('GOOGLEANALYTICSSETDOMAINNAME_SELECTED', ($global_config['googleAnalyticsSetDomainName'] == $i) ? ' selected="selected"' : '');
    $xtpl->assign('GOOGLEANALYTICSSETDOMAINNAME_VALUE', $i);
    $xtpl->assign('GOOGLEANALYTICSSETDOMAINNAME_TITLE', $lang_module['googleAnalyticsSetDomainName_' . $i]);
    $xtpl->parse('main.googleAnalyticsSetDomainName');
}

$xtpl->parse('main');

$content = "";
if ($errormess != "")
{
    $content .= "<div class=\"quote\" style=\"width:780px;\">\n";
    $content .= "<blockquote class=\"error\"><span>" . $errormess . "</span></blockquote>\n";
    $content .= "</div>\n";
    $content .= "<div class=\"clear\"></div>\n";
}
$content .= $xtpl->text('main');

include (NV_ROOTDIR . "/includes/header.php");
echo nv_admin_theme($content);
include (NV_ROOTDIR . "/includes/footer.php");
?>