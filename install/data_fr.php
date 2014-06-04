<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$db->query( 'TRUNCATE TABLE ' . $db_config['prefix'] . '_' . $lang_data . '_modules' );
$sth = $db->prepare( 'INSERT INTO ' . $db_config['prefix'] . '_' . $lang_data . '_modules (title, module_file, module_data, custom_title, admin_title, set_time, main_file, admin_file, theme, mobile, description, keywords, groups_view, weight, act, admins, rss, gid) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)' );
$sth->execute( array('about', 'page', 'about', 'À propos', '', 1276333182, 1, 1, '', '', '', '', '6', 1, 1, '', 0, 0) );
$sth->execute( array('news', 'news', 'news', 'News', '', 1270400000, 1, 1, '', '', '', '', '6', 2, 1, '', 1, 0) );
$sth->execute( array('users', 'users', 'users', 'Compte d&#039;utilisateur', '', 1274080277, 1, 1, '', '', '', '', '6', 3, 1, '', 0, 0) );
$sth->execute( array('contact', 'contact', 'contact', 'Contact', '', 1275351337, 1, 1, '', '', '', '', '6', 4, 1, '', 0, 0) );
$sth->execute( array('statistics', 'statistics', 'statistics', 'Statistiques', '', 1276520928, 1, 0, '', '', '', 'online, statistics', '6', 5, 1, '', 0, 0) );
$sth->execute( array('voting', 'voting', 'voting', 'Sondage', '', 1275315261, 1, 1, '', '', '', '', '6', 6, 1, '', 1, 0) );
$sth->execute( array('banners', 'banners', 'banners', 'Publicité', '', 1270400000, 1, 1, '', '', '', '', '6', 7, 1, '', 0, 0) );
$sth->execute( array('seek', 'seek', 'seek', 'Recherche', '', 1273474173, 1, 0, '', '', '', '', '6', 8, 1, '', 0, 0) );
$sth->execute( array('menu', 'menu', 'menu', 'Menu Site', '', 1295287334, 0, 1, '', '', '', '', '6', 9, 1, '', 0, 0) );
$sth->execute( array('feeds', 'feeds', 'feeds', 'Rss Feeds', '', 1279366705, 1, 1, '', '', '', '', '6', 10, 1, '', 0, 0) );
$sth->execute( array('page', 'page', 'page', 'Page', '', 1279366705, 1, 1, '', '', '', '', '6', 11, 1, '', 0, 0) );
$sth->execute( array('comment', 'comment', 'comment', 'Comment', '', 1279366705, 1, 1, '', '', '', '', '6', 12, 1, '', 0, 0) );

$db->query( 'TRUNCATE TABLE ' . $db_config['prefix'] . '_' . $lang_data . '_modfuncs' );
$sth = $db->prepare( 'INSERT INTO ' . $db_config['prefix'] . '_' . $lang_data . '_modfuncs (func_id, func_name, alias, func_custom_name, in_module, show_func, in_submenu, subweight, setting) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)' );
$sth->execute( array(1, 'Sitemap', 'Sitemap', 'Sitemap', 'about', 0, 0, 0, '') );
$sth->execute( array(2, 'main', 'main', 'Main', 'about', 1, 0, 1, '') );
$sth->execute( array(3, 'Sitemap', 'Sitemap', 'Sitemap', 'news', 0, 0, 0, '') );
$sth->execute( array(5, 'content', 'content', 'Content', 'news', 1, 0, 1, '') );
$sth->execute( array(6, 'detail', 'detail', 'Detail', 'news', 1, 0, 2, '') );
$sth->execute( array(7, 'main', 'main', 'Main', 'news', 1, 0, 3, '') );
$sth->execute( array(9, 'print', 'print', 'Print', 'news', 0, 0, 0, '') );
$sth->execute( array(10, 'rating', 'rating', 'Rating', 'news', 0, 0, 0, '') );
$sth->execute( array(11, 'rss', 'rss', 'Rss', 'news', 0, 0, 0, '') );
$sth->execute( array(12, 'savefile', 'savefile', 'Savefile', 'news', 0, 0, 0, '') );
$sth->execute( array(13, 'search', 'search', 'Search', 'news', 1, 0, 4, '') );
$sth->execute( array(14, 'sendmail', 'sendmail', 'Sendmail', 'news', 0, 0, 0, '') );
$sth->execute( array(15, 'topic', 'topic', 'Topic', 'news', 1, 0, 5, '') );
$sth->execute( array(16, 'viewcat', 'viewcat', 'Viewcat', 'news', 1, 0, 6, '') );
$sth->execute( array(17, 'active', 'active', 'Active', 'users', 1, 0, 8, '') );
$sth->execute( array(18, 'changepass', 'changepass', 'Changer le mot de passe', 'users', 1, 1, 6, '') );
$sth->execute( array(19, 'editinfo', 'editinfo', 'Editinfo', 'users', 1, 0, 10, '') );
$sth->execute( array(20, 'login', 'login', 'Se connecter', 'users', 1, 1, 2, '') );
$sth->execute( array(21, 'logout', 'logout', 'Logout', 'users', 1, 1, 3, '') );
$sth->execute( array(22, 'lostactivelink', 'lostactivelink', 'Lostactivelink', 'users', 1, 0, 9, '') );
$sth->execute( array(23, 'lostpass', 'lostpass', 'Mot de passe oublié?', 'users', 1, 1, 5, '') );
$sth->execute( array(24, 'main', 'main', 'Main', 'users', 1, 0, 1, '') );
$sth->execute( array(25, 'openid', 'openid', 'Openid', 'users', 1, 1, 7, '') );
$sth->execute( array(26, 'register', 'register', 'S&#039;inscrire', 'users', 1, 1, 4, '') );
$sth->execute( array(27, 'main', 'main', 'Main', 'contact', 1, 0, 1, '') );
$sth->execute( array(28, 'allbots', 'allbots', 'Par Moteur de recherche', 'statistics', 1, 1, 6, '') );
$sth->execute( array(29, 'allbrowsers', 'allbrowsers', 'Par Navigateur', 'statistics', 1, 1, 4, '') );
$sth->execute( array(30, 'allcountries', 'allcountries', 'Par Pays', 'statistics', 1, 1, 3, '') );
$sth->execute( array(31, 'allos', 'allos', 'Par Système d&#039;exploitation', 'statistics', 1, 1, 5, '') );
$sth->execute( array(32, 'allreferers', 'allreferers', 'Par Site', 'statistics', 1, 1, 2, '') );
$sth->execute( array(33, 'main', 'main', 'Main', 'statistics', 1, 0, 1, '') );
$sth->execute( array(34, 'referer', 'referer', 'referer', 'statistics', 1, 0, 7, '') );
$sth->execute( array(35, 'main', 'main', 'Main', 'voting', 1, 0, 1, '') );
$sth->execute( array(36, 'addads', 'addads', 'Addads', 'banners', 1, 0, 1, '') );
$sth->execute( array(37, 'cledit', 'cledit', 'Cledit', 'banners', 0, 0, 0, '') );
$sth->execute( array(38, 'click', 'click', 'Click', 'banners', 0, 0, 0, '') );
$sth->execute( array(39, 'clientinfo', 'clientinfo', 'Clientinfo', 'banners', 1, 0, 2, '') );
$sth->execute( array(40, 'clinfo', 'clinfo', 'Clinfo', 'banners', 0, 0, 0, '') );
$sth->execute( array(41, 'logininfo', 'logininfo', 'Logininfo', 'banners', 0, 0, 0, '') );
$sth->execute( array(42, 'main', 'main', 'Main', 'banners', 1, 0, 3, '') );
$sth->execute( array(43, 'stats', 'stats', 'Stats', 'banners', 1, 0, 4, '') );
$sth->execute( array(44, 'viewmap', 'viewmap', 'Viewmap', 'banners', 0, 0, 0, '') );
$sth->execute( array(46, 'main', 'main', 'Main', 'seek', 1, 0, 1, '') );
$sth->execute( array(47, 'main', 'main', 'Main', 'feeds', 1, 0, 1, '') );
$sth->execute( array(48, 'regroups', 'regroups', 'Regroups', 'users', 1, 0, 11, '') );
$sth->execute( array(50, 'memberlist', 'memberlist', 'Liste des membres', 'users', 1, 1, 12, '') );
$sth->execute( array(51, 'groups', 'groups', 'Groups', 'news', 1, 0, 7, '') );
$sth->execute( array(52, 'tag', 'tag', 'Tag', 'news', 1, 0, 2, '') );
$sth->execute( array(53, 'main', 'main', 'Main', 'page', 1, 0, 1, '') );
$sth->execute( array(54, 'main', 'main', 'main', 'comment', 1, 0, 1, '') );
$sth->execute( array(55, 'post', 'post', 'post', 'comment', 1, 0, 2, '') );
$sth->execute( array(56, 'like', 'like', 'Like', 'comment', 1, 0, 3, '') );
$sth->execute( array(57, 'delete', 'delete', 'Delete', 'comment', 1, 0, 4, '') );
$sth->execute( array(58, 'avatar', 'avatar', 'Avatar', 'users', 1, 0, 13, '') );

