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
$query = $db->query( "select table_name from all_tables WHERE table_name = '" . strtoupper( $db_config['prefix'] . "_" . $lang . "_" . $module_data ) . "' OR table_name = '" . strtoupper( $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows" ) . "'" );
while( $row = $query->fetch() )
{
	$sql_drop_module[] = 'drop table ' . $row['table_name'] . ' cascade constraints PURGE';
}

$query = $db->query( "select sequence_name from user_sequences WHERE sequence_name LIKE '" . strtoupper( "SNV_" . $lang . "_" . $module_data . "_%" ) . "'" );
while( $row = $query->fetch() )
{
	$sql_drop_module[] = 'drop SEQUENCE ' . $row['sequence_name'];
}

$sql_create_module = $sql_drop_module;

$sql_create_module[] = "CREATE TABLE " . $db_config["prefix"] . "_" . $lang . "_" . $module_data . " (
 vid NUMBER(5,0) DEFAULT NULL,
 question VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
 link VARCHAR2(255 CHAR) DEFAULT '',
 acceptcm NUMBER(11,0) DEFAULT 1 NOT NULL ENABLE,
 admin_id NUMBER(8,0) DEFAULT 0 NOT NULL ENABLE,
 groups_view VARCHAR2(255 CHAR) DEFAULT '',
 publ_time NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
 exp_time NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
 act NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
 primary key (vid),
 CONSTRAINT cnv_" . $lang . "_" . $module_data . "_question UNIQUE (question)
)";

$sql_create_module[] = 'create sequence SNV_' . strtoupper( $lang . '_' . $module_data ) . '_CAT MINVALUE 10';

$sql_create_module[] = 'CREATE OR REPLACE TRIGGER TNV_' . strtoupper( $lang . '_' . $module_data ) . '_CAT
 BEFORE INSERT ON ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '
 FOR EACH ROW WHEN (new.vid is null)
	BEGIN
	 SELECT SNV_' . strtoupper( $lang . '_' . $module_data ) . '_CAT.nextval INTO :new.vid FROM DUAL;
	END TNV_' . strtoupper( $lang . '_' . $module_data ) . '_CAT;';

$sql_create_module[] = "CREATE TABLE " . $db_config["prefix"] . "_" . $lang . "_" . $module_data . "_rows (
 id NUMBER(8,0) DEFAULT NULL,
 vid NUMBER(5,0) DEFAULT 0 NOT NULL ENABLE,
 title VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
 url VARCHAR2(255 CHAR) DEFAULT '',
 hitstotal NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
 primary key (id),
 CONSTRAINT cnv_" . $lang . "_" . $module_data . "_vidtitle UNIQUE (vid,title)
)";
$sql_create_module[] = 'create sequence SNV_' . strtoupper( $lang . '_' . $module_data ) . '_ROW MINVALUE 100';

$sql_create_module[] = 'CREATE OR REPLACE TRIGGER TNV_' . strtoupper( $lang . '_' . $module_data ) . '_ROW
 BEFORE INSERT ON ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_rows
 FOR EACH ROW WHEN (new.id is null)
	BEGIN
	 SELECT SNV_' . strtoupper( $lang . '_' . $module_data . '_row' ) . '.nextval INTO :new.id FROM DUAL;
	END TNV_' . strtoupper( $lang . '_' . $module_data ) . '_ROW;';