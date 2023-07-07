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

$ShortName = $Description = '';
$array_mod = LoadModulesSearch();
if (!empty($array_op[1]) and isset($opensearch_link[$array_op[1]])) {
    $mod = $opensearch_link[$array_op[1]];
    $ShortName = $mod[0];
    $Description = !empty($mod[1]) ? $mod[1] : sprintf($lang_module['opensearch_desc_mod'], $array_mod[$array_op[1]]['custom_title'], $global_config['site_name']);
    $Url .= '&m=' . $array_op[1];
} elseif(isset($opensearch_link['site'])) {
    $mod = $opensearch_link['site'];
    $ShortName = $mod[0];
    $Description = !empty($mod[1]) ? $mod[1] : sprintf($lang_module['opensearch_desc_all'], $global_config['site_name']);
}
if (empty($ShortName) or empty($Description)) {
    exit(0);
}

$Url = urlRewriteWithDomain($Url, NV_MY_DOMAIN);
$Url = str_replace('searchTerms', '{searchTerms}', $Url);

$searchForm = urlRewriteWithDomain(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, NV_MY_DOMAIN);

if (!empty($global_config['site_favicon']) and file_exists(NV_ROOTDIR . '/' . $global_config['site_favicon'])) {
    $icon = $global_config['site_favicon'];
} elseif (theme_file_exists($global_config['module_theme'] . '/images/favicon/favicon.ico')) {
    $icon = 'themes/' . $global_config['module_theme'] . '/images/favicon/favicon.ico';
} else {
    $icon = 'favicon.ico';
}
$icon = NV_MY_DOMAIN . NV_BASE_SITEURL . $icon;
$type = nv_get_mime_from_ini(nv_getextension($icon));

$namespace = 'http://www.mozilla.org/2006/browser/search/';
$openSearchHeader = '<?xml version="1.0" encoding="UTF-8"?><OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/" xmlns:moz="' . $namespace . '"></OpenSearchDescription>';
$xml = new SimpleXMLElement($openSearchHeader);
$xml->addChild('ShortName', $ShortName);
$xml->addChild('Description', $Description);
$xml->addChild('InputEncoding', 'UTF-8');
$img = $xml->addChild('Image', $icon);
$img->addAttribute('width', '16');
$img->addAttribute('height', '16');
$img->addAttribute('type', $type);
$ul = $xml->addChild('Url');
$ul->addAttribute('type', 'text/html');
$ul->addAttribute('method', 'get');
$ul->addAttribute('template', $Url);
$xml->addChild('SearchForm', $searchForm, $namespace);

$contents = $xml->asXML();
$lastModified = NV_CURRENTTIME - 86400;
nv_xmlOutput($contents, $lastModified);
