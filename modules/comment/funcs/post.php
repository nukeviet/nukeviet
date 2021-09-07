<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_COMMENT')) {
    exit('Stop!!!');
}

function _loadContents($contents)
{
    include NV_ROOTDIR . '/includes/header.php';
    echo '<script type="text/javascript">parent.nv_commment_reload("' . str_replace('"', '\"', $contents) . '");</script>';
    include NV_ROOTDIR . '/includes/footer.php';
    exit(0);
}

$contents = 'ERR__' . $lang_module['comment_unsuccess'];
$module = $nv_Request->get_string('module', 'post');

if (empty($module) or !isset($module_config[$module]['activecomm']) or !isset($site_mods[$module])) {
    _loadContents('ERR__' . $lang_module['comment_unsuccess']);
}

// Kiểm tra module có được Sử dụng chức năng bình luận
$area = $nv_Request->get_int('area', 'post', 0);
$id = $nv_Request->get_int('id', 'post');
$allowed_comm = $nv_Request->get_title('allowed', 'post');
$checkss = $nv_Request->get_title('checkss', 'post');
if ($id <= 0 or $module_config[$module]['activecomm'] != 1 or $checkss != md5($module . '-' . $area . '-' . $id . '-' . $allowed_comm . '-' . NV_CACHE_PREFIX)) {
    _loadContents('ERR__' . $lang_module['comment_unsuccess']);
}

// Kiểm tra quyền đăng bình luận
$allowed = $module_config[$module]['allowed_comm'];
if ($allowed == '-1') {
    // Quyền hạn đăng bình luận theo bài viết
    $allowed = $allowed_comm;
}
if (!nv_user_in_groups($allowed)) {
    _loadContents('ERR__' . $lang_module['comment_unsuccess']);
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

$captcha_type = (empty($module_config['comment']['captcha_type']) or in_array($module_config['comment']['captcha_type'], ['captcha', 'recaptcha'], true)) ? $module_config['comment']['captcha_type'] : 'captcha';
if ($captcha_type == 'recaptcha' and (empty($global_config['recaptcha_sitekey']) or empty($global_config['recaptcha_secretkey']))) {
    $captcha_type = 'captcha';
}

unset($code);
// Xác định giá trị của captcha nhập vào nếu sử dụng reCaptcha
if ($show_captcha and $captcha_type == 'recaptcha') {
    $code = $nv_Request->get_title('g-recaptcha-response', 'post', '');
}
// Xác định giá trị của captcha nhập vào nếu sử dụng captcha hình
elseif ($show_captcha and $captcha_type == 'captcha') {
    $code = $nv_Request->get_title('code', 'post', '');
}

// Kiểm tra tính hợp lệ của captcha nhập vào, nếu không hợp lệ => thông báo lỗi
if (isset($code) and !nv_capcha_txt($code, $captcha_type)) {
    _loadContents('ERR_code_' . $lang_global['securitycodeincorrect']);
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
        _loadContents('ERR_name_' . $lang_module['comment_name_error']);
    }

    $check_valid_email = nv_check_valid_email($email, true);
    $email = $check_valid_email[1];

    if ($check_valid_email[0] != '') {
        _loadContents('ERR_email_' . $check_valid_email[0]);
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
    _loadContents('ERR_content_' . $lang_module['comment_content_error']);
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
    $timeoutmsg = sprintf($lang_module['comment_timeout'], $timeout);
    _loadContents('ERR__' . $timeoutmsg);
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
    $upload->setLanguage($lang_global);
    $upload_info = $upload->save_file($_FILES['fileattach'], NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $dir, false);
    @unlink($_FILES['fileattach']['tmp_name']);

    if (!empty($upload_info['error'])) {
        _loadContents('ERR__' . $upload_info['error']);
    }

    mt_srand((float) microtime() * 1000000);
    $maxran = 1000000;
    $random_num = mt_rand(0, $maxran);
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
            (:module, ' . $area . ', ' . $id . ', ' . $pid . ', :content, :attach, ' . NV_CURRENTTIME . ', ' . $userid . ', :post_name, :post_email, :post_ip, ' . $status . ')';
    $data_insert = [];
    $data_insert['module'] = $module;
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
            if (file_exists(NV_ROOTDIR . '/modules/' . $mod_info['module_file'] . '/comment.php')) {
                $row = [];
                $row['module'] = $module;
                $row['id'] = $id;
                include NV_ROOTDIR . '/modules/' . $mod_info['module_file'] . '/comment.php';
            }
        }

        if (!$status) {
            $comment_success = $lang_module['comment_success_queue'];

            // Gui thong bao kiem duyet
            nv_insert_notification($module_name, 'comment_queue', [
                'content' => strip_tags($content)
            ], $new_id);
        } else {
            $comment_success = $lang_module['comment_success'];
        }
        _loadContents('OK_' . nv_base64_encode($comment_success));
    }
} catch (PDOException $e) {
    _loadContents('ERR__' . $e->getMessage());
}
