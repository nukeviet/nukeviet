<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/14/2010 0:50
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$id = $nv_Request->get_int('id', 'get', 0);

$sql = 'SELECT * FROM ' . NV_BANNERS_GLOBALTABLE. '_rows WHERE id=' . $id;
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

$contents = array();
$contents['upload_blocked'] = '';
$contents['file_allowed_ext'] = array();

if (preg_match('/images/', NV_ALLOW_FILES_TYPE)) {
    $contents['file_allowed_ext'][] = 'images';
}

if (preg_match('/flash/', NV_ALLOW_FILES_TYPE)) {
    $contents['file_allowed_ext'][] = 'flash';
}

if (empty($contents['file_allowed_ext'])) {
    $contents['upload_blocked'] = $lang_module['upload_blocked'];

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme(nv_edit_banner_theme($contents));
    include NV_ROOTDIR . '/includes/footer.php';
}

$sql = 'SELECT id,login,full_name FROM ' . NV_BANNERS_GLOBALTABLE. '_clients ORDER BY login ASC';
$result = $db->query($sql);

$clients = array();
while ($cl_row = $result->fetch()) {
    $clients[$cl_row['id']] = $cl_row['full_name'] . ' (' . $cl_row['login'] . ')';
}

$sql = 'SELECT id, title, blang FROM ' . NV_BANNERS_GLOBALTABLE. '_plans ORDER BY blang, title ASC';
$result = $db->query($sql);

$plans = array();
while ($pl_row = $result->fetch()) {
    $plans[$pl_row['id']] = $pl_row['title'] . ' (' . (! empty($pl_row['blang']) ? $language_array[$pl_row['blang']]['name'] : $lang_module['blang_all']) . ')';
}

if (empty($plans)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=add_plan');
}

$error = '';

if ($nv_Request->get_int('save', 'post') == '1') {
    $title = nv_htmlspecialchars(strip_tags($nv_Request->get_string('title', 'post', '')));
    $pid = $nv_Request->get_int('pid', 'post', 0);
    $clid = $nv_Request->get_int('clid', 'post', 0);
    $file_alt = nv_htmlspecialchars(strip_tags($nv_Request->get_string('file_alt', 'post', '')));
    $click_url = strip_tags($nv_Request->get_string('click_url', 'post', ''));
    $publ_date = strip_tags($nv_Request->get_string('publ_date', 'post', ''));
    $exp_date = strip_tags($nv_Request->get_string('exp_date', 'post', ''));
    $target = $nv_Request->get_string('target', 'post', '');
    if (! isset($targets[$target])) {
        $target = '_blank';
    }
    $bannerhtml = $nv_Request->get_editor('bannerhtml', '', NV_ALLOWED_HTML_TAGS);

    if (! empty($publ_date) and ! preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $publ_date)) {
        $publ_date = '';
    }
    if (! empty($exp_date) and ! preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $exp_date)) {
        $exp_date = '';
    }

    if (! empty($clid) and ! isset($clients[$clid])) {
        $clid = 0;
    }
    if ($click_url == 'http://') {
        $click_url = '';
    }

	$sql = 'SELECT require_image FROM ' . NV_BANNERS_GLOBALTABLE. '_plans where id = ' . $pid;
	$result = $db->query($sql);
	$array_require_image = $result->fetchAll();
    if (empty($title)) {
        $error = $lang_module['title_empty'];
    } elseif (empty($pid) or ! isset($plans[$pid])) {
        $error = $lang_module['plan_not_selected'];
    } elseif (! empty($click_url) and ! nv_is_url($click_url)) {
        $error = $lang_module['click_url_invalid'];
    } elseif (! is_uploaded_file($_FILES['banner']['tmp_name']) && $array_require_image[0]['require_image'] == 1 && empty($file_name) ) {
    	$error = $lang_module['file_upload_empty'];
    } else {
        if (isset($_FILES['banner']) and is_uploaded_file($_FILES['banner']['tmp_name'])) {
            $upload = new NukeViet\Files\Upload($contents['file_allowed_ext'], $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT);
            $upload_info = $upload->save_file($_FILES['banner'], NV_UPLOADS_REAL_DIR . '/' . NV_BANNER_DIR, false);
            @unlink($_FILES['banner']['tmp_name']);

            if (! empty($upload_info['error'])) {
                $error = $upload_info['error'];
            } else {
                @chmod($upload_info['name'], 0644);

                if (! empty($file_name) and is_file(NV_ROOTDIR . '/' . $file_name)) {
                    @nv_deletefile(NV_UPLOADS_REAL_DIR . '/' . NV_BANNER_DIR . '/' . $file_name);
                }

                $file_name = $upload_info['basename'];
                $file_ext = $upload_info['ext'];
                $file_mime = $upload_info['mime'];
                $width = $upload_info['img_info'][0];
                $height = $upload_info['img_info'][1];
            }
        }
        if ($file_ext == 'swf') {
            if (isset($_FILES['imageforswf']) and is_uploaded_file($_FILES['imageforswf']['tmp_name'])) {
                $upload = new NukeViet\Files\Upload($contents['file_allowed_ext'], $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT);
                $upload_info = $upload->save_file($_FILES['imageforswf'], NV_UPLOADS_REAL_DIR . '/' . NV_BANNER_DIR, false);
                @unlink($_FILES['imageforswf']['tmp_name']);

                if (! empty($upload_info['error'])) {
                    $error = $upload_info['error'];
                } else {
                    @chmod($upload_info['name'], 0644);
                    if (! empty($imageforswf) and is_file(NV_ROOTDIR . '/' . $imageforswf)) {
                        @nv_deletefile(NV_UPLOADS_REAL_DIR . '/' . NV_BANNER_DIR . '/' . $imageforswf);
                    }
                    $imageforswf = $upload_info['basename'];
                }
            }
        } else {
            if (! empty($imageforswf) and is_file(NV_ROOTDIR . '/' . $imageforswf)) {
                @nv_deletefile(NV_UPLOADS_REAL_DIR . '/' . NV_BANNER_DIR . '/' . $imageforswf);
            }
            $imageforswf = '';
        }
        if (empty($error)) {
            if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $publ_date, $m)) {
                $publtime = mktime(0, 0, 0, $m[2], $m[1], $m[3]);
                if ($publtime < $row['add_time']) {
                    $publtime = $row['add_time'];
                }
            } else {
                $publtime = $publtime = $row['add_time'];
            }

            if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $exp_date, $m)) {
                $exptime = mktime(23, 59, 59, $m[2], $m[1], $m[3]);
                if ($exptime <= $publtime) {
                    $exptime = $publtime;
                }
            } else {
                $exptime = 0;
            }

            $pid_old = $db->query('SELECT pid FROM ' . NV_BANNERS_GLOBALTABLE. '_rows WHERE id=' . intval($id))->fetchColumn();

            $stmt = $db->prepare('UPDATE ' . NV_BANNERS_GLOBALTABLE. '_rows SET title= :title, pid=' . $pid . ', clid=' . $clid . ',
				 file_name= :file_name, file_ext= :file_ext, file_mime= :file_mime,
				 width=' . $width . ', height=' . $height . ', file_alt= :file_alt, imageforswf= :imageforswf,
				 click_url= :click_url, target= :target, bannerhtml=:bannerhtml,
				 publ_time=' . $publtime . ', exp_time=' . $exptime . ' WHERE id=' . $id);
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

            nv_insert_logs(NV_LANG_DATA, $module_name, 'log_edit_banner', 'bannerid ' . $id, $admin_info['userid']);
            nv_CreateXML_bannerPlan();

            nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=info_banner&id=' . $id);
        }
    }
} else {
    $title = $row['title'];
    $pid = $row['pid'];
    $clid = $row['clid'];
    $file_alt = $row['file_alt'];
    $click_url = $row['click_url'];
    $target = $row['target'];
    $bannerhtml = $row['bannerhtml'];
    $publ_date = ! empty($row['publ_time']) ? date('d/m/Y', $row['publ_time']) : '';
    $exp_date = ! empty($row['exp_time']) ? date('d/m/Y', $row['exp_time']) : '';
}

