<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 31/05/2010, 00:36
 */
define( 'NV_ADMIN', true );
require_once ( str_replace( '\\\\', '/', dirname( __file__ ) ) . '/mainfile.php' );
require_once ( NV_ROOTDIR . "/includes/core/admin_functions.php" );
$global_config['new_version'] = "3.0.09";

function nv_version_compare ( $version1, $version2 )
{
    $v1 = explode( '.', $version1 );
    $v2 = explode( '.', $version2 );
    
    if ( $v1[0] > $v2[0] )
    {
        return 1;
    }
    
    if ( $v1[0] < $v2[0] )
    {
        return - 1;
    }
    
    if ( $v1[1] > $v2[1] )
    {
        return 1;
    }
    
    if ( $v1[1] < $v2[1] )
    {
        return - 1;
    }
    
    if ( $v1[2] > $v2[2] )
    {
        return 1;
    }
    
    if ( $v1[2] < $v2[2] )
    {
        return - 1;
    }
    
    return 0;
}

if ( nv_version_compare( $global_config['version'], "3.0.05" ) < 0 )
{
    die( "program support from only version: 3.0.05" );
}
if ( nv_version_compare( $global_config['version'], "3.0.06" ) < 0 )
{
    $db->sql_query( "ALTER TABLE `" . $db_config['prefix'] . "_users` ADD `md5username` VARCHAR( 32 ) NOT NULL DEFAULT '' AFTER `username`" );
    $db->sql_query( "UPDATE `" . $db_config['prefix'] . "_users` SET `md5username` = MD5(username)" );
    $db->sql_query( "ALTER TABLE `" . $db_config['prefix'] . "_users` ADD UNIQUE (`md5username`)" );
    
    $db->sql_query( "ALTER TABLE `" . $db_config['prefix'] . "_users_reg` ADD `md5username` VARCHAR( 32 ) NOT NULL DEFAULT '' AFTER `username`" );
    $db->sql_query( "UPDATE `" . $db_config['prefix'] . "_users_reg` SET `md5username` = MD5(username)" );
    $db->sql_query( "ALTER TABLE `" . $db_config['prefix'] . "_users_reg` ADD UNIQUE (`md5username`)" );
    
    $db->sql_query( "DELETE FROM `" . $db_config['prefix'] . "_config` WHERE `module` = 'global' AND `config_name` = 'site_logo'" );
    
    $array_block_title = array();
    $array_block_title['en'][1] = "Hot News";
    $array_block_title['en'][2] = "Top News";
    
    $array_block_title['vi'][1] = "Tin tiêu điểm";
    $array_block_title['vi'][2] = "Tin mới nhất";
    
    $array_block_title['fr'][1] = "Populairs";
    $array_block_title['fr'][2] = "Récents";
    
    $sql = "SELECT lang, setup FROM `" . $db_config['prefix'] . "_setup_language`";
    $result = $db->sql_query( $sql );
    $array_lang_setup = array();
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        $lang_data_i = $row['lang'];
        
        $db->sql_query( "ALTER TABLE `" . $db_config['prefix'] . "_" . $lang_data_i . "_news_block_cat` ADD `number` INT( 11 ) NOT NULL DEFAULT '4' AFTER `adddefault`" );
        $db->sql_query( "INSERT INTO `" . $db_config['prefix'] . "_config` (`lang`, `module`, `config_name`, `config_value`) VALUES ('" . $lang_data_i . "', 'global', 'site_logo', 'logo.png')" );
        $array_block_title_lang = ( isset( $array_block_title[$lang_data_i] ) ) ? $array_block_title[$lang_data_i] : $array_block_title['en'];
        $db->sql_query( "UPDATE `" . $db_config['prefix'] . "_" . $lang_data_i . "_news_block_cat` SET `title` = '" . $db->dbescape_string( $array_block_title_lang[1] ) . "' WHERE `bid` =1" );
        $db->sql_query( "UPDATE `" . $db_config['prefix'] . "_" . $lang_data_i . "_news_block_cat` SET `title` = '" . $db->dbescape_string( $array_block_title_lang[2] ) . "' WHERE `bid` =2" );
    }
}
if ( nv_version_compare( $global_config['version'], "3.0.08" ) < 0 )
{
    $db->sql_query( "CREATE TABLE IF NOT EXISTS `" . $db_config['prefix'] . "_banip` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `ip` varchar(32) DEFAULT NULL,
	  `mask` tinyint(4) NOT NULL DEFAULT '0',  
	  `area` tinyint(3) NOT NULL,
	  `begintime` int(11) DEFAULT NULL,
	  `endtime` int(11) DEFAULT NULL,
	  `notice` varchar(255) NOT NULL,
	  PRIMARY KEY (`id`),
	  UNIQUE KEY `ip` (`ip`)
	) ENGINE=MyISAM" );
    $db->sql_query( "UPDATE `" . $db_config['prefix'] . "_config` SET `config_value` = '" . min( nv_converttoBytes( ini_get( 'upload_max_filesize' ) ), nv_converttoBytes( ini_get( 'post_max_size' ) ) ) . "',  `config_name` = 'nv_max_size' WHERE `lang` = 'sys' AND `module` = 'global' AND `config_name` = 'security_tags'" );
}

