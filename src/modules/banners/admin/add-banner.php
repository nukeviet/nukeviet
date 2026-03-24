<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$page_title = $nv_Lang->getModule('admin_add_banner');

// Kiểm tra upload có bị khóa không
$file_allowed_ext = [];
if (preg_match('/images/', NV_ALLOW_FILES_TYPE)) {
    $file_allowed_ext[] = 'images';
}

if (empty($file_allowed_ext)) {
    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('add-banner.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('OP', $op);
    $tpl->assign('CHECKSS', csrf_create($csrf_key));
    $tpl->assign('UPLOAD_BLOCKED_MSG', $nv_Lang->getModule('admin_upload_blocked'));
    $tpl->assign('PLANS', []);
    $tpl->assign('TARGETS', $targets);
    $tpl->assign('ITEM', []);
    $tpl->assign('HOUR_OPTIONS', []);
    $tpl->assign('MIN_OPTIONS', []);
    $tpl->assign('FILE_ALLOWED_EXT', '');
    $tpl->assign('BANNERHTML', '');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($tpl->fetch('add-banner.tpl'));
    include NV_ROOTDIR . '/includes/footer.php';
}

// Lấy danh sách khối banner
$array_plans = [];
$sql = 'SELECT id, title, blang, form, require_image, exp_time FROM ' . NV_BANNERS_GLOBALTABLE . '_plans ORDER BY blang, title ASC';
$result = $db->query($sql);
while ($row = $result->fetch()) {
    $array_plans[$row['id']] = [
        'key'           => $row['id'],
        'title'         => $row['title'] . ' (' . (!empty($row['blang']) ? $language_array[$row['blang']]['name'] : $nv_Lang->getModule('blang_all')) . ')',
        'require_image' => (bool) $row['require_image'],
        'has_exp'       => ($row['exp_time'] > 0),
        'form'          => $row['form'],
        'exp_time'      => $row['exp_time'],
    ];
}

if (empty($array_plans)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=plan-content');
}

if ($nv_Request->get_int('save', 'post') == 1) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $title = nv_htmlspecialchars(strip_tags($nv_Request->get_string('title', 'post', '')));
    $pid = $nv_Request->get_int('pid', 'post', 0);
    $file_alt = nv_htmlspecialchars(strip_tags($nv_Request->get_string('file_alt', 'post', '')));
    $target = $nv_Request->get_string('target', 'post', '');
    if (!isset($targets[$target])) {
        $target = '_blank';
    }
    $bannerhtml = $nv_Request->get_editor('bannerhtml', '', NV_ALLOWED_HTML_TAGS);
    $click_url = strip_tags($nv_Request->get_string('click_url', 'post', ''));
    $publ_date = strip_tags($nv_Request->get_string('publ_date', 'post', ''));
    $publ_date_h = $nv_Request->get_int('publ_date_h', 'post', 0);
    $publ_date_m = $nv_Request->get_int('publ_date_m', 'post', 0);
    $exp_date = strip_tags($nv_Request->get_string('exp_date', 'post', ''));
    $exp_date_h = $nv_Request->get_int('exp_date_h', 'post', 0);
    $exp_date_m = $nv_Request->get_int('exp_date_m', 'post', 0);
    $assign_user = $nv_Request->get_title('assign_user', 'post', '');
    $assign_user_id = $admin_info['userid'];

    if (!empty($publ_date) and !preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $publ_date)) {
        $publ_date = '';
    }
    if (!empty($exp_date) and !preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $exp_date)) {
        $exp_date = '';
    }
    if ($publ_date_h < 0 or $publ_date_h > 23) {
        $publ_date_h = 0;
    }
    if ($exp_date_h < 0 or $exp_date_h > 23) {
        $exp_date_h = 0;
    }
    if ($publ_date_m < 0 or $publ_date_m > 59) {
        $publ_date_m = 0;
    }
    if ($exp_date_m < 0 or $exp_date_m > 59) {
        $exp_date_m = 0;
    }

    $click_url_allow = !empty($click_url) ? nv_is_url($click_url, true) : true;

    $sql = 'SELECT require_image FROM ' . NV_BANNERS_GLOBALTABLE . '_plans where id = ' . $pid;
    $result = $db->query($sql);
    $array_require_image = $result->fetchAll();

    $error_assign_user = '';
    if (!empty($assign_user)) {
        $sql = 'SELECT userid FROM ' . NV_USERS_GLOBALTABLE . ' WHERE active=1 AND username=:username';
        $sth = $db->prepare($sql);
        $sth->bindParam(':username', $assign_user, PDO::PARAM_STR);
        $sth->execute();
        if ($sth->rowCount() != 1) {
            $error_assign_user = $nv_Lang->getModule('assign_to_user_err', $assign_user);
        } else {
            $assign_user_id = $sth->fetchColumn();
        }
    }

    if (empty($title)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('title_empty'),
            'input' => 'title'
        ]);
    }
    if (empty($pid) or !isset($array_plans[$pid])) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('plan_not_selected'),
            'input' => 'pid'
        ]);
    }
    if (!empty($error_assign_user)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $error_assign_user,
            'input' => 'assign_user'
        ]);
    }
    if (!is_uploaded_file($_FILES['banner']['tmp_name']) and $array_require_image[0]['require_image'] == 1) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('file_upload_empty'),
            'input' => 'banner'
        ]);
    }
    if (!$click_url_allow) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('click_url_invalid'),
            'input' => 'click_url'
        ]);
    }

    $imageforswf = '';

    if (empty($publ_date)) {
        $publtime = NV_CURRENTTIME;
    } else {
        unset($m);
        preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $publ_date, $m);
        $publtime = mktime($publ_date_h, $publ_date_m, 0, $m[2], $m[1], $m[3]);
    }

    if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $exp_date, $m)) {
        $exptime = mktime($exp_date_h, $exp_date_m, 59, $m[2], $m[1], $m[3]);
        if ($exptime <= $publtime) {
            $exptime = $publtime;
        }
    } else {
        if (!empty($array_plans[$pid]['exp_time'])) {
            $exptime = $publtime + $array_plans[$pid]['exp_time'];
        } else {
            $exptime = 0;
        }
    }
    if ($exptime != 0 and $exptime <= $publtime) {
        $exptime = $publtime;
    }

    $act = (empty($exptime) or $exptime > NV_CURRENTTIME) ? ($publtime > NV_CURRENTTIME ? 0 : 1) : 2;

    $_weight = 0;
    if ($array_plans[$pid]['form'] == 'sequential' and $act != 2) {
        $_weight = $db->query('SELECT COUNT(*) FROM ' . NV_BANNERS_GLOBALTABLE . '_rows WHERE act IN(0,1,3) AND pid=' . $pid)->fetchColumn();
        $_weight = (int) $_weight + 1;
    }

    // Upload ảnh trên mobile
    if (isset($_FILES['imageforswf']) and is_uploaded_file($_FILES['imageforswf']['tmp_name'])) {
        $upload = new NukeViet\Files\Upload($file_allowed_ext, $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT);
        $upload->setLanguage(\NukeViet\Core\Language::$lang_global);
        $upload_info = $upload->save_file($_FILES['imageforswf'], NV_UPLOADS_REAL_DIR . '/' . NV_BANNER_DIR, false);
        @unlink($_FILES['imageforswf']['tmp_name']);

        if (!empty($upload_info['error'])) {
            nv_jsonOutput(['status' => 'error', 'mess' => $upload_info['error']]);
        }
        @chmod($upload_info['name'], 0644);
        $imageforswf = $upload_info['basename'];
    }

    if (!is_uploaded_file($_FILES['banner']['tmp_name'])) {
        $file_name = 'no_image';
        $file_ext = 'no_image';
        $file_mime = 'no_image';
        $width = 0;
        $height = 0;
        $_sql = 'INSERT INTO ' . NV_BANNERS_GLOBALTABLE . '_rows (
            title, pid, clid, file_name, file_ext, file_mime, width, height, file_alt, imageforswf, click_url, target, bannerhtml,
            add_time, publ_time, exp_time, hits_total, act, weight
        ) VALUES (
            :title, ' . $pid . ', ' . $assign_user_id . ', :file_name, :file_ext, :file_mime,
            ' . $width . ', ' . $height . ', :file_alt, :imageforswf, :click_url, :target, :bannerhtml, ' . NV_CURRENTTIME . ', ' . $publtime . ', ' . $exptime . ',
            0, ' . $act . ', ' . $_weight . '
        )';

        $data_insert = [
            'title'      => $title,
            'file_name'  => $file_name,
            'file_ext'   => $file_ext,
            'file_mime'  => $file_mime,
            'file_alt'   => $file_alt,
            'imageforswf'=> $imageforswf,
            'click_url'  => $click_url,
            'target'     => $target,
            'bannerhtml' => $bannerhtml,
        ];
        $id = $db->insert_id($_sql, 'id', $data_insert);
    } else {
        $upload = new NukeViet\Files\Upload($file_allowed_ext, $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT);
        $upload->setLanguage(\NukeViet\Core\Language::$lang_global);
        $upload_info = $upload->save_file($_FILES['banner'], NV_UPLOADS_REAL_DIR . '/' . NV_BANNER_DIR, false);
        @unlink($_FILES['banner']['tmp_name']);

        if (!empty($upload_info['error'])) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $upload_info['error']
            ]);
        }

        @chmod($upload_info['name'], 0644);
        $file_name = $upload_info['basename'];
        $file_ext = $upload_info['ext'];
        $file_mime = $upload_info['mime'];
        $width = $upload_info['img_info'][0];
        $height = $upload_info['img_info'][1];

        $_sql = 'INSERT INTO ' . NV_BANNERS_GLOBALTABLE . '_rows (
            title, pid, clid, file_name, file_ext, file_mime, width, height, file_alt, imageforswf,
            click_url, target, bannerhtml, add_time, publ_time, exp_time, hits_total, act, weight
        ) VALUES (
            :title, ' . $pid . ', ' . $assign_user_id . ', :file_name, :file_ext, :file_mime,
            ' . $width . ', ' . $height . ', :file_alt, :imageforswf, :click_url, :target, :bannerhtml, ' . NV_CURRENTTIME . ', ' . $publtime . ', ' . $exptime . ',
            0, ' . $act . ', ' . $_weight . '
        )';

        $data_insert = [
            'title'      => $title,
            'file_name'  => $file_name,
            'file_ext'   => $file_ext,
            'file_mime'  => $file_mime,
            'file_alt'   => $file_alt,
            'imageforswf'=> $imageforswf,
            'click_url'  => $click_url,
            'target'     => $target,
            'bannerhtml' => $bannerhtml,
        ];
        $id = $db->insert_id($_sql, 'id', $data_insert);
    }

    nv_insert_logs(NV_LANG_DATA, $module_name, 'log_add_banner', 'bannerid ' . $id, $admin_info['userid']);
    nv_CreateXML_bannerPlan();
    $nv_Cache->delMod($module_name);
    nv_jsonOutput([
        'status'   => 'OK',
        'mess'     => '',
        'redirect' => nv_url_rewrite(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=info-banner&id=' . $id, true),
    ]);
}

