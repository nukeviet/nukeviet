<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$id = $nv_Request->get_int('id', 'get', 0);

$sql = 'SELECT * FROM ' . NV_BANNERS_GLOBALTABLE . '_rows WHERE id=' . $id;
$row = $db->query($sql)->fetch();
if (empty($row)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$file_name = $row['file_name'];
$file_ext = $row['file_ext'];
$file_mime = $row['file_mime'];
$width = $row['width'];
$height = $row['height'];
$imageforswf = $row['imageforswf'];
$page_title = $lang_module['edit_banner'];

$contents = [];
$contents['upload_blocked'] = '';
$contents['file_allowed_ext'] = [];

if (preg_match('/images/', NV_ALLOW_FILES_TYPE)) {
    $contents['file_allowed_ext'][] = 'images';
}

if (empty($contents['file_allowed_ext'])) {
    $contents['upload_blocked'] = $lang_module['upload_blocked'];

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme(nv_edit_banner_theme($contents));
    include NV_ROOTDIR . '/includes/footer.php';
}

$sql = 'SELECT id, title, blang, require_image, exp_time FROM ' . NV_BANNERS_GLOBALTABLE . '_plans ORDER BY blang, title ASC';
$result = $db->query($sql);

$plans = $require_image = $plans_exp = [];
while ($pl_row = $result->fetch()) {
    $plans[$pl_row['id']] = $pl_row['title'] . ' (' . (!empty($pl_row['blang']) ? $language_array[$pl_row['blang']]['name'] : $lang_module['blang_all']) . ')';
    $require_image[$pl_row['id']] = $pl_row['require_image'];
    $plans_exp[$pl_row['id']] = $pl_row['exp_time'];
}

if (empty($plans)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=add_plan');
}

$error = '';

if ($nv_Request->get_int('save', 'post') == '1') {
    $title = nv_htmlspecialchars(strip_tags($nv_Request->get_string('title', 'post', '')));
    $pid = $nv_Request->get_int('pid', 'post', 0);
    $file_alt = nv_htmlspecialchars(strip_tags($nv_Request->get_string('file_alt', 'post', '')));
    $click_url = strip_tags($nv_Request->get_string('click_url', 'post', ''));
    $publ_date = strip_tags($nv_Request->get_string('publ_date', 'post', ''));
    $publ_date_h = $nv_Request->get_int('publ_date_h', 'post', 0);
    $publ_date_m = $nv_Request->get_int('publ_date_m', 'post', 0);
    $exp_date = strip_tags($nv_Request->get_string('exp_date', 'post', ''));
    $exp_date_h = $nv_Request->get_int('exp_date_h', 'post', 0);
    $exp_date_m = $nv_Request->get_int('exp_date_m', 'post', 0);
    $target = $nv_Request->get_string('target', 'post', '');
    if (!isset($targets[$target])) {
        $target = '_blank';
    }
    $bannerhtml = $nv_Request->get_editor('bannerhtml', '', NV_ALLOWED_HTML_TAGS);
    $assign_user = $nv_Request->get_title('assign_user', 'post', '');
    $assign_user_id = $admin_info['userid'];
    $remove_banner = (int) ($nv_Request->get_bool('remove_banner', 'post', false));
    $remove_imageforswf = (int) ($nv_Request->get_bool('remove_imageforswf', 'post', false));

    if (!empty($publ_date) and !preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $publ_date)) {
        $publ_date = '';
    }
    if (!empty($exp_date) and !preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $exp_date)) {
        $exp_date = '';
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
            $error_assign_user = sprintf($lang_module['assign_to_user_err'], $assign_user);
        } else {
            $assign_user_id = $sth->fetchColumn();
        }
    }

    // Upload file ảnh mới
    $new_tmpbanner = '';
    $old_banner = $file_name;

    if (isset($_FILES['banner']) and is_uploaded_file($_FILES['banner']['tmp_name'])) {
        $upload = new NukeViet\Files\Upload($contents['file_allowed_ext'], $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT);
        $upload->setLanguage($lang_global);
        $upload_info = $upload->save_file($_FILES['banner'], NV_UPLOADS_REAL_DIR . '/' . NV_BANNER_DIR, false);
        @unlink($_FILES['banner']['tmp_name']);

        if (!empty($upload_info['error'])) {
            $error = $upload_info['error'];
        } else {
            @chmod($upload_info['name'], 0644);
            $new_tmpbanner = $upload_info['basename'];
            $file_name = $new_tmpbanner = $upload_info['basename'];
            $file_ext = $upload_info['ext'];
            $file_mime = $upload_info['mime'];
            $width = $upload_info['img_info'][0];
            $height = $upload_info['img_info'][1];
        }
    } elseif ($remove_banner) {
        $file_name = $file_ext = $file_mime = 'no_image';
        $width = $height = 0;
    }

    // Upload ảnh mobile mới
    $new_tmpmobile_banner = '';
    $old_mobile_banner = $imageforswf;

    if (isset($_FILES['imageforswf']) and is_uploaded_file($_FILES['imageforswf']['tmp_name'])) {
        $upload = new NukeViet\Files\Upload($contents['file_allowed_ext'], $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT);
        $upload->setLanguage($lang_global);
        $upload_info = $upload->save_file($_FILES['imageforswf'], NV_UPLOADS_REAL_DIR . '/' . NV_BANNER_DIR, false);
        @unlink($_FILES['imageforswf']['tmp_name']);

        if (!empty($upload_info['error'])) {
            $error = $upload_info['error'];
        } else {
            @chmod($upload_info['name'], 0644);
            $new_tmpmobile_banner = $imageforswf = $upload_info['basename'];
        }
    } elseif ($remove_imageforswf) {
        $imageforswf = '';
    }

    if (empty($title)) {
        $error = $lang_module['title_empty'];
    } elseif (empty($pid) or !isset($plans[$pid])) {
        $error = $lang_module['plan_not_selected'];
    } elseif (!empty($error_assign_user)) {
        $error = $error_assign_user;
    } elseif ($array_require_image[0]['require_image'] == 1 and (empty($file_name) or $file_name == 'no_image')) {
        $error = $lang_module['file_upload_empty'];
    } elseif (!$click_url_allow) {
        $error = $lang_module['click_url_invalid'];
    } elseif (empty($error)) {
        if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $publ_date, $m)) {
            $publtime = mktime($publ_date_h, $publ_date_m, 0, $m[2], $m[1], $m[3]);
        // Cho tạo thoải mái thời gian, nếu lùi về sau thì đánh dấu là hết hạn để đó khi nào cần thì sửa lại có gì phải cấm
            //if ($publtime < $row['add_time']) {
            //    $publtime = $row['add_time'];
            //}
        } else {
            $publtime = $publtime = $row['add_time'];
        }

        if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $exp_date, $m)) {
            $exptime = mktime($exp_date_h, $exp_date_m, 59, $m[2], $m[1], $m[3]);
            if ($exptime <= $publtime) {
                $exptime = $publtime;
            }
        } else {
            if (!empty($plans_exp[$pid])) {
                $exptime = $publtime + $plans_exp[$pid];
            } else {
                $exptime = 0;
            }
        }
        if ($exptime != 0 and $exptime <= $publtime) {
            $exptime = $publtime;
        }

        $act = $row['act'];
        // Quảng cáo đang chờ duyệt và bị đình chỉ thì sửa cái này không làm thay đổi trạng thái
        if ($publtime > NV_CURRENTTIME) {
            // Chờ hoạt động
            $act = ($act != 3 and $act != 4) ? 0 : $act;
        } elseif ($publtime <= NV_CURRENTTIME and ($exptime <= 0 or $exptime > NV_CURRENTTIME)) {
            // Hoạt động
            $act = ($act != 3 and $act != 4) ? 1 : $act;
        } elseif ($exptime > 0 and $exptime <= NV_CURRENTTIME) {
            // Hết hạn
            $act = ($act != 3 and $act != 4) ? 2 : $act;
        }

        $pid_old = $db->query('SELECT pid FROM ' . NV_BANNERS_GLOBALTABLE . '_rows WHERE id=' . (int) $id)->fetchColumn();

        $stmt = $db->prepare('UPDATE ' . NV_BANNERS_GLOBALTABLE . '_rows SET
            title= :title, pid=' . $pid . ', clid=' . $assign_user_id . ',
            file_name= :file_name, file_ext= :file_ext, file_mime= :file_mime,
            width=' . $width . ', height=' . $height . ', file_alt= :file_alt, imageforswf= :imageforswf,
            click_url= :click_url, target= :target, bannerhtml=:bannerhtml,
            publ_time=' . $publtime . ', exp_time=' . $exptime . ', act=' . $act . '
        WHERE id=' . $id);

        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':file_name', $file_name, PDO::PARAM_STR);
        $stmt->bindParam(':file_ext', $file_ext, PDO::PARAM_STR);
        $stmt->bindParam(':file_mime', $file_mime, PDO::PARAM_STR);
        $stmt->bindParam(':file_alt', $file_alt, PDO::PARAM_STR);
        $stmt->bindParam(':imageforswf', $imageforswf, PDO::PARAM_STR);
        $stmt->bindParam(':click_url', $click_url, PDO::PARAM_STR);
        $stmt->bindParam(':target', $target, PDO::PARAM_STR);
        $stmt->bindParam(':bannerhtml', $bannerhtml, PDO::PARAM_STR, strlen($bannerhtml));
        $stmt->execute();

        if ($pid_old != $pid) {
            nv_fix_banner_weight($pid);
            nv_fix_banner_weight($pid_old);
        }

        // Xóa file cũ
        if ($file_name != $old_banner and is_file(NV_UPLOADS_REAL_DIR . '/' . NV_BANNER_DIR . '/' . $old_banner)) {
            nv_deletefile(NV_UPLOADS_REAL_DIR . '/' . NV_BANNER_DIR . '/' . $old_banner);
        }
        if ($imageforswf != $old_mobile_banner and is_file(NV_UPLOADS_REAL_DIR . '/' . NV_BANNER_DIR . '/' . $old_mobile_banner)) {
            nv_deletefile(NV_UPLOADS_REAL_DIR . '/' . NV_BANNER_DIR . '/' . $old_mobile_banner);
        }

        nv_insert_logs(NV_LANG_DATA, $module_name, 'log_edit_banner', 'bannerid ' . $id, $admin_info['userid']);
        nv_CreateXML_bannerPlan();
        $nv_Cache->delMod($module_name);

        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=info_banner&id=' . $id);
    }

    if (!empty($error)) {
        // Xóa file upload tạm
        if (!empty($new_tmpbanner) and is_file(NV_UPLOADS_REAL_DIR . '/' . NV_BANNER_DIR . '/' . $new_tmpbanner)) {
            nv_deletefile(NV_UPLOADS_REAL_DIR . '/' . NV_BANNER_DIR . '/' . $new_tmpbanner);
        }

        // Xóa file banner mobile tạm
        if (!empty($new_tmpmobile_banner) and is_file(NV_UPLOADS_REAL_DIR . '/' . NV_BANNER_DIR . '/' . $new_tmpmobile_banner)) {
            nv_deletefile(NV_UPLOADS_REAL_DIR . '/' . NV_BANNER_DIR . '/' . $new_tmpmobile_banner);
        }
    }
} else {
    $title = $row['title'];
    $pid = $row['pid'];
    $file_alt = $row['file_alt'];
    $click_url = $row['click_url'];
    $target = $row['target'];
    $bannerhtml = $row['bannerhtml'];
    $remove_banner = 0;
    $remove_imageforswf = 0;

    if (!empty($row['publ_time'])) {
        $publ_date = date('d/m/Y', $row['publ_time']);
        $publ_date_h = date('G', $row['publ_time']);
        $publ_date_m = (int) (date('i', $row['publ_time']));
    } else {
        $publ_date = '';
        $publ_date_h = 0;
        $publ_date_m = 0;
    }

    if (!empty($row['exp_time'])) {
        $exp_date = date('d/m/Y', $row['exp_time']);
        $exp_date_h = date('G', $row['exp_time']);
        $exp_date_m = (int) (date('i', $row['exp_time']));
    } else {
        $exp_date = '';
        $exp_date_h = 23;
        $exp_date_m = 59;
    }

    $assign_user = '';
    if (!empty($row['clid']) and $row['clid'] != $admin_info['userid']) {
        $user = $db->query('SELECT userid, username FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . $row['clid'])->fetch();
        if (!empty($user)) {
            $assign_user = $user['username'];
        }
    }
}

$contents['info'] = (!empty($error)) ? $error : $lang_module['edit_banner_info'];
$contents['is_error'] = (!empty($error)) ? 1 : 0;
$contents['file_allowed_ext'] = implode(', ', $contents['file_allowed_ext']);
$contents['submit'] = $lang_module['edit_banner'];
$contents['action'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit_banner&amp;id=' . $id;
$contents['title'] = [
    $lang_module['title'],
    'title',
    $title,
    255
];
$contents['plan'] = [
    $lang_module['in_plan'],
    'pid',
    $plans,
    $pid,
    $require_image,
    $plans_exp
];
$contents['assign_user'] = $assign_user;

$imageforswf = (!empty($imageforswf)) ? NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . NV_BANNER_DIR . '/' . $imageforswf : '';

if ($file_ext != 'no_image') {
    $contents['file_name'] = [
        $lang_module['file_name'],
        NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . NV_BANNER_DIR . '/' . $file_name,
        'class="open_modal_image"',
        NV_STATIC_URL . NV_ASSETS_DIR . '/images/ico_' . $file_ext . '.gif',
        $lang_global['show_picture'],
    ];
} else {
    $contents['file_name'] = [$lang_module['file_name'], ''];
}
$contents['imageforswf'] = [
    $imageforswf,
    NV_STATIC_URL . NV_ASSETS_DIR . '/images/ico_' . substr($imageforswf, -3) . '.gif'
];

$contents['upload'] = [
    sprintf($lang_module['re_upload'], $contents['file_allowed_ext']),
    'banner',
    $lang_module['imageforswf'],
    'imageforswf'
];
$contents['file_alt'] = [
    $lang_module['file_alt'],
    'file_alt',
    $file_alt,
    255
];
$contents['click_url'] = [
    $lang_module['click_url'],
    'click_url',
    $click_url,
    255
];

$contents['target'] = [
    $lang_module['target'],
    'target',
    $targets,
    $target
];

$contents['publ_date'] = [
    $lang_module['publ_date'],
    'publ_date',
    $publ_date,
    $publ_date_h,
    $publ_date_m
];
$contents['exp_date'] = [
    $lang_module['exp_date'],
    'exp_date',
    $exp_date,
    $exp_date_h,
    $exp_date_m
];

$contents['bannerhtml'] = htmlspecialchars(nv_editor_br2nl($bannerhtml));
$contents['remove_banner'] = $remove_banner;
$contents['remove_imageforswf'] = $remove_imageforswf;

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $contents['bannerhtml'] = nv_aleditor('bannerhtml', '100%', '300px', $contents['bannerhtml'], '', NV_UPLOADS_DIR . '/' . $module_upload, NV_UPLOADS_DIR . '/' . $module_upload . '/files');
} else {
    $contents['bannerhtml'] = '<textarea style="width:100%;height:300px" name="bannerhtml">' . $contents['bannerhtml'] . '</textarea>';
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme(nv_edit_banner_theme($contents));
include NV_ROOTDIR . '/includes/footer.php';
