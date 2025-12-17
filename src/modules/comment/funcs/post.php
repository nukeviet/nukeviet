<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_COMMENT')) {
    exit('Stop!!!');
}

/**
 * Trả về json kết quả post form
 *
 * @param array $json
 * @return void
 */
function _loadContents(array $json): void
{
    include NV_ROOTDIR . '/includes/header.php';
    echo '<script' . (defined('NV_SCRIPT_NONCE') ? ' nonce="' . NV_SCRIPT_NONCE . '"' : '') . '>parent.nv_commment_reload(' . json_encode($json) . ');</script>';
    include NV_ROOTDIR . '/includes/footer.php';
}

$module = $nv_Request->get_string('module', 'post');

if (empty($module) or !isset($module_config[$module]['activecomm']) or !isset($site_mods[$module])) {
    _loadContents(['status' => 'ERR', 'mess' => $nv_Lang->getModule('comment_unsuccess')]);
}

// Kiểm tra module có được Sử dụng chức năng bình luận
$area = $nv_Request->get_title('area', 'post', '');
$id = $nv_Request->get_title('id', 'post', '');
$allowed_comm = $nv_Request->get_title('allowed', 'post');
$checkss = $nv_Request->get_title('checkss', 'post');
if (empty($id) or $module_config[$module]['activecomm'] != 1 or $checkss != md5($module . '-' . $area . '-' . $id . '-' . $allowed_comm . '-' . NV_CACHE_PREFIX)) {
    _loadContents(['status' => 'ERR', 'mess' => $nv_Lang->getModule('comment_unsuccess')]);
}

// Kiểm tra quyền đăng bình luận
$allowed = $module_config[$module]['allowed_comm'];
if ($allowed == '-1') {
    // Quyền hạn đăng bình luận theo bài viết
    $allowed = $allowed_comm;
}
if (!nv_user_in_groups($allowed)) {
    _loadContents(['status' => 'ERR', 'mess' => $nv_Lang->getModule('comment_unsuccess')]);
}

// kiểm tra captcha
$captcha = (int) ($module_config[$module]['captcha_area_comm']);
$show_captcha = true;
if ($captcha == 0) {
    $show_captcha = false;
} elseif ($captcha == 1 and defined('NV_IS_USER')) {
    $show_captcha = false;
} elseif ($captcha == 2 and defined('NV_IS_MODADMIN')) {
    if (defined('NV_IS_SPADMIN')) {
        $show_captcha = false;
    } else {
        $adminscomm = array_map('intval', explode(',', $module_config[$module]['adminscomm']));
        if (in_array((int) $admin_info['admin_id'], $adminscomm, true)) {
            $show_captcha = false;
        }
    }
}

$captcha_type = (empty($module_config['comment']['captcha_type']) or in_array($module_config['comment']['captcha_type'], ['captcha', 'recaptcha', 'turnstile'], true)) ? $module_config['comment']['captcha_type'] : 'captcha';
if ($captcha_type == 'recaptcha' and (empty($global_config['recaptcha_sitekey']) or empty($global_config['recaptcha_secretkey']))) {
    $captcha_type = 'captcha';
}

if ($captcha_type == 'turnstile' and (empty($global_config['turnstile_sitekey']) or empty($global_config['turnstile_secretkey']))) {
    $captcha_type = 'captcha';
}

unset($code);

if ($show_captcha and $captcha_type == 'recaptcha') {
    // Xác định giá trị của captcha nhập vào nếu sử dụng reCaptcha
    $code = $nv_Request->get_title('g-recaptcha-response', 'post', '');
} elseif ($show_captcha and $captcha_type == 'turnstile') {
    // Xác định giá trị của captcha nhập vào nếu sử dụng Turnstile
    $code = $nv_Request->get_title('cf-turnstile-response', 'post', '');
} elseif ($show_captcha and $captcha_type == 'captcha') {
    // Xác định giá trị của captcha nhập vào nếu sử dụng captcha hình
    $code = $nv_Request->get_title('code', 'post', '');
}

// Kiểm tra tính hợp lệ của captcha nhập vào, nếu không hợp lệ => thông báo lỗi
if (isset($code) and !nv_capcha_txt($code, $captcha_type)) {
    _loadContents(['status' => 'ERR', 'mess' => $nv_Lang->getGlobal('securitycodeincorrect')]);
}

// Xác định và kiểm tra userid, name, email
if (defined('NV_IS_USER')) {
    $userid = $user_info['userid'];
    $name = $user_info['username'];
    $email = $user_info['email'];
} else {
    $userid = 0;
    $name = $nv_Request->get_title('name', 'post', '', 1);
    $email = $nv_Request->get_title('email', 'post', '');

    if (empty($name)) {
        _loadContents([
            'status' => 'ERR',
            'mess' => $nv_Lang->getModule('comment_name_error'),
            'input' => 'name'
        ]);
    }

    $check_valid_email = nv_check_valid_email($email, true);
    $email = $check_valid_email[1];

    if ($check_valid_email[0] != '') {
        _loadContents([
            'status' => 'ERR',
            'mess' => $check_valid_email[0],
            'input' => 'email'
        ]);
    }
}

// Kiểm tra nội dung bình luận
if (!empty($module_config[$module]['alloweditorcomm'])) {
    $content = nv_editor_nl2br($nv_Request->get_editor('content', '', NV_ALLOWED_HTML_TAGS));
} else {
    $content = $nv_Request->get_title('content', 'post', '', 1);
    $content = nv_nl2br($content);
}
if (empty($content)) {
    _loadContents(['status' => 'ERR', 'mess' => $nv_Lang->getModule('comment_content_error')]);
}

