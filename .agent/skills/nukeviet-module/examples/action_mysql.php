<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_MODULES')) {
    exit('Stop!!!');
}

$sql_drop_module = [];
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data;
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_config';

$sql_create_module = $sql_drop_module;

// Bảng chính
$sql_create_module[] = 'CREATE TABLE ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . ' (
 id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
 title varchar(250) NOT NULL,
 alias varchar(250) NOT NULL,
 status tinyint(1) unsigned NOT NULL DEFAULT \'0\',
 weight smallint(4) NOT NULL DEFAULT \'0\',
 admin_id mediumint(8) unsigned NOT NULL DEFAULT \'0\',
 add_time int(11) NOT NULL DEFAULT \'0\',
 edit_time int(11) NOT NULL DEFAULT \'0\',
 PRIMARY KEY (id),
 UNIQUE KEY alias (alias)
) ENGINE=InnoDB';

// Bảng config module (pattern chuẩn — hầu hết module có bảng _config riêng)
$sql_create_module[] = 'CREATE TABLE ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_config (
 config_name varchar(30) NOT NULL,
 config_value varchar(255) NOT NULL,
 UNIQUE KEY config_name (config_name)
) ENGINE=InnoDB';

// Insert giá trị mặc định cho config
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_config VALUES
('per_page', '20'),
('status_default', '1')";

// Config toàn cục (NV_CONFIG_GLOBALTABLE) — dùng khi tích hợp comment hoặc config hệ thống
// $sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value)
//     VALUES ('" . $lang . "', '" . $module_name . "', 'allowed_comm', '-1')";
