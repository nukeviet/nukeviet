<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * nv_groups_list()
 *
 * @param string $mod_data
 * @return array
 */
function nv_groups_list($mod_data = 'users')
{
    global $nv_Cache;
    $cache_file = 'groups_list_' . NV_CACHE_PREFIX . '.cache';
    if (($cache = $nv_Cache->getItem($mod_data, $cache_file)) != false) {
        return unserialize($cache);
    }
    global $db, $db_config, $global_config, $nv_Lang;

    $groups = [];
    $_mod_table = ($mod_data == 'users') ? NV_USERS_GLOBALTABLE : $db_config['prefix'] . '_' . $mod_data;
    $result = $db->query('SELECT g.group_id, d.title, g.idsite FROM ' . $_mod_table . '_groups AS g LEFT JOIN ' . $_mod_table . "_groups_detail d ON ( g.group_id = d.group_id AND d.lang='" . NV_LANG_DATA . "' ) WHERE (g.idsite = " . $global_config['idsite'] . ' OR (g.idsite =0 AND g.siteus = 1)) ORDER BY g.idsite, g.weight');
    while ($row = $result->fetch()) {
        if ($row['group_id'] < 9) {
            $row['title'] = $nv_Lang->getGlobal('level' . $row['group_id']);
        }
        $groups[$row['group_id']] = ($global_config['idsite'] > 0 and empty($row['idsite'])) ? '<strong>' . $row['title'] . '</strong>' : $row['title'];
    }
    $nv_Cache->setItem($mod_data, $cache_file, serialize($groups));

    return $groups;
}

/**
 * nv_groups_post()
 *
 * @param array $groups_view
 * @return array
 */
function nv_groups_post($groups_view)
{
    $groups_view = array_map('intval', $groups_view);
    if (in_array(6, $groups_view, true)) {
        return [6];
    }
    if (in_array(4, $groups_view, true)) {
        return array_intersect($groups_view, [4, 5, 7]);
    }
    if (in_array(3, $groups_view, true)) {
        return array_diff($groups_view, [1, 2]);
    }
    if (in_array(2, $groups_view, true)) {
        return array_diff($groups_view, [1]);
    }
    if (empty($groups_view)) {
        return [1];
    }

    return $groups_view;
}

/**
 * nv_var_export()
 *
 * @param array $var_array
 * @param bool  $isInt
 * @return string
 */
function nv_var_export($var_array, $isInt = false)
{
    $patterns = [
        '/[\s\t\n\r]+/' => ' ',
        '/array\s*\(\s*/' => '[',
        '/\s*,?\s*\)\s*/' => ']',
        '/\s*=>\s*/' => ' => ',
        '/[\s]+,[\s]+/' => ', '
    ];
    if ($isInt) {
        $patterns['/\'0\'/'] = '0';
        $patterns['/\'(-?[1-9][0-9]*)\'/'] = '$1';
    }

    $export = var_export($var_array, true);

    return preg_replace(array_keys($patterns), array_values($patterns), $export);
}

/**
 * nv_save_file_config_global()
 *
 * @return bool
 */
