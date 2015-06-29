<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 18:49
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$catid = $nv_Request->get_int( 'catid', 'post', 0 );
$contents = "NO_" . $catid;

list( $catid, $parentid, $title ) = $db->query( "SELECT catid, parentid, " . NV_LANG_DATA . "_title FROM " . $db_config['prefix'] . "_" . $module_data . "_catalogs WHERE catid=" . $catid )->fetch( 3 );

if( $catid > 0 )
{
	$delallcheckss = $nv_Request->get_string( 'delallcheckss', 'post', "" );
	$check_parentid = $db->query( "SELECT count(*) FROM " . $db_config['prefix'] . "_" . $module_data . "_catalogs WHERE parentid = '" . $catid . "'" )->fetchColumn();

	if( intval( $check_parentid ) > 0 )// Chu de con
	{
		$contents = "ERR_CAT_" . sprintf( $lang_module['delcat_msg_cat'], $check_parentid );
	}
	else// San pham trong chu de
	{
		$check_rows = $db->query( "SELECT count(*) FROM " . $db_config['prefix'] . "_" . $module_data . "_rows WHERE listcatid='" . $catid . "'" )->fetchColumn();

		if( intval( $check_rows ) > 0 )
		{
			if( $delallcheckss == md5( $catid . session_id() . $global_config['sitekey'] ) )
			{
				$delcatandrows = $nv_Request->get_string( 'delcatandrows', 'post', "" );
				$movecat = $nv_Request->get_string( 'movecat', 'post', "" );
				$catidnews = $nv_Request->get_int( 'catidnews', 'post', 0 );

				if( empty( $delcatandrows ) and empty( $movecat ) )// Hien form
				{
					$sql = "SELECT catid, " . NV_LANG_DATA . "_title, lev FROM " . $db_config['prefix'] . "_" . $module_data . "_catalogs WHERE catid !='" . $catid . "' ORDER BY sort ASC";
					$result = $db->query( $sql );

					$array_cat_list = array();
					$array_cat_list[0] = "&nbsp;";

					while( list( $catid_i, $title_i, $lev_i ) = $result->fetch( 3 ) )
					{
						$xtitle_i = "";
						if( $lev_i > 0 )
						{
							$xtitle_i .= "&nbsp;&nbsp;&nbsp;|";
							for( $i = 1; $i <= $lev_i; $i++ )
							{
								$xtitle_i .= "---";
							}
							$xtitle_i .= ">&nbsp;";
						}
						$xtitle_i .= $title_i;
						$array_cat_list[$catid_i] = $xtitle_i;
					}

					$xtpl = new XTemplate( "cat_delete.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
					$xtpl->assign( 'LANG', $lang_module );
					$xtpl->assign( 'GLANG', $lang_global );
					$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
					$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
					$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
					$xtpl->assign( 'MODULE_NAME', $module_name );
					$xtpl->assign( 'OP', $op );

					$xtpl->assign( 'CATID', $catid );
					$xtpl->assign( 'DELALLCHECKSS', $delallcheckss );
					$xtpl->assign( 'INFO', sprintf( $lang_module['delcat_msg_rows_select'], $title, $check_rows ) );

					while( list( $catid_i, $title_i ) = each( $array_cat_list ) )
					{
						$xtpl->assign( 'CAT_ID', $catid_i );
						$xtpl->assign( 'CAT_TITLE', $title_i );
						$xtpl->parse( 'main.catloop' );
					}

					$xtpl->parse( 'main' );
					$contents = $xtpl->text( 'main' );
				}
				elseif( ! empty( $delcatandrows ) )// Xoa loai san pham va san pham
				{
					$sql = $db->query( "SELECT id, listcatid FROM " . $db_config['prefix'] . "_" . $module_data . "_rows WHERE listcatid=" . $catid );
					while( $row = $sql->fetch() )
					{
						nv_del_content_module( $row['id'] );
					}

					$db->query( "DELETE FROM " . $db_config['prefix'] . "_" . $module_data . "_catalogs WHERE catid=" . $catid );

					nv_fix_cat_order();
					nv_del_moduleCache( $module_name );
					Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat&parentid=" . $parentid );
					die();
				}
				elseif( ! empty( $movecat ) and $catidnews > 0 and $catidnews != $catid )// Di chuyen san pham sang chu de moi
				{
					$catidnews = $db->query( "SELECT catid FROM " . $db_config['prefix'] . "_" . $module_data . "_catalogs WHERE catid =" . $catidnews )->fetchColumn();

					if( $catidnews > 0 )
					{
						$sql = $db->query( "SELECT id, listcatid FROM " . $db_config['prefix'] . "_" . $module_data . "_rows WHERE listcatid=" . $catid );
						while( $row = $sql->fetch() )
						{
							$db->query( "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_rows SET listcatid=" . $catidnews . " WHERE id =" . $row['id'] );
						}
						$db->query( "DELETE FROM " . $db_config['prefix'] . "_" . $module_data . "_catalogs WHERE catid=" . $catid );
						nv_fix_cat_order();
						nv_del_moduleCache( $module_name );

						Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat&parentid=" . $parentid );
						die();
					}
				}
			}
			else
			{
				$contents = "ERR_ROWS_" . $catid . "_" . md5( $catid . session_id() . $global_config['sitekey'] ) . "_" . sprintf( $lang_module['delcat_msg_rows'], $check_rows );
			}
		}
	}

	if( $contents == "NO_" . $catid )
	{
		$sql = "DELETE FROM " . $db_config['prefix'] . "_" . $module_data . "_catalogs WHERE catid=" . $catid;
		if( $db->query( $sql ) )
		{
			nv_fix_cat_order();
			$contents = "OK_" . $parentid;
			nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_catalog', "id " . $catid, $admin_info['userid'] );
		}
		nv_del_moduleCache( $module_name );
	}
}

if( defined( 'NV_IS_AJAX' ) )
{
	include NV_ROOTDIR . '/includes/header.php';
	echo $contents;
	include NV_ROOTDIR . '/includes/footer.php';
}
else
{
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat" );
	die();
}