<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

// Ten cac table cua CSDL dung chung cho he thong

$result = $db->query("SHOW TABLE STATUS LIKE '" . $db_config['prefix'] . "\_%'");
while ($item = $result->fetch()) {
    $sql_drop_table[] = 'DROP TABLE ' . $item['name'];
}

$sql_create_table[] = 'CREATE TABLE ' . $db_config['prefix'] . "_api_role (
  role_id SMALLINT(4) NOT NULL AUTO_INCREMENT,
  role_md5title CHAR(32) NOT NULL,
  role_type ENUM('private','public') NOT NULL DEFAULT 'private',
  role_object ENUM('admin','user') NOT NULL DEFAULT 'admin',
  role_title VARCHAR(250) NOT NULL DEFAULT '',
  role_description VARCHAR(250) NOT NULL DEFAULT '',
  role_data TEXT NOT NULL,
  log_period INT(11) NOT NULL DEFAULT 0,
  flood_rules TEXT NOT NULL,
  addtime INT(11) NOT NULL DEFAULT '0',
  edittime INT(11) NOT NULL DEFAULT '0',
  status TINYINT(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (role_id),
  UNIQUE KEY role_md5title (role_md5title)
) ENGINE=MyISAM";

$sql_create_table[] = 'CREATE TABLE ' . $db_config['prefix'] . "_api_role_credential (
  id INT(11) NOT NULL AUTO_INCREMENT,
  userid INT(11) UNSIGNED NOT NULL,
  role_id SMALLINT(4) UNSIGNED NOT NULL DEFAULT '0',
  access_count INT(11) UNSIGNED NOT NULL DEFAULT '0',
  last_access INT(11) UNSIGNED NOT NULL DEFAULT '0',
  addtime INT(11) UNSIGNED NOT NULL DEFAULT '0',
  endtime INT(11) UNSIGNED NOT NULL DEFAULT '0',
  quota INT(20) UNSIGNED NOT NULL DEFAULT '0',
  status TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
  PRIMARY KEY (id),
  UNIQUE KEY userid_role_id (userid, role_id)
) ENGINE=MyISAM";

$sql_create_table[] = 'CREATE TABLE ' . $db_config['prefix'] . "_api_role_logs (
  id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  role_id SMALLINT(4) NOT NULL DEFAULT '0',
  userid INT(11) NOT NULL DEFAULT '0',
  command CHAR(100) NOT NULL DEFAULT '',
  log_time INT(11) NOT NULL DEFAULT '0',
  log_ip CHAR(50) NOT NULL DEFAULT '',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$sql_create_table[] = 'CREATE TABLE ' . $db_config['prefix'] . "_api_user (
  id INT(11) NOT NULL AUTO_INCREMENT,
  userid INT(11) UNSIGNED NOT NULL,
  ident VARCHAR(50) NOT NULL DEFAULT '',
  secret VARCHAR(250) NOT NULL DEFAULT '',
  ips TEXT NOT NULL,
  method ENUM('none','password_verify','md5_verify') NOT NULL DEFAULT 'password_verify',
  addtime INT(11) NOT NULL DEFAULT '0',
  edittime INT(11) NOT NULL DEFAULT '0',
  last_access INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  UNIQUE KEY userid_method (userid, method),
  UNIQUE KEY ident (ident),
    UNIQUE KEY secret (secret)
) ENGINE=MyISAM";

$sql_create_table[] = 'CREATE TABLE ' . NV_AUTHORS_GLOBALTABLE . " (
  admin_id int(11) unsigned NOT NULL,
  editor varchar(100) DEFAULT '',
  lev tinyint(1) unsigned NOT NULL DEFAULT '0',
  files_level varchar(255) DEFAULT '',
  position varchar(255) NOT NULL,
  main_module varchar(50) NOT NULL DEFAULT 'siteinfo',
  admin_theme varchar(100) NOT NULL DEFAULT '',
  addtime int(11) NOT NULL DEFAULT '0',
  edittime int(11) NOT NULL DEFAULT '0',
  is_suspend tinyint(1) unsigned NOT NULL DEFAULT '0',
  susp_reason text,
  pre_check_num varchar(40) NOT NULL DEFAULT '',
  pre_last_login int(11) unsigned NOT NULL DEFAULT '0',
  pre_last_ip varchar(45) DEFAULT '',
  pre_last_agent varchar(255) DEFAULT '',
  check_num varchar(40) NOT NULL DEFAULT '',
  last_login int(11) unsigned NOT NULL DEFAULT '0',
  last_ip varchar(45) DEFAULT '',
  last_agent varchar(255) DEFAULT '',
  PRIMARY KEY (admin_id)
) ENGINE=MyISAM";

