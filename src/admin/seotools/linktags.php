<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
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

// Lưu cấu hình opensearch
$checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid']);
if ($nv_Request->isset_request('opensearch', 'post') and $checkss == $nv_Request->get_string('checkss', 'post')) {
    $opensearch_link = $nv_Request->get_typed_array('opensearch_link', 'post', 'title', []);
    $shortname = $nv_Request->get_typed_array('shortname', 'post', 'title', []);
    $description = $nv_Request->get_typed_array('description', 'post', 'title', []);

    $config_value = [];
    $mods = array_keys($site_mods);
    array_unshift($mods, '_site');

    foreach ($mods as $mod) {
        $config_value[$mod] = [
            'active' => intval(in_array($mod, $opensearch_link, true)),
            'shortname' => $shortname[$mod] ?? '',
            'description' => $description[$mod] ?? '',
        ];

        if ($config_value[$mod]['active'] and empty($config_value[$mod]['shortname'])) {
            nv_jsonOutput([
                'status' => 'error',
                'input' => 'shortname',
                'input_parent' => '[data-sarea="' . $mod . '"]',
                'mess' => $nv_Lang->getModule('ShortName_required')
            ]);
        }
        if (empty($config_value[$mod]['shortname'])) {
            unset($config_value[$mod]);
        }
    }

    nv_insert_logs(NV_LANG_DATA, $module_name, 'CHANGE_OPENSEARCH', '', $admin_info['userid']);

    $config_value = !empty($config_value) ? json_encode($config_value) : '';
    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value= :config_value WHERE config_name = 'opensearch_link' AND lang = '" . NV_LANG_DATA . "' AND module='global'");
    $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
    $sth->execute();
    $nv_Cache->delAll();

    nv_jsonOutput([
        'status' => 'success',
        'mess' => $nv_Lang->getGlobal('save_success'),
        'refresh' => 1
    ]);
}

// Thêm linktag
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
            'mess' => $nv_Lang->getModule('linkTags_rel_val_required')
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

    nv_insert_logs(NV_LANG_DATA, $module_name, 'Add linktag', '', $admin_info['userid']);

    nv_jsonOutput([
        'status' => 'OK',
        'mess' => ''
    ]);
}

// Xóa linktag
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
    nv_insert_logs(NV_LANG_DATA, $module_name, 'Delete linktag', $key, $admin_info['userid']);
    nv_htmlOutput('OK');
}

$page_title = $nv_Lang->getModule('linkTagsConfig');

$opensearch_link = [];
if (!empty($global_config['opensearch_link'])) {
    $opensearch_link = json_decode($global_config['opensearch_link'], true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        $opensearch_link = [];
    }
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->registerPlugin('modifier', 'array_merge', 'array_merge');
$tpl->setTemplateDir(get_module_tpl_dir('linktags.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('CHECKSS', $checkss);

$tpl->assign('SITE_MODS', $site_mods);
$tpl->assign('OPENSEARCH_LINK', $opensearch_link);

$acceptVars = [
    '<code>{BASE_SITEURL}</code> (' . NV_BASE_SITEURL . ')',
    '<code>{UPLOADS_DIR}</code> (' . NV_UPLOADS_DIR . ')',
    '<code>{ASSETS_DIR}</code> (' . NV_ASSETS_DIR . ')',
    '<code>{CONTENT-LANGUAGE}</code> (' . $nv_Lang->getGlobal('Content_Language') . ')',
    '<code>{LANGUAGE}</code> (' . $nv_Lang->getGlobal('LanguageName') . ')',
    '<code>{SITE_NAME}</code> (' . $global_config['site_name'] . ')',
    '<code>{SITE_EMAIL}</code> (' . $global_config['site_email'] . ')'
];
$tpl->assign('ACCEPTVARS', implode(', ', $acceptVars));
$tpl->assign('LINKTAGS', $linktags['link']);

$contents = $tpl->fetch('linktags.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
