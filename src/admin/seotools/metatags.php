<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_SEOTOOLS')) {
    exit('Stop!!!');
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
    if ($value['group'] == 'name' and in_array($value['value'], ['author', 'copyright'], true)) {
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
    '<code>{BASE_SITEURL}</code> (' . NV_BASE_SITEURL . ')',
    '<code>{UPLOADS_DIR}</code> (' . NV_UPLOADS_DIR . ')',
    '<code>{ASSETS_DIR}</code> (' . NV_ASSETS_DIR . ')',
    '<code>{CONTENT-LANGUAGE}</code> (' . $lang_global['Content_Language'] . ')',
    '<code>{LANGUAGE}</code> (' . $lang_global['LanguageName'] . ')',
    '<code>{SITE_NAME}</code> (' . $global_config['site_name'] . ')',
    '<code>{SITE_EMAIL}</code> (' . $global_config['site_email'] . ')'
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
        if ($global_config['idsite'] and $name == 'name' and in_array($value, ['author', 'copyright'], true)) {
            continue;
        }
        $newArray = [
            'group' => $name,
            'value' => $value,
            'content' => $content
        ];
        if (preg_match("/^[a-zA-Z0-9\-\_\.\:]+$/", $value) and !in_array($value, $ignore, true) and preg_match("/^([^\'\"]+)$/", $content) and !in_array($newArray, $metatags['meta'], true)) {
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

    $array_config = [];
    $array_config['metaTagsOgp'] = (int) $nv_Request->get_bool('metaTagsOgp', 'post', false);
    $array_config['description_length'] = $nv_Request->get_absint('description_length', 'post', 0);
    $array_config['private_site'] = (int) $nv_Request->get_bool('private_site', 'post', false);
    $array_config['ogp_image'] = '';
    $ogp_image = $nv_Request->get_title('ogp_image', 'post', '');
    if (!empty($ogp_image) and !nv_is_url($ogp_image) and nv_is_file($ogp_image) === true) {
        $array_config['ogp_image'] = substr($ogp_image, strlen(NV_BASE_SITEURL));
    }

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value= :config_value WHERE config_name = :config_name AND lang = 'sys' AND module='site'");
    foreach ($array_config as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

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

        if ($global_config['idsite'] and $value['group'] == 'name' and in_array($value['value'], ['author', 'copyright'], true)) {
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
$ogp_image = '';
if (!empty($global_config['ogp_image']) and !nv_is_url($global_config['ogp_image']) and file_exists(NV_ROOTDIR . '/' . $global_config['ogp_image'])) {
    $ogp_image = NV_BASE_SITEURL . $global_config['ogp_image'];
}
$xtpl->assign('OGP_IMAGE', $ogp_image);

$xtpl->parse('main');
$contents = $xtpl->text('main');

$array_url_instruction['metatags'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:seotools:metatags';

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
