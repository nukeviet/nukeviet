<?php

/**
 * @Project NUKEVIET 3.4
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 - 2012 VINADES.,JSC. All rights reserved
 * @Createdate Sun, 08 Apr 2012 00:00:00 GMT GMT
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

// Chinh thu tu
if( $nv_Request->isset_request( 'changeweight', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

	$fid = $nv_Request->get_int( 'fid', 'post', 0 );
	$new_vid = $nv_Request->get_int( 'new_vid', 'post', 0 );

	if( empty( $fid ) ) die( "NO" );

	$query = "SELECT * FROM `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "_field` WHERE `fid`=" . $fid;
	$result = $db->sql_query( $query );
	$numrows = $db->sql_numrows( $result );
	if( $numrows != 1 ) die( 'NO' );

	$query = "SELECT `fid` FROM `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "_field` WHERE `fid`!=" . $fid . " ORDER BY `weight` ASC";
	$result = $db->sql_query( $query );
	$weight = 0;
	while( $row = $db->sql_fetchrow( $result ) )
	{
		++$weight;
		if( $weight == $new_vid ) ++$weight;
		$sql = "UPDATE `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "_field` SET `weight`=" . $weight . " WHERE `fid`=" . $row['fid'];
		$db->sql_query( $sql );
	}
	$sql = "UPDATE `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "_field` SET `weight`=" . $new_vid . " WHERE `fid`=" . $fid;
	$db->sql_query( $sql );
	die( "OK" );
}

// lay du lieu sql
if( $nv_Request->isset_request( 'choicesql', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

	$array_choicesql = array( "module" => "table", "table" => "column" );
	$choice = $nv_Request->get_string( 'choice', 'post', '' );
	$choice_seltected = $nv_Request->get_string( 'choice_seltected', 'post', '' );

	$xtpl = new XTemplate( "fields.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );

	if( $choice == "module" )
	{
		$xtpl->assign( 'choicesql_name', "choicesql_" . $choice );
		$xtpl->assign( 'choicesql_next', $array_choicesql[$choice] );
		$xtpl->parse( 'choicesql.loop' );
		foreach( $site_mods as $module )
		{
			$_temp_choice['sl'] = ( $choice_seltected == $module['module_data'] ) ? " selected='selected'" : "";
			$_temp_choice['key'] = $module['module_data'];
			$_temp_choice['val'] = $module['custom_title'];
			$xtpl->assign( 'SQL', $_temp_choice );
			$xtpl->parse( 'choicesql.loop' );
			unset( $_temp_choice );
		}
		$xtpl->parse( 'choicesql' );
		$contents = $xtpl->text( 'choicesql' );
	}
	elseif( $choice == "table" )
	{
		$module = $nv_Request->get_string( 'module', 'post', '' );
		if( $module == "" ) exit( "" );
		$result = $db->sql_query( "SHOW TABLE STATUS LIKE '%\_" . $module . "%'" );
		$num_table = intval( $db->sql_numrows( $result ) );
		$array_table_module = array();
		$xtpl->assign( 'choicesql_name', "choicesql_" . $choice );
		$xtpl->assign( 'choicesql_next', $array_choicesql[$choice] );

		if( $num_table > 0 )
		{
			$xtpl->parse( 'choicesql.loop' );
			while( $item = $db->sql_fetch_assoc( $result ) )
			{
				$_temp_choice['sl'] = ( $choice_seltected == $item['Name'] ) ? " selected='selected'" : "";
				$_temp_choice['key'] = $item['Name'];
				$_temp_choice['val'] = $item['Name'];
				$xtpl->assign( 'SQL', $_temp_choice );
				$xtpl->parse( 'choicesql.loop' );
				unset( $_temp_choice );
			}
		}
		$xtpl->parse( 'choicesql' );
		$contents = $xtpl->text( 'choicesql' );
	}
	elseif( $choice == "column" )
	{
		$table = $nv_Request->get_string( 'table', 'post', '' );
		if( $table == "" ) exit( "" );

		$result = $db->sql_query( "SHOW COLUMNS FROM " . $table );
		$num_table = intval( $db->sql_numrows( $result ) );
		$array_table_module = array();
		$xtpl->assign( 'choicesql_name', "choicesql_" . $choice );
		$xtpl->assign( 'choicesql_next', $array_choicesql[$choice] );
		if( $num_table > 0 )
		{
			$choice_seltected = explode( "|", $choice_seltected );
			while( $item = $db->sql_fetch_assoc( $result ) )
			{
				$_temp_choice['sl_key'] = ( $choice_seltected[0] == $item['Field'] ) ? " selected='selected'" : "";
				$_temp_choice['sl_val'] = ( $choice_seltected[1] == $item['Field'] ) ? " selected='selected'" : "";
				$_temp_choice['key'] = $item['Field'];
				$_temp_choice['val'] = $item['Field'];
				$xtpl->assign( 'SQL', $_temp_choice );
				$xtpl->parse( 'column.loop1' );
				$xtpl->parse( 'column.loop2' );
				unset( $_temp_choice );
			}
		}
		$xtpl->parse( 'column' );
		$contents = $xtpl->text( 'column' );
	}

	include ( NV_ROOTDIR . "/includes/header.php" );
	echo $contents;
	include ( NV_ROOTDIR . "/includes/footer.php" );
}

//ADD
$error = '';
$field_choices = array();
if( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$preg_replace = array( 'pattern' => "/[^a-zA-Z0-9\_]/", 'replacement' => '' );

	$dataform = array();
	$dataform['fid'] = $nv_Request->get_int( 'fid', 'post', 0 );

	$dataform['title'] = filter_text_input( 'title', 'post', '' );
	$dataform['description'] = filter_text_input( 'description', 'post', '' );

	$dataform['required'] = $nv_Request->get_int( 'required', 'post', 0 );
	$dataform['show_register'] = ( $dataform['required'] ) ? 1 : $nv_Request->get_int( 'show_register', 'post', 0 );
	$dataform['user_editable'] = $nv_Request->get_int( 'user_editable', 'post', 0 );
	$dataform['user_editable_once'] = $nv_Request->get_int( 'user_editable_once', 'post', 0 );
	$dataform['show_profile'] = $nv_Request->get_int( 'show_profile', 'post', 0 );
	$dataform['class'] = filter_text_input( 'class', 'post', '', 0, 50, $preg_replace );
	if( $dataform['user_editable'] )
	{
		$dataform['user_editable_save'] = ( $dataform['user_editable_once'] ) ? 'once' : 'yes';
	}
	else
	{
		$dataform['user_editable_save'] = 'never';
	}
	$dataform['field_type'] = filter_text_input( 'field_type', 'post', '', 0, 50, $preg_replace );

	$save = 0;
	$language = array();
	if( $dataform['fid'] )
	{
		$dataform_old = $db->sql_fetch_assoc( $db->sql_query( "SELECT * FROM `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "_field` WHERE `fid`=" . $dataform['fid'] ) );
		$dataform['field_type'] = $dataform_old['field_type'];
		if( ! empty( $dataform['language'] ) )
		{
			$language = unserialize( $dataform['language'] );
		}
		$dataform['field'] = $dataform['fieldid'] = filter_text_input( 'fieldid', 'post', '', 0, 50, $preg_replace );
	}
	else
	{
		$dataform['field'] = filter_text_input( 'field', 'post', '', 0, 50, $preg_replace );
	}
	$language[NV_LANG_DATA] = array( $dataform['title'], $dataform['description'] );
	if( $dataform['field_type'] == 'textbox' || $dataform['field_type'] == 'textarea' || $dataform['field_type'] == 'editor' )
	{
		$text_fields = 1;
		$dataform['match_type'] = filter_text_input( 'match_type', 'post', '', 0, 50, $preg_replace );
		$dataform['match_regex'] = ( $dataform['match_type'] == 'regex' ) ? $nv_Request->get_string( 'match_regex', 'post', '', false ) : '';
		$dataform['func_callback'] = ( $dataform['match_type'] == 'callback' ) ? $nv_Request->get_string( 'match_callback', 'post', '', false ) : '';
		if( $dataform['func_callback'] != '' and ! function_exists( $dataform['func_callback'] ) )
		{
			$dataform['func_callback'] = '';
		}

		if( $dataform['field_type'] == 'editor' )
		{
			$dataform['editor_width'] = $nv_Request->get_string( 'editor_width', 'post', '100%', 0 );
			$dataform['editor_height'] = $nv_Request->get_string( 'editor_height', 'post', '300px', 0 );
			if( ! preg_match( "/^([0-9]+)(\%|px)+$/", $dataform['editor_width'] ) )
			{
				$dataform['editor_width'] = '100%';
			}
			if( ! preg_match( "/^([0-9]+)(\%|px)+$/", $dataform['editor_height'] ) )
			{
				$dataform['editor_height'] = '300px';
			}
			$dataform['class'] = $dataform['editor_width'] . '@' . $dataform['editor_height'];
		}
		$dataform['min_length'] = $nv_Request->get_int( 'min_length', 'post', 255 );
		$dataform['max_length'] = $nv_Request->get_int( 'max_length', 'post', 255 );
		$dataform['default_value'] = filter_text_input( 'default_value', 'post', '' );
		$dataform['field_choices'] = '';
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
		if( preg_match( "/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/", $nv_Request->get_string( 'min_date', 'post' ), $m ) )
		{
			$dataform['min_length'] = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
		}
		else
		{
			$dataform['min_length'] = 0;
		}
		if( preg_match( "/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/", $nv_Request->get_string( 'max_date', 'post' ), $m ) )
		{
			$dataform['max_length'] = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
		}
		else
		{
			$dataform['max_length'] = 0;
		}

		$dataform['current_date'] = $nv_Request->get_int( 'current_date', 'post', 0 );
		if( ! $dataform['current_date'] and preg_match( "/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/", $nv_Request->get_string( 'default_date', 'post' ), $m ) )
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

		if( $dataform['choicetypes'] == "field_choicetypes_text" )
		{
			$field_choice_value = $nv_Request->get_array( 'field_choice', 'post' );
			$field_choice_text = $nv_Request->get_array( 'field_choice_text', 'post' );
			$field_choices = array_combine( array_map( 'strip_punctuation', $field_choice_value ), array_map( 'strip_punctuation', $field_choice_text ) );
			if( sizeof( $field_choices ) )
			{
				unset( $field_choices[""] );
				$dataform['field_choices'] = serialize( $field_choices );
			}
			else
			{
				$error = $lang_module['field_choices_empty'];
			}
		}
		else
		{
			$choicesql_module = $nv_Request->get_string( 'choicesql_module', 'post', '' ); //module data
			$choicesql_table = $nv_Request->get_string( 'choicesql_table', 'post', '' ); //table trong module
			$choicesql_column_key = $nv_Request->get_string( 'choicesql_column_key', 'post', '' ); //cot value cho fields
			$choicesql_column_val = $nv_Request->get_string( 'choicesql_column_val', 'post', '' ); //cot key cho fields
			$dataform['sql_choices'] = "";
			if( $choicesql_module != "" && $choicesql_table != "" && $choicesql_column_key != "" && $choicesql_column_val != "" )
			{
				$dataform['sql_choices'] = $choicesql_module . "|" . $choicesql_table . "|" . $choicesql_column_key . "|" . $choicesql_column_val;
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
			$numrows = $db->sql_numrows( $db->sql_query( "SHOW COLUMNS FROM `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "` WHERE `Field`='" . $dataform['field'] . "'" ) );

			if( empty( $numrows ) and $dataform['max_length'] <= 4294967296 and ! empty( $dataform['field'] ) and ! empty( $dataform['title'] ) )
			{
				list( $weight ) = $db->sql_fetchrow( $db->sql_query( "SELECT MAX(`weight`) FROM `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "_field`" ) );
				$weight = intval( $weight ) + 1;

				$dataform['fid'] = $db->sql_query_insert_id( "INSERT INTO `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "_field` 
					(`fid`, `field`, `weight`, `field_type`, `field_choices`, `sql_choices`, `match_type`, 
					`match_regex`, `func_callback`, `min_length`, `max_length`, 
					`required`, `show_register`, `user_editable`, 
					`show_profile`, `class`, `language`, `default_value`) VALUES
					(NULL, '" . $dataform['field'] . "', " . $weight . ", '" . $dataform['field_type'] . "', '" . $dataform['field_choices'] . "', '" . $dataform['sql_choices'] . "', '" . $dataform['match_type'] . "', 
					'" . $dataform['match_regex'] . "', '" . $dataform['func_callback'] . "', 
					" . $dataform['min_length'] . ", " . $dataform['max_length'] . ", 
					" . $dataform['required'] . ", " . $dataform['show_register'] . ", '" . $dataform['user_editable_save'] . "', 
					" . $dataform['show_profile'] . ", '" . $dataform['class'] . "', '" . serialize( $language ) . "', " . $db->dbescape_string( $dataform['default_value'] ) . ")" );

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
					elseif( $dataform['max_length'] <= 65536 ) //2^16 TEXT
					{
						$type_date = 'TEXT NOT NULL';
					}
					elseif( $dataform['max_length'] <= 16777216 ) //2^24 MEDIUMTEXT
					{
						$type_date = 'MEDIUMTEXT NOT NULL';
					}
					elseif( $dataform['max_length'] <= 4294967296 ) //2^32 LONGTEXT
					{
						$type_date = 'LONGTEXT NOT NULL';
					}
					$save = $db->sql_query( "ALTER TABLE `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "_info` ADD `" . $dataform['field'] . "` " . $type_date );
				}
			}
		}
		elseif( $dataform['max_length'] <= 4294967296 )
		{
			$query = "UPDATE `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "_field` SET";
			if( $text_fields = 1 )
			{
				$query .= " `field_choices`='" . $dataform['field_choices'] . "', `match_type`='" . $dataform['match_type'] . "', 
				`match_regex`='" . $dataform['match_regex'] . "', `func_callback`='" . $dataform['func_callback'] . "', ";
			}
			$query .= " `max_length`=" . $dataform['max_length'] . ", `min_length`=" . $dataform['min_length'] . ",
				`required` = '" . $dataform['required'] . "',
				`sql_choices` = '" . $dataform['sql_choices'] . "', 
				`show_register` = '" . $dataform['show_register'] . "', 
				`user_editable` = '" . $dataform['user_editable_save'] . "', 
				`show_profile` = '" . $dataform['show_profile'] . "', 
				`class` = '" . $dataform['class'] . "', 
				`language`='" . serialize( $language ) . "', 
				`default_value`=" . $db->dbescape_string( $dataform['default_value'] ) . " 
				WHERE `fid` = " . $dataform['fid'];
			$save = $db->sql_query( $query );
			if( $save and $dataform['max_length'] != $dataform_old['max_length'] )
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
				elseif( $dataform['max_length'] <= 65536 ) //2^16 TEXT
				{
					$type_date = 'TEXT NOT NULL';
				}
				elseif( $dataform['max_length'] <= 16777216 ) //2^24 MEDIUMTEXT
				{
					$type_date = 'MEDIUMTEXT NOT NULL';
				}
				elseif( $dataform['max_length'] <= 4294967296 ) //2^32 LONGTEXT
				{
					$type_date = 'LONGTEXT NOT NULL';
				}
				$save = $db->sql_query( "ALTER TABLE `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "_info` CHANGE `" . $dataform_old['field'] . "` `" . $dataform_old['field'] . "` " . $type_date );
			}
		}
		if( $save )
		{
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&rand=" . nv_genpass() );
			die();
		}
	}
}
// DEL
if( $nv_Request->isset_request( 'del', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

	$fid = $nv_Request->get_int( 'fid', 'post', 0 );

	list( $fid, $field, $weight ) = $db->sql_fetchrow( $db->sql_query( "SELECT `fid`, `field`, `weight` FROM `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "_field` WHERE `fid`=" . $fid ) );

	if( $fid and ! empty( $field ) )
	{
		$query1 = "DELETE FROM `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "_field` WHERE `fid`=" . $fid;
		$query2 = "ALTER TABLE `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "_info` DROP `" . $field . "`";
		if( $db->sql_query( $query1 ) and $db->sql_query( $query2 ) )
		{
			$query = "SELECT `fid` FROM `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "_field` WHERE `weight` > " . $weight . " ORDER BY `weight` ASC";
			$result = $db->sql_query( $query );
			while( $row = $db->sql_fetchrow( $result ) )
			{
				$db->sql_query( "UPDATE `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "_field` SET `weight`=" . $weight . " WHERE `fid`=" . $row['fid'] );
				++$weight;
			}
			die( "OK" );
		}
	}
	die( "NO" );
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

$array_choice_type = array( "field_choicetypes_sql" => $lang_module['field_choicetypes_sql'], "field_choicetypes_text" => $lang_module['field_choicetypes_text'] );

$xtpl = new XTemplate( "fields.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'NV_LANG_INTERFACE', NV_LANG_INTERFACE );

// Danh sach cau hoi
if( $nv_Request->isset_request( 'qlist', 'get' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );
	$sql = "SELECT * FROM `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "_field` ORDER BY `weight` ASC";
	$result = $db->sql_query( $sql );
	$num = $db->sql_numrows( $result );
	if( $num )
	{
		$a = 0;
		while( $row = $db->sql_fetch_assoc( $result ) )
		{
			$language = unserialize( $row['language'] );
			$xtpl->assign( 'ROW', array(
				"class" => ( $a % 2 ) ? " class=\"second\"" : "",
				"fid" => $row['fid'],
				"field" => $row['field'],
				"field_lang" => ( isset( $language[NV_LANG_DATA] ) ) ? $language[NV_LANG_DATA][0] : '',
				"field_type" => $array_field_type[$row['field_type']],
				"required" => ( $row['required'] ) ? ' checked="checked"' : '',
				"show_register" => ( $row['show_register'] ) ? ' checked="checked"' : '',
				"show_profile" => ( $row['show_profile'] ) ? ' checked="checked"' : ''
			) );

			for( $i = 1; $i <= $num; ++$i )
			{
				$xtpl->assign( 'WEIGHT', array(
					"key" => $i,
					"title" => $i,
					"selected" => $i == $row['weight'] ? " selected=\"selected\"" : ""
				) );
				$xtpl->parse( 'main.data.loop.weight' );
			}

			$xtpl->parse( 'main.data.loop' );
			++$a;
		}

		$xtpl->parse( 'main.data' );
	}
	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );
}
else
{
	$fid = $nv_Request->get_int( 'fid', 'get,post', 0 );
	if( ! isset( $dataform ) )
	{
		if( $fid )
		{
			$dataform = $db->sql_fetch_assoc( $db->sql_query( "SELECT * FROM `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "_field` WHERE `fid`=" . $fid ) );

			if( $dataform['user_editable'] == 'never' )
			{
				$dataform['user_editable'] = 0;
				$dataform['user_editable_once'] = 0;
			}
			else
			{
				$dataform['user_editable_once'] = ( $dataform['user_editable'] == 'once' ) ? 1 : 0;
				$dataform['user_editable'] = 1;
			}
			if( $dataform['field_type'] == 'editor' )
			{
				$array_tmp = explode( "@", $dataform['class'] );
				$dataform['editor_width'] = $array_tmp[0];
				$dataform['editor_height'] = $array_tmp[1];
				$dataform['class'] = '';
			}
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
			$dataform = array();
			$dataform['show_register'] = 1;
			$dataform['required'] = 0;
			$dataform['show_profile'] = 1;
			$dataform['user_editable'] = 1;
			$dataform['user_editable_once'] = 0;
			$dataform['show_register'] = 1;
			$dataform['field_type'] = 'textbox';
			$dataform['match_type'] = 'none';
			$dataform['min_length'] = 0;
			$dataform['max_length'] = 255;
			$dataform['match_regex'] = $dataform['func_callback'] = '';
			$dataform['editor_width'] = '100%';
			$dataform['editor_height'] = '100px';
			$dataform['fieldid'] = '';
			$dataform['class'] = 'input';
			$dataform['default_value_number'] = 0;
			$dataform['min_number'] = 0;
			$dataform['max_number'] = 1000;
			$dataform['number_type_1'] = ' checked="checked"';
			$dataform['current_date_0'] = ' checked="checked"';
		}
	}

	$text_fields = $number_fields = $date_fields = $choice_fields = $choice_type_sql = $choice_type_text = 0;
	if( $dataform['field_type'] == 'textbox' || $dataform['field_type'] == 'textarea' || $dataform['field_type'] == 'editor' )
	{
		$text_fields = 1;
	}
	elseif( $dataform['field_type'] == 'number' )
	{
		$number_fields = 1;
		$dataform['min_number'] = $dataform['min_length'];
		$dataform['max_number'] = $dataform['max_length'];
		$dataform['number_type_1'] = ( $field_choices['number_type'] == 1 ) ? ' checked="checked"' : '';
		$dataform['number_type_2'] = ( $field_choices['number_type'] == 2 ) ? ' checked="checked"' : '';
	}
	elseif( $dataform['field_type'] == 'date' )
	{
		$date_fields = 1;
		$dataform['current_date_1'] = ( $field_choices['current_date'] == 1 ) ? ' checked="checked"' : '';
		$dataform['current_date_0'] = ( $field_choices['current_date'] == 0 ) ? ' checked="checked"' : '';
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
			$sql_data_choice = explode( "|", $dataform['sql_choices'] );
			$xtpl->assign( 'SQL_DATA_CHOICE', $sql_data_choice );
			$xtpl->parse( 'main.nv_load_sqlchoice' );
		}
		else $choice_type_text = 1;
	}
	if( $fid == 0 or $text_fields == 0 )
	{
		$number = 1;
		if( ! empty( $field_choices ) )
		{
			foreach( $field_choices as $key => $value )
			{
				$xtpl->assign( 'FIELD_CHOICES', array(
					"class" => ( $number % 2 == 0 ) ? ' class="second"' : '',
					"checked" => ( $number == $dataform['default_value'] ) ? ' checked="checked"' : '',
					"number" => $number++,
					"key" => $key,
					"value" => $value
				) );
				$xtpl->parse( 'main.load.loop_field_choice' );
			}
		}
		$xtpl->assign( 'FIELD_CHOICES', array(
			"class" => ( $number % 2 == 0 ) ? ' class="second"' : '',
			"number" => $number,
			"key" => '',
			"value" => ''
		) );
		$xtpl->parse( 'main.load.loop_field_choice' );
		$xtpl->assign( 'FIELD_CHOICES_NUMBER', $number );
	}
	$dataform['display_textfields'] = ( $text_fields ) ? '' : 'style="display: none;"';
	$dataform['display_numberfields'] = ( $number_fields ) ? '' : 'style="display: none;"';
	$dataform['display_datefields'] = ( $date_fields ) ? '' : 'style="display: none;"';
	$dataform['display_choicetypes'] = ( $choice_fields ) ? '' : 'style="display: none;"';
	$dataform['display_choiceitems'] = ( $choice_type_text ) ? '' : 'style="display: none;"';
	$dataform['display_choicesql'] = ( $choice_type_sql ) ? '' : 'style="display: none;"';

	$dataform['editordisabled'] = ( $dataform['field_type'] != 'editor' ) ? ' style="display: none;"' : '';
	$dataform['classdisabled'] = ( $dataform['field_type'] == 'editor' ) ? ' style="display: none;"' : '';

	$dataform['fielddisabled'] = ( $fid ) ? ' disabled="disabled"' : '';
	$dataform['required'] = ( $dataform['required'] ) ? ' checked="checked"' : '';
	$dataform['show_register'] = ( $dataform['show_register'] ) ? ' checked="checked"' : '';
	$dataform['show_profile'] = ( $dataform['show_profile'] ) ? ' checked="checked"' : '';
	$dataform['user_editable'] = ( $dataform['user_editable'] ) ? ' checked="checked"' : '';
	$dataform['user_editable_once'] = ( $dataform['user_editable_once'] ) ? ' checked="checked"' : '';

	$xtpl->assign( 'CAPTIONFORM', ( $fid ) ? $lang_module['captionform_edit'] . ': ' . $dataform['fieldid'] : $lang_module['captionform_add'] );
	$xtpl->assign( 'DATAFORM', $dataform );
	if( empty( $fid ) )
	{
		$xtpl->parse( 'main.load.field' );
		foreach( $array_field_type as $key => $value )
		{
			$xtpl->assign( 'FIELD_TYPE', array(
				"key" => $key,
				"value" => $value,
				"checked" => ( $dataform['field_type'] == $key ) ? ' checked="checked"' : ''
			) );
			$xtpl->parse( 'main.load.field_type.loop' );
		}
		$xtpl->parse( 'main.load.field_type' );

		foreach( $array_choice_type as $key => $value )
		{
			$xtpl->assign( 'CHOICE_TYPES', array(
				"key" => $key,
				"value" => $value,
				"selected" => ( $dataform['match_type'] == $key ) ? ' selected="selected"' : ''
			) );
			$xtpl->parse( 'main.load.choicetypes_add.choicetypes' );
		}
		$xtpl->parse( 'main.load.choicetypes_add' );
	}
	else
	{
		$xtpl->assign( 'FIELD_TYPE_TEXT', $array_field_type[$dataform['field_type']] );
		if( ( ! empty( $dataform['sql_choices'] ) ) )
		{
			$xtpl->assign( 'choicetypes_add_hidden', "field_choicetypes_sql" );
			$xtpl->assign( 'FIELD_TYPE_SQL', $array_choice_type['field_choicetypes_sql'] );
		}
		else
		{
			$xtpl->assign( 'choicetypes_add_hidden', "field_choicetypes_text" );
			$xtpl->assign( 'FIELD_TYPE_SQL', $array_choice_type['field_choicetypes_text'] );
		}
		$xtpl->parse( 'main.load.choicetypes_add_hidden' );
	}
	$array_match_type = array();
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
			"key" => $key,
			"value" => $value,
			"match_value" => ( $key == 'regex' ) ? $dataform['match_regex'] : $dataform['func_callback'],
			"checked" => ( $dataform['match_type'] == $key ) ? ' checked="checked"' : '',
			"match_disabled" => ( $dataform['match_type'] != $key ) ? ' disabled="disabled"' : ''
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

	$xtpl->parse( 'main.load' );
	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );

	$page_title = $lang_module['fields'];
	$contents = nv_admin_theme( $contents );
}
include ( NV_ROOTDIR . "/includes/header.php" );
echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>