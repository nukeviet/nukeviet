<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 20:59
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

global $op, $db;

$sql_drop_module = array();
$result = $db->query( "SHOW TABLE STATUS LIKE '" . $db_config['prefix'] . "\_" . $module_data . "\_money\_%'" );
$num_table = intval( $result->rowCount() );
$array_lang_module_setup = array();
$set_lang_data = '';
if( $num_table > 0 )
{
	while( $item = $result->fetch() )
	{
		$array_lang_module_setup[] = str_replace( $db_config['prefix'] . "_" . $module_data . "_money_", "", $item['name'] );
	}
	if( $lang != $global_config['site_lang'] and in_array( $global_config['site_lang'], $array_lang_module_setup ) )
	{
		$set_lang_data = $global_config['site_lang'];
	}
	else
	{
		foreach( $array_lang_module_setup as $lang_i )
		{
			if( $lang != $lang_i )
			{
				$set_lang_data = $lang_i;
				break;
			}
		}
	}
}

$result = $db->query( "SHOW TABLE STATUS LIKE '" . $db_config['prefix'] . "\_" . $lang . "\_comment'" );
$rows = $result->fetchAll();
if( sizeof( $rows ) )
{
	$sql_drop_module[] = "DELETE FROM " . $db_config['prefix'] . "_" . $lang . "_comment WHERE module='" . $module_name . "'";
}

if( in_array( $lang, $array_lang_module_setup ) and $num_table > 1 )
{
	$sql_drop_module[] = 'ALTER TABLE ' . $db_config['prefix'] . '_' . $module_data . '_rows
	 DROP ' . $lang . '_title,
	 DROP ' . $lang . '_alias,
	 DROP ' . $lang . '_hometext,
	 DROP ' . $lang . '_bodytext,
	 DROP ' . $lang . '_gift_content,
	 DROP ' . $lang . '_address';

	$sql_drop_module[] = 'ALTER TABLE ' . $db_config['prefix'] . '_' . $module_data . '_catalogs
	 DROP ' . $lang . '_title,
	 DROP ' . $lang . '_alias,
	 DROP ' . $lang . '_description,
	 DROP ' . $lang . '_descriptionhtml,
	 DROP ' . $lang . '_keywords';

	$sql_drop_module[] = 'ALTER TABLE ' . $db_config['prefix'] . '_' . $module_data . '_group
	 DROP ' . $lang . '_title,
	 DROP ' . $lang . '_alias,
	 DROP ' . $lang . '_description,
	 DROP ' . $lang . '_keywords';

	$sql_drop_module[] = 'ALTER TABLE ' . $db_config['prefix'] . '_' . $module_data . '_block_cat
	 DROP ' . $lang . '_title,
	 DROP ' . $lang . '_alias,
	 DROP ' . $lang . '_description,
	 DROP ' . $lang . '_keywords';

	$sql_drop_module[] = 'ALTER TABLE ' . $db_config['prefix'] . '_' . $module_data . '_units
	 DROP ' . $lang . '_title,
	 DROP ' . $lang . '_note';

	$sql_drop_module[] = 'ALTER TABLE ' . $db_config['prefix'] . '_' . $module_data . '_files
	 DROP ' . $lang . '_title,
	 DROP ' . $lang . '_description';
}
elseif( $op != 'setup' )
{
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_block';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_field';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_template';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_info';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_block_cat';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_catalogs';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_group';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_group_cateid';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_group_items';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_group_quantity';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_group_quantity_logs';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_warehouse';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_warehouse_logs';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_warehouse_logs_group';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_orders';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_orders_id';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_orders_id_group';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_orders_shipping';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_payment';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_transaction';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_rows';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_review';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_units';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_discounts';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_coupons';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_coupons_product';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_coupons_history';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_point';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_point_queue';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_point_history';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_location';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_carrier';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_carrier_config';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_carrier_config_items';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_carrier_config_location';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_carrier_config_weight';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_carrier_location';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_carrier_weight';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_shops';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_shops_carrier';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_files';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_files_rows';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_tabs';
	$set_lang_data = '';
}

$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_money_' . $lang;
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_weight_' . $lang;
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_tags_' . $lang;
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_tags_id_' . $lang;


$sql_create_module = $sql_drop_module;

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_catalogs (
 catid mediumint(8) unsigned NOT NULL auto_increment,
 parentid mediumint(8) unsigned NOT NULL default '0',
 image varchar(255) NOT NULL default '',
 weight smallint(4) unsigned NOT NULL default '0',
 sort mediumint(8) NOT NULL default '0',
 lev smallint(4) NOT NULL default '0',
 viewcat varchar(50) NOT NULL default 'viewcat_page_new',
 numsubcat int(11) NOT NULL default '0',
 subcatid varchar(255) NOT NULL default '',
 inhome tinyint(1) unsigned NOT NULL default '0',
 numlinks tinyint(2) unsigned NOT NULL default '3',
 newday tinyint(4) NOT NULL DEFAULT '3',
 typeprice tinyint(4) NOT NULL DEFAULT '2',
 form varchar(50) NOT NULL DEFAULT '',
 group_price text NOT NULL,
 viewdescriptionhtml tinyint(1) unsigned NOT NULL default '0',
 admins mediumtext NOT NULL,
 add_time int(11) unsigned NOT NULL default '0',
 edit_time int(11) unsigned NOT NULL default '0',
 groups_view varchar(255) NOT NULL default '',
 cat_allow_point tinyint(1) NOT NULL default '0',
 cat_number_point tinyint(4) NOT NULL default '0',
 cat_number_product tinyint(4) NOT NULL default '0',
 PRIMARY KEY (catid),
 KEY parentid (parentid)
) ENGINE=MyISAM";

