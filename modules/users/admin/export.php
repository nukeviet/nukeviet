<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 3, 2010 11:33:22 AM
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

if( ! file_exists( NV_ROOTDIR . '/includes/class/PHPExcel.php' ) )
{
    die( strip_tags( $lang_module['required_phpexcel'] ) );
}

$data_field = array();
$step = $nv_Request->get_int( 'step', 'get,post', 1 );

require_once NV_ROOTDIR . '/includes/class/PHPExcel.php' ;

if( extension_loaded( 'zip' ) )
{
	$excel_ext = "xlsx";
	$writerType = 'Excel2007';
}
else
{
	$excel_ext = "xls";
	$writerType = 'Excel5';
}

if( $step == 1 )
{
	$example = $nv_Request->get_int( 'example', 'get', 0 );

	$page_title = ( empty( $example ) ) ? $lang_module['export'] : $lang_module['export_example'];

	// Create new PHPExcel object
	$objPHPExcel = PHPExcel_IOFactory::load( NV_ROOTDIR . '/modules/' . $module_file . '/template.' . $excel_ext );
	$objWorksheet = $objPHPExcel->getActiveSheet();

	// Setting a spreadsheet’s metadata
	$objPHPExcel->getProperties()->setCreator( "NukeViet CMS" );
	$objPHPExcel->getProperties()->setLastModifiedBy( "NukeViet CMS" );
	$objPHPExcel->getProperties()->setTitle( $page_title );
	$objPHPExcel->getProperties()->setSubject( $page_title );
	$objPHPExcel->getProperties()->setDescription( $page_title );
	$objPHPExcel->getProperties()->setKeywords( $page_title );
	$objPHPExcel->getProperties()->setCategory( $module_name );

	// Rename sheet
	$objWorksheet->setTitle( nv_clean60( $page_title, 30 ) );

	// Set page orientation and size
	$objWorksheet->getPageSetup()->setOrientation( PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE );
	$objWorksheet->getPageSetup()->setPaperSize( PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4 );
	$objWorksheet->getPageSetup()->setHorizontalCentered( true );
	$objWorksheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd( 1, 3 );

	$columnIndex = 0;
	$user_field = array();
	$user_field['userid'] = ( isset( $lang_module['userid'] ) ) ? $lang_module['userid'] : 'userid';
	$user_field['username'] = ( isset( $lang_module['account'] ) ) ? $lang_module['account'] : 'username';
	$user_field['password'] = ( isset( $lang_module['password'] ) ) ? $lang_module['password'] : 'password';
	$user_field['email'] = ( isset( $lang_module['email'] ) ) ? $lang_module['email'] : 'email';
	$user_field['full_name'] = ( isset( $lang_module['name'] ) ) ? $lang_module['name'] : 'full_name';
	$user_field['gender'] = ( isset( $lang_module['gender'] ) ) ? $lang_module['gender'] : 'gender';
	$user_field['birthday'] = ( isset( $lang_module['birthday'] ) ) ? $lang_module['birthday'] : 'birthday';
	$user_field['sig'] = ( isset( $lang_module['sig'] ) ) ? $lang_module['sig'] : 'sig';
	$user_field['regdate'] = ( isset( $lang_module['regdate'] ) ) ? $lang_module['regdate'] : 'regdate';
	$user_field['question'] = ( isset( $lang_module['question'] ) ) ? $lang_module['question'] : 'question';
	$user_field['answer'] = ( isset( $lang_module['answer'] ) ) ? $lang_module['answer'] : 'answer';
	$user_field['view_mail'] = ( isset( $lang_module['show_email'] ) ) ? $lang_module['show_email'] : 'view_mail';
	$user_field['active'] = ( isset( $lang_module['active_users'] ) ) ? $lang_module['active_users'] : 'active';
	foreach( $user_field as $key => $value )
	{
		$TextColumnIndex = PHPExcel_Cell::stringFromColumnIndex( $columnIndex );
		$objWorksheet->setCellValue( $TextColumnIndex . '3', $value );
		$objWorksheet->setCellValue( $TextColumnIndex . '4', $key );
		$columnIndex++;
	}
	// Set Comment userid
	$objtext = new PHPExcel_RichText();
	$objtext->createTextRun( $lang_module['export_comment_userid'] )->getFont()->setBold( false )->setName( 'Tahoma' )->setSize( 9 );
	$objComment = $objWorksheet->getComment( 'A3' );
	$objComment->setText( $objtext );
	$objComment->setWidth( '220pt' )->setHeight( '27pt' );

	// Set Comment password
	$objtext = new PHPExcel_RichText();
	$objtext->createTextRun( $lang_module['export_comment_password'] )->getFont()->setBold( false )->setName( 'Tahoma' )->setSize( 9 );
	$objComment = $objWorksheet->getComment( 'C3' );
	$objComment->setText( $objtext );
	$objComment->setWidth( '200pt' )->setHeight( '27pt' );

	// Set Comment gender
	$objtext = new PHPExcel_RichText();
	$objtext->createTextRun( $lang_module['export_comment_gender'] )->getFont()->setBold( false )->setName( 'Tahoma' )->setSize( 9 );
	$objComment = $objWorksheet->getComment( 'F3' );
	$objComment->setText( $objtext );
	$objComment->setWidth( '220pt' )->setHeight( '20pt' );

	// Set Comment gender
	$objtext = new PHPExcel_RichText();
	$objtext->createTextRun( $lang_module['export_comment_date'] )->getFont()->setBold( false )->setName( 'Tahoma' )->setSize( 9 );
	$objComment = $objWorksheet->getComment( 'G3' );
	$objComment->setText( $objtext );
	$objComment->setWidth( '220pt' )->setHeight( '20pt' );

	$objComment = $objWorksheet->getComment( 'I3' );
	$objComment->setText( $objtext );
	$objComment->setWidth( '220pt' )->setHeight( '20pt' );

	$user_field_info = array();
	$result_field = $db->query( "SELECT * FROM " . NV_USERS_GLOBALTABLE . "_field ORDER BY weight ASC" );
	while( $row_field = $result_field->fetch() )
	{
		$language = unserialize( $row_field['language'] );
		$row_field['title'] = ( isset( $language[NV_LANG_DATA] ) ) ? $language[NV_LANG_DATA][0] : $row_field['field'];
		$user_field_info[$row_field['field']] = $row_field;
		$TextColumnIndex = PHPExcel_Cell::stringFromColumnIndex( $columnIndex );
		$objWorksheet->setCellValue( $TextColumnIndex . '3', $row_field['title'] );
		$objWorksheet->setCellValue( $TextColumnIndex . '4', $row_field['field'] );
		$objWorksheet->getColumnDimension( $TextColumnIndex )->setAutoSize( true );
		$columnIndex++;
	}

	if( empty( $example ) )
	{
		//export Data
		$usactive = ( int )$nv_Request->get_bool( 'usactive', 'get', 0 );
		$method = urldecode( $nv_Request->get_string( 'method', 'get', '' ) );
		$value = urldecode( $nv_Request->get_string( 'value', 'get', '' ) );
		$set_export = $nv_Request->get_int( 'set_export', 'get,post', 0 );
		if( $set_export )
		{
			$id_export = 0;
		}
		else
		{
			$id_export = $nv_Request->get_int( $module_data . '_id_export', 'session', 0 );
		}

		// Mỗi file ghi 1000 dòng
		$limit_export_data = 1000;

		$db->sqlreset()
			->select( 'COUNT(*)' )
			->from( NV_USERS_GLOBALTABLE . " t1, " . NV_USERS_GLOBALTABLE . "_info t2" )
			->where( 't1.userid=t2.userid AND t1.userid>' . $id_export);

		$num_items = $db->query( $db->sql() )->fetchColumn();

		$db->select( '*' )->order( 't1.userid ASC' )->limit( $limit_export_data );

		$result = $db->query( $db->sql() )->fetchAll();

		$number_page = sizeof( $result );
		$id_export_save = 0;

		// Ghi dữ liệu bắt đầu từ dòng thứ 5
		$i = 5;
		foreach ( $result as $data2 )
		{
			$id_export_save = $data2['userid'];
			$data2['password'] = '';
			$data2['sig'] = strip_tags( $data2['sig'] );
			$j = 0;
			foreach( $user_field as $field => $row_field )
			{
				$col = PHPExcel_Cell::stringFromColumnIndex( $j );
				if( $field == 'birthday' )
				{
					if( ! empty( $data2[$field] ) )
					{
						$CellValue = PHPExcel_Shared_Date::FormattedPHPToExcel( date( 'Y', $data2[$field] ), date( 'm', $data2[$field] ), date( 'd', $data2[$field] ) );
					}
					else
					{
						$CellValue = null;
					}
					$objWorksheet->getStyle( $col . $i )->getNumberFormat()->setFormatCode( 'dd/mm/yyyy' );
				}
				elseif( $field == 'regdate' )
				{
					$CellValue = PHPExcel_Shared_Date::FormattedPHPToExcel( date( 'Y', $data2[$field] ), date( 'm', $data2[$field] ), date( 'd', $data2[$field] ), date( 'H', $data2[$field] ), date( 'i', $data2[$field] ), date( 's', $data2[$field] ) );
					$objWorksheet->getStyle( $col . $i )->getNumberFormat()->setFormatCode( 'dd/mm/yyyy' );
				}
				else
				{
					$CellValue = nv_unhtmlspecialchars( $data2[$field] );
				}
				$objWorksheet->setCellValue( $col . $i, $CellValue );

				$j++;
			}
			foreach( $user_field_info as $field => $row_field )
			{
				$col = PHPExcel_Cell::stringFromColumnIndex( $j );
				if( $row_field['field_type'] == 'date' )
				{
					if( ! empty( $data2[$field] ) )
					{
						$CellValue = PHPExcel_Shared_Date::FormattedPHPToExcel( date( 'Y', $data2[$field] ), date( 'm', $data2[$field] ), date( 'd', $data2[$field] ) );
					}
					else
					{
						$CellValue = null;
					}
					$objWorksheet->getStyle( $col . $i )->getNumberFormat()->setFormatCode( 'dd/mm/yyyy' );
				}
				else
				{
					$CellValue = nv_unhtmlspecialchars( $data2[$field] );
				}
				//under construction
				$objWorksheet->setCellValue( $col . $i, $CellValue );
				$j++;
			}
			$i++;
		}
	}

	$highestRow = $objWorksheet->getHighestRow();
	$highestColumn = $objWorksheet->getHighestColumn();
	if( $highestRow < 10 )
	{
		$highestRow = 10;
	}

	$objWorksheet->mergeCells( 'A2:' . $highestColumn . '2' );
	$objWorksheet->setCellValue( 'A2', $page_title );
	$objWorksheet->getStyle( 'A2' )->getAlignment()->setHorizontal( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
	$objWorksheet->getStyle( 'A2' )->getAlignment()->setVertical( PHPExcel_Style_Alignment::VERTICAL_CENTER );

	$styleArray = array( 'borders' => array( 'outline' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
				'color' => array( 'argb' => 'FF000000' )
			)
		)
	);

	$objWorksheet->getStyle( 'A3' . ':' . $highestColumn . $highestRow )->applyFromArray( $styleArray );

	$objWriter = PHPExcel_IOFactory::createWriter( $objPHPExcel, $writerType );
	if( $writerType == 'Excel2007' )
	{
		$objWriter->setOffice2003Compatibility( true );
	}

	if( $example == 1 )
	{
		if( $writerType == 'Excel2007' )
		{
			header( 'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' );
		}
		else
		{
			header( 'Content-Type: application/vnd.ms-excel' );
		}
		header( 'Content-Disposition: attachment;filename="' . basename( change_alias( $page_title ) . '.' . $excel_ext ) . '"' );
		header( 'Cache-Control: max-age=0' );
		$objWriter->save( 'php://output' );
		exit();
	}

	$export_filename = $nv_Request->get_string( $module_data . '_export_filename', 'session', '' );

	if( $id_export == 0 and $num_items <= $limit_export_data )
	{
		$file_name = change_alias( $page_title );
		$result = "OK_COMPLETE";
		$nv_Request->set_Session( $module_data . '_export_filename', $file_name );
	}
	elseif( $number_page < $limit_export_data )
	{
		$file_name = change_alias( $page_title ) . "_" . $id_export_save;
		$result = "OK_COMPLETE";
		$nv_Request->set_Session( $module_data . '_export_filename', $export_filename . "@" . $file_name );
	}
	else
	{
		$file_name = change_alias( $page_title ) . "_" . $id_export_save;
		$result = "OK_GETFILE";
		$nv_Request->set_Session( $module_data . '_id_export', $id_export_save );
		$nv_Request->set_Session( $module_data . '_export_filename', $export_filename . "@" . $file_name );
	}

	$objWriter->save( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $file_name . "." . $excel_ext );
	die( $result );
}
elseif( $step == 2 and $nv_Request->isset_request( $module_data . '_export_filename', 'session' ) )
{
	$export_filename = $nv_Request->get_string( $module_data . '_export_filename', 'session', '' );
	$array_filename = explode( "@", $export_filename );
	$arry_file_zip = array();
	foreach( $array_filename as $file_name )
	{
		if( ! empty( $file_name ) and file_exists( NV_ROOTDIR . '/' . NV_CACHEDIR . '/' . $file_name . '.' . $excel_ext ) )
		{
			$arry_file_zip[] = NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $file_name . "." . $excel_ext;
		}
	}

	$file_src = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . NV_TEMPNAM_PREFIX . change_alias( $lang_module['export'] ) . '_' . md5( nv_genpass( 10 ) . session_id() ) . '.zip';
	require_once NV_ROOTDIR . '/includes/class/pclzip.class.php';
	$zip = new PclZip( $file_src );
	$zip->create( $arry_file_zip, PCLZIP_OPT_REMOVE_PATH, NV_ROOTDIR . "/" . NV_CACHEDIR );
	$filesize = @filesize( $file_src );

	$nv_Request->unset_request( $module_data . '_export_filename', 'session' );

	foreach( $arry_file_zip as $file )
	{
		nv_deletefile( $file );
	}

	//Download file
	require_once NV_ROOTDIR . '/includes/class/download.class.php' ;
	$download = new download( $file_src, NV_ROOTDIR . "/" . NV_TEMP_DIR, basename( change_alias( $lang_module['export'] ) . ".zip" ) );
	$download->download_file();
	exit();
}