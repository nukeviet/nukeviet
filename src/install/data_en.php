<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

$install_lang['modules'] = [];
$install_lang['modules']['about'] = 'About';
$install_lang['modules']['about_for_acp'] = '';
$install_lang['modules']['news'] = 'News';
$install_lang['modules']['news_for_acp'] = '';
$install_lang['modules']['users'] = 'Users';
$install_lang['modules']['users_for_acp'] = 'Users';
$install_lang['modules']['myapi'] = 'My APIs';
$install_lang['modules']['myapi_for_acp'] = '';
$install_lang['modules']['inform'] = 'Notification';
$install_lang['modules']['inform_for_acp'] = '';
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

$install_lang['modfuncs'] = [];
$install_lang['modfuncs']['users'] = [];
$install_lang['modfuncs']['users']['login'] = 'Login';
$install_lang['modfuncs']['users']['register'] = 'Register';
$install_lang['modfuncs']['users']['lostpass'] = 'Password recovery';
$install_lang['modfuncs']['users']['lostactivelink'] = 'Retrieve activation link';
$install_lang['modfuncs']['users']['r2s'] = 'Turn off 2-step authentication';
$install_lang['modfuncs']['users']['active'] = 'Active account';
$install_lang['modfuncs']['users']['editinfo'] = 'Account Settings';
$install_lang['modfuncs']['users']['memberlist'] = 'Members list';
$install_lang['modfuncs']['users']['logout'] = 'Logout';
$install_lang['modfuncs']['users']['groups'] = 'Group management';
$install_lang['modfuncs']['users']['datadeletion'] = 'Personal data deletion';
$install_lang['modfuncs']['users']['main'] = 'Account homepage';
$install_lang['modfuncs']['users']['avatar'] = 'Change avatar';
$install_lang['modfuncs']['users']['security-privacy'] = 'Security and Privacy';
$install_lang['modfuncs']['users']['verify-password'] = 'Verify Password';

$install_lang['modfuncs']['statistics'] = [];
$install_lang['modfuncs']['statistics']['allreferers'] = 'By referrers';
$install_lang['modfuncs']['statistics']['allcountries'] = 'By countries';
$install_lang['modfuncs']['statistics']['allbrowsers'] = 'By browsers ';
$install_lang['modfuncs']['statistics']['allos'] = 'By operating system';
$install_lang['modfuncs']['statistics']['allbots'] = 'By search engines';
$install_lang['modfuncs']['statistics']['referer'] = 'By month';

$install_lang['blocks_groups'] = [];
$install_lang['blocks_groups']['news'] = [];
$install_lang['blocks_groups']['news']['module.block_newscenter'] = 'Breaking news';
$install_lang['blocks_groups']['news']['global.block_category'] = 'Category';
$install_lang['blocks_groups']['news']['global.block_tophits'] = 'Top Hits';
$install_lang['blocks_groups']['banners'] = [];
$install_lang['blocks_groups']['banners']['global.banners1'] = 'Center Banner';
$install_lang['blocks_groups']['banners']['global.banners2'] = 'Left Banner';
$install_lang['blocks_groups']['banners']['global.banners3'] = 'Right Banner';
$install_lang['blocks_groups']['statistics'] = [];
$install_lang['blocks_groups']['statistics']['global.counter'] = 'Statistics';
$install_lang['blocks_groups']['about'] = [];
$install_lang['blocks_groups']['about']['global.about'] = 'About';
$install_lang['blocks_groups']['voting'] = [];
$install_lang['blocks_groups']['voting']['global.voting_random'] = 'Voting';
$install_lang['blocks_groups']['inform'] = [];
$install_lang['blocks_groups']['inform']['global.inform'] = 'Notification';
$install_lang['blocks_groups']['users'] = [];
$install_lang['blocks_groups']['users']['global.user_button'] = 'Member login';
$install_lang['blocks_groups']['theme'] = [];
$install_lang['blocks_groups']['theme']['global.company_info'] = 'Managing company';
$install_lang['blocks_groups']['theme']['global.menu_footer'] = 'Main categories';
$install_lang['blocks_groups']['freecontent'] = [];
$install_lang['blocks_groups']['freecontent']['global.free_content'] = 'Introduction';

$install_lang['cron'] = [];
$install_lang['cron']['cron_online_expired_del'] = 'Delete expired online status';
$install_lang['cron']['cron_dump_autobackup'] = 'Automatic backup database';
$install_lang['cron']['cron_auto_del_temp_download'] = 'Empty temporary files';
$install_lang['cron']['cron_del_ip_logs'] = 'Delete IP log files';
$install_lang['cron']['cron_auto_del_error_log'] = 'Delete expired error_log log files';
$install_lang['cron']['cron_auto_sendmail_error_log'] = 'Send error logs to admin';
$install_lang['cron']['cron_ref_expired_del'] = 'Delete expired referer';
$install_lang['cron']['cron_auto_check_version'] = 'Check NukeViet version';
$install_lang['cron']['cron_notification_autodel'] = 'Delete old notification';
$install_lang['cron']['cron_remove_expired_inform'] = 'Remove expired notifications';
$install_lang['cron']['cron_apilogs_autodel'] = 'Remove expired API-logs';
$install_lang['cron']['cron_expadmin_handling'] = 'Handling expired admins';
$install_lang['cron']['cron_user_datadeletion_handling'] = 'Handling scheduled user data deletion';

