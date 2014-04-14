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

list( $catid, $parentid, $title ) = $db->query( "SELECT catid, parentid, title FROM " . NV_PREFIXLANG . "_" . $module_data . "_cat WHERE catid=" . intval( $catid ) )->fetch( 3 );
if( $catid > 0 )
{
	if( ( defined( 'NV_IS_ADMIN_MODULE' ) or ( $parentid > 0 and isset( $array_cat_admin[$admin_id][$parentid] ) and $array_cat_admin[$admin_id][$parentid]['admin'] == 1 ) ) )
	{
		$delallcheckss = $nv_Request->get_string( 'delallcheckss', 'post', '' );
		$check_parentid = $db->query( "SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . "_cat WHERE parentid = '" . $catid . "'" )->fetchColumn();
		if( intval( $check_parentid ) > 0 )
		{
			$contents = "ERR_CAT_" . sprintf( $lang_module['delcat_msg_cat'], $check_parentid );
		}
		else
		{
			$check_rows = $db->query( "SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . "_" . $catid )->fetchColumn();
			if( intval( $check_rows ) > 0 )
			{
				if( $delallcheckss == md5( $catid . session_id() . $global_config['sitekey'] ) )
				{
					$delcatandrows = $nv_Request->get_string( 'delcatandrows', 'post', '' );
					$movecat = $nv_Request->get_string( 'movecat', 'post', '' );
					$catidnews = $nv_Request->get_int( 'catidnews', 'post', 0 );
					if( empty( $delcatandrows ) and empty( $movecat ) )
					{
						$sql = "SELECT catid, title, lev FROM " . NV_PREFIXLANG . "_" . $module_data . "_cat WHERE catid !='" . $catid . "' ORDER BY sort ASC";
						$result = $db->query( $sql );
						$array_cat_list = array();
						$array_cat_list[0] = "&nbsp;";
						while( list( $catid_i, $title_i, $lev_i ) = $result->fetch( 3 ) )
						{
							$xtitle_i = '';
							if( $lev_i > 0 )
							{
								$xtitle_i .= "&nbsp;&nbsp;&nbsp;|";
								for( $i = 1; $i <= $lev_i; ++$i )
								{
									$xtitle_i .= "---";
								}
								$xtitle_i .= ">&nbsp;";
							}
							$xtitle_i .= $title_i;
							$array_cat_list[$catid_i] = $xtitle_i;
						}

						$xtpl = new XTemplate( 'del_cat.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
						$xtpl->assign( 'LANG', $lang_module );
						$xtpl->assign( 'GLANG', $lang_global );
						$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
						$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
						$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
						$xtpl->assign( 'MODULE_NAME', $module_name );
						$xtpl->assign( 'OP', $op );
						$xtpl->assign( 'CATID', $catid );
						$xtpl->assign( 'DELALLCHECKSS', $delallcheckss );

						$xtpl->assign( 'TITLE', sprintf( $lang_module['delcat_msg_rows_select'], $title, $check_rows ) );

						while( list( $catid_i, $title_i ) = each( $array_cat_list ) )
						{
							$xtpl->assign( 'CATIDNEWS', array( 'key' => $catid_i, 'title' => $title_i ) );
							$xtpl->parse( 'main.catidnews' );
						}

						$xtpl->parse( 'main' );
						$contents = $xtpl->text( 'main' );
					}
					elseif( ! empty( $delcatandrows ) )
					{
						nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['delcatandrows'], $title, $admin_info['userid'] );

						$sql = $db->query( "SELECT id, catid, listcatid FROM " . NV_PREFIXLANG . "_" . $module_data . "_" . $catid );
						while( $row = $sql->fetch() )
						{
							if( $row['catid'] == $row['listcatid'] )
							{
								nv_del_content_module( $row['id'] );
							}
							else
							{
								$arr_catid_old = explode( ',', $row['listcatid'] );
								$arr_catid_i = array( $catid );
								$arr_catid_news = array_diff( $arr_catid_old, $arr_catid_i );
								if( $catid == $row['catid'] )
								{
									$row['catid'] = $arr_catid_news[0];
								}
								foreach( $arr_catid_news as $catid_i )
								{
									$db->query( "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_" . $catid_i . " SET catid=" . $row['catid'] . ", listcatid = '" . implode( ',', $arr_catid_news ) . "' WHERE id =" . $row['id'] );
								}
								$db->query( "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_rows SET catid=" . $row['catid'] . ", listcatid = '" . implode( ',', $arr_catid_news ) . "' WHERE id =" . $row['id'] );
							}
						}
						$db->query( "DROP TABLE " . NV_PREFIXLANG . "_" . $module_data . "_" . $catid );
						$db->query( "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_cat WHERE catid=" . $catid );
						$db->query( "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_admins WHERE catid=" . $catid );

						nv_fix_cat_order();
						nv_del_moduleCache( $module_name );
						Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cat&parentid=' . $parentid );
						die();
					}
					elseif( ! empty( $movecat ) and $catidnews > 0 and $catidnews != $catid )
					{
						list( $catidnews, $newstitle ) = $db->query( "SELECT catid, title FROM " . NV_PREFIXLANG . "_" . $module_data . "_cat WHERE catid =" . $catidnews )->fetch( 3 );
						if( $catidnews > 0 )
						{
							nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['move'], $title . ' --> ' . $newstitle, $admin_info['userid'] );

							$sql = $db->query( "SELECT id, catid, listcatid FROM " . NV_PREFIXLANG . "_" . $module_data . "_" . $catid );
							while( $row = $sql->fetch() )
							{
								$arr_catid_old = explode( ',', $row['listcatid'] );
								$arr_catid_i = array( $catid );
								$arr_catid_news = array_diff( $arr_catid_old, $arr_catid_i );
								if( ! in_array( $catidnews, $arr_catid_news ) )
								{
									$db->query( "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_" . $catidnews . " SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE id=" . $row['id'] );
									$arr_catid_news[] = $catidnews;
								}
								if( $catid == $row['catid'] )
								{
									$row['catid'] = $catidnews;
								}
								foreach( $arr_catid_news as $catid_i )
								{
									$db->query( "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_" . $catid_i . " SET catid=" . $row['catid'] . ", listcatid = '" . implode( ',', $arr_catid_news ) . "' WHERE id =" . $row['id'] );
								}
								$db->query( "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_rows SET catid=" . $row['catid'] . ", listcatid = '" . implode( ',', $arr_catid_news ) . "' WHERE id =" . $row['id'] );
							}
							$db->query( "DROP TABLE " . NV_PREFIXLANG . "_" . $module_data . "_" . $catid );
							$db->query( "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_cat WHERE catid=" . $catid );
							$db->query( "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_admins WHERE catid=" . $catid );

							nv_fix_cat_order();
							nv_del_moduleCache( $module_name );
							Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cat&parentid=' . $parentid );
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
			if( $delallcheckss == md5( $catid . session_id() ) )
			{
				$sql = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_cat WHERE catid=" . $catid;
				if( $db->exec( $sql ) )
				{
					nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['delcatandrows'], $title, $admin_info['userid'] );
					nv_fix_cat_order();
					$db->query( "DROP TABLE " . NV_PREFIXLANG . "_" . $module_data . "_" . $catid );
					$contents = "OK_" . $parentid;
				}
				$db->query( "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_admins WHERE catid=" . $catid );
				nv_del_moduleCache( $module_name );
			}
			else
			{
				$contents = "CONFIRM_" . $catid . "_" . md5( $catid . session_id() );
			}
		}
	}
	else
	{
		$contents = "ERR_CAT_" . $lang_module['delcat_msg_cat_permissions'];
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
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cat' );
	die();
}