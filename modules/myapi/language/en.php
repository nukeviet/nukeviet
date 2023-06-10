<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

$lang_translator['author'] = 'VINADES.,JSC <contact@vinades.vn>';
$lang_translator['createdate'] = '02/06/2023, 14:44';
$lang_translator['copyright'] = '@Copyright (C) 2010 VINADES.,JSC. All rights reserved';
$lang_translator['info'] = '';
$lang_translator['langtype'] = 'lang_module';

$lang_module['main_title'] = 'My APIs';
$lang_module['api_of_system'] = 'System';
$lang_module['auth_method_select'] = 'Please choose an authentication method';
$lang_module['api_role_select'] = 'Select API Role';
$lang_module['api_role_type_private_error'] = 'API-role does not allow arbitrary activation of use';
$lang_module['api_role_type_public2'] = 'Public API-roles';
$lang_module['api_role_type_private2'] = 'API-roles just for you';
$lang_module['status'] = 'Status';
$lang_module['active'] = 'Active';
$lang_module['inactive'] = 'Inactive';
$lang_module['activated'] = 'Activated';
$lang_module['not_activated'] = 'Not activated';
$lang_module['suspended'] = 'Suspended';
$lang_module['activate'] = 'Activate';
$lang_module['deactivate'] = 'Deactivate';
$lang_module['api_role_status'] = 'API-role<br />Status';
$lang_module['api_role_credential_status'] = 'User<br />Status';
$lang_module['authentication'] = 'Authentication';
$lang_module['api_credential_ident'] = 'Access keys';
$lang_module['api_credential_secret'] = 'Secret code';
$lang_module['value_copied'] = 'Value has been copied';
$lang_module['auth_method'] = 'Method';
$lang_module['auth_method_password_verify'] = 'password_verify (recommend)';
$lang_module['auth_method_md5_verify'] = 'md5_verify';
$lang_module['create_access_authentication'] = 'Create new authentication';
$lang_module['delete_authentication'] = 'Delete authentication';
$lang_module['api_roles_empty'] = 'The requested API-roles could not be found';
$lang_module['api_roles_list'] = 'List of API Roles';
$lang_module['api_roles_allowed'] = 'APIs';
$lang_module['api_role_credential_addtime'] = 'Starting';
$lang_module['api_role_credential_access_count'] = 'Number of<br />API-role calls';
$lang_module['api_role_credential_last_access'] = 'Last call<br />to API-role';
$lang_module['api_roles_detail'] = 'API List of';
$lang_module['endtime'] = 'End time';
$lang_module['quota'] = 'Quota';
$lang_module['indefinitely'] = 'Indefinitely';
$lang_module['no_quota'] = 'No quota';
$lang_module['api_ips'] = 'Access IP';
$lang_module['api_ips_help'] = 'IPs are separated by commas. API-role access is made only from these IPs. Leaving it blank means not checking IP';
$lang_module['api_ips_update'] = 'Update Access IP';
$lang_module['role_management'] = 'API-Role Management';
$lang_module['add_role'] = 'Create new API-role';
$lang_module['edit_role'] = 'Edit API-role';
$lang_module['api_addtime'] = 'Add time';
$lang_module['api_edittime'] = 'Edit time';
$lang_module['api_roles_empty2'] = 'No API Role is created, please create a Role API first. The system will automatically switch to the Role API creation page in a moment';
$lang_module['api_roles_title'] = 'Name';
$lang_module['api_roles_description'] = 'Description';
$lang_module['api_roles_error_title'] = 'Error: The API Role name was not entered';
$lang_module['api_roles_error_exists'] = 'Error: This API Role Name already exists, please enter a different name to avoid confusion';
$lang_module['api_roles_error_role'] = 'Error: No APIs selected';
$lang_module['api_roles_api_doesnt_exist'] = 'The APIs are not recognized';
$lang_module['api_roles_checkall'] = 'Select all';
$lang_module['api_roles_uncheckall'] = 'Deselect all';
$lang_module['api_role_notice'] = 'Note: Depending on the level of the licensed administrator account the APIs used in each Role API will be redefined.';
$lang_module['api_role_credential'] = 'API-roles access';
$lang_module['api_role_credential_empty'] = 'No object has access to this API role yet';
$lang_module['api_role'] = 'API Role';
$lang_module['api_role_credential_add'] = 'Add access';
$lang_module['api_role_credential_edit'] = 'Edit access info';
$lang_module['api_role_credential_search'] = 'Object search';
$lang_module['api_role_credential_error'] = 'Please declare the object assigned to this API-role access';
$lang_module['api_role_credential_userid'] = 'ID';
$lang_module['api_role_credential_username'] = 'Login';
$lang_module['api_role_credential_fullname'] = 'Fullname';
$lang_module['api_role_credential_unknown'] = 'Unknown object';
$lang_module['api_role_credential_count'] = 'Number of accesses';
$lang_module['api_role_type'] = 'API role Type';
$lang_module['api_role_type_private'] = 'Private';
$lang_module['api_role_type_public'] = 'Public';
$lang_module['api_role_object'] = 'API role Object';
$lang_module['api_role_object_admin'] = 'Admin';
$lang_module['api_role_object_user'] = 'User';
$lang_module['api_role_type_private_note'] = 'A private API-role is a group of APIs that an object cannot register itself to use. Only general administrator are allowed to assign private API-role to certain objects';
$lang_module['api_role_type_public_note'] = 'Public API-role is a group of APIs that any object can register themselves to use';
$lang_module['all'] = 'All';
$lang_module['admin_auth_method_password_verify'] = 'password_verify';
$lang_module['auth_method_none'] = 'None';
$lang_module['deprivation'] = 'Deprivation';
$lang_module['deprivation_confirm'] = 'Do you really want to deprive this user of permissions?';
$lang_module['config'] = 'Settings';
$lang_module['remote_api_access'] = 'Enable Remote API';
$lang_module['remote_api_access_help'] = 'Disabling all API access from outside will be blocked. Internal APIs are still used normally';
$lang_module['api_remote_off'] = 'Remote API <strong>is off</strong> so API calls will not be possible. To support API calls, <strong><a href="%s">enable Remote API here</a></strong>';
$lang_module['api_remote_off2'] = 'Remote API <strong>is off</strong> so API calls will not be possible.';
$lang_module['cat_api_list'] = 'API list under category';
$lang_module['flood_blocker'] = 'Query restriction';
$lang_module['flood_blocker_note'] = 'If you leave these fields blank, the number of requests is unlimited';
$lang_module['flood_limit'] = 'Max Requests';
$lang_module['flood_interval'] = 'Within';
$lang_module['minutes'] = 'minutes';
$lang_module['hours'] = 'hours';
$lang_module['log_period'] = 'Log retention period';
$lang_module['log_period_note'] = 'Leaving it blank means no logs';
$lang_module['flood_interval_error'] = 'The validity period of a request restriction rule cannot exceed the log retention period';
$lang_module['logs'] = 'Request Logs';
$lang_module['log_time'] = 'Request time';
$lang_module['log_ip'] = 'From IP';
$lang_module['log_del_confirm'] = 'Do you really want to delete?';
$lang_module['del_selected'] = 'Delete the selected ones';
$lang_module['del_all'] = 'Delete all';
$lang_module['api_select'] = 'Please select API';
$lang_module['fromdate'] = 'Request from';
$lang_module['todate'] = 'Request to';
$lang_module['filter_logs'] = 'Filter logs';
$lang_module['addtime_note'] = 'If left blank, this will be interpreted as the current time';
$lang_module['endtime_note'] = 'If the field is left blank, it will be interpreted as indefinitely';
$lang_module['quota_note'] = 'If the field is left blank, it will be interpreted as unlimited';
$lang_module['saveopt1'] = 'Save changes for %s';
$lang_module['saveopt2'] = 'Save changes for all languages';
$lang_module['saveopt3'] = 'Save changes for %s and go to configuration for %s';
