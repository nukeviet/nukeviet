<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$db->query('TRUNCATE TABLE ' . $db_config['prefix'] . '_' . $lang_data . '_modules');
$sth = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_' . $lang_data . '_modules (title, module_file, module_data, module_upload, module_theme, custom_title, admin_title, set_time, main_file, admin_file, theme, mobile, description, keywords, groups_view, weight, act, admins, rss, icon, sitemap) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
$sth->execute(['about', 'Page', 'about', 'about', 'Page', $install_lang['modules']['about'], $install_lang['modules']['about_for_acp'], NV_CURRENTTIME, 1, 1, '', '', '', '', '6', 1, 1, '', 1, 'fas fa-book-reader', 1]);
$sth->execute(['news', 'News', 'news', 'news', 'News', $install_lang['modules']['news'], $install_lang['modules']['news_for_acp'], NV_CURRENTTIME, 1, 1, '', '', '', '', '6', 2, 1, '', 1, 'far fa-newspaper', 1]);
$sth->execute(['users', 'Users', 'users', 'users', 'Users', $install_lang['modules']['users'], $install_lang['modules']['users_for_acp'], NV_CURRENTTIME, 1, 1, '', '', '', '', '6', 3, 1, '', 0, 'fas fa-users', 1]);
$sth->execute(['contact', 'Contact', 'contact', 'contact', 'Contact', $install_lang['modules']['contact'], $install_lang['modules']['contact_for_acp'], NV_CURRENTTIME, 1, 1, '', '', '', '', '6', 4, 1, '', 0, 'fas fa-phone', 1]);
$sth->execute(['statistics', 'Statistics', 'statistics', 'statistics', 'Statistics', $install_lang['modules']['statistics'], $install_lang['modules']['statistics_for_acp'], NV_CURRENTTIME, 1, 1, '', '', '', 'online, statistics', '6', 5, 1, '', 0, 'fas fa-filter', 1]);
$sth->execute(['voting', 'Voting', 'voting', 'voting', 'Voting', $install_lang['modules']['voting'], $install_lang['modules']['voting_for_acp'], NV_CURRENTTIME, 1, 1, '', '', '', '', '6', 6, 1, '', 1, 'fas fa-lightbulb', 1]);
$sth->execute(['banners', 'Banners', 'banners', 'banners', 'Banners',$install_lang['modules']['banners'], $install_lang['modules']['banners_for_acp'], NV_CURRENTTIME, 1, 1, '', '', '', '', '6', 7, 1, '', 0, 'far fa-image', 1]);
$sth->execute(['seek', 'Seek', 'seek', 'seek', 'Seek', $install_lang['modules']['seek'], $install_lang['modules']['seek_for_acp'], NV_CURRENTTIME, 1, 0, '', '', '', '', '6', 8, 1, '', 0, 'fas fa-search', 1]);
$sth->execute(['menu', 'Menu', 'menu', 'menu', 'Menu', $install_lang['modules']['menu'], $install_lang['modules']['menu_for_acp'], NV_CURRENTTIME, 0, 1, '', '', '', '', '6', 9, 1, '', 0, 'fas fa-th-large', 1]);
$sth->execute(['feeds', 'Feeds', 'feeds', 'feeds', 'Feeds', $install_lang['modules']['feeds'], $install_lang['modules']['feeds'], NV_CURRENTTIME, 1, 1, '', '', '', '', '6', 10, 1, '', 0, 'fas fa-rss', 1]);
$sth->execute(['page', 'Page', 'page', 'page', 'Page', $install_lang['modules']['Page'], $install_lang['modules']['Page_for_acp'], NV_CURRENTTIME, 1, 1, '', '', '', '', '6', 11, 1, '', 1, 'fas fa-book', 0]);
$sth->execute(['comment', 'Comment', 'comment', 'comment', 'Comment', $install_lang['modules']['comment'], $install_lang['modules']['comment_for_acp'], NV_CURRENTTIME, 0, 1, '', '', '', '', '6', 12, 1, '', 0, 'fas fa-comments', 1]);
$sth->execute(['siteterms', 'Page', 'siteterms', 'siteterms', 'Page', $install_lang['modules']['siteterms'], $install_lang['modules']['siteterms_for_acp'], NV_CURRENTTIME, 1, 1, '', '', '', '', '6', 13, 1, '', 1, 'fas fa-asterisk', 1]);
$sth->execute(['freecontent', 'FreeContent', 'freecontent', 'freecontent', 'FreeContent', $install_lang['modules']['freecontent'], $install_lang['modules']['freecontent_for_acp'], NV_CURRENTTIME, 0, 1, '', '', '', '', '6', 14, 1, '', 0, 'far fa-square', 1]);
$sth->execute(['two-step-verification', 'TwoStepVerification', 'twostepverification', 'twostepverification', 'TwoStepVerification', $install_lang['modules']['two_step_verification'], $install_lang['modules']['two_step_verification_for_acp'], NV_CURRENTTIME, 1, 0, '', '', '', '', '6', 15, 1, '', 0, 'fas fa-key', 1]);