function nv_save_file_config_global()
{
    global $nv_Cache, $db, $global_config, $db_config, $crypt;

    if ($global_config['idsite']) {
        return false;
    }

    $content_config = '<?php' . "\n\n";
    $content_config .= NV_FILEHEAD . "\n\n";
    $content_config .= "if (!defined('NV_MAINFILE')) {\n    exit('Stop!!!');\n}\n\n";

    $config_variable = [];
    $allowed_html_tags = '';
    $sql = 'SELECT module, config_name, config_value FROM ' . NV_CONFIG_GLOBALTABLE . " WHERE lang='sys' AND (module='global' OR module='define') ORDER BY config_name ASC";
    $result = $db->query($sql);

    while ([$c_module, $c_config_name, $c_config_value] = $result->fetch(3)) {
        if ($c_module == 'define') {
            if (preg_match('/^\d+$/', $c_config_value)) {
                $content_config .= "define('" . strtoupper($c_config_name) . "', " . $c_config_value . ");\n";
            } else {
                $content_config .= "define('" . strtoupper($c_config_name) . "', '" . $c_config_value . "');\n";
            }
            if ($c_config_name == 'nv_allowed_html_tags') {
                $allowed_html_tags = $c_config_value;
            }
        } else {
            $config_variable[$c_config_name] = $c_config_value;
        }
    }

    $nv_eol = strtoupper(substr(PHP_OS, 0, 3) == 'WIN') ? '"\r\n"' : (strtoupper(substr(PHP_OS, 0, 3) == 'MAC') ? '"\r"' : '"\n"');
    $upload_max_filesize = min(nv_converttoBytes(ini_get('upload_max_filesize')), nv_converttoBytes(ini_get('post_max_size')), $config_variable['nv_max_size']);

    $content_config .= "define('NV_EOL', " . $nv_eol . ");\n";
    $content_config .= "define('NV_UPLOAD_MAX_FILESIZE', " . (float) $upload_max_filesize . ");\n";

    $my_domains = array_map('trim', explode(',', $config_variable['my_domains']));
    $my_domains[] = NV_SERVER_NAME;
    $config_variable['my_domains'] = implode(',', array_unique($my_domains));

    $config_variable['check_rewrite_file'] = nv_check_rewrite_file();
    $config_variable['allow_request_mods'] = NV_ALLOW_REQUEST_MODS != '' ? NV_ALLOW_REQUEST_MODS : 'request';
    $config_variable['request_default_mode'] = NV_REQUEST_DEFAULT_MODE != '' ? trim(NV_REQUEST_DEFAULT_MODE) : 'request';

    $config_variable['log_errors_list'] = NV_LOG_ERRORS_LIST;
    $config_variable['display_errors_list'] = NV_DISPLAY_ERRORS_LIST;
    $config_variable['send_errors_list'] = NV_SEND_ERRORS_LIST;
    $config_variable['error_log_path'] = NV_LOGS_DIR . '/error_logs';
    $config_variable['error_log_filename'] = NV_ERRORLOGS_FILENAME;
    $config_variable['notice_log_filename'] = NV_NOTICELOGS_FILENAME;
    $config_variable['error_log_fileext'] = NV_LOGS_EXT;

    $config_name_array = ['file_allowed_ext', 'forbid_extensions', 'forbid_mimes', 'allow_sitelangs', 'allow_request_mods', 'config_sso'];
    $config_name_json = ['crosssite_valid_domains', 'crosssite_valid_ips', 'crosssite_allowed_variables', 'crossadmin_valid_domains', 'crossadmin_valid_ips', 'domains_whitelist', 'ip_allow_null_origin', 'zaloWebhookIPs', 'end_url_variables', 'cdn_url', 'region'];
    $config_name_encrypted = ['redis_password', 'fpt_user_pass', 'smtp_password'];

    foreach ($config_variable as $c_config_name => $c_config_value) {
        if (in_array($c_config_name, $config_name_array, true)) {
            // Tách chuỗi lưu thành array
            if (!empty($c_config_value)) {
                $c_config_value = "'" . implode("','", array_map('trim', explode(',', $c_config_value))) . "'";
            } else {
                $c_config_value = '';
            }
            $content_config .= "\$global_config['" . $c_config_name . "'] = [" . $c_config_value . "];\n";
        } elseif (in_array($c_config_name, $config_name_json, true)) {
            // json_decode => Lưu thành biến
            if (empty($c_config_value)) {
                $value = [];
            } else {
                $value = json_decode($c_config_value, true);
                if (!is_array($value)) {
                    $value = [];
                }
                if ($c_config_name == 'cdn_url' and json_last_error() !== JSON_ERROR_NONE) {
                    $value = [$c_config_value => [1]];
                }
            }

            if ($c_config_name == 'end_url_variables') {
                $_value = [];
                if (!empty($value)) {
                    foreach ($value as $k => $val) {
                        $val = "'" . implode("','", $val) . "'";
                        $_value[] = "'" . $k . "' => [" . $val . ']';
                    }
                }
                $value = !empty($_value) ? implode(',', $_value) : '';
                $content_config .= "\$global_config['" . $c_config_name . "'] = [" . $value . "];\n";
            } else {
                $content_config .= "\$global_config['" . $c_config_name . "'] = " . nv_var_export($value) . ";\n";
            }
        } elseif (in_array($c_config_name, $config_name_encrypted, true) and !empty($c_config_value)) {
            $content_config .= "\$global_config['" . $c_config_name . "'] = '" . ($crypt->decrypt($c_config_value) ?? '') . "';\n";
        } else {
            if (preg_match('/^(0|[1-9][0-9]*)$/', $c_config_value) and $c_config_name != 'facebook_client_id') {
                $content_config .= "\$global_config['" . $c_config_name . "'] = " . $c_config_value . ";\n";
            } else {
                $c_config_value = nv_unhtmlspecialchars($c_config_value);
                if (!preg_match("/^[a-z0-9\-\_\.\,\;\:\@\/\\s]+$/i", $c_config_value) and $c_config_name != 'my_domains') {
                    $c_config_value = nv_htmlspecialchars($c_config_value);
                }
                $content_config .= "\$global_config['" . $c_config_name . "'] = '" . $c_config_value . "';\n";
            }
        }
    }

    // Các ngôn ngữ data đã thiết lập
    $sql = 'SELECT lang FROM ' . $db_config['prefix'] . '_setup_language WHERE setup=1 ORDER BY weight ASC';
    $result = $db->query($sql);

    $setup_langs = [];
    while ($row = $result->fetch()) {
        $setup_langs[] = $row['lang'];
    }
    $content_config .= "\$global_config['setup_langs'] = ['" . implode("','", $setup_langs) . "'];\n";

    //allowed_html_tags
    if (!empty($allowed_html_tags)) {
        $allowed_html_tags = "'" . implode("','", array_map('trim', explode(',', $allowed_html_tags))) . "'";
    } else {
        $allowed_html_tags = '';
    }
    $content_config .= "\$global_config['allowed_html_tags'] = [" . $allowed_html_tags . "];\n";

    //Xac dinh cac search_engine
    $engine_allowed = (file_exists(NV_ROOTDIR . '/' . NV_DATADIR . '/search_engine.xml')) ? nv_object2array(simplexml_load_file(NV_ROOTDIR . '/' . NV_DATADIR . '/search_engine.xml')) : [];
    $content_config .= "\$global_config['engine_allowed'] = " . nv_var_export($engine_allowed) . ";\n";
    $content_config .= "\n";

    $language_array = nv_parse_ini_file(NV_ROOTDIR . '/includes/ini/langs.ini', true);
    $tmp_array = [];
    $lang_array_exit = nv_scandir(NV_ROOTDIR . '/includes/language', '/^[a-z]{2}+$/');
    foreach ($lang_array_exit as $lang) {
        $tmp_array[$lang] = $language_array[$lang];
    }
    unset($language_array);
    $content_config .= '$language_array = ' . nv_var_export($tmp_array) . ";\n";
    $content_config .= "\n";

    $nv_plugins = [];
    foreach ($setup_langs as $lang) {
        $nv_plugins[$lang] = [];
        $_sql = 'SELECT * FROM ' . $db_config['prefix'] . '_plugins WHERE plugin_lang=\'all\' OR plugin_lang=\'' . $lang . '\' ORDER BY hook_module, plugin_area ASC, weight ASC';
        $_query = $db->query($_sql);
        while ($row = $_query->fetch()) {
            // Xác định HOOK gọi từ module hay hệ thống
            if (!isset($nv_plugins[$lang][$row['hook_module']])) {
                $nv_plugins[$lang][$row['hook_module']] = [];
            }
            // Xác định tiếp HOOK theo TAG
            if (!isset($nv_plugins[$lang][$row['hook_module']][$row['plugin_area']])) {
                $nv_plugins[$lang][$row['hook_module']][$row['plugin_area']] = [];
            }
            // Xác định file plugin
            if (empty($row['plugin_module_file'])) {
                $plugin_file = 'includes/plugin/' . $row['plugin_file'];
            } else {
                $plugin_file = 'modules/' . $row['plugin_module_file'] . '/hooks/' . $row['plugin_file'];
            }
            $nv_plugins[$lang][$row['hook_module']][$row['plugin_area']][$row['weight']] = [
                $plugin_file, $row['plugin_module_name'], $row['pid']
            ];
        }
    }
    // Sắp xếp lại
    foreach ($nv_plugins as $lang => $langdata) {
        foreach ($langdata as $_hookmod => $_datahook) {
            foreach ($_datahook as $_tag => $_data) {
                krsort($nv_plugins[$lang][$_hookmod][$_tag]);
            }
        }
    }
    $content_config .= '$nv_plugins = ' . nv_var_export($nv_plugins) . ";\n";

    $return = file_put_contents(NV_ROOTDIR . '/' . NV_DATADIR . '/config_global.php', $content_config, LOCK_EX);
    $nv_Cache->delAll();

    //Resets the contents of the opcode cache
    if (function_exists('opcache_reset')) {
        opcache_reset();
    }

    return $return;
}

