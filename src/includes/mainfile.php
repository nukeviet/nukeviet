<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_SYSTEM') and !defined('NV_ADMIN') and !defined('NV_WYSIWYG')) {
    header('Location: index.php');
    exit();
}

error_reporting(0);

define('NV_MAINFILE', true);

// Thoi gian bat dau phien lam viec
define('NV_START_TIME', microtime(true));
define('NV_CURRENTTIME', $_SERVER['REQUEST_TIME'] ?? time());

// Khong cho xac dinh tu do cac variables
$db_config = $global_config = $module_config = $client_info = $user_info = $admin_info = $sys_info = $lang_global = $lang_module = $rss = $nv_vertical_menu = $array_mod_title = $content_type = $submenu = $error_info = $countries = $loadScript = $headers = $theme_config = $nv_hooks = $nv_plugins = $custom_preloads = $user_cookie = $nv_html_links = $nv_schemas = $strdata = $meta_property = [];
$page_title = $key_words = $page_url = $canonicalUrl = $prevPage = $nextPage = $my_head = $my_footer = $description = $contents = '';
$editor = false;

// Ket noi voi cac file constants, config
require NV_ROOTDIR . '/includes/constants.php';
if (file_exists(NV_ROOTDIR . '/' . NV_DATADIR . '/config_global.php')) {
    require NV_ROOTDIR . '/' . NV_DATADIR . '/config_global.php';
}

define('SYS_CACHE_TIMESTAMP', $global_config['timestamp'] ?? NV_CURRENTTIME);

// Vendor autoload
require NV_ROOTDIR . '/includes/vendor/autoload.php';
if (file_exists(NV_ROOTDIR . '/vendor/autoload.php')) {
    require NV_ROOTDIR . '/vendor/autoload.php';
}

// Xac dinh IP cua client
$ips = new NukeViet\Core\Ips();
define('NV_FORWARD_IP', $ips::$forward_ip);
define('NV_REMOTE_ADDR', $ips::$remote_addr);
define('NV_CLIENT_IP', $ips::$remote_ip);

// Ket noi voi class Error_handler
$ErrorHandler = new NukeViet\Core\Error($global_config);

$nv_Server = new NukeViet\Core\Server();

define('NV_SERVER_NAME', $nv_Server->getServerHost());
define('NV_SERVER_PROTOCOL', $nv_Server->getServerProtocol());
define('NV_SERVER_PORT', $nv_Server->getServerPort());

define('NV_MY_DOMAIN', $nv_Server->getOriginalDomain());
define('NV_BASE_SITEURL', $nv_Server->getWebsitePath() . '/');
define('NV_BASE_ADMINURL', NV_BASE_SITEURL . NV_ADMINDIR . '/');

if (file_exists(NV_ROOTDIR . '/' . NV_CONFIG_FILENAME)) {
    require realpath(NV_ROOTDIR . '/' . NV_CONFIG_FILENAME);
} else {
    if (file_exists(NV_ROOTDIR . '/install/index.php')) {
        header('Location: ' . NV_BASE_SITEURL . 'install/index.php');
    }
    exit();
}

if (empty($global_config['my_domains'])) {
    $global_config['my_domains'] = [NV_SERVER_NAME];
} else {
    $global_config['my_domains'] = array_map('trim', explode(',', strtolower($global_config['my_domains'])));
    // Nếu domain truy cập không đúng sẽ chuyển đến domain đúng (Báo mã 301)
    if (!in_array(NV_SERVER_NAME, $global_config['my_domains'], true)) {
        $location = $nv_Server->getOriginalProtocol() . '://' . $global_config['my_domains'][0] . $_SERVER['REQUEST_URI'];
        if (in_array(substr(php_sapi_name(), 0, 3), ['cgi', 'fpm'], true)) {
            header('Location: ' . $location);
            header('Status: 301 Moved Permanently');
        } else {
            header('Location: ' . $location, true, 301);
        }
        exit(0);
    }
}

