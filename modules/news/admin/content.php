<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

if( defined( 'NV_EDITOR' ) )
{
	require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

$username_alias = change_alias( $admin_info['username'] );
$array_structure_image = array();
$array_structure_image[''] = $module_name;
$array_structure_image['Y'] = $module_name . '/' . date( 'Y' );
$array_structure_image['Ym'] = $module_name . '/' . date( 'Y_m' );
$array_structure_image['Y_m'] = $module_name . '/' . date( 'Y/m' );
$array_structure_image['Ym_d'] = $module_name . '/' . date( 'Y_m/d' );
$array_structure_image['Y_m_d'] = $module_name . '/' . date( 'Y/m/d' );
$array_structure_image['username'] = $module_name . '/' . $username_alias;

$array_structure_image['username_Y'] = $module_name . '/' . $username_alias . '/' . date( 'Y' );
$array_structure_image['username_Ym'] = $module_name . '/' . $username_alias . '/' . date( 'Y_m' );
$array_structure_image['username_Y_m'] = $module_name . '/' . $username_alias . '/' . date( 'Y/m' );
$array_structure_image['username_Ym_d'] = $module_name . '/' . $username_alias . '/' . date( 'Y_m/d' );
$array_structure_image['username_Y_m_d'] = $module_name . '/' . $username_alias . '/' . date( 'Y/m/d' );

$structure_upload = isset( $module_config[$module_name]['structure_upload'] ) ? $module_config[$module_name]['structure_upload'] : 'Ym';
$currentpath = isset( $array_structure_image[$structure_upload] ) ? $array_structure_image[$structure_upload] : '';

if( file_exists( NV_UPLOADS_REAL_DIR . '/' . $currentpath ) )
{
	$upload_real_dir_page = NV_UPLOADS_REAL_DIR . '/' . $currentpath;
}
else
{
	$upload_real_dir_page = NV_UPLOADS_REAL_DIR . '/' . $module_name;
	$e = explode( '/', $currentpath );
	if( ! empty( $e ) )
	{
		$cp = '';
		foreach( $e as $p )
		{
			if( ! empty( $p ) and ! is_dir( NV_UPLOADS_REAL_DIR . '/' . $cp . $p ) )
			{
				$mk = nv_mkdir( NV_UPLOADS_REAL_DIR . '/' . $cp, $p );
				if( $mk[0] > 0 )
				{
					$upload_real_dir_page = $mk[2];
					$db->query( "INSERT INTO " . NV_UPLOAD_GLOBALTABLE . "_dir (dirname, time) VALUES ('" . NV_UPLOADS_DIR . "/" . $cp . $p . "', 0)" );
				}
			}
			elseif( ! empty( $p ) )
			{
				$upload_real_dir_page = NV_UPLOADS_REAL_DIR . '/' . $cp . $p;
			}
			$cp .= $p . '/';
		}
	}
	$upload_real_dir_page = str_replace( '\\', '/', $upload_real_dir_page );
}

$currentpath = str_replace( NV_ROOTDIR . '/', '', $upload_real_dir_page );
$uploads_dir_user = NV_UPLOADS_DIR . '/' . $module_name;
if( ! defined( 'NV_IS_SPADMIN' ) and strpos( $structure_upload, 'username' ) !== false )
{
	$array_currentpath = explode( '/', $currentpath );
	if( $array_currentpath[2] == $username_alias )
	{
		$uploads_dir_user = NV_UPLOADS_DIR . '/' . $module_name . '/' . $username_alias;
	}
}

$array_block_cat_module = array();
$id_block_content = array();
$sql = 'SELECT bid, adddefault, title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block_cat ORDER BY weight ASC';
$result = $db->query( $sql );
while( list( $bid_i, $adddefault_i, $title_i ) = $result->fetch( 3 ) )
{
	$array_block_cat_module[$bid_i] = $title_i;
	if( $adddefault_i )
	{
		$id_block_content[] = $bid_i;
	}
}

$catid = $nv_Request->get_int( 'catid', 'get', 0 );
$parentid = $nv_Request->get_int( 'parentid', 'get', 0 );
$array_imgposition = array(
	0 => $lang_module['imgposition_0'],
	1 => $lang_module['imgposition_1'],
	2 => $lang_module['imgposition_2'] );

$rowcontent = array(
	'id' => '',
	'catid' => $catid,
	'listcatid' => $catid . ',' . $parentid,
	'topicid' => '',
	'admin_id' => $admin_id,
	'author' => '',
	'sourceid' => 0,
	'addtime' => NV_CURRENTTIME,
	'edittime' => NV_CURRENTTIME,
	'status' => 0,
	'publtime' => NV_CURRENTTIME,
	'exptime' => 0,
	'archive' => 1,
	'title' => '',
	'alias' => '',
	'hometext' => '',
	'sourcetext' => '',
	'homeimgfile' => '',
	'homeimgalt' => '',
	'homeimgthumb' => '',
	'imgposition' => 1,
	'bodyhtml' => '',
	'copyright' => 0,
	'gid' => 0,
	'inhome' => 1,
	'allowed_comm' => $module_config[$module_name]['setcomm'],
	'allowed_rating' => 1,
	'allowed_send' => 1,
	'allowed_print' => 1,
	'allowed_save' => 1,
	'hitstotal' => 0,
	'hitscm' => 0,
	'total_rating' => 0,
	'click_rating' => 0,
	'keywords' => '',
	'keywords_old' => '',
	'mode' => 'add'
);

$rowcontent['topictext'] = '';
$page_title = $lang_module['content_add'];
$error = array();
$groups_list = nv_groups_list();
$array_keywords_old = array();

$rowcontent['id'] = $nv_Request->get_int( 'id', 'get,post', 0 );
if( $rowcontent['id'] > 0 )
{
	$check_permission = false;
	$rowcontent = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows where id=' . $rowcontent['id'] )->fetch();
	if( ! empty( $rowcontent['id'] ) )
	{
		$rowcontent['mode'] = 'edit';
		$arr_catid = explode( ',', $rowcontent['listcatid'] );
		if( defined( 'NV_IS_ADMIN_MODULE' ) )
		{
			$check_permission = true;
		}
		else
		{
			$check_edit = 0;
			$status = $rowcontent['status'];
			foreach( $arr_catid as $catid_i )
			{
				if( isset( $array_cat_admin[$admin_id][$catid_i] ) )
				{
					if( $array_cat_admin[$admin_id][$catid_i]['admin'] == 1 )
					{
						++$check_edit;
					}
					else
					{
						if( $array_cat_admin[$admin_id][$catid_i]['edit_content'] == 1 )
						{
							++$check_edit;
						}
						elseif( $array_cat_admin[$admin_id][$catid_i]['pub_content'] == 1 and ( $status == 0 or $status = 2 ) )
						{
							++$check_edit;
						}
						elseif( $status == 0 and $rowcontent['admin_id'] == $admin_id )
						{
							++$check_edit;
						}
					}
				}
			}
			if( $check_edit == sizeof( $arr_catid ) )
			{
				$check_permission = true;
			}
		}
	}

	if( ! $check_permission )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
		die();
	}

	$page_title = $lang_module['content_edit'];
	$rowcontent['topictext'] = '';

	$body_contents = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_bodyhtml_' . ceil( $rowcontent['id'] / 2000 ) . ' where id=' . $rowcontent['id'] )->fetch();
	$rowcontent = array_merge( $rowcontent, $body_contents );
	unset( $body_contents );

	$_query = $db->query( 'SELECT tid, keyword FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id WHERE id=' . $rowcontent['id'] . ' ORDER BY keyword ASC' );
	while( $row = $_query->fetch() )
	{
		$array_keywords_old[$row['tid']] = $row['keyword'];
	}
	$rowcontent['keywords'] = implode( ', ', $array_keywords_old );
	$rowcontent['keywords_old'] = $rowcontent['keywords'];

	$id_block_content = array();
	$sql = 'SELECT bid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block where id=' . $rowcontent['id'];
	$result = $db->query( $sql );
	while( list( $bid_i ) = $result->fetch( 3 ) )
	{
		$id_block_content[] = $bid_i;
	}
}

