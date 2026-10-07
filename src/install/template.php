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

/**
 * Khởi tạo NVSmarty cho trình cài đặt
 *
 * Bước 1 chạy trước khi kiểm tra quyền ghi thư mục nên data/cache có thể chưa ghi được,
 * khi đó biên dịch template vào data/tmp hoặc thư mục tạm của hệ thống
 *
 * @return \NukeViet\Template\NVSmarty
 */
function nv_install_tpl()
{
    global $nv_Lang;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(NV_ROOTDIR . '/install/tpl');
    $tpl->setCompileCheck(\Smarty\Smarty::COMPILECHECK_ON);

    $compile_dirs = [
        NV_ROOTDIR . '/' . NV_CACHEDIR,
        NV_ROOTDIR . '/' . NV_TEMP_DIR,
        rtrim(str_replace('\\', '/', sys_get_temp_dir()), '/') . '/nv-install-' . md5(NV_ROOTDIR)
    ];
    foreach ($compile_dirs as $dir) {
        $compile_dir = $dir . '/' . \NukeViet\Template\NVSmarty::COMPILEDIR;
        if (is_dir($compile_dir) ? is_writable($compile_dir) : (is_dir($dir) ? is_writable($dir) : is_writable(dirname($dir)))) {
            $tpl->setCompileDir($compile_dir);
            break;
        }
    }

    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('STEP_URL', NV_BASE_SITEURL . 'install/index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;t=' . NV_CURRENTTIME . '&amp;step=');

    return $tpl;
}

/**
 * Giao diện chung của trình cài đặt
 *
 * @param int $step
 * @param string $titletheme
 * @param string $contenttheme
 * @return string
 */
function nv_site_theme($step, $titletheme, $contenttheme)
{
    global $nv_Lang, $languageslist, $language_array, $global_config, $array_samples_data;

    $step_bar = [
        1 => $nv_Lang->getModule('select_language'),
        2 => $nv_Lang->getModule('check_chmod'),
        3 => $nv_Lang->getModule('license'),
        4 => $nv_Lang->getModule('check_server'),
        5 => $nv_Lang->getModule('config_database'),
        6 => $nv_Lang->getModule('website_info'),
        7 => $nv_Lang->getModule('sample_data'),
        8 => $nv_Lang->getModule('done')
    ];
    if (empty($array_samples_data)) {
        unset($step_bar[7]);
    }

    // Đánh số lại các bước hiển thị khi bỏ qua bước dữ liệu mẫu
    $steps = [];
    $current_num = 0;
    foreach ($step_bar as $n => $name) {
        $num = count($steps) + 1;
        if ($n == $step) {
            $current_num = $num;
        }
        $steps[] = [
            'num' => $num,
            'name' => $name,
            'status' => $step > $n ? 'passed' : ($step == $n ? 'current' : '')
        ];
    }

    $langs = [];
    foreach ($languageslist as $lang) {
        if (!empty($lang)) {
            $langs[$lang] = $language_array[$lang]['name'];
        }
    }

    $tpl = nv_install_tpl();
    $tpl->assign('MAIN_TITLE', $titletheme);
    $tpl->assign('MAIN_STEP', $step);
    $tpl->assign('MAIN_CONTENT', $contenttheme);
    $tpl->assign('VERSION', $global_config['version']);
    $tpl->assign('STEPS', $steps);
    $tpl->assign('CURRENT_NUM', $current_num);
    $tpl->assign('PROGRESS', round($current_num / count($steps) * 100));
    $tpl->assign('YEAR', date('Y', NV_CURRENTTIME));
    $tpl->assign('LANGS', $langs);

    return $tpl->fetch('theme.tpl');
}

/**
 * Bước 1: Chọn ngôn ngữ
 *
 * @return string
 */
function nv_step_1()
{
    global $languageslist, $language_array, $sys_info, $global_config;

    $langs = [];
    foreach ($languageslist as $lang) {
        if (!empty($lang)) {
            $langs[$lang] = $language_array[$lang]['name_' . NV_LANG_DATA] ?? $language_array[$lang]['name'];
        }
    }

    $tpl = nv_install_tpl();
    $tpl->assign('LANGS', $langs);
    $tpl->assign('UNOFFICIAL_MODE', !empty($global_config['unofficial_mode']));
    $tpl->assign('CHECK_REWRITE', empty($sys_info['supports_rewrite']));

    return $tpl->fetch('step1.tpl');
}

