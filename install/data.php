<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/28/2009 20:8
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$sql_create_table[] = "INSERT INTO " . NV_AUTHORS_GLOBALTABLE . "_module (mid, module, lang_key, weight, act_1, act_2, act_3, checksum) VALUES (1, 'siteinfo', 'mod_siteinfo', 1, 1, 1, 1, '')";
$sql_create_table[] = "INSERT INTO " . NV_AUTHORS_GLOBALTABLE . "_module (mid, module, lang_key, weight, act_1, act_2, act_3, checksum) VALUES (2, 'authors', 'mod_authors', 2, 1, 1, 1, '')";
$sql_create_table[] = "INSERT INTO " . NV_AUTHORS_GLOBALTABLE . "_module (mid, module, lang_key, weight, act_1, act_2, act_3, checksum) VALUES (3, 'settings', 'mod_settings', 3, 1, 1, 0, '')";
$sql_create_table[] = "INSERT INTO " . NV_AUTHORS_GLOBALTABLE . "_module (mid, module, lang_key, weight, act_1, act_2, act_3, checksum) VALUES (4, 'database', 'mod_database', 4, 1, 0, 0, '')";
$sql_create_table[] = "INSERT INTO " . NV_AUTHORS_GLOBALTABLE . "_module (mid, module, lang_key, weight, act_1, act_2, act_3, checksum) VALUES (5, 'webtools', 'mod_webtools', 5, 1, 1, 0, '')";
$sql_create_table[] = "INSERT INTO " . NV_AUTHORS_GLOBALTABLE . "_module (mid, module, lang_key, weight, act_1, act_2, act_3, checksum) VALUES (6, 'seotools', 'mod_seotools', 6, 1, 1, 0, '')";
$sql_create_table[] = "INSERT INTO " . NV_AUTHORS_GLOBALTABLE . "_module (mid, module, lang_key, weight, act_1, act_2, act_3, checksum) VALUES (7, 'language', 'mod_language', 7, 1, 1, 0, '')";
$sql_create_table[] = "INSERT INTO " . NV_AUTHORS_GLOBALTABLE . "_module (mid, module, lang_key, weight, act_1, act_2, act_3, checksum) VALUES (8, 'modules', 'mod_modules', 8, 1, 1, 0, '')";
$sql_create_table[] = "INSERT INTO " . NV_AUTHORS_GLOBALTABLE . "_module (mid, module, lang_key, weight, act_1, act_2, act_3, checksum) VALUES (9, 'themes', 'mod_themes', 9, 1, 1, 0, '')";
$sql_create_table[] = "INSERT INTO " . NV_AUTHORS_GLOBALTABLE . "_module (mid, module, lang_key, weight, act_1, act_2, act_3, checksum) VALUES (10, 'extensions', 'mod_extensions', 10, 1, 0, 0, '')";
$sql_create_table[] = "INSERT INTO " . NV_AUTHORS_GLOBALTABLE . "_module (mid, module, lang_key, weight, act_1, act_2, act_3, checksum) VALUES (11, 'upload', 'mod_upload', 11, 1, 1, 1, '')";

$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_upload_dir (did, dirname, time, thumb_type, thumb_width, thumb_height, thumb_quality) VALUES ('-1', '', 0, 3, 100, 150, 90)";
$sql_create_table[] = "UPDATE " . $db_config['prefix'] . "_upload_dir SET did = '0' WHERE did = '-1'";

