<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

if (! defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$my_lang['modules'] = array();
$my_lang['modules']['about'] = 'About';
$my_lang['modules']['about_for_acp'] = '';
$my_lang['modules']['news'] = 'News';
$my_lang['modules']['news_for_acp'] = '';
$my_lang['modules']['users'] = 'Users';
$my_lang['modules']['users_for_acp'] = 'Users';
$my_lang['modules']['contact'] = 'Contact';
$my_lang['modules']['contact_for_acp'] = '';
$my_lang['modules']['statistics'] = 'Statistics';
$my_lang['modules']['statistics_for_acp'] = '';
$my_lang['modules']['voting'] = 'Voting';
$my_lang['modules']['voting_for_acp'] = '';
$my_lang['modules']['banners'] = 'Banners';
$my_lang['modules']['banners_for_acp'] = '';
$my_lang['modules']['seek'] = 'Search';
$my_lang['modules']['seek_for_acp'] = '';
$my_lang['modules']['menu'] = 'Navigation Bar';
$my_lang['modules']['menu_for_acp'] = '';
$my_lang['modules']['comment'] = 'Comment';
$my_lang['modules']['comment_for_acp'] = '';
$my_lang['modules']['siteterms'] = 'Terms & Conditions';
$my_lang['modules']['siteterms_for_acp'] = '';
$my_lang['modules']['feeds'] = 'RSS-feeds';
$my_lang['modules']['Page'] = 'Page';
$my_lang['modules']['Page_for_acp'] = '';
$my_lang['modules']['freecontent'] = 'Introduction';
$my_lang['modules']['freecontent_for_acp'] = '';

$my_lang['modfuncs'] = array();
$my_lang['modfuncs']['users'] = array();
$my_lang['modfuncs']['users']['login'] = 'Login';
$my_lang['modfuncs']['users']['register'] = 'Register';
$my_lang['modfuncs']['users']['lostpass'] = 'Password recovery';
$my_lang['modfuncs']['users']['active'] = 'Active account';
$my_lang['modfuncs']['users']['editinfo'] = 'Account Settings';
$my_lang['modfuncs']['users']['memberlist'] = 'Members list';
$my_lang['modfuncs']['users']['logout'] = 'Logout';
$my_lang['modfuncs']['users']['groups'] = 'Group management';

$my_lang['modfuncs']['statistics'] = array();
$my_lang['modfuncs']['statistics']['allreferers'] = 'By referrers';
$my_lang['modfuncs']['statistics']['allcountries'] = 'By countries';
$my_lang['modfuncs']['statistics']['allbrowsers'] = 'By browsers ';
$my_lang['modfuncs']['statistics']['allos'] = 'By operating system';
$my_lang['modfuncs']['statistics']['allbots'] = 'By search engines';
$my_lang['modfuncs']['statistics']['referer'] = 'By month';

$my_lang['blocks_groups'] = array();
$my_lang['blocks_groups']['news'] = array();
$my_lang['blocks_groups']['news']['module.block_newscenter'] = 'Breaking news';
$my_lang['blocks_groups']['news']['global.block_category'] = 'Category';
$my_lang['blocks_groups']['news']['global.block_tophits'] = 'Top Hits';
$my_lang['blocks_groups']['banners'] = array();
$my_lang['blocks_groups']['banners']['global.banners1'] = 'Center Banner';
$my_lang['blocks_groups']['banners']['global.banners2'] = 'Left Banner';
$my_lang['blocks_groups']['banners']['global.banners3'] = 'Right Banner';
$my_lang['blocks_groups']['statistics'] = array();
$my_lang['blocks_groups']['statistics']['global.counter'] = 'Statistics';
$my_lang['blocks_groups']['about'] = array();
$my_lang['blocks_groups']['about']['global.about'] = 'About';
$my_lang['blocks_groups']['voting'] = array();
$my_lang['blocks_groups']['voting']['global.voting_random'] = 'Voting';
$my_lang['blocks_groups']['users'] = array();
$my_lang['blocks_groups']['users']['global.user_button'] = 'Member login';
$my_lang['blocks_groups']['theme'] = array();
$my_lang['blocks_groups']['theme']['global.company_info'] = 'Managing company';
$my_lang['blocks_groups']['theme']['global.menu_footer'] = 'Main categories';
$my_lang['blocks_groups']['freecontent'] = array();
$my_lang['blocks_groups']['freecontent']['global.free_content'] = 'Introduction';

$my_lang['cron'] = array();
$my_lang['cron']['cron_online_expired_del'] = 'Delete expired online status';
$my_lang['cron']['cron_dump_autobackup'] = 'Automatic backup database';
$my_lang['cron']['cron_auto_del_temp_download'] = 'Empty temporary files';
$my_lang['cron']['cron_del_ip_logs'] = 'Delete IP log files';
$my_lang['cron']['cron_auto_del_error_log'] = 'Delete expired error_log log files';
$my_lang['cron']['cron_auto_sendmail_error_log'] = 'Send error logs to admin';
$my_lang['cron']['cron_ref_expired_del'] = 'Delete expired referer';
$my_lang['cron']['cron_auto_check_version'] = 'Check NukeViet version';
$my_lang['cron']['cron_notification_autodel'] = 'Delete old notification';

$my_lang['groups']['NukeViet-Fans'] = 'NukeViet-Fans';
$my_lang['groups']['NukeViet-Admins'] = 'NukeViet-Admins';
$my_lang['groups']['NukeViet-Programmers'] = 'NukeViet-Programmers';

$my_lang['vinades_fullname'] = "Vietnam Open Source Development Joint Stock Company";
$my_lang['vinades_address'] = "Room 2004 – CT2 Nang Huong building, 583 Nguyen Trai street, Ha Dong, Hanoi, Vietnam";
$my_lang['nukeviet_description'] = 'Sharing success, connect passions';
$my_lang['disable_site_content'] = 'For technical reasons Web site temporary not available. we are very sorry for that inconvenience!';

$menu_rows_lev0['about'] = array(
    'title' => $my_lang['modules']['about'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=about",
    'groups_view' => '6',
    'op' => ''
);
$menu_rows_lev0['news'] = array(
    'title' => $my_lang['modules']['news'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=news",
    'groups_view' => '6',
    'op' => ''
);
$menu_rows_lev0['users'] = array(
    'title' => $my_lang['modules']['users'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=users",
    'groups_view' => '6',
    'op' => ''
);
$menu_rows_lev0['voting'] = array(
    'title' => $my_lang['modules']['voting'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=voting",
    'groups_view' => '6',
    'op' => ''
);
$menu_rows_lev0['contact'] = array(
    'title' => $my_lang['modules']['contact'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=contact",
    'groups_view' => '6',
    'op' => ''
);

$menu_rows_lev1['users'] = array();
$menu_rows_lev1['users'][] = array(
    'title' => $my_lang['modfuncs']['users']['login'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=users&op=login",
    'groups_view' => '5',
    'op' => 'login'
);
$menu_rows_lev1['users'][] = array(
    'title' => $my_lang['modfuncs']['users']['register'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=users&op=register",
    'groups_view' => '5',
    'op' => 'register'
);
$menu_rows_lev1['users'][] = array(
    'title' => $my_lang['modfuncs']['users']['lostpass'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=users&op=lostpass",
    'groups_view' => '5',
    'op' => 'lostpass'
);
$menu_rows_lev1['users'][] = array(
    'title' => $my_lang['modfuncs']['users']['editinfo'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=users&op=editinfo",
    'groups_view' => '4,7',
    'op' => 'editinfo'
);
$menu_rows_lev1['users'][] = array(
    'title' => $my_lang['modfuncs']['users']['memberlist'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=users&op=memberlist",
    'groups_view' => '4,7',
    'op' => 'memberlist'
);
$menu_rows_lev1['users'][] = array(
    'title' => $my_lang['modfuncs']['users']['logout'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=users&op=logout",
    'groups_view' => '4,7',
    'op' => 'logout'
);