/**
 * nv_geVersion()
 *
 * @param int $updatetime
 * @return mixed
 */
function nv_geVersion($updatetime = 3600)
{
    global $global_config, $nv_Lang;

    $my_file = NV_ROOTDIR . '/' . NV_CACHEDIR . '/nukeviet.version.' . NV_LANG_INTERFACE . '.xml';

    $xmlcontent = false;

    $p = NV_CURRENTTIME - $updatetime;

    if (file_exists($my_file) and @filemtime($my_file) > $p) {
        $xmlcontent = simplexml_load_file($my_file);
    } else {
        $NV_Http = new NukeViet\Http\Http($global_config, NV_TEMP_DIR);

        $args = [
            'headers' => [
                'Referer' => NV_MY_DOMAIN,
            ],
            'body' => [
                'lang' > NV_LANG_INTERFACE,
                'basever' => $global_config['version'],
                'mode' => 'getsysver'
            ]
        ];

        $array = $NV_Http->post(NUKEVIET_STORE_APIURL, $args);
        $array = (is_array($array) and !empty($array['body'])) ? @unserialize($array['body']) : [];

        $error = '';
        if (!empty(NukeViet\Http\Http::$error)) {
            $error = nv_http_get_lang(NukeViet\Http\Http::$error);
        } elseif (!isset($array['error']) or !isset($array['data']) or !isset($array['pagination']) or !is_array($array['error']) or !is_array($array['data']) or !is_array($array['pagination']) or (!empty($array['error']) and (!isset($array['error']['level']) or empty($array['error']['message'])))) {
            $error = $nv_Lang->getGlobal('error_valid_response');
        } elseif (!empty($array['error']['message'])) {
            $error = $array['error']['message'];
        }

        if (!empty($error)) {
            return $error;
        }

        $array = $array['data'];

        $content = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<cms>\n\t<name><![CDATA[" . $array['name'] . "]]></name>\n\t<version><![CDATA[" . $array['version'] . "]]></version>\n\t<date><![CDATA[" . $array['date'] . "]]></date>\n\t<message><![CDATA[" . $array['message'] . "]]></message>\n\t<link><![CDATA[" . $array['link'] . "]]></link>\n\t<updateable><![CDATA[" . $array['updateable'] . "]]></updateable>\n\t<updatepackage><![CDATA[" . $array['updatepackage'] . "]]></updatepackage>\n</cms>";

        $xmlcontent = simplexml_load_string($content);

        if ($xmlcontent !== false) {
            file_put_contents($my_file, $content);
        }
    }

    return $xmlcontent;
}

