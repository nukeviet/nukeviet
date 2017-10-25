<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    die('Stop!!!');
}

$ini = nv_parse_ini_file(NV_ROOTDIR . '/includes/ini/mime.ini', true);

$myini = array(
    'types' => array(''),
    'exts' => array(''),
    'mimes' => array('')
);

foreach ($ini as $type => $extmime) {
    $myini['types'][] = $type;
    $myini['exts'] = array_merge($myini['exts'], array_keys($extmime));
    $m = array_values($extmime);

    if (is_string($m)) {
        $myini['mimes'] = array_merge($myini['mimes'], $m);
    } else {
        foreach ($m as $m2) {
            if (!is_array($m2)) {
                $m2 = array($m2);
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

if ($nv_Request->isset_request('submit', 'post')) {
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
    $mime = implode(',', $mime);

    $upload_checking_mode = $nv_Request->get_string('upload_checking_mode', 'post', '');
    if ($upload_checking_mode != 'mild' and $upload_checking_mode != 'lite' and $upload_checking_mode != 'strong') {
        $upload_checking_mode = 'none';
    }

    $nv_max_size = $nv_Request->get_float('nv_max_size', 'post', $global_config['nv_max_size']);
    $nv_max_size = min(nv_converttoBytes(ini_get('upload_max_filesize')), nv_converttoBytes(ini_get('post_max_size')), $nv_max_size);
    $nv_auto_resize = (int)$nv_Request->get_bool('nv_auto_resize', 'post', 0);

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

    $sth = $db->prepare("UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'global' AND config_name = :config_name");
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

    $array_config_define = array();
    $array_config_define['upload_alt_require'] = (int)$nv_Request->get_bool('upload_alt_require', 'post', 0);
    $array_config_define['upload_auto_alt'] = (int)$nv_Request->get_bool('upload_auto_alt', 'post', 0);

    $sth->bindValue(':config_name', 'upload_alt_require', PDO::PARAM_STR);
    $sth->bindValue(':config_value', $array_config_define['upload_alt_require'], PDO::PARAM_STR);
    $sth->execute();

    $sth->bindValue(':config_name', 'upload_auto_alt', PDO::PARAM_STR);
    $sth->bindValue(':config_value', $array_config_define['upload_auto_alt'], PDO::PARAM_STR);
    $sth->execute();

    $array_config_define = array();
    $array_config_define['nv_max_width'] = $nv_Request->get_int('nv_max_width', 'post');
    $array_config_define['nv_max_height'] = $nv_Request->get_int('nv_max_height', 'post');

    $sth = $db->prepare("UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'define' AND config_name = :config_name");
    foreach ($array_config_define as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    nv_save_file_config_global();

    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
}

$page_title = $lang_module['uploadconfig'];

$xtpl = new XTemplate('uploadconfig.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('OP', $op);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_MAX_WIDTH', NV_MAX_WIDTH);
$xtpl->assign('NV_MAX_HEIGHT', NV_MAX_HEIGHT);

$sys_max_size = min(nv_converttoBytes(ini_get('upload_max_filesize')), nv_converttoBytes(ini_get('post_max_size')));
$p_size = $sys_max_size / 100;

$xtpl->assign('SYS_MAX_SIZE', nv_convertfromBytes($sys_max_size));
$xtpl->assign('NV_AUTO_RESIZE', ($global_config['nv_auto_resize']) ? ' checked="checked"' : '');
$xtpl->assign('UPLOAD_ALT_REQUIRE', ($global_config['upload_alt_require']) ? ' checked="checked"' : '');
$xtpl->assign('UPLOAD_AUTO_ALT', ($global_config['upload_auto_alt']) ? ' checked="checked"' : '');

for ($index = 100; $index > 0; --$index) {
    $size = floor($index * $p_size);

    $xtpl->assign('SIZE', array(
        'key' => $size,
        'title' => nv_convertfromBytes($size),
        'selected' => ($size == $global_config['nv_max_size']) ? ' selected="selected"' : ''
    ));

    $xtpl->parse('main.size');
}

$_upload_checking_mode = array(
    'strong' => $lang_module['strong_mode'],
    'mild' => $lang_module['mild_mode'],
    'lite' => $lang_module['lite_mode'],
    'none' => $lang_module['none_mode']
);

foreach ($_upload_checking_mode as $m => $n) {
    $xtpl->assign('UPLOAD_CHECKING_MODE', array(
        'key' => $m,
        'title' => $n,
        'selected' => ($m == $global_config['upload_checking_mode']) ? ' selected="selected"' : ''
    ));
    $xtpl->parse('main.upload_checking_mode');
}

$strong = false;
if (nv_function_exists('finfo_open') or nv_class_exists('finfo', false) or nv_function_exists('mime_content_type') or (substr($sys_info['os'], 0, 3) != 'WIN' and (nv_function_exists('system') or nv_function_exists('exec')))) {
    $strong = true;
}

$xtpl->assign('UPLOAD_CHECKING_NOTE', !$strong ? $lang_module['upload_checking_note'] : '');

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

$xtpl->assign('UPLOAD_CHUNK_SIZE', $upload_chunk_size);
$array_chunk_size = array('KB', 'MB');
foreach ($array_chunk_size as $chunk_size) {
    $chunk_size_lev = array(
        'key' => $chunk_size,
        'title' => $chunk_size,
        'selected' => $chunk_size == $upload_chunk_size_text ? ' selected="selected"' : ''
    );
    $xtpl->assign('CHUNK_SIZE_LEV', $chunk_size_lev);
    $xtpl->parse('main.chunk_size_lev');
}

foreach ($myini['types'] as $key => $name) {
    $xtpl->assign('TYPES', array(
        'key' => $key,
        'title' => $name,
        'checked' => in_array($name, $global_config['file_allowed_ext']) ? ' checked="checked"' : ''
    ));
    $xtpl->parse('main.types');
}

foreach ($myini['exts'] as $key => $name) {
    $xtpl->assign('EXTS', array(
        'key' => $key,
        'title' => $name,
        'checked' => in_array($name, $global_config['forbid_extensions']) ? ' checked="checked"' : ''
    ));
    $xtpl->parse('main.exts');
}

foreach ($myini['mimes'] as $key => $name) {
    $xtpl->assign('MIMES', array(
        'key' => $key,
        'title' => $name,
        'checked' => in_array($name, $global_config['forbid_mimes']) ? ' checked="checked"' : ''
    ));
    $xtpl->parse('main.mimes');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';