<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_SEARCH')) {
    exit('Stop!!!');
}

$Url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&q=searchTerms';

if (!empty($global_config['site_favicon']) and file_exists(NV_ROOTDIR . '/' . $global_config['site_favicon'])) {
    $icon = $global_config['site_favicon'];
} elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/images/favicon/favicon.ico')) {
    $icon = 'themes/' . $global_config['module_theme'] . '/images/favicon/favicon.ico';
} else {
    $icon = 'favicon.ico';
}
$icon = NV_MY_DOMAIN . NV_BASE_SITEURL . $icon;
$type = nv_get_mime_from_ini(nv_getextension($icon));

$array_mod = LoadModulesSearch();
if (!empty($array_op[1]) and isset($array_mod[$array_op[1]])) {
    $mod = $array_mod[$array_op[1]];
    $ShortName = $mod['custom_title'];
    $Description = sprintf($lang_module['opensearch_desc_mod'], $mod['custom_title'], $global_config['site_name']);
    $Url .= '&m=' . $mod['module_name'];
} else {
    $ShortName = $global_config['site_name'];
    $Description = sprintf($lang_module['opensearch_desc_all'], $global_config['site_name']);
}

$Url = NV_MY_DOMAIN . nv_url_rewrite($Url, true);
$Url = str_replace('searchTerms', '{searchTerms}', $Url);

$contents = '<?xml version="1.0" encoding="utf-8"?>
<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/">
    <ShortName>' . $ShortName . '</ShortName>
    <Description>' . $Description . '</Description>
    <InputEncoding>UTF-8</InputEncoding>
    <Image width="16" height="16" type="' . $type . '">' . $icon . '</Image>
    <Url type="text/html" method="GET" template="' . $Url . '"/>
</OpenSearchDescription>';

$lastModified = NV_CURRENTTIME - 86400;
nv_xmlOutput($contents, $lastModified);