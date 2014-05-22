<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

$sql_drop_module = array();

$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_department";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_send";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_reply";

$sql_create_module = $sql_drop_module;

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_department (
 id smallint(5) unsigned NOT NULL AUTO_INCREMENT,
 full_name varchar(255) NOT NULL,
 phone varchar(255) NOT NULL,
 fax varchar(255) NOT NULL,
 email varchar(100) NOT NULL,
 yahoo varchar(100) NOT NULL,
 skype varchar(100) NOT NULL,
 note text NOT NULL,
 admins text NOT NULL,
 act tinyint(1) unsigned NOT NULL DEFAULT '0',
 PRIMARY KEY (id),
 UNIQUE KEY full_name (full_name)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_send (
 id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
 cid smallint(5) unsigned NOT NULL DEFAULT '0',
 title varchar(255) NOT NULL,
 content text NOT NULL,
 send_time int(11) unsigned NOT NULL DEFAULT '0',
 sender_id mediumint(8) unsigned NOT NULL DEFAULT '0',
 sender_name varchar(100) NOT NULL,
 sender_email varchar(100) NOT NULL,
 sender_phone varchar(255) DEFAULT '',
 sender_ip varchar(15) NOT NULL,
 is_read tinyint(1) unsigned NOT NULL DEFAULT '0',
 is_reply tinyint(1) unsigned NOT NULL DEFAULT '0',
 PRIMARY KEY (id),
 KEY sender_name (sender_name)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_reply (
 rid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
 id mediumint(8) unsigned NOT NULL DEFAULT '0',
 reply_content text,
 reply_time int(11) unsigned NOT NULL DEFAULT '0',
 reply_aid mediumint(8) unsigned NOT NULL DEFAULT '0',
 PRIMARY KEY (rid),
 KEY id (id)
) ENGINE=MyISAM";

$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_department (full_name, phone, fax, email, yahoo, skype, note, admins, act) VALUES ('Webmaster', '', '', '', '', '', '', '1/1/1/0;', 1)";