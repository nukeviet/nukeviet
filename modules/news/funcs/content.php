<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12-11-2010 20:40
 */

if( ! defined( 'NV_IS_MOD_NEWS' ) ) die( 'Stop!!!' );

if( defined( 'NV_EDITOR' ) )
{
	require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}
elseif( ! nv_function_exists( 'nv_aleditor' ) and file_exists( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/ckeditor/ckeditor.js' ) )
{
	define( 'NV_EDITOR', true );
	define( 'NV_IS_CKEDITOR', true );
	$my_head .= '<script type="text/javascript" src="' . NV_BASE_SITEURL . NV_EDITORSDIR . '/ckeditor/ckeditor.js"></script>';

	function nv_aleditor( $textareaname, $width = '100%', $height = '450px', $val = '' )
	{
		$return = '<textarea style="width: ' . $width . '; height:' . $height . ';" id="' . $module_data . '_' . $textareaname . '" name="' . $textareaname . '">' . $val . '</textarea>';
		$return .= "<script type=\"text/javascript\">
		CKEDITOR.replace( '" . $module_data . "_" . $textareaname . "', {width: '" . $width . "',height: '" . $height . "',});
		</script>";
		return $return;
	}
}

$page_title = $lang_module['content'];
$key_words = $module_info['keywords'];

// check user post content
$array_post_config = array();
$sql = 'SELECT group_id, addcontent, postcontent, editcontent, delcontent FROM ' . NV_PREFIXLANG . '_' . $module_data . '_config_post';
$result = $db->query( $sql );
while( list( $group_id, $addcontent, $postcontent, $editcontent, $delcontent ) = $result->fetch( 3 ) )
{
	$array_post_config[$group_id] = array(
		'addcontent' => $addcontent,
		'postcontent' => $postcontent,
		'editcontent' => $editcontent,
		'delcontent' => $delcontent
	);
}

$array_post_user = array(
	'addcontent' => 0,
	'postcontent' => 0,
	'editcontent' => 0,
	'delcontent' => 0
);

if( defined( 'NV_IS_USER' ) and isset( $array_post_config[4] ) )
{
	if( $array_post_config[4]['addcontent'] )
	{
		$array_post_user['addcontent'] = 1;
	}

	if( $array_post_config[4]['postcontent'] )
	{
		$array_post_user['postcontent'] = 1;
	}

	if( $array_post_config[4]['editcontent'] )
	{
		$array_post_user['editcontent'] = 1;
	}

	if( $array_post_config[4]['delcontent'] )
	{
		$array_post_user['delcontent'] = 1;
	}

	foreach( $user_info['in_groups'] as $group_id_i )
	{
		if( $group_id_i > 0 and isset( $array_post_config[$group_id_i] ) )
		{
			if( $array_post_config[$group_id_i]['addcontent'] )
			{
				$array_post_user['addcontent'] = 1;
			}

			if( $array_post_config[$group_id_i]['postcontent'] )
			{
				$array_post_user['postcontent'] = 1;
			}

			if( $array_post_config[$group_id_i]['editcontent'] )
			{
				$array_post_user['editcontent'] = 1;
			}

			if( $array_post_config[$group_id_i]['delcontent'] )
			{
				$array_post_user['delcontent'] = 1;
			}
		}
	}
}

if( $array_post_user['postcontent'] )
{
	$array_post_user['addcontent'] = 1;
}
// check user post content

$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;

if( ! $array_post_user['addcontent'] )
{
	if( defined( 'NV_IS_USER' ) )
	{
		$array_temp['urlrefresh'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA;
	}
	else
	{
		$array_temp['urlrefresh'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=login&nv_redirect=' . nv_base64_encode( $client_info['selfurl'] );
	}

	$array_temp['content'] = $lang_module['error_addcontent'];
	$template = $module_info['template'];

	if( ! file_exists( NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file . '/content.tpl' ) )
	{
		$template = 'default';
	}

	$array_temp['urlrefresh'] = nv_url_rewrite( $array_temp['urlrefresh'], true );

	$xtpl = new XTemplate( 'content.tpl', NV_ROOTDIR . '/themes/' . $template . '/modules/' . $module_file );
	$xtpl->assign( 'DATA', $array_temp );
	$xtpl->parse( 'mainrefresh' );
	$contents = $xtpl->text( 'mainrefresh' );

	include NV_ROOTDIR . '/includes/header.php';
	echo nv_site_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
	exit();
}

if( $nv_Request->isset_request( 'get_alias', 'post' ) )
{
	$title = $nv_Request->get_title( 'get_alias', 'post', '' );
	$alias = change_alias( $title );

	include NV_ROOTDIR . '/includes/header.php';
	echo $alias;
	include NV_ROOTDIR . '/includes/footer.php';
}

$contentid = $nv_Request->get_int( 'contentid', 'get,post', 0 );
$fcheckss = $nv_Request->get_title( 'checkss', 'get,post', '' );
$checkss = md5( $contentid . $client_info['session_id'] . $global_config['sitekey'] );

if( $nv_Request->isset_request( 'contentid', 'get,post' ) and $fcheckss == $checkss )
{
	if( $contentid > 0 )
	{
		$rowcontent_old = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows where id=' . $contentid . ' and admin_id= ' . $user_info['userid'] )->fetch();
		$contentid = ( isset( $rowcontent_old['id'] ) ) ? intval( $rowcontent_old['id'] ) : 0;

		if( empty( $contentid ) )
		{
			Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op, true ) );
			die();
		}

		if( $nv_Request->get_int( 'delcontent', 'get' ) and ( empty( $rowcontent_old['status'] ) or $array_post_user['delcontent'] ) )
		{
			nv_del_content_module( $contentid );

			$user_content = defined( 'NV_IS_USER' ) ? ' | ' . $user_info['username'] : '';
			nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['del_content'], $contentid . ' | ' . $client_info['ip'] . $user_content, 0 );

			if( $rowcontent_old['status'] == 1 )
			{
				nv_del_moduleCache( $module_name );
			}

			Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op, true ) );
			die();
		}
		elseif( ! ( empty( $rowcontent_old['status'] ) or $array_post_user['editcontent'] ) )
		{
			Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op, true ) );
			die();
		}

		$page_title = $lang_module['update_content'];
	}
	else
	{
		$page_title = $lang_module['add_content'];
	}

	$array_mod_title[] = array(
		'catid' => 0,
		'title' => $lang_module['add_content'],
		'link' => $base_url
	);

	$array_imgposition = array(
		0 => $lang_module['imgposition_0'],
		1 => $lang_module['imgposition_1'],
		2 => $lang_module['imgposition_2']
	);

	$rowcontent = array(
		'id' => '',
		'listcatid' => '',
		'catid' => ( $contentid > 0 ) ? $rowcontent_old['catid'] : 0,
		'topicid' => '',
		'admin_id' => ( defined( 'NV_IS_USER' ) ) ? $user_info['userid'] : 0,
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
		'homeimgfile' => '',
		'homeimgalt' => '',
		'homeimgthumb' => '|',
		'imgposition' => 1,
		'bodyhtml' => '',
		'copyright' => 0,
		'inhome' => 1,
		'allowed_comm' => 4,
		'allowed_rating' => 1,
		'allowed_send' => 1,
		'allowed_print' => 1,
		'allowed_save' => 1,
		'hitstotal' => 0,
		'hitscm' => 0,
		'total_rating' => 0,
		'click_rating' => 0,
		'keywords' => ''
	);

	$array_catid_module = array();
	$sql = 'SELECT catid, title, lev FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat ORDER BY sort ASC';
	$result_cat = $db->query( $sql );

	while( list( $catid_i, $title_i, $lev_i ) = $result_cat->fetch( 3 ) )
	{
		$array_catid_module[] = array(
			'catid' => $catid_i,
			'title' => $title_i,
			'lev' => $lev_i
		);
	}

	$sql = 'SELECT topicid, title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_topics ORDER BY weight ASC';
	$result = $db->query( $sql );
	$array_topic_module = array();
	$array_topic_module[0] = $lang_module['topic_sl'];

	while( list( $topicid_i, $title_i ) = $result->fetch( 3 ) )
	{
		$array_topic_module[$topicid_i] = $title_i;
	}

	$error = '';

	if( $nv_Request->isset_request( 'contentid', 'post' ) )
	{
		$rowcontent['id'] = $contentid;
		$fcode = $nv_Request->get_title( 'fcode', 'post', '' );
		$catids = array_unique( $nv_Request->get_typed_array( 'catids', 'post', 'int', array() ) );

		$rowcontent['listcatid'] = implode( ',', $catids );
		$rowcontent['topicid'] = $nv_Request->get_int( 'topicid', 'post', 0 );
		$rowcontent['author'] = $nv_Request->get_title( 'author', 'post', '', 1 );

		$rowcontent['title'] = $nv_Request->get_title( 'title', 'post', '', 1 );
		$alias = $nv_Request->get_title( 'alias', 'post', '' );
		$rowcontent['alias'] = ( $alias == '' ) ? change_alias( $rowcontent['title'] ) : change_alias( $alias );

		$rowcontent['hometext'] = $nv_Request->get_title( 'hometext', 'post', '' );

		$rowcontent['homeimgfile'] = $nv_Request->get_title( 'homeimgfile', 'post', '' );
		$rowcontent['homeimgalt'] = $nv_Request->get_title( 'homeimgalt', 'post', '', 1 );
		$rowcontent['imgposition'] = $nv_Request->get_int( 'imgposition', 'post', 0 );
		$rowcontent['sourcetext'] = $nv_Request->get_title( 'sourcetext', 'post', '' );

		// Xu ly anh minh hoa
		$rowcontent['homeimgthumb'] = 0;
		if( ! nv_is_url( $rowcontent['homeimgfile'] ) and file_exists( NV_DOCUMENT_ROOT . $rowcontent['homeimgfile'] ) )
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

		if( ! array_key_exists( $rowcontent['imgposition'], $array_imgposition ) )
		{
			$rowcontent['imgposition'] = 1;
		}
		if( ! array_key_exists( $rowcontent['topicid'], $array_topic_module ) )
		{
			$rowcontent['topicid'] = 0;
		}

		$bodyhtml = $nv_Request->get_string( 'bodyhtml', 'post', '' );
		$rowcontent['bodyhtml'] = defined( 'NV_EDITOR' ) ? nv_nl2br( $bodyhtml, '' ) : nv_nl2br( nv_htmlspecialchars( strip_tags( $bodyhtml ) ), '<br />' );

		$rowcontent['keywords'] = $nv_Request->get_title( 'keywords', 'post', '', 1 );

		if( empty( $rowcontent['title'] ) )
		{
			$error = $lang_module['error_title'];
		}
		elseif( empty( $rowcontent['listcatid'] ) )
		{
			$error = $lang_module['error_cat'];
		}
		elseif( trim( strip_tags( $rowcontent['bodyhtml'] ) ) == '' )
		{
			$error = $lang_module['error_bodytext'];
		}
		elseif( ! nv_capcha_txt( $fcode ) )
		{
			$error = $lang_module['error_captcha'];
		}
		else
		{
			if( ( $array_post_user['postcontent'] ) && $nv_Request->isset_request( 'status1', 'post' ) ) $rowcontent['status'] = 1;
			elseif( $nv_Request->isset_request( 'status0', 'post' ) ) $rowcontent['status'] = 0;
			elseif( $nv_Request->isset_request( 'status4', 'post' ) ) $rowcontent['status'] = 4;
			$rowcontent['catid'] = in_array( $rowcontent['catid'], $catids ) ? $rowcontent['catid'] : $catids[0];
			$rowcontent['bodytext'] = nv_news_get_bodytext( $rowcontent['bodyhtml'] );

			$rowcontent['sourceid'] = 0;
			if( ! empty( $rowcontent['sourcetext'] ) )
			{
				$url_info = @parse_url( $rowcontent['sourcetext'] );

				if( isset( $url_info['scheme'] ) and isset( $url_info['host'] ) )
				{
					$sourceid_link = $url_info['scheme'] . '://' . $url_info['host'];
					$rowcontent['sourceid'] = $db->query( 'SELECT sourceid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources WHERE link=' . $db->quote( $sourceid_link ) )->fetchColumn();

					if( empty( $rowcontent['sourceid'] ) )
					{
						$weight = $db->query( 'SELECT max(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources' )->fetchColumn();
						$weight = intval( $weight ) + 1;
						$_sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_sources (title, link, logo, weight, add_time, edit_time) VALUES (" . $db->quote( $url_info['host'] ) . ", " . $db->quote( $sourceid_link ) . ", '', " . $db->quote( $weight ) . ", " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ")";
						$rowcontent['sourceid'] = $db->insert_id( $_sql, 'sourceid' );
					}
				}
			}
			if( $rowcontent['id'] == 0 )
			{
				$_sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_rows
						(catid, listcatid, topicid, admin_id, author, sourceid, addtime, edittime, status, publtime, exptime, archive, title, alias, hometext, homeimgfile, homeimgalt, homeimgthumb, inhome, allowed_comm, allowed_rating, hitstotal, hitscm, total_rating, click_rating) VALUES
						 (" . intval( $rowcontent['catid'] ) . ",
						 " . $db->quote( $rowcontent['listcatid'] ) . ",
						 " . intval( $rowcontent['topicid'] ) . ",
						 " . intval( $rowcontent['admin_id'] ) . ",
						 " . $db->quote( $rowcontent['author'] ) . ",
						 " . intval( $rowcontent['sourceid'] ) . ",
						 " . intval( $rowcontent['addtime'] ) . ",
						 " . intval( $rowcontent['edittime'] ) . ",
						 " . intval( $rowcontent['status'] ) . ",
						 " . intval( $rowcontent['publtime'] ) . ",
						 " . intval( $rowcontent['exptime'] ) . ",
						 " . intval( $rowcontent['archive'] ) . ",
						 " . $db->quote( $rowcontent['title'] ) . ",
						 " . $db->quote( $rowcontent['alias'] ) . ",
						 " . $db->quote( $rowcontent['hometext'] ) . ",
						 " . $db->quote( $rowcontent['homeimgfile'] ) . ",
						 " . $db->quote( $rowcontent['homeimgalt'] ) . ",
						 " . $db->quote( $rowcontent['homeimgthumb'] ) . ",
						 " . intval( $rowcontent['inhome'] ) . ",
						 " . intval( $rowcontent['allowed_comm'] ) . ",
						 " . intval( $rowcontent['allowed_rating'] ) . ",
						 " . intval( $rowcontent['hitstotal'] ) . ",
						 " . intval( $rowcontent['hitscm'] ) . ",
						 " . intval( $rowcontent['total_rating'] ) . ",
						 " . intval( $rowcontent['click_rating'] ) . ")";

				$rowcontent['id'] = $db->insert_id( $_sql, 'id' );
				if( $rowcontent['id'] > 0 )
				{
					foreach( $catids as $catid )
					{
						$db->query( "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_" . $catid . " SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE id=" . $rowcontent['id'] );
					}

					$tbhtml = NV_PREFIXLANG . "_" . $module_data . "_bodyhtml_" . ceil( $rowcontent['id'] / 2000 );
					$db->query( "CREATE TABLE IF NOT EXISTS " . $tbhtml . " (id int(11) unsigned NOT NULL, bodyhtml longtext NOT NULL, sourcetext varchar(255) NOT NULL default '', imgposition tinyint(1) NOT NULL default '1', copyright tinyint(1) NOT NULL default '0', allowed_send tinyint(1) NOT NULL default '0', allowed_print tinyint(1) NOT NULL default '0', allowed_save tinyint(1) NOT NULL default '0', PRIMARY KEY (id)) ENGINE=MyISAM" );
					$db->query( "INSERT INTO " . $tbhtml . " (id, bodyhtml, sourcetext, imgposition, copyright, allowed_send, allowed_print, allowed_save, gid) VALUES (
							" . $rowcontent['id'] . ",
							" . $db->quote( $rowcontent['bodyhtml'] ) . ",
							" . $db->quote( $rowcontent['sourcetext'] ) . ",
							" . intval( $rowcontent['imgposition'] ) . ",
			 				" . intval( $rowcontent['copyright'] ) . ",
			 				" . intval( $rowcontent['allowed_send'] ) . ",
			 				" . intval( $rowcontent['allowed_print'] ) . ",
			 				" . intval( $rowcontent['allowed_save'] ) . ", 0
						)" );

					$db->query( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_bodytext VALUES (' . $rowcontent['id'] . ', ' . $db->quote( $rowcontent['bodytext'] ) . ')' );
					$user_content = defined( 'NV_IS_USER' ) ? ' | ' . $user_info['username'] : '';

					nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['add_content'], $rowcontent['title'] . ' | ' . $client_info['ip'] . $user_content, 0 );
				}
				else
				{
					$error = $lang_module['errorsave'];
				}
			}
			else
			{
				if( $rowcontent_old['status'] == 1 )
				{
					$rowcontent['status'] = 1;
				}

				$_sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_rows SET
						 catid=" . intval( $rowcontent['catid'] ) . ",
						 listcatid=" . $db->quote( $rowcontent['listcatid'] ) . ",
						 topicid=" . intval( $rowcontent['topicid'] ) . ",
						 author=" . $db->quote( $rowcontent['author'] ) . ",
						 sourceid=" . intval( $rowcontent['sourceid'] ) . ",
						 status=" . intval( $rowcontent['status'] ) . ",
						 publtime=" . intval( $rowcontent['publtime'] ) . ",
						 exptime=" . intval( $rowcontent['exptime'] ) . ",
						 archive=" . intval( $rowcontent['archive'] ) . ",
						 title=" . $db->quote( $rowcontent['title'] ) . ",
						 alias=" . $db->quote( $rowcontent['alias'] ) . ",
						 hometext=" . $db->quote( $rowcontent['hometext'] ) . ",
						 homeimgfile=" . $db->quote( $rowcontent['homeimgfile'] ) . ",
						 homeimgalt=" . $db->quote( $rowcontent['homeimgalt'] ) . ",
						 homeimgthumb=" . $db->quote( $rowcontent['homeimgthumb'] ) . ",
						 inhome=" . intval( $rowcontent['inhome'] ) . ",
						 allowed_comm=" . intval( $rowcontent['allowed_comm'] ) . ",
						 allowed_rating=" . intval( $rowcontent['allowed_rating'] ) . ",
						 edittime=" . NV_CURRENTTIME . "
						WHERE id =" . $rowcontent['id'];

				if( $db->exec( $_sql ) )
				{
					$array_cat_old = explode( ',', $rowcontent_old['listcatid'] );

					foreach( $array_cat_old as $catid )
					{
						$db->query( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' WHERE id = ' . $rowcontent['id'] );
					}

					$array_cat_new = explode( ',', $rowcontent['listcatid'] );

					foreach( $array_cat_new as $catid )
					{
						$db->query( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . $rowcontent['id'] );
					}

					$db->query( "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_bodyhtml_" . ceil( $rowcontent['id'] / 2000 ) . " SET
							bodyhtml=" . $db->quote( $rowcontent['bodyhtml'] ) . ",
							imgposition=" . intval( $rowcontent['imgposition'] ) . ",
							 sourcetext=" . $db->quote( $rowcontent['sourcetext'] ) . ",
							 copyright=" . intval( $rowcontent['copyright'] ) . ",
							 allowed_send=" . intval( $rowcontent['allowed_send'] ) . ",
							 allowed_print=" . intval( $rowcontent['allowed_print'] ) . ",
							 allowed_save=" . intval( $rowcontent['allowed_save'] ) . "
							WHERE id =" . $rowcontent['id'] );

					$db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_bodytext SET bodytext=' . $db->quote( $rowcontent['bodytext'] ) . ' WHERE id =' . $rowcontent['id'] );

					$user_content = defined( 'NV_IS_USER' ) ? ' | ' . $user_info['username'] : '';

					nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['update_content'], $rowcontent['title'] . ' | ' . $client_info['ip'] . $user_content, 0 );
				}
				else
				{
					$error = $lang_module['errorsave'];
				}
			}

			if( empty( $error ) )
			{
				$array_temp = array();

				if( defined( 'NV_IS_USER' ) )
				{
					$array_temp['urlrefresh'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;

					if( $rowcontent['status'] )
					{
						$array_temp['content'] = $lang_module['save_content_ok'];
						nv_del_moduleCache( $module_name );
					}
					else
					{
						$array_temp['content'] = $lang_module['save_content_waite'];
					}
				}
				elseif( $rowcontent['status'] == 1 and sizeof( $catids ) )
				{
					$catid = $catids[0];
					$array_temp['urlrefresh'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $global_array_cat[$catid]['alias'] . '/' . $rowcontent['alias'] . '-' . $rowcontent['id'];
					$array_temp['content'] = $lang_module['save_content_view_page'];
					nv_del_moduleCache( $module_name );
				}
				else
				{
					$array_temp['urlrefresh'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA;
					$array_temp['content'] = $lang_module['save_content_waite_home'];
				}

				$template = $module_info['template'];

				if( ! file_exists( NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file . '/content.tpl' ) )
				{
					$template = 'default';
				}

				$array_temp['urlrefresh'] = nv_url_rewrite( $array_temp['urlrefresh'], true );

				$xtpl = new XTemplate( 'content.tpl', NV_ROOTDIR . '/themes/' . $template . '/modules/' . $module_file );
				$xtpl->assign( 'DATA', $array_temp );
				$xtpl->parse( 'mainrefresh' );
				$contents = $xtpl->text( 'mainrefresh' );

				include NV_ROOTDIR . '/includes/header.php';
				echo nv_site_theme( $contents );
				include NV_ROOTDIR . '/includes/footer.php';
				exit();
			}
		}
	}
	elseif( $contentid > 0 )
	{
		$rowcontent = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows where id=' . $contentid )->fetch();

		if( empty( $rowcontent['id'] ) )
		{
			Header( 'Location: ' . nv_url_rewrite( NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
			die();
		}

		$body_contents = $db->query( "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_bodyhtml_" . ceil( $rowcontent['id'] / 2000 ) . " where id=" . $rowcontent['id'] )->fetch();
		$rowcontent = array_merge( $rowcontent, $body_contents );
		unset( $body_contents );
	}

	if( ! empty( $rowcontent['homeimgfile'] ) and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $rowcontent['homeimgfile'] ) )
	{
		$rowcontent['homeimgfile'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $rowcontent['homeimgfile'];
	}

	$rowcontent['bodyhtml'] = htmlspecialchars( nv_editor_br2nl( $rowcontent['bodyhtml'] ) );
	if( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
	{
		$htmlbodyhtml = nv_aleditor( 'bodyhtml', '100%', '300px', $rowcontent['bodyhtml'] );
	}
	else
	{
		$htmlbodyhtml .= "<textarea class=\"textareaform\" name=\"bodyhtml\" id=\"bodyhtml\" cols=\"60\" rows=\"15\">" . $rowcontent['bodyhtml'] . "</textarea>";
	}

	if( ! empty( $error ) )
	{
		$my_head .= "<script type=\"text/javascript\">\n";
		$my_head .= "	alert('" . $error . "')\n";
		$my_head .= "</script>\n";
	}

	$template = $module_info['template'];

	if( ! file_exists( NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file . '/content.tpl' ) )
	{
		$template = 'default';
	}

	$xtpl = new XTemplate( 'content.tpl', NV_ROOTDIR . '/themes/' . $template . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'DATA', $rowcontent );
	$xtpl->assign( 'HTMLBODYTEXT', $htmlbodyhtml );

	$xtpl->assign( 'GFX_WIDTH', NV_GFX_WIDTH );
	$xtpl->assign( 'GFX_HEIGHT', NV_GFX_HEIGHT );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'CAPTCHA_REFRESH', $lang_global['captcharefresh'] );
	$xtpl->assign( 'CAPTCHA_REFR_SRC', NV_BASE_SITEURL . 'images/refresh.png' );
	$xtpl->assign( 'NV_GFX_NUM', NV_GFX_NUM );
	$xtpl->assign( 'CHECKSS', $checkss );

	$xtpl->assign( 'CONTENT_URL', $base_url . '&contentid=' . $rowcontent['id'] . '&checkss=' . $checkss );
	$array_catid_in_row = explode( ',', $rowcontent['listcatid'] );

	foreach( $array_catid_module as $value )
	{
		$xtitle_i = '';

		if( $value['lev'] > 0 )
		{
			for( $i = 1; $i <= $value['lev']; ++$i )
			{
				$xtitle_i .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			}
		}

		$array_temp = array();
		$array_temp['value'] = $value['catid'];
		$array_temp['title'] = $xtitle_i . $value['title'];
		$array_temp['checked'] = ( in_array( $value['catid'], $array_catid_in_row ) ) ? ' checked="checked"' : '';

		$xtpl->assign( 'DATACATID', $array_temp );
		$xtpl->parse( 'main.catid' );
	}

	while( list( $topicid_i, $title_i ) = each( $array_topic_module ) )
	{
		$array_temp = array();
		$array_temp['value'] = $topicid_i;
		$array_temp['title'] = $title_i;
		$array_temp['selected'] = ( $topicid_i == $rowcontent['topicid'] ) ? ' selected="selecte"' : '';
		$xtpl->assign( 'DATATOPIC', $array_temp );
		$xtpl->parse( 'main.topic' );
	}

	while( list( $id_imgposition, $title_imgposition ) = each( $array_imgposition ) )
	{
		$array_temp = array();
		$array_temp['value'] = $id_imgposition;
		$array_temp['title'] = $title_imgposition;
		$array_temp['selected'] = ( $id_imgposition == $rowcontent['imgposition'] ) ? ' selected="selecte"' : '';

		$xtpl->assign( 'DATAIMGOP', $array_temp );
		$xtpl->parse( 'main.imgposition' );
	}

	if( ! ( $rowcontent['status'] and $rowcontent['id'] ) )
	{
		$xtpl->parse( 'main.save_temp' );
	}

	if( $array_post_user['postcontent'] or ( $rowcontent['status'] and $rowcontent['id'] and $array_post_user['editcontent'] ) )
	{
		$xtpl->parse( 'main.postcontent' );
	}

	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );

	if( empty( $rowcontent['alias'] ) )
	{
		$contents .= "<script type=\"text/javascript\">\n";
		$contents .= '$("#idtitle").change(function () {
 		get_alias();
		});';
		$contents .= "</script>\n";
	}
}
elseif( defined( 'NV_IS_USER' ) )
{
	$page = 1;

	if( isset( $array_op[1] ) and substr( $array_op[1], 0, 5 ) == 'page-' )
	{
		$page = intval( substr( $array_op[1], 5 ) );
	}

	$array_catpage = array();

	$db->sqlreset()
		->select( 'COUNT(*)' )
		->from( NV_PREFIXLANG . "_" . $module_data . "_rows" )
		->where( "admin_id= " . $user_info['userid'] );

	$num_items = $db->query( $db->sql() )->fetchColumn();

	$db->select( 'id, catid, listcatid, topicid, admin_id, author, sourceid, addtime, edittime, status, publtime, title, alias, hometext, homeimgfile, homeimgalt, homeimgthumb, allowed_rating, hitstotal, hitscm, total_rating, click_rating' )
		->order( 'id DESC' )
		->limit( $per_page )
		->offset( ( $page - 1 ) * $per_page );

	$result = $db->query( $db->sql() );
	while( $item = $result->fetch() )
	{
		if( $item['homeimgthumb'] == 1 ) // image thumb
		{
			$item['imghome'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_name . '/' . $item['homeimgfile'];
		}
		elseif( $item['homeimgthumb'] == 2 ) // image file
		{
			$item['imghome'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $item['homeimgfile'];
		}
		elseif( $item['homeimgthumb'] == 3 ) // image url
		{
			$item['imghome'] = $item['homeimgfile'];
		}
		else // no image
		{
			$item['imghome'] = NV_BASE_SITEURL . 'themes/' . $global_config['site_theme'] . '/images/no_image.gif';
		}

		$item['is_edit_content'] = ( empty( $item['status'] ) or $array_post_user['editcontent'] ) ? 1 : 0;
		$item['is_del_content'] = ( empty( $item['status'] ) or $array_post_user['delcontent'] ) ? 1 : 0;

		$catid = $item['catid'];
		$item['link'] = $global_array_cat[$catid]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
		$array_catpage[] = $item;
	}

	// parse content
	$xtpl = new XTemplate( 'viewcat_page.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'IMGWIDTH1', $module_config[$module_name]['homewidth'] );

	$a = 0;
	foreach( $array_catpage as $array_row_i )
	{
		$array_row_i['publtime'] = nv_date( 'd/m/Y h:i:s A', $array_row_i['publtime'] );
		$xtpl->assign( 'CONTENT', $array_row_i );
		$id = $array_row_i['id'];
		$array_link_content = array();

		if( $array_row_i['is_edit_content'] )
		{
			$array_link_content[] = "<em class=\"fa fa-edit fa-lg\">&nbsp;</em> <a href=\"" . $base_url . "&amp;contentid=" . $id . "&amp;checkss=" . md5( $id . $client_info['session_id'] . $global_config['sitekey'] ) . "\">" . $lang_global['edit'] . "</a>";
		}

		if( $array_row_i['is_del_content'] )
		{
			$array_link_content[] = "<em class=\"fa fa-trash-o fa-lg\">&nbsp;</em> <a onclick=\"return confirm(nv_is_del_confirm[0]);\" href=\"" . $base_url . "&amp;contentid=" . $id . "&amp;delcontent=1&amp;checkss=" . md5( $id . $client_info['session_id'] . $global_config['sitekey'] ) . "\">" . $lang_global['delete'] . "</a>";
		}

		if( ! empty( $array_link_content ) )
		{
			$xtpl->assign( 'ADMINLINK', implode( '&nbsp;-&nbsp;', $array_link_content ) );
			$xtpl->parse( 'main.viewcatloop.adminlink' );
		}

		if( $array_row_i['imghome'] != '' )
		{
			$xtpl->assign( 'HOMEIMG1', $array_row_i['imghome'] );
			$xtpl->assign( 'HOMEIMGALT1', ! empty( $array_row_i['homeimgalt'] ) ? $array_row_i['homeimgalt'] : $array_row_i['title'] );
			$xtpl->parse( 'main.viewcatloop.image' );
		}

		// parse list catid
		$n = 1;
		$array_catid = explode( ',', $array_row_i['listcatid'] );
		$num_cat = sizeof( $array_catid );

		foreach( $array_catid as $catid_i )
		{
			if( isset( $global_array_cat[$catid_i] ) )
			{
				$listcat = array( 'title' => $global_array_cat[$catid_i]['title'], 'link' => $global_array_cat[$catid_i]['link'] );
				$xtpl->assign( 'CAT', $listcat );
				if( $n < $num_cat )
				{
					$xtpl->parse( 'main.viewcatloop.cat.comma' );
				}
				$xtpl->parse( 'main.viewcatloop.cat' );
			}
			++$n;
		}

		$xtpl->parse( 'main.viewcatloop' );
		++$a;
	}

	$contents .= "<div style=\"border: 1px solid #ccc;margin: 10px; font-size: 15px; font-weight: bold; text-align: center;\"><a href=\"" . $base_url . "&amp;contentid=0&checkss=" . md5( "0" . $client_info['session_id'] . $global_config['sitekey'] ) . "\">" . $lang_module['add_content'] . "</a></h1></div>";

	$generate_page = nv_alias_page( $page_title, $base_url, $num_items, $per_page, $page );

	if( ! empty( $generate_page ) )
	{
		$xtpl->assign( 'GENERATE_PAGE', $generate_page );
		$xtpl->parse( 'main.generate_page' );
	}

	$xtpl->parse( 'main' );
	$contents .= $xtpl->text( 'main' );

	if( $page > 1 )
	{
		$page_title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['page'] . ' ' . $page;
	}
}
elseif( $array_post_user['addcontent'] )
{
	Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&contentid=0&checkss=' . md5( '0' . $client_info['session_id'] . $global_config['sitekey'] ), true ) );
	die();
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';