$db->query('TRUNCATE TABLE ' . $db_config['prefix'] . '_' . $lang_data . '_modfuncs');
$_array_insert = [];
//About
$_array_insert[] = "( 'main', 'main', 'Main', 'about', 1, 0, 1, '')";
$_array_insert[] = "( 'sitemap', 'sitemap', 'Sitemap', 'about', 0, 0, 0, '')";
$_array_insert[] = "( 'rss', 'rss', 'Rss', 'about', 0, 0, 0, '')";
//News
$_array_insert[] = "( 'main', 'main', 'Main', 'news', 1, 0, 1, '')";
$_array_insert[] = "( 'viewcat', 'viewcat', 'Viewcat', 'news', 1, 0, 2, '')";
$_array_insert[] = "( 'topic', 'topic', 'Topic', 'news', 1, 0, 3, '')";
$_array_insert[] = "( 'content', 'content', 'Content', 'news', 1, 1, 4, '')";
$_array_insert[] = "( 'detail', 'detail', 'Detail', 'news', 1, 0, 5, '')";
$_array_insert[] = "( 'tag', 'tag', 'Tag', 'news', 1, 0, 6, '')";
$_array_insert[] = "( 'rss', 'rss', 'Rss', 'news', 1, 1, 7, '')";
$_array_insert[] = "( 'search', 'search', 'Search', 'news', 1, 1, 8, '')";
$_array_insert[] = "( 'groups', 'groups', 'Groups', 'news', 1, 0, 9, '')";
$_array_insert[] = "( 'sitemap', 'sitemap', 'Sitemap', 'news', 0, 0, 0, '')";
$_array_insert[] = "( 'print', 'print', 'Print', 'news', 0, 0, 0, '')";
$_array_insert[] = "( 'rating', 'rating', 'Rating', 'news', 0, 0, 0, '')";
$_array_insert[] = "( 'savefile', 'savefile', 'Savefile', 'news', 0, 0, 0, '')";
$_array_insert[] = "( 'sendmail', 'sendmail', 'Sendmail', 'news', 0, 0, 0, '')";
$_array_insert[] = "( 'instant-rss', 'instant-rss', 'Instant Articles RSS', 'news', 0, 0, 0, '')";