$array_cat_add_content = $array_cat_pub_content = $array_cat_edit_content = $array_censor_content = array();
foreach( $global_array_cat as $catid_i => $array_value )
{
	$check_add_content = $check_pub_content = $check_edit_content = $check_censor_content = false;
	if( defined( 'NV_IS_ADMIN_MODULE' ) )
	{
		$check_add_content = $check_pub_content = $check_edit_content = $check_censor_content = true;
	}
	elseif( isset( $array_cat_admin[$admin_id][$catid_i] ) )
	{
		if( $array_cat_admin[$admin_id][$catid_i]['admin'] == 1 )
		{
			$check_add_content = $check_pub_content = $check_edit_content = $check_censor_content = true;
		}
		else
		{
			if( $array_cat_admin[$admin_id][$catid_i]['add_content'] == 1 )
			{
				$check_add_content = true;
			}

			if( $array_cat_admin[$admin_id][$catid_i]['pub_content'] == 1 )
			{
				$check_censor_content = true;
			}

			if( $array_cat_admin[$admin_id][$catid_i]['edit_content'] == 1 )
			{
				$check_edit_content = true;
			}
		}
	}
	if( $check_add_content )
	{
		$array_cat_add_content[] = $catid_i;
	}

	if( $check_pub_content )
	{
		$array_cat_pub_content[] = $catid_i;
	}
	if( $check_censor_content ) //Nguoi kiem duyet
	{
		$array_censor_content[] = $catid_i;
	}

	if( $check_edit_content )
	{
		$array_cat_edit_content[] = $catid_i;
	}
}

