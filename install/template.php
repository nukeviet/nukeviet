<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * nv_site_theme()
 *
 * @param mixed $step
 * @param mixed $titletheme
 * @param mixed $contenttheme
 * @return
 */
function nv_site_theme($step, $titletheme, $contenttheme)
{
    global $lang_module, $languageslist, $language_array, $global_config, $array_samples_data;

    $xtpl = new XTemplate('theme.tpl', NV_ROOTDIR . '/install/tpl');
    $xtpl->assign('BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('NV_FILES_DIR', NV_FILES_DIR);
    $xtpl->assign('LANG_VARIABLE', NV_LANG_VARIABLE);
    $xtpl->assign('LANG_DATA', NV_LANG_DATA);
    $xtpl->assign('MAIN_TITLE', $titletheme);
    $xtpl->assign('MAIN_STEP', $step);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('VERSION', 'v' . $global_config['version']);

    $step_bar = [
        $lang_module['select_language'],
        $lang_module['check_chmod'],
        $lang_module['license'],
        $lang_module['check_server'],
        $lang_module['config_database'],
        $lang_module['website_info'],
        $lang_module['sample_data'],
        $lang_module['done']
    ];

    foreach ($step_bar as $i => $step_bar_i) {
        $n = $i + 1;
        $class = '';

        if ($n == 7 and empty($array_samples_data)) {
            continue;
        }
        if ($step >= $n) {
            $class = ' class="';
            $class .= ($step > $n) ? 'passed_step' : '';
            $class .= ($step == $n) ? 'current_step' : '';
            $class .= '"';
        }

        $xtpl->assign('CLASS_STEP', $class);
        $xtpl->assign('STEP_BAR', $step_bar_i);
        $xtpl->assign('NUM', ($n >= 7 and empty($array_samples_data)) ? ($n - 1) : $n);
        $xtpl->parse('main.step_bar.loop');
    }

    $xtpl->assign('LANGTYPESL', NV_LANG_DATA);
    $langname = $language_array[NV_LANG_DATA]['name'];
    $xtpl->assign('LANGNAMESL', $langname);

    foreach ($languageslist as $languageslist_i) {
        if (!empty($languageslist_i) and (NV_LANG_DATA != $languageslist_i)) {
            $xtpl->assign('LANGTYPE', $languageslist_i);
            $langname = $language_array[$languageslist_i]['name'];
            $xtpl->assign('LANGNAME', $langname);
            $xtpl->parse('main.looplang');
        }
    }

    $xtpl->parse('main.step_bar');
    $xtpl->assign('MAIN_CONTENT', $contenttheme);
    $xtpl->parse('main');
    $xtpl->out('main');
}

/**
 * nv_step_1()
 *
 * @return
 */
function nv_step_1()
{
    global $lang_module, $languageslist, $language_array, $sys_info, $global_config;

    $xtpl = new XTemplate('step1.tpl', NV_ROOTDIR . '/install/tpl');
    $xtpl->assign('BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('LANG_VARIABLE', NV_LANG_VARIABLE);

    foreach ($languageslist as $languageslist_i) {
        if (!empty($languageslist_i)) {
            $langname = (isset($language_array[$languageslist_i]['name_' . NV_LANG_DATA])) ? $language_array[$languageslist_i]['name_' . NV_LANG_DATA] : $language_array[$languageslist_i]['name'];

            $xtpl->assign('LANGTYPE', $languageslist_i);
            $xtpl->assign('SELECTED', (NV_LANG_DATA == $languageslist_i) ? ' selected="selected"' : '');
            $xtpl->assign('LANGNAME', $langname);
            $xtpl->parse('step.languagelist');
        }
    }

    $xtpl->assign('CURRENTLANG', NV_LANG_DATA);
    $xtpl->assign('LANG', $lang_module);

    if ($global_config['unofficial_mode']) {
        $xtpl->parse('step.unofficial_mode');
    }

    if (empty($sys_info['supports_rewrite'])) {
        $xtpl->assign('SUPPORTS_REWRITE', NV_CHECK_SESSION);
        $xtpl->parse('step.check_supports_rewrite');
    }

    $xtpl->parse('step');

    return $xtpl->text('step');
}

/**
 * nv_step_2()
 *
 * @param mixed $array_dir_check
 * @param mixed $array_ftp_data
 * @param mixed $nextstep
 * @return
 */
function nv_step_2($array_dir_check, $array_ftp_data, $nextstep)
{
    global $lang_module, $sys_info, $step;

    $xtpl = new XTemplate('step2.tpl', NV_ROOTDIR . '/install/tpl');
    $xtpl->assign('BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('LANG_VARIABLE', NV_LANG_VARIABLE);
    $xtpl->assign('CURRENTLANG', NV_LANG_DATA);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('ACTIONFORM', NV_BASE_SITEURL . 'install/index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&step=' . $step);

    if ($nextstep) {
        $xtpl->parse('step.nextstep');
    } elseif ($sys_info['ftp_support'] and strpos($sys_info['os'], 'WIN') === false) {
        $xtpl->assign('FTPDATA', $array_ftp_data);
        $xtpl->parse('step.ftpconfig.errorftp');
        $xtpl->parse('step.ftpconfig');
    }

    $a = 0;
    foreach ($array_dir_check as $dir => $check) {
        $class = ($a % 2 == 0) ? 'spec text_normal' : 'specalt text_normal';

        $xtpl->assign('DATAFILE', [
            'dir' => $dir,
            'check' => $check,
            'classcheck' => ($check == $lang_module['dir_writable']) ? 'highlight_green' : 'highlight_red',
            'class' => $class
        ]);

        $xtpl->parse('step.loopdir');
        ++$a;
    }

    if (!(strpos($sys_info['os'], 'WIN') === false)) {
        if ($nextstep) {
            $xtpl->parse('step.winhost.infonext');
        } else {
            $xtpl->parse('step.winhost.inforeload');
        }
        $xtpl->parse('step.winhost');
    }

    $xtpl->parse('step');

    return $xtpl->text('step');
}

/**
 * nv_step_3()
 *
 * @param mixed $license
 * @return
 */
function nv_step_3($license)
{
    global $lang_module;

    $xtpl = new XTemplate('step3.tpl', NV_ROOTDIR . '/install/tpl');
    $xtpl->assign('BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('LANG_VARIABLE', NV_LANG_VARIABLE);
    $xtpl->assign('CONTENT_LICENSE', $license);
    $xtpl->assign('CURRENTLANG', NV_LANG_DATA);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->parse('step');

    return $xtpl->text('step');
}

/**
 * nv_step_4()
 *
 * @param mixed $array_resquest
 * @param mixed $array_support
 * @param mixed $nextstep
 * @return
 */
function nv_step_4($array_resquest, $array_support, $nextstep)
{
    global $lang_module;

    $xtpl = new XTemplate('step4.tpl', NV_ROOTDIR . '/install/tpl');
    $xtpl->assign('BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('LANG_VARIABLE', NV_LANG_VARIABLE);
    $xtpl->assign('CURRENTLANG', NV_LANG_DATA);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('DATA_REQUEST', $array_resquest);
    $xtpl->assign('DATA_SUPPORT', $array_support);

    if ($nextstep) {
        $xtpl->parse('step.nextstep');
    }

    $xtpl->parse('step');

    return $xtpl->text('step');
}

/**
 * nv_step_5()
 *
 * @param mixed $db_config
 * @param mixed $nextstep
 * @return
 */
function nv_step_5($db_config, $nextstep)
{
    global $lang_module, $step, $PDODrivers;

    $xtpl = new XTemplate('step5.tpl', NV_ROOTDIR . '/install/tpl');
    $xtpl->assign('BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('LANG_VARIABLE', NV_LANG_VARIABLE);
    $xtpl->assign('CURRENTLANG', NV_LANG_DATA);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('DATADASE', $db_config);
    $xtpl->assign('ACTIONFORM', NV_BASE_SITEURL . 'install/index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&step=' . $step);

    $lang_pdo = [];
    $lang_pdo['pdo_cubrid'] = 'Cubrid';
    $lang_pdo['pdo_dblib'] = 'FreeTDS / Microsoft SQL Server / Sybase';
    $lang_pdo['pdo_firebird'] = 'Firebird';
    $lang_pdo['pdo_ibm'] = 'IBM DB2 ';
    $lang_pdo['pdo_informix'] = 'IBM Informix Dynamic Server';
    $lang_pdo['pdo_mysql'] = 'MySQL 5.x / MariaDB';
    $lang_pdo['pdo_oci'] = 'Oracle';
    $lang_pdo['pdo_odbc'] = 'ODBC v3 (IBM DB2, unixODBC and win32 ODBC)';
    $lang_pdo['pdo_pgsql'] = 'PostgreSQL';
    $lang_pdo['pdo_sqlite'] = ' SQLite 3 and SQLite 2 ';
    $lang_pdo['pdo_sqlsrv'] = 'Microsoft SQL Server / SQL Azure';
    $lang_pdo['pdo_4d'] = '4D';

    foreach ($PDODrivers as $value) {
        $array_dbtype = [];
        $array_dbtype['value'] = $value;
        $array_dbtype['selected'] = ($db_config['dbtype'] == $value) ? ' selected="selected"' : '';
        $array_dbtype['text'] = (isset($lang_pdo['pdo_' . $value])) ? $lang_pdo['pdo_' . $value] : $value;

        $xtpl->assign('DBTYPE', $array_dbtype);
        $xtpl->parse('step.dbtype');
    }

    if ($db_config['num_table'] > 0) {
        $xtpl->parse('step.db_detete');
    }

    if (!empty($db_config['error'])) {
        $xtpl->parse('step.errordata');
    }

    if ($nextstep) {
        $xtpl->parse('step.nextstep');
    }

    $xtpl->parse('step');

    return $xtpl->text('step');
}

/**
 * nv_step_6()
 *
 * @param mixed $array_data
 * @param mixed $nextstep
 * @return
 */
function nv_step_6($array_data, $nextstep)
{
    global $lang_module, $step;

    $xtpl = new XTemplate('step6.tpl', NV_ROOTDIR . '/install/tpl');
    $xtpl->assign('BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('LANG_VARIABLE', NV_LANG_VARIABLE);
    $xtpl->assign('CURRENTLANG', NV_LANG_DATA);
    $xtpl->assign('LANG', $lang_module);

    $array_data['dev_mode'] = empty($array_data['dev_mode']) ? '' : ' checked="checked"';

    $xtpl->assign('DATA', $array_data);
    $xtpl->assign('ACTIONFORM', NV_BASE_SITEURL . 'install/index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&step=' . $step);
    $xtpl->assign('CHECK_LANG_MULTI', ($array_data['lang_multi']) ? ' checked="checked"' : '');

    if (!empty($array_data['error'])) {
        $xtpl->parse('step.errordata');
    }

    if ($nextstep) {
        $xtpl->parse('step.nextstep');
    }

    $xtpl->parse('step');

    return $xtpl->text('step');
}

/**
 * nv_step_7()
 *
 * @param mixed $array_data
 * @param mixed $nextstep
 * @return
 */
function nv_step_7($array_data, $nextstep)
{
    // Chú ý không xóa global $db_config vì bên dưới có dùng khi require
    global $lang_module, $step, $array_samples_data, $db_config;

    $xtpl = new XTemplate('step7.tpl', NV_ROOTDIR . '/install/tpl');
    $xtpl->assign('BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('LANG_VARIABLE', NV_LANG_VARIABLE);
    $xtpl->assign('CURRENTLANG', NV_LANG_DATA);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('DATA', $array_data);
    $xtpl->assign('ACTIONFORM', NV_BASE_SITEURL . 'install/index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&step=' . $step);

    foreach ($array_samples_data as $key => $data) {
        require NV_ROOTDIR . '/install/samples/' . $data;
        unset($sql_create_table);
        $data = substr(substr($data, 0, -4), 5);
        $row = [
            'url' => $sample_base_siteurl,
            'compatible' => $sample_base_siteurl == NV_BASE_SITEURL ? true : false,
            'title' => $data
        ];
        $xtpl->assign('ROW', $row);
        $xtpl->assign('ROWKEY', $key);

        if ($row['compatible']) {
            $xtpl->assign('MESSAGE', $lang_module['spdata_compatible']);
        } else {
            $xtpl->assign('MESSAGE', sprintf($lang_module['spdata_incompatible'], ($row['url'] == '/' ? $lang_module['spdata_root'] : trim($row['url'], '/')), (NV_BASE_SITEURL == '/' ? $lang_module['spdata_root'] : trim(NV_BASE_SITEURL, '/'))));
        }

        $xtpl->parse('step.loop');
    }

    if (!empty($array_data['error'])) {
        $xtpl->parse('step.errordata');
    }

    if ($nextstep) {
        $xtpl->parse('step.nextstep');
    }

    $xtpl->parse('step');

    return $xtpl->text('step');
}

/**
 * nv_step_8()
 *
 * @param mixed $finish
 * @return
 */
function nv_step_8($finish)
{
    global $lang_module;

    $xtpl = new XTemplate('step8.tpl', NV_ROOTDIR . '/install/tpl');
    $xtpl->assign('BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('ADMINDIR', NV_ADMINDIR);
    $xtpl->assign('LANG_VARIABLE', NV_LANG_VARIABLE);
    $xtpl->assign('CURRENTLANG', NV_LANG_DATA);
    $xtpl->assign('LANG', $lang_module);

    if ($finish == 1) {
        $xtpl->parse('step.finish1');
    } else {
        $xtpl->parse('step.finish2');
    }

    $xtpl->parse('step');

    return $xtpl->text('step');
}
