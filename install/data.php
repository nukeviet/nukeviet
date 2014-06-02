<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/28/2009 20:8
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$sql_create_table[] = "INSERT INTO " . NV_AUTHORS_GLOBALTABLE . "_module (mid, module, lang_key, weight, act_1, act_2, act_3, checksum) VALUES (1, 'siteinfo', 'mod_siteinfo', 1, 1, 1, 1, '')";
$sql_create_table[] = "INSERT INTO " . NV_AUTHORS_GLOBALTABLE . "_module (mid, module, lang_key, weight, act_1, act_2, act_3, checksum) VALUES (2, 'authors', 'mod_authors', 2, 1, 1, 1, '')";
$sql_create_table[] = "INSERT INTO " . NV_AUTHORS_GLOBALTABLE . "_module (mid, module, lang_key, weight, act_1, act_2, act_3, checksum) VALUES (3, 'settings', 'mod_settings', 3, 1, 1, 0, '')";
$sql_create_table[] = "INSERT INTO " . NV_AUTHORS_GLOBALTABLE . "_module (mid, module, lang_key, weight, act_1, act_2, act_3, checksum) VALUES (4, 'database', 'mod_database', 4, 1, 0, 0, '')";
$sql_create_table[] = "INSERT INTO " . NV_AUTHORS_GLOBALTABLE . "_module (mid, module, lang_key, weight, act_1, act_2, act_3, checksum) VALUES (5, 'webtools', 'mod_webtools', 5, 1, 0, 0, '')";
$sql_create_table[] = "INSERT INTO " . NV_AUTHORS_GLOBALTABLE . "_module (mid, module, lang_key, weight, act_1, act_2, act_3, checksum) VALUES (6, 'seotools', 'mod_seotools', 6, 1, 0, 0, '')";
$sql_create_table[] = "INSERT INTO " . NV_AUTHORS_GLOBALTABLE . "_module (mid, module, lang_key, weight, act_1, act_2, act_3, checksum) VALUES (7, 'language', 'mod_language', 7, 1, 1, 0, '')";
$sql_create_table[] = "INSERT INTO " . NV_AUTHORS_GLOBALTABLE . "_module (mid, module, lang_key, weight, act_1, act_2, act_3, checksum) VALUES (8, 'modules', 'mod_modules', 8, 1, 1, 0, '')";
$sql_create_table[] = "INSERT INTO " . NV_AUTHORS_GLOBALTABLE . "_module (mid, module, lang_key, weight, act_1, act_2, act_3, checksum) VALUES (9, 'themes', 'mod_themes', 9, 1, 1, 0, '')";
$sql_create_table[] = "INSERT INTO " . NV_AUTHORS_GLOBALTABLE . "_module (mid, module, lang_key, weight, act_1, act_2, act_3, checksum) VALUES (10, 'extensions', 'mod_extensions', 10, 1, 0, 0, '')";
$sql_create_table[] = "INSERT INTO " . NV_AUTHORS_GLOBALTABLE . "_module (mid, module, lang_key, weight, act_1, act_2, act_3, checksum) VALUES (11, 'upload', 'mod_upload', 11, 1, 1, 1, '')";

