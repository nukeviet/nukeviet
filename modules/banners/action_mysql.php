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

// Xoa cac block lien quan
$_sql = 'SELECT * FROM ' . $db_config['prefix'] . '_setup_language WHERE setup=1';
$_result = $db->query($_sql);
while ($_row = $_result->fetch()) {
    $bids = $db->query('SELECT GROUP_CONCAT(bid) FROM ' . $db_config['prefix'] . '_' . $_row['lang'] . "_blocks_groups WHERE module = 'banners'")->fetchColumn();
    if (!empty($bids)) {
        $sql_drop_module[] = 'DELETE FROM ' . $db_config['prefix'] . '_' . $_row['lang'] . '_blocks_weight WHERE bid IN (' . $bids . ')';
        $sql_drop_module[] = 'DELETE FROM ' . $db_config['prefix'] . '_' . $_row['lang'] . '_blocks_groups WHERE bid IN (' . $bids . ')';
    }
}

$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_banners_click;';
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_banners_plans;';
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_banners_rows;';

$sql_create_module = $sql_drop_module;

$sql_create_module[] = 'CREATE TABLE IF NOT EXISTS ' . $db_config['prefix'] . "_banners_click (
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  bid mediumint(8) NOT NULL DEFAULT '0',
  click_time int(11) unsigned NOT NULL DEFAULT '0',
  click_day int(2) NOT NULL,
  click_ip varchar(46) NOT NULL,
  click_country varchar(10) NOT NULL,
  click_browse_key varchar(100) NOT NULL,
  click_browse_name varchar(100) NOT NULL,
  click_os_key varchar(100) NOT NULL,
  click_os_name varchar(100) NOT NULL,
  click_ref varchar(255) NOT NULL,
  KEY bid (bid),
  KEY click_day (click_day),
  KEY click_ip (click_ip),
  KEY click_country (click_country),
  KEY click_browse_key (click_browse_key),
  KEY click_os_key (click_os_key),
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$sql_create_module[] = 'CREATE TABLE IF NOT EXISTS ' . $db_config['prefix'] . "_banners_plans (
  id smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  blang char(2) DEFAULT '',
  title varchar(250) NOT NULL,
  description text,
  form varchar(100) NOT NULL,
  width smallint(4) unsigned NOT NULL DEFAULT '0',
  height smallint(4) unsigned NOT NULL DEFAULT '0',
  act tinyint(1) unsigned NOT NULL DEFAULT '0',
  require_image tinyint(1) unsigned NOT NULL DEFAULT '1',
  uploadtype varchar(255) NOT NULL DEFAULT '',
  uploadgroup varchar(255) NOT NULL DEFAULT '',
  exp_time int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  KEY title (title)
) ENGINE=MyISAM";

$sql_create_module[] = 'CREATE TABLE IF NOT EXISTS ' . $db_config['prefix'] . "_banners_rows (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  title varchar(255) NOT NULL,
  pid smallint(5) unsigned NOT NULL DEFAULT '0',
  clid mediumint(8) unsigned NOT NULL DEFAULT '0',
  file_name varchar(255) NOT NULL,
  file_ext varchar(100) NOT NULL,
  file_mime varchar(100) NOT NULL,
  width int(4) unsigned NOT NULL DEFAULT '0',
  height int(4) unsigned NOT NULL DEFAULT '0',
  file_alt varchar(255) DEFAULT '',
  imageforswf varchar(255) DEFAULT '',
  click_url varchar(255) DEFAULT '',
  target varchar(10) NOT NULL DEFAULT '_blank',
  bannerhtml mediumtext NOT NULL,
  add_time int(11) unsigned NOT NULL DEFAULT '0',
  publ_time int(11) unsigned NOT NULL DEFAULT '0',
  exp_time int(11) unsigned NOT NULL DEFAULT '0',
  hits_total mediumint(8) unsigned NOT NULL DEFAULT '0',
  act tinyint(1) unsigned NOT NULL DEFAULT '0',
  weight int(11) NOT NULL default '0',
  PRIMARY KEY (id),
  KEY pid (pid),
  KEY clid (clid)
) ENGINE=MyISAM";

$sql_create_module[] = 'INSERT IGNORE INTO ' . $db_config['prefix'] . "_banners_plans (id, blang, title, description, form, width, height, act, require_image, uploadtype) VALUES (1, '', 'Mid-page ad block', '', 'sequential', 575, 72, 1, 1, 'images')";
$sql_create_module[] = 'INSERT IGNORE INTO ' . $db_config['prefix'] . "_banners_plans (id, blang, title, description, form, width, height, act, require_image, uploadtype) VALUES (2, '', 'Left-column ad block', '', 'sequential', 212, 800, 1, 1, 'images')";
$sql_create_module[] = 'INSERT IGNORE INTO ' . $db_config['prefix'] . "_banners_plans (id, blang, title, description, form, width, height, act, require_image, uploadtype) VALUES (3, '', 'Right-column ad block', '', 'random', 250, 500, 1, 1, 'images')";

$sql_create_module[] = 'INSERT IGNORE INTO ' . $db_config['prefix'] . "_banners_rows (id, title, pid, clid, file_name, file_ext, file_mime, width, height, file_alt, imageforswf, click_url, target, bannerhtml, add_time, publ_time, exp_time, hits_total, act, weight) VALUES (1, 'Mid-page advertisement', 1, 1, 'webnhanh.jpg', 'png', 'image/jpeg', 575, 72, '', '', 'http://webnhanh.vn', '_blank', '', " . NV_CURRENTTIME . ', ' . NV_CURRENTTIME . ', 0, 0, 1, 1)';
$sql_create_module[] = 'INSERT IGNORE INTO ' . $db_config['prefix'] . "_banners_rows (id, title, pid, clid, file_name, file_ext, file_mime, width, height, file_alt, imageforswf, click_url, target, bannerhtml, add_time, publ_time, exp_time, hits_total, act, weight) VALUES (2, 'Left-column advertisement', 2, 1, 'vinades.jpg', 'jpg', 'image/jpeg', 212, 400, '', '', 'http://vinades.vn', '_blank', '', " . NV_CURRENTTIME . ', ' . NV_CURRENTTIME . ', 0, 0, 1, 2)';

$sql_create_module[] = 'INSERT IGNORE INTO ' . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'banners', 'captcha_type', 'captcha')";
