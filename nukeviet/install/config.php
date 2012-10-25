<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 31/05/2010, 00:36
 */

if( ! defined( 'NV_MAINFILE' ) ) die();

$db_config['dbhost'] = "localhost";
$db_config['dbport'] = "";
$db_config['dbname'] = "";
$db_config['dbuname'] = "";
$db_config['dbpass'] = "";
$db_config['prefix'] = "nv3";
$db_config['prefix_user'] = "nv3";

$global_config['site_email'] = "";
$global_config['error_send_email'] = "support@nukeviet.vn";
$global_config['my_domains'] = "";
$global_config['cookie_prefix'] = "";
$global_config['session_prefix'] = "";
$global_config['sitekey'] = "";

$global_config['site_timezone'] = "byCountry";
$global_config['statistics_timezone'] = "";
$global_config['gzip_method'] = 1;
$global_config['is_url_rewrite'] = 1;
$global_config['rewrite_endurl'] = "/";
$global_config['rewrite_exturl'] = ".html";
$global_config['rewrite_optional'] = 0;

$global_config['proxy_blocker'] = 0;
$global_config['str_referer_blocker'] = 0;

$global_config['lang_multi'] = 1;
$global_config['lang_geo'] = 0;
$global_config['site_lang'] = "en";
$global_config['engine_allowed'] = array();
$global_config['site_theme'] = "modern";
$global_config['gfx_chk'] = 3;

$global_config['version'] = "3.4.02"; // NukeViet 3.4.02.r1929
$global_config['revision'] = 1929;

?>