<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @createdate 12/28/2009 20:8
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$sql_create_table[] = "INSERT INTO " . NV_AUTHORS_GLOBALTABLE . "_module
	(mid, module, lang_key, weight, act_1, act_2, act_3, checksum) VALUES
	(1, 'siteinfo', 'mod_siteinfo', 1, 1, 1, 1, ''),
	(2, 'authors', 'mod_authors', 2, 1, 1, 1, ''),
	(3, 'settings', 'mod_settings', 3, 1, 1, 0, ''),
	(4, 'database', 'mod_database', 4, 1, 0, 0, ''),
	(5, 'webtools', 'mod_webtools', 5, 1, 0, 0, ''),
	(6, 'seotools', 'mod_seotools', 6, 1, 0, 0, ''),
	(7, 'language', 'mod_language', 7, 1, 1, 0, ''),
	(8, 'modules', 'mod_modules', 8, 1, 1, 0, ''),
	(9, 'themes', 'mod_themes', 9, 1, 1, 0, ''),
	(10, 'upload', 'mod_upload', 10, 1, 1, 1, '')";

$sql_create_table[] = "INSERT INTO " . NV_GROUPS_GLOBALTABLE . "
(group_id, title, content, add_time, exp_time, publics, weight, act, idsite, numbers, siteus) VALUES
(1, 'Super admin', '', " . NV_CURRENTTIME . ", 0, 0, 1, 1, 0, 1, 0),
(2, 'General admin', '', " . NV_CURRENTTIME . ", 0, 0, 2, 1, 0, 0, 0),
(3, 'Module admin', '', " . NV_CURRENTTIME . ", 0, 0, 3, 1, 0, 2, 0)";

$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_upload_dir (did, dirname, time, thumb_type, thumb_width, thumb_height, thumb_quality) VALUES ('-1', '', 0, 3, 100, 150, 90)";
$sql_create_table[] = "UPDATE " . $db_config['prefix'] . "_upload_dir SET did = '0' WHERE did = '-1'";

$sql_create_table[] = "INSERT INTO " . NV_USERS_GLOBALTABLE . "_config (config, content, edit_time) VALUES
	('access_admin', 'a:6:{s:12:\"access_addus\";a:3:{i:1;b:1;i:2;b:1;i:3;b:1;}s:14:\"access_waiting\";a:3:{i:1;b:1;i:2;b:1;i:3;b:1;}s:13:\"access_editus\";a:3:{i:1;b:1;i:2;b:1;i:3;b:1;}s:12:\"access_delus\";a:3:{i:1;b:1;i:2;b:1;i:3;b:1;}s:13:\"access_passus\";a:3:{i:1;b:1;i:2;b:1;i:3;b:1;}s:13:\"access_groups\";a:3:{i:1;b:1;i:2;b:1;i:3;b:1;}}', 1352873462),
	('password_simple', '000000|1234|2000|12345|111111|123123|123456|654321|696969|1234567|12345678|123456789|1234567890|aaaaaa|abc123|abc123@|abc@123|adobe1|adobe123|azerty|baseball|dragon|football|harley|iloveyou|jennifer|jordan|letmein|macromedia|master|michael|monkey|mustang|password|photoshop|pussy|qwerty|shadow|superman', " . NV_CURRENTTIME . "),
	('deny_email', 'yoursite.com|mysite.com|localhost|xxx', " . NV_CURRENTTIME . "),
	('deny_name', 'anonimo|anonymous|god|linux|nobody|operator|root', " . NV_CURRENTTIME . ")";