$sql_create_table[] = 'CREATE TABLE ' . NV_AUTHORS_GLOBALTABLE . "_config (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  keyname varchar(39) NOT NULL DEFAULT '',
  mask tinyint(4) NOT NULL DEFAULT '0',
  begintime int(11) unsigned NOT NULL DEFAULT '0',
  endtime int(11) unsigned NOT NULL DEFAULT '0',
  notice varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (id),
  UNIQUE KEY keyname (keyname)
) ENGINE=MyISAM";

$sql_create_table[] = 'CREATE TABLE ' . NV_AUTHORS_GLOBALTABLE . "_module (
  mid mediumint(8) NOT NULL AUTO_INCREMENT,
  module varchar(50) NOT NULL,
  lang_key varchar(50) NOT NULL DEFAULT '',
  weight mediumint(8) NOT NULL DEFAULT '0',
  act_1 tinyint(4) NOT NULL DEFAULT '0',
  act_2 tinyint(4) NOT NULL DEFAULT '1',
  act_3 tinyint(4) NOT NULL DEFAULT '1',
  checksum varchar(32) DEFAULT '',
  PRIMARY KEY (mid),
  UNIQUE KEY module (module)
) ENGINE=MyISAM";

$sql_create_table[] = 'CREATE TABLE ' . NV_AUTHORS_GLOBALTABLE . "_oauth (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  admin_id int(11) unsigned NOT NULL COMMENT 'ID của quản trị',
  oauth_server varchar(50) NOT NULL COMMENT 'Eg: facebook, google...',
  oauth_uid varchar(50) NOT NULL COMMENT 'ID duy nhất ứng với server',
  oauth_email varchar(50) NOT NULL COMMENT 'Email',
  oauth_id VARCHAR(50) NOT NULL DEFAULT '',
  addtime int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  UNIQUE KEY admin_id (admin_id, oauth_server, oauth_uid),
  KEY oauth_email (oauth_email)
) ENGINE=MyISAM COMMENT 'Bảng lưu xác thực 2 bước từ oauth của admin'";

$sql_create_table[] = 'CREATE TABLE ' . NV_CONFIG_GLOBALTABLE . " (
  lang varchar(3) NOT NULL DEFAULT 'sys',
  module varchar(50) NOT NULL DEFAULT 'global',
  config_name varchar(30) NOT NULL DEFAULT '',
  config_value text,
  UNIQUE KEY lang (lang,module,config_name)
) ENGINE=MyISAM";

$sql_create_table[] = 'CREATE TABLE ' . NV_CRONJOBS_GLOBALTABLE . " (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  start_time int(11) unsigned NOT NULL DEFAULT '0',
  inter_val int(11) unsigned NOT NULL DEFAULT '0',
  inter_val_type tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0: Lặp lại sau thời điểm bắt đầu thực tế, 1:lặp lại sau thời điểm bắt đầu trong CSDL',
  run_file varchar(255) NOT NULL,
  run_func varchar(255) NOT NULL,
  params varchar(255) DEFAULT NULL,
  del tinyint(1) unsigned NOT NULL DEFAULT '0',
  is_sys tinyint(1) unsigned NOT NULL DEFAULT '0',
  act tinyint(1) unsigned NOT NULL DEFAULT '0',
  last_time int(11) unsigned NOT NULL DEFAULT '0',
  last_result tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  KEY is_sys (is_sys)
) ENGINE=MyISAM";

$sql_create_table[] = 'CREATE TABLE ' . NV_LANGUAGE_GLOBALTABLE . " (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  idfile mediumint(8) unsigned NOT NULL DEFAULT '0',
  langtype varchar(50) NOT NULL DEFAULT 'lang_module',
  lang_key varchar(50) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY filelang (idfile,lang_key,langtype)
) ENGINE=MyISAM";

$sql_create_table[] = 'CREATE TABLE ' . NV_LANGUAGE_GLOBALTABLE . "_file (
  idfile mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  module varchar(50) NOT NULL,
  admin_file varchar(200) NOT NULL DEFAULT '0',
  langtype varchar(50) NOT NULL DEFAULT 'lang_module',
  PRIMARY KEY (idfile),
  UNIQUE KEY module (module,admin_file)
) ENGINE=MyISAM";

$sql_create_table[] = 'CREATE TABLE ' . NV_SESSIONS_GLOBALTABLE . " (
  session_id varchar(50) DEFAULT NULL,
  userid mediumint(8) unsigned NOT NULL DEFAULT '0',
  username varchar(100) NOT NULL,
  onl_time int(11) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY session_id (session_id),
  KEY onl_time (onl_time)
) ENGINE=MEMORY";