/**
 * Bước 2: Kiểm tra quyền ghi thư mục
 *
 * @param array $array_dir_check
 * @param array $array_ftp_data
 * @param int $nextstep
 * @return string
 */
function nv_step_2($array_dir_check, $array_ftp_data, $nextstep)
{
    global $nv_Lang, $sys_info, $step;

    $is_win = str_contains($sys_info['os'], 'WIN');

    $dirs = [];
    foreach ($array_dir_check as $dir => $check) {
        $dirs[] = [
            'dir' => $dir,
            'check' => $check,
            'ok' => $check == $nv_Lang->getModule('dir_writable')
        ];
    }

    $tpl = nv_install_tpl();
    $tpl->assign('ACTIONFORM', NV_BASE_SITEURL . 'install/index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;step=' . $step);
    $tpl->assign('NEXTSTEP', $nextstep);
    $tpl->assign('IS_WIN', $is_win);
    $tpl->assign('SHOW_FTP', !$nextstep and $sys_info['ftp_support'] and !$is_win);
    $tpl->assign('FTPDATA', $array_ftp_data);
    $tpl->assign('DIRS', $dirs);

    return $tpl->fetch('step2.tpl');
}

/**
 * Bước 3: Giấy phép sử dụng
 *
 * @param string $license
 * @return string
 */
function nv_step_3($license)
{
    $tpl = nv_install_tpl();
    $tpl->assign('CONTENT_LICENSE', $license);

    return $tpl->fetch('step3.tpl');
}

/**
 * Bước 4: Kiểm tra máy chủ
 *
 * @param array $array_resquest
 * @param array $array_support
 * @param int $nextstep
 * @return string
 */
function nv_step_4($array_resquest, $array_support, $nextstep)
{
    global $nv_Lang;

    $required = $nv_Lang->getModule('required_on');
    $request = $nv_Lang->getModule('request');

    $requests = [
        'php_support' => [$nv_Lang->getModule('php_version') . ': ' . $array_resquest['php_version'], $required . ' &gt;= ' . $array_resquest['php_required_min'] . ' ' . $nv_Lang->getModule('and') . ' &lt;= ' . $array_resquest['php_allowed_max']],
        'pdo_support' => [$nv_Lang->getModule('pdo_support') . ' (PDO)', $required],
        'curl_support' => [$nv_Lang->getModule('curl_support'), $required],
        'opendir_support' => [$nv_Lang->getModule('opendir_support'), $request],
        'gd_support' => [$nv_Lang->getModule('gd_support'), $request],
        'xml_support' => [$nv_Lang->getModule('xml_support'), $request],
        'openssl_support' => [$nv_Lang->getModule('openssl_support'), $request],
        'session_support' => [$nv_Lang->getModule('session_support'), $request],
        'mb_support' => ['Extension Mbstring Support', $request],
        'fileuploads_support' => [$nv_Lang->getModule('fileuploads_support'), $request],
        'json_support' => [$nv_Lang->getModule('json_support'), $request]
    ];
    $supports = [
        'supports_rewrite' => [$nv_Lang->getModule('supports_rewrite'), $nv_Lang->getModule('is_support')],
        'output_buffering' => ['Output Buffering', $nv_Lang->getModule('turnoff')],
        'session_auto_start' => ['Session Auto Start', $nv_Lang->getModule('turnoff')],
        'display_errors' => ['Display Errors', $nv_Lang->getModule('turnoff')],
        'allowed_set_time_limit' => ['Set_time_limit()', $nv_Lang->getModule('turnon')],
        'zlib_support' => ['Zlib Compression Support', $nv_Lang->getModule('is_support')],
        'zip_support' => ['Extension Zip Support', $nv_Lang->getModule('is_support')]
    ];

    $tpl = nv_install_tpl();
    $tpl->assign('REQUESTS', nv_step_4_rows($requests, $array_resquest));
    $tpl->assign('SUPPORTS', nv_step_4_rows($supports, $array_support));
    $tpl->assign('NEXTSTEP', $nextstep);

    return $tpl->fetch('step4.tpl');
}

/**
 * Ghép tên, ghi chú với kết quả kiểm tra thành các dòng hiển thị ở bước 4
 *
 * @param array $items key => [tên, ghi chú]
 * @param array $result key => tên kết quả, ok_key => đạt hay không
 * @return array
 */
