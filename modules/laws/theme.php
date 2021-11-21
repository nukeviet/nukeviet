<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Wed, 27 Jul 2011 14:55:22 GMT
 */

if (!defined('NV_IS_MOD_LAWS')) {
    die('Stop!!!');
}

/**
 * Quy chuẩn hiển thị văn bản dạng danh sách
 *
 * @param array $array_data
 * @param string $generate_page
 * @param boolean $show_header
 * @param boolean $show_stt
 * @return string
 */
function nv_theme_laws_list($array_data, $generate_page = '', $show_header = true, $show_stt = true)
{
    global $lang_module, $lang_global, $module_info, $nv_laws_setting, $module_name, $module_config;

    $xtpl = new XTemplate('list.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);

    foreach ($array_data as $row) {
        $row['url_subject'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=subject/' . $row['alias'];
        $row['publtime'] = $row['publtime'] ? nv_date('d/m/Y', $row['publtime']) : 'N/A';
        $row['exptime'] = $row['exptime'] ? nv_date('d/m/Y', $row['exptime']) : 'N/A';
        $row['number_comm'] = $row['number_comm'] ? sprintf($lang_module['number_comm'], number_format($row['number_comm'], 0, ',', '.')) : '';

        $xtpl->assign('ROW', $row);

        if (empty($nv_laws_setting['title_show_type'])) {
            // Hiển thị trích yếu
            $xtpl->assign('LAW_TITLE', $row['introtext']);
        } elseif ($nv_laws_setting['title_show_type'] == 1) {
            // Hiển thị tiêu đề
            $xtpl->assign('LAW_TITLE', $row['title']);
        } else {
            // Hiển thị tiêu đề + trích yếu
            $xtpl->assign('LAW_TITLE', $row['title']);
            $xtpl->parse('main.loop.introtext');
        }

        // Tải file trực tiếp
        if ($nv_laws_setting['down_in_home']) {
            if (nv_user_in_groups($row['groups_download'])) {
                if (!empty($row['files'])) {
                    foreach ($row['files'] as $file) {
                        $xtpl->assign('FILE', $file);
                        $xtpl->parse('main.loop.down_in_home.files.loopfile');
                    }
                    $xtpl->parse('main.loop.down_in_home.files');
                }
            }
            $xtpl->parse('main.loop.down_in_home');
        }

        /*
         * Công cụ của admin (chỉ điều hành chung và admin tối cao)
         * Quản trị module bỏ qua vì còn phân quyền theo cơ quan ban hành
         */
        if (defined('NV_IS_SPADMIN')) {
            $xtpl->assign('LINK_DELETE', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
            $xtpl->parse('main.loop.admin_link');
        }

        // Lấy ý kiến dự thảo
        if ($module_config[$module_name]['activecomm']) {
            $comment_time = [];
            if (!empty($row['start_comm_time'])) {
                $comment_time[] = sprintf($lang_module['start_comm_time'], nv_date('d/m/Y', $row['start_comm_time']));
            }
            if (!empty($row['end_comm_time'])) {
                $comment_time[] = sprintf($lang_module['end_comm_time'], nv_date('d/m/Y', $row['end_comm_time']));
            }
            if (!empty($comment_time)) {
                $xtpl->assign('COMMENT_TIME', implode(' - ', $comment_time));
                $xtpl->parse('main.loop.comment_time');
            }

            if ($row['number_comm']) {
                $xtpl->parse('main.loop.shownumbers');
            }

            if ($row['allow_comm']) {
                $xtpl->parse('main.loop.send_comm');
            } else {
                $xtpl->parse('main.loop.comm_close');
            }
        } else {
            $xtpl->parse('main.loop.publtime');
        }

        // Hiển thị cột số thứ tự
        if ($show_stt) {
            $xtpl->parse('main.loop.stt');
        }

        $xtpl->parse('main.loop');
    }

    // Hiển thị tiêu đề
    if ($show_header) {
        // Tiêu đề khi lấy ý kiến dự thảo
        if ($module_config[$module_name]['activecomm']) {
            $xtpl->parse('main.header.send_comm_title');
        } else {
            $xtpl->parse('main.header.publtime_title');
        }

        // Tiêu đề khi tải file
        if ($nv_laws_setting['down_in_home']) {
            $xtpl->parse('main.header.down_in_home');
        }

        // Hiển thị cột số thứ tự
        if ($show_stt) {
            $xtpl->parse('main.header.stt');
        }

        $xtpl->parse('main.header');
    }

    // Phân trang
    if (!empty($generate_page)) {
        $xtpl->assign('GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.generate_page');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * Trang chủ của module ở dạng xem theo danh sách
 *
 * @param mixed $array_data
 * @param mixed $generate_page
 * @return
 */
function nv_theme_laws_main($array_data, $generate_page)
{
    global $lang_module, $lang_global, $module_info;

    $xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('HTML', nv_theme_laws_list($array_data, $generate_page));
    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * Trang chủ của module ở dạng xem theo cơ quan ban hành
 *
 * @param mixed $mod
 * @param mixed $array_data
 * @return
 */
function nv_theme_laws_maincat($mod, $array_data)
{
    global $global_config, $module_name, $module_config, $lang_module, $module_info, $op, $nv_laws_setting;

    $xtpl = new XTemplate('main_' . $mod . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);

    foreach ($array_data as $data) {
        $data['url_subject'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=subject/' . $data['alias'];
        $data['numcount'] = sprintf($lang_module['s_result_num'], $data['numcount']);

        $xtpl->assign('DATA', $data);

        if (!empty($data['rows'])) {
            $xtpl->assign('HTML', nv_theme_laws_list($data['rows'], '', false, false));
            $xtpl->parse('main.loop.rows');
        }

        $xtpl->parse('main.loop');
    }

    if (!empty($module_config[$module_name]['activecomm'])) {
        $xtpl->parse('main.send_comm_title');
    } else {
        $xtpl->parse('main.publtime_title');
    }

    if ($nv_laws_setting['down_in_home']) {
        $xtpl->parse('main.down_in_home');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * Xem chi tiết văn bản
 *
 * @param mixed $array_data
 * @param mixed $other_cat
 * @param mixed $other_area
 * @param mixed $other_subject
 * @param mixed $other_signer
 * @return
 */
function nv_theme_laws_detail($array_data, $other_cat = array(), $other_area = array(), $other_subject = array(), $other_signer = array(), $content_comment)
{
    global $global_config, $module_name, $module_config, $lang_module, $module_info, $op, $nv_laws_listcat, $nv_laws_listarea, $nv_laws_listsubject, $client_info, $nv_laws_setting;

    $xtpl = new XTemplate($module_info['funcs'][$op]['func_name'] . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);

    $array_data['publtime'] = $array_data['publtime'] ? nv_date('d/m/Y', $array_data['publtime']) : '';
    $array_data['startvalid'] = $array_data['startvalid'] ? nv_date('d/m/Y', $array_data['startvalid']) : '';
    $array_data['exptime'] = $array_data['exptime'] ? nv_date('d/m/Y', $array_data['exptime']) : '';
    $array_data['start_comm_time'] = $array_data['start_comm_time'] ? nv_date('d/m/Y', $array_data['start_comm_time']) : $lang_module['unlimit'];
    $array_data['end_comm_time'] = $array_data['end_comm_time'] ? nv_date('d/m/Y', $array_data['end_comm_time']) : $lang_module['unlimit'];
    $array_data['approval'] = $array_data['approval'] == 1 ? $lang_module['e1'] : $lang_module['e0'];
    if (isset($nv_laws_listcat[$array_data['cid']])) {
        $array_data['cat'] = $nv_laws_listcat[$array_data['cid']]['title'];
        $array_data['cat_url'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $nv_laws_listcat[$array_data['cid']]['alias'];
    } else {
        $array_data['cat'] = '';
        $array_data['cat_url'] = '#';
    }

    if (isset($nv_laws_listsubject[$array_data['sid']])) {
        $array_data['subject'] = $nv_laws_listsubject[$array_data['sid']]['title'];
        $array_data['subject_url'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=subject/' . $nv_laws_listsubject[$array_data['sid']]['alias'];
    } else {
        $array_data['subject'] = '';
        $array_data['subject_url'] = '';
    }

    $xtpl->assign('DATA', $array_data);

    // Ẩn giá trị trống
    $filled_field = 0;
    if (empty($nv_laws_setting['detail_hide_empty_field']) or !empty($array_data['cat'])) {
        $filled_field ++;
        if (!empty($nv_laws_setting['detail_show_link_cat'])) {
            $xtpl->parse('main.field.cat.link');
        } else {
            $xtpl->parse('main.field.cat.text');
        }
        $xtpl->parse('main.field.cat');
    }
    if (empty($nv_laws_setting['detail_hide_empty_field']) or !empty($array_data['subject'])) {
        $filled_field ++;
        if (!empty($nv_laws_setting['detail_show_link_subject'])) {
            $xtpl->parse('main.field.subject.link');
        } else {
            $xtpl->parse('main.field.subject.text');
        }
        $xtpl->parse('main.field.subject');
    }

    if ($module_config[$module_name]['activecomm']) {
        $xtpl->parse('main.field.start_comm_time');
        $xtpl->parse('main.field.end_comm_time');
        $xtpl->parse('main.field.approval');
    }

    if ((empty($nv_laws_setting['detail_hide_empty_field']) or !empty($array_data['publtime'])) && $module_config[$module_name]['activecomm'] == 0) {
        $filled_field ++;
        $xtpl->parse('main.field.publtime');
    }

    if ((empty($nv_laws_setting['detail_hide_empty_field']) or !empty($array_data['examine'])) && $module_config[$module_name]['activecomm'] == 1) {
        $filled_field ++;
        $xtpl->parse('main.field.examine');
    }

    if (empty($nv_laws_setting['detail_hide_empty_field']) or !empty($array_data['startvalid'])) {
        $filled_field ++;
        $xtpl->parse('main.field.startvalid');
    }
    if (empty($nv_laws_setting['detail_hide_empty_field']) or !empty($array_data['exptime'])) {
        $filled_field ++;
        $xtpl->parse('main.field.exptime');
    }
    if (empty($nv_laws_setting['detail_hide_empty_field']) or !empty($array_data['signer'])) {
        $filled_field ++;
        if (!empty($nv_laws_setting['detail_show_link_signer'])) {
            $xtpl->parse('main.field.signer.link');
        } else {
            $xtpl->parse('main.field.signer.text');
        }
        $xtpl->parse('main.field.signer');
    }

    if (!empty($array_data['aid'])) {
        foreach ($array_data['aid'] as $aid) {
            $area['title'] = $nv_laws_listarea[$aid]['title'];
            $area['url'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=area/' . $nv_laws_listarea[$aid]['alias'];
            $xtpl->assign('AREA', $area);

            if (!empty($nv_laws_setting['detail_show_link_area'])) {
                $xtpl->parse('main.field.area_link');
            } else {
                $xtpl->parse('main.field.area_text');
            }
        }
        $filled_field ++;
    }

    if (!empty($array_data['relatement'])) {
        foreach ($array_data['relatement'] as $relatement) {
            $xtpl->assign('relatement', $relatement);
            $xtpl->parse('main.field.relatement.loop');
        }
        $xtpl->parse('main.field.relatement');
        $filled_field ++;
    }

    if (!empty($array_data['replacement'])) {
        foreach ($array_data['replacement'] as $replacement) {
            $xtpl->assign('replacement', $replacement);
            $xtpl->parse('main.field.replacement.loop');
        }
        $xtpl->parse('main.field.replacement');
        $filled_field ++;
    }

    if (!empty($array_data['unreplacement'])) {
        foreach ($array_data['unreplacement'] as $unreplacement) {
            $xtpl->assign('unreplacement', $unreplacement);
            $xtpl->parse('main.field.unreplacement.loop');
        }
        $xtpl->parse('main.field.unreplacement');
        $filled_field ++;
    }

    if ($filled_field > 0) {
        $xtpl->parse('main.field');
    }

    if (!empty($array_data['bodytext'])) {
        $xtpl->parse('main.bodytext');
    }

    if (nv_user_in_groups($array_data['groups_download'])) {
        if (!empty($array_data['files'])) {
            foreach ($array_data['files'] as $file) {
                $xtpl->assign('FILE', $file);

                if ($file['ext'] == 'pdf' and !empty($nv_laws_setting['detail_pdf_quick_view'])) {
                    $xtpl->parse('main.files.loop.show_quick_view');
                    $xtpl->parse('main.files.loop.content_quick_view');
                }

                $xtpl->parse('main.files.loop');
            }
            $xtpl->parse('main.files');
        }
    } else {
        $xtpl->parse('main.nodownload');
    }

    /*
     * Sửa và xóa văn bản dành cho admin tối cao và điều hành chung
     * Quản trị module cần thao tác trong admin vì còn phân quyền
     */
    if (defined('NV_IS_SPADMIN')) {
        $xtpl->assign('LINK_DELETE', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
        $xtpl->parse('main.admin_link');
    }

    if (!empty($other_cat)) {
        $xtpl->assign('OTHER_CAT', nv_theme_laws_list_other($other_cat));
        $xtpl->parse('main.other_cat');
    }

    if (!empty($other_area)) {
        $xtpl->assign('OTHER_AREA', nv_theme_laws_list_other($other_area));
        $xtpl->parse('main.other_area');
    }

    if (!empty($other_subject)) {
        $xtpl->assign('OTHER_SUBJECT', nv_theme_laws_list_other($other_subject));
        $xtpl->parse('main.other_subject');
    }

    if (!empty($other_signer)) {
        $xtpl->assign('OTHER_SIGNER', nv_theme_laws_list_other($other_signer));
        $xtpl->parse('main.other_signer');
    }

    if (!empty($content_comment)) {
        $xtpl->assign('CONTENT_COMMENT', $content_comment);
        $xtpl->parse('main.comment');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * Trang tìm kiếm văn bản
 *
 * @param mixed $array_data
 * @param mixed $generate_page
 * @param mixed $all_page
 * @return
 */
function nv_theme_laws_search($array_data, $generate_page, $all_page)
{
    global $global_config, $module_name, $lang_module, $module_info, $op;

    $xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('NUMRESULT', sprintf($lang_module['s_result_num'], $all_page));

    if (empty($array_data)) {
        $xtpl->parse('empty');
        return $xtpl->text('empty');
    }

    $xtpl->assign('HTML', nv_theme_laws_list($array_data, $generate_page));

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * Trang xem văn bản theo lĩnh vực
 *
 * @param mixed $array_data
 * @param mixed $generate_page
 * @param mixed $cat
 * @return
 */
function nv_theme_laws_area($array_data, $generate_page, $cat)
{
    global $global_config, $module_name, $lang_module, $module_info, $op, $nv_laws_setting;

    $xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('CAT', $cat);
    $xtpl->assign('HTML', nv_theme_laws_list($array_data, $generate_page));

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * Trang xem văn bản theo thể loại
 *
 * @param mixed $array_data
 * @param mixed $generate_page
 * @param mixed $cat
 * @return
 */
function nv_theme_laws_cat($array_data, $generate_page, $cat)
{
    global $global_config, $module_name, $lang_module, $module_info, $op;

    $xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('CAT', $cat);
    $xtpl->assign('HTML', nv_theme_laws_list($array_data, $generate_page));

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * Trang xem văn bản theo cơ quan ban hành
 *
 * @param mixed $array_data
 * @param mixed $generate_page
 * @param mixed $cat
 * @return
 */
function nv_theme_laws_subject($array_data, $generate_page, $cat)
{
    global $global_config, $module_name, $nv_laws_setting, $lang_module, $module_info, $op;

    $xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('CAT', $cat);
    $xtpl->assign('HTML', nv_theme_laws_list($array_data, $generate_page));

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * Trang xem văn bản theo người ký
 *
 * @param mixed $array_data
 * @param mixed $generate_page
 * @param mixed $cat
 * @return
 */
function nv_theme_laws_signer($array_data, $generate_page, $cat)
{
    global $global_config, $module_name, $lang_module, $module_info, $op, $nv_laws_setting;

    $xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('CAT', $cat);
    $xtpl->assign('HTML', nv_theme_laws_list($array_data, $generate_page));

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * Danh sách các văn bản khác tại phần xem chi tiết văn bản
 *
 * @param mixed $array_data
 * @return
 */
function nv_theme_laws_list_other($array_data)
{
    global $global_config, $module_name, $lang_module, $module_info, $op, $nv_laws_setting, $module_config, $site_mods;

    $xtpl = new XTemplate('list_other.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);

    foreach ($array_data as $row) {
        $row['publtime'] = $row['publtime'] ? nv_date('d/m/Y', $row['publtime']) : 'N/A';
        $row['exptime'] = $row['exptime'] ? nv_date('d/m/Y', $row['exptime']) : 'N/A';

        $row['comm_time'] = [];
        if (!empty($row['start_comm_time'])) {
            $row['comm_time'][] = sprintf($lang_module['start_comm_time'], nv_date('d/m/Y', $row['start_comm_time']));
        }
        if (!empty($row['end_comm_time'])) {
            $row['comm_time'][] = sprintf($lang_module['end_comm_time'], nv_date('d/m/Y', $row['end_comm_time']));
        }
        $row['comm_time'] = implode(' - ', $row['comm_time']);

        $xtpl->assign('ROW', $row);

        if (empty($nv_laws_setting['title_show_type'])) {
            // Hiển thị trích yếu
            $xtpl->assign('LAW_TITLE', $row['introtext']);
        } elseif ($nv_laws_setting['title_show_type'] == 1) {
            // Hiển thị tiêu đề
            $xtpl->assign('LAW_TITLE', $row['title']);
        } else {
            // Hiển thị tiêu đề + trích yếu
            $xtpl->assign('LAW_TITLE', $row['title']);
            $xtpl->parse('main.loop.introtext');
        }

        if (isset($site_mods['comment']) and !empty($module_config[$module_name]['activecomm'])) {
            $xtpl->parse('main.loop.comm_time');
        } else {
            $xtpl->parse('main.loop.publtime');
        }

        $xtpl->parse('main.loop');
    }

    if (isset($site_mods['comment']) and !empty($module_config[$module_name]['activecomm'])) {
        $xtpl->parse('main.comm_time');
    } else {
        $xtpl->parse('main.publtime_title');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * Xem trước văn bản khi file đính kèm là PDF
 *
 * @param mixed $file_url
 * @return
 */
function nv_theme_viewpdf($file_url)
{
    global $lang_module, $lang_global;
    $xtpl = new XTemplate('viewer.tpl', NV_ROOTDIR . '/' . NV_ASSETS_DIR . '/js/pdf.js');
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('PDF_JS_DIR', NV_BASE_SITEURL . NV_ASSETS_DIR . '/js/pdf.js/');
    $xtpl->assign('PDF_URL', $file_url);
    $xtpl->parse('main');
    return $xtpl->text('main');
}