// The Mozilla CA certificate store in PEM format
// This bundle was generated at Tue Apr 26 03:12:05 2022 GMT
// https://curl.se/docs/caextract.html
$global_config['default_cacert'] = NV_ROOTDIR . '/' . NV_CERTS_DIR . '/cacert.pem';

require NV_ROOTDIR . '/includes/ini.php';
require NV_ROOTDIR . '/includes/xtemplate.class.php';

define('SYSTEM_UPLOADS_DIR', NV_UPLOADS_DIR);
define('NV_FILES_DIR', NV_ASSETS_DIR);
define('NV_MOBILE_FILES_DIR', NV_ASSETS_DIR . '/mobile');
define('SYSTEM_CACHEDIR', NV_CACHEDIR);
define('NV_USERS_GLOBALTABLE', $db_config['prefix'] . '_users');
define('NV_GROUPS_GLOBALTABLE', $db_config['prefix'] . '_users_groups');
define('NV_GROUPSDETAIL_GLOBALTABLE', $db_config['prefix'] . '_users_groups_detail');
define('NUKEVIET_USER_AGENT', 'NUKEVIET CMS ' . $global_config['version'] . '. Developed by VINADES. Url: https://nukeviet.vn. Code: ' . md5($global_config['sitekey']));

// Neu khong co IP
if (NV_CLIENT_IP == 'none') {
    http_response_code(403);
    trigger_error('Error! Your IP address is not correct!', 256);
}

// Xac dinh IP của Zalo-webhook
if (isset($global_config['check_zaloip_expired'])) {
    if (
        (int) $global_config['check_zaloip_expired'] > NV_CURRENTTIME and
        isset($_SERVER['HTTP_USER_AGENT'], $_SERVER['HTTP_X_ZEVENT_SIGNATURE']) and
        $_SERVER['HTTP_USER_AGENT'] == 'ZaloWebhook' and
        !empty($_SERVER['HTTP_X_ZEVENT_SIGNATURE'])
    ) {
        include NV_ROOTDIR . '/includes/zalowebhookIP.php';
    }
}

if (isset($global_config['zaloWebhookIPs'])) {
    $global_config['crosssite_valid_ips'] += $global_config['zaloWebhookIPs'];
}

// Xac dinh Quoc gia
require NV_ROOTDIR . '/includes/countries.php';
if (isset($_SERVER['HTTP_CF_IPCOUNTRY'])) {
    // Cloudflare IP Geolocation
    $client_info['country'] = $_SERVER['HTTP_CF_IPCOUNTRY'];
} elseif (isset($_SERVER['GEOIP_COUNTRY_CODE'])) {
    // fastcgi_param GEOIP_COUNTRY_CODE
    $client_info['country'] = $_SERVER['GEOIP_COUNTRY_CODE'];
} elseif (isset($_SERVER['HTTP_GEOIP_COUNTRY_CODE'])) {
    // proxy_set_header GEOIP_COUNTRY_CODE
    $client_info['country'] = $_SERVER['HTTP_GEOIP_COUNTRY_CODE'];
} elseif (isset($_SERVER['COUNTRY_CODE'])) {
    // fastcgi_param COUNTRY_CODE
    $client_info['country'] = $_SERVER['COUNTRY_CODE'];
} else {
    $client_info['country'] = nv_getCountry_from_cookie(NV_CLIENT_IP);
}
$client_info['ip'] = NV_CLIENT_IP;

// Mui gio
require NV_ROOTDIR . '/includes/timezone.php';

if (empty($global_config['allow_sitelangs'])) {
    http_response_code(500);
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
    http_response_code(403);
    trigger_error('Hi and Good-bye!!!', 256);
}

// Chan proxy
if ($global_config['proxy_blocker'] != 0) {
    $client_info['is_proxy'] = $ips::nv_check_proxy();
    if (nv_is_blocker_proxy($client_info['is_proxy'], $global_config['proxy_blocker'])) {
        http_response_code(403);
        trigger_error('ERROR: You are behind a proxy server. Please disconnect and come again!', 256);
    }
}

