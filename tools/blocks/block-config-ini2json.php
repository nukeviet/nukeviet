<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

define('NV_MAINFILE', true);
define('NV_ROOTDIR', str_replace(DIRECTORY_SEPARATOR, '/', realpath(pathinfo(__file__, PATHINFO_DIRNAME) . '/../../src')));

require NV_ROOTDIR . '/includes/functions.php';
require NV_ROOTDIR . '/includes/core/filesystem_functions.php';

// Các module
$modules = nv_scandir(NV_ROOTDIR . '/modules', '/^([a-zA-Z0-9\-\_]+)$/i');
foreach ($modules as $module) {
    $blocks = nv_scandir(NV_ROOTDIR . '/modules/' . $module . '/blocks', '/^(global|module)\.(.*)\.php$/i');

    foreach ($blocks as $block) {
        $iniName = preg_replace('/\.php$/', '.ini', $block);
        $jsonName = preg_replace('/\.php$/', '.json', $block);

        $filePhp = NV_ROOTDIR . '/modules/' . $module . '/blocks/' . $block;
        $fileIni = NV_ROOTDIR . '/modules/' . $module . '/blocks/' . $iniName;
        $fileJson = NV_ROOTDIR . '/modules/' . $module . '/blocks/' . $jsonName;

        if (!preg_match('/^(module|global)\.(.*)\.php$/', $block, $m)) {
            die("Lỗi không xác định được tên block\n");
        }
        $block_name = $m[2];

        // Đã có tệp json rồi thì không xử lý nữa
        if (file_exists($fileJson)) {
            continue;
        }

        echo "modules/" . $module . "/blocks/" . $block . " > ";

        if (!file_exists($fileIni)) {
            $config_json = makeDefaultJson($block_name);
        } else {
            $config_json = getIni($fileIni, $block_name);
        }

        // Ghi tệp json và xóa tệp ini
        if (file_exists($fileIni)) {
            unlink($fileIni);
        }
        file_put_contents($fileJson, $config_json . "\n", LOCK_EX);
        echo "OK\n";
    }
}

// Các giao diện
$themes = nv_scandir(NV_ROOTDIR . '/themes', '/^([a-zA-Z0-9\-\_]+)$/i');
foreach ($themes as $theme) {
    $blocks = nv_scandir(NV_ROOTDIR . '/themes/' . $theme . '/blocks', '/^(global|module)\.(.*)\.php$/i');

    foreach ($blocks as $block) {
        $iniName = preg_replace('/\.php$/', '.ini', $block);
        $jsonName = preg_replace('/\.php$/', '.json', $block);

        $filePhp = NV_ROOTDIR . '/themes/' . $theme . '/blocks/' . $block;
        $fileIni = NV_ROOTDIR . '/themes/' . $theme . '/blocks/' . $iniName;
        $fileJson = NV_ROOTDIR . '/themes/' . $theme . '/blocks/' . $jsonName;

        if (!preg_match('/^(module|global)\.(.*)\.php$/', $block, $m)) {
            die("Lỗi không xác định được tên block\n");
        }
        $block_name = $m[2];

        // Đã có tệp json rồi thì không xử lý nữa
        if (file_exists($fileJson)) {
            continue;
        }

        echo "themes/" . $theme . "/blocks/" . $block . " > ";

        if (!file_exists($fileIni)) {
            $config_json = makeDefaultJson($block_name);
        } else {
            $config_json = getIni($fileIni, $block_name);
        }

        // Ghi tệp json và xóa tệp ini
        if (file_exists($fileIni)) {
            unlink($fileIni);
        }
        file_put_contents($fileJson, $config_json . "\n", LOCK_EX);
        echo "OK\n";
    }
}

echo "End!\n";

function makeDefaultJson(string $block_name)
{
    $langs = ['en', 'fr', 'vi'];
    $config_json = [
        'info' => [
            'name' => ucfirst($block_name),
            'author' => 'VINADES.,JSC',
            'website' => 'https://vinades.vn',
            'description' => ''
        ],
        'i18n' => []
    ];
    foreach ($langs as $lang) {
        $config_json['i18n'][$lang]['info']['name'] = $block_name;
    }

    $config_json = json_encode($config_json, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    return $config_json;
}

function getIni(string $fileIni, string $block_name)
{
    $xml = simplexml_load_file($fileIni);
    if ($xml === false) {
        die("Ini error: " . $fileIni . "\n");
    }
    $config_ini = nv_object2array($xml);

    if (empty($config_ini['datafunction']) and empty($config_ini['submitfunction'])) {
        die("Không có datafunction hoặc submitfunction. Config không hợp lệ");
    }
    if (!empty($config_ini['datafunction']) and !is_string($config_ini['datafunction'])) {
        die("datafunction sai loại dữ liệu. Config không hợp lệ");
    }
    if (!empty($config_ini['submitfunction']) and !is_string($config_ini['submitfunction'])) {
        die("submitfunction sai loại dữ liệu. Config không hợp lệ");
    }

    $config_json = [];

    // Xử lý thông tin block
    if (isset($config_ini['info'])) {
        foreach ($config_ini['info'] as $key => $value) {
            // Chỉnh sửa một số thông tin cũ
            $value = str_ireplace('VinaDes.,Jsc', 'VINADES.,JSC', $value);
            $value = str_ireplace('http://', 'https://', $value);
            $value === [] && $value = '';

            $config_json['info'][$key] = $value;
        }
    }

    // Xử lý các biến
    if (isset($config_ini['config'])) {
        foreach ($config_ini['config'] as $key => $value) {
            $value === [] && $value = '';
            $config_json['config'][$key] = $value;
        }
    }

    if (!empty($config_ini['datafunction'])) {
        $config_json['datafunction'] = $config_ini['datafunction'];
    }
    if (!empty($config_ini['submitfunction'])) {
        $config_json['submitfunction'] = $config_ini['submitfunction'];
    }

    // Xử lý đa ngôn ngữ
    $i18n = [];
    $langs = [];
    if (isset($config_ini['language'])) {
        foreach ($config_ini['language'] as $lang => $values) {
            foreach ($values as $key => $value) {
                $value === [] && $value = '';
                $i18n[$lang]['language'][$key] = $value;
            }
            $langs[$lang] = $lang;
        }
    }
    if (empty($langs)) {
        $langs = ['en', 'fr', 'vi'];
    }
    // Bổ sung tên block vào json
    foreach ($langs as $lang) {
        $i18n[$lang]['info']['name'] = $block_name;
    }
    $config_json['i18n'] = $i18n;

    $config_json = json_encode($config_json, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    return $config_json;
}
