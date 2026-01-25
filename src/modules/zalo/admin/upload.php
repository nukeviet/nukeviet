<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ZALO')) {
    exit('Stop!!!');
}

if (!$myZalo->isValid()) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=settings');
}

$types = [
    'image' => $nv_Lang->getModule('type_image'),
    'gif' => $nv_Lang->getModule('type_gif'),
    'file' => $nv_Lang->getModule('type_file')
];

$max_sizes = [
    'image' => '1MB',
    'gif' => '5MB',
    'file' => '5MB'
];

// Preview hinh anh
if ($nv_Request->isset_request('preview,id', 'get')) {
    $image_id = $nv_Request->get_int('id', 'get', 0);
    if (empty($image_id)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('file_not_selected')
        ]);
    }

    $image_info = get_file_upload_info($image_id);
    if (empty($image_info)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('file_not_selected')
        ]);
    }

    $xtpl = new XTemplate('upload.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);

    if (!empty($image_info['localfile'])) {
        $image_info['localfile'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/zalo/' . $image_info['localfile'];
    }
    $image_info['download'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=upload&amp;file_download=1&amp;id=' . $image_info['id'];
    $xtpl->assign('PREVIEW', $image_info);

    if ($image_info['type'] == 'image' and !empty($image_info['localfile'])) {
        $xtpl->parse('preview.if_img');
    }
    if ($image_info['type'] == 'file' and !empty($image_info['localfile'])) {
        $xtpl->parse('preview.file_on_local');
    }
    if ($image_info['type'] == 'image') {
        $xtpl->parse('preview.description');
    }
    $xtpl->parse('preview');
    $contents = $xtpl->text('preview');

    nv_jsonOutput([
        'status' => 'success',
        'content' => $contents
    ]);
}

// Tải file
if ($nv_Request->isset_request('file_download,id', 'get')) {
    $fileid = $nv_Request->get_int('id', 'get', 0);
    $file = get_file_by_id($fileid);
    if (empty($file)) {
        exit('File_is_empty');
    }

    $file_info = pathinfo(NV_UPLOADS_REAL_DIR . '/zalo/' . $file);
    $download = new NukeViet\Files\Download(NV_UPLOADS_REAL_DIR . '/zalo/' . $file, $file_info['dirname'], $file_info['basename'], true);
    $download->download_file();
    exit();
}

// Sua chu thich cho file
if ($nv_Request->isset_request('file_desc_change', 'get') and $nv_Request->isset_request('description,id', 'post')) {
    $description = $nv_Request->get_title('description', 'post', '');
    $description = nv_nl2br($description, ' ');
    $description = trim(preg_replace('/\s+/', ' ', $description));
    $id = $nv_Request->get_int('id', 'post', 0);
    file_desc_update($id, $description);
    nv_jsonOutput([
        'status' => 'success',
        'mess' => $description
    ]);
}

// Xoa file
if ($nv_Request->isset_request('file_delete,id', 'post')) {
    $id = $nv_Request->get_int('id', 'post', 0);
    if (empty($id)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('file_not_selected')
        ]);
    }

    upload_delete($id);

    nv_jsonOutput([
        'status' => 'success',
        'mess' => ''
    ]);
}

// Gia han
if ($nv_Request->isset_request('renewal,id', 'post')) {
    $id = $nv_Request->get_int('id', 'post', 0);
    if (empty($id)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('file_not_selected')
        ]);
    }

    $fileinfo = get_file_upload_info($id);
    if (empty($fileinfo)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('file_not_selected')
        ]);
    }

    $file_fullname = NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/zalo/' . $fileinfo['localfile'];
    $mime = nv_get_mime_type($file_fullname);
    $cfile = new CURLFile($file_fullname, $mime, $fileinfo['file']);

    get_accesstoken($accesstoken, true);
    $result = $myZalo->upload($accesstoken, $fileinfo['type'], $cfile);
    if (empty($result)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $myZalo->getError()
        ]);
    }

    $zalo_id = ($fileinfo['type'] == 'image' or $fileinfo['type'] == 'gif') ? $result['data']['attachment_id'] : $result['data']['token'];
    upload_update($id, $zalo_id, NV_CURRENTTIME);

    nv_jsonOutput([
        'status' => 'success',
        'mess' => ''
    ]);
}