if (stripos($_SERVER['PHP_SELF'], 'index.php') !== false) {
    defined('NV_SYSTEM') && require NV_ROOTDIR . '/includes/request_uri.php';
    defined('NV_ADMIN') && require NV_ROOTDIR . '/includes/request_uri_admin.php';
}

// Ket noi voi class xu ly request
$nv_Request = new NukeViet\Core\Request($global_config + ['https_only' => !empty($sys_info['https_only'])], NV_CLIENT_IP, $nv_Server);

$client_info['clid'] = $nv_Request->get_title('clid', 'cookie', '');
if (!preg_match('/^[a-z0-9]{32}$/', $client_info['clid'])) {
    $client_info['clid'] = md5(microtime(true) . bin2hex(openssl_random_pseudo_bytes(32)) . $global_config['sitekey']);
    $nv_Request->set_Cookie('clid', $client_info['clid'], 315360000);
}

// vd: HTTP/1.0
define('NV_HEADERSTATUS', $nv_Request->headerstatus);
// D:/AppServ/www
define('NV_DOCUMENT_ROOT', $nv_Request->doc_root);
// Hau to cua file cache
define('NV_CACHE_PREFIX', md5($global_config['sitekey'] . NV_SERVER_NAME));
// Kiem tra session cua nguoi dung
define('NV_CHECK_SESSION', md5(NV_CACHE_PREFIX . $nv_Request->session_id));
define('NV_USER_AGENT', $nv_Request->user_agent);
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

// Lấy thông tin cookie của user (Cần để dòng này trước dòng kết nối ngôn ngữ)
defined('NV_SYSTEM') && $user_cookie = NukeViet\Core\User::get_userlogin_hash();

// Ngon ngu
require NV_ROOTDIR . '/includes/language.php';
$nv_Lang = new \NukeViet\Core\Language();
$nv_Lang->loadGlobal();
require NV_ROOTDIR . '/includes/language/' . NV_LANG_INTERFACE . '/functions.php';

// Class ma hoa du lieu
$crypt = new NukeViet\Core\Encryption($global_config['sitekey']);

// Kiểm tra quyền truy cập vào load-files từ site khác
if (!$client_info['is_myreferer'] and (defined('NV_SYS_LOAD') or defined('NV_MOD_LOAD'))) {
    require NV_ROOTDIR . '/includes/core/check_access.php';
}

// Hiển thị nội dung file rssXsl/atomXsl
if (defined('NV_SYS_LOAD')) {
    if ($nv_Request->isset_request('xsl', 'get') and ($nv_Request->get_string('xsl', 'get') == 'rss' or $nv_Request->get_string('xsl', 'get') == 'atom')) {
        require NV_ROOTDIR . '/includes/core/xsl.php';
        exit(0);
    }
}

$cdn_is_enabled = false;
// Load các plugin
if (!empty($nv_plugins[NV_LANG_DATA])) {
    foreach ($nv_plugins[NV_LANG_DATA] as $_phook => $pdatahook) {
        foreach ($pdatahook as $_parea => $pdata) {
            foreach ($pdata as $priority => $_plugin) {
                $module_name = $_phook;
                $hook_module = $_plugin[1];
                $pid = $_plugin[2];
                if ($_plugin[0] == 'includes/plugin/cdn_js_css_image.php') {
                    $cdn_is_enabled = true;
                }
                require NV_ROOTDIR . '/' . $_plugin[0];
            }
        }
    }
    unset($_parea, $_plugin, $pdata, $priority, $module_name, $_phook, $pdatahook, $pid);
}

nv_apply_hook('', 'check_server');

if (defined('NV_ADMIN')) {
    $global_config['cdn_url'] = $global_config['nv_static_url'] = $global_config['assets_cdn_url'] = '';
} else {
    set_cdn_urls($global_config, $cdn_is_enabled, $client_info['country']);
}

