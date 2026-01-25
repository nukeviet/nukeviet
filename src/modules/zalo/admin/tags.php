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

$page_title = $nv_Lang->getModule('tags');
$tags = get_tags(); // Danh sach cac tag

// Sua ten nhan
if ($nv_Request->isset_request('edit', 'get') and $nv_Request->isset_request('alias,new_name', 'post')) {
    $tag_alias = $nv_Request->get_title('alias', 'post', '');
    if (empty($tag_alias)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('no_tag_selected')
        ]);
    }

    if (empty($tags[$tag_alias])) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('no_tag_selected')
        ]);
    }

    $new_name = $nv_Request->get_title('new_name', 'post', '');
    if (empty($new_name)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('new_tag_empty')
        ]);
    }

    update_tag($tag_alias, $new_name);

    nv_jsonOutput([
        'status' => 'success',
        'mess' => $new_name
    ]);
}

// Xóa nhãn
if ($nv_Request->isset_request('delete_tag,tag_alias', 'post')) {
    $tag_alias = $nv_Request->get_title('tag_alias', 'post', '');
    if (empty($tag_alias)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('no_tag_selected')
        ]);
    }

    if (empty($tags[$tag_alias])) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('no_tag_selected')
        ]);
    }

    $user_count = get_user_count_by_tag($tag_alias);
    if (!empty($user_count)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('tag_user_exists')
        ]);
    }

    get_accesstoken($accesstoken, true);

    $result = $myZalo->rmtag($accesstoken, $tag_alias);

    delete_tag($tag_alias);

    nv_jsonOutput([
        'status' => 'success',
        'mess' => ''
    ]);
}

// Thêm nhãn
if ($nv_Request->isset_request('add_tag,new_tag', 'post')) {
    $new_tag = $nv_Request->get_title('new_tag', 'post', '');
    if (empty($new_tag)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('new_tag_empty')
        ]);
    }

    $alias = strtolower(change_alias($new_tag));
    if (strlen($alias) < 3) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('new_tag_empty')
        ]);
    }

    if (!empty($tags[$alias])) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('tag_exists')
        ]);
    }

    add_tag([
        'alias' => $alias,
        'name' => $new_tag
    ]);

    nv_jsonOutput([
        'status' => 'success',
        'mess' => ''
    ]);
}

$xtpl = new XTemplate('tags.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=tags');
$xtpl->assign('TAG_LINK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=followers');
$xtpl->assign('EDIT_LINK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=tags&amp;edit=1');

if (!empty($tags)) {
    foreach ($tags as $alias => $name) {
        $xtpl->assign('TAG', [
            'alias' => $alias,
            'name' => $name
        ]);
        $xtpl->parse('main.ifTags.tag');
    }
    $xtpl->parse('main.ifTags');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
