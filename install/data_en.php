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
$sth = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_' . $lang_data . '_modules (title, module_file, module_data, custom_title, admin_title, set_time, main_file, admin_file, theme, mobile, description, keywords, groups_view, weight, act, admins, rss, gid) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)' );
$sth->execute( array('about', 'page', 'about', 'About', '', 1276333182, 1, 1, '', '', '', '', '6', 1, 1, '', 0, 0) );
$sth->execute( array('news', 'news', 'news', 'News', '', 1270400000, 1, 1, '', '', '', '', '6', 2, 1, '', 1, 0) );
$sth->execute( array('users', 'users', 'users', 'Users', '', 1274080277, 1, 1, '', '', '', '', '6', 3, 1, '', 0, 0) );
$sth->execute( array('contact', 'contact', 'contact', 'Contact', '', 1275351337, 1, 1, '', '', '', '', '6', 4, 1, '', 0, 0) );
$sth->execute( array('statistics', 'statistics', 'statistics', 'Statistics', '', 1276520928, 1, 0, '', '', '', 'online, statistics', '6', 5, 1, '', 0, 0) );
$sth->execute( array('voting', 'voting', 'voting', 'Voting', '', 1275315261, 1, 1, '', '', '', '', '6', 6, 1, '', 1, 0) );
$sth->execute( array('banners', 'banners', 'banners', 'Banners', '', 1270400000, 1, 1, '', '', '', '', '6', 7, 1, '', 0, 0) );
$sth->execute( array('seek', 'seek', 'seek', 'Search', '', 1273474173, 1, 0, '', '', '', '', '6', 8, 1, '', 0, 0) );
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
$sth->execute( array(18, 'changepass', 'changepass', 'Changepass', 'users', 1, 1, 6, '') );
$sth->execute( array(19, 'editinfo', 'editinfo', 'Editinfo', 'users', 1, 0, 10, '') );
$sth->execute( array(20, 'login', 'login', 'Login', 'users', 1, 1, 2, '') );
$sth->execute( array(21, 'logout', 'logout', 'Logout', 'users', 1, 1, 3, '') );
$sth->execute( array(22, 'lostactivelink', 'lostactivelink', 'Lostactivelink', 'users', 1, 0, 9, '') );
$sth->execute( array(23, 'lostpass', 'lostpass', 'Lostpass', 'users', 1, 1, 5, '') );
$sth->execute( array(24, 'main', 'main', 'Main', 'users', 1, 0, 1, '') );
$sth->execute( array(25, 'openid', 'openid', 'Openid', 'users', 1, 1, 7, '') );
$sth->execute( array(26, 'register', 'register', 'Register', 'users', 1, 1, 4, '') );
$sth->execute( array(27, 'main', 'main', 'Main', 'contact', 1, 0, 1, '') );
$sth->execute( array(28, 'allbots', 'allbots', 'Allbots', 'statistics', 1, 1, 6, '') );
$sth->execute( array(29, 'allbrowsers', 'allbrowsers', 'Allbrowsers', 'statistics', 1, 1, 4, '') );
$sth->execute( array(30, 'allcountries', 'allcountries', 'Allcountries', 'statistics', 1, 1, 3, '') );
$sth->execute( array(31, 'allos', 'allos', 'Allos', 'statistics', 1, 1, 5, '') );
$sth->execute( array(32, 'allreferers', 'allreferers', 'Allreferers', 'statistics', 1, 1, 2, '') );
$sth->execute( array(33, 'main', 'main', 'Main', 'statistics', 1, 0, 1, '') );
$sth->execute( array(34, 'referer', 'referer', 'Referer', 'statistics', 1, 0, 7, '') );
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
$sth->execute( array(50, 'memberlist', 'memberlist', 'Member List', 'users', 1, 1, 12, '') );
$sth->execute( array(51, 'groups', 'groups', 'Groups', 'news', 1, 0, 7, '') );
$sth->execute( array(52, 'tag', 'tag', 'Tag', 'news', 1, 0, 2, '') );
$sth->execute( array(53, 'main', 'main', 'Main', 'page', 1, 0, 1, '') );
$sth->execute( array(54, 'main', 'main', 'main', 'comment', 1, 0, 1, '') );
$sth->execute( array(55, 'post', 'post', 'post', 'comment', 1, 0, 2, '') );
$sth->execute( array(56, 'like', 'like', 'Like', 'comment', 1, 0, 3, '') );
$sth->execute( array(57, 'delete', 'delete', 'Delete', 'comment', 1, 0, 4, '') );
$sth->execute( array(58, 'avatar', 'avatar', 'Avatar', 'users', 1, 0, 13, '') );

$db->query( 'TRUNCATE TABLE ' . $db_config['prefix'] . '_' . $lang_data . '_modthemes' );
$sth = $db->prepare( 'INSERT INTO ' . $db_config['prefix'] . '_' . $lang_data . '_modthemes (func_id, layout, theme) VALUES (?, ?, ?)' );
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
$sth->execute( array(1, 'default', 'news', 'global.block_category.php', 'Category', '', 'no_title', '[LEFT]', 0, 1, '6', 0, 1, 'a:2:{s:5:"catid";i:0;s:12:"title_length";i:25;}') );
$sth->execute( array(2, 'default', 'statistics', 'global.counter.php', 'Counter', '', '', '[LEFT]', 0, 1, '6', 1, 2, '') );
$sth->execute( array(3, 'default', 'banners', 'global.banners.php', 'Left Banner', '', '', '[LEFT]', 0, 1, '6', 1, 3, 'a:1:{s:12:"idplanbanner";i:2;}') );
$sth->execute( array(4, 'default', 'about', 'global.about.php', 'About', '', 'border', '[RIGHT]', 0, 1, '6', 1, 1, '') );
$sth->execute( array(5, 'default', 'users', 'global.login.php', 'Login site', '', '', '[RIGHT]', 0, 1, '6', 1, 2, '') );
$sth->execute( array(6, 'default', 'voting', 'global.voting_random.php', 'Voting', '', '', '[RIGHT]', 0, 1, '6', 1, 3, '') );
$sth->execute( array(7, 'default', 'news', 'module.block_headline.php', 'headline', '', 'no_title', '[TOP]', 0, 1, '6', 0, 1, 'a:3:{s:11:"showtooltip";i:1;s:16:"tooltip_position";s:6:"bottom";s:14:"tooltip_length";s:3:"150";}') );
$sth->execute( array(8, 'default', 'banners', 'global.banners.php', 'Center Banner', '', 'no_title', '[TOP]', 0, 1, '6', 1, 2, 'a:1:{s:12:"idplanbanner";i:1;}') );
$sth->execute( array(9, 'modern', 'news', 'module.block_newscenter.php', 'News Center', '', 'no_title', '[HEADER]', 0, 1, '6', 0, 1, 'a:3:{s:11:"showtooltip";i:1;s:16:"tooltip_position";s:6:"bottom";s:14:"tooltip_length";s:3:"150";}') );
$sth->execute( array(10, 'modern', 'about', 'global.about.php', 'About', '', 'no_title_html', '[RIGHT]', 0, 1, '6', 1, 1, '') );
$sth->execute( array(11, 'modern', 'users', 'global.login.php', 'Login site', '', '', '[RIGHT]', 0, 1, '6', 1, 2, '') );
$sth->execute( array(12, 'modern', 'voting', 'global.voting_random.php', 'Voting', '', '', '[RIGHT]', 0, 1, '6', 1, 3, '') );
$sth->execute( array(13, 'modern', 'statistics', 'global.counter.php', 'Counter', '', '', '[RIGHT]', 0, 1, '6', 1, 4, '') );
$sth->execute( array(14, 'modern', 'news', 'module.block_newsright.php', 'News Right', '', 'no_title', '[RIGHT]', 0, 1, '6', 0, 5, '') );
$sth->execute( array(15, 'modern', 'banners', 'global.banners.php', 'Top banner', '', 'no_title', '[TOPADV]', 0, 1, '6', 1, 1, 'a:1:{s:12:"idplanbanner";i:1;}') );
$sth->execute( array(16, 'modern', 'theme', 'global.menu.php', 'global menu', '', 'no_title', '[MENU_SITE]', 0, 1, '6', 1, 1, '') );
$sth->execute( array(17, 'default', 'theme', 'global.menu.php', 'global menu', '', 'no_title', '[MENU_SITE]', 0, 1, '6', 1, 1, '') );
$sth->execute( array(18, 'modern', 'page', 'global.html.php', 'footer site', '', 'no_title', '[FOOTER_SITE]', 0, 1, '6', 1, 1, 'a:1:{s:11:"htmlcontent";s:207:"© Copyright NukeViet 4. All right reserved.<br  />Powered by <a href="http://nukeviet.vn/" title="NukeViet CMS">NukeViet CMS</a>. Design by <a href="http://vinades.vn/" title="VINADES.,JSC">VINADES.,JSC</a>";}') );
$sth->execute( array(19, 'default', 'page', 'global.html.php', 'footer site', '', 'no_title', '[FOOTER_SITE]', 0, 1, '6', 1, 1, 'a:1:{s:11:"htmlcontent";s:229:"<p class="footer">© Copyright NukeViet 4. All right reserved.</p><p>Powered by <a href="http://nukeviet.vn/" title="NukeViet CMS">NukeViet CMS</a>. Design by <a href="http://vinades.vn/" title="VINADES.,JSC">VINADES.,JSC</a></p>";}') );
$sth->execute( array(20, 'mobile_nukeviet', 'theme', 'global.menu.php', 'global menu', '', 'no_title', '[MENU_SITE]', 0, 1, '6', 1, 1, '') );
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