// Khởi tạo giá trị mặc định cho form
$pid = 0;
$title = $file_alt = $click_url = '';
$target = '_blank';
$bannerhtml = '';
$publ_date = '';
$publ_date_h = 0;
$publ_date_m = 0;
$exp_date = '';
$exp_date_h = 23;
$exp_date_m = 59;
$assign_user = '';

if ($nv_Request->get_bool('pid', 'get') and isset($array_plans[$nv_Request->get_int('pid', 'get')])) {
    $pid = $nv_Request->get_int('pid', 'get');
}

// Chuẩn bị danh sách giờ và phút
$hour_options = [];
for ($i = 0; $i <= 23; ++$i) {
    $hour_options[] = ['key' => $i, 'title' => str_pad($i, 2, '0', STR_PAD_LEFT)];
}
$min_options = [];
for ($i = 0; $i <= 59; ++$i) {
    $min_options[] = ['key' => $i, 'title' => str_pad($i, 2, '0', STR_PAD_LEFT)];
}

// Chuẩn bị nội dung editor
$bannerhtml_escaped = htmlspecialchars(nv_editor_br2nl($bannerhtml));
if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}
if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $bannerhtml_output = nv_aleditor('bannerhtml', '100%', '300px', $bannerhtml_escaped, '', NV_UPLOADS_DIR . '/' . $module_upload, NV_UPLOADS_DIR . '/' . $module_upload . '/files');
} else {
    $bannerhtml_output = '<textarea class="form-control" rows="6" name="bannerhtml">' . $bannerhtml_escaped . '</textarea>';
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('add-banner.tpl'));