$sql_create_table[] = "INSERT INTO " . NV_GROUPS_GLOBALTABLE . " (group_id, title, content, add_time, exp_time, publics, weight, act, idsite, numbers, siteus) VALUES (6, 'All', '', " . NV_CURRENTTIME . ", 0, 0, 1, 1, 0, 0, 0)";
$sql_create_table[] = "INSERT INTO " . NV_GROUPS_GLOBALTABLE . " (group_id, title, content, add_time, exp_time, publics, weight, act, idsite, numbers, siteus) VALUES (5, 'Guest', '', " . NV_CURRENTTIME . ", 0, 0, 2, 1, 0, 0, 0)";
$sql_create_table[] = "INSERT INTO " . NV_GROUPS_GLOBALTABLE . " (group_id, title, content, add_time, exp_time, publics, weight, act, idsite, numbers, siteus) VALUES (4, 'Users', '', " . NV_CURRENTTIME . ", 0, 0, 3, 1, 0, 1, 0)";
$sql_create_table[] = "INSERT INTO " . NV_GROUPS_GLOBALTABLE . " (group_id, title, content, add_time, exp_time, publics, weight, act, idsite, numbers, siteus) VALUES (1, 'Super admin', '', " . NV_CURRENTTIME . ", 0, 0, 4, 1, 0, 1, 0)";
$sql_create_table[] = "INSERT INTO " . NV_GROUPS_GLOBALTABLE . " (group_id, title, content, add_time, exp_time, publics, weight, act, idsite, numbers, siteus) VALUES (2, 'General admin', '', " . NV_CURRENTTIME . ", 0, 0, 5, 1, 0, 0, 0)";
$sql_create_table[] = "INSERT INTO " . NV_GROUPS_GLOBALTABLE . " (group_id, title, content, add_time, exp_time, publics, weight, act, idsite, numbers, siteus) VALUES (3, 'Module admin', '', " . NV_CURRENTTIME . ", 0, 0, 6, 1, 0, 0, 0)";

$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_upload_dir (did, dirname, time, thumb_type, thumb_width, thumb_height, thumb_quality) VALUES ('-1', '', 0, 3, 100, 150, 90)";
$sql_create_table[] = "UPDATE " . $db_config['prefix'] . "_upload_dir SET did = '0' WHERE did = '-1'";

$sql_create_table[] = "INSERT INTO " . NV_USERS_GLOBALTABLE . "_config (config, content, edit_time) VALUES ('access_admin', 'a:6:{s:12:\"access_addus\";a:3:{i:1;b:1;i:2;b:1;i:3;b:1;}s:14:\"access_waiting\";a:3:{i:1;b:1;i:2;b:1;i:3;b:1;}s:13:\"access_editus\";a:3:{i:1;b:1;i:2;b:1;i:3;b:1;}s:12:\"access_delus\";a:3:{i:1;b:1;i:2;b:1;i:3;b:1;}s:13:\"access_passus\";a:3:{i:1;b:1;i:2;b:1;i:3;b:1;}s:13:\"access_groups\";a:3:{i:1;b:1;i:2;b:1;i:3;b:1;}}', 1352873462)";
$sql_create_table[] = "INSERT INTO " . NV_USERS_GLOBALTABLE . "_config (config, content, edit_time) VALUES ('password_simple', '000000|1234|2000|12345|111111|123123|123456|654321|696969|1234567|12345678|123456789|1234567890|aaaaaa|abc123|abc123@|abc@123|adobe1|adobe123|azerty|baseball|dragon|football|harley|iloveyou|jennifer|jordan|letmein|macromedia|master|michael|monkey|mustang|password|photoshop|pussy|qwerty|shadow|superman', " . NV_CURRENTTIME . ")";
$sql_create_table[] = "INSERT INTO " . NV_USERS_GLOBALTABLE . "_config (config, content, edit_time) VALUES ('deny_email', 'yoursite.com|mysite.com|localhost|xxx', " . NV_CURRENTTIME . ")";
$sql_create_table[] = "INSERT INTO " . NV_USERS_GLOBALTABLE . "_config (config, content, edit_time) VALUES ('deny_name', 'anonimo|anonymous|god|linux|nobody|operator|root', " . NV_CURRENTTIME . ")";
$sql_create_table[] = "INSERT INTO " . NV_USERS_GLOBALTABLE . "_config (config, content, edit_time) VALUES ('avatar_width', 80, " . NV_CURRENTTIME . ")";
$sql_create_table[] = "INSERT INTO " . NV_USERS_GLOBALTABLE . "_config (config, content, edit_time) VALUES ('avatar_height', 80, " . NV_CURRENTTIME . ")";