//Users
$_array_insert[] = "( 'main', 'main', 'Main', 'users', 1, 0, 1, '')";
$_array_insert[] = "( 'login', 'login', '" . $install_lang['modfuncs']['users']['login'] . "', 'users', 1, 1, 2, '')";
$_array_insert[] = "( 'register', 'register', '" . $install_lang['modfuncs']['users']['register'] . "', 'users', 1, 1, 3, '')";
$_array_insert[] = "( 'lostpass', 'lostpass', '" . $install_lang['modfuncs']['users']['lostpass'] . "', 'users', 1, 1, 4, '')";
$_array_insert[] = "( 'active', 'active', '" . $install_lang['modfuncs']['users']['active'] . "', 'users', 1, 0, 5, '')";
$_array_insert[] = "( 'lostactivelink', 'lostactivelink', 'Lostactivelink', 'users', 1, 0, 6, '')";
$_array_insert[] = "( 'editinfo', 'editinfo', '" . $install_lang['modfuncs']['users']['editinfo'] . "', 'users', 1, 1, 7, '')";
$_array_insert[] = "( 'memberlist', 'memberlist', '" . $install_lang['modfuncs']['users']['memberlist'] . "', 'users', 1, 1, 8, '')";
$_array_insert[] = "( 'avatar', 'avatar', 'Avatar', 'users', 1, 0, 9, '')";
$_array_insert[] = "( 'logout', 'logout', '" . $install_lang['modfuncs']['users']['logout'] . "', 'users', 1, 1, 10, '')";
$_array_insert[] = "( 'groups', 'groups', '" . $install_lang['modfuncs']['users']['groups'] . "', 'users', 1, 0, 11, '')";
$_array_insert[] = "( 'oauth', 'oauth', 'Oauth', 'users', 0, 0, 0, '')";
//Statistics
$_array_insert[] = "( 'main', 'main', 'Main', 'statistics', 1, 0, 1, '')";
$_array_insert[] = "( 'allreferers', 'allreferers', '" . $install_lang['modfuncs']['statistics']['allreferers'] . "', 'statistics', 1, 1, 2, '')";
$_array_insert[] = "( 'allcountries', 'allcountries', '" . $install_lang['modfuncs']['statistics']['allcountries'] . "', 'statistics', 1, 1, 3, '')";
$_array_insert[] = "( 'allbrowsers', 'allbrowsers', '" . $install_lang['modfuncs']['statistics']['allbrowsers'] . "', 'statistics', 1, 1, 4, '')";
$_array_insert[] = "( 'allos', 'allos', '" . $install_lang['modfuncs']['statistics']['allos'] . "', 'statistics', 1, 1, 5, '')";
$_array_insert[] = "( 'allbots', 'allbots', '" . $install_lang['modfuncs']['statistics']['allbots'] . "', 'statistics', 1, 1, 6, '')";
$_array_insert[] = "( 'referer', 'referer', '" . $install_lang['modfuncs']['statistics']['referer'] . "', 'statistics', 1, 0, 7, '')";
//Banners
$_array_insert[] = "( 'main', 'main', 'Main', 'banners', 1, 0, 1, '')";
$_array_insert[] = "( 'addads', 'addads', 'Addads', 'banners', 1, 0, 2, '')";
$_array_insert[] = "( 'clientinfo', 'clientinfo', 'Clientinfo', 'banners', 1, 0, 3, '')";
$_array_insert[] = "( 'stats', 'stats', 'Stats', 'banners', 1, 0, 4, '')";
$_array_insert[] = "( 'cledit', 'cledit', 'Cledit', 'banners', 0, 0, 0, '')";
$_array_insert[] = "( 'click', 'click', 'Click', 'banners', 0, 0, 0, '')";
$_array_insert[] = "( 'clinfo', 'clinfo', 'Clinfo', 'banners', 0, 0, 0, '')";
$_array_insert[] = "( 'logininfo', 'logininfo', 'Logininfo', 'banners', 0, 0, 0, '')";
$_array_insert[] = "( 'viewmap', 'viewmap', 'Viewmap', 'banners', 0, 0, 0, '')";
//Comment
$_array_insert[] = "( 'main', 'main', 'main', 'comment', 1, 0, 1, '')";
$_array_insert[] = "( 'post', 'post', 'post', 'comment', 1, 0, 2, '')";
$_array_insert[] = "( 'like', 'like', 'Like', 'comment', 1, 0, 3, '')";
$_array_insert[] = "( 'delete', 'delete', 'Delete', 'comment', 1, 0, 4, '')";
$_array_insert[] = "( 'down', 'down', 'Down', 'comment', 1, 0, 5, '')";
//Page
$_array_insert[] = "( 'main', 'main', 'Main', 'page', 1, 0, 1, '')";
$_array_insert[] = "( 'sitemap', 'sitemap', 'Sitemap', 'page', 0, 0, 0, '')";
$_array_insert[] = "( 'rss', 'rss', 'Rss', 'page', 0, 0, 0, '')";
//Siteterms
$_array_insert[] = "( 'main', 'main', 'Main', 'siteterms', 1, 0, 1, '')";
$_array_insert[] = "( 'rss', 'rss', 'Rss', 'siteterms', 1, 0, 2, '')";
$_array_insert[] = "( 'sitemap', 'sitemap', 'Sitemap', 'siteterms', 0, 0, 0, '')";
//Two-Step Verification
$_array_insert[] = "( 'main', 'main', 'Main', 'two-step-verification', 1, 0, 1, '')";
$_array_insert[] = "( 'confirm', 'confirm', 'Confirm', 'two-step-verification', 1, 0, 2, '')";
$_array_insert[] = "( 'setup', 'setup', 'Setup', 'two-step-verification', 1, 0, 3, '')";
//Others
$_array_insert[] = "( 'main', 'main', 'Main', 'contact', 1, 0, 1, '')";
$_array_insert[] = "( 'main', 'main', 'Main', 'voting', 1, 0, 1, '')";
$_array_insert[] = "( 'main', 'main', 'Main', 'seek', 1, 0, 1, '')";
$_array_insert[] = "( 'main', 'main', 'Main', 'feeds', 1, 0, 1, '')";

$db->query('INSERT INTO ' . $db_config['prefix'] . '_' . $lang_data . '_modfuncs (func_name, alias, func_custom_name, in_module, show_func, in_submenu, subweight, setting) VALUES '.implode(',', $_array_insert));

$array_funcid = [];
$array_funcid_mod = [];

$func_result = $db->query('SELECT func_id, func_name, in_module FROM ' . $db_config['prefix'] . '_' . $lang_data . '_modfuncs WHERE show_func = 1 ORDER BY in_module ASC, subweight ASC');
while (list($func_id_i, $func_name, $in_module) = $func_result->fetch(3)) {
    $array_funcid[] = $func_id_i;
    if (!isset($array_funcid_mod[$in_module])) {
        $array_funcid_mod[$in_module] = [];
    }
    $array_funcid_mod[$in_module][$func_name] = $func_id_i;
}