/**
 * nv_version_compare()
 *
 * @param string $version1
 * @param string $version2
 * @return int
 */
function nv_version_compare($version1, $version2)
{
    $v1 = explode('.', $version1);
    $v2 = explode('.', $version2);

    if ($v1[0] > $v2[0]) {
        return 1;
    }

    if ($v1[0] < $v2[0]) {
        return -1;
    }

    if ($v1[1] > $v2[1]) {
        return 1;
    }

    if ($v1[1] < $v2[1]) {
        return -1;
    }

    if ($v1[2] > $v2[2]) {
        return 1;
    }

    if ($v1[2] < $v2[2]) {
        return -1;
    }

    return 0;
}

/**
 * nv_check_rewrite_file()
 *
 * @return bool
 */
function nv_check_rewrite_file()
{
    global $sys_info, $global_config;

    if ($sys_info['supports_rewrite'] == 'rewrite_mode_apache') {
        if (!file_exists(NV_ROOTDIR . '/.htaccess')) {
            return false;
        }

        $htaccess = @file_get_contents(NV_ROOTDIR . '/.htaccess');

        return preg_match('/\#nukeviet\_rewrite\_start(.*)\#nukeviet\_rewrite\_end/is', $htaccess);
    }

    if ($sys_info['supports_rewrite'] == 'rewrite_mode_iis') {
        if (!file_exists(NV_ROOTDIR . '/web.config')) {
            return false;
        }

        $web_config = @file_get_contents(NV_ROOTDIR . '/web.config');

        return preg_match('/\<\!\-\-\s*nukeviet\_rewrite\_start\s*\-\-\>(.*)\<\!\-\-\s*nukeviet\_rewrite\_end\s*\-\-\>/is', $web_config);
    }

    if ($sys_info['supports_rewrite'] == 'nginx') {
        return true;
    }

    return (bool) ($global_config['check_rewrite_file'] ?? false);
}

/**
 * @param array $array_config_global
 * @return mixed
 */
function nv_rewrite_change($array_config_global = [])
{
    global $sys_info, $module_name, $global_config;

    $rewrite_rule = $filename = '';
    $md5_old_file = $md5_new_file = $change_file = '';
    $Sconfig = new NukeViet\Core\Sconfig($global_config);
    if ((!empty($array_config_global['rewrite_endurl']) and $array_config_global['rewrite_endurl'] != $global_config['rewrite_endurl']) or (!empty($array_config_global['rewrite_exturl']) and $array_config_global['rewrite_exturl'] != $global_config['rewrite_exturl'])) {
        $Sconfig->setRewriteExts([$array_config_global['rewrite_endurl'], $array_config_global['rewrite_exturl']]);
    }
    if (isset($array_config_global['admin_rewrite'])) {
        $Sconfig->setAdminRewrite($array_config_global['admin_rewrite']);
    }

    if ($sys_info['supports_rewrite'] == 'nginx') {
        return [true, true];
    }
    if ($sys_info['supports_rewrite'] == 'rewrite_mode_iis') {
        $filename = NV_ROOTDIR . '/web.config';
        $change_file = 'web.config';

        $rewrite_rule = $Sconfig->iisRewriteRule();

        if (file_exists($filename)) {
            $md5_old_file = md5_file($filename);
        }
    } elseif ($sys_info['supports_rewrite'] == 'rewrite_mode_apache') {
        $filename = NV_ROOTDIR . '/.htaccess';
        $change_file = '.htaccess';

        $rewrite_rule = $Sconfig->apacheRewriteRule();

        if (file_exists($filename)) {
            $md5_old_file = md5_file($filename);
        }
    }

    $return = true;
    if (!empty($filename) and !empty($rewrite_rule)) {
        try {
            $filesize = file_put_contents($filename, trim($rewrite_rule) . "\n", LOCK_EX);
            if (empty($filesize)) {
                $return = false;
            } else {
                $md5_new_file = md5_file($filename);
            }
        } catch (Throwable $e) {
            $return = false;
        }
    }

    if (strcmp($md5_new_file, $md5_old_file) !== 0) {
        nv_insert_notification($module_name, 'server_config_file_changed', ['file' => $change_file], 0, 0, 0, 1, 1);
    }

    return [$return, NV_BASE_SITEURL . basename($filename)];
}

