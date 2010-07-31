<?php

/**
* @Project NUKEVIET 3.0
* @Author VINADES.,JSC (contact@vinades.vn)
* @Copyright (C) 2010 VINADES.,JSC. All rights reserved
* @Language English
* @Createdate Jul 31, 2010, 01:12:43 PM
*/

 if (! defined('NV_ADMIN') or ! defined('NV_MAINFILE')){
 die('Stop!!!');
}

$lang_translator['author'] ="VINADES.,JSC (contact@vinades.vn)";
$lang_translator['createdate'] ="31/05/2010, 00:07";
$lang_translator['copyright'] ="Copyright (C) 2010 VINADES.,JSC. All rights reserved";
$lang_translator['info'] ="";
$lang_translator['langtype'] ="lang_module";

$lang_module['modforum'] = "Members management by forum %1\$s.";
$lang_module['list_module_title'] = "Members list";
$lang_module['member_add'] = "Add member";
$lang_module['member_wating'] = "Member wating";
$lang_module['list_question'] = "Question list";
$lang_module['search_type'] = "Search by";
$lang_module['search_id'] = "Member's ID";
$lang_module['search_account'] = "Member's account";
$lang_module['search_name'] = "Member's name";
$lang_module['search_mail'] = "Member's email";
$lang_module['search_note'] = "Keyword maximum length is 64 characters, html isn't allowed";
$lang_module['submit'] = "Searcg";
$lang_module['members_list'] = "Member list";
$lang_module['main_title'] = "Manage member";
$lang_module['userid'] = "ID";
$lang_module['account'] = "Account";
$lang_module['name'] = "Name";
$lang_module['email'] = "Email";
$lang_module['register_date'] = "Register date";
$lang_module['status'] = "Status";
$lang_module['funcs'] = "Features";
$lang_module['user_add'] = "Add member";
$lang_module['password'] = "Password";
$lang_module['repassword'] = "Repeat password";
$lang_module['question'] = "Secret question";
$lang_module['answer'] = "Answer";
$lang_module['gender'] = "Gender";
$lang_module['male'] = "Male";
$lang_module['female'] = "Female";
$lang_module['NA'] = "N/A";
$lang_module['avata'] = "Avatar";
$lang_module['birthday'] = "Birthday";
$lang_module['date'] = "Day";
$lang_module['month'] = "Month";
$lang_module['year'] = "Year";
$lang_module['website'] = "Website";
$lang_module['address'] = "Address";
$lang_module['ym'] = "Yahoo messenger";
$lang_module['phone'] = "Phone";
$lang_module['fax'] = "Fax";
$lang_module['mobile'] = "Mobile";
$lang_module['show_email'] = "Display email";
$lang_module['sig'] = "Signature";
$lang_module['in_group'] = "Member of group";
$lang_module['addquestion'] = "Add secret question";
$lang_module['savequestion'] = "Save secret question";
$lang_module['errornotitle'] = "Error: Empty secret question";
$lang_module['errorsave'] = "Error: Can't update data, please check topic title";
$lang_module['weight'] = "position";
$lang_module['save'] = "Save";
$lang_module['siteterms'] = "Site rules";
$lang_module['error_content'] = "Error: Empty site rules";
$lang_module['saveok'] = "Update successful";
$lang_module['config'] = "Module config";
$lang_module['allow_reg'] = "Allow register";
$lang_module['allow_login'] = "Allow login";
$lang_module['allow_change_email'] = "Allow change email";
$lang_module['type_reg'] = "Register type";
$lang_module['active_not_allow'] = "Not grant to register";
$lang_module['active_admin_check'] = "Admin active";
$lang_module['active_all'] = "No need to active";
$lang_module['active_email'] = "Email activation";
$lang_module['deny_email'] = "Deny words on member's email";
$lang_module['deny_name'] = "Deny words on member's account";
$lang_module['memberlist_active'] = "Active";
$lang_module['memberlist_unactive'] = "Active";
$lang_module['memberlist_error_method'] = "Please select search method!";
$lang_module['memberlist_error_value'] = "Search value must be at least 1 and doesn't exceed 64 characters!";
$lang_module['memberlist_nousers'] = "No result match!";
$lang_module['memberlist_selectusers'] = "Select at least 1 user to delete!";
$lang_module['checkall'] = "Select all";
$lang_module['uncheckall'] = "Unselect all";
$lang_module['delete'] = "Delete";
$lang_module['delete_success'] = "Success!";
$lang_module['active_success'] = "Success!";
$lang_module['memberlist_edit'] = "Edit";
$lang_module['memberlist_deleteconfirm'] = "Do you realy want to delete?";
$lang_module['edit_title'] = "Edit";
$lang_module['edit_password_note'] = "Leave 2 frame below blank if you don't want to change password";
$lang_module['edit_avatar_note'] = "Leave blank if you don't want to change avatar mới";
$lang_module['edit_save'] = "Accept";
$lang_module['edit_error_username'] = "User name empty or contain characters doesn't allow";
$lang_module['edit_error_username_exist'] = "User name used by another member. Please choose another name";
$lang_module['edit_error_photo'] = "File type doesn't allowed";
$lang_module['edit_error_email'] = "Incorrect email";
$lang_module['edit_error_email_exist'] = "Your email used in another account. Please choose another account.";
$lang_module['edit_error_permission'] = "You can not change the account information.";
$lang_module['edit_error_password'] = "Password doesn't match";
$lang_module['edit_error_nopassword'] = "Empty password";
$lang_module['edit_add_error'] = "Can't update member information!";
$lang_module['edit_error_question'] = "Empty secret question";
$lang_module['edit_error_answer'] = "Empty answer";
$lang_module['edit_error_group'] = "Please select group for member";
$lang_module['awaiting_active'] = "Activate";
$lang_module['delconfirm_message'] = "Do you realy want to delete selected member?";
$lang_module['delconfirm_email'] = "Send notification email:";
$lang_module['delconfirm_email_yes'] = "Yes";
$lang_module['delconfirm_ok'] = "Ok!";
$lang_module['delconfirm_email_title'] = "Email notification to delete account";
$lang_module['delconfirm_email_content'] = "Hi %1\$s,

We are so sorry to delete your account at website %2\$s.";
$lang_module['adduser_email'] = "Send notification email:";
$lang_module['adduser_email_yes'] = "Yes";
$lang_module['adduser_register'] = "Your account was created";
$lang_module['adduser_register_info'] = "Hi %1\$s,

Your account at website %2\$s activated. Your login information:

URL: %3\$s
Account: %4\$s
Password: %5\$s

This is email automatic sending from website %2\$s.

Site administrator";
$lang_module['allow_openid'] = "Allow using OpenID";
$lang_module['openid_servers'] = "OpenID accepted list";
$lang_module['allow_change_login'] = "Allow change login name";
$lang_module['is_user_forum'] = "Use forum's users";
$lang_module['search_page_title'] = "Result";
$lang_module['click_to_view'] = "Click to view";
$lang_module['level0'] = "User";
$lang_module['level1'] = "Super Administrator";
$lang_module['level2'] = "General Administrator";
$lang_module['level3'] = "Area Administrative";
$lang_module['admin_add'] = "Set to admin";

?>