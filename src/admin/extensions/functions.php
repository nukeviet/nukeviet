<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    exit('Stop!!!');
}

define('NV_IS_FILE_EXTENSIONS', true);

//Document
$array_url_instruction['manage'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:manage';

$menu_top = [
    'title' => $module_name,
    'module_file' => '',
    'custom_title' => $nv_Lang->getGlobal('mod_extensions')
];

$allow_func = ['main', 'newest', 'popular', 'featured', 'downloaded', 'favorites', 'detail', 'login', 'update', 'manage'];

// Cho phep cai ung dung tu NukeViet Store
if ($global_config['extension_setup'] == 2 or $global_config['extension_setup'] == 3) {
    $allow_func[] = 'install';
    $allow_func[] = 'download';
}

/**
 * nv_extensions_is_installed()
 *
 * @param mixed $type
 * @param mixed $name
 * @param mixed $version
 * @return
 * 0: Not exists
 * 1: Exists
 * 2: Unsure
 */
function nv_extensions_is_installed($type, $name, $version)
{
    // Module
    if ($type == 1) {
        if (!is_dir(NV_ROOTDIR . '/modules/' . $name)) {
            return 0;
        }

        return 1;
    }
    // Theme
    if ($type == 2) {
        if (!is_dir(NV_ROOTDIR . '/themes/' . $name)) {
            return 0;
        }

        return 1;
    }
    // Block
    if ($type == 3) {
        return 2;
    }
    // Crons
    if ($type == 4) {
        if (!is_file(NV_ROOTDIR . '/includes/cronjobs/' . $name)) {
            return 0;
        }

        return 1;
    }

    return 2;
}

/**
 * is_serialized_string()
 *
 * @param mixed $data
 */
function is_serialized_string($data)
{
    if (!is_string($data)) {
        return false;
    }

    $data = trim($data);
    $length = nv_strlen($data);

    if ($length < 4) {
        return false;
    }
    if ($data[1] !== ':') {
        return false;
    }

    return !($data[0] !== 'a')
    ;
}

/**
 * nv_get_cookies()
 *
 * @param bool $full
 */
function nv_get_cookies($full = false)
{
    global $db;

    $data = [];
    $arrURL = parse_url(NUKEVIET_STORE_APIURL);

    $data['domain'] = '.' . $arrURL['host'];
    $data['path'] = '/';

    $stmt = $db->prepare('SELECT * FROM ' . NV_COOKIES_GLOBALTABLE . ' WHERE domain = :domain AND path = :path');
    $stmt->bindValue(':domain', $data['domain'], PDO::PARAM_STR);
    $stmt->bindValue(':path', $data['path'], PDO::PARAM_STR);
    $stmt->execute();

    $array = [];
    $array_expires = [];

    while ($row = $stmt->fetch()) {
        $row['expires'] = (float) ($row['expires']);

        if ($row['expires'] <= NV_CURRENTTIME) {
            $array_expires[] = $row['name'];
        } else {
            if ($full === false) {
                $array[$row['name']] = $row['value'];
            } else {
                $array[$row['name']] = [
                    'value' => $row['value'],
                    'secure' => $row['secure'] ? true : false,
                ];
            }
        }
    }
    $stmt->closeCursor();

    // Delete expired cookies
    if (!empty($array_expires)) {
        $values = array_values($array_expires);
        $placeholders = implode(', ', array_map(fn($k) => ':v' . $k, array_keys($values)));
        $stmt_del = $db->prepare('DELETE FROM ' . NV_COOKIES_GLOBALTABLE . ' WHERE name IN (' . $placeholders . ') AND domain = :domain AND path = :path');
        foreach ($values as $k => $v) {
            $stmt_del->bindValue(':v' . $k, $v, PDO::PARAM_STR);
        }
        $stmt_del->bindValue(':domain', $data['domain'], PDO::PARAM_STR);
        $stmt_del->bindValue(':path', $data['path'], PDO::PARAM_STR);
        $stmt_del->execute();
    }

    return $array;
}

/**
 * nv_store_cookies()
 *
 * @param mixed $cookies
 * @param mixed $currCookies
 */
function nv_store_cookies($cookies = [], $currCookies = [])
{
    global $db;

    if (!empty($cookies)) {
        $stmt_update = $db->prepare('UPDATE ' . NV_COOKIES_GLOBALTABLE . ' SET value = :value, expires = :expires WHERE name = :name AND domain = :domain AND path = :path');
        $stmt_insert = $db->prepare('INSERT INTO ' . NV_COOKIES_GLOBALTABLE . ' (name, value, domain, path, expires, secure) VALUES (:name, :value, :domain, :path, :expires, 0)');

        foreach ($cookies as $cookie) {
            if (!empty($cookie['expires'])) {
                if (!preg_match('/^([0-9]+)$/', $cookie['expires'])) {
                    $cookie['expires'] = strtotime($cookie['expires']);
                } else {
                    $cookie['expires'] = (int) ($cookie['expires']);
                }

                // Update cookie
                if (isset($currCookies[$cookie['name']])) {
                    try {
                        $stmt_update->bindValue(':value', $cookie['value'], PDO::PARAM_STR);
                        $stmt_update->bindValue(':expires', $cookie['expires'], PDO::PARAM_INT);
                        $stmt_update->bindValue(':name', $cookie['name'], PDO::PARAM_STR);
                        $stmt_update->bindValue(':domain', $cookie['domain'], PDO::PARAM_STR);
                        $stmt_update->bindValue(':path', $cookie['path'], PDO::PARAM_STR);
                        $stmt_update->execute();
                    } catch (Throwable $e) {
                        trigger_error($e);
                    }
                } else {
                    try {
                        $stmt_insert->bindValue(':name', $cookie['name'], PDO::PARAM_STR);
                        $stmt_insert->bindValue(':value', $cookie['value'], PDO::PARAM_STR);
                        $stmt_insert->bindValue(':domain', $cookie['domain'], PDO::PARAM_STR);
                        $stmt_insert->bindValue(':path', $cookie['path'], PDO::PARAM_STR);
                        $stmt_insert->bindValue(':expires', $cookie['expires'], PDO::PARAM_INT);
                        $stmt_insert->execute();
                    } catch (Throwable $e) {
                        trigger_error($e);
                    }
                }
            }
        }
    }
}

/**
 * nv_check_ext_config_filecontent()
 *
 * @param mixed $extConfig
 */
function nv_check_ext_config_filecontent($extConfig)
{
    return !(!isset($extConfig['extension']) or !isset($extConfig['author']) or !isset($extConfig['note']) or !isset($extConfig['extension']['id']) or !isset($extConfig['extension']['type']) or !isset($extConfig['extension']['name']) or !isset($extConfig['extension']['version']) or !isset($extConfig['author']['name']) or !isset($extConfig['author']['email']) or !isset($extConfig['note']['text']));
}

/**
 * Kiểm đường dẫn của tệp tin trong gói ứng dụng hợp lệ:
 * Không chứa path rỗng, absolute path, .., ., backslash traversal, drive path C:, mọi dấu :, null byte
 *
 * @param string $filename
 * @return bool
 */
function nv_zip_validate_member_name($filename)
{
    if (!is_string($filename) || $filename === '') {
        return false;
    }
    if (strpos($filename, "\0") !== false) {
        return false;
    }
    if (strpos($filename, ':') !== false) {
        return false;
    }
    if (strpos($filename, '\\') !== false) {
        return false;
    }
    if ($filename[0] === '/') {
        return false;
    }
    foreach (explode('/', $filename) as $part) {
        if ($part === '..' || $part === '.') {
            return false;
        }
    }
    return true;
}

/**
 * @param array $fileinfo
 * @param array $arraySysOption
 * @param array $info
 * @return boolean
 */
function check_structure($fileinfo, $arraySysOption, $info)
{
    $file_path = trim($fileinfo['filename']);

    if (!nv_zip_validate_member_name($file_path)) {
        return false;
    }

    $folder = explode('/', $file_path);
    $lev_folder = count($folder) - 1;
    $is_folder = $fileinfo['folder'];

    // File tại thư mục gốc chỉ chấp nhận config.ini
    if ($lev_folder < 1) {
        if ($file_path !== 'config.ini') {
            return false;
        }
        return true;
    }
    /*
     * Giao diện thì chỉ có 1 thư mục duy nhất bằng tên trong config.ini.
     * Thư mục/tập tin bên trong nó không giới hạn cấu trúc lẫn phần mở rộng
     */
    if ($info['exttype'] == 'theme') {
        if ($folder[0] !== $info['extname']) {
            return false;
        }
        return true;
    }

    $root_folder = nv_strtolower($folder[0]);
    $sub_folder = isset($folder[1]) ? nv_strtolower($folder[1]) : '';

    /*
     * Các loại khác thì kiểm tra thư mục gốc số 1 thuộc các thư mục được phép
     * Và bắt buộc phải có thư mục cấp 2 nếu đó là file.
     */
    if ($root_folder != 'includes' and !in_array($root_folder, $arraySysOption['allowfolder']) or ($lev_folder < 2 and !$is_folder)) {
        return false;
    }

    // Module thì trong thư mục module chỉ được phép chứa 1 thư mục có tên bằng tên ứng dụng
    if ($info['exttype'] == 'module' and $root_folder == 'modules' and $lev_folder >= 2 and $sub_folder != $info['extname']) {
        return false;
    }

    // Trong includes chỉ cho phép nằm trong 2 thư mục con đặc biệt
    if ($root_folder == 'includes' and !empty($sub_folder) and !in_array($root_folder . '/' . $sub_folder, $arraySysOption['allowfolder'])) {
        return false;
    }

    // Trong assets và uploads file không được chứa phần mở rộng bị cấm
    if (($root_folder == 'assets' or $root_folder == 'uploads') and in_array(nv_getextension($file_path), $arraySysOption['forbidExt'])) {
        return false;
    }

    return true;
}
