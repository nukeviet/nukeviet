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

$id = $nv_Request->get_int('id', 'get', 0);
$row = [];
if ($id > 0) {
    $stmt = $db->prepare('SELECT * FROM ' . NV_BANNERS_GLOBALTABLE . '_rows WHERE id = :id');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch();
    $stmt->closeCursor();
    if (empty($row)) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    }
    $set_active_op = 'main';
}
$is_edit = !empty($row);

$page_title = $nv_Lang->getModule($is_edit ? 'edit_banner' : 'admin_add_banner');

// Kiểm tra upload có bị khóa không
$file_allowed_ext = [];
if (preg_match('/images/', NV_ALLOW_FILES_TYPE)) {
    $file_allowed_ext[] = 'images';
}

if (empty($file_allowed_ext)) {
    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('banner-content.tpl'));
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
    $tpl->assign('IS_EDIT', $is_edit);
    $tpl->assign('CURRENT_FILE', []);
    $tpl->assign('CURRENT_IMAGEFORSWF', '');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($tpl->fetch('banner-content.tpl'));
    include NV_ROOTDIR . '/includes/footer.php';
}

// Lấy danh sách khối banner
$array_plans = [];
$sql = 'SELECT id, title, blang, form, require_image, exp_time FROM ' . NV_BANNERS_GLOBALTABLE . '_plans ORDER BY blang, title ASC';
$result = $db->query($sql);
while ($row_plan = $result->fetch()) {
    $array_plans[$row_plan['id']] = [
        'key'           => $row_plan['id'],
        'title'         => $row_plan['title'] . ' (' . (!empty($row_plan['blang']) ? $language_array[$row_plan['blang']]['name'] : $nv_Lang->getModule('blang_all')) . ')',
        'require_image' => (bool) $row_plan['require_image'],
        'has_exp'       => ($row_plan['exp_time'] > 0),
        'form'          => $row_plan['form'],
        'exp_time'      => $row_plan['exp_time'],
    ];
}
$result->closeCursor();

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

    // Tham số chỉ dùng khi edit
    $remove_banner = $is_edit ? (int) ($nv_Request->get_bool('remove_banner', 'post', false)) : 0;
    $remove_imageforswf = $is_edit ? (int) ($nv_Request->get_bool('remove_imageforswf', 'post', false)) : 0;

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

    $stmt = $db->prepare('SELECT require_image FROM ' . NV_BANNERS_GLOBALTABLE . '_plans WHERE id = :pid');
    $stmt->bindValue(':pid', $pid, PDO::PARAM_INT);
    $stmt->execute();
    $array_require_image = $stmt->fetchAll();

    $error_assign_user = '';
    if (!empty($assign_user)) {
        $stmt = $db->prepare('SELECT userid FROM ' . NV_USERS_GLOBALTABLE . ' WHERE active = 1 AND username = :username');
        $stmt->bindValue(':username', $assign_user, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->rowCount() != 1) {
            $error_assign_user = $nv_Lang->getModule('assign_to_user_err', $assign_user);
        } else {
            $assign_user_id = $stmt->fetchColumn();
        }
    }

    if (empty($title)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess'   => $nv_Lang->getModule('title_empty'),
            'input'  => 'title'
        ]);
    }
    if (empty($pid) or !isset($array_plans[$pid])) {
        nv_jsonOutput([
            'status' => 'error',
            'mess'   => $nv_Lang->getModule('plan_not_selected'),
            'input'  => 'pid'
        ]);
    }
    if (!empty($error_assign_user)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess'   => $error_assign_user,
            'input'  => 'assign_user'
        ]);
    }
    // Khi thêm mới: kiểm tra file trước khi upload
    if (!$is_edit and !is_uploaded_file($_FILES['banner']['tmp_name']) and !empty($array_require_image) and $array_require_image[0]['require_image'] == 1) {
        nv_jsonOutput([
            'status' => 'error',
            'mess'   => $nv_Lang->getModule('file_upload_empty'),
            'input'  => 'banner'
        ]);
    }
    if (!$click_url_allow) {
        nv_jsonOutput([
            'status' => 'error',
            'mess'   => $nv_Lang->getModule('click_url_invalid'),
            'input'  => 'click_url'
        ]);
    }

    // Khởi tạo trạng thái file từ row hiện tại (edit) hoặc mặc định (add)
    $file_name = $is_edit ? $row['file_name'] : 'no_image';
    $file_ext = $is_edit ? $row['file_ext'] : 'no_image';
    $file_mime = $is_edit ? $row['file_mime'] : 'no_image';
    $width = $is_edit ? (int) $row['width'] : 0;
    $height = $is_edit ? (int) $row['height'] : 0;
    $imageforswf = $is_edit ? $row['imageforswf'] : '';
    $old_banner = $file_name;
    $old_mobile_banner = $imageforswf;

    // Upload ảnh mobile
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
    } elseif ($is_edit and $remove_imageforswf) {
        $imageforswf = '';
    }

    // Upload ảnh banner chính
    if (isset($_FILES['banner']) and is_uploaded_file($_FILES['banner']['tmp_name'])) {
        $upload = new NukeViet\Files\Upload($file_allowed_ext, $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT);
        $upload->setLanguage(\NukeViet\Core\Language::$lang_global);
        $upload_info = $upload->save_file($_FILES['banner'], NV_UPLOADS_REAL_DIR . '/' . NV_BANNER_DIR, false);
        @unlink($_FILES['banner']['tmp_name']);

        if (!empty($upload_info['error'])) {
            nv_jsonOutput(['status' => 'error', 'mess' => $upload_info['error']]);
        }
        @chmod($upload_info['name'], 0644);
        $file_name = $upload_info['basename'];
        $file_ext = $upload_info['ext'];
        $file_mime = $upload_info['mime'];
        $width = $upload_info['img_info'][0];
        $height = $upload_info['img_info'][1];
    } elseif ($is_edit and $remove_banner) {
        $file_name = $file_ext = $file_mime = 'no_image';
        $width = $height = 0;
    }

    // Khi chỉnh sửa: kiểm tra require_image sau khi xử lý file
    if ($is_edit and !empty($array_require_image) and $array_require_image[0]['require_image'] == 1 and (empty($file_name) or $file_name == 'no_image')) {
        nv_jsonOutput([
            'status' => 'error',
            'mess'   => $nv_Lang->getModule('file_upload_empty'),
            'input'  => 'banner'
        ]);
    }

    // Tính thời gian phát hành
    if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $publ_date, $m)) {
        $publtime = mktime($publ_date_h, $publ_date_m, 0, $m[2], $m[1], $m[3]);
    } else {
        $publtime = $is_edit ? $row['add_time'] : NV_CURRENTTIME;
    }

    // Tính thời gian hết hạn
    if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $exp_date, $m)) {
        $exptime = mktime($exp_date_h, $exp_date_m, 59, $m[2], $m[1], $m[3]);
        if ($exptime <= $publtime) {
            $exptime = $publtime;
        }
    } else {
        $exptime = !empty($array_plans[$pid]['exp_time']) ? $publtime + $array_plans[$pid]['exp_time'] : 0;
    }
    if ($exptime != 0 and $exptime <= $publtime) {
        $exptime = $publtime;
    }

    // Tính trạng thái act
    if ($is_edit) {
        // Giữ trạng thái chờ duyệt (3) và bị đình chỉ (4) khi sửa
        $act = $row['act'];
        if ($publtime > NV_CURRENTTIME) {
            $act = ($act != 3 and $act != 4) ? 0 : $act;
        } elseif ($publtime <= NV_CURRENTTIME and ($exptime <= 0 or $exptime > NV_CURRENTTIME)) {
            $act = ($act != 3 and $act != 4) ? 1 : $act;
        } elseif ($exptime > 0 and $exptime <= NV_CURRENTTIME) {
            $act = ($act != 3 and $act != 4) ? 2 : $act;
        }
    } else {
        $act = (empty($exptime) or $exptime > NV_CURRENTTIME) ? ($publtime > NV_CURRENTTIME ? 0 : 1) : 2;
    }

    if ($is_edit) {
        // Lấy pid cũ để fix weight nếu đổi plan
        $stmt_old_pid = $db->prepare('SELECT pid FROM ' . NV_BANNERS_GLOBALTABLE . '_rows WHERE id = :id');
        $stmt_old_pid->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt_old_pid->execute();
        $pid_old = (int) $stmt_old_pid->fetchColumn();

        $stmt = $db->prepare('UPDATE ' . NV_BANNERS_GLOBALTABLE . '_rows SET
            title = :title, pid = :pid, clid = :clid,
            file_name = :file_name, file_ext = :file_ext, file_mime = :file_mime,
            width = :width, height = :height, file_alt = :file_alt, imageforswf = :imageforswf,
            click_url = :click_url, target = :target, bannerhtml = :bannerhtml,
            publ_time = :publ_time, exp_time = :exp_time, act = :act
        WHERE id = :id');

        $stmt->bindValue(':title', $title, PDO::PARAM_STR);
        $stmt->bindValue(':pid', $pid, PDO::PARAM_INT);
        $stmt->bindValue(':clid', $assign_user_id, PDO::PARAM_INT);
        $stmt->bindValue(':file_name', $file_name, PDO::PARAM_STR);
        $stmt->bindValue(':file_ext', $file_ext, PDO::PARAM_STR);
        $stmt->bindValue(':file_mime', $file_mime, PDO::PARAM_STR);
        $stmt->bindValue(':width', $width, PDO::PARAM_INT);
        $stmt->bindValue(':height', $height, PDO::PARAM_INT);
        $stmt->bindValue(':file_alt', $file_alt, PDO::PARAM_STR);
        $stmt->bindValue(':imageforswf', $imageforswf, PDO::PARAM_STR);
        $stmt->bindValue(':click_url', $click_url, PDO::PARAM_STR);
        $stmt->bindValue(':target', $target, PDO::PARAM_STR);
        $stmt->bindValue(':bannerhtml', $bannerhtml, PDO::PARAM_STR);
        $stmt->bindValue(':publ_time', $publtime, PDO::PARAM_INT);
        $stmt->bindValue(':exp_time', $exptime, PDO::PARAM_INT);
        $stmt->bindValue(':act', $act, PDO::PARAM_INT);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($pid_old != $pid) {
            nv_fix_banner_weight($pid);
            nv_fix_banner_weight($pid_old);
        }

        // Xoá file cũ nếu đã được thay thế hoặc xoá
        if ($file_name != $old_banner and nv_is_file(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . NV_BANNER_DIR . '/' . $old_banner)) {
            nv_deletefile(NV_UPLOADS_REAL_DIR . '/' . NV_BANNER_DIR . '/' . $old_banner);
        }
        if ($imageforswf != $old_mobile_banner and nv_is_file(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . NV_BANNER_DIR . '/' . $old_mobile_banner)) {
            nv_deletefile(NV_UPLOADS_REAL_DIR . '/' . NV_BANNER_DIR . '/' . $old_mobile_banner);
        }

        nv_insert_logs(NV_LANG_DATA, $module_name, 'log_edit_banner', 'bannerid ' . $id, $admin_info['userid']);
    } else {
        $_weight = 0;
        if ($array_plans[$pid]['form'] == 'sequential' and $act != 2) {
            $stmt_weight = $db->prepare('SELECT COUNT(*) FROM ' . NV_BANNERS_GLOBALTABLE . '_rows WHERE act IN(0,1,3) AND pid = :pid');
            $stmt_weight->bindValue(':pid', $pid, PDO::PARAM_INT);
            $stmt_weight->execute();
            $_weight = $stmt_weight->fetchColumn();
            $_weight = (int) $_weight + 1;
        }

        $stmt = $db->prepare('INSERT INTO ' . NV_BANNERS_GLOBALTABLE . '_rows (
            title, pid, clid, file_name, file_ext, file_mime, width, height, file_alt, imageforswf, click_url, target, bannerhtml,
            add_time, publ_time, exp_time, hits_total, act, weight
        ) VALUES (
            :title, :pid, :clid, :file_name, :file_ext, :file_mime,
            :width, :height, :file_alt, :imageforswf, :click_url, :target, :bannerhtml, :add_time, :publ_time, :exp_time,
            0, :act, :weight
        )');
        $stmt->bindValue(':title', $title, PDO::PARAM_STR);
        $stmt->bindValue(':pid', $pid, PDO::PARAM_INT);
        $stmt->bindValue(':clid', $assign_user_id, PDO::PARAM_INT);
        $stmt->bindValue(':file_name', $file_name, PDO::PARAM_STR);
        $stmt->bindValue(':file_ext', $file_ext, PDO::PARAM_STR);
        $stmt->bindValue(':file_mime', $file_mime, PDO::PARAM_STR);
        $stmt->bindValue(':width', $width, PDO::PARAM_INT);
        $stmt->bindValue(':height', $height, PDO::PARAM_INT);
        $stmt->bindValue(':file_alt', $file_alt, PDO::PARAM_STR);
        $stmt->bindValue(':imageforswf', $imageforswf, PDO::PARAM_STR);
        $stmt->bindValue(':click_url', $click_url, PDO::PARAM_STR);
        $stmt->bindValue(':target', $target, PDO::PARAM_STR);
        $stmt->bindValue(':bannerhtml', $bannerhtml, PDO::PARAM_STR);
        $stmt->bindValue(':add_time', NV_CURRENTTIME, PDO::PARAM_INT);
        $stmt->bindValue(':publ_time', $publtime, PDO::PARAM_INT);
        $stmt->bindValue(':exp_time', $exptime, PDO::PARAM_INT);
        $stmt->bindValue(':act', $act, PDO::PARAM_INT);
        $stmt->bindValue(':weight', $_weight, PDO::PARAM_INT);
        $stmt->execute();
        $id = $db->lastInsertId();

        nv_insert_logs(NV_LANG_DATA, $module_name, 'log_add_banner', 'bannerid ' . $id, $admin_info['userid']);
    }

    nv_CreateXML_bannerPlan();
    $nv_Cache->delMod($module_name);
    nv_jsonOutput([
        'status'   => 'OK',
        'mess'     => '',
        'redirect' => nv_url_rewrite(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=info-banner&id=' . $id, true),
    ]);
}

// Khởi tạo giá trị mặc định cho form
if ($is_edit) {
    $pid = $row['pid'];
    $title = $row['title'];
    $file_alt = $row['file_alt'];
    $click_url = $row['click_url'];
    $target = $row['target'];
    $bannerhtml = $row['bannerhtml'];
    $remove_banner = 0;
    $remove_imageforswf = 0;

    if (!empty($row['publ_time'])) {
        $publ_date = date('d/m/Y', $row['publ_time']);
        $publ_date_h = (int) date('G', $row['publ_time']);
        $publ_date_m = (int) date('i', $row['publ_time']);
    } else {
        $publ_date = '';
        $publ_date_h = 0;
        $publ_date_m = 0;
    }

    if (!empty($row['exp_time'])) {
        $exp_date = date('d/m/Y', $row['exp_time']);
        $exp_date_h = (int) date('G', $row['exp_time']);
        $exp_date_m = (int) date('i', $row['exp_time']);
    } else {
        $exp_date = '';
        $exp_date_h = 23;
        $exp_date_m = 59;
    }

    $assign_user = '';
    if (!empty($row['clid']) and $row['clid'] != $admin_info['userid']) {
        $stmt = $db->prepare('SELECT username FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid = :userid');
        $stmt->bindValue(':userid', $row['clid'], PDO::PARAM_INT);
        $stmt->execute();
        $cl_user = $stmt->fetch();
        $stmt->closeCursor();
        if (!empty($cl_user)) {
            $assign_user = $cl_user['username'];
        }
    }

    $current_file = [];
    if ($row['file_ext'] !== 'no_image') {
        $current_file = [
            'url'  => NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . NV_BANNER_DIR . '/' . $row['file_name'],
            'name' => $row['file_name'],
        ];
    }
    $current_imageforswf = !empty($row['imageforswf'])
        ? NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . NV_BANNER_DIR . '/' . $row['imageforswf']
        : '';
} else {
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
    $remove_banner = 0;
    $remove_imageforswf = 0;
    $current_file = [];
    $current_imageforswf = '';

    if ($nv_Request->get_bool('pid', 'get') and isset($array_plans[$nv_Request->get_int('pid', 'get')])) {
        $pid = $nv_Request->get_int('pid', 'get');
    }
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
$tpl->setTemplateDir(get_module_tpl_dir('banner-content.tpl'));

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
$tpl->assign('IS_EDIT', $is_edit);
$tpl->assign('CURRENT_FILE', $current_file);
$tpl->assign('CURRENT_IMAGEFORSWF', $current_imageforswf);

$tpl->assign('ITEM', [
    'id'                 => $id,
    'title'              => $title,
    'pid'                => $pid,
    'file_alt'           => $file_alt,
    'click_url'          => $click_url,
    'target'             => $target,
    'publ_date'          => $publ_date,
    'publ_date_h'        => $publ_date_h,
    'publ_date_m'        => $publ_date_m,
    'exp_date'           => $exp_date,
    'exp_date_h'         => $exp_date_h,
    'exp_date_m'         => $exp_date_m,
    'assign_user'        => $assign_user,
    'remove_banner'      => $remove_banner,
    'remove_imageforswf' => $remove_imageforswf,
]);

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($tpl->fetch('banner-content.tpl'));
include NV_ROOTDIR . '/includes/footer.php';
