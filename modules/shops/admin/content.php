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

if( empty( $global_array_cat ) )
{
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cat' );
	die();
}

$table_name = $db_config['prefix'] . '_' . $module_data . '_rows';
$month_dir_module = nv_mkdir( NV_UPLOADS_REAL_DIR . '/' . $module_name, date( 'Y_m' ), true );
$array_block_cat_module = array();
$id_block_content = array();
$array_custom = array();
$array_custom_lang = array();

$sql = 'SELECT bid, adddefault, ' . NV_LANG_DATA . '_title FROM ' . $db_config['prefix'] . '_' . $module_data . '_block_cat ORDER BY weight ASC';
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

$stmt = $db->prepare( 'SELECT numsubcat FROM ' . $db_config['prefix'] . '_' . $module_data . '_catalogs WHERE catid= :parentid' );
$stmt->bindParam( ':parentid', $parentid, PDO::PARAM_STR );
$stmt->execute();
$subcatid = $stmt->fetchColumn();
if( $subcatid > 0 )
{
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name );
	die();
}

$rowcontent = array(
	'id' => 0,
	'listcatid' => $catid,
	'user_id' => $admin_info['admin_id'],
	'addtime' => NV_CURRENTTIME,
	'edittime' => NV_CURRENTTIME,
	'status' => 0,
	'publtime' => NV_CURRENTTIME,
	'exptime' => 0,
	'archive' => 1,
	'product_code' => '',
	'product_number' => 1,
	'product_price' => 0,
	'discount_id' => 0,
	'money_unit' => $pro_config['money_unit'],
	'product_unit' => '',
	'homeimgfile' => '',
	'homeimgthumb' => '',
	'homeimgalt' => '',
	'imgposition' => 0,
	'copyright' => 0,
	'inhome' => 1,
	'allowed_comm' => $module_config[$module_name]['setcomm'],
	'allowed_rating' => 1,
	'ratingdetail' => '0',
	'allowed_send' => 1,
	'allowed_print' => 1,
	'allowed_save' => 1,
	'hitstotal' => 0,
	'hitscm' => 0,
	'hitslm' => 0,
	'showprice' => 1,
	'com_id' => 0,
	'title' => '',
	'alias' => '',
	'hometext' => '',
	'bodytext' => '',
	'note' => '',
	'keywords' => '',
	'keywords_old' => '',
	'warranty' => '',
	'promotional' => '',
);

$page_title = $lang_module['content_add'];
$error = '';
$groups_list = nv_groups_list();

$rowcontent['id'] = $nv_Request->get_int( 'id', 'get,post', 0 );

$group_id_old = array();
if( $rowcontent['id'] > 0 )
{
	$group_id_old = getGroupID( $rowcontent['id'] );
	
	$array_keywords_old = array();
	$_query = $db->query( 'SELECT tid, ' . NV_LANG_DATA . '_keyword FROM ' . $db_config['prefix'] . '_' . $module_data . '_tags_id WHERE id=' . $rowcontent['id'] . ' ORDER BY ' . NV_LANG_DATA . '_keyword ASC' );
	while( $row = $_query->fetch() )
	{
		$array_keywords_old[$row['tid']] = $row[NV_LANG_DATA . '_keyword'];
	}
	$rowcontent['keywords'] = implode( ', ', $array_keywords_old );
	$rowcontent['keywords_old'] = $rowcontent['keywords'];
}