function nv_step_4_rows($items, $result)
{
    $rows = [];
    foreach ($items as $key => $item) {
        $rows[] = [
            'name' => $item[0],
            'note' => $item[1],
            'result' => $result[$key],
            'ok' => !empty($result['ok_' . $key])
        ];
    }

    return $rows;
}

/**
 * Bước 5: Cấu hình CSDL
 *
 * @param array $db_config
 * @param int $nextstep
 * @return string
 */
function nv_step_5($db_config, $nextstep)
{
    global $step, $PDODrivers;

    $lang_pdo = [
        'cubrid' => 'Cubrid',
        'dblib' => 'FreeTDS / Microsoft SQL Server / Sybase',
        'firebird' => 'Firebird',
        'ibm' => 'IBM DB2',
        'informix' => 'IBM Informix Dynamic Server',
        'mysql' => 'MySQL 5.x / MariaDB',
        'oci' => 'Oracle',
        'odbc' => 'ODBC v3 (IBM DB2, unixODBC and win32 ODBC)',
        'pgsql' => 'PostgreSQL',
        'sqlite' => 'SQLite 3 and SQLite 2',
        'sqlsrv' => 'Microsoft SQL Server / SQL Azure',
        '4d' => '4D'
    ];

    $dbtypes = [];
    foreach ($PDODrivers as $value) {
        $dbtypes[$value] = $lang_pdo[$value] ?? $value;
    }

    $tpl = nv_install_tpl();
    $tpl->assign('DATABASE', $db_config);
    $tpl->assign('DBTYPES', $dbtypes);
    $tpl->assign('ACTIONFORM', NV_BASE_SITEURL . 'install/index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;step=' . $step);
    $tpl->assign('NEXTSTEP', $nextstep);

    return $tpl->fetch('step5.tpl');
}

/**
 * Bước 6: Thông tin website và tài khoản quản trị
 *
 * @param array $array_data
 * @param int $nextstep
 * @return string
 */
function nv_step_6($array_data, $nextstep)
{
    global $step;

    $tpl = nv_install_tpl();
    $tpl->assign('DATA', $array_data);
    $tpl->assign('ACTIONFORM', NV_BASE_SITEURL . 'install/index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;step=' . $step);
    $tpl->assign('NEXTSTEP', $nextstep);

    return $tpl->fetch('step6.tpl');
}

/**
 * Bước 7: Dữ liệu mẫu
 *
 * @param array $array_data
 * @param int $nextstep
 * @return string
 */
function nv_step_7($array_data, $nextstep)
{
    // Chú ý không xóa global $db_config vì bên dưới có dùng khi require
    global $nv_Lang, $step, $array_samples_data, $db_config;

    $samples = [];
    foreach ($array_samples_data as $key => $data) {
        require NV_ROOTDIR . '/install/samples/' . $data;
        unset($sql_create_table);

        $compatible = $sample_base_siteurl == NV_BASE_SITEURL;
        $samples[] = [
            'key' => $key,
            'title' => substr(substr($data, 0, -4), 5),
            'compatible' => $compatible,
            'message' => $compatible ? $nv_Lang->getModule('spdata_compatible') : $nv_Lang->getModule('spdata_incompatible', ($sample_base_siteurl == '/' ? $nv_Lang->getModule('spdata_root') : trim($sample_base_siteurl, '/')), (NV_BASE_SITEURL == '/' ? $nv_Lang->getModule('spdata_root') : trim(NV_BASE_SITEURL, '/')))
        ];
    }

    $tpl = nv_install_tpl();
    $tpl->assign('DATA', $array_data);
    $tpl->assign('SAMPLES', $samples);
    $tpl->assign('ACTIONFORM', NV_BASE_SITEURL . 'install/index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;step=' . $step);
    $tpl->assign('NEXTSTEP', $nextstep);

    return $tpl->fetch('step7.tpl');
}

/**
 * Bước 8: Hoàn tất
 *
 * @param int $finish 1 là thành công, 2 là chưa chuyển được file cấu hình
 * @return string
 */
function nv_step_8($finish)
{
    $tpl = nv_install_tpl();
    $tpl->assign('FINISH', $finish);

    return $tpl->fetch('step8.tpl');
}
