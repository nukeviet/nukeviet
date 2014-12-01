<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 - 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 08 Apr 2012 00:00:00 GMT GMT
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) )
	die( 'Stop!!!' );

//Ket noi ngon ngu cua module users
if( file_exists( NV_ROOTDIR . '/modules/users/language/admin_' . NV_LANG_INTERFACE . '.php' ) )
{
	require NV_ROOTDIR . '/modules/users/language/admin_' . NV_LANG_INTERFACE . '.php';
}
elseif( file_exists( NV_ROOTDIR . '/modules/users/language/admin_' . NV_LANG_DATA . '.php' ) )
{
	require NV_ROOTDIR . '/modules/users/language/admin_' . NV_LANG_DATA . '.php';
}
elseif( file_exists( NV_ROOTDIR . '/modules/users/language/admin_en.php' ) )
{
	require NV_ROOTDIR . '/modules/users/language/admin_en.php';
}

$sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_template where status =1 ';

$_rows = $db->query( $sql )->fetchAll( );
$num = sizeof( $_rows );
if( $num < 1 )
{
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=template' );
	die( );
}

$result = $db->query( $sql );
$array_template = array( );
while( $row = $result->fetch( ) )
{

	$array_template[$row['id']] = array(
		'id' => $row['id'],
		'title' => $row["title"],
		'alias' => $row["alias"],
	);
}
// Chinh thu tu
if( $nv_Request->isset_request( 'changeweight', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) )
		die( 'Wrong URL' );

	$fid = $nv_Request->get_int( 'fid', 'post', 0 );
	$new_vid = $nv_Request->get_int( 'new_vid', 'post', 0 );

	$query = 'SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . '_field WHERE fid=' . $fid;
	$numrows = $db->query( $query )->fetchColumn( );
	if( $numrows != 1 )
		die( 'NO' );

	$query = 'SELECT fid FROM ' . $db_config['prefix'] . '_' . $module_data . '_field WHERE fid!=' . $fid . ' ORDER BY weight ASC';
	$result = $db->query( $query );
	$weight = 0;
	while( $row = $result->fetch( ) )
	{
		++$weight;
		if( $weight == $new_vid )
			++$weight;
		$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_field SET weight=' . $weight . ' WHERE fid=' . $row['fid'];
		$db->query( $sql );
	}
	$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_field SET weight=' . $new_vid . ' WHERE fid=' . $fid;
	$db->query( $sql );
	die( 'OK' );
}

// lay du lieu sql
if( $nv_Request->isset_request( 'choicesql', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) )
		die( 'Wrong URL' );

	$array_choicesql = array(
		'module' => 'table',
		'table' => 'column'
	);
	$choice = $nv_Request->get_string( 'choice', 'post', '' );
	$choice_seltected = $nv_Request->get_string( 'choice_seltected', 'post', '' );

	$xtpl = new XTemplate( 'fields.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );

	if( $choice == 'module' )
	{
		$xtpl->assign( 'choicesql_name', 'choicesql_' . $choice );
		$xtpl->assign( 'choicesql_next', $array_choicesql[$choice] );
		$xtpl->parse( 'choicesql.loop' );
		foreach( $site_mods as $module )
		{
			$_temp_choice['sl'] = ($choice_seltected == $module['module_data']) ? ' selected="selected"' : '';
			$_temp_choice['key'] = $module['module_data'];
			$_temp_choice['val'] = $module['custom_title'];
			$xtpl->assign( 'SQL', $_temp_choice );
			$xtpl->parse( 'choicesql.loop' );
			unset( $_temp_choice );
		}
		$xtpl->parse( 'choicesql' );
		$contents = $xtpl->text( 'choicesql' );
	}
	elseif( $choice == 'table' )
	{
		$module = $nv_Request->get_string( 'module', 'post', '' );
		if( $module == '' )
			exit( );
		$_items = $db->query( "SHOW TABLE STATUS LIKE '%\_" . $module . "%'" )->fetchAll( );
		$num_table = sizeof( $_items );

		$array_table_module = array( );
		$xtpl->assign( 'choicesql_name', 'choicesql_' . $choice );
		$xtpl->assign( 'choicesql_next', $array_choicesql[$choice] );

		if( $num_table > 0 )
		{
			$xtpl->parse( 'choicesql.loop' );
			foreach( $_items as $item )
			{
				$_temp_choice['sl'] = ($choice_seltected == $item['name']) ? ' selected="selected"' : '';
				$_temp_choice['key'] = $item['name'];
				$_temp_choice['val'] = $item['name'];
				$xtpl->assign( 'SQL', $_temp_choice );
				$xtpl->parse( 'choicesql.loop' );
				unset( $_temp_choice );
			}
		}
		$xtpl->parse( 'choicesql' );
		$contents = $xtpl->text( 'choicesql' );
	}
	elseif( $choice == 'column' )
	{
		$table = $nv_Request->get_string( 'table', 'post', '' );
		if( $table == '' )
			exit( );

		$_items = $db->columns_array( $table );
		$num_table = sizeof( $_items );

		$array_table_module = array( );
		$xtpl->assign( 'choicesql_name', 'choicesql_' . $choice );
		$xtpl->assign( 'choicesql_next', $array_choicesql[$choice] );
		if( $num_table > 0 )
		{
			$choice_seltected = explode( '|', $choice_seltected );
			foreach( $_items as $item )
			{
				$_temp_choice['sl_key'] = ($choice_seltected[0] == $item['field']) ? ' selected="selected"' : '';
				$_temp_choice['sl_val'] = ($choice_seltected[1] == $item['field']) ? ' selected="selected"' : '';
				$_temp_choice['key'] = $item['field'];
				$_temp_choice['val'] = $item['field'];
				$xtpl->assign( 'SQL', $_temp_choice );
				$xtpl->parse( 'column.loop1' );
				$xtpl->parse( 'column.loop2' );
				unset( $_temp_choice );
			}
		}
		$xtpl->parse( 'column' );
		$contents = $xtpl->text( 'column' );
	}

	include NV_ROOTDIR . '/includes/header.php';
	echo $contents;
	include NV_ROOTDIR . '/includes/footer.php';
}