/**
 * @param array $my_domains
 * @param ?int|?bool $admin_rewrite
 * @return boolean[]|string[]
 */
function nv_server_config_change($my_domains = [], $admin_rewrite = null)
{
    global $sys_info, $global_config, $module_name;

    $config_contents = $filename = '';
    $md5_old_file = $md5_new_file = $change_file = '';
    $Sconfig = new NukeViet\Core\Sconfig($global_config);
    if (!empty($my_domains)) {
        $Sconfig->setMyDomains($my_domains);
    }
    if (!is_null($admin_rewrite)) {
        $Sconfig->setAdminRewrite($admin_rewrite);
    }

    if ($sys_info['supports_rewrite'] == 'rewrite_mode_apache') {
        $filename = NV_ROOTDIR . '/.htaccess';
        $change_file = '.htaccess';

        $config_contents = $Sconfig->apacheConfigs();

        if (file_exists($filename)) {
            $md5_old_file = md5_file($filename);
        }
    }

    $return = true;
    if (!empty($filename) and !empty($config_contents)) {
        try {
            $filesize = file_put_contents($filename, $config_contents . "\n", LOCK_EX);
            if (empty($filesize)) {
                $return = false;
            } else {
                $md5_new_file = md5_file($filename);
            }
        } catch (Throwable $e) {
            $return = false;
        }
    }

    if (strcmp($md5_new_file, $md5_old_file) !== 0) {
        nv_insert_notification($module_name, 'server_config_file_changed', ['file' => $change_file], 0, 0, 0, 1, 1);
    }

    return [$return, basename($filename)];
}

/**
 * nv_getExtVersion()
 *
 * @param int $updatetime
 * @return mixed
 */
