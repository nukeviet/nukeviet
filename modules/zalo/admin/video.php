<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ZALO')) {
    exit('Stop!!!');
}

if (!$zalo->isValid()) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=settings');
}

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=video';
$popup = $nv_Request->get_bool('popup', 'get', false);
$idfield = $nv_Request->get_title('idfield', 'get', '');
$viewfield = $nv_Request->get_title('viewfield', 'get', '');
$thumbfield = $nv_Request->get_title('thumbfield', 'get', '');
if ($popup) {
    $base_url .= '&amp;popup=1';
}
if (!empty($idfield)) {
    $base_url .= '&amp;idfield=' . $idfield;
}
if (!empty($viewfield)) {
    $base_url .= '&amp;viewfield=' . $viewfield;
}
if (!empty($thumbfield)) {
    $base_url .= '&amp;thumbfield=' . $thumbfield;
}

// Sua thong tin video
if ($nv_Request->isset_request('edit,id,view,thumb,description', 'post')) {
    $id = $nv_Request->get_int('id', 'post', 0);
    $view = $nv_Request->get_title('view', 'post', 'horizontal');
    $thumb = $nv_Request->get_title('thumb', 'post', '');
    $description = $nv_Request->get_title('description', 'post', '');
    $description = nv_nl2br($description, ' ');
    $description = trim(preg_replace('/\s+/', ' ', $description));

    if (empty($id)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $lang_module['file_not_selected']
        ]);
    }

    if (empty($description)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $lang_module['description_empty']
        ]);
    }

    if (!empty($thumb)) {
        if (!nv_is_url($thumb)) {
            if (file_exists(NV_UPLOADS_REAL_DIR . '/zalo/' . $thumb)) {
                $lu = strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/zalo/');
                $thumb = substr($thumb, $lu);
            }
        }
    }

    video_edit_save($id, $view, $thumb, $description);

    nv_jsonOutput([
        'status' => 'success',
        'mess' => $description
    ]);
}

// Xoa video
if ($nv_Request->isset_request('file_delete,id', 'post')) {
    $id = $nv_Request->get_int('id', 'post', 0);
    if (empty($id)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $lang_module['file_not_selected']
        ]);
    }

    video_delete($id);

    nv_jsonOutput([
        'status' => 'success',
        'mess' => ''
    ]);
}

// Kiem tra trang thai video
if ($nv_Request->isset_request('check,id', 'post')) {
    $id = $nv_Request->get_int('id', 'post', 0);
    if (empty($id)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $lang_module['file_not_selected']
        ]);
    }

    $token = video_get_token($id);
    if (empty($token)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $lang_module['file_not_selected']
        ]);
    }

    get_accesstoken($accesstoken, true);
    $result = $zalo->video_verify($accesstoken, $token);
    if (empty($result)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => zaloGetError()
        ]);
    }

    $result['data']['token'] = ('1' == (string) $result['data']['status']) ? '' : $token;

    video_update($id, $result['data']);

    nv_jsonOutput([
        'status' => 'success',
        'mess' => ''
    ]);
}

// Them video
if ($nv_Request->isset_request('add', 'post')) {
    $token = $nv_Request->get_string('token', 'post', '');
    $video_name = $nv_Request->get_title('filename', 'post', '');
    $description = $nv_Request->get_title('description', 'post', '');
    $view = $nv_Request->get_title('view', 'post', '');
    $thumb = $nv_Request->get_title('thumb', 'post', '');

    $description = nv_nl2br($description, ' ');
    $description = trim(preg_replace('/\s+/', ' ', $description));
    $video_name = preg_replace('/[^a-zA-Z0-9\_\.]/', '', $video_name);
    if (!empty($thumb)) {
        if (!nv_is_url($thumb)) {
            if (file_exists(NV_UPLOADS_REAL_DIR . '/zalo/' . $thumb)) {
                $lu = strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/zalo/');
                $thumb = substr($thumb, $lu);
            }
        }
    }

    if (empty($video_name)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $lang_module['file_empty']
        ]);
    }

    if (empty($description)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $lang_module['description_empty']
        ]);
    }

    if (empty($token)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $lang_module['token_empty']
        ]);
    }

    $video = [
        'video_id' => '',
        'token' => $token,
        'video_name' => $video_name,
        'video_size' => 0,
        'description' => $description,
        'view' => $view,
        'thumb' => $thumb,
        'status' => 0,
        'status_message' => '',
        'convert_percent' => 0,
        'convert_error_code' => 0
    ];

    get_accesstoken($accesstoken, true);
    $result = $zalo->video_verify($accesstoken, $token);
    if (empty($result)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => zaloGetError()
        ]);
    }

    if ('1' != (string) $result['data']['status']) {
        sleep(5);
        $result = $zalo->video_verify($accesstoken, $token);
        if (empty($result)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => zaloGetError()
            ]);
        }
    }

    $video = array_replace($video, $result['data']);
    if ('1' == (string) $result['data']['status']) {
        $video['token'] = '';
    }
    video_add($video);

    nv_jsonOutput([
        'status' => 'success',
        'mess' => ''
    ]);
}

$page_title = $lang_module['video'];

$xtpl = new XTemplate('video.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('FORM_ACTION', $base_url);
$xtpl->assign('ZALO_URL', $zalo::VIDEOUPLOAD_URL);
$xtpl->assign('DEL_LINK', $base_url);
$xtpl->assign('IDFIELD', $idfield);
$xtpl->assign('VIEWFIELD', $viewfield);
$xtpl->assign('THUMBFIELD', $thumbfield);

if (!$popup) {
} else {
    $xtpl->parse('main.popup');
}

$video_list = video_get_list();
if (!empty($video_list)) {
    $status_check = [];
    foreach ($video_list as $file) {
        $file['size_format'] = !empty($file['video_size']) ? nv_convertfromBytes($file['video_size']) : '';
        $file['view_format'] = $lang_module['video_view_' . $file['view']];
        $file['addtime_format'] = nv_date('d/m/Y H:i', $file['addtime']);
        if (!empty($file['thumb'])) {
            if (!nv_is_url($file['thumb'])) {
                if (file_exists(NV_UPLOADS_REAL_DIR . '/zalo/' . $file['thumb'])) {
                    $file['thumb'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/zalo/' . $file['thumb'];
                }
            }
        }
        $file['disabled'] = $file['status'] == '1' ? '' : ' disabled="disabled"';
        !empty($lang_module['video_status_' . $file['status']]) && $file['status_message'] = $lang_module['video_status_' . $file['status']];
        $xtpl->assign('FILE', $file);

        $remainder = 3600 - NV_CURRENTTIME + $file['addtime'];

        if (!empty($file['token']) and $remainder > 0) {
            $xtpl->parse('main.isFiles.file.status_check');

            empty($status_check) && $status_check = [
                'note' => sprintf($lang_module['status_check_note'], $file['video_name'], nv_convertfromSec($remainder)),
                'id' => $file['id']
            ];
        }

        if ($popup) {
            $xtpl->parse('main.isFiles.file.select');
        }

        if (!empty($file['thumb'])) {
            $xtpl->parse('main.isFiles.file.thumb');
        }

        $xtpl->parse('main.isFiles.file');
    }

    if (!empty($status_check)) {
        $xtpl->assign('STATUS_CHECK', $status_check);
        $xtpl->parse('main.isFiles.status_check_Modal');
    }

    $xtpl->parse('main.isFiles');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents, !$popup);
include NV_ROOTDIR . '/includes/footer.php';
