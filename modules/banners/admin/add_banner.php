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

$page_title = $lang_module['add_banner'];

$contents = [];
$contents['upload_blocked'] = '';
$contents['file_allowed_ext'] = [];

if (preg_match('/images/', NV_ALLOW_FILES_TYPE)) {
    $contents['file_allowed_ext'][] = 'images';
}

if (empty($contents['file_allowed_ext'])) {
    $contents['upload_blocked'] = $lang_module['upload_blocked'];

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme(nv_add_banner_theme($contents));
    include NV_ROOTDIR . '/includes/footer.php';
}

$plans = $require_image = $plans_form = $plans_exp = [];
$sql = 'SELECT id, title, blang, form, require_image, exp_time FROM ' . NV_BANNERS_GLOBALTABLE . '_plans ORDER BY blang, title ASC';
$result = $db->query($sql);
while ($row = $result->fetch()) {
    $plans[$row['id']] = $row['title'] . ' (' . (!empty($row['blang']) ? $language_array[$row['blang']]['name'] : $lang_module['blang_all']) . ')';
    $require_image[$row['id']] = $row['require_image'];
    $plans_form[$row['id']] = $row['form'];
    $plans_exp[$row['id']] = $row['exp_time'];
}

if (empty($plans)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=add_plan');
}

$error = '';

if ($nv_Request->get_int('save', 'post') == '1') {
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
            $error_assign_user = sprintf($lang_module['assign_to_user_err'], $assign_user);
        } else {
            $assign_user_id = $sth->fetchColumn();
        }
    }

    if (empty($title)) {
        $error = $lang_module['title_empty'];
    } elseif (empty($pid) or !isset($plans[$pid])) {
        $error = $lang_module['plan_not_selected'];
    } elseif (!empty($error_assign_user)) {
        $error = $error_assign_user;
    } elseif (!is_uploaded_file($_FILES['banner']['tmp_name']) and $array_require_image[0]['require_image'] == 1) {
        $error = $lang_module['file_upload_empty'];
    } elseif (!$click_url_allow) {
        $error = $lang_module['click_url_invalid'];
    } else {
        $imageforswf = '';

        if (empty($publ_date)) {
            $publtime = NV_CURRENTTIME;
        } else {
            unset($m);
            preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $publ_date, $m);
            $publtime = mktime($publ_date_h, $publ_date_m, 0, $m[2], $m[1], $m[3]);
            // Cho tạo thoải mái thời gian, nếu lùi về sau cũng được
            //if ($publtime < NV_CURRENTTIME) {
            //    $publtime = NV_CURRENTTIME;
            //}
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

        $act = (empty($exptime) or $exptime > NV_CURRENTTIME) ? ($publtime > NV_CURRENTTIME ? 0 : 1) : 2;

        $_weight = 0;
        if ($plans_form[$pid] == 'sequential' and $act != 2) {
            $_weight = $db->query('SELECT COUNT(*) FROM ' . NV_BANNERS_GLOBALTABLE . '_rows WHERE act IN(0,1,3) AND pid=' . $pid)->fetchColumn();
            $_weight = (int) $_weight + 1;
        }

        // Upload ảnh trên mobile
        if (isset($_FILES['imageforswf']) and is_uploaded_file($_FILES['imageforswf']['tmp_name'])) {
            $upload = new NukeViet\Files\Upload($contents['file_allowed_ext'], $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT);
            $upload->setLanguage($lang_global);
            $upload_info = $upload->save_file($_FILES['imageforswf'], NV_UPLOADS_REAL_DIR . '/' . NV_BANNER_DIR, false);
            @unlink($_FILES['imageforswf']['tmp_name']);

            if (!empty($upload_info['error'])) {
                $error = $upload_info['error'];
            } else {
                @chmod($upload_info['name'], 0644);
                $imageforswf = $upload_info['basename'];
            }
        }

        if (empty($error)) {
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

                $data_insert = [];
                $data_insert['title'] = $title;
                $data_insert['file_name'] = $file_name;
                $data_insert['file_ext'] = $file_ext;
                $data_insert['file_mime'] = $file_mime;
                $data_insert['file_alt'] = $file_alt;
                $data_insert['imageforswf'] = $imageforswf;
                $data_insert['click_url'] = $click_url;
                $data_insert['target'] = $target;
                $data_insert['bannerhtml'] = $bannerhtml;
                $id = $db->insert_id($_sql, 'id', $data_insert);
            } else {
                $upload = new NukeViet\Files\Upload($contents['file_allowed_ext'], $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT);
                $upload->setLanguage($lang_global);
                $upload_info = $upload->save_file($_FILES['banner'], NV_UPLOADS_REAL_DIR . '/' . NV_BANNER_DIR, false);
                @unlink($_FILES['banner']['tmp_name']);

                if (!empty($upload_info['error'])) {
                    $error = $upload_info['error'];
                } else {
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

                    $data_insert = [];
                    $data_insert['title'] = $title;
                    $data_insert['file_name'] = $file_name;
                    $data_insert['file_ext'] = $file_ext;
                    $data_insert['file_mime'] = $file_mime;
                    $data_insert['file_alt'] = $file_alt;
                    $data_insert['imageforswf'] = $imageforswf;
                    $data_insert['click_url'] = $click_url;
                    $data_insert['target'] = $target;
                    $data_insert['bannerhtml'] = $bannerhtml;
                    $id = $db->insert_id($_sql, 'id', $data_insert);
                }
            }
        }

        if (empty($error)) {
            nv_insert_logs(NV_LANG_DATA, $module_name, 'log_add_banner', 'bannerid ' . $id, $admin_info['userid']);
            nv_CreateXML_bannerPlan();
            $nv_Cache->delMod($module_name);
            nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=info_banner&id=' . $id);
        }
    }
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
    $imageforswf = '';

    if ($nv_Request->get_bool('pid', 'get') and isset($plans[$nv_Request->get_int('pid', 'get')])) {
        $pid = $nv_Request->get_int('pid', 'get');
    }
}

$contents['info'] = (!empty($error)) ? $error : $lang_module['add_banner_info'];
$contents['is_error'] = (!empty($error)) ? 1 : 0;
$contents['file_allowed_ext'] = implode(', ', $contents['file_allowed_ext']);
$contents['submit'] = $lang_module['add_banner'];
$contents['action'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=add_banner';
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
    $row,
    $require_image,
    $plans_exp
];
$contents['upload'] = [
    sprintf($lang_module['upload'], $contents['file_allowed_ext']),
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
$contents['assign_user'] = $assign_user;

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $contents['bannerhtml'] = nv_aleditor('bannerhtml', '100%', '300px', $contents['bannerhtml'], '', NV_UPLOADS_DIR . '/' . $module_upload, NV_UPLOADS_DIR . '/' . $module_upload . '/files');
} else {
    $contents['bannerhtml'] = '<textarea style="width:100%;height:300px" name="bannerhtml">' . $contents['bannerhtml'] . '</textarea>';
}
$contents['bannerhtml'] = [
    $lang_module['bannerhtml'],
    $contents['bannerhtml']
];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme(nv_add_banner_theme($contents));
include NV_ROOTDIR . '/includes/footer.php';
