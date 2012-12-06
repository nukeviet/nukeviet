<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @createdate 12/5/2012 11:29
 */

if( ! defined( 'NV_MAINFILE' ) )
	die( 'Stop!!!' );

foreach( $array_field_config as $row_f )
{
	$value = (isset( $custom_fields[$row_f['field']] )) ? $custom_fields[$row_f['field']] : '';
	if( $value != '' )
	{
		if( $row_f['field_type'] == 'textbox' )
		{
			if( $row_f['match_type'] == 'number' )
			{
				$value = intval( $value );
			}
			elseif( $row_f['match_type'] == 'alphanumeric' )
			{
				if( ! preg_match( "/^[a-zA-Z0-9\_]+$/", $value ) )
				{
					$error = sprintf( $lang_module['field_match_type_error'], $row_f['title'] );
					break;
				}
			}
			elseif( $row_f['match_type'] == 'email' )
			{
				$error = nv_check_valid_email( $value );
			}
			elseif( $row_f['match_type'] == 'url' )
			{
				if( ! nv_is_url( $value ) )
				{
					$error = sprintf( $lang_module['field_match_type_error'], $row_f['title'] );
					break;
				}
			}
			elseif( $row_f['match_type'] == 'regex' )
			{
				if( ! preg_match( "/" . $row_f['match_regex'] . "/", $value ) )
				{
					$error = sprintf( $lang_module['field_match_type_error'], $row_f['title'] );
					break;
				}
			}
			elseif( $row_f['match_type'] == 'callback' )
			{
				if( function_exists( $row_f['func_callback'] ) )
				{
					if( ! call_user_func( $row_f['func_callback'], $value ) )
					{
						$error = sprintf( $lang_module['field_match_type_error'], $row_f['title'] );
						break;
					}
				}
				else
				{
					$error = "error function not exists " . $row_f['func_callback'];
					break;
				}
			}
			else
			{
				$value = nv_htmlspecialchars( $value );
			}
		}
		elseif( $row_f['field_type'] == 'textarea' OR $row_f['field_type'] == 'editor' )
		{
			$value = filter_text_textarea( 'custom_fields[' . $row_f['field'] . ']', '', NV_ALLOWED_HTML_TAGS );
			if( $row_f['match_type'] == 'regex' )
			{
				if( ! preg_match( "/" . $row_f['match_regex'] . "/", $value ) )
				{
					$error = sprintf( $lang_module['field_match_type_error'], $row_f['title'] );
					break;
				}
			}
			elseif( $row_f['match_type'] == 'callback' )
			{
				if( function_exists( $row_f['func_callback'] ) )
				{
					if( ! call_user_func( $row_f['func_callback'], $value ) )
					{
						$error = sprintf( $lang_module['field_match_type_error'], $row_f['title'] );
						break;
					}
				}
				else
				{
					$error = "error function not exists " . $row_f['func_callback'];
					break;
				}
			}

			$value = ($row_f['field_type'] == 'textarea') ? nv_nl2br( $value, '<br />' ) : nv_editor_nl2br( $value );
		}
		elseif( $row_f['field_type'] == 'checkbox' OR $row_f['field_type'] == 'multiselect' )
		{
			$temp_value = array( );
			foreach( $value as $value_i )
			{
				if( isset( $row_f['field_choices'][$value_i] ) )
				{
					$temp_value[] = $value_i;
				}
			}
			$value = implode( ',', $temp_value );
		}
		elseif( $row_f['field_type'] == 'select' OR $row_f['field_type'] == 'radio' )
		{
			if( isset( $row_f['field_choices'][$value_i] ) )
			{
				$error = sprintf( $lang_module['field_match_type_error'], $row_f['title'] );
			}
		}
		else
		{
			die( $row_f['field_type'] . '---------' . $value );
		}
	}
	if( ! empty( $value ) )
	{
		$strlen = nv_strlen( $value );
		if( $strlen < $row_f['min_length'] OR $strlen > $row_f['max_length'] )
		{
			$error = sprintf( $lang_module['field_min_max_error'], $row_f['title'], $row_f['min_length'], $row_f['max_length'] );
		}
	}
	elseif( $row_f['required'] )
	{
		$error = sprintf( $lang_module['field_match_type_required'], $row_f['title'] );
	}

	if( $userid )
	{
		$query_field[] = "`" . $row_f['field'] . "`=" . $db->dbescape_string( $value );
	}
	else
	{
		$query_field["`" . $row_f['field'] . "`"] = $db->dbescape_string( $value );
	}
}
?>