$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'closed_site', '0')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'admin_theme', 'admin_default')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'date_pattern', 'l, d/m/Y')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'time_pattern', 'H:i')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'online_upd', '1')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'statistic', '1')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'mailer_mode', '')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'smtp_host', 'smtp.gmail.com')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'smtp_ssl', '1')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'smtp_port', '465')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'smtp_username', 'user@gmail.com')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'smtp_password', '')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'googleAnalyticsID', '')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'googleAnalyticsSetDomainName', '0')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'googleAnalyticsMethod', 'classic')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'searchEngineUniqueID', '')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'metaTagsOgp', '1')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'site_keywords', 'NukeViet, portal, mysql, php')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'site_phone', '')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'block_admin_ip', '0')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'admfirewall', '0')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'dump_autobackup', '1')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'dump_backup_ext', 'gz')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'dump_backup_day', '30')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'gfx_chk', '" . $global_config['gfx_chk'] . "')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'file_allowed_ext', 'adobe,archives,audio,documents,flash,images,real,video')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'forbid_extensions', 'php,php3,php4,php5,phtml,inc')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'forbid_mimes', '')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'nv_max_size', '" . min( nv_converttoBytes( ini_get( 'upload_max_filesize' ) ), nv_converttoBytes( ini_get( 'post_max_size' ) ) ) . "')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'upload_checking_mode', 'strong')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'allowuserreg', '1')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'allowuserlogin', '1')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'allowloginchange', '0')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'allowquestion', '0')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'allowuserpublic', '0')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'useactivate', '2')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'allowmailchange', '1')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'allow_sitelangs', '" . NV_LANG_DATA . "')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'allow_adminlangs', '" . implode( ',', $languageslist ) . "')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'read_type', '0')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'rewrite_optional', '" . $global_config['rewrite_optional'] . "')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'rewrite_endurl', '" . $global_config['rewrite_endurl'] . "')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'rewrite_exturl', '" . $global_config['rewrite_exturl'] . "')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'rewrite_op_mod', '')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'autocheckupdate', '1')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'autoupdatetime', '24')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'gzip_method', '" . $global_config['gzip_method'] . "')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'is_user_forum', '0')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'openid_mode', '1')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'authors_detail_main', '0')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'spadmin_add_admin', '1')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'openid_servers', 'yahoo,google,myopenid')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'optActive', '1')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'timestamp', '1')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'mudim_displaymode', '1')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'mudim_method', '4')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'mudim_showpanel', '1')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'mudim_active', '1')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'captcha_type', '0')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'version', '" . $global_config['version'] . "')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'whoviewuser', '2')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'facebook_client_id', '')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'facebook_client_secret', '')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'cookie_httponly', '" . $global_config['cookie_httponly'] . "')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'admin_check_pass_time', '1800')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'adminrelogin_max', '3')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'cookie_secure', '" . $global_config['cookie_secure'] . "')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'nv_unick_type', '" . $global_config['nv_unick_type'] . "')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'nv_upass_type', '" . $global_config['nv_upass_type'] . "')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'is_flood_blocker', '1')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'max_requests_60', '40')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'max_requests_300', '150')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'nv_display_errors_list', '1')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'display_errors_list', '1')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'nv_auto_resize', '1')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'dump_interval', '1')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'cdn_url', '')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'define', 'nv_unickmin', '" . NV_UNICKMIN . "')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'define', 'nv_unickmax', '" . NV_UNICKMAX . "')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'define', 'nv_upassmin', '" . NV_UPASSMIN . "')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'define', 'nv_upassmax', '" . NV_UPASSMAX . "')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'define', 'nv_gfx_num', '6')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'define', 'nv_gfx_width', '120')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'define', 'nv_gfx_height', '25')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'define', 'nv_max_width', '1500')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'define', 'nv_max_height', '1500')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'define', 'nv_live_cookie_time', '" . NV_LIVE_COOKIE_TIME . "')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'define', 'nv_live_session_time', '0')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'define', 'nv_anti_iframe', '" . NV_ANTI_IFRAME . "')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'define', 'nv_anti_agent', '" . NV_ANTI_AGENT . "')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'define', 'nv_allowed_html_tags', '" . NV_ALLOWED_HTML_TAGS . "')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'define', 'dir_forum', '')";