$status = $module_config[$module]['auto_postcomm'];
$timeout = $nv_Request->get_int($site_mods[$module]['module_data'] . '_timeout_' . $area . '_' . $id, 'cookie', 0);
$difftimeout = isset($module_config[$module]['timeoutcomm']) ? (int) ($module_config[$module]['timeoutcomm']) : 360;
if (($status == 2 and !defined('NV_IS_USER')) or $status == 0) {
    $status = 0;
} else {
    $status = 1;
}
if (defined('NV_IS_ADMIN')) {
    $status = 1;
    $timeout = 0;
}
if (!($timeout == 0 or NV_CURRENTTIME - $timeout > $difftimeout)) {
    $timeout = nv_convertfromSec($difftimeout - NV_CURRENTTIME + $timeout);
    $timeoutmsg = $nv_Lang->getModule('comment_timeout', $timeout);
    _loadContents(['status' => 'ERR', 'mess' => $timeoutmsg]);
}

$data_permission_confirm = !empty($global_config['data_warning']) ? (int) $nv_Request->get_bool('data_permission_confirm', 'post', false) : -1;
$antispam_confirm = !empty($global_config['antispam_warning']) ? (int) $nv_Request->get_bool('antispam_confirm', 'post', false) : -1;
if ($data_permission_confirm === 0) {
    _loadContents([
        'status' => 'ERR',
        'mess' => $nv_Lang->getGlobal('data_warning_error'),
        'input' => 'data_permission_confirm'
    ]);
}
if ($antispam_confirm === 0) {
    _loadContents([
        'status' => 'ERR',
        'mess' => $nv_Lang->getGlobal('antispam_warning_error'),
        'input' => 'antispam_confirm'
    ]);
}

$pid = $nv_Request->get_int('pid', 'post', 0);

// Xử lý nếu có đính kèm file vào bình luận
$fileupload = '';
if (!empty($module_config[$module]['allowattachcomm']) and isset($_FILES['fileattach']) and is_uploaded_file($_FILES['fileattach']['tmp_name'])) {
    $dir = date('Y_m');
    if (!is_dir(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $dir)) {
        $mk = nv_mkdir(NV_UPLOADS_REAL_DIR . '/' . $module_upload, $dir);
        if ($mk[0] > 0) {
            try {
                $db->query('INSERT INTO ' . NV_UPLOAD_GLOBALTABLE . "_dir (dirname, time) VALUES ('" . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $dir . "', 0)");
            } catch (PDOException $e) {
                trigger_error($e->getMessage());
            }
        }
    }

    $upload = new NukeViet\Files\Upload($global_config['file_allowed_ext'], $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT);
    $upload->setLanguage(\NukeViet\Core\Language::$lang_global);
    $upload_info = $upload->save_file($_FILES['fileattach'], NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $dir, false);
    @unlink($_FILES['fileattach']['tmp_name']);

    if (!empty($upload_info['error'])) {
        _loadContents([
            'status' => 'ERR',
            'mess' => $upload_info['error'],
            'input' => 'fileattach'
        ]);
    }

    mt_srand(microtime(true) * 1000000);
    $maxran = 1000000;
    $random_num = random_int(0, $maxran);
    $random_num = md5($random_num);
    $nv_pathinfo_filename = nv_pathinfo_filename($upload_info['name']);
    $new_name = NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $dir . '/' . $nv_pathinfo_filename . '.' . $random_num . '.' . $upload_info['ext'];

    $rename = nv_renamefile($upload_info['name'], $new_name);

    if ($rename[0] == 1) {
        $fileupload = $new_name;
    } else {
        $fileupload = $upload_info['name'];
    }

    @chmod($fileupload, 0644);
    $fileupload = str_replace(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload . '/', '', $fileupload);
}

try {
    $_sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . ' (module, area, id, pid, content, attach, post_time, userid, post_name, post_email, post_ip, status) VALUES
            (:module, :area, :id, ' . $pid . ', :content, :attach, ' . NV_CURRENTTIME . ', ' . $userid . ', :post_name, :post_email, :post_ip, ' . $status . ')';
    $data_insert = [];
    $data_insert['module'] = $module;
    $data_insert['area'] = $area;
    $data_insert['id'] = $id;
    $data_insert['content'] = $content;
    $data_insert['attach'] = $fileupload;
    $data_insert['post_name'] = $name;
    $data_insert['post_email'] = $email;
    $data_insert['post_ip'] = NV_CLIENT_IP;
    $new_id = $db->insert_id($_sql, 'cid', $data_insert);

    if ($new_id > 0) {
        if ($difftimeout) {
            $nv_Request->set_Cookie($site_mods[$module]['module_data'] . '_timeout_' . $area . '_' . $id, NV_CURRENTTIME, $difftimeout);
        }

        if ($status) {
            $mod_info = $site_mods[$module];
            if (module_file_exists($mod_info['module_file'] . '/comment.php')) {
                $row = [];
                $row['module'] = $module;
                $row['id'] = $id;
                include NV_ROOTDIR . '/modules/' . $mod_info['module_file'] . '/comment.php';
            }
        }

        if (!$status) {
            $comment_success = $nv_Lang->getModule('comment_success_queue');

            // Gui thong bao kiem duyet
            nv_insert_notification($module_name, 'comment_queue', [
                'content' => strip_tags($content)
            ], $new_id);
        } else {
            $comment_success = $nv_Lang->getModule('comment_success');
        }
        _loadContents(['status' => 'OK', 'mess' => nv_base64_encode($comment_success)]);
    }
} catch (Throwable $e) {
    _loadContents(['status' => 'ERR', 'mess' => $e->getMessage()]);
}