$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES
	('sys', 'site', 'closed_site', '0'),
	('sys', 'site', 'admin_theme', 'admin_default'),
	('sys', 'site', 'date_pattern', 'l, d/m/Y'),
	('sys', 'site', 'time_pattern', 'H:i'),
	('sys', 'site', 'online_upd', '1'),
	('sys', 'site', 'statistic', '1'),
	('sys', 'site', 'mailer_mode', ''),
	('sys', 'site', 'smtp_host', 'smtp.gmail.com'),
	('sys', 'site', 'smtp_ssl', '1'),
	('sys', 'site', 'smtp_port', '465'),
	('sys', 'site', 'smtp_username', 'user@gmail.com'),
	('sys', 'site', 'smtp_password', ''),
	('sys', 'site', 'googleAnalyticsID', ''),
	('sys', 'site', 'googleAnalyticsSetDomainName', '0'),
	('sys', 'site', 'googleAnalyticsMethod', 'classic'),
	('sys', 'site', 'searchEngineUniqueID', ''),
	('sys', 'global', 'site_keywords', 'NukeViet, portal, mysql, php'),
	('sys', 'global', 'site_phone', ''),
	('sys', 'global', 'block_admin_ip', '0'),
	('sys', 'global', 'admfirewall', '0'),
	('sys', 'global', 'dump_autobackup', '1'),
	('sys', 'global', 'dump_backup_ext', 'gz'),
	('sys', 'global', 'dump_backup_day', '30'),
	('sys', 'global', 'gfx_chk', '" . $global_config['gfx_chk'] . "'),
	('sys', 'global', 'file_allowed_ext', 'adobe,archives,audio,documents,flash,images,real,video'),
	('sys', 'global', 'forbid_extensions', 'php,php3,php4,php5,phtml,inc'),
	('sys', 'global', 'forbid_mimes', ''),
	('sys', 'global', 'nv_max_size', '" . min( nv_converttoBytes( ini_get( 'upload_max_filesize' ) ), nv_converttoBytes( ini_get( 'post_max_size' ) ) ) . "'),
	('sys', 'global', 'upload_checking_mode', 'strong'),
	('sys', 'global', 'allowuserreg', '1'),
	('sys', 'global', 'allowuserlogin', '1'),
	('sys', 'global', 'allowloginchange', '0'),
	('sys', 'global', 'allowquestion', '1'),
	('sys', 'global', 'allowuserpublic', '0'),
	('sys', 'global', 'useactivate', '2'),
	('sys', 'global', 'allowmailchange', '1'),
	('sys', 'global', 'allow_sitelangs', '" . NV_LANG_DATA . "'),
	('sys', 'global', 'allow_adminlangs', '" . implode( ',', $languageslist ) . "'),
	('sys', 'global', 'read_type', '0'),
	('sys', 'global', 'rewrite_optional', '" . $global_config['rewrite_optional'] . "'),
	('sys', 'global', 'rewrite_endurl', '" . $global_config['rewrite_endurl'] . "'),
	('sys', 'global', 'rewrite_exturl', '" . $global_config['rewrite_exturl'] . "'),
	('sys', 'global', 'rewrite_op_mod', ''),
	('sys', 'global', 'autocheckupdate', '1'),
	('sys', 'global', 'autoupdatetime', '24'),
	('sys', 'global', 'gzip_method', '" . $global_config['gzip_method'] . "'),
	('sys', 'global', 'is_user_forum', '0'),
	('sys', 'global', 'openid_mode', '1'),
	('sys', 'global', 'authors_detail_main', '0'),
	('sys', 'global', 'spadmin_add_admin', '1'),
	('sys', 'global', 'openid_servers', 'yahoo,google,myopenid'),
	('sys', 'global', 'optActive', '1'),
	('sys', 'global', 'timestamp', '1'),
	('sys', 'global', 'mudim_displaymode', '1'),
	('sys', 'global', 'mudim_method', '4'),
	('sys', 'global', 'mudim_showpanel', '1'),
	('sys', 'global', 'mudim_active', '1'),
	('sys', 'global', 'captcha_type', '0'),
	('sys', 'global', 'version', '" . $global_config['version'] . "'),
	('sys', 'global', 'whoviewuser', '2'),
	('sys', 'global', 'facebook_client_id', ''),
	('sys', 'global', 'facebook_client_secret', ''),
	('sys', 'global', 'cookie_httponly', '" . $global_config['cookie_httponly'] . "'),
	('sys', 'global', 'admin_check_pass_time', '1800'),
	('sys', 'global', 'adminrelogin_max', '3'),
	('sys', 'global', 'cookie_secure', '" . $global_config['cookie_secure'] . "'),
	('sys', 'global', 'nv_unick_type', '" . $global_config['nv_unick_type'] . "'),
	('sys', 'global', 'nv_upass_type', '" . $global_config['nv_upass_type'] . "'),
	('sys', 'global', 'is_flood_blocker', '1'),
	('sys', 'global', 'max_requests_60', '40'),
	('sys', 'global', 'max_requests_300', '150'),
	('sys', 'global', 'nv_display_errors_list', '1'),
	('sys', 'global', 'display_errors_list', '1'),
	('sys', 'global', 'nv_auto_resize', '1'),
	('sys', 'global', 'dump_interval', '1'),
	('sys', 'define', 'nv_unickmin', '" . NV_UNICKMIN . "'),
	('sys', 'define', 'nv_unickmax', '" . NV_UNICKMAX . "'),
	('sys', 'define', 'nv_upassmin', '" . NV_UPASSMIN . "'),
	('sys', 'define', 'nv_upassmax', '" . NV_UPASSMAX . "'),
	('sys', 'define', 'nv_gfx_num', '6'),
	('sys', 'define', 'nv_gfx_width', '120'),
	('sys', 'define', 'nv_gfx_height', '25'),
	('sys', 'define', 'nv_max_width', '1500'),
	('sys', 'define', 'nv_max_height', '1500'),
	('sys', 'define', 'cdn_url', ''),
	('sys', 'define', 'nv_live_cookie_time', '" . NV_LIVE_COOKIE_TIME . "'),
	('sys', 'define', 'nv_live_session_time', '0'),
	('sys', 'define', 'nv_anti_iframe', '" . NV_ANTI_IFRAME . "'),
	('sys', 'define', 'nv_allowed_html_tags', '" . NV_ALLOWED_HTML_TAGS . "'),
	('sys', 'define', 'dir_forum', '')";