$db->query( 'TRUNCATE TABLE ' . $db_config['prefix'] . '_' . $lang_data . '_modthemes' );
$sth = $db->prepare( 'INSERT INTO ' . $db_config['prefix'] . '_' . $lang_data . '_modthemes (func_id, layout, theme) VALUES  (?, ?, ?)' );
$sth->execute( array(0, 'body-right', 'modern') );
$sth->execute( array(0, 'left-body-right', 'default') );
$sth->execute( array(2, 'body', 'modern') );
$sth->execute( array(2, 'left-body-right', 'default') );
$sth->execute( array(5, 'body-right', 'modern') );
$sth->execute( array(5, 'left-body-right', 'default') );
$sth->execute( array(6, 'body-right', 'modern') );
$sth->execute( array(6, 'left-body-right', 'default') );
$sth->execute( array(7, 'body-right', 'modern') );
$sth->execute( array(7, 'left-body-right', 'default') );
$sth->execute( array(13, 'body-right', 'modern') );
$sth->execute( array(13, 'left-body-right', 'default') );
$sth->execute( array(15, 'body-right', 'modern') );
$sth->execute( array(15, 'left-body-right', 'default') );
$sth->execute( array(16, 'body-right', 'modern') );
$sth->execute( array(16, 'left-body-right', 'default') );
$sth->execute( array(17, 'body-right', 'modern') );
$sth->execute( array(17, 'left-body-right', 'default') );
$sth->execute( array(18, 'body-right', 'modern') );
$sth->execute( array(18, 'left-body-right', 'default') );
$sth->execute( array(19, 'body-right', 'modern') );
$sth->execute( array(19, 'left-body-right', 'default') );
$sth->execute( array(20, 'body-right', 'modern') );
$sth->execute( array(20, 'left-body-right', 'default') );
$sth->execute( array(21, 'body-right', 'modern') );
$sth->execute( array(21, 'left-body-right', 'default') );
$sth->execute( array(22, 'body-right', 'modern') );
$sth->execute( array(22, 'left-body-right', 'default') );
$sth->execute( array(23, 'body-right', 'modern') );
$sth->execute( array(23, 'left-body-right', 'default') );
$sth->execute( array(24, 'body-right', 'modern') );
$sth->execute( array(24, 'left-body-right', 'default') );
$sth->execute( array(25, 'body-right', 'modern') );
$sth->execute( array(25, 'left-body-right', 'default') );
$sth->execute( array(26, 'body-right', 'modern') );
$sth->execute( array(26, 'left-body-right', 'default') );
$sth->execute( array(27, 'body-right', 'modern') );
$sth->execute( array(27, 'left-body-right', 'default') );
$sth->execute( array(28, 'body', 'modern') );
$sth->execute( array(28, 'left-body', 'default') );
$sth->execute( array(29, 'body', 'modern') );
$sth->execute( array(29, 'left-body', 'default') );
$sth->execute( array(30, 'body', 'modern') );
$sth->execute( array(30, 'left-body', 'default') );
$sth->execute( array(31, 'body', 'modern') );
$sth->execute( array(31, 'left-body', 'default') );
$sth->execute( array(32, 'body', 'modern') );
$sth->execute( array(32, 'left-body', 'default') );
$sth->execute( array(33, 'body', 'modern') );
$sth->execute( array(33, 'left-body', 'default') );
$sth->execute( array(34, 'body', 'modern') );
$sth->execute( array(34, 'left-body', 'default') );
$sth->execute( array(36, 'body-right', 'modern') );
$sth->execute( array(36, 'left-body-right', 'default') );
$sth->execute( array(39, 'body-right', 'modern') );
$sth->execute( array(39, 'left-body-right', 'default') );
$sth->execute( array(42, 'body-right', 'modern') );
$sth->execute( array(42, 'left-body-right', 'default') );
$sth->execute( array(43, 'body-right', 'modern') );
$sth->execute( array(43, 'left-body-right', 'default') );
$sth->execute( array(46, 'body-right', 'modern') );
$sth->execute( array(46, 'left-body-right', 'default') );
$sth->execute( array(47, 'body', 'modern') );
$sth->execute( array(47, 'left-body-right', 'default') );
$sth->execute( array(48, 'body-right', 'modern') );
$sth->execute( array(48, 'left-body-right', 'default') );
$sth->execute( array(52, 'body-right', 'modern') );
$sth->execute( array(52, 'left-body-right', 'default') );
$sth->execute( array(53, 'body', 'modern') );
$sth->execute( array(53, 'body', 'default') );

$sth->execute( array(0, 'body', 'mobile_nukeviet') );
$sth->execute( array(2, 'body', 'mobile_nukeviet') );
$sth->execute( array(5, 'body', 'mobile_nukeviet') );
$sth->execute( array(6, 'body', 'mobile_nukeviet') );
$sth->execute( array(7, 'body', 'mobile_nukeviet') );
$sth->execute( array(13, 'body', 'mobile_nukeviet') );
$sth->execute( array(15, 'body', 'mobile_nukeviet') );
$sth->execute( array(16, 'body', 'mobile_nukeviet') );
$sth->execute( array(17, 'body', 'mobile_nukeviet') );
$sth->execute( array(18, 'body', 'mobile_nukeviet') );
$sth->execute( array(19, 'body', 'mobile_nukeviet') );
$sth->execute( array(20, 'body', 'mobile_nukeviet') );
$sth->execute( array(21, 'body', 'mobile_nukeviet') );
$sth->execute( array(22, 'body', 'mobile_nukeviet') );
$sth->execute( array(23, 'body', 'mobile_nukeviet') );
$sth->execute( array(24, 'body', 'mobile_nukeviet') );
$sth->execute( array(25, 'body', 'mobile_nukeviet') );
$sth->execute( array(26, 'body', 'mobile_nukeviet') );
$sth->execute( array(27, 'body', 'mobile_nukeviet') );
$sth->execute( array(28, 'body', 'mobile_nukeviet') );
$sth->execute( array(29, 'body', 'mobile_nukeviet') );
$sth->execute( array(30, 'body', 'mobile_nukeviet') );
$sth->execute( array(31, 'body', 'mobile_nukeviet') );
$sth->execute( array(32, 'body', 'mobile_nukeviet') );
$sth->execute( array(33, 'body', 'mobile_nukeviet') );
$sth->execute( array(34, 'body', 'mobile_nukeviet') );
$sth->execute( array(36, 'body', 'mobile_nukeviet') );
$sth->execute( array(39, 'body', 'mobile_nukeviet') );
$sth->execute( array(42, 'body', 'mobile_nukeviet') );
$sth->execute( array(43, 'body', 'mobile_nukeviet') );
$sth->execute( array(46, 'body', 'mobile_nukeviet') );
$sth->execute( array(47, 'body', 'mobile_nukeviet') );
$sth->execute( array(48, 'body', 'mobile_nukeviet') );

