<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

$sql_drop_module = [];
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_comment';

$sql_create_module = $sql_drop_module;
$sql_create_module[] = 'CREATE TABLE ' . $db_config['prefix'] . '_' . $lang . "_comment (
 cid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
 module varchar(55) NOT NULL,
 area int(11) NOT NULL DEFAULT '0',
 id mediumint(8) unsigned NOT NULL DEFAULT '0',
 pid mediumint(8) unsigned NOT NULL DEFAULT '0',
 content text NOT NULL,
 attach varchar(255) NOT NULL DEFAULT '',
 post_time int(11) unsigned NOT NULL DEFAULT '0',
 userid mediumint(8) unsigned NOT NULL DEFAULT '0',
 post_name varchar(100) NOT NULL,
 post_email varchar(100) NOT NULL,
 post_ip varchar(39) NOT NULL DEFAULT '',
 status tinyint(1) unsigned NOT NULL DEFAULT '0',
 likes mediumint(9) NOT NULL DEFAULT '0',
 dislikes mediumint(9) NOT NULL DEFAULT '0',
 PRIMARY KEY (cid),
 KEY mod_id (module,area,id),
 KEY post_time (post_time)
) ENGINE=MyISAM";

$sql_create_module[] = 'INSERT INTO ' . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', 'comment', 'captcha_type', 'captcha')";
