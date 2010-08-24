<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Language English
 * @Createdate Aug 21, 2010, 02:43:51 PM
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) )
{
    die( 'Stop!!!' );
}

$lang_translator['author'] = "VINADES.,JSC (contact@vinades.vn)";
$lang_translator['createdate'] = "04/03/2010, 15:22";
$lang_translator['copyright'] = "@Copyright (C) 2010 VINADES.,JSC. All rights reserved";
$lang_translator['info'] = "";
$lang_translator['langtype'] = "lang_module";

$lang_module['global_config'] = "System Config";
$lang_module['lang_site_config'] = "Site language config";
$lang_module['bots_config'] = "Server search";
$lang_module['checkupdate'] = "Check version";
$lang_module['sitename'] = "Site name";
$lang_module['theme'] = "Default theme";
$lang_module['themeadmin'] = "Administrator theme";
$lang_module['default_module'] = "Default module";
$lang_module['description'] = "Site's description";
$lang_module['rewrite'] = "Rewrite Configuration";
$lang_module['rewrite_optional'] = "If enable Rewrite function then remove language characters on URL";
$lang_module['site_disable'] = "Site is disable";
$lang_module['disable_content'] = "Content";
$lang_module['submit'] = "Submit";
$lang_module['err_writable'] = "Error system can't write file %s. Please chmod or check server config!";
$lang_module['err_supports_rewrite'] = "Error, server doesn't support rewrite.";
$lang_module['captcha'] = "Captcha config";
$lang_module['captcha_0'] = "Hide";
$lang_module['captcha_1'] = "When admin login";
$lang_module['captcha_2'] = "When user login";
$lang_module['captcha_3'] = "When guest register";
$lang_module['captcha_4'] = "When user login or guest register";
$lang_module['captcha_5'] = "When admin or user login";
$lang_module['captcha_6'] = "When admin login or guest register";
$lang_module['captcha_7'] = "Display at all";
$lang_module['ftp_config'] = "FTP Config";
$lang_module['smtp_config'] = "SMTP Config";
$lang_module['server'] = "Server or Url";
$lang_module['port'] = "Port";
$lang_module['username'] = "Username";
$lang_module['password'] = "Password";
$lang_module['ftp_path'] = "Remote path";
$lang_module['mail_config'] = "Select mail server type";
$lang_module['type_smtp'] = "SMTP";
$lang_module['type_linux'] = "Linux Mail";
$lang_module['type_phpmail'] = "PHPmail";
$lang_module['smtp_server'] = "Server Information";
$lang_module['incoming_ssl'] = "This server requires an encrypted connection (SSL)";
$lang_module['outgoing'] = "Outgoing mail server (SMTP)";
$lang_module['outgoing_port'] = "Outgoing port server(SMTP)";
$lang_module['smtp_username'] = "Logon information";
$lang_module['smtp_login'] = "User Name";
$lang_module['smtp_pass'] = "Password";
$lang_module['update_error'] = "Error: The system does not check the information, Please check back at another time";
$lang_module['version_latest'] = "The current version is your latest";
$lang_module['version_no_latest'] = "Your version is not latest";
$lang_module['version_info'] = "Latest information";
$lang_module['version_name'] = "System Name";
$lang_module['version_number'] = "Version number";
$lang_module['version_date'] = "Release date";
$lang_module['version_note'] = "Notes on the new version";
$lang_module['version_download'] = "you can download the new version";
$lang_module['version_updatenew'] = "update new version";
$lang_module['bot_name'] = "Server's name";
$lang_module['bot_agent'] = "UserAgent";
$lang_module['bot_ips'] = "Server's IP";
$lang_module['bot_allowed'] = "Permission";
$lang_module['site_keywords'] = "Keywords";
$lang_module['site_logo'] = "Site's logo";
$lang_module['site_email'] = "Site's email";
$lang_module['error_send_email'] = "Error send mail";
$lang_module['site_phone'] = "Site's phone";
$lang_module['lang_multi'] = "Activate multi-language";
$lang_module['site_lang'] = "Default language";
$lang_module['site_timezone'] = "Site's timezone";
$lang_module['date_pattern'] = "Date display format";
$lang_module['time_pattern'] = "Time display format";
$lang_module['online_upd'] = "Activate monitoring online users";
$lang_module['gzip_method'] = "Activate gzip";
$lang_module['statistic'] = "Activate statistics";
$lang_module['proxy_blocker'] = "Block proxy";
$lang_module['proxy_blocker_0'] = "Don't check";
$lang_module['proxy_blocker_1'] = "Low";
$lang_module['proxy_blocker_2'] = "Medium";
$lang_module['proxy_blocker_3'] = "High";
$lang_module['str_referer_blocker'] = "Activate block referers";
$lang_module['my_domains'] = "Domains";
$lang_module['cookie_prefix'] = "Cookie prefix";
$lang_module['session_prefix'] = "Session's prefix";
$lang_module['is_user_forum'] = "Switch users management to forum";
$lang_module['banip'] = "BanIP Manager";
$lang_module['banip_ip'] = "Ip address";
$lang_module['banip_timeban'] = "Ban begin time";
$lang_module['banip_timeendban'] = "Ban end time";
$lang_module['banip_funcs'] = "Feature";
$lang_module['banip_checkall'] = "Check all";
$lang_module['banip_uncheckall'] = "Uncheck all";
$lang_module['banip_add'] = "Add new";
$lang_module['banip_address'] = "Address";
$lang_module['banip_begintime'] = "Begin time";
$lang_module['banip_endtime'] = "End time";
$lang_module['banip_notice'] = "Notice";
$lang_module['banip_confirm'] = "Confirm";
$lang_module['banip_mask_select'] = "Please select one";
$lang_module['banip_area'] = "Area";
$lang_module['banip_nolimit'] = "Unlimit time";
$lang_module['banip_area_select'] = "Please select an area";
$lang_module['banip_noarea'] = "No defined";
$lang_module['banip_del_success'] = "Delete successful !";
$lang_module['banip_area_front'] = "Frontsite";
$lang_module['banip_area_admin'] = "Admin area";
$lang_module['banip_area_both'] = "Both frontsite and admin area";
$lang_module['banip_delete_confirm'] = "Are you sure to remove this ip from ban list ?";
$lang_module['banip_mask'] = "Mask IP";
$lang_module['banip_edit'] = "Edit";
$lang_module['banip_delete'] = "Delete";
$lang_module['banip_error_ip'] = "Please enter ip address want to ban";
$lang_module['banip_error_area'] = "Please select an area";
$lang_module['banip_error_validip'] = "Error: Please enter a valid Ip address";
$lang_module['uploadconfig'] = "Upload Config";
$lang_module['uploadconfig_ban_ext'] = "Forbid Extensions";
$lang_module['uploadconfig_ban_mime'] = "Forbid Mimies";
$lang_module['uploadconfig_types'] = "File types allowed";
$lang_module['nv_max_size'] = "upload max site";
$lang_module['sys_max_size'] = "Your server only allows upload maximum";

?>