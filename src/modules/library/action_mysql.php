<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_MODULES')) {
    exit('Stop!!!');
}

$sql_drop_module = [];

$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data;
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_rows';
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_listtype';

$sql_create_module = $sql_drop_module;

$sql_create_module[] = 'CREATE TABLE ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_rows (
 id INT(11) NOT NULL AUTO_INCREMENT,
 title varchar(255) NOT NULL DEFAULT '',
 description varchar(255) NOT NULL DEFAULT '',
 PRIMARY KEY (id)
) ENGINE=MyISAM";

// Thêm 3 dòng dữ liệu vào bảng _rows
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows (title, description) VALUES 
('Tiêu đề 01', 'Mô tả chi tiết cho dòng số 01'),
('Tiêu đề 02', 'Mô tả chi tiết cho dòng số 02'),
('Tiêu đề 03', 'Mô tả chi tiết cho dòng số 03')";

$sql_create_module[] = 'CREATE TABLE ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_listtype (
 id INT(11) unsigned NOT NULL AUTO_INCREMENT,
 title varchar(50) NOT NULL,
 PRIMARY KEY (id)
) ENGINE=MyISAM";
