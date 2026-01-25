<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    exit('Stop!!!');
}

$nv_Lang->setModule('thumb_default_size_note', $nv_Lang->getModule('thumb_default_size_note', $global_config['thumb_max_width'], $global_config['thumb_max_height']));

if ($nv_Request->isset_request('save', 'post')) {
    if ($nv_Request->get_title('checkss', 'post', '') !== NV_CHECK_SESSION) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => 'Error session!!!'
        ]);
    }

    $thumb_type = $nv_Request->get_typed_array('thumb_type', 'post', 'int', []);
    $thumb_width = $nv_Request->get_typed_array('thumb_width', 'post', 'int', []);
    $thumb_height = $nv_Request->get_typed_array('thumb_height', 'post', 'int', []);
    $thumb_quality = $nv_Request->get_typed_array('thumb_quality', 'post', 'int', []);

    !empty($global_config['thumb_max_width']) && $global_config['thumb_max_width'] = 350;
    !empty($global_config['thumb_max_height']) && $global_config['thumb_max_height'] = 350;

    $opts = [];
    $keys = array_keys($thumb_type);
    foreach ($keys as $did) {
        if (!empty($thumb_type[$did])) {
            $max_width = $did == 0 ? $global_config['thumb_max_width'] : 1000;
            $max_height = $did == 0 ? $global_config['thumb_max_height'] : 1000;
            $error = $did == 0 ? $nv_Lang->getModule('thumb_default_size_note') : $nv_Lang->getModule('thumb_dir_size_note');

            if ($thumb_type[$did] == 2) {
                $thumb_width[$did] = 0;
            } else {
                if ($thumb_width[$did] > $max_width or $thumb_width[$did] <= 0) {
                    nv_jsonOutput([
                        'status' => 'error',
                        'input_parent' => '.item[data-did="d' . $did . '"]',
                        'input' => 'thumb_width',
                        'mess' => $error
                    ]);
                }
            }

            if ($thumb_type[$did] == 1) {
                $thumb_height[$did] = 0;
            } elseif ($thumb_height[$did] > $max_height or $thumb_height[$did] <= 0) {
                nv_jsonOutput([
                    'status' => 'error',
                    'input_parent' => '.item[data-did="d' . $did . '"]',
                    'input' => 'thumb_height',
                    'mess' => $error
                ]);
            }
            $opts[$did] = [$thumb_type[$did], $thumb_width[$did], $thumb_height[$did], $thumb_quality[$did]];
        }
    }

    $did = $nv_Request->get_int('other_dir', 'post', 0);
    $type = $nv_Request->get_int('other_type', 'post', 0);
    if ($did and $type) {
        $width = $nv_Request->get_int('other_thumb_width', 'post', 0);
        $height = $nv_Request->get_int('other_thumb_height', 'post', 0);
        $quality = $nv_Request->get_int('other_thumb_quality', 'post', 0);

        if ($type == 2) {
            $width = 0;
        } else {
            if ($width > 1000 or $width <= 0) {
                nv_jsonOutput([
                    'status' => 'error',
                    'input' => 'other_thumb_width',
                    'mess' => $nv_Lang->getModule('thumb_dir_size_note')
                ]);
            }
        }

        if ($type == 1) {
            $height = 0;
        } elseif ($height > 1000 or $height <= 0) {
            nv_jsonOutput([
                'status' => 'error',
                'input' => 'other_thumb_height',
                'mess' => $nv_Lang->getModule('thumb_dir_size_note')
            ]);
        }
        $opts[$did] = [$type, $width, $height, $quality];
    }

    foreach ($opts as $did => $opt) {
        $db->query('UPDATE ' . NV_UPLOAD_GLOBALTABLE . '_dir SET
            thumb_type = ' . $opt[0] . ', thumb_width = ' . $opt[1] . ',
            thumb_height = ' . $opt[2] . ', thumb_quality = ' . $opt[3] . '
            WHERE did = ' . $did);
    }

    $in = implode(',', array_keys($opts));
    $db->query('UPDATE ' . NV_UPLOAD_GLOBALTABLE . '_dir SET
        thumb_type = 0, thumb_width = 0,
        thumb_height = 0, thumb_quality = 0
    WHERE did NOT IN (' . $in . ')');

    nv_jsonOutput([
        'status' => 'success',
        'mess' => $nv_Lang->getGlobal('save_success'),
        'refresh' => 1
    ]);
}

// Xem ví dụ ảnh thumb
if ($nv_Request->isset_request('getexample', 'post')) {
    if (!defined('NV_IS_AJAX')) {
        exit('Wrong URL');
    }
    if ($nv_Request->get_title('checkss', 'post', '') !== NV_CHECK_SESSION) {
        nv_jsonOutput([
            'status' => 'error',
            'message' => 'Error session!!!'
        ]);
    }

    $thumb_dir = $nv_Request->get_int('did', 'post', 0);
    $thumb_type = $nv_Request->get_int('t', 'post', 0);
    $thumb_width = $nv_Request->get_int('w', 'post', 0);
    $thumb_height = $nv_Request->get_int('h', 'post', 0);
    $thumb_quality = $nv_Request->get_int('q', 'post', 0);

    if ((!empty($thumb_dir) and !in_array($thumb_dir, $array_dirname, true)) or $thumb_type <= 0 or ($thumb_width <= 0 and $thumb_type != 2) or ($thumb_height <= 0 and $thumb_type != 1) or $thumb_quality <= 0 or $thumb_quality > 100) {
        nv_jsonOutput([
            'status' => 'error',
            'message' => $nv_Lang->getModule('prViewExampleError')
        ]);
    }

    $return = ['status' => 'error'];

    // Tìm ra các ảnh demo
    $image_demo = [];

    if ($thumb_dir) {
        $select_dir = array_intersect($array_dirname, [$thumb_dir]);
        $select_dir = key($select_dir);

        foreach ($array_dirname as $dirname => $did) {
            if (!empty($image_demo)) {
                break;
            }
            if (str_starts_with($dirname, $select_dir)) {
                $image_demo = $db->query('SELECT * FROM ' . NV_UPLOAD_GLOBALTABLE . '_file tb1, ' . NV_UPLOAD_GLOBALTABLE . '_dir tb2 WHERE tb1.did=tb2.did AND tb1.type=\'image\' AND tb1.did=' . $did . ' ORDER BY RAND() LIMIT 1')->fetch();
            }
        }
    }

    if (empty($image_demo)) {
        $image_demo = $db->query('SELECT * FROM ' . NV_UPLOAD_GLOBALTABLE . '_file tb1, ' . NV_UPLOAD_GLOBALTABLE . '_dir tb2 WHERE tb1.did=tb2.did AND tb1.type=\'image\' ORDER BY RAND() LIMIT 1')->fetch();
    }

    if (empty($image_demo)) {
        nv_jsonOutput([
            'status' => 'error',
            'message' => $nv_Lang->getModule('prViewExampleError2')
        ]);
    }

    $image_demo['sizes'] = explode('|', $image_demo['sizes']);
    $result = [];
    $result['status'] = 'success';
    $result['src'] = NV_BASE_SITEURL . $image_demo['dirname'] . '/' . $image_demo['title'] . '?t=' . NV_CURRENTTIME;
    $result['width'] = $image_demo['sizes'][0];
    $result['height'] = $image_demo['sizes'][1];
    $result['thumbsrc'] = NV_BASE_SITEURL . $image_demo['dirname'] . '/' . $image_demo['title'];
    $result['thumbwidth'] = $image_demo['sizes'][0];
    $result['thumbheight'] = $image_demo['sizes'][1];

    $file_tmp_name = 'thumbdemo_' . NV_CACHE_PREFIX . '.' . $image_demo['ext'];
    $file_tmp = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $file_tmp_name;
    if (file_exists($file_tmp)) {
        nv_deletefile($file_tmp);
    }
    $image = new NukeViet\Files\Image(NV_ROOTDIR . '/' . $image_demo['dirname'] . '/' . $image_demo['title'], NV_MAX_WIDTH, NV_MAX_HEIGHT);
    if ($thumb_type == 4) {
        $_thumb_width = $thumb_width;
        $_thumb_height = $thumb_height;
        $maxwh = max($_thumb_width, $_thumb_height);
        if ($image->fileinfo['width'] > $image->fileinfo['height']) {
            $thumb_width = 0;
            $thumb_height = $maxwh;
        } else {
            $thumb_width = $maxwh;
            $thumb_height = 0;
        }
    }
    if ($image->fileinfo['width'] > $thumb_width or $image->fileinfo['height'] > $thumb_height) {
        $image->resizeXY($thumb_width, $thumb_height);
        if ($thumb_type == 4) {
            $image->cropFromCenter($_thumb_width, $_thumb_height);
        }
        $image->save(NV_ROOTDIR . '/' . NV_TEMP_DIR, $file_tmp_name, $thumb_quality);
        $create_Image_info = $image->create_Image_info;
        $error = $image->error;
        $image->close();
        if (empty($error)) {
            $result['thumbsrc'] = NV_BASE_SITEURL . NV_TEMP_DIR . '/' . $file_tmp_name . '?t=' . NV_CURRENTTIME;
            $result['thumbwidth'] = $image->create_Image_info['width'];
            $result['thumbheight'] = $image->create_Image_info['height'];
        }
    }

    nv_jsonOutput($result);
}

$tpl = new \NukeViet\Template\NVSmarty();

$tpl->setTemplateDir(get_module_tpl_dir('thumbconfig.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);

$sql = 'SELECT * FROM ' . NV_UPLOAD_GLOBALTABLE . '_dir ORDER BY dirname ASC';
$result = $db->query($sql);

$configured_dirs = $other_dirs = [];
while ($data = $result->fetch()) {
    if ($data['did'] == 0) {
        $data['dirname'] = $nv_Lang->getModule('thumb_dir_default');
    }
    if ($data['thumb_type']) {
        $configured_dirs[] = $data;
    } else {
        $other_dirs[] = $data;
    }
}
$result->closeCursor();

$tpl->assign('CONFIGURED_DIRS', $configured_dirs);
$tpl->assign('OTHER_DIRS', $other_dirs);

$contents = $tpl->fetch('thumbconfig.tpl');
$page_title = $nv_Lang->getModule('thumbconfig');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