$sql_create_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_catalogs ADD " . $lang . "_title VARCHAR( 255 ) NOT NULL DEFAULT ''";
$sql_create_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_catalogs ADD " . $lang . "_alias VARCHAR( 255 ) NOT NULL DEFAULT ''";
$sql_create_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_catalogs ADD " . $lang . "_description VARCHAR( 255 ) NOT NULL DEFAULT ''";
$sql_create_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_catalogs ADD " . $lang . "_descriptionhtml TEXT NOT NULL";
$sql_create_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_catalogs ADD " . $lang . "_keywords text NOT NULL";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_template (
  id mediumint(8) NOT NULL AUTO_INCREMENT,
  status tinyint(1) NOT NULL DEFAULT '1',
  title varchar(255) NOT NULL default '',
  alias varchar(255) NOT NULL default '',
  PRIMARY KEY (id) ,
  UNIQUE KEY alias (alias)
) ENGINE=MyISAM ";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_info (
  id mediumint(8) NOT NULL AUTO_INCREMENT,
  shopid mediumint(8) unsigned NOT NULL default '0',
  status tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (id)
) ENGINE=MyISAM ";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_field (
  fid mediumint(8) NOT NULL AUTO_INCREMENT,
  field varchar(25) NOT NULL,
  listtemplate varchar(25) NOT NULL,
  tab varchar(255) NOT NULL DEFAULT '',
  weight int(10) unsigned NOT NULL DEFAULT '1',
  field_type enum('number','date','textbox','textarea','editor','select','radio','checkbox','multiselect') NOT NULL DEFAULT 'textbox',
  field_choices text NOT NULL,
  sql_choices text NOT NULL,
  match_type enum('none','alphanumeric','email','url','regex','callback') NOT NULL DEFAULT 'none',
  match_regex varchar(250) NOT NULL DEFAULT '',
  func_callback varchar(75) NOT NULL DEFAULT '',
  min_length int(11) NOT NULL DEFAULT '0',
  max_length bigint(20) unsigned NOT NULL DEFAULT '0',
  class varchar(25) NOT NULL DEFAULT '',
  language text NOT NULL,
  default_value varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (fid),
  UNIQUE KEY field (field)
) ENGINE=MyISAM ";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_group (
 groupid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
 parentid mediumint(8) unsigned NOT NULL DEFAULT '0',
 image varchar(255) NOT NULL DEFAULT '',
 weight smallint(4) unsigned NOT NULL DEFAULT '0',
 sort mediumint(8) NOT NULL DEFAULT '0',
 lev smallint(4) NOT NULL DEFAULT '0',
 viewgroup varchar(50) NOT NULL DEFAULT 'viewcat_page_new',
 numsubgroup int(11) NOT NULL DEFAULT '0',
 subgroupid varchar(255) NOT NULL DEFAULT '',
 inhome tinyint(1) unsigned NOT NULL DEFAULT '0',
 indetail tinyint(1) unsigned NOT NULL DEFAULT '0',
 add_time int(11) unsigned NOT NULL DEFAULT '0',
 edit_time int(11) unsigned NOT NULL DEFAULT '0',
 numpro int(11) unsigned NOT NULL DEFAULT '0',
 in_order tinyint(2) NOT NULL DEFAULT '0',
 is_require tinyint(1) NOT NULL DEFAULT '0',
 PRIMARY KEY (groupid),
 KEY parentid (parentid)
) ENGINE=MyISAM ";
$sql_create_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_group ADD " . $lang . "_title VARCHAR( 255 ) NOT NULL DEFAULT ''";
$sql_create_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_group ADD " . $lang . "_alias VARCHAR( 255 ) NOT NULL DEFAULT ''";
$sql_create_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_group ADD " . $lang . "_description VARCHAR( 255 ) NOT NULL DEFAULT ''";
$sql_create_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_group ADD " . $lang . "_keywords text NOT NULL";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_group_cateid (
  groupid mediumint(8) unsigned NOT NULL,
  cateid mediumint(8) unsigned NOT NULL,
  UNIQUE KEY groupid (groupid, cateid)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_group_items (
  pro_id int(11) unsigned NOT NULL default '0',
  group_id int(11) unsigned NOT NULL default '0',
  PRIMARY KEY (pro_id, group_id),
  KEY pro_id (pro_id),
  KEY group_id (group_id)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_group_quantity (
  pro_id int(11) unsigned NOT NULL default '0',
  listgroup varchar(255) NOT NULL,
  quantity int(11) unsigned NOT NULL,
  UNIQUE KEY pro_id (pro_id,listgroup)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_warehouse (
  wid int(11) unsigned NOT NULL AUTO_INCREMENT,
  title varchar(255) NOT NULL,
  note TEXT NOT NULL,
  user_id mediumint(8) NOT NULL DEFAULT '0',
  addtime int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (wid)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_warehouse_logs (
  logid int(11) unsigned NOT NULL AUTO_INCREMENT,
  wid int(11) unsigned NOT NULL default '0',
  pro_id int(11) unsigned NOT NULL default '0',
  quantity INT(11) UNSIGNED NOT NULL DEFAULT '0',
  price float NOT NULL DEFAULT '0',
  money_unit char(3) NOT NULL,
  PRIMARY KEY (logid),
  KEY wid (wid)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_warehouse_logs_group (
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  logid int(11) unsigned NOT NULL default '0',
  listgroup varchar(255)NOT NULL,
  quantity INT(11) UNSIGNED NOT NULL DEFAULT '0',
  price float NOT NULL DEFAULT '0',
  money_unit char(3) NOT NULL,
  PRIMARY KEY (id),
  KEY logid (logid)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_rows (
 id int(11) unsigned NOT NULL AUTO_INCREMENT,
 listcatid int(11) NOT NULL DEFAULT '0',
 user_id mediumint(8) NOT NULL DEFAULT '0',
 addtime int(11) unsigned NOT NULL DEFAULT '0',
 edittime int(11) unsigned NOT NULL DEFAULT '0',
 status tinyint(4) NOT NULL DEFAULT '1',
 publtime int(11) unsigned NOT NULL DEFAULT '0',
 exptime int(11) unsigned NOT NULL DEFAULT '0',
 archive tinyint(1) unsigned NOT NULL DEFAULT '0',
 product_code varchar(255) NOT NULL DEFAULT '',
 product_number int(11) NOT NULL DEFAULT '0',
 product_price float NOT NULL DEFAULT '0',
 price_config text NOT NULL,
 money_unit char(3) NOT NULL,
 product_unit smallint(4) NOT NULL,
 product_weight float NOT NULL DEFAULT '0',
 weight_unit char(20) NOT NULL DEFAULT '',
 discount_id smallint(6) NOT NULL DEFAULT '0',
 homeimgfile varchar(255) NOT NULL DEFAULT '',
 homeimgthumb tinyint(4) NOT NULL DEFAULT '0',
 homeimgalt varchar(255) NOT NULL,
 otherimage text NOT NULL,
 imgposition tinyint(1) NOT NULL DEFAULT '1',
 copyright tinyint(1) unsigned NOT NULL DEFAULT '0',
 gift_from int(11) unsigned NOT NULL DEFAULT '0',
 gift_to int(11) unsigned NOT NULL DEFAULT '0',
 inhome tinyint(1) unsigned NOT NULL DEFAULT '0',
 allowed_comm tinyint(1) unsigned NOT NULL DEFAULT '0',
 allowed_rating tinyint(1) unsigned NOT NULL DEFAULT '0',
 ratingdetail varchar(255) NOT NULL DEFAULT '',
 allowed_send tinyint(1) unsigned NOT NULL DEFAULT '0',
 allowed_print tinyint(1) unsigned NOT NULL DEFAULT '0',
 allowed_save tinyint(1) unsigned NOT NULL DEFAULT '0',
 hitstotal mediumint(8) unsigned NOT NULL DEFAULT '0',
 hitscm mediumint(8) unsigned NOT NULL DEFAULT '0',
 hitslm mediumint(8) unsigned NOT NULL DEFAULT '0',
 num_sell mediumint(8) NOT NULL DEFAULT '0',
 showprice tinyint(2) NOT NULL DEFAULT '0',
 PRIMARY KEY (id),
 KEY listcatid (listcatid),
 KEY user_id (user_id),
 KEY publtime (publtime),
 KEY exptime (exptime)
) ENGINE=MyISAM";

$sql_create_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_rows ADD " . $lang . "_title VARCHAR( 255 ) NOT NULL DEFAULT '',
 ADD " . $lang . "_alias VARCHAR( 255 ) NOT NULL DEFAULT '',
 ADD " . $lang . "_hometext text NOT NULL,
 ADD " . $lang . "_bodytext mediumtext NOT NULL,
 ADD " . $lang . "_gift_content text NOT NULL,
 ADD " . $lang . "_address text NOT NULL";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_review (
  review_id int(11) unsigned NOT NULL AUTO_INCREMENT,
  product_id int(11) NOT NULL DEFAULT '0',
  userid int(11) NOT NULL DEFAULT '0',
  sender varchar(255) NOT NULL,
  content text NOT NULL,
  rating int(1) NOT NULL,
  add_time int(11) NOT NULL DEFAULT '0',
  edit_time int(11) NOT NULL DEFAULT '0',
  status tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (review_id)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_block_cat (
 bid mediumint(8) unsigned NOT NULL auto_increment,
 adddefault tinyint(4) NOT NULL default '0',
 image varchar(255) NOT NULL,
 weight smallint(4) NOT NULL default '0',
 add_time int(11) NOT NULL default '0',
 edit_time int(11) NOT NULL default '0',
 PRIMARY KEY (bid)
) ENGINE=MyISAM";

$sql_create_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_block_cat ADD " . $lang . "_title VARCHAR( 255 ) NOT NULL DEFAULT '',
 ADD " . $lang . "_alias VARCHAR( 255 ) NOT NULL DEFAULT '',
 ADD " . $lang . "_description VARCHAR( 255 ) NOT NULL DEFAULT '',
 ADD " . $lang . "_keywords text NOT NULL";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_block (
 bid int(11) unsigned NOT NULL,
 id int(11) unsigned NOT NULL,
 weight int(11) unsigned NOT NULL,
 UNIQUE KEY bid (bid,id)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_units (
 id int(11) NOT NULL auto_increment,
 PRIMARY KEY (id)
) ENGINE=MyISAM";
$sql_create_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_units ADD " . $lang . "_title VARCHAR( 255 ) NOT NULL DEFAULT '',
 ADD " . $lang . "_note text NOT NULL ";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_orders (
 order_id int(11) unsigned NOT NULL auto_increment,
 order_code varchar(30) NOT NULL default '',
 lang char(2) NOT NULL default 'en',
 order_name varchar(255) NOT NULL,
 order_email varchar(255) NOT NULL,
 order_phone varchar(20) NOT NULL,
 order_note text NOT NULL,
 user_id int(11) unsigned NOT NULL default '0',
 admin_id int(11) unsigned NOT NULL default '0',
 shop_id int(11) unsigned NOT NULL default '0',
 who_is int(2) unsigned NOT NULL default '0',
 unit_total char(3) NOT NULL,
 order_total double unsigned NOT NULL default '0',
 order_time int(11) unsigned NOT NULL default '0',
 edit_time int(11) unsigned NOT NULL default '0',
 postip varchar(100) NOT NULL,
 order_view tinyint(2) NOT NULL DEFAULT '0',
 transaction_status tinyint(4) NOT NULL,
 transaction_id int(11) NOT NULL default '0',
 transaction_count int(11) NOT NULL,
 PRIMARY KEY (order_id),
 UNIQUE KEY order_code (order_code),
 KEY user_id (user_id),
 KEY order_time (order_time),
 KEY shop_id (shop_id)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_orders_id (
 id int(11) unsigned NOT NULL AUTO_INCREMENT,
 order_id int(11) NOT NULL,
 proid mediumint(9) NOT NULL,
 num mediumint(9) NOT NULL,
 price int(11) NOT NULL,
 discount_id smallint(6) NOT NULL DEFAULT '0',
 PRIMARY KEY (id),
 UNIQUE KEY orderid (order_id, id)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_orders_id_group (
 order_i int(11) NOT NULL,
 group_id mediumint(8) NOT NULL,
 UNIQUE KEY orderid (order_i, group_id)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_orders_shipping (
  id tinyint(11) unsigned NOT NULL AUTO_INCREMENT,
  order_id tinyint(11) unsigned NOT NULL,
  ship_name varchar(255) NOT NULL,
  ship_phone varchar(25) NOT NULL,
  ship_location_id mediumint(8) unsigned NOT NULL,
  ship_address_extend varchar(255) NOT NULL,
  ship_shops_id tinyint(3) unsigned NOT NULL,
  ship_carrier_id tinyint(3) unsigned NOT NULL,
  weight float NOT NULL DEFAULT '0',
  weight_unit char(20) NOT NULL DEFAULT '',
  ship_price float NOT NULL DEFAULT '0',
  ship_price_unit char(3) NOT NULL DEFAULT '',
  add_time int(11) unsigned NOT NULL,
  edit_time int(11) unsigned NOT NULL,
  PRIMARY KEY (id),
  KEY add_time (add_time)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_transaction (
 transaction_id int(11) NOT NULL AUTO_INCREMENT,
 transaction_time int(11) NOT NULL DEFAULT '0',
 transaction_status int(11) NOT NULL,
 order_id int(11) NOT NULL DEFAULT '0',
 userid int(11) NOT NULL DEFAULT '0',
 payment varchar(100) NOT NULL DEFAULT '0',
 payment_id varchar(22) NOT NULL DEFAULT '0',
 payment_time int(11) NOT NULL DEFAULT '0',
 payment_amount float NOT NULL DEFAULT '0',
 payment_data text NOT NULL,
 PRIMARY KEY (transaction_id),
 KEY order_id (order_id),
 KEY payment_id (payment_id)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_money_" . $lang . " (
 id mediumint(11) NOT NULL,
 code char(3) NOT NULL,
 currency varchar(255) NOT NULL,
 exchange float NOT NULL default '0',
 round varchar(10) NOT NULL,
 number_format varchar(5) NOT NULL DEFAULT ',||.',
 PRIMARY KEY (id),
 UNIQUE KEY code (code)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_weight_" . $lang . " (
 id tinyint(2) unsigned NOT NULL AUTO_INCREMENT,
 code char(20) NOT NULL,
 title varchar(50) NOT NULL,
 exchange float NOT NULL default '0',
 round varchar(10) NOT NULL,
 PRIMARY KEY (id),
 UNIQUE KEY code (code)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_payment (
 payment varchar(100) NOT NULL,
 paymentname varchar(255) NOT NULL,
 domain varchar(255) NOT NULL,
 active tinyint(4) NOT NULL default '0',
 weight int(11) NOT NULL default '0',
 config text NOT NULL,
 images_button varchar(255) NOT NULL,
 PRIMARY KEY (payment)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_discounts (
  did smallint(6) NOT NULL AUTO_INCREMENT,
  title varchar(100) NOT NULL DEFAULT '',
  weight smallint(6) NOT NULL DEFAULT '0',
  add_time int(11) unsigned NOT NULL DEFAULT '0',
  edit_time int(11) unsigned NOT NULL DEFAULT '0',
  begin_time int(11) unsigned NOT NULL DEFAULT '0',
  end_time int(11) unsigned NOT NULL DEFAULT '0',
  config text NOT NULL,
  detail tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (did),
  KEY begin_time (begin_time,end_time)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_wishlist (
  wid smallint(6) NOT NULL AUTO_INCREMENT,
  user_id int(11) unsigned NOT NULL default '0',
  listid text,
  PRIMARY KEY (wid)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_tags_" . $lang . " (
  tid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  numpro mediumint(8) NOT NULL DEFAULT '0',
  alias varchar(255) NOT NULL DEFAULT '',
  image varchar(255) DEFAULT '',
  description text,
  keywords varchar(255) DEFAULT '',
  PRIMARY KEY (tid),
  UNIQUE KEY alias (alias)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_tags_id_" . $lang . " (
  id int(11) NOT NULL,
  tid mediumint(9) NOT NULL,
  keyword varchar(65) NOT NULL,
  UNIQUE KEY sid (id,tid)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_coupons (
  id int(11) NOT NULL AUTO_INCREMENT,
  title varchar(100) NOT NULL DEFAULT '',
  code varchar(50) NOT NULL DEFAULT '',
  type varchar(1) NOT NULL DEFAULT 'p',
  discount float NOT NULL DEFAULT '0',
  total_amount float NOT NULL DEFAULT '0',
  date_start int(11) unsigned NOT NULL DEFAULT '0',
  date_end int(11) unsigned NOT NULL DEFAULT '0',
  uses_per_coupon int(11) unsigned NOT NULL DEFAULT '0',
  uses_per_coupon_count int(11) NOT NULL DEFAULT '0',
  date_added int(11) unsigned NOT NULL DEFAULT '0',
  status tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_coupons_history (
  id int(11) NOT NULL AUTO_INCREMENT,
  cid int(11) NOT NULL,
  order_id int(11) NOT NULL,
  amount float NOT NULL DEFAULT '0',
  date_added int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_coupons_product (
  cid int(11) unsigned NOT NULL,
  pid int(11) unsigned NOT NULL,
  UNIQUE KEY cid (cid,pid)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_point (
  userid int(11) NOT NULL DEFAULT '0',
  point_total int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (userid)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_point_queue (
  order_id int(11) NOT NULL,
  point mediumint(11) NOT NULL DEFAULT '0',
  status tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_point_history (
  id int(11) NOT NULL AUTO_INCREMENT,
  userid int(11) NOT NULL DEFAULT '0',
  order_id int(11) NOT NULL,
  point int(11) NOT NULL DEFAULT '0',
  time int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_location (
 id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
 parentid mediumint(8) unsigned NOT NULL DEFAULT '0',
 title varchar(255) NOT NULL,
 weight smallint(4) unsigned NOT NULL DEFAULT '0',
 sort mediumint(8) NOT NULL DEFAULT '0',
 lev smallint(4) NOT NULL DEFAULT '0',
 numsub int(11) NOT NULL DEFAULT '0',
 subid varchar(255) NOT NULL DEFAULT '',
 PRIMARY KEY (id),
 KEY parentid (parentid)
) ENGINE=MyISAM ";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_carrier (
  id tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  name varchar(255) NOT NULL,
  phone varchar(15) NOT NULL,
  address varchar(255) NOT NULL,
  logo varchar(255) NOT NULL,
  description text NOT NULL,
  weight tinyint(3) unsigned NOT NULL,
  status tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM ";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_carrier_config (
  id tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  title varchar(255) NOT NULL,
  description text NOT NULL,
  weight tinyint(3) unsigned NOT NULL,
  status tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (id)
) ENGINE=MyISAM ";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_carrier_config_items (
  id smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  cid tinyint(3) unsigned NOT NULL DEFAULT '0',
  title varchar(255) NOT NULL,
  description text NOT NULL,
  weight smallint(4) unsigned NOT NULL,
  add_time int(11) unsigned NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM ";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_carrier_config_location (
  cid tinyint(3) unsigned NOT NULL,
  iid smallint(4) unsigned NOT NULL,
  lid mediumint(8) unsigned NOT NULL,
  UNIQUE KEY cid( cid, lid)
) ENGINE=MyISAM ";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_carrier_config_weight (
  iid smallint(4) unsigned NOT NULL,
  weight float unsigned NOT NULL,
  weight_unit varchar(20) NOT NULL,
  carrier_price float NOT NULL,
  carrier_price_unit char(3) NOT NULL
) ENGINE=MyISAM ";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_shops (
  id tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  name varchar(255) NOT NULL,
  location mediumint(8) unsigned NOT NULL DEFAULT '0',
  address varchar(255) NOT NULL,
  description text NOT NULL,
  weight tinyint(3) unsigned NOT NULL,
  status tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM ";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_shops_carrier (
  shops_id tinyint(3) unsigned NOT NULL,
  carrier_id tinyint(3) unsigned NOT NULL,
  config_id tinyint(3) unsigned NOT NULL,
  UNIQUE KEY shops_id (shops_id, carrier_id)
) ENGINE=MyISAM ";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_files (
 id mediumint(8) unsigned NOT NULL auto_increment,
 path varchar(255) NOT NULL,
 filesize int(11) unsigned NOT NULL DEFAULT '0',
 extension varchar(10) NOT NULL DEFAULT '',
 addtime int(11) unsigned NOT NULL DEFAULT '0',
 download_groups varchar(255) NOT NULL DEFAULT '-1',
 status tinyint(1) unsigned DEFAULT '1',
 PRIMARY KEY (id)
) ENGINE=MyISAM";

$sql_create_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_files ADD " . $lang . "_title VARCHAR( 255 ) NOT NULL DEFAULT ''";
$sql_create_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_files ADD " . $lang . "_description MEDIUMTEXT NOT NULL DEFAULT ''";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_files_rows (
 id_rows int(11) unsigned NOT NULL,
 id_files mediumint(8) unsigned NOT NULL,
 download_hits mediumint(8) unsigned NOT NULL DEFAULT '0',
 UNIQUE KEY id_files (id_files, id_rows)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_tabs (
  id int(3) unsigned NOT NULL AUTO_INCREMENT,
  icon varchar(50) NOT NULL DEFAULT '',
  content varchar(50) NOT NULL DEFAULT '',
  weight int(10) unsigned NOT NULL DEFAULT '1',
  active tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$sql_create_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_tabs ADD " . $lang . "_title VARCHAR( 255 ) NOT NULL DEFAULT ''";

$data = array();
$data['image_size'] = '100x100';
$data['home_view'] = 'view_home_all';
$data['per_page'] = 20;
$data['per_row'] = 3;
$data['money_unit'] = 'VND';
$data['weight_unit'] = 'g';
$data['post_auto_member'] = 0;
$data['auto_check_order'] = 1;
$data['format_order_id'] = strtoupper( substr( $module_name, 0, 1 ) ) . '%06s';
$data['format_code_id'] = strtoupper( substr( $module_name, 0, 1 ) ) . '%06s';
$data['facebookappid'] = '';
$data['active_guest_order'] = 0;
$data['active_showhomtext'] = 1;
$data['active_order'] = 1;
$data['active_order_popup'] = 1;
$data['active_order_non_detail'] = 1;
$data['active_guest_order'] = 1;
$data['active_price'] = 1;
$data['active_order_number'] = 0;
$data['order_day'] = 0;
$data['order_nexttime'] = 0;
$data['active_payment'] = 1;
$data['groups_price'] = '3';
$data['active_tooltip'] = 1;
$data['timecheckstatus'] = 0;
$data['show_product_code'] = 1;
$data['show_compare'] = 0;
$data['show_displays'] = 0;
$data['use_shipping'] = 0;
$data['use_coupons'] = 0;
$data['active_wishlist'] = 1;
$data['active_gift'] = 1;
$data['active_warehouse'] = 1;
$data['tags_alias'] = 0;
$data['auto_tags'] = 1;
$data['tags_remind'] = 0;
$data['point_active'] = 0;
$data['point_conversion'] = 0;
$data['point_new_order'] = 0;
$data['review_active'] = 1;
$data['review_check'] = 1;
$data['review_captcha'] = 1;
$data['group_price'] = '';
$data['groups_notify'] = '3';
$data['template_active'] = '0';
$data['download_active'] = '0';
$data['download_groups'] = '6';

foreach( $data as $config_name => $config_value )
{
	$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', " . $db->quote( $module_name ) . ", " . $db->quote( $config_name ) . ", " . $db->quote( $config_value ) . ")";
}

if( ! empty( $set_lang_data ) )
{
	$numrow = $db->query( "SELECT count(*) FROM " . $db_config['prefix'] . "_" . $module_data . "_catalogs" )->fetchColumn();
	if( $numrow )
	{
		$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_catalogs SET " . $lang . "_title = " . $global_config['site_lang'] . "_title";
		$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_catalogs SET " . $lang . "_alias = " . $set_lang_data . "_alias";
		$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_catalogs SET " . $lang . "_description = " . $set_lang_data . "_description";
		$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_catalogs SET " . $lang . "_description = " . $set_lang_data . "_descriptionhtml";
		$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_catalogs SET " . $lang . "_keywords = " . $set_lang_data . "_keywords";
	}

	$numrow = $db->query( "SELECT count(*) FROM " . $db_config['prefix'] . "_" . $module_data . "_rows" )->fetchColumn();
	if( $numrow )
	{
		$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_rows SET " . $lang . "_title = " . $set_lang_data . "_title";
		$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_rows SET " . $lang . "_alias = " . $set_lang_data . "_alias";
		$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_rows SET " . $lang . "_hometext = " . $set_lang_data . "_hometext";
		$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_rows SET " . $lang . "_bodytext = " . $set_lang_data . "_bodytext";
		$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_rows SET " . $lang . "_gift_content = " . $set_lang_data . "_gift_content";
	}

	$numrow = $db->query( "SELECT count(*) FROM " . $db_config['prefix'] . "_" . $module_data . "_units" )->fetchColumn();
	if( $numrow )
	{
		$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_units SET " . $lang . "_title = " . $set_lang_data . "_title";
		$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_units SET " . $lang . "_note = " . $set_lang_data . "_note";
	}

	$numrow = $db->query( "SELECT count(*) FROM " . $db_config['prefix'] . "_" . $module_data . "_block_cat" )->fetchColumn();
	if( $numrow )
	{
		$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_block_cat SET " . $lang . "_title = " . $set_lang_data . "_title";
		$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_block_cat SET " . $lang . "_alias = " . $set_lang_data . "_alias";
		$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_block_cat SET " . $lang . "_description = " . $set_lang_data . "_description";
		$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_block_cat SET " . $lang . "_keywords = " . $set_lang_data . "_keywords";
	}

	$numrow = $db->query( "SELECT count(*) FROM " . $db_config['prefix'] . "_" . $module_data . "_group" )->fetchColumn();
	if( $numrow )
	{
		$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_group SET " . $lang . "_title = " . $set_lang_data . "_title";
		$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_group SET " . $lang . "_alias = " . $set_lang_data . "_alias";
		$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_group SET " . $lang . "_description = " . $set_lang_data . "_description";
		$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_group SET " . $lang . "_keywords = " . $set_lang_data . "_keywords";
	}

	$numrow = $db->query( "SELECT count(*) FROM " . $db_config['prefix'] . "_" . $module_data . "_block_cat" )->fetchColumn();
	if( $numrow )
	{
		//$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_money_" . $lang . " SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_money_" . $set_lang_data;
		//$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_weight_" . $lang . " SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_weight_" . $set_lang_data;
	}

	$numrow = $db->query( "SELECT count(*) FROM " . $db_config['prefix'] . "_" . $module_data . "_files" )->fetchColumn();
	if( $numrow )
	{
		$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_files SET " . $lang . "_title = " . $set_lang_data . "_title";
		$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_files SET " . $lang . "_description = " . $set_lang_data . "_description";
	}

	$numrow = $db->query( "SELECT count(*) FROM " . $db_config['prefix'] . "_" . $module_data . "_tabs" )->fetchColumn();
	if( $numrow )
	{
		$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_tabs SET " . $lang . "_title = " . $set_lang_data . "_title";
	}

	$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_money_" . $lang . " SET exchange = '1'";
	$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_weight_" . $lang . " SET exchange = '1'";
}

$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_money_" . $lang . " (id, code, currency, exchange, round, number_format) VALUES (840, 'USD', 'US Dollar', 21000, '0.01', ',||.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_money_" . $lang . " (id, code, currency, exchange, round, number_format) VALUES (704, 'VND', 'Vietnam Dong', 1, '100', ',||.')";

$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_weight_" . $lang . " (code, title, exchange, round) VALUES ('g', 'Gram', 1, '0.1')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_weight_" . $lang . " (code, title, exchange, round) VALUES ('kg', 'Kilogam', 1000, '0.1')";

$sql_create_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_catalogs ADD UNIQUE (" . $lang . "_alias)";
$sql_create_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_group ADD UNIQUE (" . $lang . "_alias)";
$sql_create_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_block_cat ADD UNIQUE (" . $lang . "_alias)";

// Comments
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'auto_postcomm', '1')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'allowed_comm', '-1')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'view_comm', '6')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'setcomm', '4')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'activecomm', '1')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'emailcomm', '0')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'adminscomm', '')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'sortcomm', '0')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'captcha', '1')";

$_sql = "SELECT table_name FROM information_schema.tables WHERE table_schema=" . $db->quote( $db_config['dbname'] ) . " AND table_name=" . $db->quote( $db_config['prefix'] . "_" . $module_data . "_catalogs" );
$row = $db->query( $_sql )->fetch();
if( empty( $row ) and $lang == 'vi' )
{
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_block (bid, id, weight) VALUES('1', '11', '5');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_block (bid, id, weight) VALUES('1', '10', '4');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_block (bid, id, weight) VALUES('1', '9', '3');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_block (bid, id, weight) VALUES('1', '6', '2');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_block (bid, id, weight) VALUES('1', '5', '1');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_block (bid, id, weight) VALUES('2', '4', '4');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_block (bid, id, weight) VALUES('2', '3', '3');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_block (bid, id, weight) VALUES('2', '2', '2');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_block (bid, id, weight) VALUES('2', '1', '1');";

	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_block_cat (bid, adddefault, image, weight, add_time, edit_time, vi_title, vi_alias, vi_description, vi_keywords) VALUES('1', '0', '', '1', '1433298294', '1433298294', 'Sản phẩm bán chạy', 'San-pham-ban-chay', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_block_cat (bid, adddefault, image, weight, add_time, edit_time, vi_title, vi_alias, vi_description, vi_keywords) VALUES('2', '0', '', '2', '1433298325', '1433298325', 'Sản phẩm hot', 'San-pham-hot', '', '');";

	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_catalogs (catid, parentid, image, weight, sort, lev, viewcat, numsubcat, subcatid, inhome, numlinks, newday, typeprice, form, group_price, viewdescriptionhtml, admins, add_time, edit_time, groups_view, cat_allow_point, cat_number_point, cat_number_product, vi_title, vi_alias, vi_description, vi_descriptionhtml, vi_keywords) VALUES('2', '0', '', '1', '1', '0', 'viewcat_page_list', '4', '6,7,8,9', '1', '4', '7', '1', '', '', '0', '', '1432362728', '1432362803', '6', '0', '0', '0', 'Váy', 'Vay-nu', '', '', 'váy, vay');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_catalogs (catid, parentid, image, weight, sort, lev, viewcat, numsubcat, subcatid, inhome, numlinks, newday, typeprice, form, group_price, viewdescriptionhtml, admins, add_time, edit_time, groups_view, cat_allow_point, cat_number_point, cat_number_product, vi_title, vi_alias, vi_description, vi_descriptionhtml, vi_keywords) VALUES('3', '0', '', '2', '6', '0', 'viewcat_page_list', '4', '13,14,15,16', '1', '4', '7', '1', '', '', '0', '', '1432362789', '1432362789', '6', '0', '0', '0', 'Giày dép', 'Giay-dep', '', '', 'giay, dep, giày, dép');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_catalogs (catid, parentid, image, weight, sort, lev, viewcat, numsubcat, subcatid, inhome, numlinks, newday, typeprice, form, group_price, viewdescriptionhtml, admins, add_time, edit_time, groups_view, cat_allow_point, cat_number_point, cat_number_product, vi_title, vi_alias, vi_description, vi_descriptionhtml, vi_keywords) VALUES('4', '0', '', '3', '11', '0', 'viewcat_page_list', '3', '10,11,12', '1', '4', '7', '1', '', '', '0', '', '1432362835', '1432364806', '6', '0', '0', '0', 'Áo', 'Ao', '', '', 'áo, ao');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_catalogs (catid, parentid, image, weight, sort, lev, viewcat, numsubcat, subcatid, inhome, numlinks, newday, typeprice, form, group_price, viewdescriptionhtml, admins, add_time, edit_time, groups_view, cat_allow_point, cat_number_point, cat_number_product, vi_title, vi_alias, vi_description, vi_descriptionhtml, vi_keywords) VALUES('5', '0', '', '4', '15', '0', 'viewcat_page_list', '5', '18,19,20,21,22', '1', '4', '7', '1', '', '', '0', '', '1432362887', '1432362887', '6', '0', '0', '0', 'Phụ kiện', 'Phu-kien', '', '', 'Phụ kiện, Phu kien, kiện, kien, phu kien');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_catalogs (catid, parentid, image, weight, sort, lev, viewcat, numsubcat, subcatid, inhome, numlinks, newday, typeprice, form, group_price, viewdescriptionhtml, admins, add_time, edit_time, groups_view, cat_allow_point, cat_number_point, cat_number_product, vi_title, vi_alias, vi_description, vi_descriptionhtml, vi_keywords) VALUES('6', '2', '', '1', '2', '1', 'viewcat_page_list', '0', '', '1', '4', '7', '1', '', '', '0', '', '1432364675', '1432364675', '6', '0', '0', '0', 'váy dài', 'vay-dai', '', '', 'váy dài, dài, vay dai');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_catalogs (catid, parentid, image, weight, sort, lev, viewcat, numsubcat, subcatid, inhome, numlinks, newday, typeprice, form, group_price, viewdescriptionhtml, admins, add_time, edit_time, groups_view, cat_allow_point, cat_number_point, cat_number_product, vi_title, vi_alias, vi_description, vi_descriptionhtml, vi_keywords) VALUES('7', '2', '', '2', '3', '1', 'viewcat_page_list', '0', '', '1', '4', '7', '1', '', '', '0', '', '1432364695', '1432364695', '6', '0', '0', '0', 'váy ngắn', 'vay-ngan', '', '', 'váy ngắn, vay ngan');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_catalogs (catid, parentid, image, weight, sort, lev, viewcat, numsubcat, subcatid, inhome, numlinks, newday, typeprice, form, group_price, viewdescriptionhtml, admins, add_time, edit_time, groups_view, cat_allow_point, cat_number_point, cat_number_product, vi_title, vi_alias, vi_description, vi_descriptionhtml, vi_keywords) VALUES('8', '2', '', '3', '4', '1', 'viewcat_page_list', '0', '', '1', '4', '7', '1', '', '', '0', '', '1432364752', '1432364752', '6', '0', '0', '0', 'đầm maxi', 'dam-maxi', '', '', 'đầm, maxi, Maxi, đầm maxi, Đầm maxi');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_catalogs (catid, parentid, image, weight, sort, lev, viewcat, numsubcat, subcatid, inhome, numlinks, newday, typeprice, form, group_price, viewdescriptionhtml, admins, add_time, edit_time, groups_view, cat_allow_point, cat_number_point, cat_number_product, vi_title, vi_alias, vi_description, vi_descriptionhtml, vi_keywords) VALUES('9', '2', '', '4', '5', '1', 'viewcat_page_list', '0', '', '1', '4', '7', '1', '', '', '0', '', '1432364786', '1432364786', '6', '0', '0', '0', 'Váy chữ A', 'Vay-chu-A', '', '', 'Váy chữ A, váy chữ a');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_catalogs (catid, parentid, image, weight, sort, lev, viewcat, numsubcat, subcatid, inhome, numlinks, newday, typeprice, form, group_price, viewdescriptionhtml, admins, add_time, edit_time, groups_view, cat_allow_point, cat_number_point, cat_number_product, vi_title, vi_alias, vi_description, vi_descriptionhtml, vi_keywords) VALUES('10', '4', '', '1', '12', '1', 'viewcat_page_list', '0', '', '1', '4', '7', '1', '', '', '0', '', '1432364825', '1432364863', '6', '0', '0', '0', 'Áo sơmi', 'Ao-somi', '', '', 'Áo sơmi,, sơmi. áo');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_catalogs (catid, parentid, image, weight, sort, lev, viewcat, numsubcat, subcatid, inhome, numlinks, newday, typeprice, form, group_price, viewdescriptionhtml, admins, add_time, edit_time, groups_view, cat_allow_point, cat_number_point, cat_number_product, vi_title, vi_alias, vi_description, vi_descriptionhtml, vi_keywords) VALUES('11', '4', '', '2', '13', '1', 'viewcat_page_list', '0', '', '1', '4', '7', '1', '', '', '0', '', '1432364880', '1432364880', '6', '0', '0', '0', 'Áo phông', 'Ao-phong', '', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_catalogs (catid, parentid, image, weight, sort, lev, viewcat, numsubcat, subcatid, inhome, numlinks, newday, typeprice, form, group_price, viewdescriptionhtml, admins, add_time, edit_time, groups_view, cat_allow_point, cat_number_point, cat_number_product, vi_title, vi_alias, vi_description, vi_descriptionhtml, vi_keywords) VALUES('12', '4', '', '3', '14', '1', 'viewcat_page_list', '0', '', '1', '4', '7', '1', '', '', '0', '', '1432364936', '1432364936', '6', '0', '0', '0', 'Áo dáng dài', 'Ao-dang-dai', '', '', 'Áo dáng dài, áo');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_catalogs (catid, parentid, image, weight, sort, lev, viewcat, numsubcat, subcatid, inhome, numlinks, newday, typeprice, form, group_price, viewdescriptionhtml, admins, add_time, edit_time, groups_view, cat_allow_point, cat_number_point, cat_number_product, vi_title, vi_alias, vi_description, vi_descriptionhtml, vi_keywords) VALUES('13', '3', '', '1', '7', '1', 'viewcat_page_list', '0', '', '1', '4', '7', '1', '', '', '0', '', '1432364976', '1432364976', '6', '0', '0', '0', 'Giày cao gót', 'Giay-cao-got', '', '', 'Giày cao gót, cao gót, cao got');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_catalogs (catid, parentid, image, weight, sort, lev, viewcat, numsubcat, subcatid, inhome, numlinks, newday, typeprice, form, group_price, viewdescriptionhtml, admins, add_time, edit_time, groups_view, cat_allow_point, cat_number_point, cat_number_product, vi_title, vi_alias, vi_description, vi_descriptionhtml, vi_keywords) VALUES('14', '3', '', '2', '8', '1', 'viewcat_page_list', '0', '', '1', '4', '7', '1', '', '', '0', '', '1432365033', '1432365033', '6', '0', '0', '0', 'Giày sandal', 'Giay-sandal', '', '', 'sandal, Sandal, giày, giày sandal');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_catalogs (catid, parentid, image, weight, sort, lev, viewcat, numsubcat, subcatid, inhome, numlinks, newday, typeprice, form, group_price, viewdescriptionhtml, admins, add_time, edit_time, groups_view, cat_allow_point, cat_number_point, cat_number_product, vi_title, vi_alias, vi_description, vi_descriptionhtml, vi_keywords) VALUES('15', '3', '', '3', '9', '1', 'viewcat_page_list', '0', '', '1', '4', '7', '1', '', '', '0', '', '1432365081', '1432365081', '6', '0', '0', '0', 'Giày búp bê', 'Giay-bup-be', '', '', 'giày búp bê, Giày búp bê, giay bup be, Giay bup be, búp bê, bup be');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_catalogs (catid, parentid, image, weight, sort, lev, viewcat, numsubcat, subcatid, inhome, numlinks, newday, typeprice, form, group_price, viewdescriptionhtml, admins, add_time, edit_time, groups_view, cat_allow_point, cat_number_point, cat_number_product, vi_title, vi_alias, vi_description, vi_descriptionhtml, vi_keywords) VALUES('16', '3', '', '4', '10', '1', 'viewcat_page_list', '0', '', '1', '4', '7', '1', '', '', '0', '', '1432365108', '1432365108', '6', '0', '0', '0', 'Giày vải', 'Giay-vai', '', '', 'vải, giày vải, giay vai');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_catalogs (catid, parentid, image, weight, sort, lev, viewcat, numsubcat, subcatid, inhome, numlinks, newday, typeprice, form, group_price, viewdescriptionhtml, admins, add_time, edit_time, groups_view, cat_allow_point, cat_number_point, cat_number_product, vi_title, vi_alias, vi_description, vi_descriptionhtml, vi_keywords) VALUES('19', '5', '', '2', '17', '1', 'viewcat_page_list', '0', '', '1', '4', '7', '1', '', '', '0', '', '1432365211', '1432365211', '6', '0', '0', '0', 'Lắc tay', 'Lac-tay', '', '', 'Lắc tay. lac tay, lắc');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_catalogs (catid, parentid, image, weight, sort, lev, viewcat, numsubcat, subcatid, inhome, numlinks, newday, typeprice, form, group_price, viewdescriptionhtml, admins, add_time, edit_time, groups_view, cat_allow_point, cat_number_point, cat_number_product, vi_title, vi_alias, vi_description, vi_descriptionhtml, vi_keywords) VALUES('18', '5', '', '1', '16', '1', 'viewcat_page_list', '0', '', '1', '4', '7', '1', '', '', '0', '', '1432365185', '1432365185', '6', '0', '0', '0', 'Vòng cổ', 'Phu-kien-Vong-co', '', '', 'vòng cổ, Vòng cổ, vong co');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_catalogs (catid, parentid, image, weight, sort, lev, viewcat, numsubcat, subcatid, inhome, numlinks, newday, typeprice, form, group_price, viewdescriptionhtml, admins, add_time, edit_time, groups_view, cat_allow_point, cat_number_point, cat_number_product, vi_title, vi_alias, vi_description, vi_descriptionhtml, vi_keywords) VALUES('20', '5', '', '3', '18', '1', 'viewcat_page_list', '0', '', '1', '4', '7', '1', '', '', '0', '', '1432365242', '1432365242', '6', '0', '0', '0', 'Thắt lưng', 'That-lung', '', '', 'Thắt lưng, that lung, thắt lưng');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_catalogs (catid, parentid, image, weight, sort, lev, viewcat, numsubcat, subcatid, inhome, numlinks, newday, typeprice, form, group_price, viewdescriptionhtml, admins, add_time, edit_time, groups_view, cat_allow_point, cat_number_point, cat_number_product, vi_title, vi_alias, vi_description, vi_descriptionhtml, vi_keywords) VALUES('21', '5', '', '4', '19', '1', 'viewcat_page_list', '0', '', '1', '4', '7', '1', '', '', '0', '', '1432365281', '1432365281', '6', '0', '0', '0', 'Đồng hồ', 'Dong-ho', '', '', 'Đồng hồ, hồ, dong ho');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_catalogs (catid, parentid, image, weight, sort, lev, viewcat, numsubcat, subcatid, inhome, numlinks, newday, typeprice, form, group_price, viewdescriptionhtml, admins, add_time, edit_time, groups_view, cat_allow_point, cat_number_point, cat_number_product, vi_title, vi_alias, vi_description, vi_descriptionhtml, vi_keywords) VALUES('22', '5', '', '5', '20', '1', 'viewcat_page_list', '0', '', '1', '4', '7', '1', '', '', '0', '', '1432365303', '1432365303', '6', '0', '0', '0', 'Ví nữ', 'Vi-nu', '', '', 'ví nữ, ví');";

	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_rows (id, listcatid, user_id, addtime, edittime, status, publtime, exptime, archive, product_code, product_number, product_price, price_config, money_unit, product_unit, product_weight, weight_unit, discount_id, homeimgfile, homeimgthumb, homeimgalt, otherimage, imgposition, copyright, gift_from, gift_to, inhome, allowed_comm, allowed_rating, ratingdetail, allowed_send, allowed_print, allowed_save, hitstotal, hitscm, hitslm, num_sell, showprice, vi_title, vi_alias, vi_hometext, vi_bodytext, vi_gift_content, vi_address) VALUES('1', '6', '1', '1432363521', '1432365563', '1', '1432363521', '0', '2', 'V01', '19', '100000', '', 'VND', '1', '20', 'g', '0', '2015_05/giay-bup-be-ngoi-sao-nhap-khau.jpg', '1', 'Váy Maxi sang trọng', '', '0', '0', '0', '0', '1', '4', '1', '0', '1', '1', '1', '11', '0', '0', '1', '1', 'Đầm Maxi sang trọng', 'Dam-Maxi-sang-trong', 'Đầm maxi thời trang', 'Sản phẩm thời trang<br  /> <div style=\"text-align:center\"><img alt=\"giay bup be ngoi sao nhap khau\" height=\"800\" src=\"/uploads/shops/2015_05/giay-bup-be-ngoi-sao-nhap-khau.jpg\" width=\"800\" /></div> ', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_rows (id, listcatid, user_id, addtime, edittime, status, publtime, exptime, archive, product_code, product_number, product_price, price_config, money_unit, product_unit, product_weight, weight_unit, discount_id, homeimgfile, homeimgthumb, homeimgalt, otherimage, imgposition, copyright, gift_from, gift_to, inhome, allowed_comm, allowed_rating, ratingdetail, allowed_send, allowed_print, allowed_save, hitstotal, hitscm, hitslm, num_sell, showprice, vi_title, vi_alias, vi_hometext, vi_bodytext, vi_gift_content, vi_address) VALUES('2', '6', '1', '1432365534', '1432365970', '1', '1432365534', '0', '2', 'V02', '50', '100000', '', 'VND', '1', '250', 'g', '0', '2015_05/giay-bup-be-ngoi-sao-nhap-khau.jpg', '1', '', '', '0', '0', '0', '0', '1', '4', '1', '0', '1', '1', '1', '1', '0', '0', '0', '1', 'Đầm maxi họa tiết', 'Dam-maxi-hoa-tiet', 'đầm maxi sang trọng', 'Sản phẩm thời trang<br  /> <div style=\"text-align:center\"><img alt=\"giay bup be ngoi sao nhap khau\" height=\"800\" src=\"/uploads/shops/2015_05/giay-bup-be-ngoi-sao-nhap-khau.jpg\" width=\"800\" /></div> ', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_rows (id, listcatid, user_id, addtime, edittime, status, publtime, exptime, archive, product_code, product_number, product_price, price_config, money_unit, product_unit, product_weight, weight_unit, discount_id, homeimgfile, homeimgthumb, homeimgalt, otherimage, imgposition, copyright, gift_from, gift_to, inhome, allowed_comm, allowed_rating, ratingdetail, allowed_send, allowed_print, allowed_save, hitstotal, hitscm, hitslm, num_sell, showprice, vi_title, vi_alias, vi_hometext, vi_bodytext, vi_gift_content, vi_address) VALUES('3', '7', '1', '1432366714', '1432366740', '1', '1432366714', '0', '2', 'V03', '14', '50000', '', 'VND', '1', '250', 'g', '0', '2015_05/giay-bup-be-ngoi-sao-nhap-khau.jpg', '1', 'Chân Váy Công Sở', '', '0', '0', '0', '0', '1', '4', '1', '0', '1', '1', '1', '4', '0', '0', '1', '1', 'Chân Váy Công Sở', 'Chan-Vay-Cong-So', 'chân váy công sở', 'Sản phẩm thời trang<br  /> <div style=\"text-align:center\"><img alt=\"giay bup be ngoi sao nhap khau\" height=\"800\" src=\"/uploads/shops/2015_05/giay-bup-be-ngoi-sao-nhap-khau.jpg\" width=\"800\" /></div> ', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_rows (id, listcatid, user_id, addtime, edittime, status, publtime, exptime, archive, product_code, product_number, product_price, price_config, money_unit, product_unit, product_weight, weight_unit, discount_id, homeimgfile, homeimgthumb, homeimgalt, otherimage, imgposition, copyright, gift_from, gift_to, inhome, allowed_comm, allowed_rating, ratingdetail, allowed_send, allowed_print, allowed_save, hitstotal, hitscm, hitslm, num_sell, showprice, vi_title, vi_alias, vi_hometext, vi_bodytext, vi_gift_content, vi_address) VALUES('4', '7', '1', '1432367089', '1432367089', '1', '1432367089', '0', '2', 'S000004', '17', '50000', '', 'VND', '1', '300', 'g', '0', '2015_05/giay-bup-be-ngoi-sao-nhap-khau.jpg', '1', 'chân váy caro', '', '0', '0', '0', '0', '1', '4', '1', '0', '1', '1', '1', '4', '0', '0', '3', '1', 'Chân váy caro', 'Chan-vay-caro', 'chân váy caro', 'Sản phẩm thời trang<br  /> <div style=\"text-align:center\"><img alt=\"giay bup be ngoi sao nhap khau\" height=\"800\" src=\"/uploads/shops/2015_05/giay-bup-be-ngoi-sao-nhap-khau.jpg\" width=\"800\" /></div> ', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_rows (id, listcatid, user_id, addtime, edittime, status, publtime, exptime, archive, product_code, product_number, product_price, price_config, money_unit, product_unit, product_weight, weight_unit, discount_id, homeimgfile, homeimgthumb, homeimgalt, otherimage, imgposition, copyright, gift_from, gift_to, inhome, allowed_comm, allowed_rating, ratingdetail, allowed_send, allowed_print, allowed_save, hitstotal, hitscm, hitslm, num_sell, showprice, vi_title, vi_alias, vi_hometext, vi_bodytext, vi_gift_content, vi_address) VALUES('5', '10', '1', '1432367366', '1432367366', '1', '1432367366', '0', '2', 'S000005', '30', '0', '', 'VND', '1', '220', 'g', '0', '2015_05/giay-bup-be-ngoi-sao-nhap-khau.jpg', '1', 'áo somi lụa đẹp', '', '0', '0', '0', '0', '1', '4', '1', '0', '1', '1', '1', '0', '0', '0', '0', '1', 'Áo sơmi lụa', 'Ao-somi-lua', 'áo somi lụa đẹp', 'Sản phẩm thời trang<br  /> <div style=\"text-align:center\"><img alt=\"giay bup be ngoi sao nhap khau\" height=\"800\" src=\"/uploads/shops/2015_05/giay-bup-be-ngoi-sao-nhap-khau.jpg\" width=\"800\" /></div> ', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_rows (id, listcatid, user_id, addtime, edittime, status, publtime, exptime, archive, product_code, product_number, product_price, price_config, money_unit, product_unit, product_weight, weight_unit, discount_id, homeimgfile, homeimgthumb, homeimgalt, otherimage, imgposition, copyright, gift_from, gift_to, inhome, allowed_comm, allowed_rating, ratingdetail, allowed_send, allowed_print, allowed_save, hitstotal, hitscm, hitslm, num_sell, showprice, vi_title, vi_alias, vi_hometext, vi_bodytext, vi_gift_content, vi_address) VALUES('6', '10', '1', '1432367846', '1432370007', '1', '1432367846', '0', '2', 'S000006', '15', '0', '', 'VND', '1', '300', 'g', '0', '2015_05/giay-bup-be-ngoi-sao-nhap-khau.jpg', '1', '', '', '0', '0', '0', '0', '1', '4', '1', '0', '1', '1', '1', '8', '0', '0', '0', '1', 'Áo sơ mi voan phối tay ren', 'Ao-so-mi-voan-phoi-tay-ren', '<h1><span style=\"font-size:14px;\">Áo sơ mi voan</span></h1>', 'Sản phẩm thời trang<br  /> <div style=\"text-align:center\"><img alt=\"giay bup be ngoi sao nhap khau\" height=\"800\" src=\"/uploads/shops/2015_05/giay-bup-be-ngoi-sao-nhap-khau.jpg\" width=\"800\" /></div> ', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_rows (id, listcatid, user_id, addtime, edittime, status, publtime, exptime, archive, product_code, product_number, product_price, price_config, money_unit, product_unit, product_weight, weight_unit, discount_id, homeimgfile, homeimgthumb, homeimgalt, otherimage, imgposition, copyright, gift_from, gift_to, inhome, allowed_comm, allowed_rating, ratingdetail, allowed_send, allowed_print, allowed_save, hitstotal, hitscm, hitslm, num_sell, showprice, vi_title, vi_alias, vi_hometext, vi_bodytext, vi_gift_content, vi_address) VALUES('8', '11', '1', '1432605984', '1432605984', '1', '1432605984', '0', '2', 'S000008', '15', '120000', '', 'VND', '1', '200', 'g', '0', '2015_05/giay-bup-be-ngoi-sao-nhap-khau.jpg', '1', 'áo thun nữ', '', '0', '0', '0', '0', '1', '4', '1', '0', '1', '1', '1', '1', '0', '0', '0', '1', 'áo thun nữ họa tiết độc đáo', 'ao-thun-nu-hoa-tiet-doc-dao', 'áo thun nữ họa tiết độc đáo', 'Sản phẩm thời trang<br  /> <div style=\"text-align:center\"><img alt=\"giay bup be ngoi sao nhap khau\" height=\"800\" src=\"/uploads/shops/2015_05/giay-bup-be-ngoi-sao-nhap-khau.jpg\" width=\"800\" /></div> ', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_rows (id, listcatid, user_id, addtime, edittime, status, publtime, exptime, archive, product_code, product_number, product_price, price_config, money_unit, product_unit, product_weight, weight_unit, discount_id, homeimgfile, homeimgthumb, homeimgalt, otherimage, imgposition, copyright, gift_from, gift_to, inhome, allowed_comm, allowed_rating, ratingdetail, allowed_send, allowed_print, allowed_save, hitstotal, hitscm, hitslm, num_sell, showprice, vi_title, vi_alias, vi_hometext, vi_bodytext, vi_gift_content, vi_address) VALUES('9', '13', '1', '1432606317', '1432629809', '1', '1432606317', '0', '2', 'S000009', '10', '100000', '', 'VND', '1', '500', 'g', '1', '2015_05/giay-bup-be-ngoi-sao-nhap-khau.jpg', '1', 'Giày da nữ gót vuông', '', '0', '0', '0', '0', '1', '4', '1', '0', '1', '1', '1', '1', '0', '0', '0', '1', 'Giày da nữ gót vuông', 'Giay-da-nu-got-vuong', 'Giày da nữ gót vuông', 'Sản phẩm thời trang<br  /> <div style=\"text-align:center\"><img alt=\"giay bup be ngoi sao nhap khau\" height=\"800\" src=\"/uploads/shops/2015_05/giay-bup-be-ngoi-sao-nhap-khau.jpg\" width=\"800\" /></div> ', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_rows (id, listcatid, user_id, addtime, edittime, status, publtime, exptime, archive, product_code, product_number, product_price, price_config, money_unit, product_unit, product_weight, weight_unit, discount_id, homeimgfile, homeimgthumb, homeimgalt, otherimage, imgposition, copyright, gift_from, gift_to, inhome, allowed_comm, allowed_rating, ratingdetail, allowed_send, allowed_print, allowed_save, hitstotal, hitscm, hitslm, num_sell, showprice, vi_title, vi_alias, vi_hometext, vi_bodytext, vi_gift_content, vi_address) VALUES('10', '13', '1', '1432606522', '1432629789', '1', '1432606522', '0', '2', 'S000010', '10', '100000', '', 'VND', '2', '350', 'g', '2', '2015_05/giay-bup-be-ngoi-sao-nhap-khau.jpg', '1', 'Giày cao gót mũi nhọn màu hồng', '', '0', '0', '0', '0', '1', '4', '1', '0', '1', '1', '1', '1', '0', '0', '0', '1', 'Giày cao gót mũi nhọn màu hồng be quý phái', 'Giay-cao-got-mui-nhon-mau-hong-be-quy-phai', 'Giày cao gót mũi nhọn màu hồng be quý phái', 'Sản phẩm thời trang<br  /> <div style=\"text-align:center\"><img alt=\"giay bup be ngoi sao nhap khau\" height=\"800\" src=\"/uploads/shops/2015_05/giay-bup-be-ngoi-sao-nhap-khau.jpg\" width=\"800\" /></div> ', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_rows (id, listcatid, user_id, addtime, edittime, status, publtime, exptime, archive, product_code, product_number, product_price, price_config, money_unit, product_unit, product_weight, weight_unit, discount_id, homeimgfile, homeimgthumb, homeimgalt, otherimage, imgposition, copyright, gift_from, gift_to, inhome, allowed_comm, allowed_rating, ratingdetail, allowed_send, allowed_print, allowed_save, hitstotal, hitscm, hitslm, num_sell, showprice, vi_title, vi_alias, vi_hometext, vi_bodytext, vi_gift_content, vi_address) VALUES('7', '11', '1', '1432369124', '1432369433', '1', '1432369124', '0', '2', 'S000007', '50', '120000', '', 'VND', '1', '150', 'g', '0', '2015_05/giay-bup-be-ngoi-sao-nhap-khau.jpg', '1', '', '', '0', '0', '0', '0', '1', '4', '1', '0', '1', '1', '1', '2', '0', '0', '0', '1', 'áo thun nữ', 'ao-thun-nu', 'áo thun nữ', 'Sản phẩm thời trang<br  /> <div style=\"text-align:center\"><img alt=\"giay bup be ngoi sao nhap khau\" height=\"800\" src=\"/uploads/shops/2015_05/giay-bup-be-ngoi-sao-nhap-khau.jpg\" width=\"800\" /></div> ', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_rows (id, listcatid, user_id, addtime, edittime, status, publtime, exptime, archive, product_code, product_number, product_price, price_config, money_unit, product_unit, product_weight, weight_unit, discount_id, homeimgfile, homeimgthumb, homeimgalt, otherimage, imgposition, copyright, gift_from, gift_to, inhome, allowed_comm, allowed_rating, ratingdetail, allowed_send, allowed_print, allowed_save, hitstotal, hitscm, hitslm, num_sell, showprice, vi_title, vi_alias, vi_hometext, vi_bodytext, vi_gift_content, vi_address) VALUES('11', '15', '1', '1432607113', '1435376866', '1', '1432607113', '0', '2', 'S000011', '20', '100000', '', 'VND', '2', '250', 'g', '0', '2015_05/giay-bup-be-ngoi-sao-nhap-khau.jpg', '1', 'GIÀY BÚP BÊ NGÔI SAO NHẬP KHẨU', '', '0', '0', '0', '0', '1', '4', '1', '0', '1', '1', '1', '2', '0', '0', '0', '1', 'GIÀY BÚP BÊ NGÔI SAO NHẬP KHẨU', 'GIAY-BUP-BE-NGOI-SAO-NHAP-KHAU', 'GIÀY BÚP BÊ NGÔI SAO NHẬP KHẨU', 'Sản phẩm thời trang<br  /> <div style=\"text-align:center\"><img alt=\"giay bup be ngoi sao nhap khau\" height=\"800\" src=\"/uploads/shops/2015_05/giay-bup-be-ngoi-sao-nhap-khau.jpg\" width=\"800\" /></div> ', '', '');";

	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_units (id, vi_title, vi_note) VALUES('1', 'cái', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_units (id, vi_title, vi_note) VALUES('2', 'đôi', '');";

	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_tabs (id, icon, content, weight, active, vi_title) VALUES('1', '', 'content_detail', '1', '1', 'Chi tiết sản phẩm');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_tabs (id, icon, content, weight, active, vi_title) VALUES('2', '', 'content_comments', '2', '1', 'Bình luận');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_tabs (id, icon, content, weight, active, vi_title) VALUES('3', '', 'content_rate', '3', '1', 'Đánh giá');";

	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_tags_vi (tid, numpro, alias, image, description, keywords) VALUES('1', '1', 'thời-trang', '', '', 'thời trang');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_tags_vi (tid, numpro, alias, image, description, keywords) VALUES('2', '1', 'sang-trọng', '', '', 'sang trọng');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_tags_vi (tid, numpro, alias, image, description, keywords) VALUES('3', '1', 'phù-hợp', '', '', 'phù hợp');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_tags_vi (tid, numpro, alias, image, description, keywords) VALUES('4', '1', 'đi-chơi', '', '', 'đi chơi');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_tags_vi (tid, numpro, alias, image, description, keywords) VALUES('5', '1', 'áo-sơ-mi', '', '', 'áo sơ mi');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_tags_vi (tid, numpro, alias, image, description, keywords) VALUES('6', '1', 'mũi-nhọn', '', '', 'mũi nhọn');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_tags_vi (tid, numpro, alias, image, description, keywords) VALUES('7', '1', 'búp-bê', '', '', 'búp bê');";

	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_tags_id_vi (id, tid, keyword) VALUES('1', '1', 'thời trang');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_tags_id_vi (id, tid, keyword) VALUES('1', '2', 'sang trọng');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_tags_id_vi (id, tid, keyword) VALUES('1', '3', 'phù hợp');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_tags_id_vi (id, tid, keyword) VALUES('1', '4', 'đi chơi');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_tags_id_vi (id, tid, keyword) VALUES('6', '5', 'áo sơ mi');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_tags_id_vi (id, tid, keyword) VALUES('10', '6', 'mũi nhọn');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_tags_id_vi (id, tid, keyword) VALUES('11', '7', 'búp bê');";

	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('1', '0', '', '1', '1', '0', 'viewcat_page_list', '6', '6,7,8,9,10,11', '1', '0', '1432623061', '1432623061', '0', '1', '0', 'Thương hiệu', 'Thuong-hieu', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('2', '0', '', '2', '8', '0', 'viewcat_page_list', '12', '12,13,15,16,17,18,19,20,21,22,23,24', '1', '0', '1432623083', '1432623083', '0', '1', '0', 'Màu sắc', 'Mau-sac', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('3', '0', '', '3', '21', '0', 'viewcat_page_list', '15', '25,26,27,28,29,30,31,32,33,34,35,36,37,38,39', '1', '0', '1432623101', '1432623101', '0', '1', '0', 'Kích thước', 'Kich-thuoc', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('4', '0', '', '4', '37', '0', 'viewcat_page_list', '8', '40,41,42,43,44,45,46,47', '1', '0', '1432623118', '1432623118', '0', '1', '0', 'Chất liệu', 'Chat-lieu', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('5', '0', '', '5', '46', '0', 'viewcat_page_list', '10', '48,49,50,51,52,53,54,55,56,57', '1', '0', '1432623133', '1432623133', '0', '1', '0', 'Xuất xứ', 'Xuat-xu', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('6', '1', '', '1', '2', '1', 'viewcat_page_list', '0', '', '1', '0', '1432626862', '1432626862', '0', '1', '0', 'Việt Tiến', 'Viet-Tien', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('7', '1', '', '2', '3', '1', 'viewcat_page_list', '0', '', '1', '0', '1432626882', '1432626882', '3', '1', '0', 'ZARA', 'ZARA', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('8', '1', '', '3', '4', '1', 'viewcat_page_list', '0', '', '1', '0', '1432626899', '1432626899', '0', '1', '0', 'MATTANA', 'MATTANA', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('9', '1', '', '4', '5', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627013', '1432627013', '0', '1', '0', 'KELVIN', 'KELVIN', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('10', '1', '', '5', '6', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627027', '1432627027', '0', '1', '0', 'THÁI TUẤN', 'THAI-TUAN', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('11', '1', '', '6', '7', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627053', '1432627053', '0', '1', '0', 'VICTORIA SECRECT', 'VICTORIA-SECRECT', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('12', '2', '', '1', '9', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627064', '1432627064', '0', '1', '0', 'ĐỎ', 'DO', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('13', '2', '', '2', '10', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627070', '1432627070', '0', '1', '0', 'VÀNG', 'VANG', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('16', '2', '', '4', '12', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627102', '1432627102', '1', '1', '0', 'HỒNG PHẤN', 'HONG-PHAN', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('15', '2', '', '3', '11', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627095', '1432627095', '0', '1', '0', 'XANH NGỌC', 'XANH-NGOC', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('17', '2', '', '5', '13', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627107', '1432627107', '0', '1', '0', 'XANH RÊU', 'XANH-REU', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('18', '2', '', '6', '14', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627112', '1432627112', '0', '1', '0', 'TÍM', 'TIM', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('19', '2', '', '7', '15', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627123', '1432627123', '0', '1', '0', 'XÁM', 'XAM', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('20', '2', '', '8', '16', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627135', '1432627135', '0', '1', '0', 'XANH NƯỚC BIỂN', 'XANH-NUOC-BIEN', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('21', '2', '', '9', '17', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627148', '1432627148', '0', '1', '0', 'CAM', 'CAM', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('22', '2', '', '10', '18', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627153', '1432627153', '0', '1', '0', 'BẠC', 'BAC', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('23', '2', '', '11', '19', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627160', '1432627160', '0', '1', '0', 'MÀU DA', 'MAU-DA', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('24', '2', '', '12', '20', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627182', '1432627182', '2', '1', '0', 'ĐEN', 'DEN', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('25', '3', '', '1', '22', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627201', '1432627201', '0', '1', '0', 'F', 'F', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('26', '3', '', '2', '23', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627210', '1432627210', '0', '1', '0', 'L', 'L', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('27', '3', '', '3', '24', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627215', '1432627215', '0', '1', '0', 'M', 'M', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('28', '3', '', '4', '25', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627219', '1432627219', '0', '1', '0', 'S', 'S', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('29', '3', '', '5', '26', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627223', '1432627223', '0', '1', '0', 'XL', 'XL', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('30', '3', '', '6', '27', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627241', '1432627241', '0', '1', '0', 'XXL', 'XXL', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('31', '3', '', '7', '28', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627250', '1432627250', '0', '1', '0', 'XXXL', 'XXXL', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('32', '3', '', '8', '29', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627259', '1432627259', '2', '1', '0', '35', '35', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('33', '3', '', '9', '30', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627264', '1432627264', '2', '1', '0', '36', '36', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('34', '3', '', '10', '31', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627269', '1432627269', '2', '1', '0', '37', '37', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('35', '3', '', '11', '32', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627274', '1432627274', '3', '1', '0', '38', '38', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('36', '3', '', '12', '33', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627279', '1432627279', '0', '1', '0', '39', '39', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('37', '3', '', '13', '34', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627284', '1432627284', '0', '1', '0', '40', '40', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('38', '3', '', '14', '35', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627291', '1432627291', '0', '1', '0', '41', '41', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('39', '3', '', '15', '36', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627296', '1432627296', '0', '1', '0', '42', '42', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('40', '4', '', '1', '38', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627339', '1432627339', '0', '1', '0', 'COTTON', 'COTTON', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('41', '4', '', '2', '39', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627346', '1432627346', '0', '1', '0', 'DẠ', 'DA', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('42', '4', '', '3', '40', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627364', '1432627364', '0', '1', '0', 'JEANS', 'JEANS', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('43', '4', '', '4', '41', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627369', '1432627369', '0', '1', '0', 'BÒ', 'BO', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('44', '4', '', '5', '42', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627378', '1432627378', '0', '1', '0', 'LANH', 'LANH', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('45', '4', '', '6', '43', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627385', '1432627385', '0', '1', '0', 'TƠ TẰM', 'TO-TAM', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('46', '4', '', '7', '44', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627399', '1432627399', '0', '1', '0', 'THUN', 'THUN', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('47', '4', '', '8', '45', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627407', '1432627407', '0', '1', '0', 'LỤA', 'LUA', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('48', '5', '', '1', '47', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627418', '1432627418', '0', '1', '0', 'VIỆT NAM', 'VIET-NAM', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('49', '5', '', '2', '48', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627425', '1432627425', '0', '1', '0', 'HÀN QUỐC', 'HAN-QUOC', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('50', '5', '', '3', '49', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627519', '1432627519', '0', '1', '0', 'ĐỨC', 'DUC', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('51', '5', '', '4', '50', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627528', '1432627528', '1', '1', '0', 'NHẬT BẢN', 'NHAT-BAN', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('52', '5', '', '5', '51', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627541', '1432627541', '0', '1', '0', 'THÁI LAN', 'THAI-LAN', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('53', '5', '', '6', '52', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627553', '1432627553', '1', '1', '0', 'HONGKONG', 'HONGKONG', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('54', '5', '', '7', '53', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627565', '1432627565', '0', '1', '0', 'TRUNG QUỐC', 'TRUNG-QUOC', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('55', '5', '', '8', '54', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627573', '1432627573', '0', '1', '0', 'PHÁP', 'PHAP', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('56', '5', '', '9', '55', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627579', '1432627579', '0', '1', '0', 'ANH', 'ANH', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('57', '5', '', '10', '56', '1', 'viewcat_page_list', '0', '', '1', '0', '1432627617', '1432627617', '0', '1', '0', 'AUSTRALIA', 'AUSTRALIA', '', '');";

	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group_cateid (groupid, cateid) VALUES('1', '2');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group_cateid (groupid, cateid) VALUES('1', '3');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group_cateid (groupid, cateid) VALUES('1', '4');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group_cateid (groupid, cateid) VALUES('1', '5');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group_cateid (groupid, cateid) VALUES('2', '2');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group_cateid (groupid, cateid) VALUES('2', '3');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group_cateid (groupid, cateid) VALUES('2', '4');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group_cateid (groupid, cateid) VALUES('2', '5');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group_cateid (groupid, cateid) VALUES('3', '2');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group_cateid (groupid, cateid) VALUES('3', '3');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group_cateid (groupid, cateid) VALUES('3', '4');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group_cateid (groupid, cateid) VALUES('3', '5');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group_cateid (groupid, cateid) VALUES('4', '2');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group_cateid (groupid, cateid) VALUES('4', '3');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group_cateid (groupid, cateid) VALUES('4', '4');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group_cateid (groupid, cateid) VALUES('4', '5');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group_cateid (groupid, cateid) VALUES('5', '2');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group_cateid (groupid, cateid) VALUES('5', '3');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group_cateid (groupid, cateid) VALUES('5', '4');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group_cateid (groupid, cateid) VALUES('5', '5');";

	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group_items (pro_id, group_id) VALUES('9', '7');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group_items (pro_id, group_id) VALUES('9', '24');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group_items (pro_id, group_id) VALUES('9', '32');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group_items (pro_id, group_id) VALUES('9', '33');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group_items (pro_id, group_id) VALUES('9', '34');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group_items (pro_id, group_id) VALUES('9', '35');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group_items (pro_id, group_id) VALUES('9', '53');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group_items (pro_id, group_id) VALUES('10', '7');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group_items (pro_id, group_id) VALUES('10', '16');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group_items (pro_id, group_id) VALUES('10', '32');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group_items (pro_id, group_id) VALUES('10', '33');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group_items (pro_id, group_id) VALUES('10', '34');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group_items (pro_id, group_id) VALUES('10', '35');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group_items (pro_id, group_id) VALUES('10', '51');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group_items (pro_id, group_id) VALUES('11', '7');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group_items (pro_id, group_id) VALUES('11', '24');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group_items (pro_id, group_id) VALUES('11', '35');";

	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_warehouse (wid, title, note, user_id, addtime) VALUES('1', 'Nhập kho ngày 23/05/2015', '', '1', '1432364016');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_warehouse (wid, title, note, user_id, addtime) VALUES('2', 'Nhập kho ngày 23/05/2015', '', '1', '1432365552');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_warehouse (wid, title, note, user_id, addtime) VALUES('3', 'Nhập kho ngày 23/05/2015', '', '1', '1432366753');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_warehouse (wid, title, note, user_id, addtime) VALUES('4', 'Nhập kho ngày 23/05/2015', '', '1', '1432367106');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_warehouse (wid, title, note, user_id, addtime) VALUES('5', 'Nhập kho ngày 23/05/2015', '', '1', '1432367387');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_warehouse (wid, title, note, user_id, addtime) VALUES('6', 'Nhập kho ngày 23/05/2015', '', '1', '1432367857');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_warehouse (wid, title, note, user_id, addtime) VALUES('7', 'Nhập kho ngày 23/05/2015', '', '1', '1432369139');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_warehouse (wid, title, note, user_id, addtime) VALUES('8', 'Nhập kho ngày 26/05/2015', '', '1', '1432608794');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_warehouse (wid, title, note, user_id, addtime) VALUES('9', 'Nhập kho ngày 26/05/2015', '', '1', '1432608805');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_warehouse (wid, title, note, user_id, addtime) VALUES('10', 'Nhập kho ngày 26/05/2015', '', '1', '1432608819');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_warehouse (wid, title, note, user_id, addtime) VALUES('11', 'Nhập kho ngày 26/05/2015', '', '1', '1432608835');";

	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_warehouse_logs (logid, wid, pro_id, quantity, price, money_unit) VALUES('1', '1', '1', '20', '150000', 'VND');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_warehouse_logs (logid, wid, pro_id, quantity, price, money_unit) VALUES('2', '2', '2', '50', '250000', 'VND');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_warehouse_logs (logid, wid, pro_id, quantity, price, money_unit) VALUES('3', '3', '3', '15', '70000', 'VND');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_warehouse_logs (logid, wid, pro_id, quantity, price, money_unit) VALUES('4', '4', '4', '20', '120000', 'VND');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_warehouse_logs (logid, wid, pro_id, quantity, price, money_unit) VALUES('5', '5', '5', '30', '120000', 'VND');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_warehouse_logs (logid, wid, pro_id, quantity, price, money_unit) VALUES('6', '6', '6', '15', '180000', 'VND');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_warehouse_logs (logid, wid, pro_id, quantity, price, money_unit) VALUES('7', '7', '7', '50', '50000', 'VND');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_warehouse_logs (logid, wid, pro_id, quantity, price, money_unit) VALUES('8', '8', '11', '20', '80', 'VND');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_warehouse_logs (logid, wid, pro_id, quantity, price, money_unit) VALUES('9', '9', '10', '10', '180', 'VND');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_warehouse_logs (logid, wid, pro_id, quantity, price, money_unit) VALUES('10', '10', '9', '10', '150', 'VND');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_warehouse_logs (logid, wid, pro_id, quantity, price, money_unit) VALUES('11', '11', '8', '15', '50000', 'VND');";
}
$sql_create_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_rows ADD UNIQUE (" . $lang . "_alias)";