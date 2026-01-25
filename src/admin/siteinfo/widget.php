<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_SITEINFO')) {
    exit('Stop!!!');
}

// Cấu hình giao diện
$theme_config = get_theme_config();
$init_widget = [
    'widget_id' => '',
    'sizes' => [
        'xs' => 12,
        'sm' => 12,
        'md' => 12,
        'lg' => 12,
        'xl' => 12,
        'xxl' => 12
    ]
];

// Thêm khối vào đầu hoặc cuối
if ($nv_Request->get_title('addparent', 'post', '') === NV_CHECK_SESSION) {
    $respon = [
        'error' => 1,
        'message' => 'Error!!!'
    ];

    $placement = $nv_Request->get_title('placement', 'post', '');
    if (!in_array($placement, ['bottom', 'top'], true)) {
        $respon['message'] = 'Wrong placement!!!';
        nv_jsonOutput($respon);
    }

    $next_key = empty($theme_config['grid_widgets']) ? 0 : (max(array_keys($theme_config['grid_widgets'])) + 1);

    $widget = [
        $next_key => $init_widget
    ];
    if ($placement == 'top') {
        $theme_config['grid_widgets'] = $widget + $theme_config['grid_widgets'];
    } else {
        $theme_config['grid_widgets'] += $widget;
    }
    save_theme_config('grid_widgets', $theme_config['grid_widgets']);
    $respon['error'] = 0;
    $respon['new_key'] = $next_key;

    nv_jsonOutput($respon);
}

// Xóa khối
if ($nv_Request->get_title('delete', 'post', '') === NV_CHECK_SESSION) {
    $respon = [
        'error' => 1,
        'message' => 'Error!!!'
    ];

    $widget_id = $nv_Request->get_title('widget_id', 'post', -1);
    $widget_parentid = $nv_Request->get_title('widget_parentid', 'post', -1);

    if ($widget_parentid < 0) {
        if (!isset($theme_config['grid_widgets'][$widget_id])) {
            $respon['message'] = 'Widget not exists!!!';
            nv_jsonOutput($respon);
        }
        unset($theme_config['grid_widgets'][$widget_id]);
    } else {
        if (!isset($theme_config['grid_widgets'][$widget_parentid], $theme_config['grid_widgets'][$widget_parentid]['subs'], $theme_config['grid_widgets'][$widget_parentid]['subs'][$widget_id])) {
            $respon['message'] = 'Widget not exists!!!';
            nv_jsonOutput($respon);
        }
        unset($theme_config['grid_widgets'][$widget_parentid]['subs'][$widget_id]);
        if (empty($theme_config['grid_widgets'][$widget_parentid]['subs'])) {
            $theme_config['grid_widgets'][$widget_parentid] = $init_widget;
        }
    }
    save_theme_config(['grid_widgets', 'widgets'], [$theme_config['grid_widgets'], get_list_widgets($theme_config['grid_widgets'])]);
    $respon['error'] = 0;
    nv_jsonOutput($respon);
}

// Thêm khối con
if ($nv_Request->get_title('addchild', 'post', '') === NV_CHECK_SESSION) {
    $respon = [
        'error' => 1,
        'message' => 'Error!!!'
    ];

    $widget_id = $nv_Request->get_title('widget_id', 'post', -1);
    $placement = $nv_Request->get_title('placement', 'post', '');
    if (!in_array($placement, ['bottom', 'top'], true)) {
        $respon['message'] = 'Wrong placement!!!';
        nv_jsonOutput($respon);
    }
    if (!isset($theme_config['grid_widgets'][$widget_id])) {
        $respon['message'] = 'Widget not exists!!!';
        nv_jsonOutput($respon);
    }
    if (empty($theme_config['grid_widgets'][$widget_id]['subs'])) {
        $new_widget = $theme_config['grid_widgets'][$widget_id];
        $new_widget['widget_id'] = '';
        $new_widget['subs'] = [
            0 => $theme_config['grid_widgets'][$widget_id]
        ];
        $theme_config['grid_widgets'][$widget_id] = $new_widget;
    } else {
        $next_key = (max(array_keys($theme_config['grid_widgets'][$widget_id]['subs'])) + 1);

        $widget = [
            $next_key => $init_widget
        ];
        if ($placement == 'top') {
            $theme_config['grid_widgets'][$widget_id]['subs'] = $widget + $theme_config['grid_widgets'][$widget_id]['subs'];
        } else {
            $theme_config['grid_widgets'][$widget_id]['subs'] += $widget;
        }
    }
    save_theme_config('grid_widgets', $theme_config['grid_widgets']);
    $respon['error'] = 0;
    nv_jsonOutput($respon);
}

