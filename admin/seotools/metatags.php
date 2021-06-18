<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/12/2010, 13:16
 */

if (!defined('NV_IS_FILE_SEOTOOLS')) {
    die('Stop!!!');
}

if ($global_config['idsite']) {
    $file_metatags = NV_ROOTDIR . '/' . NV_DATADIR . '/site_' . $global_config['idsite'] . '_metatags.xml';
} else {
    $file_metatags = NV_ROOTDIR . '/' . NV_DATADIR . '/metatags.xml';
}
$sys_metatags = [];
$sys_metatags['meta'] = [];
$mt = nv_object2array(simplexml_load_file(NV_ROOTDIR . '/' . NV_DATADIR . '/metatags.xml'));
if ($mt['meta_item']) {
    if (isset($mt['meta_item'][0])) {
        $sys_metatags['meta'] = $mt['meta_item'];
    } else {
        $sys_metatags['meta'][] = $mt['meta_item'];
    }
}
$default_metasys = [];
foreach ($sys_metatags['meta'] as $value) {
    if ($value['group'] == 'name' and in_array($value['value'], ['author', 'copyright'])) {
        $default_metasys[] = [
            'group' => $value['group'],
            'value' => $value['value'],
            'content' => $value['content']
        ];
    }
}

$metatags = [];
$metatags['meta'] = [];
$ignore = ['content-type', 'generator', 'description', 'keywords'];
$vas = [
    '{CONTENT-LANGUAGE} (' . $lang_global['Content_Language'] . ')',
    '{LANGUAGE} (' . $lang_global['LanguageName'] . ')',
    '{SITE_NAME} (' . $global_config['site_name'] . ')',
    '{SITE_EMAIL} (' . $global_config['site_email'] . ')'
];

$checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid']);
if ($checkss == $nv_Request->get_string('checkss', 'post')) {
    $metaGroupsName = $nv_Request->get_array('metaGroupsName', 'post');
    $metaGroupsValue = $nv_Request->get_array('metaGroupsValue', 'post');
    $metaContents = $nv_Request->get_array('metaContents', 'post');

    foreach ($metaGroupsName as $key => $name) {
        if ($name != 'http-equiv' and $name != 'name' and $name != 'property') {
            continue;
        }
        $value = str_replace(['\\', '"'], '', nv_unhtmlspecialchars(trim(strip_tags($metaGroupsValue[$key]))));
        $content = str_replace(['\\', '"'], '', nv_unhtmlspecialchars(trim(strip_tags($metaContents[$key]))));
        if ($global_config['idsite'] and $name == 'name' and in_array($value, ['author', 'copyright'])) {
            continue;
        }
        $newArray = [
            'group' => $name,
            'value' => $value,
            'content' => $content
        ];
        if (preg_match("/^[a-zA-Z0-9\-\_\.\:]+$/", $value) and !in_array($value, $ignore) and preg_match("/^([^\'\"]+)$/", $content) and !in_array($newArray, $metatags['meta'])) {
            $metatags['meta'][] = $newArray;
        }
    }
    if ($global_config['idsite'] and !empty($default_metasys)) {
        $metatags['meta'] = array_merge($default_metasys, $metatags['meta']);
    }

    if (file_exists($file_metatags)) {
        nv_deletefile($file_metatags);
    }
    if (!empty($metatags['meta'])) {
        $array2XML = new NukeViet\Xml\Array2XML();
        $array2XML->saveXML($metatags, 'metatags', $file_metatags, $global_config['site_charset']);
    }

    $metaTagsOgp = (int)$nv_Request->get_bool('metaTagsOgp', 'post', false);
    $description_length = $nv_Request->get_absint('description_length', 'post', 0);
    $private_site = (int)$nv_Request->get_bool('private_site', 'post', false);

    $db->query("UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = '" . $metaTagsOgp . "' WHERE lang = 'sys' AND module = 'site' AND config_name = 'metaTagsOgp'");
    $db->query("UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = '" . $description_length . "' WHERE lang = 'sys' AND module = 'site' AND config_name = 'description_length'");
    $db->query("UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = '" . $private_site . "' WHERE lang = 'sys' AND module = 'site' AND config_name = 'private_site'");

    $nv_Cache->delAll(false);
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
} elseif (empty($global_config['idsite'])) {
    $metatags = $sys_metatags;
} else {
    if (!file_exists($file_metatags)) {
        $file_metatags = NV_ROOTDIR . '/' . NV_DATADIR . '/metatags.xml';
    }
    $mt = simplexml_load_file($file_metatags);
    $mt = nv_object2array($mt);
    if ($mt['meta_item']) {
        if (isset($mt['meta_item'][0])) {
            $metatags['meta'] = $mt['meta_item'];
        } else {
            $metatags['meta'][] = $mt['meta_item'];
        }
    }
}

$page_title = $lang_module['metaTagsConfig'];

$xtpl = new XTemplate('metatags.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('NOTE', sprintf($lang_module['metaTagsNote'], implode(', ', $ignore)));
$xtpl->assign('VARS', $lang_module['metaTagsVar'] . ': ' . implode(', ', $vas));
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('OP', $op);
$xtpl->assign('CHECKSS', $checkss);

// Các meta hiện có
if (!empty($metatags['meta'])) {
    foreach ($metatags['meta'] as $value) {
        $value['h_selected'] = $value['group'] == 'http-equiv' ? ' selected="selected"' : '';
        $value['n_selected'] = $value['group'] == 'name' ? ' selected="selected"' : '';
        $value['p_selected'] = $value['group'] == 'property' ? ' selected="selected"' : '';

        if ($global_config['idsite'] and $value['group'] == 'name' and in_array($value['value'], ['author', 'copyright'])) {
            $value['disabled'] = ' disabled="disabled"';
        } else {
            $value['disabled'] = '';
        }

        $xtpl->assign('DATA', $value);
        $xtpl->parse('main.loop');
    }
}

// Tạo mới 2 meta trống
for ($i = 0; $i < 2; ++$i) {
    $data = [
        'content' => '',
        'value' => '',
        'h_selected' => '',
        'n_selected' => ''
    ];
    $xtpl->assign('DATA', $data);
    $xtpl->parse('main.loop');
}

$xtpl->assign('METATAGSOGPCHECKED', $global_config['metaTagsOgp'] ? ' checked="checked" ' : '');
$xtpl->assign('PRIVATE_SITE', $global_config['private_site'] ? ' checked="checked" ' : '');
$xtpl->assign('DESCRIPTION_LENGTH', $global_config['description_length']);

$xtpl->parse('main');
$contents = $xtpl->text('main');

$array_url_instruction['metatags'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:seotools:metatags';

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
