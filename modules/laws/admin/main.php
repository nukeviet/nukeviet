<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Wed, 27 Jul 2011 14:55:22 GMT
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['main'];

$contents = "";

$groups_list = nv_groups_list();

$catList = nv_catList();

$aList = nv_aList();

$sList = nv_sList();
$scount = count( $sList );

$sgList = nv_sgList();
$scount = count( $sgList );

$sql = "SELECT COUNT(*) as ccount FROM " . NV_PREFIXLANG . "_" . $module_data . "_row";
$result = $db->query( $sql );
$all_page = $result->fetch();
$all_page = $all_page['ccount'];

$xtpl = new XTemplate( $op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_ASSETS_DIR', NV_ASSETS_DIR );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'MODULE_DATA', $module_data );
$xtpl->assign( 'MODULE_URL', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'UPLOADS_DIR_USER', NV_UPLOADS_DIR . '/' . $module_upload );

if ( empty( $all_page ) and ! $nv_Request->isset_request( 'add', 'get' ))
{
 	if ( empty( $catList ) )
	{
		$type = $lang_module['cat'];
		$href = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat&add";
	}
	elseif ( empty( $aList ) )
	{
		$type = $lang_module['area'];
		$href = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=area&add";
	}
	elseif ( empty( $sList ) )
	{
		$type = $lang_module['subject'];
		$href = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=subject&add";
	}
	elseif ( empty( $sgList ) )
	{
		$type = $lang_module['signer'];
		$href = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=signer&add";
	}
	elseif ( empty( $sgList ) )
	{
		$type = $lang_module['signer'];
		$href = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=signer&add";
	}
	else
	{
	    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&add" );die();
	}

	$xtpl->assign('TYPE', $type);
	$xtpl->assign('HREF', $href);
	$xtpl->parse( 'msg.loop' );
	$xtpl->parse( 'msg' );
	$contents = $xtpl->text( 'msg' );
	$contents.='<meta http-equiv="refresh" content="5;url='.$href.'">';
}
else
{
	if ( $nv_Request->isset_request( 'add', 'get' ) or $nv_Request->isset_request( 'edit, id', 'get' ) )
	{
	    $row = array();
	    if ( $nv_Request->isset_request( 'edit, id', 'get' ) )
	    {
	        $post['id'] = $nv_Request->get_int( 'id', 'get', 0 );

	        $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_row WHERE id=" . $post['id'];
	        $result = $db->query( $sql );
	        $num = $result->rowCount();
	        if ( $num != 1 )
	        {
	            Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
	            die();
	        }
	        $row = $result->fetch();

			$post['area_id'] = array();
			$result = $db->query( 'SELECT area_id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_row_area WHERE row_id=' . $row['id'] );
			while( list( $area_id ) = $result->fetch( 3 ) )
			{
				$post['area_id'][] = $area_id;
			}
			$row['area_id_old'] = $row['area_id'] = $post['area_id'];
	    }
		else{
			$row['area_id_old'] = $post['area_id'] = array();
		}

	    if ( $nv_Request->isset_request( 'save', 'post' ) )
	    {
	        $post['title'] = $nv_Request->get_title( 'title', 'post', '', 1 );
	        if ( empty( $post['title'] ) )
	        {
	            die( $lang_module['errorIsEmpty'] . ": " . $lang_module['title'] );
	        }

	        $post['cid'] = $nv_Request->get_int( 'cid', 'post', 0 );
	        if ( ! isset( $catList[$post['cid']] ) )
	        {
	            die( $lang_module['erroNotSelectCat'] );
	        }

			$post['area_id'] = $nv_Request->get_typed_array( 'aid', 'post', 'int', array() );
	        if ( empty($post['area_id'] ) )
	        {
	            die( $lang_module['erroNotSelectArea'] );
	        }

	        $post['sid'] = $nv_Request->get_int( 'sid', 'post', 0 );
	        if ( ! isset( $sList[$post['sid']] ) )
	        {
	            die( $lang_module['erroNotSelectSubject'] );
	        }

	        $post['sgid'] = $nv_Request->get_title( 'sgid', 'post', '' );
			if( !is_numeric( $post['sgid'] ) and !empty( $post['sgid'] ) )
			{
				$result = $db->query( "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_signer WHERE title=" . $db->quote( $post['title'] ) . " AND offices='' AND positions=''" );
				if ( $result->rowCount() == 0 )
				{
					$sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_signer(title, addtime) VALUES(' . $db->quote( $post['sgid'] ) . ', ' . NV_CURRENTTIME . ')';
					$post['sgid'] = $db->insert_id( $sql );
				}
				else
				{
					$post['sgid'] = $result->fetchColumn();
				}
			}
			else
			{
				$post['sgid'] = intval( $post['sgid'] );
			}

	        $post['introtext'] = $nv_Request->get_title( 'introtext', 'post', '', 1 );
	        $post['introtext'] = nv_nl2br( $post['introtext'], "<br />" );
	        if ( empty( $post['introtext'] ) )
	        {
	            die( $lang_module['errorIsEmpty'] . ": " . $lang_module['introtext'] );
	        }

			$post['note'] = $nv_Request->get_title( 'note', 'post', '', 1 );
			$post['note'] = nv_nl2br( $post['note'], "<br />" );

	        $post['replacement'] = nv_substr( $nv_Request->get_title( 'replacement', 'post', '', 1 ), 0, 255);

	        if ( ! empty( $post['replacement'] ) )
	        {
				$check_replacement = explode( ",", $post['replacement'] );
				$check_replacement = array_map( "trim", $check_replacement );
				$check_replacement = array_map( "intval", $check_replacement );
				$check_replacement = array_filter( $check_replacement );

				foreach( $check_replacement as $replacement )
				{
					$sql = "SELECT COUNT(*) as count FROM " . NV_PREFIXLANG . "_" . $module_data . "_row WHERE id=" . $replacement;
					$result = $db->query( $sql );
					$count = $result->fetchColumn();

					if ( $count != 1 )
					{
						die( sprintf( $lang_module['replacementError'], $replacement ) );
					}
				}

				$post['replacement'] = implode( ",", $check_replacement );
	        }

			$post['relatement'] = nv_substr( $nv_Request->get_title( 'relatement', 'post', '', 1 ), 0, 255);

	        if ( ! empty( $post['relatement'] ) )
	        {
				$check_relatement = explode( ",", $post['relatement'] );
				$check_relatement = array_map( "trim", $check_relatement );
				$check_relatement = array_map( "intval", $check_relatement );
				$check_relatement = array_filter( $check_relatement );

				foreach( $check_relatement as $relatement )
				{
					$sql = "SELECT COUNT(*) as count FROM " . NV_PREFIXLANG . "_" . $module_data . "_row WHERE id=" . $relatement;
					$result = $db->query( $sql );
					$count = $result->fetchColumn();

					if ( $count != 1 )
					{
						die( sprintf( $lang_module['relatementError'], $relatement ) );
					}
				}

				$post['relatement'] = implode( ",", $check_relatement );
	        }

	        $alias = change_alias( $post['title'] );
	        $post['code'] = $nv_Request->get_title( 'code', 'post', '', 1 );
	        $post['bodytext'] = $nv_Request->get_editor( 'bodytext', '', NV_ALLOWED_HTML_TAGS );
	        $test_content = strip_tags( $post['bodytext'] );
	        $test_content = trim( $test_content );
	        $post['bodytext'] = ! empty( $test_content ) ? nv_editor_nl2br( $post['bodytext'] ) : "";
	        $post['keywords'] = $nv_Request->get_title( 'keywords', 'post', '', 1 );
	        if ( ! empty( $post['keywords'] ) )
	        {
	            $post['keywords'] = explode( ",", $post['keywords'] );
	            $post['keywords'] = array_map( "trim", $post['keywords'] );
	            $post['keywords'] = array_unique( $post['keywords'] );
	            $post['keywords'] = implode( ",", $post['keywords'] );
	        }
	        if ( empty( $post['keywords'] ) ) $post['keywords'] = nv_get_keywords( ( ! empty( $post['bodytext'] ) ? $post['bodytext'] : $post['introtext'] ) );

	        $_groups_post = $nv_Request->get_array( 'groups_view', 'post', array() );
	        $post['groups_view'] = ! empty( $_groups_post ) ? implode( ',', nv_groups_post( array_intersect( $_groups_post, array_keys( $groups_list ) ) ) ) : '';

	        $_groups_download = $nv_Request->get_array( 'groups_download', 'post', array() );
	        $post['groups_download'] = ! empty( $_groups_download ) ? implode( ',', nv_groups_post( array_intersect( $_groups_download, array_keys( $groups_list ) ) ) ) : '';

	        $post['files'] = array();
	        $fileupload = $nv_Request->get_array( 'files', 'post' );
	        if ( ! empty( $fileupload ) )
	        {
	            $fileupload = array_map( "trim", $fileupload );
	            $fileupload = array_unique( $fileupload );
	            foreach ( $fileupload as $_file )
	            {
					if ( preg_match( "/^" . str_replace( "/", "\/", NV_BASE_SITEURL . NV_UPLOADS_DIR ) . "\//", $_file ) )
					{
						$_file = substr ( $_file, strlen ( NV_BASE_SITEURL . NV_UPLOADS_DIR .'/'. $module_upload.'/') );

						if( file_exists( NV_UPLOADS_REAL_DIR .'/'. $module_upload.'/'.$_file ) )
						{
							$post['files'][] = $_file;
						}
					}
	            }
	        }
	        $post['files'] = ! empty( $post['files'] ) ? implode( ",", $post['files'] ) : "";
	        $post['publtime'] = nv_substr( $nv_Request->get_title( 'publtime', 'post', '', 1 ), 0, 10);
	        unset( $m );
	        if ( preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $post['publtime'], $m ) )
	        {
	            $post['publtime'] = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
	        }
	        else
	        {
	            $post['publtime'] = 0;
	        }
	        if ( empty( $post['publtime'] ) )
	        {
	            die( $lang_module['erroNotSelectPubtime'] );
	        }

	        $post['exptime'] = nv_substr( $nv_Request->get_title( 'exptime', 'post', '', 1 ), 0, 10);
	        unset( $m );
	        if ( preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $post['exptime'], $m ) )
	        {
	            $post['exptime'] = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
	        }
	        else
	        {
	            $post['exptime'] = 0;
	        }
	        $post['startvalid'] = nv_substr( $nv_Request->get_title( 'startvalid', 'post', '', 1 ), 0, 10);
	        unset( $m );
	        if ( preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $post['startvalid'], $m ) )
	        {
	            $post['startvalid'] = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
	        }
	        else
	        {
	            $post['startvalid'] = 0;
	        }

	        if ( isset( $post['id'] ) )
	        {
	            $query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_row SET
	                replacement=" . $db->quote( $post['replacement'] ) . ",
	                relatement=" . $db->quote( $post['relatement'] ) . ",
	                title=" . $db->quote( $post['title'] ) . ",
	                alias=" . $db->quote( $alias . "-" . $post['id'] ) . ",
	                code=" . $db->quote( $post['code'] ) . ",
	                cid=" . $post['cid'] . ",
	                sid=" . $post['sid'] . ",
	                sgid=" . $post['sgid'] . ",
	                note=" . $db->quote( $post['note'] ) . ",
	                introtext=" . $db->quote( $post['introtext'] ) . ",
	                bodytext=" . $db->quote( $post['bodytext'] ) . ",
	                keywords=" . $db->quote( $post['keywords'] ) . ",
	                groups_view=" . $db->quote( $post['groups_view'] ) . ",
	                groups_download=" . $db->quote( $post['groups_download'] ) . ",
	                files=" . $db->quote( $post['files'] ) . ",
	                edittime=" . NV_CURRENTTIME . ",
	                publtime=" . $post['publtime'] . ",
	                exptime=" . $post['exptime'] . ",
	                startvalid=" . $post['startvalid'] . ",
	                admin_edit=" . $admin_info['userid'] . "
	                WHERE id=" . $post['id'];
	            $db->query( $query );

				$_id = $post['id'];

				$sql = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_set_replace WHERE nid=" . $post['id'];
				$db->query( $sql );

				$replacement = explode( ",", $post['replacement'] );
				$replacement = array_filter( $replacement );

				foreach( $replacement as $rep )
				{
					$db->query( "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_set_replace VALUES( NULL, " . $post['id'] . ", " . $rep . " )" );
				}

				// Cap nhat lai so luong van ban o chu de
				$db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_subject SET numcount=(SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_row WHERE sid=' . $post['sid'] . ') WHERE id=' . $post['sid'] );

	            nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['editRow'], "Id: " . $post['id'], $admin_info['userid'] );
	        }
	        else
	        {
	            $query = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_row VALUES
	                (NULL,
	                " . $db->quote( $post['replacement'] ) . ",
	                " . $db->quote( $post['relatement'] ) . ",
	                " . $db->quote( $post['title'] ) . ",
	                '',
	                " . $db->quote( $post['code'] ) . ",
	                " . $post['cid'] . ",
	                " . $post['sid'] . ",
	                " . $post['sgid'] . ",
	                " . $db->quote( $post['note'] ) . ",
	                " . $db->quote( $post['introtext'] ) . ",
	                " . $db->quote( $post['bodytext'] ) . ",
	                " . $db->quote( $post['keywords'] ) . ",
	                " . $db->quote( $post['groups_view'] ) . ",
	                " . $db->quote( $post['groups_download'] ) . ",
	                " . $db->quote( $post['files'] ) . ",
	                1, " . NV_CURRENTTIME . ", 0,
	                " . $post['publtime'] . ",
	                " . $post['startvalid'] . ",
	                " . $post['exptime'] . ",
	                0, 0, " . $admin_info['userid'] . ", 0);";
	            $_id = $db->insert_id( $query );

	            $alias .= "-" . $_id;
	            $query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_row SET alias=" . $db->quote( $alias ) . " WHERE id=" . $_id;
	            $db->query( $query );

				$replacement = explode( ",", $post['replacement'] );
				$replacement = array_filter( $replacement );

				foreach( $replacement as $rep )
				{
					$db->query( "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_set_replace VALUES( NULL, " . $_id . ", " . $rep . " )" );
				}

				// Cap nhat lai so luong van ban o chu de
				$db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_subject SET numcount=(SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_row WHERE sid=' . $post['sid'] . ') WHERE id=' . $post['sid'] );

	            nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['addRow'], "Id: " . $_id, $admin_info['userid'] );
	        }

			if( $post['area_id'] != $row['area_id_old'] )
			{
				$sth = $db->prepare( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_row_area (row_id, area_id) VALUES( :row_id, :area_id )' );
				foreach( $post['area_id'] as $area_id )
				{
					if( !in_array( $area_id, $row['area_id_old'] ) )
					{
						$sth->bindParam( ':row_id', $_id, PDO::PARAM_INT );
						$sth->bindParam( ':area_id', $area_id, PDO::PARAM_INT );
						$sth->execute();
					}
				}

				foreach( $row['area_id_old'] as $area_id_old )
				{
					if( !in_array( $area_id_old, $post['area_id'] ) )
					{
						$db->query( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_row_area WHERE area_id = ' . $area_id_old . ' AND row_id=' . $_id );
					}
				}
			}

	        $nv_Cache->delMod( $module_name );
	        die( 'OK' );
	    }

	    if ( defined( 'NV_EDITOR' ) )
	    {
	        require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );
	    }

	    if ( isset( $post['id'] ) )
	    {
	        $post = $row;

	        $post['select0'] = ( $post['exptime'] == 0 or $post['exptime'] > NV_CURRENTTIME ) ? " selected=\"selected\"" : "";
	        $post['select1'] = ( $post['exptime'] != 0 and $post['exptime'] <= NV_CURRENTTIME ) ? " selected=\"selected\"" : "";
			$post['display'] = ( $post['exptime'] == 0 or $post['exptime'] > NV_CURRENTTIME ) ? "none" : "block";

	        $post['groups_view'] = ! empty( $post['groups_view'] ) ? explode( ",", $post['groups_view'] ) : array();
	        $post['groups_download'] = ! empty( $post['groups_download'] ) ? explode( ",", $post['groups_download'] ) : array();
	        $post['files'] = ! empty( $post['files'] ) ? explode( ",", $post['files'] ) : array();
	        $post['publtime'] = ! empty( $post['publtime'] ) ? date( "d.m.Y", $post['publtime'] ) : "";
	        $post['exptime'] = ! empty( $post['exptime'] ) ? date( "d.m.Y", $post['exptime'] ) : "";
	        $post['startvalid'] = ! empty( $post['startvalid'] ) ? date( "d.m.Y", $post['startvalid'] ) : "";

	        $post['ptitle'] = $lang_module['editRow'];
	        $post['action_url'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=main&edit&id=" . $post['id'];
	    }
	    else
	    {
	        $post['relatement'] = $post['replacement'] = $post['title'] = $post['code'] = $post['introtext'] = $post['bodytext'] = $post['keywords'] = $post['author'] = $post['publtime'] = $post['exptime'] = "";
	        $post['groups_view'] = $post['groups_download'] = array( 6 );
	        $post['cid'] = $post['sid'] = $post['sgid'] = $post['who_view'] = $post['who_download'] = 0;

	        $post['groupcss'] = $post['groupcss2'] = "groupcss0";
			$post['files'] = '';

			$post['select0'] = " selected=\"selected\"";
	        $post['select1'] = "";
	        $post['display'] = "none";

	        $post['ptitle'] = $lang_module['addRow'];
	        $post['action_url'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=main&add";
	    }

	    if ( ! empty( $post['introtext'] ) ) $post['introtext'] = nv_htmlspecialchars( $post['introtext'] );
	    if ( ! empty( $post['bodytext'] ) ) $post['bodytext'] = nv_htmlspecialchars( $post['bodytext'] );
	    if ( ! empty( $post['note'] ) ) $post['note'] = nv_htmlspecialchars( $post['note'] );

	    $xtpl->assign( 'DATA', $post );
	    foreach ( $catList as $_cat )
	    {
	        $_cat['selected'] = $_cat['id'] == $post['cid'] ? " selected=\"selected\"" : "";
	        $xtpl->assign( 'CATOPT', $_cat );
	        $xtpl->parse( 'add.catopt' );
	    }

	    foreach ( $aList as $_a )
	    {
	        $_a['checked'] = in_array($_a['id'], $post['area_id']) ? " checked=\"checked\"" : "";
	        $xtpl->assign( 'AREAOPT', $_a );
	        $xtpl->parse( 'add.areaopt' );
	    }

	    foreach ( $sList as $_s )
	    {
	        $_s['selected'] = $_s['id'] == $post['sid'] ? " selected=\"selected\"" : "";
	        $xtpl->assign( 'SUBOPT', $_s );
	        $xtpl->parse( 'add.subopt' );
	    }

	    foreach ( $sgList as $_sg )
	    {
	        $_sg['selected'] = $_sg['id'] == $post['sgid'] ? " selected=\"selected\"" : "";
	        $xtpl->assign('SINGER', $_sg);
	        $xtpl->parse( 'add.singers' );

	    }

		$is_editor = 0;
	    if ( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
	    {
	        $is_editor = 1;
	        $_cont = nv_aleditor( 'bodytext', '100%', '300px', $post['bodytext'] );
	    }
	    else
	    {
	        $_cont = "<textarea style=\"width:100%;height:300px\" name=\"bodytext\" id=\"bodytext\">" . $post['bodytext'] . "</textarea>";
	    }
	    $xtpl->assign( 'CONTENT', $_cont );

	    $groups_views = array();
		foreach( $groups_list as $group_id => $grtl )
		{
			$groups_views[] = array(
				'id' => $group_id,
				'checked' => in_array( $group_id, $post['groups_view'] ) ? ' checked="checked"' : '',
				'title' => $grtl
			);
	        $groups_downloads[] = array(
				'id' => $group_id,
				'checked' => in_array( $group_id, $post['groups_download'] ) ? ' checked="checked"' : '',
				'title' => $grtl
			);
		}

	    foreach ( $groups_views as $data )
	    {
	        $xtpl->assign( 'GROUPS_VIEWS', $data );
	        $xtpl->parse( 'add.group_view' );
	    }

	    foreach ( $groups_downloads as $data )
	    {
	        $xtpl->assign( 'GROUPS_DOWNLOAD', $data );
	        $xtpl->parse( 'add.groups_download' );
	    }

	    if ( ! empty( $post['files'] ) )
	    {
			$post['files'] = array_filter( $post['files'] );
	        foreach ( $post['files'] as $_id => $_file )
	        {
	            if ( ! empty( $_file ) )
	            {
	                $xtpl->assign( 'FILEUPL', array( 'id' => $_id, 'value' => NV_BASE_SITEURL . NV_UPLOADS_DIR .'/'. $module_upload.'/'.$_file ) );
	                $xtpl->parse( 'add.files' );
	            }
	        }
	    }
		else
		{
			$xtpl->assign( 'FILEUPL', array( 'id' => 0, 'value' => '' ) );
			$xtpl->parse( 'add.files' );
		}

		$xtpl->assign( 'NUMFILE', count( $post['files'] ) );
		$xtpl->assign( 'IS_EDITOR', $is_editor );

	    $xtpl->parse( 'add' );
	    $contents = $xtpl->text( 'add' );

	    include NV_ROOTDIR . '/includes/header.php';
	    echo nv_admin_theme( $contents );
	    include NV_ROOTDIR . '/includes/footer.php';
	    die();
	}

	if ( $nv_Request->isset_request( 'del', 'post' ) )
	{
	    $id = $nv_Request->get_int( 'id', 'post', 0 );

		$data = $db->query( 'SELECT sid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_row WHERE id=' . $id )->fetch();
		if( !empty( $data ) )
		{
		    $query = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_row WHERE id = " . $id;
		    $db->query( $query );

		    $query = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_set_replace WHERE oid = " . $id;
		    $db->query( $query );

			// Cap nhat lai so luong van ban o chu de
			$db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_subject SET numcount=(SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_row WHERE sid=' . $data['sid'] . ') WHERE id=' . $data['sid'] );

		    $nv_Cache->delMod( $module_name );
		    die( 'OK' );
		}
		die( 'NO' );
	}

	if ( $nv_Request->isset_request( 'changestatus', 'post' ) )
	{
	    $id = $nv_Request->get_int( 'id', 'post', 0 );

	    $sql = "SELECT status FROM " . NV_PREFIXLANG . "_" . $module_data . "_row WHERE id=" . $id;
	    $result = $db->query( $sql );
	    $num = $result->rowCount();
	    if ( ! $num ) die( "ERROR" );
	    $row = $result->fetch();
	    $status = $row['status'];
	    if ( $status != 1 ) $status = 1;
	    else  $status = 0;
	    $query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_row SET status=" . $status . " WHERE id=" . $id;
	    if ( $db->query( $query ) === false ) die( "ERROR" );

	    $nv_Cache->delMod( $module_name );

	    die( 'OK' );
	}

	if ( $nv_Request->isset_request( 'list', 'get' ) )
	{
	    $base_url = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&list";
	    $where = $join = "";
	    if ( $nv_Request->isset_request( 'cat', 'get' ) )
	    {
	    	$keywords = $nv_Request->get_string( 'keywords', 'get', '' );
	        $cid = $nv_Request->get_int( 'cat', 'get', 0 );
			$aid = $nv_Request->get_int( 'aid', 'get', 0 );
			$sid = $nv_Request->get_int( 'sid', 'get', 0 );
			$sgid = $nv_Request->get_int( 'sgid', 'get', 0 );

	        if ( ! empty( $keywords ) )
	        {
				$dbkey = $db->dblikeescape( $keywords );
				$keyhtml = nv_htmlspecialchars($keywords);
	            $where .= " AND title like '%" . $keyhtml . "%' OR code like '%" . $keyhtml . "%' OR note like '%" . $keyhtml . "%' OR introtext like '%" . $keyhtml . "%' OR bodytext like '%" . $dbkey . "%'";
	            $base_url .= "&keywords=" . $keywords;
	        }

	        if ( ! empty( $cid ) and isset( $catList[$cid] ) )
	        {
	            $where .= " AND cid IN (" . implode( ',', nv_GetCatidInParent( $cid, $catList ) ) . ")";
	            $base_url .= "&cat=" . $cid;
	        }

	        if ( ! empty( $aid ) and isset( $aList[$aid] ) )
	        {
	        	$join .= 'INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_row_area t2 ON t1.id=t2.row_id';
	            $where .= " AND t2.area_id IN (" . implode( ',', nv_GetCatidInParent( $aid, $aList ) ) . ")";
	            $base_url .= "&aid=" . $aid;
	        }

	        if ( ! empty( $sid ) and isset( $sList[$sid] ) )
	        {
	            $where .= " AND sid=" . $sid;
	            $base_url .= "&sid=" . $sid;
	        }

	        if ( ! empty( $sgid ) and isset( $sgList[$sgid] ) )
	        {
	            $where .= " AND sgid=" . $sgid;
	            $base_url .= "&sgid=" . $sgid;
	        }
	    }

	    $sql = "SELECT COUNT(*) as ccount FROM " . NV_PREFIXLANG . "_" . $module_data . "_row t1 " . $join . " WHERE 1=1 " . $where;
	    $result = $db->query( $sql );
	    $all_page = $result->fetch();
	    $all_page = $all_page['ccount'];

	    $page = $nv_Request->get_int( 'page', 'get', 1 );
		
	    $per_page = 30;

	    if ( $all_page )
	    {
	        $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_row t1 " . $join . " WHERE 1=1 " . $where . " ORDER BY addtime DESC LIMIT " . (($page - 1) * $per_page) . "," . $per_page;
	        $result = $db->query( $sql );
	        $a = 0;
	        while ( $row = $result->fetch() )
	        {
	            $row['publtime'] = date( "d-m-Y", $row['publtime'] );
	            $row['exptime'] = $row['exptime'] ? date( "d-m-Y", $row['exptime'] ) : "N/A";
	            $row['selected'] = $row['status'] == 1 ? " selected=\"selected\"" : "";
	            $row['url_edit'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;edit=1&amp;id=" . $row['id'];
	            $row['url_view'] = nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=".$module_info['alias']['detail']."/" . $row['alias'], true );
	            $xtpl->assign( 'CLASS', $a % 2 ? " class=\"second\"" : "" );
	            $xtpl->assign( 'DATA', $row );
	            $xtpl->parse( 'list.loop' );
	            $a++;
	        }

	        $generate_page = nv_generate_page(  $base_url, $all_page, $per_page, $page, true, true, "nv_load_laws", "data"  );

	        $xtpl->assign( 'NV_GENERATE_PAGE', $generate_page );
	        $xtpl->parse( 'list' );
	        $xtpl->out( 'list' );
	    }
	    exit;
	}

	$search = array(
		'keywords' => '',
		'cid' => 0,
		'aid' => 0,
		'sid' => 0
	);

	foreach ( $catList as $_cat )
	{
	    $xtpl->assign( 'CATOPT', $_cat );
	    $xtpl->parse( 'main.catParent' );
	}

	foreach ( $aList as $_aList )
	{
	    $xtpl->assign( 'ALIST', $_aList );
	    $xtpl->parse( 'main.alist' );
	}

	foreach ( $sList as $_sList )
	{
	    $xtpl->assign( 'SLIST', $_sList );
	    $xtpl->parse( 'main.slist' );
	}

	foreach ( $sgList as $_sgList )
	{
	    $xtpl->assign( 'SGLIST', $_sgList );
	    $xtpl->parse( 'main.sglist' );
	}

	$xtpl->assign( 'BASE_LOAD', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&list" );
	$xtpl->assign( 'ADD_LINK', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&add" );

	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );
}
include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