//ADD
$text_fields = $number_fields = $date_fields = $choice_fields = $choice_type_sql = $choice_type_text = 0;
$error = '';
$field_choices = array( );
if( $nv_Request->isset_request( 'submit', 'post' ) )
{

	$validatefield = array(
		'pattern' => '/[^a-zA-Z\_]/',
		'replacement' => ''
	);
	$preg_replace = array(
		'pattern' => '/[^a-zA-Z0-9\_]/',
		'replacement' => ''
	);

	$dataform = array( );
	$dataform['fid'] = $nv_Request->get_int( 'fid', 'post', 0 );

	$templateids = array_unique( $nv_Request->get_typed_array( 'templateid', 'post', 'int', array( ) ) );

	if( ! empty( $templateids ) )
	{
		$dataform['listtemplate'] = implode( "|", $templateids );
	}
	else
	{
		$dataform['listtemplate'] = '';
	}

	$dataform['title'] = $nv_Request->get_title( 'title', 'post', '' );
	$dataform['description'] = $nv_Request->get_title( 'description', 'post', '' );

	$dataform['field_type'] = nv_substr( $nv_Request->get_title( 'field_type', 'post', '', 0, $preg_replace ), 0, 50 );

	$save = 0;
	$language = array( );
	if( $dataform['fid'] )
	{
		$dataform_old = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_field WHERE fid=' . $dataform['fid'] )->fetch( );
		$dataform['field_type'] = $dataform_old['field_type'];
		if( ! empty( $dataform['language'] ) )
		{
			$language = unserialize( $dataform['language'] );
		}
		$dataform['field'] = $dataform['fieldid'] = nv_substr( $nv_Request->get_title( 'fieldid', 'post', '', 0, $preg_replace ), 0, 50 );
	}
	else
	{
		$dataform['field'] = nv_substr( $nv_Request->get_title( 'field', 'post', '', 0, $validatefield ), 0, 50 );

		require_once NV_ROOTDIR . '/includes/field_not_allow.php';

		if( in_array( $dataform['field'], $field_not_allow ) )
		{
			$error = $lang_module['field_error_not_allow'];
		}
		elseif( empty( $dataform['field'] ) )
		{
			$error = $lang_module['field_error_empty'];
		}
		else
		{

			// Kiểm tra trùng trường dữ liệu
			$stmt = $db->prepare( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_field WHERE field= :field' );
			$stmt->bindParam( ':field', $dataform['field'], PDO::PARAM_STR );
			$stmt->execute( );
			if( $stmt->fetchColumn( ) )
			{
				$error = $lang_module['field_error'];
			}
		}
	}

	$language[NV_LANG_DATA] = array(
		$dataform['title'],
		$dataform['description']
	);
	if( $dataform['field_type'] == 'textbox' || $dataform['field_type'] == 'textarea' || $dataform['field_type'] == 'editor' )
	{
		$text_fields = 1;
		$dataform['match_type'] = nv_substr( $nv_Request->get_title( 'match_type', 'post', '', 0, $preg_replace ), 0, 50 );
		$dataform['match_regex'] = ($dataform['match_type'] == 'regex') ? $nv_Request->get_string( 'match_regex', 'post', '', false ) : '';
		$dataform['func_callback'] = ($dataform['match_type'] == 'callback') ? $nv_Request->get_string( 'match_callback', 'post', '', false ) : '';
		if( $dataform['func_callback'] != '' and ! function_exists( $dataform['func_callback'] ) )
		{
			$dataform['func_callback'] = '';
		}

		if( $dataform['field_type'] == 'editor' )
		{
			$dataform['editor_width'] = $nv_Request->get_string( 'editor_width', 'post', '100%', 0 );
			$dataform['editor_height'] = $nv_Request->get_string( 'editor_height', 'post', '300px', 0 );
			if( ! preg_match( '/^([0-9]+)(\%|px)+$/', $dataform['editor_width'] ) )
			{
				$dataform['editor_width'] = '100%';
			}
			if( ! preg_match( '/^([0-9]+)(\%|px)+$/', $dataform['editor_height'] ) )
			{
				$dataform['editor_height'] = '300px';
			}
		}
		$dataform['min_length'] = $nv_Request->get_int( 'min_length', 'post', 255 );
		$dataform['max_length'] = $nv_Request->get_int( 'max_length', 'post', 255 );
		$dataform['default_value'] = $nv_Request->get_title( 'default_value', 'post', '' );

		if( $dataform['min_length'] >= $dataform['max_length'] )
		{
			$error = $lang_module['field_number_error'];
		}
		else
		{
			$dataform['field_choices'] = '';
		}
	}
	elseif( $dataform['listtemplate'] == '' )
	{
		$error = $lang_module['listtemplate_error'];
	}
	elseif( $dataform['field_type'] == 'number' )
	{
		$number_fields = 1;
		$dataform['number_type'] = $nv_Request->get_int( 'number_type', 'post', 1 );
		if( $dataform['number_type'] == 1 )
		{
			$dataform['default_value_number'] = $nv_Request->get_int( 'default_value_number', 'post', 0 );
		}
		else
		{
			$dataform['default_value_number'] = $nv_Request->get_float( 'default_value_number', 'post', 0 );
		}
		$dataform['min_length'] = $nv_Request->get_int( 'min_number_length', 'post', 0 );
		$dataform['max_length'] = $nv_Request->get_int( 'max_number_length', 'post', 0 );
		$dataform['match_type'] = 'none';
		$dataform['match_regex'] = $dataform['func_callback'] = '';

		$field_choices['number_type'] = $dataform['number_type'];
		$dataform['default_value'] = $dataform['default_value_number'];

		if( $dataform['min_length'] >= $dataform['max_length'] )
		{
			$error = $lang_module['field_number_error'];
		}
		else
		{
			$dataform['field_choices'] = serialize( array( 'number_type' => $dataform['number_type'] ) );
		}
	}
	elseif( $dataform['field_type'] == 'date' )
	{
		$date_fields = 1;
		if( preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string( 'min_date', 'post' ), $m ) )
		{
			$dataform['min_length'] = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
		}
		else
		{
			$dataform['min_length'] = 0;
		}
		if( preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string( 'max_date', 'post' ), $m ) )
		{
			$dataform['max_length'] = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
		}
		else
		{
			$dataform['max_length'] = 0;
		}

		$dataform['current_date'] = $nv_Request->get_int( 'current_date', 'post', 0 );
		if( ! $dataform['current_date'] and preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string( 'default_date', 'post' ), $m ) )
		{
			$dataform['default_value'] = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
		}
		else
		{
			$dataform['default_value'] = 0;
		}
		$dataform['match_type'] = 'none';
		$dataform['match_regex'] = $dataform['func_callback'] = '';
		$field_choices['current_date'] = $dataform['current_date'];
		if( $dataform['min_length'] >= $dataform['max_length'] )
		{
			$error = $lang_module['field_date_error'];
		}
		else
		{
			$dataform['field_choices'] = serialize( array( 'current_date' => $dataform['current_date'] ) );
		}
	}
	else
	{
		$dataform['choicetypes'] = $nv_Request->get_string( 'choicetypes', 'post', '' );
		$dataform['match_type'] = 'none';
		$dataform['match_regex'] = $dataform['func_callback'] = '';
		$dataform['min_length'] = 0;
		$dataform['max_length'] = 255;
		$dataform['default_value'] = $nv_Request->get_int( 'default_value_choice', 'post', 0 );

		if( $dataform['choicetypes'] == 'field_choicetypes_text' )
		{
			$dataform['sql_choices'] = '';

			$field_choice_value = $nv_Request->get_array( 'field_choice', 'post' );
			$field_choice_text = $nv_Request->get_array( 'field_choice_text', 'post' );
			$field_choices = array_combine( array_map( 'strip_punctuation', $field_choice_value ), array_map( 'strip_punctuation', $field_choice_text ) );
			if( sizeof( $field_choices ) )
			{
				unset( $field_choices[''] );
				$dataform['field_choices'] = serialize( $field_choices );
			}
			else
			{
				$error = $lang_module['field_choices_empty'];
			}
		}
		else
		{
			$choicesql_module = $nv_Request->get_string( 'choicesql_module', 'post', '' );
			//module data
			$choicesql_table = $nv_Request->get_string( 'choicesql_table', 'post', '' );
			//table trong module
			$choicesql_column_key = $nv_Request->get_string( 'choicesql_column_key', 'post', '' );
			//cot value cho fields
			$choicesql_column_val = $nv_Request->get_string( 'choicesql_column_val', 'post', '' );
			//cot key cho fields
			$dataform['sql_choices'] = '';
			if( $choicesql_module != '' && $choicesql_table != '' && $choicesql_column_key != '' && $choicesql_column_val != '' )
			{
				$dataform['sql_choices'] = $choicesql_module . '|' . $choicesql_table . '|' . $choicesql_column_key . '|' . $choicesql_column_val;
			}
			else
			{
				$error = $lang_module['field_sql_choices_empty'];
			}
		}
	}

	if( empty( $error ) )
	{

		if( empty( $dataform['fid'] ) )
		{

			if( $dataform['max_length'] <= 4294967296 and ! empty( $dataform['field'] ) and ! empty( $dataform['title'] ) )
			{
				$weight = $db->query( 'SELECT MAX(weight) FROM ' . $db_config['prefix'] . '_' . $module_data . '_field' )->fetchColumn( );
				$weight = intval( $weight ) + 1;

				$sql = "INSERT INTO " . $db_config['prefix'] . '_' . $module_data . "_field
					(field,listtemplate, weight, field_type, field_choices, sql_choices, match_type,
					match_regex, func_callback, min_length, max_length,
					 language, default_value) VALUES
					('" . $dataform['field'] . "', '" . $dataform['listtemplate'] . "', " . $weight . ", '" . $dataform['field_type'] . "', '" . $dataform['field_choices'] . "', '" . $dataform['sql_choices'] . "', '" . $dataform['match_type'] . "',
					'" . $dataform['match_regex'] . "', '" . $dataform['func_callback'] . "',
					" . $dataform['min_length'] . ", " . $dataform['max_length'] . ",
					'" . serialize( $language ) . "', :default_value)";

				$data_insert = array( );
				$data_insert['default_value'] = $dataform['default_value'];
				$dataform['fid'] = $db->insert_id( $sql, 'fid', $data_insert );

				if( $dataform['fid'] )
				{
					$type_date = '';
					if( $dataform['field_type'] == 'number' or $dataform['field_type'] == 'date' )
					{
						$type_date = "DOUBLE NOT NULL DEFAULT '" . $dataform['default_value'] . "'";
					}
					elseif( $dataform['max_length'] <= 255 )
					{
						$type_date = "VARCHAR( " . $dataform['max_length'] . " ) NOT NULL DEFAULT ''";
					}
					elseif( $dataform['max_length'] <= 65536 )//2^16 TEXT
					{
						$type_date = 'TEXT NOT NULL';
					}
					elseif( $dataform['max_length'] <= 16777216 )//2^24 MEDIUMTEXT
					{
						$type_date = 'MEDIUMTEXT NOT NULL';
					}
					elseif( $dataform['max_length'] <= 4294967296 )//2^32 LONGTEXT
					{
						$type_date = 'LONGTEXT NOT NULL';
					}

					foreach( $templateids as $templateids_i )
					{
						$save = $db->exec( "ALTER TABLE " . $db_config['prefix'] . '_' . $module_data . "_info_" . $templateids_i . " ADD " . $dataform['field'] . " " . $type_date );
						$result = $db->query( "Select field, field_type  FROM " . $db_config['prefix'] . '_' . $module_data . "_field WHERE  listtemplate IN  (" . $templateids_i . ") " );

						while( $column = $result->fetch( ) )
						{
							$array_views[$column['field']] = $column['field_type'];
						}

						$content_2 = "<!-- BEGIN: main -->\n";						
						$content_2 .= "\t<div class=\"table-responsive\">\n\t\t<table class=\"table table-striped table-bordered table-hover\">\n";
						$content_2 .= "\t\t\t<tbody>\n";

						foreach( $array_views as $key => $input_type_i )
						{
							if( ! isset( $array_hiddens[$key] ) or isset( $array_requireds[$key] ) )
							{
								$content_2 .= "\t\t\t\t<tr>\n";
								$content_2 .= "\t\t\t\t\t<td> {LANG." . $key . "} </td>\n";

								$content_2 .= "\t\t\t\t\t<td>";

								if( $input_type_i == 'time' )
								{
									$content_2 .= "<input class=\"form-control\" type=\"text\" pattern=\"^[0-9]{2,2}\$\" name=\"custom[" . $key . "_hour]\" value=\"{ROW." . $key . "_hour}\" >:";
									$content_2 .= "<input class=\"form-control\" type=\"text\" pattern=\"^[0-9]{2,2}\$\" name=\"custom[" . $key . "_min]\" value=\"{ROW." . $key . "_min}\" >&nbsp;";
								}

								if( $input_type_i == 'textarea' )
								{
									// Nếu là textarea
									$content_2 .= "<textarea class=\"form-control\" style=\"width: 98%; height:100px;\" cols=\"75\" rows=\"5\" name=\"custom[" . $key . "]\">{ROW." . $key . "}</textarea>";
								}
								elseif( $input_type_i == 'editor' )
								{
									// Nếu là trình soạn thảo
									$content_2 .= "{ROW." . $key . "}";
								}
								elseif( $input_type_i == 'select' )
								{
									$content_2 .= "<select class=\"form-control\" name=\"custom[" . $key . "]\">\n";
									$content_2 .= "\t\t\t\t\t<option value=\"\"> --- </option>\n";
									$content_2 .= "\t\t\t\t\t<!-- BEGIN: select_" . $key . " -->\n";
									$content_2 .= "\t\t\t\t\t<option value=\"{OPTION.key}\" {OPTION.selected}>{OPTION.title}</option>\n";
									$content_2 .= "\t\t\t\t\t<!-- END: select_" . $key . " -->\n";
									$content_2 .= "\t\t\t\t</select>";
								}
								elseif( $input_type_i == 'radio' or $input_type_i == 'checkbox' )
								{
									$type_html = ($input_type_i == 'radio') ? 'radio' : 'checkbox';
									$content_2 .= "\n\t\t\t\t\t<!-- BEGIN: " . $type_html . "_" . $key . " -->\n";
									$content_2 .= "\t\t\t\t\t<input class=\"form-control\" type=\"" . $type_html . "\" name=\"custom[" . $key . "]\" value=\"{OPTION.key}\" {OPTION.checked}";

									if( isset( $array_requireds[$key] ) )
									{
										$content_2 .= 'required="required" ';
										if( $oninvalid )
										{
											$content_2 .= "oninvalid=\"setCustomValidity( nv_required )\" oninput=\"setCustomValidity('')\" ";
										}
									}
									$content_2 .= ">{OPTION.title} &nbsp; \n";
									$content_2 .= "\t\t\t\t\t<!-- END: " . $type_html . "_" . $key . " -->\n";
									$content_2 .= "\t\t\t\t";
								}
								elseif( $input_type_i == 'checkbox_groups' )
								{
									$content_2 .= "\n\t\t\t\t\t<!-- BEGIN: " . $key . " -->\n";
									$content_2 .= "\t\t\t\t\t<div class=\"row\">\n";
									$content_2 .= "\t\t\t\t\t\t<label><input class=\"form-control\" type=\"checkbox\" name=\"custom[" . $key . "][]\" value=\"{OPTION.key}\" {OPTION.checked}>{OPTION.title}</label>\n";
									$content_2 .= "\t\t\t\t\t</div>\n";
									$content_2 .= "\t\t\t\t\t<!-- END: " . $key . " -->\n";
									$content_2 .= "\t\t\t\t";
								}
								else
								{
									// Nếu là cá loại input khác
									switch( $input_type_i )
									{
										case 'email' :
											$type_html = 'email';
											break;
										case 'url' :
											$type_html = 'url';
											break;
										case 'password' :
											$type_html = 'password';
											break;
										default :
											$type_html = 'text';
									}

									$oninvalid = true;
									$content_2 .= "<input class=\"form-control\" type=\"" . $type_html . "\" name=\"custom[" . $key . "]\" value=\"{ROW." . $key . "}\" ";
									if( $input_type_i == 'date' or $input_type_i == 'time' )
									{
										$content_2 .= 'id="' . $key . '" pattern="^[0-9]{2,2}\/[0-9]{2,2}\/[0-9]{1,4}$" ';
										$array_field_js['date'][] = '#' . $key;
									}
									elseif( $input_type_i == 'textfile' )
									{
										$content_2 .= 'id="id_' . $key . '" ';
										$array_field_js['file'][] = $key;
									}
									elseif( $input_type_i == 'textalias' )
									{
										$content_2 .= 'id="id_' . $key . '" ';
									}
									elseif( $input_type_i == 'email' )
									{
										$content_2 .= "oninvalid=\"setCustomValidity( nv_email )\" oninput=\"setCustomValidity('')\" ";
										$oninvalid = false;
									}
									elseif( $input_type_i == 'url' )
									{
										$content_2 .= "oninvalid=\"setCustomValidity( nv_url )\" oninput=\"setCustomValidity('')\" ";
										$oninvalid = false;
									}
									elseif( $input_type_i == 'number_int' )
									{
										$content_2 .= "pattern=\"^[0-9]*$\"  oninvalid=\"setCustomValidity( nv_digits )\" oninput=\"setCustomValidity('')\" ";
										$oninvalid = false;
									}
									elseif( $input_type_i == 'number_float' )
									{
										$content_2 .= "pattern=\"^([0-9]*)(\.*)([0-9]+)$\" oninvalid=\"setCustomValidity( nv_number )\" oninput=\"setCustomValidity('')\" ";
										$oninvalid = false;
									}

									if( isset( $array_requireds[$key] ) )
									{
										$content_2 .= 'required="required" ';
										if( $oninvalid )
										{
											$content_2 .= "oninvalid=\"setCustomValidity( nv_required )\" oninput=\"setCustomValidity('')\" ";
										}
									}

									$content_2 .= "/>";
									if( $input_type_i == 'textfile' )
									{
										$content_2 .= '&nbsp;<button type="button" class="btn btn-info" id="img_' . $key . '"><i class="fa fa-folder-open-o">&nbsp;</i> Browse server </button>';
									}
									if( $input_type_i == 'textalias' and $array_field_js['textalias'] == $key )
									{
										$content_2 .= "&nbsp;<i class=\"fa fa-refresh fa-lg icon-pointer\" onclick=\"nv_get_alias('id_" . $key . "');\">&nbsp;</i>";
									}
								}
								$content_2 .= "</td>\n";
								$content_2 .= "\t\t\t\t</tr>\n";
							}
						}

						$content_2 .= "\t\t\t</tbody>\n";
						$content_2 .= "\t\t</table>\n";
						$content_2 .= "\t</div>\n";
						
						$content_2 .= "<!-- END: main -->";
						
						file_put_contents( NV_ROOTDIR . "/themes/admin_default/modules/" . $module_name . "/" . $array_template[$templateids_i]['title'] . ".tpl", $content_2, LOCK_EX );

					}

					$content_lang = file_get_contents( NV_ROOTDIR . '/modules/' . $module_name . '/language/admin_' . NV_LANG_DATA . '.php' );
					$content_lang .= "\$lang_module['" . $dataform['field'] . "'] = '" . $dataform['title'] . "';\n";

					file_put_contents( NV_ROOTDIR . '/modules/' . $module_name . '/language/admin_' . NV_LANG_DATA . '.php', $content_lang, LOCK_EX );

					$tablename = $db_config['prefix'] . '_' . $module_data . "_info_" . $templateids_i;

					//taofile

					Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass( ) );
					die( );
				}

			}
		}
		elseif( $dataform['max_length'] <= 4294967296 )
		{
		
			$listtem = $db->query( 'SELECT listtemplate FROM ' . $db_config['prefix'] . '_' . $module_data . '_field WHERE fid=' . $dataform['fid'] )->fetch( );

			$arr_t = explode( "|", $listtem['listtemplate'] );
			
		
			foreach( $arr_t as $arr_t_i )
			{				
				$db->query( "ALTER TABLE " . $db_config['prefix'] . '_' . $module_data . "_info_" . $arr_t_i . " DROP " . $dataform['field'] );
			}

			$query = "UPDATE " . $db_config['prefix'] . '_' . $module_data . "_field SET";
			
			if( $text_fields == 1 )
			{
				$query .= " match_type='" . $dataform['match_type'] . "',
				match_regex='" . $dataform['match_regex'] . "', func_callback='" . $dataform['func_callback'] . "', ";
			}
			$query .= " max_length=" . $dataform['max_length'] . ", min_length=" . $dataform['min_length'] . ",				
				listtemplate = '" . $dataform['listtemplate'] . "',
				field_choices='" . $dataform['field_choices'] . "',
				sql_choices = '" . $dataform['sql_choices'] . "',
				language='" . serialize( $language ) . "',
				default_value= :default_value
				WHERE fid = " . $dataform['fid'];

			$stmt = $db->prepare( $query );
			$stmt->bindParam( ':default_value', $dataform['default_value'], PDO::PARAM_STR, strlen( $dataform['default_value'] ) );
			$save = $stmt->execute( );
			
			if( $save  )
			{
				
				$type_date = '';
				if( $dataform['field_type'] == 'number' or $dataform['field_type'] == 'date' )
				{
					$type_date = "DOUBLE NOT NULL DEFAULT '" . $dataform['default_value'] . "'";
				}
				elseif( $dataform['max_length'] <= 255 )
				{
					$type_date = "VARCHAR( " . $dataform['max_length'] . " ) NOT NULL DEFAULT ''";
				}
				elseif( $dataform['max_length'] <= 65536 )//2^16 TEXT
				{
					$type_date = 'TEXT NOT NULL';
				}
				elseif( $dataform['max_length'] <= 16777216 )//2^24 MEDIUMTEXT
				{
					$type_date = 'MEDIUMTEXT NOT NULL';
				}
				elseif( $dataform['max_length'] <= 4294967296 )//2^32 LONGTEXT
				{
					$type_date = 'LONGTEXT NOT NULL';
				}

				foreach( $templateids as $templateids_i )
				{
					$save = $db->exec( "ALTER TABLE " . $db_config['prefix'] . '_' . $module_data . "_info_" . $templateids_i . " ADD " . $dataform['field'] . " " . $type_date );
				}
				
				$array_t = array_merge($templateids, $arr_t);
				$array_t = array_unique ($array_t);
				
				$array_views = array();
				foreach ($array_t as $array_t_i)
				{													
					$result = $db->query( "Select field, field_type  FROM " . $db_config['prefix'] . '_' . $module_data . "_field WHERE  listtemplate IN  (" . $array_t_i . ") " );
					
					while( $column = $result->fetch( ) )
						{
							$array_views[$column['field']] = $column['field_type'];
						}						

						$content_2 = "<!-- BEGIN: main -->\n";						
						$content_2 .= "\t<div class=\"table-responsive\">\n\t\t<table class=\"table table-striped table-bordered table-hover\">\n";
						$content_2 .= "\t\t\t<tbody>\n";

						foreach( $array_views as $key => $input_type_i )
						{
							if( ! isset( $array_hiddens[$key] ) or isset( $array_requireds[$key] ) )
							{
								$content_2 .= "\t\t\t\t<tr>\n";
								$content_2 .= "\t\t\t\t\t<td> {LANG." . $key . "} </td>\n";

								$content_2 .= "\t\t\t\t\t<td>";

								if( $input_type_i == 'time' )
								{
									$content_2 .= "<input class=\"form-control\" type=\"text\" pattern=\"^[0-9]{2,2}\$\" name=\"custom[" . $key . "_hour]\" value=\"{ROW." . $key . "_hour}\" >:";
									$content_2 .= "<input class=\"form-control\" type=\"text\" pattern=\"^[0-9]{2,2}\$\" name=\"custom[" . $key . "_min]\" value=\"{ROW." . $key . "_min}\" >&nbsp;";
								}

								if( $input_type_i == 'textarea' )
								{
									// Nếu là textarea
									$content_2 .= "<textarea class=\"form-control\" style=\"width: 98%; height:100px;\" cols=\"75\" rows=\"5\" name=\"custom[" . $key . "]\">{ROW." . $key . "}</textarea>";
								}
								elseif( $input_type_i == 'editor' )
								{
									// Nếu là trình soạn thảo
									$content_2 .= "{ROW." . $key . "}";
								}
								elseif( $input_type_i == 'select' )
								{
									$content_2 .= "<select class=\"form-control\" name=\"custom[" . $key . "]\">\n";
									$content_2 .= "\t\t\t\t\t<option value=\"\"> --- </option>\n";
									$content_2 .= "\t\t\t\t\t<!-- BEGIN: select_" . $key . " -->\n";
									$content_2 .= "\t\t\t\t\t<option value=\"{OPTION.key}\" {OPTION.selected}>{OPTION.title}</option>\n";
									$content_2 .= "\t\t\t\t\t<!-- END: select_" . $key . " -->\n";
									$content_2 .= "\t\t\t\t</select>";
								}
								elseif( $input_type_i == 'radio' or $input_type_i == 'checkbox' )
								{
									$type_html = ($input_type_i == 'radio') ? 'radio' : 'checkbox';
									$content_2 .= "\n\t\t\t\t\t<!-- BEGIN: " . $type_html . "_" . $key . " -->\n";
									$content_2 .= "\t\t\t\t\t<input class=\"form-control\" type=\"" . $type_html . "\" name=\"custom[" . $key . "]\" value=\"{OPTION.key}\" {OPTION.checked}";

									if( isset( $array_requireds[$key] ) )
									{
										$content_2 .= 'required="required" ';
										if( $oninvalid )
										{
											$content_2 .= "oninvalid=\"setCustomValidity( nv_required )\" oninput=\"setCustomValidity('')\" ";
										}
									}
									$content_2 .= ">{OPTION.title} &nbsp; \n";
									$content_2 .= "\t\t\t\t\t<!-- END: " . $type_html . "_" . $key . " -->\n";
									$content_2 .= "\t\t\t\t";
								}
								elseif( $input_type_i == 'checkbox_groups' )
								{
									$content_2 .= "\n\t\t\t\t\t<!-- BEGIN: " . $key . " -->\n";
									$content_2 .= "\t\t\t\t\t<div class=\"row\">\n";
									$content_2 .= "\t\t\t\t\t\t<label><input class=\"form-control\" type=\"checkbox\" name=\"custom[" . $key . "][]\" value=\"{OPTION.key}\" {OPTION.checked}>{OPTION.title}</label>\n";
									$content_2 .= "\t\t\t\t\t</div>\n";
									$content_2 .= "\t\t\t\t\t<!-- END: " . $key . " -->\n";
									$content_2 .= "\t\t\t\t";
								}
								else
								{
									// Nếu là cá loại input khác
									switch( $input_type_i )
									{
										case 'email' :
											$type_html = 'email';
											break;
										case 'url' :
											$type_html = 'url';
											break;
										case 'password' :
											$type_html = 'password';
											break;
										default :
											$type_html = 'text';
									}

									$oninvalid = true;
									$content_2 .= "<input class=\"form-control\" type=\"" . $type_html . "\" name=\"custom[" . $key . "]\" value=\"{ROW." . $key . "}\" ";
									if( $input_type_i == 'date' or $input_type_i == 'time' )
									{
										$content_2 .= 'id="' . $key . '" pattern="^[0-9]{2,2}\/[0-9]{2,2}\/[0-9]{1,4}$" ';
										$array_field_js['date'][] = '#' . $key;
									}
									elseif( $input_type_i == 'textfile' )
									{
										$content_2 .= 'id="id_' . $key . '" ';
										$array_field_js['file'][] = $key;
									}
									elseif( $input_type_i == 'textalias' )
									{
										$content_2 .= 'id="id_' . $key . '" ';
									}
									elseif( $input_type_i == 'email' )
									{
										$content_2 .= "oninvalid=\"setCustomValidity( nv_email )\" oninput=\"setCustomValidity('')\" ";
										$oninvalid = false;
									}
									elseif( $input_type_i == 'url' )
									{
										$content_2 .= "oninvalid=\"setCustomValidity( nv_url )\" oninput=\"setCustomValidity('')\" ";
										$oninvalid = false;
									}
									elseif( $input_type_i == 'number_int' )
									{
										$content_2 .= "pattern=\"^[0-9]*$\"  oninvalid=\"setCustomValidity( nv_digits )\" oninput=\"setCustomValidity('')\" ";
										$oninvalid = false;
									}
									elseif( $input_type_i == 'number_float' )
									{
										$content_2 .= "pattern=\"^([0-9]*)(\.*)([0-9]+)$\" oninvalid=\"setCustomValidity( nv_number )\" oninput=\"setCustomValidity('')\" ";
										$oninvalid = false;
									}

									if( isset( $array_requireds[$key] ) )
									{
										$content_2 .= 'required="required" ';
										if( $oninvalid )
										{
											$content_2 .= "oninvalid=\"setCustomValidity( nv_required )\" oninput=\"setCustomValidity('')\" ";
										}
									}

									$content_2 .= "/>";
									if( $input_type_i == 'textfile' )
									{
										$content_2 .= '&nbsp;<button type="button" class="btn btn-info" id="img_' . $key . '"><i class="fa fa-folder-open-o">&nbsp;</i> Browse server </button>';
									}
									if( $input_type_i == 'textalias' and $array_field_js['textalias'] == $key )
									{
										$content_2 .= "&nbsp;<i class=\"fa fa-refresh fa-lg icon-pointer\" onclick=\"nv_get_alias('id_" . $key . "');\">&nbsp;</i>";
									}
								}
								$content_2 .= "</td>\n";
								$content_2 .= "\t\t\t\t</tr>\n";
							}
						}

						$content_2 .= "\t\t\t</tbody>\n";
						$content_2 .= "\t\t</table>\n";
						$content_2 .= "\t</div>\n";
						
						$content_2 .= "<!-- END: main -->";						
						
						file_put_contents( NV_ROOTDIR . "/themes/admin_default/modules/" . $module_name . "/" . $array_template[$templateids_i]['title'] . ".tpl", $content_2, LOCK_EX );
						$content_2 ='';
						}
				
				
				

			}
		}
		if( $save )
		{
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass( ) );
			die( );
		}
	}
}
// DEL
if( $nv_Request->isset_request( 'del', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) )
		die( 'Wrong URL' );

	$fid = $nv_Request->get_int( 'fid', 'post', 0 );

	list( $fid, $listtemplate, $field, $weight ) = $db->query( 'SELECT fid,listtemplate, field, weight FROM ' . $db_config['prefix'] . '_' . $module_data . '_field WHERE fid=' . $fid )->fetch( 3 );
	if( $listtemplate != '' )
	{
		$array_template = explode( "|", $listtemplate );
		foreach( $array_template as $array_template_i )
		{
			$db->query( ' ALTER TABLE ' . $db_config['prefix'] . '_' . $module_data . '_info_' . $array_template_i . '  DROP ' . $field );
		}
	}

	if( $fid and ! empty( $field ) )
	{

		$query1 = 'DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_field WHERE fid=' . $fid;
		if( $db->query( $query1 ) )
		{
			$query = 'SELECT fid FROM ' . $db_config['prefix'] . '_' . $module_data . '_field WHERE weight > ' . $weight . ' ORDER BY weight ASC';
			$result = $db->query( $query );
			while( $row = $result->fetch( ) )
			{
				$db->query( 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_field SET weight=' . $weight . ' WHERE fid=' . $row['fid'] );
				++$weight;
			}
			die( 'OK' );
		}
	}
	die( 'NO' );
}
$array_field_type = array(
	'number' => $lang_module['field_type_number'],
	'date' => $lang_module['field_type_date'],
	'textbox' => $lang_module['field_type_textbox'],
	'textarea' => $lang_module['field_type_textarea'],
	'editor' => $lang_module['field_type_editor'],
	'select' => $lang_module['field_type_select'],
	'radio' => $lang_module['field_type_radio'],
	'checkbox' => $lang_module['field_type_checkbox'],
	'multiselect' => $lang_module['field_type_multiselect']
);

