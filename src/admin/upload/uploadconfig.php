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

$ini = nv_parse_ini_file(NV_ROOTDIR . '/includes/ini/mime.ini', true);

$myini = [
    'types' => [''],
    'exts' => [''],
    'mimes' => ['']
];

foreach ($ini as $type => $extmime) {
    $myini['types'][] = $type;
    $myini['exts'] = array_merge($myini['exts'], array_keys($extmime));
    $m = array_values($extmime);

    if (is_string($m)) {
        $myini['mimes'] = array_merge($myini['mimes'], $m);
    } else {
        foreach ($m as $m2) {
            if (!is_array($m2)) {
                $m2 = [$m2];
            }
            $myini['mimes'] = array_merge($myini['mimes'], $m2);
        }
    }
}

sort($myini['types']);
unset($myini['types'][0]);
sort($myini['exts']);
unset($myini['exts'][0]);

$myini['mimes'] = array_unique($myini['mimes']);

sort($myini['mimes']);
unset($myini['mimes'][0]);

if ($nv_Request->isset_request('save', 'post')) {
    if ($nv_Request->get_title('checkss', 'post', '') !== NV_CHECK_SESSION) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => 'Error session!!!'
        ]);
    }

    // Quyền điều hành chung
    $array_config = [];
    $array_config['show_folder_size'] = (int) $nv_Request->get_bool('show_folder_size', 'post', false);

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang='sys' AND module='site' AND config_name=:config_name");
    foreach ($array_config as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    // Quyền tối cao
    if (defined('NV_IS_GODADMIN') and $global_config['idsite'] == 0) {
        $type = $nv_Request->get_typed_array('type', 'post', 'int');
        $type = array_flip($type);
        $type = array_intersect_key($myini['types'], $type);
        $type = implode(',', $type);

        $ext = $nv_Request->get_typed_array('ext', 'post', 'int');
        $ext = array_flip($ext);
        $ext = array_intersect_key($myini['exts'], $ext);
        $ext[] = 'php';
        $ext[] = 'php3';
        $ext[] = 'php4';
        $ext[] = 'php5';
        $ext[] = 'phtml';
        $ext[] = 'inc';
        $ext = array_unique($ext);
        $ext = implode(',', $ext);

        $mime = $nv_Request->get_typed_array('mime', 'post', 'int');
        $mime = array_flip($mime);
        $mime = array_intersect_key($myini['mimes'], $mime);
        $mime[] = 'application/x-httpd-php';
        $mime[] = 'application/x-httpd-php-source';
        $mime[] = 'application/php';
        $mime[] = 'application/x-php';
        $mime[] = 'text/php';
        $mime[] = 'text/x-php';
        $mime = array_unique($mime);
        $mime = implode(',', $mime);

        $upload_checking_mode = $nv_Request->get_string('upload_checking_mode', 'post', '');
        if ($upload_checking_mode != 'mild' and $upload_checking_mode != 'lite' and $upload_checking_mode != 'strong') {
            $upload_checking_mode = 'none';
        }

        $nv_max_size = $nv_Request->get_float('nv_max_size', 'post', $global_config['nv_max_size']);
        $nv_max_size = min(nv_converttoBytes(ini_get('upload_max_filesize')), nv_converttoBytes(ini_get('post_max_size')), $nv_max_size);
        $nv_auto_resize = (int) $nv_Request->get_bool('nv_auto_resize', 'post', 0);

        $upload_chunk_size = $nv_Request->get_float('upload_chunk_size', 'post', 0);
        $upload_chunk_size_text = $nv_Request->get_title('upload_chunk_size_text', 'post', '');
        if ($upload_chunk_size_text == 'MB') {
            $pow = 2;
        } elseif ($upload_chunk_size_text == 'KB') {
            $pow = 1;
        } else {
            $pow = 0;
        }
        $upload_chunk_size = round($upload_chunk_size * pow(1024, $pow));
        if ($upload_chunk_size > $nv_max_size or $upload_chunk_size < 0) {
            $upload_chunk_size = 0;
        }

        // Upload vượt cấu hình PHP
        $nv_overflow_size = $nv_Request->get_float('nv_overflow_size', 'post', 0);
        $nv_overflow_size_text = $nv_Request->get_title('nv_overflow_size_text', 'post', '');
        if ($nv_overflow_size_text == 'GB') {
            $pow = 3;
        } else {
            $pow = 2;
        }
        $nv_overflow_size = round($nv_overflow_size * pow(1024, $pow));
        if ($nv_overflow_size < $nv_max_size) {
            $nv_overflow_size = 0;
        }

        $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'global' AND config_name = :config_name");
        $sth->bindValue(':config_name', 'file_allowed_ext', PDO::PARAM_STR);
        $sth->bindValue(':config_value', $type, PDO::PARAM_STR);
        $sth->execute();

        $sth->bindValue(':config_name', 'forbid_extensions', PDO::PARAM_STR);
        $sth->bindValue(':config_value', $ext, PDO::PARAM_STR);
        $sth->execute();

        $sth->bindValue(':config_name', 'forbid_mimes', PDO::PARAM_STR);
        $sth->bindValue(':config_value', $mime, PDO::PARAM_STR);
        $sth->execute();

        $sth->bindValue(':config_name', 'nv_auto_resize', PDO::PARAM_STR);
        $sth->bindValue(':config_value', $nv_auto_resize, PDO::PARAM_STR);
        $sth->execute();

        $sth->bindValue(':config_name', 'nv_max_size', PDO::PARAM_STR);
        $sth->bindValue(':config_value', $nv_max_size, PDO::PARAM_STR);
        $sth->execute();

        $sth->bindValue(':config_name', 'upload_checking_mode', PDO::PARAM_STR);
        $sth->bindValue(':config_value', $upload_checking_mode, PDO::PARAM_STR);
        $sth->execute();

        $sth->bindValue(':config_name', 'upload_chunk_size', PDO::PARAM_STR);
        $sth->bindValue(':config_value', $upload_chunk_size, PDO::PARAM_STR);
        $sth->execute();

        $sth->bindValue(':config_name', 'nv_overflow_size', PDO::PARAM_STR);
        $sth->bindValue(':config_value', $nv_overflow_size, PDO::PARAM_STR);
        $sth->execute();

        $array_config_define = [];
        $array_config_define['upload_alt_require'] = (int) $nv_Request->get_bool('upload_alt_require', 'post', 0);
        $array_config_define['upload_auto_alt'] = (int) $nv_Request->get_bool('upload_auto_alt', 'post', 0);

        $sth->bindValue(':config_name', 'upload_alt_require', PDO::PARAM_STR);
        $sth->bindValue(':config_value', $array_config_define['upload_alt_require'], PDO::PARAM_STR);
        $sth->execute();

        $sth->bindValue(':config_name', 'upload_auto_alt', PDO::PARAM_STR);
        $sth->bindValue(':config_value', $array_config_define['upload_auto_alt'], PDO::PARAM_STR);
        $sth->execute();

        $array_config_define = [];
        $array_config_define['nv_max_width'] = $nv_Request->get_int('nv_max_width', 'post');
        $array_config_define['nv_max_height'] = $nv_Request->get_int('nv_max_height', 'post');
        $array_config_define['nv_mobile_mode_img'] = $nv_Request->get_int('nv_mobile_mode_img', 'post', 0);

        $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'define' AND config_name = :config_name");
        foreach ($array_config_define as $config_name => $config_value) {
            $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
            $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
            $sth->execute();
        }

        $db->query('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = '" . NV_CURRENTTIME . "' WHERE lang = 'sys' AND module = 'global' AND config_name = 'timestamp'");
        nv_save_file_config_global();
    } else {
        $nv_Cache->delMod('settings');
    }
    nv_jsonOutput([
        'status' => 'success',
        'mess' => $nv_Lang->getGlobal('save_success')
    ]);
}

$page_title = $nv_Lang->getModule('uploadconfig');

$tpl = new \NukeViet\Template\NVSmarty();

$tpl->registerPlugin('modifier', 'floor', 'floor');
$tpl->registerPlugin('modifier', 'dsize', 'nv_convertfromBytes');

$tpl->setTemplateDir(get_module_tpl_dir('uploadconfig.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);

$tpl->assign('GCONFIG', $global_config);

if (defined('NV_IS_GODADMIN') and $global_config['idsite'] == 0) {
    $sys_max_size = min(nv_converttoBytes(ini_get('upload_max_filesize')), nv_converttoBytes(ini_get('post_max_size')));
    $p_size = $sys_max_size / 100;

    $tpl->assign('SYS_MAX_SIZE', $sys_max_size);
    $tpl->assign('STEP_SIZE', $p_size);
    $tpl->assign('MYINI', $myini);

    $checking_mode = [
        'strong' => $nv_Lang->getModule('strong_mode'),
        'mild' => $nv_Lang->getModule('mild_mode'),
        'lite' => $nv_Lang->getModule('lite_mode'),
        'none' => $nv_Lang->getModule('none_mode')
    ];
    $tpl->assign('CHECKING_MODE', $checking_mode);

    $supported_strong = false;
    if (nv_function_exists('finfo_open') or nv_class_exists('finfo', false) or nv_function_exists('mime_content_type') or (substr($sys_info['os'], 0, 3) != 'WIN' and (nv_function_exists('system') or nv_function_exists('exec')))) {
        $supported_strong = true;
    }
    $tpl->assign('SUPPORTED_STRONG', $supported_strong);

    $upload_chunk_size = '';
    $upload_chunk_size_text = '';
    if ($global_config['upload_chunk_size'] > 1048575) {
        $upload_chunk_size = round($global_config['upload_chunk_size'] / 1048576, 2, PHP_ROUND_HALF_DOWN);
        $upload_chunk_size_text = 'MB';
    } elseif ($global_config['upload_chunk_size'] > 1023) {
        $upload_chunk_size = round($global_config['upload_chunk_size'] / 1024, 2, PHP_ROUND_HALF_DOWN);
        $upload_chunk_size_text = 'KB';
    } elseif ($global_config['upload_chunk_size'] > 0) {
        $upload_chunk_size = $global_config['upload_chunk_size'];
    }
    $tpl->assign('UPLOAD_CHUNK_SIZE', $upload_chunk_size);
    $tpl->assign('UPLOAD_CHUNK_SIZE_TEXT', $upload_chunk_size_text);

    $upload_overflow_size = '';
    $upload_overflow_size_text = '';
    if ($global_config['nv_overflow_size'] > 1073741823) {
        $upload_overflow_size = round($global_config['nv_overflow_size'] / 1073741824, 2, PHP_ROUND_HALF_DOWN);
        $upload_overflow_size_text = 'GB';
    } elseif ($global_config['nv_overflow_size'] > 1048575) {
        $upload_overflow_size = round($global_config['nv_overflow_size'] / 1048576, 2, PHP_ROUND_HALF_DOWN);
        $upload_overflow_size_text = 'MB';
    }
    $tpl->assign('UPLOAD_OVERFLOW_SIZE', $upload_overflow_size);
    $tpl->assign('UPLOAD_OVERFLOW_SIZE_TEXT', $upload_overflow_size_text);
}

$contents = $tpl->fetch('uploadconfig.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