$themes_default = [];
$themes_default['left-main-right'] = array(
    $array_funcid_mod['about']['main'],
    $array_funcid_mod['news']['content'],
    $array_funcid_mod['news']['detail'],
    $array_funcid_mod['news']['main'],
    $array_funcid_mod['news']['rss'],
    $array_funcid_mod['news']['search'],
    $array_funcid_mod['news']['topic'],
    $array_funcid_mod['news']['viewcat'],
    $array_funcid_mod['banners']['addads'],
    $array_funcid_mod['banners']['clientinfo'],
    $array_funcid_mod['banners']['main'],
    $array_funcid_mod['banners']['stats'],
    $array_funcid_mod['seek']['main'],
    $array_funcid_mod['feeds']['main'],
    $array_funcid_mod['news']['groups'],
    $array_funcid_mod['news']['tag'],
    $array_funcid_mod['users']['active'],
    $array_funcid_mod['users']['login'],
    $array_funcid_mod['users']['logout'],
    $array_funcid_mod['users']['lostactivelink'],
    $array_funcid_mod['users']['lostpass'],
    $array_funcid_mod['users']['main'],
    $array_funcid_mod['users']['register'],
    $array_funcid_mod['users']['memberlist'],
    $array_funcid_mod['users']['avatar'],
    $array_funcid_mod['comment']['main'],
    $array_funcid_mod['comment']['post'],
    $array_funcid_mod['comment']['like'],
    $array_funcid_mod['comment']['delete'],
    $array_funcid_mod['siteterms']['main'],
    $array_funcid_mod['siteterms']['rss'],
    $array_funcid_mod['two-step-verification']['main'],
    $array_funcid_mod['two-step-verification']['setup'],
    $array_funcid_mod['two-step-verification']['confirm']
    );

$themes_default['left-main'] = array(
    $array_funcid_mod['users']['editinfo'],
    $array_funcid_mod['users']['groups'],
    $array_funcid_mod['contact']['main'],
    $array_funcid_mod['statistics']['allbots'],
    $array_funcid_mod['statistics']['allbrowsers'],
    $array_funcid_mod['statistics']['allcountries'],
    $array_funcid_mod['statistics']['allos'],
    $array_funcid_mod['statistics']['allreferers'],
    $array_funcid_mod['statistics']['main'],
    $array_funcid_mod['statistics']['referer'],
    $array_funcid_mod['voting']['main'],
    $array_funcid_mod['page']['main'] );

$themes_mobile = [];
$themes_mobile['main'] = array(
    $array_funcid_mod['about']['main'],
    $array_funcid_mod['news']['content'],
    $array_funcid_mod['news']['detail'],
    $array_funcid_mod['news']['main'],
    $array_funcid_mod['news']['search'],
    $array_funcid_mod['news']['topic'],
    $array_funcid_mod['news']['viewcat'],
    $array_funcid_mod['users']['active'],
    $array_funcid_mod['users']['editinfo'],
    $array_funcid_mod['users']['login'],
    $array_funcid_mod['users']['logout'],
    $array_funcid_mod['users']['lostactivelink'],
    $array_funcid_mod['users']['lostpass'],
    $array_funcid_mod['users']['main'],
    $array_funcid_mod['users']['register'],
    $array_funcid_mod['users']['groups'],
    $array_funcid_mod['contact']['main'],
    $array_funcid_mod['statistics']['allbots'],
    $array_funcid_mod['statistics']['allbrowsers'],
    $array_funcid_mod['statistics']['allcountries'],
    $array_funcid_mod['statistics']['allos'],
    $array_funcid_mod['statistics']['allreferers'],
    $array_funcid_mod['statistics']['main'],
    $array_funcid_mod['statistics']['referer'],
    $array_funcid_mod['voting']['main'],
    $array_funcid_mod['banners']['addads'],
    $array_funcid_mod['banners']['clientinfo'],
    $array_funcid_mod['banners']['main'],
    $array_funcid_mod['banners']['stats'],
    $array_funcid_mod['seek']['main'],
    $array_funcid_mod['feeds']['main'],
    $array_funcid_mod['users']['memberlist'],
    $array_funcid_mod['news']['groups'],
    $array_funcid_mod['news']['tag'],
    $array_funcid_mod['page']['main'],
    $array_funcid_mod['comment']['main'],
    $array_funcid_mod['comment']['post'],
    $array_funcid_mod['comment']['like'],
    $array_funcid_mod['comment']['delete'],
    $array_funcid_mod['siteterms']['main'],
    $array_funcid_mod['siteterms']['rss'],
    $array_funcid_mod['two-step-verification']['main'],
    $array_funcid_mod['two-step-verification']['setup'],
    $array_funcid_mod['two-step-verification']['confirm']
);

$db->query('TRUNCATE TABLE ' . $db_config['prefix'] . '_' . $lang_data . '_modthemes');
$_array_insert = [];
$_array_insert[] = "(0, 'left-main-right', 'default')";
$_array_insert[] = "(0, 'main', 'mobile_default')";

foreach ($array_funcid as $funcid) {
    foreach ($themes_default as $_key => $_vals) {
        if (in_array($funcid, $_vals)) {
            $_array_insert[] = "(" . $funcid . ", '" . $_key . "', 'default')";
        }
    }

    foreach ($themes_mobile as $_key => $_vals) {
        if (in_array($funcid, $_vals)) {
            $_array_insert[] = "(" . $funcid . ", '" . $_key . "', 'mobile_default')";
        }
    }
}
$db->query('INSERT INTO ' . $db_config['prefix'] . '_' . $lang_data . '_modthemes (func_id, layout, theme) VALUES ' . implode(', ', $_array_insert));

