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
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_cat;';
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_content;';

$sql_create_module = $sql_drop_module;

$sql_create_module[] = 'CREATE TABLE ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_cat (
  catid smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  title varchar(250) NOT NULL,
  alias varchar(250) NOT NULL DEFAULT '',
  description text,
  image varchar(255) DEFAULT '',
  weight smallint(5) unsigned NOT NULL DEFAULT '0',
  keywords text,
  add_time int(11) unsigned NOT NULL DEFAULT '0',
  edit_time int(11) unsigned NOT NULL DEFAULT '0',
  status smallint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (catid),
  UNIQUE KEY alias (alias)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

$sql_create_module[] = 'CREATE TABLE ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_content (
    id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
    catid smallint(5) unsigned NOT NULL DEFAULT '0',
    title varchar(250) NOT NULL,
    alias varchar(250) NOT NULL,
    image varchar(255) DEFAULT '',
    imagealt varchar(255) DEFAULT '',
    imageposition tinyint(1) unsigned NOT NULL DEFAULT '0',
    description text,
    bodytext mediumtext NOT NULL,
    keywords text,
    socialbutton tinyint(4) NOT NULL DEFAULT '0',
    activecomm varchar(255) DEFAULT '',
    layout_func varchar(100) DEFAULT '',
    weight smallint(4) NOT NULL DEFAULT '0',
    admin_id mediumint(8) unsigned NOT NULL DEFAULT '0',
    add_time int(11) NOT NULL DEFAULT '0',
    edit_time int(11) NOT NULL DEFAULT '0',
    status tinyint(1) unsigned NOT NULL DEFAULT '0',
    hitstotal mediumint(8) unsigned NOT NULL DEFAULT '0',
    hot_post tinyint(1) unsigned NOT NULL DEFAULT '0',
    schema_type varchar(20) NOT NULL DEFAULT 'article',
    schema_about varchar(50) NOT NULL DEFAULT 'Organization',
    PRIMARY KEY (id),
    UNIQUE KEY alias (alias)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES
    ('" . $lang . "', '" . $module_name . "', 'schema_type', 'article'),
    ('" . $lang . "', '" . $module_name . "', 'schema_about', 'organization'),
    ('" . $lang . "', '" . $module_name . "', 'viewtype', '0'),
    ('" . $lang . "', '" . $module_name . "', 'per_page', '20'),
    ('" . $lang . "', '" . $module_name . "', 'alias_lower', '1'),
    ('" . $lang . "', '" . $module_name . "', 'socialbutton', 'facebook,twitter'),
    ('" . $lang . "', '" . $module_name . "', 'facebookapi', ''),
    ('" . $lang . "', '" . $module_name . "', 'related_articles', '5'),
    ('" . $lang . "', '" . $module_name . "', 'news_first', '0'),
    ('" . $lang . "', '" . $module_name . "', 'copy_page', '0')
";
