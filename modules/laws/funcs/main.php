<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Wed, 27 Jul 2011 14:55:22 GMT
 */

if (!defined('NV_IS_MOD_LAWS')) die('Stop!!!');

$page_title = $module_info['site_title'];
$key_words = $module_info['keywords'];

$page = 1;
if (isset($array_op[0]) and substr($array_op[0], 0, 5) == 'page-') {
    $page = intval(substr($array_op[0], 5));
}

$contents = $cache_file = '';
$per_page = $nv_laws_setting['nummain'];
$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name;

if (!defined('NV_IS_MODADMIN') and $page < 5) {
    $cache_file = NV_LANG_DATA . '_' . $module_info['template'] . '_' . $op . '_' . $page . '_' . NV_CACHE_PREFIX . '.cache';
    if (($cache = $nv_Cache->getItem($module_name, $cache_file)) != false) {
        $contents = $cache;
    }
}

if (empty($contents)) {
    if ($nv_laws_setting['typeview'] != 2) {
        // Hien thi danh sach van ban
        $order = ($nv_laws_setting['typeview'] == 1 or $nv_laws_setting['typeview'] == 4) ? "ASC" : "DESC";
        $order_param = ($nv_laws_setting['typeview'] == 0 or $nv_laws_setting['typeview'] == 1) ? "publtime" : "addtime";

        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM " . NV_PREFIXLANG . "_" . $module_data . "_row WHERE status=1 ORDER BY " . $order_param . " " . $order . " LIMIT " . $per_page . " OFFSET " . ($page - 1) * $per_page;

        $result = $db->query($sql);
        $query = $db->query("SELECT FOUND_ROWS()");
        $all_page = $query->fetchColumn();

        $generate_page = nv_alias_page($page_title, $base_url, $all_page, $per_page, $page);

        $array_data = array();
        $stt = nv_get_start_id($page, $per_page);
        while ($row = $result->fetch()) {
            $row['areatitle'] = array();
            $_result = $db->query('SELECT area_id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_row_area WHERE row_id=' . $row['id']);
            while (list ($area_id) = $_result->fetch(3)) {
                $row['areatitle'][] = $nv_laws_listarea[$area_id]['title'];
            }
            $row['areatitle'] = !empty($row['areatitle']) ? implode(', ', $row['areatitle']) : '';
            $row['subjecttitle'] = $nv_laws_listsubject[$row['sid']]['title'];
            $row['cattitle'] = $nv_laws_listcat[$row['cid']]['title'];
            $row['url'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $module_info['alias']['detail'] . "/" . $row['alias'];
            $row['comm_url'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $module_info['alias']['detail'] . "/" . $row['alias'];
			if(($row['start_comm_time']>0 && $row['start_comm_time']> NV_CURRENTTIME) || ($row['end_comm_time']>0 && $row['end_comm_time']< NV_CURRENTTIME)){
	        	$row['allow_comm'] = 0;
	        }else{
	        	$row['allow_comm'] = 1;
	        }
			//Đếm số comment
			if (isset($site_mods['comment']) and isset($module_config[$module_name]['activecomm'])) {
		        $area = $module_info['funcs']['detail']['func_id'];
		        $_where = 'a.module=' .$db_slave->quote($module_name);
			    if ($area) {
			        $_where .= ' AND a.area= ' . $area;
			    }
			    $_where .= ' AND a.id= ' . $row['id'] . ' AND a.status=1 AND a.pid=0';

			    $db_slave->sqlreset()->select('COUNT(*)')->from(NV_PREFIXLANG . '_comment a')->join('LEFT JOIN ' . NV_USERS_GLOBALTABLE . ' b ON a.userid =b.userid')->where($_where);

			    $num_comm = $db_slave->query($db_slave->sql())->fetchColumn();
		    } else {
		        $num_comm = '';
		    }
			$row['start_comm_time'] = ($row['start_comm_time']>0) ? sprintf($lang_module['start_comm_time'], nv_date('d/m/Y', $row['start_comm_time'])) : '';
            $row['end_comm_time'] = ($row['end_comm_time']>0) ? sprintf($lang_module['end_comm_time'], nv_date('d/m/Y', $row['end_comm_time'])) : '';
            $row['number_comm'] = sprintf($lang_module['number_comm'],$num_comm);
            $row['comm_time'] = $row['start_comm_time'] . '-' . $row['end_comm_time'];
            $row['stt'] = $stt++;
			$row['edit_link'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=main&amp;edit=1&amp;id=" . $row['id'];
			$row['delete_link'] = 'nv_delete_law(' . $row['id'] . ', \'' . md5($row['id'] . session_id()) . '\')';

            if ($nv_laws_setting['down_in_home']) {
                // File download
                if (!empty($row['files'])) {
                    $row['files'] = explode(",", $row['files']);
                    $files = $row['files'];
                    $row['files'] = array();

                    foreach ($files as $id => $file) {
                        $file_title = basename($file);
                        $row['files'][] = array(
                            "title" => $file_title,
                            "titledown" => $lang_module['download'] . ' ' . (count($files) > 1 ? $id + 1 : ''),
                            "url" => (!preg_match("/^http*/", $file)) ? NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $module_info['alias']['detail'] . "/" . $row['alias'] . "&amp;download=1&amp;id=" . $id : $file
                        );
                    }
                }
            }

            $array_data[] = $row;
        }
        $contents = nv_theme_laws_main($array_data, $generate_page);
    } else {
        // Hien thi theo phan muc
        if (!empty($nv_laws_listsubject)) {
            foreach ($nv_laws_listsubject as $subjectid => $subject) {
                $result = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_row WHERE sid=' . $subjectid . ' ORDER BY addtime DESC LIMIT ' . $subject['numlink']);
                while ($row = $result->fetch()) {
                    $row['url'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $module_info['alias']['detail'] . "/" . $row['alias'];
                    $row['comm_url'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $module_info['alias']['detail'] . "/" . $row['alias'];
					if(($row['start_comm_time']>0 && $row['start_comm_time']> NV_CURRENTTIME) || ($row['end_comm_time']>0 && $row['end_comm_time']< NV_CURRENTTIME)){
			        	$row['allow_comm'] = 0;
			        }else{
			        	$row['allow_comm'] = 1;
			        }
					//Đếm số comment
					if (isset($site_mods['comment']) and isset($module_config[$module_name]['activecomm'])) {
				        $area = $module_info['funcs']['detail']['func_id'];//print_r($area);die('ok');
				        $_where = 'a.module=' .$db_slave->quote($module_name);
					    if ($area) {
					        $_where .= ' AND a.area= ' . $area;
					    }
					    $_where .= ' AND a.id= ' . $row['id'] . ' AND a.status=1 AND a.pid=0';

					    $db_slave->sqlreset()->select('COUNT(*)')->from(NV_PREFIXLANG . '_comment a')->join('LEFT JOIN ' . NV_USERS_GLOBALTABLE . ' b ON a.userid =b.userid')->where($_where);

					    $num_comm = $db_slave->query($db_slave->sql())->fetchColumn();
				    } else {
				        $num_comm = '';
				    }
					$row['start_comm_time'] = ($row['start_comm_time']>0) ? sprintf($lang_module['start_comm_time'], nv_date('d/m/Y', $row['start_comm_time'])) : '';
		            $row['end_comm_time'] = ($row['end_comm_time']>0) ? sprintf($lang_module['end_comm_time'], nv_date('d/m/Y', $row['end_comm_time'])) : '';
		            $row['comm_time'] = $row['start_comm_time'] . '-' . $row['end_comm_time'];
					$row['number_comm'] = sprintf($lang_module['number_comm'],$num_comm);
					$row['edit_link'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=main&amp;edit=1&amp;id=" . $row['id'];
					$row['delete_link'] = 'nv_delete_law(' . $row['id'] . ', \'' . md5($row['id'] . session_id()) . '\')';
					if ($nv_laws_setting['down_in_home']) {
                        // File download
                        if (!empty($row['files'])) {
                            $row['files'] = explode(",", $row['files']);
                            $files = $row['files'];
                            $row['files'] = array();

                            foreach ($files as $id => $file) {
                                $file_title = basename($file);
                                $row['files'][] = array(
                                    "title" => $file_title,
                                    "titledown" => $lang_module['download'] . ' ' . (count($files) > 1 ? $id + 1 : ''),
                                    "url" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $module_info['alias']['detail'] . "/" . $row['alias'] . "&amp;download=1&amp;id=" . $id
                                );
                            }
                        }
                    }
                    $nv_laws_listsubject[$subjectid]['rows'][] = $row;
                }
            }
        }
        $contents = nv_theme_laws_maincat('subject', $nv_laws_listsubject);
    }

    if (!defined('NV_IS_MODADMIN') and $contents != '' and $cache_file != '') {
        $nv_Cache->setItem($module_name, $cache_file, $contents);
    }
}

if ($nv_Request->isset_request('id', 'get, post') and $nv_Request->isset_request('checkss', 'get, post')) {
    $contents = 'NO_' . $id;

    $id = $nv_Request->get_int('id', 'get, post', 0);
    $checkss = $nv_Request->get_string('checkss', 'get, post', '');
    if ($id > 0 and md5($id . session_id()) == $checkss) {
    	$data = $db->query('SELECT sid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_row WHERE id=' . $id)->fetch();
        if(!empty($data)){
	        $query = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_row WHERE id = " . $id;
	            $db->query($query);

	            $query = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_set_replace WHERE oid = " . $id;
	            $db->query($query);

	            // Cap nhat lai so luong van ban o chu de
	            $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_subject SET numcount=(SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_row WHERE sid=' . $data['sid'] . ') WHERE id=' . $data['sid']);


	        $nv_Cache->delMod($module_name);
	        $contents = 'OK_' . $id;
		}
    }

    die($contents);
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';