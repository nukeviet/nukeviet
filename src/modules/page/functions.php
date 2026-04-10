<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_SYSTEM')) {
    exit('Stop!!!');
}

define('NV_IS_MOD_PAGE', true);

// Get Config Module
$sql = 'SELECT config_name, config_value FROM ' . NV_PREFIXLANG . '_' . $module_data . '_config';
$list = $nv_Cache->db($sql, '', $module_name);
$page_config = [];
foreach ($list as $values) {
    $page_config[$values['config_name']] = $values['config_value'];
}

require NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';

$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
$id = 0;
$page = 1;

if ($page_config['viewtype'] != 2) {
    $alias = (!empty($array_op) and !empty($array_op[0])) ? $array_op[0] : '';
    if (substr($alias, 0, 5) == 'page-') {
        $page = (int) (substr($array_op[0], 5));
        $id = 0;
        $alias = '';
    } elseif (empty($alias) and empty($page_config['viewtype'])) {
        $stmt = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE status = 1 ORDER BY weight ASC LIMIT 1');
        $rowdetail = $stmt->fetch();
        $stmt->closeCursor();
        if (!empty($rowdetail)) {
            $id = $rowdetail['id'];
        }
    } elseif (!empty($alias)) {
        $sth = $db->prepare('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE alias = :alias');
        $sth->bindValue(':alias', $alias, PDO::PARAM_STR);
        $sth->execute();
        $rowdetail = $sth->fetch();
        $sth->closeCursor();
        if (empty($rowdetail)) {
            nv_redirect_location($base_url);
        }
        $id = $rowdetail['id'];
    }
}

/**
 * Bổ sung các thuộc tính thêm cho bài đăng nói chung
 *
 * @param array $row
 * @return void
 */
function extend_row_data(array &$row): void {
    if (defined('NV_IS_MODADMIN')) {
        global $admin_info, $module_name;

        $row['admin_checkss'] = csrf_create($admin_info['admin_id'] . '_' . $module_name . '_' . $row['id']);
        $row['admin_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=content&amp;id=' . $row['id'];
    } else {
        $row['admin_checkss'] = '';
        $row['admin_edit'] = '';
    }
}
