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

$count = $db->query( "select count(*) from all_tables where table_name='" . strtoupper( $db_config['prefix'] . "_" . $lang . "_" . $module_data ) . "'" )->fetchColumn();
if( $count )
{
	$sql_drop_module[] = 'drop table ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . ' cascade constraints PURGE';
	$sql_drop_module[] = 'drop SEQUENCE SNV_' . strtoupper( $lang . '_' . $module_data ) . '_ROWS';

	$sql_drop_module[] = 'drop table ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_tmp cascade constraints PURGE';
	$sql_drop_module[] = 'drop SEQUENCE SNV_' . strtoupper( $lang . '_' . $module_data ) . '_TMP';

	$sql_drop_module[] = 'drop table ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_categories cascade constraints PURGE';
	$sql_drop_module[] = 'drop SEQUENCE SNV_' . strtoupper( $lang . '_' . $module_data ) . '_CAT';

	$sql_drop_module[] = 'drop table ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_report cascade constraints PURGE';
	$sql_drop_module[] = 'drop table ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_config cascade constraints PURGE';

	$query = $db->query( "select count(*) from all_tables WHERE table_name = '" . strtoupper( $db_config['prefix'] . "_" . $lang . "_comments" ) . "'" );
	if( $query->fetchColumn() )
	{
		$sql_drop_module[] = "DELETE FROM " . $db_config["prefix"] . "_" . $lang . "_comments WHERE module='" . $module_name . "'";
	}
}

$sql_create_module = $sql_drop_module;

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . " (
		 id NUMBER(8,0) DEFAULT NULL,
		 catid NUMBER(5,0) DEFAULT 0 NOT NULL ENABLE,
		 title VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
		 alias VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
		 description CLOB NOT NULL ENABLE,
		 introtext VARCHAR2(4000 CHAR) NOT NULL ENABLE,
		 uploadtime NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
		 updatetime NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
		 user_id NUMBER(8,0) DEFAULT 0 NOT NULL ENABLE,
		 user_name VARCHAR2(100 CHAR) DEFAULT '' NOT NULL ENABLE,
		 author_name VARCHAR2(100 CHAR) DEFAULT '' NOT NULL ENABLE,
		 author_email VARCHAR2(60 CHAR) DEFAULT '' NOT NULL ENABLE,
		 author_url VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
		 fileupload VARCHAR2(4000 CHAR) NOT NULL ENABLE,
		 linkdirect VARCHAR2(4000 CHAR) NOT NULL ENABLE,
		 version VARCHAR2(20 CHAR) DEFAULT '' NOT NULL ENABLE,
		 filesize NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
		 fileimage VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
		 status NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
		 copyright VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
		 view_hits NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
		 download_hits NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
		 groups_comment VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
		 groups_view VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
		 groups_download VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
		 comment_hits NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
		 rating_detail VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
		 primary key (id),
		 CONSTRAINT cnv_" . $lang . "_" . $module_data . "_alias UNIQUE (alias)
	)";
$sql_create_module[] = 'create sequence SNV_' . strtoupper( $lang . '_' . $module_data ) . '_ROWS';

$sql_create_module[] = 'CREATE OR REPLACE TRIGGER TNV_' . strtoupper( $lang . '_' . $module_data ) . '_ROWS
	 BEFORE INSERT ON ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '
	 FOR EACH ROW WHEN (new.id is null)
		BEGIN
		 SELECT SNV_' . strtoupper( $lang . '_' . $module_data ) . '_ROWS.nextval INTO :new.id FROM DUAL;
		END TNV_' . strtoupper( $lang . '_' . $module_data ) . '_ROWS;';


