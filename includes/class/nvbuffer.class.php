<?php

/**
 * @Project NUKEVIET 4.x 
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2/3/2012, 19:53
 */

if( ! defined( 'NV_BUFFER_CLASS' ) ) define( 'NV_BUFFER_CLASS', true );

/**
 * NVbuffer
 * 
 * @package NukeViet
 * @author VINADES.,JSC
 * @copyright 2012
 * @version 3.3
 * @access public
 */
class NVbuffer
{
	var $position = 0;
	var $varname = null;

	/**
	 * NVbuffer::stream_open()
	 * 
	 * @param mixed $path
	 * @param mixed $mode
	 * @param mixed $options
	 * @param mixed $opened_path
	 * @return
	 */
	function stream_open( $path, $mode, $options, &$opened_path )
	{
		$url = parse_url( $path );
		$this->varname = $url["host"];
		$this->position = 0;

		return true;
	}

	/**
	 * NVbuffer::stream_read()
	 * 
	 * @param mixed $count
	 * @return
	 */
	function stream_read( $count )
	{
		$ret = substr( $GLOBALS[$this->varname], $this->position, $count );
		$this->position += strlen( $ret );

		return $ret;
	}

	/**
	 * NVbuffer::stream_write()
	 * 
	 * @param mixed $data
	 * @return
	 */
	function stream_write( $data )
	{
		if( ! isset( $GLOBALS[$this->varname] ) ) $GLOBALS[$this->varname] = '';

		$left = substr( $GLOBALS[$this->varname], 0, $this->position );
		$right = substr( $GLOBALS[$this->varname], $this->position + strlen( $data ) );
		$GLOBALS[$this->varname] = $left . $data . $right;
		$this->position += strlen( $data );

		return strlen( $data );
	}

	/**
	 * NVbuffer::stream_tell()
	 * 
	 * @return
	 */
	function stream_tell()
	{
		return $this->position;
	}

	/**
	 * NVbuffer::stream_eof()
	 * 
	 * @return
	 */
	function stream_eof()
	{
		return $this->position >= strlen( $GLOBALS[$this->varname] );
	}

	/**
	 * NVbuffer::stream_seek()
	 * 
	 * @param mixed $offset
	 * @param mixed $whence
	 * @return
	 */
	function stream_seek( $offset, $whence )
	{
		switch( $whence )
		{
			case SEEK_SET:
				if( $offset < strlen( $GLOBALS[$this->varname] ) && $offset >= 0 )
				{
					$this->position = $offset;
					return true;
				}
				else
				{
					return false;
				}

				break;

			case SEEK_CUR:
				if( $offset >= 0 )
				{
					$this->position += $offset;
					return true;
				}
				else
				{
					return false;
				}

				break;

			case SEEK_END:
				if( strlen( $GLOBALS[$this->varname] ) + $offset >= 0 )
				{
					$this->position = strlen( $GLOBALS[$this->varname] ) + $offset;
					return true;
				}
				else
				{
					return false;
				}

				break;

			default:
				return false;
		}
	}

	/**
	 * NVbuffer::stream_metadata()
	 * 
	 * @param mixed $path
	 * @param mixed $option
	 * @param mixed $var
	 * @return
	 */
	function stream_metadata( $path, $option, $var )
	{
		if( $option == STREAM_META_TOUCH )
		{
			$url = parse_url( $path );
			$varname = $url["host"];

			if( ! isset( $GLOBALS[$varname] ) )
			{
				$GLOBALS[$varname] = '';
			}

			return true;
		}
		return false;
	}
}