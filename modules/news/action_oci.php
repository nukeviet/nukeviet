<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) )	die( 'Stop!!!' );

$sql_drop_module = array();
$query = $db->query( "select table_name from all_tables WHERE table_name LIKE '" . strtoupper( $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_%" ) . "'" );
while( $row = $query->fetch() )
{
	$sql_drop_module[] = 'drop table ' . $row['table_name'] . ' cascade constraints PURGE';
}

$query = $db->query( "select sequence_name from user_sequences WHERE sequence_name LIKE '" . strtoupper( "SNV_" . $lang . "_" . $module_data . "_%" ) . "'" );
while( $row = $query->fetch() )
{
	$sql_drop_module[] = 'drop SEQUENCE ' . $row['sequence_name'];
}

$query = $db->query( "select count(*) from all_tables WHERE table_name = '" . strtoupper( $db_config['prefix'] . "_" . $lang . "_comments" ) . "'" );
if( $query->fetchColumn() )
{
	$sql_drop_module[] = "DELETE FROM " . $db_config["prefix"] . "_" . $lang . "_comments WHERE module='" . $module_name . "'";
}
$sql_create_module = $sql_drop_module;

$sql_create_module[] = "CREATE TABLE " . $db_config["prefix"] . "_" . $lang . "_" . $module_data . "_cat (
 	 catid NUMBER(5,0) DEFAULT NULL,
	 parentid NUMBER(5,0) DEFAULT 0 NOT NULL ENABLE,
	 title VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
	 titlesite VARCHAR2(255 CHAR) DEFAULT NULL,
	 alias VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
	 description VARCHAR2(4000 CHAR) DEFAULT NULL,
	 image VARCHAR2(255 CHAR) DEFAULT NULL,
	 viewdescription NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	 weight NUMBER(5,0) DEFAULT 0 NOT NULL ENABLE,
	 sort NUMBER(5,0) DEFAULT 0 NOT NULL ENABLE,
	 lev NUMBER(5,0) DEFAULT 0 NOT NULL ENABLE,
	 viewcat VARCHAR2(50 CHAR) DEFAULT 'viewcat_page_new' NOT NULL ENABLE,
	 numsubcat NUMBER(5,0) DEFAULT 0 NOT NULL ENABLE,
	 subcatid VARCHAR2(255 CHAR) DEFAULT NULL,
	 inhome NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	 numlinks NUMBER(3,0) DEFAULT 3 NOT NULL ENABLE,
	 newday NUMBER(3,0) DEFAULT 2 NOT NULL ENABLE,
	 keywords VARCHAR2(4000 CHAR) DEFAULT NULL,
	 admins VARCHAR2(4000 CHAR) DEFAULT NULL,
	 add_time NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	 edit_time NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	 groups_view VARCHAR2(255 CHAR) DEFAULT NULL,
	 primary key (catid),
	 CONSTRAINT cnv_" . $lang . "_" . $module_data . "_cat_alias UNIQUE (alias)
)";

$sql_create_module[] = 'create sequence SNV_' . strtoupper( $lang . '_' . $module_data ) . '_CAT MINVALUE 100';

$sql_create_module[] = 'CREATE OR REPLACE TRIGGER TNV_' . strtoupper( $lang . '_' . $module_data ) . '_CAT
 BEFORE INSERT ON ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_cat
 FOR EACH ROW WHEN (new.catid is null)
	BEGIN
	 SELECT SNV_' . strtoupper( $lang . '_' . $module_data ) . '_CAT.nextval INTO :new.catid FROM DUAL;
	END TNV_' . strtoupper( $lang . '_' . $module_data ) . '_CAT;';

$sql_create_module[] = "CREATE TABLE " . $db_config["prefix"] . "_" . $lang . "_" . $module_data . "_sources (
	 sourceid NUMBER(8,0) DEFAULT NULL,
	 title VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
	 link VARCHAR2(255 CHAR) DEFAULT '',
	 logo VARCHAR2(255 CHAR) DEFAULT '',
	 weight NUMBER(8,0) DEFAULT 0 NOT NULL ENABLE,
	 add_time NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	 edit_time NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	 primary key (sourceid),
	 CONSTRAINT cnv_" . $lang . "_" . $module_data . "_sources_title UNIQUE (title)
)";