function nv_getExtVersion($updatetime = 3600)
{
    global $global_config, $nv_Lang, $db, $db_config;

    $my_file = NV_ROOTDIR . '/' . NV_CACHEDIR . '/extensions.version.' . NV_LANG_INTERFACE . '.xml';

    $xmlcontent = false;

    $p = NV_CURRENTTIME - $updatetime;

    if (file_exists($my_file) and @filemtime($my_file) > $p) {
        $xmlcontent = simplexml_load_file($my_file);
    } else {
        $sql = 'SELECT * FROM ' . $db_config['prefix'] . '_setup_extensions WHERE title=basename ORDER BY title ASC';
        $result = $db->query($sql);

        $array = $array_ext_ids = [];
        while ($row = $result->fetch()) {
            $row['version'] = explode(' ', $row['version']);

            $array[$row['title']] = [
                'id' => $row['id'],
                'type' => $row['type'],
                'name' => $row['title'],
                'current_version' => trim($row['version'][0]),
                'current_release' => trim($row['version'][1]),
                'remote_version' => '',
                'remote_release' => 0,
                'updateable' => [], // Thong tin cac phien ban co the update
                'author' => $row['author'],
                'license' => '',
                'mode' => $row['is_sys'] ? 'sys' : 'other',
                'message' => $row['note'],
                'link' => '',
                'support' => '',
                'origin' => false,
            ];

            if (!empty($row['id'])) {
                $array_ext_ids[] = $row['id'];
            }
        }

        if (!empty($array_ext_ids)) {
            $NV_Http = new NukeViet\Http\Http($global_config, NV_TEMP_DIR);

            $args = [
                'headers' => [
                    'Referer' => NV_MY_DOMAIN,
                ],
                'body' => [
                    'lang' > NV_LANG_INTERFACE,
                    'basever' => $global_config['version'],
                    'mode' => 'checkextver',
                    'ids' => implode(',', $array_ext_ids),
                ]
            ];

            $apidata = $NV_Http->post(NUKEVIET_STORE_APIURL, $args);
            $apidata = (is_array($apidata) and !empty($apidata['body'])) ? @unserialize($apidata['body']) : [];

            $error = '';
            if (!empty(NukeViet\Http\Http::$error)) {
                $error = nv_http_get_lang(NukeViet\Http\Http::$error);
            } elseif (!isset($apidata['error']) or !isset($apidata['data']) or !isset($apidata['pagination']) or !is_array($apidata['error']) or !is_array($apidata['data']) or !is_array($apidata['pagination']) or (!empty($apidata['error']) and (!isset($apidata['error']['level']) or empty($apidata['error']['message'])))) {
                $error = $nv_Lang->getGlobal('error_valid_response');
            } elseif (!empty($apidata['error']['message'])) {
                $error = $apidata['error']['message'];
            }

            if (!empty($error)) {
                return $error;
            }

            $apidata = $apidata['data'];
        }

        $content = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<cms>\n";

        foreach ($array as $row) {
            if (isset($apidata[$row['id']])) {
                $row['remote_version'] = $apidata[$row['id']]['lastest_version'];
                $row['remote_release'] = $apidata[$row['id']]['lastest_release'];
                $row['updateable'] = $apidata[$row['id']]['updateable'];

                if (empty($row['author'])) {
                    $row['author'] = $apidata[$row['id']]['author'];
                }

                $row['license'] = $apidata[$row['id']]['license'];
                $row['message'] = $apidata[$row['id']]['note'];
                $row['link'] = $apidata[$row['id']]['link'];
                $row['support'] = $apidata[$row['id']]['support'];
                $row['origin'] = true;
            }

            $content .= "\t<extension>\n";
            $content .= "\t\t<id><![CDATA[" . $row['id'] . "]]></id>\n";
            $content .= "\t\t<type><![CDATA[" . $row['type'] . "]]></type>\n";
            $content .= "\t\t<name><![CDATA[" . $row['name'] . "]]></name>\n";
            $content .= "\t\t<version><![CDATA[" . $row['current_version'] . "]]></version>\n";
            $content .= "\t\t<date><![CDATA[" . gmdate('D, d M Y H:i:s', $row['current_release']) . " GMT]]></date>\n";
            $content .= "\t\t<new_version><![CDATA[" . $row['remote_version'] . "]]></new_version>\n";
            $content .= "\t\t<new_date><![CDATA[" . ($row['remote_release'] ? gmdate('D, d M Y H:i:s', $row['current_release']) . ' GMT' : '') . "]]></new_date>\n";
            $content .= "\t\t<author><![CDATA[" . $row['author'] . "]]></author>\n";
            $content .= "\t\t<license><![CDATA[" . $row['license'] . "]]></license>\n";
            $content .= "\t\t<mode><![CDATA[" . $row['mode'] . "]]></mode>\n";
            $content .= "\t\t<message><![CDATA[" . $row['message'] . "]]></message>\n";
            $content .= "\t\t<link><![CDATA[" . $row['link'] . "]]></link>\n";
            $content .= "\t\t<support><![CDATA[" . $row['support'] . "]]></support>\n";
            $content .= "\t\t<updateable>\n";

            if (!empty($row['updateable'])) {
                $content .= "\t\t\t<upds>\n";

                foreach ($row['updateable'] as $updateable) {
                    $content .= "\t\t\t\t<upd>\n";
                    $content .= "\t\t\t\t\t<upd_fid><![CDATA[" . $updateable['fid'] . "]]></upd_fid>\n";
                    $content .= "\t\t\t\t\t<upd_old><![CDATA[" . $updateable['old_ver'] . "]]></upd_old>\n";
                    $content .= "\t\t\t\t\t<upd_new><![CDATA[" . $updateable['new_ver'] . "]]></upd_new>\n";
                    $content .= "\t\t\t\t</upd>\n";
                }
                $content .= "\t\t\t</upds>\n";

                unset($updateable);
            }

            $content .= "\t\t</updateable>\n";
            $content .= "\t\t<origin><![CDATA[" . ($row['origin'] === true ? 'true' : 'false') . "]]></origin>\n";
            $content .= "\t</extension>\n";
        }

        $content .= '</cms>';

        $xmlcontent = simplexml_load_string($content);

        if ($xmlcontent !== false) {
            file_put_contents($my_file, $content);
        }
    }

    return $xmlcontent;
}

/**
 * nv_save_file_ips()
 *
 * @param int $type
 *                  $type 0 là IP cấm, 1 là IP bỏ qua flood
 * @return string|true
 */