$sth->execute( array(35, 'body-right', 'modern') );
$sth->execute( array(35, 'left-body-right', 'default') );
$sth->execute( array(35, 'body', 'mobile_nukeviet') );

$sth->execute( array(50, 'body-right', 'modern') );
$sth->execute( array(50, 'left-body-right', 'default') );
$sth->execute( array(50, 'body', 'mobile_nukeviet') );

$db->query( 'TRUNCATE TABLE ' . $db_config['prefix'] . '_' . $lang_data . '_blocks_groups' );
$sth = $db->prepare( 'INSERT INTO ' . $db_config['prefix'] . '_' . $lang_data . '_blocks_groups (bid, theme, module, file_name, title, link, template, position, exp_time, active, groups_view, all_func, weight, config) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)' );
$sth->execute( array(1, 'default', 'news', 'global.block_category.php', 'Menu', '', '', '[LEFT]', 0, 1, '6', 0, 1, 'a:2:{s:5:"catid";i:0;s:12:"title_length";i:25;}') );
$sth->execute( array(2, 'default', 'statistics', 'global.counter.php', 'Statistiques', '', '', '[LEFT]', 0, 1, '6', 1, 2, '') );
$sth->execute( array(3, 'default', 'banners', 'global.banners.php', 'Publicité à côté', '', '', '[LEFT]', 0, 1, '6', 1, 3, 'a:1:{s:12:"idplanbanner";i:2;}') );
$sth->execute( array(4, 'default', 'about', 'global.about.php', 'À Propos', '', 'border', '[RIGHT]', 0, 1, '6', 1, 1, '') );
$sth->execute( array(5, 'default', 'users', 'global.voting_random.php', 'Identification', '', '', '[RIGHT]', 0, 1, '6', 1, 2, '') );
$sth->execute( array(6, 'default', 'voting', 'global.voting.php', 'Sondage', '', '', '[RIGHT]', 0, 1, '6', 1, 3, '') );
$sth->execute( array(7, 'default', 'news', 'module.block_headline.php', 'Hot news', '', 'no_title', '[TOP]', 0, 1, '6', 0, 1, 'a:3:{s:11:"showtooltip";i:1;s:16:"tooltip_position";s:6:"bottom";s:14:"tooltip_length";s:3:"150";}') );
$sth->execute( array(8, 'default', 'banners', 'global.banners.php', 'Publicité du centre', '', 'no_title', '[TOP]', 0, 1, '6', 1, 2, 'a:1:{s:12:"idplanbanner";i:1;}') );
$sth->execute( array(9, 'modern', 'news', 'module.block_newscenter.php', 'Nouvelles', '', 'no_title', '[HEADER]', 0, 1, '6', 0, 1, 'a:3:{s:11:"showtooltip";i:1;s:16:"tooltip_position";s:6:"bottom";s:14:"tooltip_length";s:3:"150";}') );
$sth->execute( array(10, 'modern', 'about', 'global.about.php', 'À Propos', '', 'no_title_html', '[RIGHT]', 0, 1, '6', 1, 1, '') );
$sth->execute( array(11, 'modern', 'users', 'global.login.php', 'Identification', '', '', '[RIGHT]', 0, 1, '6', 1, 2, '') );
$sth->execute( array(12, 'modern', 'voting', 'global.voting_random.php', 'Sondage', '', '', '[RIGHT]', 0, 1, '6', 1, 3, '') );
$sth->execute( array(13, 'modern', 'statistics', 'global.counter.php', 'Statistiques', '', '', '[RIGHT]', 0, 1, '6', 1, 4, '') );
$sth->execute( array(14, 'modern', 'news', 'module.block_newsright.php', 'News Right', '', 'no_title', '[RIGHT]', 0, 1, '6', 0, 5, '') );
$sth->execute( array(15, 'modern', 'banners', 'global.banners.php', 'Bannière Top', '', 'no_title', '[TOPADV]', 0, 1, '6', 1, 1, 'a:1:{s:12:"idplanbanner";i:1;}') );
$sth->execute( array(16, 'modern', 'theme', 'global.menu.php', 'global menu', '', 'no_title', '[MENU_SITE]', 0, 1, '6', 1, 1, '') );
$sth->execute( array(17, 'default', 'theme', 'global.menu.php', 'global menu', '', 'no_title', '[MENU_SITE]', 0, 1, '6', 1, 1, '') );
$sth->execute( array(18, 'modern', 'page', 'global.html.php', 'footer site', '', 'no_title', '[FOOTER_SITE]', 0, 1, '6', 1, 1, 'a:1:{s:11:"htmlcontent";s:207:"© Copyright NukeViet 4. All right reserved.<br  />Powered by <a href="http://nukeviet.vn/" title="NukeViet CMS">NukeViet CMS</a>. Design by <a href="http://vinades.vn/" title="VINADES.,JSC">VINADES.,JSC</a>";}') );
$sth->execute( array(19, 'default', 'page', 'global.html.php', 'footer site', '', 'no_title', '[FOOTER_SITE]', 0, 1, '6', 1, 1, 'a:1:{s:11:"htmlcontent";s:231:"<p class="footer"> © Copyright NukeViet 4. All right reserved.</p><p> Powered by <a href="http://nukeviet.vn/" title="NukeViet CMS">NukeViet CMS</a>. Design by <a href="http://vinades.vn/" title="VINADES.,JSC">VINADES.,JSC</a></p>";}') );
$sth->execute( array(20, 'mobile_nukeviet', 'menu', 'global.menu.php', 'global menu', '', 'no_title', '[MENU_SITE]', 0, 1, '6', 1, 1, '') );
$sth->execute( array(21, 'default', 'page', 'global.html.php', 'Social icon', '', 'no_title', '[SOCIAL_ICONS]', 0, 1, '6', 1, 1, 'a:1:{s:11:"htmlcontent";s:310:"<ul><li><a href="#"><i class="fa fa-facebook">&nbsp;</i></a></li><li><a href="#"><i class="fa fa-google-plus">&nbsp;</i></a></li><li><a href="#"><i class="fa fa-youtube">&nbsp;</i></a></li><li><a href="#"><i class="fa fa-twitter">&nbsp;</i></a></li><li><a href="#"><i class="fa fa-rss">&nbsp;</i></a></li></ul>";}') );

// Thiết lập Block
$db->query( 'TRUNCATE TABLE ' . $db_config['prefix'] . '_' . $lang_data . '_blocks_weight' );