$array_choice_type = array(
	'field_choicetypes_sql' => $lang_module['field_choicetypes_sql'],
	'field_choicetypes_text' => $lang_module['field_choicetypes_text']
);

$xtpl = new XTemplate( 'fields.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'NV_LANG_INTERFACE', NV_LANG_INTERFACE );

// Danh sach cau hoi
$show_view = false;
if( ! $nv_Request->isset_request( 'id', 'post,get' ) )
{
	$show_view = true;

	$per_page = 6;

	$page = $nv_Request->get_int( 'page', 'post,get', 1 );
	$xtpl->assign( 'page', $page );
	$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;

	$db->sqlreset( )->select( 'COUNT(*)' )->from( '' . $db_config['prefix'] . '_' . $module_data . '_field' );

	$num_items = $db->query( $db->sql( ) )->fetchColumn( );

	$db->select( '*' )->order( 'weight ASC' )->limit( $per_page )->offset( ($page - 1) * $per_page );

	$sth = $db->prepare( $db->sql( ) );
	$sth->execute( );

}
if( $show_view )
{
	if( $num_items > 0 )
	{

		while( $row = $sth->fetch( ) )
		{

			$language = unserialize( $row['language'] );
			$xtpl->assign( 'ROW', array(
				'fid' => $row['fid'],
				'field' => $row['field'],
				'field_lang' => ( isset( $language[NV_LANG_DATA] )) ? $language[NV_LANG_DATA][0] : '',
				'field_type' => $array_field_type[$row['field_type']]
			) );

			for( $i = 1; $i <= $num_items; ++$i )
			{
				$xtpl->assign( 'WEIGHT', array(
					'key' => $i,
					'title' => $i,
					'selected' => $i == $row['weight'] ? ' selected="selected"' : ''
				) );
				$xtpl->parse( 'main.data.loop.weight' );
			}

			$xtpl->parse( 'main.data.loop' );
		}

		$xtpl->assign( 'NV_GENERATE_PAGE', nv_generate_page( $base_url, $num_items, $per_page, $page ) );
		$xtpl->parse( 'main.data' );
	}

}

$fid = $nv_Request->get_int( 'fid', 'get,post', 0 );
if( ! isset( $dataform ) )
{
	if( $fid )
	{
		$dataform = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_field WHERE fid=' . $fid )->fetch( );

		if( ! empty( $dataform['field_choices'] ) )
		{
			$field_choices = unserialize( $dataform['field_choices'] );
		}
		if( ! empty( $dataform['language'] ) )
		{
			$language = unserialize( $dataform['language'] );
			if( isset( $language[NV_LANG_DATA] ) )
			{
				$dataform['title'] = $language[NV_LANG_DATA][0];
				$dataform['description'] = $language[NV_LANG_DATA][1];
			}
		}
		$dataform['fieldid'] = $dataform['field'];
		$dataform['default_value_number'] = $dataform['default_value'];
	}
	else
	{
		$dataform = array( );
		$dataform['field_type'] = 'textbox';
		$dataform['match_type'] = 'none';
		$dataform['min_length'] = 0;
		$dataform['max_length'] = 255;
		$dataform['match_regex'] = $dataform['func_callback'] = '';
		$dataform['editor_width'] = '100%';
		$dataform['editor_height'] = '100px';
		$dataform['fieldid'] = '';
		$dataform['default_value_number'] = 0;
		$dataform['min_number'] = 0;
		$dataform['max_number'] = 1000;
		$dataform['number_type_1'] = ' checked="checked"';
		$dataform['current_date_0'] = ' checked="checked"';
		$dataform['listtemplate'] = '';
	}
}

if( $dataform['field_type'] == 'textbox' || $dataform['field_type'] == 'textarea' || $dataform['field_type'] == 'editor' )
{
	$text_fields = 1;
}
elseif( $dataform['field_type'] == 'number' )
{
	$number_fields = 1;
	$dataform['min_number'] = $dataform['min_length'];
	$dataform['max_number'] = $dataform['max_length'];
	$dataform['number_type_1'] = ($field_choices['number_type'] == 1) ? ' checked="checked"' : '';
	$dataform['number_type_2'] = ($field_choices['number_type'] == 2) ? ' checked="checked"' : '';
}
elseif( $dataform['field_type'] == 'date' )
{
	$date_fields = 1;
	$dataform['current_date_1'] = ($field_choices['current_date'] == 1) ? ' checked="checked"' : '';
	$dataform['current_date_0'] = ($field_choices['current_date'] == 0) ? ' checked="checked"' : '';
	$dataform['default_date'] = empty( $dataform['default_value'] ) ? '' : date( 'd/m/Y', $dataform['default_value'] );
	$dataform['min_date'] = empty( $dataform['min_length'] ) ? '' : date( 'd/m/Y', $dataform['min_length'] );
	$dataform['max_date'] = empty( $dataform['max_length'] ) ? '' : date( 'd/m/Y', $dataform['max_length'] );
}
else
{
	$choice_fields = 1;
	if( ! empty( $dataform['sql_choices'] ) )
	{
		$choice_type_sql = 1;
		$sql_data_choice = explode( '|', $dataform['sql_choices'] );
		$xtpl->assign( 'SQL_DATA_CHOICE', $sql_data_choice );
		$xtpl->parse( 'main.nv_load_sqlchoice' );
	}
	else
		$choice_type_text = 1;
}
if( $fid == 0 or $text_fields == 0 )
{
	$number = 1;
	if( ! empty( $field_choices ) )
	{
		foreach( $field_choices as $key => $value )
		{
			$xtpl->assign( 'FIELD_CHOICES', array(
				'checked' => ($number == $dataform['default_value']) ? ' checked="checked"' : '',
				"number" => $number++,
				'key' => $key,
				'value' => $value
			) );
			$xtpl->parse( 'main.load.loop_field_choice' );
		}
	}
	$xtpl->assign( 'FIELD_CHOICES', array(
		'number' => $number,
		'key' => '',
		'value' => ''
	) );
	$xtpl->parse( 'main.load.loop_field_choice' );
	$xtpl->assign( 'FIELD_CHOICES_NUMBER', $number );
}
$dataform['display_textfields'] = ($text_fields) ? '' : 'style="display: none;"';
$dataform['display_numberfields'] = ($number_fields) ? '' : 'style="display: none;"';
$dataform['display_datefields'] = ($date_fields) ? '' : 'style="display: none;"';
$dataform['display_choicetypes'] = ($choice_fields) ? '' : 'style="display: none;"';
$dataform['display_choiceitems'] = ($choice_type_text) ? '' : 'style="display: none;"';
$dataform['display_choicesql'] = ($choice_type_sql) ? '' : 'style="display: none;"';

$dataform['editordisabled'] = ($dataform['field_type'] != 'editor') ? ' style="display: none;"' : '';

$dataform['fielddisabled'] = ($fid) ? ' disabled="disabled"' : '';

$xtpl->assign( 'CAPTIONFORM', ($fid) ? $lang_module['captionform_edit'] . ': ' . $dataform['fieldid'] : $lang_module['captionform_add'] );
$xtpl->assign( 'DATAFORM', $dataform );
if( empty( $fid ) )
{
	$xtpl->parse( 'main.load.field' );
	foreach( $array_field_type as $key => $value )
	{
		$xtpl->assign( 'FIELD_TYPE', array(
			'key' => $key,
			'value' => $value,
			'checked' => ($dataform['field_type'] == $key) ? ' checked="checked"' : ''
		) );
		$xtpl->parse( 'main.load.field_type.loop' );
	}
	$xtpl->parse( 'main.load.field_type' );

	foreach( $array_choice_type as $key => $value )
	{
		$xtpl->assign( 'CHOICE_TYPES', array(
			'key' => $key,
			'value' => $value,
			'selected' => ($dataform['match_type'] == $key) ? ' selected="selected"' : ''
		) );
		$xtpl->parse( 'main.load.choicetypes_add.choicetypes' );
	}
	$xtpl->parse( 'main.load.choicetypes_add' );
}
else
{
	$xtpl->assign( 'FIELD_TYPE_TEXT', $array_field_type[$dataform['field_type']] );
	if( ( ! empty( $dataform['sql_choices'] )) )
	{
		$xtpl->assign( 'choicetypes_add_hidden', 'field_choicetypes_sql' );
		$xtpl->assign( 'FIELD_TYPE_SQL', $array_choice_type['field_choicetypes_sql'] );
	}
	else
	{
		$xtpl->assign( 'choicetypes_add_hidden', 'field_choicetypes_text' );
		$xtpl->assign( 'FIELD_TYPE_SQL', $array_choice_type['field_choicetypes_text'] );
	}
	$xtpl->parse( 'main.load.choicetypes_add_hidden' );
}
$array_match_type = array( );
$array_match_type['none'] = $lang_module['field_match_type_none'];
if( $dataform['field_type'] != 'editor' and $dataform['field_type'] != 'textarea' )
{
	$array_match_type['alphanumeric'] = $lang_module['field_match_type_alphanumeric'];
	$array_match_type['email'] = $lang_global['email'];
	$array_match_type['url'] = $lang_module['field_match_type_url'];
}
$array_match_type['regex'] = $lang_module['field_match_type_regex'];
$array_match_type['callback'] = $lang_module['field_match_type_callback'];
foreach( $array_match_type as $key => $value )
{
	$xtpl->assign( 'MATCH_TYPE', array(
		'key' => $key,
		'value' => $value,
		'match_value' => ($key == 'regex') ? $dataform['match_regex'] : $dataform['func_callback'],
		"checked" => ($dataform['match_type'] == $key) ? ' checked="checked"' : '',
		"match_disabled" => ($dataform['match_type'] != $key) ? ' disabled="disabled"' : ''
	) );

	if( $key == 'regex' or $key == 'callback' )
	{
		$xtpl->parse( 'main.load.match_type.match_input' );
	}
	$xtpl->parse( 'main.load.match_type' );
}

if( ! empty( $error ) )
{
	$xtpl->assign( 'ERROR', $error );
	$xtpl->parse( 'main.load.error' );
}
$array_catid_in_row = explode( '|', $dataform['listtemplate'] );

foreach( $array_template as $template_i => $array_value )
{
	$catiddisplay = (sizeof( $array_catid_in_row ) > 1 and ( in_array( $template_i, $array_catid_in_row ))) ? '' : ' display: none;';
	$temp = array(
		'id' => $template_i,
		'title' => $array_value['title'],
		'checked' => ( in_array( $template_i, $array_catid_in_row )) ? ' checked="checked"' : ''
	);
	$xtpl->assign( 'CATS', $temp );
	$xtpl->parse( 'main.load.catid' );

}

$xtpl->parse( 'main.load' );
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

$page_title = $lang_module['fields'];
$contents = nv_admin_theme( $contents );

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