// NV_STATIC_URL - URL của host chứa các file tĩnh hoặc đường dẫn tương đối của site
define('NV_STATIC_URL', (!empty($global_config['nv_static_url']) and empty($global_config['cdn_url'])) ? $global_config['nv_static_url'] . '/' : NV_BASE_SITEURL);
// ASSETS_STATIC_URL - jsDelivr zone URL đến thư mục assets của dự án trên github.com hoặc đường dẫn tương đối đến thư mục assets của site
define('ASSETS_STATIC_URL', !empty($global_config['assets_cdn_url']) ? $global_config['assets_cdn_url'] . 'assets' : NV_STATIC_URL . NV_ASSETS_DIR);
// ASSETS_LANG_STATIC_URL - Cũng là ASSETS_STATIC_URL nhưng chỉ áp dụng cho các file javascript liên quan đến ngôn ngữ Anh, Pháp, Việt
define('ASSETS_LANG_STATIC_URL', (in_array(NV_LANG_INTERFACE, ['en', 'fr', 'vi'], true)) ? ASSETS_STATIC_URL : NV_STATIC_URL . NV_ASSETS_DIR);
// AUTO_MINIFIED - Tự thu nhỏ dung lượng file nếu thêm '.min' vào trước phần mở rộng .css, .js (Chỉ áp dụng khi mạng CDN jsDelivr được bật)
define('AUTO_MINIFIED', (!empty($global_config['assets_cdn_url']) and in_array(NV_LANG_INTERFACE, ['en', 'fr', 'vi'], true)) ? '.min' : '');

// Khởi tạo hệ thống cache
$nv_Cache = NukeViet\Cache::getInstance($global_config);

// Xac dinh duong dan thuc den thu muc upload
define('NV_UPLOADS_REAL_DIR', NV_ROOTDIR . '/' . NV_UPLOADS_DIR);

// Xac dinh co phai AJAX hay khong
if (preg_match('/^[0-9]{10,}$/', $nv_Request->get_string('nocache', 'get', '')) and $client_info['is_myreferer'] === 1) {
    define('NV_IS_AJAX', true);
}

// Chan truy cap neu HTTP_USER_AGENT == 'none'
if (NV_USER_AGENT == 'none' and NV_ANTI_AGENT) {
    http_response_code(403);
    trigger_error('We\'re sorry. The software you are using to access our website is not allowed. Some examples of this are e-mail harvesting programs and programs that will copy websites to your hard drive. If you feel you have gotten this message in error, please send an e-mail addressed to admin. Your I.P. address has been logged. Thanks.', 256);
}

// xac dinh co phai User_Agent cua NukeViet hay khong
if (NV_USER_AGENT == NUKEVIET_USER_AGENT) {
    define('NV_IS_MY_USER_AGENT', true);
}

// Xac dinh browser cua client
$browser = new NukeViet\Client\Browser(NV_USER_AGENT);
$client_info['browser'] = [];
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
$client_info['client_os'] = [
    'key' => $browser->getPlatformKey(),
    'name' => $browser->getPlatform()
];

$is_mobile_tablet = $client_info['is_mobile'] . '-' . $client_info['is_tablet'];
if ($is_mobile_tablet != $nv_Request->get_string('is_mobile_tablet', 'session')) {
    $nv_Request->set_Session('is_mobile_tablet', $is_mobile_tablet);
    $nv_Request->unset_request(CURRENT_THEMETYPE_COOKIE_NAME . NV_LANG_DATA, 'cookie');
}

// Captcha
define('SRC_CAPTCHA', NV_BASE_SITEURL . 'sload.php?scaptcha=captcha&t=' . NV_CURRENTTIME);
define('GFX_WIDTH', NV_GFX_WIDTH);
define('GFX_HEIGHT', NV_GFX_HEIGHT);
define('GFX_NUM', NV_GFX_NUM);
define('GFX_MAXLENGTH', NV_GFX_NUM);
define('CAPTCHA_REFR_SRC', NV_STATIC_URL . NV_ASSETS_DIR . '/images/refresh.png');
define('CAPTCHA_REFRESH', $nv_Lang->getGlobal('captcharefresh'));
if ($nv_Request->isset_request('scaptcha', 'get')) {
    require NV_ROOTDIR . '/includes/core/captcha.php';
}

