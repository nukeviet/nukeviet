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

$install_lang['modules'] = [];
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

$install_lang['modfuncs'] = [];
$install_lang['modfuncs']['users'] = [];
$install_lang['modfuncs']['users']['login'] = 'Login';
$install_lang['modfuncs']['users']['register'] = 'Register';
$install_lang['modfuncs']['users']['lostpass'] = 'Password recovery';
$install_lang['modfuncs']['users']['active'] = 'Active account';
$install_lang['modfuncs']['users']['editinfo'] = 'Account Settings';
$install_lang['modfuncs']['users']['memberlist'] = 'Members list';
$install_lang['modfuncs']['users']['logout'] = 'Logout';
$install_lang['modfuncs']['users']['groups'] = 'Group management';

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

$install_lang['groups']['NukeViet-Fans'] = 'NukeViet-Fans';
$install_lang['groups']['NukeViet-Admins'] = 'NukeViet-Admins';
$install_lang['groups']['NukeViet-Programmers'] = 'NukeViet-Programmers';

$install_lang['vinades_fullname'] = "Vietnam Open Source Development Joint Stock Company";
$install_lang['vinades_address'] = "Room 1706 – CT2 Nang Huong building, 583 Nguyen Trai street, Ha Dong, Hanoi, Vietnam";
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
$install_lang['emailtemplates']['emails'][EmailTpl::E_USER_EMAIL_ACTIVE] = [
    'pids' => '3',
    'catid' => EmailCat::CAT_USER,
    't' => 'Account activation via email',
    's' => 'Activate information',
    'c' => 'Hi {$user_full_name},<br /><br />Your account at website {$site_name} waitting to activate. To activate, please click link follow:<br /><br />URL: <a href="{$active_link}">{$active_link}</a><br /><br />Account information:<br /><br />Account: {$user_username}<br />Email: {$user_email}<br /><br />Activate expired on {$active_deadline}<br /><br />This is email automatic sending from website {$site_name}.<br /><br />Site administrator'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_USER_DELETE] = [
    'pids' => '8',
    'catid' => EmailCat::CAT_USER,
    't' => 'Email notification to delete account',
    's' => 'Email notification to delete account',
    'c' => 'Hi {$user_full_name} ({$user_username}),<br />We are so sorry to delete your account at website {$site_name}.'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_USER_NEW_2STEP_CODE] = [
    'pids' => '9',
    'catid' => EmailCat::CAT_USER,
    't' => 'Send new backup code',
    's' => 'New backup code',
    'c' => 'Hello {$user_full_name},<br /><br /> backup code to your account at the website {$site_name} has been changed. Here is a new backup code: <br /><br />{foreach from=$new_code item=code}{$code}<br />{/foreach}<br /><br /> You keep your backup safe. If you lose your phone and lose your backup code, you will no longer be able to access your account. <br /><br /> This is an automated message sent to your e-mail from website {$site_name}. If you do not understand the content of this letter, simply delete it. <br /><br /> Site Admin'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_USER_NEW_INFO] = [
    'pids' => '10',
    'catid' => EmailCat::CAT_USER,
    't' => 'Account notification created/activated',
    's' => 'Your account was created',
    'c' => 'Hi {$user_full_name},<br /><br />Your account at website {$site_name} activated. Your login information:<br /><br />URL: <a href="{$login_link}">{$login_link}</a><br /><br />Account: {$user_username}<br /><br /><br />This is email automatic sending from website {$site_name}.<br /><br />Site administrator'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_USER_ADMIN_ADDED] = [
    'pids' => '11',
    'catid' => EmailCat::CAT_USER,
    't' => 'Account notification created by admin',
    's' => 'Your account was created',
    'c' => 'Hello {$user_full_name},<br /><br /> Your account at website {$site_name} has been created. Here are the logins: <br /><br />URL: <a href="{$login_link}">{$login_link}</a><br /> Account Name: {$user_username}<br /> < Password: {$user_password}<br /><br />We recommend that you change your password before using your account. <br /> <br /> This is an automated message sent to Your email box from {$site_name}. website. If you do not understand the content of this letter, simply delete it. <br /> <br /> Site Admin'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_USER_SAFE_KEY] = [
    'pids' => '12',
    'catid' => EmailCat::CAT_USER,
    't' => 'Safe mode verification code',
    's' => 'Safe mode verification code',
    'c' => 'Hello {$user_full_name},<br /><br />You sent a request using safe mode in website {$site_name}. Below is a verifykey for activating or off safe mode:<br /><br /><strong>{$code}</strong><br /><br />This verifykey only works on-off safe mode once only. After you turn off safe mode, this verification code will be worthless.<br /><br />These are automatic messages sent to your e-mail inbox from website {$site_name}.<br /><br /><br /><br />Administration site'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_USER_SELF_EDIT] = [
    'pids' => '13',
    'catid' => EmailCat::CAT_USER,
    't' => 'Account update notification',
    's' => 'Update account infomation success',
    'c' => 'Hello {$user_full_name},<br /><br />Your account on the website {$site_name} has been updated with {$edit_label} new <strong>{$new_value}</strong>.<br /><br />These are automatic messages sent to your e-mail inbox from website {$site_name}.<br /><br /><br /><br />Administration site'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_USER_ADMIN_EDIT] = [
    'pids' => '14',
    'catid' => EmailCat::CAT_USER,
    't' => 'Notification about administrator modifying account information',
    's' => 'Your account has been updated',
    'c' => 'Hello {$user_full_name},<br /><br /> Your account at website {$site_name} has been updated. Here are the new login information: <br /><br />URL: <a href="{$login_url}">{$login_url}</a><br /> Account Name: {$user_username}{if $send_password}<br /> Password: {$user_password}{/if}<br /> <br /> This is an automated message sent to your email from {$site_name}. If you do not understand the content of this letter, simply delete it. <br /> <br /> Site Admin'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_USER_VERIFY_EMAIL] = [
    'pids' => '15',
    'catid' => EmailCat::CAT_USER,
    't' => 'Confirmation email account change confirmation',
    's' => 'Activation information for changing email',
    'c' => 'Hello {$user_full_name},<br /><br />You sent a request to change the email address of the personal Account on the website {$site_name}. To complete this change, you must confirm your new email address by entering the verifykey below in the appropriate fields in the area Edit Account Information:<br /><br />Verifykey: <strong>{$code}</strong><br /><br />This key expires on {$timeout}.<br /><br />These are automatic messages sent to your e-mail inbox from website {$site_name}. If you do not understand anything about the contents of this letter, simply delete it.<br /><br /><br /><br />Administration site'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_USER_GROUP_JOIN] = [
    'pids' => '16',
    'catid' => EmailCat::CAT_USER,
    't' => 'Notice of group member request',
    's' => 'Request to join group',
    'c' => 'Hello leader <strong>{$leader_name}</strong>,<br /><br /><strong>{$user_full_name}</strong> has sent the request to join the group <strong>{$group_name}</strong> because you are managing. You need to approve this request! <br /> <br /> Please <a href="{$link}"> visit this link </a> to approve membership.'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_USER_LOST_ACTIVE] = [
    'pids' => '17',
    'catid' => EmailCat::CAT_USER,
    't' => 'Recover account activation link',
    's' => 'Activate information',
    'c' => 'Hi {$user_full_name},<br /><br />Your account at website {$site_name} waitting to activate. To activate, please click link follow:<br /><br />URL: <a href="{$active_link}">{$active_link}</a><br /><br />Account information:<br /><br />Account: {$user_username}<br />Email: {$user_email}<br />Password: {$user_password}<br /><br />Activate expired on {$timeout}<br /><br />This is email automatic sending from website {$site_name}.<br /><br />Site administrator'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_USER_LOST_PASS] = [
    'pids' => '18',
    'catid' => EmailCat::CAT_USER,
    't' => 'Recover user password',
    's' => 'Guide password recovery in website {$site_name}',
    'c' => 'Hello {$user_full_name},<br /><br />You propose to change my login password at the website {$site_name}. To change your password, you will need to enter the verification code below in the corresponding box at the password change area.<br /><br />Verification code: <strong>%3$s</strong><br /><br />This code is only used once and before the deadline of %4$s<br />This letter is automatically sent to your email inbox from site {$site_name}. If you do not know anything about the contents of this letter, just delete it.<br /><br />Administrator'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_AUTHOR_DELETE] = [
    'pids' => '5',
    'catid' => EmailCat::CAT_AUTHOR,
    't' => 'Xóa tài khoản quản trị',
    's' => 'Thông báo từ website {$site_name}',
    'c' => 'Ban quản trị website {$site_name} xin thông báo:<br />Tài khoản quản trị của bạn tại website {$site_name} đã bị xóa vào {$delete_time}{if not empty($delete_reason)} vì lý do: {$delete_reason}{/if}.<br />Mọi đề nghị, thắc mắc... xin gửi đến địa chỉ {$contact_link}'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_AUTHOR_SUSPEND] = [
    'pids' => '6',
    'catid' => EmailCat::CAT_AUTHOR,
    't' => 'Đình chỉ/Kích hoạt lại quản trị site',
    's' => 'Thông báo từ website {$site_name}',
    'c' => '{if $is_suspend}Ban quản trị website {$site_name} xin thông báo:<br />Tài khoản quản trị của bạn tại website {$site_name} đã bị đình chỉ hoạt động vào {$suspend_time} vì lý do: {$suspend_reason}.<br />Mọi đề nghị, thắc mắc... xin gửi đến địa chỉ {$contact_link}{else}Ban quản trị website {$site_name} xin thông báo:<br />Tài khoản quản trị của bạn tại website {$site_name} đã hoạt động trở lại vào {$suspend_time}.<br />Trước đó tài khoản này đã bị đình chỉ hoạt động vì lý do: {$suspend_reason}{/if}'
];
$install_lang['emailtemplates']['emails'][EmailTpl::E_AUTO_ERROR_REPORT] = [
    'pids' => '7',
    'catid' => EmailCat::CAT_SYSTEM,
    't' => 'Email tự động thông báo lỗi',
    's' => 'Cảnh báo từ website {$site_name}',
    'c' => 'Hệ thống đã nhận được một số thông báo. Bạn hãy mở file đính kèm để xem chi tiết'
];

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

$menu_rows_lev1['users'] = [];
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