$company = [];
$company['company_name'] = $install_lang['vinades_fullname'];
$company['company_address'] = $install_lang['vinades_address'];
$company['company_sortname'] = "VINADES.,JSC";
$company['company_regcode'] = "";
$company['company_regplace'] = "";
$company['company_licensenumber'] = "";
$company['company_responsibility'] = "";
$company['company_showmap'] = 1;
$company['company_mapurl'] = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2634.116366996857!2d105.79399620326203!3d20.9844946314258!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ac93055e2f2f%3A0x91f4b423089193dd!2zQ8O0bmcgdHkgQ-G7lSBwaOG6p24gUGjDoXQgdHJp4buDbiBOZ3Xhu5NuIG3hu58gVmnhu4d0IE5hbQ!5e0!3m2!1svi!2s!4v1558315703646!5m2!1svi!2s';
$company['company_phone'] = "+84-24-85872007[+842485872007]|+84-904762534[+84904762534]";
$company['company_fax'] = "+84-24-35500914";
$company['company_email'] = "contact@vinades.vn";
$company['company_website'] = "http://vinades.vn";
$company = serialize($company);

$copyright = 'a:5:{s:12:"copyright_by";s:0:"";s:13:"copyright_url";s:0:"";s:9:"design_by";s:12:"VINADES.,JSC";s:10:"design_url";s:18:"http://vinades.vn/";s:13:"siteterms_url";s:'. (38 + strlen(NV_BASE_SITEURL)).':"' . NV_BASE_SITEURL . 'index.php?language=' . $lang_data . '&amp;nv=siteterms";}';
$social = 'a:3:{s:8:"facebook";s:32:"http://www.facebook.com/nukeviet";s:7:"youtube";s:37:"https://www.youtube.com/user/nukeviet";s:7:"twitter";s:28:"https://twitter.com/nukeviet";}';

$db->query('TRUNCATE TABLE ' . $db_config['prefix'] . '_' . $lang_data . '_blocks_groups');
$sth = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_' . $lang_data . '_blocks_groups (theme, module, file_name, title, link, template, position, exp_time, active, groups_view, all_func, weight, config) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');

//Theme Default
$sth->execute(array('default', 'news', 'module.block_newscenter.php', $install_lang['blocks_groups']['news']['module.block_newscenter'], '', 'no_title', '[TOP]', 0, '1', '6', 0, 1, 'a:10:{s:6:"numrow";i:6;s:11:"showtooltip";i:1;s:16:"tooltip_position";s:6:"bottom";s:14:"tooltip_length";s:3:"150";s:12:"length_title";i:0;s:15:"length_hometext";i:0;s:17:"length_othertitle";i:60;s:5:"width";i:500;s:6:"height";i:0;s:7:"nocatid";a:0:{}}'));
$sth->execute(array('default', 'banners', 'global.banners.php', $install_lang['blocks_groups']['banners']['global.banners1'], '', 'no_title', '[TOP]', 0, '1', '6', 0, 2, 'a:1:{s:12:"idplanbanner";i:1;}'));

$sth->execute(array('default', 'news', 'global.block_category.php', $install_lang['blocks_groups']['news']['global.block_category'], '', 'no_title', '[LEFT]', 0, '1', '6', 0, 1, 'a:2:{s:5:"catid";i:0;s:12:"title_length";i:0;}'));
$sth->execute(array('default', 'theme', 'global.module_menu.php', 'Module Menu', '', 'no_title', '[LEFT]', 0, '1', '6', 0, 2, ''));
$sth->execute(array('default', 'banners', 'global.banners.php', $install_lang['blocks_groups']['banners']['global.banners2'], '', 'no_title', '[LEFT]', 0, '1', '6', 1, 3, 'a:1:{s:12:"idplanbanner";i:2;}'));
$sth->execute(array('default', 'statistics', 'global.counter.php', $install_lang['blocks_groups']['statistics']['global.counter'], '', 'primary', '[LEFT]', 0, '1', '6', 1, 4, ''));

$sth->execute(array('default', 'about', 'global.about.php', $install_lang['blocks_groups']['about']['global.about'], '', 'border', '[RIGHT]', 0, '1', '6', 1, 1, 'a:4:{s:12:"title_length";i:0;s:15:"bodytext_length";i:300;s:5:"rowid";i:0;s:10:"show_image";i:0;}'));
$sth->execute(array('default', 'banners', 'global.banners.php', $install_lang['blocks_groups']['banners']['global.banners3'], '', 'no_title', '[RIGHT]', 0, '1', '6', 1, 2, 'a:1:{s:12:"idplanbanner";i:3;}'));
$sth->execute(array('default', 'voting', 'global.voting_random.php', $install_lang['blocks_groups']['voting']['global.voting_random'], '', 'primary', '[RIGHT]', 0, '1', '6', 1, 3, ''));
$sth->execute(array('default', 'news', 'global.block_tophits.php', $install_lang['blocks_groups']['news']['global.block_tophits'], '', 'primary', '[RIGHT]', 0, '1', '6', 1, 4, 'a:6:{s:10:"number_day";i:3650;s:6:"numrow";i:10;s:11:"showtooltip";i:1;s:16:"tooltip_position";s:6:"bottom";s:14:"tooltip_length";s:3:"150";s:7:"nocatid";a:2:{i:0;i:10;i:1;i:11;}}'));

