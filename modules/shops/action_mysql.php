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
	 DROP ' . $lang . '_description,
	 DROP ' . $lang . '_keywords,
	 DROP ' . $lang . '_note,
	 DROP ' . $lang . '_hometext,
	 DROP ' . $lang . '_bodytext,
	 DROP ' . $lang . '_address,
	 DROP ' . $lang . '_warranty,
	 DROP ' . $lang . '_promotional';

	$sql_drop_module[] = 'ALTER TABLE ' . $db_config['prefix'] . '_' . $module_data . '_catalogs
	 DROP ' . $lang . '_title,
	 DROP ' . $lang . '_alias,
	 DROP ' . $lang . '_description,
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

	$sql_drop_module[] = 'ALTER TABLE ' . $db_config['prefix'] . '_' . $module_data . '_sources
	 DROP ' . $lang . '_title';

	$sql_drop_module[] = 'ALTER TABLE ' . $db_config['prefix'] . '_' . $module_data . '_units
	 DROP ' . $lang . '_title,
	 DROP ' . $lang . '_note';
}
elseif( $op != 'setup' )
{
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_block';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_block_cat';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_catalogs';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_group';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_orders';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_payment';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_transaction';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_rows';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_sources';
	$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_units';
	$set_lang_data = '';
}

$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_money_' . $lang;

$sql_create_module = $sql_drop_module;

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_catalogs (
 catid mediumint(8) unsigned NOT NULL auto_increment,
 parentid mediumint(8) unsigned NOT NULL default '0',
 image varchar(255) NOT NULL default '',
 thumbnail varchar(255) NOT NULL default '',
 weight smallint(4) unsigned NOT NULL default '0',
 sort mediumint(8) NOT NULL default '0',
 lev smallint(4) NOT NULL default '0',
 viewcat varchar(50) NOT NULL default 'viewcat_page_new',
 numsubcat int(11) NOT NULL default '0',
 subcatid varchar(255) NOT NULL default '',
 inhome tinyint(1) unsigned NOT NULL default '0',
 numlinks tinyint(2) unsigned NOT NULL default '3',
 admins mediumtext NOT NULL,
 add_time int(11) unsigned NOT NULL default '0',
 edit_time int(11) unsigned NOT NULL default '0',
 groups_view varchar(255) NOT NULL default '',
 PRIMARY KEY (catid),
 KEY parentid (parentid)
) ENGINE=MyISAM";

$sql_create_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_catalogs ADD " . $lang . "_title VARCHAR( 255 ) NOT NULL DEFAULT ''";
$sql_create_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_catalogs ADD " . $lang . "_alias VARCHAR( 255 ) NOT NULL DEFAULT ''";
$sql_create_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_catalogs ADD " . $lang . "_description VARCHAR( 255 ) NOT NULL DEFAULT ''";
$sql_create_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_catalogs ADD " . $lang . "_keywords text NOT NULL";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_group (
 groupid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
 parentid mediumint(8) unsigned NOT NULL DEFAULT '0',
 cateid int(11) NOT NULL DEFAULT '0',
 image varchar(255) NOT NULL DEFAULT '',
 thumbnail varchar(255) NOT NULL DEFAULT '',
 weight smallint(4) unsigned NOT NULL DEFAULT '0',
 sort mediumint(8) NOT NULL DEFAULT '0',
 lev smallint(4) NOT NULL DEFAULT '0',
 viewgroup varchar(50) NOT NULL DEFAULT 'viewcat_page_new',
 numsubgroup int(11) NOT NULL DEFAULT '0',
 subgroupid varchar(255) NOT NULL DEFAULT '',
 inhome tinyint(1) unsigned NOT NULL DEFAULT '0',
 numlinks tinyint(2) unsigned NOT NULL DEFAULT '3',
 admins mediumtext NOT NULL,
 add_time int(11) unsigned NOT NULL DEFAULT '0',
 edit_time int(11) unsigned NOT NULL DEFAULT '0',
 numpro int(11) unsigned NOT NULL DEFAULT '0',
 PRIMARY KEY (groupid),
 KEY parentid (parentid)
) ENGINE=MyISAM ";
$sql_create_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_group ADD " . $lang . "_title VARCHAR( 255 ) NOT NULL DEFAULT ''";
$sql_create_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_group ADD " . $lang . "_alias VARCHAR( 255 ) NOT NULL DEFAULT ''";
$sql_create_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_group ADD " . $lang . "_description VARCHAR( 255 ) NOT NULL DEFAULT ''";
$sql_create_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_group ADD " . $lang . "_keywords text NOT NULL";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_rows (
 id int(11) unsigned NOT NULL AUTO_INCREMENT,
 listcatid int(11) NOT NULL DEFAULT '0',
 group_id varchar(255) NOT NULL DEFAULT '',
 user_id mediumint(8) NOT NULL DEFAULT '0',
 source_id mediumint(8) NOT NULL DEFAULT '0',
 addtime int(11) unsigned NOT NULL DEFAULT '0',
 edittime int(11) unsigned NOT NULL DEFAULT '0',
 status tinyint(4) NOT NULL DEFAULT '1',
 publtime int(11) unsigned NOT NULL DEFAULT '0',
 exptime int(11) unsigned NOT NULL DEFAULT '0',
 archive tinyint(1) unsigned NOT NULL DEFAULT '0',
 product_code varchar(255) NOT NULL DEFAULT '',
 product_number int(11) NOT NULL DEFAULT '0',
 product_price double NOT NULL DEFAULT '0',
 product_discounts int(11) NOT NULL DEFAULT '0',
 money_unit char(3) NOT NULL,
 product_unit int(11) NOT NULL,
 homeimgfile varchar(255) NOT NULL DEFAULT '',
 homeimgthumb tinyint(4) NOT NULL DEFAULT '0',
 homeimgalt varchar(255) NOT NULL,
 otherimage text NOT NULL,
 imgposition tinyint(1) NOT NULL DEFAULT '1',
 copyright tinyint(1) unsigned NOT NULL DEFAULT '0',
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
 showprice tinyint(2) NOT NULL DEFAULT '0',
 PRIMARY KEY (id),
 KEY listcatid (listcatid),
 KEY user_id (user_id),
 KEY publtime (publtime),
 KEY exptime (exptime)
) ENGINE=MyISAM";

