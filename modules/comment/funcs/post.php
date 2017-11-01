<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Mon, 27 Jan 2014 00:08:04 GMT
 */

if (! defined('NV_IS_MOD_COMMENT')) {
    die('Stop!!!');
}

$contents = 'ERR_' . $lang_module['comment_unsuccess'];
$module = $nv_Request->get_string('module', 'post');

if (!empty($module) and isset($module_config[$module]['activecomm']) and isset($site_mods[$module])) {
    // Kiểm tra module có được Sử dụng chức năng bình luận
    $area = $nv_Request->get_int('area', 'post', 0);
    $id = $nv_Request->get_int('id', 'post');
    $allowed_comm = $nv_Request->get_title('allowed', 'post');
    $checkss = $nv_Request->get_title('checkss', 'post');

    if ($id > 0 and $module_config[$module]['activecomm'] == 1 and $checkss == md5($module . '-' . $area . '-' . $id . '-' . $allowed_comm . '-' . NV_CACHE_PREFIX)) {
        // Kiểm tra quyền đăng bình luận
        $allowed = $module_config[$module]['allowed_comm'];
        if ($allowed == '-1') {
            // Quyền hạn đăng bình luận theo bài viết
            $allowed = $allowed_comm;
        }

        if (nv_user_in_groups($allowed)) {
            if (!empty($module_config[$module]['alloweditorcomm'])) {
                $content = nv_editor_nl2br($nv_Request->get_editor('content', '', NV_ALLOWED_HTML_TAGS));
            } else {
                $content = $nv_Request->get_title('content', 'post', '', 1);
                $content = nv_nl2br($content);
            }
            if ($global_config['captcha_type'] == 2) {
                $code = $nv_Request->get_title('g-recaptcha-response', 'post', '');
            } else {
                $code = $nv_Request->get_title('code', 'post', '');
            }
            $status = $module_config[$module]['auto_postcomm'];

            $timeout = $nv_Request->get_int($site_mods[$module]['module_data'] . '_timeout_' . $area . '_' . $id, 'cookie', 0);
            $difftimeout = isset($module_config[$module]['timeoutcomm']) ? intval($module_config[$module]['timeoutcomm']) : 360;

            if (($status == 2 and !defined('NV_IS_USER')) or $status == 0) {
                $status = 0;
            } else {
                $status = 1;
            }

            if (defined('NV_IS_USER')) {
                $userid = $user_info['userid'];
                $name = $user_info['username'];
                $email = $user_info['email'];

                if (defined('NV_IS_ADMIN')) {
                    $status = 1;
                    $timeout = 0;
                }
            } else {
                $userid = 0;
                $name = $nv_Request->get_title('name', 'post', '', 1);
                $email = $nv_Request->get_title('email', 'post', '');
            }

            $captcha = intval($module_config[$module]['captcha']);
            $show_captcha = true;
            if ($captcha == 0) {
                $show_captcha = false;
            } elseif ($captcha == 1 and defined('NV_IS_USER')) {
                $show_captcha = false;
            } elseif ($captcha == 2 and defined('NV_IS_MODADMIN')) {
                if (defined('NV_IS_SPADMIN')) {
                    $show_captcha = false;
                } else {
                    $adminscomm = explode(',', $module_config[$module]['adminscomm']);
                    if (in_array($admin_info['admin_id'], $adminscomm)) {
                        $show_captcha = false;
                    }
                }
            }
            if ($show_captcha and ! nv_capcha_txt($code)) {
                $contents = 'ERR_' . $lang_global['securitycodeincorrect'];
            } elseif ($timeout == 0 or NV_CURRENTTIME - $timeout > $difftimeout) {
                $pid = $nv_Request->get_int('pid', 'post', 0);

                // Xử lý nếu có đính kèm file vào bình luận
                $is_error = false;
                $fileupload = '';
                if (!empty($module_config[$module]['allowattachcomm']) and isset($_FILES['fileattach']) and is_uploaded_file($_FILES['fileattach']['tmp_name'])) {
                    $dir = date('Y_m');
                    if (!is_dir(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $dir)) {
                        $mk = nv_mkdir(NV_UPLOADS_REAL_DIR . '/' . $module_upload, $dir);
                        if ($mk[0] > 0) {
                            try {
                                $db->query("INSERT INTO " . NV_UPLOAD_GLOBALTABLE . "_dir (dirname, time) VALUES ('" . NV_UPLOADS_DIR . "/" . $module_upload . "/" . $dir . "', 0)");
                            } catch (PDOException $e) {
                                trigger_error($e->getMessage());
                            }
                        }
                    }

                    $upload = new NukeViet\Files\Upload($global_config['file_allowed_ext'], $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT);
                    $upload->setLanguage($lang_global);
                    $upload_info = $upload->save_file($_FILES['fileattach'], NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $dir, false);
                    @unlink($_FILES['fileattach']['tmp_name']);

                    if (empty($upload_info['error'])) {
                        mt_srand(( double )microtime() * 1000000);
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
                    } else {
                        $is_error = true;
                        $contents = 'ERR_' . $upload_info['error'];
                    }
                }

                if (!$is_error) {
                    try {
                        $_sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . ' (
                            module, area, id, pid, content, attach, post_time, userid, post_name, post_email, post_ip, status
                        ) VALUES (
                            :module, ' . $area . ', ' . $id . ', ' . $pid . ', :content, :attach, ' . NV_CURRENTTIME . ', ' . $userid . ', :post_name, :post_email,
                            :post_ip, ' . $status . '
                        )';
                        $data_insert = array();
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
                                    $row = array();
                                    $row['module'] =  $module;
                                    $row['id'] = $id;
                                    include NV_ROOTDIR . '/modules/' . $mod_info['module_file'] . '/comment.php';
                                }
                            }

                            if (!$status) {
                                $comment_success = $lang_module['comment_success_queue'];

                                // Gui thong bao kiem duyet
                                nv_insert_notification($module_name, 'comment_queue', array( 'content' => strip_tags($content) ), $new_id);
                            } else {
                                $comment_success = $lang_module['comment_success'];
                            }
                            $contents = 'OK_' . nv_base64_encode($comment_success);
                        }
                    } catch (PDOException $e) {
                        $contents = 'ERR_' . $e->getMessage();
                    }
                }
            } else {
                $timeout = nv_convertfromSec($difftimeout - NV_CURRENTTIME + $timeout);
                $timeoutmsg = sprintf($lang_module['comment_timeout'], $timeout);
                $contents = 'ERR_' . $timeoutmsg;
            }
        }
    }
}
include NV_ROOTDIR . '/includes/header.php';
echo '<script type="text/javascript">parent.nv_commment_reload("' . str_replace('"', '\"', $contents) . '");</script>';
include NV_ROOTDIR . '/includes/footer.php';
