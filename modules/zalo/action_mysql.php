<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_MODULES')) {
    exit('Stop!!!');
}

$sql_drop_module = [];
$tls = ['article', 'conversation', 'followers', 'settings', 'tags', 'tags_follower', 'template', 'upload', 'video'];
foreach ($tls as $tl) {
    $sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_zalo_' . $tl . ';';
}

$sql_create_module = $sql_drop_module;
$sql_create_module[] = 'CREATE TABLE ' . $db_config['prefix'] . "_zalo_article (
 id MEDIUMINT(8) NOT NULL AUTO_INCREMENT,
 zalo_id CHAR(100) NOT NULL DEFAULT '',
 token TEXT NOT NULL,
 type CHAR(10) NOT NULL DEFAULT '',
 title VARCHAR(150) NOT NULL DEFAULT '',
 author CHAR(50) NOT NULL DEFAULT '',
 cover_type CHAR(20) NOT NULL DEFAULT '',
 cover_photo_url VARCHAR(250) NOT NULL DEFAULT '',
 cover_video_id CHAR(100) NOT NULL DEFAULT '',
 cover_view CHAR(10) NOT NULL DEFAULT 'horizontal',
 cover_status CHAR(10) NOT NULL DEFAULT 'hide',
 description TEXT NOT NULL,
 body TEXT NOT NULL,
 related_medias TEXT NOT NULL,
 tracking_link VARCHAR(250) NOT NULL DEFAULT '',
 video_id CHAR(100) NOT NULL DEFAULT '',
 video_avatar VARCHAR(250) NOT NULL DEFAULT '',
 status CHAR(10) NOT NULL DEFAULT 'show',
 comment CHAR(10) NOT NULL DEFAULT 'show',
 create_date INT(11) NOT NULL DEFAULT '0',
 update_date INT(11) NOT NULL DEFAULT '0',
 total_view INT(11) NOT NULL DEFAULT '0',
 total_share INT(11) NOT NULL DEFAULT '0',
 is_sync TINYINT(1) NOT NULL DEFAULT '0',
 PRIMARY KEY (id),
 UNIQUE KEY zalo_id (zalo_id),
 KEY is_sync (is_sync)
) ENGINE=MyISAM";

$sql_create_module[] = 'CREATE TABLE ' . $db_config['prefix'] . "_zalo_conversation (
 message_id CHAR(50) NOT NULL,
 user_id CHAR(30) NOT NULL,
 src TINYINT(1) NOT NULL DEFAULT '0',
 time INT(11) NOT NULL DEFAULT '0',
 type CHAR(20) NOT NULL DEFAULT '',
 message TEXT NOT NULL,
 links TEXT NOT NULL,
 thumb VARCHAR(250) NOT NULL DEFAULT '',
 url VARCHAR(250) NOT NULL DEFAULT '',
 description TEXT NOT NULL,
 location CHAR(150) NOT NULL DEFAULT '',
 note TEXT NOT NULL,
 displayed TINYINT(1) NOT NULL DEFAULT '0',
 PRIMARY KEY (message_id)
) ENGINE=MyISAM";

$sql_create_module[] = 'CREATE TABLE ' . $db_config['prefix'] . "_zalo_followers (
 user_id CHAR(30) NOT NULL,
 app_id CHAR(30) NOT NULL,
 user_id_by_app CHAR(30) NOT NULL DEFAULT '',
 display_name VARCHAR(250) NOT NULL DEFAULT '',
 is_sensitive TINYINT(1) NOT NULL DEFAULT '0',
 avatar120 VARCHAR(250) NOT NULL DEFAULT '',
 avatar240 VARCHAR(250) NOT NULL DEFAULT '',
 user_gender CHAR(1) NOT NULL DEFAULT '',
 tags_info TEXT NOT NULL,
 notes_info TEXT NOT NULL,
 isfollow TINYINT(1) NOT NULL DEFAULT '1',
 weight MEDIUMINT(8) NOT NULL DEFAULT '0',
 name CHAR(100) NOT NULL DEFAULT '',
 phone_code CHAR(10) NOT NULL DEFAULT '',
 phone_number CHAR(20) NOT NULL DEFAULT '',
 address VARCHAR(250) NOT NULL DEFAULT '',
 city_id CHAR(10) NOT NULL DEFAULT '',
 district_id CHAR(10) NOT NULL DEFAULT '',
 is_sync TINYINT(1) NOT NULL DEFAULT '0',
 updatetime INT(11) NOT NULL DEFAULT '0',
 UNIQUE KEY user_id (user_id)
) ENGINE=MyISAM";

$sql_create_module[] = 'CREATE TABLE ' . $db_config['prefix'] . "_zalo_settings (
 skey CHAR(100) NOT NULL,
 type CHAR(20) NOT NULL DEFAULT '',
 svalue TEXT NOT NULL,
 UNIQUE KEY info_key (skey, type)
) ENGINE=MyISAM";

