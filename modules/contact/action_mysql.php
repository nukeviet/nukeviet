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

$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_department';
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_send';
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_reply';
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_supporter';

$sql_create_module = $sql_drop_module;

$sql_create_module[] = 'CREATE TABLE ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_department (
 id smallint(5) unsigned NOT NULL AUTO_INCREMENT,
 full_name varchar(250) NOT NULL,
 alias varchar(250) NOT NULL,
 image varchar(255) NOT NULL  DEFAULT '',
 phone varchar(255) NOT NULL,
 fax varchar(255) NOT NULL,
 email varchar(100) NOT NULL,
 address varchar(255) NOT NULL,
 note text NOT NULL,
 others text NOT NULL,
 cats text NOT NULL,
 admins text NOT NULL,
 act tinyint(1) unsigned NOT NULL DEFAULT '0',
 weight smallint(5) NOT NULl,
 is_default tinyint(1) unsigned NOT NULL DEFAULT '0',
 PRIMARY KEY (id),
 UNIQUE KEY full_name (full_name),
 UNIQUE KEY alias (alias)
) ENGINE=MyISAM";

$sql_create_module[] = 'CREATE TABLE ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_send (
 id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
 cid smallint(5) unsigned NOT NULL DEFAULT '0',
 cat varchar(255) NOT NULL,
 title varchar(255) NOT NULL,
 content text NOT NULL,
 send_time int(11) unsigned NOT NULL DEFAULT '0',
 sender_id mediumint(8) unsigned NOT NULL DEFAULT '0',
 sender_name varchar(100) NOT NULL,
 sender_address varchar(250) NOT NULL,
 sender_email varchar(100) NOT NULL,
 sender_phone varchar(20) DEFAULT '',
 sender_ip varchar(39) NOT NULL DEFAULT '',
 is_read tinyint(1) unsigned NOT NULL DEFAULT '0',
 is_reply tinyint(1) unsigned NOT NULL DEFAULT '0',
 is_processed tinyint(1) unsigned NOT NULL DEFAULT '0',
 processed_by int(11) unsigned NOT NULL DEFAULT '0',
 processed_time int(11) unsigned NOT NULL DEFAULT '0',
 PRIMARY KEY (id),
 KEY sender_name (sender_name)
) ENGINE=MyISAM";

$sql_create_module[] = 'CREATE TABLE ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_reply (
 rid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
 id mediumint(8) unsigned NOT NULL DEFAULT '0',
 reply_content text,
 reply_time int(11) unsigned NOT NULL DEFAULT '0',
 reply_aid mediumint(8) unsigned NOT NULL DEFAULT '0',
 PRIMARY KEY (rid),
 KEY id (id)
) ENGINE=MyISAM";

$sql_create_module[] = 'CREATE TABLE ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_supporter (
 id smallint(5) unsigned NOT NULL AUTO_INCREMENT,
 departmentid smallint(5) unsigned NOT NULL,
 full_name varchar(255) NOT NULL,
 image varchar(255) NOT NULL  DEFAULT '',
 phone varchar(255) NOT NULL,
 email varchar(100) NOT NULL,
 others text NOT NULL,
 act tinyint(1) unsigned NOT NULL DEFAULT '1',
 weight smallint(5) NOT NULl,
 PRIMARY KEY (id)
) ENGINE=MyISAM";

// Cấu hình mặc định của module
$sql_create_module[] = 'INSERT INTO ' . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'bodytext', '')";
$sql_create_module[] = 'INSERT INTO ' . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'sendcopymode', '0')";
$sql_create_module[] = 'INSERT INTO ' . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'captcha_type', 'captcha')";