$sth->execute(array('default', 'theme', 'global.copyright.php', 'Copyright', '', 'no_title', '[FOOTER_SITE]', 0, '1', '6', 1, 1, $copyright));
$sth->execute(array('default', 'contact', 'global.contact_form.php', 'Feedback', '', 'no_title', '[FOOTER_SITE]', 0, '1', '6', 1, 2, ''));

$sth->execute(array('default', 'theme', 'global.QR_code.php', 'QR code', '', 'no_title', '[QR_CODE]', 0, '1', '6', 1, 1, 'a:3:{s:5:"level";s:1:"M";s:15:"pixel_per_point";i:4;s:11:"outer_frame";i:1;}'));
$sth->execute(array('default', 'statistics', 'global.counter_button.php', 'Online button', '', 'no_title', '[QR_CODE]', 0, '1', '6', 1, 2, ''));

$sth->execute(array('default', 'users', 'global.user_button.php', $install_lang['blocks_groups']['users']['global.user_button'], '', 'no_title', '[PERSONALAREA]', 0, '1', '6', 1, 1, ''));

$sth->execute(array('default', 'theme', 'global.company_info.php', $install_lang['blocks_groups']['theme']['global.company_info'], '', 'simple', '[COMPANY_INFO]', 0, '1', '6', 1, 1, $company));
$sth->execute(array('default', 'menu', 'global.bootstrap.php', 'Menu Site', '', 'no_title', '[MENU_SITE]', 0, '1', '6', 1, 1, 'a:2:{s:6:"menuid";i:1;s:12:"title_length";i:0;}'));
$sth->execute(array('default', 'contact', 'global.contact_default.php', 'Contact Default', '', 'no_title', '[CONTACT_DEFAULT]', 0, '1', '6', 1, 1, ''));
$sth->execute(array('default', 'theme', 'global.social.php', 'Social icon', '', 'no_title', '[SOCIAL_ICONS]', 0, '1', '6', 1, 1, $social));
$sth->execute(array('default', 'theme', 'global.menu_footer.php', $install_lang['blocks_groups']['theme']['global.menu_footer'], '', 'simple', '[MENU_FOOTER]', 0, '1', '6', 1, 1, 'a:1:{s:14:"module_in_menu";a:8:{i:0;s:5:"about";i:1;s:4:"news";i:2;s:5:"users";i:3;s:7:"contact";i:4;s:6:"voting";i:5;s:7:"banners";i:6;s:4:"seek";i:7;s:5:"feeds";}}'));
$sth->execute(array('default', 'freecontent', 'global.free_content.php', $install_lang['blocks_groups']['freecontent']['global.free_content'], '', 'no_title', '[FEATURED_PRODUCT]', 0, '1', '6', 1, 1, 'a:2:{s:7:"blockid";i:1;s:7:"numrows";i:2;}'));

//Theme Mobile
$sth->execute(array('mobile_default', 'menu', 'global.metismenu.php', 'Menu Site', '', 'no_title', '[MENU_SITE]', 0, '1', '6', 1, 1, 'a:2:{s:6:"menuid";i:1;s:12:"title_length";i:0;}'));
$sth->execute(array('mobile_default', 'users', 'global.user_button.php', 'Sign In', '', 'no_title', '[MENU_SITE]', 0, '1', '6', 1, 2, ''));

$sth->execute(array('mobile_default', 'contact', 'global.contact_default.php', 'Contact Default', '', 'no_title', '[SOCIAL_ICONS]', 0, '1', '6', 1, 1, ''));
$sth->execute(array('mobile_default', 'contact', 'global.contact_form.php', 'Feedback', '', 'no_title', '[SOCIAL_ICONS]', 0, '1', '6', 1, 2, ''));
$sth->execute(array('mobile_default', 'theme', 'global.social.php', 'Social icon', '', 'no_title', '[SOCIAL_ICONS]', 0, '1', '6', 1, 3, $social));
$sth->execute(array('mobile_default', 'theme', 'global.QR_code.php', 'QR code', '', 'no_title', '[SOCIAL_ICONS]', 0, '1', '6', 1, 4, 'a:3:{s:5:"level";s:1:"L";s:15:"pixel_per_point";i:4;s:11:"outer_frame";i:1;}'));