$install_lang['groups']['NukeViet-Fans'] = 'NukeViet-Fans';
$install_lang['groups']['NukeViet-Admins'] = 'NukeViet-Admins';
$install_lang['groups']['NukeViet-Programmers'] = 'NukeViet-Programmers';

$install_lang['groups']['NukeViet-Fans-desc'] = 'NukeViet System Fans Group';
$install_lang['groups']['NukeViet-Admins-desc'] = 'Group of administrators for sites built by the NukeViet system';
$install_lang['groups']['NukeViet-Programmers-desc'] = 'NukeViet System Programmers Group';

$install_lang['vinades_fullname'] = 'Vietnam Open Source Development Joint Stock Company';
$install_lang['vinades_address'] = '6th floor, Song Da building, No. 131 Tran Phu Street, Ha Dong Ward, Hanoi City, Vietnam';
$install_lang['nukeviet_description'] = 'Sharing success, connect passions';
$install_lang['disable_site_content'] = 'For technical reasons Web site temporary not available. we are very sorry for that inconvenience!';

// Ngôn ngữ dữ liệu cho phần mẫu email
use NukeViet\Template\Email\Cat as EmailCat;
use NukeViet\Template\Email\Tpl as EmailTpl;

$install_lang['emailtemplates'] = [];
$install_lang['emailtemplates']['cats'] = [];
$install_lang['emailtemplates']['cats'][EmailCat::CAT_SYSTEM] = 'System Messages';
$install_lang['emailtemplates']['cats'][EmailCat::CAT_USER] = 'User Messages';
$install_lang['emailtemplates']['cats'][EmailCat::CAT_AUTHOR] = 'Admin Messages';
$install_lang['emailtemplates']['cats'][EmailCat::CAT_MODULE] = 'Module Messages';

$install_lang['emailtemplates']['emails'] = [];

$install_lang['emailtemplates']['emails'][EmailTpl::E_AUTO_ERROR_REPORT] = [
    'pids' => '5',
    'catid' => EmailCat::CAT_SYSTEM,
    't' => 'Email auto-notification error',
    's' => 'Error report from website {$site_name}',
    'c' => 'The system received some error messages. Please open the attached file for details.'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_EMAIL_CONFIG_TEST] = [
    'pids' => '5',
    'catid' => EmailCat::CAT_SYSTEM,
    't' => 'Send test email to test email sending configuration',
    's' => 'Test email',
    'c' => 'This is a test email to check the mail configuration. Simply delete it!'
];

$install_lang['emailtemplates']['emails'][EmailTpl::E_AUTHOR_DELETE] = [
    'pids' => '4',
    'catid' => EmailCat::CAT_AUTHOR,
    't' => 'Notice that the administrator account has been deleted',
    's' => 'Information from {$site_name} website',
    'c' => 'Administrator {$site_name} website notify:<br />Your administrator account in {$site_name} website deleted at {$time}{if not empty($note)}. Reason: {$note}{/if}.<br />If you have any questions... please email <a href="mailto:{$email}">{$email}</a>{if not empty($username)}<br/><br/>{$sig}<br/><br/>{$username}<br/>{$position}<br/>{$email}{/if}'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_AUTHOR_SUSPEND] = [
    'pids' => '4',
    'catid' => EmailCat::CAT_AUTHOR,
    't' => 'Notice of suspension of site administration',
    's' => 'Information from {$site_name} website',
    'c' => 'Information from {$site_name} aministrators:<br />Your administrator account in {$site_name} is suspended at {$time} reason: {$note}.<br />If you have any questions... please email <a href="mailto:{$email}">{$email}</a>{if not empty($username)}<br/><br/>{$sig}<br/><br/>{$username}<br/>{$position}<br/>{$email}{/if}'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_AUTHOR_REACTIVE] = [
    'pids' => '4',
    'catid' => EmailCat::CAT_AUTHOR,
    't' => 'Notice of reactivation of site administration',
    's' => 'Information from {$site_name} website',
    'c' => 'Information from {$site_name} aministrators:<br />Your administrator account in {$site_name} is reactive at {$time}.<br />Your account has been suspended before because: {$note}{if not empty($username)}<br/><br/>{$sig}<br/><br/>{$username}<br/>{$position}<br/>{$email}{/if}'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_AUTHOR_2STEP_ADD] = [
    'pids' => '4',
    'catid' => EmailCat::CAT_AUTHOR,
    't' => 'Notice that a new two-step authentication method has been added to the admin account',
    's' => 'Configuring 2-step verification using Oauth done',
    'c' => '{$greeting_user}<br /><br />Administration of the site {$site_name} would like to inform:<br />Two-step authentication using Oauth in the admin panel has been successfully installed. You can use the account <strong>{$oauth_id}</strong> of the provider <strong>{$oauth_name}</strong> for authentication when you log into the site admin area.'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_AUTHOR_2STEP_TRUNCATE] = [
    'pids' => '4',
    'catid' => EmailCat::CAT_AUTHOR,
    't' => 'Notification that all two-step authentication methods have been removed from the admin account',
    's' => 'Configuring Two-Step Authentication with Oauth has been canceled',
    'c' => '{$greeting_user}<br /><br />Administration of the site {$site_name} would like to inform:<br />At your request, 2-Step Verification using Oauth has been successfully canceled. From now on, you cannot use the accounts <strong>{$oauth_id}</strong> to authenticate in the site admin area.'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_AUTHOR_2STEP_DEL] = [
    'pids' => '4',
    'catid' => EmailCat::CAT_AUTHOR,
    't' => 'Notice that two-step authentication has been removed from the admin account',
    's' => 'Configuring Two-Step Authentication with Oauth has been canceled',
    'c' => '{$greeting_user}<br /><br />Administration of the site {$site_name} would like to inform:<br />At your request, 2-Step Verification using Oauth has been successfully canceled. From now on, you cannot use the account <strong>{$oauth_id}</strong> of the provider <strong>{$oauth_name}</strong> to authenticate in the site admin area.'
];

