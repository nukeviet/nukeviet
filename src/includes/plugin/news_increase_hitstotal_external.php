<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$nv_hook_module = 'news'; // Module xảy ra event chứa data
$nv_receive_module = 'news'; // Module nhận và xử lý data

$callback = function ($vars, $from_data, $receive_data) {
    global $nv_Request, $op, $db;
    
    $module_name = $receive_data['module_name'];
    $module_info = $receive_data['module_info'];
    $module_data = $module_info['module_data'];
    $news_contents = $vars[0];
    $time_set = $nv_Request->get_int($module_data . '_' . $op . '_' . $news_contents['id'], 'session');
    if (empty($time_set)) {
        $nv_Request->set_Session($module_data . '_' . $op . '_' . $news_contents['id'], NV_CURRENTTIME);
        $query = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET hitstotal=hitstotal+1 WHERE id=' . $news_contents['id'];
        $db->query($query);

        $array_catid = explode(',', $news_contents['listcatid']);
        foreach ($array_catid as $catid_i) {
            $query = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid_i . ' SET hitstotal=hitstotal+1 WHERE id=' . $news_contents['id'];
            $db->query($query);
        }
    }
    return $news_contents;
};

nv_add_hook($module_name, 'before_redirect_external_link', $priority, $callback, $hook_module, $pid);
