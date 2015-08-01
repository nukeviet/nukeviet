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
$sth = $db->prepare( 'INSERT INTO ' . $db_config['prefix'] . '_' . $lang_data . '_modules (title, module_file, module_data, module_upload, custom_title, admin_title, set_time, main_file, admin_file, theme, mobile, description, keywords, groups_view, weight, act, admins, rss, gid) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)' );
$sth->execute( array('about', 'page', 'about', 'about', 'Giới thiệu', '', NV_CURRENTTIME, 1, 1, '', '', '', '', '6', 1, 1, '', 1, 0) );
$sth->execute( array('news', 'news', 'news', 'news', 'Tin Tức', '', NV_CURRENTTIME, 1, 1, '', '', '', '', '6', 2, 1, '', 1, 0) );
$sth->execute( array('users', 'users', 'users', 'users', 'Thành viên', 'Tài khoản', NV_CURRENTTIME, 1, 1, '', '', '', '', '6', 3, 1, '', 0, 0) );
$sth->execute( array('contact', 'contact', 'contact', 'contact', 'Liên hệ', '', NV_CURRENTTIME, 1, 1, '', '', '', '', '6', 4, 1, '', 0, 0) );
$sth->execute( array('statistics', 'statistics', 'statistics', 'statistics', 'Thống kê', '', NV_CURRENTTIME, 1, 1, '', '', '', 'truy cập, online, statistics', '2', 5, 1, '', 0, 0) );
$sth->execute( array('voting', 'voting', 'voting', 'voting', 'Thăm dò ý kiến', '', NV_CURRENTTIME, 1, 1, '', '', '', '', '6', 6, 1, '', 1, 0) );
$sth->execute( array('banners', 'banners', 'banners', 'banners', 'Quảng cáo', '', NV_CURRENTTIME, 1, 1 , '', '', '', '', '6', 7, 1, '', 0, 0) );
$sth->execute( array('seek', 'seek', 'seek', 'seek', 'Tìm kiếm', '', NV_CURRENTTIME, 1, 0, '', '', '', '', '6', 8, 1, '', 0, 0) );
$sth->execute( array('menu', 'menu', 'menu', 'menu', 'Menu Site', '', NV_CURRENTTIME, 0, 1, '', '', '', '', '6', 9, 1, '', 0, 0) );
$sth->execute( array('feeds', 'feeds', 'feeds', 'feeds', 'Rss Feeds', '', NV_CURRENTTIME, 1, 1, '', '', '', '', '6', 10, 1, '', 0, 0) );
$sth->execute( array('page', 'page', 'page', 'page', 'Page', '', NV_CURRENTTIME, 1, 1, '', '', '', '', '6', 11, 1, '', 1, 0) );
$sth->execute( array('comment', 'comment', 'comment', 'comment', 'Bình luận', 'Quản lý bình luận', NV_CURRENTTIME, 1, 1, '', '', '', '', '6', 12, 1, '', 0, 0) );
$sth->execute( array('siteterms', 'page', 'siteterms', 'siteterms', 'Điều khoản sử dụng', '', NV_CURRENTTIME, 1, 1, '', '', '', '', '6', 13, 1, '', 1, 0) );
$sth->execute( array('freecontent', 'freecontent', 'freecontent', 'freecontent', 'Giới thiệu sản phẩm', '', NV_CURRENTTIME, 0, 1, '', '', '', '', '6', 14, 1, '', 0, 0) );

$db->query( 'TRUNCATE TABLE ' . $db_config['prefix'] . '_' . $lang_data . '_modfuncs' );
$sth = $db->prepare( 'INSERT INTO ' . $db_config['prefix'] . '_' . $lang_data . '_modfuncs (func_id, func_name, alias, func_custom_name, in_module, show_func, in_submenu, subweight, setting) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)' );
$sth->execute( array(1, 'sitemap', 'sitemap', 'Sitemap', 'about', 0, 0, 0, '') );
$sth->execute( array(2, 'main', 'main', 'Main', 'about', 1, 0, 1, '') );
$sth->execute( array(3, 'sitemap', 'sitemap', 'Sitemap', 'news', 0, 0, 0, '') );
$sth->execute( array(5, 'content', 'content', 'Content', 'news', 1, 1, 3, '') );
$sth->execute( array(6, 'detail', 'detail', 'Detail', 'news', 1, 0, 4, '') );
$sth->execute( array(7, 'main', 'main', 'Main', 'news', 1, 0, 5, '') );
$sth->execute( array(9, 'print', 'print', 'Print', 'news', 0, 0, 0, '') );
$sth->execute( array(10, 'rating', 'rating', 'Rating', 'news', 0, 0, 0, '') );
$sth->execute( array(11, 'rss', 'rss', 'Rss', 'news', 1, 1, 1, '') );
$sth->execute( array(12, 'savefile', 'savefile', 'Savefile', 'news', 0, 0, 0, '') );
$sth->execute( array(13, 'search', 'search', 'Search', 'news', 1, 1, 6, '') );
$sth->execute( array(14, 'sendmail', 'sendmail', 'Sendmail', 'news', 0, 0, 0, '') );
$sth->execute( array(15, 'topic', 'topic', 'Topic', 'news', 1, 0, 7, '') );
$sth->execute( array(16, 'viewcat', 'viewcat', 'Viewcat', 'news', 1, 0, 8, '') );
$sth->execute( array(17, 'active', 'active', 'Active', 'users', 1, 1, 8, '') );
$sth->execute( array(18, 'changepass', 'changepass', 'Đổi mật khẩu', 'users', 1, 1, 6, '') );
$sth->execute( array(19, 'editinfo', 'editinfo', 'Editinfo', 'users', 1, 0, 10, '') );
$sth->execute( array(20, 'login', 'login', 'Đăng nhập', 'users', 1, 1, 2, '') );
$sth->execute( array(21, 'logout', 'logout', 'Logout', 'users', 1, 1, 3, '') );
$sth->execute( array(22, 'lostactivelink', 'lostactivelink', 'Lostactivelink', 'users', 1, 0, 9, '') );
$sth->execute( array(23, 'lostpass', 'lostpass', 'Quên mật khẩu', 'users', 1, 1, 5, '') );
$sth->execute( array(24, 'main', 'main', 'Main', 'users', 1, 0, 1, '') );
$sth->execute( array(25, 'openid', 'openid', 'Openid', 'users', 1, 1, 7, '') );
$sth->execute( array(26, 'register', 'register', 'Đăng ký', 'users', 1, 1, 4, '') );
$sth->execute( array(27, 'main', 'main', 'Main', 'contact', 1, 0, 1, '') );
$sth->execute( array(28, 'allbots', 'allbots', 'Máy chủ tìm kiếm', 'statistics', 1, 1, 6, '') );
$sth->execute( array(29, 'allbrowsers', 'allbrowsers', 'Theo trình duyệt', 'statistics', 1, 1, 4, '') );
$sth->execute( array(30, 'allcountries', 'allcountries', 'Theo quốc gia', 'statistics', 1, 1, 3, '') );
$sth->execute( array(31, 'allos', 'allos', 'Theo hệ điều hành', 'statistics', 1, 1, 5, '') );
$sth->execute( array(32, 'allreferers', 'allreferers', 'Theo đường dẫn đến site', 'statistics', 1, 1, 2, '') );
$sth->execute( array(33, 'main', 'main', 'Main', 'statistics', 1, 0, 1, '') );
$sth->execute( array(34, 'referer', 'referer', 'Đường dẫn đến site theo tháng', 'statistics', 1, 0, 7, '') );
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
$sth->execute( array(48, 'regroups', 'regroups', 'Nhóm thành viên', 'users', 1, 0, 11, '') );
$sth->execute( array(50, 'memberlist', 'memberlist', 'Danh sách thành viên', 'users', 1, 1, 12, '') );
$sth->execute( array(51, 'groups', 'groups', 'Groups', 'news', 1, 0, 9, '') );
$sth->execute( array(52, 'tag', 'tag', 'Tag', 'news', 1, 0, 2, '') );
$sth->execute( array(53, 'main', 'main', 'Main', 'page', 1, 0, 1, '') );
$sth->execute( array(54, 'main', 'main', 'main', 'comment', 1, 0, 1, '') );
$sth->execute( array(55, 'post', 'post', 'post', 'comment', 1, 0, 2, '') );
$sth->execute( array(56, 'like', 'like', 'Like', 'comment', 1, 0, 3, '') );
$sth->execute( array(57, 'delete', 'delete', 'Delete', 'comment', 1, 0, 4, '') );
$sth->execute( array(58, 'avatar', 'avatar', 'Avatar', 'users', 1, 0, 13, '') );
$sth->execute( array(59, 'oauth', 'oauth', 'Oauth', 'users', 0, 0, 0, '') );
$sth->execute( array(60, 'sitemap', 'sitemap', 'Sitemap', 'page', 0, 0, 0, '') );
$sth->execute( array(61, 'rss', 'rss', 'Rss', 'page', 0, 0, 0, '') );
$sth->execute( array(62, 'rss', 'rss', 'Rss', 'about', 0, 0, 0, '') );
$sth->execute( array(63, 'changequestion', 'changequestion', 'Thay đổi câu hỏi bảo mật', 'users', 1, 1, 14, '') );
$sth->execute( array(64, 'main', 'main', 'Main', 'siteterms', 1, 0, 1, '') );
$sth->execute( array(65, 'rss', 'rss', 'Rss', 'siteterms', 1, 0, 2, '') );
$sth->execute( array(66, 'sitemap', 'sitemap', 'Sitemap', 'siteterms', 0, 0, 0, ''));