$array_funcid = array();
$array_funcid_mod = array();
$array_weight_block = array();

$func_result = $db->query( 'SELECT func_id, in_module FROM ' . $db_config['prefix'] . '_' . $lang_data . '_modfuncs WHERE show_func = 1 ORDER BY in_module ASC, subweight ASC' );
while( list( $func_id_i, $in_module ) = $func_result->fetch( 3 ) )
{
	$array_funcid[] = $func_id_i;
	$array_funcid_mod[$in_module][] = $func_id_i;
}

$func_result = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $lang_data . '_blocks_groups ORDER BY theme ASC, position ASC, weight ASC' );
while( $row = $func_result->fetch() )
{
	$array_funcid_i = ( $row['all_func']==1 ) ? $array_funcid : $array_funcid_mod[$row['module']];
	foreach( $array_funcid_i as $func_id )
	{
		if( isset($array_weight_block[$row['theme']][$row['position']][$func_id]) )
		{
			$weight = $array_weight_block[$row['theme']][$row['position']][$func_id] + 1;
		}
		else
		{
			$weight = 1;
		}
		$array_weight_block[$row['theme']][$row['position']][$func_id] = $weight;

		$db->query( 'INSERT INTO ' . $db_config['prefix'] . '_' . $lang_data . '_blocks_weight (bid, func_id, weight) VALUES (' . $row['bid'] . ', ' . $func_id . ', ' . $weight . ')' );
	}
}

$disable_site_content = 'Notre site est fermé temporairement pour la maintenance. Veuillez revenir plus tard. Merci!';
$copyright = 'Veuillez citer le lien vers l&#039;article original si vous le reproduisez sur un autre site. Merci.';
$site_description = 'NUKEVIET CMS 3.0 Developé par Vinades.,Jsc';

$db->query( "UPDATE " . $db_config['prefix'] . "_config SET config_value = " . $db->quote( $site_description ) . " WHERE module = 'global' AND config_name = 'site_description' AND lang='fr'" );
$db->query( "UPDATE " . $db_config['prefix'] . "_config SET config_value = " . $db->quote( $disable_site_content ) . " WHERE module = 'global' AND config_name = 'disable_site_content' AND lang='fr'" );

$array_cron_name = array();
$array_cron_name['cron_online_expired_del'] = 'Supprimer les anciens registres du status en ligne dans la base de données';
$array_cron_name['cron_dump_autobackup'] = 'Sauvegarder automatique la base de données';
$array_cron_name['cron_auto_del_temp_download'] = 'Supprimer les fichiers temporaires du répertoire tmp';
$array_cron_name['cron_del_ip_logs'] = 'Supprimer les fichiers ip_logs expirés';
$array_cron_name['cron_auto_del_error_log'] = 'Supprimer les fichiers error_log expirés';
$array_cron_name['cron_auto_sendmail_error_log'] = 'Envoyer à l\'administrateur l\'e-mail des notifications d\'erreurs';
$array_cron_name['cron_ref_expired_del'] = 'Supprimer les referers expirés';
$array_cron_name['cron_siteDiagnostic_update'] = 'Mise à jour du site de diagnostic';
$array_cron_name['cron_auto_check_version'] = 'Vérifier la version NukeViet';

$result = $db->query( 'SELECT id, run_func FROM ' . $db_config['prefix'] . '_cronjobs ORDER BY id ASC' );
while( list( $id, $run_func ) = $result->fetch( 3 ) )
{
	$cron_name = ( isset( $array_cron_name[$run_func] ) ) ? $array_cron_name[$run_func] : $run_func;
	$db->query( "UPDATE " . $db_config['prefix'] . "_cronjobs SET " . $lang_data . "_cron_name = " . $db->quote( $cron_name ) . " WHERE id=" . $id );
}

$db->query( "UPDATE " . $db_config['prefix'] . "_config SET config_value = '" . $global_config['site_theme'] . "' WHERE lang = '" . $lang_data . "' AND module = 'global' AND config_name = 'site_theme'" );

