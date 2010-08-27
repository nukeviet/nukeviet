<?php

/**
* @Project NUKEVIET 3.0
* @Author VINADES.,JSC (contact@vinades.vn)
* @Copyright (C) 2010 VINADES.,JSC. All rights reserved
* @Language English
* @Createdate Aug 27, 2010, 02:26:03 PM
*/

 if (! defined('NV_ADMIN') or ! defined('NV_MAINFILE')){
 die('Stop!!!');
}

$lang_translator['author'] ="VINADES.,JSC (contact@vinades.vn)";
$lang_translator['createdate'] ="04/03/2010, 15:22";
$lang_translator['copyright'] ="@Copyright (C) 2010 VINADES.,JSC. All rights reserved";
$lang_translator['info'] ="";
$lang_translator['langtype'] ="lang_module";

$lang_module['level1'] = "Super";
$lang_module['level2'] = "General administration";
$lang_module['level3'] = "Module management";
$lang_module['is_suspend0'] = "Active";
$lang_module['is_suspend1'] = "Suspend &ldquo;%1\$s&rdquo; bởi &ldquo;%2\$s&rdquo; reason &ldquo;%3\$s&rdquo;";
$lang_module['last_login0'] = "Never";
$lang_module['login'] = "Username";
$lang_module['email'] = "Email";
$lang_module['full_name'] = "Site name";
$lang_module['sig'] = "Signature";
$lang_module['editor'] = "Editor";
$lang_module['lev'] = "Right";
$lang_module['position'] = "Position";
$lang_module['regtime'] = "Registration date time";
$lang_module['is_suspend'] = "Status";
$lang_module['last_login'] = "Last login";
$lang_module['last_ip'] = "From IP";
$lang_module['browser'] = "Browser";
$lang_module['os'] = "Operate system";
$lang_module['admin_info_title1'] = "Account information: %s";
$lang_module['admin_info_title2'] = "Account information: %s (you)";
$lang_module['menulist'] = "List Administrator";
$lang_module['menuadd'] = "Add Administrator";
$lang_module['main'] = "Website adminsistrators list";
$lang_module['nv_admin_edit'] = "Edit website administrator's information";
$lang_module['nv_admin_add'] = "Add website administrator's information";
$lang_module['nv_admin_del'] = "Delete website administrator's information";
$lang_module['username_incorrect'] = "Error: Don't found this member account:% s";
$lang_module['full_name_incorrect'] = "You do not declare the name of this administrator";
$lang_module['position_incorrect'] = "You do not declare the position of this administrator";
$lang_module['nv_admin_add_info'] = "To add new website administrator account, you need to declare fully in the box below. You can create an account below your level management";
$lang_module['if_level3_selected'] = "Please tick on the module that you allows to manage";
$lang_module['login_info'] = "You need to enter the user name, if not a member you need to create first member.";
$lang_module['nv_admin_add_result'] = "New administrator's information";
$lang_module['nv_admin_add_title'] = "System has successfully created a new website account administrator with the information below";
$lang_module['nv_admin_modules'] = "Modules management";
$lang_module['admin_account_info'] = "Administrator information %s";
$lang_module['nv_admin_add_download'] = "Download";
$lang_module['nv_admin_add_sendmail'] = "Send mail";
$lang_module['nv_admin_login_address'] = "URL management page";
$lang_module['nv_admin_edit_info'] = "Edit account information &ldquo;<strong>%s</strong>&rdquo;";
$lang_module['show_mail'] = "Show email";
$lang_module['sig_info'] = "Signature is inserted at the end of each reply, email... sent from the Administrator account &ldquo;<strong>%s</strong>&rdquo;. Only accept simple text";
$lang_module['not_use'] = "Not in use";
$lang_module['nv_admin_edit_result'] = "Edit website administrator's information: %s";
$lang_module['nv_admin_edit_result_title'] = "Administrator account's changes: %s";
$lang_module['show_mail0'] = "Not show";
$lang_module['show_mail1'] = "Show";
$lang_module['field'] = "Criteria";
$lang_module['old_value'] = "Old";
$lang_module['new_value'] = "New";
$lang_module['chg_is_suspend0'] = "Status: suspend. To be active administrator account, Please you declare in the box below";
$lang_module['chg_is_suspend1'] = "Status: active. To suspend this administrator account, Please declare the box below";
$lang_module['chg_is_suspend2'] = "Re-active/Suspend";
$lang_module['nv_admin_chg_suspend'] = "Change status administrator account &ldquo;<strong>%s</strong>&rdquo;";
$lang_module['position_info'] = "Position is used for external activities such as mail exchange , written comments...";
$lang_module['susp_reason_empty'] = "You do not declare the reason for suspending Administrator account&ldquo;<strong>%s</strong>&rdquo;";
$lang_module['suspend_info_empty'] = "Administrator account &ldquo;<strong>%s</strong>&rdquo; not be suspended any time";
$lang_module['suspend_info_yes'] = "List of times to suspend the operation of the Administrator Account &ldquo;<strong>%s</strong>&rdquo;";
$lang_module['suspend_start'] = "Start";
$lang_module['suspend_end'] = "Finish";
$lang_module['suspend_reason'] = "Suspending reason";
$lang_module['suspend_info'] = "At: %1\$s<br />By: %2\$s";
$lang_module['suspend0'] = "Active";
$lang_module['suspend1'] = "Suspend";
$lang_module['clean_history'] = "Clear history";
$lang_module['suspend_sendmail'] = "Send notify";
$lang_module['suspend_sendmail_mess1'] = "Information from %1\$s Aministrators:<br />Your administrator account %1\$s is suspended %2\$s reason: %3\$s.<br />If you have any questions... Email: %4\$s";
$lang_module['suspend_sendmail_mess0'] = "Information from %1\$s Aministrators:<br />Your administrator account %1\$s is active at%2\$s.<br />Your account has been suspended because:: %3\$s";
$lang_module['suspend_sendmail_title'] = "Website notify %s";
$lang_module['delete_sendmail_mess0'] = "Administrator %1\$s notify:<br />Your administrator account in %1\$s website deleted  %2\$s.<br />If you have any questions... Email %3\$s";
$lang_module['delete_sendmail_mess1'] = "Administrator %1\$s website notify:<br />Your administrator account in %1\$s website deleted %2\$s Reason: %3\$s.<br />If you have any questions... Email %4\$s";
$lang_module['delete_sendmail_title'] = "Information from %s website";
$lang_module['delete_sendmail_info'] = "Do you really want to delete the administrator account &ldquo;<strong>%s</strong>&rdquo;? Please fill in box below to confirm";
$lang_module['admin_del_sendmail'] = "Send notify";
$lang_module['admin_del_reason'] = "Reason";
$lang_module['allow_files_type'] = "The file types are allowed to upload";
$lang_module['allow_modify_files'] = "Allow to edit,delete";
$lang_module['allow_create_subdirectories'] = "Allow to create directory";
$lang_module['allow_modify_subdirectories'] = "Allow to change name, delete folder";
$lang_module['admin_login_incorrect'] = "Account &ldquo;<strong>%s</strong>&rdquo; already exist. Please use other account";
$lang_module['config'] = "Config";
$lang_module['adminip'] = "Admin IP";
$lang_module['adminip_ip'] = "IP";
$lang_module['adminip_timeban'] = "Start";
$lang_module['adminip_timeendban'] = "Finish";
$lang_module['adminip_funcs'] = "Functions";
$lang_module['adminip_checkall'] = "Check all";
$lang_module['adminip_uncheckall'] = "Uncheck all";
$lang_module['adminip_add'] = "Add IP";
$lang_module['adminip_address'] = "Adresss";
$lang_module['adminip_begintime'] = "Start";
$lang_module['adminip_endtime'] = "Finish";
$lang_module['adminip_notice'] = "Note";
$lang_module['save'] = "Save";
$lang_module['adminip_mask_select'] = "Please select";
$lang_module['adminip_nolimit'] = "Unlimited";
$lang_module['adminip_del_success'] = "Successfully";
$lang_module['adminip_delete_confirm'] = "Are you sure you want to remove this IP?";
$lang_module['adminip_mask'] = "IP Mask";
$lang_module['adminip_edit'] = "Edit";
$lang_module['adminip_delete'] = "Delete";
$lang_module['adminip_error_ip'] = "Please enter admin IP";
$lang_module['adminip_error_validip'] = "Invalid IP";
$lang_module['adminip_note'] = "Note: You need basic knowledge about networking to use check IP features!";
$lang_module['title_nickname'] = "Manage firewall account";
$lang_module['admfirewall'] = "Check firewall";
$lang_module['block_admin_ip'] = "Check IP";
$lang_module['nickname_add'] = "Add";
$lang_module['nickname_edit'] = "Edit";
$lang_module['nicknam_delete_confirm'] = "Are you sure you want to remove this account?";
$lang_module['passwordsincorrect'] = "Password does not match";
$lang_module['nochangepass'] = "Leave password fields blank if you don't want to change password";
$lang_module['rule_user'] = "Account only accept letters, numbers and underscore";
$lang_module['rule_pass'] = "Password only accept letters, numbers and underscore";

?>