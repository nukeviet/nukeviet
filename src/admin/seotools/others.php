<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_SEOTOOLS')) {
    exit('Stop!!!');
}

// Thông tin doanh nghiệp mẫu
$sample_data = [
    '@context' => 'https://schema.org',
    '@type' => 'LocalBusiness',
    'name' => $nv_Lang->getModule('localbusiness_name'),
    'image' => ['https://www.mywebsite.com/images/image1.jpg', 'https://www.mywebsite.com/images/image2.jpg'],
    'address' => [
        '@type' => 'PostalAddress',
        'streetAddress' => '141 Nguyen Hue, Ben Nghe Ward, District 1',
        'addressLocality' => 'Ho Chi Minh',
        'addressRegion' => '',
        'postalCode' => '700000',
        'addressCountry' => 'VN'
    ],
    'geo' => [
        '@type' => 'GeoCoordinates',
        'latitude' => 10.776378,
        'longitude' => 106.701376
    ],
    'url' => 'https://www.mywebsite.com',
    'telephone' => '+84933123456',
    'openingHoursSpecification' => [
        [
            '@type' => 'OpeningHoursSpecification',
            'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
            'opens' => '08:00',
            'closes' => '18:00'
        ],
        [
            '@type' => 'OpeningHoursSpecification',
            'dayOfWeek' => ['Saturday'],
            'opens' => '08:00',
            'closes' => '14:00'
        ]
    ]
];

function strip_tags_array($array)
{
    return array_map(function ($val) {
        if (!is_array($val)) {
            return is_string($val) ? trim(strip_tags($val)) : $val;
        }

        return strip_tags_array($val);
    }, $array);
}

[$template, $dir] = get_module_tpl_dir('others.tpl', true);
$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir($dir);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('TEMPLATE', $template);
$tpl->assign('OP', $op);

// Khai báo thông tin doanh nghiệp
if ($nv_Request->isset_request('localbusiness_information', 'get')) {
    if ($nv_Request->isset_request('save', 'post')) {
        $jsondata = $nv_Request->get_textarea('jsondata', '', '', false, false);
        if (empty($jsondata)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('localbusiness_data_empty')
            ]);
        }

        $jsondata = json_decode($jsondata, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('localbusiness_data_empty')
            ]);
        }

        $jsondata = strip_tags_array($jsondata);
        if (empty($jsondata['@context']) or $jsondata['@context'] != 'https://schema.org' or empty($jsondata['@type'])) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('localbusiness_data_empty')
            ]);
        }

        if (empty($jsondata['name'])) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('localbusiness_name_error')
            ]);
        }

        if (empty($jsondata['address']['streetAddress']) or empty($jsondata['address']['addressLocality']) or empty($jsondata['address']['addressCountry'])) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('localbusiness_address_error')
            ]);
        }

        $jsondata = json_encode($jsondata, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents(NV_ROOTDIR . '/' . NV_DATADIR . '/localbusiness.json', $jsondata, LOCK_EX);
        nv_jsonOutput([
            'status' => 'OK',
            'redirect' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op
        ]);
    }
    $page_title = $nv_Lang->getModule('localbusiness_information');

    $data = [];
    if (file_exists(NV_ROOTDIR . '/' . NV_DATADIR . '/localbusiness.json')) {
        $data = file_get_contents(NV_ROOTDIR . '/' . NV_DATADIR . '/localbusiness.json');
        $data = json_decode($data, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $data = [];
        }
    }
    if (empty($data)) {
        $data = $sample_data;
    }
    $tpl->assign('DATA', htmlspecialchars(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)));

    $contents = $tpl->fetch('localbusiness.tpl');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

// Lấy thông tin doanh nghiệp mẫu
if ($nv_Request->isset_request('sample_data', 'post')) {
    nv_htmlOutput(json_encode($sample_data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// Xóa file thông tin doanh nghiệp
if ($nv_Request->isset_request('lbinf_delete', 'post')) {
    if (file_exists(NV_ROOTDIR . '/' . NV_DATADIR . '/localbusiness.json')) {
        nv_deletefile(NV_ROOTDIR . '/' . NV_DATADIR . '/localbusiness.json');
    }

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = '0' WHERE lang = 'sys' AND module = 'site' AND config_name = 'localbusiness'");
    $sth->execute();
    $nv_Cache->delAll(false);
    nv_htmlOutput(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
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
            $array['error'] = $nv_Lang->getModule('logo_error_data');
        } else {
            $upload = new NukeViet\Files\Upload(['images'], $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE);
            $upload->setLanguage(\NukeViet\Core\Language::$lang_global);

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
                    $array['error'] = $nv_Lang->getModule('avatar_error_save');
                }
                @nv_deletefile($upload_info['name']);
            } else {
                $array['error'] = $upload_info['error'];
            }
        }
    }

    $nv_Lang->setModule('bigfile', $nv_Lang->getModule('bigfile', nv_convertfromBytes(NV_UPLOAD_MAX_FILESIZE)));
    $nv_Lang->setModule('bigsize', $nv_Lang->getModule('bigsize', NV_MAX_WIDTH, NV_MAX_HEIGHT));
    $nv_Lang->setModule('smallsize', $nv_Lang->getModule('smallsize', $logo_config['logo_width'], $logo_config['logo_height']));

    $tpl->assign('DATA', json_encode($array));

    $contents = $tpl->fetch('others-uploaded.tpl');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
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

// Lưu các giá trị gửi qua form
$checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid']);
if ($checkss == $nv_Request->get_string('checkss', 'post')) {
    $name = $nv_Request->get_title('name', 'post', '');
    $val = (int) $nv_Request->get_bool('val', 'post', false);

    if ($name == 'localbusiness' and $val == 1) {
        if (!file_exists(NV_ROOTDIR . '/' . NV_DATADIR . '/localbusiness.json')) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('localbusiness_file_error')
            ]);
        }
    }

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'site' AND config_name = :config_name");
    $sth->bindParam(':config_name', $name, PDO::PARAM_STR, 30);
    $sth->bindParam(':config_value', $val, PDO::PARAM_STR);
    $sth->execute();

    $nv_Cache->delAll(false);
    nv_jsonOutput([
        'status' => 'OK'
    ]);
}

$page_title = $nv_Lang->getModule('other_seo_tools');

$tpl->registerPlugin('modifier', 'file_exists', 'file_exists');
$tpl->assign('GCONFIG', $global_config);
$tpl->assign('CHECKSS', $checkss);

$contents = $tpl->fetch('others.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
