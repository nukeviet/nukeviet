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

// Bảng log dùng chung — không phân theo ngôn ngữ
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_seeder_log;';

$sql_create_module = $sql_drop_module;

$sql_create_module[] = 'CREATE TABLE ' . $db_config['prefix'] . "_seeder_log (
 id int(11) unsigned NOT NULL AUTO_INCREMENT,
 run_id varchar(36) NOT NULL DEFAULT '' COMMENT 'UUID v4 cho 1 lần chạy seed',
 step varchar(50) NOT NULL DEFAULT '' COMMENT 'categories|topics|articles|...',
 natural_key varchar(250) NOT NULL DEFAULT '' COMMENT 'alias hoặc title của item trong manifest',
 db_id int(11) NOT NULL DEFAULT '0' COMMENT 'id thực tế trong bảng đích',
 db_table varchar(100) NOT NULL DEFAULT '' COMMENT 'tên bảng đã ghi (nv5_vi_news_cat...)',
 action varchar(20) NOT NULL DEFAULT '' COMMENT 'created|updated|skipped|failed',
 message text NULL,
 run_time int(11) unsigned NOT NULL DEFAULT '0',
 PRIMARY KEY (id),
 KEY step (step),
 KEY run_id (run_id),
 KEY natural_key (natural_key)
) ENGINE=InnoDB";