$result = $db->query( "SELECT COUNT(*) FROM " . $db_config['prefix'] . "_" . $lang_data . "_modules where title='news'" );
if( $result->fetchColumn() )
{
	$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_cat VALUES (1, 0, 'News', '', 'News', '', '', 0, 1, 1, 0, 'viewcat_page_new', 3, '5,6,7', 1, 3, '2', '', '', 1280644983, 1280927178, '6') ");
	$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_cat VALUES (2, 0, 'Produits', '', 'Produits', '', '', 0, 2, 5, 0, 'viewcat_page_new', 0, '', 1, 3, '2', '', '', 1280644996, 1280644996, '6') ");
	$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_cat VALUES (3, 0, 'Partenaires', '', 'Partenaires', '', '', 0, 3, 6, 0, 'viewcat_page_new', 0, '', 1, 3, '2', '', '', 1280645023, 1280645023, '6') ");
	$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_cat VALUES (4, 0, 'Recrutement', '', 'Recruitement', '', '', 0, 4, 7, 0, 'viewcat_page_new', 0, '', 1, 3, '2', '', '', 1280649352, 1280649900, '6') ");
	$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_cat VALUES (5, 1, 'News Interne', '', 'News-Interne', '', '', 0, 1, 2, 1, 'viewcat_page_new', 0, '', 1, 3, '2', '', '', 1280927318, 1280927318, '6') ");
	$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_cat VALUES (6, 1, 'Nouvelles Technologies', '', 'Nouvelles-Technologies', '', '', 0, 2, 3, 1, 'viewcat_page_new', 0, '', 1, 3, '2', '', '', 1280927364, 1280927364, '6') ");
	$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_cat VALUES (7, 1, 'Espace presse', '', 'Espace-presse', '', '', 0, 3, 4, 1, 'viewcat_page_new', 0, '', 1, 3, '2', '', '', 1280928740, 1280928740, '6') ");

	$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_rows VALUES (1, 1, '1,2', 0, 1, '', 0, 1280645699, 1280751776, 1, 1280645640, 0, 2, 'Nukeviet 3.0', 'Nukeviet-3-0', 'NukeViet 3 est une nouvelle génération de Système de Gestion de Contenu développée par les Vietnamiens. Pour la première fois au Vietnam, un noyau de Open Source ouverte est investi professionnelement en financement, en ressources humaines et en temps. Le résultat est que 100% de ligne de code de NukeViet est écrit entièrement neuf. Nukeviet 3.0 utilise XHTML, CSS et jQuery avec Xtemplate permettant une application souple de Ajax, même au niveau de noyau.', 'nukeviet3.jpg', '', 1, 1, '6', 1, 2, 0, 0, 0) ");
	$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_rows VALUES (2, 2, '2', 0, 1, '', 0, 1280645876, 1280751372, 1, 1280645820, 0, 2, 'NukeViet', 'NukeViet', 'NukeViet est un système de gestion de contenu open source. Les utilisateurs l’appellent habituellement Portail parce qu&#039;il est capable d&#039;intégrer plusieurs applications sur le Web. Nguyễn Anh Tú, un ex-étudiant vietnamien en Russie, avec la communauté a développé NukeViet en une application purement vietnamienne en basant sur PHP-Nuke.', 'screenshot.jpg', '', 1, 1, '6', 1, 3, 0, 0, 0)" );
	$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_rows VALUES (3, 3, '3', 0, 1, '', 0, 1280646202, 1280751407, 1, 1280646180, 0, 2, 'VINADES', 'VINADES', 'Pour professionaliser la publication de NukeViet, l&#039;administration de NukeViet a décidé de créer une société spécialisant la gestion de NukeViet avec la raison sociale en vietnamien “Công ty cổ phần Phát triển Nguồn mở Việt Nam”, en anglais &quot;VIET NAM OPEN SOURCE DEVELOPMENT JOINT STOCK COMPANY&quot; et en abrégé VINADES.,JSC.', 'nangly.jpg', '', 1, 1, '6', 1, 3, 0, 0, 0)" );
	$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_rows VALUES (4, 4, '4', 0, 1, '', 0, 1280650419, 1280751748, 1, 1280650380, 0, 2, 'Recrutement et la formation des enseignants', 'Recrutement-et-la-formation-des-enseignants', 'A l’issue d’une série de consultations avec les organisations représentatives des personnels de l’éducation nationale et de l’enseignement supérieur sur la réforme du recrutement et de la formation des enseignants, le ministre de l’Éducation nationale et la ministre de l’Enseignement supérieur et de la Recherche apportent plusieurs éléments d’information complémentaires.', 'hoptac.jpg', '', 1, 1, '6', 1, 4, 0, 0, 0)" );

	$sth = $db->prepare( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_bodyhtml_1 VALUES (1, :bodyhtml, '', 1, 0, 1, 1, 1, 0)" );
	$bodyhtml = "<p> Profiter les fruits de Open Source, mais chaque ligne de code de NukeViet est écrit manuellement. NukeViet 3 n&#039;utilise aucune plateforme. Cela signifie que Nukeviet 3 est complètement indépendant dans son développemnt. Il est très facile à lire, à comprendre le code de NukeViet pour programmer tout seul si vous avez les connaissances de base sur PHP et MySQL. NukeViet 3.0 est complètement ouvert et facile à apprendre pour tous ceux qui veulent étudier le code de NukeViet.</p><p> Hériter la simplicité de Nukeviet mais NukeViet 3 n&#039;oublie pas de se renouveller. Le système de Nukeviet 3 supporte le multi-noyau du module. Nous appelons cela la technologie de virtualisation de module. Cette technologie permet aux utilisateurs de créer automatiquement de milliers de modules sans toucher une seule ligne de code. Le module né de cette technologie est appelé module virtuel. Il est cloné à partir de n&#039;importe quel module du système de NukeViet si ce module-ci permet la création des modules virtuels.</p><p> NukeViet 3 prend en charge l&#039;installation automatique de modules, de blocks, de thèmes dans la section d&#039;administration, les utilisateurs peuvent installer le module sans faire de tâches complexes. NukeViet 3.0 permet également le paquetage des modules pour partager aux autres utilisateus.</p><p> Le multi-langage de NukeViet 3 est parfait avec le multi-langage de l&#039;interface et celui de données. NukeViet 3.0 supporte aux administrateurs de créer facilement de nouvelles langues pour le site. Le paquetage des fichiers de langue est également supporté pour faciliter la contribution du travai à la communauté.</p>";
	$sth->bindParam( ':bodyhtml', $bodyhtml, PDO::PARAM_STR, strlen( $bodyhtml ) );
	$sth->execute();

	$sth = $db->prepare( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_bodyhtml_1 VALUES (2, :bodyhtml, '', 1, 0, 1, 1, 1, 0)" );
	$bodyhtml = "<p> Similaire à PHP-Nuke, NukeViet est écrit en langage PHP et utilise la base de données MySQL, permet aux utilisateurs de publier, de gérer facilement leur contenu sur Internet ou Intranet.</p><p> <strong>* Fonctionnalités de base de NukeViet: </strong></p><p> - News: Gestion d’articles: créer les articles multi-niveau, générer la page d’impression, permettre le téléchargement, les commentaires.</p><p> -&nbsp; Download: Gestion de téléchargement des fichier</p><p> - Vote: sondage</p><p> - Contact</p><p> -&nbsp; Search: Rechercher</p><p> -&nbsp; RSS</p><p> <strong>* Caractéristiques: </strong></p><p> - Supporter le multi-langage</p><p> - Permettre le changement de l’interface (theme)</p><p> - Monter le pare-feu pour limiter DDOS ...</p><p> Nukeviet est utilisé dans de nombreux sites Web, de sites personnels aux sites professionnels. Il offre de nombreux services et applications grâce à la capacité d&#039;accroître la fonctionnalité en installant des modules, blocks additionnels ... Cependant, Nukeviet est utilisé principalement pour les sites d’actualités vietnamiens par ce que son module News conforme bien aux exigences et habitudes des Vietnamiens. Il est très facile d’installer, de gérer Nukeviet, même avec les débutants, il est donc un système favorable des amateurs.</p><p> NukeViet est open source, et totalement gratuit pour tout de monde de tous les pays. Toutefois, les Vietnamiens sont les utilisateurs principales en raison des caractéristiques de la code source (provenant de PHP-Nuke) et de la politique des développeurs &quot;Système de Portail Pour les Vietnamiens&quot;.</p>";
	$sth->bindParam( ':bodyhtml', $bodyhtml, PDO::PARAM_STR, strlen( $bodyhtml ) );
	$sth->execute();

	$sth = $db->prepare( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_bodyhtml_1 VALUES (3, :bodyhtml, '', 1, 0, 1, 1, 1, 0)" );
	$bodyhtml = "<p> Cette société est ouverte officiellement au&nbsp; 25-2-2010 avec le bureau à&nbsp; Chambre 1805 – CT2 Nang Huong building, 583 Nguyen Trai, Hanoi, Vietnam. Son but est de développer et de diffuser NukeViet au Vietnam.<br /> <br /> D&#039;après M. Nguyễn Anh Tú, président de VINADES, cette société développera le source de NukeViet sous forme open source, professionnel, et totalement gratuit selon l&#039;esprit mondial de open source.<br /> <br /> NukeViet est un système de gestion de contenu open source (Open Source Content Management System) purement vietnamien développé à la base de PHP-Nuke et base de données MySQL. Les utilisateurs l&#039;appellent habituellement Portail par ce qu&#039;ils puissent intégrer de multiples applications permettant la publication et la gestion facile de contenu sur l&#039;internet ou sur l&#039;intranet.</p><p> NukeViet peut fournir plusieurs services et appliations grace aux modules, blocks... L&#039;installation, la gestion de NukeViet 3 est très facile,&nbsp; même avec les débutants.</p><p> Depuis quelques années, NukeViet est devenu une application Open Source tres familière de la communauté informatique du Vietnam. Nukeviet est utilisé dans presque toutes les domaines, de l&#039;actualité, de la commerce électronique, de site personnel aux site professionel.</p><p> Pour avoir les details plus amples sur NukeViet, veuillez consulter le site http://nukeviet.vn.</p>";
	$sth->bindParam( ':bodyhtml', $bodyhtml, PDO::PARAM_STR, strlen( $bodyhtml ) );
	$sth->execute();

	$sth = $db->prepare( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_bodyhtml_1 VALUES (4, :bodyhtml, '', 1, 0, 1, 1, 1, 0)" );
	$bodyhtml = "<p> Ils précisent, notamment, les modalités concrètes de concertation qui conduiront à la mise en place de la réforme définitive au cours de l’année 2010/2011. Le processus de concertation avec les organisations représentatives reposera notamment sur trois groupes de travail ch@rgés d’étudier :<br /> <br /> &nbsp;&nbsp;&nbsp; * Les concours de recrutement<br /> &nbsp;&nbsp;&nbsp; * Le cadrage des masters et leur articulation avec les concours<br /> &nbsp;&nbsp;&nbsp; * L’organisation et le contenu de la période de formation continuée pendant l’année de fonctionnaire stagiaire à l’issue du concours<br /> <br /> Une commission de concertation sur la réforme du recrutement et de la formation sera également mise en place avec des acteurs universitaires. Le recteur Marois et le président Filatre en assureront la coprésidence. Ils feront très rapidement des propositions aux ministres sur la composition et le fonctionnement de cette commission qui consultera régulièrement les organisations représentatives.<br /> <br /> Les ministres ont également détaillé les conditions de mise en oeuvre du processus de mastérisation de la formation des enseignants et des conseillers principaux d’éducation (C.P.E.), qui sera engagé dès l’année prochaine.<br /> <br /> Ils confirment que, pour la session 2010, les contenus des concours resteront en l’état. Par ailleurs, pour s’inscrire aux concours de la session 2010, les étudiants devront :<br /> <br /> &nbsp;&nbsp;&nbsp; * Soit déjà être titulaires d’un master ou inscrits en M2 à la rentrée universitaire 2009 ;<br /> &nbsp;&nbsp;&nbsp; * Soit, à titre exceptionnel et dérogatoire, pour la seule session 2010 des concours :<br /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - avoir été présents aux épreuves d’admissibilité de la session 2009<br /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - ou bien, être inscrits en M1 dans une composante universitaire à la rentrée 2009.<br /> <br /> Pour l’année transitoire 2009 - 2010 l’inscription en I.U.F.M. vaudra également inscription en M1 par convention avec les universités afin de favoriser le processus de mastérisation. En cas de réussite à un concours de la session 2010, le bénéfice du concours sera garanti pendant un an à ces candidats inscrits en M1. Ils seront recrutés comme enseignant stagiaire pour la rentrée scolaire 2011 sous réserve de l’obtention de leur M2 à l’issue de l’année universitaire 2010-2011.<br /> <br /> Dès septembre 2009, des stages de pratique accompagnée ou en responsabilité rémunérés seront mis en place afin d’engager le processus de préprofessionnalisation lié à la mastérisation.<br /> <br /> Dès la prochaine rentrée universitaire, les étudiants se destinant au métier d’enseignant pourront également bénéficier d’un dispositif d’aides complémentaires mis en oeuvre par le ministère de l’Éducation Nationale.<br /> <br /> A la rentrée 2010, un tiers de l’obligation de service des nouveaux enseignants, recrutés lors de la session 2010 des concours, sera consacré à une formation continue renforcée, prenant la forme d’un tutorat et d’une formation universitaire à visée disciplinaire ou professionnelle.<br /> <br /> Enfin, la discussion sur la revalorisation du salaire des nouveaux enseignants sera conduite en parallèle pour être applicable aux lauréats des concours de la session 2010.</p>";
	$sth->bindParam( ':bodyhtml', $bodyhtml, PDO::PARAM_STR, strlen( $bodyhtml ) );
	$sth->execute();

	// news_bodytext
	$sth = $db->prepare( 'INSERT INTO ' . $db_config['prefix'] . '_' . $lang_data . '_news_bodytext VALUES ( :id, :bodytext )' );
	$bodytext = "Profiter les fruits de Open Source, mais chaque ligne de code de NukeViet est écrit manuellement. NukeViet 3 n&#039;utilise aucune plateforme. Cela signifie que Nukeviet 3 est complètement indépendant dans son développemnt. Il est très facile à lire, à comprendre le code de NukeViet pour programmer tout seul si vous avez les connaissances de base sur PHP et MySQL. NukeViet 3.0 est complètement ouvert et facile à apprendre pour tous ceux qui veulent étudier le code de NukeViet. Hériter la simplicité de Nukeviet mais NukeViet 3 n&#039;oublie pas de se renouveller. Le système de Nukeviet 3 supporte le multi-noyau du module. Nous appelons cela la technologie de virtualisation de module. Cette technologie permet aux utilisateurs de créer automatiquement de milliers de modules sans toucher une seule ligne de code. Le module né de cette technologie est appelé module virtuel. Il est cloné à partir de n&#039;importe quel module du système de NukeViet si ce module-ci permet la création des modules virtuels. NukeViet 3 prend en c-harge l&#039;installation automatique de modules, de blocks, de thèmes dans la section d&#039;administration, les utilisateurs peuvent installer le module sans faire de tâches complexes. NukeViet 3.0 permet également le paquetage des modules pour partager aux autres utilisateus. Le multi-langage de NukeViet 3 est parfait avec le multi-langage de l&#039;interface et celui de données. NukeViet 3.0 supporte aux administrateurs de créer facilement de nouvelles langues pour le site. Le paquetage des fichiers de langue est également supporté pour faciliter la contribution du travai à la communauté";
	$sth->bindValue( ':id', 1, PDO::PARAM_INT);
	$sth->bindParam( ':bodytext', $bodytext, PDO::PARAM_STR, strlen( $bodytext ) );
	$sth->execute();

	$bodytext ="Similaire à PHP-Nuke, NukeViet est écrit en langage PHP et utilise la base de données MySQL, permet aux utilisateurs de publier, de gérer facilement leur contenu sur Internet ou Intranet. * Fonctionnalités de base de NukeViet: - News: Gestion d’articles: créer les articles multi-niveau, générer la page d’impression, permettre le téléc-hargement, les commentaires. - Download: Gestion de téléc-hargement des fichier - Vote: sondage - Contact - Search: Rechercher - RSS * Caractéristiques: - Supporter le multi-langage - Permettre le changement de l’interface (theme) - Monter le pare-feu pour limiter DDOS ... Nukeviet est utilisé dans de nombreux sites Web, de sites personnels aux sites professionnels. Il offre de nombreux services et applications grâce à la capacité d&#039;accroître la fonctionnalité en installant des modules, blocks additionnels ... Cependant, Nukeviet est utilisé principalement pour les sites d’actualités vietnamiens par ce que son module News conforme bien aux exigences et habitudes des Vietnamiens. Il est très facile d’installer, de gérer Nukeviet, même avec les débutants, il est donc un système favorable des amateurs. NukeViet est open source, et totalement gratuit pour tout de monde de tous les pays. Toutefois, les Vietnamiens sont les utilisateurs principales en raison des caractéristiques de la code source (provenant de PHP-Nuke) et de la politique des développeurs \"Système de Portail Pour les Vietnamiens\".";
	$sth->bindValue( ':id', 2, PDO::PARAM_INT);
	$sth->bindParam( ':bodytext', $bodytext, PDO::PARAM_STR, strlen( $bodytext ) );
	$sth->execute();

	$bodytext = "Cette société est ouverte officiellement au 25-2-2010 avec le bureau à Chambre 1805 – CT2 Nang Huong building, 583 Nguyen Trai, Hanoi, Vietnam. Son but est de développer et de diffuser NukeViet au Vietnam. D&#039;après M. Nguyễn Anh Tú, président de VINADES, cette société développera le source de NukeViet sous forme open source, professionnel, et totalement gratuit selon l&#039;esprit mondial de open source. NukeViet est un système de gestion de contenu open source (Open Source Content Management System) purement vietnamien développé à la base de PHP-Nuke et base de données MySQL. Les utilisateurs l&#039;appellent habituellement Portail par ce qu&#039;ils puissent intégrer de multiples applications permettant la publication et la gestion facile de contenu sur l&#039;internet ou sur l&#039;intranet. NukeViet peut fournir plusieurs services et appliations grace aux modules, blocks... L&#039;installation, la gestion de NukeViet 3 est très facile, même avec les débutants. Depuis quelques années, NukeViet est devenu une application Open Source tres familière de la communauté informatique du Vietnam. Nukeviet est utilisé dans presque toutes les domaines, de l&#039;actualité, de la commerce électronique, de site personnel aux site professionel. Pour avoir les details plus amples sur NukeViet, veuillez consulter le site http://nukeviet.vn.";
	$sth->bindValue( ':id', 3, PDO::PARAM_INT);
	$sth->bindParam( ':bodytext', $bodytext, PDO::PARAM_STR, strlen( $bodytext ) );
	$sth->execute();

	$bodytext = "Ils précisent, notamment, les modalités concrètes de concertation qui conduiront à la mise en place de la réforme définitive au cours de l’année 2010/2011. Le processus de concertation avec les organisations représentatives reposera notamment sur trois groupes de travail ch@rgés d’étudier : * Les concours de recrutement * Le cadrage des masters et leur articulation avec les concours * L’organisation et le contenu de la période de formation continuée pendant l’année de fonctionnaire stagiaire à l’issue du concours Une commission de concertation sur la réforme du recrutement et de la formation sera également mise en place avec des acteurs universitaires. Le recteur Marois et le président Filatre en assureront la coprésidence. Ils feront très rapidement des propositions aux ministres sur la composition et le fonctionnement de cette commission qui consultera régulièrement les organisations représentatives. Les ministres ont également détaillé les conditions de mise en oeuvre du processus de mastérisation de la formation des enseignants et des conseillers principaux d’éducation (C.P.E.), qui sera engagé dès l’année prochaine. Ils confirment que, pour la session 2010, les contenus des concours resteront en l’état. Par ailleurs, pour s’inscrire aux concours de la session 2010, les étudiants devront : * Soit déjà être titulaires d’un master ou inscrits en M2 à la rentrée universitaire 2009 ; * Soit, à titre exceptionnel et dérogatoire, pour la seule session 2010 des concours : - avoir été présents aux épreuves d’admissibilité de la session 2009 - ou bien, être inscrits en M1 dans une composante universitaire à la rentrée 2009. Pour l’année transitoire 2009 - 2010 l’inscription en I.U.F.M. vaudra également inscription en M1 par convention avec les universités afin de favoriser le processus de mastérisation. En cas de réussite à un concours de la session 2010, le bénéfice du concours sera garanti pendant un an à ces candidats inscrits en M1. Ils seront recrutés comme enseignant stagiaire pour la rentrée scolaire 2011 sous réserve de l’obtention de leur M2 à l’issue de l’année universitaire 2010-2011. Dès septembre 2009, des stages de pratique accompagnée ou en responsabilité rémunérés seront mis en place afin d’engager le processus de préprofessionnalisation lié à la mastérisation. Dès la prochaine rentrée universitaire, les étudiants se destinant au métier d’enseignant pourront également bénéficier d’un dispositif d’aides complémentaires mis en oeuvre par le ministère de l’Éducation Nationale. A la rentrée 2010, un tiers de l’obligation de service des nouveaux enseignants, recrutés lors de la session 2010 des concours, sera consacré à une formation continue renforcée, prenant la forme d’un tutorat et d’une formation universitaire à visée disciplinaire ou professionnelle. Enfin, la discussion sur la revalorisation du salaire des nouveaux enseignants sera conduite en parallèle pour être applicable aux lauréats des concours de la session 2010.";
	$sth->bindValue( ':id', 4, PDO::PARAM_INT);
	$sth->bindParam( ':bodytext', $bodytext, PDO::PARAM_STR, strlen( $bodytext ) );
	$sth->execute();

	$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_block_cat (bid, adddefault, numbers, title, alias, image, description, weight, keywords, add_time, edit_time) VALUES (1, 0, 4, 'Populairs', 'Populairs', '', 'Block Populairs', 1, '', 1279945710, 1279956943)" );
	$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_block_cat (bid, adddefault, numbers, title, alias, image, description, weight, keywords, add_time, edit_time) VALUES (2, 1, 4, 'Récents', 'Recents', '', 'Block Récents', 2, '', 1279945725, 1279956445)" );

	$db->query( 'INSERT INTO ' . $db_config['prefix'] . '_' . $lang_data . '_news_block (bid, id, weight) VALUES (1, 2, 2)' );
	$db->query( 'INSERT INTO ' . $db_config['prefix'] . '_' . $lang_data . '_news_block (bid, id, weight) VALUES (1, 1, 1)' );

	$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_tags (tid, numnews, alias, image, description, keywords) VALUES (1, 0, 'nukeviet', '', '', 'Nukeviet')" );
	$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_tags_id (id, tid, keyword) VALUES (1, 1, 'Nukeviet')" );
	$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_tags_id (id, tid, keyword) VALUES (2, 1, 'NukeViet')" );

	$db->query( "UPDATE " . $db_config['prefix'] . "_config SET config_value = " . $db->quote( $copyright ) . " WHERE module = 'news' AND config_name = 'copyright' AND lang='fr'" );
	$db->query( "UPDATE " . $db_config['prefix'] . "_config SET config_value = 'news' WHERE module = 'global' AND config_name = 'site_home_module' AND lang='" . $lang_data . "'" );
}

$result = $db->query( "SELECT COUNT(*) FROM " . $db_config['prefix'] . "_" . $lang_data . "_modules where title='voting'" );
if( $result->fetchColumn() )
{
	$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_voting VALUES (1, 'Qu&#039;est ce que NukeViet 3.0?', '', 1, 1, '6', 1275318563, 0, 1)" );

	$sth = $db->prepare( 'INSERT INTO ' . $db_config['prefix'] . '_' . $lang_data . '_voting_rows VALUES (?, ?, ?, ?, ?)' );
	$sth->execute( array(1, 1, 'Une code source de web tout neuve','', 0) );
	$sth->execute( array(2, 1, 'Open source, libre et gratuit','', 0) );
	$sth->execute( array(3, 1, 'Utilise xHTML, CSS et supporte Ajax','', 0) );
	$sth->execute( array(4, 1, 'Toutes ces réponses','', 1) );
}

$result = $db->query( "SELECT COUNT(*) FROM " . $db_config['prefix'] . "_" . $lang_data . "_modules where title='about'" );
if( $result->fetchColumn() )
{
	$sth = $db->prepare( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_about (id, title, alias, image, imagealt, description, bodytext, keywords, socialbutton, activecomm, facebookappid, layout_func, gid, weight, admin_id, add_time, edit_time, status) VALUES (1, 'Qu’est ce que Nukeviet?', 'Qu-est-ce-que-Nukeviet', '', '', '', :bodytext, '', 0, 4, '', '', 0, 1, 1, 1280634933, 1280634933, 1)");
	$bodytext = "<p> NukeViet est un système de gestion de contenu open source. Les utilisateurs l’appellent habituellement Portail parce qu&#039;il est capable d&#039;intégrer plusieurs applications sur le Web. Nguyễn Anh Tú, un ex-étudiant vietnamien en Russie, avec la communauté a développé NukeViet en une application purement vietnamienne en basant sur PHP-Nuke. Similaire à PHP-Nuke, NukeViet est écrit en langage PHP et utilise la base de données MySQL, permet aux utilisateurs de publier, de gérer facilement leur contenu sur Internet ou Intranet.<br />  </p><p> <strong>* Fonctionnalités de base de NukeViet: </strong></p><p> - News: Gestion d’articles: créer les articles multi-niveau, générer la page d’impression, permettre le téléchargement, les commentaires.</p><p> -&nbsp; Download: Gestion de téléchargement des fichier</p><p> - Vote: sondage</p><p> - Contact</p><p> -&nbsp; Search: Rechercher</p><p> -&nbsp; RSS</p><p> <strong>* Caractéristiques: </strong></p><p> - Supporter le multi-langage</p><p> - Permettre le changement de l’interface (theme)</p><p> - Monter le pare-feu pour limiter DDOS ...</p><p> Nukeviet est utilisé dans de nombreux sites Web, de sites personnels aux sites professionnels. Il offre de nombreux services et applications grâce à la capacité d&#039;accroître la fonctionnalité en installant des modules, blocks additionnels ... Cependant, Nukeviet est utilisé principalement pour les sites d’actualités vietnamiens par ce que son module News conforme bien aux exigences et habitudes des Vietnamiens. Il est très facile d’installer, de gérer Nukeviet, même avec les débutants, il est donc un système favorable des amateurs.</p><p> NukeViet est open source, et totalement gratuit pour tout de monde de tous les pays. Toutefois, les Vietnamiens sont les utilisateurs principales en raison des caractéristiques de la code source (provenant de PHP-Nuke) et de la politique des développeurs &quot;Système de Portail Pour les Vietnamiens&quot;.</p>";
	$sth->bindParam( ':bodytext', $bodytext, PDO::PARAM_STR, strlen( $bodyhtml ) );
	$sth->execute();

	$sth = $db->prepare( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_about (id, title, alias, image, imagealt, description, bodytext, keywords, socialbutton, activecomm, facebookappid, layout_func, gid, weight, admin_id, add_time, edit_time, status) VALUES (2, 'Introduction de NukeViet 3', 'Introduction-de-NukeViet-3', '', '', '', :bodytext, '', 0, 4, '', '', 0, 3, 1, 1280637520, 1280637520, 1) ");
	$bodytext = "<p> NukeViet 3 est une nouvelle génération de Système de Gestion de Contenu développée par les Vietnamiens. Pour la première fois au Vietnam, un noyau de Open Source ouverte est investi professionnelement en financement, en ressources humaines et en temps. Le résultat est que 100% de ligne de code de NukeViet est écrit entièrement neuf. Nukeviet 3.0 utilise XHTML, CSS et jQuery avec Xtemplate permettant une application souple de Ajax, même au niveau de noyau.</p><p> Profiter les fruits de Open Source, mais chaque ligne de code de NukeViet est écrit manuellement. NukeViet 3 n&#039;utilise aucune plateforme. Cela signifie que Nukeviet 3 est complètement indépendant dans son développemnt. Il est très facile à lire, à comprendre le code de NukeViet pour programmer tout seul si vous avez les connaissances de base sur PHP et MySQL. NukeViet 3.0 est complètement ouvert et facile à apprendre pour tous ceux qui veulent étudier le code de NukeViet.</p><p> Hériter la simplicité de Nukeviet mais NukeViet 3 n&#039;oublie pas de se renouveller. Le système de Nukeviet 3 supporte le multi-noyau du module. Nous appelons cela la technologie de virtualisation de module. Cette technologie permet aux utilisateurs de créer automatiquement de milliers de modules sans toucher une seule ligne de code. Le module né de cette technologie est appelé module virtuel. Il est cloné à partir de n&#039;importe quel module du système de NukeViet si ce module-ci permet la création des modules virtuels.</p><p> NukeViet 3 prend en charge l&#039;installation automatique de modules, de blocks, de thèmes dans la section d&#039;administration, les utilisateurs peuvent installer le module sans faire de tâches complexes. NukeViet 3.0 permet également le paquetage des modules pour partager aux autres utilisateus.</p><p> Le multi-langage de NukeViet 3 est parfait avec le multi-langage de l&#039;interface et celui de données. NukeViet 3.0 supporte aux administrateurs de créer facilement de nouvelles langues pour le site. Le paquetage des fichiers de langue est également supporté pour faciliter la contribution du travai à la communauté.</p><p> L&#039;histoire de NukeViet sera encore très longue&nbsp; par ce qu’une variété de fonctionnalités avancées sont encore en cours d&#039;élaboration.</p><p> Utilisez et diffusez NukeViet 3 pour jouir les récents fruits de la technologies de web open source.</p><p> Enfin, NukeViet 3 est un cadeau que VINADES voudrait envoyer à la communauté pour remercier son soutient. NukeViet retourne maintenant à la communauté dans l’espoir à son développement continu.</p><p> Si vous intéressez à NukeViet, n’hésitez pas à nous joindre au Forum de NukeViet.Vn.</p>";
	$sth->bindParam( ':bodytext', $bodytext, PDO::PARAM_STR, strlen( $bodyhtml ) );
	$sth->execute();

	$sth = $db->prepare( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_about (id, title, alias, image, imagealt, description, bodytext, keywords, socialbutton, activecomm, facebookappid, layout_func, gid, weight, admin_id, add_time, edit_time, status) VALUES (3, 'Ouverture de VINADES', 'Ouverture-de-VINADES', '', '', '', :bodytext, '', 0, 4, '', '', 0, 2, 1, 1280637944, 1280637944, 1)" );
	$bodytext = "<p> Depuis quelques années, NukeViet est devenu une application Open Source tres familière de la communauté informatique du Vietnam. Étant donnée qu&#039;il n&#039;y a pas encore les activités officielles, Nukeviet est utilisé dans presque toutes les domaines, de l&#039;actualité, de la commerce électronique, de site personnel aux site professionle.<br />  </p><p> Pour professionaliser la publication de NukeViet,&nbsp; l&#039;administration de NukeViet a décidé de créer une société spécialisant la&nbsp; gestion de NukeViet avec la raison sociale en vietnamien “Công ty cổ phần Phát triển Nguồn mở Việt Nam”, en anglais &quot;VIET NAM OPEN SOURCE DEVELOPMENT JOINT STOCK COMPANY&quot; et en abrégé VINADES.,JSC. Cette société est ouverte officiellement au&nbsp; 25-2-2010 avec le bureau à&nbsp; Chambre 1805 – CT2 Nang Huong building, 583 Nguyen Trai, Hanoi, Vietnam. Son but est de développer et de diffuser NukeViet au Vietnam.<br /> <br /> D&#039;après M. Nguyễn Anh Tú, président de VINADES, cette société développera le source de NukeViet sous forme open source, professionnel, et totalement gratuit selon l&#039;esprit mondial de open source.<br /> <br /> NukeViet est un système de gestion de contenu open source (Open Source Content Management System) purement vietnamien développé à la base de PHP-Nuke et base de données MySQL. Les utilisateurs l&#039;appellent habituellement Portail par ce qu&#039;ils puissent intégrer de multiples applications permettant la publication et la gestion facile de contenu sur l&#039;internet ou sur l&#039;intranet.</p><p> <br /> NukeViet peut fournir plusieurs services et appliations grace aux modules, blocks... L&#039;installation, la gestion de NukeViet 3 est très facile,&nbsp; même avec les débutants.</p><p> Pour avoir les details plus amples sur NukeViet, veuillez consulter le site http://nukeviet.vn.</p>";
	$sth->bindParam( ':bodytext', $bodytext, PDO::PARAM_STR, strlen( $bodyhtml ) );
	$sth->execute();
}