$db->query( 'TRUNCATE TABLE ' . $db_config['prefix'] . '_' . $lang_data . '_modthemes' );
$sth = $db->prepare( 'INSERT INTO ' . $db_config['prefix'] . '_' . $lang_data . '_modthemes (func_id, layout, theme) VALUES (?, ?, ?)' );
$sth->execute( array(0, 'left-body-right', 'default') );
$sth->execute( array(2, 'left-body-right', 'default') );
$sth->execute( array(5, 'left-body-right', 'default') );
$sth->execute( array(6, 'left-body-right', 'default') );
$sth->execute( array(7, 'left-body-right', 'default') );
$sth->execute( array(11, 'left-body-right', 'default') );
$sth->execute( array(13, 'left-body-right', 'default') );
$sth->execute( array(15, 'left-body-right', 'default') );
$sth->execute( array(16, 'left-body-right', 'default') );
$sth->execute( array(17, 'left-body-right', 'default') );
$sth->execute( array(18, 'left-body-right', 'default') );
$sth->execute( array(19, 'left-body-right', 'default') );
$sth->execute( array(20, 'left-body-right', 'default') );
$sth->execute( array(21, 'left-body-right', 'default') );
$sth->execute( array(22, 'left-body-right', 'default') );
$sth->execute( array(23, 'left-body-right', 'default') );
$sth->execute( array(24, 'left-body-right', 'default') );
$sth->execute( array(25, 'left-body-right', 'default') );
$sth->execute( array(26, 'left-body-right', 'default') );
$sth->execute( array(27, 'left-body-right', 'default') );
$sth->execute( array(28, 'left-body', 'default') );
$sth->execute( array(29, 'left-body', 'default') );
$sth->execute( array(30, 'left-body', 'default') );
$sth->execute( array(31, 'left-body', 'default') );
$sth->execute( array(32, 'left-body', 'default') );
$sth->execute( array(33, 'left-body', 'default') );
$sth->execute( array(34, 'left-body', 'default') );
$sth->execute( array(36, 'left-body-right', 'default') );
$sth->execute( array(39, 'left-body-right', 'default') );
$sth->execute( array(42, 'left-body-right', 'default') );
$sth->execute( array(43, 'left-body-right', 'default') );
$sth->execute( array(46, 'left-body-right', 'default') );
$sth->execute( array(47, 'left-body-right', 'default') );
$sth->execute( array(48, 'left-body-right', 'default') );
$sth->execute( array(35, 'left-body-right', 'default') );
$sth->execute( array(50, 'left-body-right', 'default') );
$sth->execute( array(51, 'left-body-right', 'default') );
$sth->execute( array(52, 'left-body-right', 'default') );
$sth->execute( array(53, 'body', 'default') );
$sth->execute( array(54, 'left-body-right', 'default') );
$sth->execute( array(55, 'left-body-right', 'default') );
$sth->execute( array(56, 'left-body-right', 'default') );
$sth->execute( array(57, 'left-body-right', 'default') );
$sth->execute( array(58, 'left-body-right', 'default') );
$sth->execute( array(63, 'left-body-right', 'default') );
$sth->execute( array(64, 'left-body-right', 'default') );
$sth->execute( array(65, 'left-body-right', 'default') );

$sth->execute( array(0, 'body', 'mobile_default') );
$sth->execute( array(2, 'body', 'mobile_default') );
$sth->execute( array(5, 'body', 'mobile_default') );
$sth->execute( array(6, 'body', 'mobile_default') );
$sth->execute( array(7, 'body', 'mobile_default') );
$sth->execute( array(13, 'body', 'mobile_default') );
$sth->execute( array(15, 'body', 'mobile_default') );
$sth->execute( array(16, 'body', 'mobile_default') );
$sth->execute( array(17, 'body', 'mobile_default') );
$sth->execute( array(18, 'body', 'mobile_default') );
$sth->execute( array(19, 'body', 'mobile_default') );
$sth->execute( array(20, 'body', 'mobile_default') );
$sth->execute( array(21, 'body', 'mobile_default') );
$sth->execute( array(22, 'body', 'mobile_default') );
$sth->execute( array(23, 'body', 'mobile_default') );
$sth->execute( array(24, 'body', 'mobile_default') );
$sth->execute( array(25, 'body', 'mobile_default') );
$sth->execute( array(26, 'body', 'mobile_default') );
$sth->execute( array(27, 'body', 'mobile_default') );
$sth->execute( array(28, 'body', 'mobile_default') );
$sth->execute( array(29, 'body', 'mobile_default') );
$sth->execute( array(30, 'body', 'mobile_default') );
$sth->execute( array(31, 'body', 'mobile_default') );
$sth->execute( array(32, 'body', 'mobile_default') );
$sth->execute( array(33, 'body', 'mobile_default') );
$sth->execute( array(34, 'body', 'mobile_default') );
$sth->execute( array(35, 'body', 'mobile_default') );
$sth->execute( array(36, 'body', 'mobile_default') );
$sth->execute( array(39, 'body', 'mobile_default') );
$sth->execute( array(42, 'body', 'mobile_default') );
$sth->execute( array(43, 'body', 'mobile_default') );
$sth->execute( array(46, 'body', 'mobile_default') );
$sth->execute( array(47, 'body', 'mobile_default') );
$sth->execute( array(48, 'body', 'mobile_default') );
$sth->execute( array(50, 'body', 'mobile_default') );
$sth->execute( array(51, 'body', 'mobile_default') );
$sth->execute( array(52, 'body', 'mobile_default') );
$sth->execute( array(53, 'body', 'mobile_default') );
$sth->execute( array(54, 'body', 'mobile_default') );
$sth->execute( array(55, 'body', 'mobile_default') );
$sth->execute( array(56, 'body', 'mobile_default') );
$sth->execute( array(57, 'body', 'mobile_default') );
$sth->execute( array(63, 'body', 'mobile_default') );
$sth->execute( array(64, 'body', 'mobile_default') );
$sth->execute( array(65, 'body', 'mobile_default') );