// Upload len Zalo
if ($nv_Request->isset_request('zalo_upload', 'get')) {
    $type = $nv_Request->get_title('type', 'post', '');
    $description = $nv_Request->get_title('description', 'post', '');
    $description = nv_nl2br($description, ' ');
    $description = trim(preg_replace('/\s+/', ' ', $description));

    $width = $height = 0;
    $localfile = '';
    if (if_store_on_server()) {
        if (empty($_FILES) or empty($_FILES['file']) or empty($_FILES['file']['tmp_name']) or empty($_FILES['file']['type']) or empty($_FILES['file']['size'])) {
            //$sys_max_size = nv_convertfromBytes(min($global_config['nv_max_size'], nv_converttoBytes(ini_get('upload_max_filesize')), nv_converttoBytes(ini_get('post_max_size'))));
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('file_empty')
            ]);
        }

        if (empty($type) or !isset($types[$type])) {
            @unlink($_FILES['file']['tmp_name']);
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('type_empty')
            ]);
        }

        $extension = nv_getextension($_FILES['file']['name']);

        $isError = false;
        if ($type == 'image' and !preg_match('/^image\/(x\-)*(png|jpe?g)$/', $_FILES['file']['type'])) {
            $isError = true;
        } elseif ($type == 'gif' and !preg_match('/^image\/(x\-)*gif$/', $_FILES['file']['type'])) {
            $isError = true;
        } elseif ($type == 'file') {
            if (empty($extension) or !in_array($extension, ['pdf', 'doc', 'docx'], true)) {
                $isError = true;
            } elseif ($extension == 'pdf' and !preg_match('/^application\/(x\-)*pdf$/', $_FILES['file']['type'])) {
                $isError = true;
            } elseif ($extension == 'doc' and !preg_match('/^application\/(x\-)*msword$/', $_FILES['file']['type'])) {
                $isError = true;
            } elseif ($extension == 'docx' and !preg_match('/^application\/vnd\./', $_FILES['file']['type'])) {
                $isError = true;
            }
        }

        if ($isError) {
            nv_deletefile($file_fullname);
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('type_' . $type . '_invalid')
            ]);
        }

        $allowsize = $type == 'image' ? 1048576 : 5242880;
        if ((int) $_FILES['file']['size'] > $allowsize) {
            nv_deletefile($file_fullname);
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('type_' . $type . '_exceedlimit')
            ]);
        }

        nv_mkdir(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/zalo/', $type);
        $dir_name = date('Ym');
        nv_mkdir(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/zalo/' . $type, $dir_name);
        $name = substr($_FILES['file']['name'], 0, (strlen($extension) + 1) * -1);
        if (empty($description)) {
            $description = $name;
        }
        $name = nv_string_to_filename($name);
        $name = substr($name, 0, 20);
        $dir = $type . '/' . $dir_name;
        $filename = filename_create($name, $extension, $dir);
        $localfile = $dir . '/' . $filename;
        $file_fullname = NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/zalo/' . $localfile;
        if (!@copy($_FILES['file']['tmp_name'], $file_fullname)) {
            @move_uploaded_file($_FILES['file']['tmp_name'], $file_fullname);
        }

        @unlink($_FILES['file']['tmp_name']);

        if ($type == 'image' or $type == 'gif') {
            $sizes = getimagesize($file_fullname);
            $width = $sizes[0];
            $height = $sizes[1];
        }
    } else {
        if (empty($type) or !isset($types[$type])) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('type_empty')
            ]);
        }

        $_filename = $nv_Request->get_title('filename', 'post', '');
        $extension = nv_getextension($_filename);
        $name = substr($_filename, 0, (strlen($extension) + 1) * -1);
        if (empty($description)) {
            $description = $name;
        }
        $name = nv_string_to_filename($name);
        $name = substr($name, 0, 20);
        $filename = $name . '.' . $extension;
    }

    $zalo_id = $nv_Request->get_title('zalo_id', 'post', '');

    upload_save($type, $filename, $localfile, $extension, $width, $height, $zalo_id, $description);

    nv_jsonOutput([
        'status' => 'success',
        'mess' => ''
    ]);
}