function nv_save_file_ips($type = 0)
{
    global $db, $db_config, $ips;

    $content_config_site = '';
    $content_config_admin = '';

    if ($type == 0) {
        $variable_name = 'banip';
        $file_name = 'banip';
    } elseif ($type == 1) {
        $variable_name = 'except_flood';
        $file_name = 'efloodip';
    } else {
        return true;
    }

    $result = $db->query('SELECT ip, mask, area, begintime, endtime FROM ' . $db_config['prefix'] . '_ips WHERE type=' . $type);
    while ([$dbip, $dbmask, $dbarea, $dbbegintime, $dbendtime] = $result->fetch(3)) {
        $dbendtime = (int) $dbendtime;
        $dbarea = (int) $dbarea;

        if ($dbendtime == 0 or $dbendtime > NV_CURRENTTIME) {
            if ($ips->isIp6($dbip)) {
                $ip6 = 1;
                $ip_mask = $dbip . '/' . $dbmask;
            } else {
                $ip6 = 0;
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
            }

            if ($dbarea == 1 or $dbarea == 3) {
                $content_config_site .= '$array_' . $variable_name . "_site['" . $dbip . "'] = ['ip6' => " . $ip6 . ", 'mask' => \"" . $ip_mask . "\", 'begintime' => " . $dbbegintime . ", 'endtime' => " . $dbendtime . "];\n";
            }

            if ($dbarea == 2 or $dbarea == 3) {
                $content_config_admin .= '$array_' . $variable_name . "_admin['" . $dbip . "'] = ['ip6' => " . $ip6 . ", 'mask' => \"" . $ip_mask . "\", 'begintime' => " . $dbbegintime . ", 'endtime' => " . $dbendtime . "];\n";
            }
        }
    }

    if (!$content_config_site and !$content_config_admin) {
        nv_deletefile(NV_ROOTDIR . '/' . NV_DATADIR . '/' . $file_name . '.php');

        return true;
    }

    $content_config = "<?php\n\n";
    $content_config .= NV_FILEHEAD . "\n\n";
    $content_config .= "if (!defined('NV_MAINFILE')) {\n    exit('Stop!!!');\n}\n\n";
    $content_config .= '$array_' . $variable_name . "_site = [];\n";
    $content_config .= $content_config_site;
    $content_config .= "\n";
    $content_config .= '$array_' . $variable_name . "_admin = [];\n";
    $content_config .= $content_config_admin;

    $write = file_put_contents(NV_ROOTDIR . '/' . NV_DATADIR . '/' . $file_name . '.php', $content_config, LOCK_EX);

    if ($write === false) {
        return $content_config;
    }

    return true;
}

/**
 * Lấy các tag từ file
 *
 * @param mixed $file_path
 */
function nv_get_plugin_area($file_path)
{
    global $nv_hooks;

    $nv_hooks_backup = $nv_hooks;
    $nv_hooks = [];
    $priority = 10;
    $module_name = '';
    $hook_module = '';
    $pid = 0;

    require $file_path;

    $plugin_area = [];
    foreach ($nv_hooks as $data) {
        $plugin_area = array_merge_recursive($plugin_area, array_keys($data));
    }
    $nv_hooks = $nv_hooks_backup;

    return $plugin_area;
}

/**
 * Lấy module xảy ra sự kiện từ file
 *
 * @param mixed $file_path
 */
function nv_get_hook_require($file_path)
{
    global $nv_hooks;
    $nv_hooks_backup = $nv_hooks;
    $nv_hooks = [];
    $priority = 10;
    $module_name = '';
    $hook_module = '';
    $pid = 0;

    require $file_path;

    $nv_hooks = $nv_hooks_backup;
    if (!isset($nv_hook_module)) {
        return '';
    }

    return $nv_hook_module;
}

/**
 * Lấy module nhận dữ liệu từ file
 *
 * @param mixed $file_path
 */
function nv_get_hook_revmod($file_path)
{
    global $nv_hooks;
    $nv_hooks_backup = $nv_hooks;
    $nv_hooks = [];
    $priority = 10;
    $module_name = '';
    $hook_module = '';
    $pid = 0;

    require $file_path;

    $nv_hooks = $nv_hooks_backup;
    if (!isset($nv_receive_module)) {
        return '';
    }

    return $nv_receive_module;
}

/**
 * mod_admin_list()
 *
 * @param string $module_name
 * @param bool   $suspend_inc
 * @return array
 */
function mod_admin_list($module_name, $suspend_inc = false)
{
    global $db, $site_mods;

    $sql = 'SELECT t1.admin_id, t1.lev as level, t1.is_suspend, t2.username, t2.email, t2.first_name, t2.last_name, t2.active
    FROM ' . NV_AUTHORS_GLOBALTABLE . ' t1
    INNER JOIN ' . NV_USERS_GLOBALTABLE . ' t2
    ON t1.admin_id = t2.userid
    WHERE t1.lev!=0';
    $result = $db->query($sql);

    $adms = [];
    while ($row = $result->fetch()) {
        $allowed = false;
        if ($row['level'] == '1') {
            $allowed = true;
        } else {
            $row['is_suspend'] = ($row['is_suspend'] or empty($row['active'])) ? true : false;
            if (!$suspend_inc and $row['is_suspend']) {
                continue;
            }
            if ($row['level'] == '2') {
                $allowed = true;
            } else {
                if (!empty($site_mods[$module_name]['admins']) and in_array((int) $row['admin_id'], array_map('intval', explode(',', $site_mods[$module_name]['admins'])), true)) {
                    $allowed = true;
                }
            }
        }
        if ($allowed) {
            $adms[$row['admin_id']] = $row;
        }
    }

    return $adms;
}

