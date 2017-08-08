<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/31/2009 5:50
 */

if (! defined('NV_ADMIN') or ! defined('NV_MAINFILE') or ! defined('NV_IS_MODADMIN')) {
    die('Stop!!!');
}

define('NV_IS_FILE_EXTENSIONS', true);

//Document
$array_url_instruction['manage'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:manage';

$menu_top = array(
    'title' => $module_name,
    'module_file' => '',
    'custom_title' => $lang_global['mod_extensions']
);

$allow_func = array( 'main', 'newest', 'popular', 'featured', 'downloaded', 'favorites', 'detail', 'login', 'update', 'manage' );

// Cho phep upload ung dung
if (!empty($global_config['extension_setup'])) {
    $allow_func[] = 'upload';
}

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
        if (! is_dir(NV_ROOTDIR . '/modules/' . $name)) {
            return 0;
        }
        return 1;
    }
    // Theme
    elseif ($type == 2) {
        if (! is_dir(NV_ROOTDIR . '/themes/' . $name)) {
            return 0;
        }
        return 1;
    }
    // Block
    elseif ($type == 3) {
        return 2;
    }
    // Crons
    elseif ($type == 4) {
        if (! is_file(NV_ROOTDIR . '/includes/cronjobs/' . $name)) {
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
 * @return
 */
function is_serialized_string($data)
{
    if (! is_string($data)) {
        return false;
    }

    $data = trim($data);
    $length = nv_strlen($data);

    if ($length < 4) {
        return false;
    } elseif ($data{1} !== ':') {
        return false;
    } elseif ($data{0} !== 'a') {
        return false;
    } else {
        return true;
    }
}

/**
 * nv_get_cookies()
 *
 * @param bool $full
 * @return
 */
function nv_get_cookies($full = false)
{
    global $db;

    $data = array();
    $arrURL = parse_url(NUKEVIET_STORE_APIURL);

    $data['domain'] = '.' . $arrURL['host'];
    $data['path'] = '/';

    $sql = 'SELECT * FROM ' . NV_COOKIES_GLOBALTABLE . ' WHERE domain=' . $db->quote($data['domain']) . ' AND path=' . $db->quote($data['path']);
    $result = $db->query($sql);

    $array = array();
    $array_expires = array();

    while ($row = $result->fetch()) {
        $row['expires'] = floatval($row['expires']);

        if ($row['expires'] <= NV_CURRENTTIME) {
            $array_expires[] = $db->quote($row['name']);
        } else {
            if ($full === false) {
                $array[$row['name']] = $row['value'];
            } else {
                $array[$row['name']] = array(
                    'value' => $row['value'],
                    'secure' => $row['secure'] ? true : false,
                );
            }
        }
    }

    // Delete expired cookies
    if (! empty($array_expires)) {
        $sql = 'DELETE FROM ' . NV_COOKIES_GLOBALTABLE . ' WHERE name IN(' . implode($array_expires) . ') AND domain=' . $db->quote($data['domain']) . ' AND path=' . $db->quote($data['path']);
        $db->query($sql);
    }

    return $array;
}

/**
 * nv_store_cookies()
 *
 * @param mixed $cookies
 * @param mixed $currCookies
 * @return void
 */
function nv_store_cookies($cookies = array(), $currCookies = array())
{
    global $db;

    if (! empty($cookies)) {
        foreach ($cookies as $cookie) {
            if (! empty($cookie['expires'])) {
                if (! preg_match("/^([0-9]+)$/", $cookie['expires'])) {
                    $cookie['expires'] = strtotime($cookie['expires']);
                } else {
                    $cookie['expires'] = intval($cookie['expires']);
                }

                // Update cookie
                if (isset($currCookies[$cookie['name']])) {
                    try {
                        $sth = $db->prepare('UPDATE ' . NV_COOKIES_GLOBALTABLE . ' SET value= :value, expires= ' . $cookie['expires'] . ' WHERE name=' . $db->quote($cookie['name']) . ' AND domain=' . $db->quote($cookie['domain']) . ' AND path=' . $db->quote($cookie['path']));
                        $sth->bindParam(':value', $cookie['value'], PDO::PARAM_STR);
                        $sth->execute();
                    } catch (PDOException $e) {
                        trigger_error($e->getMessage());
                    }
                } else {
                    try {
                        $sth = $db->prepare('INSERT INTO ' . NV_COOKIES_GLOBALTABLE . ' ( name, value, domain, path, expires, secure ) VALUES( :name, :value, :domain, :path, ' . $cookie['expires'] . ', 0 )');
                        $sth->bindParam(':name', $cookie['name'], PDO::PARAM_STR);
                        $sth->bindParam(':value', $cookie['value'], PDO::PARAM_STR);
                        $sth->bindParam(':domain', $cookie['domain'], PDO::PARAM_STR);
                        $sth->bindParam(':path', $cookie['path'], PDO::PARAM_STR);
                        $sth->execute();
                    } catch (PDOException $e) {
                        trigger_error($e->getMessage());
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
 * @return
 */
function nv_check_ext_config_filecontent($extConfig)
{
    if (! isset($extConfig['extension']) or ! isset($extConfig['author']) or ! isset($extConfig['note']) or ! isset($extConfig['extension']['id']) or ! isset($extConfig['extension']['type']) or ! isset($extConfig['extension']['name']) or ! isset($extConfig['extension']['version']) or ! isset($extConfig['author']['name']) or ! isset($extConfig['author']['email']) or ! isset($extConfig['note']['text'])) {
        return false;
    }

    return true;
}