// Ket noi voi class chong flood
if (
    $global_config['is_flood_blocker'] and
    !$nv_Request->isset_request('admin', 'session') and
    !defined('NV_REMOTE_API') and
    (!$nv_Request->isset_request('second', 'get') or ($nv_Request->isset_request('second', 'get') and $client_info['is_myreferer'] != 1))
) {
    require NV_ROOTDIR . '/includes/core/flood_blocker.php';
}

// Hook sector 1
nv_apply_hook('', 'sector1');

// Bat dau phien lam viec cua Database
$db = $db_slave = new NukeViet\Core\Database($db_config);
if (empty($db->connect)) {
    if (!empty($global_config['closed_site'])) {
        nv_disable_site();
    } else {
        http_response_code(500);
        trigger_error('Sorry! Could not connect to data server', 256);
    }
}
$db_slave = nv_apply_hook('', 'db_slave_connect', [$db, $db_config], $db);
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
define('NV_INFORM_GLOBALTABLE', $db_config['prefix'] . '_inform');
define('NV_INFORM_STATUS_GLOBALTABLE', $db_config['prefix'] . '_inform_status');
define('NV_EMAILTEMPLATES_GLOBALTABLE', $db_config['prefix'] . '_emailtemplates');

define('NV_UPLOAD_GLOBALTABLE', $db_config['prefix'] . '_upload');
define('NV_BANNERS_GLOBALTABLE', $db_config['prefix'] . '_banners');
define('NV_COUNTER_GLOBALTABLE', $db_config['prefix'] . '_counter');

define('NV_PREFIXLANG', $db_config['prefix'] . '_' . NV_LANG_DATA);
define('NV_MODULES_TABLE', NV_PREFIXLANG . '_modules');
define('NV_BLOCKS_TABLE', NV_PREFIXLANG . '_blocks');
define('NV_MODFUNCS_TABLE', NV_PREFIXLANG . '_modfuncs');

define('NV_SEARCHKEYS_TABLE', NV_PREFIXLANG . '_searchkeys');
define('NV_REFSTAT_TABLE', NV_PREFIXLANG . '_referer_stats');

// Lấy tổng số thông báo chưa xem
if (defined('NV_SYS_LOAD')) {
    if (defined('NV_IS_AJAX') and $nv_Request->isset_request('__checkInform, __userid, __groups, _csrf', 'post')) {
        require NV_ROOTDIR . '/includes/core/check_inform.php';
    }
}

$sql = 'SELECT lang, module, config_name, config_value FROM ' . NV_CONFIG_GLOBALTABLE . " WHERE lang='" . NV_LANG_DATA . "' or (lang='sys' AND (module='site' OR module='banners')) ORDER BY module ASC";
$list = $nv_Cache->db($sql, '', 'settings');

foreach ($list as $row) {
    if (($row['lang'] == NV_LANG_DATA and $row['module'] == 'global') or ($row['lang'] == 'sys' and $row['module'] == 'site')) {
        $global_config[$row['config_name']] = $row['config_value'];
    } else {
        $module_config[$row['module']][$row['config_name']] = $row['config_value'];
    }
}

$global_config['site_int_phone'] = '';
if (!empty($global_config['site_phone']) and preg_match('/^(.+)\[([0-9\*\#\+\-\.\,\;]+)\]$/', $global_config['site_phone'], $matches)) {
    $global_config['site_phone'] = $matches[1];
    $global_config['site_int_phone'] = $matches[2];
}

if (!empty($global_config['custom_configs'])) {
    $custom_configs = json_decode($global_config['custom_configs'], true);
    $global_config['custom_configs'] = [];
    foreach ($custom_configs as $key => $val) {
        $global_config['custom_configs'][$key] = is_array($val) ? $val[0] : $val;
    }
} else {
    $global_config['custom_configs'] = [];
}