$sql_create_table[] = "INSERT INTO " . NV_CRONJOBS_GLOBALTABLE . " ( start_time, inter_val, run_file, run_func, params, del, is_sys, act, last_time, last_result) VALUES (" . NV_CURRENTTIME . ", 5, 'online_expired_del.php', 'cron_online_expired_del', '', 0, 1, 1, 0, 0)";
$sql_create_table[] = "INSERT INTO " . NV_CRONJOBS_GLOBALTABLE . " ( start_time, inter_val, run_file, run_func, params, del, is_sys, act, last_time, last_result) VALUES (" . NV_CURRENTTIME . ", 1440, 'dump_autobackup.php', 'cron_dump_autobackup', '', 0, 1, 1, 0, 0)";
$sql_create_table[] = "INSERT INTO " . NV_CRONJOBS_GLOBALTABLE . " ( start_time, inter_val, run_file, run_func, params, del, is_sys, act, last_time, last_result) VALUES (" . NV_CURRENTTIME . ", 60, 'temp_download_destroy.php', 'cron_auto_del_temp_download', '', 0, 1, 1, 0, 0)";
$sql_create_table[] = "INSERT INTO " . NV_CRONJOBS_GLOBALTABLE . " ( start_time, inter_val, run_file, run_func, params, del, is_sys, act, last_time, last_result) VALUES (" . NV_CURRENTTIME . ", 30, 'ip_logs_destroy.php', 'cron_del_ip_logs', '', 0, 1, 1, 0, 0)";
$sql_create_table[] = "INSERT INTO " . NV_CRONJOBS_GLOBALTABLE . " ( start_time, inter_val, run_file, run_func, params, del, is_sys, act, last_time, last_result) VALUES (" . NV_CURRENTTIME . ", 1440, 'error_log_destroy.php', 'cron_auto_del_error_log', '', 0, 1, 1, 0, 0)";
$sql_create_table[] = "INSERT INTO " . NV_CRONJOBS_GLOBALTABLE . " ( start_time, inter_val, run_file, run_func, params, del, is_sys, act, last_time, last_result) VALUES (" . NV_CURRENTTIME . ", 360, 'error_log_sendmail.php', 'cron_auto_sendmail_error_log', '', 0, 1, 0, 0, 0)";
$sql_create_table[] = "INSERT INTO " . NV_CRONJOBS_GLOBALTABLE . " ( start_time, inter_val, run_file, run_func, params, del, is_sys, act, last_time, last_result) VALUES (" . NV_CURRENTTIME . ", 60, 'ref_expired_del.php', 'cron_ref_expired_del', '', 0, 1, 1, 0, 0)";
$sql_create_table[] = "INSERT INTO " . NV_CRONJOBS_GLOBALTABLE . " ( start_time, inter_val, run_file, run_func, params, del, is_sys, act, last_time, last_result) VALUES (" . NV_CURRENTTIME . ", 1440, 'siteDiagnostic_update.php', 'cron_siteDiagnostic_update', '', 0, 0, 1, 0, 0)";
$sql_create_table[] = "INSERT INTO " . NV_CRONJOBS_GLOBALTABLE . " ( start_time, inter_val, run_file, run_func, params, del, is_sys, act, last_time, last_result) VALUES (" . NV_CURRENTTIME . ", 60, 'check_version.php', 'cron_auto_check_version', '', 0, 1, 1, 0, 0)";