$disable_site_content = 'For technical reasons Web site temporary not available. we are very sorry for that inconvenience!';

$copyright = 'Note: The above article reprinted at the website or other media sources not specify the source http://nukeviet.vn is copyright infringement';

$db->query( "UPDATE " . $db_config['prefix'] . "_config SET config_value = " . $db->quote( $disable_site_content ) . " WHERE module = 'global' AND config_name = 'disable_site_content' AND lang='" . $lang_data . "'" );

$array_cron_name = array();
$array_cron_name['cron_online_expired_del'] = 'Delete expired online status';
$array_cron_name['cron_dump_autobackup'] = 'Automatic backup database';
$array_cron_name['cron_auto_del_temp_download'] = 'Empty temporary files';
$array_cron_name['cron_del_ip_logs'] = 'Delete IP log files';
$array_cron_name['cron_auto_del_error_log'] = 'Delete expired error_log log files';
$array_cron_name['cron_auto_sendmail_error_log'] = 'Send error logs to admin';
$array_cron_name['cron_ref_expired_del'] = 'Delete expired referer';
$array_cron_name['cron_siteDiagnostic_update'] = 'Update site diagnostic';
$array_cron_name['cron_auto_check_version'] = 'Check NukeViet version';


$result = $db->query( "SELECT id, run_func FROM " . $db_config['prefix'] . "_cronjobs ORDER BY id ASC" );
while( list( $id, $run_func ) = $result->fetch( 3 ) )
{
	$cron_name = ( isset( $array_cron_name[$run_func] ) ) ? $array_cron_name[$run_func] : $run_func;
	$db->query( 'UPDATE ' . $db_config['prefix'] . '_cronjobs SET ' . $lang_data . '_cron_name = ' . $db->quote( $cron_name ) . ' WHERE id=' . $id );
}

$db->query( "UPDATE " . $db_config['prefix'] . "_config SET config_value = '" . $global_config['site_theme'] . "' WHERE lang = 'vi' AND module = 'global' AND config_name = 'site_theme'" );