$db->query( 'TRUNCATE TABLE ' . $db_config['prefix'] . '_' . $lang_data . '_blocks_groups' );
$sth = $db->prepare( 'INSERT INTO ' . $db_config['prefix'] . '_' . $lang_data . '_blocks_groups (bid, theme, module, file_name, title, link, template, position, exp_time, active, hide_device, groups_view, all_func, weight, config) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)' );
$sth->execute( array(1, 'default', 'news', 'global.block_category.php', 'Chủ đề', '', 'no_title', '[LEFT]', 0, 1, 0, '6', 1, 1, 'a:2:{s:5:"catid";i:0;s:12:"title_length";i:25;}') );
$sth->execute( array(2, 'default', 'statistics', 'global.counter_button.php', 'Online button', '', 'no_title', '[QR_CODE]', 0, 1, 0, '6', 1, 2, '') );
$sth->execute( array(3, 'default', 'banners', 'global.banners.php', 'Quảng cáo trái', '', 'no_title', '[LEFT]', 0, 1, 0, '6', 1, 3, 'a:1:{s:12:"idplanbanner";i:2;}') );
$sth->execute( array(4, 'default', 'about', 'global.about.php', 'Giới thiệu', '', 'border', '[RIGHT]', 0, 1, 0, '6', 1, 1, '') );
$sth->execute( array(5, 'default', 'users', 'global.user_button.php', 'Đăng nhập thành viên', '', 'no_title', '[PERSONALAREA]', 0, 1, 0, '6', 1, 1, '') );
$sth->execute( array(6, 'default', 'voting', 'global.voting_random.php', 'Thăm dò ý kiến', '', 'primary', '[RIGHT]', 0, 1, 0, '6', 1, 3, '') );
$sth->execute( array(7, 'default', 'news', 'module.block_newscenter.php', 'Tin mới nhất', '', 'no_title', '[TOP]', 0, 1, 0, '6', 0, 1, 'a:3:{s:11:"showtooltip";i:1;s:16:"tooltip_position";s:6:"bottom";s:14:"tooltip_length";s:3:"150";}') );
$sth->execute( array(8, 'default', 'banners', 'global.banners.php', 'Quảng cáo giữa trang', '', 'no_title', '[TOP]', 0, 1, 0, '6', 1, 2, 'a:1:{s:12:"idplanbanner";i:1;}') );
$sth->execute( array(9, 'default', 'theme', 'global.company_info.php', 'Công ty chủ quản', '', 'simple', '[COMPANY_INFO]', 0, 1, 0, '6', 1, 1, 'a:17:{s:12:"company_name";s:58:"Công ty cổ phần phát triển nguồn mở Việt Nam";s:16:"company_sortname";s:12:"VINADES.,JSC";s:15:"company_regcode";s:0:"";s:16:"company_regplace";s:0:"";s:21:"company_licensenumber";s:0:"";s:22:"company_responsibility";s:0:"";s:15:"company_address";s:72:"Phòng 2004 - Tòa nhà CT2 Nàng Hương, 583 Nguyễn Trãi, Hà Nội";s:15:"company_showmap";i:1;s:20:"company_mapcenterlat";d:20.9845159999999992805896908976137638092041015625;s:20:"company_mapcenterlng";d:105.7954749999999961573848850093781948089599609375;s:14:"company_maplat";d:20.9845159999999992805896908976137638092041015625;s:14:"company_maplng";d:105.7954750000000103682396002113819122314453125;s:15:"company_mapzoom";i:17;s:13:"company_phone";s:56:"+84-4-85872007[+84485872007]|+84-904762534[+84904762534]";s:11:"company_fax";s:14:"+84-4-35500914";s:13:"company_email";s:18:"contact@vinades.vn";s:15:"company_website";s:17:"http://vinades.vn";}') );
$sth->execute( array(10, 'default', 'theme', 'global.QR_code.php', 'QR code', '', 'no_title', '[QR_CODE]', 0, 1, 0, '6', 1, 1, 'a:3:{s:5:"level";s:1:"M";s:15:"pixel_per_point";i:4;s:11:"outer_frame";i:1;}') );
$sth->execute( array(17, 'default', 'menu', 'global.bootstrap.php', 'Menu Site', '', 'no_title', '[MENU_SITE]', 0, 1, 0, '6', 1, 1, 'a:2:{s:6:"menuid";i:1;s:12:"title_length";i:20;}') );
$sth->execute( array(18, 'default', 'contact', 'global.contact_default.php', 'Contact Default', '', 'no_title', '[CONTACT_DEFAULT]', 0, 1, 0, '6', 1, 1, '') );
$sth->execute( array(19, 'default', 'theme', 'global.copyright.php', 'Copyright', '', 'no_title', '[FOOTER_SITE]', 0, 1, 0, '6', 1, 1, 'a:5:{s:12:"copyright_by";s:0:"";s:13:"copyright_url";s:0:"";s:9:"design_by";s:12:"VINADES.,JSC";s:10:"design_url";s:18:"http://vinades.vn/";s:13:"siteterms_url";s:'. ( 38 + strlen( NV_BASE_SITEURL ) ).':"' . NV_BASE_SITEURL . 'index.php?language=' . $lang_data . '&amp;nv=siteterms";}') );
$sth->execute( array(21, 'default', 'theme', 'global.social.php', 'Social icon', '', 'no_title', '[SOCIAL_ICONS]', 0, 1, 0, '6', 1, 1, 'a:4:{s:8:"facebook";s:32:"http://www.facebook.com/nukeviet";s:11:"google_plus";s:32:"https://www.google.com/+nukeviet";s:7:"youtube";s:37:"https://www.youtube.com/user/nukeviet";s:7:"twitter";s:28:"https://twitter.com/nukeviet";}') );
$sth->execute( array(22, 'default', 'theme', 'global.menu_footer.php', 'Các chuyên mục chính', '', 'simple', '[MENU_FOOTER]', 0, 1, 0, '6', 1, 1, 'a:1:{s:14:"module_in_menu";a:8:{i:0;s:5:"about";i:1;s:4:"news";i:2;s:5:"users";i:3;s:7:"contact";i:4;s:6:"voting";i:5;s:7:"banners";i:6;s:4:"seek";i:7;s:5:"feeds";}}') );
$sth->execute( array(24, 'default', 'freecontent', 'global.free_content.php', 'Sản phẩm', '', 'no_title', '[FEATURED_PRODUCT]', 0, 1, 0, '6', 1, 1, 'a:2:{s:7:"blockid";i:1;s:7:"numrows";i:2;}') );
$sth->execute( array(20, 'mobile_default', 'menu', 'global.metismenu.php', 'Menu Site', '', 'no_title', '[MENU_SITE]', 0, 1, 0, '6', 1, 1, 'a:2:{s:6:"menuid";i:1;s:12:"title_length";i:0;}') );
$sth->execute( array(23, 'mobile_default', 'page', 'global.html.php', 'Copyright', '', 'no_title', '[FOOTER_SITE]', 0, 1, 0, '6', 1, 1, 'a:1:{s:11:"htmlcontent";s:229:"<p class="footer">© Copyright NukeViet 4. All right reserved.</p><p>Powered by <a href="http://nukeviet.vn/" title="NukeViet CMS">NukeViet CMS</a>. Design by <a href="http://vinades.vn/" title="VINADES.,JSC">VINADES.,JSC</a></p>";}') );
// Thiết lập Block
$db->query( 'TRUNCATE TABLE ' . $db_config['prefix'] . '_' . $lang_data . '_blocks_weight' );