$sql_create_module[] = 'CREATE TABLE ' . $db_config['prefix'] . '_zalo_tags (
 alias CHAR(50) NOT NULL,
 name CHAR(100) NOT NULL,
 UNIQUE KEY alias (alias)
) ENGINE=MyISAM';

$sql_create_module[] = 'CREATE TABLE ' . $db_config['prefix'] . '_zalo_tags_follower (
 tag CHAR(50) NOT NULL,
 user_id CHAR(30) NOT NULL,
 UNIQUE KEY tag (tag, user_id)
) ENGINE=MyISAM';

$sql_create_module[] = 'CREATE TABLE ' . $db_config['prefix'] . '_zalo_template (
 id SMALLINT(4) NOT NULL AUTO_INCREMENT,
 type CHAR(10) NOT NULL,
 content TEXT NOT NULL,
 PRIMARY KEY (id)
) ENGINE=MyISAM';

$sql_create_module[] = 'CREATE TABLE ' . $db_config['prefix'] . "_zalo_upload (
 id TINYINT(8) NOT NULL AUTO_INCREMENT,
 type CHAR(50) NOT NULL,
 extension CHAR(10) NOT NULL,
 file VARCHAR(250) NOT NULL,
 localfile VARCHAR(250) NOT NULL DEFAULT '',
 width SMALLINT(4) NOT NULL DEFAULT '0',
 height SMALLINT(4) NOT NULL DEFAULT '0',
 zalo_id VARCHAR(250) NOT NULL DEFAULT '',
 description VARCHAR(250) NOT NULL DEFAULT '',
 addtime INT(11) NOT NULL DEFAULT '0',
 PRIMARY KEY (id),
 KEY type (type)
) ENGINE=MyISAM";

$sql_create_module[] = 'CREATE TABLE ' . $db_config['prefix'] . "_zalo_video (
 id MEDIUMINT(8) NOT NULL AUTO_INCREMENT,
 video_id CHAR(100) NOT NULL DEFAULT '',
 token TEXT NOT NULL,
 video_name CHAR(100) NOT NULL DEFAULT '',
 video_size INT(11) NOT NULL DEFAULT '0',
 description VARCHAR(250) NOT NULL DEFAULT '',
 view CHAR(10) NOT NULL DEFAULT 'horizontal',
 thumb VARCHAR(250) NOT NULL DEFAULT '',
 status TINYINT(1) NOT NULL DEFAULT '0',
 status_message CHAR(100) NOT NULL DEFAULT '',
 convert_percent INT(11) NOT NULL DEFAULT '0',
 convert_error_code INT(11) NOT NULL DEFAULT '0',
 addtime INT(11) NOT NULL DEFAULT '0',
 PRIMARY KEY (id)
) ENGINE=MyISAM";

$sql_create_module[] = 'DELETE FROM ' . NV_CONFIG_GLOBALTABLE . " WHERE  config_name IN ('zaloOfficialAccountID', 'zaloAppID', 'zaloAppSecretKey', 'zaloOAAccessToken', 'zaloOARefreshToken', 'zaloOAAccessTokenTime', 'zaloOASecretKey', 'zaloWebhookIPs', 'check_zaloip_expired')";
$sql_create_module[] = 'INSERT INTO ' . NV_CONFIG_GLOBALTABLE . " (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'site', 'zaloOfficialAccountID', '')";
$sql_create_module[] = 'INSERT INTO ' . NV_CONFIG_GLOBALTABLE . " (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'site', 'zaloAppID', '')";
$sql_create_module[] = 'INSERT INTO ' . NV_CONFIG_GLOBALTABLE . " (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'site', 'zaloAppSecretKey', '')";
$sql_create_module[] = 'INSERT INTO ' . NV_CONFIG_GLOBALTABLE . " (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'site', 'zaloOAAccessToken', '')";
$sql_create_module[] = 'INSERT INTO ' . NV_CONFIG_GLOBALTABLE . " (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'site', 'zaloOARefreshToken', '')";
$sql_create_module[] = 'INSERT INTO ' . NV_CONFIG_GLOBALTABLE . " (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'site', 'zaloOAAccessTokenTime', '0')";
$sql_create_module[] = 'INSERT INTO ' . NV_CONFIG_GLOBALTABLE . " (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'site', 'zaloOASecretKey', '')";
$sql_create_module[] = 'INSERT INTO ' . NV_CONFIG_GLOBALTABLE . " (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'zaloWebhookIPs', '')";
$sql_create_module[] = 'INSERT INTO ' . NV_CONFIG_GLOBALTABLE . " (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'check_zaloip_expired', '0')";