$result = $db->query( "SELECT COUNT(*) FROM " . $db_config['prefix'] . "_" . $lang_data . "_modules where title='news'" );
if( $result->fetchColumn() )
{
	$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_cat VALUES (1, 0, 'Co-operate', '', 'Co-operate', '', '', 0, 2, 5, 0, 'viewcat_page_new', 2, '2,3', 1, 3, '2', '', '', 1277689708, 1277689708, '6') ");
	$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_cat VALUES (2, 1, 'Careers at NukeViet', '', 'Careers-at-NukeViet', '', '', 0, 1, 6, 1, 'viewcat_page_new', 0, '', 1, 3, '2', '', '', 1277690086, 1277690259, '6') ");
	$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_cat VALUES (3, 1, 'Partners', '', 'Partners', '', '', 0, 2, 7, 1, 'viewcat_page_new', 0, '', 1, 3, '2', '', '', 1277690142, 1277690291, '6') ");
	$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_cat VALUES (4, 0, 'NukeViet news', '', 'NukeViet-news', '', '', 0, 1, 1, 0, 'viewcat_page_new', 3, '5,6,7', 1, 3, '2', '', '', 1277690451, 1277690451, '6') ");
	$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_cat VALUES (5, 4, 'Security issues', '', 'Security-issues', '', '', 0, 1, 2, 1, 'viewcat_page_new', 0, '', 1, 3, '2', '', '', 1277690497, 1277690564, '6') ");
	$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_cat VALUES (6, 4, 'Release notes', '', 'Release-notes', '', '', 0, 2, 3, 1, 'viewcat_page_new', 0, '', 1, 3, '2', '', '', 1277690588, 1277690588, '6') ");
	$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_cat VALUES (7, 4, 'Development team talk', '', 'Development-team-talk', '', '', 0, 3, 4, 1, 'viewcat_page_new', 0, '', 1, 3, '2', '', '', 1277690652, 1277690652, '6') ");
	$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_cat VALUES (8, 0, 'NukeViet community', '', 'NukeViet-community', '', '', 0, 3, 8, 0, 'viewcat_page_new', 3, '9,10,11', 1, 3, '2', '', '', 1277690748, 1277690748, '6') ");
	$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_cat VALUES (9, 8, 'Activities', '', 'Activities', '', '', 0, 1, 9, 1, 'viewcat_page_new', 0, '', 1, 3, '2', '', '', 1277690765, 1277690765, '6') ");
	$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_cat VALUES (10, 8, 'Events', '', 'Events', '', '', 0, 2, 10, 1, 'viewcat_page_new', 0, '', 1, 3, '2', '', '', 1277690783, 1277690783, '6') ");
	$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_cat VALUES (11, 8, 'Faces of week &#x3A;D', '', 'Faces-of-week-D', '', '', 0, 3, 11, 1, 'viewcat_page_new', 0, '', 1, 3, '2', '', '', 1277690821, 1277690821, '6') ");
	$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_cat VALUES (12, 0, 'Lastest technologies', '', 'Lastest-technologies', '', '', 0, 4, 12, 0, 'viewcat_page_new', 2, '13,14', 1, 3, '2', '', '', 1277690888, 1277690888, '6') ");
	$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_cat VALUES (13, 12, 'World wide web', '', 'World-wide-web', '', '', 0, 1, 13, 1, 'viewcat_page_new', 0, '', 1, 3, '2', '', '', 1277690934, 1277690934, '6') ");
	$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_cat VALUES (14, 12, 'Around internet', '', 'Around-internet', '', '', 0, 2, 14, 1, 'viewcat_page_new', 0, '', 1, 3, '2', '', '', 1277690982, 1277690982, '6') ");

	$db->query ("INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_rows VALUES (1, 1, '1,7,8', 0, 8, 'VINADES', 0, 1277689959, 1277690410, 1, 1277689920, 0, 2, 'Invite to co-operate announcement', 'Invite-to-co-operate-announcement', 'VINADES.,JSC was founded in order to professionalize NukeViet opensource development and release. We also using NukeViet in our bussiness projects to make it continue developing. Include Advertisment, provide hosting services for NukeViet CMS development.', 'hoptac.jpg', '', 1, 1, '6', 1, 2, 0, 0, 0) ");
	$db->query ("INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_rows VALUES (2, 14, '14,8', 0, 8, '', 1, 1277691366, 1277691470, 1, 1277691360, 0, 2, 'What does WWW mean?', 'What-does-WWW-mean', 'The World Wide Web, abbreviated as WWW and commonly known as the Web, is a system of interlinked hypertext&nbsp; documents accessed via the Internet.', 'nukeviet3.jpg', 'NukeViet 3.0', 1, 1, 2, 1, 0, 0, 0, 0) ");
	$db->query ("INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_rows VALUES (3, 12, '12,7', 0, 8, '', 2, 1277691851, 1287160943, 1, 1277691840, 0, 2, 'HTML 5 review', 'HTML-5-review', 'I have to say that my money used to be on XHTML 2.0 eventually winning the battle for the next great web standard. Either that, or the two titans would continue to battle it out for the forseable future, leading to an increasingly fragmented web.', 'screenshot.jpg', '',1, 1, '6', 1, 2, 0, 0, 0) ");
	$db->query ("INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_rows VALUES (4, 4, '4', 0, 1, 'VOVNews&#x002F;VNA', 0, 1292959020, 1292959513, 1, 1292959020, 0, 2, 'First open-source company starts operation', 'First-open-source-company-starts-operation', 'The Vietnam Open Source Development Joint Stock Company (VINADES.,JSC), the first firm operating in the field of open source in the country, made its debut on February 25.', 'nangly.jpg', '', 1, 1, '6', 1, 1, 0, 0, 0) ");
	$db->query ("INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_rows VALUES (5, 4, '4', 0, 1, '', 0, 1292959490, 1292959664, 1, 1292959440, 0, 2, 'NukeViet 3.0 - New CMS for News site', 'NukeViet-30-New-CMS-for-News-site', 'NukeViet 3.0 is a professional system: VINADES.,JSC founded to maintain and improve NukeViet 3.0 features. VINADES.,JSC co-operated with many professional hosting providers to test compatibility issues.', 'nukeviet3.jpg', '', 1, 1, '6', 1, 1, 0, 0, 0) ");

	$bodyhtml = "<p> <span style=\"color: black;\"><span style=\"color: black;\"><font size=\"2\"><span style=\"font-family: verdana,sans-serif;\">VIETNAM OPEN SOURCE DEVELOPMENT COMPANY (VINADES.,JSC)<br /> Head office: Room 1805 – CT2 Nang Huong building, 583 Nguyen Trai street, Hanoi, Vietnam.<br /> Mobile: (+84)4 8587 2007<br /> Fax: (+84) 4 3550 0914<br /> Website: <a f8f55ee40942436149=\"true\" href=\"http://www.vinades.vn/\" target=\"_blank\">www.vinades.vn</a> - <a f8f55ee40942436149=\"true\" href=\"http://www.nukeviet.vn/\" target=\"_blank\">www.nukeviet.vn</a></span></font></span></span></p><div h4f82558737983=\"nukeviet.vn\" style=\"display: inline; cursor: pointer; padding-right: 16px; width: 16px; height: 16px;\"> <span style=\"color: black;\"><span style=\"color: black;\"><font size=\"2\"><span style=\"font-family: verdana,sans-serif;\">&nbsp;</span></font></span></span></div><br /><p> <span style=\"color: black;\"><span style=\"color: black;\"><font size=\"2\"><span style=\"font-family: verdana,sans-serif;\">Email: <a href=\"mailto:contact@vinades.vn\" target=\"_blank\">contact@vinades.vn</a><br /> <br /> <br /> Dear valued customers and partners,<br /> <br /> VINADES.,JSC was founded in order to professionalize NukeViet opensource development and release. We also using NukeViet in our bussiness projects to make it continue developing.<br /> <br /> NukeViet is a Content Management System (CMS). 1st general purpose CMS developed by Vietnamese community. It have so many pros. Ex: Biggest community in VietNam, pure Vietnamese, easy to use, easy to develop...<br /> <br /> NukeViet 3 is lastest version of NukeViet and it still developing but almost complete with many advantage features.<br /> <br /> With respects to invite hosting - domain providers, and all company that pay attension to NukeViet in bussiness co-operate.<br /> <br /> Co-operate types:<br /> <br /> 1. Website advertisement, banners exchange, links:<br /> a. Description:<br /> Website advertising &amp; communication channels.<br /> On each release version of NukeViet.<br /> b. Benefits:<br /> Broadcast to all end users on both side.<br /> Reduce advertisement cost.<br /> c. Warranties:<br /> Place advertisement banner of partners on both side.<br /> Open sub-forum at NukeViet.VN to support end users who using hosting services providing by partners.<br /> <br /> 2. Provide host packet for NukeViet development testing purpose:<br /> <br /> a. Description:<br /> Sign the contract and agreements.<br /> Partners provide all types of hosting packet for VINADES.,JSC. Each type at least 1 re-sale packet.<br /> VINADES.,JSC provide an certificate verify host providing by partner compartable with NukeViet.<br /> b. Benefits:<br /> Expand market.<br /> Reduce cost, improve bussiness value.<br /> c. Warranties:<br /> Partner provide free hosting packet for VINADES.,JSC to test NukeViet compatibility.<br /> VINADES.JSC annoucement tested result to community.<br /> <br /> 3. Support end users:<br /> a. Description:<br /> Co-operate to solve problem of end user.<br /> Partners send end user requires about NukeViet CMS to VINADES.,JSC. VINADES also send user requires about hosting services to partners.<br /> b. Benefits:<br /> Reduce cost, human resources to support end users.<br /> Support end user more effective.<br /> c. Warranties:<br /> Solve end user requires as soon as possible.<br /> <br /> 4. Other types:<br /> Besides, as a publisher of NukeViet CMS, we also place advertisements on software user interface, sample articles in each release version. With thousands of downloaded hits each release version, we believe that it is the most effective advertisement type to webmasters.<br /> If partners have any ideas about new co-operate types. You are welcome and feel free to send specifics to us. Our slogan is &quot;Co-operate for development&quot;.<br /> <br /> We look forward to co-operating with you.<br /> <br /> Sincerely,<br /> <br /> VINADES.,JSC</span></font></span></span></p>";
	$sth = $db->prepare( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_bodyhtml_1 VALUES (1, :bodyhtml, '', 2, 0, 1, 1, 1, 0) ");
	$sth->bindParam( ':bodyhtml', $bodyhtml, PDO::PARAM_STR, strlen( $bodyhtml ) );
	$sth->execute();

	$bodyhtml = "<p> With a web browser, one can view web pages&nbsp; that may contain text, images, videos, and other multimedia&nbsp; and navigate between them by using hyperlinks. Using concepts from earlier hypertext systems, British engineer and computer scientist Sir Tim Berners-Lee, now the Director of the World Wide Web Consortium, wrote a proposal in March 1989 for what would eventually become the World Wide Web. He was later joined by Belgian computer scientist Robert Cailliau while both were working at CERN in Geneva, Switzerland. In 1990, they proposed using &quot;HyperText to link and access information of various kinds as a web of nodes in which the user can browse at will&quot;, and released that web in December.<br /> <br /> &quot;The World-Wide Web (W3) was developed to be a pool of human knowledge, which would allow collaborators in remote sites to share their ideas and all aspects of a common project.&quot;. If two projects are independently crea-ted, rather than have a central figure make the changes, the two bodies of information could form into one cohesive piece of work.</p><p> For more detail. See <a href=\"http://en.wikipedia.org/wiki/World_Wide_Web\" target=\"_blank\">Wikipedia</a></p>";
	$sth = $db->prepare( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_bodyhtml_1 VALUES (2, :bodyhtml,'', 1, 0, 1, 1, 1, 0)" );
	$sth->bindParam( ':bodyhtml', $bodyhtml, PDO::PARAM_STR, strlen( $bodyhtml ) );
	$sth->execute();

	$bodyhtml = "<p> But now that the W3C has admitted defeat, and abandoned <span class=\"caps\">XHTML</span> 2.0, there’s now no getting away f-rom the fact that <span class=\"caps\">HTML</span> 5 is the future. As such, I’ve now spent some time taking a look at this emerging standard, and hope you’ll endulge my ego by taking a glance over my thoughts on the matter.</p><p> Before I get started though, I have to say that I’m very impressed by what I’ve seen. It’s a good set of standards that are being cre-ated, and I hope that they will gradually be adopted over the next few years.</p><h2> New markup</h2><p> <span class=\"caps\">HTML</span> 5 introduces some new markup elements to encourage better structure within documents. The most important of these is &lt;section&gt;, which is used to define a hierarchy within a document. Sections can be nested to define subsections, and each section can be broken up into &lt;header&gt; and &lt;footer&gt; areas.</p><p> The important thing about this addition is that it removes the previous dependancy on &lt;h1&gt;, &lt;h2&gt; and related tags to define structure. Within each &lt;section&gt;, the top level heading is always &lt;h1&gt;. You can use as many &lt;h1&gt; tags as you like within your content, so long as they are correctly nested within &lt;section&gt; tags.</p><p> There’s a plethora of other new tags, all of which seem pretty useful. The best thing about all of this, however, is that there’s no reason not to start using them right away. There’s a small piece of JavaScript that’s needed to make Internet Explorer behave, but aside f-rom that it’s all good. More details about this hack are available at <a href=\"http://www.diveintohtml5.org/\">http://www.diveintohtml5.org</a></p><h2> Easier media embedding</h2><p> <span class=\"caps\">HTML</span> 5 defines some new tags that will make it a lot easier to embed video and audio into pages. In the same way that images are embedded using &lt;img&gt; tags, so now can video and audio files be embedded using &lt;video&gt; and &lt;audio&gt;.</p><p> I don’t think than anyone is going to complain about these new features. They free us f-rom relying on third-party plugins, such as Adobe Flash, for such simple activities such as playing video.</p><p> Unfortunately, due to some annoying licensing conditions and a lack of support for the open-source Theora codec, actually using these tags at the moment requires that videos are encoded in two different formats. Even then, you’ll still need to still provide an Adobe Flash fallback for Internet Explorer.</p><p> You’ll need to be pretty devoted to <span class=\"caps\">HTML</span> 5 to use these tags yet…</p><h2> Relaxed markup rules</h2><p> This is one thorny subject. You know how we’ve all been so good recently with our well-formed <span class=\"caps\">XHTML</span>, quoting those attributes and closing those tags? Now there’s no need to, apparently…</p><p> On the surface, this seems like a big step backwards into the bad days of tag soup. However, if you dig deeper, the reasoning behind this decision goes something like this:</p><ol> <li> It’s unnacceptable to crash out an entire <span class=\"caps\">HTML</span> page just because of a simple <span class=\"caps\">XML</span> syntax error.</li> <li> This means that browsers cannot use an <span class=\"caps\">XML</span> parser, and must instead use a HTML-aware fault-tolerant parser.</li> <li> For consistency, all browsers should handle any such “syntax errors” (such as unquoted attributes and unclosed tags), in the same way.</li> <li> If all browsers are behaving in the same way, then unquoted attributes and unclosed tags are not really syntax errors any more. In fact, by leaving them out of our pages, we can save a few bytes!</li></ol><p> This isn’t to say that you have to throw away those <span class=\"caps\">XHTML</span> coding habits. It’s still all valid <span class=\"caps\">HTML</span> 5. In fact, if you really want to be strict, you can set a different content-type header to enforce well-formed <span class=\"caps\">XHTML</span>. But for most people, we’ll just carry on coding well-formed <span class=\"caps\">HTML</span> with the odd typo, but no longer have to worry about clients screaming at us when the perfectly-rendered page doesn’t validate.</p><h2> So what now?</h2><p> The <span class=\"caps\">HTML</span> 5 specification is getting pretty close to stable, so it’s now safe to use bits of this new standard in your code. How much you use is entirely a personal choice. However, we should all get used to the new markup over the next few years, because <span class=\"caps\">HTML</span> 5 is assuredly here to stay.</p><p> Myself, I’ll be switching to the new doctype and using the new markup for document sections in my code. This step involves very little effort and does a good job of showing support for the new specification.</p><p> The new media tags are another matter. Until all platforms support a single video format, it’s simply not sustainable to be transcoding all videos into two filetypes. When this is coupled with having to provide a Flash fallback, it all seems like a pretty poor return on investment.</p><p> These features will no doubt become more useable over the next few years, as newer browser take the place of old. One day, hopefully, we’ll be able write clean, semantic pages without having to worry about backwards-compatibility.</p><p> Part of this progress relies on web developers using these new standards in our pages. By adopting new technology, we show our support for the standards it represents and place pressure on browser vendors to adhere to those standards. It’s a bit of effort in the short term, but in the long term it will pay dividends.</p>', 'http://www.etianen.com/blog/developers/2010/2/html-5-review/";
	$sth = $db->prepare( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_bodyhtml_1 VALUES (3, :bodyhtml, '', 2, 0, 1, 1, 1, 0)" );
	$sth->bindParam( ':bodyhtml', $bodyhtml, PDO::PARAM_STR, strlen( $bodyhtml ) );
	$sth->execute();

	$bodyhtml = "<p> <span>The Hanoi-based company will further develop and popularise an open source content management system best known as NukeViet in the country. </span></p><p> <span>VINADES Chairman Nguyen Anh Tu said NukeViet is totally free and users can download the product at www.nukeviet.vn. </span></p><p> <span>NukeViet has been widely used across the country over the past five years. The system, built on PHP-Nuke and MySQL database, enables users to easily post and manage files on the Internet or Intranet.</span></p>";
	$sth = $db->prepare( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_bodyhtml_1 VALUES (4, :bodyhtml, '', 0, 0, 1, 1, 1, 0)" );
	$sth->bindParam( ':bodyhtml', $bodyhtml, PDO::PARAM_STR, strlen( $bodyhtml ) );
	$sth->execute();

	$bodyhtml = "<p style=\"text-align: justify;\"> NukeViet also testing by many experienced webmasters to optimize system features. NukeViet&#039;s core team are programming enthusiasts. All of them want to make NukeViet become the best and most popular open source CMS.</p><p style=\"text-align: justify;\"> <strong>NukeViet 3.0 is a powerful system:</strong><br /> Learn by experiences f-rom NukeViet 2.0, NukeViet 3.0 build ground up on latest web technologies, allow you easily cre-ate portal, online news express, social network, e commerce system.<br /> NukeViet 3.0 can process huge amount of data. It was used by many companies, corporation&#039;s website with millions of news entries with high traffic.<br /> <br /> <strong>NukeViet 3.0 is easy to use system:</strong><br /> NukeViet allow you easily to customize and instantly use without any line of code. As developers, NukeViet help you build your own modules rapidly.</p><h2 style=\"text-align: justify;\"> NukeViet 3.0 features:</h2><p style=\"text-align: justify;\"> <strong>Technology bases:</strong><br /> NukeViet 3.0 using PHP 5 and MySQL 5 as main programming languages. XTemplate and jQuery for use Ajax f-rom system core.<br /> NukeViet 3.0 is fully validated with xHTML 1.0, CSS 2.1 and compatible with all major browsers.<br /> NukeViet 3.0 layout website using grid CSS framework like BluePrintCSS for design templates rapidly.<br /> <br /> NukeViet 3.0 has it own core libraries and it is platform independent. You can build your own modules with basic knowledge of PHP and MySQL.<br /> <br /> <strong>Module structure:</strong><br /> NukeViet 3.0 re-construct module structure. All module files packed into a particular folder. It&#039;s also define module block and module theme for layout modules in many ways.<br /> <br /> NukeViet 3.0 support modules can be multiply. We called it abstract modules. It help users automatic cre-ate many modules without any line of code f-rom any exists module which support cre-ate abstract modules.<br /> <br /> NukeViet 3.0 support automatic setup modules, blocks, themes f-rom Admin Control Panel. It&#039;s also allow you to share your modules by packed it into packets. NukeViet allow grant, deny access or even re-install, de-lete module.<br /> <br /> <strong>Multi language:</strong><br /> NukeViet 3 support multi languages in 2 types. Multi interface languages and multi database languages. It had features support administrators to build new languages. In NukeViet 3, admin language, user language, interface language, database language are separate for easily build multi languages systems.<br /> <br /> <strong>Right:</strong><br /> All manage features only access in admin area. NukeViet 3.0 allow grant access by module and language. It also allow cre-ate user groups and grant access modules by group.<br /> <br /> <strong>Themes:</strong><br /> NukeViet 3.0 support automatic install and uninstall themes. You can easily customize themes in module and module&#039;s functions. NukeViet store HTML, CSS code separately f-rom PHP code to help designers rapidly layout website.<br /> <br /> <strong>Customize website using blocks</strong><br /> A block can be a widget, advertisement pictures or any defined data. You can place block in many positions visually by drag and d-rop or argument it in Admin Control Panel.<br /> <br /> <strong>Securities:</strong><br /> NukeViet using security filters to filter data upload.<br /> Logging and control access f-rom many search engine as Google, Yahoo or any search engine.<br /> Anti spam using Captcha, anti flood data...<br /> NukeViet 3.0 has logging systems to log and track information about client to prevent attack.<br /> NukeViet 3.0 support automatic up-date to fix security issues or upgrade your website to latest version of NukeViet.<br /> <br /> <strong>Database:</strong><br /> You can backup database and download backup files to restore database to any point you restored your database.<br /> <br /> <strong>Control errors report</strong><br /> You can configure to display each type of error only one time. System then sent log files about this error to administrator via email.<br /> <br /> <strong>SEO:</strong><br /> Support SEO link<br /> Manage and customize website title<br /> Manage meta tag<br /> <br /> Support keywords for cre-ate statistic via search engine<br /> <br /> <strong>Prepared for integrate with third party application</strong><br /> NukeViet 3.0 has it own user database and many built-in methods to connect with many forum application. PHPBB or VBB can integrate and use with NukeViet 3.0 by single click.<br /> <br /> <strong>Distributed login</strong><br /> NukeViet support login by OpenID. Users can login to your website by accounts f-rom popular and well-known provider, such as Google, Yahoo or other OpenID providers. It help your website more accessible and reduce user&#039;s time to filling out registration forms.<br /> <br /> Download NukeViet 3.0: <a href=\"http://code.google.com/p/nuke-viet/downloads/list\">http://code.google.com/p/nuke-viet/downloads/list</a><br /> Website: <a href=\"http://nukeviet.vn/\">http://nukeviet.vn</a></p>";
	$sth = $db->prepare( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_bodyhtml_1 VALUES (5, :bodyhtml,'', 2, 0, 1, 1, 1, 0)");
	$sth->bindParam( ':bodyhtml', $bodyhtml, PDO::PARAM_STR, strlen( $bodyhtml ) );
	$sth->execute();
// news_bodytext
	$sth = $db->prepare( 'INSERT INTO ' . $db_config['prefix'] . '_' . $lang_data . '_news_bodytext VALUES ( :id, :bodytext )' );

	$bodytext = "VIETNAM OPEN SOURCE DEVELOPMENT COMPANY (VINADES.,JSC) Head office: Room 1805 – CT2 Nang Huong building, 583 Nguyen Trai street, Hanoi, Vietnam. Mobile: (+84)4 8587 2007 Fax: (+84) 4 3550 0914 Website: http://www.vinades.vn/ www.vinades.vn - http://www.nukeviet.vn/ www.nukeviet.vn Email: mailto:contact@vinades.vn contact@vinades.vn Dear valued customers and partners, VINADES.,JSC was founded in order to professionalize NukeViet opensource development and release. We also using NukeViet in our bussiness projects to make it continue developing. NukeViet is a Content Management System (CMS). 1st general purpose CMS developed by Vietnamese community. It have so many pros. Ex: Biggest community in VietNam, pure Vietnamese, easy to use, easy to develop... NukeViet 3 is lastest version of NukeViet and it still developing but almost complete with many advantage features. With respects to invite hosting - domain providers, and all company that pay attension to NukeViet in bussiness co-operate. Co-operate types: 1. Website advertisement, banners exchange, links: a. Description: Website advertising & communication channels. On each release version of NukeViet. b. Benefits: Broadcast to all end users on both side. Reduce advertisement cost. c. Warranties: Place advertisement banner of partners on both side. Open sub-forum at NukeViet.VN to support end users who using hosting services providing by partners. 2. Provide host packet for NukeViet development testing purpose: a. Description: Sign the contract and agreements. Partners provide all types of hosting packet for VINADES.,JSC. Each type at least 1 re-sale packet. VINADES.,JSC provide an certificate verify host providing by partner compartable with NukeViet. b. Benefits: Expand market. Reduce cost, improve bussiness value. c. Warranties: Partner provide free hosting packet for VINADES.,JSC to test NukeViet compatibility. VINADES.JSC annoucement tested result to community. 3. Support end users: a. Description: Co-operate to solve problem of end user. Partners send end user requires about NukeViet CMS to VINADES.,JSC. VINADES also send user requires about hosting services to partners. b. Benefits: Reduce cost, human resources to support end users. Support end user more effective. c. Warranties: Solve end user requires as soon as possible. 4. Other types: Besides, as a publisher of NukeViet CMS, we also place advertisements on software user interface, sample articles in each release version. With thousands of downloaded hits each release version, we believe that it is the most effective advertisement type to webmasters. If partners have any ideas about new co-operate types. You are welcome and feel free to send specifics to us. Our slogan is \"Co-operate for development\". We look forward to co-operating with you. Sincerely, VINADES.,JSC";
	$sth->bindValue( ':id', 1, PDO::PARAM_INT);
	$sth->bindParam( ':bodytext', $bodytext, PDO::PARAM_STR, strlen( $bodytext ) );
	$sth->execute();

	$bodytext = "With a web browser, one can view web pages that may contain text, images, videos, and other multimedia and navigate between them by using hyperlinks. Using concepts f-rom earlier hypertext systems, British engineer and computer scientist Sir Tim Berners-Lee, now the Director of the World Wide Web Consortium, wrote a proposal in March 1989 for what would eventually become the World Wide Web. He was later joined by Belgian computer scientist Robert Cailliau while both were working at CERN in Geneva, Switzerland. In 1990, they proposed using \"HyperText to link and access information of various kinds as a web of nodes in which the user can browse at will\", and released that web in December. \"The World-Wide Web (W3) was developed to be a pool of human knowledge, which would allow collaborators in remote sites to share their ideas and all aspects of a common project.\". If two projects are independently crea-ted, rather than have a central figure make the changes, the two bodies of information could form into one cohesive piece of work. For more detail. See http://en.wikipedia.org/wiki/World_Wide_Web Wikipedia";
	$sth->bindValue( ':id', 2, PDO::PARAM_INT);
	$sth->bindParam( ':bodytext', $bodytext, PDO::PARAM_STR, strlen( $bodytext ) );
	$sth->execute();

	$bodytext = "But now that the W3C has admitted defeat, and abandoned XHTML 2.0, there’s now no getting away f-rom the fact that HTML 5 is the future. As such, I’ve now spent some time taking a look at this emerging standard, and hope you’ll endulge my ego by taking a glance over my thoughts on the matter. Before I get started though, I have to say that I’m very impressed by what I’ve seen. It’s a good set of standards that are being cre-ated, and I hope that they will gradually be adopted over the next few years. New markup HTML 5 introduces some new markup elements to encourage better structure within documents. The most important of these is <section>, which is used to define a hierarchy within a document. Sections can be nested to define subsections, and each section can be broken up into <header> and <footer> areas. The important thing about this addition is that it removes the previous dependancy on <h1>, <h2> and related tags to define structure. Within each <section>, the top level heading is always <h1>. You can use as many <h1> tags as you like within your content, so long as they are correctly nested within <section> tags. There’s a plethora of other new tags, all of which seem pretty useful. The best thing about all of this, however, is that there’s no reason not to start using them right away. There’s a small piece of JavaScript that’s needed to make Internet Explorer behave, but aside f-rom that it’s all good. More details about this hack are available at http://www.diveintohtml5.org/ http://www.diveintohtml5.org Easier media embedding HTML 5 defines some new tags that will make it a lot easier to embed video and audio into pages. In the same way that images are embedded using <img> tags, so now can video and audio files be embedded using <video> and <audio>. I don’t think than anyone is going to complain about these new features. They free us f-rom relying on third-party plugins, such as Adobe Flash, for such simple activities such as playing video. Unfortunately, due to some annoying licensing conditions and a lack of support for the open-source Theora codec, actually using these tags at the moment requires that videos are encoded in two different formats. Even then, you’ll still need to still provide an Adobe Flash fallback for Internet Explorer. You’ll need to be pretty devoted to HTML 5 to use these tags yet… Relaxed markup rules This is one thorny subject. You know how we’ve all been so good recently with our well-formed XHTML, quoting those attributes and closing those tags? Now there’s no need to, apparently… On the surface, this seems like a big step backwards into the bad days of tag soup. However, if you dig deeper, the reasoning behind this decision goes something like this: It’s unnacceptable to crash out an entire HTML page just because of a simple XML syntax error. This means that browsers cannot use an XML parser, and must instead use a HTML-aware fault-tolerant parser. For consistency, all browsers should handle any such “syntax errors” (such as unquoted attributes and unclosed tags), in the same way. If all browsers are behaving in the same way, then unquoted attributes and unclosed tags are not really syntax errors any more. In fact, by leaving them out of our pages, we can save a few bytes! This isn’t to say that you have to throw away those XHTML coding habits. It’s still all valid HTML 5. In fact, if you really want to be strict, you can set a different content-type header to enforce well-formed XHTML. But for most people, we’ll just carry on coding well-formed HTML with the odd typo, but no longer have to worry about clients screaming at us when the perfectly-rendered page doesn’t validate. So what now? The HTML 5 specification is getting pretty close to stable, so it’s now safe to use bits of this new standard in your code. How much you use is entirely a personal choice. However, we should all get used to the new markup over the next few years, because HTML 5 is assuredly here to stay. Myself, I’ll be switching to the new doctype and using the new markup for document sections in my code. This step involves very little effort and does a good job of showing support for the new specification. The new media tags are another matter. Until all platforms support a single video format, it’s simply not sustainable to be transcoding all videos into two filetypes. When this is coupled with having to provide a Flash fallback, it all seems like a pretty poor return on investment. These features will no doubt become more useable over the next few years, as newer browser take the place of old. One day, hopefully, we’ll be able write clean, semantic pages without having to worry about backwards-compatibility. Part of this progress relies on web developers using these new standards in our pages. By adopting new technology, we show our support for the standards it represents and place pressure on browser vendors to adhere to those standards. It’s a bit of effort in the short term, but in the long term it will pay dividends";
	$sth->bindValue( ':id', 3, PDO::PARAM_INT);
	$sth->bindParam( ':bodytext', $bodytext, PDO::PARAM_STR, strlen( $bodytext ) );
	$sth->execute();

	$bodytext = "The Hanoi-based company will further develop and popularise an open source content management system best known as NukeViet in the country. VINADES Chairman Nguyen Anh Tu said NukeViet is totally free and users can download the product at www.nukeviet.vn. NukeViet has been widely used across the country over the past five years. The system, built on PHP-Nuke and MySQL database, enables users to easily post and manage files on the Internet or Intranet";
	$sth->bindValue( ':id', 4, PDO::PARAM_INT);
	$sth->bindParam( ':bodytext', $bodytext, PDO::PARAM_STR, strlen( $bodytext ) );
	$sth->execute();

	$bodytext = "NukeViet also testing by many experienced webmasters to optimize system features. NukeViet&#039;s core team are programming enthusiasts. All of them want to make NukeViet become the best and most popular open source CMS. NukeViet 3.0 is a powerful system: Learn by experiences f-rom NukeViet 2.0, NukeViet 3.0 build ground up on latest web technologies, allow you easily cre-ate portal, online news express, social network, e commerce system. NukeViet 3.0 can process huge amount of data. It was used by many companies, corporation&#039;s website with millions of news entries with high traffic. NukeViet 3.0 is easy to use system: NukeViet allow you easily to customize and instantly use without any line of code. As developers, NukeViet help you build your own modules rapidly. NukeViet 3.0 features: Technology bases: NukeViet 3.0 using PHP 5 and MySQL 5 as main programming languages. XTemplate and jQuery for use Ajax f-rom system core. NukeViet 3.0 is fully validated with xHTML 1.0, CSS 2.1 and compatible with all major browsers. NukeViet 3.0 layout website using grid CSS framework like BluePrintCSS for design templates rapidly. NukeViet 3.0 has it own core libraries and it is platform independent. You can build your own modules with basic knowledge of PHP and MySQL. Module structure: NukeViet 3.0 re-construct module structure. All module files packed into a particular folder. It&#039;s also define module block and module theme for layout modules in many ways. NukeViet 3.0 support modules can be multiply. We called it abstract modules. It help users automatic cre-ate many modules without any line of code f-rom any exists module which support cre-ate abstract modules. NukeViet 3.0 support automatic setup modules, blocks, themes f-rom Admin Control Panel. It&#039;s also allow you to share your modules by packed it into packets. NukeViet allow grant, deny access or even re-install, de-lete module. Multi language: NukeViet 3 support multi languages in 2 types. Multi interface languages and multi database languages. It had features support administrators to build new languages. In NukeViet 3, admin language, user language, interface language, database language are separate for easily build multi languages systems. Right: All manage features only access in admin area. NukeViet 3.0 allow grant access by module and language. It also allow cre-ate user groups and grant access modules by group. Themes: NukeViet 3.0 support automatic install and uninstall themes. You can easily customize themes in module and module&#039;s functions. NukeViet store HTML, CSS code separately f-rom PHP code to help designers rapidly layout website. Customize website using blocks A block can be a widget, advertisement pictures or any defined data. You can place block in many positions visually by drag and d-rop or argument it in Admin Control Panel. Securities: NukeViet using security filters to filter data upload. Logging and control access f-rom many search engine as Google, Yahoo or any search engine. Anti spam using Captcha, anti flood data... NukeViet 3.0 has logging systems to log and track information about client to prevent attack. NukeViet 3.0 support automatic up-date to fix security issues or upgrade your website to latest version of NukeViet. Database: You can backup database and download backup files to restore database to any point you restored your database. Control errors report You can configure to display each type of error only one time. System then sent log files about this error to administrator via email. SEO: Support SEO link Manage and customize website title Manage meta tag Support keywords for cre-ate statistic via search engine Prepared for integrate with third party application NukeViet 3.0 has it own user database and many built-in methods to connect with many forum application. PHPBB or VBB can integrate and use with NukeViet 3.0 by single click. Distributed login NukeViet support login by OpenID. Users can login to your website by accounts f-rom popular and well-known provider, such as Google, Yahoo or other OpenID providers. It help your website more accessible and reduce user&#039;s time to filling out registration forms. Download NukeViet 3.0: http://code.google.com/p/nuke-viet/downloads/list http://code.google.com/p/nuke-viet/downloads/list Website: http://nukeviet.vn/ http://nukeviet.vn')";
	$sth->bindValue( ':id', 5, PDO::PARAM_INT);
	$sth->bindParam( ':bodytext', $bodytext, PDO::PARAM_STR, strlen( $bodytext ) );
	$sth->execute();

	$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_block_cat (bid, adddefault, numbers, title, alias, image, description, weight, keywords, add_time, edit_time) VALUES (1, 0, 4,'Hot News', 'Hot-News', '', '', 1, '', 1279963759, 1279963759)" );
	$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_block_cat (bid, adddefault, numbers, title, alias, image, description, weight, keywords, add_time, edit_time) VALUES (2, 1, 4, 'Top News', 'Top-News', '', '', 2, '', 1279963766, 1279963766)" );

	$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_sources VALUES (1, 'Wikipedia', 'http://www.wikipedia.org', '', 1, 1277691366, 1277691366) ");
	$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_sources VALUES (2, 'Enlightened Website Development', 'http://www.etianen.com', '', 2, 1277691851, 1277691851)" );

	$sth = $db->prepare( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_tags (tid, numnews, alias, image, description, keywords) VALUES (?, ?, ?, ?, ?, ?)" );
	$sth->execute( array(1, 0, 'vinades', '', '', 'VINADES') );
	$sth->execute( array(2, 0, 'web', '', '', 'Web') );
	$sth->execute( array(3, 0, 'html5', '', '', 'HTML5') );
	$sth->execute( array(4, 0, 'nguyen-anh-tu', '', '', 'Nguyen Anh Tu') );
	$sth->execute( array(5, 0, 'nukeviet', '', '', 'NukeViet') );

	$sth = $db->prepare( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_news_tags_id (id, tid, keyword) VALUES (?, ?, ?)" );
	$sth->execute( array(1, 1, 'VINADES') );
	$sth->execute( array(2, 2, 'Web') );
	$sth->execute( array(3, 3, 'HTML5') );
	$sth->execute( array(4, 1, 'VINADES') );
	$sth->execute( array(4, 4, 'Nguyen Anh Tu') );
	$sth->execute( array(5, 5, 'NukeViet') );
	$sth->execute( array(5, 1, 'VINADES') );

	$db->query( "UPDATE " . $db_config['prefix'] . "_config SET config_value = " . $db->quote( $copyright ) . " WHERE module = 'news' AND config_name = 'copyright' AND lang='" . $lang_data . "'" );
	$db->query( "UPDATE " . $db_config['prefix'] . "_config SET config_value = 'news' WHERE module = 'global' AND config_name = 'site_home_module' AND lang='" . $lang_data . "'" );
}

$result = $db->query( "SELECT COUNT(*) FROM " . $db_config['prefix'] . "_" . $lang_data . "_modules where title='voting'" );
if( $result->fetchColumn() )
{
	$sth = $db->prepare( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_voting VALUES (:vid, :question, '', 1, 1, '6', 1275318563, 0, 1)" );
	$sth->bindValue( ':vid', 2, PDO::PARAM_INT);
	$sth->bindValue( ':question', 'Do you know about Nukeviet 3?', PDO::PARAM_STR );
	$sth->execute();

	$sth->bindValue( ':vid', 3, PDO::PARAM_INT);
	$sth->bindValue( ':question', 'What are you interested in open source?', PDO::PARAM_STR );
	$sth->execute();

	$sth = $db->prepare( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_voting_rows VALUES (?, ?, ?, ?, ?)" );
	$sth->execute( array(5, 2, 'A whole new sourcecode for the web.','' , 0) );
	$sth->execute( array(6, 2, 'Open source, free to use.','' , 0) );
	$sth->execute( array(7, 2, 'Use of xHTML, CSS and Ajax support','' , 0) );
	$sth->execute( array(8, 2, 'All the comments on','' , 0) );
	$sth->execute( array(9, 3, 'constantly improved, modified by the whole world.','' , 0) );
	$sth->execute( array(10, 3, 'To use the free of charge.','' , 0) );
	$sth->execute( array(11, 3, 'The freedom to explore, modify at will.','' , 0) );
	$sth->execute( array(12, 3, 'Match to learning and research because the freedom to modify at will.','', 0) );
	$sth->execute( array(13, 3, 'All comments on','', 0) );
}

$result = $db->query( "SELECT COUNT(*) FROM " . $db_config['prefix'] . "_" . $lang_data . "_modules where title='about'" );
if( $result->fetchColumn() )
{
	$sth = $db->prepare( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_about (id, title, alias, image, imagealt, description, bodytext, keywords, socialbutton, activecomm, facebookappid, layout_func, gid, weight, admin_id, add_time, edit_time, status) VALUES (1, 'Welcome to NukeViet 3.0', 'Welcome-to-NukeViet-3-0', '', '', '', :bodytext, '', 0, 4, '', '', 0, 1, 1, 1277266815, 1277266815, 1) ");
	$bodytext = "<p> NukeViet developed by Vietnamese and for Vietnamese. It&#039;s the 1st opensource CMS in Vietnam. Next generation of NukeViet, version 3.0 coding ground up. Support newest web technology, include xHTML, CSS 3, XTemplate, jQuery, AJAX...<br /> <br /> NukeViet&#039;s has it own core libraries build in. So, it&#039;s doesn&#039;t depend on other exists frameworks. With basic knowledge of PHP and MySQL, you can easily using NukeViet for your purposes.<br /> <br /> NukeViet 3 core is simply but powerful. It support modules can be multiply. We called it abstract modules. It help users automatic crea-te many modules without any line of code from any exists module which support crea-te abstract modules.<br /> <br /> NukeViet 3 support automatic setup modules, blocks, themes at Admin Control Panel. It&#039;s also allow you to share your modules by packed it into packets.<br /> <br /> NukeViet 3 support multi languages in 2 types. Multi interface languages and multi database langguages. Had features support web master to build new languages. Many advance features still developing. Let use it, distribute it and feel about opensource.<br /> <br /> At last, NukeViet 3 is a thanksgiving gift from VINADES.,JSC to community for all of your supports. And we hoping we going to be a biggest opensource CMS not only in VietNam, but also in the world. :).<br /> <br /> If you had any feedbacks and ideas for NukeViet 3 close beta. Feel free to send email to admin@nukeviet.vn. All are welcome<br /> <br /> Best regard.</p>";
	$sth->bindParam( ':bodytext', $bodytext, PDO::PARAM_STR, strlen( $bodyhtml ) );
	$sth->execute();

	$sth = $db->prepare( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_about (id, title, alias, image, imagealt, description, bodytext, keywords, socialbutton, activecomm, facebookappid, layout_func, gid, weight, admin_id, add_time, edit_time, status) VALUES (2, 'NukeViet&#039;s versioning schemes', 'NukeViet-s-versioning-schemes', '', '', '', :bodytext, '', 0, 4, '', '', 0, 2, 1, 1277267054, 1277693688, 1)" );
	$bodytext = "<p> NukeViet using 2 versioning schemes:<br /> <br /> I. By numbers (technical purposes):<br /> Structure for numbers is:<br /> major.minor.revision<br /> <br /> 1.Major: Major up-date. Probably not backwards compatible with older version.<br /> 2.Minor: Minor change, may introduce new features, but backwards compatibility is mostly retained.<br /> 3.Revision: Minor bug fixes. Packed for testing or pre-release purposes... Closed beta, open beta, RC, Official release.<br /> <br /> II: By names (new version release management)<br /> Main milestones: Closed beta, Open beta, Release candidate, Official.<br /> 1. Closed beta: Limited testing.<br /> characteristics: New features testing. It may not include in official version if doesn&#039;t accord with community. Closed beta&#039;s name can contain unique numbers. Ex: Closed beta 1, closed beta 2,... Features of previous release may not include in it&#039;s next release. Time release is announced by development team. This milestone stop when system haven&#039;t any major changes.<br /> Purposes: Pre-release version to receive feedbacks and ideas from community. Bug fixes for release version.<br /> Release to: Programmers, expert users.<br /> Supports:<br /> &nbsp;&nbsp;&nbsp; Using: None.<br /> &nbsp;&nbsp;&nbsp; Testing: Documents, not include manual.<br /> Upgrade: None.<br /> <br /> 2. Open beta: Public testing.<br /> characteristics: Features testing, contain full features of official version. It&#039;s almost include in official version even if it doesn&#039;t accord with community. This milestone start after closed beta milestone closed and release weekly to fix bugs. Open beta&#039;s name can contain unique numbers. Ex: Open beta 1, open beta 2,... Next release include all features of it&#039;s previous release. Open beta milestone stop when system haven&#039;t any critical issue.<br /> Purposes: Bug fixed which not detect in closed beta.<br /> Release to: All users of nukeviet.vn forum.<br /> Supports:<br /> &nbsp;&nbsp;&nbsp; Using: Limited. Manual and forum supports.<br /> &nbsp;&nbsp;&nbsp; Testing: Full.<br /> Upgrade: None.<br /> <br /> 3. Release Candidate:<br /> characteristics: Most stable version and prepare for official release. Release candidate&#039;s name can contain unique numbers.<br /> Ex: RC 1, RC 2,... by released number.<br /> If detect cretical issue in this milestone. Another Release Candidate version can be release sooner than release time announced by development team.<br /> Purposes: Reduce bugs of using official version.<br /> Release to: All people.<br /> Supports: Full.<br /> Upgrade: Yes.<br /> <br /> 4. Official:<br /> characteristics: 1st stable release of new version. It only using 1 time. Next release using numbers. Release about 2 weeks after Release Candidate milestone stoped.<br /> Purposes: Stop testing and recommend users using new version.</p>";
	$sth->bindParam( ':bodytext', $bodytext, PDO::PARAM_STR, strlen( $bodyhtml ) );
	$sth->execute();
}