<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

$sql_drop_module = array();
$query = $db->query( "select table_name from all_tables WHERE table_name = '" . strtoupper( $db_config['prefix'] . "_" . $lang . "_comments" ) . "'" );
while( $row = $query->fetch() )
{
	$sql_drop_module[] = 'drop table ' . $row['table_name'] . ' cascade constraints PURGE';
}

$query = $db->query( "select sequence_name from user_sequences WHERE sequence_name = '" . strtoupper( "SNV_" . $lang . "_comments" ) . "'" );
while( $row = $query->fetch() )
{
	$sql_drop_module[] = 'drop SEQUENCE ' . $row['sequence_name'];
}
$sql_create_module = $sql_drop_module;

$sql_create_module[] = "CREATE TABLE " . $db_config["prefix"] . "_" . $lang . "_comments (
	 cid NUMBER(8,0) DEFAULT NULL,
	 module VARCHAR2(55 CHAR) NOT NULL ENABLE,
	 area NUMBER(4,0) DEFAULT 0 NOT NULL ENABLE,
	 id NUMBER(8,0) DEFAULT 0 NOT NULL ENABLE,
	 pid NUMBER(8,0) DEFAULT 0 NOT NULL ENABLE,
	 content VARCHAR2(4000 CHAR) NOT NULL ENABLE,
	 post_time NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	 userid NUMBER(8,0) DEFAULT 0 NOT NULL ENABLE,
	 post_name VARCHAR2(100 CHAR) DEFAULT '' NOT NULL ENABLE,
	 post_email VARCHAR2(100 CHAR) DEFAULT '' NOT NULL ENABLE,
	 post_ip VARCHAR2(15 CHAR) DEFAULT '' NOT NULL ENABLE,
	 status NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	 likes NUMBER(8,0) DEFAULT 0 NOT NULL ENABLE,
	 dislikes NUMBER(8,0) DEFAULT 0 NOT NULL ENABLE,
	 primary key (cid)
)";

$sql_create_module[] = 'create sequence SNV_' . strtoupper( $lang . '_comments' ) . '_CMEN';

$sql_create_module[] = 'CREATE OR REPLACE TRIGGER TNV_' . strtoupper( $lang . '_comments' ) . '_CMEN
 BEFORE INSERT ON ' . $db_config['prefix'] . '_' . $lang . '_comments
 FOR EACH ROW WHEN (new.cid is null)
	BEGIN
	 SELECT SNV_' . strtoupper( $lang . '_' . $module_data ) . '_CMEN.nextval INTO :new.cid FROM DUAL;
	END TNV_' . strtoupper( $lang . '_' . $module_data ) . '_CMEN;';

$sql_create_module[] = "CREATE INDEX inv_" . $lang . "_cid ON " . $db_config['prefix'] . "_" . $lang . "_comments(module,area,id) TABLESPACE USERS";
$sql_create_module[] = "CREATE INDEX inv_" . $lang . "_cposttime ON " . $db_config['prefix'] . "_" . $lang . "_comments(post_time) TABLESPACE USERS";