$page_title = $nv_Lang->getModule('upload');
$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=upload';
$popup = $nv_Request->get_bool('popup', 'get', false);
if ($popup) {
    $base_url .= '&amp;popup=1';
}

$type = $nv_Request->get_title('type', 'get', '');

if (!empty($type) and !isset($types[$type])) {
    $type = '';
}

if ($popup and empty($type)) {
    exit('file_type_is_invalid');
}

if (!empty($type)) {
    $base_url .= '&amp;type=' . $type;
}

$xtpl = new XTemplate('upload.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=upload');
$xtpl->assign('FORM_UPLOAD_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=upload&zalo_upload=1');

$file_accept = ($type == 'gif') ? '.gif' : ($type == 'file' ? '.pdf,.doc,.docx' : '.jpg,.jpeg,.png');
$xtpl->assign('FILE_ACCEPT', $file_accept);
$xtpl->assign('MAX_SIZE', !empty($type) ? $max_sizes[$type] : $max_sizes['image']);

$idfield = $nv_Request->get_title('idfield', 'get', '');
$textfield = $nv_Request->get_title('textfield', 'get', '');
$clfield = $nv_Request->get_title('clfield', 'get', '');
$xtpl->assign('IDFIELD', $idfield);
$xtpl->assign('TEXTFIELD', $textfield);
$xtpl->assign('CLFIELD', $clfield);
$xtpl->assign('STORE_ON_SERVER', if_store_on_server() ? 'true' : 'false');

if (!$popup) {
    foreach ($types as $key => $name) {
        $xtpl->assign('TYPE', [
            'key' => $key,
            'sel' => (!empty($type) and $key == $type) ? ' selected="selected"' : '',
            'name' => $name
        ]);
        $xtpl->parse('main.isType.type');
    }
    $xtpl->parse('main.isType');

    foreach ($types as $key => $name) {
        $xtpl->assign('FILE_TYPE', [
            'key' => $key,
            'accept' => ($key == 'gif') ? '.gif' : ($key == 'file' ? '.pdf,.doc,.docx' : '.jpg,.jpeg,.png'),
            'maxsize' => $max_sizes[$key],
            'url' => $myZalo::UPLOAD_URL . $key,
            'sel' => (!empty($type) and $key == $type) ? ' selected="selected"' : '',
            'name' => $name
        ]);
        $xtpl->parse('main.type_select.type');
    }
    $xtpl->parse('main.type_select');
} else {
    $xtpl->parse('main.popup');
    $xtpl->parse('main.popup2');

    $xtpl->assign('TYPE', $type);
    $xtpl->assign('ZALO_URL', $myZalo::UPLOAD_URL . $type);
    $xtpl->parse('main.type_hide');
}

$files = get_upload($type);

if (!empty($files)) {
    if ($popup) {
        $xtpl->parse('main.isFiles.select_title');
    } else {
        $xtpl->parse('main.isFiles.non_popup');
    }
    foreach ($files as $file) {
        $xtpl->assign('FILE', $file);

        if ($file['isexpired']) {
            $xtpl->parse('main.isFiles.file.if_expired');
        }

        if ($popup) {
            if ($type != 'file' and !empty($textfield)) {
                $xtpl->parse('main.isFiles.file.select.with_desc');
            }
            $xtpl->parse('main.isFiles.file.select');
            $xtpl->parse('main.isFiles.file.select2');
        } else {
            if ($file['type'] == 'image') {
                $xtpl->parse('main.isFiles.file.non_popup2.image');
            } else {
                if ($file['extension'] == 'doc' or $file['extension'] == 'docx') {
                    $xtpl->parse('main.isFiles.file.non_popup2.doc');
                } elseif ($file['extension'] == 'pdf') {
                    $xtpl->parse('main.isFiles.file.non_popup2.pdf');
                }
            }
            $xtpl->parse('main.isFiles.file.non_popup');
            $xtpl->parse('main.isFiles.file.non_popup2');
        }
        $xtpl->parse('main.isFiles.file');
    }
    $xtpl->parse('main.isFiles');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents, !$popup);
include NV_ROOTDIR . '/includes/footer.php';
