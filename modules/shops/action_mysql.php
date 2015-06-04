<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 20:59
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );
$sql_drop_module = array();

global $op, $db;

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

$result = $db->query( "SHOW TABLE STATUS LIKE '" . $db_config['prefix'] . "\_" . $lang . "\_comments'" );
$rows = $result->fetchAll();
if( sizeof( $rows ) )
{
	$sql_drop_module[] = "DELETE FROM " . $db_config['prefix'] . "_" . $lang . "_comments WHERE module='" . $module_name . "'";
}

if( in_array( $lang, $array_lang_module_setup ) and $num_table > 1 )
{
	$sql_drop_module[] = 'ALTER TABLE ' . $db_config['prefix'] . '_' . $module_data . '_rows
	 DROP ' . $lang . '_title,
	 DROP ' . $lang . '_alias,
	 DROP ' . $lang . '_hometext,
	 DROP ' . $lang . '_bodytext,
	 DROP ' . $lang . '_gift_content';

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

	$sql_drop_module[] = 'ALTER TABLE ' . $db_config['prefix'] . '_' . $module_data . '_tags
	 DROP ' . $lang . '_numpro,
	 DROP ' . $lang . '_alias,
	 DROP ' . $lang . '_image,
	 DROP ' . $lang . '_description,
	 DROP ' . $lang . '_keywords';

	$sql_drop_module[] = 'ALTER TABLE ' . $db_config['prefix'] . '_' . $module_data . '_files
	 DROP ' . $lang . '_title,
	 DROP ' . $lang . '_description';

	$sql_drop_module[] = 'ALTER TABLE ' . $db_config['prefix'] . '_' . $module_data . '_tabs DROP ' . $lang . '_title';

	$sql_drop_module[] = 'ALTER TABLE ' . $db_config['prefix'] . '_' . $module_data . '_tags_id DROP ' . $lang . '_keyword';
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
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_tags';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_tags_id';
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
 product_unit int(11) NOT NULL,
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

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_tags (
	 tid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
	 PRIMARY KEY (tid)
	) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_tags_id (
	 id int(11) NOT NULL,
	 tid mediumint(9) NOT NULL,
	 UNIQUE KEY sid (id,tid)
	) ENGINE=MyISAM";

$sql_create_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_tags ADD " . $lang . "_numpro mediumint(8) NOT NULL DEFAULT '0',
 ADD " . $lang . "_alias varchar(255) NOT NULL DEFAULT '',
 ADD " . $lang . "_image varchar(255) DEFAULT '',
 ADD " . $lang . "_description text,
 ADD " . $lang . "_keywords varchar(255) DEFAULT '',
 ADD UNIQUE(" . $lang . "_alias)";

$sql_create_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_tags_id ADD " . $lang . "_keyword varchar(65) NOT NULL";

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
		$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_money_" . $lang . " SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_money_" . $set_lang_data;
		$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_weight_" . $lang . " SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_weight_" . $set_lang_data;
	}

	$numrow = $db->query( "SELECT count(*) FROM " . $db_config['prefix'] . "_" . $module_data . "_tags" )->fetchColumn();
	if( $numrow )
	{
		$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_tags SET " . $lang . "_alias = " . $set_lang_data . "_title";
		$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_tags SET " . $lang . "_image = " . $set_lang_data . "_alias";
		$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_tags SET " . $lang . "_description = " . $set_lang_data . "_description";
		$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_tags SET " . $lang . "_keywords = " . $set_lang_data . "_keywords";
	}

	$numrow = $db->query( "SELECT count(*) FROM " . $db_config['prefix'] . "_" . $module_data . "_tags_id" )->fetchColumn();
	if( $numrow )
	{
		$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_tags_id SET " . $lang . "_keyword = " . $set_lang_data . "_keyword";
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

//demo by Nguyễn Huế believe.in.love135@gmail.com
$_sql = "SELECT table_name FROM information_schema.tables
	WHERE table_schema = '".$db_config['dbname']."'
	AND table_name = '" . $db_config['prefix'] . "_" . $module_data . "_catalogs'";
$row = $db->query( $_sql )->fetch();
if( empty( $row ) )
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
	
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_rows (id, listcatid, user_id, addtime, edittime, status, publtime, exptime, archive, product_code, product_number, product_price, price_config, money_unit, product_unit, product_weight, weight_unit, discount_id, homeimgfile, homeimgthumb, homeimgalt, otherimage, imgposition, copyright, gift_from, gift_to, inhome, allowed_comm, allowed_rating, ratingdetail, allowed_send, allowed_print, allowed_save, hitstotal, hitscm, hitslm, num_sell, showprice, vi_title, vi_alias, vi_hometext, vi_bodytext, vi_gift_content, vi_address) VALUES('1', '6', '1', '1432363521', '1432365563', '1', '1432363521', '0', '2', 'V01', '19', '100000', '', 'VND', '1', '20', 'g', '0', '2015_05/maya-dam-maxi-sang-trong-hoa-cao-cap-bm12-1m4g3-dbc487_simg_d0daf0_800x1200_max.jpg', '1', 'Váy Maxi sang trọng', '', '0', '0', '0', '0', '1', '4', '1', '0', '1', '1', '1', '11', '0', '0', '1', '1', 'Đầm Maxi sang trọng', 'Dam-Maxi-sang-trong', 'Đầm maxi thời trang', '<span style=\"font-size:14px;\"><span style=\"font-family: arial,helvetica,sans-serif;\">Mang đến bạn bộ sưu tập maxi phối thời trang, cao cấp và cá tính thích hợp cho bạn trong những buổi dạ tiệc hay vui chơi, dạo phố.<br  />Phong cách free stlye phù hợp cho cuộc sống năng động hằng ngày</span></span><p><span style=\"font-size:14px;\"><span style=\"font-family: arial,helvetica,sans-serif;\">Thích hợp để làm quà tặng cho ngườii thân cũng như bạn bè.</span></span></p>', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_rows (id, listcatid, user_id, addtime, edittime, status, publtime, exptime, archive, product_code, product_number, product_price, price_config, money_unit, product_unit, product_weight, weight_unit, discount_id, homeimgfile, homeimgthumb, homeimgalt, otherimage, imgposition, copyright, gift_from, gift_to, inhome, allowed_comm, allowed_rating, ratingdetail, allowed_send, allowed_print, allowed_save, hitstotal, hitscm, hitslm, num_sell, showprice, vi_title, vi_alias, vi_hometext, vi_bodytext, vi_gift_content, vi_address) VALUES('2', '6', '1', '1432365534', '1432365970', '1', '1432365534', '0', '2', 'V02', '50', '100000', '', 'VND', '1', '250', 'g', '0', '2015_05/dam-cao-cap.jpg', '1', '', '', '0', '0', '0', '0', '1', '4', '1', '0', '1', '1', '1', '1', '0', '0', '0', '1', 'Đầm maxi họa tiết', 'Dam-maxi-hoa-tiet', 'đầm maxi sang trọng', '<a href=\"https://www.sendo.vn/dam-maxi.htm\">Đầm maxi</a> họa tiết <a href=\"https://www.sendo.vn/cao-cap.htm\">cao cấp</a> ( hàng nhập cao cấp )Size S, M, L<br  />Chất liệu: voan lụa mịn đẹp, <a href=\"https://www.sendo.vn/hoa-tiet.htm\">họa tiết</a> sang trọng, eo bo thun, hàng chất giống hình 100% nhé các nàng!<br  /><br  /><img alt=\"Hàng nhập cao cấp - Đầm maxi họa tiết cao cấp 2\" src=\"https://media3.sendo.vn/img1/2015/3_22/hang-nhap-cao-cap-dam-maxi-hoa-tiet-cao-cap-1m4G3-dammaxihoatietcaocap1_2kgkktril7mhm_simg_d0daf0_800x1200_max.jpg\" /><br  /><br  /><img alt=\"Hàng nhập cao cấp - Đầm maxi họa tiết cao cấp 3\" src=\"https://media3.sendo.vn/img1/2015/3_22/hang-nhap-cao-cap-dam-maxi-hoa-tiet-cao-cap-1m4G3-dammaxihoatietcaocap2_2kgkkt5saqrg4_simg_d0daf0_800x1200_max.jpg\" /><br  /><br  /><img alt=\"Hàng nhập cao cấp - Đầm maxi họa tiết cao cấp 4\" src=\"https://media3.sendo.vn/img1/2015/3_22/hang-nhap-cao-cap-dam-maxi-hoa-tiet-cao-cap-1m4G3-dammaxihoatietcaocap3_2kgkktelfqn3d_simg_d0daf0_800x1200_max.jpg\" /><br  />&nbsp;', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_rows (id, listcatid, user_id, addtime, edittime, status, publtime, exptime, archive, product_code, product_number, product_price, price_config, money_unit, product_unit, product_weight, weight_unit, discount_id, homeimgfile, homeimgthumb, homeimgalt, otherimage, imgposition, copyright, gift_from, gift_to, inhome, allowed_comm, allowed_rating, ratingdetail, allowed_send, allowed_print, allowed_save, hitstotal, hitscm, hitslm, num_sell, showprice, vi_title, vi_alias, vi_hometext, vi_bodytext, vi_gift_content, vi_address) VALUES('3', '7', '1', '1432366714', '1432366740', '1', '1432366714', '0', '2', 'V03', '14', '50000', '', 'VND', '1', '250', 'g', '0', '2015_05/chan-vay-cong-so.jpg', '1', 'Chân Váy Công Sở', '', '0', '0', '0', '0', '1', '4', '1', '0', '1', '1', '1', '4', '0', '0', '1', '1', 'Chân Váy Công Sở', 'Chan-Vay-Cong-So', 'chân váy công sở', '<span style=\"margin: 0px; padding: 0px; font-size: 18px;\"><span style=\"margin: 0px; padding: 0px; font-family: &#039;times new roman&#039;, times, serif;\">Kiểu dáng: <u><a href=\"https://www.sendo.vn/chan-vay-cong-so.htm\">chân váy công sở</a> </u>ôm body gợi cảm.</span></span><p style=\"margin: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &#039;Open Sans&#039;; font-size: 15px; line-height: 30px; text-align: justify; background-color: rgb(242, 242, 242);\"><span style=\"margin: 0px; padding: 0px; font-size: 18px;\"><span style=\"margin: 0px; padding: 0px; font-family: &#039;times new roman&#039;, times, serif;\">- Trọng lượng: 250g</span></span></p><p style=\"margin: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &#039;Open Sans&#039;; font-size: 15px; line-height: 30px; text-align: justify; background-color: rgb(242, 242, 242);\"><span style=\"margin: 0px; padding: 0px; font-size: 18px;\"><span style=\"margin: 0px; padding: 0px; font-family: &#039;times new roman&#039;, times, serif;\">- Form Ôm Tinh Tế, Chất Liệu Tuyết Mưa Đẹp – Tôn Dáng Cùng Phong Cách Công Sở Thanh Lịch.&nbsp;</span></span></p><p style=\"margin: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &#039;Open Sans&#039;; font-size: 15px; line-height: 30px; text-align: center;\"><a href=\"https://ban.sendo.vn/product\" style=\"margin: 0px; padding: 0px; color: rgb(0, 0, 0); text-decoration: none;\"><span style=\"margin: 0px; padding: 0px; font-family: &#039;times new roman&#039;, times, serif;\"><span style=\"margin: 0px; padding: 0px; font-size: 18px;\"><img alt=\"BM402 Chân Váy Công Sở Tuyết Mưa thời trang 1\" src=\"https://media3.sendo.vn/img1/2015/4_7/bm402-chan-vay-cong-so-tuyet-mua-thoi-trang-1m4G3-958d7f.jpg\" style=\"border-style: none; margin: 0px; padding: 0px; max-width: 670px; height: 450px; width: 660px;\" /></span></span></a></p><p style=\"margin: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &#039;Open Sans&#039;; font-size: 15px; line-height: 30px; text-align: center;\"><span style=\"margin: 0px; padding: 0px; font-family: &#039;times new roman&#039;, times, serif;\"><span style=\"margin: 0px; padding: 0px; font-size: 18px;\">-&nbsp;<a href=\"https://www.sendo.vn/chan-vay.htm\">Chân Váy</a> Công Sở Tuyết Mưa&nbsp;thiết kế form ôm body&nbsp;tôn lên phong cách thời trang thanh lịch, tinh tế của bạn gái.</span></span></p><p style=\"margin: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &#039;Open Sans&#039;; font-size: 15px; line-height: 30px; text-align: center;\">&nbsp;</p><p style=\"margin: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &#039;Open Sans&#039;; font-size: 15px; line-height: 30px; text-align: center;\"><a href=\"https://ban.sendo.vn/product\" style=\"margin: 0px; padding: 0px; color: rgb(0, 0, 0); text-decoration: none;\"><span style=\"margin: 0px; padding: 0px; font-family: &#039;times new roman&#039;, times, serif;\"><span style=\"margin: 0px; padding: 0px; font-size: 18px;\"><img alt=\"BM402 Chân Váy Công Sở Tuyết Mưa thời trang 2\" src=\"https://media3.sendo.vn/img1/2015/4_7/bm402-chan-vay-cong-so-tuyet-mua-thoi-trang-1m4G3-9b115b.jpg\" style=\"border-style: none; margin: 0px; padding: 0px; max-width: 670px; height: 701px; width: 660px;\" /></span></span></a></p><p style=\"margin: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &#039;Open Sans&#039;; font-size: 15px; line-height: 30px; text-align: center;\">&nbsp;</p><p style=\"margin: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &#039;Open Sans&#039;; font-size: 15px; line-height: 30px; text-align: center;\"><span style=\"margin: 0px; padding: 0px; color: rgb(255, 97, 0); text-decoration: none; font-family: &#039;times new roman&#039;, times, serif;\"><span style=\"margin: 0px; padding: 0px; font-size: 18px;\"><a href=\"https://ban.sendo.vn/product\" style=\"margin: 0px; padding: 0px; color: rgb(255, 97, 0); text-decoration: none;\"><img alt=\"BM402 Chân Váy Công Sở Tuyết Mưa thời trang 3\" src=\"https://media3.sendo.vn/img1/2015/4_7/bm402-chan-vay-cong-so-tuyet-mua-thoi-trang-1m4G3-13e1ad.jpg\" style=\"border-style: none; margin: 0px; padding: 0px; max-width: 670px; height: 737px; width: 660px;\" /></a></span></span></p><p>&nbsp;</p><p style=\"margin: 0px; padding: 0px; color: rgb(51, 51, 51); font-family: &#039;Open Sans&#039;; font-size: 15px; line-height: 30px; text-align: justify; background-color: rgb(242, 242, 242);\">&nbsp;</p>', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_rows (id, listcatid, user_id, addtime, edittime, status, publtime, exptime, archive, product_code, product_number, product_price, price_config, money_unit, product_unit, product_weight, weight_unit, discount_id, homeimgfile, homeimgthumb, homeimgalt, otherimage, imgposition, copyright, gift_from, gift_to, inhome, allowed_comm, allowed_rating, ratingdetail, allowed_send, allowed_print, allowed_save, hitstotal, hitscm, hitslm, num_sell, showprice, vi_title, vi_alias, vi_hometext, vi_bodytext, vi_gift_content, vi_address) VALUES('4', '7', '1', '1432367089', '1432367089', '1', '1432367089', '0', '2', 'S000004', '17', '50000', '', 'VND', '1', '300', 'g', '0', '2015_05/chan-vay-caro.jpg', '1', 'chân váy caro', '', '0', '0', '0', '0', '1', '4', '1', '0', '1', '1', '1', '3', '0', '0', '3', '1', 'Chân váy caro', 'Chan-vay-caro', 'chân váy caro', '<div class=\"content_tab active\" id=\"pr-detail-inf\" itemprop=\"description\">&nbsp;<p style=\"margin: 0px 0px 10px; color: rgb(136, 136, 136); font-family: &#039;Open Sans&#039;, Helvetica, Arial, sans-serif; font-size: 12px; line-height: 20px; text-align: center;\"><span style=\"text-decoration: underline;\">Thông Tin Sản Phẩm :</span></p><p style=\"margin: 0px 0px 10px; color: rgb(136, 136, 136); font-family: &#039;Open Sans&#039;, Helvetica, Arial, sans-serif; font-size: 12px; line-height: 20px; text-align: center;\">Tên Sản Phẩm : &nbsp;<strong><a href=\"https://www.sendo.vn/chan-vay.htm\">CHÂN VÁY</a> CARO CÀI NÚT TRƯỚC</strong>&nbsp;- 90 - CV</p><p style=\"margin: 0px 0px 10px; color: rgb(136, 136, 136); font-family: &#039;Open Sans&#039;, Helvetica, Arial, sans-serif; font-size: 12px; line-height: 20px; text-align: center;\">Màu : CARO ĐEN TRẮNG</p><p style=\"margin: 0px 0px 10px; color: rgb(136, 136, 136); font-family: &#039;Open Sans&#039;, Helvetica, Arial, sans-serif; font-size: 12px; line-height: 20px; text-align: center;\">Chất liệu : KAKI NHUNG</p><p style=\"margin: 0px 0px 10px; color: rgb(136, 136, 136); font-family: &#039;Open Sans&#039;, Helvetica, Arial, sans-serif; font-size: 12px; line-height: 20px; text-align: center;\">Size :&nbsp;</p><table style=\"max-width: 100%; border-collapse: collapse; border-spacing: 0px; color: rgb(136, 136, 136); font-family: &#039;open sans&#039;; font-size: 15px; height: auto; line-height: 30px; margin: 0px; padding: 0px; text-align: justify; width: 660px;\">	<tbody>		<tr>			<td style=\"width: 91px;\">			<p style=\"margin: 0px 0px 10px; text-align: center;\"><span style=\"font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif;\">Màu sắc</span></span></p>			</td>			<td style=\"width: 91px;\">			<p style=\"margin: 0px 0px 10px; text-align: center;\"><span style=\"font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif;\">Size</span></span></p>			</td>			<td style=\"width: 91px;\">			<p style=\"margin: 0px 0px 10px; text-align: center;\"><span style=\"font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif;\">Chất liệu vải</span></span></p>			</td>			<td style=\"width: 91px;\">			<p style=\"margin: 0px 0px 10px; text-align: center;\"><span style=\"font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif;\">Vòng eo (cm)</span></span></p>			</td>			<td style=\"width: 98px;\">			<p style=\"margin: 0px 0px 10px; text-align: center;\"><span style=\"font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif;\">Vòng mông (cm)</span></span></p>			</td>			<td style=\"width: 84px;\">			<p style=\"margin: 0px 0px 10px; text-align: center;\"><span style=\"font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif;\">Chiều dài (cm)</span></span></p>			</td>			<td style=\"width: 91px;\">			<p style=\"margin: 0px 0px 10px; text-align: center;\"><span style=\"font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif;\">Co giãn</span></span></p>			</td>		</tr>		<tr>			<td style=\"width: 91px;\">			<p style=\"margin: 0px 0px 10px; text-align: center;\"><span style=\"font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif;\">Caro trắng đen</span></span></p>			</td>			<td style=\"width: 91px;\">			<p style=\"margin: 0px 0px 10px; text-align: center;\"><span style=\"font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif;\">M</span></span></p>			</td>			<td style=\"width: 91px;\">			<p style=\"margin: 0px 0px 10px; text-align: center;\"><span style=\"font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif;\">Kaki nhung</span></span></p>			</td>			<td style=\"width: 91px;\">			<p style=\"margin: 0px 0px 10px; text-align: center;\"><span style=\"font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif;\">66-70</span></span></p>			</td>			<td style=\"width: 98px;\">			<p style=\"margin: 0px 0px 10px; text-align: center;\"><span style=\"font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif;\">84-88</span></span></p>			</td>			<td style=\"width: 84px;\">			<p style=\"margin: 0px 0px 10px; text-align: center;\"><span style=\"font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif;\">50</span></span></p>			</td>			<td style=\"width: 91px;\">			<p style=\"margin: 0px 0px 10px; text-align: center;\"><span style=\"font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif;\">Ít</span></span></p>			</td>		</tr>		<tr>			<td style=\"width: 91px;\">			<p style=\"margin: 0px 0px 10px; text-align: center;\"><span style=\"font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif;\">L</span></span></p>			</td>			<td style=\"width: 91px;\">			<p style=\"margin: 0px 0px 10px; text-align: center;\"><span style=\"font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif;\">70-74</span></span></p>			</td>			<td style=\"width: 98px;\">			<p style=\"margin: 0px 0px 10px; text-align: center;\"><span style=\"font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif;\">88-92</span></span></p>			</td>		</tr>	</tbody></table><p style=\"margin: 0px 0px 10px; color: rgb(136, 136, 136); font-family: &#039;Open Sans&#039;, Helvetica, Arial, sans-serif; font-size: 12px; line-height: 20px; text-align: center;\">&nbsp;</p><p style=\"margin: 0px 0px 10px; color: rgb(136, 136, 136); font-family: &#039;Open Sans&#039;, Helvetica, Arial, sans-serif; font-size: 12px; line-height: 20px; text-align: center;\"><span style=\"color: rgb(0, 0, 0); font-family: arial, helvetica, sans-serif; font-size: 14px;\">Kiểu dáng: chân váy caro có 2 túi trước cài nút trước.</span></p><p style=\"margin: 0px 0px 10px; color: rgb(136, 136, 136); font-family: &#039;Open Sans&#039;, Helvetica, Arial, sans-serif; font-size: 12px; line-height: 20px; text-align: center;\"><span style=\"color: rgb(0, 0, 0); font-family: arial, helvetica, sans-serif; font-size: 14px;\"><img alt=\"chan vay caro, chân váy,\" src=\"https://media3.sendo.vn/img1/2014/12_14/chan-vay-caro-cai-nut-truoc-1m4G3-a1koreanasianfashionshoppingmall00006927_2k2asshp3lj7q.jpg\" style=\"max-width: 100%; height: 500px; vertical-align: middle; width: 500px;\" /><img alt=\"chan vay caro cai nut, \" src=\"https://media3.sendo.vn/img1/2014/12_14/chan-vay-caro-cai-nut-truoc-1m4G3-c2korean-asian-fashion-shopping-mall-000_2k2assredkhdm.jpg\" style=\"max-width: 100%; height: 801px; vertical-align: middle; width: 600px;\" /><img alt=\"chan vay caro cai nút\" src=\"https://media3.sendo.vn/img1/2014/12_14/chan-vay-caro-cai-nut-truoc-1m4G3-c3korean-asian-fashion-shopping-mall-000_2k2asshkn7t1p.jpg\" style=\"max-width: 100%; height: 693px; vertical-align: middle; width: 600px;\" /><img alt=\"chan vay caro cai nut,\" src=\"https://media3.sendo.vn/img1/2014/12_14/chan-vay-caro-cai-nut-truoc-1m4G3-c4korean-asian-fashion-shopping-mall-000_2k2assmhaa4a6.jpg\" style=\"max-width: 100%; height: 677px; vertical-align: middle; width: 600px;\" /><img alt=\"chan vay caro cai nut, \" src=\"https://media3.sendo.vn/img1/2014/12_14/chan-vay-caro-cai-nut-truoc-1m4G3-c5korean-asian-fashion-shopping-mall-000_2k2asshtj4sna.jpg\" style=\"max-width: 100%; height: 690px; vertical-align: middle; width: 600px;\" /><img alt=\"chan vay caro cai nut,\" src=\"https://media3.sendo.vn/img1/2014/12_14/chan-vay-caro-cai-nut-truoc-1m4G3-c1korean-asian-fashion-shopping-mall-000_2k2asshe9rlh4.jpg\" style=\"max-width: 100%; height: 486px; vertical-align: middle; width: 600px;\" /></span></p></div>', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_rows (id, listcatid, user_id, addtime, edittime, status, publtime, exptime, archive, product_code, product_number, product_price, price_config, money_unit, product_unit, product_weight, weight_unit, discount_id, homeimgfile, homeimgthumb, homeimgalt, otherimage, imgposition, copyright, gift_from, gift_to, inhome, allowed_comm, allowed_rating, ratingdetail, allowed_send, allowed_print, allowed_save, hitstotal, hitscm, hitslm, num_sell, showprice, vi_title, vi_alias, vi_hometext, vi_bodytext, vi_gift_content, vi_address) VALUES('5', '10', '1', '1432367366', '1432367366', '1', '1432367366', '0', '2', 'S000005', '30', '0', '', 'VND', '1', '220', 'g', '0', '2015_05/ao-so-mi-voan-lua.jpg', '1', 'áo somi lụa đẹp', '', '0', '0', '0', '0', '1', '4', '1', '0', '1', '1', '1', '0', '0', '0', '0', '1', 'Áo sơmi lụa', 'Ao-somi-lua', 'áo somi lụa đẹp', '<div class=\"breadcrumb_detail util-clearfix\" itemprop=\"breadcrumb\"><div class=\"br first\"><a href=\"https://www.sendo.vn/\" rel=\"v:url\" title=\"Sendo\">Sendo.vn </a></div><div class=\"br first link\"><a href=\"https://www.sendo.vn/sitemap/\" rel=\"v:url\" target=\"_blank\" title=\"Danh mục sản phẩm\">Danh mục sản phẩm</a></div><div class=\"br \"><a alt=\"Thời trang nữ\" href=\"https://www.sendo.vn/thoi-trang-nu/\" rel=\"v:url\" title=\"Thời trang nữ\">Thời trang nữ</a></div><div class=\"br \"><a alt=\"Áo nữ\" href=\"https://www.sendo.vn/thoi-trang-nu/ao-nu/\" rel=\"v:url\" title=\"Áo nữ\">Áo nữ</a></div><div class=\"br end\"><a alt=\"Áo sơ mi công sở\" href=\"https://www.sendo.vn/thoi-trang-nu/ao-nu/ao-so-mi-cong-so/\" rel=\"v:url\" title=\"Áo sơ mi công sở\">Áo sơ mi công sở</a></div></div><div class=\"left-col left_detail\" itemscope=\"\" itemtype=\"http://schema.org/Product\"><div class=\"box_img_attr \"><div class=\"box_img_product hoa-sen\">&nbsp;</div></div></div><div class=\"box_img_product hoa-sen\"><div class=\"bg-l\"><div class=\"zoomWrapper\" style=\"height:320px;width:320px;\"><img alt=\"áo sơ mi voan lụa mịn+ren hết hột cổ áo 116200\" data-title=\"áo sơ mi voan lụa mịn+ren hết hột cổ áo 116200\" data-zoom-image=\"https://media3.sendo.vn/img/2014/6_15/somi_voan_ren_2j5kla3a7pj44.jpg\" height=\"320\" id=\"zoom_detail\" itemprop=\"image\" src=\"https://media3.sendo.vn/img/2014/6_15/ao-so-mi-voan-lua-minren-het-hot-co-ao-116200-1m4G3-somi_voan_ren_2j5kla3a7pj44_simg_5acd92_320x320_maxb.jpg\" style=\"position: absolute;\" width=\"320\" /></div></div><div class=\"zoom-desc\" id=\"gallery_01\"><a data-image=\"https://media3.sendo.vn/img/2014/6_15/ao-so-mi-voan-lua-minren-het-hot-co-ao-116200-1m4G3-somi_voan_ren_2j5kla3a7pj44_simg_5acd92_320x320_maxb.jpg\" data-zoom-image=\"https://media3.sendo.vn/img/2014/6_15/somi_voan_ren_2j5kla3a7pj44.jpg\" title=\"\"> <img alt=\"áo sơ mi voan lụa mịn+ren hết hột cổ áo 116200\" class=\"lazydetail\" height=\"50\" id=\"img_01\" src=\"https://media3.sendo.vn/img/2014/6_15/somi_voan_ren_2j5kla3a7pj44_simg_02d57e_50x50_maxb.jpg\" width=\"50\" /> </a></div><div class=\"box-fb-go social\"><div class=\"for-fb\"><div class=\"fb-like fb_iframe_widget\" data-action=\"like\" data-href=\"https://www.sendo.vn/thoi-trang-nu/ao-nu/ao-so-mi-cong-so/ao-so-mi-voan-lua-min-ren-het-hot-co-ao-116200-710081/\" data-layout=\"button_count\" data-share=\"true\" data-show-faces=\"false\">&nbsp;</div></div><div class=\"for-go\">&nbsp;</div></div></div><div class=\"box_attr_product\"><div class=\"name_product item\"><h1><a class=\"fn\" href=\"https://www.sendo.vn/thoi-trang-nu/ao-nu/ao-so-mi-cong-so/ao-so-mi-voan-lua-min-ren-het-hot-co-ao-116200-710081/\" itemprop=\"name\" title=\"áo sơ mi voan lụa mịn+ren hết hột cổ áo 116200 - 116200\">áo sơ mi voan lụa mịn+ren hết hột cổ áo 116200 - 116200</a></h1></div><div class=\"rating_buy_view hreview-aggregate\"><div class=\"rating\"><a href=\"https://www.sendo.vn/thoi-trang-nu/ao-nu/ao-so-mi-cong-so/ao-so-mi-voan-lua-min-ren-het-hot-co-ao-116200-710081/#tabs_rate\" rel=\"nofollow\" title=\"\"><span class=\"bg_star\"><span class=\"rstar\" style=\"width:100%;\">&nbsp;</span> </span> </a> <strong> 100% </strong> <a class=\"dg \" href=\"https://www.sendo.vn/thoi-trang-nu/ao-nu/ao-so-mi-cong-so/ao-so-mi-voan-lua-min-ren-het-hot-co-ao-116200-710081/#tabs_rate\" rel=\"nofollow\" title=\"\">(21 đánh giá) </a></div><div class=\"buyview\">﻿<div class=\"separator\">&nbsp;</div><div class=\"buy\" title=\"Lượt mua\"><strong>73</strong> lượt mua</div><div class=\"separator\">&nbsp;</div><div class=\"view\" title=\"Lượt xem\"><strong>24,032</strong> lượt xem</div></div></div><div class=\"price_wirehouse_shopinfo\" itemprop=\"offers\" itemscope=\"\" itemtype=\"http://schema.org/Offer\"><div class=\"price\"><div class=\"current_price\" itemprop=\"price\">250,000 VNĐ</div><div class=\"old_price\">Giá cũ: 357,000 VNĐ</div><div class=\"update_price\" title=\"Ngày cập nhật giá\">3 ngày trước</div></div><div class=\"wirehouse\"><strong>Kho hàng :</strong> Hồ Chí Minh</div><div class=\"shopinfo\"><strong>Thông tin shop:</strong><img alt=\"\" height=\"17\" src=\"https://static.scdn.vn/images/ecom/img-thongtinshop.gif\" title=\"\" width=\"35\" /></div></div><div class=\"loyaltyDetail\"><p class=\"desLoyaltyDetail\" data-original-title=\"Là số điểm SEN bạn được thưởng khi mua đơn hàng này. Tích điểm để dùng thay cho tiền khi mua hàng trên Sendo.vn\" data-target=\"#modalPointSen\" data-toggle=\"modal\" title=\"\">Tặng ngay <span class=\"icLoyaltyDetail\">&nbsp;</span> <span class=\"pointSen\"> + 5,000</span></p></div><div class=\"grab-taxi-box\"><p>Tặng voucher <img alt=\"grabtaxi\" src=\"https://static.scdn.vn/images/ecom/grabtaxi-icon.png\" /> <b><i>Grab</i>taxi</b> khi mua sản phẩm này</p></div><form class=\"ng-pristine ng-valid\" id=\"product_detail_infomation\" method=\"POST\"><div class=\"box-attrs\"><div class=\"attrs est-shipping-fee\"><div class=\"attrs\"><label>Vận chuyển<br  />toàn quốc: </label><div class=\"vc-info\">Giao hàng đến <a class=\"usr_locale\" data-target=\"#modalShipping\" data-toggle=\"modal\" title=\"chọn nhà vận chuyển\"> <strong> Hồ Chí Minh </strong> </a> bằng <a data-target=\"#modalShipping\" data-toggle=\"modal\" title=\"chọn nhà vận chuyển\"> <strong class=\"usr_carrier\">Giao hàng nhanh</strong> </a> với phí: <strong>17,000 VNĐ</strong><p class=\"new_carrier_time\">Thời gian giao hàng 2 ngày.</p><p class=\"ic-ship\">Shop <strong>MIỄN PHÍ VẬN CHUYỂN</strong> cho đơn hàng từ <strong>700,000 VNĐ</strong> trở lên</p></div></div></div><div class=\"attrs\"><label class=\"label\">Màu sắc: </label><div class=\"attrs-item option\"><label class=\"check\" title=\"Trắng\"><input checked=\"checked\" name=\"options[19270582][]\" type=\"radio\" value=\"28430427\" /><span class=\"label\" style=\"background-color:rgb(255, 255, 255);\">&nbsp;</span></label></div></div><div class=\"attrs\"><label class=\"label\">Kích thước: </label><div class=\"attrs-item option\"><label title=\"\"><input name=\"options[19270584][]\" type=\"radio\" value=\"28430429\" /><span class=\"label\">S</span></label><label title=\"\"><input name=\"options[19270584][]\" type=\"radio\" value=\"28430430\" /><span class=\"label\">M</span></label><label title=\"\"><input name=\"options[19270584][]\" type=\"radio\" value=\"28430431\" /><span class=\"label\">L</span></label></div></div><div class=\"attrs\"><label class=\"label\">Chất liệu: </label><div class=\"attrs-item \">Voan</div></div><div class=\"attrs\"><label class=\"label\">Kiểu dáng: </label><div class=\"attrs-item \">Dài tay</div></div><div class=\"attrs\"><label class=\"label\">Xuất xứ: </label><div class=\"attrs-item \" itemprop=\"manufacturer\">Hàn Quốc</div></div><div class=\"attrs\"><label class=\"label\">Kiểu vạt áo: </label><div class=\"attrs-item \">Khác</div></div><div class=\"attrs quality\"><label>Số lượng: </label> <input name=\"qty\" type=\"text\" value=\"1\" /></div></div></form></div><div class=\"box-attrs\"><div class=\"attrs quality\">&nbsp;</div></div><div class=\"buybtn\">&nbsp;</div><div class=\"box_img_attr \"><div class=\"box_attr_product\"><form class=\"ng-pristine ng-valid\" id=\"product_detail_infomation\" method=\"POST\"><div class=\"buybtn\"><div class=\"overflow_box\"><a class=\"fav\" title=\"Thêm vào danh sách yêu thích\"><i>&nbsp;</i> Thêm vào danh sách yêu thích (<span class=\"wl-num\">0</span>) </a><div class=\"img-tmp\"><img alt=\"áo sơ mi voan lụa mịn+ren hết hột cổ áo 116200\" class=\"imgtodrag\" height=\"200\" src=\"https://media3.sendo.vn/img/2014/6_15/somi_voan_ren_2j5kla3a7pj44_simg_ce2f5e_200x200_maxb.jpg\" title=\"áo sơ mi voan lụa mịn+ren hết hột cổ áo 116200\" width=\"200\" /></div></div></div><div class=\"box-hoa-sen\"><h2>shop hoa sen</h2><span>Trả hàng - hoàn tiền khi không vừa ý với sản phẩm .</span> <span>Nhận hàng trong thời gian nhanh nhất. </span> <span class=\"shopLoyalty\">Được thưởng điểm SEN khi mua hàng. </span> <a class=\"view-more\" href=\"https://www.sendo.vn/huong-dan/shop-hoa-sen/\" title=\"Xem thêm\">Xem thêm</a></div></form></div><div class=\"cls\">&nbsp;</div></div><div class=\"help_btn_detail\"><div class=\"PagingWrapper\"><div class=\"PagingControl\"><div class=\"CenterWrapper\"><a class=\"buy_step\" href=\"https://www.sendo.vn/huong-dan/huong-dan-mua-hang/\" target=\"_blank\" title=\"Các bước mua hàng\">các bước mua hàng</a> <a class=\"howshipping\" href=\"https://www.sendo.vn/huong-dan/giao-hang-toan-quoc/\" target=\"_blank\" title=\"Phương thức vận chuyển\"> Phương thức vận chuyển</a> <a class=\"howpay\" href=\"https://www.sendo.vn/huong-dan/cach-thuc-thanh-toan/\" target=\"_blank\" title=\"Cách thức thanh toán\"> Cách thức thanh toán </a></div></div></div></div><div class=\"tab_li tab-scroll\"><ul>	<li class=\"tabs shop_border_color_hover active\"><a href=\"https://www.sendo.vn/thoi-trang-nu/ao-nu/ao-so-mi-cong-so/ao-so-mi-voan-lua-min-ren-het-hot-co-ao-116200-710081/#pr-detail-inf\" title=\"Chi tiết sản phẩm\">Chi tiết sản phẩm</a></li>	<li class=\"tabs shop_border_color_hover\"><a href=\"https://www.sendo.vn/thoi-trang-nu/ao-nu/ao-so-mi-cong-so/ao-so-mi-voan-lua-min-ren-het-hot-co-ao-116200-710081/#tabs_rate\" title=\"Đánh giá/phản hồi\">Đánh giá/phản hồi <span class=\"tabs_rate_total shop_color\">(21)</span></a></li>	<li class=\"tabs shop_border_color_hover\"><a href=\"https://www.sendo.vn/thoi-trang-nu/ao-nu/ao-so-mi-cong-so/ao-so-mi-voan-lua-min-ren-het-hot-co-ao-116200-710081/#hoi_dap\" title=\"Hỏi đáp\">Hỏi đáp <span class=\"shop_color\" id=\"qna_count\">(8)</span></a></li></ul></div><p style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: 12px; vertical-align: baseline; color: rgb(51, 51, 51); font-family: arial; line-height: 12px; text-align: center; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">&nbsp;</p><p style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: 12px; vertical-align: baseline; color: rgb(51, 51, 51); font-family: arial; line-height: 12px; text-align: center; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\"><span style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: large; vertical-align: baseline; color: rgb(255, 0, 0); background: transparent;\"><strong style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: 18px; vertical-align: baseline; background: transparent;\"><img alt=\"áo sơ mi voan lụa mịn+ren hết hột cổ áo 116200 1\" src=\"https://media3.sendo.vn/img1/2015/4_2/ao-so-mi-voan-lua-minren-het-hot-co-ao-116200-1m4G3-94aacf_simg_d0daf0_800x1200_max.gif\" /></strong></span></p><p style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: 12px; vertical-align: baseline; color: rgb(51, 51, 51); font-family: arial; line-height: 12px; text-align: center; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">&nbsp;</p><p style=\"text-align: center;\"><span style=\"font-size: large; color: rgb(255, 0, 0); background-color: rgb(255, 255, 0);\"><strong>áo <a href=\"https://www.sendo.vn/so-mi-voan.htm\">sơ mi voan</a> <a href=\"https://www.sendo.vn/lua.htm\">lụa</a> mịn+ren hết hột cổ áo 116200---250.000 VNĐ</strong></span></p><p style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: 12px; vertical-align: baseline; color: rgb(51, 51, 51); font-family: arial; line-height: 12px; text-align: center; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\"><span style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: large; vertical-align: baseline; color: rgb(255, 0, 0); background: transparent;\"><strong style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: 18px; vertical-align: baseline; background: transparent;\"><span style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: medium; vertical-align: baseline; color: rgb(51, 51, 51); font-family: Helvetica, Arial, sans-serif; font-weight: normal; line-height: 17px; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\"><span style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; background: transparent;\">Size S, M, L</span></span><br style=\"box-sizing: border-box;\" /><span style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: medium; vertical-align: baseline; color: rgb(51, 51, 51); font-family: Helvetica, Arial, sans-serif; font-weight: normal; line-height: 17px; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\"><span style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; background: transparent;\">Chất liệu: <a href=\"https://www.sendo.vn/voan-lua.htm\">voan lụa</a> mịn , phối ren đính hạt cực xinh, hàng chất cực đẹp nhé các nàng!</span></span></strong></span></p><p style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: 12px; vertical-align: baseline; color: rgb(51, 51, 51); font-family: arial; line-height: 12px; text-align: center; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">&nbsp;</p><p style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: 12px; vertical-align: baseline; color: rgb(51, 51, 51); font-family: arial; line-height: 12px; text-align: center;\">&nbsp;</p><span style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: large; vertical-align: baseline; background-color: transparent; color: rgb(255, 0, 0); background-position: initial initial; background-repeat: initial initial;\"><strong style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: 18px; vertical-align: baseline; background-color: transparent; background-position: initial initial; background-repeat: initial initial;\"><img alt=\"áo sơ mi voan lụa mịn+ren hết hột cổ áo 116200 2\" src=\"https://media3.sendo.vn/img/2014/6_15/ao-so-mi-voan-lua-minren-het-hot-co-ao-116200-1m4G3-so_mi_voan_lua_min_ren_het_hot_co_ao_116_2j5kl973hn9r7_simg_d0daf0_800x1200_max.jpg\" /><img alt=\"áo sơ mi voan lụa mịn+ren hết hột cổ áo 116200 3\" src=\"https://media3.sendo.vn/img/2014/6_15/ao-so-mi-voan-lua-minren-het-hot-co-ao-116200-1m4G3-so_mi_voan_lua_min_ren_het_hot_co_ao_116_2j5kl9ajj2ahj_simg_d0daf0_800x1200_max.jpg\" /><img alt=\"áo sơ mi voan lụa mịn+ren hết hột cổ áo 116200 4\" src=\"https://media3.sendo.vn/img/2014/6_15/ao-so-mi-voan-lua-minren-het-hot-co-ao-116200-1m4G3-so_mi_voan_lua_min_ren_het_hot_co_ao_116_2j5kl9e3kthmf_simg_d0daf0_800x1200_max.jpg\" /><img alt=\"áo sơ mi voan lụa mịn+ren hết hột cổ áo 116200 5\" src=\"https://media3.sendo.vn/img/2014/6_15/ao-so-mi-voan-lua-minren-het-hot-co-ao-116200-1m4G3-so_mi_voan_lua_min_ren_het_hot_co_ao_116_2j5kl9hgaq9c7_simg_d0daf0_800x1200_max.jpg\" /><img alt=\"áo sơ mi voan lụa mịn+ren hết hột cổ áo 116200 6\" src=\"https://media3.sendo.vn/img/2014/6_15/ao-so-mi-voan-lua-minren-het-hot-co-ao-116200-1m4G3-so_mi_voan_lua_min_ren_het_hot_co_ao_116_2j5kl9knel0g1_simg_d0daf0_800x1200_max.jpg\" /><img alt=\"áo sơ mi voan lụa mịn+ren hết hột cổ áo 116200 7\" src=\"https://media3.sendo.vn/img/2014/6_15/ao-so-mi-voan-lua-minren-het-hot-co-ao-116200-1m4G3-so_mi_voan_lua_min_ren_het_hot_co_ao_116_2j5kl9phgegc7_simg_d0daf0_800x1200_max.jpg\" /><img alt=\"áo sơ mi voan lụa mịn+ren hết hột cổ áo 116200 8\" src=\"https://media3.sendo.vn/img/2014/6_15/ao-so-mi-voan-lua-minren-het-hot-co-ao-116200-1m4G3-somi_voan_ren_2j5kla3a7pj44_simg_d0daf0_800x1200_max.jpg\" /></strong></span>', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_rows (id, listcatid, user_id, addtime, edittime, status, publtime, exptime, archive, product_code, product_number, product_price, price_config, money_unit, product_unit, product_weight, weight_unit, discount_id, homeimgfile, homeimgthumb, homeimgalt, otherimage, imgposition, copyright, gift_from, gift_to, inhome, allowed_comm, allowed_rating, ratingdetail, allowed_send, allowed_print, allowed_save, hitstotal, hitscm, hitslm, num_sell, showprice, vi_title, vi_alias, vi_hometext, vi_bodytext, vi_gift_content, vi_address) VALUES('6', '10', '1', '1432367846', '1432370007', '1', '1432367846', '0', '2', 'S000006', '15', '0', '', 'VND', '1', '300', 'g', '0', '2015_05/ao-so-mi-voan.jpg', '1', '', '', '0', '0', '0', '0', '1', '4', '1', '0', '1', '1', '1', '8', '0', '0', '0', '1', 'Áo sơ mi voan phối tay ren', 'Ao-so-mi-voan-phoi-tay-ren', '<h1><span style=\"font-size:14px;\">Áo sơ mi voan</span></h1>', '&nbsp;<p style=\"text-align: center;\"><span style=\"color: rgb(255, 0, 255);\"><strong><span style=\"font-size: large;\">MÃ SP: 15379</span></strong></span></p><p style=\"text-align: center;\"><strong><span style=\"font-size: large;\">CHẤT VOAN CÁT MỀM MẠI PHỐI REN Y HÌNH</span></strong></p><img alt=\"HÀNG NHẬP CAO CẤP---Áo sơ mi voan phối tay ren 15379 2\" src=\"https://media3.sendo.vn/img1/2015/3_31/hang-nhap-cao-cap-ao-so-mi-voan-phoi-tay-ren-15379-1m4G3-15379smlxl1_2khsrk67h2s7k_simg_d0daf0_800x1200_max.jpg\" style=\"display: block; margin-left: auto; margin-right: auto;\" /><img alt=\"HÀNG NHẬP CAO CẤP---Áo sơ mi voan phối tay ren 15379 3\" src=\"https://media3.sendo.vn/img1/2015/3_31/hang-nhap-cao-cap-ao-so-mi-voan-phoi-tay-ren-15379-1m4G3-15379smlxl2_2khsrla7cn26e_simg_d0daf0_800x1200_max.jpg\" style=\"display: block; margin-left: auto; margin-right: auto;\" /><img alt=\"HÀNG NHẬP CAO CẤP---Áo sơ mi voan phối tay ren 15379 4\" src=\"https://media3.sendo.vn/img1/2015/3_31/hang-nhap-cao-cap-ao-so-mi-voan-phoi-tay-ren-15379-1m4G3-15379smlxl3_2khsrlf5kgaj4_simg_d0daf0_800x1200_max.jpg\" style=\"display: block; margin-left: auto; margin-right: auto;\" /><img alt=\"HÀNG NHẬP CAO CẤP---Áo sơ mi voan phối tay ren 15379 5\" src=\"https://media3.sendo.vn/img1/2015/3_31/hang-nhap-cao-cap-ao-so-mi-voan-phoi-tay-ren-15379-1m4G3-15379smlxl5_2khsrljlal2a1_simg_d0daf0_800x1200_max.jpg\" /><img alt=\"HÀNG NHẬP CAO CẤP---Áo sơ mi voan phối tay ren 15379 6\" src=\"https://media3.sendo.vn/img1/2015/3_31/hang-nhap-cao-cap-ao-so-mi-voan-phoi-tay-ren-15379-1m4G3-15379smlxl7_2khsrkotscj2p_simg_d0daf0_800x1200_max.jpg\" />', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_rows (id, listcatid, user_id, addtime, edittime, status, publtime, exptime, archive, product_code, product_number, product_price, price_config, money_unit, product_unit, product_weight, weight_unit, discount_id, homeimgfile, homeimgthumb, homeimgalt, otherimage, imgposition, copyright, gift_from, gift_to, inhome, allowed_comm, allowed_rating, ratingdetail, allowed_send, allowed_print, allowed_save, hitstotal, hitscm, hitslm, num_sell, showprice, vi_title, vi_alias, vi_hometext, vi_bodytext, vi_gift_content, vi_address) VALUES('8', '11', '1', '1432605984', '1432605984', '1', '1432605984', '0', '2', 'S000008', '15', '120000', '', 'VND', '1', '200', 'g', '0', '2015_05/ao-thun-nu-4-chieu.jpg', '1', 'áo thun nữ', '', '0', '0', '0', '0', '1', '4', '1', '0', '1', '1', '1', '0', '0', '0', '0', '1', 'áo thun nữ họa tiết độc đáo', 'ao-thun-nu-hoa-tiet-doc-dao', 'áo thun nữ họa tiết độc đáo', '<table cellpadding=\"0\" cellspacing=\"0\" class=\"technical\">	<tbody>		<tr class=\"data even\">			<td class=\"padding_right\">&nbsp;</td>			<td>&nbsp;</td>		</tr>		<tr class=\"data odd\">			<td class=\"padding_right\"><span class=\"label name\">Bảo hành:</span> Không có</td>			<td><span class=\"label name\">&nbsp;Nguồn gốc:</span> Chính hãng</td>		</tr>		<tr class=\"data odd\">			<td class=\"padding_right\"><span class=\"name\">Kiểu dáng:</span> Cộc tay,</td>			<td><span class=\"name\">Cổ áo:</span> Cổ tròn</td>		</tr>		<tr class=\"data even\">			<td class=\"padding_right\"><span class=\"name\">Kích cỡ:</span> Size S</td>			<td><span class=\"name\">Chất liệu:</span> <a class=\"text_link\" href=\"http://www.vatgia.com/s/thun\">Thun</a></td>		</tr>		<tr class=\"data odd\">			<td class=\"padding_right\">&nbsp;</td>			<td class=\"name\" colspan=\"2\">&nbsp;</td>		</tr>	</tbody></table>', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_rows (id, listcatid, user_id, addtime, edittime, status, publtime, exptime, archive, product_code, product_number, product_price, price_config, money_unit, product_unit, product_weight, weight_unit, discount_id, homeimgfile, homeimgthumb, homeimgalt, otherimage, imgposition, copyright, gift_from, gift_to, inhome, allowed_comm, allowed_rating, ratingdetail, allowed_send, allowed_print, allowed_save, hitstotal, hitscm, hitslm, num_sell, showprice, vi_title, vi_alias, vi_hometext, vi_bodytext, vi_gift_content, vi_address) VALUES('9', '13', '1', '1432606317', '1432629809', '1', '1432606317', '0', '2', 'S000009', '10', '100000', '', 'VND', '1', '500', 'g', '1', '2015_05/giay-da-nu-got-vuong.jpg', '1', 'Giày da nữ gót vuông', '', '0', '0', '0', '0', '1', '4', '1', '0', '1', '1', '1', '1', '0', '0', '0', '1', 'Giày da nữ gót vuông', 'Giay-da-nu-got-vuong', 'Giày da nữ gót vuông', '<p><span style=\"color: rgb(51, 51, 51); font-family: Arial, Helvetica, sans-serif; font-size: large; line-height: 18px;\"><a href=\"https://www.sendo.vn/giay-da-nu.htm\">Giày da nữ</a>, gót bọc da 5p</span></p><span style=\"color: rgb(51, 51, 51); font-family: Arial, Helvetica, sans-serif; font-size: large; line-height: 18px;\"><img alt=\"Giày da nữ gót vuông 5p 7368 1\" src=\"https://media3.sendo.vn/img/2014/11_9/giay-da-nu-got-vuong-5p-7368-1m4G3-7368n1_2jr6k5oab3bhs_simg_d0daf0_800x1200_max.jpg\" style=\"margin: 0px auto; display: block;\" /><br  /><br  /><img alt=\"Giày da nữ gót vuông 5p 7368 2\" src=\"https://media3.sendo.vn/img/2014/11_9/giay-da-nu-got-vuong-5p-7368-1m4G3-7368n2_2jr6k66gt1atl_simg_d0daf0_800x1200_max.jpg\" style=\"margin: 0px auto; display: block;\" /><br  /><br  /><img alt=\"Giày da nữ gót vuông 5p 7368 3\" src=\"https://media3.sendo.vn/img/2014/11_9/giay-da-nu-got-vuong-5p-7368-1m4G3-7368n3_2jr6k6iir3hjj_simg_d0daf0_800x1200_max.jpg\" style=\"margin: 0px auto; display: block;\" /><br  /><br  /><img alt=\"Giày da nữ gót vuông 5p 7368 4\" src=\"https://media3.sendo.vn/img/2014/11_9/giay-da-nu-got-vuong-5p-7368-1m4G3-7368n4_2jr6k70bn5kk6_simg_d0daf0_800x1200_max.jpg\" style=\"margin: 0px auto; display: block;\" /><br  /><br  /><img alt=\"Giày da nữ gót vuông 5p 7368 5\" src=\"https://media3.sendo.vn/img/2014/11_9/giay-da-nu-got-vuong-5p-7368-1m4G3-7368n5_2jr6k79pl89qd_simg_d0daf0_800x1200_max.jpg\" style=\"margin: 0px auto; display: block;\" /></span>', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_rows (id, listcatid, user_id, addtime, edittime, status, publtime, exptime, archive, product_code, product_number, product_price, price_config, money_unit, product_unit, product_weight, weight_unit, discount_id, homeimgfile, homeimgthumb, homeimgalt, otherimage, imgposition, copyright, gift_from, gift_to, inhome, allowed_comm, allowed_rating, ratingdetail, allowed_send, allowed_print, allowed_save, hitstotal, hitscm, hitslm, num_sell, showprice, vi_title, vi_alias, vi_hometext, vi_bodytext, vi_gift_content, vi_address) VALUES('10', '13', '1', '1432606522', '1432629789', '1', '1432606522', '0', '2', 'S000010', '10', '100000', '', 'VND', '2', '350', 'g', '2', '2015_05/giay-cao-got-mui-nhon-mau-hong-be-quy-phai-cg-666h-1m4g3-giaycaogotcg666hcopy_2k4etslal2erb_simg_d0daf0_800x1200_max.png', '1', 'Giày cao gót mũi nhọn màu hồng', '', '0', '0', '0', '0', '1', '4', '1', '0', '1', '1', '1', '1', '0', '0', '0', '1', 'Giày cao gót mũi nhọn màu hồng be quý phái', 'Giay-cao-got-mui-nhon-mau-hong-be-quy-phai', 'Giày cao gót mũi nhọn màu hồng be quý phái', '<div class=\"box-attrs\"><div class=\"attrs quality\">&nbsp;</div></div><div class=\"buybtn\">&nbsp;</div><div class=\"box_img_attr \"><div class=\"box_attr_product\"><form class=\"ng-pristine ng-valid\" id=\"product_detail_infomation\" method=\"POST\"><div class=\"buybtn\"><div class=\"overflow_box\"><a class=\"fav\" title=\"Thêm vào danh sách yêu thích\"><i>&nbsp;</i> Thêm vào danh sách yêu thích (<span class=\"wl-num\">0</span>) </a><div class=\"img-tmp\"><img alt=\"Giày cao gót mũi nhọn màu hồng be quý phái CG-666H\" class=\"imgtodrag\" height=\"200\" src=\"https://media3.sendo.vn/img/2014/7_26/giaycaogot1png_2jble14eoqe22_simg_ce2f5e_200x200_maxb.png\" title=\"Giày cao gót mũi nhọn màu hồng be quý phái CG-666H\" width=\"200\" /></div></div></div><div class=\"box-hoa-sen\"><h2>shop hoa sen</h2><span>Trả hàng - hoàn tiền khi không vừa ý với sản phẩm .</span> <span>Nhận hàng trong thời gian nhanh nhất. </span> <span class=\"shopLoyalty\">Được thưởng điểm SEN khi mua hàng. </span> <a class=\"view-more\" href=\"https://www.sendo.vn/huong-dan/shop-hoa-sen/\" title=\"Xem thêm\">Xem thêm</a></div></form></div><div class=\"cls\">&nbsp;</div></div><div class=\"help_btn_detail\"><div class=\"PagingWrapper\"><div class=\"PagingControl\"><div class=\"CenterWrapper\"><a class=\"buy_step\" href=\"https://www.sendo.vn/huong-dan/huong-dan-mua-hang/\" target=\"_blank\" title=\"Các bước mua hàng\">các bước mua hàng</a> <a class=\"howshipping\" href=\"https://www.sendo.vn/huong-dan/giao-hang-toan-quoc/\" target=\"_blank\" title=\"Phương thức vận chuyển\"> Phương thức vận chuyển</a> <a class=\"howpay\" href=\"https://www.sendo.vn/huong-dan/cach-thuc-thanh-toan/\" target=\"_blank\" title=\"Cách thức thanh toán\"> Cách thức thanh toán </a></div></div></div></div><div class=\"tab_li tab-scroll\"><ul>	<li class=\"tabs shop_border_color_hover active\"><a href=\"https://www.sendo.vn/thoi-trang-nu/giay-dep-nu/giay-cao-got/giay-cao-got-mui-nhon-mau-hong-be-quy-phai-cg-666h-787871/#pr-detail-inf\" title=\"Chi tiết sản phẩm\">Chi tiết sản phẩm</a></li>	<li class=\"tabs shop_border_color_hover\"><a href=\"https://www.sendo.vn/thoi-trang-nu/giay-dep-nu/giay-cao-got/giay-cao-got-mui-nhon-mau-hong-be-quy-phai-cg-666h-787871/#tabs_rate\" title=\"Đánh giá/phản hồi\">Đánh giá/phản hồi <span class=\"tabs_rate_total shop_color\">(14)</span></a></li>	<li class=\"tabs shop_border_color_hover\"><a href=\"https://www.sendo.vn/thoi-trang-nu/giay-dep-nu/giay-cao-got/giay-cao-got-mui-nhon-mau-hong-be-quy-phai-cg-666h-787871/#hoi_dap\" title=\"Hỏi đáp\">Hỏi đáp <span class=\"shop_color\" id=\"qna_count\">(5)</span></a></li></ul></div><div style=\"text-align: center;\">&nbsp;</div><div><span style=\"font-family: tahoma, arial, helvetica, sans-serif; font-size: medium;\"><strong>Tên Sản phẩm</strong>:&nbsp;<a href=\"https://www.sendo.vn/giay-cao-got.htm\">Giày cao gót</a> mũi nhọn <a href=\"https://www.sendo.vn/mau-hong.htm\">màu hồng</a> be quý phái CG-666H</span></div><p>&nbsp;</p><p><span style=\"font-family: tahoma, arial, helvetica, sans-serif; font-size: medium;\"><strong>Kích cỡ (Size)</strong></span></p><p>&nbsp;</p><p><span style=\"font-family: tahoma, arial, helvetica, sans-serif; font-size: medium;\"><strong>Sau 1 thời gian dài bán sản phẩm chúng tôi lưu ý Quý khách khi mua hàng trực tuyến nên giảm bớt đi 1 Size. Ví dụ quý khách đi giày cỡ bình thường là 37 thì mua giày này 36 là vừa chân.&nbsp;</strong></span></p><p>&nbsp;</p><p style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px none; outline: none 0px; font-size: 12px; vertical-align: baseline; color: rgb(51, 51, 51); font-family: Arial, Helvetica, sans-serif; line-height: 12px; text-align: center; background-image: none; background-attachment: scroll; background-size: initial; background-origin: initial; background-clip: initial; background-position: 0% 0%; background-repeat: repeat;\"><span style=\"font-family: tahoma, arial, helvetica, sans-serif; font-size: medium;\"><strong style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: 13px; vertical-align: baseline; background: transparent;\"><img alt=\"Giày cao gót mũi nhọn màu hồng be quý phái CG-666H 1\" src=\"https://media3.sendo.vn/img1/2015/5_6/giay-cao-got-mui-nhon-mau-hong-be-quy-phai-cg-666h-1m4G3-hang-nhap-giay-sandal-cao-got-dinh-cuom-sang-trong-cg300-1m4G3-a7df74_simg_d0daf0_800x1200_max.png\" /></strong></span></p><p>&nbsp;</p><div style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px none; outline: none 0px; font-size: 12px; vertical-align: baseline; color: rgb(51, 51, 51); font-family: Arial, Helvetica, sans-serif; line-height: 12px; text-align: center; background-image: none; background-attachment: scroll; background-size: initial; background-origin: initial; background-clip: initial; background-position: 0% 0%; background-repeat: repeat;\"><div style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; text-align: justify; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">&nbsp;</div><div style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; text-align: justify; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\"><span style=\"font-family: tahoma, arial, helvetica, sans-serif; font-size: medium;\"><strong style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: 13px; vertical-align: baseline; background: transparent;\">&nbsp;</strong></span></div><div style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; text-align: justify; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\"><span style=\"font-family: tahoma, arial, helvetica, sans-serif; font-size: medium;\"><strong>Chất liệu:</strong>&nbsp;Da tổng hợp</span></div><div style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; text-align: justify; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">&nbsp;</div><div style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; text-align: justify; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">&nbsp;</div><div style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; text-align: justify; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\"><span style=\"font-family: tahoma, arial, helvetica, sans-serif; font-size: medium;\"><strong>Xuất xứ:&nbsp;</strong>Hongkong</span></div><div style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; text-align: justify; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">&nbsp;</div><div style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; text-align: justify; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">&nbsp;</div><div style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; text-align: justify; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\"><span style=\"font-family: tahoma, arial, helvetica, sans-serif; font-size: medium;\"><strong>Chiều <a href=\"https://www.sendo.vn/cao-got.htm\">cao gót</a>:</strong>&nbsp;8cm</span></div><div style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; text-align: justify; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">&nbsp;</div><div style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; text-align: justify; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">&nbsp;</div><div style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; text-align: justify; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\"><span style=\"font-family: tahoma, arial, helvetica, sans-serif; font-size: medium;\"><strong>Kiểu dáng:&nbsp;</strong>Thiết kế dạng <a href=\"https://www.sendo.vn/giay-cao.htm\">giày cao</a> gót, kiểu dáng sang trong và nổi bật, da mềm, đi rất &nbsp;&nbsp;</span></div><div style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; text-align: justify; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">&nbsp;</div><div style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; text-align: justify; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\"><span style=\"font-family: tahoma, arial, helvetica, sans-serif; font-size: medium;\">êm chân tạo sự thoải mái cho người đi</span></div><div style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; text-align: justify; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">&nbsp;</div><div style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; text-align: justify; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\"><span style=\"font-family: tahoma, arial, helvetica, sans-serif; font-size: medium;\">Thiết kế với tiêu chí nhẹ nhàng, thoải mái, tôn vinh nét duyên dáng của người phụ nữ hiện</span></div><div style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; text-align: justify; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">&nbsp;</div><div style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; text-align: justify; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\"><span style=\"font-family: tahoma, arial, helvetica, sans-serif; font-size: medium;\">đại.&nbsp;Phong cách trẻ trung, sang trọng, hiện đại, thích hợp cho phụ nữ văn phòng, nhân viên&nbsp;</span></div><div style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; text-align: justify; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">&nbsp;</div><div style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; text-align: justify; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\"><span style=\"font-family: tahoma, arial, helvetica, sans-serif; font-size: medium;\">công sở.</span></div><div style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; text-align: justify; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">&nbsp;</div><div style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; text-align: justify; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\"><span style=\"font-family: tahoma, arial, helvetica, sans-serif; font-size: medium;\"><strong style=\"color: rgb(0, 0, 0); font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; line-height: normal; text-align: start;\">&nbsp;</strong>Hàng cao cấp chính hãng không như hàng face, nhái trên thị trường, cho nên màu sắc,</span></div><div style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; text-align: justify; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">&nbsp;</div><div style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; text-align: justify; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\"><span style=\"font-family: tahoma, arial, helvetica, sans-serif; font-size: medium;\">kiểu dáng, chết liệu thực như hình mẫu</span></div><div style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; text-align: justify; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">&nbsp;</div><div style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; text-align: justify; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">&nbsp;</div><div style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; text-align: justify; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\"><span style=\"font-family: tahoma, arial, helvetica, sans-serif; font-size: medium;\"><strong style=\"color: rgb(0, 0, 0); font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; line-height: normal; text-align: start;\">&nbsp;</strong>Shop hỗ trợ miễn phí vận chuyển cho đơn hàng này.</span></div><div style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; text-align: justify; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">&nbsp;</div><div style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; text-align: justify; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">&nbsp;</div><div style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; text-align: justify; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\"><span style=\"font-family: tahoma, arial, helvetica, sans-serif; font-size: medium;\">Phương thức thanh toán trả sau: COD - Thanh toán khi nhận hàng theo địa chỉ bạn cung cấp</span></div><div style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; text-align: justify; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">&nbsp;</div><div style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; text-align: justify; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">&nbsp;</div><div style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; text-align: justify; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\"><span style=\"font-family: tahoma, arial, helvetica, sans-serif; font-size: medium;\"><strong>Màu sắc: Có 3 màu</strong></span></div>&nbsp;<div style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; text-align: justify; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\"><span style=\"font-family: tahoma, arial, helvetica, sans-serif; font-size: medium;\"><strong style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: 13px; vertical-align: baseline; background: transparent;\">&nbsp;</strong></span></div><div style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; text-align: justify; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\"><span style=\"font-family: tahoma, arial, helvetica, sans-serif; font-size: medium;\"><strong style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: 13px; vertical-align: baseline; background: transparent;\">&nbsp;</strong></span></div><div style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px none; outline: none 0px; vertical-align: baseline; background-image: none; background-attachment: scroll; background-size: initial; background-origin: initial; background-clip: initial; background-position: 0% 0%; background-repeat: repeat;\"><span style=\"font-family: tahoma, arial, helvetica, sans-serif; font-size: medium;\"><strong style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: 13px; vertical-align: baseline; background: transparent;\"><img alt=\"Giày cao gót mũi nhọn màu hồng be quý phái CG-666H 2\" src=\"https://media3.sendo.vn/img/2014/10_30/giay-cao-got-mui-nhon-mau-hong-be-quy-phai-cg-666h-1m4G3-6661_2jpln7s3ncmfr_simg_d0daf0_800x1200_max.png\" style=\"display: block; margin-left: auto; margin-right: auto;\" /></strong></span></div><div style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; text-align: justify; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\"><span style=\"font-family: tahoma, arial, helvetica, sans-serif; font-size: medium;\"><strong style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: 13px; vertical-align: baseline; background: transparent;\">&nbsp;</strong></span></div><div style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; text-align: justify; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">&nbsp;</div><div style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; text-align: justify; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">&nbsp;</div><div style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; vertical-align: baseline; text-align: justify; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">&nbsp;</div>&nbsp;<p style=\"box-sizing: border-box; margin: 0px; padding: 6px 0px; border: 0px none; outline: none 0px; font-size: 13px; vertical-align: baseline; line-height: normal; color: rgb(44, 43, 43); font-family: arial, verdana, tahoma; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\"><span style=\"font-family: tahoma, arial, helvetica, sans-serif; font-size: medium;\"><a href=\"http://www.sendo.vn/shop/thoi-trang-han-quoc/thoi-trang-nu/giay-dep-nu/\"><strong style=\"box-sizing: border-box; margin: 0px; padding: 0px; border: 0px; outline: 0px; font-size: 13px; vertical-align: baseline; background: transparent;\"><img alt=\"Giày cao gót mũi nhọn màu hồng be quý phái CG-666H 3\" src=\"https://media3.sendo.vn/img1/2014/12_29/giay-cao-got-mui-nhon-mau-hong-be-quy-phai-cg-666h-1m4G3-666h2jltlnkh4ispcd0daf0800x1200max_2k4eckrt284ib_simg_d0daf0_800x1200_max.png\" /></strong></a></span></p></div><p>&nbsp;</p><p><span style=\"font-family: tahoma, arial, helvetica, sans-serif; font-size: medium;\"><strong>Hình ảnh sản phẩm:</strong></span></p><p>&nbsp;</p><p>&nbsp;</p><p style=\"text-align: center;\"><span style=\"font-family: tahoma, arial, helvetica, sans-serif; font-size: medium;\"><strong><img alt=\"Giày cao gót mũi nhọn màu hồng be quý phái CG-666H 4\" src=\"https://media3.sendo.vn/img1/2014/12_29/giay-cao-got-mui-nhon-mau-hong-be-quy-phai-cg-666h-1m4G3-giaycaogotcg666hcopy_2k4etslal2erb_simg_d0daf0_800x1200_max.png\" /></strong></span></p><p style=\"text-align: center;\">&nbsp;</p><p style=\"text-align: center;\">&nbsp;</p><p style=\"text-align: center;\">&nbsp;</p><p style=\"text-align: center;\"><span style=\"font-family: tahoma, arial, helvetica, sans-serif; font-size: medium;\"><strong><img alt=\"Giày cao gót mũi nhọn màu hồng be quý phái CG-666H 5\" src=\"https://media3.sendo.vn/img1/2014/12_29/giay-cao-got-mui-nhon-mau-hong-be-quy-phai-cg-666h-1m4G3-giaycaogotcg666h1copy_2k4ett531ojpn_simg_d0daf0_800x1200_max.png\" /></strong></span></p><p style=\"text-align: center;\">&nbsp;</p><p style=\"text-align: center;\">&nbsp;</p><p style=\"text-align: center;\">&nbsp;</p><p style=\"text-align: center;\">&nbsp;</p><p style=\"text-align: center;\">&nbsp;</p><p style=\"text-align: center;\"><span style=\"font-family: tahoma, arial, helvetica, sans-serif; font-size: medium;\"><strong><img alt=\"Giày cao gót mũi nhọn màu hồng be quý phái CG-666H 6\" src=\"https://media3.sendo.vn/img1/2014/12_29/giay-cao-got-mui-nhon-mau-hong-be-quy-phai-cg-666h-1m4G3-giaycaogotcg666h2copy_2k4etst9jntgd_simg_d0daf0_800x1200_max.png\" /></strong></span></p><p style=\"text-align: center;\">&nbsp;</p><p style=\"text-align: center;\">&nbsp;</p><p style=\"text-align: center;\">&nbsp;</p><p style=\"text-align: center;\">&nbsp;</p><p style=\"text-align: center;\"><span style=\"font-family: tahoma, arial, helvetica, sans-serif; font-size: medium;\"><strong><img alt=\"Giày cao gót mũi nhọn màu hồng be quý phái CG-666H 7\" src=\"https://media3.sendo.vn/img1/2014/12_29/giay-cao-got-mui-nhon-mau-hong-be-quy-phai-cg-666h-1m4G3-giaycaogotcg666h3copy_2k4ettc62g3q3_simg_d0daf0_800x1200_max.png\" /></strong></span></p><p style=\"text-align: center;\">&nbsp;</p><p style=\"text-align: center;\">&nbsp;</p><p style=\"text-align: center;\"><span style=\"font-family: tahoma, arial, helvetica, sans-serif; font-size: medium;\"><strong><img alt=\"Giày cao gót mũi nhọn màu hồng be quý phái CG-666H 8\" src=\"https://media3.sendo.vn/img1/2014/12_29/giay-cao-got-mui-nhon-mau-hong-be-quy-phai-cg-666h-1m4G3-giaycaogotcg666h4copy_2k4ett6ldee8a_simg_d0daf0_800x1200_max.png\" /></strong></span></p><p style=\"text-align: center;\">&nbsp;</p><p style=\"text-align: center;\">&nbsp;</p><p style=\"text-align: center;\">&nbsp;</p><p style=\"text-align: center;\">&nbsp;</p><p style=\"text-align: center;\">&nbsp;</p><p style=\"text-align: center;\">&nbsp;</p><p style=\"text-align: center;\">&nbsp;</p><p style=\"text-align: center;\">&nbsp;</p><p style=\"text-align: center;\">&nbsp;</p><p style=\"text-align: center;\">&nbsp;</p><p style=\"text-align: center;\"><span style=\"font-family: tahoma, arial, helvetica, sans-serif; font-size: medium;\"><strong><img alt=\"Giày cao gót mũi nhọn màu hồng be quý phái CG-666H 9\" src=\"https://media3.sendo.vn/img1/2014/12_29/giay-cao-got-mui-nhon-mau-hong-be-quy-phai-cg-666h-1m4G3-giaycaogotcg666h8copy_2k4f0070d1ec7_simg_d0daf0_800x1200_max.png\" /><img alt=\"Giày cao gót mũi nhọn màu hồng be quý phái CG-666H 10\" src=\"https://media3.sendo.vn/img1/2014/12_29/giay-cao-got-mui-nhon-mau-hong-be-quy-phai-cg-666h-1m4G3-giaycaogotcg666h9copy_2k4f01agfhf8a_simg_d0daf0_800x1200_max.png\" /></strong></span></p><p style=\"text-align: center;\">&nbsp;</p><p style=\"text-align: center;\"><span style=\"font-family: tahoma, arial, helvetica, sans-serif; font-size: medium;\"><strong style=\"font-family: verdana, geneva; font-size: medium;\"><img alt=\"Giày cao gót mũi nhọn màu hồng be quý phái CG-666H 11\" src=\"https://media3.sendo.vn/img1/2014/12_29/giay-cao-got-mui-nhon-mau-hong-be-quy-phai-cg-666h-1m4G3-giaycaogotcg666h7copy_2k4ettnpidlkl_simg_d0daf0_800x1200_max.png\" /></strong></span></p>', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_rows (id, listcatid, user_id, addtime, edittime, status, publtime, exptime, archive, product_code, product_number, product_price, price_config, money_unit, product_unit, product_weight, weight_unit, discount_id, homeimgfile, homeimgthumb, homeimgalt, otherimage, imgposition, copyright, gift_from, gift_to, inhome, allowed_comm, allowed_rating, ratingdetail, allowed_send, allowed_print, allowed_save, hitstotal, hitscm, hitslm, num_sell, showprice, vi_title, vi_alias, vi_hometext, vi_bodytext, vi_gift_content, vi_address) VALUES('7', '11', '1', '1432369124', '1432369433', '1', '1432369124', '0', '2', 'S000007', '50', '120000', '', 'VND', '1', '150', 'g', '0', '2015_05/ao-thun-nu-4-chieu-xb417.jpg', '1', '', '', '0', '0', '0', '0', '1', '4', '1', '0', '1', '1', '1', '2', '0', '0', '0', '1', 'áo thun nữ', 'ao-thun-nu', 'áo thun nữ', '<table cellpadding=\"0\" cellspacing=\"0\" class=\"technical\">	<tbody>		<tr class=\"data even\">			<td class=\"padding_right\">&nbsp;</td>			<td>&nbsp;</td>		</tr>		<tr class=\"data odd\">			<td class=\"padding_right\"><span class=\"label name\">Bảo hành:</span> Không có</td>			<td><span class=\"label name\">Nguồn gốc:</span> Chính hãng</td>		</tr>		<tr class=\"data odd\">			<td class=\"padding_right\"><span class=\"name\">Kiểu dáng:</span> Cộc tay,</td>			<td><span class=\"name\">Cổ áo:</span> Cổ tròn</td>		</tr>		<tr class=\"data even\">			<td class=\"padding_right\"><span class=\"name\">Kích cỡ:</span> Size S</td>			<td><span class=\"name\">Chất liệu:</span> <a class=\"text_link\" href=\"http://www.vatgia.com/s/thun\">Thun</a></td>		</tr>		<tr class=\"data odd\">			<td class=\"padding_right\"><span class=\"name\">Màu:</span> Trắng, Tím, Xám,</td>			<td class=\"name\" colspan=\"2\">&nbsp;</td>		</tr>	</tbody></table><div><b>Thông tin về sản phẩm:</b></div><p><img alt=\"\" src=\"http://g.vatgia.vn/gallery_img/15/medium_syj1394438249.jpg\" style=\"margin: 2px; cursor: pointer;\" /><img alt=\"\" src=\"http://g.vatgia.vn/gallery_img/15/medium_kdg1388409143.jpg\" style=\"margin: 2px; cursor: pointer;\" /><img alt=\"\" src=\"http://g.vatgia.vn/gallery_img/15/medium_pxf1388409144.jpg\" style=\"margin: 2px; cursor: pointer;\" /></p>', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_rows (id, listcatid, user_id, addtime, edittime, status, publtime, exptime, archive, product_code, product_number, product_price, price_config, money_unit, product_unit, product_weight, weight_unit, discount_id, homeimgfile, homeimgthumb, homeimgalt, otherimage, imgposition, copyright, gift_from, gift_to, inhome, allowed_comm, allowed_rating, ratingdetail, allowed_send, allowed_print, allowed_save, hitstotal, hitscm, hitslm, num_sell, showprice, vi_title, vi_alias, vi_hometext, vi_bodytext, vi_gift_content, vi_address) VALUES('11', '15', '1', '1432607113', '1432629575', '1', '1432607113', '0', '2', 'S000011', '20', '100000', '', 'VND', '2', '250', 'g', '1', '2015_05/giay-bup-be-ngoi-sao-nhap-khau.jpg', '1', 'GIÀY BÚP BÊ NGÔI SAO NHẬP KHẨU', '', '0', '0', '0', '0', '1', '4', '1', '0', '1', '1', '1', '2', '0', '0', '0', '1', 'GIÀY BÚP BÊ NGÔI SAO NHẬP KHẨU', 'GIAY-BUP-BE-NGOI-SAO-NHAP-KHAU', 'GIÀY BÚP BÊ NGÔI SAO NHẬP KHẨU', '<img alt=\"GIÀY BÚP BÊ NGÔI SAO NHẬP KHẨU G16 -HÀNG CAO CẤP LOẠI 1 3\" src=\"https://media3.sendo.vn/img1/2015/4_28/giay-bup-be-ngoi-sao-nhap-khau-g16-hang-cao-cap-loai-1-1m4G3-9b82fa_simg_d0daf0_800x1200_max.jpg\" /><br  /><br  /><img alt=\"GIÀY BÚP BÊ NGÔI SAO NHẬP KHẨU G16 -HÀNG CAO CẤP LOẠI 1 4\" src=\"https://media3.sendo.vn/img1/2015/4_28/giay-bup-be-ngoi-sao-nhap-khau-g16-hang-cao-cap-loai-1-1m4G3-f802da_simg_d0daf0_800x1200_max.jpg\" /><br  /><br  /><img alt=\"GIÀY BÚP BÊ NGÔI SAO NHẬP KHẨU G16 -HÀNG CAO CẤP LOẠI 1 5\" src=\"https://media3.sendo.vn/img1/2015/4_28/giay-bup-be-ngoi-sao-nhap-khau-g16-hang-cao-cap-loai-1-1m4G3-166dba_simg_d0daf0_800x1200_max.jpg\" /><br  /><img alt=\"GIÀY BÚP BÊ NGÔI SAO NHẬP KHẨU G16 -HÀNG CAO CẤP LOẠI 1 6\" src=\"https://media3.sendo.vn/img1/2015/4_28/giay-bup-be-ngoi-sao-nhap-khau-g16-hang-cao-cap-loai-1-1m4G3-62836f_simg_d0daf0_800x1200_max.jpg\" /><br  /><img alt=\"GIÀY BÚP BÊ NGÔI SAO NHẬP KHẨU G16 -HÀNG CAO CẤP LOẠI 1 7\" src=\"https://media3.sendo.vn/img1/2015/4_28/giay-bup-be-ngoi-sao-nhap-khau-g16-hang-cao-cap-loai-1-1m4G3-e719fa_simg_d0daf0_800x1200_max.jpg\" /><br  /><br  /><img alt=\"GIÀY BÚP BÊ NGÔI SAO NHẬP KHẨU G16 -HÀNG CAO CẤP LOẠI 1 8\" src=\"https://media3.sendo.vn/img1/2015/4_28/giay-bup-be-ngoi-sao-nhap-khau-g16-hang-cao-cap-loai-1-1m4G3-c77041_simg_d0daf0_800x1200_max.jpg\" /><br  /><img alt=\"GIÀY BÚP BÊ NGÔI SAO NHẬP KHẨU G16 -HÀNG CAO CẤP LOẠI 1 9\" src=\"https://media3.sendo.vn/img1/2015/4_28/giay-bup-be-ngoi-sao-nhap-khau-g16-hang-cao-cap-loai-1-1m4G3-a9c5e4_simg_d0daf0_800x1200_max.jpg\" /><br  /><br  />&nbsp;', '', '');";
	
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_units (id, vi_title, vi_note) VALUES('1', 'cái', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_units (id, vi_title, vi_note) VALUES('2', 'đôi', '');";
	
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_tabs (id, icon, content, weight, active, vi_title) VALUES('1', '', 'content_detail', '1', '1', 'Chi tiết sản phẩm');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_tabs (id, icon, content, weight, active, vi_title) VALUES('2', '', 'content_comments', '2', '1', 'Bình luận');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_tabs (id, icon, content, weight, active, vi_title) VALUES('3', '', 'content_rate', '3', '1', 'Đánh giá');";
	
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_tags (tid, vi_numpro, vi_alias, vi_image, vi_description, vi_keywords) VALUES('1', '1', 'thời-trang', '', '', 'thời trang');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_tags (tid, vi_numpro, vi_alias, vi_image, vi_description, vi_keywords) VALUES('2', '1', 'sang-trọng', '', '', 'sang trọng');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_tags (tid, vi_numpro, vi_alias, vi_image, vi_description, vi_keywords) VALUES('3', '1', 'phù-hợp', '', '', 'phù hợp');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_tags (tid, vi_numpro, vi_alias, vi_image, vi_description, vi_keywords) VALUES('4', '1', 'đi-chơi', '', '', 'đi chơi');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_tags (tid, vi_numpro, vi_alias, vi_image, vi_description, vi_keywords) VALUES('5', '1', 'áo-sơ-mi', '', '', 'áo sơ mi');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_tags (tid, vi_numpro, vi_alias, vi_image, vi_description, vi_keywords) VALUES('6', '1', 'mũi-nhọn', '', '', 'mũi nhọn');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_tags (tid, vi_numpro, vi_alias, vi_image, vi_description, vi_keywords) VALUES('7', '1', 'búp-bê', '', '', 'búp bê');";
	
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_tags_id (id, tid, vi_keyword) VALUES('1', '1', 'thời trang');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_tags_id (id, tid, vi_keyword) VALUES('1', '2', 'sang trọng');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_tags_id (id, tid, vi_keyword) VALUES('1', '3', 'phù hợp');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_tags_id (id, tid, vi_keyword) VALUES('1', '4', 'đi chơi');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_tags_id (id, tid, vi_keyword) VALUES('6', '5', 'áo sơ mi');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_tags_id (id, tid, vi_keyword) VALUES('10', '6', 'mũi nhọn');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_tags_id (id, tid, vi_keyword) VALUES('11', '7', 'búp bê');";
	
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('1', '0', '', '1', '1', '0', 'viewcat_page_list', '6', '6,7,8,9,10,11', '1', '1', '1432623061', '1432623061', '0', '1', '0', 'Thương hiệu', 'Thuong-hieu', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('2', '0', '', '2', '8', '0', 'viewcat_page_list', '12', '12,13,15,16,17,18,19,20,21,22,23,24', '1', '1', '1432623083', '1432623083', '0', '1', '0', 'Màu sắc', 'Mau-sac', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('3', '0', '', '3', '21', '0', 'viewcat_page_list', '15', '25,26,27,28,29,30,31,32,33,34,35,36,37,38,39', '1', '1', '1432623101', '1432623101', '0', '1', '0', 'Kích thước', 'Kich-thuoc', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('4', '0', '', '4', '37', '0', 'viewcat_page_list', '8', '40,41,42,43,44,45,46,47', '1', '1', '1432623118', '1432623118', '0', '1', '0', 'Chất liệu', 'Chat-lieu', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('5', '0', '', '5', '46', '0', 'viewcat_page_list', '10', '48,49,50,51,52,53,54,55,56,57', '1', '1', '1432623133', '1432623133', '0', '1', '0', 'Xuất xứ', 'Xuat-xu', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('6', '1', '', '1', '2', '1', 'viewcat_page_list', '0', '', '1', '1', '1432626862', '1432626862', '0', '1', '0', 'Việt Tiến', 'Viet-Tien', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('7', '1', '', '2', '3', '1', 'viewcat_page_list', '0', '', '1', '1', '1432626882', '1432626882', '3', '1', '0', 'ZARA', 'ZARA', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('8', '1', '', '3', '4', '1', 'viewcat_page_list', '0', '', '1', '1', '1432626899', '1432626899', '0', '1', '0', 'MATTANA', 'MATTANA', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('9', '1', '', '4', '5', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627013', '1432627013', '0', '1', '0', 'KELVIN', 'KELVIN', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('10', '1', '', '5', '6', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627027', '1432627027', '0', '1', '0', 'THÁI TUẤN', 'THAI-TUAN', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('11', '1', '', '6', '7', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627053', '1432627053', '0', '1', '0', 'VICTORIA SECRECT', 'VICTORIA-SECRECT', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('12', '2', '', '1', '9', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627064', '1432627064', '0', '1', '0', 'ĐỎ', 'DO', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('13', '2', '', '2', '10', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627070', '1432627070', '0', '1', '0', 'VÀNG', 'VANG', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('16', '2', '', '4', '12', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627102', '1432627102', '1', '1', '0', 'HỒNG PHẤN', 'HONG-PHAN', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('15', '2', '', '3', '11', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627095', '1432627095', '0', '1', '0', 'XANH NGỌC', 'XANH-NGOC', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('17', '2', '', '5', '13', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627107', '1432627107', '0', '1', '0', 'XANH RÊU', 'XANH-REU', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('18', '2', '', '6', '14', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627112', '1432627112', '0', '1', '0', 'TÍM', 'TIM', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('19', '2', '', '7', '15', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627123', '1432627123', '0', '1', '0', 'XÁM', 'XAM', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('20', '2', '', '8', '16', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627135', '1432627135', '0', '1', '0', 'XANH NƯỚC BIỂN', 'XANH-NUOC-BIEN', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('21', '2', '', '9', '17', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627148', '1432627148', '0', '1', '0', 'CAM', 'CAM', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('22', '2', '', '10', '18', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627153', '1432627153', '0', '1', '0', 'BẠC', 'BAC', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('23', '2', '', '11', '19', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627160', '1432627160', '0', '1', '0', 'MÀU DA', 'MAU-DA', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('24', '2', '', '12', '20', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627182', '1432627182', '2', '1', '0', 'ĐEN', 'DEN', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('25', '3', '', '1', '22', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627201', '1432627201', '0', '1', '0', 'F', 'F', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('26', '3', '', '2', '23', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627210', '1432627210', '0', '1', '0', 'L', 'L', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('27', '3', '', '3', '24', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627215', '1432627215', '0', '1', '0', 'M', 'M', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('28', '3', '', '4', '25', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627219', '1432627219', '0', '1', '0', 'S', 'S', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('29', '3', '', '5', '26', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627223', '1432627223', '0', '1', '0', 'XL', 'XL', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('30', '3', '', '6', '27', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627241', '1432627241', '0', '1', '0', 'XXL', 'XXL', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('31', '3', '', '7', '28', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627250', '1432627250', '0', '1', '0', 'XXXL', 'XXXL', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('32', '3', '', '8', '29', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627259', '1432627259', '2', '1', '0', '35', '35', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('33', '3', '', '9', '30', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627264', '1432627264', '2', '1', '0', '36', '36', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('34', '3', '', '10', '31', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627269', '1432627269', '2', '1', '0', '37', '37', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('35', '3', '', '11', '32', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627274', '1432627274', '3', '1', '0', '38', '38', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('36', '3', '', '12', '33', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627279', '1432627279', '0', '1', '0', '39', '39', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('37', '3', '', '13', '34', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627284', '1432627284', '0', '1', '0', '40', '40', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('38', '3', '', '14', '35', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627291', '1432627291', '0', '1', '0', '41', '41', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('39', '3', '', '15', '36', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627296', '1432627296', '0', '1', '0', '42', '42', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('40', '4', '', '1', '38', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627339', '1432627339', '0', '1', '0', 'COTTON', 'COTTON', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('41', '4', '', '2', '39', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627346', '1432627346', '0', '1', '0', 'DẠ', 'DA', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('42', '4', '', '3', '40', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627364', '1432627364', '0', '1', '0', 'JEANS', 'JEANS', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('43', '4', '', '4', '41', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627369', '1432627369', '0', '1', '0', 'BÒ', 'BO', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('44', '4', '', '5', '42', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627378', '1432627378', '0', '1', '0', 'LANH', 'LANH', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('45', '4', '', '6', '43', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627385', '1432627385', '0', '1', '0', 'TƠ TẰM', 'TO-TAM', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('46', '4', '', '7', '44', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627399', '1432627399', '0', '1', '0', 'THUN', 'THUN', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('47', '4', '', '8', '45', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627407', '1432627407', '0', '1', '0', 'LỤA', 'LUA', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('48', '5', '', '1', '47', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627418', '1432627418', '0', '1', '0', 'VIỆT NAM', 'VIET-NAM', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('49', '5', '', '2', '48', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627425', '1432627425', '0', '1', '0', 'HÀN QUỐC', 'HAN-QUOC', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('50', '5', '', '3', '49', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627519', '1432627519', '0', '1', '0', 'ĐỨC', 'DUC', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('51', '5', '', '4', '50', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627528', '1432627528', '1', '1', '0', 'NHẬT BẢN', 'NHAT-BAN', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('52', '5', '', '5', '51', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627541', '1432627541', '0', '1', '0', 'THÁI LAN', 'THAI-LAN', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('53', '5', '', '6', '52', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627553', '1432627553', '1', '1', '0', 'HONGKONG', 'HONGKONG', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('54', '5', '', '7', '53', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627565', '1432627565', '0', '1', '0', 'TRUNG QUỐC', 'TRUNG-QUOC', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('55', '5', '', '8', '54', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627573', '1432627573', '0', '1', '0', 'PHÁP', 'PHAP', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('56', '5', '', '9', '55', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627579', '1432627579', '0', '1', '0', 'ANH', 'ANH', '', '');";
	$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_group (groupid, parentid, image, weight, sort, lev, viewgroup, numsubgroup, subgroupid, inhome, indetail, add_time, edit_time, numpro, in_order, is_require, vi_title, vi_alias, vi_description, vi_keywords) VALUES('57', '5', '', '10', '56', '1', 'viewcat_page_list', '0', '', '1', '1', '1432627617', '1432627617', '0', '1', '0', 'AUSTRALIA', 'AUSTRALIA', '', '');";
	
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
