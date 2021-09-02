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
    $file_linktags = NV_ROOTDIR . '/' . NV_DATADIR . '/site_' . $global_config['idsite'] . '_linktags.xml';
} else {
    $file_linktags = NV_ROOTDIR . '/' . NV_DATADIR . '/linktags.xml';
}

$linktags = [];
$linktags['link'] = [];
if (file_exists($file_linktags)) {
    $lt = nv_object2array(simplexml_load_file($file_linktags));
    if (!empty($lt['link_item'])) {
        if (isset($lt['link_item'][0])) {
            $linktags['link'] = $lt['link_item'];
        } else {
            $linktags['link'][] = $lt['link_item'];
        }
    }
}

$checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid']);
if ($nv_Request->isset_request('add', 'post') and $checkss == $nv_Request->get_string('checkss', 'post')) {
    $key = $nv_Request->get_string('key', 'post', '');
    $linktags_key = -1;
    if (!empty($key)) {
        $linktags_key = (int) (substr($key, 2));
    }
    $attributes = $nv_Request->get_typed_array('linktags_attribute', 'post', 'title');
    $values = $nv_Request->get_typed_array('linktags_value', 'post', 'title');

    $rel_key = array_search('rel', $attributes, true);
    if ($rel_key === false or empty($values[$rel_key])) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $lang_module['linkTags_rel_val_required']
        ]);
    }

    $content = [];
    foreach ($attributes as $k => $attribute) {
        $attribute = trim($attribute);
        if (!empty($attribute) and preg_match('/^[a-zA-Z][a-zA-Z0-9\_\-]+$/', $attribute)) {
            $content[$attribute] = trim(strip_tags($values[$k]));
        }
    }

    if (!empty($key)) {
        $linktags['link'][$linktags_key] = $content;
    } else {
        $linktags['link'][] = $content;
    }

    if (file_exists($file_linktags)) {
        nv_deletefile($file_linktags);
    }

    if (!empty($linktags['link'])) {
        $array2XML = new NukeViet\Xml\Array2XML();
        $array2XML->saveXML($linktags, 'link', $file_linktags, $global_config['site_charset']);
    }

    nv_jsonOutput([
        'status' => 'OK',
        'mess' => ''
    ]);
}

if ($nv_Request->isset_request('del,key', 'post') and $checkss == $nv_Request->get_string('checkss', 'post')) {
    $key = $nv_Request->get_string('key', 'post', '');
    $key = (int) (substr($key, 2));
    if (isset($linktags['link'][$key])) {
        unset($linktags['link'][$key]);

        if (file_exists($file_linktags)) {
            nv_deletefile($file_linktags);
        }

        if (!empty($linktags['link'])) {
            $array2XML = new NukeViet\Xml\Array2XML();
            $array2XML->saveXML($linktags, 'link', $file_linktags, $global_config['site_charset']);
        }
    }
    echo 'OK';
    exit();
}

$page_title = $lang_module['linkTagsConfig'];

$xtpl = new XTemplate('linktags.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('OP', $op);
$xtpl->assign('CHECKSS', $checkss);

$acceptVars = [
    '<code>{BASE_SITEURL}</code> (' . NV_BASE_SITEURL . ')',
    '<code>{UPLOADS_DIR}</code> (' . NV_UPLOADS_DIR . ')',
    '<code>{ASSETS_DIR}</code> (' . NV_ASSETS_DIR . ')',
    '<code>{CONTENT-LANGUAGE}</code> (' . $lang_global['Content_Language'] . ')',
    '<code>{LANGUAGE}</code> (' . $lang_global['LanguageName'] . ')',
    '<code>{SITE_NAME}</code> (' . $global_config['site_name'] . ')',
    '<code>{SITE_EMAIL}</code> (' . $global_config['site_email'] . ')'
];
$xtpl->assign('ACCEPTVARS', implode('<br/>', $acceptVars));

if (!empty($linktags['link'])) {
    foreach ($linktags['link'] as $key => $val) {
        $title = [];
        foreach ($val as $attribute => $v) {
            $title[] = $attribute . (!empty($v) ? '=&quot;' . $v . '&quot;' : '');

            if ($attribute != 'rel') {
                $xtpl->assign('ATTRIBUTE', [
                    'k' => $attribute,
                    'v' => $v
                ]);
                $xtpl->parse('main.if_links.item.attr');
            }
        }
        $title = '&lt;link ' . implode(' ', $title) . ' /&gt;';
        $item = [
            'key' => $key,
            'title' => $title,
            'rel' => $val['rel']
        ];
        $xtpl->assign('LINK_TAGS', $item);
        $xtpl->parse('main.if_links.item');
    }
    $xtpl->parse('main.if_links');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
