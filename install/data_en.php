<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

if (! defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$install_lang['modules'] = array();
$install_lang['modules']['about'] = 'About';
$install_lang['modules']['about_for_acp'] = '';
$install_lang['modules']['news'] = 'News';
$install_lang['modules']['news_for_acp'] = '';
$install_lang['modules']['users'] = 'Users';
$install_lang['modules']['users_for_acp'] = 'Users';
$install_lang['modules']['contact'] = 'Contact';
$install_lang['modules']['contact_for_acp'] = '';
$install_lang['modules']['statistics'] = 'Statistics';
$install_lang['modules']['statistics_for_acp'] = '';
$install_lang['modules']['voting'] = 'Voting';
$install_lang['modules']['voting_for_acp'] = '';
$install_lang['modules']['banners'] = 'Banners';
$install_lang['modules']['banners_for_acp'] = '';
$install_lang['modules']['seek'] = 'Search';
$install_lang['modules']['seek_for_acp'] = '';
$install_lang['modules']['menu'] = 'Navigation Bar';
$install_lang['modules']['menu_for_acp'] = '';
$install_lang['modules']['comment'] = 'Comment';
$install_lang['modules']['comment_for_acp'] = '';
$install_lang['modules']['siteterms'] = 'Terms & Conditions';
$install_lang['modules']['siteterms_for_acp'] = '';
$install_lang['modules']['feeds'] = 'RSS-feeds';
$install_lang['modules']['Page'] = 'Page';
$install_lang['modules']['Page_for_acp'] = '';
$install_lang['modules']['freecontent'] = 'Introduction';
$install_lang['modules']['freecontent_for_acp'] = '';
$install_lang['modules']['two_step_verification'] = '2-Step Verification';
$install_lang['modules']['two_step_verification_for_acp'] = '';

$install_lang['modfuncs'] = array();
$install_lang['modfuncs']['users'] = array();
$install_lang['modfuncs']['users']['login'] = 'Login';
$install_lang['modfuncs']['users']['register'] = 'Register';
$install_lang['modfuncs']['users']['lostpass'] = 'Password recovery';
$install_lang['modfuncs']['users']['active'] = 'Active account';
$install_lang['modfuncs']['users']['editinfo'] = 'Account Settings';
$install_lang['modfuncs']['users']['memberlist'] = 'Members list';
$install_lang['modfuncs']['users']['logout'] = 'Logout';
$install_lang['modfuncs']['users']['groups'] = 'Group management';

$install_lang['modfuncs']['statistics'] = array();
$install_lang['modfuncs']['statistics']['allreferers'] = 'By referrers';
$install_lang['modfuncs']['statistics']['allcountries'] = 'By countries';
$install_lang['modfuncs']['statistics']['allbrowsers'] = 'By browsers ';
$install_lang['modfuncs']['statistics']['allos'] = 'By operating system';
$install_lang['modfuncs']['statistics']['allbots'] = 'By search engines';
$install_lang['modfuncs']['statistics']['referer'] = 'By month';

$install_lang['blocks_groups'] = array();
$install_lang['blocks_groups']['news'] = array();
$install_lang['blocks_groups']['news']['module.block_newscenter'] = 'Breaking news';
$install_lang['blocks_groups']['news']['global.block_category'] = 'Category';
$install_lang['blocks_groups']['news']['global.block_tophits'] = 'Top Hits';
$install_lang['blocks_groups']['banners'] = array();
$install_lang['blocks_groups']['banners']['global.banners1'] = 'Center Banner';
$install_lang['blocks_groups']['banners']['global.banners2'] = 'Left Banner';
$install_lang['blocks_groups']['banners']['global.banners3'] = 'Right Banner';
$install_lang['blocks_groups']['statistics'] = array();
$install_lang['blocks_groups']['statistics']['global.counter'] = 'Statistics';
$install_lang['blocks_groups']['about'] = array();
$install_lang['blocks_groups']['about']['global.about'] = 'About';
$install_lang['blocks_groups']['voting'] = array();
$install_lang['blocks_groups']['voting']['global.voting_random'] = 'Voting';
$install_lang['blocks_groups']['users'] = array();
$install_lang['blocks_groups']['users']['global.user_button'] = 'Member login';
$install_lang['blocks_groups']['theme'] = array();
$install_lang['blocks_groups']['theme']['global.company_info'] = 'Managing company';
$install_lang['blocks_groups']['theme']['global.menu_footer'] = 'Main categories';
$install_lang['blocks_groups']['freecontent'] = array();
$install_lang['blocks_groups']['freecontent']['global.free_content'] = 'Introduction';

$install_lang['cron'] = array();
$install_lang['cron']['cron_online_expired_del'] = 'Delete expired online status';
$install_lang['cron']['cron_dump_autobackup'] = 'Automatic backup database';
$install_lang['cron']['cron_auto_del_temp_download'] = 'Empty temporary files';
$install_lang['cron']['cron_del_ip_logs'] = 'Delete IP log files';
$install_lang['cron']['cron_auto_del_error_log'] = 'Delete expired error_log log files';
$install_lang['cron']['cron_auto_sendmail_error_log'] = 'Send error logs to admin';
$install_lang['cron']['cron_ref_expired_del'] = 'Delete expired referer';
$install_lang['cron']['cron_auto_check_version'] = 'Check NukeViet version';
$install_lang['cron']['cron_notification_autodel'] = 'Delete old notification';

$install_lang['groups']['NukeViet-Fans'] = 'NukeViet-Fans';
$install_lang['groups']['NukeViet-Admins'] = 'NukeViet-Admins';
$install_lang['groups']['NukeViet-Programmers'] = 'NukeViet-Programmers';

$install_lang['vinades_fullname'] = "Vietnam Open Source Development Joint Stock Company";
$install_lang['vinades_address'] = "Room 1706 – CT2 Nang Huong building, 583 Nguyen Trai street, Ha Dong, Hanoi, Vietnam";
$install_lang['nukeviet_description'] = 'Sharing success, connect passions';
$install_lang['disable_site_content'] = 'For technical reasons Web site temporary not available. we are very sorry for that inconvenience!';

$menu_rows_lev0['about'] = array(
    'title' => $install_lang['modules']['about'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=about",
    'groups_view' => '6',
    'op' => ''
);
$menu_rows_lev0['news'] = array(
    'title' => $install_lang['modules']['news'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=news",
    'groups_view' => '6',
    'op' => ''
);
$menu_rows_lev0['users'] = array(
    'title' => $install_lang['modules']['users'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=users",
    'groups_view' => '6',
    'op' => ''
);
$menu_rows_lev0['voting'] = array(
    'title' => $install_lang['modules']['voting'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=voting",
    'groups_view' => '6',
    'op' => ''
);
$menu_rows_lev0['contact'] = array(
    'title' => $install_lang['modules']['contact'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=contact",
    'groups_view' => '6',
    'op' => ''
);

$menu_rows_lev1['users'] = array();
$menu_rows_lev1['users'][] = array(
    'title' => $install_lang['modfuncs']['users']['login'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=users&op=login",
    'groups_view' => '5',
    'op' => 'login'
);
$menu_rows_lev1['users'][] = array(
    'title' => $install_lang['modfuncs']['users']['register'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=users&op=register",
    'groups_view' => '5',
    'op' => 'register'
);
$menu_rows_lev1['users'][] = array(
    'title' => $install_lang['modfuncs']['users']['lostpass'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=users&op=lostpass",
    'groups_view' => '5',
    'op' => 'lostpass'
);
$menu_rows_lev1['users'][] = array(
    'title' => $install_lang['modfuncs']['users']['editinfo'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=users&op=editinfo",
    'groups_view' => '4,7',
    'op' => 'editinfo'
);
$menu_rows_lev1['users'][] = array(
    'title' => $install_lang['modfuncs']['users']['memberlist'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=users&op=memberlist",
    'groups_view' => '4,7',
    'op' => 'memberlist'
);
$menu_rows_lev1['users'][] = array(
    'title' => $install_lang['modfuncs']['users']['logout'],
    'link' => NV_BASE_SITEURL . "index.php?language=" . $lang_data . "&nv=users&op=logout",
    'groups_view' => '4,7',
    'op' => 'logout'
);