$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'closed_site', '0')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'admin_theme', 'admin_default')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'date_pattern', 'l, d/m/Y')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'time_pattern', 'H:i')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'online_upd', '1')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'statistic', '1')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'site_phone', '')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'mailer_mode', '')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'smtp_host', 'smtp.gmail.com')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'smtp_ssl', '1')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'smtp_port', '465')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'verify_peer_ssl', '1')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'verify_peer_name_ssl', '1')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'smtp_username', 'user@gmail.com')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'smtp_password', '')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'googleAnalyticsID', '')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'googleAnalyticsSetDomainName', '0')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'googleAnalyticsMethod', 'classic')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'googleMapsAPI', 'AIzaSyC8ODAzZ75hsAufVBSffnwvKfTOT6TnnNQ')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'searchEngineUniqueID', '')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'metaTagsOgp', '1')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'pageTitleMode', 'pagetitle')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'description_length', '170')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'nv_unickmin', '" . $global_config['nv_unickmin'] . "')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'nv_unickmax', '" . $global_config['nv_unickmax'] . "')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'nv_upassmin', '" . $global_config['nv_upassmin'] . "')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'nv_upassmax', '" . $global_config['nv_upassmax'] . "')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'dir_forum', '')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'nv_unick_type', '" . $global_config['nv_unick_type'] . "')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'nv_upass_type', '" . $global_config['nv_upass_type'] . "')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'allowmailchange', '1')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'allowuserpublic', '1')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'allowquestion', '0')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'allowloginchange', '0')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'allowuserlogin', '1')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'allowuserloginmulti', '0')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'allowuserreg', '2')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'is_user_forum', '0')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'openid_servers', '')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'openid_processing', '0')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'user_check_pass_time', '1800')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'auto_login_after_reg', '1')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'whoviewuser', '2')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'ssl_https', '0')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'facebook_client_id', '')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'facebook_client_secret', '')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'google_client_id', '')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', 'google_client_secret', '')";

$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'define', 'nv_gfx_num', '6')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'notification_active', '1')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'notification_autodel', '15')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'site_keywords', 'NukeViet, portal, mysql, php')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'block_admin_ip', '0')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'admfirewall', '0')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'dump_autobackup', '1')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'dump_backup_ext', 'gz')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'dump_backup_day', '30')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'gfx_chk', '" . $global_config['gfx_chk'] . "')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'file_allowed_ext', 'adobe,archives,audio,documents,flash,images,real,video')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'forbid_extensions', 'php,php3,php4,php5,phtml,inc')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'forbid_mimes', '')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'nv_max_size', '" . min(nv_converttoBytes(ini_get('upload_max_filesize')), nv_converttoBytes(ini_get('post_max_size'))) . "')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'upload_checking_mode', 'strong')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'upload_alt_require', '1')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'upload_auto_alt', '1')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'upload_chunk_size', '0')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'useactivate', '2')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'allow_sitelangs', '" . NV_LANG_DATA . "')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'read_type', '0')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'rewrite_enable', '" . $global_config['rewrite_enable'] . "')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'rewrite_optional', '" . $global_config['rewrite_optional'] . "')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'rewrite_endurl', '" . $global_config['rewrite_endurl'] . "')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'rewrite_exturl', '" . $global_config['rewrite_exturl'] . "')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'rewrite_op_mod', '')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'autocheckupdate', '1')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'autoupdatetime', '24')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'gzip_method', '" . $global_config['gzip_method'] . "')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'authors_detail_main', '0')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'spadmin_add_admin', '1')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'timestamp', '1')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'captcha_type', '1')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'version', '" . $global_config['version'] . "')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'cookie_httponly', '" . $global_config['cookie_httponly'] . "')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'admin_check_pass_time', '1800')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'cookie_secure', '" . $global_config['cookie_secure'] . "')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'is_flood_blocker', '1')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'max_requests_60', '40')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'max_requests_300', '150')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'is_login_blocker', '1')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'login_number_tracking', '5')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'login_time_tracking', '5')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'login_time_ban', '30')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'nv_display_errors_list', '1')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'display_errors_list', '1')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'nv_auto_resize', '1')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'dump_interval', '1')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'cdn_url', '')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'two_step_verification', '0')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'recaptcha_sitekey', '')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'recaptcha_secretkey', '')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'global', 'recaptcha_type', 'image')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'define', 'nv_gfx_width', '150')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'define', 'nv_gfx_height', '40')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'define', 'nv_max_width', '1500')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'define', 'nv_max_height', '1500')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'define', 'nv_live_cookie_time', '" . NV_LIVE_COOKIE_TIME . "')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'define', 'nv_live_session_time', '0')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'define', 'nv_anti_iframe', '" . NV_ANTI_IFRAME . "')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'define', 'nv_anti_agent', '" . NV_ANTI_AGENT . "')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'define', 'nv_allowed_html_tags', '" . NV_ALLOWED_HTML_TAGS . "')";
$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'define', 'nv_debug', '" . NV_DEBUG . "')";

