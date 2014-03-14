<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

// Edit file
if( $nv_Request->isset_request( 'edit', 'get' ) )
{
	$report = $nv_Request->isset_request( 'report', 'get' );

	$id = $nv_Request->get_int( 'id', 'get', 0 );

	$query = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $id;
	$row = $db->query( $query )->fetch();
	if( empty( $row ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
		exit();
	}

	define( 'IS_EDIT', true );
	$page_title = $lang_module['download_editfile'];

	$groups_list = nv_groups_list();
	$array_who = array( $lang_global['who_view0'], $lang_global['who_view1'], $lang_global['who_view2'] );
	if( ! empty( $groups_list ) )
	{
		$array_who[] = $lang_global['who_view3'];
	}

	$array = array();
	$is_error = false;
	$error = '';

	if( $nv_Request->isset_request( 'submit', 'post' ) )
	{
		$array['catid'] = $nv_Request->get_int( 'catid', 'post', 0 );
		$array['title'] = $nv_Request->get_title( 'title', 'post', '', 1 );
		$array['description'] = $nv_Request->get_editor( 'description', '', NV_ALLOWED_HTML_TAGS );
		$array['introtext'] = $nv_Request->get_textarea( 'introtext', '', NV_ALLOWED_HTML_TAGS );
		$array['author_name'] = $nv_Request->get_title( 'author_name', 'post', '', 1 );
		$array['author_email'] = $nv_Request->get_title( 'author_email', 'post', '' );
		$array['author_url'] = $nv_Request->get_title( 'author_url', 'post', '' );
		$array['fileupload'] = $nv_Request->get_typed_array( 'fileupload', 'post', 'string' );
		$array['linkdirect'] = $nv_Request->get_typed_array( 'linkdirect', 'post', 'string' );
		$array['version'] = $nv_Request->get_title( 'version', 'post', '', 1 );
		$array['fileimage'] = $nv_Request->get_title( 'fileimage', 'post', '' );
		$array['copyright'] = $nv_Request->get_title( 'copyright', 'post', '', 1 );
		$array['comment_allow'] = $nv_Request->get_int( 'comment_allow', 'post', 0 );
		$array['who_comment'] = $nv_Request->get_int( 'who_comment', 'post', 0 );
		$array['groups_comment'] = $nv_Request->get_typed_array( 'groups_comment', 'post', 'int' );
		$array['is_del_report'] = $nv_Request->get_int( 'is_del_report', 'post', 0 );

		$array['who_view'] = $nv_Request->get_int( 'who_view', 'post', 0 );
		$array['groups_view'] = $nv_Request->get_typed_array( 'groups_view', 'post', 'int' );
		$array['who_download'] = $nv_Request->get_int( 'who_download', 'post', 0 );
		$array['groups_download'] = $nv_Request->get_typed_array( 'groups_download', 'post', 'int' );

		if( ! empty( $array['author_url'] ) )
		{
			if( ! preg_match( '#^(http|https|ftp|gopher)\:\/\/#', $array['author_url'] ) )
			{
				$array['author_url'] = 'http://' . $array['author_url'];
			}
		}

		$array['filesize'] = 0;
		if( ! empty( $array['fileupload'] ) )
		{
			$fileupload = array_unique( $array['fileupload'] );
			$array['fileupload'] = array();
			foreach( $fileupload as $file )
			{
				if( ! empty( $file ) )
				{
					$file2 = substr( $file, strlen( NV_BASE_SITEURL ) );
					if( file_exists( NV_ROOTDIR . '/' . $file2 ) and ( $filesize = filesize( NV_ROOTDIR . '/' . $file2 ) ) != 0 )
					{
						$array['fileupload'][] = substr( $file, strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR ) );
						$array['filesize'] += $filesize;
					}
				}
			}
		}
		else
		{
			$array['fileupload'] = array();
		}

		// Sort image
		if( ! empty( $array['fileimage'] ) )
		{
			if( ! preg_match( '#^(http|https|ftp|gopher)\:\/\/#', $array['fileimage'] ) )
			{
				$array['fileimage'] = substr( $array['fileimage'], strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR ) );
			}
		}

		if( ! empty( $array['linkdirect'] ) )
		{
			$linkdirect = $array['linkdirect'];
			$array['linkdirect'] = array();
			foreach( $linkdirect as $links )
			{
				$linkdirect2 = array();
				if( ! empty( $links ) )
				{
					$links = nv_nl2br( $links, '<br />' );
					$links = explode( '<br />', $links );
					$links = array_map( 'trim', $links );
					$links = array_unique( $links );

					foreach( $links as $link )
					{
						if( ! preg_match( '#^(http|https|ftp|gopher)\:\/\/#', $link ) )
						{
							$link = 'http://' . $link;
						}
						if( nv_is_url( $link ) )
						{
							$linkdirect2[] = $link;
						}
					}
				}

				if( ! empty( $linkdirect2 ) )
				{
					$array['linkdirect'][] = implode( "\n", $linkdirect2 );
				}
			}
		}
		else
		{
			$array['linkdirect'] = array();
		}
		if( ! empty( $array['linkdirect'] ) )
		{
			$array['linkdirect'] = array_unique( $array['linkdirect'] );
		}

		if( ! empty( $array['linkdirect'] ) and empty( $array['fileupload'] ) )
		{
			$array['filesize'] = $nv_Request->get_float( 'filesize', 'post', 0 );
            $array['filesize'] = intval( $array['filesize'] * 1048576 );
		}

		$alias = change_alias( $array['title'] );

		$stmt = $db->prepare( 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id!=' . $id . ' AND alias= :alias ');
		$stmt->bindParam( ':alias', $alias, PDO::PARAM_STR );
		$stmt->execute();
		$is_exists = $stmt->fetchColumn();

		if( ! $is_exists )
		{
			$stmt = $db->prepare( 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tmp WHERE title= :title');
			$stmt->bindParam( ':title', $array['title'], PDO::PARAM_STR );
			$stmt->execute();
			$is_exists = $stmt->fetchColumn();
		}

		if( empty( $array['title'] ) )
		{
			$is_error = true;
			$error = $lang_module['file_error_title'];
		}
		elseif( $is_exists )
		{
			$is_error = true;
			$error = $lang_module['file_title_exists'];
		}
		elseif( ! empty( $array['author_email'] ) and ( $check_valid_email = nv_check_valid_email( $array['author_email'] ) ) != '' )
		{
			$is_error = true;
			$error = $check_valid_email;
		}
		elseif( ! empty( $array['author_url'] ) and ! nv_is_url( $array['author_url'] ) )
		{
			$is_error = true;
			$error = $lang_module['file_error_author_url'];
		}
		elseif( empty( $array['fileupload'] ) and empty( $array['linkdirect'] ) )
		{
			$is_error = true;
			$error = $lang_module['file_error_fileupload'];
		}
		else
		{
			$array['introtext'] = ! empty( $array['introtext'] ) ? nv_nl2br( $array['introtext'], '<br />' ) : '';
			$array['description'] = ! empty( $array['description'] ) ? nv_editor_nl2br( $array['description'] ) : $array['introtext'];
			$array['fileupload'] = ( ! empty( $array['fileupload'] ) ) ? implode( '[NV]', $array['fileupload'] ) : '';
			if( ( ! empty( $array['linkdirect'] ) ) )
			{
				$array['linkdirect'] = array_map( 'nv_nl2br', $array['linkdirect'] );
				$array['linkdirect'] = implode( '[NV]', $array['linkdirect'] );
			}
			else
			{
				$array['linkdirect'] = '';
			}

			if( ! in_array( $array['who_comment'], array_keys( $array_who ) ) )
			{
				$array['who_comment'] = 0;
			}
			if( ! in_array( $array['who_view'], array_keys( $array_who ) ) )
			{
				$array['who_view'] = 0;
			}
			if( ! in_array( $array['who_download'], array_keys( $array_who ) ) )
			{
				$array['who_download'] = 0;
			}

			$array['groups_comment'] = ( ! empty( $array['groups_comment'] ) ) ? implode( ',', $array['groups_comment'] ) : '';
			$array['groups_view'] = ( ! empty( $array['groups_view'] ) ) ? implode( ',', $array['groups_view'] ) : '';
			$array['groups_download'] = ( ! empty( $array['groups_download'] ) ) ? implode( ',', $array['groups_download'] ) : '';

			$stmt = $db->prepare( "UPDATE " . NV_PREFIXLANG . "_" . $module_data . " SET
				 catid=" . $array['catid'] . ",
				 title= :title,
				 alias= :alias,
				 description= :description,
				 introtext= :introtext,
				 updatetime=" . NV_CURRENTTIME . ",
				 author_name= :author_name,
				 author_email= :author_email,
				 author_url= :author_url,
				 fileupload= :fileupload,
				 linkdirect= :linkdirect,
				 version= :version,
				 filesize=" . $array['filesize'] . ",
				 fileimage= :fileimage,
				 copyright= :copyright,
				 comment_allow=" . $array['comment_allow'] . ",
				 who_comment=" . $array['who_comment'] . ",
				 groups_comment= :groups_comment,
				 who_view=" . $array['who_view'] . ",
				 groups_view= :groups_view,
				 who_download=" . $array['who_download'] . ",
				 groups_download= :groups_download
				 WHERE id=" . $id );

			$stmt->bindParam( ':title', $array['title'], PDO::PARAM_STR );
			$stmt->bindParam( ':alias', $alias, PDO::PARAM_STR );
			$stmt->bindParam( ':description', $array['description'], PDO::PARAM_STR, strlen( $array['description'] ) );
			$stmt->bindParam( ':introtext', $array['introtext'], PDO::PARAM_STR, strlen( $array['introtext'] ) );
			$stmt->bindParam( ':author_name', $array['author_name'], PDO::PARAM_STR );
			$stmt->bindParam( ':author_email', $array['author_email'], PDO::PARAM_STR );
			$stmt->bindParam( ':author_url', $array['author_url'], PDO::PARAM_STR );
			$stmt->bindParam( ':fileupload', $array['fileupload'], PDO::PARAM_STR, strlen( $array['fileupload'] ) );
			$stmt->bindParam( ':linkdirect', $array['linkdirect'], PDO::PARAM_STR, strlen( $array['linkdirect'] ) );
			$stmt->bindParam( ':version', $array['version'], PDO::PARAM_STR );
			$stmt->bindParam( ':fileimage', $array['fileimage'], PDO::PARAM_STR );
			$stmt->bindParam( ':copyright', $array['copyright'], PDO::PARAM_STR );
			$stmt->bindParam( ':groups_comment', $array['groups_comment'], PDO::PARAM_STR );
			$stmt->bindParam( ':groups_view', $array['groups_view'], PDO::PARAM_STR );
			$stmt->bindParam( ':groups_download', $array['groups_download'], PDO::PARAM_STR );

			if( ! $stmt->execute() )
			{
				$is_error = true;
				$error = $lang_module['file_error1'];
			}
			else
			{
				if( $report and $array['is_del_report'] )
				{
					$db->query( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_report WHERE fid=' . $id );
				}

                nv_del_moduleCache( $module_name );
				nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['download_editfile'], $array['title'], $admin_info['userid'] );
				Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
				exit();
			}
		}

		$array['fileupload'] = ( ! empty( $array['fileupload'] ) ) ? explode( '[NV]', $array['fileupload'] ) : array();
	}
	else
	{
		$array['catid'] = ( int )$row['catid'];
		$array['title'] = $row['title'];
		$array['description'] = nv_editor_br2nl( $row['description'] );
		$array['introtext'] = nv_br2nl( $row['introtext'] );
		$array['author_name'] = $row['author_name'];
		$array['author_email'] = $row['author_email'];
		$array['author_url'] = $row['author_url'];
		$array['fileupload'] = $row['fileupload'];
		$array['linkdirect'] = $row['linkdirect'];
		$array['version'] = $row['version'];
		$array['filesize'] = ( int )$row['filesize'];
		$array['fileimage'] = $row['fileimage'];
		$array['copyright'] = $row['copyright'];
		$array['comment_allow'] = ( int )$row['comment_allow'];
		$array['who_comment'] = ( int )$row['who_comment'];
		$array['groups_comment'] = $row['groups_comment'];

		$array['who_view'] = ( int )$row['who_view'];
		$array['groups_view'] = $row['groups_view'];
		$array['who_download'] = ( int )$row['who_download'];
		$array['groups_download'] = $row['groups_download'];

		$array['fileupload'] = ! empty( $array['fileupload'] ) ? explode( '[NV]', $array['fileupload'] ) : array();
		if( ! empty( $array['linkdirect'] ) )
		{
			$array['linkdirect'] = explode( '[NV]', $array['linkdirect'] );
			$array['linkdirect'] = array_map( 'nv_br2nl', $array['linkdirect'] );
		}
		else
		{
			$array['linkdirect'] = array();
		}
		$array['groups_comment'] = ! empty( $array['groups_comment'] ) ? explode( ',', $array['groups_comment'] ) : array();
		$array['groups_view'] = ! empty( $array['groups_view'] ) ? explode( ',', $array['groups_view'] ) : array();
		$array['groups_download'] = ! empty( $array['groups_download'] ) ? explode( ',', $array['groups_download'] ) : array();
		$array['is_del_report'] = 1;
	}

	if( ! empty( $array['description'] ) ) $array['description'] = nv_htmlspecialchars( $array['description'] );
	if( ! empty( $array['introtext'] ) ) $array['introtext'] = nv_htmlspecialchars( $array['introtext'] );

	$array['fileupload_num'] = sizeof( $array['fileupload'] );
	$array['linkdirect_num'] = sizeof( $array['linkdirect'] );

	// Build fileimage
	if( ! empty( $array['fileimage'] ) )
	{
		if( ! preg_match( '#^(http|https|ftp|gopher)\:\/\/#', $array['fileimage'] ) )
		{
			$array['fileimage'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . $array['fileimage'];
		}
	}

	//Rebuild fileupload
	if( ! empty( $array['fileupload'] ) )
	{
		$fileupload = $array['fileupload'];
		$array['fileupload'] = array();
		foreach( $fileupload as $tmp )
		{
			if( ! preg_match( '#^(http|https|ftp|gopher)\:\/\/#', $tmp ) )
			{
				$tmp = NV_BASE_SITEURL . NV_UPLOADS_DIR . $tmp;
			}
			$array['fileupload'][] = $tmp;
		}
	}

	if( ! sizeof( $array['fileupload'] ) ) array_push( $array['fileupload'], '' );
	if( ! sizeof( $array['linkdirect'] ) ) array_push( $array['linkdirect'], '' );

	$listcats = nv_listcats( $array['catid'] );
	if( empty( $listcats ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cat&add=1' );
		exit();
	}

	$array['comment_allow'] = $array['comment_allow'] ? ' checked="checked"' : '';
	$array['is_del_report'] = $array['is_del_report'] ? ' checked="checked"' : '';

	$who_comment = $array['who_comment'];
	$array['who_comment'] = array();
	foreach( $array_who as $key => $who )
	{
		$array['who_comment'][] = array(
			'key' => $key,
			'title' => $who,
			'selected' => $key == $who_comment ? ' selected="selected"' : ''
		);
	}

	$groups_comment = $array['groups_comment'];
	$array['groups_comment'] = array();
	if( ! empty( $groups_list ) )
	{
		foreach( $groups_list as $key => $title )
		{
			$array['groups_comment'][] = array(
				'key' => $key,
				'title' => $title,
				'checked' => in_array( $key, $groups_comment ) ? ' checked="checked"' : ''
			);
		}
	}

	$who_view = $array['who_view'];
	$array['who_view'] = array();
	foreach( $array_who as $key => $who )
	{
		$array['who_view'][] = array(
			'key' => $key,
			'title' => $who,
			'selected' => $key == $who_view ? ' selected="selected"' : ''
		);
	}

	$groups_view = $array['groups_view'];
	$array['groups_view'] = array();
	if( ! empty( $groups_list ) )
	{
		foreach( $groups_list as $key => $title )
		{
			$array['groups_view'][] = array(
				'key' => $key,
				'title' => $title,
				'checked' => in_array( $key, $groups_view ) ? ' checked="checked"' : ''
			);
		}
	}

	$who_download = $array['who_download'];
	$array['who_download'] = array();
	foreach( $array_who as $key => $who )
	{
		$array['who_download'][] = array(
			'key' => $key,
			'title' => $who,
			'selected' => $key == $who_download ? ' selected="selected"' : ''
		);
	}

	$groups_download = $array['groups_download'];
	$array['groups_download'] = array();
	if( ! empty( $groups_list ) )
	{
		foreach( $groups_list as $key => $title )
		{
			$array['groups_download'][] = array(
				'key' => $key,
				'title' => $title,
				'checked' => in_array( $key, $groups_download ) ? ' checked="checked"' : ''
			);
		}
	}

	if( defined( 'NV_EDITOR' ) )
	{
		require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
	}

	if( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
	{
		$array['description'] = nv_aleditor( 'description', '100%', '300px', $array['description'] );
	}
	else
	{
		$array['description'] = "<textarea style=\"width:100%; height:300px\" name=\"description\" id=\"description\">" . $array['description'] . "</textarea>";
	}
    $array['id'] = $id;

	$sql = "SELECT config_value FROM " . NV_PREFIXLANG . "_" . $module_data . "_config WHERE config_name='upload_dir'";
	$result = $db->query( $sql );
	$upload_dir = $result->fetchColumn();

	if( empty( $array['filesize'] ) )
	{
	    $array['filesize'] = '';
    }
    else
    {
        $array['filesize'] = number_format( $array['filesize']/1048576, 2);
    }

	$xtpl = new XTemplate( 'content.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );

	$report = $report ? '&amp;report=1' : '';
	$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;edit=1&amp;id=' . $id . $report );

	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'DATA', $array );
	$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'IMG_DIR', NV_UPLOADS_DIR . '/' . $module_name . '/images' );
	$xtpl->assign( 'FILES_DIR', NV_UPLOADS_DIR . '/' . $module_name . '/' . $upload_dir );

	if( ! empty( $error ) )
	{
		$xtpl->assign( 'ERROR', $error );
		$xtpl->parse( 'main.error' );
	}

	foreach( $listcats as $cat )
	{
		$xtpl->assign( 'LISTCATS', $cat );
		$xtpl->parse( 'main.catid' );
	}

	$a = 0;
	foreach( $array['fileupload'] as $file )
	{
		$xtpl->assign( 'FILEUPLOAD', array( 'value' => $file, 'key' => $a ) );
		$xtpl->parse( 'main.fileupload' );
		++$a;
	}

	$a = 0;
	foreach( $array['linkdirect'] as $link )
	{
		$xtpl->assign( 'LINKDIRECT', array( 'value' => $link, 'key' => $a ) );
		$xtpl->parse( 'main.linkdirect' );
		++$a;
	}

	foreach( $array['who_comment'] as $who )
	{
		$xtpl->assign( 'WHO_COMMENT', $who );
		$xtpl->parse( 'main.who_comment' );
	}

	if( ! empty( $array['groups_comment'] ) )
	{
		foreach( $array['groups_comment'] as $group )
		{
			$xtpl->assign( 'GROUPS_COMMENT', $group );
			$xtpl->parse( 'main.group_empty.groups_comment' );
		}
		$xtpl->parse( 'main.group_empty' );
	}

	foreach( $array['who_view'] as $who )
	{
		$xtpl->assign( 'WHO_VIEW', $who );
		$xtpl->parse( 'main.who_view' );
	}

	if( ! empty( $array['groups_view'] ) )
	{
		foreach( $array['groups_view'] as $group )
		{
			$xtpl->assign( 'GROUPS_VIEW', $group );
			$xtpl->parse( 'main.group_empty_view.groups_view' );
		}
		$xtpl->parse( 'main.group_empty_view' );
	}

	foreach( $array['who_download'] as $who )
	{
		$xtpl->assign( 'WHO_DOWNLOAD', $who );
		$xtpl->parse( 'main.who_download' );
	}

	if( ! empty( $array['groups_download'] ) )
	{
		foreach( $array['groups_download'] as $group )
		{
			$xtpl->assign( 'GROUPS_DOWNLOAD', $group );
			$xtpl->parse( 'main.group_empty_download.groups_download' );
		}
		$xtpl->parse( 'main.group_empty_download' );
	}

	$xtpl->parse( 'main.is_del_report' );

	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );

	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
	exit();
}

// Avtive - Deactive
if( $nv_Request->isset_request( 'changestatus', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

	$id = $nv_Request->get_int( 'id', 'post', 0 );

	$query = 'SELECT status FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $id;
	$row = $db->query( $query )->fetch();
	if( empty( $row ) ) die( 'NO' );

	$status = $row['status'] ? 0 : 1;

	$db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET status=' . $status . ' WHERE id=' . $id );

    nv_del_moduleCache( $module_name );
	die( 'OK' );
}

// Delete file
if( $nv_Request->isset_request( 'del', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

	$id = $nv_Request->get_int( 'id', 'post', 0 );

	$query = 'SELECT fileupload, fileimage, title FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $id;
	$row = $db->query( $query )->fetch();
	if( empty( $row ) ) die( 'NO' );

	$db->query( 'DELETE FROM ' . NV_PREFIXLANG . '_comments WHERE module=' . $db->quote( $module_name ) . ' AND id=' . $id );
	$db->query( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_report WHERE fid=' . $id );
	$db->query( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $id );

    nv_del_moduleCache( $module_name );

	nv_insert_logs( NV_LANG_DATA, $module_data, $lang_module['download_filequeue_del'], $row['title'], $admin_info['userid'] );
	die( 'OK' );
}

// List file
$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;

$listcats = nv_listcats( 0 );
if( empty( $listcats ) )
{
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cat&add=1' );
	exit();
}

$db->sqlreset()
	->select( 'COUNT(*)' )
	->from( NV_PREFIXLANG . '_' . $module_data );

if( $nv_Request->isset_request( 'catid', 'get' ) )
{
	$catid = $nv_Request->get_int( 'catid', 'get', 0 );
	if( ! $catid or ! isset( $listcats[$catid] ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
		exit();
	}

	$page_title = sprintf( $lang_module['file_list_by_cat'], $listcats[$catid]['title'] );
	$base_url .= '&amp;catid=' . $catid;

	$db->where( 'catid=' . $catid );
}
else
{
	$page_title = $lang_module['download_filemanager'];
}

$all_page = $db->query( $db->sql() )->fetchColumn();

if( empty( $all_page ) )
{
	if( $catid )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
		exit();
	}
	else
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=add' );
		exit();
	}
}

$page = $nv_Request->get_int( 'page', 'get', 0 );
$per_page = 30;

$db->select( '*' )
	->order( 'uploadtime DESC' )
	->limit( $per_page )
	->offset( $page );

$result2 = $db->query( $db->sql() );

$array = array();

while( $row = $result2->fetch() )
{
	$array[$row['id']] = array(
		'id' => $row['id'],
		'title' => $row['title'],
		'cattitle' => $listcats[$row['catid']]['title'],
		'catlink' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;catid=' . $row['catid'],
		'uploadtime' => nv_date( 'd/m/Y H:i', $row['uploadtime'] ),
		'status' => $row['status'] ? ' checked="checked"' : '',
		'view_hits' => $row['view_hits'],
		'download_hits' => $row['download_hits'],
		'comment_hits' => $row['comment_hits']
	);
}

$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );

$xtpl = new XTemplate( 'main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'ADD_NEW_FILE', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=add' );

if( ! empty( $array ) )
{
	foreach( $array as $row )
	{
		$xtpl->assign( 'ROW', $row );
		$xtpl->assign( 'EDIT_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;edit=1&amp;id=' . $row['id'] );
		$xtpl->parse( 'main.row' );
	}
}

if( ! empty( $generate_page ) )
{
	$xtpl->assign( 'GENERATE_PAGE', $generate_page );
	$xtpl->parse( 'main.generate_page' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';

?>