$sql_create_table[] = 'CREATE TABLE ' . NV_COOKIES_GLOBALTABLE . " (
  name varchar(50) NOT NULL DEFAULT '',
  value mediumtext NOT NULL,
  domain varchar(100) NOT NULL DEFAULT '',
  path varchar(100) NOT NULL DEFAULT '',
  expires int(11) NOT NULL DEFAULT '0',
  secure tinyint(1) NOT NULL DEFAULT '0',
  UNIQUE KEY cookiename (name, domain, path),
  KEY name (name)
) ENGINE=MyISAM";

$sql_create_table[] = 'CREATE TABLE ' . $db_config['prefix'] . "_setup_language (
  lang char(2) NOT NULL,
  setup tinyint(1) NOT NULL DEFAULT '0',
  weight smallint(4) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (lang)
) ENGINE=MyISAM";

$sql_create_table[] = 'CREATE TABLE ' . $db_config['prefix'] . "_setup_extensions (
  id int(11) NOT NULL DEFAULT '0',
  type varchar(10) NOT NULL DEFAULT 'other',
  title varchar(55) NOT NULL,
  is_sys tinyint(1) NOT NULL DEFAULT '0',
  is_virtual tinyint(1) NOT NULL DEFAULT '0',
  basename varchar(50) NOT NULL DEFAULT '',
  table_prefix varchar(55) NOT NULL DEFAULT '',
  version varchar(50) NOT NULL,
  addtime int(11) NOT NULL DEFAULT '0',
  author text NOT NULL,
  note varchar(255) DEFAULT '',
  UNIQUE KEY title (type, title),
  KEY id (id),
  KEY type (type)
) ENGINE=MyISAM";

$sql_create_table[] = 'CREATE TABLE ' . $db_config['prefix'] . "_extension_files (
  idfile mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  type varchar(10) NOT NULL DEFAULT 'other',
  title varchar(55) NOT NULL DEFAULT '',
  path varchar(255) NOT NULL DEFAULT '',
  lastmodified int(11) unsigned NOT NULL DEFAULT '0',
  duplicate smallint(4) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (idfile)
) ENGINE=MyISAM";

$sql_create_table[] = 'CREATE TABLE ' . $db_config['prefix'] . "_ips (
  id mediumint(8) NOT NULL AUTO_INCREMENT,
  type tinyint(4) unsigned NOT NULL DEFAULT '0',
  ip varchar(32) DEFAULT NULL,
  mask tinyint(4) unsigned NOT NULL DEFAULT '0',
  area tinyint(3) NOT NULL,
  begintime int(11) DEFAULT NULL,
  endtime int(11) DEFAULT NULL,
  notice varchar(255) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY ip (ip, type)
) ENGINE=MyISAM";

$sql_create_table[] = 'CREATE TABLE ' . $db_config['prefix'] . "_logs (
  id int(11) NOT NULL AUTO_INCREMENT,
  lang varchar(10) NOT NULL,
  module_name varchar(50) NOT NULL,
  name_key varchar(255) NOT NULL,
  note_action text NOT NULL,
  link_acess varchar(255) DEFAULT '',
  userid mediumint(8) unsigned NOT NULL,
  log_time int(11) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$sql_create_table[] = 'CREATE TABLE ' . $db_config['prefix'] . "_upload_dir (
  did mediumint(8) NOT NULL AUTO_INCREMENT,
  dirname varchar(250) DEFAULT NULL,
  time int(11) NOT NULL DEFAULT '0',
  total_size double unsigned NOT NULL DEFAULT '0' COMMENT 'Dung lượng thư mục',
  thumb_type tinyint(4) NOT NULL DEFAULT '0',
  thumb_width smallint(6) NOT NULL DEFAULT '0',
  thumb_height smallint(6) NOT NULL DEFAULT '0',
  thumb_quality tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (did),
  UNIQUE KEY name (dirname)
) ENGINE=MyISAM";

$sql_create_table[] = 'CREATE TABLE ' . $db_config['prefix'] . "_upload_file (
  name varchar(245) NOT NULL,
  ext varchar(10) NOT NULL DEFAULT '',
  type varchar(5) NOT NULL DEFAULT '',
  filesize double NOT NULL DEFAULT '0',
  src varchar(255) NOT NULL DEFAULT '',
  srcwidth int(11) NOT NULL DEFAULT '0',
  srcheight int(11) NOT NULL DEFAULT '0',
  sizes varchar(50) NOT NULL DEFAULT '',
  userid mediumint(8) unsigned NOT NULL DEFAULT '0',
  mtime int(11) NOT NULL DEFAULT '0',
  did int(11) NOT NULL DEFAULT '0',
  title varchar(245) NOT NULL DEFAULT '',
  alt varchar(255) NOT NULL DEFAULT '',
  UNIQUE KEY did (did,title),
  KEY userid (userid),
  KEY type (type)
) ENGINE=MyISAM";