$sql_create_module[] = 'create sequence SNV_' . strtoupper( $lang . '_' . $module_data ) . '_SOU MINVALUE 10';

$sql_create_module[] = 'CREATE OR REPLACE TRIGGER TNV_' . strtoupper( $lang . '_' . $module_data ) . '_SOU
 BEFORE INSERT ON ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_sources
 FOR EACH ROW WHEN (new.sourceid is null)
	BEGIN
	 SELECT SNV_' . strtoupper( $lang . '_' . $module_data ) . '_SOU.nextval INTO :new.sourceid FROM DUAL;
	END TNV_' . strtoupper( $lang . '_' . $module_data ) . '_SOU;';

$sql_create_module[] = "CREATE TABLE " . $db_config["prefix"] . "_" . $lang . "_" . $module_data . "_topics (
	 topicid NUMBER(5,0) DEFAULT NULL,
	 title VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
	 alias VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
	 image VARCHAR2(255 CHAR) DEFAULT '',
	 description VARCHAR2(255 CHAR) DEFAULT '',
	 weight NUMBER(5,0) DEFAULT 0 NOT NULL ENABLE,
	 keywords VARCHAR2(4000 CHAR) DEFAULT '',
	 add_time NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	 edit_time NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	 primary key (topicid),
	 CONSTRAINT cnv_" . $lang . "_" . $module_data . "_topics_title UNIQUE (title),
	 CONSTRAINT cnv_" . $lang . "_" . $module_data . "_topics_alias UNIQUE (alias)
)";

$sql_create_module[] = 'create sequence SNV_' . strtoupper( $lang . '_' . $module_data ) . '_TOP MINVALUE 10';

$sql_create_module[] = 'CREATE OR REPLACE TRIGGER TNV_' . strtoupper( $lang . '_' . $module_data ) . '_TOP
 BEFORE INSERT ON ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_topics
 FOR EACH ROW WHEN (new.topicid is null)
	BEGIN
	 SELECT SNV_' . strtoupper( $lang . '_' . $module_data ) . '_TOP.nextval INTO :new.topicid FROM DUAL;
	END TNV_' . strtoupper( $lang . '_' . $module_data ) . '_TOP;';

$sql_create_module[] = "CREATE TABLE " . $db_config["prefix"] . "_" . $lang . "_" . $module_data . "_block_cat (
	 bid NUMBER(5,0) DEFAULT NULL,
	 adddefault NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	 numbers NUMBER(5,0) DEFAULT 10 NOT NULL ENABLE,
	 title VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
	 alias VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
	 image VARCHAR2(255 CHAR) DEFAULT '',
	 description VARCHAR2(255 CHAR) DEFAULT '',
	 weight NUMBER(5,0) DEFAULT 0 NOT NULL ENABLE,
	 keywords VARCHAR2(4000 CHAR) DEFAULT '',
	 add_time NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	 edit_time NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	 primary key (bid),
	 CONSTRAINT cnv_" . $lang . "_" . $module_data . "_block_cat_title UNIQUE (title),
	 CONSTRAINT cnv_" . $lang . "_" . $module_data . "_block_cat_alias UNIQUE (alias)
)";

$sql_create_module[] = 'create sequence SNV_' . strtoupper( $lang . '_' . $module_data ) . '_BCAT MINVALUE 10';

$sql_create_module[] = 'CREATE OR REPLACE TRIGGER TNV_' . strtoupper( $lang . '_' . $module_data ) . '_BCAT
 BEFORE INSERT ON ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_block_cat
 FOR EACH ROW WHEN (new.bid is null)
	BEGIN
	 SELECT SNV_' . strtoupper( $lang . '_' . $module_data ) . '_BCAT.nextval INTO :new.bid FROM DUAL;
	END TNV_' . strtoupper( $lang . '_' . $module_data ) . '_BCAT;';

