<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

if (!defined('NV_SYSTEM') and !defined('NV_ADMIN') and !defined('NV_WYSIWYG')) {
    Header('Location: index.php');
    exit();
}

error_reporting(0);

define('NV_MAINFILE', true);

// Thoi gian bat dau phien lam viec
define('NV_START_TIME', microtime(true));
define('NV_CURRENTTIME', isset($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time());

// Khong cho xac dinh tu do cac variables
$db_config = $global_config = $module_config = $client_info = $user_info = $admin_info = $sys_info = $lang_global = $lang_module = $rss = $nv_vertical_menu = $array_mod_title = $content_type = $submenu = $error_info = $countries = $loadScript = $headers = array();
$page_title = $key_words = $canonicalUrl = $mod_title = $editor_password = $my_head = $my_footer = $description = $contents = '';
$editor = false;

// Ket noi voi cac file constants, config
require NV_ROOTDIR . '/includes/constants.php';

$server_name = trim((isset($_SERVER['HTTP_HOST']) and ! empty($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME']);
$server_name = preg_replace('/^[a-z]+\:\/\//i', '', $server_name);
$server_name = preg_replace('/(\:[0-9]+)$/', '', $server_name);
$server_protocol = strtolower(preg_replace('/^([^\/]+)\/*(.*)$/', '\\1', $_SERVER['SERVER_PROTOCOL'])) . (($_SERVER['HTTPS'] == 'on') ? 's' : '');
$server_port = ($_SERVER['SERVER_PORT'] == '80' or $_SERVER['SERVER_PORT'] == '443') ? '' : (':' . $_SERVER['SERVER_PORT']);
if (filter_var($server_name, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false) {
    $my_current_domain = $server_protocol . '://' . $server_name . $server_port;
} else {
    $my_current_domain = $server_protocol . '://[' . $server_name . ']' . $server_port;
}

$base_siteurl = pathinfo($_SERVER['PHP_SELF'], PATHINFO_DIRNAME);
if ($base_siteurl == DIRECTORY_SEPARATOR) {
    $base_siteurl = '';
}
if (! empty($base_siteurl)) {
    $base_siteurl = str_replace(DIRECTORY_SEPARATOR, '/', $base_siteurl);
}
if (! empty($base_siteurl)) {
    $base_siteurl = preg_replace('/[\/]+$/', '', $base_siteurl);
}
if (! empty($base_siteurl)) {
    $base_siteurl = preg_replace('/^[\/]*(.*)$/', '/\\1', $base_siteurl);
}
if (defined('NV_WYSIWYG') and ! defined('NV_ADMIN')) {
    $base_siteurl = preg_replace('#/' . NV_EDITORSDIR . '(.*)$#', '', $base_siteurl);
} elseif (defined('NV_IS_UPDATE')) {
    // Update se bao gom ca admin nen update phai dat truoc
    $base_siteurl = preg_replace('#/install(.*)$#', '', $base_siteurl);
} elseif (defined('NV_ADMIN')) {
    $base_siteurl = preg_replace('#/' . NV_ADMINDIR . '(.*)$#i', '', $base_siteurl);
} elseif (! empty($base_siteurl)) {
    $base_siteurl = preg_replace('#/index\.php(.*)$#', '', $base_siteurl);
}
define('NV_SERVER_NAME', $server_name);// vd: mydomain1.com
define('NV_SERVER_PROTOCOL', $server_protocol);// vd: http
define('NV_SERVER_PORT', $server_port);// vd: 80
define('NV_MY_DOMAIN', $my_current_domain);// vd: http://mydomain1.com:80
define('NV_BASE_SITEURL', $base_siteurl . '/');// vd: /ten_thu_muc_chua_site/

if (file_exists(NV_ROOTDIR . '/' . NV_CONFIG_FILENAME)) {
    require realpath(NV_ROOTDIR . '/' . NV_CONFIG_FILENAME);
} else {
    if (file_exists(NV_ROOTDIR . '/install/index.php')) {
        Header('Location: ' . NV_BASE_SITEURL . 'install/index.php');
    }
    die();
}

require NV_ROOTDIR . '/' . NV_DATADIR . '/config_global.php';

if (empty($global_config['my_domains'])) {
    $global_config['my_domains'] = [NV_SERVER_NAME];
} else {
    $global_config['my_domains'] = array_map('trim', explode(',', $global_config['my_domains']));
    $global_config['my_domains'] = array_map('strtolower', $global_config['my_domains']);
}

require NV_ROOTDIR . '/includes/ini.php';

// Vendor autoload
require NV_ROOTDIR . '/vendor/autoload.php';
require NV_ROOTDIR . '/includes/xtemplate.class.php';

// Xac dinh IP cua client
$ips = new NukeViet\Core\Ips();
// define( 'NV_SERVER_IP', $ips->server_ip );
define('NV_FORWARD_IP', $ips->forward_ip);
define('NV_REMOTE_ADDR', $ips->remote_addr);
define('NV_CLIENT_IP', $ips->remote_ip);

define('SYSTEM_UPLOADS_DIR', NV_UPLOADS_DIR);
define('NV_FILES_DIR', NV_ASSETS_DIR);
define('SYSTEM_CACHEDIR', NV_CACHEDIR);
define('NV_USERS_GLOBALTABLE', $db_config['prefix'] . '_users');
define('NV_GROUPS_GLOBALTABLE', $db_config['prefix'] . '_users_groups');

// Neu khong co IP
if (NV_CLIENT_IP == 'none') {
    die('Error: Your IP address is not correct');
}

// Xac dinh Quoc gia
require NV_ROOTDIR . '/includes/countries.php';
$client_info['country'] = isset($_SERVER['GEOIP_COUNTRY_CODE']) ? $_SERVER['GEOIP_COUNTRY_CODE'] : nv_getCountry_from_cookie(NV_CLIENT_IP);
$client_info['ip'] = NV_CLIENT_IP;

// Mui gio
require NV_ROOTDIR . '/includes/timezone.php';

// Ket noi voi class Error_handler
$ErrorHandler = new NukeViet\Core\Error($global_config);

if (empty($global_config['allow_sitelangs'])) {
    trigger_error('Error! Language variables is empty!', 256);
}

// Ket noi voi cac file cau hinh, function va template
require NV_ROOTDIR . '/includes/utf8/' . $sys_info['string_handler'] . '_string_handler.php';
require NV_ROOTDIR . '/includes/utf8/utf8_functions.php';
require NV_ROOTDIR . '/includes/core/filesystem_functions.php';
require NV_ROOTDIR . '/includes/functions.php';
require NV_ROOTDIR . '/includes/core/theme_functions.php';

// IP Ban
if (nv_is_banIp(NV_CLIENT_IP)) {
    trigger_error('Hi and Good-bye!!!', 256);
}

// Chan proxy
if ($global_config['proxy_blocker'] != 0) {
    $client_info['is_proxy'] = $ips->nv_check_proxy();
    if (nv_is_blocker_proxy($client_info['is_proxy'], $global_config['proxy_blocker'])) {
        trigger_error('ERROR: You are behind a proxy server. Please disconnect and come again!', 256);
    }
}

if (defined('NV_SYSTEM')) {
    require NV_ROOTDIR . '/includes/request_uri.php';
}

// Ket noi voi class xu ly request
$nv_Request = new NukeViet\Core\Request($global_config, NV_CLIENT_IP);

define('NV_HEADERSTATUS', $nv_Request->headerstatus);
// vd: HTTP/1.0

define('NV_BASE_ADMINURL', $nv_Request->base_adminurl . '/');
// vd: /ten_thu_muc_chua_site/admin/


define('NV_DOCUMENT_ROOT', $nv_Request->doc_root);
// D:/AppServ/www


define('NV_CACHE_PREFIX', md5($global_config['sitekey'] . NV_SERVER_NAME));
// Hau to cua file cache


define('NV_CHECK_SESSION', md5(NV_CACHE_PREFIX . $nv_Request->session_id));
// Kiem tra session cua nguoi dung


define('NV_USER_AGENT', $nv_Request->user_agent);

// Ngon ngu
require NV_ROOTDIR . '/includes/language.php';
require NV_ROOTDIR . '/includes/language/' . NV_LANG_INTERFACE . '/global.php';
require NV_ROOTDIR . '/includes/language/' . NV_LANG_INTERFACE . '/functions.php';

if (!in_array(NV_SERVER_NAME, $global_config['my_domains'])) {
    $global_config['site_logo'] = NV_ASSETS_DIR . '/images/logo.png';
    $global_config['site_url'] = NV_SERVER_PROTOCOL . '://' . $global_config['my_domains'][0] . NV_SERVER_PORT;
    nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'], 400, '', '', '', '');
}
// Ket noi Cache
if ($global_config['cached'] == 'memcached') {
    $nv_Cache = new NukeViet\Cache\Memcached(NV_MEMCACHED_HOST, NV_MEMCACHED_PORT, NV_LANG_DATA, NV_CACHE_PREFIX);
} elseif ($global_config['cached'] == 'redis') {
    $nv_Cache = new NukeViet\Cache\Redis(NV_REDIS_HOST, NV_REDIS_PORT, NV_REDIS_TIMEOUT, NV_REDIS_PASSWORD, NV_REDIS_DBINDEX, NV_LANG_DATA, NV_CACHE_PREFIX);
} else {
    $nv_Cache = new NukeViet\Cache\Files(NV_ROOTDIR . '/' . NV_CACHEDIR, NV_LANG_DATA, NV_CACHE_PREFIX);
}

// Xac dinh duong dan thuc den thu muc upload
define('NV_UPLOADS_REAL_DIR', NV_ROOTDIR . '/' . NV_UPLOADS_DIR);

// vd: /ten_thu_muc_chua_site/
$global_config['cookie_path'] = $nv_Request->cookie_path;

// vd: .mydomain1.com
$global_config['cookie_domain'] = $nv_Request->cookie_domain;

// vd: http://mydomain1.com/ten_thu_muc_chua_site
$global_config['site_url'] = $nv_Request->site_url;

// vd: D:/AppServ/www/ten_thu_muc_chua_site/sess/
$sys_info['sessionpath'] = $nv_Request->session_save_path;

// ten cua session
$client_info['session_id'] = $nv_Request->session_id;

// referer
$client_info['referer'] = $nv_Request->referer;

// 0 = referer tu ben ngoai site, 1 = referer noi bo, 2 = khong co referer
$client_info['is_myreferer'] = $nv_Request->referer_key;
// trang dang xem
$client_info['selfurl'] = $nv_Request->my_current_domain . $nv_Request->request_uri;

// Xac dinh co phai AJAX hay khong
if (preg_match('/^[0-9]{10,}$/', $nv_Request->get_string('nocache', 'get', '')) and $client_info['is_myreferer'] === 1) {
    define('NV_IS_AJAX', true);
}

// Chan truy cap neu HTTP_USER_AGENT == 'none'
if (NV_USER_AGENT == 'none' and NV_ANTI_AGENT) {
    trigger_error('We\'re sorry. The software you are using to access our website is not allowed. Some examples of this are e-mail harvesting programs and programs that will copy websites to your hard drive. If you feel you have gotten this message in error, please send an e-mail addressed to admin. Your I.P. address has been logged. Thanks.', 256);
}

// xac dinh co phai User_Agent cua NukeViet hay khong
if (NV_USER_AGENT == 'NUKEVIET CMS ' . $global_config['version'] . '. Developed by VINADES. Url: http://nukeviet.vn. Code: ' . md5($global_config['sitekey'])) {
    define('NV_IS_MY_USER_AGENT', true);
}

// Xac dinh borwser cua client
$browser = new NukeViet\Client\Browser(NV_USER_AGENT);
$client_info['browser'] = array();
$client_info['browser']['key'] = $browser->getBrowserKey();
$client_info['browser']['name'] = $browser->getBrowser();
if (preg_match('/^([0-9]+)\.(.*)$/', $browser->getVersion(), $matches)) {
    $client_info['browser']['version'] = (int) $matches[1];
} else {
    $client_info['browser']['version'] = 0;
}
$client_info['is_mobile'] = $browser->isMobile();
$client_info['is_tablet'] = $browser->isTablet();
$client_info['is_bot'] = $browser->isRobot();
$client_info['client_os'] = array(
    'key' => $browser->getPlatformKey(),
    'name' => $browser->getPlatform()
);

$is_mobile_tablet = $client_info['is_mobile'] . '-' . $client_info['is_tablet'];
if ($is_mobile_tablet != $nv_Request->get_string('is_mobile_tablet', 'session')) {
    $nv_Request->set_Session('is_mobile_tablet', $is_mobile_tablet);
    $nv_Request->unset_request('nv' . NV_LANG_DATA . 'themever', 'cookie');
}

// Ket noi voi class chong flood
if ($global_config['is_flood_blocker'] and !$nv_Request->isset_request('admin', 'session') and //
(!$nv_Request->isset_request('second', 'get') or ($nv_Request->isset_request('second', 'get') and $client_info['is_myreferer'] != 1))) {
    require NV_ROOTDIR . '/includes/core/flood_blocker.php';
}

// Captcha
if ($nv_Request->isset_request('scaptcha', 'get')) {
    require NV_ROOTDIR . '/includes/core/captcha.php';
}
// Class ma hoa du lieu
$crypt = new NukeViet\Core\Encryption($global_config['sitekey']);
$global_config['ftp_user_pass'] = $crypt->decrypt($global_config['ftp_user_pass']);

if (isset($nv_plugin_area[1])) {
    // Kết nối với các plugin Trước khi kết nối CSDL
    foreach ($nv_plugin_area[1] as $_fplugin) {
        include NV_ROOTDIR . '/includes/plugin/' . $_fplugin;
    }
}

// Bat dau phien lam viec cua Database
$db = $db_slave = new NukeViet\Core\Database($db_config);
if (empty($db->connect)) {
    trigger_error('Sorry! Could not connect to data server', 256);
}
unset($db_config['dbpass']);
$nv_Cache->SetDb($db);

// Ten cac table cua CSDL dung chung cho he thong
define('NV_AUTHORS_GLOBALTABLE', $db_config['prefix'] . '_authors');
define('NV_SESSIONS_GLOBALTABLE', $db_config['prefix'] . '_sessions');
define('NV_COOKIES_GLOBALTABLE', $db_config['prefix'] . '_cookies');
define('NV_LANGUAGE_GLOBALTABLE', $db_config['prefix'] . '_language');

define('NV_CONFIG_GLOBALTABLE', $db_config['prefix'] . '_config');
define('NV_CRONJOBS_GLOBALTABLE', $db_config['prefix'] . '_cronjobs');
define('NV_NOTIFICATION_GLOBALTABLE', $db_config['prefix'] . '_notification');

define('NV_UPLOAD_GLOBALTABLE', $db_config['prefix'] . '_upload');
define('NV_BANNERS_GLOBALTABLE', $db_config['prefix'] . '_banners');
define('NV_COUNTER_GLOBALTABLE', $db_config['prefix'] . '_counter');

define('NV_PREFIXLANG', $db_config['prefix'] . '_' . NV_LANG_DATA);
define('NV_MODULES_TABLE', NV_PREFIXLANG . '_modules');
define('NV_BLOCKS_TABLE', NV_PREFIXLANG . '_blocks');
define('NV_MODFUNCS_TABLE', NV_PREFIXLANG . '_modfuncs');

define('NV_SEARCHKEYS_TABLE', NV_PREFIXLANG . '_searchkeys');
define('NV_REFSTAT_TABLE', NV_PREFIXLANG . '_referer_stats');

$sql = "SELECT lang, module, config_name, config_value FROM " . NV_CONFIG_GLOBALTABLE . " WHERE lang='" . NV_LANG_DATA . "' or (lang='sys' AND module='site') ORDER BY module ASC";
$list = $nv_Cache->db($sql, '', 'settings');

foreach ($list as $row) {
    if (($row['lang'] == NV_LANG_DATA and $row['module'] == 'global') or ($row['lang'] == 'sys' and $row['module'] == 'site')) {
        $global_config[$row['config_name']] = $row['config_value'];
    } else {
        $module_config[$row['module']][$row['config_name']] = $row['config_value'];
    }
}

// Check https
if (($global_config['ssl_https'] == 1 or $global_config['ssl_https'] == 2 and defined('NV_ADMIN')) and (!isset($_SERVER['HTTPS']) or $_SERVER['HTTPS'] == 'off')) {
    nv_redirect_location('https://' . NV_SERVER_NAME . NV_SERVER_PORT . $_SERVER['REQUEST_URI']);
}

if ($global_config['is_user_forum']) {
    define('NV_IS_USER_FORUM', true);
}

if (!empty($global_config['openid_servers'])) {
    $global_config['openid_servers'] = explode(',', $global_config['openid_servers']);
    define('NV_OPENID_ALLOWED', true);
}

if (empty($global_config['site_logo'])) {
    $global_config['site_logo'] = NV_ASSETS_DIR . '/images/logo.png';
}

$global_config['array_theme_type'] = explode(',', $global_config['theme_type']);
$global_config['array_preview_theme'] = explode(',', $global_config['preview_theme']);

define('NV_MAIN_DOMAIN', in_array($global_config['site_domain'], $global_config['my_domains']) ? str_replace(NV_SERVER_NAME, $global_config['site_domain'], NV_MY_DOMAIN) : NV_MY_DOMAIN);

$global_config['smtp_password'] = $crypt->decrypt($global_config['smtp_password']);
if ($sys_info['ini_set_support']) {
    ini_set('sendmail_from', $global_config['site_email']);
}
if (!isset($global_config['upload_checking_mode']) or !in_array($global_config['upload_checking_mode'], array('mild','lite','none'))) {
    $global_config['upload_checking_mode'] = 'strong';
}
define('UPLOAD_CHECKING_MODE', $global_config['upload_checking_mode']);

if (defined('NV_ADMIN')) {
    if (!file_exists(NV_ROOTDIR . '/includes/language/' . NV_LANG_DATA . '/global.php')) {
        if ($global_config['lang_multi']) {
            $nv_Request->set_Cookie('data_lang', $global_config['site_lang'], NV_LIVE_COOKIE_TIME);
        }
        nv_redirect_location(NV_BASE_ADMINURL);
    }
    if (!file_exists(NV_ROOTDIR . '/includes/language/' . NV_LANG_INTERFACE . '/global.php')) {
        if ($global_config['lang_multi']) {
            $nv_Request->set_Cookie('int_lang', $global_config['site_lang'], NV_LIVE_COOKIE_TIME);
        }
        nv_redirect_location(NV_BASE_ADMINURL);
    }
}

// Cronjobs execute
if ($nv_Request->get_string('second', 'get') == 'cronjobs') {
    require NV_ROOTDIR . '/includes/core/cronjobs.php';
}

// Kiem tra tu cach admin
if (defined('NV_IS_ADMIN') or defined('NV_IS_SPADMIN')) {
    trigger_error('Hacking attempt', 256);
}

// Kiem tra ton tai goi cap nhat va tu cach admin
$nv_check_update = file_exists(NV_ROOTDIR . '/install/update_data.php');
define('ADMIN_LOGIN_MODE', $nv_check_update ? 1 : (empty($global_config['closed_site']) ? 3 : $global_config['closed_site']));

$admin_cookie = $nv_Request->get_bool('admin', 'session', false);
if (!empty($admin_cookie)) {
    require NV_ROOTDIR . '/includes/core/admin_access.php';
    require NV_ROOTDIR . '/includes/core/is_admin.php';
}

// Dinh chi hoat dong cua site
if (!defined('NV_IS_ADMIN')) {
    $site_lang = $nv_Request->get_string(NV_LANG_VARIABLE, 'get,post', NV_LANG_DATA);
    if (!in_array($site_lang, $global_config['allow_sitelangs'])) {
        $global_config['closed_site'] = 1;
    }
}
if ($nv_check_update and !defined('NV_IS_UPDATE')) {
    // Dinh chi neu khong la admin toi cao
    if (!defined('NV_ADMIN') and !defined('NV_IS_GODADMIN')) {
        $disable_site_content = (isset($global_config['disable_site_content']) and !empty($global_config['disable_site_content'])) ? $global_config['disable_site_content'] : $lang_global['disable_site_content'];
        nv_info_die($global_config['site_description'], $lang_global['disable_site_title'], $disable_site_content, 200, '', '', '', '');
    }
} elseif (!defined('NV_ADMIN') and !defined('NV_IS_ADMIN')) {
    if (!empty($global_config['closed_site'])) {
        $disable_site_content = (isset($global_config['disable_site_content']) and !empty($global_config['disable_site_content'])) ? $global_config['disable_site_content'] : $lang_global['disable_site_content'];
        nv_info_die($global_config['site_description'], $lang_global['disable_site_title'], $disable_site_content, 200, '', '', '', '');
    } elseif (!in_array(NV_LANG_DATA, $global_config['allow_sitelangs'])) {
        nv_redirect_location(NV_BASE_SITEURL);
    }
}
unset($nv_check_update);

$cache_file = NV_LANG_DATA . '_sitemods_' . NV_CACHE_PREFIX . '.cache';
if (($cache = $nv_Cache->getItem('modules', $cache_file)) != false) {
    $sys_mods = unserialize($cache);
} else {
    $sys_mods = array();
    try {
        $result = $db->query('SELECT * FROM ' . NV_MODULES_TABLE . ' m LEFT JOIN ' . NV_MODFUNCS_TABLE . ' f ON m.title=f.in_module WHERE m.act = 1 ORDER BY m.weight, f.subweight');
        while ($row = $result->fetch()) {
            $m_title = $row['title'];
            $f_name = $row['func_name'];
            $f_alias = $row['alias'];
            if (!isset($sys_mods[$m_title])) {
                $sys_mods[$m_title] = array(
                    'module_file' => $row['module_file'],
                    'module_data' => $row['module_data'],
                    'module_upload' => $row['module_upload'],
                    'module_theme' => $row['module_theme'],
                    'custom_title' => $row['custom_title'],
                    'site_title' => (empty($row['site_title'])) ? $row['custom_title'] : $row['site_title'],
                    'admin_title' => (empty($row['admin_title'])) ? $row['custom_title'] : $row['admin_title'],
                    'admin_file' => $row['admin_file'],
                    'main_file' => $row['main_file'],
                    'theme' => $row['theme'],
                    'mobile' => $row['mobile'],
                    'description' => $row['description'],
                    'keywords' => $row['keywords'],
                    'groups_view' => $row['groups_view'],
                    'is_modadmin' => false,
                    'admins' => $row['admins'],
                    'rss' => $row['rss'],
                    'sitemap' => $row['sitemap'],
                    'gid' => $row['gid'],
                    'funcs' => array()
                );
            }
            $sys_mods[$m_title]['funcs'][$f_alias] = array(
                'func_id' => $row['func_id'],
                'func_name' => $f_name,
                'show_func' => $row['show_func'],
                'func_custom_name' => $row['func_custom_name'],
                'func_site_title' => empty($row['func_site_title']) ? $row['func_custom_name'] : $row['func_site_title'],
                'in_submenu' => $row['in_submenu']
            );
            $sys_mods[$m_title]['alias'][$f_name] = $f_alias;
        }
        $cache = serialize($sys_mods);
        $nv_Cache->setItem('modules', $cache_file, $cache);
        unset($cache, $result);
    } catch (PDOException $e) {
        // trigger_error( $e->getMessage() );
    }
}

define('PCLZIP_TEMPORARY_DIR', NV_ROOTDIR . '/' . NV_TEMP_DIR . '/');

if (isset($nv_plugin_area[2])) {
    // Kết nối với các plugin Trước khi gọi các module
    foreach ($nv_plugin_area[2] as $_fplugin) {
        include NV_ROOTDIR . '/includes/plugin/' . $_fplugin;
    }
}