<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_THEMES')) {
    exit('Stop!!!');
}

if (!defined('NV_IS_AJAX')) {
    nv_htmlOutput('Wrong ajax!');
}
$respon = [
    'error' => 1,
    'text' => 'Wrong session!!!'
];
if ($nv_Request->get_title('checkss', 'post', '') !== NV_CHECK_SESSION) {
    nv_jsonOutput($respon);
}

$file_name = $nv_Request->get_string('file_name', 'post');
if (empty($file_name)) {
    $respon['text'] = 'No block file name!';
    nv_jsonOutput($respon);
}

$module = $nv_Request->get_string('module', 'post', '');
$selectthemes = $nv_Request->get_string('selectthemes', 'post', '');

// Xác định tồn tại của block
$path_file_php = $path_file_ini = $path_file_json = $block_type = $block_dir = '';

if ($module == 'theme' and (preg_match($global_config['check_theme'], $selectthemes, $mtheme) or preg_match($global_config['check_theme_mobile'], $selectthemes, $mtheme)) and preg_match($global_config['check_block_theme'], $file_name, $matches) and file_exists(NV_ROOTDIR . '/themes/' . $selectthemes . '/blocks/' . $file_name)) {
    // Block giao diện
    $path_file_php = NV_ROOTDIR . '/themes/' . $selectthemes . '/blocks/' . $file_name;
    $path_file_ini = NV_ROOTDIR . '/themes/' . $selectthemes . '/blocks/' . $matches[1] . '.' . $matches[2] . '.ini';
    $path_file_json = NV_ROOTDIR . '/themes/' . $selectthemes . '/blocks/' . $matches[1] . '.' . $matches[2] . '.json';
    $block_type = 'theme';
    $block_dir = $selectthemes;
} elseif (isset($site_mods[$module]) and preg_match($global_config['check_block_module'], $file_name, $matches)) {
    // Block module
    $module_file = $site_mods[$module]['module_file'];
    $path_file_php = NV_ROOTDIR . '/modules/' . $module_file . '/blocks/' . $file_name;
    $path_file_ini = NV_ROOTDIR . '/modules/' . $module_file . '/blocks/' . $matches[1] . '.' . $matches[2] . '.ini';
    $path_file_json = NV_ROOTDIR . '/modules/' . $module_file . '/blocks/' . $matches[1] . '.' . $matches[2] . '.json';
    $block_type = 'module';
    $block_dir = $module_file;
} else {
    $respon['text'] = 'Block not exists!';
    nv_jsonOutput($respon);
}

/*
 * Đọc lấy tên hàm xử lý, data và ngôn ngữ của block nếu có tệp config
 * đọc cả tệp xml (chuẩn cũ) và json (chuẩn mới từ 5.0.00) theo thứ tự json ưu tiên hơn,
 * nội dung trong json nếu có sẽ đè trong xml
 */
$function_name = '';
$array_config = $lang_block = [];
if (file_exists($path_file_ini)) {
    $xml = simplexml_load_file($path_file_ini);
    if ($xml === false) {
        $respon['text'] = $nv_Lang->getModule('block_error_bconfig');
        nv_jsonOutput($respon);
    }
    $function_name = trim((string) $xml->datafunction);

    // Đọc cấu hình mặc định của block
    $xmlconfig = $xml->xpath('config');
    $config = (array) $xmlconfig[0];
    $array_config = [];
    foreach ($config as $key => $value) {
        $array_config[$key] = trim($value);
    }

    // Ngôn ngữ cấu hình block
    $xmllanguage = $xml->xpath('language');
    if (!empty($xmllanguage)) {
        $language = (array) $xmllanguage[0];
        if (isset($language[NV_LANG_INTERFACE])) {
            $lang_block = (array) $language[NV_LANG_INTERFACE];
        } elseif (isset($language['en'])) {
            $lang_block = (array) $language['en'];
        }
    }
}
if (file_exists($path_file_json)) {
    $json = json_decode(file_get_contents($path_file_json), true);
    if (empty($json) or !is_array($json)) {
        $respon['text'] = $nv_Lang->getModule('block_error_bconfig2');
        nv_jsonOutput($respon);
    }

    // Cập nhật hàm xử lý
    if (!empty($json['datafunction'])) {
        $function_name = trim($json['datafunction']);
    }

    // Cấu hình mặc định
    if (!empty($json['config']) and is_array($json['config'])) {
        $array_config = array_merge($array_config, $json['config']);
    }

    // Ngôn ngữ cấu hình block
    if (!empty($json['i18n']) and is_array($json['i18n'])) {
        if (!empty($json['i18n'][NV_LANG_INTERFACE]) and is_array($json['i18n'][NV_LANG_INTERFACE]) and !empty($json['i18n'][NV_LANG_INTERFACE]['language']) and is_array($json['i18n'][NV_LANG_INTERFACE]['language'])) {
            $lang_block = array_merge($lang_block, (array) $json['i18n'][NV_LANG_INTERFACE]['language']);
        }
        if (empty($lang_block) and !empty($json['i18n']['en']) and is_array($json['i18n']['en']) and !empty($json['i18n']['en']['language']) and is_array($json['i18n']['en']['language'])) {
            $lang_block = array_merge($lang_block, (array) $json['i18n']['en']['language']);
        }
    }
}

// Không chỉ ra hàm xử lý
if (!file_exists($path_file_php) or empty($function_name)) {
    $respon['error'] = 0;
    $respon['html'] = '';
    nv_jsonOutput($respon);
}

include_once $path_file_php;

// Chỉ ra hàm nhưng trong file php lại không có
if (!nv_function_exists($function_name)) {
    $respon['error'] = 0;
    $respon['html'] = '';
    nv_jsonOutput($respon);
}

$data_block = $array_config;
$bid = $nv_Request->get_int('bid', 'get,post', 0);

if ($bid > 0) {
    $row_config = $db->query('SELECT module, file_name, config FROM ' . NV_BLOCKS_TABLE . '_groups WHERE bid=' . $bid)->fetch();
    if ($row_config['file_name'] == $file_name and $row_config['module'] == $module) {
        $data_block = unserialize($row_config['config']);
    }
}

if ($block_type == 'module') {
    $nv_Lang->loadModule($block_dir, false, true);
} else {
    $nv_Lang->loadTheme($block_dir, true);
}

if (!empty($lang_block)) {
    $nv_Lang->setModule($lang_block, '', true);
}

// Gọi hàm xử lý hiển thị cấu hình block
$contents = call_user_func($function_name, $module, $data_block);

// Xóa lang tạm giải phóng bộ nhớ
$nv_Lang->changeLang();

$respon['error'] = 0;
$respon['html'] = $contents;
nv_jsonOutput($respon);
