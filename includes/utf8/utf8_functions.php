<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 23/8/2010, 0:13
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

/**
 * utf8_to_unicode()
 * Vie^.t Nam => Array ( [0] => 86 [1] => 105 [2] => 7879 [3] => 116 [4] => 32 [5] => 78 [6] => 97 [7] => 109 )
 * @param mixed $str
 * @return
 */
function utf8_to_unicode( $str )
{
	$unicode = array();
	$values = array();
	$lookingFor = 1;
	$strlen = strlen( $str );

	for( $i = 0; $i < $strlen; ++$i )
	{
		$thisValue = ord( $str[$i] );

		if( $thisValue < 128 ) $unicode[] = $thisValue;
		else
		{
			if( sizeof( $values ) == 0 ) $lookingFor = ( $thisValue < 224 ) ? 2 : 3;

			$values[] = $thisValue;

			if( sizeof( $values ) == $lookingFor )
			{
				$number = ( $lookingFor == 3 ) ? ( ( $values[0] % 16 ) * 4096 ) + ( ( $values[1] % 64 ) * 64 ) + ( $values[2] % 64 ) : ( ( $values[0] % 32 ) * 64 ) + ( $values[1] % 64 );

				$unicode[] = $number;
				$values = array();
				$lookingFor = 1;
			}
		}
	}

	return $unicode;
}

/**
 * unicode_to_entities()
 * Array ( [0] => 86 [1] => 105 [2] => 7879 [3] => 116 [4] => 32 [5] => 78 [6] => 97 [7] => 109 ) => &#86;&#105;&#7879;&#116;&#32;&#78;&#97;&#109;
 *
 * @param mixed $unicode
 * @return
 */
function unicode_to_entities( $unicode )
{
	$entities = '';
	foreach( $unicode as $value )
	{
		$entities .= '&#' . $value . ';';
	}
	return $entities;
}

/**
 * unicode_to_entities_preserving_ascii()
 * Array ( [0] => 86 [1] => 105 [2] => 7879 [3] => 116 [4] => 32 [5] => 78 [6] => 97 [7] => 109 ) => Vi&#7879;t Nam
 *
 * @param mixed $unicode
 * @return
 */
function unicode_to_entities_preserving_ascii( $unicode )
{
	$entities = '';
	foreach( $unicode as $value )
	{
		$entities .= ( $value > 127 ) ? '&#' . $value . ';' : chr( $value );
	}
	return $entities;
}

/**
 * unicode_to_utf8()
 * Array ( [0] => 86 [1] => 105 [2] => 7879 [3] => 116 [4] => 32 [5] => 78 [6] => 97 [7] => 109 ) => Vie^.t Nam
 *
 * @param mixed $str
 * @return
 */
function unicode_to_utf8( $str )
{
	$utf8 = '';

	foreach( $str as $unicode )
	{
		if( $unicode < 128 )
		{
			$utf8 .= chr( $unicode );
		}
		elseif( $unicode < 2048 )
		{
			$utf8 .= chr( 192 + ( ( $unicode - ( $unicode % 64 ) ) / 64 ) );
			$utf8 .= chr( 128 + ( $unicode % 64 ) );
		}
		else
		{
			$utf8 .= chr( 224 + ( ( $unicode - ( $unicode % 4096 ) ) / 4096 ) );
			$utf8 .= chr( 128 + ( ( ( $unicode % 4096 ) - ( $unicode % 64 ) ) / 64 ) );
			$utf8 .= chr( 128 + ( $unicode % 64 ) );
		}
	}

	return $utf8;
}

/**
 * nv_str_split()
 *
 * @param mixed $str
 * @param integer $split_len
 * @return
 */
function nv_str_split( $str, $split_len = 1 )
{
	if( ! is_int( $split_len ) || $split_len < 1 )
	{
		return false;
	}

	$len = nv_strlen( $str );
	if( $len <= $split_len )
	{
		return array( $str );
	}

	preg_match_all( '/.{' . $split_len . '}|[^\x00]{1,' . $split_len . '}$/us', $str, $ar );
	return $ar[0];
}

/**
 * nv_strspn()
 *
 * @param mixed $str
 * @param mixed $mask
 * @param mixed $start
 * @param mixed $length
 * @return
 */