$contents['info'] = (! empty($error)) ? $error : $lang_module['edit_banner_info'];
$contents['is_error'] = (! empty($error)) ? 1 : 0;
$contents['file_allowed_ext'] = implode(', ', $contents['file_allowed_ext']);
$contents['submit'] = $lang_module['edit_banner'];
$contents['action'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit_banner&amp;id=' . $id;
$contents['title'] = array( $lang_module['title'], 'title', $title, 255 );
$contents['plan'] = array( $lang_module['in_plan'], 'pid', $plans, $pid );
$contents['client'] = array( $lang_module['of_client'], 'clid', $clients, $clid );

$imageforswf = (! empty($imageforswf)) ? NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . NV_BANNER_DIR . '/' . $imageforswf : '';

if($file_ext != 'no_image'){
	$contents['file_name'] = array( $lang_module['file_name'], NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . NV_BANNER_DIR . '/' . $file_name, "data-width=" . $width . " id=" . ($file_ext == 'swf' ? 'open_modal_flash' : 'open_modal_image') . "", NV_BASE_SITEURL . NV_ASSETS_DIR . "/images/ico_" . $file_ext . ".gif", $lang_global['show_picture'], $imageforswf, NV_BASE_SITEURL . NV_ASSETS_DIR . "/images/ico_" . substr($imageforswf, -3) . ".gif" );
}else{
	$contents['file_name'] = array( $lang_module['file_name'],'');
}

$contents['upload'] = array( sprintf($lang_module['re_upload'], $contents['file_allowed_ext']), 'banner', $lang_module['imageforswf'], 'imageforswf' );
$contents['file_alt'] = array( $lang_module['file_alt'], 'file_alt', $file_alt, 255 );
$contents['click_url'] = array( $lang_module['click_url'], 'click_url', $click_url, 255 );

$contents['target'] = array( $lang_module['target'], 'target', $targets, $target );

$contents['publ_date'] = array( $lang_module['publ_date'], 'publ_date', $publ_date, 10 );
$contents['exp_date'] = array( $lang_module['exp_date'], 'exp_date', $exp_date, 10 );

$contents['bannerhtml'] = htmlspecialchars(nv_editor_br2nl($bannerhtml));

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $contents['bannerhtml'] = nv_aleditor('bannerhtml', '100%', '300px', $contents['bannerhtml']);
} else {
    $contents['bannerhtml'] = '<textarea style="width:100%;height:300px" name="bannerhtml">' . $contents['bannerhtml'] . '</textarea>';
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme(nv_edit_banner_theme($contents));
include NV_ROOTDIR . '/includes/footer.php';
