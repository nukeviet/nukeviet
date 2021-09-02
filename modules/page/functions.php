<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
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
        $db_slave->sqlreset()
            ->select('*')
            ->from(NV_PREFIXLANG . '_' . $module_data)
            ->where('status=1')
            ->order('weight ASC')
            ->limit(1);
        $rowdetail = $db_slave->query($db_slave->sql())
            ->fetch();
        if (!empty($rowdetail)) {
            $id = $rowdetail['id'];
        }
    } elseif (!empty($alias)) {
        $sth = $db_slave->prepare('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE alias=:alias');
        $sth->bindParam(':alias', $alias, PDO::PARAM_STR);
        $sth->execute();
        $rowdetail = $sth->fetch();
        if (empty($rowdetail)) {
            nv_redirect_location($base_url);
        }
        $id = $rowdetail['id'];
    }
}