nv_apply_hook('', 'zalo_webhook');

if (!empty($global_config['nv_csp_script_nonce'])) {
    define('NV_SCRIPT_NONCE', bin2hex(openssl_random_pseudo_bytes(10)));
}

// Check https
if (empty($sys_info['http_only']) and (($global_config['ssl_https'] == 1 or ($global_config['ssl_https'] == 2 and defined('NV_ADMIN'))) and ($nv_Server->getOriginalProtocol() !== 'https'))) {
    nv_redirect_location('https://' . $nv_Server->getOriginalHost() . $nv_Server->getOriginalPort() . $_SERVER['REQUEST_URI']);
}

if ($global_config['is_user_forum']) {
    define('NV_IS_USER_FORUM', true);
}

if (!empty($global_config['openid_servers'])) {
    $global_config['openid_servers'] = explode(',', $global_config['openid_servers']);
    define('NV_OPENID_ALLOWED', true);
} else {
    $global_config['openid_servers'] = [];
}

if (empty($global_config['site_logo'])) {
    $global_config['site_logo'] = NV_ASSETS_DIR . '/images/logo.png';
}

$global_config['array_theme_type'] = !empty($global_config['theme_type']) ? explode(',', $global_config['theme_type']) : [];
$global_config['array_preview_theme'] = !empty($global_config['preview_theme']) ? explode(',', $global_config['preview_theme']) : [];
$global_config['array_user_allowed_theme'] = empty($global_config['user_allowed_theme']) ? [] : json_decode($global_config['user_allowed_theme'], true);

define('NV_MAIN_DOMAIN', (!empty($global_config['site_domain']) and in_array($global_config['site_domain'], $global_config['my_domains'], true)) ? str_replace(NV_SERVER_NAME, $global_config['site_domain'], NV_MY_DOMAIN) : NV_MY_DOMAIN);

!empty($global_config['smtp_password']) && $global_config['smtp_password'] = $crypt->decrypt($global_config['smtp_password']);
if ($sys_info['ini_set_support']) {
    ini_set('sendmail_from', $global_config['site_email']);
}
if (!isset($global_config['upload_checking_mode']) or !in_array($global_config['upload_checking_mode'], [
    'mild',
    'lite',
    'none'
], true)) {
    $global_config['upload_checking_mode'] = 'strong';
}
define('UPLOAD_CHECKING_MODE', $global_config['upload_checking_mode']);

if (defined('NV_ADMIN')) {
    if (!file_exists(NV_ROOTDIR . '/includes/language/' . NV_LANG_DATA . '/global.php')) {
        if ($global_config['lang_multi']) {
            $nv_Request->set_Cookie(DATA_LANG_COOKIE_NAME, $global_config['site_lang'], NV_LIVE_COOKIE_TIME);
        }
        nv_redirect_location(NV_BASE_ADMINURL);
    }
    if (!file_exists(NV_ROOTDIR . '/includes/language/' . NV_LANG_INTERFACE . '/global.php')) {
        if ($global_config['lang_multi']) {
            $nv_Request->set_Cookie(INT_LANG_COOKIE_NAME, $global_config['site_lang'], NV_LIVE_COOKIE_TIME);
        }
        nv_redirect_location(NV_BASE_ADMINURL);
    }
}

