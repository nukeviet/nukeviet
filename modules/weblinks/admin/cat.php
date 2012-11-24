<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['categories'];
$error = "";

$catid = $nv_Request->get_int( 'catid', 'get', 0 );
$pid = $nv_Request->get_int( 'pid', 'get', 0 );

$data_content = array( 'catid' => $catid, 'parentid_old' => 0, 'parentid' => $pid, 'title' => '', 'alias' => '', 'description' => '', 'keywords' => '' );
	
// Get array catid
$querysubcat = $db->sql_query( "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` ORDER BY `parentid`, `weight` ASC" );
$array_cat = array();
$numcat = 0;

while( $row = $db->sql_fetchrow( $querysubcat, 2 ) )
{
	$array_cat[$row['catid']] = $row;
	if( $row['parentid'] == $pid ) ++$numcat;
}

if( $pid > 0 ) $page_title = $lang_module['categories'] . " : " . $array_cat[$pid]['title'];

//post data
$savecat = $nv_Request->get_int( 'savecat', 'post', 0 );

if( ! empty( $savecat ) )
{
	$data_content['catid'] = $nv_Request->get_int( 'catid', 'post', 0 );
	$data_content['parentid_old'] = $nv_Request->get_int( 'parentid_old', 'post', 0 );
	$data_content['parentid'] = $nv_Request->get_int( 'parentid', 'post', 0 );
	$data_content['title'] = filter_text_input( 'title', 'post', "", 1, 100 );
	$data_content['catimage'] = filter_text_input( 'catimage', 'post' );
	$data_content['keywords'] = filter_text_input( 'keywords', 'post' );
	$data_content['alias'] = filter_text_input( 'alias', 'post', '', 1, 100 );
	$data_content['description'] = filter_text_textarea( 'description', '', NV_ALLOWED_HTML_TAGS );
	$data_content['alias'] = ( $data_content['alias'] == "" ) ? change_alias( $data_content['title'] ) : change_alias( $data_content['alias'] );

	if( empty( $data_content['title'] ) )
	{
		$error = $lang_module['weblink_sub_input'];
	}
	else
	{
		if( $data_content['catid'] == 0 )
		{
			list( $weight ) = $db->sql_fetchrow( $db->sql_query( "SELECT max(`weight`) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` WHERE `parentid`=" . $db->dbescape( $data_content['parentid'] ) . "" ) );
			$weight = intval( $weight ) + 1;
			
			$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_cat` (`catid`, `parentid`, `title`, `catimage`, `alias`, `description`, `weight`, `inhome`, `numlinks`, `keywords`, `add_time`, `edit_time`) VALUES (NULL, " . $db->dbescape( $data_content['parentid'] ) . ", " . $db->dbescape( $data_content['title'] ) . ", " . $db->dbescape( $data_content['catimage'] ) . " , " . $db->dbescape( $data_content['alias'] ) . ", " . $db->dbescape( $data_content['description'] ) . ", " . $db->dbescape( $weight ) . ", '1', '3', " . $db->dbescape( $data_content['keywords'] ) . ", UNIX_TIMESTAMP(), UNIX_TIMESTAMP())";
			
			$idnew = $db->sql_query_insert_id( $sql );

			if( $idnew > 0 )
			{
				nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['add_cat'], $data_content['title'], $admin_info['userid'] );
				$db->sql_freeresult();
				nv_del_moduleCache( $module_name );
				Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&pid=" . $data_content['parentid'] );
				die();
			}
			else
			{
				$error = $lang_module['errorsave'];
			}
		}
		elseif( $data_content['catid'] > 0 )
		{
			$check_exit = 0;

			if( $data_content['parentid'] != $data_content['parentid_old'] )
			{
				list( $check_exit ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `catid` = '" . $data_content['catid'] . "'" ) );
			}

			if( intval( $check_exit ) > 0 )
			{
				$error = "error delete cat";
			}
			else
			{
				$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_cat` SET `parentid`=" . $db->dbescape( $data_content['parentid'] ) . ", `title`=" . $db->dbescape( $data_content['title'] ) . ", `catimage` =  " . $db->dbescape( $data_content['catimage'] ) . ", `alias` =  " . $db->dbescape( $data_content['alias'] ) . ", `description`=" . $db->dbescape( $data_content['description'] ) . ", `keywords`= " . $db->dbescape( $data_content['keywords'] ) . ", `edit_time`=UNIX_TIMESTAMP( ) WHERE `catid` =" . $data_content['catid'] . "";
				$db->sql_query( $sql );

				if( $db->sql_affectedrows() > 0 )
				{
					$db->sql_freeresult();

					if( $data_content['parentid'] != $data_content['parentid_old'] )
					{
						list( $weight ) = $db->sql_fetchrow( $db->sql_query( "SELECT max(`weight`) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` WHERE `parentid`=" . $db->dbescape( $data_content['parentid'] ) . "" ) );
						$weight = intval( $weight ) + 1;
						$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_cat` SET `weight`=" . $weight . " WHERE `catid`=" . intval( $data_content['catid'] );
						$db->sql_query( $sql );
						nv_fix_cat( $data_content['parentid'] );
						nv_fix_cat( $data_content['parentid_old'] );
					}

					nv_del_moduleCache( $module_name );
					nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['edit_cat'], $data_content['title'], $admin_info['userid'] );

					Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&pid=" . $data_content['parentid'] );
					die();
				}
				else
				{
					$error = $lang_module['errorsave'];
				}
				$db->sql_freeresult();
			}
		}
	}
}

if( $data_content['catid'] > 0 )
{
	$data_content = $array_cat[$data_content['catid']];
	$caption = $lang_module['edit_cat'];
}
else
{
	$data_content['catimage'] = '';
	$caption = $lang_module['add_cat'];
}

$lang_module['edit'] = $lang_global['edit'];
$lang_module['delete'] = $lang_global['delete'];

$xtpl = new XTemplate( "cat.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'DATA', $data_content );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'module_name', $module_name );
$xtpl->assign( 'PATH', NV_UPLOADS_DIR . '/' . $module_name );
$xtpl->assign( 'catid', $data_content['catid'] );

// get catid
if( ! empty( $array_cat ) )
{
	foreach( $array_cat as $cat )
	{
		$xtitle = "";
		
		if( $cat['catid'] != $data_content['catid'] )
		{
			if( $cat['parentid'] != 0 ) $xtitle = getlevel( $cat['parentid'], $array_cat );
			$cat['xtitle'] = $xtitle . $cat['title'];
			$cat['sl'] = ( $cat['catid'] == $data_content['parentid'] ) ? "selected=\"selected\"" : "";
			$xtpl->assign( 'CAT', $cat );
			$xtpl->parse( 'main.loopcat' );
		}
		
		if( $cat['parentid'] == $data_content['parentid'] )
		{
			$cat['link_add'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat&amp;pid=" . $cat['catid'] . "";
			$cat['link_edit'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat&amp;catid=" . $cat['catid'] . "";
			$cat['weight_select'] = drawselect_number( "change", 1, $numcat, $cat['weight'], "nv_chang_cat(this,'" . $cat['catid'] . "','weight');" );
			$cat['inhome_select'] = drawselect_yesno( $select_name = "slinhome", $cat['inhome'], $lang_module['weblink_no'], $lang_module['weblink_yes'], "nv_chang_cat(this,'" . $cat['catid'] . "','inhome');" );
			$xtpl->assign( 'ROW', $cat );
			$xtpl->parse( 'main.data.loop' );
		}
	}
	
	$xtpl->assign( 'url_back', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat&pid=" . $data_content['parentid'] . "" );
	if( $numcat > 0 ) $xtpl->parse( 'main.data' );
}

$xtpl->assign( 'DATA', $data_content );
$xtpl->assign( 'caption', $caption );
$xtpl->assign( 'PATH', NV_UPLOADS_DIR . '/' . $module_name );
$xtpl->assign( 'UPLOAD_CURRENT', NV_UPLOADS_DIR . '/' . $module_name . '/cat' );

if( ! empty( $error ) )
{
	$xtpl->assign( 'error', $error );
	$xtpl->parse( 'main.error' );
}

$xtpl->parse( 'main' );
$contents .= $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>