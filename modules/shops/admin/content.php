<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

if( defined( 'NV_EDITOR' ) )
{
	require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );
}

$table_name = $db_config['prefix'] . "_" . $module_data . "_rows";
$month_dir_module = nv_mkdir( NV_UPLOADS_REAL_DIR . '/' . $module_name, date( "Y_m" ), true );
$array_block_cat_module = array();
$id_block_content = array();

$sql = "SELECT `bid`, `adddefault`, `" . NV_LANG_DATA . "_title` FROM `" . $db_config['prefix'] . "_" . $module_data . "_block_cat` ORDER BY `weight` ASC";
$result = $db->sql_query( $sql );

while( list( $bid_i, $adddefault_i, $title_i ) = $db->sql_fetchrow( $result ) )
{
	$array_block_cat_module[$bid_i] = $title_i;
	if( $adddefault_i )
	{
		$id_block_content[] = $bid_i;
	}
}

$catid = $nv_Request->get_int( 'catid', 'get', 0 );
$parentid = $nv_Request->get_int( 'parentid', 'get', 0 );

$sql = "SELECT `numsubcat` FROM `" . $db_config['prefix'] . "_" . $module_data . "_catalogs` WHERE `catid`=" . $db->dbescape( $parentid );
$result = $db->sql_query( $sql );
list( $subcatid ) = $db->sql_fetchrow( $result );
if( $subcatid > 0 )
{
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
	die();
}

if( $pro_config['who_comment'] == 0 )
{
	$allowed_comm = 1;
}
else
{
	$allowed_comm = 2;
}

$rowcontent = array(
	"id" => 0,
	"listcatid" => $catid,
	"group_id" => "",
	"user_id" => $admin_info['admin_id'],
	"source_id" => 0,
	"addtime" => NV_CURRENTTIME,
	"edittime" => NV_CURRENTTIME,
	"status" => 0,
	"publtime" => NV_CURRENTTIME,
	"exptime" => 0,
	"archive" => 1,
	"product_code" => "",
	"product_number" => 1,
	"product_price" => 1,
	"product_discounts" => 0,
	"money_unit" => $pro_config['money_unit'],
	"product_unit" => "",
	"homeimgfile" => "",
	"homeimgthumb" => "",
	"homeimgalt" => "",
	"imgposition" => 0,
	"copyright" => 0,
	"inhome" => 1,
	"allowed_comm" => $allowed_comm,
	"allowed_rating" => 1,
	"ratingdetail" => "0",
	"allowed_send" => 1,
	"allowed_print" => 1,
	"allowed_save" => 1,
	"hitstotal" => 0,
	"hitscm" => 0,
	"hitslm" => 0,
	"showprice" => 1,
	"com_id" => 0,
	"title" => "",
	"alias" => "",
	"hometext" => "",
	"bodytext" => "",
	"note" => "",
	"keywords" => "",
	"address" => "",
	"description" => "",
	"warranty" => "",
	"promotional" => "",
	"sourcetext" => "",
	"topictext" => "",
);

$page_title = $lang_module['content_add'];
$error = "";
$groups_list = nv_groups_list();

$rowcontent['id'] = $nv_Request->get_int( 'id', 'get,post', 0 );