$array_funcid = array();
$array_funcid_mod = array();
$array_weight_block = array();

$func_result = $db->query( 'SELECT func_id, func_name, in_module FROM ' . $db_config['prefix'] . '_' . $lang_data . '_modfuncs WHERE show_func = 1 ORDER BY in_module ASC, subweight ASC' );
while( list( $func_id_i, $func_name, $in_module ) = $func_result->fetch( 3 ) )
{
	$array_funcid[] = $func_id_i;
	$array_funcid_mod[$in_module][$func_name] = $func_id_i;
}

$func_result = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $lang_data . '_blocks_groups ORDER BY theme ASC, position ASC, weight ASC' );
while( $row = $func_result->fetch() )
{
	if( $row['all_func']==1 )
	{
		$array_funcid_i = $array_funcid;
	}
	else
	{
		$array_funcid_i = $array_funcid_mod[$row['module']];

		$xml = simplexml_load_file( NV_ROOTDIR . '/themes/' . $row['theme'] . '/config.ini' );
		$blocks = $xml->xpath( 'setblocks/block' );
		for( $i = 0, $count = sizeof( $blocks ); $i < $count; ++$i )
		{
			$rowini = (array)$blocks[$i];
			if( $rowini['module'] == $row['module'] AND $rowini['file_name'] == $row['file_name'] )
			{
				$array_funcid_i = array();
				if( ! is_array( $rowini['funcs'] ) )
				{
					$rowini['funcs'] = array( $rowini['funcs'] );
				}
				foreach( $rowini['funcs'] as $_funcs_list )
				{
					list( $mod, $func_list ) = explode( ':', $_funcs_list );
					$func_array = explode( ',', $func_list );
					foreach( $func_array as $_func )
					{
						if( isset( $array_funcid_mod[$row['module']][$_func] ))
						{
							$array_funcid_i[] = $array_funcid_mod[$row['module']][$_func];
						}
					}
				}
				break;
			}
		}
	}

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

$disable_site_content = 'Vì lý do kỹ thuật website tạm ngưng hoạt động. Thành thật xin lỗi các bạn vì sự bất tiện này!';
$site_description = 'Chia sẻ thành công, kết nối đam mê';

$db->query( "UPDATE " . $db_config['prefix'] . "_config SET config_value = " . $db->quote( $site_description ) . " WHERE module = 'global' AND config_name = 'site_description' AND lang='vi'" );
$db->query( "UPDATE " . $db_config['prefix'] . "_config SET config_value = " . $db->quote( $disable_site_content ) . " WHERE module = 'global' AND config_name = 'disable_site_content' AND lang='vi'" );

$array_cron_name = array();
$array_cron_name['cron_online_expired_del'] = 'Xóa các dòng ghi trạng thái online đã cũ trong CSDL';
$array_cron_name['cron_dump_autobackup'] = 'Tự động lưu CSDL';
$array_cron_name['cron_auto_del_temp_download'] = 'Xóa các file tạm trong thư mục tmp';
$array_cron_name['cron_del_ip_logs'] = 'Xóa IP log files, Xóa các file nhật ký truy cập';
$array_cron_name['cron_auto_del_error_log'] = 'Xóa các file error_log quá hạn';
$array_cron_name['cron_auto_sendmail_error_log'] = 'Gửi email các thông báo lỗi cho admin';
$array_cron_name['cron_ref_expired_del'] = 'Xóa các referer quá hạn';
$array_cron_name['cron_siteDiagnostic_update'] = 'Cập nhật đánh giá site từ các máy chủ tìm kiếm';
$array_cron_name['cron_auto_check_version'] = 'Kiểm tra phiên bản NukeViet';
$array_cron_name['cron_notification_autodel'] = 'Xóa thông báo cũ';

$result = $db->query( 'SELECT id, run_func FROM ' . $db_config['prefix'] . '_cronjobs ORDER BY id ASC' );
while( list( $id, $run_func ) = $result->fetch( 3 ) )
{
	$cron_name = ( isset( $array_cron_name[$run_func] ) ) ? $array_cron_name[$run_func] : $run_func;
	$db->query( 'UPDATE ' . $db_config['prefix'] . '_cronjobs SET ' . $lang_data . '_cron_name = ' . $db->quote( $cron_name ) . ' WHERE id=' . $id );
}

$db->query( "UPDATE " . $db_config['prefix'] . "_config SET config_value = '" . $global_config['site_theme'] . "' WHERE lang = 'vi' AND module = 'global' AND config_name = 'site_theme'" );

$result = $db->query( "SELECT COUNT(*) FROM " . $db_config['prefix'] . "_" . $lang_data . "_modules where title='" . $global_config['site_home_module'] . "'" );
if( $result->fetchColumn() )
{
	$db->query( "UPDATE " . $db_config['prefix'] . "_config SET config_value = '" . $global_config['site_home_module'] . "' WHERE module = 'global' AND config_name = 'site_home_module' AND lang='" . $lang_data . "'" );
}

$result = $db->query( "SELECT COUNT(*) FROM " . $db_config['prefix'] . "_" . $lang_data . "_modules where title='voting'" );
if( $result->fetchColumn() )
{
	$sth = $db->prepare( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_voting VALUES (:vid, :question, '', 1, 1, '6', 1275318563, 0, 1)" );
	$sth->bindValue( ':vid', 2, PDO::PARAM_INT);
	$sth->bindValue( ':question', 'Bạn biết gì về NukeViet 3?', PDO::PARAM_STR );
	$sth->execute();

	$sth->bindValue( ':vid', 3, PDO::PARAM_INT);
	$sth->bindValue( ':question', 'Bạn quan tâm gì nhất ở mã nguồn mở?', PDO::PARAM_STR );
	$sth->execute();

	$sth = $db->prepare( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_voting_rows VALUES (?, ?, ?, ?, ?)" );
	$sth->execute( array(5, 2, 'Một bộ sourcecode cho web hoàn toàn mới.', '', 0) );
	$sth->execute( array(6, 2, 'Mã nguồn mở, sử dụng miễn phí.','', 0) );
	$sth->execute( array(7, 2, 'Sử dụng xHTML, CSS và hỗ trợ Ajax','', 0) );
	$sth->execute( array(8, 2, 'Tất cả các ý kiến trên','', 0) );
	$sth->execute( array(9, 3, 'Liên tục được cải tiến, sửa đổi bởi cả thế giới.','', 0) );
	$sth->execute( array(10, 3, 'Được sử dụng miễn phí không mất tiền.','', 0) );
	$sth->execute( array(11, 3, 'Được tự do khám phá, sửa đổi theo ý thích.','', 0) );
	$sth->execute( array(12, 3, 'Phù hợp để học tập, nghiên cứu vì được tự do sửa đổi theo ý thích.', '',0) );
	$sth->execute( array(13, 3, 'Tất cả các ý kiến trên','', 0) );
}

$result = $db->query( "SELECT COUNT(*) FROM " . $db_config['prefix'] . "_" . $lang_data . "_modules where title='about'" );
if( $result->fetchColumn() )
{
	$sth = $db->prepare( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_about (id, title, alias, image, imagealt, description, bodytext, keywords, socialbutton, activecomm, layout_func, gid, weight, admin_id, add_time, edit_time, status) VALUES (1, 'Giới thiệu về NukeViet 3.0', 'Gioi-thieu-ve-NukeViet-3-0', '', '', '', :bodytext, '', 0, 4, '', 0, 1, 1, 1275320174, 1275320174, 1)" );
	$bodytext = "<p> NukeViet 3.0 là thế hệ CMS hoàn toàn mới do người Việt phát triển. Lần đầu tiên ở Việt Nam, một bộ nhân mã nguồn mở được đầu tư bài bản và chuyên nghiệp cả về tài chính, nhân lực và thời gian. Kết quả là 100% dòng code của NukeViet được viết mới hoàn toàn, NukeViet 3 sử dụng xHTML, CSS với Xtemplate và jquery cho phép vận dụng Ajax uyển chuyển cả trong công nghệ nhân.</p><p> Tận dụng các thành tựu mã nguồn mở có sẵn nhưng NukeViet 3 vẫn đảm bảo rằng từng dòng code là được code tay (NukeViet 3 không sử dụng bất cứ một nền tảng (framework) nào). Điều này có nghĩa là NukeViet 3 hoàn toàn không phụ thuộc vào bất cứ framework nào trong quá trình phát triển của mình; Bạn hoàn toàn có thể đọc hiểu để tự lập trình trên NukeViet 3 nếu bạn biết PHP và MySQL (đồng nghĩa với việc NukeViet 3 hoàn toàn mở và dễ nghiên cứu cho bất cứ ai muốn tìm hiểu về code của NukeViet).</p><p style=\"text-align: justify;\"> Bộ nhân NukeViet 3 ngoài việc thừa hưởng sự đơn giản vốn có của NukeViet nhưng không vì thế mà quên nâng cấp mình. Hệ thống NukeViet 3 hỗ trợ công nghệ đa nhân module. Chúng tôi gọi đó là công nghệ ảo hóa module. Công nghệ này cho phép người sử dụng có thể khởi tạo hàng ngàn module một cách tự động mà không cần động đến một dòng code. Các module được sinh ra từ công nghệ này gọi là module ảo. Module ảo là module được nhân bản từ một module bất kỳ của hệ thống nukeviet nếu module đó cho phép tạo module ảo.</p><p style=\"text-align: justify;\"> NukeViet 3 cũng hỗ trợ việc cài đặt từ động 100% các module, block, theme từ Admin Control Panel, người sử dụng có thể cài module mà không cần làm bất cứ thao tác phức tạp nào. NukeViet 3 còn cho phép bạn đóng gói module để chia sẻ cho người khác.</p><p style=\"text-align: justify;\"> NukeViet 3 đa ngôn ngữ 100% với 2 loại: đa ngôn ngữ giao diện và đa ngôn ngữ database. NukeViet 3 có tính năng&nbsp; cho phép người quản trị tự xây dựng ngôn ngữ mới cho site. Cho&nbsp; phép đóng gói file ngôn ngữ để chia sẻ cho cộng đồng... câu chuyện về nukeviet 3 sẽ còn dài vì một loạt các tính năng cao cấp vẫn đang được phát triển. Hãy sử dụng và phổ biến NukeViet 3 để tự mình tận hưởng những thành quả mới nhất từ công nghệ web mã nguồn mở. Cuối cùng NukeViet 3 là món của của <a href=\"http://vinades.vn\" target=\"_blank\">VINADES.,JSC</a> gửi tới cộng đồng để cảm ơn cộng đồng đã ủng hộ thời gian qua, bây giờ NukeViet 3 được đưa trở lại cộng đồng để cộng đồng tiếp tục nuôi nấng và chăm sóc NukeViet lớn mạnh hơn.</p><p style=\"text-align: justify;\"> Mọi ý kiến và yêu cầu trợ giúp về NukeViet 3 các bạn có thể gửi lên diễn đàn NukeViet tại địa chỉ: <a href=\"http://nukeviet.vn/phpbb/\" target=\"_blank\">http://nukeviet.vn/phpbb/</a>. Việc giúp đỡ hoàn toàn miễn phí và mọi góp ý của bạn đều được hoan nghênh.</p> <div style=\"text-align: center;\"> <object height=\"400\" width=\"480\"><param name=\"movie\" value=\"//www.youtube.com/v/dG66RocXSeY?rel=0&amp;autoplay=1&amp;hl=pt_BR&amp;version=3\" /><param name=\"allowFullScreen\" value=\"true\" /><param name=\"allowscriptaccess\" value=\"always\" /><embed allowfullscreen=\"true\" allowscriptaccess=\"always\" height=\"400\" src=\"//www.youtube.com/v/dG66RocXSeY?rel=0&amp;autoplay=1&amp;hl=pt_BR&amp;version=3\" type=\"application/x-shockwave-flash\" width=\"480\"></embed></object>	<br /> Video clip Giới thiệu mã nguồn mở NukeViet trong bản tin Tiêu điểm của chương trình Xã hội thông tin<br /> (Đài truyền hình kỹ thuật số VTC) phát sóng lúc 20h chủ nhật, ngày 05-09-2010 trên VTC1</div>";
	$sth->bindParam( ':bodytext', $bodytext, PDO::PARAM_STR, strlen( $bodyhtml ) );
	$sth->execute();

	$sth = $db->prepare( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_about (id, title, alias, image, imagealt, description, bodytext, keywords, socialbutton, activecomm, layout_func, gid, weight, admin_id, add_time, edit_time, status) VALUES (2, 'Giới thiệu về công ty chuyên quản NukeViet', 'Gioi-thieu-ve-cong-ty-chuyen-quan-NukeViet', '', '', '', :bodytext, '', 0, 4, '', 0, 2, 1, 1275320224, 1275320224, 1)" );
	$bodytext = "<p style=\"text-align: justify;\"> <strong>Công ty cổ phần phát triển nguồn mở Việt Nam</strong> (VINADES.,JSC) là công ty mã nguồn mở đầu tiên của Việt Nam sở hữu riêng một mã nguồn mở nổi tiếng và đang được sử dụng ở hàng ngàn website lớn nhỏ trong mọi lĩnh vực.<br /> <br /> Ra đời từ hoạt động của tổ chức nguồn mở NukeViet (từ năm 2004) và chính thức được thành lập đầu 2010 tại Hà Nội, khi đó báo chí đã gọi VINADES.,JSC là &quot;Công ty mã nguồn mở đầu tiên tại Việt Nam&quot;.<br /> <br /> Ngay sau khi thành lập, VINADES.,JSC đã thành công trong việc xây dựng <strong><a href=\"http://nukeviet.vn/\" target=\"_blank\">NukeViet</a></strong> thành một <a href=\"http://nukeviet.vn/\" target=\"_blank\">mã nguồn mở</a> thuần Việt. Với khả năng mạnh mẽ, cùng các ưu điểm vượt trội về công nghệ, độ an toàn và bảo mật, NukeViet đã được hàng ngàn website lựa chọn sử dụng trong năm qua. Ngay khi ra mắt phiên bản mới năm 2010, NukeViet đã tạo nên hiệu ứng truyền thông chưa từng có trong lịch sử mã nguồn mở Việt Nam. Tiếp đó, năm 2011 Mã nguồn mở NukeViet đã giành giải thưởng Nhân tài đất Việt cho sản phẩm Công nghệ thông tin đã được ứng dụng rộng rãi.<br /></p><div style=\"text-align: center;\"> <object height=\"400\" width=\"480\"><param name=\"movie\" value=\"//www.youtube.com/v/ZOhu2bLE-eA?rel=0&amp;autoplay=1&amp;hl=pt_BR&amp;version=3\" /><param name=\"allowFullScreen\" value=\"true\" /><param name=\"allowscriptaccess\" value=\"always\" /><embed allowfullscreen=\"true\" allowscriptaccess=\"always\" height=\"400\" src=\"//www.youtube.com/v/ZOhu2bLE-eA?rel=0&amp;autoplay=1&amp;hl=pt_BR&amp;version=3\" type=\"application/x-shockwave-flash\" width=\"480\"></embed></object><br /> <strong>Video clip trao giải Nhân tài đất Việt 2011.</strong><br /> Sản phẩm &quot;Mã nguồn mở NukeViet&quot; đã nhận giải cao nhất (Giải ba, không có giải nhất, giải nhì) của Giải thưởng Nhân Tài Đất Việt 2011 ở lĩnh vực Công nghệ thông tin - Sản phẩm đã có ứng dụng rộng rãi.</div><p style=\"text-align: justify;\"><br /> Tự chuyên nghiệp hóa mình, thoát khỏi mô hình phát triển tự phát, công ty đã nỗ lực vươn mình ra thế giới và đang phấn đấu trở thành một trong những hiện tượng của thời &quot;dotcom&quot; ở Việt Nam.<br /> <br /> Để phục vụ hoạt động của công ty, công ty liên tục mở rộng và tuyển thêm nhân sự ở các vị trí: Lập trình viên, chuyên viên đồ họa, nhân viên kinh doanh... Hãy liên hệ ngay để gia nhập VINADES.,JSC và cùng chúng tôi trở thành một công ty phát triển nguồn mở thành công nhất Việt Nam.</p> <p>Nếu bạn có nhu cầu triển khai các hệ thống <a href=\"http://toasoandientu.vn\" target=\"_blank\">Tòa Soạn Điện Tử</a>, <a href=\"http://webnhanh.vn\" target=\"_blank\">phần mềm trực tuyến</a>, <a href=\"http://vinades.vn\" target=\"_blank\">thiết kế web</a> theo yêu cầu hoặc dịch vụ có liên quan, hãy liên hệ công ty chuyên quản NukeViet theo thông tin dưới đây:</p><p><strong><span style=\"font-family: Tahoma; color: rgb(255, 69, 0); font-size: 14px;\">CÔNG TY CỔ PHẦN PHÁT TRIỂN NGUỒN MỞ VIỆT NAM</span></strong><br /> <strong>VIET NAM OPEN SOURCE DEVELOPMENT JOINT STOCK COMPANY</strong> (<strong>VINADES.,JSC</strong>)<br />Website: <a href=\"http://vinades.vn/\">http://vinades.vn</a> | <a href=\"http://nukeviet.vn/\">http://nukeviet.vn</a> | <a href=\"http://webnhanh.vn/\">http://webnhanh.vn</a><br />Trụ sở: Phòng 2004 - Tòa nhà CT2 Nàng Hương, 583 Nguyễn Trãi, Hà Nội.<br /> - Tel: +84-4-85872007<br /> - Fax: +84-4-35500914<br /> - Email: <a href=\"mailto:contact@vinades.vn\">contact@vinades.vn</a></p>";
	$sth->bindParam( ':bodytext', $bodytext, PDO::PARAM_STR, strlen( $bodyhtml ) );
	$sth->execute();

	$db->query( "UPDATE " . $db_config['prefix'] . "_config SET config_value = '0' WHERE module = 'about' AND config_name = 'activecomm' AND lang='" . $lang_data . "'" );
}

$result = $db->query( "SELECT COUNT(*) FROM " . $db_config['prefix'] . "_" . $lang_data . "_modules where title='siteterms'" );
if( $result->fetchColumn() )
{
	$sth = $db->prepare( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_siteterms (id, title, alias, image, imagealt, description, bodytext, keywords, socialbutton, activecomm, layout_func, gid, weight, admin_id, add_time, edit_time, status) VALUES (1, 'Chính sách bảo mật Quyền riêng tư', 'Chinh-sach-bao-mat-Quyen-rieng-tu', '', '', '', :bodytext, '', 0, 4, '', 0, 1, 1, " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", 1)" );
	$bodytext = '<h2 style="text-align: justify;">Điều 1: Thu thập thông tin</h2><h3 style="text-align: justify;">1.1. Thu thập tự động:</h3><div style="text-align: justify;">Như mọi website hiện đại khác, Chúng tôi sẽ thu thập địa chỉ IP và các thông tin web tiêu chuẩn khác của bạn như: loại trình duyệt, các trang bạn truy cập trong quá trình sử dụng dịch vụ, thông tin về máy tính &amp; thiết bị mạng v.v… cho mục đích phân tích thông tin phục vụ việc bảo mật và giữ an toàn cho hệ thống.</div><h3 style="text-align: justify;">1.2. Thu thập từ các khai báo của chính bạn:</h3><div style="text-align: justify;">Các thông tin do bạn khai báo cho chúng tôi trong quá trình làm việc như: đăng ký tài khoản, liên hệ với chúng tôi... cũng sẽ được chúng tôi lưu trữ phục vụ công việc chăm sóc khách hàng sau này.</div><h3 style="text-align: justify;">1.3. Thu thập thông tin thông qua việc đặt cookies:</h3><p style="text-align: justify;">Như mọi website hiện đại khác, khi bạn truy cập website, chúng tôi (hoặc các công cụ theo dõi hoặc thống kê hoạt động của website do các đối tác cung cấp) sẽ đặt một số File dữ liệu gọi là Cookies lên đĩa cứng hoặc bộ nhớ máy tính của bạn.</p><p style="text-align: justify;">Một trong số những Cookies này có thể tồn tại lâu để thuận tiện cho bạn trong quá trình sử dụng, ví dụ như: lưu Email của bạn trong trang đăng nhập để bạn không phải nhập lại v.v…</p><h3 style="text-align: justify;">1.4. Thu thập và lưu trữ thông tin trong quá khứ:</h3><p style="text-align: justify;">Bạn có thể thay đổi thông tin cá nhân của mình bất kỳ lúc nào bằng cách sử dụng chức năng tương ứng. Tuy nhiên chúng tôi sẽ lưu lại những thông tin bị thay đổi để chống các hành vi xóa dấu vết gian lận.</p><h2 style="text-align: justify;"><br  />Điều 2: Lưu trữ &amp; Bảo vệ thông tin</h2><div style="text-align: justify;">Hầu hết các thông tin được thu thập sẽ được lưu trữ tại cơ sở dữ liệu của chúng tôi.<br  /><br  />Chúng tôi bảo vệ dữ liệu cá nhân của các bạn bằng các hình thức như: mật khẩu, tường lửa, mã hóa cùng các hình thức thích hợp khác và chỉ cấp phép việc truy cập và xử lý dữ liệu cho các đối tượng phù hợp, ví dụ chính bạn hoặc các nhân viên có trách nhiệm xử thông tin với bạn thông qua các bước xác định danh tính phù hợp.</div><h2 style="text-align: justify;"><br  />Điều 3: Sử dụng thông tin</h2><p style="text-align: justify;">Thông tin thu thập được sẽ được chúng tôi sử dụng để:</p><blockquote><p style="text-align: justify;">o Cung cấp các dịch vụ hỗ trợ &amp; chăm sóc khách hàng.</p><p style="text-align: justify;">o Thực hiện giao dịch thanh toán &amp; gửi các thông báo trong quá trình giao dịch.</p><p style="text-align: justify;">o Xử lý khiếu nại, thu phí &amp; giải quyết sự cố.</p><p style="text-align: justify;">o Ngăn chặn các hành vi có nguy cơ rủi ro, bị cấm hoặc bất hợp pháp và đảm bảo tuân thủ đúng chính sách “Thỏa thuận người dùng”.</p><p style="text-align: justify;">o Đo đạc, tùy biến &amp; cải tiến dịch vụ, nội dung và hình thức của website.</p><p style="text-align: justify;">o Gửi bạn các thông tin về chương trình Marketing, các thông báo &amp; chương trình khuyến mại.</p><p style="text-align: justify;">o So sánh độ chính xác của thông tin cá nhân của bạn trong quá trình kiểm tra với bên thứ ba.</p></blockquote><h2 style="text-align: justify;"><br  />Điều 4: Tiếp nhận thông tin từ các đối tác</h2><div style="text-align: justify;">Khi sử dụng các công cụ giao dịch và thanh toán thông qua internet, chúng tôi có thể tiếp nhận thêm các thông tin về bạn như địa chỉ username, Email, số tài khoản ngân hàng... Chúng tôi kiểm tra những thông tin này với cơ sở dữ liệu người dùng của mình nhằm xác nhận rằng bạn có phải là khách hàng của chúng tôi hay không nhằm giúp việc thực hiện các dịch vụ cho bạn được thuận lợi.<br  /><br  />Các thông tin tiếp nhận được sẽ được chúng tôi bảo mật như những thông tin mà chúng tôi thu thập được trực tiếp từ bạn.</div><h2 style="text-align: justify;"><br  />Điều 5: Chia sẻ thông tin với bên thứ ba</h2><p style="text-align: justify;">Chúng tôi sẽ không chia sẻ thông tin cá nhân, thông tin tài chính... của bạn cho các bên thứ 3 trừ khi được sự đồng ý của chính bạn hoặc khi chúng tôi buộc phải tuân thủ theo các quy định pháp luật hoặc khi có yêu cầu từ cơ quan công quyền có thẩm quyền.</p><h2 style="text-align: justify;"><br  />Điều 6: Thay đổi chính sách bảo mật</h2><p style="text-align: justify;">Chính sách Bảo mật này có thể thay đổi theo thời gian. Chúng tôi sẽ không giảm quyền của bạn theo Chính sách Bảo mật này mà không có sự đồng ý rõ ràng của bạn. Chúng tôi sẽ đăng bất kỳ thay đổi Chính sách Bảo mật nào trên trang này và, nếu những thay đổi này quan trọng, chúng tôi sẽ cung cấp thông báo nổi bật hơn (bao gồm, đối với một số dịch vụ nhất định, thông báo bằng email về các thay đổi của Chính sách Bảo mật).<br  />&nbsp;</p><p style="text-align: right;">Tham khảo từ website <a href="http://webnhanh.vn/vi/thiet-ke-web/detail/Chinh-sach-bao-mat-Quyen-rieng-tu-Privacy-Policy-2147/">webnhanh.vn</a></p>';
	$sth->bindParam( ':bodytext', $bodytext, PDO::PARAM_STR, strlen( $bodyhtml ) );
	$sth->execute();

	$db->query( "UPDATE " . $db_config['prefix'] . "_config SET config_value = '0' WHERE module = 'siteterms' AND config_name = 'activecomm' AND lang='" . $lang_data . "'" );
}

$result = $db->query( "SELECT COUNT(*) FROM " . $db_config['prefix'] . "_" . $lang_data . "_modules where title='freecontent'" );
if( $result->fetchColumn() )
{
	$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_freecontent_blocks (bid, title, description) VALUES(1, 'Sản phẩm', 'Sản phẩm của công ty cổ phần phát triển nguồn mở Việt Nam - VINADES.,JSC')" );
	$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_freecontent_rows (id, bid, title, description, link, target, image, start_time, end_time, status) VALUES (1, 1, 'Hệ quản trị nội dung NukeViet', '<ul>\n	<li>Giải thưởng Nhân tài đất Việt 2011, 10.000+ website đang sử dụng</li>\n	<li>Được Bộ GD&amp;ĐT khuyến khích sử dụng trong các cơ sở giáo dục</li>\n	<li>Bộ TT&amp;TT quy định ưu tiên sử dụng trong cơ quan nhà nước</li>\n</ul>', 'http://vinades.vn/vi/san-pham/nukeviet/', '_blank', 'nukeviet.jpg', " . NV_CURRENTTIME . ", 0, 1)" );
	$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_freecontent_rows (id, bid, title, description, link, target, image, start_time, end_time, status) VALUES (2, 1, 'Cổng thông tin doanh nghiệp', '<ul>\n	<li>Tích hợp bán hàng trực tuyến</li>\n	<li>Tích hợp các nghiệp vụ quản lý (quản lý khách hàng, quản lý nhân sự, quản lý tài liệu)</li>\n</ul>', 'http://vinades.vn/vi/san-pham/Cong-thong-tin-doanh-nghiep-NukeViet-portal/', '_blank', 'nukeviet-portal.jpg', " . NV_CURRENTTIME . ", 0, 1)" );
	$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_freecontent_rows (id, bid, title, description, link, target, image, start_time, end_time, status) VALUES (3, 1, 'Cổng thông tin Phòng giáo dục, Sở giáo dục', '<ul>\n	<li>Tích hợp chung website hàng trăm trường</li>\n	<li>Tích hợp các ứng dụng trực tuyến (Tra điểm SMS, Tra cứu văn bằng, Học bạ điện tử ...)</li>\n</ul>', 'http://vinades.vn/vi/san-pham/Cong-thong-tin-giao-duc-NukeViet-Edugate/', '_blank', 'nukeviet-edu.jpg', " . NV_CURRENTTIME . ", 0, 1)" );
	$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_freecontent_rows (id, bid, title, description, link, target, image, start_time, end_time, status) VALUES (4, 1, 'Tòa soạn báo điện tử chuyên nghiệp', '<ul>\n	<li>Bảo mật đa tầng, phân quyền linh hoạt</li>\n	<li>Hệ thống bóc tin tự động, đăng bài tự động, cùng nhiều chức năng tiên tiến khác...</li>\n</ul>', 'http://vinades.vn/vi/san-pham/Toa-soan-bao-dien-tu/', '_blank', 'nukeviet-toasoan.jpg', " . NV_CURRENTTIME . ", 0, 1)" );
}

$result = $db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_menu (id, title) VALUES (1, 'Top Menu')");
$result = $db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_menu_rows VALUES (1, 0, 1, 'Giới thiệu', '" . NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=about', '', '', 1, 1, 0, '2,3', '6', 'about', '', 1, '', 1, 1)" );
$result = $db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_menu_rows VALUES (2, 1, 1, 'Giới thiệu về NukeViet 3.0', '" . NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=about&amp;op=Gioi-thieu-ve-NukeViet-3-0" . $global_config['rewrite_exturl'] . "', '', '', 1, 2, 1, '', '6', 'about', 'Gioi-thieu-ve-NukeViet-3-0" . $global_config['rewrite_exturl'] . "', 1, '', 1, 1) " );
$result = $db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_menu_rows VALUES (3, 1, 1, 'Giới thiệu về công ty chuyên quản NukeViet', '" . NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=about&amp;op=Gioi-thieu-ve-cong-ty-chuyen-quan-NukeViet" . $global_config['rewrite_exturl'] . "', '', '', 2, 3, 1, '', '6', 'about', 'Gioi-thieu-ve-cong-ty-chuyen-quan-NukeViet" . $global_config['rewrite_exturl'] . "', 1, '', 1, 1) ");
$result = $db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_menu_rows VALUES (4, 0, 1, 'Tin Tức', '" . NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=news', '', '', 2, 4, 0, '5,6,7,8,30,31,32', '6', 'news', '', 1, '', 1, 1)" );
$result = $db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_menu_rows VALUES (5, 4, 1, 'Tin tức', '" . NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=news&amp;op=Tin-tuc', '', '', 1, 5, 1, '', '6', 'news', 'Tin-tuc', 1, '', 1, 1) ");
$result = $db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_menu_rows VALUES (6, 4, 1, 'Sản phẩm', '" . NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=news&amp;op=San-pham', '', '', 2, 6, 1, '', '6', 'news', 'San-pham', 1, '', 1, 1) ");
$result = $db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_menu_rows VALUES (7, 4, 1, 'Đối tác', '" . NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=news&amp;op=Doi-tac', '', '', 3, 7, 1, '', '6', 'news', 'Doi-tac', 1, '', 1, 1) ");
$result = $db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_menu_rows VALUES (8, 4, 1, 'Tuyển dụng', '" . NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=news&amp;op=Tuyen-dung', '', '', 4, 8, 1, '', '6', 'news', 'Tuyen-dung', 1, '', 1, 1) ");
$result = $db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_menu_rows VALUES (9, 0, 1, 'Thành viên', '" . NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=users', '', '', 3, 12, 0, '10,11,12,13,14,15,16', '6', 'users', '', 1, '', 1, 1)" );
$result = $db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_menu_rows VALUES (10, 9, 1, 'Đăng nhập', '" . NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=users&op=login', '', '', 1, 13, 1, '', '5', 'users', 'login', 1, '', 1, 1) ");
$result = $db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_menu_rows VALUES (11, 9, 1, 'Logout', '" . NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=users&op=logout', '', '', 2, 14, 1, '', '4', 'users', 'logout', 1, '', 1, 1) ");
$result = $db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_menu_rows VALUES (12, 9, 1, 'Đăng ký', '" . NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=users&op=register', '', '', 3, 15, 1, '', '5', 'users', 'register', 1, '', 1, 1) ");
$result = $db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_menu_rows VALUES (13, 9, 1, 'Quên mật khẩu', '" . NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=users&op=lostpass', '', '', 4, 16, 1, '', '5', 'users', 'lostpass', 1, '', 1, 1) ");
$result = $db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_menu_rows VALUES (14, 9, 1, 'Đổi mật khẩu', '" . NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=users&op=changepass', '', '', 5, 17, 1, '', '4', 'users', 'changepass', 1, '', 1, 1) ");
$result = $db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_menu_rows VALUES (15, 9, 1, 'Openid', '" . NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=users&op=openid', '', '', 6, 18, 1, '', '4', 'users', 'openid', 1, '', 1, 1) ");
$result = $db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_menu_rows VALUES (16, 9, 1, 'Danh sách thành viên', '" . NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=users&op=memberlist', '', '', 7, 19, 1, '', '4', 'users', 'memberlist', 1, '', 1, 1) ");
$result = $db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_menu_rows VALUES (17, 0, 1, 'Liên hệ', '" . NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=contact', '', '', 7, 28, 0, '18', '6', 'contact', '', 1, '', 1, 1)" );
$result = $db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_menu_rows VALUES (18, 17, 1, 'Webmaster', '" . NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=contact&amp;op=Webmaster', '', '', 1, 29, 1, '', '6', 'contact', '1', 1, '', 1, 1) ");
$result = $db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_menu_rows VALUES (19, 0, 1, 'Thống kê', '" . NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=statistics', '', '', 4, 20, 0, '20,21,22,23,24', '2', 'statistics', '', 1, '', 1, 1)" );
$result = $db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_menu_rows VALUES (20, 19, 1, 'Theo đường dẫn đến site', '" . NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=statistics&amp;op=allreferers', '', '', 1, 21, 1, '', '2', 'statistics', 'allreferers', 1, '', 1, 1) ");
$result = $db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_menu_rows VALUES (21, 19, 1, 'Theo quốc gia', '" . NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=statistics&amp;op=allcountries', '', '', 2, 22, 1, '', '2', 'statistics', 'allcountries', 1, '', 1, 1) ");
$result = $db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_menu_rows VALUES (22, 19, 1, 'Theo trình duyệt', '" . NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=statistics&amp;op=allbrowsers', '', '', 3, 23, 1, '', '2', 'statistics', 'allbrowsers', 1, '', 1, 1) ");
$result = $db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_menu_rows VALUES (23, 19, 1, 'Theo hệ điều hành', '" . NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=statistics&amp;op=allos', '', '', 4, 24, 1, '', '2', 'statistics', 'allos', 1, '', 1, 1) ");
$result = $db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_menu_rows VALUES (24, 19, 1, 'Máy chủ tìm kiếm', '" . NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=statistics&amp;op=allbots', '', '', 5, 25, 1, '', '2', 'statistics', 'allbots', 1, '', 1, 1) ");
$result = $db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_menu_rows VALUES (25, 0, 1, 'Thăm dò ý kiến', '" . NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=voting', '', '', 5, 26, 0, '', '6', 'voting', '', 1, '', 1, 1)" );
$result = $db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_menu_rows VALUES (30, 4, 1, 'Rss', '" . NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=news&op=rss', '', '', 5, 9, 1, '', '6', 'news', 'rss', 1, '', 0, 1)" );
$result = $db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_menu_rows VALUES (27, 0, 1, 'Tìm kiếm', '" . NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=seek', '', '', 6, 27, 0, '', '6', 'seek', '', 1, '', 1, 1) ");
$result = $db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_menu_rows VALUES (31, 4, 1, 'Đăng bài viết', '" . NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=news&op=content', '', '', 6, 10, 1, '', '6', 'news', 'content', 1, '', 0, 1)" );
$result = $db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $lang_data . "_menu_rows VALUES (32, 4, 1, 'Tìm kiếm', '" . NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=news&op=search', '', '', 7, 11, 1, '', '6', 'news', 'search', 1, '', 0, 1) ");