/**
 * Hàm tạo file cache robots mặc định và ghi thêm các rule tùy chỉnh
 * @param mixed $robots_config đặt là false thì sẽ tạo ra rule mặc định, truyền array[$robots_data, $robots_other] để set rule tuỳ chỉnh
 * @param bool $save true thì sẽ lưu file cache, false thì chỉ trả về dữ liệu
 * @param array $config các config cần thiết trong trường hợp thiếu $global_config
 * @param bool $force_gconfig true thì sẽ cập nhật $global_config theo $config và trong CSDL
 * @return array
 */
function nv_update_robots($robots_config, bool $save = false, array $config = [], bool $force_gconfig = false)
{
    global $db, $db_config, $global_config;

    $real_config = $global_config;

    // Lấy các config trong CSDL đảm bảo luôn luôn mới nhất
    $sql = "SELECT config_name, config_value FROM " . $db_config['prefix'] . "_config
    WHERE lang='sys' AND module='global' AND config_name IN ('allow_sitelangs', 'rewrite_enable', 'rewrite_optional')";
    $result = $db->query($sql);
    while ($row = $result->fetch()) {
        $real_config[$row['config_name']] = $row['config_value'];
    }
    $result->closeCursor();

    $real_config['allow_sitelangs'] = !empty($real_config['allow_sitelangs']) ? explode(',', $real_config['allow_sitelangs']) : [];
    $real_config['allow_sitelangs'] = array_filter(array_unique(array_map('trim', $real_config['allow_sitelangs'])));

    // Config init hoặc tùy chỉnh
    if (!empty($config)) {
        $real_config = array_merge($real_config, $config);
    }
    // Kiểm tra các config cần thiết
    if (!isset($real_config['allow_sitelangs'], $real_config['rewrite_enable'], $real_config['check_rewrite_file'])) {
        http_response_code(500);
        trigger_error('Error: Missing config for updating robots', E_USER_ERROR);
    }
    if ($force_gconfig) {
        $global_config = $real_config;
    }

    if ($robots_config === false) {
        // Đọc các rule đã có đồng thời kết hợp với rule mặc định của NukeViet
        $robots_data = [];
        $robots_other = [];
        $cache_file = NV_ROOTDIR . '/' . NV_DATADIR . '/robots.php';
        if (file_exists($cache_file)) {
            include $cache_file;
            $robots_data = unserialize($cache);
            $robots_other = unserialize($cache_other);
        } else {
            $robots_data['/data/'] = 0;
            $robots_data['/includes/'] = 0;
            $robots_data['/install/'] = 0;
            $robots_data['/modules/'] = 0;
            $robots_data['/robots.php'] = 0;
            $robots_data['/web.config'] = 0;
        }

        // Bổ sung các đường link chặn cố định
        if ($real_config['rewrite_enable']) {
            foreach ($real_config['allow_sitelangs'] as $lang) {
                $sql = "SELECT * FROM " . $db_config['prefix'] . "_" . $lang . "_modules";
                $result = $db->query($sql);
                while ($row = $result->fetch()) {
                    $url = '';
                    if ($row['module_file'] == 'users' or $row['module_file'] == 'statistics') {
                        $url = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $lang . '&' . NV_NAME_VARIABLE . '=' . $row['title'], true);
                    } elseif ($row['module_file'] == 'banners') {
                        $url = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $lang . '&' . NV_NAME_VARIABLE . '=' . $row['title'] . '&' . NV_OP_VARIABLE . '=click', true);
                    }
                    if (!empty($url)) {
                        if (!isset($robots_other[$url])) {
                            $robots_other[$url] = 0;
                        }
                    }
                }
                $result->closeCursor();
            }
        }
    } else {
        // Các rule tùy chỉnh
        [$robots_data, $robots_other] = $robots_config;
    }

    if ($save) {
        $cache_file = NV_ROOTDIR . '/' . NV_DATADIR . '/robots.php';
        $content_config = "<?php\n\n";
        $content_config .= NV_FILEHEAD . "\n\n";
        $content_config .= "if (!defined('NV_MAINFILE')) {\n    exit('Stop!!!');\n}\n\n";
        $content_config .= "\$cache = '" . serialize($robots_data) . "';\n";
        $content_config .= "\$cache_other = '" . serialize($robots_other) . "';\n";
        $check = file_put_contents($cache_file, $content_config, LOCK_EX);

        return [$check, $content_config];
    }

    return [$robots_data, $robots_other];
}