$sql_create_table[] = 'CREATE TABLE ' . $db_config['prefix'] . "_plugins (
  pid MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
    plugin_lang VARCHAR(3) NOT NULL DEFAULT 'all' COMMENT 'Ngôn ngữ sử dụng, all là tất cả ngôn ngữ',
    plugin_file VARCHAR(50) NOT NULL COMMENT 'File PHP của plugin',
    plugin_area VARCHAR(50) NOT NULL DEFAULT '' COMMENT 'Tên hook, tự đặt, không nên có tên nào trùng nhau',
    plugin_module_name VARCHAR(50) NOT NULL DEFAULT '' COMMENT 'Tên module nhận và xử lý data',
    plugin_module_file VARCHAR(50) NOT NULL DEFAULT '' COMMENT 'Tên module chứa file plugin, rỗng thì nằm ở includes/plugin',
    hook_module VARCHAR(50) NOT NULL DEFAULT '' COMMENT 'Module xảy ra event, rỗng thì là của hệ thống',
    weight TINYINT(4) NOT NULL COMMENT 'Thứ tự trong cùng một hook, càng to càng ưu tiên',
    PRIMARY KEY (pid),
    UNIQUE KEY plugin (plugin_lang, plugin_file, plugin_area, plugin_module_name, hook_module)
) ENGINE=MyISAM AUTO_INCREMENT=1001";

$sql_create_table[] = 'CREATE TABLE ' . $db_config['prefix'] . "_counter (
   c_type varchar(100) NOT NULL,
   c_val varchar(100) NOT NULL,
   last_update int(11) NOT NULL DEFAULT '0',
   c_count int(11) unsigned NOT NULL DEFAULT '0',
   " . NV_LANG_DATA . "_count int(11) unsigned NOT NULL DEFAULT '0',
   UNIQUE KEY c_type (c_type,c_val)
) ENGINE=MyISAM";

$sql_create_table[] = 'CREATE TABLE ' . $db_config['prefix'] . "_notification (
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  admin_view_allowed tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Cấp quản trị được xem: 0,1,2',
  logic_mode tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0: Cấp trên xem được cấp dưới, 1: chỉ cấp hoặc người được chỉ định',
  send_to varchar(250) NOT NULL DEFAULT '' COMMENT 'Danh sách id người nhận, phân cách bởi dấu phảy',
  send_from mediumint(8) unsigned NOT NULL DEFAULT '0',
  area tinyint(1) unsigned NOT NULL,
  language char(3) NOT NULL,
  module varchar(50) NOT NULL,
  obid int(11) unsigned NOT NULL DEFAULT '0',
  type varchar(255) NOT NULL,
  content text NOT NULL,
  add_time int(11) unsigned NOT NULL,
  view tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  KEY send_to (send_to),
  KEY admin_view_allowed (admin_view_allowed),
  KEY logic_mode (logic_mode)
) ENGINE=MyISAM";

$sql_create_table[] = 'CREATE TABLE ' . $db_config['prefix'] . "_push (
  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  receiver_grs VARCHAR(1000) NOT NULL DEFAULT '',
  receiver_ids VARCHAR(1000) NOT NULL DEFAULT '',
  sender_role ENUM('system','group','admin') NOT NULL DEFAULT 'system',
  sender_group MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
  sender_admin MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
  message TEXT NULL DEFAULT NULL,
  link VARCHAR(500) NOT NULL DEFAULT '',
  add_time INT(11) UNSIGNED NOT NULL DEFAULT '0',
  exp_time INT(11) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$sql_create_table[] = 'CREATE TABLE ' . $db_config['prefix'] . "_push_status (
  pid INT(11) UNSIGNED NOT NULL,
  userid INT(11) UNSIGNED NOT NULL,
  shown_time INT(11) UNSIGNED NOT NULL DEFAULT '0',
  viewed_time INT(11) UNSIGNED NOT NULL DEFAULT '0',
  favorite_time INT(11) UNSIGNED NOT NULL DEFAULT '0',
  hidden_time INT(11) UNSIGNED NOT NULL DEFAULT '0',
  UNIQUE KEY pid_userid (pid, userid)
) ENGINE=MyISAM";
