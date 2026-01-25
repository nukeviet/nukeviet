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

$templates = [
    'plaintext' => $nv_Lang->getModule('plaintext'),
    'request' => $nv_Lang->getModule('info_request')
];

// Xem mẫu
if ($nv_Request->isset_request('preview, id', 'get')) {
    $id = $nv_Request->get_int('id', 'get', 0);
    if (empty($id)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('template_not_selected')
        ]);
    }

    $info = template_getinfo($id);

    if (empty($info)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('template_not_selected')
        ]);
    }

    $template_type = $info['type'];
    unset($info['type']);

    $xtpl = new XTemplate('templates.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);

    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('DATA', $global_config);

    if ($template_type == 'plaintext') {
        $xtpl->assign('PREVIEW', $info);
        $xtpl->parse('preview.plaintext');
    }

    $xtpl->parse('preview');
    $contents = $xtpl->text('preview');

    nv_jsonOutput([
        'status' => 'success',
        'content' => $contents
    ]);
}

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
$type = $nv_Request->get_string('type', 'get', '');
if (!empty($type) and isset($templates[$type])) {
    $base_url .= '&amp;type=' . $type;
} else {
    $type = '';
}

if (!empty($type)) {
    $popup = $nv_Request->get_bool('popup', 'get', false);
    $idfield = $nv_Request->get_title('idfield', 'get', '');
    $parameter = $nv_Request->get_title('parameter', 'get', '');
    $clfield = $nv_Request->get_title('clfield', 'get', '');
    if ($popup) {
        $base_url .= '&amp;popup=1';
    }
    if (!empty($idfield)) {
        $base_url .= '&amp;idfield=' . $idfield;
    }
    if (!empty($parameter)) {
        $base_url .= '&amp;parameter=' . $parameter;
    }
    if (!empty($clfield)) {
        $base_url .= '&amp;clfield=' . $clfield;
    }
}

$page_url = $base_url;

