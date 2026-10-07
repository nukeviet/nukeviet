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
 * Sử dụng nhiều thư mục tạm cho trình biên dịch (phòng khi chưa chmod)
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
 * Layout dùng chung cho luồng cài đặt và luồng nâng cấp
 *
 * @param array $page Các khóa:
 *  - site_title: tiêu đề chính ở header
 *  - version: phiên bản hiển thị cạnh tiêu đề
 *  - title: tiêu đề bước hiện tại
 *  - content: nội dung bước
 *  - steps: số bước => tên bước, bước bị bỏ qua thì không có trong mảng
 *  - step: số bước hiện tại
 *  - lang: mã ngôn ngữ đang dùng
 *  - langs: mã ngôn ngữ => ['name' => tên, 'url' => link chuyển]
 *  - modal_title: tiêu đề mặc định của modal thông báo lỗi
 *  - scripts: các file JS nạp thêm
 * @return string
 */
function nv_install_theme($page)
{
    global $nv_Lang;

    // Đánh số lại các bước hiển thị để không bị nhảy số khi có bước bị bỏ qua
    $steps = [];
    $current_num = 0;
    foreach ($page['steps'] as $n => $name) {
        $num = count($steps) + 1;
        if ($n == $page['step']) {
            $current_num = $num;
        }
        $steps[] = [
            'num' => $num,
            'name' => $name,
            'status' => $page['step'] > $n ? 'passed' : ($page['step'] == $n ? 'current' : '')
        ];
    }

    $tpl = nv_install_tpl();
    $tpl->assign('SITE_TITLE', $page['site_title']);
    $tpl->assign('VERSION', $page['version']);
    $tpl->assign('MAIN_TITLE', $page['title']);
    $tpl->assign('MAIN_CONTENT', $page['content']);
    $tpl->assign('STEPS', $steps);
    $tpl->assign('CURRENT_NUM', $current_num);
    $tpl->assign('PROGRESS', round($current_num / count($steps) * 100));
    $tpl->assign('LANG_CODE', $page['lang']);
    $tpl->assign('LANGS', $page['langs']);
    $tpl->assign('MODAL_TITLE', $page['modal_title']);
    $tpl->assign('SCRIPTS', $page['scripts'] ?? []);
    $tpl->assign('YEAR', date('Y', NV_CURRENTTIME));

    return $tpl->fetch('theme.tpl');
}