$sql_create_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_rows ADD " . $lang . "_title VARCHAR( 255 ) NOT NULL DEFAULT '',
 ADD " . $lang . "_alias VARCHAR( 255 ) NOT NULL DEFAULT '',
 ADD " . $lang . "_description VARCHAR( 255 ) NOT NULL DEFAULT '',
 ADD " . $lang . "_keywords text NOT NULL,
 ADD " . $lang . "_note text NOT NULL,
 ADD " . $lang . "_hometext text NOT NULL,
 ADD " . $lang . "_bodytext mediumtext NOT NULL,
 ADD " . $lang . "_address text NOT NULL,
 ADD " . $lang . "_warranty text NOT NULL,
 ADD " . $lang . "_promotional text NOT NULL";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_sources (
 sourceid mediumint(8) unsigned NOT NULL auto_increment,
 link varchar(255) NOT NULL default '',
 logo varchar(255) NOT NULL default '',
 weight mediumint(8) unsigned NOT NULL default '0',
 add_time int(11) unsigned NOT NULL,
 edit_time int(11) unsigned NOT NULL,
 PRIMARY KEY (sourceid)
) ENGINE=MyISAM";

$sql_create_module[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $module_data . "_sources ADD " . $lang . "_title VARCHAR( 255 ) NOT NULL DEFAULT ''";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_block_cat (
 bid mediumint(8) unsigned NOT NULL auto_increment,
 adddefault tinyint(4) NOT NULL default '0',
 image varchar(255) NOT NULL,
 thumbnail varchar(255) NOT NULL,
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
 order_address text NOT NULL,
 order_phone varchar(20) NOT NULL,
 order_note text NOT NULL,
 listid text NOT NULL,
 listnum text NOT NULL,
 listprice text NOT NULL,
 user_id int(11) unsigned NOT NULL default '0',
 admin_id int(11) unsigned NOT NULL default '0',
 shop_id int(11) unsigned NOT NULL default '0',
 who_is int(2) unsigned NOT NULL default '0',
 unit_total char(3) NOT NULL,
 order_total double unsigned NOT NULL default '0',
 order_time int(11) unsigned NOT NULL default '0',
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

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_transaction (
 transaction_id int(11) NOT NULL AUTO_INCREMENT,
 transaction_time int(11) NOT NULL DEFAULT '0',
 transaction_status int(11) NOT NULL,
 order_id int(11) NOT NULL DEFAULT '0',
 userid int(11) NOT NULL DEFAULT '0',
 payment varchar(22) NOT NULL DEFAULT '0',
 payment_id varchar(22) NOT NULL DEFAULT '0',
 payment_time int(11) NOT NULL DEFAULT '0',
 payment_amount int(11) NOT NULL DEFAULT '0',
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

$data = array();
$data['image_size'] = '100x100';
$data['home_view'] = 'view_home_all';
$data['per_page'] = 20;
$data['per_row'] = 4;
$data['money_unit'] = 'VND';
$data['post_auto_member'] = 0;
$data['auto_check_order'] = 1;
$data['format_order_id'] = strtoupper( substr( $module_name, 0, 1 ) ) . '%06s';
$data['format_code_id'] = strtoupper( substr( $module_name, 0, 1 ) ) . '%06s';
$data['active_guest_order'] = 0;
$data['active_showhomtext'] = 1;
$data['active_order'] = 1;
$data['active_price'] = 1;
$data['active_order_number'] = 0;
$data['active_payment'] = 1;
$data['active_tooltip'] = 1;
$data['timecheckstatus'] = 0;
$data['show_product_code'] = 1;
$data['show_compare'] = 0;
$data['show_displays'] = 0;

foreach( $data as $config_name => $config_value )
{
	$sql_create_module[] = "REPLACE INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES('" . $lang . "', " . $db->quote( $module_name ) . ", " . $db->quote( $config_name ) . ", " . $db->quote( $config_value ) . ")";
}

if( ! empty( $set_lang_data ) )
{
	$numrow = $db->query( "SELECT count(*) FROM " . $db_config['prefix'] . "_" . $module_data . "_catalogs" )->fetchColumn();
	if( $numrow )
	{
		$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_catalogs SET " . $lang . "_title = " . $global_config['site_lang'] . "_title";
		$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_catalogs SET " . $lang . "_alias = " . $set_lang_data . "_alias";
		$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_catalogs SET " . $lang . "_description = " . $set_lang_data . "_description";
		$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_catalogs SET " . $lang . "_keywords = " . $set_lang_data . "_keywords";
	}

	$numrow = $db->query( "SELECT count(*) FROM " . $db_config['prefix'] . "_" . $module_data . "_rows" )->fetchColumn();
	if( $numrow )
	{
		$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_rows SET " . $lang . "_title = " . $set_lang_data . "_title";
		$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_rows SET " . $lang . "_alias = " . $set_lang_data . "_alias";
		$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_rows SET " . $lang . "_description = " . $set_lang_data . "_description";
		$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_rows SET " . $lang . "_keywords = " . $set_lang_data . "_keywords";
		$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_rows SET " . $lang . "_hometext = " . $set_lang_data . "_hometext";
		$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_rows SET " . $lang . "_bodytext = " . $set_lang_data . "_bodytext";
		$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_rows SET " . $lang . "_address = " . $set_lang_data . "_address";
		$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_rows SET " . $lang . "_warranty = " . $set_lang_data . "_warranty";
		$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_rows SET " . $lang . "_promotional = " . $set_lang_data . "_promotional";
		$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_rows SET " . $lang . "_note = " . $set_lang_data . "_note";
	}

	$numrow = $db->query( "SELECT count(*) FROM " . $db_config['prefix'] . "_" . $module_data . "_units" )->fetchColumn();
	if( $numrow )
	{
		$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_units SET " . $lang . "_title = " . $set_lang_data . "_title";
		$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_units SET " . $lang . "_note = " . $set_lang_data . "_note";
	}

	$numrow = $db->query( "SELECT count(*) FROM " . $db_config['prefix'] . "_" . $module_data . "_sources" )->fetchColumn();
	if( $numrow )
	{
		$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_sources SET " . $lang . "_title = " . $set_lang_data . "_title";
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
	}

	$sql_create_module[] = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_money_" . $lang . " SET exchange = '1'";
}

$sql_create_module[] = "REPLACE INTO " . $db_config['prefix'] . "_" . $module_data . "_money_" . $lang . " (id, code, currency, exchange) VALUES (840, 'USD', 'US Dollar', 21000)";
$sql_create_module[] = "REPLACE INTO " . $db_config['prefix'] . "_" . $module_data . "_money_" . $lang . " (id, code, currency, exchange) VALUES (704, 'VND', 'Vietnam Dong', 1)";

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