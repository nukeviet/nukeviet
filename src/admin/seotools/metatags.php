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

$metatags = [];
$metatags['meta'] = [];
$ignore = array( 'content-type', 'generator', 'description', 'keywords' );
$vas = array( '{CONTENT-LANGUAGE} (' . $nv_Lang->getGlobal('Content_Language') . ')', '{LANGUAGE} (' . $nv_Lang->getGlobal('LanguageName') . ')', '{SITE_NAME} (' . $global_config['site_name'] . ')', '{SITE_EMAIL} (' . $global_config['site_email'] . ')' );

if ($nv_Request->isset_request('submit', 'post')) {
    $metaGroupsName = $nv_Request->get_array('metaGroupsName', 'post');
    $metaGroupsValue = $nv_Request->get_array('metaGroupsValue', 'post');
    $metaContents = $nv_Request->get_array('metaContents', 'post');

    foreach ($metaGroupsName as $key => $name) {
        if ($name == 'http-equiv' or $name == 'name' or $name == 'property') {
            $value = trim(strip_tags($metaGroupsValue[$key]));
            $content = trim(strip_tags($metaContents[$key]));
            $newArray = array(
                'group' => $name,
                'value' => $value,
                'content' => $content
            );
            if (preg_match("/^[a-zA-Z0-9\-\_\.\:]+$/", $value) and !in_array($value, $ignore) and preg_match("/^([^\'\"]+)$/", $content) and !in_array($newArray, $metatags['meta'])) {
                $metatags['meta'][] = $newArray;
            }
        }
    }

    if (file_exists($file_metatags)) {
        nv_deletefile($file_metatags);
    }

    if (!empty($metatags['meta'])) {
        $array2XML = new NukeViet\Xml\Array2XML();
        $array2XML->saveXML($metatags, 'metatags', $file_metatags, $global_config['site_charset']);
    }
    $metaTagsOgp = (int)$nv_Request->get_bool('metaTagsOgp', 'post');
    $description_length = $nv_Request->get_int('description_length', 'post');

    $db->query("UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = '" . $metaTagsOgp . "' WHERE lang = 'sys' AND module = 'site' AND config_name = 'metaTagsOgp'");
    $db->query("UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = '" . $description_length . "' WHERE lang = 'sys' AND module = 'site' AND config_name = 'description_length'");
    $nv_Cache->delAll(false);
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
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

$page_title = $nv_Lang->getModule('metaTagsConfig');

$tpl = new \NukeViet\Template\Smarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);

$tpl->assign('NOTE', $nv_Lang->getModule('metaTagsNote', implode(', ', $ignore)));
$tpl->assign('VARS', $nv_Lang->getModule('metaTagsVar') . ': ' . implode(', ', $vas));
$tpl->assign('CONFIG', $global_config);
$tpl->assign('METAS', $metatags['meta']);

$contents = $tpl->fetch('metatags.tpl');

$array_url_instruction['metatags'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:seotools:metatags';

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