$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_setup_modules (title, is_sysmod, virtual, module_file, module_data, mod_version, addtime, author, note) VALUES ('about', 0, 0, 'page', 'about', '4.0.00 1393416212', " . NV_CURRENTTIME . ", 'VINADES (contact@vinades.vn)', '')";
$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_setup_modules (title, is_sysmod, virtual, module_file, module_data, mod_version, addtime, author, note) VALUES ('banners', 1, 0, 'banners', 'banners', '4.0.00 1393416212', " . NV_CURRENTTIME . ", 'VINADES (contact@vinades.vn)', '')";
$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_setup_modules (title, is_sysmod, virtual, module_file, module_data, mod_version, addtime, author, note) VALUES ('contact', 0, 1, 'contact', 'contact', '4.0.00 1393416212', " . NV_CURRENTTIME . ", 'VINADES (contact@vinades.vn)', '')";
$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_setup_modules (title, is_sysmod, virtual, module_file, module_data, mod_version, addtime, author, note) VALUES ('news', 0, 1, 'news', 'news', '4.0.00 1393416212', " . NV_CURRENTTIME . ", 'VINADES (contact@vinades.vn)', '')";
$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_setup_modules (title, is_sysmod, virtual, module_file, module_data, mod_version, addtime, author, note) VALUES ('voting', 0, 0, 'voting', 'voting', '4.0.00 1393416212', " . NV_CURRENTTIME . ", 'VINADES (contact@vinades.vn)', '')";
$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_setup_modules (title, is_sysmod, virtual, module_file, module_data, mod_version, addtime, author, note) VALUES ('forum', 0, 0, 'forum', 'forum', '4.0.00 1393416212', " . NV_CURRENTTIME . ", 'VINADES (contact@vinades.vn)', '')";
$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_setup_modules (title, is_sysmod, virtual, module_file, module_data, mod_version, addtime, author, note) VALUES ('seek', 1, 0, 'seek', 'seek', '4.0.00 1393416212', " . NV_CURRENTTIME . ", 'VINADES (contact@vinades.vn)', '')";
$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_setup_modules (title, is_sysmod, virtual, module_file, module_data, mod_version, addtime, author, note) VALUES ('users', 1, 0, 'users', 'users', '4.0.00 1393416212', " . NV_CURRENTTIME . ", 'VINADES (contact@vinades.vn)', '')";
$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_setup_modules (title, is_sysmod, virtual, module_file, module_data, mod_version, addtime, author, note) VALUES ('download', 0, 1, 'download', 'download', '4.0.00 1393416212', " . NV_CURRENTTIME . ", 'VINADES (contact@vinades.vn)', '')";
$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_setup_modules (title, is_sysmod, virtual, module_file, module_data, mod_version, addtime, author, note) VALUES ('statistics', 0, 0, 'statistics', 'statistics', '4.0.00 1393416212', " . NV_CURRENTTIME . ", 'VINADES (contact@vinades.vn)', '')";
$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_setup_modules (title, is_sysmod, virtual, module_file, module_data, mod_version, addtime, author, note) VALUES ('menu', 0, 1, 'menu', 'menu', '4.0.00 1393416212', " . NV_CURRENTTIME . ", 'VINADES (contact@vinades.vn)', '')";
$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_setup_modules (title, is_sysmod, virtual, module_file, module_data, mod_version, addtime, author, note) VALUES ('feeds', 1, 0, 'feeds', 'feeds', '4.0.00 1393416212', " . NV_CURRENTTIME . ", 'VINADES (contact@vinades.vn)', '')";
$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_setup_modules (title, is_sysmod, virtual, module_file, module_data, mod_version, addtime, author, note) VALUES ('page', 1, 1, 'page', 'page', '4.0.00 1393416212', " . NV_CURRENTTIME . ", 'VINADES (contact@vinades.vn)', '')";
$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_setup_modules (title, is_sysmod, virtual, module_file, module_data, mod_version, addtime, author, note) VALUES ('comment', 1, 0, 'comment', 'comment', '4.0.00 1393416212', " . NV_CURRENTTIME . ", 'VINADES (contact@vinades.vn)', '')";

$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_banners_plans VALUES (1, '', 'Quang cao giua trang', '', 'sequential', 510, 100, 1)";
$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_banners_plans VALUES (2, '', 'Quang cao trai', '', 'sequential', 190, 500, 1)";

$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_banners_rows VALUES (1, 'Bo ngoai giao', 2, 0, 'bongoaigiao.jpg', 'jpg', 'image/jpeg', 160, 54, '', '', 'http://www.mofa.gov.vn', '_blank', " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", 0, 0, 1,1)";
$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_banners_rows VALUES (2, 'vinades', 2, 0, 'vinades.jpg', 'jpg', 'image/jpeg', 190, 454, '', '', 'http://vinades.vn', '_blank', " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", 0, 0, 1,2)";
$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_banners_rows VALUES (3, 'Quang cao giua trang', 1, 0, 'webnhanh_vn.gif', 'gif', 'image/gif', 510, 65, '', '', 'http://webnhanh.vn', '_blank', " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", 0, 0, 1,1)";