<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_MODULES')) {
    exit('Stop!!!');
}

$sql_drop_module = [];

$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data;
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_image';

$sql_create_module = $sql_drop_module;

$sql_create_module[] = 'CREATE TABLE ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_image (
    id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
    ten_anh varchar(245) NOT NULL DEFAULT "",
    id_album int(11) unsigned NOT NULL DEFAULT "0",
    url_anh varchar(255) DEFAULT "",
    id_user int(11) unsigned NOT NULL DEFAULT "0",
    time int(11) unsigned NOT NULL DEFAULT "0",
    trangthai int(11) unsigned NOT NULL DEFAULT "0",
    UNIQUE KEY vid (vid)
) ENGINE=MyISAM";


$sql_create_module[] = 'CREATE TABLE ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . ' (
    id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
    ten_album varchar(245) NOT NULL DEFAULT '',
    url_album varchar(255) DEFAULT '',
    mota TEXT DEFAULT '',
    id_user int(11) unsigned NOT NULL DEFAULT '0',
    time int(11) unsigned NOT NULL DEFAULT '0',
    PRIMARY KEY (id),
    UNIQUE KEY album (ten_album,url),
    INDEX (id_user)
) ENGINE=MyISAM';