$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('CHECKSS', csrf_create($csrf_key));
$tpl->assign('CHECKSS_AJAX_USER', csrf_create($admin_info['admin_id'] . '_' . $module_name . '_ajaxqueryusername'));
$tpl->assign('UPLOAD_BLOCKED_MSG', '');
$tpl->assign('PLANS', array_values($array_plans));
$tpl->assign('TARGETS', $targets);
$tpl->assign('FILE_ALLOWED_EXT', implode(', ', $file_allowed_ext));
$tpl->assign('HOUR_OPTIONS', $hour_options);
$tpl->assign('MIN_OPTIONS', $min_options);
$tpl->assign('BANNERHTML', $bannerhtml_output);

$tpl->assign('ITEM', [
    'title'       => $title,
    'pid'         => $pid,
    'file_alt'    => $file_alt,
    'click_url'   => $click_url,
    'target'      => $target,
    'publ_date'   => $publ_date,
    'publ_date_h' => $publ_date_h,
    'publ_date_m' => $publ_date_m,
    'exp_date'    => $exp_date,
    'exp_date_h'  => $exp_date_h,
    'exp_date_m'  => $exp_date_m,
    'assign_user' => $assign_user,
]);

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($tpl->fetch('add-banner.tpl'));
include NV_ROOTDIR . '/includes/footer.php';