$sql_create_table[] = "INSERT INTO " . NV_CRONJOBS_GLOBALTABLE . " (start_time, inter_val, run_file, run_func, params, del, is_sys, act, last_time, last_result) VALUES (" . NV_CURRENTTIME . ", 5, 'online_expired_del.php', 'cron_online_expired_del', '', 0, 1, 1, 0, 0)";
$sql_create_table[] = "INSERT INTO " . NV_CRONJOBS_GLOBALTABLE . " (start_time, inter_val, run_file, run_func, params, del, is_sys, act, last_time, last_result) VALUES (" . NV_CURRENTTIME . ", 1440, 'dump_autobackup.php', 'cron_dump_autobackup', '', 0, 1, 1, 0, 0)";
$sql_create_table[] = "INSERT INTO " . NV_CRONJOBS_GLOBALTABLE . " (start_time, inter_val, run_file, run_func, params, del, is_sys, act, last_time, last_result) VALUES (" . NV_CURRENTTIME . ", 60, 'temp_download_destroy.php', 'cron_auto_del_temp_download', '', 0, 1, 1, 0, 0)";
$sql_create_table[] = "INSERT INTO " . NV_CRONJOBS_GLOBALTABLE . " (start_time, inter_val, run_file, run_func, params, del, is_sys, act, last_time, last_result) VALUES (" . NV_CURRENTTIME . ", 30, 'ip_logs_destroy.php', 'cron_del_ip_logs', '', 0, 1, 1, 0, 0)";
$sql_create_table[] = "INSERT INTO " . NV_CRONJOBS_GLOBALTABLE . " (start_time, inter_val, run_file, run_func, params, del, is_sys, act, last_time, last_result) VALUES (" . NV_CURRENTTIME . ", 1440, 'error_log_destroy.php', 'cron_auto_del_error_log', '', 0, 1, 1, 0, 0)";
$sql_create_table[] = "INSERT INTO " . NV_CRONJOBS_GLOBALTABLE . " (start_time, inter_val, run_file, run_func, params, del, is_sys, act, last_time, last_result) VALUES (" . NV_CURRENTTIME . ", 360, 'error_log_sendmail.php', 'cron_auto_sendmail_error_log', '', 0, 1, 0, 0, 0)";
$sql_create_table[] = "INSERT INTO " . NV_CRONJOBS_GLOBALTABLE . " (start_time, inter_val, run_file, run_func, params, del, is_sys, act, last_time, last_result) VALUES (" . NV_CURRENTTIME . ", 60, 'ref_expired_del.php', 'cron_ref_expired_del', '', 0, 1, 1, 0, 0)";
$sql_create_table[] = "INSERT INTO " . NV_CRONJOBS_GLOBALTABLE . " (start_time, inter_val, run_file, run_func, params, del, is_sys, act, last_time, last_result) VALUES (" . NV_CURRENTTIME . ", 60, 'check_version.php', 'cron_auto_check_version', '', 0, 1, 1, 0, 0)";
$sql_create_table[] = "INSERT INTO " . NV_CRONJOBS_GLOBALTABLE . " (start_time, inter_val, run_file, run_func, params, del, is_sys, act, last_time, last_result) VALUES (" . NV_CURRENTTIME . ", 1440, 'notification_autodel.php', 'cron_notification_autodel', '', 0, 1, 1, 0, 0)";

