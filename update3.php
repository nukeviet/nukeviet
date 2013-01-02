<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 31/05/2010, 00:36
 */

define( 'NV_SYSTEM', true );

require (str_replace( DIRECTORY_SEPARATOR, '/', dirname( __file__ ) ) . '/mainfile.php');

// Cần viết upadte cho tất cả các ngôn ngữ và các module ảo của module news;
$module_name = $module_data = 'news';
$lang = 'vi';
$nv_prefixlang = $db_config['prefix'] . '_' . $lang;

$sql = "SELECT `id`, `listcatid`, `homeimgfile`,  `homeimgthumb` FROM `" . $nv_prefixlang . "_" . $module_data . "_rows` WHERE  `status` < 100 LIMIT  0, 100";
$result = $db->sql_query( $sql );
if( $db->sql_numrows( $result ) )
{
	while( $item = $db->sql_fetch_assoc( $result ) )
	{
		$array_img = (! empty( $item['homeimgthumb'] )) ? explode( "|", $item['homeimgthumb'] ) : $array_img = array (
				"",
				"" 
		);
		$homeimgthumb = 0;
		if( $item['homeimgfile'] != "" and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $item['homeimgfile'] ) and $array_img[0] != "" and file_exists( NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_name . '/' . $array_img[0] ) )
		{
			$path = dirname( $item['homeimgfile'] );
			if( ! empty( $path ) )
			{
				if( ! is_dir( NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_name . '/' . $path ) )
				{
					$e = explode( "/", $path );
					$cp = NV_FILES_DIR . '/' . $module_name;
					foreach( $e as $p )
					{
						if( is_dir( NV_ROOTDIR . '/' . $cp . '/' . $p ) )
						{
							$viewDir .= '/' . $p;
						}
						else
						{
							$mk = nv_mkdir( NV_ROOTDIR . '/' . $cp, $p );
							if( $mk[0] > 0 )
							{
								$viewDir .= '/' . $p;
							}
						}
						$cp .= '/' . $p;
					}
				}
			}
			if( @rename( NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_name . '/' . $array_img[0], NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_name . '/' . $item['homeimgfile'] ) )
			{
				$homeimgthumb = 1;
			}
			else
			{
				$homeimgthumb = 2;
			}
		}
		elseif( nv_is_url( $item['homeimgfile'] ) )
		{
			$homeimgthumb = 3;
		}
		elseif( $item['homeimgfile'] != "" and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $item['homeimgfile'] ) )
		{
			$homeimgthumb = 2;
		}
		$db->sql_query( "UPDATE `" . $nv_prefixlang . "_" . $module_data . "_rows` SET `homeimgthumb`= '" . $homeimgthumb . "', `status`=`status`+100 WHERE `id` =" . $item['id'] );
		$catids = explode( ",", $item['listcatid'] );
		foreach( $catids as $catid )
		{
			$db->sql_query( "UPDATE `" . $nv_prefixlang . "_" . $module_data . "_" . $catid . "` SET `homeimgthumb`= '" . $homeimgthumb . "' WHERE `id` =" . $item['id'] );
		}
	}
	$contents = "<meta http-equiv=\"refresh\" content=\"1;URL=" . NV_BASE_SITEURL . "update3.php?step=1\" />";
	die( $item['id'] . ' Đang thực hiện nâng cấp CSDL cho module: ' . $module_name . $contents );
}
else
{
	$db->sql_query( "UPDATE `" . $nv_prefixlang . "_" . $module_data . "_rows` SET `status`=`status`-100 WHERE `status`>=100" );
	$sql = "SELECT `catid` FROM `" . $nv_prefixlang . "_" . $module_data . "_cat`";
	$result = $db->sql_query( $sql );
	while( $item = $db->sql_fetch_assoc( $result ) )
	{
		$db->sql_query( "ALTER TABLE `" . $nv_prefixlang . "_" . $module_data . "_" . $item['catid'] . "` CHANGE `homeimgthumb` `homeimgthumb` TINYINT(4) NOT NULL DEFAULT '0'" );
	}
	die( 'Thực hiện nâng cấp CSDL thành công, Bạn cần xóa các file update3.php ở thư mục gốc của site ngay lập tức' );
}
?>