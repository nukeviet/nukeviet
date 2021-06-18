<?php

/**
* @Project NUKEVIET 4.x
* @Author VINADES.,JSC <contact@vinades.vn>
* @Copyright (C) 2017 VINADES.,JSC. All rights reserved
* @Language English
* @License CC BY-SA (http://creativecommons.org/licenses/by-sa/4.0/)
* @Createdate Mar 04, 2010, 08:22:00 AM
*/

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$lang_translator['author'] = 'VINADES.,JSC (contact@vinades.vn)';
$lang_translator['createdate'] = '04/03/2010, 15:22';
$lang_translator['copyright'] = '@Copyright (C) 2010 VINADES.,JSC. All rights reserved';
$lang_translator['info'] = '';
$lang_translator['langtype'] = 'lang_module';

$lang_module['main'] = 'General Informations';
$lang_module['database_info'] = 'General information about the database &ldquo;<strong>%s</strong>&rdquo;';
$lang_module['tables_info'] = 'Tables of database &ldquo;<strong>%s</strong>&rdquo;';
$lang_module['table_caption'] = 'Table information &ldquo;<strong>%s</strong>&rdquo;';
$lang_module['table_row_caption'] = 'Table field information &ldquo;<strong>%s</strong>&rdquo;';
$lang_module['db_host_info'] = 'Server';
$lang_module['db_sql_version'] = 'Version';
$lang_module['db_proto_info'] = 'TCP/IP version';
$lang_module['server'] = 'Server name';
$lang_module['db_dbname'] = 'Database name';
$lang_module['db_uname'] = 'Username';
$lang_module['db_charset'] = 'Charset';
$lang_module['db_collation'] = 'Collation';
$lang_module['db_time_zone'] = 'Time zone';
$lang_module['table_name'] = 'Table';
$lang_module['table_size'] = 'Size';
$lang_module['table_max_size'] = 'Max size';
$lang_module['table_datafree'] = 'Free capacity';
$lang_module['table_numrow'] = 'Row';
$lang_module['table_charset'] = 'Charset';
$lang_module['table_type'] = 'Type';
$lang_module['row_format'] = 'Format';
$lang_module['table_auto_increment'] = 'Auto increment';
$lang_module['table_create_time'] = 'Create date';
$lang_module['table_update_time'] = 'Update';
$lang_module['table_check_time'] = 'Check';
$lang_module['optimize'] = 'Optimize';
$lang_module['savefile'] = 'Save on server';
$lang_module['download'] = 'Download';
$lang_module['download_now'] = 'Database download now';
$lang_module['download_all'] = 'Structures and data';
$lang_module['download_str'] = 'Structures';
$lang_module['ext_sql'] = 'Sql file';
$lang_module['ext_gz'] = 'Zip file';
$lang_module['submit'] = 'Submit';
$lang_module['third'] = 'Total table: %1$d; Size: %2$s; Free capacity: %3$s';
$lang_module['optimize_result'] = 'Optimize tables:%1$sFree %2$s excess data';
$lang_module['nv_show_tab'] = 'Table information &ldquo;%s&rdquo;';
$lang_module['field_name'] = 'Field';
$lang_module['field_type'] = 'Type';
$lang_module['field_null'] = 'Required';
$lang_module['field_key'] = 'Key';
$lang_module['field_default'] = 'Default';
$lang_module['field_extra'] = 'Extra';
$lang_module['php_code'] = 'Code PHP';
$lang_module['sql_code'] = 'Code SQL';
$lang_module['save_data'] = 'Save database';
$lang_module['save_error'] = 'Error: System can not write file <br /><br /> Please check permissions of folder: %1$s.';
$lang_module['save_ok'] = 'Save successfully';
$lang_module['save_download'] = 'Click here to download the file.';
$lang_module['dump_autobackup'] = 'Activate auto backup';
$lang_module['dump_backup_ext'] = 'File extension';
$lang_module['dump_interval'] = 'Repeat following jobs';
$lang_module['dump_backup_day'] = 'Time to save database files backup';
$lang_module['file_backup'] = 'Backups';
$lang_module['file_nb'] = 'No.';
$lang_module['file_name'] = 'File name';
$lang_module['file_time'] = 'Time';
$lang_module['file_size'] = 'Size';
$lang_module['sampledata'] = 'Export sample data';
$lang_module['sampledata_note'] = 'This is a way to export the entire database of the current website to a template file for the purpose of packaging the entire website. When installed new, the system will restore the old packaging data instead of installing the sample data in the installer. Please complete the required items below then click the make button to begin the process';
$lang_module['sampledata_creat'] = 'Create a new sample data packet';
$lang_module['sampledata_list'] = 'List of generated template packets';
$lang_module['sampledata_empty'] = 'No sample data package yet';
$lang_module['sampledata_start'] = 'Start creating';
$lang_module['sampledata_dat_init'] = 'The process begins to run, please do not turn off the browser until the completion message or error message. The system is checking information';
$lang_module['sampledata_name'] = 'Sample package name';
$lang_module['sampledata_name_rule'] = 'Only enter characters from a-z and 0-9';
$lang_module['sampledata_error_sys'] = 'Server error, please reload the page and try again';
$lang_module['sampledata_error_name'] = 'Please enter a sample package name';
$lang_module['sampledata_error_namerule'] = 'Please enter only characters from a-z and 0-9';
$lang_module['sampledata_error_exists'] = 'This sample data package already exists, by clicking the <strong /> Start creating  button again, the system will overwrite the existing template data package. If you do not want to overwrite, enter a different name.';
$lang_module['sampledata_error_writetmp'] = 'Error: The system failed to write data, give write permission to the% s directory and then execute again';
$lang_module['sampledata_success_1'] = 'Data export successful! The system has written data to the file. Now you can clean the system to delete the extra files, then delete the config file and encapsulate the code to share.';
$lang_module['sampledata_success_2'] = 'Data export was successful but the system failed to write to the file. You can download a manual <a href="%s"> <strong /> here ! </a> package';