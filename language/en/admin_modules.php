<?php

/**
* @Project NUKEVIET 4.x
* @Author VINADES.,JSC (contact@vinades.vn)
* @Copyright (C) 2014 VINADES.,JSC. All rights reserved
* @Language English
* @License CC BY-SA (http://creativecommons.org/licenses/by-sa/4.0/)
* @Createdate Mar 04, 2010, 08:22:00 AM
*/

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$lang_translator['author'] = 'VINADES.,JSC (contact@vinades.vn)';
$lang_translator['createdate'] = '04/03/2010, 15:22';
$lang_translator['copyright'] = '@Copyright (C) 2010 VINADES.,JSC. All rights reserved';
$lang_translator['info'] = '';
$lang_translator['langtype'] = 'lang_module';

$lang_module['modules'] = 'Setup new module';
$lang_module['blocks'] = 'Setting of Blocks';
$lang_module['language'] = 'Setup language';
$lang_module['setup'] = 'Setup';
$lang_module['main'] = 'List of modules';
$lang_module['edit'] = 'Edit modules &ldquo;%s&rdquo;';
$lang_module['caption_actmod'] = 'List of active modules';
$lang_module['caption_deactmod'] = 'List of inactive modules';
$lang_module['caption_badmod'] = 'List error modules';
$lang_module['caption_newmod'] = 'List deactive modules';
$lang_module['module_name'] = 'Module';
$lang_module['custom_title'] = 'Name';
$lang_module['weight'] = 'Order';
$lang_module['in_menu'] = 'Top Menu';
$lang_module['submenu'] = 'Sub Menu';
$lang_module['version'] = 'Version';
$lang_module['settime'] = 'Setup time';
$lang_module['author'] = 'Author';
$lang_module['theme'] = 'Theme';
$lang_module['theme_default'] = 'Default';
$lang_module['keywords'] = 'Keywork';
$lang_module['keywords_info'] = 'Separated by commas';
$lang_module['funcs_list'] = 'List of function modules &ldquo;%s&rdquo;';
$lang_module['funcs_title'] = 'Function';
$lang_module['funcs_custom_title'] = 'Name';
$lang_module['funcs_layout'] = 'Using layouts';
$lang_module['funcs_in_submenu'] = 'Menu';
$lang_module['funcs_subweight'] = 'Index';
$lang_module['activate_rss'] = 'Activate RSS';
$lang_module['module_sys'] = 'System modules';
$lang_module['vmodule'] = 'Virtual modules';
$lang_module['vmodule_add'] = 'Add virtual module';
$lang_module['vmodule_name'] = 'Module name';
$lang_module['vmodule_file'] = 'Original module';
$lang_module['vmodule_note'] = 'Note';
$lang_module['vmodule_select'] = 'Select module';
$lang_module['vmodule_blockquote'] = 'Note: Module name only accept regular characters, numerals and underscore';
$lang_module['autoinstall'] = 'Automatic setup';
$lang_module['autoinstall_method'] = 'Select process';
$lang_module['autoinstall_method_none'] = 'Please select:';
$lang_module['autoinstall_method_module'] = 'Setup module & block packet';
$lang_module['autoinstall_method_block'] = 'Install blocks';
$lang_module['autoinstall_method_packet'] = 'Module packeted';
$lang_module['autoinstall_continue'] = 'Next';
$lang_module['back'] = 'Back';
$lang_module['autoinstall_error_nomethod'] = 'Empty setup type!';
$lang_module['autoinstall_module_install'] = 'Setup module';
$lang_module['autoinstall_module_select_file'] = 'Please select packet:';
$lang_module['autoinstall_module_error_filetype'] = 'Setup file extension must be zip or gz';
$lang_module['autoinstall_module_error_nofile'] = 'No file selected';
$lang_module['autoinstall_module_nomethod'] = 'No setup method selected';
$lang_module['autoinstall_module_uploadedfile'] = 'File uploaded:';
$lang_module['autoinstall_module_uploadedfilesize'] = 'Size:';
$lang_module['autoinstall_module_uploaded_filenum'] = 'Total file & folder';
$lang_module['autoinstall_module_error_uploadfile'] = 'Fail to upload file. Please check chmod of tmp folder';
$lang_module['autoinstall_module_error_createfile'] = 'Fail to create file. Please check chmod of tmp folder';
$lang_module['autoinstall_module_error_invalidfile'] = 'Invalid zip file';
$lang_module['autoinstall_module_error_warning_overwrite'] = 'Announcement: structure of the module has the file and folder wrong standard, Do you want to continue installed?';
$lang_module['autoinstall_module_overwrite'] = 'Continue install';
$lang_module['autoinstall_module_error_warning_fileexist'] = 'List files:';
$lang_module['autoinstall_module_error_warning_invalidfolder'] = 'Invalid folder struct!';
$lang_module['autoinstall_module_error_warning_permission_folder'] = 'Safe mode on. Fail to create folder.';
$lang_module['autoinstall_module_checkfile_notice'] = 'To continue setup, click CHECK to check compatibility';
$lang_module['autoinstall_module_checkfile'] = 'CHECK';
$lang_module['autoinstall_module_installdone'] = 'SETTING UP...';
$lang_module['autoinstall_module_cantunzip'] = 'Fail to unzip. Please check folders chmod.';
$lang_module['autoinstall_module_unzip_success'] = 'Successful. Automatic redirect to activate page.';
$lang_module['autoinstall_module_unzip_setuppage'] = 'Go to modules page';
$lang_module['autoinstall_module_unzip_filelist'] = 'List unzip files';
$lang_module['autoinstall_module_error_movefile'] = 'The installation can not continue automatically because the host does not support moving files after unpacking.';
$lang_module['autoinstall_package_select'] = 'Select module to package';
$lang_module['autoinstall_package_noselect'] = 'No module selected';
$lang_module['autoinstall_package_processing'] = 'please wait to complete...';
$lang_module['mobile'] = 'Mobile theme';
$lang_module['delete_module_info1'] = 'This module is used in language <strong>%s</strong>, please delete it in this language before';
$lang_module['delete_module_info2'] = 'There is %d virtual module created by this module, please delete it before';
$lang_module['admin_title'] = 'Title of administration section';
$lang_module['change_func_name'] = 'Rename function "%s" of module "%s"';
$lang_module['edit_error_update_theme'] = 'The update module is detected on theme %s does not properly or defective, please check again.';
$lang_module['description'] = 'Description';
$lang_module['funcs_alias'] = 'Alias';
$lang_module['vmodule_exit'] = 'Error: Module you have put in the system.';
$lang_module['change_fun_alias'] = 'Rename alias "%s" of module "%s"';