$sth->execute(array('mobile_default', 'theme', 'global.copyright.php', 'Copyright', '', 'no_title', '[FOOTER_SITE]', 0, '1', '6', 1, 1, $copyright));
$sth->execute(array('mobile_default', 'theme', 'global.menu_footer.php', $install_lang['blocks_groups']['theme']['global.menu_footer'], '', 'primary', '[MENU_FOOTER]', 0, '1', '6', 1, 1, 'a:1:{s:14:"module_in_menu";a:9:{i:0;s:5:"about";i:1;s:4:"news";i:2;s:5:"users";i:3;s:7:"contact";i:4;s:6:"voting";i:5;s:7:"banners";i:6;s:4:"seek";i:7;s:5:"feeds";i:8;s:9:"siteterms";}}'));
$sth->execute(array('mobile_default', 'theme', 'global.company_info.php', $install_lang['blocks_groups']['theme']['global.company_info'], '', 'primary', '[COMPANY_INFO]', 0, '1', '6', 1, 1, $company));

// Thiet lap Block
$db->query('TRUNCATE TABLE ' . $db_config['prefix'] . '_' . $lang_data . '_blocks_weight');
$_array_insert = [];
$func_result = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $lang_data . '_blocks_groups ORDER BY theme ASC, position ASC, weight ASC');
$array_weight_block = [];
while ($row = $func_result->fetch()) {
    if ($row['all_func'] == 1) {
        $array_funcid_i = $array_funcid;
    } else {
        $array_funcid_i = isset($array_funcid_mod[$row['module']]) ? $array_funcid_mod[$row['module']] : [];

        $xml = simplexml_load_file(NV_ROOTDIR . '/themes/' . $row['theme'] . '/config.ini');
        $blocks = $xml->xpath('setblocks/block');
        for ($i = 0, $count = sizeof($blocks); $i < $count; ++$i) {
            $rowini = ( array )$blocks[$i];
            if (isset($rowini['funcs']) and $rowini['module'] == $row['module'] and $rowini['file_name'] == $row['file_name']) {
                $array_funcid_i = [];
                if (!is_array($rowini['funcs'])) {
                    $rowini['funcs'] = array( $rowini['funcs'] );
                }
                foreach ($rowini['funcs'] as $_funcs_list) {
                    list($mod, $func_list) = explode(':', $_funcs_list);
                    $func_array = explode(',', $func_list);
                    foreach ($func_array as $_func) {
                        if (isset($array_funcid_mod[$mod][$_func])) {
                            $array_funcid_i[] = $array_funcid_mod[$mod][$_func];
                        }
                    }
                }
            }
        }
    }

    foreach ($array_funcid_i as $func_id) {
        if (isset($array_weight_block[$row['theme']][$row['position']][$func_id])) {
            $weight = $array_weight_block[$row['theme']][$row['position']][$func_id] + 1;
        } else {
            $weight = 1;
        }
        $array_weight_block[$row['theme']][$row['position']][$func_id] = $weight;
        $_array_insert[] = '(' . $row['bid'] . ', ' . $func_id . ', ' . $weight . ')';
    }
}
$db->query('INSERT INTO ' . $db_config['prefix'] . '_' . $lang_data . '_blocks_weight (bid, func_id, weight) VALUES '.implode(', ', $_array_insert));

$db->query("UPDATE " . $db_config['prefix'] . "_config SET config_value = " . $db->quote($install_lang['nukeviet_description']) . " WHERE module = 'global' AND config_name = 'site_description' AND lang='" . $lang_data . "'");
$db->query("UPDATE " . $db_config['prefix'] . "_config SET config_value = " . $db->quote($install_lang['disable_site_content']) . " WHERE module = 'global' AND config_name = 'disable_site_content' AND lang='" . $lang_data . "'");

$result = $db->query('SELECT id, run_func FROM ' . $db_config['prefix'] . '_cronjobs ORDER BY id ASC');
while (list($id, $run_func) = $result->fetch(3)) {
    $cron_name = (isset($install_lang['cron'][$run_func])) ? $install_lang['cron'][$run_func] : $run_func;
    $db->query('UPDATE ' . $db_config['prefix'] . '_cronjobs SET ' . $lang_data . '_cron_name = ' . $db->quote($cron_name) . ' WHERE id=' . $id);
}

$db->query("UPDATE " . $db_config['prefix'] . "_config SET config_value = '" . $global_config['site_theme'] . "' WHERE lang = '" . $lang_data . "' AND module = 'global' AND config_name = 'site_theme'");

$result = $db->query("SELECT COUNT(*) FROM " . $db_config['prefix'] . "_" . $lang_data . "_modules where title='" . $global_config['site_home_module'] . "'");
if ($result->fetchColumn()) {
    $db->query("UPDATE " . $db_config['prefix'] . "_config SET config_value = '" . $global_config['site_home_module'] . "' WHERE module = 'global' AND config_name = 'site_home_module' AND lang='" . $lang_data . "'");
}