$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_setup_extensions (id, type, title, is_sys, is_virtual, basename, table_prefix, version, addtime, author, note) VALUES (0, 'module', 'about', 0, 0, 'page', 'about', '4.3.03 1533549600', " . NV_CURRENTTIME . ", 'VINADES <contact@vinades.vn>', '')";
$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_setup_extensions (id, type, title, is_sys, is_virtual, basename, table_prefix, version, addtime, author, note) VALUES (0, 'module', 'siteterms', 0, 0, 'page', 'siteterms', '4.3.03 1533549600', " . NV_CURRENTTIME . ", 'VINADES <contact@vinades.vn>', '')";
$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_setup_extensions (id, type, title, is_sys, is_virtual, basename, table_prefix, version, addtime, author, note) VALUES (19, 'module', 'banners', 1, 0, 'banners', 'banners', '4.3.03 1533549600', " . NV_CURRENTTIME . ", 'VINADES <contact@vinades.vn>', '')";
$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_setup_extensions (id, type, title, is_sys, is_virtual, basename, table_prefix, version, addtime, author, note) VALUES (20, 'module', 'contact', 0, 1, 'contact', 'contact', '4.3.03 1533549600', " . NV_CURRENTTIME . ", 'VINADES <contact@vinades.vn>', '')";
$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_setup_extensions (id, type, title, is_sys, is_virtual, basename, table_prefix, version, addtime, author, note) VALUES (1, 'module', 'news', 0, 1, 'news', 'news', '4.3.03 1533549600', " . NV_CURRENTTIME . ", 'VINADES <contact@vinades.vn>', '')";
$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_setup_extensions (id, type, title, is_sys, is_virtual, basename, table_prefix, version, addtime, author, note) VALUES (21, 'module', 'voting', 0, 0, 'voting', 'voting', '4.3.03 1533549600', " . NV_CURRENTTIME . ", 'VINADES <contact@vinades.vn>', '')";
$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_setup_extensions (id, type, title, is_sys, is_virtual, basename, table_prefix, version, addtime, author, note) VALUES (22, 'module', 'forum', 0, 0, 'forum', 'forum', '4.3.03 1533549600', " . NV_CURRENTTIME . ", 'VINADES <contact@vinades.vn>', '')";
$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_setup_extensions (id, type, title, is_sys, is_virtual, basename, table_prefix, version, addtime, author, note) VALUES (284, 'module', 'seek', 1, 0, 'seek', 'seek', '4.3.03 1533549600', " . NV_CURRENTTIME . ", 'VINADES <contact@vinades.vn>', '')";
$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_setup_extensions (id, type, title, is_sys, is_virtual, basename, table_prefix, version, addtime, author, note) VALUES (24, 'module', 'users', 1, 1, 'users', 'users', '4.3.03 1533549600', " . NV_CURRENTTIME . ", 'VINADES <contact@vinades.vn>', '')";
$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_setup_extensions (id, type, title, is_sys, is_virtual, basename, table_prefix, version, addtime, author, note) VALUES (27, 'module', 'statistics', 0, 0, 'statistics', 'statistics', '4.3.03 1533549600', " . NV_CURRENTTIME . ", 'VINADES <contact@vinades.vn>', '')";
$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_setup_extensions (id, type, title, is_sys, is_virtual, basename, table_prefix, version, addtime, author, note) VALUES (29, 'module', 'menu', 0, 0, 'menu', 'menu', '4.3.03 1533549600', " . NV_CURRENTTIME . ", 'VINADES <contact@vinades.vn>', '')";
$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_setup_extensions (id, type, title, is_sys, is_virtual, basename, table_prefix, version, addtime, author, note) VALUES (283, 'module', 'feeds', 1, 0, 'feeds', 'feeds', '4.3.03 1533549600', " . NV_CURRENTTIME . ", 'VINADES <contact@vinades.vn>', '')";
$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_setup_extensions (id, type, title, is_sys, is_virtual, basename, table_prefix, version, addtime, author, note) VALUES (282, 'module', 'page', 1, 1, 'page', 'page', '4.3.03 1533549600', " . NV_CURRENTTIME . ", 'VINADES <contact@vinades.vn>', '')";
$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_setup_extensions (id, type, title, is_sys, is_virtual, basename, table_prefix, version, addtime, author, note) VALUES (281, 'module', 'comment', 1, 0, 'comment', 'comment', '4.3.03 1533549600', " . NV_CURRENTTIME . ", 'VINADES <contact@vinades.vn>', '')";
$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_setup_extensions (id, type, title, is_sys, is_virtual, basename, table_prefix, version, addtime, author, note) VALUES (312, 'module', 'freecontent', 0, 1, 'freecontent', 'freecontent', '4.3.03 1533549600', " . NV_CURRENTTIME . ", 'VINADES <contact@vinades.vn>', '')";
$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_setup_extensions (id, type, title, is_sys, is_virtual, basename, table_prefix, version, addtime, author, note) VALUES (327, 'module', 'two-step-verification', 1, 0, 'two-step-verification', 'two_step_verification', '4.3.03 1533549600', " . NV_CURRENTTIME . ", 'VINADES <contact@vinades.vn>', '')";
$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_setup_extensions (id, type, title, is_sys, is_virtual, basename, table_prefix, version, addtime, author, note) VALUES (307, 'theme', 'default', 0, 0, 'default', 'default', '4.3.03 1533549600', " . NV_CURRENTTIME . ", 'VINADES <contact@vinades.vn>', '')";
$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_setup_extensions (id, type, title, is_sys, is_virtual, basename, table_prefix, version, addtime, author, note) VALUES (311, 'theme', 'mobile_default', 0, 0, 'mobile_default', 'mobile_default', '4.3.03 1533549600', " . NV_CURRENTTIME . ", 'VINADES <contact@vinades.vn>', '')";