if( $nv_Request->get_int( 'save', 'post' ) == 1 )
{
	$catids = array_unique( $nv_Request->get_typed_array( 'catids', 'post', 'int', array() ) );
	$id_block_content_post = array_unique( $nv_Request->get_typed_array( 'bids', 'post', 'int', array() ) );

	$rowcontent['catid'] = $nv_Request->get_int( 'catid', 'post', 0 );

	$rowcontent['listcatid'] = implode( ',', $catids );

	if( $nv_Request->isset_request( 'status1', 'post' ) ) $rowcontent['status'] = 1; //dang tin
	elseif( $nv_Request->isset_request( 'status0', 'post' ) ) $rowcontent['status'] = 0; //cho tong bien tap duyet
	elseif( $nv_Request->isset_request( 'status4', 'post' ) ) $rowcontent['status'] = 4; //luu tam
	else  $rowcontent['status'] = 6; //gui, cho bien tap

	$message_error_show = $lang_module['permissions_pub_error'];
	if( $rowcontent['status'] == 1 )
	{
		$array_cat_check_content = $array_cat_pub_content;
	}
	elseif( $rowcontent['status'] == 1 and $rowcontent['publtime'] <= NV_CURRENTTIME )
	{
		$array_cat_check_content = $array_cat_edit_content;
	}
	elseif( $rowcontent['status'] == 0 )
	{
		$array_cat_check_content = $array_censor_content;
		$message_error_show = $lang_module['permissions_sendspadmin_error'];
	}
	else
	{
		$array_cat_check_content = $array_cat_add_content;
	}

	foreach( $catids as $catid_i )
	{
		if( ! in_array( $catid_i, $array_cat_check_content ) )
		{
			$error[] = sprintf( $message_error_show, $global_array_cat[$catid_i]['title'] );
		}
	}

	$rowcontent['topicid'] = $nv_Request->get_int( 'topicid', 'post', 0 );
	if( $rowcontent['topicid'] == 0 )
	{
		$rowcontent['topictext'] = $nv_Request->get_title( 'topictext', 'post', '' );
		if( ! empty( $rowcontent['topictext'] ) )
		{
			$stmt = $db->prepare( 'SELECT topicid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_topics WHERE title= :title' );
			$stmt->bindParam( ':title', $rowcontent['topictext'], PDO::PARAM_STR );
			$stmt->execute();
			$rowcontent['topicid'] = $stmt->fetchColumn();
		}
	}
	$rowcontent['author'] = $nv_Request->get_title( 'author', 'post', '', 1 );
	$rowcontent['sourcetext'] = $nv_Request->get_title( 'sourcetext', 'post', '' );

	$publ_date = $nv_Request->get_title( 'publ_date', 'post', '' );

	if( ! empty( $publ_date ) and preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $publ_date, $m ) )
	{
		$phour = $nv_Request->get_int( 'phour', 'post', 0 );
		$pmin = $nv_Request->get_int( 'pmin', 'post', 0 );
		$rowcontent['publtime'] = mktime( $phour, $pmin, 0, $m[2], $m[1], $m[3] );
	}
	else
	{
		$rowcontent['publtime'] = NV_CURRENTTIME;
	}

	$exp_date = $nv_Request->get_title( 'exp_date', 'post', '' );
	if( ! empty( $exp_date ) and preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $exp_date, $m ) )
	{
		$ehour = $nv_Request->get_int( 'ehour', 'post', 0 );
		$emin = $nv_Request->get_int( 'emin', 'post', 0 );
		$rowcontent['exptime'] = mktime( $ehour, $emin, 0, $m[2], $m[1], $m[3] );
	}
	else
	{
		$rowcontent['exptime'] = 0;
	}

	$rowcontent['archive'] = $nv_Request->get_int( 'archive', 'post', 0 );
	if( $rowcontent['archive'] > 0 )
	{
		$rowcontent['archive'] = ( $rowcontent['exptime'] > NV_CURRENTTIME ) ? 1 : 2;
	}
	$rowcontent['title'] = $nv_Request->get_title( 'title', 'post', '', 1 );

	$alias = $nv_Request->get_title( 'alias', 'post', '' );
	$rowcontent['alias'] = ( $alias == '' ) ? change_alias( $rowcontent['title'] ) : change_alias( $alias );

	$rowcontent['hometext'] = $nv_Request->get_title( 'hometext', 'post', '' );

	$rowcontent['homeimgfile'] = $nv_Request->get_title( 'homeimg', 'post', '' );
	$rowcontent['homeimgalt'] = $nv_Request->get_title( 'homeimgalt', 'post', '', 1 );
	$rowcontent['imgposition'] = $nv_Request->get_int( 'imgposition', 'post', 0 );
	if( ! array_key_exists( $rowcontent['imgposition'], $array_imgposition ) )
	{
		$rowcontent['imgposition'] = 1;
	}
	$rowcontent['bodyhtml'] = $nv_Request->get_editor( 'bodyhtml', '', NV_ALLOWED_HTML_TAGS );

	$rowcontent['copyright'] = ( int )$nv_Request->get_bool( 'copyright', 'post' );
	$rowcontent['inhome'] = ( int )$nv_Request->get_bool( 'inhome', 'post' );

	$_groups_post = $nv_Request->get_array( 'allowed_comm', 'post', array() );
	$rowcontent['allowed_comm'] = ! empty( $_groups_post ) ? implode( ',', nv_groups_post( array_intersect( $_groups_post, array_keys( $groups_list ) ) ) ) : '';

	$rowcontent['allowed_rating'] = ( int )$nv_Request->get_bool( 'allowed_rating', 'post' );
	$rowcontent['allowed_send'] = ( int )$nv_Request->get_bool( 'allowed_send', 'post' );
	$rowcontent['allowed_print'] = ( int )$nv_Request->get_bool( 'allowed_print', 'post' );
	$rowcontent['allowed_save'] = ( int )$nv_Request->get_bool( 'allowed_save', 'post' );
	$rowcontent['gid'] = $nv_Request->get_int( 'gid', 'post', 0 );

	$rowcontent['keywords'] = $nv_Request->get_array( 'keywords', 'post', '' );
    $rowcontent['keywords'] = implode(', ', $rowcontent['keywords'] );

	if( $rowcontent['keywords'] == '' )
	{
		if( $rowcontent['hometext'] != '' )
		{
			$rowcontent['keywords'] = nv_get_keywords( $rowcontent['hometext'] );
		}
		else
		{
			$rowcontent['keywords'] = nv_get_keywords( $rowcontent['bodyhtml'] );
		}
	}

	if( empty( $rowcontent['title'] ) )
	{
		$error[] = $lang_module['error_title'];
	}
	elseif( empty( $rowcontent['listcatid'] ) )
	{
		$error[] = $lang_module['error_cat'];
	}
	elseif( trim( strip_tags( $rowcontent['bodyhtml'] ) ) == '' )
	{
		$error[] = $lang_module['error_bodytext'];
	}

	if( empty( $error ) )
	{
		$rowcontent['catid'] = in_array( $rowcontent['catid'], $catids ) ? $rowcontent['catid'] : $catids[0];
		$rowcontent['bodytext'] = nv_news_get_bodytext( $rowcontent['bodyhtml'] );

		if( ! empty( $rowcontent['topictext'] ) and empty( $rowcontent['topicid'] ) )
		{
			$weightopic = $db->query( 'SELECT max(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_topics' )->fetchColumn();
			$weightopic = intval( $weightopic ) + 1;
			$aliastopic = change_alias( $rowcontent['topictext'] );
			$_sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_topics (title, alias, description, image, weight, keywords, add_time, edit_time) VALUES ( :title, :alias, :description, '', :weight, :keywords, " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ")";
			$data_insert = array();
			$data_insert['title'] = $rowcontent['topictext'];
			$data_insert['alias'] = $aliastopic;
			$data_insert['description'] = $rowcontent['topictext'];
			$data_insert['weight'] = $weightopic;
			$data_insert['keywords'] = $rowcontent['topictext'];
			$rowcontent['topicid'] = $db->insert_id( $_sql, 'topicid', $data_insert );
		}

		$rowcontent['sourceid'] = 0;
		if( ! empty( $rowcontent['sourcetext'] ) )
		{
			$url_info = @parse_url( $rowcontent['sourcetext'] );
			if( isset( $url_info['scheme'] ) and isset( $url_info['host'] ) )
			{
				$sourceid_link = $url_info['scheme'] . '://' . $url_info['host'];
				$stmt = $db->prepare( 'SELECT sourceid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources WHERE link= :link' );
				$stmt->bindParam( ':link', $sourceid_link, PDO::PARAM_STR );
				$stmt->execute();
				$rowcontent['sourceid'] = $stmt->fetchColumn();

				if( empty( $rowcontent['sourceid'] ) )
				{
					$weight = $db->query( 'SELECT max(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources' )->fetchColumn();
					$weight = intval( $weight ) + 1;
					$_sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_sources (title, link, logo, weight, add_time, edit_time) VALUES ( :title ,:sourceid_link, '', :weight, " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ")";

					$data_insert = array();
					$data_insert['title'] = $url_info['host'];
					$data_insert['sourceid_link'] = $sourceid_link;
					$data_insert['weight'] = $weight;

					$rowcontent['sourceid'] = $db->insert_id( $_sql, 'sourceid', $data_insert );
				}
			}
			else
			{
				$stmt = $db->prepare( 'SELECT sourceid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources WHERE title= :title' );
				$stmt->bindParam( ':title', $rowcontent['sourcetext'], PDO::PARAM_STR );
				$stmt->execute();
				$rowcontent['sourceid'] = $stmt->fetchColumn();

				if( empty( $rowcontent['sourceid'] ) )
				{
					$weight = $db->query( 'SELECT max(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources' )->fetchColumn();
					$weight = intval( $weight ) + 1;
					$_sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_sources (title, link, logo, weight, add_time, edit_time) VALUES ( :title, '', '', " . $weight . " , " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ")";
					$data_insert = array();
					$data_insert['title'] = $rowcontent['sourcetext'];

					$rowcontent['sourceid'] = $db->insert_id( $_sql, 'sourceid', $data_insert );
				}
			}
		}

		// Xu ly anh minh hoa
		$rowcontent['homeimgthumb'] = 0;
		if( ! nv_is_url( $rowcontent['homeimgfile'] ) and is_file( NV_DOCUMENT_ROOT . $rowcontent['homeimgfile'] ) )
		{
			$lu = strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' );
			$rowcontent['homeimgfile'] = substr( $rowcontent['homeimgfile'], $lu );
			if( file_exists( NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_name . '/' . $rowcontent['homeimgfile'] ) )
			{
				$rowcontent['homeimgthumb'] = 1;
			}
			else
			{
				$rowcontent['homeimgthumb'] = 2;
			}
		}
		elseif( nv_is_url( $rowcontent['homeimgfile'] ) )
		{
			$rowcontent['homeimgthumb'] = 3;
		}
		else
		{
			$rowcontent['homeimgfile'] = '';
		}

		if( $rowcontent['id'] == 0 )
		{
			$rowcontent['publtime'] = ( $rowcontent['publtime'] > NV_CURRENTTIME ) ? $rowcontent['publtime'] : NV_CURRENTTIME;
			if( $rowcontent['status'] == 1 and $rowcontent['publtime'] > NV_CURRENTTIME )
			{
				$rowcontent['status'] = 2;
			}
			$sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_rows
				(catid, listcatid, topicid, admin_id, author, sourceid, addtime, edittime, status, publtime, exptime, archive, title, alias, hometext, homeimgfile, homeimgalt, homeimgthumb, inhome, allowed_comm, allowed_rating, hitstotal, hitscm, total_rating, click_rating) VALUES
				 (' . intval( $rowcontent['catid'] ) . ',
				 :listcatid,
				 ' . $rowcontent['topicid'] . ',
				 ' . intval( $rowcontent['admin_id'] ) . ',
				 :author,
				 ' . intval( $rowcontent['sourceid'] ) . ',
				 ' . intval( $rowcontent['addtime'] ) . ',
				 ' . intval( $rowcontent['edittime'] ) . ',
				 ' . intval( $rowcontent['status'] ) . ',
				 ' . intval( $rowcontent['publtime'] ) . ',
				 ' . intval( $rowcontent['exptime'] ) . ',
				 ' . intval( $rowcontent['archive'] ) . ',
				 :title,
				 :alias,
				 :hometext,
				 :homeimgfile,
				 :homeimgalt,
				 :homeimgthumb,
				 ' . intval( $rowcontent['inhome'] ) . ',
				 :allowed_comm,
				 ' . intval( $rowcontent['allowed_rating'] ) . ',
				 ' . intval( $rowcontent['hitstotal'] ) . ',
				 ' . intval( $rowcontent['hitscm'] ) . ',
				 ' . intval( $rowcontent['total_rating'] ) . ',
				 ' . intval( $rowcontent['click_rating'] ) . ')';

			$data_insert = array();
			$data_insert['listcatid'] = $rowcontent['listcatid'];
			$data_insert['author'] = $rowcontent['author'];
			$data_insert['title'] = $rowcontent['title'];
			$data_insert['alias'] = $rowcontent['alias'];
			$data_insert['hometext'] = $rowcontent['hometext'];
			$data_insert['homeimgfile'] = $rowcontent['homeimgfile'];
			$data_insert['homeimgalt'] = $rowcontent['homeimgalt'];
			$data_insert['homeimgthumb'] = $rowcontent['homeimgthumb'];
			$data_insert['allowed_comm'] = $rowcontent['allowed_comm'];

			$rowcontent['id'] = $db->insert_id( $sql, 'id', $data_insert );
			if( $rowcontent['id'] > 0 )
			{
				nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['content_add'], $rowcontent['title'], $admin_info['userid'] );
				$ct_query = array();

				$tbhtml = NV_PREFIXLANG . '_' . $module_data . '_bodyhtml_' . ceil( $rowcontent['id'] / 2000 );
				$db->query( "CREATE TABLE IF NOT EXISTS " . $tbhtml . " (id int(11) unsigned NOT NULL, bodyhtml longtext NOT NULL, sourcetext varchar(255) NOT NULL default '', imgposition tinyint(1) NOT NULL default '1', copyright tinyint(1) NOT NULL default '0', allowed_send tinyint(1) NOT NULL default '0', allowed_print tinyint(1) NOT NULL default '0', allowed_save tinyint(1) NOT NULL default '0', gid mediumint(9) NOT NULL DEFAULT '0', PRIMARY KEY (id)) ENGINE=MyISAM" );

				$stmt = $db->prepare( 'INSERT INTO ' . $tbhtml . ' VALUES
					(' . $rowcontent['id'] . ',
					 :bodyhtml,
					 :sourcetext,
					 ' . $rowcontent['imgposition'] . ',
					 ' . $rowcontent['copyright'] . ',
					 ' . $rowcontent['allowed_send'] . ',
					 ' . $rowcontent['allowed_print'] . ',
					 ' . $rowcontent['allowed_save'] . ',
					 ' . $rowcontent['gid'] . '
					 )' );
				$stmt->bindParam( ':bodyhtml', $rowcontent['bodyhtml'], PDO::PARAM_STR, strlen( $rowcontent['bodyhtml'] ) );
				$stmt->bindParam( ':sourcetext', $rowcontent['sourcetext'], PDO::PARAM_STR, strlen( $rowcontent['sourcetext'] ) );
				$ct_query[] = ( int )$stmt->execute();

				foreach( $catids as $catid )
				{
					$ct_query[] = ( int )$db->exec( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . $rowcontent['id'] );
				}

				$stmt = $db->prepare( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_bodytext VALUES (' . $rowcontent['id'] . ', :bodytext )' );
				$stmt->bindParam( ':bodytext', $rowcontent['bodytext'], PDO::PARAM_STR, strlen( $rowcontent['bodytext'] ) );
				$ct_query[] = ( int )$stmt->execute();

				if( array_sum( $ct_query ) != sizeof( $ct_query ) )
				{
					$error[] = $lang_module['errorsave'];
				}
				unset( $ct_query );
			}
			else
			{
				$error[] = $lang_module['errorsave'];
			}
		}
		else
		{
			$rowcontent_old = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows where id=' . $rowcontent['id'] )->fetch();
			if( $rowcontent_old['status'] == 1 )
			{
				$rowcontent['status'] = 1;
			}
			if( intval( $rowcontent['publtime'] ) < intval( $rowcontent_old['addtime'] ) )
			{
				$rowcontent['publtime'] = $rowcontent_old['addtime'];
			}

			if( $rowcontent['status'] == 1 and $rowcontent['publtime'] > NV_CURRENTTIME )
			{
				$rowcontent['status'] = 2;
			}
			$sth = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET
					 catid=' . intval( $rowcontent['catid'] ) . ',
					 listcatid=:listcatid,
					 topicid=' . $rowcontent['topicid'] . ',
					 author=:author,
					 sourceid=' . intval( $rowcontent['sourceid'] ) . ',
					 status=' . intval( $rowcontent['status'] ) . ',
					 publtime=' . intval( $rowcontent['publtime'] ) . ',
					 exptime=' . intval( $rowcontent['exptime'] ) . ',
					 archive=' . intval( $rowcontent['archive'] ) . ',
					 title=:title,
					 alias=:alias,
					 hometext=:hometext,
					 homeimgfile=:homeimgfile,
					 homeimgalt=:homeimgalt,
					 homeimgthumb=:homeimgthumb,
					 inhome=' . intval( $rowcontent['inhome'] ) . ',
					 allowed_comm=:allowed_comm,
					 allowed_rating=' . intval( $rowcontent['allowed_rating'] ) . ',
					 edittime=' . NV_CURRENTTIME . '
				WHERE id =' . $rowcontent['id'] );

			$sth->bindParam( ':listcatid', $rowcontent['listcatid'], PDO::PARAM_STR );
			$sth->bindParam( ':author', $rowcontent['author'], PDO::PARAM_STR );
			$sth->bindParam( ':title', $rowcontent['title'], PDO::PARAM_STR );
			$sth->bindParam( ':alias', $rowcontent['alias'], PDO::PARAM_STR );
			$sth->bindParam( ':hometext', $rowcontent['hometext'], PDO::PARAM_STR, strlen( $rowcontent['hometext'] ) );
			$sth->bindParam( ':homeimgfile', $rowcontent['homeimgfile'], PDO::PARAM_STR );
			$sth->bindParam( ':homeimgalt', $rowcontent['homeimgalt'], PDO::PARAM_STR );
			$sth->bindParam( ':homeimgthumb', $rowcontent['homeimgthumb'], PDO::PARAM_STR );
			$sth->bindParam( ':allowed_comm', $rowcontent['allowed_comm'], PDO::PARAM_STR );

			if( $sth->execute() )
			{
				nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['content_edit'], $rowcontent['title'], $admin_info['userid'] );

				$ct_query = array();
				$sth = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_bodyhtml_' . ceil( $rowcontent['id'] / 2000 ) . ' SET
					bodyhtml=:bodyhtml,
					sourcetext=:sourcetext,
					imgposition=' . intval( $rowcontent['imgposition'] ) . ',
					copyright=' . intval( $rowcontent['copyright'] ) . ',
					allowed_send=' . intval( $rowcontent['allowed_send'] ) . ',
					allowed_print=' . intval( $rowcontent['allowed_print'] ) . ',
					allowed_save=' . intval( $rowcontent['allowed_save'] ) . ',
					gid=' . intval( $rowcontent['gid'] ) . '
				WHERE id =' . $rowcontent['id'] );

				$sth->bindParam( ':bodyhtml', $rowcontent['bodyhtml'], PDO::PARAM_STR, strlen( $rowcontent['bodyhtml'] ) );
				$sth->bindParam( ':sourcetext', $rowcontent['sourcetext'], PDO::PARAM_STR, strlen( $rowcontent['sourcetext'] ) );

				$ct_query[] = ( int )$sth->execute();

				$array_cat_old = explode( ',', $rowcontent_old['listcatid'] );
				$array_cat_new = explode( ',', $rowcontent['listcatid'] );

				$array_cat_diff = array_diff( $array_cat_old, $array_cat_new );
				foreach( $array_cat_diff as $catid )
				{
					$ct_query[] = $db->exec( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' WHERE id = ' . $rowcontent['id'] );
				}
				foreach( $array_cat_new as $catid )
				{
					$db->exec( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' WHERE id = ' . $rowcontent['id'] );
					$ct_query[] = $db->exec( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . $rowcontent['id'] );
				}

				$sth = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_bodytext SET bodytext=:bodytext WHERE id =' . $rowcontent['id'] );
				$sth->bindParam( ':bodytext', $rowcontent['bodytext'], PDO::PARAM_STR, strlen( $rowcontent['bodytext'] ) );
				$ct_query[] = ( int )$sth->execute();

				if( array_sum( $ct_query ) != sizeof( $ct_query ) )
				{
					$error[] = $lang_module['errorsave'];
				}
			}
			else
			{
				$error[] = $lang_module['errorsave'];
			}
		}

		nv_set_status_module();
		if( empty( $error ) )
		{
			$id_block_content_new = $rowcontent['mode'] == 'edit' ? array_diff( $id_block_content_post, $id_block_content ) : $id_block_content_post;
			$id_block_content_del = $rowcontent['mode'] == 'edit' ? array_diff( $id_block_content, $id_block_content_post ) : array();

			foreach( $id_block_content_new as $bid_i )
			{
				$_sql = 'SELECT max(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block WHERE bid=' . $bid_i;
				$weight = $db->query( $_sql )->fetchColumn();
				$weight = intval( $weight ) + 1;

				$db->query( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_block (bid, id, weight) VALUES (' . $bid_i . ', ' . $rowcontent['id'] . ', ' . $weight . ')' );
			}
			foreach( $id_block_content_del as $bid_i )
			{
				$db->query( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block WHERE id = ' . $rowcontent['id'] . ' AND bid = ' . $bid_i );
				nv_news_fix_block( $bid_i, false );
			}

			$id_block_content = array_keys( $array_block_cat_module );
			foreach( $id_block_content as $bid_i )
			{
				nv_news_fix_block( $bid_i, false );
			}

			if( $rowcontent['keywords'] != $rowcontent['keywords_old'] )
			{
				$keywords = explode( ',', nv_strtolower( $rowcontent['keywords'] ) );
				$keywords = array_map( 'strip_punctuation', $keywords );
				$keywords = array_map( 'trim', $keywords );
				$keywords = array_diff( $keywords, array( '' ) );
				$keywords = array_unique( $keywords );

				foreach( $keywords as $keyword )
				{
					if( ! in_array( $keyword, $array_keywords_old ) )
					{
						$alias_i = ( $module_config[$module_name]['tags_alias'] ) ? change_alias( $keyword ) : str_replace( ' ', '-', $keyword );

						$sth = $db->prepare( 'SELECT tid, alias, description, keywords FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags where alias= :alias OR FIND_IN_SET(:keyword, keywords)>0' );
						$sth->bindParam( ':alias', $alias_i, PDO::PARAM_STR );
						$sth->bindParam( ':keyword', $keyword, PDO::PARAM_STR );
						$sth->execute();

						list( $tid, $alias, $keywords_i ) = $sth->fetch( 3 );
						if( empty( $tid ) )
						{
							$array_insert = array();
							$array_insert['alias'] = $alias_i;
							$array_insert['keyword'] = $keyword;

							$tid = $db->insert_id( "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_tags (numnews, alias, description, image, keywords) VALUES (1, :alias, '', '', :keyword)", "tid", $array_insert );
						}
						else
						{
							if( $alias != $alias_i )
							{
								if( ! empty( $keywords_i ) )
								{
									$keyword_arr = explode( ',', $keywords_i );
									$keyword_arr[] = $keyword;
									$keywords_i2 = implode( ',', array_unique( $keyword_arr ) );
								}
								else
								{
									$keywords_i2 = $keyword;
								}
								if( $keywords_i != $keywords_i2 )
								{
									$sth = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tags SET keywords= :keywords WHERE tid =' . $tid );
									$sth->bindParam( ':keywords', $keywords_i2, PDO::PARAM_STR );
									$sth->execute();
								}
							}
							$db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tags SET numnews = numnews+1 WHERE tid = ' . $tid );
						}
						$sth = $db->prepare( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id (id, tid, keyword) VALUES (' . $rowcontent['id'] . ', ' . $tid . ', :keyword)' );
						$sth->bindParam( ':keyword', $keyword, PDO::PARAM_STR );
						$sth->execute();
					}
				}

				foreach( $array_keywords_old as $tid => $keyword )
				{
					if( ! in_array( $keyword, $keywords ) )
					{
						$db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tags SET numnews = numnews-1 WHERE tid = ' . $tid );
						$db->query( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id WHERE id = ' . $rowcontent['id'] . ' AND tid=' . $tid );
					}
				}
			}

			if( isset( $module_config['seotools']['prcservice'] ) and ! empty( $module_config['seotools']['prcservice'] ) and $rowcontent['status'] == 1 and $rowcontent['publtime'] < NV_CURRENTTIME + 1 and ( $rowcontent['exptime'] == 0 or $rowcontent['exptime'] > NV_CURRENTTIME + 1 ) )
			{
				Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=rpc&id=' . $rowcontent['id'] . '&rand=' . nv_genpass() );
				die();
			}
			else
			{
				$url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;
				$msg1 = $lang_module['content_saveok'];
				$msg2 = $lang_module['content_main'] . ' ' . $module_info['custom_title'];
				redriect( $msg1, $msg2, $url, $module_data . '_bodyhtml' );
			}
		}
	}
	else
	{
		$url = 'javascript: history.go(-1)';
		$msg1 = implode( '<br />', $error );
		$msg2 = $lang_module['content_back'];
		redriect( $msg1, $msg2, $url );
	}
	$id_block_content = $id_block_content_post;
}

$rowcontent['bodyhtml'] = htmlspecialchars( nv_editor_br2nl( $rowcontent['bodyhtml'] ) );

if( ! empty( $rowcontent['homeimgfile'] ) and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $rowcontent['homeimgfile'] ) )
{
	$rowcontent['homeimgfile'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $rowcontent['homeimgfile'];
}

$array_catid_in_row = explode( ',', $rowcontent['listcatid'] );

$db->sqlreset()
  ->select( 'topicid, title' )
  ->from( NV_PREFIXLANG . '_' . $module_data . '_topics' )
  ->order( 'weight ASC' )
  ->limit( 100 );
$result = $db->query( $db->sql() );

$array_topic_module = array();
$array_topic_module[0] = $lang_module['topic_sl'];

while( list( $topicid_i, $title_i ) = $result->fetch( 3 ) )
{
	$array_topic_module[$topicid_i] = $title_i;
}

$sql = 'SELECT sourceid, title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources ORDER BY weight ASC';
$result = $db->query( $sql );
$array_source_module = array();
$array_source_module[0] = $lang_module['sources_sl'];
while( list( $sourceid_i, $title_i ) = $result->fetch( 3 ) )
{
	$array_source_module[$sourceid_i] = $title_i;
}

$tdate = date( 'H|i', $rowcontent['publtime'] );
$publ_date = date( 'd/m/Y', $rowcontent['publtime'] );
list( $phour, $pmin ) = explode( '|', $tdate );
if( $rowcontent['exptime'] == 0 )
{
	$emin = $ehour = 0;
	$exp_date = '';
}
else
{
	$exp_date = date( 'd/m/Y', $rowcontent['exptime'] );
	$tdate = date( 'H|i', $rowcontent['exptime'] );
	list( $ehour, $emin ) = explode( '|', $tdate );
}

if( $rowcontent['status'] == 1 and $rowcontent['publtime'] > NV_CURRENTTIME )
{
	$array_cat_check_content = $array_cat_pub_content;
}
elseif( $rowcontent['status'] == 1 )
{
	$array_cat_check_content = $array_cat_edit_content;
}
else
{
	$array_cat_check_content = $array_cat_add_content;
}

if( empty( $array_cat_check_content ) )
{
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cat' );
	die();
}
$contents = '';

$lang_global['title_suggest_max'] = sprintf( $lang_global['length_suggest_max'], 65 );
$lang_global['description_suggest_max'] = sprintf( $lang_global['length_suggest_max'], 160 );

$xtpl = new XTemplate( 'content.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'rowcontent', $rowcontent );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );

$xtpl->assign( 'module_name', $module_name );

foreach( $global_array_cat as $catid_i => $array_value )
{
	if( defined( 'NV_IS_ADMIN_MODULE' ) )
	{
		$check_show = 1;
	}
	else
	{
		$array_cat = GetCatidInParent( $catid_i );
		$check_show = array_intersect( $array_cat, $array_cat_check_content );
	}
	if( ! empty( $check_show ) )
	{
		$space = intval( $array_value['lev'] ) * 30;
		$catiddisplay = ( sizeof( $array_catid_in_row ) > 1 and ( in_array( $catid_i, $array_catid_in_row ) ) ) ? '' : ' display: none;';
		$temp = array(
			'catid' => $catid_i,
			'space' => $space,
			'title' => $array_value['title'],
			'disabled' => ( ! in_array( $catid_i, $array_cat_check_content ) ) ? ' disabled="disabled"' : '',
			'checked' => ( in_array( $catid_i, $array_catid_in_row ) ) ? ' checked="checked"' : '',
			'catidchecked' => ( $catid_i == $rowcontent['catid'] ) ? ' checked="checked"' : '',
			'catiddisplay' => $catiddisplay );
		$xtpl->assign( 'CATS', $temp );
		$xtpl->parse( 'main.catid' );
	}
}

// Copyright
$checkcop = ( $rowcontent['copyright'] ) ? ' checked="checked"' : '';
$xtpl->assign( 'checkcop', $checkcop );

// topic
while( list( $topicid_i, $title_i ) = each( $array_topic_module ) )
{
	$sl = ( $topicid_i == $rowcontent['topicid'] ) ? ' selected="selected"' : '';
	$xtpl->assign( 'topicid', $topicid_i );
	$xtpl->assign( 'topic_title', $title_i );
	$xtpl->assign( 'sl', $sl );
	$xtpl->parse( 'main.rowstopic' );
}

// position images
while( list( $id_imgposition, $title_imgposition ) = each( $array_imgposition ) )
{
	$sl = ( $id_imgposition == $rowcontent['imgposition'] ) ? ' selected="selected"' : '';
	$xtpl->assign( 'id_imgposition', $id_imgposition );
	$xtpl->assign( 'title_imgposition', $title_imgposition );
	$xtpl->assign( 'posl', $sl );
	$xtpl->parse( 'main.looppos' );
}

// time update
$xtpl->assign( 'publ_date', $publ_date );
$select = '';
for( $i = 0; $i <= 23; ++$i )
{
	$select .= "<option value=\"" . $i . "\"" . ( ( $i == $phour ) ? ' selected="selected"' : '' ) . ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
}
$xtpl->assign( 'phour', $select );
$select = '';
for( $i = 0; $i < 60; ++$i )
{
	$select .= "<option value=\"" . $i . "\"" . ( ( $i == $pmin ) ? ' selected="selected"' : '' ) . ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
}
$xtpl->assign( 'pmin', $select );

// time exp
$xtpl->assign( 'exp_date', $exp_date );
$select = '';
for( $i = 0; $i <= 23; ++$i )
{
	$select .= "<option value=\"" . $i . "\"" . ( ( $i == $ehour ) ? ' selected="selected"' : '' ) . ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
}
$xtpl->assign( 'ehour', $select );
$select = '';
for( $i = 0; $i < 60; ++$i )
{
	$select .= "<option value=\"" . $i . "\"" . ( ( $i == $emin ) ? ' selected="selected"' : '' ) . ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
}
$xtpl->assign( 'emin', $select );

// allowed comm

$allowed_comm = explode( ',', $rowcontent['allowed_comm'] );
foreach( $groups_list as $_group_id => $_title )
{
	$xtpl->assign( 'ALLOWED_COMM', array(
		'value' => $_group_id,
		'checked' => in_array( $_group_id, $allowed_comm ) ? ' checked="checked"' : '',
		'title' => $_title
	) );
	$xtpl->parse( 'main.allowed_comm' );
}
if( $module_config[$module_name]['allowed_comm'] != '-1' )
{
	$xtpl->parse( 'main.content_note_comm' );
}

// source
$select = '';
while( list( $sourceid_i, $source_title_i ) = each( $array_source_module ) )
{
	$source_sl = ( $sourceid_i == $rowcontent['sourceid'] ) ? ' selected="selected"' : '';
	$select .= "<option value=\"" . $sourceid_i . "\" " . $source_sl . ">" . $source_title_i . "</option>\n";
}
$xtpl->assign( 'sourceid', $select );

if( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
{
	$edits = nv_aleditor( 'bodyhtml', '100%', '400px', $rowcontent['bodyhtml'], $uploads_dir_user, $currentpath );
}
else
{
	$edits = "<textarea style=\"width: 100%\" name=\"bodyhtml\" id=\"bodyhtml\" cols=\"20\" rows=\"15\">" . $rowcontent['bodyhtml'] . "</textarea>";
}

$shtm = '';
if( sizeof( $array_block_cat_module ) )
{
	foreach( $array_block_cat_module as $bid_i => $bid_title )
	{
		if( in_array( $bid_i, $id_block_content ) )
		{
			$xtpl->assign( 'BLOCKS', array( 'title' => $bid_title, 'bid' => $bid_i ) );
			$xtpl->parse( 'main.block_cat.default' );
		}
	}
	$xtpl->parse( 'main.block_cat' );
}
if( ! empty( $rowcontent['keywords'] ) )
{
	$keywords_array = explode( ',', $rowcontent['keywords'] );
	foreach( $keywords_array as $keywords )
	{
		$xtpl->assign( 'KEYWORDS', $keywords );
		$xtpl->parse( 'main.keywords' );
	}
}
$archive_checked = ( $rowcontent['archive'] ) ? ' checked="checked"' : '';
$xtpl->assign( 'archive_checked', $archive_checked );
$inhome_checked = ( $rowcontent['inhome'] ) ? ' checked="checked"' : '';
$xtpl->assign( 'inhome_checked', $inhome_checked );
$allowed_rating_checked = ( $rowcontent['allowed_rating'] ) ? ' checked="checked"' : '';
$xtpl->assign( 'allowed_rating_checked', $allowed_rating_checked );
$allowed_send_checked = ( $rowcontent['allowed_send'] ) ? ' checked="checked"' : '';
$xtpl->assign( 'allowed_send_checked', $allowed_send_checked );
$allowed_print_checked = ( $rowcontent['allowed_print'] ) ? ' checked="checked"' : '';
$xtpl->assign( 'allowed_print_checked', $allowed_print_checked );
$allowed_save_checked = ( $rowcontent['allowed_save'] ) ? ' checked="checked"' : '';
$xtpl->assign( 'allowed_save_checked', $allowed_save_checked );

$xtpl->assign( 'edit_bodytext', $edits );

if( ! empty( $error ) )
{
	$xtpl->assign( 'error', implode( '<br />', $error ) );
	$xtpl->parse( 'main.error' );
}

if( defined( 'NV_IS_ADMIN_MODULE' ) || ! empty( $array_pub_content ) ) //toan quyen module
{
	if( $rowcontent['status'] == 1 and $rowcontent['id'] > 0 )
	{
		$xtpl->parse( 'main.status' );
	}
	else
	{
		$xtpl->parse( 'main.status0' );
	}
}
else
{

	//gioi hoan quyen
	if( $rowcontent['status'] == 1 and $rowcontent['id'] > 0 )
	{
		$xtpl->parse( 'main.status' );
	}
	else
	{
		if( ! empty( $array_censor_content ) ) // neu co quyen duyet bai thi
		{
			$xtpl->parse( 'main.status1.status0' );
		}
		$xtpl->parse( 'main.status1' );
	}
}
if( empty( $rowcontent['alias'] ) )
{
	$xtpl->parse( 'main.getalias' );
}
$xtpl->assign( 'UPLOADS_DIR_USER', $uploads_dir_user );
$xtpl->assign( 'UPLOAD_CURRENT', $currentpath );

$sql = 'SELECT * FROM ' . $db_config['prefix'] . '_googleplus ORDER BY weight ASC';
$_array = $db->query( $sql )->fetchAll();
if( sizeof( $_array ) )
{
	$array_googleplus = array();
	$array_googleplus[] = array( 'gid' => -1, 'title' => $lang_module['googleplus_1'] );
	$array_googleplus[] = array( 'gid' => 0, 'title' => $lang_module['googleplus_0'] );
	foreach( $_array as $row )
	{
		$array_googleplus[] = $row;
	}
	foreach( $array_googleplus as $grow )
	{
		$grow['selected'] = ( $rowcontent['gid'] == $grow['gid'] ) ? ' selected="selected"' : '';
		$xtpl->assign( 'GOOGLEPLUS', $grow );
		$xtpl->parse( 'main.googleplus.gid' );
	}
	$xtpl->parse( 'main.googleplus' );
}
$xtpl->parse( 'main' );
$contents .= $xtpl->text( 'main' );

if( $rowcontent['id'] > 0 )
{
	$op = '';
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';

?>