if( $nv_Request->get_int( 'save', 'post' ) == 1 )
{
	$field_lang = nv_file_table( $table_name );
	$id_block_content = array_unique( $nv_Request->get_typed_array( 'bids', 'post', 'int', array() ) );

	$rowcontent['listcatid'] = $nv_Request->get_int( 'catid', 'post', 0 );
	
	$group_id = array_unique( $nv_Request->get_typed_array( 'groupids', 'post', 'int', array() ) );
	$rowcontent['group_id'] = implode( ",", $group_id );

	$rowcontent['source_id'] = $nv_Request->get_int( 'sourceid', 'post', 0 );
	$rowcontent['showprice'] = $nv_Request->get_int( 'showprice', 'post', 0 );
	$rowcontent['showorder'] = $nv_Request->get_int( 'showorder', 'post', 0 );
	
	if( $rowcontent['source_id'] == 0 )
	{
		$rowcontent['sourcetext'] = filter_text_input( 'sourcetext', 'post', '', 1, 255 );
		if( ! empty( $rowcontent['sourcetext'] ) )
		{
			list( $rowcontent['source_id'] ) = $db->sql_fetchrow( $db->sql_query( "SELECT `sourceid` FROM `" . $db_config['prefix'] . "_" . $module_data . "_sources` WHERE `" . NV_LANG_DATA . "_title`=" . $db->dbescape( $rowcontent['sourcetext'] ) . "" ) );
		}
	}
	if( intval( $rowcontent['source_id'] ) > 0 ) $rowcontent['sourcetext'] = "";

	$publ_date = filter_text_input( 'publ_date', 'post', '' );
	$exp_date = filter_text_input( 'exp_date', 'post', '' );

	if( ! empty( $publ_date ) and ! preg_match( "/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/", $publ_date ) ) $publ_date = "";
	if( ! empty( $exp_date ) and ! preg_match( "/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/", $exp_date ) ) $exp_date = "";
	
	if( empty( $publ_date ) )
	{
		$rowcontent['publtime'] = NV_CURRENTTIME;
	}
	else
	{
		$phour = $nv_Request->get_int( 'phour', 'post', 0 );
		$pmin = $nv_Request->get_int( 'pmin', 'post', 0 );
		unset( $m );
		preg_match( "/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/", $publ_date, $m );
		$rowcontent['publtime'] = mktime( $phour, $pmin, 0, $m[2], $m[1], $m[3] );
	}

	if( empty( $exp_date ) )
	{
		$rowcontent['exptime'] = 0;
	}
	else
	{
		$ehour = $nv_Request->get_int( 'ehour', 'post', 0 );
		$emin = $nv_Request->get_int( 'emin', 'post', 0 );
		unset( $m );
		preg_match( "/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/", $exp_date, $m );
		$rowcontent['exptime'] = mktime( $ehour, $emin, 0, $m[2], $m[1], $m[3] );
	}

	$rowcontent['archive'] = $nv_Request->get_int( 'archive', 'post', 0 );
	
	if( $rowcontent['archive'] > 0 )
	{
		$rowcontent['archive'] = ( $rowcontent['exptime'] > NV_CURRENTTIME ) ? 1 : 2;
	}
	
	$rowcontent['title'] = filter_text_input( 'title', 'post', '', 1, 255 );
	$rowcontent['note'] = filter_text_input( 'note', 'post', '', 1 );
	$rowcontent['warranty'] = filter_text_input( 'warranty', 'post', '', 1 );
	$rowcontent['promotional'] = filter_text_input( 'promotional', 'post', '', 1 );

	$alias = filter_text_input( 'alias', 'post', '', 1, 255 );
	$rowcontent['alias'] = ( $alias == "" ) ? change_alias( $rowcontent['title'] ) : change_alias( $alias );

	$rowcontent['hometext'] = filter_text_input( 'hometext', 'post', '' );
	$rowcontent['product_code'] = filter_text_input( 'product_code', 'post', '', 1, 255 );
	$rowcontent['product_number'] = $nv_Request->get_int( 'product_number', 'post', 0 );
	$rowcontent['product_price'] = $nv_Request->get_int( 'product_price', 'post', 0 );
	$rowcontent['product_discounts'] = $nv_Request->get_int( 'product_discounts', 'post', 0 );
	$rowcontent['money_unit'] = $nv_Request->get_string( 'money_unit', 'post', "" );
	$rowcontent['product_unit'] = $nv_Request->get_int( 'product_unit', 'post', 0 );
	$rowcontent['homeimgfile'] = filter_text_input( 'homeimg', 'post', '' );
	$rowcontent['homeimgalt'] = filter_text_input( 'homeimgalt', 'post', '', 1 );
	$rowcontent['address'] = filter_text_input( 'address', 'post', '', 1 );

	$bodytext = $nv_Request->get_string( 'bodytext', 'post', '' );
	$rowcontent['bodytext'] = defined( 'NV_EDITOR' ) ? nv_nl2br( $bodytext, '' ) : nv_nl2br( nv_htmlspecialchars( strip_tags( $bodytext ) ), '<br />' );

	$rowcontent['copyright'] = ( int )$nv_Request->get_bool( 'copyright', 'post' );
	$rowcontent['inhome'] = ( int )$nv_Request->get_bool( 'inhome', 'post' );

	$rowcontent['allowed_comm'] = $nv_Request->get_int( 'allowed_comm', 'post', 0 );

	$rowcontent['allowed_rating'] = ( int )$nv_Request->get_bool( 'allowed_rating', 'post' );
	$rowcontent['allowed_send'] = ( int )$nv_Request->get_bool( 'allowed_send', 'post' );
	$rowcontent['allowed_print'] = ( int )$nv_Request->get_bool( 'allowed_print', 'post' );
	$rowcontent['allowed_save'] = ( int )$nv_Request->get_bool( 'allowed_save', 'post' );
	$rowcontent['keywords'] = filter_text_input( 'keywords', 'post', '', 1 );
		
	// Xu ly anh minh hoa khac
	$otherimage = $nv_Request->get_typed_array( 'otherimage', 'post', 'string' );
	$array_otherimage = array();
	foreach( $otherimage as $otherimage_i )
	{
		if( ! nv_is_url( $otherimage_i ) and file_exists( NV_DOCUMENT_ROOT . $otherimage_i ) )
		{
			$lu = strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" );
			$otherimage_i = substr( $otherimage_i, $lu );
		}
		elseif( ! nv_is_url( $otherimage_i ) )
		{
			$otherimage_i = "";
		}
		if( ! empty( $otherimage_i ) )
		{
			$array_otherimage[] = $otherimage_i;
		}
	}
	$rowcontent['otherimage'] = implode( "|", $array_otherimage );

	// Kiem tra ma san pham trung
	$error_product_code = false;
	if( ! empty( $rowcontent['product_code'] ) )
	{
		if( $rowcontent['id'] == 0 and $db->sql_numrows( $db->sql_query( "SELECT `id` FROM `" . $db_config['prefix'] . "_" . $module_data . "_rows` WHERE `product_code`=" . $db->dbescape( $rowcontent['product_code'] ) ) ) )
		{
			$error_product_code = true;
		}
		elseif( $db->sql_numrows( $db->sql_query( "SELECT `id` FROM `" . $db_config['prefix'] . "_" . $module_data . "_rows` WHERE `product_code`=" . $db->dbescape( $rowcontent['product_code'] ) . " AND `id`!=" . $rowcontent['id'] ) ) )
		{
			$error_product_code = true;
		}
	}
	
	if( empty( $rowcontent['title'] ) )
	{
		$error = $lang_module['error_title'];
	}
	elseif( $error_product_code )
	{
		$error = $lang_module['error_product_code'];
	}
	elseif( empty( $rowcontent['listcatid'] ) )
	{
		$error = $lang_module['error_cat'];
	}
	elseif( trim( strip_tags( $rowcontent['hometext'] ) ) == "" )
	{
		$error = $lang_module['error_hometext'];
	}
	elseif( trim( strip_tags( $rowcontent['bodytext'] ) ) == "" )
	{
		$error = $lang_module['error_bodytext'];
	}
	elseif( $rowcontent['product_unit'] == 0 )
	{
		$error = $lang_module['error_product_unit'];
	}
	elseif( $rowcontent['product_price'] <= 0 )
	{
		$error = $lang_module['error_product_price'];
	}
	elseif( $rowcontent['product_discounts'] < 0 )
	{
		$error = $lang_module['error_product_discounts'];
	}
	else
	{
		// Xu ly hang san xuat moi
		if( ! empty( $rowcontent['sourcetext'] ) )
		{
			list( $weight ) = $db->sql_fetchrow( $db->sql_query( "SELECT max(`weight`) FROM `" . $db_config['prefix'] . "_" . $module_data . "_sources`" ) );
			$weight = intval( $weight ) + 1;
			$datasource['title'] = $rowcontent['sourcetext'];
			$field_lang_source = nv_file_table( $db_config['prefix'] . "_" . $module_data . "_sources" );
			$listfield = "";
			$listvalue = "";
			foreach( $field_lang_source as $field_lang_i )
			{
				list( $flang, $fname ) = $field_lang_i;
				$listfield .= ", `" . $flang . "_" . $fname . "`";
				if( $flang == NV_LANG_DATA )
				{
					$listvalue .= ", " . $db->dbescape( $datasource[$fname] );
				}
				else
				{
					$listvalue .= ", " . $db->dbescape( $datasource[$fname] );
				}
			}
			$sql = "INSERT INTO `" . $db_config['prefix'] . "_" . $module_data . "_sources` (`sourceid`, `link`, `logo`, `weight`, `add_time`, `edit_time` " . $listfield . ") VALUES (NULL, '', '', " . $db->dbescape( $weight ) . ", UNIX_TIMESTAMP( ), UNIX_TIMESTAMP( ) " . $listvalue . ")";
			$rowcontent['source_id'] = $db->sql_query_insert_id( $sql );
		}
		
		// Xu ly tu khoa
		if( $rowcontent['keywords'] == "" )
		{
			if( $rowcontent['hometext'] != "" )
			{
				$rowcontent['keywords'] = nv_get_keywords( $rowcontent['hometext'] );
			}
			else
			{
				$rowcontent['keywords'] = nv_get_keywords( $rowcontent['bodytext'] );
			}
		}
		$rowcontent['status'] = ( $nv_Request->isset_request( 'status1', 'post' ) ) ? 1 : 0;

		// Xu ly anh minh hoa
		$rowcontent['homeimgthumb'] = "";
		if( ! nv_is_url( $rowcontent['homeimgfile'] ) and file_exists( NV_DOCUMENT_ROOT . $rowcontent['homeimgfile'] ) )
		{
			$lu = strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" );
			$rowcontent['homeimgfile'] = substr( $rowcontent['homeimgfile'], $lu );
		}
		elseif( ! nv_is_url( $rowcontent['homeimgfile'] ) )
		{
			$rowcontent['homeimgfile'] = "";
		}
		
		$check_thumb = false; // Kiem tra lai anh thumb
		
		if( $rowcontent['id'] > 0 )
		{
			list( $homeimgfile, $homeimgthumb ) = $db->sql_fetchrow( $db->sql_query( "SELECT `homeimgfile`, `homeimgthumb` FROM `" . $db_config['prefix'] . "_" . $module_data . "_rows` WHERE `id`=" . $rowcontent['id'] ) );
			
			if( $rowcontent['homeimgfile'] != $homeimgfile )
			{
				$check_thumb = true;
				if( $homeimgthumb != "" and $homeimgthumb != "|" )
				{
					$rowcontent['homeimgthumb'] = "";
					$homeimgthumb_arr = explode( "|", $homeimgthumb );
					foreach( $homeimgthumb_arr as $homeimgthumb_i )
					{
						if( file_exists( NV_UPLOADS_REAL_DIR . "/" . $module_name . "/" . $homeimgthumb_i ) )
						{
							nv_deletefile( NV_UPLOADS_REAL_DIR . "/" . $module_name . "/" . $homeimgthumb_i );
						}
					}
				}
			}
			else
			{
				$rowcontent['homeimgthumb'] = $homeimgthumb;
			}
		}
		elseif( ! empty( $rowcontent['homeimgfile'] ) )
		{
			$check_thumb = true;
		}
		
		if( nv_is_url( $homeimgfile ) )
		{
			$homeimgfile = $rowcontent['homeimgfile'];
		}
		elseif( $rowcontent['homeimgfile'] != "" and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $rowcontent['homeimgfile'] ) )
		{
			$homeimgfile = NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $rowcontent['homeimgfile'];
		
		}
		else
		{
			$homeimgfile = "";
		}
		
		if( $check_thumb and ! empty( $homeimgfile ) )
		{
			require_once ( NV_ROOTDIR . "/includes/class/image.class.php" );

			$basename = basename( $homeimgfile );
			$image = new image( $homeimgfile, NV_MAX_WIDTH, NV_MAX_HEIGHT );

			$thumb_basename = $basename;
			$i = 1;
			while( file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/thumb/' . $thumb_basename ) )
			{
				$thumb_basename = preg_replace( '/(.*)(\.[a-zA-Z]+)$/', '\1_' . $i . '\2', $basename );
				$i++;
			}

			$image->resizeXY( $pro_config['homewidth'], $pro_config['homeheight'] );
			$image->save( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/thumb', $thumb_basename );
			$image_info = $image->create_Image_info;
			$thumb_name = str_replace( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/', '', $image_info['src'] );

			$block_basename = $basename;
			$i = 1;
			while( file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/block/' . $block_basename ) )
			{
				$block_basename = preg_replace( '/(.*)(\.[a-zA-Z]+)$/', '\1_' . $i . '\2', $basename );
				$i++;
			}
			$image->resizeXY( $pro_config['blockwidth'], $pro_config['blockheight'] );
			$image->save( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/block', $block_basename );
			$image_info = $image->create_Image_info;
			$block_name = str_replace( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/', '', $image_info['src'] );

			$image->close();
			$rowcontent['homeimgthumb'] = $thumb_name . "|" . $block_name;
		}
		
		$listfield = "";
		$listvalue = "";
		foreach( $field_lang as $field_lang_i )
		{
			list( $flang, $fname ) = $field_lang_i;
			$listfield .= ", `" . $flang . "_" . $fname . "`";
			if( $flang == NV_LANG_DATA )
			{
				$listvalue .= ", " . $db->dbescape( $rowcontent[$fname] );
			}
			else
			{
				$listvalue .= ", " . $db->dbescape( $rowcontent[$fname] );
			}
		}
		
		if( $rowcontent['id'] == 0 )
		{
			$rowcontent['publtime'] = ( $rowcontent['publtime'] > NV_CURRENTTIME ) ? $rowcontent['publtime'] : NV_CURRENTTIME;
			if( $rowcontent['status'] == 1 and $rowcontent['publtime'] > NV_CURRENTTIME )
			{
				$rowcontent['status'] = 2;
			}
			
			$sql = "INSERT INTO `" . $db_config['prefix'] . "_" . $module_data . "_rows` (`id`, `listcatid`, `group_id`, `user_id`, `source_id`, `addtime`, `edittime`, `status`, `publtime`, `exptime`, `archive`, `product_code`, `product_number`, `product_price`,`product_discounts`, `money_unit` , `product_unit`, `homeimgfile`, `homeimgthumb`, `homeimgalt`,`otherimage`,`imgposition`, `copyright`, `inhome`, `allowed_comm`, `allowed_rating`, `ratingdetail`, `allowed_send`, `allowed_print`, `allowed_save`, `hitstotal`, `hitscm`, `hitslm`, `showprice` " . $listfield . ") 
                VALUES ( NULL , " . $db->dbescape_string( $rowcontent['listcatid'] ) . ", 
                " . $db->dbescape_string( $rowcontent['group_id'] ) . ", 
                " . intval( $rowcontent['user_id'] ) . ",
                " . intval( $rowcontent['source_id'] ) . ", 
                " . intval( $rowcontent['addtime'] ) . ", 
                " . intval( $rowcontent['edittime'] ) . ", 
                " . intval( $rowcontent['status'] ) . ", 
                " . intval( $rowcontent['publtime'] ) . ",  
                " . intval( $rowcontent['exptime'] ) . ",  
                " . intval( $rowcontent['archive'] ) . ",  
                " . $db->dbescape_string( $rowcontent['product_code'] ) . ",  
                " . intval( $rowcontent['product_number'] ) . ",  
                " . intval( $rowcontent['product_price'] ) . ",  
                " . intval( $rowcontent['product_discounts'] ) . ", 
                " . $db->dbescape_string( $rowcontent['money_unit'] ) . ",
                " . intval( $rowcontent['product_unit'] ) . ", 
                " . $db->dbescape_string( $rowcontent['homeimgfile'] ) . ", 
                " . $db->dbescape_string( $rowcontent['homeimgthumb'] ) . ", 
                " . $db->dbescape_string( $rowcontent['homeimgalt'] ) . ", 
                " . $db->dbescape_string( $rowcontent['otherimage'] ) . ", 
                " . intval( $rowcontent['imgposition'] ) . ", 
                " . intval( $rowcontent['copyright'] ) . ", 
                " . intval( $rowcontent['inhome'] ) . ", 
                " . intval( $rowcontent['allowed_comm'] ) . ", 
                " . intval( $rowcontent['allowed_rating'] ) . ", 
                " . $db->dbescape_string( $rowcontent['ratingdetail'] ) . ",
                " . intval( $rowcontent['allowed_send'] ) . ", 
                " . intval( $rowcontent['allowed_print'] ) . ", 
                " . intval( $rowcontent['allowed_save'] ) . ", 
                " . intval( $rowcontent['hitstotal'] ) . ", 
                " . intval( $rowcontent['hitscm'] ) . ", 
                " . intval( $rowcontent['hitslm'] ) . ",
                " . intval( $rowcontent['showprice'] ) . "
				" . $listvalue . "
			)";
			
			$rowcontent['id'] = $db->sql_query_insert_id( $sql );
			
			if( $rowcontent['id'] > 0 )
			{
				$auto_product_code = "";
				if( ! empty( $pro_config['format_code_id'] ) and empty( $rowcontent['product_code'] ) )
				{
					$i = 1;
					$auto_product_code = vsprintf( $pro_config['format_code_id'], $rowcontent['id'] );
					while( $db->sql_numrows( $db->sql_query( "SELECT `id` FROM `" . $db_config['prefix'] . "_" . $module_data . "_rows` WHERE `product_code`=" . $db->dbescape( $auto_product_code ) ) ) )
					{
						$auto_product_code = vsprintf( $pro_config['format_code_id'], ( $rowcontent['id'] + $i ++ ) );
					}
					
					$db->sql_query( "UPDATE `" . $db_config['prefix'] . "_" . $module_data . "_rows` SET `product_code`=" . $db->dbescape( $auto_product_code ) . " WHERE `id`=" . $rowcontent['id'] );
				}
				
				nv_fix_group_count( $rowcontent['group_id'] );
				nv_insert_logs( NV_LANG_DATA, $module_name, 'Add A Product', "ID: " . $rowcontent['id'], $admin_info['userid'] );
			}
			else
			{
				$error = $lang_module['errorsave'];
			}
			$db->sql_freeresult();
		}
		else
		{
			$rowcontent_old = $db->sql_fetchrow( $db->sql_query( "SELECT * FROM `" . $db_config['prefix'] . "_" . $module_data . "_rows` where `id`=" . $rowcontent['id'] ) );
			
			$rowcontent['user_id'] = $rowcontent_old['user_id'];
			
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
			
			$sql = "UPDATE `" . $db_config['prefix'] . "_" . $module_data . "_rows` SET 
			   `listcatid`=" . $db->dbescape_string( $rowcontent['listcatid'] ) . ", 
			   `group_id`=" . $db->dbescape_string( $rowcontent['group_id'] ) . ", 
			   `user_id`=" . intval( $rowcontent['user_id'] ) . ",
			   `source_id`=" . intval( $rowcontent['source_id'] ) . ", 
			   `status`=" . intval( $rowcontent['status'] ) . ", 
			   `publtime`=" . intval( $rowcontent['publtime'] ) . ", 
			   `exptime`=" . intval( $rowcontent['exptime'] ) . ", 
			   `edittime`=UNIX_TIMESTAMP( ) ,
			   `archive`=" . intval( $rowcontent['archive'] ) . ", 
			   `product_code` = " . $db->dbescape_string( $rowcontent['product_code'] ) . ",  
			   `product_number` = `product_number` + " . intval( $rowcontent['product_number'] ) . ",  
			   `product_price` = " . intval( $rowcontent['product_price'] ) . ",  
			   `product_discounts` = " . intval( $rowcontent['product_discounts'] ) . ",
			   `money_unit` = " . $db->dbescape_string( $rowcontent['money_unit'] ) . ", 
			   `product_unit` = " . intval( $rowcontent['product_unit'] ) . ", 
			   `homeimgfile`=" . $db->dbescape_string( $rowcontent['homeimgfile'] ) . ",
			   `homeimgalt`=" . $db->dbescape_string( $rowcontent['homeimgalt'] ) . ",
			   `otherimage`=" . $db->dbescape_string( $rowcontent['otherimage'] ) . ",
			   `homeimgthumb`=" . $db->dbescape_string( $rowcontent['homeimgthumb'] ) . ",
			   `imgposition`=" . intval( $rowcontent['imgposition'] ) . ",
			   `copyright`=" . intval( $rowcontent['copyright'] ) . ", 
			   `inhome`=" . intval( $rowcontent['inhome'] ) . ", 
			   `allowed_comm`=" . intval( $rowcontent['allowed_comm'] ) . ", 
			   `allowed_rating`=" . intval( $rowcontent['allowed_rating'] ) . ", 
			   `allowed_send`=" . intval( $rowcontent['allowed_send'] ) . ", 
			   `allowed_print`=" . intval( $rowcontent['allowed_print'] ) . ", 
			   `allowed_save`=" . intval( $rowcontent['allowed_save'] ) . ", 
			   `showprice` = " . intval( $rowcontent['showprice'] ) . ", 
			   `" . NV_LANG_DATA . "_title`=" . $db->dbescape_string( $rowcontent['title'] ) . ", 
			   `" . NV_LANG_DATA . "_alias`=" . $db->dbescape_string( $rowcontent['alias'] ) . ", 
			   `" . NV_LANG_DATA . "_hometext`=" . $db->dbescape_string( $rowcontent['hometext'] ) . ",
			   `" . NV_LANG_DATA . "_bodytext`=" . $db->dbescape_string( $rowcontent['bodytext'] ) . ", 
			   `" . NV_LANG_DATA . "_address`=" . $db->dbescape_string( $rowcontent['address'] ) . ", 
			   `" . NV_LANG_DATA . "_note`=" . $db->dbescape_string( $rowcontent['note'] ) . ",
			   `" . NV_LANG_DATA . "_keywords`=" . $db->dbescape_string( $rowcontent['keywords'] ) . ",
			   `" . NV_LANG_DATA . "_promotional`=" . $db->dbescape_string( $rowcontent['promotional'] ) . ",
			   `" . NV_LANG_DATA . "_warranty`=" . $db->dbescape_string( $rowcontent['warranty'] ) . "
			WHERE `id` =" . $rowcontent['id'];
			
			$db->sql_query( $sql );

			if( $db->sql_affectedrows() > 0 )
			{
				nv_fix_group_count( $rowcontent['group_id'] );
				if( $rowcontent_old['group_id'] != $rowcontent['group_id'] ) nv_fix_group_count( $rowcontent_old['group_id'] );
				nv_insert_logs( NV_LANG_DATA, $module_name, 'Edit A Product', "ID: " . $rowcontent['id'], $admin_info['userid'] );
			}
			else
			{
				$error = $lang_module['errorsave'];
			}
			$db->sql_freeresult();
		}
		
		nv_set_status_module();
		
		if( $error == "" )
		{
			foreach( $id_block_content as $bid_i )
			{
				$db->sql_query( "INSERT INTO `" . $db_config['prefix'] . "_" . $module_data . "_block` (`bid`, `id`, `weight`) VALUES ('" . $bid_i . "', '" . $rowcontent['id'] . "', '0')" );
			}
			
			$id_block_content[] = 0;
			
			$db->sql_query( "DELETE FROM `" . $db_config['prefix'] . "_" . $module_data . "_block` WHERE `id` = " . $rowcontent['id'] . " AND `bid` NOT IN (" . implode( ",", $id_block_content ) . ")" );
			
			foreach( $array_block_cat_module as $bid_i )
			{
				nv_news_fix_block( $bid_i );
			}
			
			nv_del_moduleCache( $module_name );
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=items" );
			die();
		}
		
		nv_del_moduleCache( $module_name );
	}

}
elseif( $rowcontent['id'] > 0 )
{
	$rowdata = $db->sql_fetchrow( $db->sql_query( "SELECT * FROM `" . $db_config['prefix'] . "_" . $module_data . "_rows` where `id`=" . $rowcontent['id'] ) );
	$rowcontent = array(
		"id" => $rowdata['id'],
		"listcatid" => $rowdata['listcatid'],
		"group_id" => $rowdata['group_id'],
		"user_id" => $rowdata['user_id'],
		"source_id" => $rowdata['source_id'],
		"addtime" => $rowdata['addtime'],
		"edittime" => $rowdata['edittime'],
		"status" => $rowdata['status'],
		"publtime" => $rowdata['publtime'],
		"exptime" => $rowdata['exptime'],
		"archive" => $rowdata['archive'],
		"product_code" => $rowdata['product_code'],
		"product_number" => $rowdata['product_number'],
		"product_price" => $rowdata['product_price'],
		"product_discounts" => $rowdata['product_discounts'],
		"money_unit" => $rowdata['money_unit'],
		"product_unit" => $rowdata['product_unit'],
		"homeimgfile" => $rowdata['homeimgfile'],
		"homeimgthumb" => $rowdata['homeimgthumb'],
		"homeimgalt" => $rowdata['homeimgalt'],
		"otherimage" => $rowdata['otherimage'],
		"imgposition" => $rowdata['imgposition'],
		"copyright" => $rowdata['copyright'],
		"inhome" => $rowdata['inhome'],
		"allowed_comm" => $rowdata['allowed_comm'],
		"allowed_rating" => $rowdata['allowed_rating'],
		"ratingdetail" => $rowdata['ratingdetail'],
		"allowed_send" => $rowdata['allowed_send'],
		"allowed_print" => $rowdata['allowed_print'],
		"allowed_save" => $rowdata['allowed_save'],
		"hitstotal" => $rowdata['hitstotal'],
		"hitscm" => $rowdata['hitscm'],
		"hitslm" => $rowdata['hitslm'],
		"showprice" => $rowdata['showprice'],
		"title" => $rowdata[NV_LANG_DATA . '_title'],
		"alias" => $rowdata[NV_LANG_DATA . '_alias'],
		"hometext" => $rowdata[NV_LANG_DATA . '_hometext'],
		"bodytext" => $rowdata[NV_LANG_DATA . '_bodytext'],
		"note" => $rowdata[NV_LANG_DATA . '_note'],
		"keywords" => $rowdata[NV_LANG_DATA . '_keywords'],
		"address" => $rowdata[NV_LANG_DATA . '_address'],
		"promotional" => $rowdata[NV_LANG_DATA . '_promotional'],
		"warranty" => $rowdata[NV_LANG_DATA . '_warranty'],
	);
	
	$page_title = $lang_module['content_edit'];
	
	$rowcontent['sourcetext'] = "";
	$rowcontent['topictext'] = "";

	$id_block_content = array();
	$sql = "SELECT bid FROM `" . $db_config['prefix'] . "_" . $module_data . "_block` where `id`='" . $rowcontent['id'] . "'";
	$result = $db->sql_query( $sql );
	
	while( list( $bid_i ) = $db->sql_fetchrow( $result ) )
	{
		$id_block_content[] = $bid_i;
	}
}

if( ! empty( $rowcontent['bodytext'] ) ) $rowcontent['bodytext'] = nv_htmlspecialchars( $rowcontent['bodytext'] );

if( ! empty( $rowcontent['homeimgfile'] ) and file_exists( NV_UPLOADS_REAL_DIR . "/" . $module_name . "/" . $rowcontent['homeimgfile'] ) )
{
	$rowcontent['homeimgfile'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $rowcontent['homeimgfile'];
}

$sql = "SELECT `sourceid`, `" . NV_LANG_DATA . "_title` FROM `" . $db_config['prefix'] . "_" . $module_data . "_sources` ORDER BY `weight` ASC";
$result = $db->sql_query( $sql );
$array_source_module = array();
$array_source_module[0] = $lang_module['sources_sl'];
while( list( $sourceid_i, $title_i ) = $db->sql_fetchrow( $result ) )
{
	$array_source_module[$sourceid_i] = $title_i;
}

$tdate = date( "H|i", $rowcontent['publtime'] );
$publ_date = date( "d/m/Y", $rowcontent['publtime'] );
list( $phour, $pmin ) = explode( "|", $tdate );
if( $rowcontent['exptime'] == 0 )
{
	$emin = $ehour = 0;
	$exp_date = "";
}
else
{
	$exp_date = date( "d/m/Y", $rowcontent['exptime'] );
	$tdate = date( "H|i", $rowcontent['exptime'] );
	list( $ehour, $emin ) = explode( "|", $tdate );
}

if( ! empty( $rowcontent['otherimage'] ) )
{
	$otherimage = explode( "|", $rowcontent['otherimage'] );
}
else
{
	$otherimage = array();
}

$xtpl = new XTemplate( "content.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'rowcontent', $rowcontent );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'module_name', $module_name );
$xtpl->assign( 'CURRENT', NV_UPLOADS_DIR . '/' . $module_name . '/' . date( "Y_m" ) );

if( $error != "" )
{
	$xtpl->assign( 'error', $error );
	$xtpl->parse( 'main.error' );
}

if( $rowcontent['status'] == 1 )
{
	$xtpl->parse( 'main.status' );
}
else
{
	$xtpl->parse( 'main.status0' );
}

// Other image
$items = 0;
if( ! empty( $otherimage ) )
{
	foreach( $otherimage as $otherimage_i )
	{
		if( ! empty( $otherimage_i ) and file_exists( NV_UPLOADS_REAL_DIR . "/" . $module_name . "/" . $otherimage_i ) )
		{
			$otherimage_i = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $otherimage_i;
		}
		$data_otherimage_i = array( "id" => $items, "value" => $otherimage_i );
		$xtpl->assign( 'DATAOTHERIMAGE', $data_otherimage_i );
		$xtpl->parse( 'main.otherimage' );
		$items++;
	}
}
$xtpl->assign( 'FILE_ITEMS', $items );

// List catalogs
$sql = "SELECT `catid`, `" . NV_LANG_DATA . "_title`, `lev`, `numsubcat` FROM `" . $db_config['prefix'] . "_" . $module_data . "_catalogs` ORDER BY `order` ASC";
$result_cat = $db->sql_query( $sql );
if( $db->sql_numrows( $result_cat ) == 0 )
{
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat" );
	die();
}

while( list( $catid_i, $title_i, $lev_i, $numsubcat_i ) = $db->sql_fetchrow( $result_cat ) )
{
	if( $rowcontent['listcatid'] == 0 ) $rowcontent['listcatid'] = $catid_i;
	
	$xtitle_i = "";
	if( $lev_i > 0 )
	{
		for( $i = 1; $i <= $lev_i; $i++ )
		{
			$xtitle_i .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		}
	}
	
	$select = ( $catid_i == $rowcontent['listcatid'] ) ? " selected=\"selected\"" : "";
	
	$xtpl->assign( 'xtitle_i', $xtitle_i );
	$xtpl->assign( 'title_i', $title_i );
	$xtpl->assign( 'catid_i', $catid_i );
	$xtpl->assign( 'select', $select );
	
	$xtpl->parse( 'main.rowscat' );
}

// List group
if( ! empty( $rowcontent['group_id'] ) )
{
	$array_groupid_in_row = explode( ",", $rowcontent['group_id'] );
}
else
{
	$array_groupid_in_row = array();
}

$inrow = nv_base64_encode( serialize( $array_groupid_in_row ) );
$xtpl->assign( 'url_load', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=getgroup&cid=" . $rowcontent['listcatid'] . "&inrow=" . $inrow );
$xtpl->assign( 'inrow', $inrow );
$xtpl->parse( 'main.listgroup' );

// Time update
$xtpl->assign( 'publ_date', $publ_date );
$select = "";
for( $i = 0; $i <= 23; $i++ )
{
	$select .= "<option value=\"" . $i . "\"" . ( ( $i == $phour ) ? " selected=\"selected\"" : "" ) . ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
}
$xtpl->assign( 'phour', $select );

$select = "";
for( $i = 0; $i < 60; $i++ )
{
	$select .= "<option value=\"" . $i . "\"" . ( ( $i == $pmin ) ? " selected=\"selected\"" : "" ) . ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
}
$xtpl->assign( 'pmin', $select );

// Time exp
$xtpl->assign( 'exp_date', $exp_date );
$select = "";
for( $i = 0; $i <= 23; $i++ )
{
	$select .= "<option value=\"" . $i . "\"" . ( ( $i == $ehour ) ? " selected=\"selected\"" : "" ) . ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
}
$xtpl->assign( 'ehour', $select );

$select = "";
for( $i = 0; $i < 60; $i++ )
{
	$select .= "<option value=\"" . $i . "\"" . ( ( $i == $emin ) ? " selected=\"selected\"" : "" ) . ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
}
$xtpl->assign( 'emin', $select );

// Allowed
$select = "";
while( list( $commid_i, $commid_title_i ) = each( $array_allowed_comm ) )
{
	$comm_sl = ( $commid_i == $rowcontent['allowed_comm'] ) ? " selected=\"selected\"" : "";
	$select .= "<option value=\"" . $commid_i . "\" " . $comm_sl . ">" . $commid_title_i . "</option>\n";
}
$xtpl->assign( 'allowed_comm', $select );

// Source
$select = "";
while( list( $sourceid_i, $source_title_i ) = each( $array_source_module ) )
{
	$source_sl = ( $sourceid_i == $rowcontent['source_id'] ) ? " selected=\"selected\"" : "";
	$select .= "<option value=\"" . $sourceid_i . "\" " . $source_sl . ">" . $source_title_i . "</option>\n";
}
$xtpl->assign( 'sourceid', $select );

if( defined( 'NV_EDITOR' ) and function_exists( 'nv_aleditor' ) )
{
	$edits = nv_aleditor( 'bodytext', '100%', '300px', $rowcontent['bodytext'] );
}
else
{
	$edits = "<textarea style=\"width: 100%\" name=\"bodytext\" id=\"bodytext\" cols=\"20\" rows=\"15\">" . $rowcontent['bodytext'] . "</textarea>";
}

$shtm = "";
if( count( $array_block_cat_module ) > 0 )
{
	foreach( $array_block_cat_module as $bid_i => $bid_title )
	{
		$ch = in_array( $bid_i, $id_block_content ) ? " checked=\"checked\"" : "";
		$shtm .= "<input class=\"news_checkbox\" type=\"checkbox\" name=\"bids[]\" value=\"" . $bid_i . "\"" . $ch . ">" . $bid_title . "<br />\n";
	}
	$xtpl->assign( 'row_block', $shtm );
	$xtpl->parse( 'main.block_cat' );
}

// List pro_unit
$sql = "SELECT `id`, `" . NV_LANG_DATA . "_title` FROM `" . $db_config['prefix'] . "_" . $module_data . "_units`";
$result_unit = $db->sql_query( $sql );
if( $db->sql_numrows( $result_unit ) == 0 )
{
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=prounit" );
	die();
}

while( list( $unitid_i, $title_i ) = $db->sql_fetchrow( $result_unit ) )
{
	$xtpl->assign( 'utitle', $title_i );
	$xtpl->assign( 'uid', $unitid_i );
	$uch = ( $rowcontent['product_unit'] == $unitid_i ) ? "selected=\"selected\"" : "";
	$xtpl->assign( 'uch', $uch );
	$xtpl->parse( 'main.rowunit' );
}


$archive_checked = ( $rowcontent['archive'] ) ? "  checked=\"checked\"" : "";
$xtpl->assign( 'archive_checked', $archive_checked );

$inhome_checked = ( $rowcontent['inhome'] ) ? "  checked=\"checked\"" : "";
$xtpl->assign( 'inhome_checked', $inhome_checked );

$allowed_rating_checked = ( $rowcontent['allowed_rating'] ) ? "  checked=\"checked\"" : "";
$xtpl->assign( 'allowed_rating_checked', $allowed_rating_checked );

$allowed_send_checked = ( $rowcontent['allowed_send'] ) ? "  checked=\"checked\"" : "";
$xtpl->assign( 'allowed_send_checked', $allowed_send_checked );

$allowed_print_checked = ( $rowcontent['allowed_print'] ) ? "  checked=\"checked\"" : "";
$xtpl->assign( 'allowed_print_checked', $allowed_print_checked );

$allowed_save_checked = ( $rowcontent['allowed_save'] ) ? "  checked=\"checked\"" : "";
$xtpl->assign( 'allowed_save_checked', $allowed_save_checked );

$showprice_checked = ( $rowcontent['showprice'] ) ? "  checked=\"checked\"" : "";
$xtpl->assign( 'ck_showprice', $showprice_checked );

if( ! empty( $money_config ) )
{
	foreach( $money_config as $code => $info )
	{
		$info['select'] = ( $rowcontent['money_unit'] == $code ) ? "selected=\"selected\"" : "";
		$xtpl->assign( 'MON', $info );
		$xtpl->parse( 'main.money_unit' );
	}
}

$xtpl->assign( 'edit_bodytext', $edits );

if( $rowcontent['id'] > 0 )
{
	$xtpl->parse( 'main.edit' );
}
else
{
	$xtpl->parse( 'main.add' );
}

if( empty( $rowcontent['alias'] ) )
{
	$xtpl->parse( 'main.getalias' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?> 