$sql_create_module[] = "CREATE TABLE " . $db_config["prefix"] . "_" . $lang . "_" . $module_data . "_block (
	 bid NUMBER(5,0) DEFAULT 0 NOT NULL ENABLE,
	 id NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	 weight NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	 CONSTRAINT cnv_" . $lang . "_" . $module_data . "_block_bid UNIQUE (bid,id)
)";

$sql_create_module[] = "CREATE TABLE " . $db_config["prefix"] . "_" . $lang . "_" . $module_data . "_rows (
	 id NUMBER(8,0) DEFAULT NULL,
	 catid NUMBER(5,0) DEFAULT 0 NOT NULL ENABLE,
	 listcatid VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
	 topicid NUMBER(5,0) DEFAULT 0 NOT NULL ENABLE,
	 admin_id NUMBER(8,0) DEFAULT 0 NOT NULL ENABLE,
	 author VARCHAR2(255 CHAR) DEFAULT '',
	 sourceid NUMBER(8,0) DEFAULT 0 NOT NULL ENABLE,
	 addtime NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	 edittime NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	 status NUMBER(3,0) DEFAULT 1 NOT NULL ENABLE,
	 publtime NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	 exptime NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	 archive NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	 title VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
	 alias VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
	 hometext VARCHAR2(4000 CHAR) NOT NULL ENABLE,
	 homeimgfile VARCHAR2(255 CHAR) DEFAULT '',
	 homeimgalt VARCHAR2(255 CHAR) DEFAULT '',
	 homeimgthumb NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	 inhome NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	 allowed_comm NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	 allowed_rating NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	 hitstotal NUMBER(8,0) DEFAULT 0 NOT NULL ENABLE,
	 hitscm NUMBER(8,0) DEFAULT 0 NOT NULL ENABLE,
	 total_rating NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	 click_rating NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	 primary key (id)
)";

$sql_create_module[] = 'create sequence SNV_' . strtoupper( $lang . '_' . $module_data ) . '_ROW MINVALUE 100';

$sql_create_module[] = 'CREATE OR REPLACE TRIGGER TNV_' . strtoupper( $lang . '_' . $module_data ) . '_ROW
 BEFORE INSERT ON ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_rows
 FOR EACH ROW WHEN (new.id is null)
	BEGIN
	 SELECT SNV_' . strtoupper( $lang . '_' . $module_data ) . '_ROW.nextval INTO :new.id FROM DUAL;
	END TNV_' . strtoupper( $lang . '_' . $module_data ) . '_ROW;';

$sql_create_module[] = "CREATE INDEX inv_" . $lang . "_" . $module_data . "_rcat ON " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows(catid) TABLESPACE USERS";
$sql_create_module[] = "CREATE INDEX inv_" . $lang . "_" . $module_data . "_rtop ON " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows(topicid) TABLESPACE USERS";
$sql_create_module[] = "CREATE INDEX inv_" . $lang . "_" . $module_data . "_radmin ON " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows(admin_id) TABLESPACE USERS";
$sql_create_module[] = "CREATE INDEX inv_" . $lang . "_" . $module_data . "_rauthor ON " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows(author) TABLESPACE USERS";
$sql_create_module[] = "CREATE INDEX inv_" . $lang . "_" . $module_data . "_rtitle ON " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows(title) TABLESPACE USERS";
$sql_create_module[] = "CREATE INDEX inv_" . $lang . "_" . $module_data . "_raddtime ON " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows(addtime) TABLESPACE USERS";
$sql_create_module[] = "CREATE INDEX inv_" . $lang . "_" . $module_data . "_rpubltime ON " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows(publtime) TABLESPACE USERS";
$sql_create_module[] = "CREATE INDEX inv_" . $lang . "_" . $module_data . "_rexptime ON " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows(exptime) TABLESPACE USERS";
$sql_create_module[] = "CREATE INDEX inv_" . $lang . "_" . $module_data . "_rstatus ON " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows(status) TABLESPACE USERS";