$sql_create_table[] = "INSERT INTO " . NV_CRONJOBS_GLOBALTABLE . " ( start_time, inter_val, run_file, run_func, params, del, is_sys, act, last_time, last_result) VALUES
	(" . NV_CURRENTTIME . ", 5, 'online_expired_del.php', 'cron_online_expired_del', '', 0, 1, 1, 0, 0),
	(" . NV_CURRENTTIME . ", 1440, 'dump_autobackup.php', 'cron_dump_autobackup', '', 0, 1, 1, 0, 0),
	(" . NV_CURRENTTIME . ", 60, 'temp_download_destroy.php', 'cron_auto_del_temp_download', '', 0, 1, 1, 0, 0),
	(" . NV_CURRENTTIME . ", 30, 'ip_logs_destroy.php', 'cron_del_ip_logs', '', 0, 1, 1, 0, 0),
	(" . NV_CURRENTTIME . ", 1440, 'error_log_destroy.php', 'cron_auto_del_error_log', '', 0, 1, 1, 0, 0),
	(" . NV_CURRENTTIME . ", 360, 'error_log_sendmail.php', 'cron_auto_sendmail_error_log', '', 0, 1, 0, 0, 0),
	(" . NV_CURRENTTIME . ", 60, 'ref_expired_del.php', 'cron_ref_expired_del', '', 0, 1, 1, 0, 0),
	(" . NV_CURRENTTIME . ", 1440, 'siteDiagnostic_update.php', 'cron_siteDiagnostic_update', '', 0, 0, 1, 0, 0),
	(" . NV_CURRENTTIME . ", 60, 'check_version.php', 'cron_auto_check_version', '', 0, 1, 1, 0, 0)";

$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_setup_modules (title, is_sysmod, virtual, module_file, module_data, mod_version, addtime, author, note) VALUES
	('about', 0, 0, 'page', 'about', '3.0.01 1287532800', " . NV_CURRENTTIME . ", 'VINADES (contact@vinades.vn)', ''),
	('banners', 1, 0, 'banners', 'banners', '3.0.01 1287532800', " . NV_CURRENTTIME . ", 'VINADES (contact@vinades.vn)', ''),
	('contact', 0, 1, 'contact', 'contact', '3.0.01 1287532800', " . NV_CURRENTTIME . ", 'VINADES (contact@vinades.vn)', ''),
	('news', 0, 1, 'news', 'news', '3.0.01 1287532800', " . NV_CURRENTTIME . ", 'VINADES (contact@vinades.vn)', ''),
	('voting', 0, 0, 'voting', 'voting', '3.1.01 1287532800', " . NV_CURRENTTIME . ", 'VINADES (contact@vinades.vn)', ''),
	('forum', 0, 0, 'forum', 'forum', '3.0.01 1287532800', " . NV_CURRENTTIME . ", 'VINADES (contact@vinades.vn)', ''),
	('search', 1, 0, 'search', 'search', '3.0.01 1287532800', " . NV_CURRENTTIME . ", 'VINADES (contact@vinades.vn)', ''),
	('users', 1, 0, 'users', 'users', '3.0.01 1287532800', " . NV_CURRENTTIME . ", 'VINADES (contact@vinades.vn)', ''),
	('download', 0, 1, 'download', 'download', '3.0.01 1287532800', " . NV_CURRENTTIME . ", 'VINADES (contact@vinades.vn)', ''),
	('weblinks', 0, 1, 'weblinks', 'weblinks', '3.0.01 1287532800', " . NV_CURRENTTIME . ", 'VINADES (contact@vinades.vn)', ''),
	('statistics', 0, 0, 'statistics', 'statistics', '3.0.01 1287532800', " . NV_CURRENTTIME . ", 'VINADES (contact@vinades.vn)', ''),
	('faq', 0, 1, 'faq', 'faq', '3.0.01 1287532800', " . NV_CURRENTTIME . ", 'VINADES (contact@vinades.vn)', ''),
	('menu', 0, 1, 'menu', 'menu', '3.1.00 1273225635', " . NV_CURRENTTIME . ", 'VINADES (contact@vinades.vn)', ''),
	('rss', 1, 0, 'rss', 'rss', '3.0.01 1287532800', " . NV_CURRENTTIME . ", 'VINADES (contact@vinades.vn)', ''),
	('page', 1, 1, 'page', 'page', '3.5.00 1385567707', " . NV_CURRENTTIME . ", 'VINADES (contact@vinades.vn)', '')";

$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_banners_plans VALUES
	(1, '', 'Quang cao giua trang', '', 'sequential', 510, 100, 1),
	(2, '', 'Quang cao trai', '', 'sequential', 190, 500, 1)";

$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_banners_rows VALUES
	(1, 'Bo ngoai giao', 2, 0, 'bongoaigiao.jpg', 'jpg', 'image/jpeg', 160, 54, '', '', 'http://www.mofa.gov.vn', '_blank', " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", 0, 0, 1,1),
	(2, 'vinades', 2, 0, 'vinades.jpg', 'jpg', 'image/jpeg', 190, 454, '', '', 'http://vinades.vn', '_blank', " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", 0, 0, 1,2),
	(3, 'Quang cao giua trang', 1, 0, 'webnhanh_vn.gif', 'gif', 'image/gif', 510, 65, '', '', 'http://webnhanh.vn', '_blank', " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", 0, 0, 1,1)";

?>