// Cronjobs execute
$global_config['cronjobs_next_time'] = (int) $global_config['cronjobs_last_time'] + (int) $global_config['cronjobs_interval'] * 60;
if ($global_config['cronjobs_launcher'] == 'server' and $nv_Request->isset_request('loadcron', 'get')) {
    if ($nv_Request->get_title('loadcron', 'get') == md5('cronjobs' . $global_config['sitekey']) and NV_CURRENTTIME >= $global_config['cronjobs_next_time']) {
        $db->query('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = '" . NV_CURRENTTIME . "' WHERE lang = 'sys' AND module = 'site' AND config_name = 'cronjobs_last_time'");
        $nv_Cache->delMod('settings');
        require NV_ROOTDIR . '/includes/core/cronjobs.php';
    }
    exit();
}
if ($global_config['cronjobs_launcher'] == 'system') {
    if (defined('NV_SYS_LOAD') and $nv_Request->isset_request('__cronjobs', 'post')) {
        require NV_ROOTDIR . '/includes/core/cronjobs.php';
        exit(0);
    }
    if (!defined('NV_SYS_LOAD') and !defined('NV_MOD_LOAD') and !defined('NV_IS_AJAX') and !empty($client_info['is_myreferer'])) {
        if (NV_CURRENTTIME >= $global_config['cronjobs_next_time']) {
            $db->query('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = '" . NV_CURRENTTIME . "' WHERE lang = 'sys' AND module = 'site' AND config_name = 'cronjobs_last_time'");
            $nv_Cache->delMod('settings');
            post_async(NV_BASE_SITEURL . 'sload.php', ['__cronjobs' => 1]);
        }
    }
}

// Gửi mail từ luồng truy vấn không đồng bộ
if (defined('NV_SYS_LOAD')) {
    if ($nv_Request->isset_request('__sendmail', 'post')) {
        require NV_ROOTDIR . '/includes/core/async_sendmail.php';
        exit(0);
    } elseif ($nv_Request->isset_request('__sendmail_template', 'post')) {
        require NV_ROOTDIR . '/includes/core/async_sendmail_template.php';
        exit(0);
    }
}

// Quản lý thẻ meta, header các máy chủ tìm kiếm
$nv_BotManager = new NukeViet\Seo\BotManager($global_config['private_site']);

// Kiem tra tu cach admin
if (defined('NV_IS_ADMIN') or defined('NV_IS_SPADMIN')) {
    http_response_code(403);
    trigger_error('Hacking attempt', 256);
}

// Kiem tra ton tai goi cap nhat va tu cach admin
$nv_check_update = file_exists(NV_ROOTDIR . '/install/update_data.php');
define('ADMIN_LOGIN_MODE', ($nv_check_update or ($global_config['idsite'] > 0 and !empty($global_config['closed_subsite']))) ? 1 : (empty($global_config['closed_site']) ? 3 : $global_config['closed_site']));

$admin_cookie = $nv_Request->get_bool('admin', 'session', false);
if (!empty($admin_cookie)) {
    require NV_ROOTDIR . '/includes/core/admin_access.php';
    require NV_ROOTDIR . '/includes/core/is_admin.php';
}

// Dinh chi hoat dong cua site
if ($nv_check_update and !defined('NV_IS_UPDATE')) {
    // Trong quá trình nâng cấp, đình chỉ nếu không là admin tối cao
    if (!defined('NV_ADMIN') and !defined('NV_IS_GODADMIN')) {
        nv_disable_site();
    }
} elseif (!defined('NV_ADMIN') and !defined('NV_IS_ADMIN')) {
    if (!empty($global_config['closed_site']) or ($global_config['idsite'] > 0 and !empty($global_config['closed_subsite']))) {
        nv_disable_site();
    }
    if (!in_array($nv_Request->get_string(NV_LANG_VARIABLE, 'get,post', NV_LANG_DATA), $global_config['allow_sitelangs'], true) or !in_array(NV_LANG_DATA, $global_config['allow_sitelangs'], true)) {
        // Chuyển hướng nếu ngôn ngữ chưa được kích hoạt ngoài site cho người dùng
        nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $global_config['site_lang']);
    }
}
unset($nv_check_update);

nv_apply_hook('', 'modify_global_config');

$sys_mods = nv_sys_mods();

define('PCLZIP_TEMPORARY_DIR', NV_ROOTDIR . '/' . NV_TEMP_DIR . '/');
// Hook sector 2
nv_apply_hook('', 'sector2');
