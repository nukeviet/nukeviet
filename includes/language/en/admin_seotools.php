<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

$lang_translator['author'] = 'VINADES.,JSC <contact@vinades.vn>';
$lang_translator['createdate'] = '04/03/2010, 15:22';
$lang_translator['copyright'] = '@Copyright (C) 2009-2021 VINADES.,JSC. All rights reserved';
$lang_translator['info'] = '';
$lang_translator['langtype'] = 'lang_module';

$lang_module['pagetitle'] = 'Configuration tag "title"';
$lang_module['metaTagsConfig'] = 'Meta-Tags Configuration';
$lang_module['linkTagsConfig'] = 'Link-Tags Configuration';
$lang_module['sitemapPing'] = 'Sitemap Ping';
$lang_module['searchEngine'] = 'search Engine';
$lang_module['searchEngineConfig'] = 'Search Engines Management';
$lang_module['searchEngineName'] = 'searchEngine Name';
$lang_module['searchEngineActive'] = 'Active';
$lang_module['searchEngineSelect'] = 'Please Select searchEngine';
$lang_module['sitemapModule'] = 'Please Select Module';
$lang_module['sitemapView'] = 'View sitemap';
$lang_module['sitemapSend'] = 'Send';
$lang_module['PingNotSupported'] = 'Ping not Supported';
$lang_module['pleasePingAgain'] = 'You have just sent it. Wait a while';
$lang_module['searchEngineValue'] = 'Ping Service Links';
$lang_module['searchEngineFailed'] = 'Error Ping Service Links';
$lang_module['pingOK'] = 'Sitemap file has been sent successfully';
$lang_module['submit'] = 'Submit';
$lang_module['weight'] = 'No.';
$lang_module['robots'] = 'Config. robots.txt';
$lang_module['robots_number'] = 'Order number';
$lang_module['robots_filename'] = 'File name';
$lang_module['robots_type'] = 'Mode';
$lang_module['robots_type_0'] = 'No access';
$lang_module['robots_type_1'] = 'Not show in the robots.txt file';
$lang_module['robots_type_2'] = 'Allow access';
$lang_module['robots_error_writable'] = 'Error: The system can not write the robots.txt file, please create a file robots.txt with below content and put into the site parent folder';
$lang_module['pagetitle2'] = 'Display tag "title" option';
$lang_module['pagetitleNote'] = '<strong>Accept variables:</strong><br /><br />- <strong>pagetitle</strong>: Page title is determined in each specific case,<br />- <strong>funcname</strong>: Function,<br />- <strong>modulename</strong>: Module name,<br />- <strong>sitename</strong>: Site name';
$lang_module['metaTagsGroupName'] = 'Group type';
$lang_module['metaTagsGroupValue'] = 'Group Name';
$lang_module['metaTagsNote'] = 'The Meta-Tags: "%s" is determined automatically';
$lang_module['metaTagsVar'] = 'Accept the following variables';
$lang_module['metaTagsContent'] = 'Content';
$lang_module['metaTagsOgp'] = 'Active meta-Tag Open Graph protocol';
$lang_module['metaTagsOgpNote'] = 'Open Graph protocol: Is a prepared  data to share on facebook, view detail in <a href="http://ogp.me" target="_blank">http://ogp.me</a>';
$lang_module['description_length'] = 'Number of characters of meta description tag';
$lang_module['description_note'] = ' = 0 unlimited number of characters';
$lang_module['module'] = 'Module';
$lang_module['custom_title'] = 'Outside site name';
$lang_module['rpc'] = 'PING service';
$lang_module['rpc_setting'] = 'Configuration PING service';
$lang_module['rpc_error_timeout'] = 'Please wait %s again to continue Ping';
$lang_module['rpc_error_titleEmpty'] = 'Please declare the name of the URL needed Ping';
$lang_module['rpc_error_urlEmpty'] = 'Please correct URL declare Ping';
$lang_module['rpc_error_rsschannelEmpty'] = 'Please correct declaration of URLs RSS channel';
$lang_module['rpc_error_serviceEmpty'] = 'Service not available. Please notify the board administrator';
$lang_module['rpc_error_unknown'] = 'Unknown error';
$lang_module['rpc_flerror0'] = 'PING successed';
$lang_module['rpc_flerror1'] = 'Error';
$lang_module['rpc_ftitle'] = 'PING is a free utility to help you quickly create indexes for your website on major search servers.';
$lang_module['rpc_webtitle'] = 'Title news';
$lang_module['rpc_weblink'] = 'URL news';
$lang_module['rpc_rsslink'] = 'RSS chanel news';
$lang_module['rpc_submit'] = 'PING !';
$lang_module['rpc_linkname'] = 'Sever';
$lang_module['rpc_reruslt'] = 'Result';
$lang_module['rpc_message'] = 'Message';
$lang_module['rpc_ping'] = 'PING when updating data';
$lang_module['rpc_ping_page'] = 'PING article';
$lang_module['rpc_finish'] = 'Complete PING process, you may want to transfer the management page article?';
$lang_module['private_site'] = 'Discourage search engines from indexing this site';
$lang_module['ogp_image'] = 'Default image for Open Graph tags<br/>(best size: 1080px x 1080px)';

$lang_module['linkTags_attribute'] = 'Attribute';
$lang_module['linkTags_value'] = 'Value';
$lang_module['linkTags_add_attribute'] = 'Add attribute';
$lang_module['linkTags_rel_val_required'] = 'You need to declare the value of the rel attribute';
$lang_module['linkTags_add'] = 'Add new link-tag';
$lang_module['linkTags_acceptVars'] = 'Variables accepted in attribute value';
$lang_module['linkTags_del_confirm'] = 'Do you really want to delete?';