if ( nv_version_compare( $global_config['version'], "3.0.09" ) < 0 )
{
    $db->sql_query( "CREATE TABLE IF NOT EXISTS `" . NV_AUTHORS_GLOBALTABLE . "_config` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `keyname` varchar(32) DEFAULT NULL,
	  `mask` tinyint(4) NOT NULL DEFAULT '0',
	  `begintime` int(11) DEFAULT NULL,
	  `endtime` int(11) DEFAULT NULL,
	  `notice` varchar(255) NOT NULL,
	  PRIMARY KEY (`id`),
	  UNIQUE KEY `keyname` (`keyname`)
	) ENGINE=MyISAM" );
    
    $db->sql_query( "ALTER TABLE `nv3_cronjobs` DROP `cron_name`" );
    
    $array_cron_name = array();
    $array_cron_name['en'][1] = 'Delete expired online status';
    $array_cron_name['en'][2] = 'Automatic backup database';
    $array_cron_name['en'][3] = 'Empty temporary files';
    $array_cron_name['en'][4] = 'Delete IP log files';
    $array_cron_name['en'][5] = 'Delete expired error_log log files';
    $array_cron_name['en'][6] = 'Send error logs to admin';
    $array_cron_name['en'][7] = 'Delete expired referer';
    
    $array_cron_name['vi'][1] = 'Xóa các dòng ghi trạng thái online đã cũ trong CSDL';
    $array_cron_name['vi'][2] = 'Tự động lưu CSDL';
    $array_cron_name['vi'][3] = 'Xóa các file tạm trong thư mục tmp';
    $array_cron_name['vi'][4] = 'Xóa IP log files Xóa các file logo truy cập';
    $array_cron_name['vi'][5] = 'Xóa các file error_log quá hạn';
    $array_cron_name['vi'][6] = 'Gửi email các thông báo lỗi cho admin';
    $array_cron_name['vi'][7] = 'Xóa các referer quá hạn';
    
    $array_cron_name['fr'][1] = 'Supprimer les anciens registres du status en ligne dans la base de données';
    $array_cron_name['fr'][2] = 'Sauvegarder automatique la base de données';
    $array_cron_name['fr'][3] = 'Supprimer les fichiers temporaires du répertoire tmp';
    $array_cron_name['fr'][4] = 'Supprimer les fichiers ip_logs expirés';
    $array_cron_name['fr'][5] = 'Supprimer les fichiers error_log expirés';
    $array_cron_name['fr'][6] = 'Envoyer à l\'administrateur l\'e-mail des notifications d\'erreurs';
    $array_cron_name['fr'][7] = 'Supprimer les referers expirés';
    
    $sql = "SELECT lang FROM `" . $db_config['prefix'] . "_setup_language` where `lang`!='" . NV_LANG_INTERFACE . "'";
    $result_lang = $db->sql_query( $sql );
    while ( list( $lang_i ) = $db->sql_fetchrow( $result_lang ) )
    {
        $sql = "ALTER TABLE `" . $db_config['prefix'] . "_cronjobs` ADD `" . $lang_i . "_cron_name` VARCHAR( 255 ) NOT NULL DEFAULT ''";
        $db->sql_query( $sql );
        
        $array_cron_name_lang = ( isset( $array_cron_name[$lang_i] ) ) ? $array_cron_name[$lang_i] : $array_cron_name['en'];
        
        $result = $db->sql_query( "SELECT `id`, `run_func` FROM `" . $db_config['prefix'] . "_cronjobs` ORDER BY `id` ASC" );
        while ( list( $id, $run_func ) = $db->sql_fetchrow( $result ) )
        {
            $cron_name = ( isset( $array_cron_name_lang[$id] ) ) ? $array_cron_name_lang[$id] : $run_func;
            $sql_create_table[] = "UPDATE `" . $db_config['prefix'] . "_cronjobs` SET `" . $lang_i . "_cron_name` =  " . $db->dbescape_string( $cron_name ) . " WHERE `id`=" . $id;
        }
        $db->sql_freeresult();
    }
}
$db->sql_query( "UPDATE `" . $db_config['prefix'] . "_config` SET `config_value` = '" . $global_config['new_version'] . "' WHERE `lang` = 'sys' AND `module` = 'global' AND `config_name` = 'version'" );

nv_save_file_config_global();
nv_delete_all_cache(); //xoa toan bo cache


die( "Update successfully, you should immediately delete this file." );

?>