$sql_create_module[] = "CREATE INDEX inv_" . $lang . "_" . $module_data . "_catid ON " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "(catid) TABLESPACE USERS";
$sql_create_module[] = "CREATE INDEX inv_" . $lang . "_" . $module_data . "_user_id ON " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "(user_id) TABLESPACE USERS";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_tmp (
	 	 id NUMBER(8,0) DEFAULT NULL,
		 catid NUMBER(5,0) DEFAULT 0 NOT NULL ENABLE,
		 title VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
		 description CLOB NOT NULL ENABLE,
		 introtext VARCHAR2(4000 CHAR) NOT NULL ENABLE,
		 uploadtime NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
		 user_id NUMBER(8,0) DEFAULT 0 NOT NULL ENABLE,
		 user_name VARCHAR2(100 CHAR) DEFAULT '' NOT NULL ENABLE,
		 author_name VARCHAR2(100 CHAR) DEFAULT '' NOT NULL ENABLE,
		 author_email VARCHAR2(60 CHAR) DEFAULT '' NOT NULL ENABLE,
		 author_url VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
		 fileupload VARCHAR2(4000 CHAR) NOT NULL ENABLE,
		 linkdirect VARCHAR2(4000 CHAR) NOT NULL ENABLE,
		 version VARCHAR2(20 CHAR) DEFAULT '' NOT NULL ENABLE,
		 filesize VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
		 fileimage VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
		 copyright VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
		 primary key (id),
		 CONSTRAINT cnv_" . $lang . "_" . $module_data . "_tmp UNIQUE (title)
	)";

$sql_create_module[] = 'create sequence SNV_' . strtoupper( $lang . '_' . $module_data ) . '_TMP';

$sql_create_module[] = 'CREATE OR REPLACE TRIGGER TNV_' . strtoupper( $lang . '_' . $module_data ) . '_TMP
 BEFORE INSERT ON ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_tmp
 FOR EACH ROW WHEN (new.id is null)
	BEGIN
	 SELECT SNV_' . strtoupper( $lang . '_' . $module_data ) . '_TMP.nextval INTO :new.id FROM DUAL;
	END TNV_' . strtoupper( $lang . '_' . $module_data ) . '_TMP;';


$sql_create_module[] = "CREATE INDEX inv_" . $lang . "_" . $module_data . "_tmp_catid ON " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_tmp(catid) TABLESPACE USERS";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_categories (
	 	 id NUMBER(5,0) DEFAULT NULL,
		 parentid NUMBER(5,0) DEFAULT 0 NOT NULL ENABLE,
		 title VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
		 alias VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
		 description VARCHAR2(4000 CHAR) DEFAULT '',
		 groups_view VARCHAR2(255 CHAR) DEFAULT '',
		 groups_download VARCHAR2(255 CHAR) DEFAULT '',
		 weight NUMBER(5,0) DEFAULT 0 NOT NULL ENABLE,
		 status NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
		 primary key (id),
		 CONSTRAINT cnv_" . $lang . "_" . $module_data . "_calias UNIQUE (alias)
	)";

$sql_create_module[] = 'create sequence SNV_' . strtoupper( $lang . '_' . $module_data ) . '_CAT';
$sql_create_module[] = 'CREATE OR REPLACE TRIGGER TNV_' . strtoupper( $lang . '_' . $module_data ) . '_CAT
 BEFORE INSERT ON ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_categories
 FOR EACH ROW WHEN (new.id is null)
	BEGIN
	 SELECT SNV_' . strtoupper( $lang . '_' . $module_data ) . '_CAT.nextval INTO :new.id FROM DUAL;
	END TNV_' . strtoupper( $lang . '_' . $module_data ) . '_CAT;';

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_report (
	 fid NUMBER(8,0) DEFAULT NULL,
	 post_ip VARCHAR2(45 CHAR) DEFAULT '' NOT NULL ENABLE,
	 post_time NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	 CONSTRAINT cnv_" . $lang . "_" . $module_data . "_report_fid UNIQUE (fid)
	)";


$sql_create_module[] = "CREATE INDEX inv_" . $lang . "_" . $module_data . "_report_time ON " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_report(post_time) TABLESPACE USERS";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config (
	 config_name VARCHAR2(30 CHAR) NOT NULL ENABLE,
	 config_value VARCHAR2(255 CHAR) DEFAULT NULL,
	 CONSTRAINT cnv_" . $lang . "_" . $module_data . "_config UNIQUE (config_name)
	)";

$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config VALUES ('is_addfile', '1')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config VALUES ('is_upload', '1')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config VALUES ('groups_upload', '')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config VALUES ('maxfilesize', '2097152')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config VALUES ('upload_filetype', 'doc,xls,zip,rar')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config VALUES ('upload_dir', 'files')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config VALUES ('temp_dir', 'temp')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config VALUES ('groups_addfile', '')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config VALUES ('is_zip', '1')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config VALUES ('is_resume', '1')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config VALUES ('max_speed', '0')";

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