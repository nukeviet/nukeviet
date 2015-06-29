<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/5/2012 11:29
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

foreach( $array_field_config as $row_f )
{
	$value = ( isset( $custom_fields[$row_f['field']] ) ) ? $custom_fields[$row_f['field']] : '';

	if( $value != '' )
	{
		if( $row_f['field_type'] == 'number' )
		{
			$number_type = $row_f['field_choices']['number_type'];
			$pattern = ( $number_type == 1 ) ? "/^[0-9]+$/" : "/^[0-9\.]+$/";

			if( ! preg_match( $pattern, $value ) )
			{
				$error = sprintf( $lang_module['field_match_type_error'], $row_f['title'] );
			}
			else
			{
				$value = ( $number_type == 1 ) ? intval( $value ) : floatval( $value );

				if( $value < $row_f['min_length'] or $value > $row_f['max_length'] )
				{
					$error = sprintf( $lang_module['field_min_max_value'], $row_f['title'], $row_f['min_length'], $row_f['max_length'] );
				}
			}
		}
		elseif( $row_f['field_type'] == 'date' )
		{
			if( preg_match( "/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/", $value, $m ) )
			{
				$value = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );

				if( $value < $row_f['min_length'] or $value > $row_f['max_length'] )
				{
					$error = sprintf( $lang_module['field_min_max_value'], $row_f['title'], date( 'd/m/Y', $row_f['min_length'] ), date( 'd/m/Y', $row_f['max_length'] ) );
				}
			}
			else
			{
				$error = sprintf( $lang_module['field_match_type_error'], $row_f['title'] );
			}
		}
		elseif( $row_f['field_type'] == 'textbox' )
		{
			if( $row_f['match_type'] == 'alphanumeric' )
			{
				if( ! preg_match( "/^[a-zA-Z0-9\_]+$/", $value ) )
				{
					$error = sprintf( $lang_module['field_match_type_error'], $row_f['title'] );
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
				}
			}
			elseif( $row_f['match_type'] == 'regex' )
			{
				if( ! preg_match( "/" . $row_f['match_regex'] . "/", $value ) )
				{
					$error = sprintf( $lang_module['field_match_type_error'], $row_f['title'] );
				}
			}
			elseif( $row_f['match_type'] == 'callback' )
			{
				if( function_exists( $row_f['func_callback'] ) )
				{
					if( ! call_user_func( $row_f['func_callback'], $value ) )
					{
						$error = sprintf( $lang_module['field_match_type_error'], $row_f['title'] );
					}
				}
				else
				{
					$error = "error function not exists " . $row_f['func_callback'];
				}
			}
			else
			{
				$value = nv_htmlspecialchars( $value );
			}

			$strlen = nv_strlen( $value );

			if( $strlen < $row_f['min_length'] or $strlen > $row_f['max_length'] )
			{
				$error = sprintf( $lang_module['field_min_max_error'], $row_f['title'], $row_f['min_length'], $row_f['max_length'] );
			}
		}
		elseif( $row_f['field_type'] == 'textarea' or $row_f['field_type'] == 'editor' )
		{
			$allowed_html_tags = array_map( "trim", explode( ',', NV_ALLOWED_HTML_TAGS ) );
			$allowed_html_tags = "<" . implode( "><", $allowed_html_tags ) . ">";
			$value = strip_tags( $value, $allowed_html_tags );
			$value = nv_nl2br( $value, '<br />' );

			if( $row_f['match_type'] == 'regex' )
			{
				if( ! preg_match( "/" . $row_f['match_regex'] . "/", $value ) )
				{
					$error = sprintf( $lang_module['field_match_type_error'], $row_f['title'] );
				}
			}
			elseif( $row_f['match_type'] == 'callback' )
			{
				if( function_exists( $row_f['func_callback'] ) )
				{
					if( ! call_user_func( $row_f['func_callback'], $value ) )
					{
						$error = sprintf( $lang_module['field_match_type_error'], $row_f['title'] );
					}
				}
				else
				{
					$error = "error function not exists " . $row_f['func_callback'];
				}
			}

			$value = ( $row_f['field_type'] == 'textarea' ) ? nv_nl2br( $value, '<br />' ) : nv_editor_nl2br( $value );
			$strlen = nv_strlen( $value );

			if( $strlen < $row_f['min_length'] or $strlen > $row_f['max_length'] )
			{
				$error = sprintf( $lang_module['field_min_max_error'], $row_f['title'], $row_f['min_length'], $row_f['max_length'] );
			}
		}
		elseif( $row_f['field_type'] == 'checkbox' or $row_f['field_type'] == 'multiselect' )
		{
			$temp_value = array();
			foreach( $value as $value_i )
			{
				if( isset( $row_f['field_choices'][$value_i] ) )
				{
					$temp_value[] = $value_i;
				}
			}

			$value = implode( ',', $temp_value );
		}
		elseif( $row_f['field_type'] == 'select' or $row_f['field_type'] == 'radio' )
		{
			if( ! isset( $row_f['field_choices'][$value] ) )
			{
				$error = sprintf( $lang_module['field_match_type_error'], $row_f['title'] );
			}
		}

		$custom_fields[$row_f['field']] = $value;
	}

	if( empty( $value ) and $row_f['required'] )
	{
		$error = sprintf( $lang_module['field_match_type_required'], $row_f['title'] );
	}

	if( $userid )
	{
		$query_field[] = $row_f['field'] . "=" . $db->quote( $value );
	}
	else
	{
		$query_field[$row_f['field']] = $db->quote( $value );
	}
}