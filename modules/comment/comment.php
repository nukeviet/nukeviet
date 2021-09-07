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
 * nv_comment_data()
 *
 * @param string $module
 * @param string $area
 * @param int    $id
 * @param int    $page
 * @param int    $sortcomm
 * @param string $base_url
 * @return array
 */
function nv_comment_data($module, $area, $id, $page, $sortcomm, $base_url)
{
    global $db_slave, $module_config;

    $comment_array = [];
    $per_page_comment = empty($module_config[$module]['perpagecomm']) ? 5 : $module_config[$module]['perpagecomm'];

    $_where = 'a.module=' . $db_slave->quote($module);
    if ($area) {
        $_where .= ' AND a.area= ' . $area;
    }
    $_where .= ' AND a.id= ' . $id . ' AND a.status=1 AND a.pid=0';

    $db_slave->sqlreset()
        ->select('COUNT(*)')
        ->from(NV_PREFIXLANG . '_comment a')
        ->join('LEFT JOIN ' . NV_USERS_GLOBALTABLE . ' b ON a.userid =b.userid')
        ->where($_where);

    $num_items = $db_slave->query($db_slave->sql())
        ->fetchColumn();

    $total = ceil($num_items / $per_page_comment);
    if ($page > 1 and $page > $total) {
        $page = 1;
    }

    if ($num_items) {
        $emailcomm = $module_config[$module]['emailcomm'];
        $db_slave->select('a.cid, a.pid, a.content, a.attach, a.post_time, a.post_name, a.post_email, a.likes, a.dislikes, b.userid, b.username, b.email, b.first_name, b.last_name, b.photo, b.view_mail')
            ->limit($per_page_comment)
            ->offset(($page - 1) * $per_page_comment);

        if ($sortcomm == 1) {
            $db_slave->order('a.cid ASC');
        } elseif ($sortcomm == 2) {
            $db_slave->order('a.likes DESC, a.cid DESC');
        } else {
            $db_slave->order('a.cid DESC');
        }

        $result = $db_slave->query($db_slave->sql());
        $comment_list_id = [];
        while ($row = $result->fetch()) {
            $comment_list_id[] = $row['cid'];
            if ($row['userid'] > 0) {
                $row['post_email'] = $row['email'];
                $row['post_name'] = $row['first_name'];
            }
            $row['check_like'] = md5($row['cid'] . '_' . NV_CHECK_SESSION);
            $row['post_email'] = ($emailcomm) ? $row['post_email'] : '';
            if (!empty($row['attach'])) {
                $row['attach'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=comment&amp;' . NV_OP_VARIABLE . '=down&cid=' . $row['cid'] . '&amp;tokend=' . md5($row['cid'] . '_' . NV_CHECK_SESSION);
            }
            $comment_array[$row['cid']] = $row;
        }
        if (!empty($comment_list_id)) {
            foreach ($comment_list_id as $cid) {
                $comment_array[$cid]['subcomment'] = nv_comment_get_reply($cid, $module, NV_CHECK_SESSION, $sortcomm);
            }
            $result->closeCursor();
            unset($row, $result);
            $generate_page = nv_generate_page($base_url, $num_items, $per_page_comment, $page, true, true, 'nv_urldecode_ajax', 'showcomment');
        } else {
            $generate_page = '';
        }

        return [
            'comment' => $comment_array,
            'page' => $generate_page
        ];
    }
}

/**
 * nv_comment_get_reply()
 *
 * @param int    $cid
 * @param string $module
 * @param string $session_id
 * @param int    $sortcomm
 * @return array
 */
function nv_comment_get_reply($cid, $module, $session_id, $sortcomm)
{
    global $db_slave, $module_config;

    $db_slave->sqlreset()
        ->select('COUNT(*)')
        ->from(NV_PREFIXLANG . '_comment a')
        ->join('LEFT JOIN ' . NV_USERS_GLOBALTABLE . ' b ON a.userid =b.userid')
        ->where('a.pid=' . $cid . ' AND a.status=1');

    $data_reply_comment = [];

    $num_items_sub = $db_slave->query($db_slave->sql())
        ->fetchColumn();
    if ($num_items_sub) {
        $emailcomm = $module_config[$module]['emailcomm'];
        $db_slave->select('a.cid, a.pid, a.content, a.attach, a.post_time, a.post_name, a.post_email, a.likes, a.dislikes, b.userid, b.email, b.first_name, b.last_name, b.photo, b.view_mail');

        if ($sortcomm == 1) {
            $db_slave->order('a.cid ASC');
        } elseif ($sortcomm == 2) {
            $db_slave->order('a.likes DESC, a.cid DESC');
        } else {
            $db_slave->order('a.cid DESC');
        }
        $result = $db_slave->query($db_slave->sql());
        $comment_list_id_reply = [];
        while ($row = $result->fetch()) {
            $row['check_like'] = md5($row['cid'] . '_' . $session_id);
            $row['post_email'] = ($emailcomm) ? $row['post_email'] : '';
            if (!empty($row['attach'])) {
                $row['attach'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=comment&amp;' . NV_OP_VARIABLE . '=down&cid=' . $row['cid'] . '&amp;tokend=' . md5($row['cid'] . '_' . NV_CHECK_SESSION);
            }
            $data_reply_comment[$row['cid']] = $row;
            $data_reply_comment[$row['cid']]['subcomment'] = nv_comment_get_reply($row['cid'], $module, $session_id, $sortcomm);
        }
    }

    return $data_reply_comment;
}

/**
 * nv_comment_load()
 *
 * @param string $module
 * @param string $checkss
 * @param string $area
 * @param int    $id
 * @param mixed  $allowed
 * @param int    $page
 * @param string $status_comment
 * @return string
 */
function nv_comment_load($module, $checkss, $area, $id, $allowed, $page, $status_comment = '')
{
    global $module_config, $nv_Request, $lang_module_comment;

    // Kiểm tra module có được Sử dụng chức năng bình luận
    if (!empty($module) and isset($module_config[$module]['activecomm'])) {
        if ($id > 0 and $module_config[$module]['activecomm'] == 1 and $checkss == md5($module . '-' . $area . '-' . $id . '-' . $allowed . '-' . NV_CACHE_PREFIX)) {
            if (file_exists(NV_ROOTDIR . '/modules/comment/language/' . NV_LANG_INTERFACE . '.php')) {
                require NV_ROOTDIR . '/modules/comment/language/' . NV_LANG_INTERFACE . '.php';
            } else {
                require NV_ROOTDIR . '/modules/comment/language/en.php';
            }
            $lang_module_comment = $lang_module;

            $view_comm = nv_user_in_groups($module_config[$module]['view_comm']);
            if ($view_comm) {
                $allowed_comm = nv_user_in_groups($allowed);
                $sortcomm_old = $nv_Request->get_int('sortcomm', 'cookie', $module_config[$module]['sortcomm']);
                $sortcomm = $nv_Request->get_int('sortcomm', 'post', $sortcomm_old);
                if ($sortcomm < 0 or $sortcomm > 2) {
                    $sortcomm = 0;
                }
                if ($sortcomm_old != $sortcomm) {
                    $nv_Request->set_Cookie('sortcomm', $sortcomm, NV_LIVE_COOKIE_TIME);
                }
                $per_page_comment = empty($module_config[$module]['perpagecomm']) ? 5 : $module_config[$module]['perpagecomm'];
                $base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=comment&module=' . $module . '&area=' . $area . '&id=' . $id . '&allowed=' . $allowed . '&checkss=' . $checkss . '&comment_load=1&perpage=' . $per_page_comment;
                $comment_array = nv_comment_data($module, $area, $id, $page, $sortcomm, $base_url);

                $is_delete = false;
                if (defined('NV_IS_SPADMIN')) {
                    $is_delete = true;
                } elseif (defined('NV_IS_ADMIN')) {
                    global $admin_info;
                    $adminscomm = array_map('intval', explode(',', $module_config[$module]['adminscomm']));
                    if (in_array((int) $admin_info['admin_id'], $adminscomm, true)) {
                        $is_delete = true;
                    }
                }

                return nv_comment_module_data($module, $comment_array, $is_delete, $allowed_comm, $status_comment);
            }
        }
    }

    return '';
}

/**
 * nv_comment_module()
 *
 * @param string $module
 * @param string $checkss
 * @param string $area
 * @param int    $id
 * @param mixed  $allowed
 * @param int    $page
 * @param string $status_comment
 * @param int    $header
 * @return string|void
 */
function nv_comment_module($module, $checkss, $area, $id, $allowed, $page, $status_comment = '', $header = 1)
{
    global $module_config, $nv_Request, $lang_module_comment, $module_info, $global_config, $lang_global;

    // Kiểm tra module có được Sử dụng chức năng bình luận
    if (!empty($module) and isset($module_config[$module]['activecomm'])) {
        if ($id > 0 and $module_config[$module]['activecomm'] == 1 and $checkss == md5($module . '-' . $area . '-' . $id . '-' . $allowed . '-' . NV_CACHE_PREFIX)) {
            $per_page_comment = empty($module_config[$module]['perpagecomm']) ? 5 : $module_config[$module]['perpagecomm'];
            $base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=comment&module=' . $module . '&area=' . $area . '&id=' . $id . '&allowed=' . $allowed . '&checkss=' . $checkss . '&comment_load=1&perpage=' . $per_page_comment;

            if (file_exists(NV_ROOTDIR . '/modules/comment/language/' . NV_LANG_INTERFACE . '.php')) {
                require NV_ROOTDIR . '/modules/comment/language/' . NV_LANG_INTERFACE . '.php';
            } else {
                require NV_ROOTDIR . '/modules/comment/language/en.php';
            }
            $lang_module_comment = $lang_module;

            // Kiểm tra quyền xem bình luận
            $form_login = [
                'display' => 0, // Có hiển thị form login hay ẩn
                'mode' => 'direct', // Trực tiếp đăng nhập hay đăng nhập nhóm
                'link' => '', // Link thông báo
                'groups' => [] // Các nhóm cần tham gia vào, hoặc đăng nhập dưới quyền
            ];
            $view_comm = nv_user_in_groups($module_config[$module]['view_comm']);
            $allowed_comm = nv_user_in_groups($allowed);
            // Xử lý nếu có quyền xem và không có quyền bình
            if ($view_comm and !$allowed_comm and $global_config['allowuserlogin']) {
                $allowed_tmp = explode(',', $allowed);
                $allowed_tmp = array_flip(array_diff($allowed_tmp, [
                    1,
                    2,
                    3
                ])); // Loại nhóm quản trị ra
                if ((isset($allowed_tmp['4']) or isset($allowed_tmp['7']))) {
                    // Thành viên chính thức hoặc thành viên mới thì đăng nhập trực tiếp
                    $form_login['display'] = 1;
                    if (!isset($allowed_tmp['7'])) {
                        // Thành viên chính thức
                        $form_login['groups'][0] = $lang_global['level4'];
                    } else {
                        // Thành viên chính thức hoặc thành viên mới
                        $form_login['groups'][0] = $lang_module_comment['user'];
                    }
                } else {
                    $list_groups = array_intersect_key(nv_groups_list_pub(), $allowed_tmp);
                    if (!empty($list_groups)) {
                        $form_login['display'] = 1;
                        $form_login['mode'] = 'reggroups';
                        $form_login['groups'] = $list_groups;
                        if (defined('NV_IS_USER')) {
                            $form_login['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=editinfo/group';
                        } else {
                            $form_login['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=login&amp;nv_redirect=' . nv_redirect_encrypt(nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=editinfo/group', true));
                        }
                    }
                }
            }

            $sortcomm = $nv_Request->get_int('sortcomm', 'cookie', $module_config[$module]['sortcomm']);
            if ($sortcomm < 0 or $sortcomm > 2) {
                $sortcomm = 0;
            }

            $is_delete = false;
            if (defined('NV_IS_SPADMIN')) {
                $is_delete = true;
            } elseif (defined('NV_IS_ADMIN')) {
                global $admin_info;
                $adminscomm = array_map('intval', explode(',', $module_config[$module]['adminscomm']));
                if (in_array((int) $admin_info['admin_id'], $adminscomm, true)) {
                    $is_delete = true;
                }
            }
            if ($view_comm) {
                $comment_array = nv_comment_data($module, $area, $id, $page, $sortcomm, $base_url);
                $comment = nv_comment_module_data($module, $comment_array, $is_delete, $allowed_comm, $status_comment);
            } else {
                $comment = '';
            }

            return nv_theme_comment_module($module, $area, $id, $allowed, $checkss, $comment, $sortcomm, $form_login, $header);
        }

        return '';
    }
}

/**
 * nv_theme_comment_module()
 *
 * @param string $module
 * @param string $area
 * @param int    $id
 * @param mixed  $allowed_comm
 * @param string $checkss
 * @param mixed  $comment
 * @param int    $sortcomm
 * @param array  $form_login
 * @param int    $header
 * @return string
 */
function nv_theme_comment_module($module, $area, $id, $allowed_comm, $checkss, $comment, $sortcomm, $form_login, $header = 1)
{
    global $global_config, $module_data, $module_config, $admin_info, $user_info, $lang_global, $lang_module_comment, $module_name;

    $template = file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/comment/main.tpl') ? $global_config['module_theme'] : 'default';
    $templateCSS = file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/css/comment.css') ? $global_config['module_theme'] : 'default';
    $templateJS = file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/js/comment.js') ? $global_config['module_theme'] : 'default';

    $xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $template . '/modules/comment');
    $xtpl->assign('LANG', $lang_module_comment);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('TEMPLATE', $template);
    $xtpl->assign('TEMPLATE_CSS', $templateCSS);
    $xtpl->assign('TEMPLATE_JS', $templateJS);
    $xtpl->assign('CHECKSS_COMM', $checkss);
    $xtpl->assign('MODULE_COMM', $module);
    $xtpl->assign('MODULE_DATA', $module_data);
    $xtpl->assign('AREA_COMM', $area);
    $xtpl->assign('ID_COMM', $id);
    $xtpl->assign('ALLOWED_COMM', $allowed_comm);
    $xtpl->assign('COMMENTCONTENT', $comment);

    // Hiện không dùng, giữ lại để tương thích phiên bản cũ.
    // $xtpl->assign('BASE_URL_COMM', $base_url);

    if (defined('NV_COMM_ID')) {
        $xtpl->parse('main.header');
    }

    // Order by comm
    for ($i = 0; $i <= 2; ++$i) {
        $xtpl->assign('OPTION', [
            'key' => $i,
            'title' => $lang_module_comment['sortcomm_' . $i],
            'selected' => ($i == $sortcomm) ? ' selected="selected"' : ''
        ]);

        $xtpl->parse('main.sortcomm');
    }

    $allowed_comm = nv_user_in_groups($allowed_comm);
    if ($allowed_comm) {
        $xtpl->assign('FORM_ACTION', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=comment&amp;' . NV_OP_VARIABLE . '=post');

        if (defined('NV_IS_USER')) {
            $xtpl->assign('NAME', $user_info['full_name']);
            $xtpl->assign('EMAIL', $user_info['email']);
            $xtpl->assign('DISABLED', ' disabled="disabled"');
        } else {
            $xtpl->assign('NAME', '');
            $xtpl->assign('EMAIL', '');
            $xtpl->assign('DISABLED', '');
        }

        if (!empty($module_config[$module]['allowattachcomm'])) {
            $xtpl->assign('ENCTYPE', ' enctype="multipart/form-data"');
            $xtpl->parse('main.allowed_comm.attach');
        }

        if (!empty($module_config[$module]['alloweditorcomm'])) {
            $xtpl->assign('EDITOR_COMM', 1);
            if ($header) {
                $xtpl->assign('NV_EDITORSDIR', NV_EDITORSDIR);
                $xtpl->assign('TIMESTAMP', $global_config['timestamp']);
                $xtpl->parse('main.allowed_comm.editor');
            }
        } else {
            $xtpl->assign('EDITOR_COMM', 0);
        }

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

        if ($show_captcha) {
            if ($captcha_type == 'recaptcha' and $global_config['recaptcha_ver'] == 3) {
                $xtpl->parse('main.allowed_comm.recaptcha3');
            } elseif ($captcha_type == 'recaptcha' and $global_config['recaptcha_ver'] == 2) {
                $xtpl->assign('RECAPTCHA_ELEMENT', 'recaptcha' . nv_genpass(8));
                $xtpl->assign('GFX_NUM', -1);
                $xtpl->parse('main.allowed_comm.recaptcha');
            } elseif ($captcha_type == 'captcha') {
                $xtpl->assign('N_CAPTCHA', $lang_global['securitycode']);
                $xtpl->assign('CAPTCHA_REFRESH', $lang_global['captcharefresh']);
                $xtpl->assign('GFX_NUM', NV_GFX_NUM);
                $xtpl->assign('GFX_WIDTH', NV_GFX_WIDTH);
                $xtpl->assign('GFX_WIDTH', NV_GFX_WIDTH);
                $xtpl->assign('GFX_HEIGHT', NV_GFX_HEIGHT);
                $xtpl->assign('CAPTCHA_REFR_SRC', NV_STATIC_URL . NV_ASSETS_DIR . '/images/refresh.png');
                $xtpl->assign('SRC_CAPTCHA', NV_BASE_SITEURL . 'index.php?scaptcha=captcha&t=' . NV_CURRENTTIME);
                $xtpl->parse('main.allowed_comm.captcha');
            } else {
                $xtpl->assign('GFX_NUM', 0);
            }
        } else {
            $xtpl->assign('GFX_NUM', 0);
        }

        $xtpl->parse('main.allowed_comm');
    } elseif ($form_login['display']) {
        if ($form_login['mode'] == 'direct') {
            // Thành viên đăng nhập trực tiếp
            $xtpl->assign('LOGIN_MESSAGE', sprintf($lang_module_comment['comment_login'], $form_login['groups'][0]));
            $xtpl->parse('main.form_login.message_login');
        } else {
            // Tham gia nhóm để bình luận
            $xtpl->assign('LANG_REG_GROUPS', sprintf($lang_module_comment['comment_register_groups'], implode(', ', $form_login['groups']), $form_login['link']));
            $xtpl->parse('main.form_login.message_register_group');
        }
        $xtpl->parse('main.form_login');
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * nv_comment_module_data()
 *
 * @param string $module
 * @param array  $comment_array
 * @param bool   $is_delete
 * @param bool   $allowed_comm
 * @param string $status_comment
 * @return string
 */
function nv_comment_module_data($module, $comment_array, $is_delete, $allowed_comm, $status_comment)
{
    global $global_config, $module_config, $lang_module_comment;

    $template = file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/comment/comment.tpl') ? $global_config['module_theme'] : 'default';

    if (!empty($comment_array['comment'])) {
        $xtpl = new XTemplate('comment.tpl', NV_ROOTDIR . '/themes/' . $template . '/modules/comment');
        $xtpl->assign('TEMPLATE', $template);
        $xtpl->assign('LANG', $lang_module_comment);

        if (!empty($status_comment)) {
            $status_comment = nv_base64_decode($status_comment);
            $xtpl->assign('STATUS_COMMENT', $status_comment);
            $xtpl->parse('main.comment_result');
        }

        foreach ($comment_array['comment'] as $comment_array_i) {
            if (!empty($comment_array_i['subcomment'])) {
                $comment_array_reply = nv_comment_module_data_reply($module, $comment_array_i['subcomment'], $is_delete, $allowed_comm);
                $xtpl->assign('CHILDREN', $comment_array_reply);
                $xtpl->parse('main.detail.children');
            }
            $comment_array_i['post_time'] = nv_date('d/m/Y H:i', $comment_array_i['post_time']);

            if (!empty($comment_array_i['photo']) and file_exists(NV_ROOTDIR . '/' . $comment_array_i['photo'])) {
                $comment_array_i['photo'] = NV_BASE_SITEURL . $comment_array_i['photo'];
            } elseif (is_file(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/images/users/no_avatar.png')) {
                $comment_array_i['photo'] = NV_STATIC_URL . 'themes/' . $global_config['module_theme'] . '/images/users/no_avatar.png';
            } else {
                $comment_array_i['photo'] = NV_STATIC_URL . 'themes/default/images/users/no_avatar.png';
            }

            if (!empty($comment_array_i['userid'])) {
                $comment_array_i['post_name'] = nv_show_name_user($comment_array_i['first_name'], $comment_array_i['last_name'], $comment_array_i['username']);
            }

            $xtpl->assign('COMMENT', $comment_array_i);

            if ($module_config[$module]['emailcomm'] and !empty($comment_array_i['post_email'])) {
                $xtpl->parse('main.detail.emailcomm');
            }

            if ($allowed_comm) {
                $xtpl->parse('main.detail.allowed_comm');
            }

            if ($is_delete) {
                $xtpl->parse('main.detail.delete');
            }

            if (!empty($comment_array_i['attach'])) {
                $xtpl->parse('main.detail.attach');
            }

            $xtpl->parse('main.detail');
        }
        if (!empty($comment_array['page'])) {
            $xtpl->assign('PAGE', $comment_array['page']);
        }
        $xtpl->parse('main');

        return $xtpl->text('main');
    }

    return '';
}

/**
 * nv_comment_module_data_reply()
 *
 * @param string $module
 * @param array  $comment_array
 * @param bool   $is_delete
 * @param bool   $allowed_comm
 * @return string
 */
function nv_comment_module_data_reply($module, $comment_array, $is_delete, $allowed_comm)
{
    global $global_config, $module_file, $module_config, $lang_module_comment;

    $template = file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/comment/comment.tpl') ? $global_config['module_theme'] : 'default';

    $xtpl = new XTemplate('comment.tpl', NV_ROOTDIR . '/themes/' . $template . '/modules/comment');
    $xtpl->assign('TEMPLATE', $template);
    $xtpl->assign('LANG', $lang_module_comment);

    foreach ($comment_array as $comment_array_i) {
        if (!empty($comment_array_i['subcomment'])) {
            $comment_array_reply = nv_comment_module_data_reply($module, $comment_array_i['subcomment'], $is_delete, $allowed_comm);
            $xtpl->assign('CHILDREN', $comment_array_reply);
            $xtpl->parse('children.detail.children');
        }
        $comment_array_i['post_time'] = nv_date('d/m/Y H:i', $comment_array_i['post_time']);

        if (!empty($comment_array_i['photo']) and file_exists(NV_ROOTDIR . '/' . $comment_array_i['photo'])) {
            $comment_array_i['photo'] = NV_BASE_SITEURL . $comment_array_i['photo'];
        } else {
            $comment_array_i['photo'] = NV_STATIC_URL . 'themes/' . $global_config['module_theme'] . '/images/users/no_avatar.png';
        }

        if (!empty($comment_array_i['userid'])) {
            $comment_array_i['post_name'] = nv_show_name_user($comment_array_i['first_name'], $comment_array_i['last_name']);
        }

        $xtpl->assign('COMMENT', $comment_array_i);

        if ($module_config[$module]['emailcomm'] and !empty($comment_array_i['post_email'])) {
            $xtpl->parse('children.detail.emailcomm');
        }

        if ($allowed_comm) {
            $xtpl->parse('children.detail.allowed_comm');
        }

        if ($is_delete) {
            $xtpl->parse('children.detail.delete');
        }

        if (!empty($comment_array_i['attach'])) {
            $xtpl->parse('children.detail.attach');
        }

        $xtpl->parse('children.detail');
    }
    $xtpl->parse('children');

    return $xtpl->text('children');
}
