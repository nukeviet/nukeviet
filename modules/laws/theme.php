<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Wed, 27 Jul 2011 14:55:22 GMT
 */

if (!defined('NV_IS_MOD_LAWS'))
    die('Stop!!!');

/**
 * nv_theme_laws_main()
 * 
 * @param mixed $array_data
 * @param mixed $generate_page
 * @return
 */
function nv_theme_laws_main($array_data, $generate_page)
{
    global $global_config, $module_name, $lang_module, $module_info, $op, $nv_laws_setting;

    $xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('generate_page', $generate_page);

    foreach ($array_data as $row) {
        $row['url_subject'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=subject/' . $row['alias'];
        $row['publtime'] = nv_date('d/m/Y', $row['publtime']);
        $row['exptime'] = nv_date('d/m/Y', $row['exptime']);
        $xtpl->assign('ROW', $row);

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
        $xtpl->parse('main.loop');
    }

    if ($nv_laws_setting['down_in_home']) {
        $xtpl->parse('main.down_in_home');
        $xtpl->parse('main.down_in_home_col');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_theme_laws_maincat()
 * 
 * @param mixed $mod
 * @param mixed $array_data
 * @return
 */
function nv_theme_laws_maincat($mod, $array_data)
{
    global $global_config, $module_name, $lang_module, $module_info, $op, $nv_laws_setting;

    $xtpl = new XTemplate('main_' . $mod . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);

    foreach ($array_data as $data) {
        $data['url_subject'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=subject/' . $data['alias'];
        $data['numcount'] = sprintf($lang_module['s_result_num'], $data['numcount']);
        $xtpl->assign('DATA', $data);

        if (!empty($data['rows'])) {
            foreach ($data['rows'] as $rows) {
                $rows['publtime'] = !empty($rows['publtime']) ? nv_date('d/m/Y', $rows['publtime']) : '';
                $xtpl->assign('ROW', $rows);

                if ($nv_laws_setting['down_in_home']) {
                    if (nv_user_in_groups($rows['groups_download'])) {
                        if (!empty($rows['files'])) {
                            foreach ($rows['files'] as $file) {
                                $xtpl->assign('FILE', $file);
                                $xtpl->parse('main.loop.row.down_in_home.files.loopfile');
                            }
                            $xtpl->parse('main.loop.row.down_in_home.files');
                        }
                    }
                    $xtpl->parse('main.loop.row.down_in_home');
                }

                $xtpl->parse('main.loop.row');
            }
        }
        if ($nv_laws_setting['down_in_home']) {
            $xtpl->parse('main.loop.down_in_home');
        }
        $xtpl->parse('main.loop');
    }

    if ($nv_laws_setting['down_in_home']) {
        $xtpl->parse('main.down_in_home');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_theme_laws_detail()
 * 
 * @param mixed $array_data
 * @param mixed $other_cat
 * @param mixed $other_area
 * @param mixed $other_subject
 * @param mixed $other_signer
 * @return
 */
function nv_theme_laws_detail($array_data, $other_cat = array(), $other_area = array(), $other_subject = array(), $other_signer = array())
{
    global $global_config, $module_name, $lang_module, $module_info, $op, $nv_laws_listcat, $nv_laws_listarea, $nv_laws_listsubject, $client_info, $nv_laws_setting;

    $xtpl = new XTemplate($module_info['funcs'][$op]['func_name'] . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);

    $array_data['publtime'] = $array_data['publtime'] ? nv_date('d/m/Y', $array_data['publtime']) : '';
    $array_data['startvalid'] = $array_data['startvalid'] ? nv_date('d/m/Y', $array_data['startvalid']) : '';
    $array_data['exptime'] = $array_data['exptime'] ? nv_date('d/m/Y', $array_data['exptime']) : '';

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
    if (empty($nv_laws_setting['detail_hide_empty_field'])or !empty($array_data['cat'])) {
        $filled_field++;
        if (!empty($nv_laws_setting['detail_show_link_cat'])) {
            $xtpl->parse('main.field.cat.link');
        } else {
            $xtpl->parse('main.field.cat.text');
        }
        $xtpl->parse('main.field.cat');
    }
    if (empty($nv_laws_setting['detail_hide_empty_field'])or !empty($array_data['subject'])) {
        $filled_field++;
        if (!empty($nv_laws_setting['detail_show_link_subject'])) {
            $xtpl->parse('main.field.subject.link');
        } else {
            $xtpl->parse('main.field.subject.text');
        }
        $xtpl->parse('main.field.subject');
    }
    if (empty($nv_laws_setting['detail_hide_empty_field'])or !empty($array_data['publtime'])) {
        $filled_field++;
        $xtpl->parse('main.field.publtime');
    }
    if (empty($nv_laws_setting['detail_hide_empty_field'])or !empty($array_data['startvalid'])) {
        $filled_field++;
        $xtpl->parse('main.field.startvalid');
    }
    if (empty($nv_laws_setting['detail_hide_empty_field'])or !empty($array_data['exptime'])) {
        $filled_field++;
        $xtpl->parse('main.field.exptime');
    }
    if (empty($nv_laws_setting['detail_hide_empty_field'])or !empty($array_data['signer'])) {
        $filled_field++;
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
        $filled_field++;
    }

    if (!empty($array_data['relatement'])) {
        foreach ($array_data['relatement'] as $relatement) {
            $xtpl->assign('relatement', $relatement);
            $xtpl->parse('main.field.relatement.loop');
        }
        $xtpl->parse('main.field.relatement');
        $filled_field++;
    }

    if (!empty($array_data['replacement'])) {
        foreach ($array_data['replacement'] as $replacement) {
            $xtpl->assign('replacement', $replacement);
            $xtpl->parse('main.field.replacement.loop');
        }
        $xtpl->parse('main.field.replacement');
        $filled_field++;
    }

    if (!empty($array_data['unreplacement'])) {
        foreach ($array_data['unreplacement'] as $unreplacement) {
            $xtpl->assign('unreplacement', $unreplacement);
            $xtpl->parse('main.field.unreplacement.loop');
        }
        $xtpl->parse('main.field.unreplacement');
        $filled_field++;
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

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_theme_laws_search()
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

    $xtpl->assign('generate_page', $generate_page);
    $xtpl->assign('NUMRESULT', sprintf($lang_module['s_result_num'], $all_page));

    $i = 1;
    foreach ($array_data as $row) {
        $row['publtime'] = nv_date('d/m/Y', $row['publtime']);
        $row['exptime'] = nv_date('d/m/Y', $row['exptime']);
        $xtpl->assign('ROW', $row);
        $xtpl->parse('main.loop');
        $i++;
    }

    if (empty($array_data)) {
        $xtpl->parse('empty');
        return $xtpl->text('empty');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_theme_laws_area()
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

    $xtpl->assign('generate_page', $generate_page);

    $i = 1;
    foreach ($array_data as $row) {
        $row['publtime'] = nv_date('d/m/Y', $row['publtime']);
        $row['exptime'] = nv_date('d/m/Y', $row['exptime']);
        $xtpl->assign('ROW', $row);

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
        $xtpl->parse('main.loop');
        $i++;
    }

    if ($nv_laws_setting['down_in_home']) {
        $xtpl->parse('main.down_in_home');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_theme_laws_cat()
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

    $xtpl->assign('generate_page', $generate_page);

    $i = 1;
    foreach ($array_data as $row) {
        $row['publtime'] = nv_date('d/m/Y', $row['publtime']);
        $row['exptime'] = nv_date('d/m/Y', $row['exptime']);
        $xtpl->assign('ROW', $row);
        $xtpl->parse('main.loop');
        $i++;
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_theme_laws_subject()
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

    $xtpl->assign('generate_page', $generate_page);

    $i = 1;
    foreach ($array_data as $row) {
        $row['publtime'] = nv_date('d/m/Y', $row['publtime']);
        $row['exptime'] = nv_date('d/m/Y', $row['exptime']);
        $xtpl->assign('ROW', $row);

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

        $xtpl->parse('main.loop');
        $i++;
    }

    if ($nv_laws_setting['down_in_home']) {
        $xtpl->parse('main.down_in_home');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_theme_laws_signer()
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

    $xtpl->assign('generate_page', $generate_page);

    $i = 1;
    foreach ($array_data as $row) {
        $row['publtime'] = nv_date('d/m/Y', $row['publtime']);
        $row['exptime'] = nv_date('d/m/Y', $row['exptime']);

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

        $xtpl->assign('ROW', $row);
        $xtpl->parse('main.loop');
        $i++;
    }

    if ($nv_laws_setting['down_in_home']) {
        $xtpl->parse('main.down_in_home');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_theme_laws_list_other()
 * 
 * @param mixed $array_data
 * @return
 */
function nv_theme_laws_list_other($array_data)
{
    global $global_config, $module_name, $lang_module, $module_info, $op, $nv_laws_setting;

    $xtpl = new XTemplate('list_other.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);

    $i = 1;
    foreach ($array_data as $row) {
        $row['publtime'] = nv_date('d/m/Y', $row['publtime']);
        $row['exptime'] = nv_date('d/m/Y', $row['exptime']);
        $xtpl->assign('ROW', $row);
        $xtpl->parse('main.loop');
        $i++;
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}


/**
 * nv_theme_viewpdf()
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
