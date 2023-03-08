<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_SEOTOOLS')) {
    exit('Stop!!!');
}

// Upload biểu trưng
if ($nv_Request->isset_request('logoupload', 'get')) {
    $array = [];
    $array['success'] = 0;
    $array['error'] = '';

    $logo_config = [
        'logo_width' => 112,
        'logo_height' => 112
    ];

    if (isset($_FILES['image_file']) and is_uploaded_file($_FILES['image_file']['tmp_name'])) {
        // Get post data
        $array['crop_x'] = $nv_Request->get_int('crop_x', 'post', 0);
        $array['crop_y'] = $nv_Request->get_int('crop_y', 'post', 0);
        $array['logo_width'] = $nv_Request->get_int('crop_width', 'post', 0);
        $array['logo_height'] = $nv_Request->get_int('crop_height', 'post', 0);

        if ($array['logo_width'] < $logo_config['logo_width'] or $array['logo_height'] < $logo_config['logo_height']) {
            $array['error'] = $lang_module['logo_error_data'];
        } else {
            $upload = new NukeViet\Files\Upload(['images'], $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE);
            $upload->setLanguage($lang_global);

            // Storage in temp dir
            $upload_info = $upload->save_file($_FILES['image_file'], NV_ROOTDIR . '/' . NV_TEMP_DIR, false);

            // Delete upload tmp
            @unlink($_FILES['image_file']['tmp_name']);

            if (empty($upload_info['error'])) {
                $basename = 'organization_logo.v' . NV_CURRENTTIME . '.' . $upload_info['ext'];
                $image = new NukeViet\Files\Image($upload_info['name']);
                $image->cropFromLeft($array['crop_x'], $array['crop_y'], $array['logo_width'], $array['logo_height']);
                $image->save(NV_ROOTDIR . '/' . NV_ASSETS_DIR, $basename);
                $image->close();

                if (file_exists($image->create_Image_info['src'])) {
                    if (!empty($global_config['organization_logo']) and file_exists(NV_ROOTDIR . '/' . $global_config['organization_logo'])) {
                        nv_deletefile(NV_ROOTDIR . '/' . $global_config['organization_logo']);
                    }

                    $photo = NV_ASSETS_DIR . '/' . $basename;
                    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'site' AND config_name = 'organization_logo'");
                    $sth->bindParam(':config_value', $photo, PDO::PARAM_STR);
                    $sth->execute();
                    $nv_Cache->delAll(false);

                    $array['filename'] = NV_BASE_SITEURL . $photo;
                    $array['success'] = 1;
                } else {
                    $array['error'] = $lang_module['avatar_error_save'];
                }
                @nv_deletefile($upload_info['name']);
            } else {
                $array['error'] = $upload_info['error'];
            }
        }
    }

    $lang_module['bigfile'] = sprintf($lang_module['bigfile'], nv_convertfromBytes(NV_UPLOAD_MAX_FILESIZE));
    $lang_module['bigsize'] = sprintf($lang_module['bigsize'], NV_MAX_WIDTH, NV_MAX_HEIGHT);
    $lang_module['smallsize'] = sprintf($lang_module['smallsize'], $logo_config['logo_width'], $logo_config['logo_height']);

    $xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    if ($array['success']) {
        $xtpl->assign('FILENAME', $array['filename']);
        $xtpl->parse('logoupload.complete');
    } else {
        $xtpl->assign('LANG', $lang_module);
        $xtpl->assign('GLANG', $lang_global);
        $xtpl->assign('CONFIG', $logo_config);
        $xtpl->assign('TEMPLATE', $global_config['module_theme']);
        $xtpl->assign('MODULE_NAME', $module_name);
        $xtpl->assign('OP', $op);
    
        if ($array['error']) {
            $xtpl->assign('ERROR', $array['error']);
            $xtpl->parse('logoupload.init.error');
        }

        $xtpl->parse('logoupload.init');
    }

    $xtpl->parse('logoupload');
    $content = $xtpl->text('logoupload');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($content, false);
    include NV_ROOTDIR . '/includes/footer.php';
}

// Xóa biểu trưng
if ($nv_Request->isset_request('logodel', 'post')) {
    if (!empty($global_config['organization_logo']) and file_exists(NV_ROOTDIR . '/' . $global_config['organization_logo'])) {
        nv_deletefile(NV_ROOTDIR . '/' . $global_config['organization_logo']);
    }
    $db->query('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = '' WHERE lang = 'sys' AND module = 'site' AND config_name = 'organization_logo'");
    $nv_Cache->delAll(false);
    nv_jsonOutput([
        'status' => 'OK'
    ]);
}

$checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid']);
if ($checkss == $nv_Request->get_string('checkss', 'post')) {
    $array_config_site = [];
    $array_config_site['sitelinks_search_box_schema'] = (int) $nv_Request->get_bool('sitelinks_search_box_schema', 'post', false);
    $array_config_site['breadcrumblist'] = (int) $nv_Request->get_bool('breadcrumblist', 'post', false);

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'site' AND config_name = :config_name");
    foreach ($array_config_site as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    $nv_Cache->delAll(false);
    nv_jsonOutput([
        'status' => 'OK'
    ]);
}

$page_title = $lang_module['other_seo_tools'];

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('DATA', $global_config);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('CHECKSS', $checkss);
$xtpl->assign('ORGANIZATION_LOGO_DEFAULT', NV_BASE_SITEURL . NV_ASSETS_DIR . '/images/no-photo.svg');
$xtpl->assign('ORGANIZATION_LOGO', !empty($global_config['organization_logo']) ? NV_BASE_SITEURL . $global_config['organization_logo'] : NV_BASE_SITEURL . NV_ASSETS_DIR . '/images/no-photo.svg');
$xtpl->assign('BREADCRUMBLIST_CHECKED', $global_config['breadcrumblist'] ? ' checked="checked"' : '');
$xtpl->assign('SEARCH_BOX_SCHEMA_CHECKED', $global_config['sitelinks_search_box_schema'] ? ' checked="checked"' : '');

if (empty($global_config['organization_logo'])) {
    $xtpl->parse('main.delbtn');
}

$xtpl->parse('main');
$content = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($content);
include NV_ROOTDIR . '/includes/footer.php';
