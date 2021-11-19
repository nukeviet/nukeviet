<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 20:59
 */

if (! defined('NV_IS_FILE_MODULES')) {
    die('Stop!!!');
}

$sql_drop_module = array();

$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_songs';
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_singers';
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_cats';
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_game_max_results';
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_questions';
$sql_create_module = $sql_drop_module;

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_questions (
  id int(11) NOT NULL AUTO_INCREMENT,
  question varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  answer varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (id)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_songs (
    id int(11) NOT NULL AUTO_INCREMENT,
  song_name varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  path varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  singer_id int(11) NOT NULL,
  cat_id smallint(4) NOT NULL DEFAULT '0',
  add_time int(11) NOT NULL DEFAULT '0',
  update_time int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  KEY cat_id (cat_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_singers (
    id int(11) NOT NULL AUTO_INCREMENT,
  singer_name varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  add_time int(11) NOT NULL,
  update_time int(11) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_cats (
    id int(4) NOT NULL AUTO_INCREMENT,
  cat_name varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  add_time int(11) NOT NULL,
  update_time int(11) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_game_max_results (
  id int(11) NOT NULL AUTO_INCREMENT,
  username varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  diem int(11) NOT NULL,
  timeupdate varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