// Nếu là text đơn thuần
if ($type == 'plaintext') {
    // Xóa mẫu
    if ($nv_Request->isset_request('delete,id', 'post')) {
        $id = $nv_Request->get_int('id', 'post', 0);
        if (empty($id)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('template_not_selected')
            ]);
        }

        template_delete($id);
        nv_jsonOutput([
            'status' => 'success',
            'mess' => ''
        ]);
    }

    // Sửa/thêm mới mẫu
    if ($nv_Request->isset_request('add', 'get') or $nv_Request->isset_request('edit,id', 'get')) {
        $elements = [
            'title' => '',
            'content' => ''
        ];

        $is_save = $nv_Request->isset_request('save', 'post');

        if ($nv_Request->isset_request('add', 'get')) {
            $action = 'add';
            $form_action = $base_url . '&amp;add=1';
            $page_title = $nv_Lang->getModule('template_plaintext_add');
        } else {
            $id = $nv_Request->get_int('id', 'get', 0);
            if (empty($id)) {
                if ($is_save) {
                    nv_jsonOutput([
                        'status' => 'error',
                        'mess' => $nv_Lang->getModule('template_not_selected')
                    ]);
                } else {
                    nv_redirect_location($base_url);
                }
            }

            $info = template_getinfo($id);
            unset($info['type']);

            if (empty($info)) {
                if ($is_save) {
                    nv_jsonOutput([
                        'status' => 'error',
                        'mess' => $nv_Lang->getModule('template_not_selected')
                    ]);
                } else {
                    nv_redirect_location($base_url);
                }
            }

            $elements = array_replace($elements, $info);

            $action = 'edit';
            $form_action = $base_url . '&amp;edit=1&amp;id=' . $id;
            $page_title = $nv_Lang->getModule('template_plaintext_edit');
        }

        if ($is_save) {
            $title = $nv_Request->get_title('title', 'post', '');
            $content = $nv_Request->get_title('content', 'post', '');
            $content = nv_nl2br($content, '<br/>');
            $content = trim(preg_replace('/\s+/', ' ', $content));

            if (empty($title)) {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => $nv_Lang->getModule('title_error')
                ]);
            }

            if (empty($content)) {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => $nv_Lang->getModule('chat_text_empty')
                ]);
            }

            $content = json_encode([
                'title' => $title,
                'content' => $content
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            if ($action == 'add') {
                template_save('plaintext', $content);
            } else {
                template_update($id, $content);
            }

            nv_jsonOutput([
                'status' => 'success',
                'redirect' => str_replace('&amp;', '&', $page_url)
            ]);
        }

        $xtpl = new XTemplate('plaintext.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
        $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
        $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
        $xtpl->assign('LIST_LINK', $base_url);
        $xtpl->assign('ADD_LINK', $base_url . '&amp;add=1');
        $xtpl->assign('FORM_ACTION', $form_action);

        if (!$popup) {
        } else {
            $xtpl->parse('add.popup');
        }

        if ($action == 'edit') {
            $xtpl->parse('add.add_bt');
        }

        $elements['content'] = nv_br2nl($elements['content']);
        $xtpl->assign('TEMPLATE', $elements);

        $xtpl->parse('add');
        $contents = $xtpl->text('add');

        include NV_ROOTDIR . '/includes/header.php';
        echo nv_admin_theme($contents, !$popup);
        include NV_ROOTDIR . '/includes/footer.php';
    }

    $page_title = $nv_Lang->getModule('plaintext');

    $template_list = template_getlist('plaintext');
    if (empty($template_list)) {
        nv_redirect_location($base_url . '&amp;add=1');
    }

    $xtpl = new XTemplate('plaintext.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('ADD_LINK', $base_url . '&amp;add=1');
    $xtpl->assign('EDIT_LINK', $base_url . '&amp;edit=1&amp;id=');
    $xtpl->assign('MAIN_PAGE', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
    $xtpl->assign('DEL_LINK', $base_url);
    $xtpl->assign('IDFIELD', $idfield);
    $xtpl->assign('PARAMETER', $parameter);
    $xtpl->assign('CLFIELD', $clfield);

    if (!$popup) {
        foreach ($templates as $k => $n) {
            $xtpl->assign('LOOP', [
                'key' => $k,
                'name' => $n,
                'sel' => $k == $type ? ' selected="selected"' : '',
                'url' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;type=' . $k
            ]);
            $xtpl->parse('main.change_template_type.loop');
        }
        $xtpl->parse('main.change_template_type');
    } else {
        $xtpl->parse('main.popup');
    }

    $i = 0;
    foreach ($template_list as $id => $template) {
        if ($i == 3) {
            $xtpl->parse('main.template.element3');
        }

        $xtpl->assign('TEMPLATE', $template);

        if ($popup) {
            $xtpl->parse('main.template.select');
        }

        $xtpl->parse('main.template');
        ++$i;
    }

    $xtpl->parse('main');
    $contents = $xtpl->text('main');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents, !$popup);
    include NV_ROOTDIR . '/includes/footer.php';
}
// Neu la request
elseif ($type == 'request') {
    $action = $nv_Request->get_title('action', 'post', '');
    $id = 0;
    $request_info = [];

    // Them/Cap nhat/Xoa request
    if ($action == 'update' or $action == 'delete') {
        $id = $nv_Request->get_int('id', 'post', 0);
        if (empty($id)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('request_not_selected')
            ]);
        }

        $request_info = template_getinfo($id);
        unset($request_info['type']);

        if (empty($request_info)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('request_not_selected')
            ]);
        }
    }

    if ($action == 'delete') {
        template_delete($id);

        nv_jsonOutput([
            'status' => 'success',
            'mess' => ''
        ]);
    }

    if ($action == 'add' or $action == 'update') {
        $image_url = $nv_Request->get_string('image_url', 'post', '');
        if (empty($image_url)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('image_url_invalid')
            ]);
        }

        $error = get_error_image($image_url);
        if (!empty($error)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $error
            ]);
        }

        $title = $nv_Request->get_title('title', 'post', '');
        if (empty($title)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('title_error')
            ]);
        }

        $subtitle = $nv_Request->get_title('subtitle', 'post', '');
        $subtitle = nv_nl2br($subtitle, ' ');
        $subtitle = trim(preg_replace('/\s+/', ' ', $subtitle));
        if (empty($subtitle)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('subtitle_error')
            ]);
        }

        if (!nv_is_url($image_url)) {
            if (file_exists(NV_ROOTDIR . '/' . $image_url)) {
                $lu = strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/zalo/');
                $image_url = substr($image_url, $lu);
            }
        }

        if ($action == 'update') {
            template_update($id, json_encode([
                'title' => $title,
                'subtitle' => $subtitle,
                'image_url' => $image_url
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        } else {
            template_save('request', json_encode([
                'title' => $title,
                'subtitle' => $subtitle,
                'image_url' => $image_url
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        }

        nv_jsonOutput([
            'status' => 'success',
            'mess' => ''
        ]);
    }

    $page_title = $nv_Lang->getModule('info_request');

    $xtpl = new XTemplate('request.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('FORM_ACTION', $page_url);
    $xtpl->assign('MAIN_PAGE', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
    $xtpl->assign('IDFIELD', $idfield);
    $xtpl->assign('CLFIELD', $clfield);

    if ($popup) {
        $xtpl->parse('main.popup');
    } else {
        foreach ($templates as $k => $n) {
            $xtpl->assign('LOOP', [
                'key' => $k,
                'name' => $n,
                'sel' => $k == $type ? ' selected="selected"' : '',
                'url' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;type=' . $k
            ]);
            $xtpl->parse('main.change_template_type.loop');
        }
        $xtpl->parse('main.change_template_type');
    }

    $requests = template_getlist('request');
    if (!empty($requests)) {
        foreach ($requests as $id => $request) {
            $request['id'] = $id;
            if (!nv_is_url($request['image_url'])) {
                if (file_exists(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/zalo/' . $request['image_url'])) {
                    $request['image_url'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/zalo/' . $request['image_url'];
                }
            }

            $xtpl->assign('REQUEST', $request);

            if ($popup) {
                $xtpl->parse('main.request.isPopup');
            }
            $xtpl->parse('main.request');
        }
    }

    $xtpl->parse('main');
    $contents = $xtpl->text('main');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents, !$popup);
    include NV_ROOTDIR . '/includes/footer.php';
}

$xtpl = new XTemplate('templates.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);

$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('DATA', $global_config);
$xtpl->assign('PAGE_LINK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $nv_Lang->getModule('templates');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
