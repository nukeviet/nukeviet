<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */
 
if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}
$table_name = $db_config['prefix'] . '_' . $module_data . '_tabs';

$arr_tab = array();
$arr_tab['introduce'] = 'introduce';
$sql = 'SELECT * FROM ' . $table_name . ' where content = ' . $db->quote('content_customdata') . ' ORDER BY weight ASC';
$result = $db->query($sql);
$field_lang = nv_file_table($table_name);

while ($row = $result->fetch()) {
    $arr_tab[$row['id']] = $row[NV_LANG_DATA . '_title'];
}

if ($nv_Request->isset_request('ajax_action', 'post')) {
    $fid = $nv_Request->get_int('fid', 'post', 0);
    $new_vid = $nv_Request->get_int('new_vid', 'post', 0);
    $content = 'NO_' . $fid;
    if ($new_vid > 0) {
        $sql = 'SELECT fid FROM ' . $db_config['prefix'] . '_' . $module_data . '_field WHERE fid!=' . $fid . ' ORDER BY weight ASC';
        $result = $db->query($sql);
        $weight = 0;
        while ($row = $result->fetch()) {
            ++ $weight;
            if ($weight == $new_vid) {
                ++ $weight;
            }
            $sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_field SET weight=' . $weight . ' WHERE fid=' . $row['fid'];
            $db->query($sql);
        }
        $sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_field SET weight=' . $new_vid . ' WHERE fid=' . $fid;
        $db->query($sql);
        $content = 'OK_' . $fid;
    }
    $nv_Cache->delMod($module_name);
    include NV_ROOTDIR . '/includes/header.php';
    echo $content;
    include NV_ROOTDIR . '/includes/footer.php';
    exit();
}

$row = array();
$row['fid'] = $nv_Request->get_int('fid', 'post,get', 0);
$template = $nv_Request->get_int('template', 'post,get', 0);

// Fetch Limit
$show_view = false;
if (! $nv_Request->isset_request('id', 'post,get')) {
    $show_view = true;
    $per_page = 20;
    $page = $nv_Request->get_int('page', 'post,get', 1);
    $db->sqlreset()
        ->select('COUNT(*)')
        ->from('' . $db_config['prefix'] . '_' . $module_data . '_field');
    $sth = $db->prepare($db->sql());
    $sth->execute();
    $num_items = $sth->fetchColumn();
    
    $db->select('*')
        ->where(' FIND_IN_SET (' . $template . ',listtemplate)')
        ->order('weight ASC')
        ->limit($per_page)
        ->offset(($page - 1) * $per_page);
    $sth = $db->prepare($db->sql());
    $sth->execute();
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('ROW', $row);
$xtpl->assign('template', $template);

if ($show_view) {
    $arr_tab_tmp = $arr_tab;
    $arr_tab_tmp['introduce'] = $lang_module['field_info_list'];
    foreach ($arr_tab_tmp as $key => $value) {
        $xtpl->assign('title_tab', $value);
        $xtpl->parse('main.view.title_tab');
    }
    unset($arr_tab_tmp);
    
    while ($view = $sth->fetch()) {
        $arr_display_tab = unserialize($view['tab']);
        for ($i = 1; $i <= $num_items; ++ $i) {
            $xtpl->assign('WEIGHT', array(
                'key' => $i,
                'title' => $i,
                'selected' => ($i == $view['weight']) ? ' selected="selected"' : ''
            ));
            $xtpl->parse('main.view.loop.weight_loop');
        }
        $view['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;fid=' . $view['fid'];
        $view['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_fid=' . $view['fid'] . '&amp;delete_checkss=' . md5($view['fid'] . NV_CACHE_PREFIX . $client_info['session_id']);
        $xtpl->assign('VIEW', $view);
        foreach ($arr_tab as $key => $value) {
            $xtpl->assign('tab', $key);
            if (! empty($arr_display_tab[$template])) {
                $xtpl->assign('CHECK', in_array($key, $arr_display_tab[$template]) ? ' checked="checked"' : '');
            }
            
            $xtpl->parse('main.view.loop.tab');
        }
        $xtpl->parse('main.view.loop');
    }
    $xtpl->parse('main.view');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['field_tab_page'];

if ($nv_Request->isset_request('submit', 'post')) { // luu lai
    $check = $nv_Request->get_array('check', 'post');
    $template = $nv_Request->get_int('template', 'post');
    $arr_tab_ser = array();
    
    $sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_field where FIND_IN_SET (' . $template . ',listtemplate)';
    $result = $db->query($sql);
    while ($row = $result->fetch()) {
        $row['tab'] = unserialize($row['tab']);
        $row['tab'][$template] = null;
        $tab_old[$row['fid']][] = $row['tab'];
        $tab = serialize($row['tab']);
        $sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_field SET tab=' . $db->quote($tab) . ' WHERE fid=' . $row['fid'];
        $db->query($sql);
    }
    
    foreach ($check as $key => $value) {
        $tab = '';
        $arr_tab_ser = array();
        foreach ($value as $val) {
            $arr_tab_ser[$template][] = $val;
        }
        
        foreach ($tab_old[$key][0] as $key_old => $value_old) {
            if ($key_old != $template and $value_old != null) {
                $arr_tab_ser[$key_old] = $value_old;
            }
        }
        $tab = serialize($arr_tab_ser);
        $sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_field SET tab=' . $db->quote($tab) . ' WHERE fid=' . $key;
        $db->query($sql);
    }
    
    // Tao file tpl
    $sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_field';
    $result = $db->query($sql);
    while ($row = $result->fetch()) {
        $row['tab'] = unserialize($row['tab']);
        foreach ($row['tab'] as $key => $value) {
            foreach ($value as $val) {
                $arr[$val][] = $row['field'];
            }
        }
    }
    
    foreach ($arr as $key => $value) {
        // loai bo phan tu trung nhau
        
        $arr_tab_tpl[$key] = array_unique($value);
    }
    
    if (! file_exists(NV_ROOTDIR . '/' . NV_ASSETS_DIR . '/' . $module_upload . '/files_tpl')) {
        nv_mkdir(NV_ROOTDIR . '/' . NV_ASSETS_DIR . '/' . $module_upload, 'files_tpl');
    }
    
    foreach ($arr_tab_tpl as $key => $value) {
        $name_file = 'tab-' . strtolower(change_alias($arr_tab[$key])) . '.tpl';
        $html_tpl = "<!-- BEGIN: main -->\n";
        $html_tpl .= "\t<ul>\n";
        
        foreach ($value as $key => $val) {
            $html_tpl .= "\t\t<!-- BEGIN: " . $val . " -->\n";
            $html_tpl .= "\t\t\t<li>\n";
            $html_tpl .= "\t\t\t\t<p> <strong>{CUSTOM_LANG." . $val . "}:</strong> {CUSTOM_DATA." . $val . "}</p>\n";
            $html_tpl .= "\t\t\t</li>\n";
            $html_tpl .= "\t\t<!-- END: " . $val . " -->\n";
        }
        
        $html_tpl .= "\t</ul>\n";
        $html_tpl .= "<!-- END: main -->";
        
        file_put_contents(NV_ROOTDIR . '/' . NV_ASSETS_DIR . '/' . $module_upload . '/files_tpl/' . $name_file, $html_tpl, LOCK_EX);
    }
    Header('Location:' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=template');
    exit();
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