if( $nv_Request->get_int( 'save', 'post' ) == 1 )
{
	$field_lang = nv_file_table( $table_name );
	$id_block_content = array_unique( $nv_Request->get_typed_array( 'bids', 'post', 'int', array() ) );

	$rowcontent['listcatid'] = $nv_Request->get_int( 'catid', 'post', 0 );

	$rowcontent['group_id'] = array_unique( $nv_Request->get_typed_array( 'groupids', 'post', 'int', array() ) );

	$rowcontent['showprice'] = $nv_Request->get_int( 'showprice', 'post', 0 );
	$rowcontent['showorder'] = $nv_Request->get_int( 'showorder', 'post', 0 );

	$publ_date = $nv_Request->get_title( 'publ_date', 'post', '' );
	$exp_date = $nv_Request->get_title( 'exp_date', 'post', '' );

	if( ! empty( $publ_date ) and ! preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $publ_date ) ) $publ_date = '';
	if( ! empty( $exp_date ) and ! preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $exp_date ) ) $exp_date = '';

	if( empty( $publ_date ) )
	{
		$rowcontent['publtime'] = NV_CURRENTTIME;
	}
	else
	{
		$phour = $nv_Request->get_int( 'phour', 'post', 0 );
		$pmin = $nv_Request->get_int( 'pmin', 'post', 0 );
		unset( $m );
		preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $publ_date, $m );
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
		preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $exp_date, $m );
		$rowcontent['exptime'] = mktime( $ehour, $emin, 0, $m[2], $m[1], $m[3] );
	}

	$rowcontent['archive'] = $nv_Request->get_int( 'archive', 'post', 0 );

	if( $rowcontent['archive'] > 0 )
	{
		$rowcontent['archive'] = ( $rowcontent['exptime'] > NV_CURRENTTIME ) ? 1 : 2;
	}

	$rowcontent['title'] = nv_substr( $nv_Request->get_title( 'title', 'post', '', 1 ), 0, 255 );
	$rowcontent['note'] = $nv_Request->get_title( 'note', 'post', '', 1 );
	$rowcontent['warranty'] = $nv_Request->get_title( 'warranty', 'post', '', 1 );
	$rowcontent['promotional'] = $nv_Request->get_title( 'promotional', 'post', '', 1 );

	$alias = nv_substr( $nv_Request->get_title( 'alias', 'post', '', 1 ), 0, 255 );
	$rowcontent['alias'] = ( $alias == '' ) ? change_alias( $rowcontent['title'] ) : change_alias( $alias );

	$rowcontent['hometext'] = $nv_Request->get_textarea( 'hometext', 'post', '', 'br', 1 );
	$rowcontent['product_code'] = nv_substr( $nv_Request->get_title( 'product_code', 'post', '', 1 ), 0, 255 );
	$rowcontent['product_number'] = $nv_Request->get_int( 'product_number', 'post', 0 );
	$rowcontent['product_price'] = $nv_Request->get_string( 'product_price', 'post', '' );
	$rowcontent['product_price'] = floatval( preg_replace( '/[^0-9\.]/', '', $rowcontent['product_price'] ) );

	$rowcontent['discount_id'] = $nv_Request->get_int( 'discount_id', 'post', 0 );
	$rowcontent['money_unit'] = $nv_Request->get_string( 'money_unit', 'post', '' );
	$rowcontent['product_unit'] = $nv_Request->get_int( 'product_unit', 'post', 0 );
	$rowcontent['homeimgfile'] = $nv_Request->get_title( 'homeimg', 'post', '' );
	$rowcontent['homeimgalt'] = $nv_Request->get_title( 'homeimgalt', 'post', '', 1 );

	$bodytext = $nv_Request->get_string( 'bodytext', 'post', '' );
	$rowcontent['bodytext'] = defined( 'NV_EDITOR' ) ? nv_nl2br( $bodytext, '' ) : nv_nl2br( nv_htmlspecialchars( strip_tags( $bodytext ) ), '<br />' );

	$rowcontent['copyright'] = ( int )$nv_Request->get_bool( 'copyright', 'post' );
	$rowcontent['inhome'] = ( int )$nv_Request->get_bool( 'inhome', 'post' );

	$_groups_post = $nv_Request->get_array( 'allowed_comm', 'post', array() );
	$rowcontent['allowed_comm'] = ! empty( $_groups_post ) ? implode( ',', nv_groups_post( array_intersect( $_groups_post, array_keys( $groups_list ) ) ) ) : '';

	$rowcontent['allowed_rating'] = ( int )$nv_Request->get_bool( 'allowed_rating', 'post' );
	$rowcontent['allowed_send'] = ( int )$nv_Request->get_bool( 'allowed_send', 'post' );
	$rowcontent['allowed_print'] = ( int )$nv_Request->get_bool( 'allowed_print', 'post' );
	$rowcontent['allowed_save'] = ( int )$nv_Request->get_bool( 'allowed_save', 'post' );

	$rowcontent['keywords'] = $nv_Request->get_array( 'keywords', 'post', '' );
    $rowcontent['keywords'] = implode(', ', $rowcontent['keywords'] );

	$array_custom = $nv_Request->get_array( 'custom', 'post' );
	$array_custom_lang = $nv_Request->get_array( 'custom_lang', 'post' );

	$rowcontent['custom_site'] = ( ! empty( $array_custom ) ) ? serialize( $array_custom ) : '';
	$rowcontent['custom'] = ( ! empty( $array_custom_lang ) ) ? serialize( $array_custom_lang ) : '';

	// Xu ly anh minh hoa khac
	$otherimage = $nv_Request->get_typed_array( 'otherimage', 'post', 'string' );
	$array_otherimage = array();
	foreach( $otherimage as $otherimage_i )
	{
		if( ! nv_is_url( $otherimage_i ) and file_exists( NV_DOCUMENT_ROOT . $otherimage_i ) )
		{
			$lu = strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' );
			$otherimage_i = substr( $otherimage_i, $lu );
		}
		elseif( ! nv_is_url( $otherimage_i ) )
		{
			$otherimage_i = '';
		}
		if( ! empty( $otherimage_i ) )
		{
			$array_otherimage[] = $otherimage_i;
		}
	}
	$rowcontent['otherimage'] = implode( '|', $array_otherimage );

	// Kiem tra ma san pham trung
	$error_product_code = false;
	if( ! empty( $rowcontent['product_code'] ) )
	{
		$stmt = $db->prepare( 'SELECT id FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE product_code= :product_code AND id!=' . $rowcontent['id'] );
		$stmt->bindParam( ':product_code', $rowcontent['product_code'], PDO::PARAM_STR );
		$stmt->execute();
		$id_err = $stmt->rowCount();

		$stmt = $db->prepare( 'SELECT id FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE product_code= :product_code' );
		$stmt->bindParam( ':product_code', $rowcontent['product_code'], PDO::PARAM_STR );
		$stmt->execute();
		if( $rowcontent['id'] == 0 and $stmt->rowCount() )
		{
			$error_product_code = true;
		}
		elseif( $id_err )
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
	elseif( trim( strip_tags( $rowcontent['hometext'] ) ) == '' )
	{
		$error = $lang_module['error_hometext'];
	}
	elseif( trim( strip_tags( $rowcontent['bodytext'] ) ) == '' )
	{
		$error = $lang_module['error_bodytext'];
	}
	elseif( $rowcontent['product_unit'] == 0 )
	{
		$error = $lang_module['error_product_unit'];
	}
	elseif( $rowcontent['product_price'] <= 0 and $rowcontent['showprice'] )
	{
		$error = $lang_module['error_product_price'];
	}
	else
	{
		// Xu ly tu khoa
		if( $rowcontent['keywords'] == '' )
		{
			if( $rowcontent['hometext'] != '' )
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

		$listfield = '';
		$listvalue = '';
		foreach( $field_lang as $field_lang_i )
		{
			list( $flang, $fname ) = $field_lang_i;
			$listfield .= ', ' . $flang . '_' . $fname;
			$listvalue .= ', :' . $flang . '_' . $fname;
		}

		if( $rowcontent['id'] == 0 )
		{
			$rowcontent['publtime'] = ( $rowcontent['publtime'] > NV_CURRENTTIME ) ? $rowcontent['publtime'] : NV_CURRENTTIME;
			if( $rowcontent['status'] == 1 and $rowcontent['publtime'] > NV_CURRENTTIME )
			{
				$rowcontent['status'] = 2;
			}

			$sql = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_rows (id, listcatid, user_id, addtime, edittime, status, publtime, exptime, archive, product_code, product_number, product_price, money_unit, product_unit, discount_id, homeimgfile, homeimgthumb, homeimgalt,otherimage,imgposition, copyright, inhome, allowed_comm, allowed_rating, ratingdetail, allowed_send, allowed_print, allowed_save, hitstotal, hitscm, hitslm, showprice, custom " . $listfield . ")
				 VALUES ( NULL ,
				 :listcatid,
				 " . intval( $rowcontent['user_id'] ) . ",
				 " . intval( $rowcontent['addtime'] ) . ",
				 " . intval( $rowcontent['edittime'] ) . ",
				 " . intval( $rowcontent['status'] ) . ",
				 " . intval( $rowcontent['publtime'] ) . ",
				 " . intval( $rowcontent['exptime'] ) . ",
				 " . intval( $rowcontent['archive'] ) . ",
				 :product_code,
				 " . intval( $rowcontent['product_number'] ) . ",
				 :product_price,
				 :money_unit,
				 " . intval( $rowcontent['product_unit'] ) . ",
				 " . intval( $rowcontent['discount_id'] ) . ",
				 :homeimgfile,
				 :homeimgthumb,
				 :homeimgalt,
				 :otherimage,
				 " . intval( $rowcontent['imgposition'] ) . ",
				 " . intval( $rowcontent['copyright'] ) . ",
				 " . intval( $rowcontent['inhome'] ) . ",
				 :allowed_comm,
				 " . intval( $rowcontent['allowed_rating'] ) . ",
				 :ratingdetail,
				 " . intval( $rowcontent['allowed_send'] ) . ",
				 " . intval( $rowcontent['allowed_print'] ) . ",
				 " . intval( $rowcontent['allowed_save'] ) . ",
				 " . intval( $rowcontent['hitstotal'] ) . ",
				 " . intval( $rowcontent['hitscm'] ) . ",
				 " . intval( $rowcontent['hitslm'] ) . ",
				 " . intval( $rowcontent['showprice'] ) . ",
				 :custom
				" . $listvalue . "
			)";
			$data_insert = array();
			$data_insert['listcatid'] = $rowcontent['listcatid'];
			$data_insert['product_code'] = $rowcontent['product_code'];
			$data_insert['product_price'] = $rowcontent['product_price'];
			$data_insert['money_unit'] = $rowcontent['money_unit'];
			$data_insert['homeimgfile'] = $rowcontent['homeimgfile'];
			$data_insert['homeimgthumb'] = $rowcontent['homeimgthumb'];
			$data_insert['homeimgalt'] = $rowcontent['homeimgalt'];
			$data_insert['otherimage'] = $rowcontent['otherimage'];
			$data_insert['ratingdetail'] = $rowcontent['ratingdetail'];
			$data_insert['allowed_comm'] = $rowcontent['allowed_comm'];
			$data_insert['custom'] = $rowcontent['custom_site'];
			foreach( $field_lang as $field_lang_i )
			{
				list( $flang, $fname ) = $field_lang_i;
				$data_insert[$flang . '_' . $fname] = $rowcontent[$fname];
			}

			$rowcontent['id'] = $db->insert_id( $sql, 'catid', $data_insert );

			if( $rowcontent['id'] > 0 )
			{
				if( ! empty( $rowcontent['group_id'] ) )
				{
					$stmt = $db->prepare( 'INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_items_group VALUES(' . $rowcontent['id'] . ', :group_id)' );
					foreach( $rowcontent['group_id'] as $group_id_i )
					{
						$stmt->bindParam( ':group_id', $group_id_i, PDO::PARAM_STR );
						$stmt->execute();
					}
				}
					
				$auto_product_code = '';
				if( ! empty( $pro_config['format_code_id'] ) and empty( $rowcontent['product_code'] ) )
				{
					$i = 1;
					$auto_product_code = vsprintf( $pro_config['format_code_id'], $rowcontent['id'] );

					$stmt = $db->prepare( 'SELECT id FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE product_code= :product_code' );
					$stmt->bindParam( ':product_code', $auto_product_code, PDO::PARAM_STR );
					$stmt->execute();
					while( $stmt->rowCount() )
					{
						$auto_product_code = vsprintf( $pro_config['format_code_id'], ( $rowcontent['id'] + $i++ ) );
					}

					$stmt = $db->prepare( 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_rows SET product_code= :product_code WHERE id=' . $rowcontent['id'] );
					$stmt->bindParam( ':product_code', $auto_product_code, PDO::PARAM_STR );
					$stmt->execute();
				}

				nv_fix_group_count( $rowcontent['group_id'] );
				nv_insert_logs( NV_LANG_DATA, $module_name, 'Add A Product', 'ID: ' . $rowcontent['id'], $admin_info['userid'] );
			}
			else
			{
				$error = $lang_module['errorsave'];
			}
		}
		else
		{
			$rowcontent_old = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows where id=' . $rowcontent['id'] )->fetch();

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

			$stmt = $db->prepare( "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_rows SET
			 listcatid= :listcatid,
			 user_id=" . intval( $rowcontent['user_id'] ) . ",
			 status=" . intval( $rowcontent['status'] ) . ",
			 publtime=" . intval( $rowcontent['publtime'] ) . ",
			 exptime=" . intval( $rowcontent['exptime'] ) . ",
			 edittime= " . NV_CURRENTTIME . " ,
			 archive=" . intval( $rowcontent['archive'] ) . ",
			 product_code = :product_code,
			 product_number = product_number + " . intval( $rowcontent['product_number'] ) . ",
			 product_price = :product_price,
			 money_unit = :money_unit,
			 product_unit = " . intval( $rowcontent['product_unit'] ) . ",
			 discount_id = " . intval( $rowcontent['discount_id'] ) . ",
			 homeimgfile= :homeimgfile,
			 homeimgalt= :homeimgalt,
			 otherimage= :otherimage,
			 homeimgthumb= :homeimgthumb,
			 imgposition=" . intval( $rowcontent['imgposition'] ) . ",
			 copyright=" . intval( $rowcontent['copyright'] ) . ",
			 inhome=" . intval( $rowcontent['inhome'] ) . ",
			 allowed_comm= :allowed_comm,
			 allowed_rating=" . intval( $rowcontent['allowed_rating'] ) . ",
			 allowed_send=" . intval( $rowcontent['allowed_send'] ) . ",
			 allowed_print=" . intval( $rowcontent['allowed_print'] ) . ",
			 allowed_save=" . intval( $rowcontent['allowed_save'] ) . ",
			 showprice = " . intval( $rowcontent['showprice'] ) . ",
			 custom= :custom,
			 " . NV_LANG_DATA . "_title= :title,
			 " . NV_LANG_DATA . "_alias= :alias,
			 " . NV_LANG_DATA . "_hometext= :hometext,
			 " . NV_LANG_DATA . "_bodytext= :bodytext,
			 " . NV_LANG_DATA . "_warranty= :warranty,
			 " . NV_LANG_DATA . "_promotional= :promotional,
			 " . NV_LANG_DATA . "_custom= :custom_lang
			 WHERE id =" . $rowcontent['id'] );

			$stmt->bindParam( ':listcatid', $rowcontent['listcatid'], PDO::PARAM_STR );
			$stmt->bindParam( ':product_code', $rowcontent['product_code'], PDO::PARAM_STR );
			$stmt->bindParam( ':money_unit', $rowcontent['money_unit'], PDO::PARAM_STR );
			$stmt->bindParam( ':product_price', $rowcontent['product_price'], PDO::PARAM_STR );
			$stmt->bindParam( ':homeimgfile', $rowcontent['homeimgfile'], PDO::PARAM_STR );
			$stmt->bindParam( ':homeimgalt', $rowcontent['homeimgalt'], PDO::PARAM_STR );
			$stmt->bindParam( ':otherimage', $rowcontent['otherimage'], PDO::PARAM_STR );
			$stmt->bindParam( ':homeimgthumb', $rowcontent['homeimgthumb'], PDO::PARAM_STR );
			$stmt->bindParam( ':title', $rowcontent['title'], PDO::PARAM_STR );
			$stmt->bindParam( ':alias', $rowcontent['alias'], PDO::PARAM_STR );
			$stmt->bindParam( ':hometext', $rowcontent['hometext'], PDO::PARAM_STR, strlen( $rowcontent['hometext'] ) );
			$stmt->bindParam( ':bodytext', $rowcontent['bodytext'], PDO::PARAM_STR, strlen( $rowcontent['bodytext'] ) );
			$stmt->bindParam( ':promotional', $rowcontent['promotional'], PDO::PARAM_STR );
			$stmt->bindParam( ':warranty', $rowcontent['warranty'], PDO::PARAM_STR );
			$stmt->bindParam( ':allowed_comm', $rowcontent['allowed_comm'], PDO::PARAM_STR );
			$stmt->bindParam( ':custom', $rowcontent['custom_site'], PDO::PARAM_STR, strlen( $rowcontent['custom_site'] ) );
			$stmt->bindParam( ':custom_lang', $rowcontent['custom'], PDO::PARAM_STR, strlen( $rowcontent['custom'] ) );

			if( $stmt->execute() )
			{
				if( $group_id_old != $rowcontent['group_id'] )
				{
					$sql = 'DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_items_group WHERE pro_id = ' . $rowcontent['id'];
					$db->query( $sql );
					
					if( ! empty( $rowcontent['group_id'] ) )
					{
						$stmt = $db->prepare( 'INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_items_group VALUES(' . $rowcontent['id'] . ', :group_id)' );
						foreach( $rowcontent['group_id'] as $group_id_i )
						{
							$stmt->bindParam( ':group_id', $group_id_i, PDO::PARAM_STR );
							$stmt->execute();
						}
					}

					nv_fix_group_count( $rowcontent['group_id'] );
					nv_fix_group_count( $group_id_old );
				}
				nv_insert_logs( NV_LANG_DATA, $module_name, 'Edit A Product', 'ID: ' . $rowcontent['id'], $admin_info['userid'] );
			}
			else
			{
				$error = $lang_module['errorsave'];
			}
		}

		nv_set_status_module();

		if( $error == '' )
		{
			$db->query( 'DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_block WHERE id = ' . $rowcontent['id'] );

			foreach( $id_block_content as $bid_i )
			{
				$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_block (bid, id, weight) VALUES ('" . $bid_i . "', '" . $rowcontent['id'] . "', '0')" );
			}

			foreach( $array_block_cat_module as $bid_i )
			{
				nv_news_fix_block( $bid_i );
			}

			// Update tags list
			if( $rowcontent['keywords'] != $rowcontent['keywords_old'] )
			{
				$keywords = explode( ',', $rowcontent['keywords'] );
				$keywords = array_map( 'strip_punctuation', $keywords );
				$keywords = array_map( 'trim', $keywords );
				$keywords = array_diff( $keywords, array( '' ) );
				$keywords = array_unique( $keywords );

				foreach( $keywords as $keyword )
				{
					if( ! in_array( $keyword, $array_keywords_old ) )
					{
						$alias_i = ( $module_config[$module_name]['tags_alias'] ) ? change_alias( $keyword ) : str_replace( ' ', '-', $keyword );
						$alias_i = nv_strtolower( $alias_i );
						$sth = $db->prepare( 'SELECT tid, ' . NV_LANG_DATA . '_alias, ' . NV_LANG_DATA .'_description, ' . NV_LANG_DATA . '_keywords FROM ' . $db_config['prefix'] . '_' . $module_data . '_tags where ' . NV_LANG_DATA . '_alias= :alias OR FIND_IN_SET(:keyword, ' . NV_LANG_DATA . '_keywords)>0' );
						$sth->bindParam( ':alias', $alias_i, PDO::PARAM_STR );
						$sth->bindParam( ':keyword', $keyword, PDO::PARAM_STR );
						$sth->execute();

						list( $tid, $alias, $keywords_i ) = $sth->fetch( 3 );
						if( empty( $tid ) )
						{
							$array_insert = array();
							$array_insert['alias'] = $alias_i;
							$array_insert['keyword'] = $keyword;

							$tid = $db->insert_id( "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_tags (" . NV_LANG_DATA . "_numpro, " . NV_LANG_DATA . "_alias, " . NV_LANG_DATA . "_description, " . NV_LANG_DATA . "_image, " . NV_LANG_DATA . "_keywords) VALUES (1, :alias, '', '', :keyword)", "tid", $array_insert );
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
									$sth = $db->prepare( 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_tags SET ' . NV_LANG_DATA . '_keywords= :keywords WHERE tid =' . $tid );
									$sth->bindParam( ':keywords', $keywords_i2, PDO::PARAM_STR );
									$sth->execute();
								}
							}
							$db->query( 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_tags SET ' . NV_LANG_DATA . '_numpro = ' . NV_LANG_DATA . '_numpro+1 WHERE tid = ' . $tid );
						}

						// insert keyword for table _tags_id
						try
						{
							$sth = $db->prepare( 'INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_tags_id (id, tid, ' . NV_LANG_DATA . '_keyword) VALUES (' . $rowcontent['id'] . ', ' . intval( $tid ) . ', :keyword)' );
							$sth->bindParam( ':keyword', $keyword, PDO::PARAM_STR );
							$sth->execute();
						}
						catch( PDOException $e )
						{
							$sth = $db->prepare( 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_tags_id SET ' . NV_LANG_DATA . '_keyword = :keyword WHERE id = ' . $rowcontent['id'] . ' AND tid=' . intval( $tid ) );
							$sth->bindParam( ':keyword', $keyword, PDO::PARAM_STR );
							$sth->execute();
						}
						unset( $array_keywords_old[$tid] );
					}
				}

				foreach( $array_keywords_old as $tid => $keyword )
				{
					if( ! in_array( $keyword, $keywords ) )
					{
						$db->query( 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_tags SET ' . NV_LANG_DATA . '_numpro = ' . NV_LANG_DATA . '_numpro-1 WHERE tid = ' . $tid );
						$db->query( 'DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_tags_id WHERE id = ' . $rowcontent['id'] . ' AND tid=' . $tid );
					}
				}
			}

			nv_del_moduleCache( $module_name );
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=items' );
			die();
		}

		nv_del_moduleCache( $module_name );
	}
}
elseif( $rowcontent['id'] > 0 )
{
	$rowcontent = $db->query( "SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_rows where id=" . $rowcontent['id'] )->fetch();
	$rowcontent['title'] = $rowcontent[NV_LANG_DATA . '_title'];
	$rowcontent['alias'] = $rowcontent[NV_LANG_DATA . '_alias'];
	$rowcontent['hometext'] = $rowcontent[NV_LANG_DATA . '_hometext'];
	$rowcontent['bodytext'] = $rowcontent[NV_LANG_DATA . '_bodytext'];
	$rowcontent['promotional'] = $rowcontent[NV_LANG_DATA . '_promotional'];
	$rowcontent['warranty'] = $rowcontent[NV_LANG_DATA . '_warranty'];
	$rowcontent['group_id'] = $group_id_old;

	if ( !empty( $rowcontent['custom'] ) )
	{
		$array_custom = unserialize( $rowcontent['custom'] );
	}
	if ( !empty( $rowcontent[NV_LANG_DATA . '_custom'] ) )
	{
		$array_custom_lang = unserialize( $rowcontent[NV_LANG_DATA . '_custom'] );
	}

	$page_title = $lang_module['content_edit'];

	$id_block_content = array();
	$sql = 'SELECT bid FROM ' . $db_config['prefix'] . '_' . $module_data . '_block where id=' . $rowcontent['id'];
	$result = $db->query( $sql );

	while( list( $bid_i ) = $result->fetch( 3 ) )
	{
		$id_block_content[] = $bid_i;
	}
}

if( ! empty( $rowcontent['homeimgfile'] ) and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $rowcontent['homeimgfile'] ) )
{
	$rowcontent['homeimgfile'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $rowcontent['homeimgfile'];
}
$rowcontent['hometext'] = nv_htmlspecialchars( nv_br2nl( $rowcontent['hometext'] ) );

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

if( ! empty( $rowcontent['otherimage'] ) )
{
	$otherimage = explode( '|', $rowcontent['otherimage'] );
}
else
{
	$otherimage = array();
}

$xtpl = new XTemplate( 'content.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'rowcontent', $rowcontent );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'module_name', $module_name );
$xtpl->assign( 'CURRENT', NV_UPLOADS_DIR . '/' . $module_name . '/' . date( 'Y_m' ) );

if( $error != '' )
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
		if( ! empty( $otherimage_i ) and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $otherimage_i ) )
		{
			$otherimage_i = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $otherimage_i;
		}
		$data_otherimage_i = array( 'id' => $items, 'value' => $otherimage_i );
		$xtpl->assign( 'DATAOTHERIMAGE', $data_otherimage_i );
		$xtpl->parse( 'main.otherimage' );
		++$items;
	}
}
$xtpl->assign( 'FILE_ITEMS', $items );

foreach ($global_array_cat as $catid_i => $rowscat )
{
	$xtitle_i = '';
	if( $rowscat['lev'] > 0 )
	{
		for( $i = 1; $i <= $rowscat['lev']; $i++ )
		{
			$xtitle_i .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		}
	}
	$rowscat['title'] = $xtitle_i . $rowscat['title'];
	$rowscat['selected'] = ( $catid_i == $rowcontent['listcatid'] ) ? ' selected="selected"' : '';

	$xtpl->assign( 'ROWSCAT', $rowscat );
	$xtpl->parse( 'main.rowscat' );
}

// List group
//var_dump($rowcontent['group_id']); die;
if( ! empty( $rowcontent['group_id'] ) )
{
	$array_groupid_in_row = $rowcontent['group_id'];
}
else
{
	$array_groupid_in_row = array();
}

$inrow = nv_base64_encode( serialize( $array_groupid_in_row ) );
$xtpl->assign( 'url_load', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=getgroup&cid=' . $rowcontent['listcatid'] . '&inrow=' . $inrow );
$xtpl->assign( 'inrow', $inrow );
$xtpl->parse( 'main.listgroup' );

// Time update
$xtpl->assign( 'publ_date', $publ_date );
$select = '';
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

$select = '';
for( $i = 0; $i < 60; $i++ )
{
	$select .= "<option value=\"" . $i . "\"" . ( ( $i == $emin ) ? " selected=\"selected\"" : "" ) . ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
}
$xtpl->assign( 'emin', $select );

// Allowed comm
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

$rowcontent['bodytext'] = htmlspecialchars( nv_editor_br2nl( $rowcontent['bodytext'] ) );
if( defined( 'NV_EDITOR' ) and function_exists( 'nv_aleditor' ) )
{
	$edits = nv_aleditor( 'bodytext', '100%', '300px', $rowcontent['bodytext'] );
}
else
{
	$edits = "<textarea style=\"width: 100%\" name=\"bodytext\" id=\"bodytext\" cols=\"20\" rows=\"15\">" . $rowcontent['bodytext'] . "</textarea>";
}

$shtm = '';
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

// List discount
$sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_discounts';
$_result = $db->query( $sql );
while( $_discount = $_result->fetch() )
{
	$_discount['selected'] = ( $_discount['did'] == $rowcontent['discount_id'] ) ? "selected=\"selected\"" : "";
	$xtpl->assign( 'DISCOUNT', $_discount );
	$xtpl->parse( 'main.discount' );
}

// List pro_unit
$sql = 'SELECT id, ' . NV_LANG_DATA . '_title FROM ' . $db_config['prefix'] . '_' . $module_data . '_units';
$result_unit = $db->query( $sql );
if( $result_unit->rowCount() == 0 )
{
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=prounit' );
	die();
}

while( list( $unitid_i, $title_i ) = $result_unit->fetch( 3 ) )
{
	$xtpl->assign( 'utitle', $title_i );
	$xtpl->assign( 'uid', $unitid_i );
	$uch = ( $rowcontent['product_unit'] == $unitid_i ) ? "selected=\"selected\"" : "";
	$xtpl->assign( 'uch', $uch );
	$xtpl->parse( 'main.rowunit' );
}

// Print tags
if( ! empty( $rowcontent['keywords'] ) )
{
	$keywords_array = explode( ',', $rowcontent['keywords'] );
	foreach( $keywords_array as $keywords )
	{
		$xtpl->assign( 'KEYWORDS', $keywords );
		$xtpl->parse( 'main.keywords' );
	}
}

if( $module_config[$module_name]['auto_tags'] )
{
	$xtpl->parse( 'main.auto_tags' );
}

$archive_checked = ( $rowcontent['archive'] ) ? " checked=\"checked\"" : "";
$xtpl->assign( 'archive_checked', $archive_checked );

$inhome_checked = ( $rowcontent['inhome'] ) ? " checked=\"checked\"" : "";
$xtpl->assign( 'inhome_checked', $inhome_checked );

$allowed_rating_checked = ( $rowcontent['allowed_rating'] ) ? " checked=\"checked\"" : "";
$xtpl->assign( 'allowed_rating_checked', $allowed_rating_checked );

$allowed_send_checked = ( $rowcontent['allowed_send'] ) ? " checked=\"checked\"" : "";
$xtpl->assign( 'allowed_send_checked', $allowed_send_checked );

$allowed_print_checked = ( $rowcontent['allowed_print'] ) ? " checked=\"checked\"" : "";
$xtpl->assign( 'allowed_print_checked', $allowed_print_checked );

$allowed_save_checked = ( $rowcontent['allowed_save'] ) ? " checked=\"checked\"" : "";
$xtpl->assign( 'allowed_save_checked', $allowed_save_checked );

$showprice_checked = ( $rowcontent['showprice'] ) ? " checked=\"checked\"" : "";
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
	$op = 'items';
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

if( $rowcontent['listcatid'] AND !empty( $global_array_cat[$rowcontent['listcatid']]['form'] ) )
{
	$datacustom_form = nv_show_custom_form( $global_array_cat[$rowcontent['listcatid']]['form'], $array_custom, $array_custom_lang );
	$xtpl->assign( 'DATACUSTOM_FORM', $datacustom_form );
}
$xtpl->parse( 'main.custom_form' );

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';