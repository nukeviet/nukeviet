<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ZALO')) {
    exit('Stop!!!');
}

if (!$zalo->isValid()) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=settings');
}

$templates = [
    'plaintext' => $nv_Lang->getModule('plaintext'),
    'textlist' => $nv_Lang->getModule('textlist'),
    'btnlist' => $nv_Lang->getModule('btnlist'),
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
    } elseif ($template_type == 'text') {
        $element0 = array_shift($info);
        if (!nv_is_url($element0['image_url'])) {
            $element0['image_url'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/zalo/' . $element0['image_url'];
        }
        $element0['default_action_title'] = '';
        $element0['default_action_content'] = '';
        if (!empty($element0['default_action'])) {
            $element0['default_action_title'] = $nv_Lang->getModule('oa_' . $element0['default_action']);
            if ($element0['default_action'] == 'open_url') {
                $element0['default_action_content'] = $nv_Lang->getModule('url') . ': ' . $element0['url'];
            } elseif ($element0['default_action'] == 'query_show' or $element0['default_action'] == 'query_hide') {
                $element0['default_action_content'] = $nv_Lang->getModule('content') . ': ' . $element0['content'];
            } elseif ($element0['default_action'] == 'query_keyword') {
                $element0['default_action_content'] = $nv_Lang->getModule('command_keyword') . ': ' . $element0['keyword'];
            } elseif ($element0['default_action'] == 'open_sms') {
                $element0['default_action_content'] = $nv_Lang->getModule('phone') . ': +' . substr($element0['phone_code'], 2) . $element0['phone_number'] . '; ' . $nv_Lang->getModule('content') . ': ' . $element0['content'];
            } elseif ($element0['default_action'] == 'open_phone') {
                $element0['default_action_content'] = $nv_Lang->getModule('phone') . ': +' . substr($element0['phone_code'], 2) . $element0['phone_number'];
            }
        }
        $xtpl->assign('ELEMENT0', $element0);

        if (!empty($element0['default_action'])) {
            $xtpl->parse('preview.textlist.element0_action');
            $xtpl->parse('preview.textlist.element0_action2');
        }

        foreach ($info as $other) {
            if (!empty($other['title']) and !empty($other['image_url']) and !empty($other['default_action'])) {
                if (!nv_is_url($other['image_url'])) {
                    $other['image_url'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/zalo/' . $other['image_url'];
                }
                $other['default_action_title'] = $nv_Lang->getModule('oa_' . $other['default_action']);
                $other['default_action_content'] = '';
                if ($other['default_action'] == 'open_url' and !empty($other['url'])) {
                    $other['default_action_content'] = $nv_Lang->getModule('url') . ': ' . $other['url'];
                } elseif (($other['default_action'] == 'query_show' or $other['default_action'] == 'query_hide') and !empty($other['content'])) {
                    $other['default_action_content'] = $nv_Lang->getModule('content') . ': ' . $other['content'];
                } elseif (($other['default_action'] == 'query_keyword') and !empty($other['keyword'])) {
                    $other['default_action_content'] = $nv_Lang->getModule('command_keyword') . ': ' . $other['keyword'];
                } elseif ($other['default_action'] == 'open_sms' and !empty($other['phone_number']) and !empty($other['content'])) {
                    $other['default_action_content'] = $nv_Lang->getModule('phone') . ': +' . substr($other['phone_code'], 2) . $other['phone_number'] . '; ' . $nv_Lang->getModule('content') . ': ' . $other['content'];
                } elseif ($other['default_action'] == 'open_phone' and !empty($other['phone_number'])) {
                    $other['default_action_content'] = $nv_Lang->getModule('phone') . ': +' . substr($other['phone_code'], 2) . $other['phone_number'];
                }

                if (!empty($other['default_action_content'])) {
                    $xtpl->assign('OTHER', $other);

                    if (!empty($other['subtitle'])) {
                        $xtpl->parse('preview.textlist.other.subtitle');
                    }
                    $xtpl->parse('preview.textlist.other');
                }
            }
        }

        $xtpl->parse('preview.textlist');
    } elseif ($template_type == 'btn') {
        $xtpl->assign('TEXT', $info['text']);

        foreach ($info['buttons'] as $btn) {
            if (!empty($btn['title']) and !empty($btn['type'])) {
                $btn['action_title'] = $nv_Lang->getModule('oa_' . $btn['type']);
                $btn['action_content'] = '';
                if ($btn['type'] == 'open_url' and !empty($btn['url'])) {
                    $btn['action_content'] = $nv_Lang->getModule('url') . ': ' . $btn['url'];
                } elseif (($btn['type'] == 'query_show' or $btn['type'] == 'query_hide') and !empty($btn['content'])) {
                    $btn['action_content'] = $nv_Lang->getModule('content') . ': ' . $btn['content'];
                } elseif (($btn['type'] == 'query_keyword') and !empty($btn['keyword'])) {
                    $btn['action_content'] = $nv_Lang->getModule('command_keyword') . ': ' . $btn['keyword'];
                } elseif ($btn['type'] == 'open_sms' and !empty($btn['phone_number']) and !empty($btn['content'])) {
                    $btn['action_content'] = $nv_Lang->getModule('phone') . ': +' . substr($btn['phone_code'], 2) . $btn['phone_number'] . '; ' . $nv_Lang->getModule('content') . ': ' . $btn['content'];
                } elseif ($btn['type'] == 'open_phone' and !empty($btn['phone_number'])) {
                    $btn['action_content'] = $nv_Lang->getModule('phone') . ': +' . substr($btn['phone_code'], 2) . $btn['phone_number'];
                }

                if (!empty($btn['action_content'])) {
                    $xtpl->assign('BTN', $btn);
                    $xtpl->parse('preview.btnlist.btn');
                }
            }
        }
        $xtpl->parse('preview.btnlist');
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
// Neu la textlist
elseif ($type == 'textlist') {
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
            [
                'title' => '',
                'subtitle' => '',
                'image_url' => '',
                'default_action' => '',
                'url' => '',
                'content' => '',
                'keyword' => '',
                'phone_code' => '',
                'phone_number' => ''
            ],
            [
                'title' => '',
                'subtitle' => '',
                'image_url' => '',
                'default_action' => 'open_url',
                'url' => '',
                'content' => '',
                'keyword' => '',
                'phone_code' => '',
                'phone_number' => ''
            ],
            [
                'title' => '',
                'subtitle' => '',
                'image_url' => '',
                'default_action' => 'open_url',
                'url' => '',
                'content' => '',
                'keyword' => '',
                'phone_code' => '',
                'phone_number' => ''
            ],
            [
                'title' => '',
                'subtitle' => '',
                'image_url' => '',
                'default_action' => 'open_url',
                'url' => '',
                'content' => '',
                'keyword' => '',
                'phone_code' => '',
                'phone_number' => ''
            ],
            [
                'title' => '',
                'subtitle' => '',
                'image_url' => '',
                'default_action' => 'open_url',
                'url' => '',
                'content' => '',
                'keyword' => '',
                'phone_code' => '',
                'phone_number' => ''
            ]
        ];

        $actions = [
            'open_url' => $nv_Lang->getModule('oa_open_url'),
            'query_show' => $nv_Lang->getModule('oa_query_show'),
            'query_hide' => $nv_Lang->getModule('oa_query_hide'),
            'query_keyword' => $nv_Lang->getModule('oa_query_keyword'),
            'open_sms' => $nv_Lang->getModule('oa_open_sms'),
            'open_phone' => $nv_Lang->getModule('oa_open_phone')
        ];

        $is_save = $nv_Request->isset_request('save', 'post');

        if ($nv_Request->isset_request('add', 'get')) {
            $action = 'add';
            $form_action = $base_url . '&amp;add=1';
            $page_title = $nv_Lang->getModule('template_textlist_add');
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

            $list_info = template_getinfo($id);
            unset($list_info['type']);

            if (empty($list_info)) {
                if ($is_save) {
                    nv_jsonOutput([
                        'status' => 'error',
                        'mess' => $nv_Lang->getModule('template_not_selected')
                    ]);
                } else {
                    nv_redirect_location($base_url);
                }
            }

            $elements = array_replace($elements, $list_info);

            $action = 'edit';
            $form_action = $base_url . '&amp;edit=1&amp;id=' . $id;
            $page_title = $nv_Lang->getModule('template_textlist_edit');
        }

        require_once NV_ROOTDIR . '/' . NV_DATADIR . '/callingcodes.php';

        if ($is_save) {
            $titles = $nv_Request->get_typed_array('title', 'post', 'title', []);
            $issubtitles = $nv_Request->get_typed_array('issubtitle', 'post', 'bool', []);
            $subtitles = $nv_Request->get_typed_array('subtitle', 'post', 'title', []);
            $image_urls = $nv_Request->get_typed_array('image_url', 'post', 'string', []);
            $default_actions = $nv_Request->get_typed_array('default_action', 'post', 'title', []);
            $urls = $nv_Request->get_typed_array('url', 'post', 'string', []);
            $contents = $nv_Request->get_typed_array('content', 'post', 'title', []);
            $keywords = $nv_Request->get_typed_array('keyword', 'post', 'title', []);
            $phone_codes = $nv_Request->get_typed_array('phone_code', 'post', 'title', []);
            $phone_numbers = $nv_Request->get_typed_array('phone_number', 'post', 'title', []);

            $valid = [true, true, true, true, true];
            for ($i = 0; $i < 5; ++$i) {
                if (empty($titles[$i])) {
                    if ($i == 0) {
                        nv_jsonOutput([
                            'status' => 'error',
                            'mess' => $nv_Lang->getModule('element0_title_error')
                        ]);
                    }

                    $valid[$i] = false;
                }

                if (empty($subtitles[$i]) and $i == 0) {
                    nv_jsonOutput([
                        'status' => 'error',
                        'mess' => $nv_Lang->getModule('element0_subtitle_error')
                    ]);
                }

                if (empty($image_urls[$i])) {
                    if ($i == 0) {
                        nv_jsonOutput([
                            'status' => 'error',
                            'mess' => $nv_Lang->getModule('element0_image_url_error')
                        ]);
                    }

                    $valid[$i] = false;
                } else {
                    if (!nv_is_url($image_urls[$i])) {
                        if (file_exists(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/zalo/' . $image_urls[$i])) {
                            $lu = strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/zalo/');
                            $image_urls[$i] = substr($image_urls[$i], $lu);
                        } elseif (!file_exists(NV_ROOTDIR . '/' . $image_urls[$i])) {
                            $image_urls[$i] = '';
                            if ($i == 0) {
                                nv_jsonOutput([
                                    'status' => 'error',
                                    'mess' => $nv_Lang->getModule('element0_image_url_error')
                                ]);
                            }

                            $valid[$i] = false;
                        }
                    }
                }

                if (empty($default_actions[$i]) and $i != 0) {
                    $valid[$i] = false;
                }

                if ($default_actions[$i] == 'open_url' and (empty($urls[$i]) or !nv_is_url($urls[$i]))) {
                    if ($i == 0) {
                        nv_jsonOutput([
                            'status' => 'error',
                            'mess' => $nv_Lang->getModule('element0_url_error')
                        ]);
                    }

                    $urls[$i] = '';
                    $valid[$i] = false;
                }

                if (($default_actions[$i] == 'query_show' or $default_actions[$i] == 'query_hide' or $default_actions[$i] == 'open_sms') and empty($contents[$i])) {
                    if ($i == 0) {
                        nv_jsonOutput([
                            'status' => 'error',
                            'mess' => $nv_Lang->getModule('element0_content_error')
                        ]);
                    }

                    $valid[$i] = false;
                }

                if ($default_actions[$i] == 'query_keyword' and empty($keywords[$i])) {
                    if ($i == 0) {
                        nv_jsonOutput([
                            'status' => 'error',
                            'mess' => $nv_Lang->getModule('element0_keyword_error')
                        ]);
                    }

                    $valid[$i] = false;
                }

                if (!empty($phone_numbers[$i])) {
                    $phone_numbers[$i] = preg_replace('/[^0-9]+/', '', $phone_numbers[$i]);
                }
                if (($default_actions[$i] == 'open_sms' or $default_actions[$i] == 'open_phone') and strlen($phone_numbers[$i]) < 6) {
                    if ($i == 0) {
                        nv_jsonOutput([
                            'status' => 'error',
                            'mess' => $nv_Lang->getModule('element0_phone_error')
                        ]);
                    }

                    $phone_numbers[$i] = '';
                    $valid[$i] = false;
                }

                $elements[$i] = [
                    'title' => $titles[$i],
                    'subtitle' => $issubtitles[$i] ? $subtitles[$i] : '',
                    'image_url' => $image_urls[$i],
                    'default_action' => $default_actions[$i],
                    'url' => $default_actions[$i] == 'open_url' ? $urls[$i] : '',
                    'content' => (in_array($default_actions[$i], ['query_show', 'query_hide', 'open_sms'], true)) ? $contents[$i] : '',
                    'keyword' => ($default_actions[$i] == 'query_keyword') ? $keywords[$i] : '',
                    'phone_code' => (in_array($default_actions[$i], ['open_sms', 'open_phone'], true)) ? $phone_codes[$i] : '',
                    'phone_number' => (in_array($default_actions[$i], ['open_sms', 'open_phone'], true)) ? $phone_numbers[$i] : ''
                ];
            }

            $valid = array_filter($valid);
            if (count($valid) < 2) {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => $nv_Lang->getModule('elements_error')
                ]);
            }

            $content = json_encode($elements, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            if ($action == 'add') {
                template_save('text', $content);
            } else {
                template_update($id, $content);
            }

            nv_jsonOutput([
                'status' => 'success',
                'redirect' => str_replace('&amp;', '&', $page_url)
            ]);
        }

        $xtpl = new XTemplate('textlist.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
        $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
        $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
        $xtpl->assign('LIST_LINK', $base_url);
        $xtpl->assign('ADD_LINK', $base_url . '&amp;add=1');
        $xtpl->assign('FORM_ACTION', $form_action);
        $xtpl->assign('KEYWORDS_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=chatbot&amp;tab=command_keywords&amp;popup=1&amp;idfield=');

        if (!$popup) {
        } else {
            $xtpl->parse('add.popup');
        }

        if ($action == 'edit') {
            $xtpl->parse('add.add_bt');
        }

        foreach ($elements as $key => $element) {
            if ($key == 3) {
                $xtpl->parse('add.element.element3');
            }

            $element['num_format'] = str_pad($key + 1, 2, '0', STR_PAD_LEFT);
            $element['issubtitle'] = $key == 0 ? 1 : (!empty($element['subtitle']) ? 1 : 0);
            if (!empty($element['image_url'])) {
                if (!nv_is_url($element['image_url'])) {
                    if (file_exists(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/zalo/' . $element['image_url'])) {
                        $element['image_url'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/zalo/' . $element['image_url'];
                    }
                }
            }

            $xtpl->assign('ELEMENT', $element);

            if ($key == 0) {
                $xtpl->parse('add.element.subtitle1');
            } else {
                if (empty($element['subtitle'])) {
                    $xtpl->parse('add.element.subtitle0.subtitle_hide');
                    $xtpl->parse('add.element.subtitle_hide');
                } else {
                    $xtpl->parse('add.element.subtitle0.subtitle_show');
                }
                $xtpl->parse('add.element.subtitle0');
            }

            $el_actions = $actions;
            if ($key == 0) {
                $el_actions = ['' => $nv_Lang->getModule('no_action')] + $el_actions;
            }
            foreach ($el_actions as $action_key => $action_name) {
                $xtpl->assign('ACTION', [
                    'key' => $action_key,
                    'sel' => (!empty($element['default_action']) and $action_key == $element['default_action']) ? ' selected="selected"' : '',
                    'name' => $action_name
                ]);
                $xtpl->parse('add.element.action');
            }

            $isSel = false;
            foreach ($callingcodes as $code => $vals) {
                $sel = '';
                if (!empty($element['phone_code']) and $element['phone_code'] == $code) {
                    $sel = ' selected="selected"';
                    $isSel = true;
                }
                if (!$isSel and $client_info['country'] != 'ZZ' and $client_info['country'] == $vals[1]) {
                    $sel = ' selected="selected"';
                    $isSel = true;
                }
                if (!$isSel and $vals[1] == 'VN') {
                    $sel = ' selected="selected"';
                    $isSel = true;
                }
                $xtpl->assign('PHONE_CODE', [
                    'key' => $code,
                    'sel' => $sel,
                    'name' => $vals[1] . ' +' . $vals[0]
                ]);
                $xtpl->parse('add.element.phone_code');
            }

            if ($element['default_action'] == 'open_url') {
                $xtpl->parse('add.element.content_hide');
                $xtpl->parse('add.element.keyword_hide');
                $xtpl->parse('add.element.phone_hide');
            } elseif ($element['default_action'] == 'query_show' or $element['default_action'] == 'query_hide') {
                $xtpl->parse('add.element.url_hide');
                $xtpl->parse('add.element.keyword_hide');
                $xtpl->parse('add.element.phone_hide');
            } elseif ($element['default_action'] == 'query_keyword') {
                $xtpl->parse('add.element.url_hide');
                $xtpl->parse('add.element.content_hide');
                $xtpl->parse('add.element.phone_hide');
            } elseif ($element['default_action'] == 'open_sms') {
                $xtpl->parse('add.element.url_hide');
                $xtpl->parse('add.element.keyword_hide');
            } elseif ($element['default_action'] == 'open_phone') {
                $xtpl->parse('add.element.url_hide');
                $xtpl->parse('add.element.content_hide');
                $xtpl->parse('add.element.keyword_hide');
            } else {
                $xtpl->parse('add.element.url_hide');
                $xtpl->parse('add.element.content_hide');
                $xtpl->parse('add.element.keyword_hide');
                $xtpl->parse('add.element.phone_hide');
            }

            $xtpl->parse('add.element');
        }

        $xtpl->parse('add');
        $contents = $xtpl->text('add');

        include NV_ROOTDIR . '/includes/header.php';
        echo nv_admin_theme($contents, !$popup);
        include NV_ROOTDIR . '/includes/footer.php';
    }

    $page_title = $nv_Lang->getModule('textlist');

    $template_list = template_getlist('text');
    if (empty($template_list)) {
        nv_redirect_location($base_url . '&amp;add=1');
    }

    $xtpl = new XTemplate('textlist.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('ADD_LINK', $base_url . '&amp;add=1');
    $xtpl->assign('EDIT_LINK', $base_url . '&amp;edit=1&amp;id=');
    $xtpl->assign('MAIN_PAGE', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
    $xtpl->assign('DEL_LINK', $base_url);
    $xtpl->assign('IDFIELD', $idfield);
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

        $xtpl->assign('ID', $id);

        $element0 = array_shift($template);
        if (!nv_is_url($element0['image_url'])) {
            if (file_exists(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/zalo/' . $element0['image_url'])) {
                $element0['image_url'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/zalo/' . $element0['image_url'];
            }
        }
        $element0['default_action_title'] = '';
        $element0['default_action_content'] = '';
        if (!empty($element0['default_action'])) {
            $element0['default_action_title'] = $nv_Lang->getModule('oa_' . $element0['default_action']);
            if ($element0['default_action'] == 'open_url') {
                $element0['default_action_content'] = $nv_Lang->getModule('url') . ': ' . $element0['url'];
            } elseif ($element0['default_action'] == 'query_show' or $element0['default_action'] == 'query_hide') {
                $element0['default_action_content'] = $nv_Lang->getModule('content') . ': ' . $element0['content'];
            } elseif ($element0['default_action'] == 'query_keyword') {
                $element0['default_action_content'] = $nv_Lang->getModule('command_keyword') . ': ' . $element0['keyword'];
            } elseif ($element0['default_action'] == 'open_sms') {
                $element0['default_action_content'] = $nv_Lang->getModule('phone') . ': +' . substr($element0['phone_code'], 2) . $element0['phone_number'] . '; ' . $nv_Lang->getModule('content') . ': ' . $element0['content'];
            } elseif ($element0['default_action'] == 'open_phone') {
                $element0['default_action_content'] = $nv_Lang->getModule('phone') . ': +' . substr($element0['phone_code'], 2) . $element0['phone_number'];
            }
        }
        $xtpl->assign('ELEMENT0', $element0);

        if (!empty($element0['default_action'])) {
            $xtpl->parse('main.template.element0_action');
            $xtpl->parse('main.template.element0_action2');
        }

        foreach ($template as $other) {
            if (!empty($other['title']) and !empty($other['image_url']) and !empty($other['default_action'])) {
                if (!nv_is_url($other['image_url'])) {
                    if (file_exists(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/zalo/' . $other['image_url'])) {
                        $other['image_url'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/zalo/' . $other['image_url'];
                    }
                }
                $other['default_action_title'] = $nv_Lang->getModule('oa_' . $other['default_action']);
                $other['default_action_content'] = '';
                if ($other['default_action'] == 'open_url' and !empty($other['url'])) {
                    $other['default_action_content'] = $nv_Lang->getModule('url') . ': ' . $other['url'];
                } elseif (($other['default_action'] == 'query_show' or $other['default_action'] == 'query_hide') and !empty($other['content'])) {
                    $other['default_action_content'] = $nv_Lang->getModule('content') . ': ' . $other['content'];
                } elseif ($other['default_action'] == 'query_keyword') {
                    $other['default_action_content'] = $nv_Lang->getModule('command_keyword') . ': ' . $other['keyword'];
                } elseif ($other['default_action'] == 'open_sms' and !empty($other['phone_number']) and !empty($other['content'])) {
                    $other['default_action_content'] = $nv_Lang->getModule('phone') . ': +' . substr($other['phone_code'], 2) . $other['phone_number'] . '; ' . $nv_Lang->getModule('content') . ': ' . $other['content'];
                } elseif ($other['default_action'] == 'open_phone' and !empty($other['phone_number'])) {
                    $other['default_action_content'] = $nv_Lang->getModule('phone') . ': +' . substr($other['phone_code'], 2) . $other['phone_number'];
                }

                if (!empty($other['default_action_content'])) {
                    $xtpl->assign('OTHER', $other);

                    if (!empty($other['subtitle'])) {
                        $xtpl->parse('main.template.other.subtitle');
                    }
                    $xtpl->parse('main.template.other');
                }
            }
        }

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
// Neu la btnlist
elseif ($type == 'btnlist') {
    // Xoa mẫu
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

    // Sửa/Thêm mẫu
    if ($nv_Request->isset_request('add', 'get') or $nv_Request->isset_request('edit,id', 'get')) {
        $btns = [
            'text' => '',
            'buttons' => [
                [
                    'title' => '',
                    'type' => 'open_url',
                    'url' => '',
                    'content' => '',
                    'keyword' => '',
                    'phone_code' => '',
                    'phone_number' => ''
                ],
                [
                    'title' => '',
                    'type' => 'open_url',
                    'url' => '',
                    'content' => '',
                    'keyword' => '',
                    'phone_code' => '',
                    'phone_number' => ''
                ],
                [
                    'title' => '',
                    'type' => 'open_url',
                    'url' => '',
                    'content' => '',
                    'keyword' => '',
                    'phone_code' => '',
                    'phone_number' => ''
                ],
                [
                    'title' => '',
                    'type' => 'open_url',
                    'url' => '',
                    'content' => '',
                    'keyword' => '',
                    'phone_code' => '',
                    'phone_number' => ''
                ],
                [
                    'title' => '',
                    'type' => 'open_url',
                    'url' => '',
                    'content' => '',
                    'keyword' => '',
                    'phone_code' => '',
                    'phone_number' => ''
                ]
            ]
        ];

        $actions = [
            'open_url' => $nv_Lang->getModule('oa_open_url'),
            'query_show' => $nv_Lang->getModule('oa_query_show'),
            'query_hide' => $nv_Lang->getModule('oa_query_hide'),
            'query_keyword' => $nv_Lang->getModule('oa_query_keyword'),
            'open_sms' => $nv_Lang->getModule('oa_open_sms'),
            'open_phone' => $nv_Lang->getModule('oa_open_phone')
        ];

        $is_save = $nv_Request->isset_request('save', 'post');

        if ($nv_Request->isset_request('add', 'get')) {
            $action = 'add';
            $form_action = $base_url . '&amp;add=1';
            $page_title = $nv_Lang->getModule('template_btnlist_add');
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

            $list_info = template_getinfo($id);
            unset($list_info['type']);

            if (empty($list_info)) {
                if ($is_save) {
                    nv_jsonOutput([
                        'status' => 'error',
                        'mess' => $nv_Lang->getModule('template_not_selected')
                    ]);
                } else {
                    nv_redirect_location($base_url);
                }
            }

            $btns = array_replace($btns, $list_info);

            $action = 'edit';
            $form_action = $base_url . '&amp;edit=1&amp;id=' . $id;
            $page_title = $nv_Lang->getModule('template_btnlist_edit');
        }

        require_once NV_ROOTDIR . '/' . NV_DATADIR . '/callingcodes.php';

        if ($is_save) {
            $text = $nv_Request->get_title('text', 'post', '');
            if (empty($text)) {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => $nv_Lang->getModule('text_list_error')
                ]);
            }
            $btns['text'] = $text;

            $titles = $nv_Request->get_typed_array('title', 'post', 'title', []);
            $types = $nv_Request->get_typed_array('type', 'post', 'title', []);
            $urls = $nv_Request->get_typed_array('url', 'post', 'string', []);
            $contents = $nv_Request->get_typed_array('content', 'post', 'title', []);
            $keywords = $nv_Request->get_typed_array('keyword', 'post', 'title', []);
            $phone_codes = $nv_Request->get_typed_array('phone_code', 'post', 'title', []);
            $phone_numbers = $nv_Request->get_typed_array('phone_number', 'post', 'title', []);

            $valid = [true, true, true, true, true];
            for ($i = 0; $i < 5; ++$i) {
                if (empty($titles[$i])) {
                    $valid[$i] = false;
                }

                if (empty($types[$i])) {
                    $valid[$i] = false;
                }

                if ($types[$i] == 'open_url' and (empty($urls[$i]) or !nv_is_url($urls[$i]))) {
                    $urls[$i] = '';
                    $valid[$i] = false;
                }

                if (($types[$i] == 'query_show' or $types[$i] == 'query_hide' or $types[$i] == 'open_sms') and empty($contents[$i])) {
                    $valid[$i] = false;
                }

                if ($types[$i] == 'query_keyword' and empty($keywords[$i])) {
                    $valid[$i] = false;
                }

                if (!empty($phone_numbers[$i])) {
                    $phone_numbers[$i] = preg_replace('/[^0-9]+/', '', $phone_numbers[$i]);
                }
                if (($types[$i] == 'open_sms' or $types[$i] == 'open_phone') and strlen($phone_numbers[$i]) < 6) {
                    $phone_numbers[$i] = '';
                    $valid[$i] = false;
                }

                $btns['buttons'][$i] = [
                    'title' => $titles[$i],
                    'type' => $types[$i],
                    'url' => $types[$i] == 'open_url' ? $urls[$i] : '',
                    'content' => (in_array($types[$i], ['query_show', 'query_hide', 'open_sms'], true)) ? $contents[$i] : '',
                    'keyword' => $types[$i] == 'query_keyword' ? $keywords[$i] : '',
                    'phone_code' => (in_array($types[$i], ['open_sms', 'open_phone'], true)) ? $phone_codes[$i] : '',
                    'phone_number' => (in_array($types[$i], ['open_sms', 'open_phone'], true)) ? $phone_numbers[$i] : ''
                ];
            }

            $valid = array_filter($valid);
            if (!count($valid)) {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => $nv_Lang->getModule('btns_error')
                ]);
            }

            $content = json_encode($btns, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            if ($action == 'add') {
                template_save('btn', $content);
            } else {
                template_update($id, $content);
            }

            nv_jsonOutput([
                'status' => 'success',
                'redirect' => str_replace('&amp;', '&', $page_url)
            ]);
        }

        $xtpl = new XTemplate('btnlist.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
        $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
        $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
        $xtpl->assign('LIST_LINK', $base_url);
        $xtpl->assign('ADD_LINK', $base_url . '&amp;add=1');
        $xtpl->assign('FORM_ACTION', $form_action);
        $xtpl->assign('TEXT', $btns['text']);
        $xtpl->assign('KEYWORDS_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=chatbot&amp;tab=command_keywords&amp;popup=1&amp;idfield=');

        if (!$popup) {
        } else {
            $xtpl->parse('add.popup');
        }

        if ($action == 'edit') {
            $xtpl->parse('add.add_bt');
        }

        foreach ($btns['buttons'] as $key => $btn) {
            if ($key == 3) {
                $xtpl->parse('add.btn.btn3');
            }

            $btn['num_format'] = str_pad($key + 1, 2, '0', STR_PAD_LEFT);

            $xtpl->assign('BTN', $btn);

            foreach ($actions as $action_key => $action_name) {
                $xtpl->assign('ACTION', [
                    'key' => $action_key,
                    'sel' => (!empty($btn['type']) and $action_key == $btn['type']) ? ' selected="selected"' : '',
                    'name' => $action_name
                ]);
                $xtpl->parse('add.btn.action');
            }

            $isSel = false;
            foreach ($callingcodes as $code => $vals) {
                $sel = '';
                if (!empty($btn['phone_code']) and $btn['phone_code'] == $code) {
                    $sel = ' selected="selected"';
                    $isSel = true;
                }
                if (!$isSel and $client_info['country'] != 'ZZ' and $client_info['country'] == $vals[1]) {
                    $sel = ' selected="selected"';
                    $isSel = true;
                }
                if (!$isSel and $vals[1] == 'VN') {
                    $sel = ' selected="selected"';
                    $isSel = true;
                }
                $xtpl->assign('PHONE_CODE', [
                    'key' => $code,
                    'sel' => $sel,
                    'name' => $vals[1] . ' +' . $vals[0]
                ]);
                $xtpl->parse('add.btn.phone_code');
            }

            if ($btn['type'] == 'open_url') {
                $xtpl->parse('add.btn.content_hide');
                $xtpl->parse('add.btn.keyword_hide');
                $xtpl->parse('add.btn.phone_hide');
            } elseif ($btn['type'] == 'query_show' or $btn['type'] == 'query_hide') {
                $xtpl->parse('add.btn.url_hide');
                $xtpl->parse('add.btn.keyword_hide');
                $xtpl->parse('add.btn.phone_hide');
            } elseif ($btn['type'] == 'query_keyword') {
                $xtpl->parse('add.btn.url_hide');
                $xtpl->parse('add.btn.content_hide');
                $xtpl->parse('add.btn.phone_hide');
            } elseif ($btn['type'] == 'open_sms') {
                $xtpl->parse('add.btn.url_hide');
                $xtpl->parse('add.btn.keyword_hide');
            } elseif ($btn['type'] == 'open_phone') {
                $xtpl->parse('add.btn.url_hide');
                $xtpl->parse('add.btn.content_hide');
                $xtpl->parse('add.btn.keyword_hide');
            } else {
                $xtpl->parse('add.btn.url_hide');
                $xtpl->parse('add.btn.content_hide');
                $xtpl->parse('add.btn.keyword_hide');
                $xtpl->parse('add.btn.phone_hide');
            }

            $xtpl->parse('add.btn');
        }

        $xtpl->parse('add');
        $contents = $xtpl->text('add');

        include NV_ROOTDIR . '/includes/header.php';
        echo nv_admin_theme($contents, !$popup);
        include NV_ROOTDIR . '/includes/footer.php';
    }

    $page_title = $nv_Lang->getModule('btnlist');

    $template_list = template_getlist('btn');
    if (empty($template_list)) {
        nv_redirect_location($base_url . '&amp;add=1');
    }

    $xtpl = new XTemplate('btnlist.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('ADD_LINK', $base_url . '&amp;add=1');
    $xtpl->assign('EDIT_LINK', $base_url . '&amp;edit=1&amp;id=');
    $xtpl->assign('MAIN_PAGE', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
    $xtpl->assign('DEL_LINK', $base_url);
    $xtpl->assign('IDFIELD', $idfield);
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

        $xtpl->assign('ID', $id);
        $xtpl->assign('TEXT', $template['text']);

        foreach ($template['buttons'] as $btn) {
            if (!empty($btn['title']) and !empty($btn['type'])) {
                $btn['action_title'] = $nv_Lang->getModule('oa_' . $btn['type']);
                $btn['action_content'] = '';
                if ($btn['type'] == 'open_url' and !empty($btn['url'])) {
                    $btn['action_content'] = $nv_Lang->getModule('url') . ': ' . $btn['url'];
                } elseif (($btn['type'] == 'query_show' or $btn['type'] == 'query_hide') and !empty($btn['content'])) {
                    $btn['action_content'] = $nv_Lang->getModule('content') . ': ' . $btn['content'];
                } elseif ($btn['type'] == 'query_keyword' and !empty($btn['keyword'])) {
                    $btn['action_content'] = $nv_Lang->getModule('command_keyword') . ': ' . $btn['keyword'];
                } elseif ($btn['type'] == 'open_sms' and !empty($btn['phone_number']) and !empty($btn['content'])) {
                    $btn['action_content'] = $nv_Lang->getModule('phone') . ': +' . substr($btn['phone_code'], 2) . $btn['phone_number'] . '; ' . $nv_Lang->getModule('content') . ': ' . $btn['content'];
                } elseif ($btn['type'] == 'open_phone' and !empty($btn['phone_number'])) {
                    $btn['action_content'] = $nv_Lang->getModule('phone') . ': +' . substr($btn['phone_code'], 2) . $btn['phone_number'];
                }

                if (!empty($btn['action_content'])) {
                    $xtpl->assign('BTN', $btn);
                    $xtpl->parse('main.template.btn');
                }
            }
        }

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