$sql_create_module[] = "CREATE TABLE " . $db_config["prefix"] . "_" . $lang . "_" . $module_data . "_bodytext (
	 id NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	 bodytext CLOB NOT NULL ENABLE,
	 primary key (id)
)";

$sql_create_module[] = "CREATE TABLE " . $db_config["prefix"] . "_" . $lang . "_" . $module_data . "_bodyhtml_1 (
	 id NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	 bodyhtml CLOB NOT NULL ENABLE,
	 sourcetext VARCHAR2(255 CHAR) DEFAULT '',
	 imgposition NUMBER(3,0) DEFAULT 1 NOT NULL ENABLE,
	 copyright NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	 allowed_send NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	 allowed_print NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	 allowed_save NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	 gid NUMBER(8,0) DEFAULT 0 NOT NULL ENABLE,
	 primary key (id)
)";

$sql_create_module[] = "CREATE TABLE " . $db_config["prefix"] . "_" . $lang . "_" . $module_data . "_config_post (
	 group_id NUMBER(5,0) DEFAULT 0 NOT NULL ENABLE,
	 addcontent NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	 postcontent NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	 editcontent NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	 delcontent NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	 primary key (group_id)
)";

$sql_create_module[] = "CREATE TABLE " . $db_config["prefix"] . "_" . $lang . "_" . $module_data . "_admins (
	 userid NUMBER(8,0) DEFAULT 0 NOT NULL ENABLE,
	 catid NUMBER(5,0) DEFAULT 0 NOT NULL ENABLE,
	 admin NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	 add_content NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	 pub_content NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	 edit_content NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	 del_content NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	 CONSTRAINT cnv_" . $lang . "_" . $module_data . "_admins UNIQUE (userid,catid)
)";

$sql_create_module[] = "CREATE TABLE " . $db_config["prefix"] . "_" . $lang . "_" . $module_data . "_tags (
	 tid NUMBER(8,0) DEFAULT NULL,
	 numnews NUMBER(8,0) DEFAULT 0 NOT NULL ENABLE,
	 alias VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
	 image VARCHAR2(255 CHAR) DEFAULT '',
	 description VARCHAR2(4000 CHAR) DEFAULT '',
	 keywords VARCHAR2(255 CHAR) DEFAULT '',
	 primary key (tid),
	 CONSTRAINT cnv_" . $lang . "_" . $module_data . "_tagalias UNIQUE (alias)
)";

$sql_create_module[] = 'create sequence SNV_' . strtoupper( $lang . '_' . $module_data ) . '_TAG MINVALUE 100';

$sql_create_module[] = 'CREATE OR REPLACE TRIGGER TNV_' . strtoupper( $lang . '_' . $module_data ) . '_TAG
 BEFORE INSERT ON ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_tags
 FOR EACH ROW WHEN (new.tid is null)
	BEGIN
	 SELECT SNV_' . strtoupper( $lang . '_' . $module_data ) . '_TAG.nextval INTO :new.tid FROM DUAL;
	END TNV_' . strtoupper( $lang . '_' . $module_data ) . '_TAG;';

$sql_create_module[] = "CREATE TABLE " . $db_config["prefix"] . "_" . $lang . "_" . $module_data . "_tags_id (
	 id NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	 tid NUMBER(8,0) DEFAULT 0 NOT NULL ENABLE,
	 keyword VARCHAR2(65 CHAR) DEFAULT '' NOT NULL ENABLE,
	 CONSTRAINT cnv_vi_vuthao_tags_id_sid UNIQUE (id,tid)
)";

$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'indexfile', 'viewcat_main_right')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'per_page', '20')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'st_links', '10')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'homewidth', '100')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'homeheight', '150')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'blockwidth', '52')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'blockheight', '75')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'imagefull', '460')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'copyright', '')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'showtooltip', '1')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'tooltip_position', 'bottom')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'tooltip_length', '150')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'showhometext', '1')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'timecheckstatus', '0')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'config_source', '0')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'show_no_image', '')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'allowed_rating_point', '1')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'facebookappid', '')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'socialbutton', '1')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'tags_alias', '0')";

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