// Chỉnh kích thước
if ($nv_Request->get_title('resize', 'post', '') === NV_CHECK_SESSION) {
    $respon = [
        'error' => 1,
        'message' => 'Error!!!'
    ];

    $widget_id = $nv_Request->get_title('widget_id', 'post', -1);
    $widget_parentid = $nv_Request->get_title('widget_parentid', 'post', -1);
    $breakpoint = $nv_Request->get_title('breakpoint', 'post', '');
    $value = $nv_Request->get_int('value', 'post', 0);

    if (!isset($init_widget['sizes'][$breakpoint])) {
        $respon['message'] = 'Wrong breakpoint!!!';
        nv_jsonOutput($respon);
    }
    if ($value < 1 or $value > 12) {
        $respon['message'] = 'Wrong size!!!';
        nv_jsonOutput($respon);
    }
    if ($widget_parentid < 0) {
        if (!isset($theme_config['grid_widgets'][$widget_id])) {
            $respon['message'] = 'Widget not exists!!!';
            nv_jsonOutput($respon);
        }
        $theme_config['grid_widgets'][$widget_id]['sizes'][$breakpoint] = $value;
        $sizes = $theme_config['grid_widgets'][$widget_id]['sizes'];
    } else {
        if (!isset($theme_config['grid_widgets'][$widget_parentid], $theme_config['grid_widgets'][$widget_parentid]['subs'], $theme_config['grid_widgets'][$widget_parentid]['subs'][$widget_id])) {
            $respon['message'] = 'Widget not exists!!!';
            nv_jsonOutput($respon);
        }
        $theme_config['grid_widgets'][$widget_parentid]['subs'][$widget_id]['sizes'][$breakpoint] = $value;
        $sizes = $theme_config['grid_widgets'][$widget_parentid]['subs'][$widget_id]['sizes'];
    }

    save_theme_config('grid_widgets', $theme_config['grid_widgets']);
    $respon['error'] = 0;
    $respon['sizes'] = [];
    foreach ($sizes as $key => $value) {
        $respon['sizes'][] = 'col-' . $key . '-' . $value;
    }
    $respon['sizes'] = implode(' ', $respon['sizes']);
    nv_jsonOutput($respon);
}

// Chọn widget cho khối
if ($nv_Request->get_title('setwidget', 'post', '') === NV_CHECK_SESSION) {
    $respon = [
        'error' => 1,
        'message' => 'Error!!!'
    ];

    $widget_id = $nv_Request->get_title('widget_id', 'post', -1);
    $widget_parentid = $nv_Request->get_title('widget_parentid', 'post', -1);
    $id = $nv_Request->get_title('id', 'post', '');

    if ($widget_parentid < 0) {
        if (!isset($theme_config['grid_widgets'][$widget_id])) {
            $respon['message'] = 'Widget not exists!!!';
            nv_jsonOutput($respon);
        }
        $theme_config['grid_widgets'][$widget_id]['widget_id'] = $id;
    } else {
        if (!isset($theme_config['grid_widgets'][$widget_parentid], $theme_config['grid_widgets'][$widget_parentid]['subs'], $theme_config['grid_widgets'][$widget_parentid]['subs'][$widget_id])) {
            $respon['message'] = 'Widget not exists!!!';
            nv_jsonOutput($respon);
        }
        $theme_config['grid_widgets'][$widget_parentid]['subs'][$widget_id]['widget_id'] = $id;
    }

    save_theme_config(['grid_widgets', 'widgets'], [$theme_config['grid_widgets'], get_list_widgets($theme_config['grid_widgets'])]);
    $respon['error'] = 0;
    nv_jsonOutput($respon);
}

// Chọn widget cho khối
if ($nv_Request->get_title('swapwidget', 'post', '') === NV_CHECK_SESSION) {
    $respon = [
        'error' => 1,
        'message' => 'Error!!!'
    ];

    $widget_id1 = $nv_Request->get_title('widget_id1', 'post', -1);
    $widget_parentid1 = $nv_Request->get_title('widget_parentid1', 'post', -1);
    $widget_id2 = $nv_Request->get_title('widget_id2', 'post', -1);
    $widget_parentid2 = $nv_Request->get_title('widget_parentid2', 'post', -1);

    if ($widget_parentid1 < 0) {
        if (!isset($theme_config['grid_widgets'][$widget_id1])) {
            $respon['message'] = 'Widget not exists!!!';
            nv_jsonOutput($respon);
        }
        $id1 = $theme_config['grid_widgets'][$widget_id1]['widget_id'];
    } else {
        if (!isset($theme_config['grid_widgets'][$widget_parentid1], $theme_config['grid_widgets'][$widget_parentid1]['subs'], $theme_config['grid_widgets'][$widget_parentid1]['subs'][$widget_id1])) {
            $respon['message'] = 'Widget not exists!!!';
            nv_jsonOutput($respon);
        }
        $id1 = $theme_config['grid_widgets'][$widget_parentid1]['subs'][$widget_id1]['widget_id'];
    }
    if ($widget_parentid2 < 0) {
        if (!isset($theme_config['grid_widgets'][$widget_id2])) {
            $respon['message'] = 'Widget not exists!!!';
            nv_jsonOutput($respon);
        }
        $id2 = $theme_config['grid_widgets'][$widget_id2]['widget_id'];
    } else {
        if (!isset($theme_config['grid_widgets'][$widget_parentid2], $theme_config['grid_widgets'][$widget_parentid2]['subs'], $theme_config['grid_widgets'][$widget_parentid2]['subs'][$widget_id2])) {
            $respon['message'] = 'Widget not exists!!!';
            nv_jsonOutput($respon);
        }
        $id2 = $theme_config['grid_widgets'][$widget_parentid2]['subs'][$widget_id2]['widget_id'];
    }

    if ($widget_parentid1 < 0) {
        $theme_config['grid_widgets'][$widget_id1]['widget_id'] = $id2;
    } else {
        $theme_config['grid_widgets'][$widget_parentid1]['subs'][$widget_id1]['widget_id'] = $id2;
    }
    if ($widget_parentid2 < 0) {
        $theme_config['grid_widgets'][$widget_id2]['widget_id'] = $id1;
    } else {
        $theme_config['grid_widgets'][$widget_parentid2]['subs'][$widget_id2]['widget_id'] = $id1;
    }

    save_theme_config(['grid_widgets', 'widgets'], [$theme_config['grid_widgets'], get_list_widgets($theme_config['grid_widgets'])]);
    $respon['error'] = 0;
    $respon['message'] = $nv_Lang->getModule('widget_swap_success');
    nv_jsonOutput($respon);
}

nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