$menu_rows_lev0['about'] = [
    'title' => $install_lang['modules']['about'],
    'link' => NV_BASE_SITEURL . 'index.php?language=' . $lang_data . '&nv=about',
    'groups_view' => '6',
    'op' => ''
];
$menu_rows_lev0['news'] = [
    'title' => $install_lang['modules']['news'],
    'link' => NV_BASE_SITEURL . 'index.php?language=' . $lang_data . '&nv=news',
    'groups_view' => '6',
    'op' => ''
];
$menu_rows_lev0['users'] = [
    'title' => $install_lang['modules']['users'],
    'link' => NV_BASE_SITEURL . 'index.php?language=' . $lang_data . '&nv=users',
    'groups_view' => '6',
    'op' => ''
];
$menu_rows_lev0['voting'] = [
    'title' => $install_lang['modules']['voting'],
    'link' => NV_BASE_SITEURL . 'index.php?language=' . $lang_data . '&nv=voting',
    'groups_view' => '6',
    'op' => ''
];
$menu_rows_lev0['contact'] = [
    'title' => $install_lang['modules']['contact'],
    'link' => NV_BASE_SITEURL . 'index.php?language=' . $lang_data . '&nv=contact',
    'groups_view' => '6',
    'op' => ''
];

$menu_rows_lev1['users'] = [];
$menu_rows_lev1['users'][] = [
    'title' => $install_lang['modfuncs']['users']['login'],
    'link' => NV_BASE_SITEURL . 'index.php?language=' . $lang_data . '&nv=users&op=login',
    'groups_view' => '5',
    'op' => 'login'
];
$menu_rows_lev1['users'][] = [
    'title' => $install_lang['modfuncs']['users']['register'],
    'link' => NV_BASE_SITEURL . 'index.php?language=' . $lang_data . '&nv=users&op=register',
    'groups_view' => '5',
    'op' => 'register'
];
$menu_rows_lev1['users'][] = [
    'title' => $install_lang['modfuncs']['users']['lostpass'],
    'link' => NV_BASE_SITEURL . 'index.php?language=' . $lang_data . '&nv=users&op=lostpass',
    'groups_view' => '5',
    'op' => 'lostpass'
];
$menu_rows_lev1['users'][] = [
    'title' => $install_lang['modfuncs']['users']['editinfo'],
    'link' => NV_BASE_SITEURL . 'index.php?language=' . $lang_data . '&nv=users&op=editinfo',
    'groups_view' => '4,7',
    'op' => 'editinfo'
];
$menu_rows_lev1['users'][] = [
    'title' => $install_lang['modfuncs']['users']['memberlist'],
    'link' => NV_BASE_SITEURL . 'index.php?language=' . $lang_data . '&nv=users&op=memberlist',
    'groups_view' => '4,7',
    'op' => 'memberlist'
];
$menu_rows_lev1['users'][] = [
    'title' => $install_lang['modfuncs']['users']['logout'],
    'link' => NV_BASE_SITEURL . 'index.php?language=' . $lang_data . '&nv=users&op=logout',
    'groups_view' => '4,7',
    'op' => 'logout'
];