$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_banners_plans (id, blang, title, description, form, width, height, act, require_image, uploadtype) VALUES (1, '', 'Quang cao giua trang', '', 'sequential', 575, 72, 1, 1, 'images,flash')";
$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_banners_plans (id, blang, title, description, form, width, height, act, require_image, uploadtype) VALUES (2, '', 'Quang cao trai', '', 'sequential', 212, 800, 1, 1, 'images,flash')";
$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_banners_plans (id, blang, title, description, form, width, height, act, require_image, uploadtype) VALUES (3, '', 'Quang cao Phai', '', 'random', 250, 500, 1, 1, 'images,flash')";

$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_banners_rows (id, title, pid, clid, file_name, file_ext, file_mime, width, height, file_alt, imageforswf, click_url, target, bannerhtml, add_time, publ_time, exp_time, hits_total, act, weight) VALUES (2, 'vinades', 2, 1, 'vinades.jpg', 'jpg', 'image/jpeg', 212, 400, '', '', 'http://vinades.vn', '_blank', '', " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", 0, 0, 1, 2)";
$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_banners_rows (id, title, pid, clid, file_name, file_ext, file_mime, width, height, file_alt, imageforswf, click_url, target, bannerhtml, add_time, publ_time, exp_time, hits_total, act, weight) VALUES (3, 'Quang cao giua trang', 1, 1, 'webnhanh.jpg', 'png', 'image/jpeg', 575, 72, '', '', 'http://webnhanh.vn', '_blank', '', " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", 0, 0, 1, 1)";

$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_plugin (pid, plugin_file, plugin_area, weight) VALUES (1, 'qrcode.php', 1, 1)";
$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_plugin (pid, plugin_file, plugin_area, weight) VALUES (2, 'cdn_js_css_image.php', 3, 1)";