function nv_strspn( $str, $mask, $start = null, $length = null )
{
	if( $start !== null || $length !== null )
	{
		$str = nv_substr( $str, $start, $length );
	}

	preg_match( '/^[' . $mask . ']+/u', $str, $matches );

	if( isset( $matches[0] ) )
	{
		return nv_strlen( $matches[0] );
	}

	return 0;
}

/**
 * nv_ucfirst()
 *
 * @param mixed $str
 * @return
 */
function nv_ucfirst( $str )
{
	switch( nv_strlen( $str ) )
	{
		case 0:
			return '';
			break;

		case 1:
			return nv_strtoupper( $str );
			break;

		default:
			preg_match( '/^(.{1})(.*)$/us', $str, $matches );
			return nv_strtoupper( $matches[1] ) . $matches[2];
			break;
	}
}

/**
 * nv_ltrim()
 *
 * @param mixed $str
 * @param bool $charlist
 * @return
 */
function nv_ltrim( $str, $charlist = false )
{
	if( $charlist === false ) return ltrim( $str );

	$charlist = preg_replace( '!([\\\\\\-\\]\\[/^])!', '\\\${1}', $charlist );

	return preg_replace( '/^[' . $charlist . ']+/u', '', $str );
}

/**
 * nv_rtrim()
 *
 * @param mixed $str
 * @param bool $charlist
 * @return
 */
function nv_rtrim( $str, $charlist = false )
{
	if( $charlist === false ) return rtrim( $str );

	$charlist = preg_replace( '!([\\\\\\-\\]\\[/^])!', '\\\${1}', $charlist );

	return preg_replace( '/[' . $charlist . ']+$/u', '', $str );
}

/**
 * nv_trim()
 *
 * @param mixed $str
 * @param bool $charlist
 * @return
 */
function nv_trim( $str, $charlist = false )
{
	if( $charlist === false ) return trim( $str );

	return nv_ltrim( nv_rtrim( $str, $charlist ), $charlist );
}

/**
 * nv_EncString()
 *
 * @param mixed $str
 * @return
 */
function nv_EncString( $string )
{
	if( file_exists( NV_ROOTDIR . '/includes/utf8/lookup_' . NV_LANG_DATA . '.php' ) )
	{
		include NV_ROOTDIR . '/includes/utf8/lookup_' . NV_LANG_DATA . '.php' ;
		$string = strtr( $string, $utf8_lookup_lang );
	}

	include NV_ROOTDIR . '/includes/utf8/lookup.php' ;
	return strtr( $string, $utf8_lookup['romanize'] );
}

/**
 * change_alias()
 *
 * @return
 */
function change_alias( $alias )
{
	$alias = preg_replace('/[\x{0300}\x{0301}\x{0303}\x{0309}\x{0323}]/u', '', $alias); // fix unicode consortium for Vietnamese
	$search = array( '&amp;', '&#039;', '&quot;', '&lt;', '&gt;', '&#x005C;', '&#x002F;', '&#40;', '&#41;', '&#42;', '&#91;', '&#93;', '&#33;', '&#x3D;', '&#x23;', '&#x25;', '&#x5E;', '&#x3A;', '&#x7B;', '&#x7D;', '&#x60;', '&#x7E;' );
	$alias = preg_replace( array( '/[^a-zA-Z0-9]/', '/[ ]+/', '/^[\-]+/', '/[\-]+$/' ), array( ' ', '-', '', '' ), str_replace( $search, ' ', nv_EncString( $alias ) ) );
	return $alias;
}

/**
 * nv_clean60()
 *
 * @param mixed $string
 * @param integer $num
 * @return
 */
function nv_clean60( $string, $num = 60 )
{
	global $global_config;

	$string = nv_unhtmlspecialchars( $string );

	$len = nv_strlen( $string );

	if( $num and $num < $len )
	{
		if( ord( nv_substr( $string, $num, 1 ) ) == 32 )
		{
			$string = nv_substr( $string, 0, $num ) . '...';
		}
		elseif( strpos( $string, ' ' ) === false )
		{
			$string = nv_substr( $string, 0, $num );
		}
		else
		{
			$string = nv_clean60( $string, $num - 1 );
		}
	}
	return $string;
}