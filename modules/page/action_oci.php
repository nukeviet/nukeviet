<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sat, 28 Dec 2013 12:56:09 GMT
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) )	die( 'Stop!!!' );

$sql_drop_module = array();
$query = $db->query( "select table_name from all_tables WHERE table_name = '" . strtoupper( $db_config['prefix'] . "_" . $lang . "_" . $module_data ) . "'" );
while( $row = $query->fetch() )
{
	$sql_drop_module[] = 'drop table ' . $row['table_name'] . ' cascade constraints PURGE';
}

$query = $db->query( "select sequence_name from user_sequences WHERE sequence_name = '" . strtoupper( "SNV_" . $lang . "_" . $module_data ) . "'" );
while( $row = $query->fetch() )
{
	$sql_drop_module[] = 'drop SEQUENCE ' . $row['sequence_name'];
}

$sql_create_module = $sql_drop_module;

$sql_create_module[] = "CREATE TABLE " . $db_config["prefix"] . "_" . $lang . "_" . $module_data . " (
 id NUMBER(8,0) DEFAULT NULL,
 title VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
 alias VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
 image VARCHAR2(255 CHAR) DEFAULT '',
 imagealt VARCHAR2(255 CHAR) DEFAULT '',
 description VARCHAR2(4000 CHAR) DEFAULT '',
 bodytext CLOB NOT NULL ENABLE,
 keywords VARCHAR2(4000 CHAR) DEFAULT '',
 socialbutton NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
 activecomm VARCHAR2(255 CHAR) DEFAULT '',
 facebookappid VARCHAR2(30 CHAR) DEFAULT '',
 layout_func VARCHAR2(100 CHAR) DEFAULT '',
 gid NUMBER(8,0) DEFAULT 0 NOT NULL ENABLE,
 weight NUMBER(5,0) DEFAULT 0 NOT NULL ENABLE,
 admin_id NUMBER(8,0) DEFAULT 0 NOT NULL ENABLE,
 add_time NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
 edit_time NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
 status NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
 primary key (id),
 CONSTRAINT cnv_" . $lang . "_" . $module_data . "_alias UNIQUE (alias)
)";

$sql_create_module[] = 'create sequence SNV_' . strtoupper( $lang . '_' . $module_data ) . ' MINVALUE 10';

$sql_create_module[] = 'CREATE OR REPLACE TRIGGER TNV_' . strtoupper( $lang . '_' . $module_data ) . '
 BEFORE INSERT ON ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '
 FOR EACH ROW WHEN (new.id is null)
	BEGIN
	 SELECT SNV_' . strtoupper( $lang . '_' . $module_data ) . '.nextval INTO :new.id FROM DUAL;
	END TNV_' . strtoupper( $lang . '_' . $module_data ) . ';';

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