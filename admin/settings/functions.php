<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-2-2010 1:58
 */

if (! defined('NV_ADMIN') or ! defined('NV_MAINFILE') or ! defined('NV_IS_MODADMIN')) {
    die('Stop!!!');
}

$allow_func = array( 'main', 'language', 'smtp' );
if (defined('NV_IS_GODADMIN') or (defined('NV_IS_SPADMIN') and $global_config['idsite'] > 0)) {
    $allow_func[] = 'system';
}
if (defined('NV_IS_GODADMIN')) {
    $allow_func[] = 'ftp';
    $allow_func[] = 'security';
    $allow_func[] = 'cronjobs';
    $allow_func[] = 'cronjobs_add';
    $allow_func[] = 'cronjobs_edit';
    $allow_func[] = 'cronjobs_del';
    $allow_func[] = 'cronjobs_act';
    $allow_func[] = 'plugin';
    $allow_func[] = 'variables';
    $allow_func[] = 'cdn';
}

$menu_top = array(
    'title' => $module_name,
    'module_file' => '',
    'custom_title' => $lang_global['mod_settings']
);

unset($page_title, $select_options);

define('NV_IS_FILE_SETTINGS', true);

//Document
$array_url_instruction['main'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:settings';
$array_url_instruction['system'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:settings:system';
$array_url_instruction['smtp'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:settings:smtp';
$array_url_instruction['security'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:settings:security';
$array_url_instruction['plugin'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:settings:plugin';
$array_url_instruction['cronjobs'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:settings:cronjobs';
$array_url_instruction['ftp'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:settings:ftp';
$array_url_instruction['variables'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:setting:variables';

/**
 * nv_admin_add_theme()
 *
 * @param mixed $contents
 * @return
 */
function nv_admin_add_theme($contents)
{
    global $global_config, $module_file, $my_head, $my_footer;

    $xtpl = new XTemplate('cronjobs_add.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);

    $my_head .= "<link type=\"text/css\" href=\"" . NV_BASE_SITEURL . NV_ASSETS_DIR . "/js/jquery-ui/jquery-ui.min.css\" rel=\"stylesheet\" />\n";

    $my_footer .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . NV_ASSETS_DIR . "/js/jquery-ui/jquery-ui.min.js\"></script>\n";
    $my_footer .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . NV_ASSETS_DIR . "/js/language/jquery.ui.datepicker-" . NV_LANG_INTERFACE . ".js\"></script>\n";

    if ($contents['is_error']) {
        $xtpl->parse('main.error');
    }

    $xtpl->assign('DATA', $contents);

    foreach ($contents['run_file'][2] as $run) {
        $xtpl->assign('RUN_FILE', array( 'key' => $run, 'selected' => $contents['run_file'][3] == $run ? ' selected="selected"' : '' ));
        $xtpl->parse('main.run_file');
    }

    for ($i = 0; $i < 24; ++$i) {
        $xtpl->assign('HOUR', array( 'key' => $i, 'selected' => $i == $contents['hour'][1] ? ' selected="selected"' : '' ));
        $xtpl->parse('main.hour');
    }

    for ($i = 0; $i < 60; ++$i) {
        $xtpl->assign('MIN', array( 'key' => $i, 'selected' => $i == $contents['min'][1] ? ' selected="selected"' : '' ));
        $xtpl->parse('main.min');
    }

    $xtpl->assign('DELETE', ! empty($contents['del'][1]) ? ' checked="checked"' : '');

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * main_theme()
 *
 * @param mixed $contents
 * @return
 */
function main_theme($contents)
{
    if (empty($contents)) {
        return '';
    }

    global $global_config, $module_file;

    $xtpl = new XTemplate('cronjobs_list.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);

    foreach ($contents as $id => $values) {
        $xtpl->assign('DATA', array(
            'caption' => $values['caption'],
            'edit' => empty($values['edit']) ? array() : $values['edit'],
            'disable' => empty($values['disable']) ? array() : $values['disable'],
            'delete' => empty($values['delete']) ? array() : $values['delete'],
            'id' => $id
        ));

        if (! empty($values['edit'][0])) {
            $xtpl->parse('main.edit');
        }
        if (! empty($values['disable'][0])) {
            $xtpl->parse('main.disable');
        }
        if (! empty($values['delete'][0])) {
            $xtpl->parse('main.delete');
        }

        foreach ($values['detail'] as $key => $value) {
            $xtpl->assign('ROW', array(
                'key' => $key,
                'value' => $value
            ));

            $xtpl->parse('main.loop');
        }

        $xtpl->parse('main');
    }

    return $xtpl->text('main');
}