if (!empty($menu_rows_lev0)) {
    $result = $db->query("INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_menu (id, title) VALUES (1, 'Top Menu')");

    $is_yes_sub = !empty($menu_rows_lev1) ? array_unique(array_keys($menu_rows_lev1)) : [];
    $menu_y_sub = [];
    if (!empty($is_yes_sub)) {
        foreach ($is_yes_sub as $mys) {
            $menu_y_sub[$mys] = [];
            $menu_y_sub[$mys]['subsize'] = sizeof($menu_rows_lev1[$mys]);
        }
    }

    $sth = $db->prepare("INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_menu_rows (id, parentid, mid, title, link, weight, sort, lev, subitem, groups_view, module_name, op, target, status) VALUES (?, ?, 1, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, 1)");

    $a = 1;
    $b = 1;
    $d = sizeof($menu_rows_lev0);
    $executes = [];
    $subitem = [];
    foreach ($menu_rows_lev0 as $m => $item) {
        $executes[$a] = array(
            $a,
            0,
            $item['title'],
            $item['link'],
            $a,
            $b,
            0,
            '',
            $item['groups_view'],
            $m,
            $item['op']);
        $subitem[$a] = [];
        if (isset($menu_y_sub[$m])) {
            for ($c = 1; $c <= $menu_y_sub[$m]['subsize']; ++$c) {
                ++$b;
                ++$d;
                $e = $c - 1;
                $executes[$d] = array(
                    $d,
                    $a,
                    $menu_rows_lev1[$m][$e]['title'],
                    $menu_rows_lev1[$m][$e]['link'],
                    $c,
                    $b,
                    1,
                    '',
                    $menu_rows_lev1[$m][$e]['groups_view'],
                    $m,
                    $menu_rows_lev1[$m][$e]['op']);
                $subitem[$a][] = $d;
            }
        }
        ++$a;
        ++$b;
    }

    ksort($executes);
    foreach ($executes as $id => $execute) {
        if (!empty($subitem[$id]))
            $execute[7] = implode(",", $subitem[$id]);
        $sth->execute($execute);
    }
}

// Các plugin của module theo ngôn ngữ
$db->query("INSERT INTO " . $db_config['prefix'] . "_plugin (plugin_lang, plugin_file, plugin_area, plugin_module_name, plugin_module_file, weight) VALUES ('" . $lang_data . "', 'del_menu_row.php', 'after_module_deleted', 'menu', 'Menu', 1)");

// Email templates
if (!empty($module_data) and $module_data == 'language') {
    // Cập nhật tên danh mục của hệ thống
    foreach ($install_lang['emailtemplates']['cats'] as $_catid => $_cattitle) {
        try {
            $db->query("UPDATE " . $db_config['prefix'] . "_emailtemplates_categories SET " . $lang_data . "_title=" . $db->quote($_cattitle) . " WHERE catid=" . $_catid);
        } catch (PDOException $e) {}
    }

    // Cập nhật tên, tiêu đề và nội dung các mẫu email của hệ thống
    foreach ($install_lang['emailtemplates']['emails'] as $_tplid => $_tpldata) {
        $db->query("UPDATE " . $db_config['prefix'] . "_emailtemplates SET " . $lang_data . "_subject=" . $db->quote($_tpldata['s']) . ", " . $lang_data . "_content=" . $db->quote($_tpldata['c']) . " WHERE emailid=" . $_tplid);
        try {
            $db->query("UPDATE " . $db_config['prefix'] . "_emailtemplates SET " . $lang_data . "_title=" . $db->quote($_tpldata['t']) . " WHERE emailid=" . $_tplid);
        } catch (PDOException $e) {}
    }
} else {
    // Thêm mới danh mục
    $sql_emailtpl = [];
    $weight = 0;
    foreach ($install_lang['emailtemplates']['cats'] as $_catid => $_cattitle) {
        $weight++;
        $sql_emailtpl[] = "(" . $_catid . ", " . NV_CURRENTTIME . ", " . $weight . ", 1, " . $db->quote($_cattitle) . ")";
    }
    $db->query("INSERT INTO " . $db_config['prefix'] . "_emailtemplates_categories (
        catid, time_add, weight, is_system, " . $lang_data . "_title
    ) VALUES " . implode(", ", $sql_emailtpl));

    /*
     * Các mẫu email
     */
    $sql_emailtpl = [];
    foreach ($install_lang['emailtemplates']['emails'] as $_tplid => $_tpldata) {
        $sql_emailtpl[] = "(
            " . $_tplid . ", '" . $_tpldata['pids'] . "', " . $_tpldata['catid'] . ", " . NV_CURRENTTIME . ", '', '', '', 1,
            " . $db->quote($_tpldata['s']) . ", " . $db->quote($_tpldata['c']) . ", " . $db->quote($_tpldata['t']) . ", '', ''
        )";
    }
    $db->query("INSERT INTO " . $db_config['prefix'] . "_emailtemplates (
        emailid, sys_pids, catid, time_add, send_cc, send_bcc, attachments, is_system, default_subject, default_content,
        " . $lang_data . "_title, " . $lang_data . "_subject, " . $lang_data . "_content
    ) VALUES " . implode(", ", $sql_emailtpl));
}
