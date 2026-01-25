<?php

/**
 * NUKEVIET Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

$lang_translator['author'] = 'VINADES.,JSC <contact@vinades.vn>';
$lang_translator['createdate'] = '04/03/2010, 15:22';
$lang_translator['copyright'] = '@Copyright (C) 2012 VINADES.,JSC. All rights reserved';
$lang_translator['info'] = '';
$lang_translator['langtype'] = 'lang_module';

$lang_module['copy'] = 'Copy';
$lang_module['errorsave'] = 'Unknown error leading to data can not be saved';
$lang_module['add_template'] = 'Add email template';
$lang_module['edit_template'] = 'Edit email template';
$lang_module['categories'] = 'Email template categories';
$lang_module['categories_other'] = 'Others';
$lang_module['categories_list'] = 'List of categories';
$lang_module['categories_add'] = 'Add category';
$lang_module['categories_edit'] = 'Edit category';
$lang_module['categories_title'] = 'Category name';
$lang_module['categories_error_title'] = 'Category name not already entered';
$lang_module['categories_error_exists'] = 'This category name has already been used, please choose a different name';
$lang_module['categories_show'] = 'Show email in list';
$lang_module['from'] = 'From';
$lang_module['to'] = 'To';
$lang_module['keywords'] = 'Keyword';
$lang_module['all'] = 'All';
$lang_module['add_edit'] = 'Add/edit time';
$lang_module['default'] = 'Default';
$lang_module['order'] = 'Order';
$lang_module['adv_info'] = 'Advanced parameters';
$lang_module['tpl_send_name'] = 'Sender &amp; Email send';
$lang_module['tpl_send_cc'] = 'CC';
$lang_module['tpl_send_bcc'] = 'BCC';
$lang_module['tpl_is_plaintext'] = 'Plain text';
$lang_module['tpl_is_plaintext_help'] = 'Remove formatting in outgoing email content';
$lang_module['tpl_is_disabled'] = 'Cancel sending mail';
$lang_module['tpl_is_disabled_help'] = 'Select this option and the system will suspend email from this template';
$lang_module['tpl_is_selftemplate'] = 'Self template';
$lang_module['tpl_is_selftemplate_help'] = 'Select this option if you do not want to apply generic email templates when sending out mail';
$lang_module['list_email_help'] = 'Multiple emails can be entered, separated by commas';
$lang_module['tpl_send_name_help'] = 'If not entered here, the system will take from the website name and email address of the site';
$lang_module['tpl_basic_info'] = 'Basic information';
$lang_module['tpl_attachments'] = 'Attachments';
$lang_module['tpl_error_default_subject'] = 'Email subject is empty';
$lang_module['tpl_error_default_content'] = 'Email content is empty';
$lang_module['tpl_error_title'] = 'Email template %s name empty';
$lang_module['tpl_error_exists'] = 'This %s name is already in use, please choose another name to avoid confusion';
$lang_module['tpl_error_smarty_subject'] = 'For security reasons, you are not allowed to use the $smarty variable in default email subject';
$lang_module['tpl_error_smarty_subject1'] = 'For security reasons, you are not allowed to use the $smarty variable in %s email subject';
$lang_module['tpl_error_smarty_content'] = 'For security reasons, you are not allowed to use the $smarty variable in default email content';
$lang_module['tpl_error_smarty_content1'] = 'For security reasons, you are not allowed to use the $smarty variable in %s email content';
$lang_module['tpl_title'] = 'Email template name';
$lang_module['tpl_subject'] = 'Email subject';
$lang_module['tpl_content'] = 'Email body';
$lang_module['tpl_incat'] = 'Category';
$lang_module['default_content'] = 'Default email content';
$lang_module['default_content_info'] = 'Applies to all languages if the language is not defined below';
$lang_module['lang_content'] = 'Email content by language';
$lang_module['tpl_list'] = 'List of email templates';
$lang_module['tpl_is_active'] = 'Receiving email';
$lang_module['tpl_is_disabled'] = 'Stop sending emails';
$lang_module['tpl_is_disabled_label'] = 'Stop';
$lang_module['tpl_custom_label'] = 'Custom';
$lang_module['tpl_plugin'] = 'Plugin';
$lang_module['tpl_plugin_help'] = 'Choose plugin that handles merge fields in email content';
$lang_module['tpl_pluginsys'] = 'System plugin';
$lang_module['tpl_mailtpl'] = 'Template file';
$lang_module['tpl_pluginsys_help'] = 'These plugin are fixed to the email template of the system and can not be changed. If you want to add more, select below';
$lang_module['function'] = 'Function';
$lang_module['rollback_message'] = 'If during the editing process, you have changed the title and content of the email without remembering the original prototype. Currently this email cannot be sent normally. Please click the button below to restore the original email. The name, subject, content of emails in all available languages will return to their original state.';
$lang_module['update_for'] = 'Take this action for';
$lang_module['update_for1'] = 'This template is across all languages';
$lang_module['update_for2'] = 'This template is in %s';
$lang_module['update_for3'] = 'This template is based on modules of the same name %s';
$lang_module['update_for4'] = 'This template only';
$lang_module['merge_field'] = 'Merge fields';
$lang_module['merge_field_help'] = 'These fields are automatically replaced with the corresponding value when exporting the email content. Click on the description of the variables to fill in the editor';
$lang_module['merge_field_guild1'] = 'Conditional display';
$lang_module['merge_field_guild2'] = 'Display content based on the condition of a variable. For example:';
$lang_module['merge_field_guild3'] = 'For more details, see <a href="https://www.smarty.net/docs/en/language.function.if.tpl" target="_blank">here</a>';
$lang_module['merge_field_guild4'] = 'Output as a loop';
$lang_module['merge_field_guild5'] = 'Loop array to output elements in that array. For example:';
$lang_module['merge_field_guild6'] = 'For more details, see <a href="https://www.smarty.net/docs/en/language.function.foreach.tpl" target="_blank">here</a>';
$lang_module['test'] = 'Send test email';
$lang_module['test_tomail'] = 'Received email';
$lang_module['test_error_tomail'] = 'Received email empty';
$lang_module['test_error_template'] = 'This email template does not exist';
$lang_module['test_tomail_note'] = 'Enter one email per line, usually up to 50 emails';
$lang_module['test_value_fields'] = 'Custom fields';
$lang_module['test_success'] = 'Test email sent successfully, please check your inbox (spam box if not in your inbox) to see the email received';
$lang_module['test_note1'] = 'The email test sending feature supports single variables with string or numeric data such as <code>$site_name</code>, <code>$username</code>, and one-dimensional array data of strings or numbers e.g. <code>$user.full_name</code>';
$lang_module['test_note2'] = 'If your variable is an array, write <code>$user.full_name</code> instead of <code>$user[\'full_name\']</code>';
$lang_module['test_note3'] = 'When you draft an email template with complex conditions and variables that are beyond the scope of support above, test sending emails using the <code>nv_sendmail_from_template</code> function or the <code>nv_sendmail_template_async</code> function through programming';
