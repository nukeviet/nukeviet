<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

$_SERVER['HTTP_HOST'] = $_ENV['HTTP_HOST'];
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '';
$_SERVER['SERVER_NAME'] = $_ENV['HTTP_HOST'];
$_SERVER['SERVER_PORT'] = '80';
$_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
$_SERVER['HTTP_USER_AGENT'] = 'NUKEVIET CMS. Developed by VINADES. Url: https://nukeviet.vn';
$_SERVER['SERVER_SOFTWARE'] = 'Apache';

require NV_ROOTDIR . '/includes/vendor/autoload.php';

define('NV_ADMIN', true);
if (!defined('NV_MAINFILE')) {
    define('NV_MAINFILE', true);
}
define('NV_SITE_TIMEZONE_GMT_NAME', preg_replace('/^([\+|\-]{1}\d{2})(\d{2})$/', '$1:$2', date('O')));

global $db, $db_slave, $global_config, $meta_property, $nv_parse_ini_timezone, $language_array, $nv_plugins, $db_config;
global $nv_default_regions, $nv_Lang;

$global_config = [];
$db_config = [
    'prefix' => 'nv5',
];

require NV_ROOTDIR . '/includes/constants.php';
$path_config = realpath(NV_ROOTDIR . '/' . NV_CONFIG_FILENAME);
if ($path_config) {
    require $path_config;
}
require NV_ROOTDIR . '/' . NV_DATADIR . '/config_global.php';

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

define('NV_LANG_DATA', $global_config['allow_sitelangs'][0]);
define('NV_LANG_INTERFACE', $global_config['allow_sitelangs'][0]);

define('NV_CURRENTTIME', time());

$nv_Server = new NukeViet\Core\Server();

define('NV_SERVER_NAME', $nv_Server->getServerHost());
define('NV_SERVER_PROTOCOL', $nv_Server->getServerProtocol());
define('NV_SERVER_PORT', $nv_Server->getServerPort());

define('NV_CACHE_PREFIX', md5(($global_config['sitekey'] ?? '') . NV_SERVER_NAME));

if ($path_config) {
    $db = $db_slave = new NukeViet\Core\Database($db_config);
} else {
    $db = $db_slave = null;
}

$nv_Lang = new NukeViet\Core\Language();
$nv_Lang->loadGlobal();

require NV_ROOTDIR . '/includes/functions.php';
require NV_ROOTDIR . '/includes/core/filesystem_functions.php';
